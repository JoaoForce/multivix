<?php
// Prevent direct access
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/**
 * OneClick Chat to Order Floating Button
 *
 * @package     OneClick Chat to Order
 * @author      Walter Pinem <hello@walterpinem.me>
 * @link        https://walterpinem.me/
 * @link        https://onlinestorekit.com/oneclick-chat-to-order/
 * @copyright   Copyright (c) 2019 - 2024, Walter Pinem | Online Store Kit
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * @category    Public Page
 */

// Helper function
function wa_order_is_floating_tooltip_enabled()
{
	return get_option('wa_order_floating_tooltip_enable', 'no') === 'yes';
}

// Display Floating Button
function wa_order_display_floating_button()
{

	if (wa_order_is_floating_tooltip_enabled()) {
		// If tooltip is enabled, don't display the standard floating button
		return;
	}
	$floating = get_option('wa_order_floating_button');
	if ($floating !== 'yes') {
		return;
	}
	$floating_position = get_option('wa_order_floating_button_position', 'left');
	$custom_message = urlencode(get_option('wa_order_floating_message', ''));
	$floating_target = get_option('wa_order_floating_target', '_blank');
	$wanumberpage = get_option('wa_order_selected_wa_number_floating', '');
	$postid = get_page_by_path($wanumberpage, OBJECT, 'wa-order-numbers');
	if (!$postid) {
		return;
	}
	$phonenumb = get_post_meta($postid->ID, 'wa_order_phone_number_input', true);
	if (!$phonenumb) {
		return;
	}
	$include_source = get_option('wa_order_floating_source_url', 'no');
	$src_label = get_option('wa_order_floating_source_url_label', 'From URL:');
	$source_url = $include_source === 'yes' ? urlencode(home_url(add_query_arg(null, null))) : '';
	$floating_message = $custom_message . ($include_source === 'yes' ? "\n\n*" . $src_label . "* " . $source_url : '');
	$floating_link = wa_order_the_url($phonenumb, urldecode($floating_message)); // phpcs:ignore WordPress.Security.EscapeOutput.
?>
	<a id="sendbtn" class="floating_button" href="<?php echo $floating_link; // phpcs:ignore WordPress.Security.EscapeOutput.
													?>" role="button" target="<?php echo esc_attr($floating_target); ?>"></a>
<?php
}
add_action('wp_footer', 'wa_order_display_floating_button');

// Floating Button CSS
function wa_order_display_floating_button_css()
{
	$floating = get_option('wa_order_floating_button');
	if ($floating !== 'yes') {
		return;
	}

	// Sanitize and get the margin and padding values for the button
	$margin_top = esc_attr(get_option('wa_order_floating_button_margin_top', '20')); // Default margin 20px
	$margin_right = esc_attr(get_option('wa_order_floating_button_margin_right', '20'));
	$margin_bottom = esc_attr(get_option('wa_order_floating_button_margin_bottom', '20'));
	$margin_left = esc_attr(get_option('wa_order_floating_button_margin_left', '20'));

	$padding_top = esc_attr(get_option('wa_order_floating_button_padding_top', '10')); // Default padding 10px
	$padding_right = esc_attr(get_option('wa_order_floating_button_padding_right', '10'));
	$padding_bottom = esc_attr(get_option('wa_order_floating_button_padding_bottom', '10'));
	$padding_left = esc_attr(get_option('wa_order_floating_button_padding_left', '10'));

	// Sanitize and get the margin and padding values for the button icon
	$icon_margin_top = esc_attr(get_option('wa_order_floating_button_icon_margin_top', '0'));
	$icon_margin_right = esc_attr(get_option('wa_order_floating_button_icon_margin_right', '0'));
	$icon_margin_bottom = esc_attr(get_option('wa_order_floating_button_icon_margin_bottom', '0'));
	$icon_margin_left = esc_attr(get_option('wa_order_floating_button_icon_margin_left', '0'));

	$icon_padding_top = esc_attr(get_option('wa_order_floating_button_icon_padding_top', '0'));
	$icon_padding_right = esc_attr(get_option('wa_order_floating_button_icon_padding_right', '0'));
	$icon_padding_bottom = esc_attr(get_option('wa_order_floating_button_icon_padding_bottom', '0'));
	$icon_padding_left = esc_attr(get_option('wa_order_floating_button_icon_padding_left', '0'));

	$floating_position = esc_attr(get_option('wa_order_floating_button_position', 'left'));

?>
	<style>
		.floating_button {
			margin-top: <?php echo $margin_top; ?>px !important;
			margin-right: <?php echo $margin_right; ?>px !important;
			margin-bottom: <?php echo $margin_bottom; ?>px !important;
			margin-left: <?php echo $margin_left; ?>px !important;

			padding-top: <?php echo $padding_top; ?>px !important;
			padding-right: <?php echo $padding_right; ?>px !important;
			padding-bottom: <?php echo $padding_bottom; ?>px !important;
			padding-left: <?php echo $padding_left; ?>px !important;

			position: fixed !important;
			width: 60px !important;
			height: 60px !important;
			bottom: 20px !important;
			background-color: #25D366 !important;
			color: #ffffff !important;
			border-radius: 50% !important;
			text-align: center !important;
			box-shadow: 0 8px 25px -5px rgba(45, 62, 79, .3) !important;
			z-index: 9999999 !important;
			text-decoration: none !important;
			<?php echo esc_attr($floating_position); ?>: 20px !important;
		}

		.floating_button:before {
			content: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="30px" height="30px"><path fill="%23fff" d="M3.516 3.516c4.686-4.686 12.284-4.686 16.97 0 4.686 4.686 4.686 12.283 0 16.97a12.004 12.004 0 01-13.754 2.299l-5.814.735a.392.392 0 01-.438-.44l.748-5.788A12.002 12.002 0 013.517 3.517zm3.61 17.043l.3.158a9.846 9.846 0 0011.534-1.758c3.843-3.843 3.843-10.074 0-13.918-3.843-3.843-10.075-3.843-13.918 0a9.846 9.846 0 00-1.747 11.554l.16.303-.51 3.942a.196.196 0 00.219.22l3.961-.501zm6.534-7.003l-.933 1.164a9.843 9.843 0 01-3.497-3.495l1.166-.933a.792.792 0 00.23-.94L9.561 6.96a.793.793 0 00-.924-.445 1291.6 1291.6 0 00-2.023.524.797.797 0 00-.588.88 11.754 11.754 0 0010.005 10.005.797.797 0 00.88-.587l.525-2.023a.793.793 0 00-.445-.923L14.6 13.327a.792.792 0 00-.94.23z"/></svg>') !important;
			display: block !important;
			width: 30px !important;
			height: 30px !important;
			margin-top: <?php echo $icon_margin_top; ?>px !important;
			margin-right: <?php echo $icon_margin_right; ?>px !important;
			margin-bottom: <?php echo $icon_margin_bottom; ?>px !important;
			margin-left: <?php echo $icon_margin_left; ?>px !important;

			padding-top: <?php echo $icon_padding_top; ?>px !important;
			padding-right: <?php echo $icon_padding_right; ?>px !important;
			padding-bottom: <?php echo $icon_padding_bottom; ?>px !important;
			padding-left: <?php echo $icon_padding_left; ?>px !important;

			top: 50% !important;
			transform: translateY(-50%) !important;
		}

		.label-container {
			position: fixed !important;
			bottom: 33px !important;
			display: table !important;
			visibility: hidden !important;
			z-index: 9999999 !important;
		}

		.label-text {
			color: #43474e !important;
			background: #f5f7f9 !important;
			display: inline-block !important;
			padding: 7px !important;
			border-radius: 3px !important;
			font-size: 14px !important;
			bottom: 15px !important;
		}

		a.floating_button:hover div.label-container,
		a.floating_button:hover div.label-text {
			visibility: visible !important;
			opacity: 1 !important;
		}

		@media only screen and (max-width: 480px) {
			.floating_button {
				bottom: 10px !important;
				<?php echo esc_attr($floating_position); ?>: 10px !important;
			}
		}
	</style>
	<?php
}
add_action('wp_head', 'wa_order_display_floating_button_css');

// Display Floating Button with Tooltip
function wa_order_display_floating_tooltip()
{
	if (!wa_order_is_floating_tooltip_enabled()) {
		// If tooltip is not enabled, don't display the tooltip floating button
		return;
	}
	$floating = get_option('wa_order_floating_button', 'no');
	$floating_position = get_option('wa_order_floating_button_position', 'left');
	$custom_message = urlencode(get_option('wa_order_floating_message', ''));
	$floating_target = get_option('wa_order_floating_target', '_blank');
	$wanumberpage = get_option('wa_order_selected_wa_number_floating', '');
	$postid = get_page_by_path($wanumberpage, OBJECT, 'wa-order-numbers');
	if (!$postid) {
		return;
	}
	$phonenumb = get_post_meta($postid->ID, 'wa_order_phone_number_input', true);
	if (!$phonenumb) {
		return;
	}
	$tooltip_enable = get_option('wa_order_floating_tooltip_enable', 'no');
	$tool_tip = get_option('wa_order_floating_tooltip', "Let's Chat");
	$include_source = get_option('wa_order_floating_source_url', 'no');
	$src_label = get_option('wa_order_floating_source_url_label', 'From URL:');
	$source_url = $include_source === 'yes' ? urlencode(home_url(add_query_arg(null, null))) : '';
	$floating_message = $custom_message . ($include_source === 'yes' ? "\n\n*" . $src_label . "* " . $source_url : '');
	$floating_link = wa_order_the_url($phonenumb, urldecode($floating_message)); // phpcs:ignore WordPress.Security.EscapeOutput.
	if ($floating === 'yes' && $tooltip_enable === 'yes') {
	?>
		<a id="sendbtn" href="<?php echo $floating_link; ?>" role="button" target="<?php echo esc_attr($floating_target); ?>" class="floating_button">
			<div class="label-container">
				<div class="label-text"><?php echo esc_html($tool_tip); ?></div>
			</div>
		</a>
		<style>
			.floating_button {
				<?php echo esc_attr($floating_position); ?>: 20px;
			}

			.label-container {
				<?php echo esc_attr($floating_position); ?>: 85px;
			}
		</style>
<?php
	}
}
add_action('wp_footer', 'wa_order_display_floating_tooltip');

// Desktop & Mobile Visibities
function wa_order_adjust_floating_button_visibility()
{
	$floating_mobile = get_option('wa_order_floating_hide_mobile', 'no');
	$floating_desktop = get_option('wa_order_floating_hide_desktop', 'no');

	if ($floating_mobile === 'yes' || $floating_desktop === 'yes') {
		echo '<style>';
		if ($floating_mobile === 'yes') {
			// Hides on mobile devices
			echo '@media only screen and (max-width: 480px) { .floating_button { display: none !important; } }';
		}
		if ($floating_desktop === 'yes') {
			// Hides on desktop
			echo '@media (min-width: 481px) { .floating_button { display: none !important; } }';
		}
		echo '</style>';
	}
}
add_action('wp_footer', 'wa_order_adjust_floating_button_visibility');

// Conditionally Hide Floating Button on selected queries
function wa_order_hide_floating_button_conditionally()
{
	global $post;

	// Get the settings as arrays
	$posts_array = (array) get_option('wa_order_floating_hide_specific_posts');
	$pages_array = (array) get_option('wa_order_floating_hide_specific_pages');
	$cats_array  = (array) get_option('wa_order_floating_hide_product_cats');
	$tags_array  = (array) get_option('wa_order_floating_hide_product_tags');

	// Early exit if no conditions are set
	if (empty($posts_array) && empty($pages_array) && empty($cats_array) && empty($tags_array)) {
		return;
	}

	$should_hide = false;

	// Check conditions to hide the floating button
	if (is_product()) {
		$product = wc_get_product($post->ID);
		if (!is_null($product)) {
			if (!empty($cats_array) && has_term($cats_array, 'product_cat', $post->ID)) {
				$should_hide = true;
			}
			if (!empty($tags_array) && has_term($tags_array, 'product_tag', $post->ID)) {
				$should_hide = true;
			}
		}
	} elseif (is_page() && !empty($pages_array) && in_array($post->ID, $pages_array)) {
		$should_hide = true;
	} elseif (is_single() && !empty($posts_array) && in_array($post->ID, $posts_array)) {
		$should_hide = true;
	}

	// Apply the style if needed
	if ($should_hide) {
		echo '<style>.floating_button { display: none !important; }</style>';
	}
}
add_action('wp_head', 'wa_order_hide_floating_button_conditionally');

// Hide floating button on all posts & pages
function wa_order_hide_floating_button_posts_pages()
{
	$hide_on_posts = get_option('wa_order_floating_hide_all_single_posts') === 'yes';
	$hide_on_pages = get_option('wa_order_floating_hide_all_single_pages') === 'yes';

	if (($hide_on_posts && is_single()) || ($hide_on_pages && is_page())) {
		echo '<style>.floating_button { display: none !important; }</style>';
	}
}
add_action('wp_head', 'wa_order_hide_floating_button_posts_pages');
