<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
/**
 * Class LTRS_Table_Rates_Helper.
 *
 * Helps initialize the table rates sections like rate by default, category, shipping class or a product.
 *
 */
class LTRS_Table_Rates_Helper
{
    public function __construct()
    {
        $this->load_files();
        // Initialize pricing options
        add_action( 'init', array( $this, 'load_table_rates_options' ) );
    }
    
    /**
     * Load the files for the table rates sections
     *
     */
    public function load_files()
    {
        // Load abstract
        require_once LTRS_PLUGIN_DIR . '/includes/table-rates/class-ltrs-table-rate-abstract.php';
        require_once LTRS_PLUGIN_DIR . '/includes/table-rates/class-ltrs-table-rate-per-default.php';
        // Allow 3rd party to load at this point
        do_action( 'ltrs_load_table_rate_files' );
    }
    
    /**
     * Returns the instances of classes responsible for their respective table rates sections
     */
    public function load_table_rates_options()
    {
        $registered_table_rates_options = apply_filters( 'ltrs_registered_table_rates_options', array( 'rate_per_default' ) );
        $table_rates_options = array();
        foreach ( $registered_table_rates_options as $table_rates_option ) {
            
            if ( $class_name = $this->table_rates_option_class_name_from_type( $table_rates_option ) ) {
                $class = new $class_name();
                $table_rates_options[$class->id] = $class;
            }
        
        }
        return $table_rates_options;
    }
    
    /**
     * Get the class for the table rates option provided as the parameter
     *
     */
    public function table_rates_option_class_name_from_type( $option = '' )
    {
        $class_name = 'LTRS_Table_' . implode( '_', array_map( 'ucfirst', explode( '_', $option ) ) );
        if ( !class_exists( $class_name ) ) {
            return false;
        }
        return $class_name;
    }
    
    /**
     * Get all available table rates options
     */
    public function get_table_rates_options()
    {
        return $this->load_table_rates_options();
    }

}