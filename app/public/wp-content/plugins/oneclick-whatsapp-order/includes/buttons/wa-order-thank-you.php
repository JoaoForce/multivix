<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * OneClick Chat to Order Thank You Page
 *
 * @package     OneClick Chat to Order
 * @author      Walter Pinem <hello@walterpinem.me>
 * @link        https://walterpinem.me/
 * @link        https://onlinestorekit.com/oneclick-chat-to-order/
 * @copyright   Copyright (c) 2019 - 2024, Walter Pinem | Online Store Kit
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * @category    Checkout Page
 */

// Checkbox value
$override_thankyou_page = get_option(sanitize_text_field('wa_order_option_enable_button_thank_you'));
function wa_order_thank_you_override($title, $id)
{
    global $wp;
    // Consolidate get_option() calls
    $options = array(
        'wanumberpage'               => get_option('wa_order_selected_wa_number_thanks', ''),
        'custom_title'               => get_option('wa_order_option_custom_thank_you_title', 'Thanks and You\'re Awesome'),
        'custom_subtitle'            => get_option('wa_order_option_custom_thank_you_subtitle', 'For faster response, send your order details by clicking below button.'),
        'button_text'                => get_option('wa_order_option_custom_thank_you_button_text', 'Send Order Details'),
        'custom_message'             => get_option('wa_order_option_custom_thank_you_custom_message', "Hello, here's my order details:"),
        'thanks_label'               => get_option('wa_order_option_thank_you_label', ''),
        'include_order_number'       => get_option('wa_order_option_custom_thank_you_order_number', 'no'),
        'order_number_label'         => get_option('wa_order_option_custom_thank_you_order_number_label', ''),
        'include_payment_link'       => get_option('wa_order_option_thank_you_payment_link', 'no'),
        'payment_link_label'         => get_option('wa_order_option_thank_you_payment_link_label', 'Payment Link'),
        'include_order_summary_link' => get_option('wa_order_option_thank_you_order_summary_link', 'no'),
        'order_summary_label'        => get_option('wa_order_option_thank_you_order_summary_label', 'Order Summary'),
        'include_view_order_link'    => get_option('wa_order_option_thank_you_view_order_link', 'no'),
        'view_order_label'           => get_option('wa_order_option_thank_you_view_order_label', 'View Order'),
        'tax_label'                  => get_option('wa_order_option_tax_label', 'Tax'),
        'customer_details_label'     => get_option('wa_order_option_custom_thank_you_customer_details_label', 'Customer Details'),
        'total_products_label'       => get_option('wa_order_option_custom_thank_you_total_products_label'),
        'total_label'                => get_option('wa_order_option_total_amount_label'),
        'total_discount_label'       => get_option('wa_order_option_total_discount_label'),
        'payment_label'              => get_option('wa_order_option_payment_method_label'),
        'include_sku'                => get_option('wa_order_option_custom_thank_you_include_sku'),
        'include_tax'                => get_option('wa_order_option_custom_thank_you_include_tax'),
        'include_coupon'             => get_option('wa_order_option_custom_thank_you_inclue_coupon'),
        'coupon_label'               => get_option('wa_order_option_custom_thank_you_coupon_label'),
        'order_date'                 => get_option('wa_order_option_custom_thank_you_include_order_date'),
        'open_new_tab'               => get_option('wa_order_option_custom_thank_you_open_new_tab'),
    );
    $wanumberpage                   = $options['wanumberpage'];
    $postid                         = get_page_by_path($wanumberpage, OBJECT, 'wa-order-numbers');
    $phonenumb                      = apply_filters('wa_order_filter_thank_you_page_phone_number', get_post_meta($postid->ID, 'wa_order_phone_number_input', true));

    // Mapping the options
    $custom_title           = apply_filters('wa_order_filter_thank_you_page_custom_title', $options['custom_title']);
    $custom_subtitle        = apply_filters('wa_order_filter_thank_you_page_subtitle', $options['custom_subtitle']);
    $button_text            = apply_filters('wa_order_filter_thank_you_page_button_text', $options['button_text']);
    $custom_message         = apply_filters('wa_order_filter_thank_you_page_custom_message', $options['custom_message']);
    $thanks_label           = $options['thanks_label'];
    $include_order_number   = $options['include_order_number'];
    $order_number_label     = $options['order_number_label'];
    $order_number_label     = apply_filters('wa_order_filter_thank_you_page_order_number_label', $order_number_label);

    $customer_details_label = $options['customer_details_label'];
    $total_products_label   = $options['total_products_label'];
    $total_discount_label   = $options['total_discount_label'];
    $tax_label              = $options['tax_label'];
    $include_payment_link   = $options['include_payment_link'];
    $payment_link_label     = $options['payment_link_label'];
    $include_view_order     = $options['include_view_order_link'];
    $view_order_label       = $options['view_order_label'];
    $include_order_summary  = $options['include_order_summary_link'];
    $order_summary_label    = $options['order_summary_label'];
    // Check if the payment link label is empty and set to default if necessary
    if (empty($payment_link_label)) {
        $payment_link_label = 'Payment Link';
    }
    // Check if the view order label is empty and set to default if necessary
    if (empty($view_order_label)) {
        $view_order_label = 'View Order';
    }
    // Check if the order summary label is empty and set to default if necessary
    if (empty($order_summary_label)) {
        $order_summary_label = 'Order Summary';
    }

    // Check the order
    $order_id               = (int) $wp->query_vars['order-received'];
    if ($order_id) {
        $order              = wc_get_order($order_id);
    }

    // Prepare the message
    $customer_id        = $order->get_user_id();
    $first_name         = $order->get_billing_first_name();
    $message            = $custom_message . "\n\n";
    $thetitle           = $custom_title . ', ' . $first_name . '!';
    $subtitle           = $custom_subtitle;
    $button             = $button_text;
    if (!$order) {
        return '';
    }
    $customer_email     = $order->get_billing_email();
    $customer_phone     = $order->get_billing_phone();

    $billing_address    = $order->get_formatted_billing_address();
    $formatted_billingx = str_replace('<br/>', "\r\n", $billing_address);
    $formatted_billing  = $formatted_billingx . "\r\n" . $customer_phone . "\r\n" . $customer_email;

    $shipping_address   = $order->get_formatted_shipping_address();
    $formatted_shipping = str_replace('<br/>', "\r\n", $shipping_address);
    // Check if the order has tax
    $total_tax          = $order->get_total_tax();
    $total_label        = $options['total_label'];
    $payment_label      = $options['payment_label'];
    $subtotal_price     = $order->get_subtotal_to_display();
    $format_subtotal_pricex = wp_strip_all_tags($subtotal_price);
    $format_subtotal_price = html_entity_decode($format_subtotal_pricex);
    $label_total        = "*" . $total_label . ":*" . "\r\n";
    $total_format_subtotal_price = $label_total . $format_subtotal_price;
    $payment_method     = $order->get_payment_method_title();
    $payment            = "\r\n" . "*" . $payment_label . ":*" . "\r\n" . $payment_method . "\r\n";
    $date               = gmdate('F j, Y - g:i A', $order->get_date_created()->getOffsetTimestamp());
    $order_number       = $order->get_order_number();
    if ($order_number_label == '') {
        $on_label = "Order Number:";
    } else {
        $on_label = "$order_number_label";
    }

    // If Order Number inclusion is checked
    if ($include_order_number === 'yes') {
        $message .= "*" . $on_label . "*: " . "#" . $order_number . "\n\n";
    }
    if ($total_products_label) {
        $message .= "*" . $total_products_label . "*: " . $order->get_item_count() . "\n-----------\n";
    }
    foreach ($order->get_items() as $item_id => $item) {
        $product_id     = $item->get_product_id();
        $quantity       = $item->get_quantity();
        $product_name   = $item->get_name();
        $message        .= $quantity . "x - *" . $product_name . "*" . "\n";

        $formatted_meta_data = $item->get_formatted_meta_data('_', true);
        foreach ($formatted_meta_data as $meta) {
            $message    .= "     - ```" . $meta->display_key . ":``` ```" . wp_strip_all_tags($meta->display_value) . "```" . "\r\n";
        }

        $productsku         = $item->get_product();
        $include_sku        = $options['include_sku'];
        $sku                = $productsku->get_sku();
        $sku_label          = __('SKU', 'woocommerce');
        if (!empty($sku) && $include_sku === 'yes') {
            $message .= "     - ```" . $sku_label . ": " . $sku . "```" . "\n";
        }
    }
    $message .= "\n" . $total_format_subtotal_price . "\n" . $payment;

    // Customer Details
    if (!empty($customer_details_label)) {
        $message .= "\n*" . $customer_details_label . "*\n" . $formatted_billing . "\n";
    }

    $ship_method    = $order->get_shipping_method();

    // Check if shipping to a different address
    $ship_to_different_address  = get_post_meta($order->get_id(), '_shipping_address_1', true);
    $shipping_cost              = $order->get_shipping_total();
    $shipping_method            = $order->get_shipping_method();
    $plain_shipping_cost        = html_entity_decode(wp_strip_all_tags(wc_price($shipping_cost)));
    $ship_label                 = apply_filters('wa_order_filter_thank_you_page_shipping_label', __('Shipping:', 'woocommerce'));
    $shipping_cost              = apply_filters('wa_order_filter_thank_you_page_shipping_cost', $plain_shipping_cost);

    // If shipping address is different, include shipping details
    if (!empty($ship_method) && !empty($ship_to_different_address)) {
        $message .= "\r\n*" . $ship_label . "*\r\n";
        $message .= $shipping_method . ' - ' . $plain_shipping_cost . "\n-----------\n";
        $message .= $formatted_shipping;  // Include only if ship to different address is checked
    } else {
        $message .= "\r\n*" . $ship_label . "*\r\n";
        $message .= $shipping_method . ' - ' . $plain_shipping_cost;
    }

    // Coupon item: Check if coupon code used
    $order_items = $order->get_items('coupon');
    foreach ($order_items as $item_id => $item) {
        $args = array(
            'name'           => $item->get_name(),
            'post_type'      => 'shop_coupon',
            'post_status'    => 'publish',
            'numberposts'    => 1
        );

        $coupon_posts = get_posts($args);
        if ($coupon_posts) {
            $coupon_id = $coupon_posts[0]->ID;
            $coupon = new WC_Coupon($coupon_id);

            $include_coupon = $options['include_coupon'];
            if ($order->get_total_discount() > 0 && $include_coupon === 'yes') {
                $coupons  = $order->get_coupon_codes();
                $coupons  = count($coupons) > 0 ? implode(',', $coupons) : '';

                $coupon_label = $options['coupon_label'];
                if ($coupon_label == '') {
                    $voucher_label = "Voucher Code:";
                } else {
                    $voucher_label = $coupon_label;
                }

                if ($coupon->is_type('fixed_cart') || $coupon->is_type('fixed_product')) {
                    $discount_format = html_entity_decode(wp_strip_all_tags(wc_price($coupon->get_amount())));
                    $coupon_code     = "\r\n" . "*" . $voucher_label . "*" . "\r\n" . strtoupper($coupon->get_code()) . ": -" . $discount_format . "";
                    $total_discount  = html_entity_decode(wp_strip_all_tags(wc_price($order->get_discount_total())));
                    $coupon_code     = apply_filters('wa_order_filter_thank_you_page_coupon_code', $coupon_code);
                    $total_discount  = apply_filters('wa_order_filter_thank_you_page_total_discount', $total_discount);
                    $message .= "\r\n" . $coupon_code . "\r\n";
                    if ($total_discount_label) {
                        $discount_label = $total_discount_label;
                        $discount_label = apply_filters('wa_order_filter_thank_you_page_discount_label', $discount_label);
                    } else {
                        $discount_label = __('Total Discount', 'woocommerce');
                        $discount_label = apply_filters('wa_order_filter_thank_you_page_discount_label', $discount_label);
                    }
                    $message .= "*" . $discount_label . ":* " . $total_discount . "\r\n";
                    $numeric_subtotal = $order->get_subtotal();
                    // Get the total discount applied
                    $numeric_total_discount = $order->get_total_discount();
                    $subtotal_minus_discount = $numeric_subtotal - $numeric_total_discount;

                    $subtotal_minus_discount_formatted = html_entity_decode(wp_strip_all_tags(wc_price($subtotal_minus_discount)));
                    $subtlabel = __('Discount', 'woocommerce');
                    $subtcalculatedoutput = html_entity_decode(wp_strip_all_tags(wc_price($numeric_subtotal))) . " - " . $total_discount . " = " . $subtotal_minus_discount_formatted;
                    $message .= "*" . $subtlabel . ":* " . "\r\n" . $subtcalculatedoutput;
                } elseif ($coupon->is_type(array('percent')) || $coupon->is_type('percent')) {
                    $discount_percent = $coupon->get_amount();
                    // Get the subtotal before discount
                    $numeric_subtotal = $order->get_subtotal();

                    // Calculate the discount amount based on percentage
                    $discount_amount = ($discount_percent / 100) * $numeric_subtotal;

                    // Format the discount and total
                    $formatted_discount_amount = html_entity_decode(wp_strip_all_tags(wc_price($discount_amount)));
                    $total_discount = html_entity_decode(wp_strip_all_tags(wc_price($order->get_discount_total())));

                    // Add coupon code and discount details to the message
                    $coupon_code = "\r\n" . "*" . $voucher_label . "*" . "\r\n" . strtoupper($coupon->get_code()) . ": -" . $discount_percent . "% (-" . $formatted_discount_amount . ")";
                    $coupon_code = apply_filters('wa_order_filter_thank_you_page_coupon_code', $coupon_code);
                    $total_discount = apply_filters('wa_order_filter_thank_you_page_total_discount', $total_discount);
                    $message .= "\r\n" . $coupon_code . "\r\n";
                    if ($total_discount_label) {
                        $discount_label = $total_discount_label;
                        $discount_label = apply_filters('wa_order_filter_thank_you_page_discount_label', $discount_label);
                    } else {
                        $discount_label = __('Total Discount', 'woocommerce');
                        $discount_label = apply_filters('wa_order_filter_thank_you_page_discount_label', $discount_label);
                    }
                    $message .= "*" . $discount_label . ":* " . $total_discount;
                } else {
                    $total_discount = html_entity_decode(wp_strip_all_tags(wc_price($order->get_discount_total())));

                    if ($total_discount_label) {
                        $discount_label = $total_discount_label;
                    } else {
                        $discount_label = __('Total Discount', 'woocommerce');
                    }
                    $message .= "*" . $discount_label . ":* " . $total_discount;
                }
            }
        }
    }

    $note = $order->get_customer_note();
    if ($note) {
        $note_label = __('Note:', 'woocommerce');
        $purchase_note = "*" . $note_label . "*" . "\r\n" . $note . "\n\n";
    } else {
        $purchase_note = "";
    }
    $include_tax        = $options['include_tax'];
    $total_tax          = $order->get_total_tax();
    if ($include_tax === 'yes' && $total_tax > 0) {
        $formatted_total_tax =  html_entity_decode(wp_strip_all_tags(wc_price($total_tax)));
        $formatted_total_tax = apply_filters('wa_order_filter_thank_you_page_tax', html_entity_decode(wp_strip_all_tags(wc_price($total_tax))));
        $tax_label           = apply_filters('wa_order_filter_thank_you_page_tax_label', $tax_label);
        $message            .= "\n*" . $tax_label . ":* " . $formatted_total_tax;
    }
    $price              = $order->get_total();
    $total_price        = apply_filters('wa_order_filter_thank_you_page_total_price', html_entity_decode(wp_strip_all_tags(wc_price($price))));
    $label_total        = apply_filters('wa_order_filter_thank_you_page_total_label', "*Total:*");
    $total_price        = "\n\n" . $label_total . "\r\n" . $total_price;
    $message            .= $total_price;

    // Order Summary Link
    $order_summary_link  = apply_filters('wa_order_filter_thank_you_page_order_summary_link', $order->get_checkout_order_received_url());
    $order_summary_label = apply_filters('wa_order_filter_thank_you_page_order_summary_label', $order_summary_label);
    if ($include_order_summary === 'yes') {
        $message .= "\n\n*" . $order_summary_label . ":* \r\n" . $order_summary_link;
    }

    // Payment Link
    $payment_link       = apply_filters('wa_order_filter_thank_you_page_payment_link', $order->get_checkout_payment_url());
    $payment_link_label = apply_filters('wa_order_filter_thank_you_page_payment_link_label', $payment_link_label);
    if ($include_payment_link === 'yes') {
        $message .= "\n\n*" . $payment_link_label . ":* \r\n" . $payment_link;
    }

    // View Order Link
    $order_view_url     = apply_filters('wa_order_filter_thank_you_page_view_order_url', $order->get_view_order_url());
    $view_order_label   = apply_filters('wa_order_filter_thank_you_page_view_order_label', $view_order_label);
    // Check if the order has a user account associated with it or the customer has WooCommerce details
    if ($include_view_order === 'yes' && $customer_id || !empty($order->get_billing_email())) {
        // Include the order view URL
        $message .= "\n\n*" . $view_order_label . ":* \r\n" . $order_view_url;
    }

    // Order Date, Purchase Note & Thank You Message
    $order_date = $options['order_date'];
    if ($order_date !== 'yes') {
        $message .= "\n\n" . $purchase_note . $thanks_label;
    } else {
        $message .= "\n\n" . $purchase_note . $thanks_label . "\n\n(" . $date . ")";
    }
    $message            = apply_filters('wa_order_filter_thank_you_page_final_message', $message);
    // WhatsApp Button
    $button_url         = wa_order_the_url($phonenumb, $message); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped $message;
    $target             = $options['open_new_tab'];
    // Final Output
    $final_output = '<div class="thankyoucustom_wrapper">
    <h2 class="thankyoutitle">' . esc_attr($thetitle) . '</h2>
    <p class="subtitle">' . esc_attr($subtitle) . '</p>
    <a id="sendbtn" href="' . $button_url . '" target="' . esc_attr($target) . '" class="wa-order-thankyou">
        ' . esc_attr($button) . '
    </a>
    </div>';
    return wp_kses_post($final_output);
}
if ($override_thankyou_page === 'yes') {
    add_filter('woocommerce_thankyou_order_received_text', 'wa_order_thank_you_override', 10, 2);
}

// Thank you page default class
function wa_order_remove_default_thankyou_class()
{
    $override_thankyou_page = get_option(sanitize_text_field('wa_order_option_enable_button_thank_you'));
    if ($override_thankyou_page === 'yes') {
?>
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery(".woocommerce-thankyou-order-received").remove();
            });
        </script>
<?php
    }
}
// Remove element based on class
add_action('wp_footer', 'wa_order_remove_default_thankyou_class');
