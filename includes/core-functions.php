<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Get a list of the available conditions for the plugin.
 *
 */
function ltrs_get_method_conditions() {

    $conditions = array(
        __('Cart', 'livemesh-tr-shipping') => array(
            'subtotal' => __('Subtotal', 'livemesh-tr-shipping'),
            'subtotal_ex_tax' => __('Subtotal ex. taxes', 'livemesh-tr-shipping'),
            'tax' => __('Tax', 'livemesh-tr-shipping'),
            'quantity' => __('Quantity', 'livemesh-tr-shipping'),
            'contains_product' => __('Contains product', 'livemesh-tr-shipping'),
            'coupon' => __('Coupon', 'livemesh-tr-shipping'),
            'weight' => __('Weight', 'livemesh-tr-shipping'),
            'contains_shipping_class' => __('Contains shipping class', 'livemesh-tr-shipping'),
        ),
        __('User Details', 'livemesh-tr-shipping') => array(
            'zipcode' => __('Zipcode', 'livemesh-tr-shipping'),
            'city' => __('City', 'livemesh-tr-shipping'),
            'state' => __('State', 'livemesh-tr-shipping'),
            'country' => __('Country', 'livemesh-tr-shipping'),
            'role' => __('User role', 'livemesh-tr-shipping'),
        ),
        __('Product', 'livemesh-tr-shipping') => array(
            'width' => __('Width', 'livemesh-tr-shipping'),
            'height' => __('Height', 'livemesh-tr-shipping'),
            'length' => __('Length', 'livemesh-tr-shipping'),
            'stock' => __('Stock', 'livemesh-tr-shipping'),
            'stock_status' => __('Stock status', 'livemesh-tr-shipping'),
            'category' => __('Category', 'livemesh-tr-shipping'),
        ),
    );
    $conditions = apply_filters('ltrs_conditions', $conditions);

    return $conditions;
}


/**
 * Check if the current page related to Livemesh Table Rate Shipping plugin?
 *
 */
function is_ltrs_page() {
    $return = false;

    // Shipping instance
    if (isset($_GET['tab'], $_GET['instance_id']) && $_GET['tab'] === 'shipping') {
        $instance_id = absint($_GET['instance_id']);
        $shipping_method = WC_Shipping_Zones::get_shipping_method($instance_id);
        if ($shipping_method->id === 'livemesh_table_rate_shipping') {
            $return = true;
        }
    }

    return $return;
}


if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


/**
 * Calculate Table Rates shipping costs based on weight, subtotal, volume or quantity of the items in the cart and the
 * rates provided by the user in the rates table
 *
 */
function ltrs_calculate_table_rate_shipping_cost($cart_amount, $group_subtotal, $rate_args) {

    $input_base_cost = $rate_args['base_cost'];

    $base_cost = str_replace(array('-'), '', $input_base_cost);
    $base_cost = str_replace(',', '.', $base_cost);

    $additional_cost = str_replace(array('-'), '', $rate_args['additional_cost']);
    $additional_cost = str_replace(',', '.', $additional_cost);

    $additional_cost = (float)$additional_cost;
    // Ensure per value is 1 or greater.
    $per_value = max(absint($rate_args['per_value']), 1);
    $threshold_value = absint($rate_args['threshold_value']);

    // If no additional cost is specified, related values do not matter
    if ($additional_cost > 0):
        // Threshold value can be 0 or more
        $additional_cart_amount = $cart_amount - $threshold_value;
        // Per value can be 1 or more. Round up the value as done in other plugins
        $additional_cost_multiplier = ceil($additional_cart_amount / $per_value);
        $cost = $additional_cost * $additional_cost_multiplier;
    endif;

    // Add the base cost at the end
    if (strstr($input_base_cost, '%%')) :
        // Group subtotal percentage
        $percent = (float)str_replace('%%', '', $base_cost);
        $cost += (($group_subtotal / 100) * $percent);
    elseif (strstr($input_base_cost, '%')) :
        // Cart subtotal percentage
        $cart_subtotal = apply_filters('ltrs_get_cart_subtotal', (WC()->cart->cart_contents_total + WC()->cart->get_taxes_total(false, false)));
        $percent = (float)str_replace('%', '', $base_cost);
        $cost += ($cart_subtotal / 100) * $percent;
    else :
        // Flat base rate
        $cost += (float)$base_cost;

    endif;

    return apply_filters('ltrs_calculate_table_rate_shipping_cost', $cost, $cart_amount, $group_subtotal, $rate_args);
}
