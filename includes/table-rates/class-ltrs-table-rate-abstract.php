<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
/**
 * Class LTRS_Table_Rate_Abstract.
 *
 * Abstract class to add new LTRS Rate Option.
 *
 */
abstract class LTRS_Table_Rate_Abstract
{
    /**
     * ID of the Rate option
     *
     */
    public  $id ;
    /**
     * Name of the Rate option.
     *
     */
    public  $name ;
    public function __construct()
    {
    }
    
    /**
     * Get rate type options.
     *
     */
    protected function get_rate_type_options()
    {
        $rate_types = array(
            'subtotal_rate' => __( 'Subtotal Rate', 'livemesh-tr-shipping' ),
            'volume_rate'   => __( 'Volume Rate', 'livemesh-tr-shipping' ),
            'quantity_rate' => __( 'Quantity Rate', 'livemesh-tr-shipping' ),
        );
        return apply_filters( 'lwbs_rate_type_options', $rate_types );
    }
    
    /**
     * Get the table rates set by the user for the rate id provided
     *
     */
    public function get_table_rates( $rate_id = null )
    {
        
        if ( isset( $_GET['instance_id'] ) || strpos( $rate_id, ':' ) ) {
            $instance_id = ( isset( $_GET['instance_id'] ) ? sanitize_key( $_GET['instance_id'] ) : substr( $rate_id, strpos( $rate_id, ':' ) + 1 ) );
            if ( $shipping_method = WC_Shipping_Zones::get_shipping_method( absint( $instance_id ) ) ) {
                return $shipping_method->get_instance_option( 'table_rates_' . $this->id );
            }
        }
        
        return null;
    }
    
    /**
     * Output the settings HTML for this rate option.
     *
     */
    abstract function output();
    
    /**
     * Abstract method to override for calculating shipping costs for the rate option chosen.
     * Returns the cost only if min/max values are met
     *
     *
     */
    abstract function calculate_table_rates_shipping_cost( $shipping_rate_id, $package );
    
    /**
     * Get the amount to compare the min/max values against.
     *
     * - The min/max field requirement is based on the subtotal, weight, volume and quantity of the relevant products.
     *
     */
    public function get_cart_amount( $package, $value = null, $rate_type = null )
    {
        $amount = 0;
        foreach ( $this->get_relevant_products( $package, $value ) as $cart_key => $item ) {
            
            if ( $rate_type === 'weight_rate' && !empty($item['data']->get_weight()) ) {
                $amount += $item['data']->get_weight() * $item['quantity'];
            } elseif ( $rate_type === 'subtotal_rate' ) {
                $amount += $item['data']->get_price() * $item['quantity'];
            } elseif ( $rate_type === 'volume_rate' ) {
                $product = wc_get_product( $item['data']->get_id() );
                $amount += (double) ($product->get_width() * $product->get_height() * $product->get_length()) * $item['quantity'];
            } else {
                $amount += $item['quantity'];
            }
        
        }
        return $amount;
    }
    
    /**
     * Get the relevant products from the cart which match the product, category
     * or shipping class specified for shipping costs calculation.
     * By default, it returns all products in the cart as required by rate by default option.
     *
     *
     */
    public function get_relevant_products( $package, $value = null )
    {
        $relevant_products = array();
        foreach ( $package['contents'] as $cart_key => $item ) {
            $relevant_products[$cart_key] = $item;
        }
        return $relevant_products;
    }

}