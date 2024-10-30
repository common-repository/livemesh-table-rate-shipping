<?php

/**
 * Plugin Name: Table Rate Shipping for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/livemesh-table-rate-shipping/
 * Description: Discover the most intuitive yet flexible way to set conditional table shipping rates for WooCommerce.
 * Author: Livemesh
 * Author URI: https://livemeshwp.com/woocommerce-table-rate-shipping/
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Version: 1.2
 * WC requires at least: 5.2
 * WC tested up to: 6.9
 * Text Domain: livemesh-tr-shipping
 * Domain Path: languages
 *
 * Table Rate Shipping for WooCommerce by Livemesh is distributed under the terms of the GNU
 * General Public License as published by the Free Software Foundation,
 * either version 2 of the License, or any later version.
 *
 * Table Rate Shipping for WooCommerce by Livemesh is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Table Rate Shipping for WooCommerce by Livemesh. If not, see <http://www.gnu.org/licenses/>.
 *
 *
 *
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'ltrs_fs' ) ) {
    ltrs_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    // Ensure the free version is deactivated if premium is running
    
    if ( !function_exists( 'ltrs_fs' ) ) {
        // Plugin version
        define( 'LTRS_VERSION', '1.2' );
        // Plugin Root File
        define( 'LTRS_PLUGIN_FILE', __FILE__ );
        // Plugin Folder Path
        define( 'LTRS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
        define( 'LTRS_PLUGIN_SLUG', dirname( plugin_basename( __FILE__ ) ) );
        // Plugin Folder URL
        define( 'LTRS_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
        // Plugin Help Page URL
        define( 'LTRS_PLUGIN_HELP_URL', admin_url() . 'admin.php?page=livemesh_wc_shipping_documentation' );
        // Create a helper function for easy SDK access.
        function ltrs_fs()
        {
            global  $ltrs_fs ;
            
            if ( !isset( $ltrs_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $ltrs_fs = fs_dynamic_init( array(
                    'id'             => '10049',
                    'slug'           => 'livemesh-table-rate-shipping',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_58784336f244e33e56b565d6054a0',
                    'is_premium'     => false,
                    'premium_suffix' => 'Pro',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'menu'           => array(
                    'first-path' => 'plugins.php',
                    'support'    => false,
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $ltrs_fs;
        }
        
        // Init Freemius.
        ltrs_fs();
        // Signal that SDK was initiated.
        do_action( 'ltrs_fs_loaded' );
    }
    
    require_once dirname( __FILE__ ) . '/plugin.php';
}
