<?php
// Prevent direct access
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/**
 * OneClick Chat to Order Shop Loop Page
 *
 * @package     OneClick Chat to Order
 * @author      Walter Pinem <hello@walterpinem.me>
 * @link        https://walterpinem.me/
 * @link        https://onlinestorekit.com/oneclick-chat-to-order/
 * @copyright   Copyright (c) 2019 - 2024, Walter Pinem | Online Store Kit
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * @category    Shop Archive Page
 */

// Add WhatsApp button under each product on Shop page
function wa_order_display_button_shop_page()
{
	$enable_button = apply_filters('wa_order_filter_enable_shop_button', get_option(sanitize_text_field('wa_order_option_enable_button_shop_loop')));
	if ($enable_button === 'yes') {
		global $product;
		// WA Number from Setting: Check if number is set
		$wanumberpage = apply_filters('wa_order_filter_shop_wa_number_page', get_option('wa_order_selected_wa_number_shop'));
		$postid = get_page_by_path($wanumberpage, '', 'wa-order-numbers');
		if (empty($postid)) {
			$pid = 0;
		} else {
			$pid = $postid->ID;
		}
		$phonenumb = apply_filters('wa_order_filter_shop_phone_number', get_post_meta($pid, 'wa_order_phone_number_input', true));

		// Set Default Button Text
		$button_text = apply_filters('wa_order_filter_shop_button_text', get_option(sanitize_text_field('wa_order_option_button_text_shop_loop'), 'Buy via WhatsApp'));
		if ($button_text == '') {
			$button_txt = "Buy via WhatsApp";
			$button_text = $button_txt;
		} else {
			$button_text = $button_text;
		}
		// Set Default Custom Message
		$custom_message = apply_filters('wa_order_filter_shop_custom_message', get_option(sanitize_text_field('wa_order_option_custom_message_shop_loop'), 'Hello, I want to purchase:'));
		if ($custom_message == '') $custom_msg = "Hello, I want to purchase:";
		else $custom_msg = "$custom_message";

		$product_url	= apply_filters('wa_order_filter_shop_product_url', $product->get_permalink(), $product);
		$product_title	= apply_filters('wa_order_filter_shop_product_title', $product->get_name(), $product);
		// Translators: %s is the product title
		$link_title		= sprintf(__('Complete order on WhatsApp to buy %s', 'oneclick-wa-order'), $product_title);

		$class			= apply_filters('wa_order_filter_shop_button_class', sprintf('button add_to_cart_button wa-shop-button product_type_%s', $product->get_type()), $product);

		$price			= wc_get_price_including_tax($product);
		$format_price	= apply_filters('wa_order_filter_shop_price', html_entity_decode(wp_strip_all_tags(wc_price($price))), $price, $product);

		// Labels
		$price_label	= apply_filters('wa_order_filter_shop_price_label', get_option('wa_order_option_price_label', 'Price'));
		$url_label		= apply_filters('wa_order_filter_shop_url_label', get_option('wa_order_option_url_label', 'Product URL'));
		$thanks_label	= apply_filters('wa_order_filter_shop_thanks_label', get_option('wa_order_option_thank_you_label', 'Thank You!'));

		// URL Encoding
		$encode_custom_message = urlencode($custom_msg);
		$encode_title	= urlencode($product_title);
		$encode_product_url = urlencode($product_url);
		$encode_thanks	= urlencode($thanks_label);
		$encode_url_label = urlencode($url_label);
		$encode_price_label = urlencode($price_label);

		$final_message	= apply_filters('wa_order_filter_shop_final_message', "$encode_custom_message%0D%0A%0D%0A*$encode_title*", $product);

		// Exclude Price
		$excludeprice = apply_filters('wa_order_filter_shop_exclude_price', get_option(sanitize_text_field('wa_order_option_shop_loop_exclude_price'), 'no'));
		if ($excludeprice === 'yes') {
			$final_message .= "";
		} else {
			// $final_message .= "%0A*$encode_price_label:*%20$currency$encode_price";
			$final_message .= "%0A*$encode_price_label:*%20$format_price"; // new price format
		}

		// Remove product URL
		$removeproductURL	= apply_filters('wa_order_filter_shop_remove_product_url', get_option(sanitize_text_field('wa_order_option_shop_loop_hide_product_url'), 'no'));
		if ($removeproductURL === 'yes') {
			$final_message .= "";
		} else {
			$final_message .= "%0A*$encode_url_label:*%20$encode_product_url";
		}

		$final_message .= "%0D%0A%0D%0A$encode_thanks";

		$button_url = apply_filters('wa_order_filter_shop_button_url', wa_order_the_url($phonenumb, urldecode($final_message)), $phonenumb, $final_message); // phpcs:ignore WordPress.Security.EscapeOutput.
		$target = apply_filters('wa_order_filter_shop_button_target', get_option(sanitize_text_field('wa_order_option_shop_loop_open_new_tab'), '_blank'));
?>
		<a id="sendbtn" href="<?php echo $button_url; ?>" title="<?php echo esc_attr($link_title); ?>" target="<?php echo esc_attr($target); ?>" class="<?php echo esc_attr($class); ?>">
			<?php echo esc_html($button_text); ?>
		</a>
	<?php
	}
}
add_action('woocommerce_after_shop_loop_item', 'wa_order_display_button_shop_page', 20);

// Option to remove Add to Cart on Shop page product loop
if (get_option(sanitize_text_field('wa_order_option_hide_atc_shop_loop')) === 'yes') {
	remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
}
// Alternative way to force hide the Add to Cart button on shop loop page
add_action('wp_head', 'wa_order_alternative_way_hide_shop_loop');
function wa_order_alternative_way_hide_shop_loop()
{
	if (get_option(sanitize_text_field('wa_order_option_hide_atc_shop_loop')) === 'yes') {
	?>
		<style>
			.add_to_cart_button,
			.ajax_add_to_cart {
				display: none !important;
			}

			.wa-shop-button {
				display: inline-block !important;
			}
		</style>
<?php
	}
}

// Hide WhatsApp button under products based on taxonomies
function wa_order_hide_shop_taxonomies()
{
	global $product, $post;
	$option_shop_cats = get_option('wa_order_option_exlude_shop_product_cats');
	$option_shop_cats_array = (array) $option_shop_cats;
	$option_shop_tags = get_option('wa_order_option_exlude_shop_product_tags');
	$option_shop_tags_array = (array) $option_shop_tags;

	$cats_archive = get_option('wa_order_exlude_shop_product_cats_archive');
	$tags_archive = get_option('wa_order_exlude_shop_product_tags_archive');

	// Cache the product IDs to avoid repeated queries
	$cat_ids_cache_key = 'wa_order_cat_ids';
	$tag_ids_cache_key = 'wa_order_tag_ids';

	// Check if the cache exists
	$cat_ids = get_transient($cat_ids_cache_key);
	$tag_ids = get_transient($tag_ids_cache_key);

	// If cache is empty, perform the query and cache the results
	if (false === $cat_ids) {
		$cat_ids = get_posts(array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'fields' => 'ids',
			'tax_query' => array(
				array(
					'taxonomy' => 'product_cat',
					'field' => 'term_id',
					'terms' => $option_shop_cats_array,
					'operator' => 'IN',
				)
			),
		));
		set_transient($cat_ids_cache_key, $cat_ids, HOUR_IN_SECONDS); // Cache for 1 hour
	}

	if (false === $tag_ids) {
		$tag_ids = get_posts(array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'fields' => 'ids',
			'tax_query' => array(
				array(
					'taxonomy' => 'product_tag',
					'field' => 'term_id',
					'terms' => $option_shop_tags_array,
					'operator' => 'IN',
				)
			),
		));
		set_transient($tag_ids_cache_key, $tag_ids, HOUR_IN_SECONDS); // Cache for 1 hour
	}

	// Hide WA button conditionally on Shop page
	if (is_shop()) {
		foreach ($cat_ids as $cat_id) {
			echo '<style>
		     .products .post-' . esc_attr($cat_id) . ' #sendbtn {
		     	display: none!important;
		     }
		     </style>';
		}
		foreach ($tag_ids as $tag_id) {
			echo '<style>
		     .products .post-' . esc_attr($tag_id) . ' #sendbtn {
		     	display: none!important;
		     }
		     </style>';
		}
	}

	// Hide WA button conditionally on category archive page
	if ($cats_archive === 'yes' && is_product_category($option_shop_cats_array)) {
		foreach ($cat_ids as $cat_id) {
			echo '<style>
		     .products .post-' . esc_attr($cat_id) . ' #sendbtn {
		     	display: none!important;
		     }
		     </style>';
		}
	}

	// Hide WA button conditionally on tag archive page
	if ($tags_archive === 'yes' && is_product_tag($option_shop_tags_array)) {
		foreach ($tag_ids as $tag_id) {
			echo '<style>
		     .products .post-' . esc_attr($tag_id) . ' #sendbtn {
		     	display: none!important;
		     }
		     </style>';
		}
	}
}
add_action('wp_head', 'wa_order_hide_shop_taxonomies', 20);
