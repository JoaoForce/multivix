<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * OneClick Chat to Order Admin Settings Page
 *
 * @package     OneClick Chat to Order
 * @author      Walter Pinem <hello@walterpinem.me>
 * @link        https://walterpinem.me/
 * @link        https://onlinestorekit.com/oneclick-chat-to-order/
 * @copyright   Copyright (c) 2019 - 2024, Walter Pinem | Online Store Kit
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * @category    Admin Page
 */

// WA Number Post Type Submenu
function wa_order_add_number_submenu()
{
    add_submenu_page('wa-order', 'OneClick Chat to Order Options', 'Global Settings', 'manage_options', 'admin.php?page=wa-order&tab=welcome');
    add_submenu_page('wa-order', 'WhatsApp Numbers', 'WhatsApp Numbers', 'manage_options', 'edit.php?post_type=wa-order-numbers');
    add_submenu_page('wa-order', 'Add Number', 'Add New Number', 'manage_options', 'post-new.php?post_type=wa-order-numbers');
};
add_action('admin_menu', 'wa_order_add_number_submenu');
// Build plugin admin setting page
function wa_order_add_admin_page()
{
    // Generate Chat to Order Admin Page
    add_menu_page('OneClick Chat to Order Options', 'Chat to Order', 'manage_options', 'wa-order', 'wa_order_create_admin_page', plugin_dir_url(dirname(__FILE__)) . '/assets/images/wa-icon.svg', 98);
    // Begin building
    add_action('admin_init', 'wa_order_register_settings');
}
add_action('admin_menu', 'wa_order_add_admin_page');
// Array mapping for better customizations
// Since version 1.0.5
function wa_order_register_settings()
{
    $settings = wa_order_get_settings();
    foreach ($settings as $group => $group_settings) {
        foreach ($group_settings as $setting_name => $sanitization_callback) {
            register_setting($group, $setting_name, $sanitization_callback);
        }
    }
}
// Sanitization callback that can handle arrays of data properly
// Since version 1.0.5
function wa_order_sanitize_array($input)
{
    if (is_array($input)) {
        return array_map('sanitize_text_field', $input);
    }
    return [];
}
// Sanitization callback function for WP color picker
// Since version 1.0.5
function wa_order_sanitize_rgba_color($color)
{
    // Handling RGBA color format
    if (preg_match('/^rgba\(\d{1,3},\s?\d{1,3},\s?\d{1,3},\s?(0|1|0?\.\d+)\)$/', trim($color))) {
        return $color;
    }
    // Handling RGB color format (convert to HEX)
    elseif (preg_match('/^rgb\(\d{1,3},\s?\d{1,3},\s?\d{1,3}\)$/', trim($color))) {
        return wa_order_rgb_to_hex($color);
    }
    // Handling HEX color format
    return sanitize_hex_color($color);
}
function wa_order_rgb_to_hex($rgb)
{
    // Convert RGB to HEX color format
    list($r, $g, $b) = sscanf($rgb, "rgb(%d, %d, %d)");
    return sprintf("#%02x%02x%02x", $r, $g, $b);
}

// Revamped admin settings registrations
// Since version 1.0.5
function wa_order_get_settings()
{
    return [
        /*
        ******************************************** Basic tab options ****************************************
        */
        'wa-order-settings-group-button-config' => [
            'wa_order_selected_wa_number_single_product' => 'sanitize_text_field',
            'wa_order_option_dismiss_notice_confirmation' => 'sanitize_checkbox',
            'wa_order_whatsapp_base_url' => 'sanitize_text_field', // Reactivated in version 1.0.7
            'wa_order_whatsapp_base_url_desktop' => 'sanitize_text_field',
            'wa_order_single_product_button_position' => 'sanitize_text_field',
            'wa_order_option_enable_single_product' => 'sanitize_checkbox',
            'wa_order_option_message' => 'sanitize_textarea_field',
            'wa_order_option_text_button' => 'sanitize_text_field',
            'wa_order_option_target' => 'sanitize_checkbox',
            'wa_order_exclude_price' => 'sanitize_checkbox',
            'wa_order_exclude_product_url' => 'sanitize_checkbox',
            'wa_order_option_quantity_label' => 'sanitize_text_field',
            'wa_order_option_price_label' => 'sanitize_text_field',
            'wa_order_option_url_label' => 'sanitize_text_field',
            'wa_order_option_total_amount_label' => 'sanitize_text_field',
            'wa_order_option_total_discount_label' => 'sanitize_text_field',
            'wa_order_option_payment_method_label' => 'sanitize_text_field',
            'wa_order_option_thank_you_label' => 'sanitize_text_field',
            'wa_order_option_tax_label' => 'sanitize_text_field',
            'wa_order_single_force_fullwidth' => 'sanitize_checkbox',
            'wa_order_option_single_show_regular_sale_prices' => 'sanitize_checkbox',
        ],
        /*
        ******************************************** Display tab options ****************************************
        */
        'wa-order-settings-group-display-options' => [
            'wa_order_bg_color' => 'wa_order_sanitize_rgba_color',
            'wa_order_bg_hover_color' => 'wa_order_sanitize_rgba_color',
            'wa_order_txt_color' => 'wa_order_sanitize_rgba_color',
            'wa_order_txt_hover_color' => 'wa_order_sanitize_rgba_color',
            'wa_order_btn_box_shdw' => 'wa_order_sanitize_rgba_color',
            'wa_order_bshdw_horizontal' => 'sanitize_text_field',
            'wa_order_bshdw_vertical' => 'sanitize_text_field',
            'wa_order_bshdw_blur' => 'sanitize_text_field',
            'wa_order_bshdw_spread' => 'sanitize_text_field',
            'wa_order_bshdw_position' => 'sanitize_text_field',
            'wa_order_btn_box_shdw_hover' => 'wa_order_sanitize_rgba_color',
            'wa_order_bshdw_horizontal_hover' => 'sanitize_text_field',
            'wa_order_bshdw_vertical_hover' => 'sanitize_text_field',
            'wa_order_bshdw_blur_hover' => 'sanitize_text_field',
            'wa_order_bshdw_spread_hover' => 'sanitize_text_field',
            'wa_order_bshdw_position_hover' => 'sanitize_text_field',
            'wa_order_option_remove_btn' => 'sanitize_checkbox',
            'wa_order_option_remove_btn_mobile' => 'sanitize_checkbox',
            'wa_order_option_remove_price' => 'sanitize_checkbox',
            'wa_order_option_remove_cart_btn' => 'sanitize_checkbox',
            'wa_order_option_remove_quantity' => 'sanitize_checkbox',
            'wa_order_option_exlude_single_product_cats' => 'wa_order_sanitize_array',
            'wa_order_option_exlude_single_product_tags' => 'wa_order_sanitize_array',
            'wa_order_single_button_margin_top' => 'sanitize_text_field',
            'wa_order_single_button_margin_right' => 'sanitize_text_field',
            'wa_order_single_button_margin_bottom' => 'sanitize_text_field',
            'wa_order_single_button_margin_left' => 'sanitize_text_field',
            'wa_order_single_button_padding_top' => 'sanitize_text_field',
            'wa_order_single_button_padding_right' => 'sanitize_text_field',
            'wa_order_single_button_padding_bottom' => 'sanitize_text_field',
            'wa_order_single_button_padding_left' => 'sanitize_text_field',
            'wa_order_display_option_shop_loop_hide_desktop' => 'sanitize_checkbox',
            'wa_order_display_option_shop_loop_hide_mobile' => 'sanitize_checkbox',
            'wa_order_option_exlude_shop_product_cats' => 'wa_order_sanitize_array',
            'wa_order_exlude_shop_product_cats_archive' => 'sanitize_checkbox',
            'wa_order_option_exlude_shop_product_tags' => 'wa_order_sanitize_array',
            'wa_order_exlude_shop_product_tags_archive' => 'sanitize_checkbox',
            'wa_order_display_option_cart_hide_desktop' => 'sanitize_checkbox',
            'wa_order_display_option_cart_hide_mobile' => 'sanitize_checkbox',
            'wa_order_display_option_checkout_hide_desktop' => 'sanitize_checkbox',
            'wa_order_display_option_checkout_hide_mobile' => 'sanitize_checkbox',
            'wa_order_option_convert_phone_order_details' => 'sanitize_checkbox',
            'wa_order_option_custom_message_backend_order_details' => 'sanitize_text_field'
        ],
        /*
    ******************************************** GDPR tab options ****************************************
    */
        'wa-order-settings-group-gdpr' => [
            'wa_order_gdpr_status_enable' => 'sanitize_checkbox',
            'wa_order_gdpr_message' => 'sanitize_textarea_field',
            'wa_order_gdpr_privacy_page' => 'sanitize_text_field'
        ],
        /*
    ******************************************** Floating Button tab options ****************************************
    */
        'wa-order-settings-group-floating' => [
            'wa_order_selected_wa_number_floating' => 'sanitize_text_field',
            'wa_order_floating_button' => 'sanitize_checkbox',
            'wa_order_floating_button_position' => 'sanitize_text_field',
            'wa_order_floating_message' => 'sanitize_textarea_field',
            'wa_order_floating_target' => 'sanitize_checkbox',
            'wa_order_floating_tooltip_enable' => 'sanitize_checkbox',
            'wa_order_floating_tooltip' => 'sanitize_text_field',
            'wa_order_floating_hide_mobile' => 'sanitize_checkbox',
            'wa_order_floating_hide_desktop' => 'sanitize_checkbox',
            'wa_order_floating_source_url' => 'sanitize_checkbox',
            'wa_order_floating_source_url_label' => 'sanitize_text_field',
            'wa_order_floating_hide_all_single_posts' => 'sanitize_text_field',
            'wa_order_floating_hide_all_single_pages' => 'sanitize_text_field',
            'wa_order_floating_hide_specific_posts' => 'wa_order_sanitize_array',
            'wa_order_floating_hide_specific_pages' => 'wa_order_sanitize_array',
            'wa_order_floating_hide_product_cats' => 'wa_order_sanitize_array',
            'wa_order_floating_hide_product_tags' => 'wa_order_sanitize_array',
            'wa_order_floating_button_margin_top' => 'sanitize_text_field',
            'wa_order_floating_button_margin_right' => 'sanitize_text_field',
            'wa_order_floating_button_margin_bottom' => 'sanitize_text_field',
            'wa_order_floating_button_margin_left' => 'sanitize_text_field',
            'wa_order_floating_button_padding_top' => 'sanitize_text_field',
            'wa_order_floating_button_padding_right' => 'sanitize_text_field',
            'wa_order_floating_button_padding_bottom' => 'sanitize_text_field',
            'wa_order_floating_button_padding_left' => 'sanitize_text_field',
            'wa_order_floating_button_icon_margin_top' => 'sanitize_text_field',
            'wa_order_floating_button_icon_margin_right' => 'sanitize_text_field',
            'wa_order_floating_button_icon_margin_bottom' => 'sanitize_text_field',
            'wa_order_floating_button_icon_margin_left' => 'sanitize_text_field',
            'wa_order_floating_button_icon_padding_top' => 'sanitize_text_field',
            'wa_order_floating_button_icon_padding_right' => 'sanitize_text_field',
            'wa_order_floating_button_icon_padding_bottom' => 'sanitize_text_field',
            'wa_order_floating_button_icon_padding_left' => 'sanitize_text_field'
        ],
        /*
    ******************************************** Shortcode tab options ****************************************
    */
        'wa-order-settings-group-shortcode' => [
            'wa_order_selected_wa_number_shortcode' => 'sanitize_text_field',
            'wa_order_shortcode_message' => 'sanitize_textarea_field',
            'wa_order_shortcode_text_button' => 'sanitize_text_field',
            'wa_order_shortcode_target' => 'sanitize_checkbox'
        ],
        /*
    ******************************************** Cart page tab options ****************************************
    */
        'wa-order-settings-group-cart-options' => [
            'wa_order_selected_wa_number_cart' => 'sanitize_text_field',
            'wa_order_option_add_button_to_cart' => 'sanitize_checkbox',
            'wa_order_option_cart_custom_message' => 'sanitize_textarea_field',
            'wa_order_option_cart_button_text' => 'sanitize_text_field',
            'wa_order_option_cart_hide_checkout' => 'sanitize_checkbox',
            'wa_order_option_cart_hide_product_url' => 'sanitize_checkbox',
            'wa_order_option_cart_open_new_tab' => 'sanitize_checkbox',
            'wa_order_option_cart_enable_variations' => 'sanitize_checkbox',
            'wa_order_option_cart_include_tax' => 'sanitize_checkbox'
        ],
        /*
    ******************************************** Thank You page tab options ****************************************
    */
        'wa-order-settings-group-order-completion' => [
            'wa_order_selected_wa_number_thanks' => 'sanitize_text_field',
            'wa_order_option_thank_you_redirect_checkout' => 'sanitize_checkbox',
            'wa_order_option_enable_button_thank_you' => 'sanitize_checkbox',
            'wa_order_option_custom_thank_you_title' => 'sanitize_text_field',
            'wa_order_option_custom_thank_you_subtitle' => 'sanitize_text_field',
            'wa_order_option_custom_thank_you_button_text' => 'sanitize_text_field',
            'wa_order_option_custom_thank_you_custom_message' => 'sanitize_textarea_field',
            'wa_order_option_custom_thank_you_include_order_date' => 'sanitize_checkbox',
            'wa_order_option_custom_thank_you_order_number' => 'sanitize_checkbox',
            'wa_order_option_custom_thank_you_order_number_label' => 'sanitize_text_field',
            'wa_order_option_thank_you_order_summary_link' => 'sanitize_checkbox',
            'wa_order_option_thank_you_order_summary_label' => 'sanitize_text_field',
            'wa_order_option_thank_you_payment_link' => 'sanitize_checkbox',
            'wa_order_option_thank_you_payment_link_label' => 'sanitize_text_field',
            'wa_order_option_thank_you_view_order_link' => 'sanitize_checkbox',
            'wa_order_option_thank_you_view_order_label' => 'sanitize_text_field',
            'wa_order_option_custom_thank_you_open_new_tab' => 'sanitize_checkbox',
            'wa_order_option_custom_thank_you_customer_details_label' => 'sanitize_text_field',
            'wa_order_option_custom_thank_you_total_products_label' => 'sanitize_text_field',
            'wa_order_option_custom_thank_you_include_sku' => 'sanitize_checkbox',
            'wa_order_option_custom_thank_you_include_tax' => 'sanitize_checkbox',
            'wa_order_option_custom_thank_you_inclue_coupon' => 'sanitize_checkbox',
            'wa_order_option_custom_thank_you_coupon_label' => 'sanitize_text_field'
        ],
        /*
    ******************************************** Shop page tab options ****************************************
    */
        'wa-order-settings-group-shop-loop' => [
            'wa_order_selected_wa_number_shop' => 'sanitize_text_field',
            'wa_order_option_enable_button_shop_loop' => 'sanitize_checkbox',
            'wa_order_option_hide_atc_shop_loop' => 'sanitize_checkbox',
            'wa_order_option_button_text_shop_loop' => 'sanitize_text_field',
            'wa_order_option_custom_message_shop_loop' => 'sanitize_textarea_field',
            'wa_order_option_shop_loop_hide_product_url' => 'sanitize_checkbox',
            'wa_order_option_shop_loop_exclude_price' => 'sanitize_checkbox',
            'wa_order_option_shop_loop_open_new_tab' => 'sanitize_checkbox'
        ],
    ];
}
// Delete option upon deactivation
function wa_order_deactivation()
{
    // delete_option( 'wa_order_option_phone_number' ); // Old phone number option
    delete_option('wa_order_selected_wa_number'); // New phone number option
    delete_option('wa_order_option_dismiss_notice_confirmation');
    delete_option('wa_order_whatsapp_base_url'); // Reactivated in version 1.0.7
    delete_option('wa_order_whatsapp_base_url_desktop');
    delete_option('wa_order_single_product_button_position');
    delete_option('wa_order_option_enable_single_product');
    delete_option('wa_order_option_message');
    delete_option('wa_order_option_text_button');
    delete_option('wa_order_option_target');
    delete_option('wa_order_single_force_fullwidth');
    delete_option('wa_order_option_single_show_regular_sale_prices');
    delete_option('wa_order_exclude_product_url');
    delete_option('wa_order_option_remove_btn');
    delete_option('wa_order_option_remove_btn_mobile');
    delete_option('wa_order_option_remove_price');
    delete_option('wa_order_option_remove_cart_btn');
    delete_option('wa_order_option_remove_quantity');
    delete_option('wa_order_option_exlude_single_product_cats');
    delete_option('wa_order_option_exlude_single_product_tags');
    delete_option('wa_order_single_button_margin_top');
    delete_option('wa_order_single_button_margin_right');
    delete_option('wa_order_single_button_margin_bottom');
    delete_option('wa_order_single_button_margin_left');
    delete_option('wa_order_single_button_padding_top');
    delete_option('wa_order_single_button_padding_right');
    delete_option('wa_order_single_button_padding_bottom');
    delete_option('wa_order_single_button_padding_left');
    delete_option('wa_order_exlude_shop_product_cats_archive');
    delete_option('wa_order_exlude_shop_product_tags_archive');
    delete_option('wa_order_display_option_shop_loop_hide_desktop');
    delete_option('wa_order_display_option_shop_loop_hide_mobile');
    delete_option('wa_order_btn_box_shdw');
    delete_option('wa_order_bshdw_horizontal');
    delete_option('wa_order_bshdw_vertical');
    delete_option('wa_order_bshdw_blur');
    delete_option('wa_order_bshdw_spread');
    delete_option('wa_order_bshdw_position');
    delete_option('wa_order_option_exlude_shop_product_cats');
    delete_option('wa_order_option_exlude_shop_product_tags');
    delete_option('wa_order_display_option_cart_hide_desktop');
    delete_option('wa_order_display_option_cart_hide_mobile');
    delete_option('wa_order_display_option_checkout_hide_desktop');
    delete_option('wa_order_display_option_checkout_hide_mobile');
    delete_option('wa_order_option_convert_phone_order_details');
    delete_option('wa_order_option_custom_message_backend_order_details');
    delete_option('wa_order_gdpr_status_enable');
    delete_option('wa_order_gdpr_message');
    delete_option('wa_order_gdpr_privacy_page');
    delete_option('wa_order_floating_button');
    delete_option('wa_order_floating_button_position');
    delete_option('wa_order_floating_message');
    delete_option('wa_order_floating_target');
    delete_option('wa_order_floating_tooltip_enable');
    delete_option('wa_order_floating_tooltip');
    delete_option('wa_order_floating_hide_mobile');
    delete_option('wa_order_floating_hide_desktop');
    delete_option('wa_order_floating_source_url');
    delete_option('wa_order_floating_source_url_label');
    delete_option('wa_order_floating_hide_all_single_posts');
    delete_option('wa_order_floating_hide_all_single_pages');
    delete_option('wa_order_floating_hide_specific_posts');
    delete_option('wa_order_floating_hide_specific_pages');
    delete_option('wa_order_floating_hide_product_cats');
    delete_option('wa_order_floating_hide_product_tags');
    delete_option('wa_order_shortcode_message');
    delete_option('wa_order_shortcode_text_button');
    delete_option('wa_order_shortcode_target');
    delete_option('wa_order_option_add_button_to_cart');
    delete_option('wa_order_option_cart_custom_message');
    delete_option('wa_order_option_cart_button_text');
    delete_option('wa_order_option_cart_hide_checkout');
    delete_option('wa_order_option_cart_hide_product_url');
    delete_option('wa_order_option_cart_open_new_tab');
    delete_option('wa_order_option_cart_enable_variations');
    delete_option('wa_order_option_cart_include_tax');
    delete_option('wa_order_option_quantity_label');
    delete_option('wa_order_option_price_label');
    delete_option('wa_order_option_url_label');
    delete_option('wa_order_option_total_amount_label');
    delete_option('wa_order_option_total_discount_label');
    delete_option('wa_order_option_payment_method_label');
    delete_option('wa_order_option_thank_you_label');
    delete_option('wa_order_option_thank_you_redirect_checkout');
    delete_option('wa_order_option_enable_button_thank_you');
    delete_option('wa_order_option_custom_thank_you_title');
    delete_option('wa_order_option_custom_thank_you_subtitle');
    delete_option('wa_order_option_custom_thank_you_button_text');
    delete_option('wa_order_option_custom_thank_you_custom_message');
    delete_option('wa_order_option_custom_thank_you_include_order_date');
    delete_option('wa_order_option_custom_thank_you_order_number');
    delete_option('wa_order_option_custom_thank_you_order_number_label');
    delete_option('wa_order_option_thank_you_order_summary_link');
    delete_option('wa_order_option_thank_you_order_summary_label');
    delete_option('wa_order_option_thank_you_payment_link');
    delete_option('wa_order_option_thank_you_payment_link_label');
    delete_option('wa_order_option_thank_you_view_order_link');
    delete_option('wa_order_option_thank_you_view_order_label');
    delete_option('wa_order_option_custom_thank_you_include_tax');
    delete_option('wa_order_option_custom_thank_you_open_new_tab');
    delete_option('wa_order_option_custom_thank_you_customer_details_label');
    delete_option('wa_order_option_custom_thank_you_total_products_label');
    delete_option('wa_order_option_custom_thank_you_include_sku');
    delete_option('wa_order_option_tax_label');
    delete_option('wa_order_option_custom_thank_you_inclue_coupon');
    delete_option('wa_order_option_custom_thank_you_coupon_label');
    delete_option('wa_order_option_enable_button_shop_loop');
    delete_option('wa_order_option_hide_atc_shop_loop');
    delete_option('wa_order_option_button_text_shop_loop');
    delete_option('wa_order_option_custom_message_shop_loop');
    delete_option('wa_order_option_shop_loop_hide_product_url');
    delete_option('wa_order_option_shop_loop_exclude_price');
    delete_option('wa_order_option_shop_loop_open_new_tab');
    delete_option('wa_order_floating_button_margin_top');
    delete_option('wa_order_floating_button_margin_right');
    delete_option('wa_order_floating_button_margin_bottom');
    delete_option('wa_order_floating_button_margin_left');
    delete_option('wa_order_floating_button_padding_top');
    delete_option('wa_order_floating_button_padding_right');
    delete_option('wa_order_floating_button_padding_bottom');
    delete_option('wa_order_floating_button_padding_left');
    delete_option('wa_order_floating_button_icon_margin_top');
    delete_option('wa_order_floating_button_icon_margin_right');
    delete_option('wa_order_floating_button_icon_margin_bottom');
    delete_option('wa_order_floating_button_icon_margin_left');
    delete_option('wa_order_floating_button_icon_padding_top');
    delete_option('wa_order_floating_button_icon_padding_right');
    delete_option('wa_order_floating_button_icon_padding_bottom');
    delete_option('wa_order_floating_button_icon_padding_left');
}
register_deactivation_hook(__FILE__, 'wa_order_deactivation');
// Begin Building the Admin Tabs
function wa_order_create_admin_page()
{
    // Define the valid tabs
    $valid_tabs = [
        'button_config',
        'floating_button',
        'display_option',
        'shop_page',
        'cart_button',
        'thanks_page',
        'gdpr_notice',
        'generate_shortcode',
        'tutorial_support',
        'welcome'
    ];
    // Sanitize and validate the 'tab' parameter
    $active_tab = isset($_GET['tab']) ? wp_unslash($_GET['tab']) : 'welcome';
    if (!in_array($active_tab, $valid_tabs)) {
        $active_tab = 'welcome'; // default to the 'welcome' tab
    }
?>
    <div class="wrap OCWAORDER_pluginpage_title">
        <h1><?php esc_html_e('OneClick Chat to Order', 'oneclick-wa-order'); ?></h1>
        <hr>
        <h2 class="nav-tab-wrapper">
            <a href="?page=wa-order&tab=welcome" class="nav-tab <?php echo esc_attr($active_tab == 'welcome') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Welcome', 'oneclick-wa-order'); ?></a>
            <a href="edit.php?post_type=wa-order-numbers" class="nav-tab <?php echo esc_attr($active_tab == 'phone-numbers') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Numbers', 'oneclick-wa-order'); ?></a>
            <a href="?page=wa-order&tab=button_config" class="nav-tab <?php echo esc_attr($active_tab == 'button_config') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Basic', 'oneclick-wa-order'); ?></a>
            <a href="?page=wa-order&tab=shop_page" class="nav-tab <?php echo esc_attr($active_tab == 'shop_page') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Shop', 'oneclick-wa-order'); ?></a>
            <a href="?page=wa-order&tab=cart_button" class="nav-tab <?php echo esc_attr($active_tab == 'cart_button') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Cart', 'oneclick-wa-order'); ?></a>
            <a href="?page=wa-order&tab=thanks_page" class="nav-tab <?php echo esc_attr($active_tab == 'thanks_page') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Checkout', 'oneclick-wa-order'); ?></a>
            <a href="?page=wa-order&tab=floating_button" class="nav-tab <?php echo esc_attr($active_tab == 'floating_button') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Floating', 'oneclick-wa-order'); ?></a>
            <a href="?page=wa-order&tab=display_option" class="nav-tab <?php echo esc_attr($active_tab == 'display_option') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Display', 'oneclick-wa-order'); ?></a>
            <a href="?page=wa-order&tab=gdpr_notice" class="nav-tab <?php echo esc_attr($active_tab == 'gdpr_notice') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('GDPR', 'oneclick-wa-order'); ?></a>
            <a href="?page=wa-order&tab=generate_shortcode" class="nav-tab <?php echo esc_attr($active_tab == 'generate_shortcode') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Shortcode', 'oneclick-wa-order'); ?></a>
            <a href="?page=wa-order&tab=tutorial_support" class="nav-tab <?php echo esc_attr($active_tab == 'tutorial_support') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Support', 'oneclick-wa-order'); ?></a>
        </h2>
        <?php if ($active_tab == 'generate_shortcode') { ?>
            <?php wp_enqueue_script('wa_order_js_admin'); ?>
            <h2 class="section_wa_order"><?php esc_html_e('Generate Shortcode', 'oneclick-wa-order'); ?></h2>
            <p>
                <?php esc_html_e('Use shortcode to display OneClick Chat to Order\'s WhatsApp button anywhere on your site. There are three options; single product, global and dynamic.', 'oneclick-wa-order'); ?>
                <br />
            </p>

            <hr />
            <h3 class="section_wa_order"><?php esc_html_e('Single Product Shortcode Generator', 'oneclick-wa-order'); ?></h3>
            <p>
                <?php esc_html_e('Create a dynamic shortcode for a single product page. Note: This shortcode will only work for single products.', 'oneclick-wa-order'); ?>
                <br />
                <?php echo esc_html__('All other options will be pulled from the Single Product Page settings under the ', 'oneclick-wa-order') . ' <a href="admin.php?page=wa-order&tab=button_config"><b>' . esc_html__('Basic', 'oneclick-wa-order') . '</b></a> tab.'; ?>
                <br />
            </p>
            <hr />
            <!-- Single Product Shortcode Generator -->
            <form>
                <table class="form-table">
                    <tbody>
                        <!-- Dropdown WA Number -->
                        <tr>
                            <th scope="row">
                                <label><?php echo esc_html_e('WhatsApp Number', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <?php wa_order_phone_numbers_dropdown_shortcode_generator(
                                    array(
                                        'name'      => 'wa_order_phone_numbers_dropdown_shortcode_generator',
                                        'selected'  => esc_attr(get_option('wa_order_selected_wa_number_shortcode')),
                                    )
                                ); ?>
                                <p class="description">
                                    <?php
                                    /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                                    echo wp_kses(
                                        sprintf(
                                            /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                                            __('WhatsApp number is %1$srequired%2$s. Please set it on the %3$sNumbers%4$s tab.', 'oneclick-wa-order'),
                                            '<strong style="color:red;">', // opening <strong> tag with red color style
                                            '</strong>', // closing <strong> tag
                                            '<a href="edit.php?post_type=wa-order-numbers"><strong>', // opening <a> and <strong> tag
                                            '</strong></a>' // closing <a> and <strong> tag
                                        ),
                                        array(
                                            'strong' => array('style' => array()), // Allow strong with style attribute
                                            'a' => array('href' => array(), 'target' => array()) // Allow a tag with href and target attributes
                                        )
                                    );
                                    ?>
                                </p>
                            </td>
                        </tr>
                        <!-- For Which Product? -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label for="SingleWAWhichPage"><?php echo esc_html__('For Which Product?', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <select name="SingleWAWhichPage" id="SingleWAWhichPage" onChange="generateSingleWAshortcode();" class="wa_order-admin-select2 regular-text">
                                    <option value="current"><?php echo esc_html__('Current Product', 'oneclick-wa-order'); ?></option>
                                    <option value="product_id"><?php echo esc_html__('Product by ID', 'oneclick-wa-order'); ?></option>
                                </select>
                                <p class="description">
                                    - <?php echo esc_html__('If you choose ', 'oneclick-wa-order') . ' <b>' . esc_html__('Current Product', 'oneclick-wa-order') . '</b>' . esc_html__(', the shortcode will automatically pull in the product details where you place the shortcode.', 'oneclick-wa-order'); ?>
                                    <br>
                                    - <?php echo esc_html__('On the other hand, if you choose ', 'oneclick-wa-order') . ' <b>' . esc_html__('Product by ID', 'oneclick-wa-order') . '</b>' . esc_html__(', just enter a  product ID and it\'ll pull in the details for that product instead.', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>
                        <!-- Product by ID -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label for="SingleWAProductID"><?php echo esc_html__('Product ID', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="number" id="SingleWAProductID" name="SingleWAProductID" onChange="generateSingleWAshortcode();" class="wa_order_input" placeholder="<?php echo esc_attr__('e.g. 23', 'oneclick-wa-order'); ?>">
                                <p class="description">
                                    <?php echo esc_html__('Insert a valid ', 'oneclick-wa-order') . ' <b>' . esc_html__('Product ID', 'oneclick-wa-order') . '</b>' . esc_html__('. Ensure it\'s available and published.', 'oneclick-wa-order'); ?>
                                    <br>
                                </p>
                            </td>
                        </tr>
                        <!-- Text on Button -->
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="SingleWAbuttonText"><b><?php echo esc_html__('Text on Button', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" id="SingleWAbuttonText" name="SingleWAbuttonText" onChange="generateSingleWAshortcode();" class="wa_order_input" placeholder="<?php echo esc_attr__('e.g. Order via WhatsApp', 'oneclick-wa-order'); ?>">
                                <p class="description">
                                    <?php echo esc_html__('Enter button text, e.g.', 'oneclick-wa-order') . ' <code>' . esc_html__('Order via WhatsApp', 'oneclick-wa-order') . '</code>'; ?>
                                    <br>
                                    <?php echo esc_html__('If empty, first the shortcode will use the single product\'s button text value, then the global single product text on button.', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>
                        <!-- Custom Message -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label class="wa_order_message_label" for="SingleWAcustomMessage"><b><?php echo esc_html__('Custom Message', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <textarea class="wa_order_input_areatext" rows="5" placeholder="<?php echo esc_attr__('e.g. Hello, I need to know more about', 'oneclick-wa-order'); ?>" id="SingleWAcustomMessage" name="SingleWAcustomMessage" onChange="generateSingleWAshortcode();"></textarea>
                                <p class="description">
                                    <?php echo esc_html__('Enter custom message, e.g.', 'oneclick-wa-order') . ' <code>' . esc_html__('Hello, I need to know more about', 'oneclick-wa-order') . '</code>'; ?>
                                    <br>
                                    <?php echo esc_html__('If empty, first the shortcode will use the single product\'s custom message, then the global single product message.', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>
                        <!-- Force Fullwidth? -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label for="SingleWAFullwidth"><?php echo esc_html__('Force Fullwidth?', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <select name="SingleWAFullwidth" id="SingleWAFullwidth" onChange="generateSingleWAshortcode();" class="wa_order-admin-select2 regular-text">
                                    <option value="false"><?php echo esc_html__('No', 'oneclick-wa-order'); ?></option>
                                    <option value="true"><?php echo esc_html__('Yes', 'oneclick-wa-order'); ?></option>
                                </select>
                            </td>
                        </tr>
                        <!-- Copy Shortcode -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label class="wa_order_message_label" for="generatedSingleWAShortcode"><b><?php echo esc_html__('Copy Shortcode', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <textarea class="wa_order_input_areatext" rows="5" id="generatedSingleWAShortcode" onclick="this.setSelectionRange(0, this.value.length)"></textarea>
                                <p class="description">
                                    <?php echo esc_html__('Copy above shortcode and paste it anywhere.', 'oneclick-wa-order'); ?></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <hr />
            <!-- End - Single Product Shortcode Generator -->

            <h3 class="section_wa_order"><?php esc_html_e('General Shortcode Generator', 'oneclick-wa-order'); ?></h3>
            <p>
                <?php esc_html_e('Create a general purpose shortcode using the following generator.', 'oneclick-wa-order'); ?>
                <br />
            </p>
            <hr />
            <form>
                <!-- Shortcode Generator -->
                <table class="form-table">
                    <tbody>
                        <!-- Dropdown WA Number -->
                        <tr>
                            <th scope="row">
                                <label><?php echo esc_html_e('WhatsApp Number', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <?php wa_order_phone_numbers_dropdown_shortcode_generator(
                                    array(
                                        'name'      => 'wa_order_phone_numbers_dropdown_shortcode_generator',
                                        'selected'  => esc_attr(get_option('wa_order_selected_wa_number_shortcode')),
                                    )
                                ); ?>
                                <p class="description">
                                    <?php
                                    /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                                    echo wp_kses(
                                        sprintf(
                                            /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                                            __('WhatsApp number is %1$srequired%2$s. Please set it on the %3$sNumbers%4$s tab.', 'oneclick-wa-order'),
                                            '<strong style="color:red;">', // opening <strong> tag with red color style
                                            '</strong>', // closing <strong> tag
                                            '<a href="edit.php?post_type=wa-order-numbers"><strong>', // opening <a> and <strong> tag
                                            '</strong></a>' // closing <a> and <strong> tag
                                        ),
                                        array(
                                            'strong' => array('style' => array()), // Allow strong with style attribute
                                            'a' => array('href' => array(), 'target' => array()) // Allow a tag with href and target attributes
                                        )
                                    );
                                    ?>
                                </p>
                            </td>
                        </tr>
                        <!-- Text on Button -->
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="WAbuttonText"><b><?php echo esc_html__('Text on Button', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" id="WAbuttonText" name="WAbuttonText" onChange="generateWAshortcode();" class="wa_order_input" placeholder="<?php echo esc_attr__('e.g. Order via WhatsApp', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>
                        <!-- Custom Message -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label class="wa_order_message_label" for="message_wbw"><b><?php echo esc_html__('Custom Message', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <textarea class="wa_order_input_areatext" rows="5" placeholder="<?php echo esc_attr__('e.g. Hello, I need to know more about', 'oneclick-wa-order'); ?>" id="WAcustomMessage" name="WAcustomMessage" onChange="generateWAshortcode();"></textarea>
                                <p class="description">
                                    <?php echo esc_html__('Enter custom message, e.g.', 'oneclick-wa-order') . ' <code>' . esc_html__('Hello, I need to know more about', 'oneclick-wa-order') . '</code>'; ?>
                                </p>
                            </td>
                        </tr>
                        <!-- Open in New Tab? -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label for="WAnewTab"><?php echo esc_html__('Open in New Tab?', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <select name="WAnewTab" id="WAnewTab" onChange="generateWAshortcode();">
                                    <option value="no"><?php echo esc_html__('No', 'oneclick-wa-order'); ?></option>
                                    <option value="yes"><?php echo esc_html__('Yes', 'oneclick-wa-order'); ?></option>
                                </select>
                            </td>
                        </tr>
                        <!-- Copy Shortcode -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label class="wa_order_message_label" for="message_wbw"><b><?php echo esc_html__('Copy Shortcode', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <textarea class="wa_order_input_areatext" rows="5" id="generatedShortcode" onclick="this.setSelectionRange(0, this.value.length)"></textarea>
                                <p class="description">
                                    <?php echo esc_html__('Copy above shortcode and paste it anywhere.', 'oneclick-wa-order'); ?></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <hr />
            <!-- End - Shortcode Generator -->
            <!-- Start Global Shortcode -->
            <form method="post" action="options.php">
                <?php settings_errors(); ?>
                <?php settings_fields('wa-order-settings-group-shortcode'); ?>
                <?php do_settings_sections('wa-order-settings-group-shortcode'); ?>
                <h3 class="section_wa_order"><?php echo esc_html__('Global Shortcode', 'oneclick-wa-order'); ?></h3>
                <p>
                    <?php echo esc_html__('You need to click the', 'oneclick-wa-order') . ' <b>' . esc_html__('Save Changes', 'oneclick-wa-order') . '</b> ' . esc_html__('button below in order to use the', 'oneclick-wa-order') . ' <code>[wa-order]</code> ' . esc_html__('shortcode.', 'oneclick-wa-order'); ?>
                </p>
                <table class="form-table">
                    <tbody>
                        <!-- Dropdown WA Number -->
                        <tr>
                            <th scope="row">
                                <label><?php echo esc_html__('WhatsApp Number', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <?php wa_order_phone_numbers_dropdown(
                                    array(
                                        'name'      => 'wa_order_selected_wa_number_shortcode',
                                        'selected'  => esc_attr(get_option('wa_order_selected_wa_number_shortcode')),
                                    )
                                ); ?>
                                <p class="description">
                                    <?php
                                    /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                                    echo wp_kses(
                                        sprintf(
                                            /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                                            __('WhatsApp number is %1$srequired%2$s. Please set it on the %3$sNumbers%4$s tab.', 'oneclick-wa-order'),
                                            '<strong style="color:red;">', // opening <strong> tag with red color style
                                            '</strong>', // closing <strong> tag
                                            '<a href="edit.php?post_type=wa-order-numbers"><strong>', // opening <a> and <strong> tag
                                            '</strong></a>' // closing <a> and <strong> tag
                                        ),
                                        array(
                                            'strong' => array('style' => array()), // Allow strong with style attribute
                                            'a' => array('href' => array(), 'target' => array()) // Allow a tag with href and target attributes
                                        )
                                    );
                                    ?>
                                </p>
                            </td>
                        </tr>
                        <!-- Text on Button -->
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="text_button"><b><?php echo esc_html__('Text on Button', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_shortcode_text_button" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_shortcode_text_button')); ?>" placeholder="<?php echo esc_attr__('e.g. Order via WhatsApp', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>
                        <!-- Custom Message -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label class="wa_order_message_label" for="message_wbw"><b><?php echo esc_html__('Custom Message', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <textarea name="wa_order_shortcode_message" class="wa_order_input_areatext" rows="5" placeholder="<?php echo esc_attr__('e.g. Hello, I need to know more about', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_shortcode_message')); ?></textarea>
                                <p class="description">
                                    <?php echo esc_html__('Enter custom message, e.g.', 'oneclick-wa-order') . ' <code>' . esc_html__('Hello, I need to know more about', 'oneclick-wa-order') . '</code>'; ?>
                                </p>
                            </td>
                        </tr>
                        <!-- Copy Shortcode -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_copy_label" for="wa_order_copy"><b><?php echo esc_html__('Copy Shortcode', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input style="letter-spacing: 1px;" class="wa_order_shortcode_input" onClick="this.setSelectionRange(0, this.value.length)" value="[wa-order]" readonly />
                            </td>
                        </tr>
                        <!-- Open in New Tab? -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_target_label" for="wa_order_target"><b><?php echo esc_html__('Open in New Tab?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_shortcode_target" class="wa_order_input_check" value="_blank" <?php checked(get_option('wa_order_shortcode_target'), '_blank'); ?>>
                                <?php echo esc_html__('Yes, Open in New Tab', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <?php submit_button(); ?>
            </form>
            <!-- End - Shortcode Tab Setting Page -->
        <?php } elseif ($active_tab == 'button_config') { ?>
            <!-- Basic Configurations -->
            <form method="post" action="options.php">
                <?php settings_errors(); ?>
                <?php settings_fields('wa-order-settings-group-button-config'); ?>
                <?php do_settings_sections('wa-order-settings-group-button-config'); ?>
                <!-- Basic Configuration tab -->
                <h2 class="section_wa_order"><?php esc_html_e('Confirmation', 'oneclick-wa-order'); ?></h2>
                <p>
                    <?php
                    /* translators: 1. opening <a> tag with strong tag for "set it here", 2. closing </a> tag, 3. opening <a> tag with strong tag for "Learn more", 4. closing </a> tag. */
                    echo sprintf(
                        /* translators: 1: "set it here" link, 2: "Learn more" link */
                        esc_html__('Make sure that you have added at least one WhatsApp number to dismiss the admin notice. Please %1$sset it here%2$s to get started. %3$sLearn more%4$s.', 'oneclick-wa-order'),
                        '<a href="edit.php?post_type=wa-order-numbers"><strong>',
                        '</strong></a>',
                        '<a href="https://walterpinem.me/projects/oneclick-chat-to-order-mutiple-numbers-feature/?utm_source=admin-notice&utm_medium=admin-dashboard&utm_campaign=OneClick-Chat-to-Order" target="_blank"><strong>',
                        '</strong></a>'
                    );
                    ?>
                    <br />
                </p>
                <table class="form-table">
                    <tbody>
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Dismiss Notice', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_dismiss_notice_confirmation" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_dismiss_notice_confirmation'), 'yes'); ?>>
                                <?php esc_html_e('Check this if you have added at least one WhatsApp number.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <?php /** Re-activated in version 1.0.7. */ ?>
                <h2 class="section_wa_order"><?php esc_html_e('WhatsApp Base URL', 'oneclick-wa-order'); ?></h2>
                <p class="description">
                    <?php esc_html_e('If you or your customers are having trouble opening the WhatsApp link on a mobile device or desktop, don\'t worry - you can simply configure the base URL for each device type here.', 'oneclick-wa-order'); ?>
                </p>
                <hr>
                <table class="form-table">
                    <tbody>
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_whatsapp_base_url">
                                    <strong><?php esc_html_e('Base URL for Mobile', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <select name="wa_order_whatsapp_base_url" id="wa_order_whatsapp_base_url" class="wa_order-admin-select2">
                                    <option value="api" <?php selected(get_option('wa_order_whatsapp_base_url'), 'api'); ?>><?php esc_html_e('api - api.whatsapp.com (default)', 'oneclick-wa-order'); ?></option>
                                    <option value="protocol" <?php selected(get_option('wa_order_whatsapp_base_url'), 'protocol'); ?>><?php esc_html_e('protocol - whatsapp://send', 'oneclick-wa-order'); ?></option>
                                </select>
                                <p class="description">
                                    - <code><?php esc_html_e('whatsapp://send', 'oneclick-wa-order'); ?></code> <?php esc_html_e('is ideal for mobile devices with WhatsApp already installed, offering a faster and more direct experience by bypassing the browser.', 'oneclick-wa-order'); ?>
                                    <br>
                                </p>
                                <p class="description">
                                    - <code><?php esc_html_e('api.whatsapp.com', 'oneclick-wa-order'); ?></code> <?php esc_html_e('is good for a universal experience that works across mobile device environments, but might involve some extra steps on mobile browsers.', 'oneclick-wa-order'); ?>
                                </p>
                                <br>
                            </td>
                        </tr>
                        <!-- For Desktop -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_whatsapp_base_url_desktop">
                                    <strong><?php esc_html_e('Base URL for Desktop', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <select name="wa_order_whatsapp_base_url_desktop" id="wa_order_whatsapp_base_url_desktop" class="wa_order-admin-select2">
                                    <option value="web" <?php selected(get_option('wa_order_whatsapp_base_url_desktop'), 'web'); ?>><?php esc_html_e('web - web.whatsapp.com (default)', 'oneclick-wa-order'); ?></option>
                                    <option value="api" <?php selected(get_option('wa_order_whatsapp_base_url_desktop'), 'api'); ?>><?php esc_html_e('api - api.whatsapp.com', 'oneclick-wa-order'); ?></option>
                                    <option value="protocol" <?php selected(get_option('wa_order_whatsapp_base_url_desktop'), 'protocol'); ?>><?php esc_html_e('protocol - whatsapp://send', 'oneclick-wa-order'); ?></option>
                                </select>
                                <p class="description">
                                    - <?php esc_html_e('Using', 'oneclick-wa-order'); ?> <code><?php esc_html_e('api', 'oneclick-wa-order'); ?></code> <?php esc_html_e('as the base URL, the customers will be prompted to open WhatsApp desktop app if installed.', 'oneclick-wa-order'); ?>
                                    <br>
                                </p>
                                <p class="description">
                                    - <?php esc_html_e('Whereas using', 'oneclick-wa-order'); ?> <code><?php esc_html_e('web', 'oneclick-wa-order'); ?></code> <?php esc_html_e('as the base URL, the customers will be immediately redirected to the WhatsApp web on the browser.', 'oneclick-wa-order'); ?>
                                    <br>
                                </p>
                                <p class="description">
                                    - <?php esc_html_e('Using the protocol', 'oneclick-wa-order'); ?> <code><?php esc_html_e('whatsapp://send', 'oneclick-wa-order'); ?></code> <?php esc_html_e('will immediately prompt the customers to open the WhatsApp desktop app, bypassing the browser interaction.', 'oneclick-wa-order'); ?>
                                    <br>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <table class="form-table">
                    <tbody>
                        <h2 class="section_wa_order"><?php esc_html_e('Single Product Page', 'oneclick-wa-order'); ?></h2>
                        <p>
                            <?php esc_html_e('These configurations will be only effective on single product page.', 'oneclick-wa-order'); ?>
                            <br />
                        </p>
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Display Button?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_enable_single_product" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_enable_single_product'), 'yes'); ?>>
                                <?php esc_html_e('This will display WhatsApp button on single product page', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <!-- Dropdown WA Number -->
                        <tr>
                            <th scope="row">
                                <label>
                                    <?php esc_html_e('WhatsApp Number', 'oneclick-wa-order') ?>
                                </label>
                            </th>
                            <td>
                                <?php wa_order_phone_numbers_dropdown(
                                    array(
                                        'name'      => 'wa_order_selected_wa_number_single_product',
                                        'selected'  => (get_option('wa_order_selected_wa_number_single_product')),
                                    )
                                )
                                ?>
                                <p class="description">
                                    <?php
                                    /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                                    echo wp_kses(
                                        sprintf(
                                            /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                                            __('WhatsApp number is %1$srequired%2$s. Please set it on the %3$sNumbers%4$s tab.', 'oneclick-wa-order'),
                                            '<strong style="color:red;">', // opening <strong> tag with red color style
                                            '</strong>', // closing <strong> tag
                                            '<a href="edit.php?post_type=wa-order-numbers"><strong>', // opening <a> and <strong> tag
                                            '</strong></a>' // closing <a> and <strong> tag
                                        ),
                                        array(
                                            'strong' => array('style' => array()), // Allow strong with style attribute
                                            'a' => array('href' => array(), 'target' => array()) // Allow a tag with href and target attributes
                                        )
                                    );
                                    ?>

                                </p>
                            </td>
                        </tr>
                        <!-- END - Dropdown WA Number -->
                        <!-- Dropdown Button Position -->
                        <tr>
                            <th scope="row">
                                <label for="wa_order_single_product_button_position"><?php echo esc_html_e('Button Position', 'oneclick-wa-order') ?></label>
                            </th>
                            <td>
                                <select name="wa_order_single_product_button_position" id="wa_order_single_product_button_position" class="wa_order-admin-select2">
                                    <option value="after_atc" <?php selected(get_option('wa_order_single_product_button_position'), 'after_atc'); ?>><?php esc_html_e('After Add to Cart Button (Default)', 'oneclick-wa-order'); ?></option>
                                    <option value="under_atc" <?php selected(get_option('wa_order_single_product_button_position'), 'under_atc'); ?>><?php esc_html_e('Under Add to Cart Button', 'oneclick-wa-order'); ?></option>
                                    <option value="after_shortdesc" <?php selected(get_option('wa_order_single_product_button_position'), 'after_shortdesc'); ?>><?php esc_html_e('After Short Description', 'oneclick-wa-order'); ?></option>
                                    <option value="after_single_product_summary" <?php selected(get_option('wa_order_single_product_button_position'), 'after_single_product_summary'); ?>><?php esc_html_e('After Single Product Summary', 'oneclick-wa-order'); ?></option>
                                    <option value="around_share_area" <?php selected(get_option('wa_order_single_product_button_position'), 'around_share_area'); ?>><?php esc_html_e('Around Product Share Area', 'oneclick-wa-order'); ?></option>
                                </select>
                                <p class="description">
                                    <?php esc_html_e('Choose where to put the WhatsApp button on single product page.', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>
                        <!-- END - Dropdown Button Position -->

                        <!-- Force Full-Width -->
                        <tr class="wa_order_price" id="force_fullwidth_container">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_price"><b><?php esc_html_e('Force Full-Width?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_single_force_fullwidth" id="wa_order_single_force_fullwidth" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_single_force_fullwidth'), 'yes'); ?>>
                                <?php esc_html_e('Yes, force the button to be full width.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <!-- END - Force Full-Width -->

                        <tr class="wa_order_message">
                            <th scope="row">
                                <label class="wa_order_message_label" for="message_owo"><b><?php esc_html_e('Custom Message', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <textarea name="wa_order_option_message" class="wa_order_input_areatext" rows="5" placeholder="<?php esc_html_e('e.g. Hello, I want to buy:', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_option_message')); ?></textarea>
                                <p class="description">
                                    <?php
                                    /* translators: 1. example custom message wrapped in <code> tag */
                                    echo sprintf(
                                        /* translators: 1. example custom message wrapped in <code> tag */
                                        esc_html__('Fill this form with a custom message, e.g. %1$sHello, I want to buy:%2$s', 'oneclick-wa-order'),
                                        '<code>', // opening <code> tag
                                        '</code>' // closing <code> tag
                                    );
                                    ?>
                                </p>
                            </td>
                        </tr>
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="text_button"><b><?php esc_html_e('Text on Button', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_text_button" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_text_button')); ?>" placeholder="<?php esc_html_e('e.g. Order via WhatsApp', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>
                        <!-- Show Regular & Sale Price -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_target_label" for="wa_order_option_single_show_regular_sale_prices"><b><?php esc_html_e('Show Regular & Sale Prices?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_single_show_regular_sale_prices" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_single_show_regular_sale_prices'), 'yes'); ?>>
                                <?php esc_html_e('Check to show both regular and sale prices in the WhatsApp message.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_target_label" for="wa_order_target"><b><?php esc_html_e('Open in New Tab?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_target" class="wa_order_input_check" value="_blank" <?php checked(get_option('wa_order_option_target'), '_blank'); ?>>
                                <?php esc_html_e('Yes, Open in New Tab', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <table class="form-table">
                    <tbody>
                        <h2 class="section_wa_order"><?php esc_html_e('Exclusion', 'oneclick-wa-order'); ?></h2>
                        <p>
                            <?php
                            /* translators: 1. opening <a> tag with href to "Display Options" tab, 2. closing </a> tag */
                            echo sprintf(
                                /* translators: 1. opening <a> tag with href to "Display Options" tab */
                                /* translators: 2. closing </a> tag */
                                esc_html__('The following option is only for the output message you\'ll receive on WhatsApp. To hide some elements, please go to the %1$sDisplay Options%2$s tab.', 'oneclick-wa-order'),
                                '<a href="admin.php?page=wa-order&tab=display_option"><strong>', // opening <a> and <strong> tag
                                '</strong></a>' // closing <a> and <strong> tag
                            );
                            ?>
                        </p>

                        <tr class="wa_order_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_price"><b><?php esc_html_e('Exclude Price?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_exclude_price" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_exclude_price'), 'yes'); ?>>
                                <?php esc_html_e('Yes, exclude price in WhatsApp message.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <tr class="wa_order_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_price"><b><?php esc_html_e('Remove Product URL?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_exclude_product_url" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_exclude_product_url'), 'yes'); ?>>
                                <?php esc_html_e('This will remove product URL from WhatsApp message sent from single product page.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <table class="form-table">
                    <tbody>
                        <h2 class="section_wa_order"><?php esc_html_e('Text Translations', 'oneclick-wa-order'); ?></h2>
                        <p><?php esc_html_e('You can translate the following strings which will be included in the sent message. By default, the labels are used in the message. You can translate or change them below accordingly.', 'oneclick-wa-order'); ?></p>
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="text_button"><b><?php esc_html_e('Quantity', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_quantity_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_quantity_label', 'Quantity')); ?>" placeholder="<?php esc_html_e('e.g. Quantity', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="text_button"><b><?php esc_html_e('Price', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_price_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_price_label', 'Price')); ?>" placeholder="<?php esc_html_e('e.g. Price', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="text_button"><b><?php esc_html_e('URL', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_url_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_url_label', 'URL')); ?>" placeholder="<?php esc_html_e('e.g. Link', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="text_button"><b><?php esc_html_e('Total Amount', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_total_amount_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_total_amount_label', 'Total Price')); ?>" placeholder="<?php esc_html_e('e.g. Total Amount', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="text_button"><b><?php esc_html_e('Total Discount', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_total_discount_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_total_discount_label', 'Total Discount')); ?>" placeholder="<?php esc_html_e('e.g. Total Discount', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="text_button"><b><?php esc_html_e('Payment Method', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_payment_method_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_payment_method_label', 'Payment Method')); ?>" placeholder="<?php esc_html_e('e.g. Payment via', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="text_button"><b><?php esc_html_e('Thank you!', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_thank_you_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_thank_you_label', 'Thank you!')); ?>" placeholder="<?php esc_html_e('e.g. Thank you in advance!', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>
                        <!-- Tax Label -->
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label for="wa_order_option_tax_label"><?php echo esc_html__('Tax Label', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_tax_label" id="wa_order_option_tax_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_tax_label')); ?>" placeholder="<?php echo esc_attr__('e.g. Tax', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <?php submit_button(); ?>
            </form>
        <?php } elseif ($active_tab == 'floating_button') { ?>
            <form method="post" action="options.php">
                <?php settings_errors(); ?>
                <?php settings_fields('wa-order-settings-group-floating'); ?>
                <?php do_settings_sections('wa-order-settings-group-floating'); ?>
                <!-- Floating Button -->
                <h2 class="section_wa_order"><?php esc_html_e('Floating Button', 'oneclick-wa-order'); ?></h2>
                <p>
                    <?php esc_html_e('Enable / disable a floating WhatsApp button on your entire pages. You can configure the floating button below.', 'oneclick-wa-order'); ?>
                    <br />
                </p>
                <table class="form-table">
                    <tbody>
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Display Floating Button?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_floating_button" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_floating_button'), 'yes'); ?>>
                                <?php esc_html_e('This will show floating WhatsApp Button', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <!-- Dropdown WA Number -->
                        <tr>
                            <th scope="row">
                                <label>
                                    <?php esc_html_e('WhatsApp Number', 'oneclick-wa-order') ?>
                                </label>
                            </th>
                            <td>
                                <?php wa_order_phone_numbers_dropdown(
                                    array(
                                        'name'      => 'wa_order_selected_wa_number_floating',
                                        'selected'  => (get_option('wa_order_selected_wa_number_floating')),
                                    )
                                )
                                ?>
                                <p class="description">
                                    <?php
                                    /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                                    echo wp_kses(
                                        sprintf(
                                            /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                                            __('WhatsApp number is %1$srequired%2$s. Please set it on the %3$sNumbers%4$s tab.', 'oneclick-wa-order'),
                                            '<strong style="color:red;">', // opening <strong> tag with red color style
                                            '</strong>', // closing <strong> tag
                                            '<a href="edit.php?post_type=wa-order-numbers"><strong>', // opening <a> and <strong> tag
                                            '</strong></a>' // closing <a> and <strong> tag
                                        ),
                                        array(
                                            'strong' => array('style' => array()), // Allow strong with style attribute
                                            'a' => array('href' => array(), 'target' => array()) // Allow a tag with href and target attributes
                                        )
                                    );
                                    ?>
                                </p>
                            </td>
                        </tr>
                        <!-- END- Dropdown WA Number -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label class="wa_order_message_label" for="message_wbw"><b><?php esc_html_e('Custom Message', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <textarea name="wa_order_floating_message" class="wa_order_input_areatext" rows="5" placeholder="<?php esc_html_e('e.g. Hello, I need to know more about', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_floating_message')); ?></textarea>
                                <p class="description">
                                    <?php
                                    /* translators: 1. example message for custom input inside <code> tags */
                                    echo sprintf(
                                        /* translators: 1. example message for custom input */
                                        esc_html__('Enter custom message, e.g. %1$sHello, I need to know more about%2$s', 'oneclick-wa-order'),
                                        '<code>', // opening <code> tag
                                        '</code>' // closing <code> tag
                                    );
                                    ?>
                                </p>
                            </td>
                        </tr>
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_target_label" for="wa_order_target"><b><?php esc_html_e('Show Source Page URL?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_floating_source_url" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_floating_source_url'), 'yes'); ?>>
                                <?php esc_html_e('This will include the URL of the page where the button is clicked in the message.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="wa_order_floating_source_url_label"><b><?php esc_html_e('Source Page URL Label', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_floating_source_url_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_floating_source_url_label')); ?>" placeholder="<?php esc_html_e('From URL:', 'oneclick-wa-order'); ?>">
                                <p class="description">
                                    <?php
                                    /* translators: 1. example label for the source page URL inside <code> tags */
                                    echo sprintf(
                                        /* translators: 1. example label for the source page URL */
                                        esc_html__('Add a label for the source page URL. %1$se.g. From URL:%2$s', 'oneclick-wa-order'),
                                        '<code>', // opening <code> tag
                                        '</code>' // closing <code> tag
                                    );
                                    ?>
                                </p>
                            </td>
                        </tr>
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_target_label" for="wa_order_target"><b><?php esc_html_e('Open in New Tab?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_floating_target" class="wa_order_input_check" value="_blank" <?php checked(get_option('wa_order_floating_target'), '_blank'); ?>>
                                <?php esc_html_e('Yes, Open in New Tab', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- Floating Button Display Options -->
                <table class="form-table">
                    <tbody>
                        <hr />
                        <h2 class="section_wa_order"><?php esc_html_e('Display Options', 'oneclick-wa-order'); ?></h2>
                        <p>
                            <?php esc_html_e('Configure where and how you\'d like the floating button to be displayed..', 'oneclick-wa-order'); ?>
                            <br />
                        </p>
                        <hr />
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label>
                                    <?php esc_html_e('Floating Button Position', 'oneclick-wa-order') ?>
                                </label>
                            </th>
                            <td>
                                <input type="radio" name="wa_order_floating_button_position" value="left" <?php checked('left', get_option('wa_order_floating_button_position'), true); ?>> <?php esc_html_e('Left', 'oneclick-wa-order'); ?>
                                <input type="radio" name="wa_order_floating_button_position" value="right" <?php checked('right', get_option('wa_order_floating_button_position'), true); ?>> <?php esc_html_e('Right', 'oneclick-wa-order'); ?>
                                <?php esc_html_e('Right', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn">
                                    <strong><?php esc_html_e('Display Tooltip?', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_floating_tooltip_enable" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_floating_tooltip_enable'), 'yes'); ?>>
                                <?php esc_html_e('This will show a custom tooltip', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="floating_tooltip">
                                    <strong><?php esc_html_e('Button Tooltip', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_floating_tooltip" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_floating_tooltip')); ?>" placeholder="<?php esc_html_e('e.g. Let\'s Chat', 'oneclick-wa-order'); ?>">
                                <p class="description">
                                    <?php esc_html_e('Use this to greet your customers.', 'oneclick-wa-order'); ?>
                                    <br>
                                    <?php esc_html_e('The tooltip container size is very limited so make sure to make it as short as possible.', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_target_label" for="wa_order_target">
                                    <strong><?php esc_html_e('Hide Floating Button on Mobile?', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_floating_hide_mobile" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_floating_hide_mobile'), 'yes'); ?>>
                                <?php esc_html_e('This will hide Floating Button on Mobile.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_target_label" for="wa_order_target">
                                    <strong><?php esc_html_e('Hide Floating Button on Desktop?', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_floating_hide_desktop" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_floating_hide_desktop'), 'yes'); ?>>
                                <?php esc_html_e('This will hide Floating Button on Desktop.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <!-- Hide floating button on all posts -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_target_label" for="wa_order_target">
                                    <strong><?php esc_html_e('Hide Floating Button on All Single Posts?', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_floating_hide_all_single_posts" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_floating_hide_all_single_posts'), 'yes'); ?>>
                                <?php esc_html_e('This will hide Floating Button on all single posts.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <!-- END - Hide floating button on all posts -->
                        <!-- Hide floating button on all pages -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_target_label" for="wa_order_target">
                                    <strong><?php esc_html_e('Hide Floating Button on All Single Posts?', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_floating_hide_all_single_pages" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_floating_hide_all_single_pages'), 'yes'); ?>>
                                <?php esc_html_e('This will hide Floating Button on all single pages.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <!-- END - Hide floating button on all pages -->
                        <!-- Multiple posts selection -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn">
                                    <strong><?php esc_html_e('Hide Floating Button on Selected Post(s)', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <?php wp_enqueue_script('wa_order_js_select2'); ?>
                                <?php wp_enqueue_script('wa_order_select2_helper'); ?>
                                <?php wp_enqueue_style('wa_order_selet2_style'); ?>
                                <select multiple="multiple" name="wa_order_floating_hide_specific_posts[]" class="postform octo-post-filter" style="width: 50%;">
                                    <?php
                                    global $post;
                                    $option = get_option('wa_order_floating_hide_specific_posts');
                                    $option_array = (array) $option;
                                    $args = array(
                                        'post_type'        => 'post',
                                        'orderby'          => 'title',
                                        'order'            => 'ASC',
                                        'post_status'      => 'publish',
                                        'posts_per_page'   => -1
                                    );
                                    $posts = get_posts($args);
                                    foreach ($posts as $post) {
                                        $selected = in_array($post->ID, $option_array) ? ' selected="selected" ' : '';
                                    ?>
                                        <option value="<?php echo esc_attr($post->ID); ?>" <?php echo esc_attr($selected); ?>>
                                            <?php echo esc_html(ucwords($post->post_title)); ?>
                                        </option>
                                    <?php
                                    } //endforeach
                                    ?>
                                </select>
                                <p><?php esc_html_e('You can hide the floating button on the selected post(s).', 'oneclick-wa-order'); ?></p><br>
                            </td>
                        </tr>
                        <!-- END - Multiple posts selection -->
                        <!-- Multiple pages selection -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn">
                                    <strong><?php esc_html_e('Hide Floating Button on Selected Page(s)', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <select multiple="multiple" name="wa_order_floating_hide_specific_pages[]" class="postform octo-page-filter" style="width: 50%;">
                                    <?php
                                    global $post;
                                    $option = get_option('wa_order_floating_hide_specific_pages');
                                    $option_array = (array) $option;
                                    $args = array(
                                        'post_type'        => 'page',
                                        'orderby'          => 'title',
                                        'order'            => 'ASC',
                                        'post_status'      => 'publish',
                                        'posts_per_page'   => -1
                                    );
                                    $pages = get_posts($args);
                                    foreach ($pages as $page) {
                                        $selected = in_array($page->ID, $option_array) ? ' selected="selected" ' : '';
                                    ?>
                                        <option value="<?php echo esc_attr($page->ID); ?>" <?php echo esc_attr($selected); ?>>
                                            <?php echo esc_html(ucwords($page->post_title)); ?>
                                        </option>
                                    <?php
                                    } //endforeach
                                    ?>
                                </select>
                                <p><?php esc_html_e('You can hide the floating button on the selected page(s).', 'oneclick-wa-order'); ?></p><br>
                            </td>
                        </tr>
                        <!-- END - Multiple pages selection -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn">
                                    <strong><?php esc_html_e('Hide Floating Button on Products in Categories', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <select multiple="multiple" name="wa_order_floating_hide_product_cats[]" class="postform octo-category-filter" style="width: 50%;">
                                    <?php
                                    $option = get_option('wa_order_floating_hide_product_cats');
                                    $option_array = (array) $option;
                                    $args = array(
                                        'taxonomy' => 'product_cat',
                                        'orderby'  => 'name'
                                    );
                                    $categories = get_categories($args);
                                    foreach ($categories as $category) {
                                        $selected = in_array($category->term_id, $option_array) ? ' selected="selected" ' : '';
                                    ?>
                                        <option value="<?php echo esc_attr($category->term_id); ?>" <?php echo esc_attr($selected); ?>>
                                            <?php echo esc_html(ucwords($category->cat_name)) . ' (' . esc_html($category->category_count) . ')'; ?>
                                        </option>
                                    <?php
                                    } //endforeach
                                    ?>
                                </select>
                                <p><?php esc_html_e('You can hide the floating button on products in the selected categories.', 'oneclick-wa-order'); ?></p>
                                <br>
                            </td>
                        </tr>
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide Floating Button on Products in Tags', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <select multiple="multiple" name="wa_order_floating_hide_product_tags[]" class="postform octo-category-filter" style="width: 50%;">
                                    <?php
                                    $option = get_option('wa_order_floating_hide_product_tags');
                                    $option_array = (array) $option;
                                    $args = array(
                                        'taxonomy' => 'product_tag',
                                        'orderby'  => 'name'
                                    );
                                    $tag_query = get_terms($args);
                                    foreach ($tag_query as $term) {
                                        $selected = in_array($term->term_id, $option_array) ? ' selected="selected" ' : '';
                                    ?>
                                        <option value="<?php echo esc_attr($term->term_id); ?>" <?php echo esc_attr($selected); ?>>
                                            <?php echo esc_html(ucwords($term->name)) . ' (' . esc_html($term->count) . ')'; ?>
                                        </option>
                                    <?php
                                    } //endforeach
                                    ?>
                                </select>
                                <p>
                                    <?php esc_html_e('You can hide the floating button on products in the selected tags.', 'oneclick-wa-order');
                                    ?>
                                    <br />
                                </p>
                                <br>
                            </td>
                        </tr>
                        <!-- Floating Button Margin -->
                        <tr class="wa_order_remove_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_floating_button_margin_top">
                                    <strong><?php esc_html_e('Button Margin', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <ul class="boxes-control">
                                    <li class="box-control">
                                        <input id="wa_order_floating_button_margin_top" type="number" name="wa_order_floating_button_margin_top" value="<?php echo esc_attr(get_option('wa_order_floating_button_margin_top')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Top', 'oneclick-wa-order'); ?>
                                            <br />
                                        </p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_floating_button_margin_right" type="number" name="wa_order_floating_button_margin_right" value="<?php echo esc_attr(get_option('wa_order_floating_button_margin_right')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Right', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_floating_button_margin_bottom" type="number" name="wa_order_floating_button_margin_bottom" value="<?php echo esc_attr(get_option('wa_order_floating_button_margin_bottom')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Bottom', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_floating_button_margin_left" type="number" name="wa_order_floating_button_margin_left" value="<?php echo esc_attr(get_option('wa_order_floating_button_margin_left')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Left', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <!-- END - Floating Button Margin -->
                        <!-- Floating Button Padding -->
                        <tr class="wa_order_remove_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_floating_button_padding_top">
                                    <strong><?php esc_html_e('Button Padding', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <ul class="boxes-control">
                                    <li class="box-control">
                                        <input id="wa_order_floating_button_padding_top" type="number" name="wa_order_floating_button_padding_top" value="<?php echo esc_attr(get_option('wa_order_floating_button_padding_top')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Top', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_floating_button_padding_right" type="number" name="wa_order_floating_button_padding_right" value="<?php echo esc_attr(get_option('wa_order_floating_button_padding_right')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Right', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_floating_button_padding_bottom" type="number" name="wa_order_floating_button_padding_bottom" value="<?php echo esc_attr(get_option('wa_order_floating_button_padding_bottom')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Bottom', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_floating_button_padding_left" type="number" name="wa_order_floating_button_padding_left" value="<?php echo esc_attr(get_option('wa_order_floating_button_padding_left')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Left', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <!-- END - Floating Button Padding -->
                        <!-- Floating Button Icon Margin -->
                        <tr class="wa_order_remove_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_floating_button_icon_margin_top">
                                    <strong><?php esc_html_e('Button Icon Margin', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <ul class="boxes-control">
                                    <li class="box-control">
                                        <input id="wa_order_floating_button_icon_margin_top" type="number" name="wa_order_floating_button_icon_margin_top" value="<?php echo esc_attr(get_option('wa_order_floating_button_icon_margin_top')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Top', 'oneclick-wa-order'); ?>
                                            <br />
                                        </p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_floating_button_icon_margin_right" type="number" name="wa_order_floating_button_icon_margin_right" value="<?php echo esc_attr(get_option('wa_order_floating_button_icon_margin_right')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Right', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_floating_button_icon_margin_bottom" type="number" name="wa_order_floating_button_icon_margin_bottom" value="<?php echo esc_attr(get_option('wa_order_floating_button_icon_margin_bottom')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Bottom', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_floating_button_icon_margin_left" type="number" name="wa_order_floating_button_icon_margin_left" value="<?php echo esc_attr(get_option('wa_order_floating_button_icon_margin_left')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Left', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <!-- END - Floating Button Icon Margin -->
                        <!-- Floating Button Icon Padding -->
                        <tr class="wa_order_remove_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_floating_button_icon_padding_top">
                                    <strong><?php esc_html_e('Button Icon Padding', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <ul class="boxes-control">
                                    <li class="box-control">
                                        <input id="wa_order_floating_button_icon_padding_top" type="number" name="wa_order_floating_button_icon_padding_top" value="<?php echo esc_attr(get_option('wa_order_floating_button_icon_padding_top')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Top', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_floating_button_icon_padding_right" type="number" name="wa_order_floating_button_icon_padding_right" value="<?php echo esc_attr(get_option('wa_order_floating_button_icon_padding_right')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Right', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_floating_button_icon_padding_bottom" type="number" name="wa_order_floating_button_icon_padding_bottom" value="<?php echo esc_attr(get_option('wa_order_floating_button_icon_padding_bottom')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Bottom', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_floating_button_icon_padding_left" type="number" name="wa_order_floating_button_icon_padding_left" value="<?php echo esc_attr(get_option('wa_order_floating_button_icon_padding_left')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Left', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <!-- END - Floating Button Icon Padding -->
                    </tbody>
                </table>
                <!-- END - Floating Button Display Options -->
                <hr>
                <?php submit_button(); ?>
            </form>
        <?php } elseif ($active_tab == 'display_option') { ?>
            <form method="post" action="options.php">
                <?php settings_errors(); ?>
                <?php settings_fields('wa-order-settings-group-display-options'); ?>
                <?php do_settings_sections('wa-order-settings-group-display-options'); ?>
                <?php wp_enqueue_script('wa_order_js_select2'); ?>
                <?php wp_enqueue_script('wa_order_select2_helper'); ?>
                <?php wp_enqueue_style('wp-color-picker'); ?>
                <?php wp_enqueue_style('wa_order_selet2_style'); ?>
                <?php wp_enqueue_script('wp-color-picker-alpha'); ?>
                <?php wp_enqueue_script('wp-color-picker-init'); ?>
                <?php wp_enqueue_script('wa_order_js_admin'); ?>
                <h2 class="section_wa_order"><?php esc_html_e('Display Options', 'oneclick-wa-order'); ?></h2>
                <p>
                    <?php esc_html_e('Here, you can configure some options for hiding elements to convert customers phone number into clickable WhatsApp link.', 'oneclick-wa-order'); ?>
                    <br />
                </p>
                <hr>
                <!-- Button Colors - Display Options -->
                <table class="form-table">
                    <tbody>
                        <h3 class="section_wa_order"><?php esc_html_e('Button Colors', 'oneclick-wa-order'); ?></h3>
                        <p><?php esc_html_e('Customize the WhatsApp button appearance however you like.', 'oneclick-wa-order'); ?></p>
                        <!-- Button Background Color -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Background Color', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <?php
                                $bg = get_option('wa_order_bg_color');
                                if (empty($bg)) {
                                    $bg = 'rgba(37, 211, 102, 1)';
                                }
                                ?>
                                <input type="text" class="color-picker" data-alpha-enabled="true" data-default-color="rgba(37, 211, 102, 1)" name="wa_order_bg_color" value="<?php echo esc_attr($bg); ?>" />
                            </td>
                        </tr>
                        <!-- Button Background Hover Color -->
                        <tr class="wa_order_option_remove_quantity">
                            <th scope="row">
                                <label class="wa_order_option_remove_quantity" for="wa_order_option_remove_quantity"><b><?php esc_html_e('Background Hover Color', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <?php
                                $bg_hover = get_option('wa_order_bg_hover_color');
                                if (empty($bg_hover)) {
                                    $bg_hover = 'rgba(37, 211, 102, 1)';
                                }
                                ?>
                                <input type="text" class="color-picker" data-alpha-enabled="true" data-default-color="rgba(37, 211, 102, 1)" name="wa_order_bg_hover_color" value="<?php echo esc_attr($bg_hover); ?>" />
                            </td>
                        </tr>
                        <!-- Button Text Color -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Text Color', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <?php
                                $txt = get_option('wa_order_txt_color');
                                if (empty($txt)) {
                                    $txt = 'rgba(255, 255, 255, 1)';
                                }
                                ?>
                                <input type="text" class="color-picker" data-alpha-enabled="true" data-default-color="rgba(255, 255, 255, 1)" name="wa_order_txt_color" value="<?php echo esc_attr($txt); ?>" />
                            </td>
                        </tr>
                        <!-- Button Text Hover Color -->
                        <tr class="wa_order_remove_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_remove_price"><b><?php esc_html_e('Text Hover Color', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <?php
                                $txt_hover = get_option('wa_order_txt_hover_color');
                                if (empty($txt_hover)) {
                                    $txt_hover = 'rgba(255, 255, 255, 1)';
                                }
                                ?>
                                <input type="text" class="color-picker" data-alpha-enabled="true" data-default-color="rgba(255, 255, 255, 1)" name="wa_order_txt_hover_color" value="<?php echo esc_attr($txt_hover); ?>" />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <!-- Button Box Shadow -->
                <table class="form-table">
                    <tbody>
                        <h3 class="section_wa_order"><?php esc_html_e('Button Box Shadow Color', 'oneclick-wa-order'); ?></h3>
                        <p><?php esc_html_e('Customize the box shadow color for the WhatsApp button.', 'oneclick-wa-order'); ?></p>
                        <!-- Button Box Shadow Settings -->
                        <?php
                        $bshdw_hz = get_option('wa_order_bshdw_horizontal', '0');
                        $bshdw_v = get_option('wa_order_bshdw_vertical', '4');
                        $bshdw_b = get_option('wa_order_bshdw_blur', '7');
                        $bshdw_s = get_option('wa_order_bshdw_spread', '0');
                        $bshdw_color = get_option('wa_order_btn_box_shdw', 'rgba(0,0,0,0.25)');
                        $bshdw_h_h = get_option('wa_order_bshdw_horizontal_hover', '0');
                        $bshdw_v_h = get_option('wa_order_bshdw_vertical_hover', '4');
                        $bshdw_b_h = get_option('wa_order_bshdw_blur_hover', '7');
                        $bshdw_s_h = get_option('wa_order_bshdw_spread_hover', '0');
                        $bshdw_color_hover = get_option('wa_order_btn_box_shdw_hover', 'rgba(0,0,0,0.25)');
                        ?>
                        <!-- Normal State Box Shadow -->
                        <tr class="wa_order_remove_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_remove_price"><strong><?php esc_html_e('Box Shadow', 'oneclick-wa-order'); ?></strong></label>
                            </th>
                            <td>
                                <ul class="boxes-control">
                                    <li class="box-control">
                                        <input id="wa_order_bshdw_horizontal" type="number" name="wa_order_bshdw_horizontal" value="<?php echo esc_attr($bshdw_hz); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Horizontal', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_bshdw_vertical" type="number" name="wa_order_bshdw_vertical" value="<?php echo esc_attr($bshdw_v); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Vertical', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_bshdw_blur" type="number" name="wa_order_bshdw_blur" value="<?php echo esc_attr($bshdw_b); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Blur', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_bshdw_spread" type="number" name="wa_order_bshdw_spread" value="<?php echo esc_attr($bshdw_s); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Spread', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-color-control">
                                        <input id="wa_order_btn_box_shdw" type="text" class="color-picker" data-alpha-enabled="true" name="wa_order_btn_box_shdw" value="<?php echo esc_attr($bshdw_color); ?>" />
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <!-- Hover State Box Shadow -->
                        <tr class="wa_order_remove_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_remove_price"><strong><?php esc_html_e('Box Shadow Hover', 'oneclick-wa-order'); ?></strong></label>
                            </th>
                            <td>
                                <ul class="boxes-control">
                                    <li class="box-control">
                                        <input id="wa_order_bshdw_horizontal_hover" type="number" name="wa_order_bshdw_horizontal_hover" value="<?php echo esc_attr($bshdw_h_h); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Horizontal', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_bshdw_vertical_hover" type="number" name="wa_order_bshdw_vertical_hover" value="<?php echo esc_attr($bshdw_v_h); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Vertical', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_bshdw_blur_hover" type="number" name="wa_order_bshdw_blur_hover" value="<?php echo esc_attr($bshdw_b_h); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Blur', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_bshdw_spread_hover" type="number" name="wa_order_bshdw_spread_hover" value="<?php echo esc_attr($bshdw_s_h); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Spread', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-color-control">
                                        <input id="wa_order_btn_box_shdw_hover" type="text" class="color-picker" data-alpha-enabled="true" name="wa_order_btn_box_shdw_hover" value="<?php echo esc_attr($bshdw_color_hover); ?>" />
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <!-- Box Shadow Position -->
                        <tr class="wa_order_remove_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_remove_price"><b><?php esc_html_e('Position', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="radio" name="wa_order_bshdw_position" value="outline" <?php checked('outline', get_option('wa_order_bshdw_position'), true); ?>>
                                <?php esc_html_e('Outline', 'oneclick-wa-order'); ?>
                                <input type="radio" name="wa_order_bshdw_position" value="inset" <?php checked('inset', get_option('wa_order_bshdw_position'), true); ?>>
                                <?php esc_html_e('Inset', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                        <!-- Box Shadow Hover Position -->
                        <tr class="wa_order_remove_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_remove_price"><b><?php esc_html_e('Hover Position', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="radio" name="wa_order_bshdw_position_hover" value="outline" <?php checked('outline', get_option('wa_order_bshdw_position_hover'), true); ?>>
                                <?php esc_html_e('Outline', 'oneclick-wa-order'); ?>
                                <input type="radio" name="wa_order_bshdw_position_hover" value="inset" <?php checked('inset', get_option('wa_order_bshdw_position_hover'), true); ?>>
                                <?php esc_html_e('Inset', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- END of Button Customizations - Display Options -->
                <hr>
                <!-- Single Product Page Display Options -->
                <table class="form-table">
                    <tbody>
                        <h3 class="section_wa_order"><?php esc_html_e('Single Product Page', 'oneclick-wa-order'); ?></h3>
                        <p><?php esc_html_e('The following options will be only effective on single product page.', 'oneclick-wa-order'); ?></p>
                        <!-- Hide Button on Desktop -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Hide Button on Desktop?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_remove_btn" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_remove_btn'), 'yes'); ?>>
                                <?php esc_html_e('This will hide WhatsApp Button on Desktop.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <!-- Hide Button on Mobile -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Hide Button on Mobile?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_remove_btn_mobile" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_remove_btn_mobile'), 'yes'); ?>>
                                <?php esc_html_e('This will hide WhatsApp Button on Mobile.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <tr class="wa_order_option_remove_quantity">
                            <th scope="row">
                                <label class="wa_order_option_remove_quantity" for="wa_order_option_remove_quantity"><b><?php esc_html_e('Hide Product Quantity Option?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_remove_quantity" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_remove_quantity'), 'yes'); ?>>
                                <?php esc_html_e('This will hide product quantity option field.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <tr class="wa_order_remove_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_remove_price"><b><?php esc_html_e('Hide Price in Product Page?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_remove_price" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_remove_price'), 'yes'); ?>>
                                <?php esc_html_e('This will hide price in Product page.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide Add to Cart button?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_remove_cart_btn" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_remove_cart_btn'), 'yes'); ?>>
                                <?php esc_html_e('This will hide Add to Cart button.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide WA Button on Products in Categories', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <select multiple="multiple" name="wa_order_option_exlude_single_product_cats[]" class="postform octo-category-filter" style="width: 50%;">
                                    <?php
                                    $option = get_option('wa_order_option_exlude_single_product_cats');
                                    $option_array = (array) $option;
                                    $args = array('taxonomy' => 'product_cat', 'orderby' => 'name');
                                    $categories = get_categories($args);
                                    foreach ($categories as $category) {
                                        $selected = in_array($category->term_id, $option_array) ? ' selected="selected" ' : ''; ?>
                                        <option value="<?php echo esc_attr($category->term_id); ?>" <?php echo esc_attr($selected); ?>>
                                            <?php echo esc_html(ucwords($category->cat_name)) . ' (' . esc_html($category->category_count) . ')'; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <p>
                                    <?php esc_html_e('You can hide the WhatsApp button on products in the selected categories.', 'oneclick-wa-order'); ?>
                                    <br />
                                </p>
                                <br>
                            </td>
                        </tr>
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide WA Button on Products in Tags', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <select multiple="multiple" name="wa_order_option_exlude_single_product_tags[]" class="postform octo-category-filter" style="width: 50%;">
                                    <?php
                                    $option = get_option('wa_order_option_exlude_single_product_tags');
                                    $option_array = (array) $option;
                                    $tags = get_terms(['taxonomy' => 'product_tag', 'orderby' => 'name']);
                                    foreach ($tags as $tag) {
                                        $selected = in_array($tag->term_id, $option_array) ? ' selected="selected" ' : '';
                                        echo '<option value="' . esc_attr($tag->term_id) . '"' . esc_attr($selected) . '>';
                                        echo esc_html(ucwords($tag->name)) . ' (' . esc_html($tag->count) . ')';
                                        echo '</option>';
                                    }
                                    ?>
                                </select>
                                <p>
                                    <?php esc_html_e('You can hide the WhatsApp button on products in the selected tags.', 'oneclick-wa-order');
                                    ?>
                                    <br />
                                </p>
                                <br>
                            </td>
                        </tr>
                        <!-- Button Margin -->
                        <tr class="wa_order_remove_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_remove_price">
                                    <strong><?php esc_html_e('Button Margin', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <ul class="boxes-control">
                                    <li class="box-control">
                                        <input id="wa_order_single_button_margin_top" type="number" name="wa_order_single_button_margin_top" value="<?php echo esc_attr(get_option('wa_order_single_button_margin_top')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Top', 'oneclick-wa-order'); ?>
                                            <br />
                                        </p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_single_button_margin_right" type="number" name="wa_order_single_button_margin_right" value="<?php echo esc_attr(get_option('wa_order_single_button_margin_right')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Right', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_single_button_margin_bottom" type="number" name="wa_order_single_button_margin_bottom" value="<?php echo esc_attr(get_option('wa_order_single_button_margin_bottom')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Bottom', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_single_button_margin_left" type="number" name="wa_order_single_button_margin_left" value="<?php echo esc_attr(get_option('wa_order_single_button_margin_left')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Left', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <!-- END - Button Margin -->
                        <!-- Button Padding -->
                        <tr class="wa_order_remove_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_remove_price">
                                    <strong><?php esc_html_e('Button Padding', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <ul class="boxes-control">
                                    <li class="box-control">
                                        <input id="wa_order_single_button_padding_top" type="number" name="wa_order_single_button_padding_top" value="<?php echo esc_attr(get_option('wa_order_single_button_padding_top')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Top', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_single_button_padding_right" type="number" name="wa_order_single_button_padding_right" value="<?php echo esc_attr(get_option('wa_order_single_button_padding_right')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Right', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_single_button_padding_bottom" type="number" name="wa_order_single_button_padding_bottom" value="<?php echo esc_attr(get_option('wa_order_single_button_padding_bottom')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Bottom', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_single_button_padding_left" type="number" name="wa_order_single_button_padding_left" value="<?php echo esc_attr(get_option('wa_order_single_button_padding_left')); ?>" placeholder="">
                                        <p class="control-label"><?php esc_html_e('Left', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <!-- END - Button Padding -->
                    </tbody>
                </table>
                <!-- END of Single Product Page Display Options -->
                <hr>
                <!-- Shop Loop Display Options -->
                <table class="form-table">
                    <tbody>
                        <h2 class="section_wa_order"><?php esc_html_e('Shop Loop Page', 'oneclick-wa-order'); ?></h2>
                        <p><?php esc_html_e('The following options will be only effective on shop loop page.', 'oneclick-wa-order'); ?></p>
                        <!-- Hide Button on Desktop -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide Button on Desktop?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_display_option_shop_loop_hide_desktop" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_display_option_shop_loop_hide_desktop'), 'yes'); ?>>
                                <?php esc_html_e('This will hide WhatsApp Button on Desktop.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                        <!-- Hide Button on Mobile -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide Button on Mobile?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_display_option_shop_loop_hide_mobile" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_display_option_shop_loop_hide_mobile'), 'yes'); ?>>
                                <?php esc_html_e('This will hide WhatsApp Button on Mobile.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                        <!-- Select Categories -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide WA Button Under Products in Categories', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <select multiple="multiple" name="wa_order_option_exlude_shop_product_cats[]" class="postform octo-category-filter" style="width: 50%;">
                                    <?php
                                    $option = get_option('wa_order_option_exlude_shop_product_cats');
                                    $option_array = (array) $option;
                                    $args = array(
                                        'taxonomy' => 'product_cat',
                                        'orderby'  => 'name'
                                    );
                                    $categories = get_categories($args);
                                    foreach ($categories as $category) {
                                        $selected = in_array($category->term_id, $option_array) ? ' selected="selected" ' : '';
                                    ?>
                                        <option value="<?php echo esc_attr($category->term_id); ?>" <?php echo esc_attr($selected); ?>>
                                            <?php echo esc_html(ucwords($category->cat_name)) . ' (' . esc_html($category->category_count) . ')'; ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <p><?php esc_html_e('You can hide the WhatsApp button under products in the selected categories.', 'oneclick-wa-order'); ?></p>
                            </td>
                        </tr>
                        <!-- Archive Pages Options -->
                        <tr class="wa_order_remove_add_btn">
                            <!-- For Categories -->
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Also Hide on Category Archive Page(s)?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_exlude_shop_product_cats_archive" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_exlude_shop_product_cats_archive'), 'yes'); ?>>
                                <?php esc_html_e('This will hide WhatsApp Button on the selected category archive page(s).', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                        <!-- Select Tags -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide WA Button Under Products in Tags', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <select multiple="multiple" name="wa_order_option_exlude_shop_product_tags[]" class="postform octo-category-filter" style="width: 50%;">
                                    <?php
                                    $option = get_option('wa_order_option_exlude_shop_product_tags');
                                    $option_array = (array) $option;
                                    $args = array(
                                        'taxonomy' => 'product_tag',
                                        'orderby'  => 'name'
                                    );
                                    $tag_query = get_terms($args);
                                    foreach ($tag_query as $term) {
                                        $selected = in_array($term->term_id, $option_array) ? ' selected="selected" ' : '';
                                    ?>
                                        <option value="<?php echo esc_attr($term->term_id); ?>" <?php echo esc_attr($selected); ?>>
                                            <?php echo esc_html(ucwords($term->name)) . ' (' . esc_html($term->count) . ')'; ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <p><?php esc_html_e('You can hide the WhatsApp button under products in the selected tags.', 'oneclick-wa-order'); ?></p>
                            </td>
                        </tr>
                        <!-- For Tags -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Also Hide on Tag Archive Page(s)?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_exlude_shop_product_tags_archive" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_exlude_shop_product_tags_archive'), 'yes'); ?>>
                                <?php esc_html_e('This will hide WhatsApp Button on the selected tag archive page(s).', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- END of Shop Loop Display Options -->
                <hr>
                <!-- Cart Display Options -->
                <table class="form-table">
                    <tbody>
                        <h2 class="section_wa_order"><?php esc_html_e('Cart Page', 'oneclick-wa-order'); ?></h2>
                        <p><?php esc_html_e('The following options will be only effective on cart page.', 'oneclick-wa-order'); ?></p>

                        <!-- Hide Button on Desktop -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide Button on Desktop?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_display_option_cart_hide_desktop" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_display_option_cart_hide_desktop'), 'yes'); ?>>
                                <?php esc_html_e('This will hide WhatsApp Button on Desktop.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Hide Button on Mobile -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide Button on Mobile?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_display_option_cart_hide_mobile" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_display_option_cart_hide_mobile'), 'yes'); ?>>
                                <?php esc_html_e('This will hide WhatsApp Button on Mobile.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- END of Cart Display Options -->
                <hr>
                <!-- Checkout / Thank You Page Display Options -->
                <table class="form-table">
                    <tbody>
                        <h2 class="section_wa_order"><?php esc_html_e('Thank You Page', 'oneclick-wa-order'); ?></h2>
                        <p><?php esc_html_e('The following options will be only effective on thank you page.', 'oneclick-wa-order'); ?></p>

                        <!-- Hide Button on Desktop -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide Button on Desktop?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_display_option_checkout_hide_desktop" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_display_option_checkout_hide_desktop'), 'yes'); ?>>
                                <?php esc_html_e('This will hide WhatsApp Button on Desktop.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Hide Button on Mobile -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide Button on Mobile?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_display_option_checkout_hide_mobile" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_display_option_checkout_hide_mobile'), 'yes'); ?>>
                                <?php esc_html_e('This will hide WhatsApp Button on Mobile.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- END of Checkout / Thank You Page Display Options -->
                <hr>
                <!-- Miscellaneous Display Options -->
                <table class="form-table">
                    <tbody>
                        <h2 class="section_wa_order"><?php esc_html_e('Miscellaneous', 'oneclick-wa-order'); ?></h2>
                        <p><?php esc_html_e('An additional option you might need.', 'oneclick-wa-order'); ?></p>

                        <!-- Convert Phone Number into WhatsApp in Order Details -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_convert_phone"><b><?php esc_html_e('Convert Phone Number into WhatsApp in Order Details?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_convert_phone_order_details" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_convert_phone_order_details'), 'yes'); ?>>
                                <?php esc_html_e('This will convert phone number link into WhatsApp chat link.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Custom WhatsApp Message in Backend Order Details -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label class="wa_order_message_label" for="message_wbw"><b><?php esc_html_e('Custom Message', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <textarea name="wa_order_option_custom_message_backend_order_details" class="wa_order_input_areatext" rows="5" placeholder="<?php esc_html_e('e.g. Hello, I\'d like to follow up on your order.', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_option_custom_message_backend_order_details')); ?></textarea>
                                <p class="description">
                                    <?php
                                    /* translators: 1. example custom message inside <code> tags */
                                    echo sprintf(
                                        /* translators: 1. example custom message */
                                        esc_html__('Enter custom message, %1$se.g. Hello, I\'d like to follow up on your order.%2$s', 'oneclick-wa-order'),
                                        '<code>', // opening <code> tag
                                        '</code>' // closing <code> tag
                                    );
                                    ?>
                                </p>
                            </td>
                        </tr>

                    </tbody>
                </table>
                <!-- END of Miscellaneous Display Options -->
                <hr>
                <?php submit_button(); ?>
            </form>
        <?php } elseif ($active_tab == 'shop_page') { ?>
            <!-- Custom Shortcode -->
            <form method="post" action="options.php">
                <?php settings_errors(); ?>
                <?php settings_fields('wa-order-settings-group-shop-loop'); ?>
                <?php do_settings_sections('wa-order-settings-group-shop-loop'); ?>
                <h2 class="section_wa_order"><?php esc_html_e('WhatsApp Button on Shop Page', 'oneclick-wa-order'); ?></h2>
                <p>
                    <?php
                    /* translators: 1. opening <strong> tag for "Shop", 2. closing </strong> tag, 3. opening <strong> tag for "Add to Cart", 4. closing </strong> tag */
                    echo sprintf(
                        /* translators: 1. opening <strong> tag for "Shop" */
                        /* translators: 2. closing </strong> tag */
                        /* translators: 3. opening <strong> tag for "Add to Cart" */
                        /* translators: 4. closing </strong> tag */
                        esc_html__('Add custom WhatsApp button on %1$sShop%2$s page or product loop page right under / besides the %3$sAdd to Cart%4$s button.', 'oneclick-wa-order'),
                        '<strong>', // opening <strong> tag for "Shop"
                        '</strong>', // closing <strong> tag for "Shop"
                        '<strong>', // opening <strong> tag for "Add to Cart"
                        '</strong>' // closing <strong> tag for "Add to Cart"
                    );
                    ?>
                    <br />
                </p>
                <table class="form-table">
                    <tbody>
                        <h2 class="section_wa_order"><?php esc_html_e('Shop Loop Page', 'oneclick-wa-order'); ?></h2>
                        <p><?php esc_html_e('The following options will be only effective on shop loop page.', 'oneclick-wa-order'); ?></p>

                        <!-- Display Button on Shop Page -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Display button on Shop page?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_enable_button_shop_loop" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_enable_button_shop_loop'), 'yes'); ?>>
                                <?php esc_html_e('This will display WhatsApp button on Shop page', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- WhatsApp Number Dropdown -->
                        <tr>
                            <th scope="row">
                                <label><?php esc_html_e('WhatsApp Number', 'oneclick-wa-order') ?></label>
                            </th>
                            <td>
                                <?php wa_order_phone_numbers_dropdown(
                                    array(
                                        'name'      => 'wa_order_selected_wa_number_shop',
                                        'selected'  => get_option('wa_order_selected_wa_number_shop'),
                                    )
                                ) ?>
                                <p class="description">
                                    <?php
                                    /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                                    echo wp_kses(
                                        sprintf(
                                            /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                                            __('WhatsApp number is %1$srequired%2$s. Please set it on the %3$sNumbers%4$s tab.', 'oneclick-wa-order'),
                                            '<strong style="color:red;">', // opening <strong> tag with red color style
                                            '</strong>', // closing <strong> tag
                                            '<a href="edit.php?post_type=wa-order-numbers"><strong>', // opening <a> and <strong> tag
                                            '</strong></a>' // closing <a> and <strong> tag
                                        ),
                                        array(
                                            'strong' => array('style' => array()), // Allow strong with style attribute
                                            'a' => array('href' => array(), 'target' => array()) // Allow a tag with href and target attributes
                                        )
                                    );
                                    ?>

                                </p>
                            </td>
                        </tr>

                        <!-- Hide Add to Cart Button -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Hide Add to Cart button?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_hide_atc_shop_loop" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_hide_atc_shop_loop'), 'yes'); ?>>
                                <?php
                                /* translators: 1. <code> tag for "Add to Cart" */
                                echo sprintf(
                                    /* translators: 1. opening <code> tag for "Add to Cart" */
                                    /* translators: 2. closing </code> tag */
                                    esc_html__('This will only display the WhatsApp button and hide the %1$sAdd to Cart%2$s button.', 'oneclick-wa-order'),
                                    '<code>', // opening <code> tag
                                    '</code>' // closing <code> tag
                                );
                                ?>
                            </td>
                        </tr>

                        <!-- Text on Button -->
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="text_button"><b><?php esc_html_e('Text on Button', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_button_text_shop_loop" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_button_text_shop_loop')); ?>" placeholder="<?php esc_html_e('e.g. Buy via WhatsApp', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>

                        <!-- Custom Message -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label class="wa_order_message_label" for="message_wbw"><b><?php esc_html_e('Custom Message', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <textarea name="wa_order_option_custom_message_shop_loop" class="wa_order_input_areatext" rows="5" placeholder="<?php esc_html_e('e.g. Hello, I want to purchase:', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_option_custom_message_shop_loop')); ?></textarea>
                                <p class="description">
                                    <?php
                                    /* translators: 1. <code> tag for the example custom message */
                                    echo sprintf(
                                        /* translators: 1. opening <code> tag for example message */
                                        /* translators: 2. closing </code> tag */
                                        esc_html__('Enter custom message, e.g. %1$sHello, I want to purchase:%2$s', 'oneclick-wa-order'),
                                        '<code>', // opening <code> tag
                                        '</code>' // closing <code> tag
                                    );
                                    ?>
                                </p>
                            </td>
                        </tr>

                        <!-- Exclude Price Option -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Exclude Price?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_shop_loop_exclude_price" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_shop_loop_exclude_price'), 'yes'); ?>>
                                <?php esc_html_e('This will remove product price from WhatsApp message sent from Shop loop page.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Hide Product URL Option -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Remove Product URL?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_shop_loop_hide_product_url" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_shop_loop_hide_product_url'), 'yes'); ?>>
                                <?php esc_html_e('This will remove product URL from WhatsApp message sent from Shop loop page.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Open in New Tab Option -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Open in New Tab?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_shop_loop_open_new_tab" class="wa_order_input_check" value="_blank" <?php checked(get_option('wa_order_option_shop_loop_open_new_tab'), '_blank'); ?>>
                                <?php esc_html_e('Yes, Open in New Tab', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <?php submit_button(); ?>
            </form>
        <?php } elseif ($active_tab == 'cart_button') { ?>
            <!-- Custom Shortcode -->
            <form method="post" action="options.php">
                <?php settings_errors(); ?>
                <?php settings_fields('wa-order-settings-group-cart-options'); ?>
                <?php do_settings_sections('wa-order-settings-group-cart-options'); ?>
                <h2 class="section_wa_order"><?php esc_html_e('WhatsApp Button on Cart Page', 'oneclick-wa-order'); ?></h2>
                <p>
                    <?php
                    /* translators: 1. opening <strong> tag for "Cart", 2. closing </strong> tag, 3. opening <strong> tag for "Proceed to Checkout", 4. closing </strong> tag */
                    echo sprintf(
                        /* translators: 1. opening <strong> tag for "Cart" */
                        /* translators: 2. closing </strong> tag */
                        /* translators: 3. opening <strong> tag for "Proceed to Checkout" */
                        /* translators: 4. closing </strong> tag */
                        esc_html__('Add custom WhatsApp button on %1$sCart%2$s page right under the %3$sProceed to Checkout%4$s button.', 'oneclick-wa-order'),
                        '<strong>', // opening <strong> tag for "Cart"
                        '</strong>', // closing <strong> tag for "Cart"
                        '<strong>', // opening <strong> tag for "Proceed to Checkout"
                        '</strong>' // closing <strong> tag for "Proceed to Checkout"
                    );
                    ?>
                    <br />
                </p>
                <table class="form-table">
                    <tbody>
                        <h2 class="section_wa_order"><?php esc_html_e('Cart Page', 'oneclick-wa-order'); ?></h2>
                        <p><?php esc_html_e('The following options will be only effective on cart page.', 'oneclick-wa-order'); ?></p>

                        <!-- Display Button on Cart Page -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Display button on Cart page?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_add_button_to_cart" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_add_button_to_cart'), 'yes'); ?>>
                                <?php esc_html_e('This will display WhatsApp button on Cart page', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- WhatsApp Number Dropdown -->
                        <tr>
                            <th scope="row">
                                <label><?php esc_html_e('WhatsApp Number', 'oneclick-wa-order') ?></label>
                            </th>
                            <td>
                                <?php wa_order_phone_numbers_dropdown(
                                    array(
                                        'name'      => 'wa_order_selected_wa_number_cart',
                                        'selected'  => get_option('wa_order_selected_wa_number_cart'),
                                    )
                                ) ?>
                                <p class="description">
                                    <?php
                                    /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                                    echo wp_kses(
                                        sprintf(
                                            /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                                            __('WhatsApp number is %1$srequired%2$s. Please set it on the %3$sNumbers%4$s tab.', 'oneclick-wa-order'),
                                            '<strong style="color:red;">', // opening <strong> tag with red color style
                                            '</strong>', // closing <strong> tag
                                            '<a href="edit.php?post_type=wa-order-numbers"><strong>', // opening <a> and <strong> tag
                                            '</strong></a>' // closing <a> and <strong> tag
                                        ),
                                        array(
                                            'strong' => array('style' => array()), // Allow strong with style attribute
                                            'a' => array('href' => array(), 'target' => array()) // Allow a tag with href and target attributes
                                        )
                                    );
                                    ?>
                                </p>
                            </td>
                        </tr>

                        <!-- Hide Proceed to Checkout Button -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Hide Proceed to Checkout button?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_cart_hide_checkout" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_cart_hide_checkout'), 'yes'); ?>>
                                <?php
                                /* translators: 1. <code> tag for "Proceed to Checkout" */
                                echo sprintf(
                                    /* translators: 1. <code> tag for "Proceed to Checkout" */
                                    esc_html__('This will only display WhatsApp button and hide the %1$sProceed to Checkout%2$s button', 'oneclick-wa-order'),
                                    '<code>', // opening <code> tag
                                    '</code>' // closing <code> tag
                                );
                                ?>
                            </td>
                        </tr>

                        <!-- Text on Button -->
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="text_button"><b><?php esc_html_e('Text on Button', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_cart_button_text" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_cart_button_text')); ?>" placeholder="<?php esc_html_e('e.g. Complete Order via WhatsApp', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>

                        <!-- Custom Message -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label class="wa_order_message_label" for="message_wbw"><b><?php esc_html_e('Custom Message', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <textarea name="wa_order_option_cart_custom_message" class="wa_order_input_areatext" rows="5" placeholder="<?php esc_html_e('e.g. Hello, I want to purchase the item(s) below:', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_option_cart_custom_message')); ?></textarea>
                                <p class="description">
                                    <?php
                                    /* translators: 1. <code> tag for the example message */
                                    echo sprintf(
                                        /* translators: 1. <code> tag for the example message */
                                        esc_html__('Enter custom message, e.g. %1$sHello, I want to purchase the item(s) below:%2$s', 'oneclick-wa-order'),
                                        '<code>', // opening <code> tag
                                        '</code>' // closing <code> tag
                                    );
                                    ?>
                                </p>
                            </td>
                        </tr>

                        <!-- Remove Product URL Option -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Remove Product URL?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_cart_hide_product_url" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_cart_hide_product_url'), 'yes'); ?>>
                                <?php esc_html_e('This will remove product URL from WhatsApp message sent from Cart page.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Include Product Variation Option -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Include Product Variation?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_cart_enable_variations" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_cart_enable_variations'), 'yes'); ?>>
                                <?php esc_html_e('The product variation will be included in the message if it is stored by WooCommerce, might not all.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Include Tax Option -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Include Tax?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_cart_include_tax" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_cart_include_tax'), 'yes'); ?>>
                                <?php esc_html_e('This will include the tax in the message.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Open in New Tab Option -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Open in New Tab?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_cart_open_new_tab" class="wa_order_input_check" value="_blank" <?php checked(get_option('wa_order_option_cart_open_new_tab'), '_blank'); ?>>
                                <?php esc_html_e('Yes, Open in New Tab', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <?php submit_button(); ?>
            </form>
        <?php } elseif ($active_tab == 'thanks_page') { ?>
            <!-- Checkout Thank You Page -->
            <form method="post" action="options.php">
                <?php settings_errors(); ?>
                <?php settings_fields('wa-order-settings-group-order-completion'); ?>
                <?php do_settings_sections('wa-order-settings-group-order-completion'); ?>

                <h2 class="section_wa_order"><?php echo esc_html__('Thank You Page Customization', 'oneclick-wa-order'); ?></h2>
                <p>
                    <?php echo esc_html__('Add a WhatsApp button on Thank You / Order Received page. If enabled, it will add a new section under the Order Received or Thank You title and override default text by using below data, including adding a WhatsApp button to send order details.', 'oneclick-wa-order'); ?>
                    <br />
                    <strong><?php echo esc_html__('Tip:', 'oneclick-wa-order'); ?></strong> <?php echo esc_html__('You can use this to make it quick for your customers to send their own order receipt to you via WhatsApp.', 'oneclick-wa-order'); ?>
                </p>

                <table class="form-table">
                    <tbody>
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label for="wa_order_option_enable_button_thank_you"><?php echo esc_html__('Enable Setting?', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_enable_button_thank_you" id="wa_order_option_enable_button_thank_you" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_enable_button_thank_you')), 'yes'); ?>>
                                <?php echo esc_html__('This will override default appearance and add a WhatsApp button.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                        <!-- WhatsApp Number Dropdown -->
                        <tr>
                            <th scope="row">
                                <label><?php echo esc_html__('WhatsApp Number', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <?php wa_order_phone_numbers_dropdown(
                                    array(
                                        'name'      => 'wa_order_selected_wa_number_thanks',
                                        'selected'  => get_option('wa_order_selected_wa_number_thanks'),
                                    )
                                ); ?>
                                <p class="description">
                                    <?php
                                    /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                                    echo wp_kses(
                                        sprintf(
                                            /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                                            __('WhatsApp number is %1$srequired%2$s. Please set it on the %3$sNumbers%4$s tab.', 'oneclick-wa-order'),
                                            '<strong style="color:red;">', // opening <strong> tag with red color style
                                            '</strong>', // closing <strong> tag
                                            '<a href="edit.php?post_type=wa-order-numbers"><strong>', // opening <a> and <strong> tag
                                            '</strong></a>' // closing <a> and <strong> tag
                                        ),
                                        array(
                                            'strong' => array('style' => array()), // Allow strong with style attribute
                                            'a' => array('href' => array(), 'target' => array()) // Allow a tag with href and target attributes
                                        )
                                    );
                                    ?>
                                </p>
                            </td>
                        </tr>
                        <!-- Text on Button -->
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_button_text"><?php echo esc_html__('Text on Button', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_custom_thank_you_button_text" id="wa_order_option_custom_thank_you_button_text" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_custom_thank_you_button_text')); ?>" placeholder="<?php echo esc_attr__('e.g. Send Order Details', 'oneclick-wa-order'); ?>">
                                <p class="description">
                                    <?php echo esc_html__('Enter the text on WhatsApp button. e.g. Send Order Details', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>
                        <!-- Custom Message -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_custom_message"><?php echo esc_html__('Custom Message', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <textarea name="wa_order_option_custom_thank_you_custom_message" id="wa_order_option_custom_thank_you_custom_message" class="wa_order_input_areatext" rows="5" placeholder="<?php echo esc_attr__('e.g. Hello, here\'s my order details:', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_option_custom_thank_you_custom_message')); ?></textarea>
                                <p class="description">
                                    <?php echo esc_html__('Enter custom message to send along with order details. e.g. Hello, here\'s my order details:', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>
                        <!-- Custom Title -->
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_title"><?php echo esc_html__('Custom Title', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_custom_thank_you_title" id="wa_order_option_custom_thank_you_title" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_custom_thank_you_title')); ?>" placeholder="<?php echo esc_attr__('e.g. Thanks and You\'re Awesome', 'oneclick-wa-order'); ?>">
                                <p class="description">
                                    <?php echo esc_html__('You can personalize the title by changing it here. This will be shown like this: [your custom title], [customer\'s first name]. e.g. Thanks and You\'re Awesome, Igor!', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>
                        <!-- Custom Subtitle -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_subtitle"><?php echo esc_html__('Custom Subtitle', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <textarea name="wa_order_option_custom_thank_you_subtitle" id="wa_order_option_custom_thank_you_subtitle" class="wa_order_input_areatext" rows="5" placeholder="<?php echo esc_attr__('e.g. For faster response, send your order details by clicking below button.', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_option_custom_thank_you_subtitle')); ?></textarea>
                                <p class="description">
                                    <?php echo esc_html__('Enter custom subtitle. e.g. For faster response, send your order details by clicking below button.', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>
                        <!-- Customer Details Label -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_customer_details_label"><?php echo esc_html__('Customer Details Label', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_custom_thank_you_customer_details_label" id="wa_order_option_custom_thank_you_customer_details_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_custom_thank_you_customer_details_label')); ?>" placeholder="<?php echo esc_attr__('e.g. Customer Details', 'oneclick-wa-order'); ?>">
                                <p class="description">
                                    <?php echo esc_html__('Enter a label for customer details. e.g. Customer Details', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>

                        <!-- Total Products Label -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_total_products_label"><?php echo esc_html__('Total Products Label', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_custom_thank_you_total_products_label" id="wa_order_option_custom_thank_you_total_products_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_custom_thank_you_total_products_label')); ?>" placeholder="<?php echo esc_attr__('e.g. Total Products', 'oneclick-wa-order'); ?>">
                                <p class="description">
                                    <?php echo esc_html__('Enter a label for the total number of products. This field is optional and can be left blank to disable it. e.g. Total Products', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>

                        <!-- Include Coupon Discount -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_inclue_coupon"><?php echo esc_html__('Include Coupon Discount?', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_custom_thank_you_inclue_coupon" id="wa_order_option_custom_thank_you_inclue_coupon" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_custom_thank_you_inclue_coupon')), 'yes'); ?>>
                                <?php echo esc_html__('This includes a coupon code and its associated deduction amount, along with a label if it is enabled.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Coupon Label -->
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_coupon_label"><?php echo esc_html__('Coupon Label', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_custom_thank_you_coupon_label" id="wa_order_option_custom_thank_you_coupon_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_custom_thank_you_coupon_label')); ?>" placeholder="<?php echo esc_attr__('e.g. Voucher Code', 'oneclick-wa-order'); ?>">
                                <p class="description">
                                    <?php echo esc_html__('Enter a label for the coupon code. e.g. Voucher Code', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>

                        <!-- Include Order Summary Link -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label for="wa_order_option_thank_you_order_summary_link"><?php echo esc_html__('Include Order Summary Link?', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_thank_you_order_summary_link" id="wa_order_option_thank_you_order_summary_link" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_thank_you_order_summary_link')), 'yes'); ?>>
                                <?php echo esc_html__('Include an Order Summary link in the message.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Order Summary Label -->
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label for="wa_order_option_thank_you_order_summary_label"><?php echo esc_html__('Order Summary Label', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_thank_you_order_summary_label" id="wa_order_option_thank_you_order_summary_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_thank_you_order_summary_label')); ?>" placeholder="<?php echo esc_attr__('e.g. Check Order Summary:', 'oneclick-wa-order'); ?>">
                                <p class="description">
                                    <?php echo esc_html__('Enter a label for the order summary. e.g. Check Order Summary:', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>

                        <!-- Include Payment Link -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label for="wa_order_option_thank_you_payment_link"><?php echo esc_html__('Include Payment Link?', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_thank_you_payment_link" id="wa_order_option_thank_you_payment_link" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_thank_you_payment_link')), 'yes'); ?>>
                                <?php echo esc_html__('Include the Payment Link in the message.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Payment Link Label -->
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label for="wa_order_option_thank_you_payment_link_label"><?php echo esc_html__('Payment Link Label', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_thank_you_payment_link_label" id="wa_order_option_thank_you_payment_link_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_thank_you_payment_link_label')); ?>" placeholder="<?php echo esc_attr__('e.g. Payment Link:', 'oneclick-wa-order'); ?>">
                                <p class="description">
                                    <?php echo esc_html__('Enter a label for the payment link. e.g. Payment Link:', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>

                        <!-- Include View Order Link -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label for="wa_order_option_thank_you_view_order_link"><?php echo esc_html__('Include View Order Link?', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_thank_you_view_order_link" id="wa_order_option_thank_you_view_order_link" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_thank_you_view_order_link')), 'yes'); ?>>
                                <?php echo esc_html__('Note: It only works if a customer already has an account.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- View Order Label -->
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label for="wa_order_option_thank_you_view_order_label"><?php echo esc_html__('View Order Label', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_thank_you_view_order_label" id="wa_order_option_thank_you_view_order_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_thank_you_view_order_label')); ?>" placeholder="<?php echo esc_attr__('e.g. View Order:', 'oneclick-wa-order'); ?>">
                                <p class="description">
                                    <?php echo esc_html__('Enter a label for the view order. e.g. View Order:', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>

                        <!-- Include Order Number -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_order_number"><?php echo esc_html__('Include Order Number?', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_custom_thank_you_order_number" id="wa_order_option_custom_thank_you_order_number" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_custom_thank_you_order_number')), 'yes'); ?>>
                                <?php echo esc_html__('The order number will include a label, if enabled.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Order Number Label -->
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_order_number_label"><?php echo esc_html__('Order Number Label', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_custom_thank_you_order_number_label" id="wa_order_option_custom_thank_you_order_number_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_custom_thank_you_order_number_label')); ?>" placeholder="<?php echo esc_attr__('e.g. Order Number:', 'oneclick-wa-order'); ?>">
                                <p class="description">
                                    <?php echo esc_html__('Enter a label for the order number. e.g. Order Number:', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>

                        <!-- Include Product SKU -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_include_sku"><?php echo esc_html__('Include Product SKU?', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_custom_thank_you_include_sku" id="wa_order_option_custom_thank_you_include_sku" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_custom_thank_you_include_sku')), 'yes'); ?>>
                                <?php echo esc_html__('Yes, Include Product SKU', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                        <!-- Include Tax -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_include_tax"><?php echo esc_html__('Include Tax?', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_custom_thank_you_include_tax" id="wa_order_option_custom_thank_you_include_tax" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_custom_thank_you_include_tax')), 'yes'); ?>>
                                <?php echo esc_html__('Yes, Include Tax', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Include Order Date -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_include_order_date"><?php echo esc_html__('Include Order Date?', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_custom_thank_you_include_order_date" id="wa_order_option_custom_thank_you_include_order_date" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_custom_thank_you_include_order_date')), 'yes'); ?>>
                                <?php echo esc_html__('Yes, Include Order Date', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Open in New Tab -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_open_new_tab"><?php echo esc_html__('Open in New Tab?', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_custom_thank_you_open_new_tab" id="wa_order_option_custom_thank_you_open_new_tab" class="wa_order_input_check" value="_blank" <?php checked(esc_attr(get_option('wa_order_option_custom_thank_you_open_new_tab')), '_blank'); ?>>
                                <?php echo esc_html__('Yes, Open in New Tab', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <?php submit_button(); ?>
            </form>
        <?php } elseif ($active_tab == 'gdpr_notice') { ?>
            <form method="post" action="options.php">
                <?php settings_errors(); ?>
                <?php settings_fields('wa-order-settings-group-gdpr'); ?>
                <?php do_settings_sections('wa-order-settings-group-gdpr'); ?>

                <h2 class="section_wa_order"><?php echo esc_html__('GDPR Notice', 'oneclick-wa-order'); ?></h2>
                <p>
                    <?php echo esc_html__('You can enable or disable the GDPR notice to make your site more GDPR compliant. The GDPR notice you configure below will be displayed right under the WhatsApp Order button. Please note that this option will only show the GDPR notice on single product page.', 'oneclick-wa-order'); ?>
                </p>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label><?php echo esc_html__('Enable GDPR Notice', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_gdpr_status_enable" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_gdpr_status_enable')), 'yes'); ?>>
                                <?php echo esc_html__('Check to Enable GDPR Notice.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label><?php echo esc_html__('GDPR Message', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <textarea name="wa_order_gdpr_message" class="wa_order_input_areatext" rows="5" placeholder="<?php echo esc_attr__('e.g. I have read the [gdpr_link]', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_gdpr_message')); ?></textarea>
                                <p class="description">
                                    <?php
                                    // Translators: %s is the link
                                    printf(esc_html__('Use %s to display Privacy Policy page link.', 'oneclick-wa-order'), '<code>[gdpr_link]</code>'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label><?php echo esc_html__('Privacy Policy Page', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <?php wa_order_options_dropdown(
                                    array(
                                        'name'      => 'wa_order_gdpr_privacy_page',
                                        'selected'  => esc_attr(get_option('wa_order_gdpr_privacy_page')),
                                    )
                                ); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <?php submit_button(); ?>
            </form>
        <?php } elseif ($active_tab == 'tutorial_support') { ?>
            <!-- Begin creating plugin admin page -->
            <div class="wrap">
                <div class="feature-section one-col wrap about-wrap">
                    <div class="about-text">
                        <h4><?php
                            /* translators: 1. <strong> tag for "OneClick Chat to Order" */
                            echo sprintf(
                                /* translators: 1. opening <strong> tag */
                                esc_html__('%1$sOneClick Chat to Order%2$s is Waiting for Your Feedback', 'oneclick-wa-order'),
                                '<strong>', // opening <strong> tag
                                '</strong>' // closing <strong> tag
                            );
                            ?></h4>
                    </div>
                    <div class="indo-about-description">
                        <?php
                        /* translators: 1. <strong> tag for "OneClick Chat to Order", 2. <a> tag with href to the review page, 3. closing </a> tag */
                        echo sprintf(
                            /* translators: 1. <strong> tag for "OneClick Chat to Order" */
                            /* translators: 2. opening <a> tag for "leaving a review" */
                            /* translators: 3. closing <a> tag */
                            esc_html__('%1$sOneClick Chat to Order%2$s is my second plugin and it\'s open source. I acknowledge that there are still a lot to fix, here and there, that\'s why I really need your feedback. Let\'s get in touch and show some love by %3$sleaving a review%4$s.', 'oneclick-wa-order'),
                            '<strong>', // opening <strong> tag
                            '</strong>', // closing <strong> tag
                            '<a href="https://wordpress.org/support/plugin/oneclick-whatsapp-order/reviews/?rate=5#new-post" target="_blank"><strong>', // opening <a> and <strong> tag
                            '</strong></a>' // closing <a> and <strong> tag
                        );
                        ?>
                    </div>
                    <table class="tg" style="table-layout: fixed; width: 269px">
                        <colgroup>
                            <col style="width: 105px">
                            <col style="width: 164px">
                        </colgroup>
                        <tr>
                            <th class="tg-kiyi">
                                <?php esc_html_e('Author:', 'oneclick-wa-order'); ?></th>
                            <th class="tg-fymr">
                                <?php esc_html_e('Walter Pinem', 'oneclick-wa-order'); ?></th>
                        </tr>
                        <tr>
                            <td class="tg-kiyi">
                                <?php esc_html_e('Website:', 'oneclick-wa-order'); ?></td>
                            <td class="tg-fymr"><a href="https://walterpinem.me/" title="<?php esc_attr_e('Visit walterpinem.me', 'oneclick-wa-order'); ?>" target="_blank">
                                    <?php esc_html_e('walterpinem.me', 'oneclick-wa-order'); ?></a></td>
                        </tr>
                        <tr>
                            <td class="tg-kiyi">
                            <td class="tg-fymr"><a href="https://walterpinem.com/" title="<?php esc_attr_e('Visit walterpinem.com', 'oneclick-wa-order'); ?>" target="_blank">
                                    <?php esc_html_e('walterpinem.com', 'oneclick-wa-order'); ?></a></td>
                        </tr>
                        <tr>
                            <td class="tg-kiyi">
                            <td class="tg-fymr"><a href="https://www.onlinestorekit.com/" title="<?php esc_attr_e('Online Store Kit', 'oneclick-wa-order'); ?>" target="_blank">
                                    <?php esc_html_e('Online Store Kit', 'oneclick-wa-order'); ?></a></td>
                        </tr>
                        <tr>
                            <td class="tg-kiyi">
                            <td class="tg-fymr"><a href="https://walterpinem.me/projects/tools/" title="<?php esc_attr_e('65+ Free Online Tools', 'oneclick-wa-order'); ?>" target="_blank">
                                    <?php esc_html_e('65+ Free Online Tools', 'oneclick-wa-order'); ?></a></td>
                        </tr>
                        <tr>
                            <td class="tg-kiyi">
                                <?php esc_html_e('Email:', 'oneclick-wa-order'); ?></td>
                            <td class="tg-fymr"><a href="mailto:hello@walterpinem.me" title="<?php esc_attr_e('Send email to hello@walterpinem.me', 'oneclick-wa-order'); ?>" target="_blank">
                                    <?php esc_html_e('hello@walterpinem.me', 'oneclick-wa-order'); ?></a></td>
                        </tr>
                        <tr>
                            <td class="tg-kiyi"><?php esc_html_e('More:', 'oneclick-wa-order'); ?></td>
                            <td class="tg-fymr"><a href="https://youtu.be/LuURM5vZyB8" title="<?php esc_attr_e('Complete Youtube Tutorial', 'oneclick-wa-order'); ?>" target="_blank">
                                    <?php esc_html_e('Complete Tutorial', 'oneclick-wa-order'); ?></a></td>
                        </tr>
                        <tr>
                            <td class="tg-kiyi" rowspan="3"></td>
                            <td class="tg-fymr"><a href="https://walterpinem.me/projects/contact/" title="<?php esc_attr_e('Support & Feature Request', 'oneclick-wa-order'); ?>" target="_blank">
                                    <?php esc_html_e('Support & Feature Request', 'oneclick-wa-order'); ?></a></td>
                        </tr>
                        <tr>
                            <td class="tg-kiyi" rowspan="3"></td>
                            <td class="tg-fymr"><a href="https://www.paypal.me/WalterPinem" title="<?php esc_attr_e('Buy Me a Coffee?', 'oneclick-wa-order'); ?>" target="_blank">
                                    <?php esc_html_e('Donate', 'oneclick-wa-order'); ?></a></td>
                        </tr>
                    </table>
                    <br>
                    <hr>
                    <?php echo do_shortcode("[donate]"); ?>
                    <center>
                        <p><?php echo wp_kses_post("Created with ❤ and ☕ in Central Jakarta, Indonesia by <a href=\"https://walterpinem.me\" target=\"_blank\"><strong>Walter Pinem</strong></a>", 'oneclick-wa-order'); ?></p>
                    </center>
                </div>
            </div>
        <?php } elseif ($active_tab == 'welcome') { ?>
            <!-- Begin creating plugin admin page -->
            <div class="wrap">
                <div class="feature-section one-col wrap about-wrap">
                    <div class="indo-title-text">
                        <h2><?php echo wp_kses_post('Thank you for using <br><strong>OneClick Chat to Order</strong>', 'oneclick-wa-order'); ?></h2>
                        <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'assets/images/oneclick-chat-to-order.png'); ?>" alt="<?php esc_attr_e('OneClick Chat to Order Logo', 'oneclick-wa-order'); ?>" />
                    </div>
                    <div class="feature-section one-col about-text">
                        <h3><?php esc_html_e("Make It Easy for Customers to Reach You!", 'oneclick-wa-order'); ?></h3>
                    </div>
                    <div class="feature-section one-col indo-about-description">
                        <p>
                            <?php esc_html_e('OneClick Chat to Order will enable you to connect your WooCommerce-powered online store with WhatsApp and make it super quick and easy for your customers to complete their order via WhatsApp.', 'oneclick-wa-order'); ?>
                        </p>
                        <p>
                            <a href="https://onlinestorekit.com/oneclick-chat-to-order/" target="_blank"><?php esc_html_e('Learn More', 'oneclick-wa-order'); ?></a>
                        </p>
                    </div>
                    <div class="clear"></div>
                    <hr>
                    <div class="feature-section one-col about-text">
                        <h4><?php echo wp_kses_post(__("<strong style=\"color:red;\">NEW!</strong> Build a Powerful Multi-Vendor Online Marketplace", 'oneclick-wa-order')); ?></h4>

                    </div>
                    <div class="feature-section one-col indo-about-description">
                        <p>
                            <?php esc_html_e('Seamlessly combine the power of WordPress & WooCommerce, OneClick Chat to Order, WCFM Marketplace, WCFM Frontend Manager and WhatsApp with the new and most requested add-on, OneClick WCFM Connector, that your vendors will love.', 'oneclick-wa-order'); ?>
                        </p>
                        <p>
                            <?php esc_html_e('Help them increase their sales, increase your revenue.', 'oneclick-wa-order'); ?>
                        </p>
                        <p>
                            <a href="https://onlinestorekit.com/oneclick-wcfm-connector/" target="_blank"><?php esc_html_e('Read Details', 'oneclick-wa-order'); ?></a>
                        </p>
                    </div>
                    <div class="clear"></div>
                    <hr />
                    <div class="feature-section one-col">
                        <h3 style="text-align: center;"><?php esc_html_e('Watch the Complete Overview and Tutorial', 'oneclick-wa-order'); ?></h3>
                        <div class="headline-feature feature-video">
                            <div class='embed-container'>
                                <iframe width="560" height="315" src="https://www.youtube.com/embed/videoseries?si=P4KW9wnME3q2Mqvj&amp;list=PLwazGJFvaLnBTOw4pNvPcsFW1ls4tn1Uj" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <hr />
                    <div class="feature-section one-col">
                        <div class="indo-get-started">
                            <h3><?php esc_html_e('Let\'s Get Started', 'oneclick-wa-order'); ?></h3>
                            <ul>
                                <li><strong><?php esc_html_e('Step #1:', 'oneclick-wa-order'); ?></strong> <?php esc_html_e('Start adding your WhatsApp number on WhatsApp Numbers post type. You can add unlimited numbers! Learn more or dismiss notice.', 'oneclick-wa-order'); ?></li>
                                <li><strong><?php esc_html_e('Step #2:', 'oneclick-wa-order'); ?></strong> <?php esc_html_e('Show a fancy Floating Button with customized message and tooltip which you can customize easily on Floating Button setting panel.', 'oneclick-wa-order'); ?></li>
                                <li><strong><?php esc_html_e('Step #3:', 'oneclick-wa-order'); ?></strong> <?php esc_html_e('Configure some options to display or hide buttons, including the WhatsApp button on Display Options setting panel.', 'oneclick-wa-order'); ?></li>
                                <li><strong><?php esc_html_e('Step #4:', 'oneclick-wa-order'); ?></strong> <?php esc_html_e('Make your online store GDPR-ready by showing GDPR Notice right under the WhatsApp Order button on GDPR Notice setting panel.', 'oneclick-wa-order'); ?></li>
                                <li><strong><?php esc_html_e('Step #5:', 'oneclick-wa-order'); ?></strong> <?php esc_html_e('Display WhatsApp button anywhere you like with a single shortcode. You can generate it with a customized message and a nice text on button on Generate Shortcode setting panel.', 'oneclick-wa-order'); ?></li>
                                <li><strong><?php esc_html_e('Step #6:', 'oneclick-wa-order'); ?></strong> <?php esc_html_e('Have an inquiry? Find out how to reach me out on Support panel.', 'oneclick-wa-order'); ?></li>
                            </ul>
                        </div>
                    </div>
                    <hr>
                    <div class="feature-section two-col">
                        <div class="col">
                            <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'assets/images/simple-chat-button.png'); ?>" alt="<?php esc_attr_e('Simple Chat Button', 'oneclick-wa-order'); ?>" />
                            <h3><?php esc_html_e('Simple Chat to Order Button', 'oneclick-wa-order'); ?></h3>
                            <p><?php esc_html_e('Replace the default Add to Cart button or simply show both. Once the Chat to Order button is clicked, the message along with the product details are sent to you through WhatsApp.', 'oneclick-wa-order'); ?></p>
                        </div>
                        <div class="col">
                            <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'assets/images/fancy-floating-button.png'); ?>" alt="<?php esc_attr_e('Fancy Floating Button', 'oneclick-wa-order'); ?>" />
                            <h3><?php esc_html_e('Fancy Floating Button', 'oneclick-wa-order'); ?></h3>
                            <p><?php esc_html_e('Make it easy for any customers/visitors to reach you out through a click of a floating WhatsApp button, displayed on the left of right with tons of customization options.', 'oneclick-wa-order'); ?></p>
                        </div>
                    </div>
                    <div class="feature-section two-col">
                        <div class="col">
                            <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'assets/images/display-this-or-hide-that.png'); ?>" alt="<?php esc_attr_e('Display or Hide Elements', 'oneclick-wa-order'); ?>" />
                            <h3><?php esc_html_e('Display This or Hide That', 'oneclick-wa-order'); ?></h3>
                            <p><?php esc_html_e('Wanna hide some buttons or elements you don\'t like? You have the command to rule them all. Just visit the panel and all of the options are there to configure.', 'oneclick-wa-order'); ?></p>
                        </div>
                        <div class="col">
                            <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'assets/images/gdpr-ready.png'); ?>" alt="<?php esc_attr_e('GDPR Ready', 'oneclick-wa-order'); ?>" />
                            <h3><?php esc_html_e('Make It GDPR-Ready', 'oneclick-wa-order'); ?></h3>
                            <p><?php esc_html_e('The regulations are real and it\'s time to make your site ready for them. Make your site GDPR-ready with some simple configurations, really easy!', 'oneclick-wa-order'); ?></p>
                        </div>
                    </div>
                    <div class="feature-section two-col">
                        <div class="col">
                            <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'assets/images/shortcode.png'); ?>" alt="<?php esc_attr_e('Shortcode Generator', 'oneclick-wa-order'); ?>" />
                            <h3><?php esc_html_e('Shortcode Generator', 'oneclick-wa-order'); ?></h3>
                            <p><?php esc_html_e('Are the previous options still not enough for you? You can extend the flexibility to display a WhatsApp button using a shortcode, which you can generate easily.', 'oneclick-wa-order'); ?></p>
                        </div>
                        <div class="col">
                            <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'assets/images/documentation.png'); ?>" alt="<?php esc_attr_e('Comprehensive Documentation', 'oneclick-wa-order'); ?>" />
                            <h3><?php esc_html_e('Comprehensive Documentation', 'oneclick-wa-order'); ?></h3>
                            <p><?php esc_html_e('You will not be left alone. My complete documentation or tutorial will always help and support all your needs to get started. Watch tutorial videos.', 'oneclick-wa-order'); ?></p>
                        </div>
                    </div>
                    <br>
                    <hr>
                    <?php echo do_shortcode("[donate]"); ?>
                    <center>
                        <p><?php esc_html_e('Created with ❤ and ☕ in Jakarta, Indonesia by Walter Pinem', 'oneclick-wa-order'); ?></p>
                    </center>
                </div>
            </div>
            <br>
    </div>
<?php
        }
    }

    // Donate button
    function wa_order_donate_button_shortcode()
    {
        ob_start();
?>
<center>
    <div class="donate-container">
        <p><?php esc_html_e('To keep this plugin free, I spent cups of coffee building it. If you love and find it really useful for your business, you can always', 'oneclick-wa-order'); ?></p>
        <a href="https://www.paypal.me/WalterPinem" target="_blank">
            <button class="donatebutton">
                ☕ <?php esc_html_e('Buy Me a Coffee', 'oneclick-wa-order'); ?>
            </button>
        </a>
    </div>
</center>
<?php
        return ob_get_clean();
    }
    add_shortcode('donate', 'wa_order_donate_button_shortcode');
