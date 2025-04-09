<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * @package     OneClick Chat to Order
 * @author      Walter Pinem
 * @link        https://walterpinem.me
 * @link        https://www.seniberpikir.com/oneclick-wa-order-woocommerce/
 * @copyright   Copyright (c) 2019, Walter Pinem, Seni Berpikir
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * @category    Admin Page
 */

// Include important files
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/buttons/wa-order-cart-page.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/buttons/wa-order-display-options.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/buttons/wa-order-floating-button.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/buttons/wa-order-shop-archive.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/buttons/wa-order-single-product.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/buttons/wa-order-thank-you.php';

// Display admin notice message if confirmation check is unchecked
function wa_order_confirm_if_number_added()
{
    // Get the dismiss option
    $dismiss_notice = sanitize_text_field(get_option('wa_order_option_dismiss_notice_confirmation'));
    // Define the error message with translations and escaping
    $error_message = sprintf(
        /* translators: %1$s and %2$s are the placeholders for the links */
        __('%1$s Important! %2$s With %3$sOneClick Chat to Order%4$s, you can now set multiple WhatsApp numbers. Please %5$sset it here%6$s to get started. %7$sLearn more%8$s. %9$sDismiss%10$s.', 'oneclick-wa-order'),
        '<strong style="color:red;">',
        '</strong>',
        '<strong>',
        '</strong>',
        '<a href="' . esc_url(admin_url('edit.php?post_type=wa-order-numbers')) . '">',
        '</a>',
        '<a href="' . esc_url('https://walterpinem.me/projects/oneclick-chat-to-order-mutiple-numbers-feature/?utm_source=admin-notice&utm_medium=admin-dashboard&utm_campaign=OneClick-Chat-to-Order') . '" target="_blank">',
        '</a>',
        '<a href="' . esc_url(admin_url('admin.php?page=wa-order&tab=button_config')) . '">',
        '</a>'
    );

    // If the dismiss notice is not set, display the message
    if ($dismiss_notice !== 'yes') {
        printf(
            '<div class="notice notice-warning is-dismissible wa-order-notice-dismissible">%s</div>',
            wp_kses_post($error_message)
        );
    }
}
add_action('admin_notices', 'wa_order_confirm_if_number_added');

// Global Shortcode Function
function wa_order_shortcode_button($atts, $content = null)
{
    global $post;

    // Consolidate get_option() calls
    $options = array(
        'wanumberpage'   => get_option('wa_order_selected_wa_number_shortcode', ''),
        'target_blank'   => get_option('wa_order_shortcode_target', ''),
        'custom_message' => get_option('wa_order_shortcode_message', ''),
        'button_text'    => get_option('wa_order_shortcode_text_button', ''),
    );

    // Get the phone number from the custom post type
    $postid = get_page_by_path($options['wanumberpage'], OBJECT, 'wa-order-numbers');
    $phonenumb = $postid ? get_post_meta($postid->ID, 'wa_order_phone_number_input', true) : '';

    // Define the button's target attribute
    $blank = ($options['target_blank'] === '_blank') ? 'target="_blank"' : '';

    // Use the dynamic WhatsApp URL function
    $button_url = wa_order_the_url($phonenumb, $options['custom_message']);

    // Build the button HTML
    if (!empty($options['button_text'])) {
        $output = "<a id=\"sendbtn\" class=\"shortcode_wa_button\" href=\"" . esc_url($button_url) . "\" " . $blank . "><span>" . do_shortcode($content) . esc_html($options['button_text']) . "</span></a>";
    } else {
        $output = "<a id=\"sendbtn\" class=\"shortcode_wa_button_nt\" href=\"" . esc_url($button_url) . "\" " . $blank . "><span>" . do_shortcode($content) . "</span></a>";
    }

    return $output;
}
add_shortcode('wa-order', 'wa_order_shortcode_button');

// Dynamic Shortcode Generator
// Shortcode: [waorder phone="" button="" message="" target=""]
function wa_order_shortcode_generator_button($atts, $content = null)
{
    // Extract shortcode attributes with default values
    $atts = shortcode_atts(
        array(
            'phone'   => '',
            'button'  => '',
            'message' => '',
            'target'  => '',
        ),
        $atts,
        'waorder'
    );

    // Sanitize attributes to prevent XSS
    $phone   = sanitize_text_field($atts['phone']);
    $button  = sanitize_text_field($atts['button']);
    $message = sanitize_textarea_field($atts['message']);
    $target  = sanitize_text_field($atts['target']);

    // Define the button click target (open in new tab or not)
    $blank = ($target === 'yes') ? 'target="_blank"' : '';

    // Use the dynamic WhatsApp URL function to build the correct URL
    $button_url = wa_order_the_url($phone, $message);

    // Build the output for the button
    $output = "<a id=\"sendbtn\" class=\"shortcode_wa_button\" href=\"" . esc_url($button_url) . "\" " . esc_attr($blank) . "><span>" . do_shortcode($content) . esc_html($button) . "</span></a>";

    return $output;
}

add_shortcode('waorder', 'wa_order_shortcode_generator_button');

// Convert phone number link into WhatsApp chat link in Order Details page
function wa_order_convert_phone_link()
{
    global $pagenow;
    // Check if current page is WooCommerce Orders edit page
    if (
        $pagenow == 'admin.php' &&
        isset($_GET['page']) && $_GET['page'] == 'wc-orders' &&
        isset($_GET['action']) && $_GET['action'] == 'edit'
    ) {
        $convert_phone_no   = get_option('wa_order_option_convert_phone_order_details');
        $custom_message     = get_option('wa_order_option_custom_message_backend_order_details');
        if (empty($custom_message)) {
            $custom_message = 'Hello, I would like to follow up on your order.';
        }
        if ($convert_phone_no === 'yes') { ?>
            <script type="text/javascript">
                function wa_order_chage_href() {
                    var numberElement = document.querySelector(".address p:nth-of-type(3) a");
                    if (numberElement) {
                        var number = numberElement.textContent;
                        var message = encodeURIComponent("<?php echo esc_html($custom_message); ?>");
                        var changephonelinktowhatsapp = "https://wa.me/" + number.replace(/[^0-9]/g, '') + "?text=" + message;
                        numberElement.setAttribute("href", changephonelinktowhatsapp);
                    }
                }
                window.onload = wa_order_chage_href;
            </script>
<?php
        }
    }
}
$convert_phone_no   = get_option('wa_order_option_convert_phone_order_details');
if ($convert_phone_no === 'yes') {
    if (is_admin()) {
        add_action('admin_footer', 'wa_order_convert_phone_link');
    }
}

// Single Product Shortcode Generator
// Shortcode Full: [oneclick single="true" phone="1234567890" product="current" text="Order via WhatsApp" message="Hello, I need to know more about"]
// Shortcode without message and text: [oneclick single="true" phone="1234567890" product="current"]
function wa_order_shortcode($atts)
{
    global $product;
    // Shortcode attributes
    $atts = shortcode_atts(array(
        'single' => 'true', // Always 'true' for single product page
        'product' => 'current', // Can be 'current' or a specific product ID
        'phone' => '', // WhatsApp number
        'text' => '', // Button text from shortcode
        'message' => '', // Custom message from shortcode
        'fullwidth' => '',
    ), $atts);
    $atts['phone']      = sanitize_text_field($atts['phone']);
    $atts['text']       = sanitize_text_field($atts['text']);
    $atts['message']    = sanitize_textarea_field($atts['message']);
    $atts['fullwidth']  = sanitize_text_field($atts['fullwidth']);

    // Ensure if the shortcode is on a single product page or dealing with a valid product ID
    $product_id = isset($atts['product']) && $atts['product'] !== 'current' ? intval($atts['product']) : $product->get_id();

    if (!$product_id) {
        return ''; // Exit if no product ID is set or invalid
    }

    $product = wc_get_product($product_id);

    if (!$product) {
        return ''; // Exit if the product is invalid
    }

    // Fetch phone number from the shortcode generator
    $phone = apply_filters('wa_order_filter_oneclick_shortcode_phone', sanitize_text_field($atts['phone']), $atts);
    if (!$phone) {
        return ''; // Exit if no phone number is set
    }

    // Get product details
    $product_url = apply_filters('wa_order_filter_oneclick_shortcode_product_url', get_permalink($product_id), $product_id);
    $product_url = esc_url($product_url);
    $title = apply_filters('wa_order_filter_oneclick_shortcode_product_title', $product->get_name(), $product_id);
    $title = esc_html($title);
    $price = wc_price($product->get_price());
    $price = esc_html($price);
    $format_price = apply_filters('wa_order_filter_oneclick_shortcode_product_price', wp_strip_all_tags($price), $price, $product_id);
    // Regular Price
    $regular_price = wc_price($product->get_regular_price());
    $format_regular_price = wp_strip_all_tags($regular_price);
    $decoded_regular_price = html_entity_decode($format_regular_price, ENT_QUOTES, 'UTF-8');
    // Sale Price
    $sale_price = wc_price($product->get_sale_price());
    $format_sale_price = wp_strip_all_tags($sale_price);
    $decoded_sale_price = html_entity_decode($format_sale_price, ENT_QUOTES, 'UTF-8');

    // Decode HTML entities in the price
    $decoded_price = html_entity_decode($format_price, ENT_QUOTES, 'UTF-8');

    // Button text logic
    $button_text = apply_filters(
        'wa_order_filter_oneclick_shortcode_button_text',
        !empty($atts['text']) ? $atts['text'] : (
            get_post_meta($product_id, '_wa_order_button_text', true) ?:
            get_option('wa_order_option_text_button', esc_html__('Buy via WhatsApp', 'oneclick-wa-order'))
        ),
        $atts
    );
    $button_text = esc_html($button_text);
    // Message logic
    $custom_message = apply_filters('wa_order_filter_oneclick_shortcode_message', !empty($atts['message']) ? $atts['message'] : (
        get_post_meta($product_id, '_wa_order_custom_message', true) ?: get_option('wa_order_option_message', 'Hello, I want to buy:')
    ), $atts);
    $custom_message = esc_html($custom_message);
    // URL Encoding
    $encoded_title = urlencode($title);
    $encoded_product_url = urlencode($product_url);
    $encoded_price_label = urlencode(get_option('wa_order_option_price_label', 'Price'));
    $encoded_url_label = urlencode(get_option('wa_order_option_url_label', 'URL'));
    $encoded_thanks = urlencode(get_option('wa_order_option_thank_you_label', 'Thank you!'));

    if (get_option('wa_order_option_single_show_regular_sale_prices', 'no') === 'yes') {
        $encoded_price = "~" . urlencode($decoded_regular_price) . "~ " . urlencode($decoded_sale_price);
    } else {
        $encoded_price = urlencode($decoded_price);
    }

    // Exclude price if the option is checked
    $exclude_price = apply_filters('wa_order_filter_oneclick_shortcode_exclude_price', get_option('wa_order_exclude_price', 'no'), $atts);
    $message_content = apply_filters('wa_order_filter_oneclick_shortcode_message_content', urlencode($custom_message) . "%0D%0A%0D%0A*$encoded_title*", $product_id);
    if ($exclude_price !== 'yes') {
        $message_content .= "%0D%0A*$encoded_price_label:* $encoded_price";
    }
    if (get_option('wa_order_exclude_product_url') !== 'yes') {
        $message_content .= "%0D%0A*$encoded_url_label:* $encoded_product_url";
    }
    $message_content .= "%0D%0A%0D%0A$encoded_thanks";

    // WhatsApp URL
    $button_url = apply_filters('wa_order_filter_oneclick_shortcode_button_url', wa_order_the_url($phone, urldecode($message_content)), $phone, $message_content);

    // Full-Width button
    $force_fullwidth = apply_filters('wa_order_filter_oneclick_shortcode_fullwidth', $atts['fullwidth'], $atts);

    // Button classes and styles based on full-width option
    $button_fullwidth_class = ($force_fullwidth === 'true') ? 'single_product_shortcode_fullwidth' : '';
    $button_fullwidth_style = ($force_fullwidth === 'true') ? 'style="width:100%;display:block;"' : '';

    // Button HTML
    $button_html = apply_filters('wa_order_filter_oneclick_shortcode_button_html', "<a href=\"$button_url\" id=\"sendbtn\" class=\"shortcode_wa_button single_product_shortcode $button_fullwidth_class\" role=\"button\" target=\"_blank\" rel=\"nofollow noopener\" $button_fullwidth_style>$button_text</a>", $button_url, $button_text, $atts);

    // GDPR compliance logic
    $gdpr_status = get_option('wa_order_gdpr_status_enable', 'no');
    if ($gdpr_status === 'yes') {
        $gdpr_message = wp_kses_post(do_shortcode(stripslashes(get_option('wa_order_gdpr_message'))));

        $gdpr_script = "
        <script>
            function WAOrder() {
                var phone = '" . esc_js($phone) . "',
                    wa_message = '" . esc_js($custom_message) . "',
                    button_url = '" . esc_url($button_url) . "',
                    target = '_blank';
            }
        </script>
        <style>
            .wa-order-button,
            .wa-order-button .wa-order-class {
                display: none !important;
            }
        </style>";

        $button_html = "
        $gdpr_script
        <label class=\"wa-button-gdpr\">
            <a href=\"$button_url\" class=\"gdpr_wa_button $button_fullwidth_class\" role=\"button\" target=\"_blank\">
                <button type=\"button\" class=\"gdpr_wa_button_input $button_fullwidth_class button alt\" disabled=\"disabled\" onclick=\"WAOrder();\">$button_text</button>
            </a>
        </label>
        <div class=\"wa-order-gdprchk\">
            <input type=\"checkbox\" name=\"wa_order_gdpr_status_enable\" class=\"css-checkbox wa_order_input_check\" id=\"gdprChkbx\" />
            <label for=\"gdprChkbx\" class=\"label-gdpr\">$gdpr_message</label>
        </div>
        <script>
            document.getElementById('gdprChkbx').addEventListener('click', function(e) {
                var buttons = document.querySelectorAll('.gdpr_wa_button_input');
                buttons.forEach(function(button) {
                    button.disabled = !e.target.checked;
                });
            });
        </script>";
    }

    return apply_filters('wa_order_filter_oneclick_shortcode_output', $button_html, $atts);
}
add_shortcode('oneclick', 'wa_order_shortcode');
