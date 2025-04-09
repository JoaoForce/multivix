<?php
// Prevent direct access
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/**
 * OneClick Chat to Order Single Product Page
 *
 * @package     OneClick Chat to Order
 * @author      Walter Pinem <hello@walterpinem.me>
 * @link        https://walterpinem.me/
 * @link        https://onlinestorekit.com/oneclick-chat-to-order/
 * @copyright   Copyright (c) 2019 - 2024, Walter Pinem | Online Store Kit
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * @category    Single Product Page
 */

// Default button position single product page
$wa_order_position = get_option('wa_order_single_product_button_position', 'after_atc');

// Start processing the WhatsApp button
// Revamped in version 1.0.5
function wa_order_add_button_plugin()
{
	$enable_wa_button = apply_filters('wa_order_filter_enable_single_product', get_option('wa_order_option_enable_single_product', 'yes'));
	// Check if the button should be displayed
	if ($enable_wa_button !== 'yes') {
		return;
	}

	global $product;

	// Fetch phone number
	$phone = apply_filters('wa_order_filter_phone_number', wa_order_get_phone_number($product->get_id()), $product->get_id());
	if (!$phone) {
		return; // Exit if no phone number is set
	}

	// Product details
	$product_url = apply_filters('wa_order_filter_product_url', get_permalink($product->get_id()), $product);
	$title = apply_filters('wa_order_filter_product_title', $product->get_name(), $product);
	$price = apply_filters('wa_order_filter_product_price', wc_price(wc_get_price_including_tax($product)), $product);
	$format_price = wp_strip_all_tags($price); // Strip HTML tags
	// Regular Price
	$regular_price = apply_filters('wa_order_filter_product_regular_price', wc_price($product->get_regular_price()), $product);
	$format_regular_price = wp_strip_all_tags($regular_price);
	$decoded_regular_price = html_entity_decode($format_regular_price, ENT_QUOTES, 'UTF-8');
	// Sale Price
	$sale_price = apply_filters('wa_order_filter_product_sale_price', wc_price($product->get_sale_price()), $product);
	$format_sale_price = wp_strip_all_tags($sale_price);
	$decoded_sale_price = html_entity_decode($format_sale_price, ENT_QUOTES, 'UTF-8');

	// Decode HTML entities in the price
	$decoded_price = html_entity_decode($format_price, ENT_QUOTES, 'UTF-8');

	// Settings
	$button_text = apply_filters('wa_order_filter_button_text', get_post_meta($product->get_id(), '_wa_order_button_text', true) ?: get_option('wa_order_option_text_button', 'Buy via WhatsApp'), $product);
	$target = apply_filters('wa_order_filter_button_target', get_option('wa_order_option_target', '_blank'));
	$gdpr_status = apply_filters('wa_order_filter_gdpr_status', get_option('wa_order_gdpr_status_enable', 'no'));
	$gdpr_message = apply_filters('wa_order_filter_gdpr_message', do_shortcode(stripslashes(get_option('wa_order_gdpr_message'))));

	// URL Encoding
	$custom_message = apply_filters('wa_order_filter_custom_message', urlencode(get_option('wa_order_option_message', 'Hello, I want to buy:')));
	$encoded_title = apply_filters('wa_order_filter_encoded_title', urlencode($title));
	$encoded_product_url = apply_filters('wa_order_filter_encoded_product_url', urlencode($product_url));
	$encoded_price_label = apply_filters('wa_order_filter_price_label', urlencode(get_option('wa_order_option_price_label', 'Price')));
	$encoded_url_label = apply_filters('wa_order_filter_url_label', urlencode(get_option('wa_order_option_url_label', 'URL')));
	$encoded_thanks = apply_filters('wa_order_filter_thank_you_label', urlencode(get_option('wa_order_option_thank_you_label', 'Thank you!')));

	if (get_option('wa_order_option_single_show_regular_sale_prices', 'no') === 'yes') {
		$encoded_price = "~" . urlencode($decoded_regular_price) . "~ " . urlencode($decoded_sale_price);
	} else {
		$encoded_price = apply_filters('wa_order_filter_encoded_price', urlencode($decoded_price));
	}

	// Exclude price from the message if the option is checked
	$exclude_price = apply_filters('wa_order_filter_exclude_price', get_option('wa_order_exclude_price', 'no'));
	$message_content = $custom_message . "%0D%0A%0D%0A*$encoded_title*";
	if ($exclude_price !== 'yes') {
		$message_content .= "%0D%0A*$encoded_price_label:* $encoded_price";
	}
	if (apply_filters('wa_order_filter_exclude_product_url', get_option('wa_order_exclude_product_url')) !== 'yes') {
		$message_content .= "%0D%0A*$encoded_url_label:* $encoded_product_url";
	}
	$message_content .= "%0D%0A%0D%0A$encoded_thanks";

	// WhatsApp URL
	$button_url = apply_filters('wa_order_filter_button_url', wa_order_the_url($phone, urldecode($message_content)), $phone, $message_content); // phpcs:ignore WordPress.Security.EscapeOutput.

	// Get the 'Force Full-Width' option value
	$force_fullwidth = apply_filters('wa_order_filter_force_fullwidth', get_option('wa_order_single_force_fullwidth', 'no'));

	// Button classes and styles based on full-width option
	$button_fullwidth_class = ($force_fullwidth === 'yes') ? 'wa-order-fullwidth' : '';
	$button_fullwidth_style = ($force_fullwidth === 'yes') ? 'style="width:100%;display:block;"' : '';

	// Button HTML
	$button_html = "<a href=\"$button_url\" class=\"wa-order-class\" role=\"button\" target=\"$target\"><button type=\"button\" class=\"wa-order-button single_add_to_cart_button button alt $button_fullwidth_class\" $button_fullwidth_style\">$button_text</button></a>";

	// GDPR compliance
	if ($gdpr_status === 'yes') {
		$gdpr_script = "
    <script>
        function WAOrder() {
            var phone = '" . esc_js($phone) . "',
                wa_message = '" . esc_js($custom_message) . "',
                button_url = '" . esc_url($button_url) . "',
                target = '" . esc_attr($target) . "';
        }
    </script>
    <style>
        .wa-order-button,
        .wa-order-button .wa-order-class {
            display: none !important;
        }
    </style>
    ";

		$button_position = apply_filters('wa_order_filter_button_position', get_option('wa_order_single_product_button_position', 'after_atc'));
		$button_id = "sendbtn" . ($button_position === "after_atc" ? "2" : "");
		$button_class = "single_add_to_cart_button wa-order-button-" . ($button_position === "after_atc" ? "after-atc" : ($button_position === "under_atc" ? "under-atc" : "shortdesc"));

		// Add full-width class and inline style for GDPR button if full-width is enabled
		$button_fullwidth_class_gdpr = ($force_fullwidth === 'yes') ? 'wa-order-fullwidth' : '';
		$button_fullwidth_style_gdpr = ($force_fullwidth === 'yes') ? 'style="width:100%!important;display:block!important;"' : '';

		$button_html = "
    $gdpr_script
    <label class=\"wa-button-gdpr2\" $button_fullwidth_style_gdpr>
        <a href=\"$button_url\" class=\"gdpr_wa_button\" role=\"button\" target=\"$target\">
            <button type=\"button\" class=\"gdpr_wa_button_input $button_class $button_fullwidth_class_gdpr button alt\" disabled=\"disabled\" onclick=\"WAOrder();\">
                $button_text
            </button>
        </a>
    </label>
    <div class=\"wa-order-gdprchk\">
        <input type=\"checkbox\" name=\"wa_order_gdpr_status_enable\" class=\"css-checkbox wa_order_input_check\" id=\"gdprChkbx\" />
        <label for=\"gdprChkbx\" class=\"label-gdpr\">$gdpr_message</label>
    </div>
    <script type=\"text/javascript\">
        document.getElementById('gdprChkbx').addEventListener('click', function (e) {
            var buttons = document.querySelectorAll('.gdpr_wa_button_input');
            buttons.forEach(function(button) {
                button.disabled = !e.target.checked;
            });
        });
    </script>
    ";
	}

	echo wp_kses_post(apply_filters('wa_order_filter_button_html', $button_html, $button_url, $button_text, $product));
}
// Determine the position of the button and add the action accordingly
$button_position = get_option('wa_order_single_product_button_position', 'after_atc');
$hook = 'woocommerce_after_add_to_cart_button'; // Default hook
switch ($button_position) {
	case 'under_atc':
		$hook = 'woocommerce_after_add_to_cart_form';
		break;
	case 'after_shortdesc':
		$hook = 'woocommerce_before_add_to_cart_form';
		break;
	case 'after_single_product_summary':
		$hook = 'woocommerce_after_single_product_summary';
		break;
	case 'around_share_area':
		$hook = 'woocommerce_share';
		break;
}
add_action($hook, 'wa_order_add_button_plugin', 5);

// Single product custom metabox
// Hide button checkbox
function wa_order_execute_metabox_value()
{
	// Check if WooCommerce is active
	if (!function_exists('is_product')) {
		return;
	}

	// Check if it's a product page
	if (!is_product()) {
		return;
	}

	// Get the current post object
	$post = get_post();

	// Check if the WhatsApp button should be hidden
	if (get_post_meta($post->ID, '_hide_wa_button', true) == 'yes') {
		// Ensure the action function exists
		if (function_exists('wa_order_add_button_plugin')) {
			remove_action('woocommerce_after_add_to_cart_button', 'wa_order_add_button_plugin', 5);
			remove_action('woocommerce_after_add_to_cart_form', 'wa_order_add_button_plugin', 5);
			remove_action('woocommerce_before_add_to_cart_form', 'wa_order_add_button_plugin', 5);
			remove_action('woocommerce_after_single_product_summary', 'wa_order_add_button_plugin', 5);
			remove_action('woocommerce_share', 'wa_order_add_button_plugin', 5);
		}
	}
}
add_action('wp_head', 'wa_order_execute_metabox_value');

// Hide WA button based on categories & tags
add_action('wp_head', 'wa_order_hide_single_taxonomies');
function wa_order_hide_single_taxonomies()
{
	// Check if WooCommerce is active
	if (!function_exists('is_product')) {
		return;
	}

	// Only proceed if on a product page
	if (!is_product()) {
		return;
	}

	// Retrieve the current product ID
	global $post;
	$product_id = $post->ID;

	// Get the category and tag options
	$option_cats = get_option('wa_order_option_exlude_single_product_cats', []);
	$option_tags = get_option('wa_order_option_exlude_single_product_tags', []);

	// Check if the product belongs to specified categories
	if (!empty($option_cats) && has_term($option_cats, 'product_cat', $product_id)) {
		wa_order_remove_button_actions();
		return;
	}

	// Check if the product has specified tags
	if (!empty($option_tags) && has_term($option_tags, 'product_tag', $product_id)) {
		wa_order_remove_button_actions();
		return;
	}
}

function wa_order_remove_button_actions()
{
	remove_action('woocommerce_after_add_to_cart_button', 'wa_order_add_button_plugin', 5);
	remove_action('woocommerce_after_add_to_cart_form', 'wa_order_add_button_plugin', 5);
	remove_action('woocommerce_before_add_to_cart_form', 'wa_order_add_button_plugin', 5);
	remove_action('woocommerce_after_single_product_summary', 'wa_order_add_button_plugin', 5);
	remove_action('woocommerce_share', 'wa_order_add_button_plugin', 5);
}

// Hide ATC button checkbox
add_action('woocommerce_before_single_product', 'wa_order_check_and_hide_atc_button');
function wa_order_check_and_hide_atc_button()
{
	global $product;

	if (is_a($product, 'WC_Product') && get_post_meta($product->get_id(), '_hide_atc_button', true) === 'yes') {
		// add_filter('woocommerce_is_purchasable', '__return_false');

		// Directly output CSS to hide ATC button
		add_action('wp_footer', function () {
			echo '<style>
                    .single-product button[name="add-to-cart"] {
                        display: none !important;
                    }
                  </style>';
		});
	}
}

function wa_order_remove_atc_button()
{
	// Ensure WooCommerce is active
	if (!class_exists('WooCommerce')) {
		return;
	}

	// Get options for removing ATC and enabling WhatsApp
	$enable_wa_button = get_option('wa_order_option_enable_single_product', 'no');
	$hide_atc_button = get_option('wa_order_option_remove_cart_btn', 'no');

	// Logic to remove or show buttons based on options
	if ($hide_atc_button === 'yes') {

		if ($enable_wa_button === 'yes') {
			add_action('wp_footer', 'wa_order_hide_atc_show_wa_button_css');
		} else {
			add_filter('woocommerce_is_purchasable', '__return_false');
			add_action('wp_footer', 'wa_order_remove_atc_button_css');
		}
	} elseif ($hide_atc_button === 'no' && $enable_wa_button === 'no') {
		// Case 3: Show both Add to Cart and WhatsApp buttons
		add_filter('woocommerce_is_purchasable', '__return_true');
	}
}
add_action('wp', 'wa_order_remove_atc_button');
// Completely hide Add to Cart and WhatsApp buttons
function wa_order_remove_atc_button_css()
{
?>
	<style>
		.single_add_to_cart_button,
		.woocommerce-variation-add-to-cart button[type="submit"],
		.single-product button[name="add-to-cart"],
		.wa-order-class,
		.wa-order-button {
			display: none !important;
		}
	</style>
<?php
}

// Hide Add to Cart button, but display WhatsApp button
function wa_order_hide_atc_show_wa_button_css()
{
?>
	<style>
		.single_add_to_cart_button,
		.woocommerce-variation-add-to-cart button[type="submit"] {
			display: none !important;
		}

		.wa-order-class,
		/* Assuming this is the class for the WhatsApp button */
		.wa-order-button {
			display: block !important;
		}
	</style>
<?php
}

// Force show Add to Cart button product metabox
function wa_order_force_show_atc_button()
{
	// Ensure WooCommerce is active
	if (!class_exists('WooCommerce')) {
		return;
	}

	global $post;
	if (is_product()) {
		$force_show_atc = get_post_meta($post->ID, '_force_show_atc_button', true);
		if ($force_show_atc === 'yes') {
			// Re-enable purchasability if it was disabled
			add_filter('woocommerce_is_purchasable', '__return_true');

			// Remove inline CSS that hides the ATC button
			remove_action('wp_footer', 'wa_order_remove_atc_button_css');
		}
	}
}
add_action('wp_head', 'wa_order_force_show_atc_button', 10);

// Remove price on single product page based on option
function wa_order_remove_single_product_price()
{
	$hide_price = get_option('wa_order_option_remove_price', 'no');
	if ($hide_price === 'yes') {
		remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
		add_filter('woocommerce_get_price_html', 'wa_order_hide_price_html', 10, 2);
		add_filter('woocommerce_variable_sale_price_html', 'wa_order_hide_price_html', 10, 2);
		add_filter('woocommerce_variable_price_html', 'wa_order_hide_price_html', 10, 2);
		add_filter('woocommerce_get_variation_price_html', 'wa_order_hide_price_html', 10, 2);
	}
}
add_action('woocommerce_before_single_product', 'wa_order_remove_single_product_price', 1);
function wa_order_hide_price_html($price, $product)
{
	if (is_single()) {
		return '';
	}
	return $price;
}

function wa_order_function_remove_elements_css()
{
	$hide_price            = get_option('wa_order_option_remove_price', 'no');
	$hide_button           = get_option('wa_order_option_remove_btn');
	$hide_button_mobile    = get_option('wa_order_option_remove_btn_mobile');

	// Collect CSS rules in a variable
	$custom_css = "";

	// Hide price
	if ($hide_price === 'yes') {
		$custom_css .= "
            .single-product .woocommerce-Price-amount,
            .single-product p.price {
                display: none !important;
            }
        ";
	}

	// Hide button for desktop
	if ($hide_button === 'yes') {
		$custom_css .= "
            @media screen and (min-width: 768px) {
                .wa-order-button,
                .gdpr_wa_button_input,
                .wa-order-gdprchk,
                button.gdpr_wa_button_input:disabled,
                button.gdpr_wa_button_input {
                    display: none !important;
                }
            }
        ";
	}

	// Hide button for mobile
	if ($hide_button_mobile === 'yes') {
		$custom_css .= "
            @media screen and (max-width: 768px) {
                .wa-order-button,
                .gdpr_wa_button_input,
                .wa-order-gdprchk,
                button.gdpr_wa_button_input:disabled,
                button.gdpr_wa_button_input {
                    display: none !important;
                }
            }
        ";
	}

	// Output the CSS if it's not empty
	if (!empty($custom_css)) {
		echo '<style>' . esc_html($custom_css) . '</style>';
	}
}
add_action('wp_head', 'wa_order_function_remove_elements_css');
