<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Handle all admin related functions.
 *
 */
class LTRS_Admin {


    /**
     * Constructor.
     *
     */
    public function __construct() {

        // Add to WC Screen IDs to load scripts.
        add_filter('woocommerce_screen_ids', array($this, 'add_screen_ids'));

        // Enqueue scripts
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

        // Help tab
        add_action('current_screen', array($this, 'add_help_tab'), 90);

    }


    /**
     * Add 'ltrs' to the screen IDs so the WooCommerce scripts are loaded.
     *
     * @param array $screen_ids List of existing screen IDs.
     * @return array             List of modified screen IDs.
     *
     */
    public function add_screen_ids($screen_ids) {

        $screen_ids[] = 'ltrs';

        return $screen_ids;

    }


    /**
     * Enqueue scripts and styles
     *
     */
    public function admin_enqueue_scripts() {

        // Use minified libraries if LTRS_SCRIPT_DEBUG is turned off
        $suffix = (defined('LTRS_SCRIPT_DEBUG') && LTRS_SCRIPT_DEBUG) ? '' : '.min';

        // Style script
        wp_register_style('livemesh-table-rate-shipping', LTRS_PLUGIN_URL . 'assets/css/livemesh-table-rate-shipping' . $suffix . '.css', array(), LTRS_VERSION);

        // Javascript
        wp_register_script('livemesh-table-rate-shipping', LTRS_PLUGIN_URL . 'assets/js/livemesh-table-rate-shipping' . $suffix . '.js', array('jquery', 'jquery-ui-sortable', 'jquery-blockui', 'jquery-tiptip'), LTRS_VERSION, true);

        // Only load scripts on relevant pages
        if (isset($_REQUEST['tab']) && 'shipping' === $_REQUEST['tab']) :

            wp_enqueue_style('livemesh-table-rate-shipping');
            wp_enqueue_script('livemesh-table-rate-shipping');

            wp_enqueue_script('lwc-conditions');

            wp_localize_script('lwc-conditions', 'lwc2', array(
                'action_prefix' => 'ltrs_',
            ));
            wp_localize_script('livemesh-table-rate-shipping', 'ltrs', array(
                'nonce' => wp_create_nonce('livemesh-shipping'),
                'rate_id' => get_the_ID(),
            ));

            wp_dequeue_script('autosave');

        endif;

    }

    /**
     * Add help tab on Livemesh Table Rate Shipping related pages.
     *
     */
    public function add_help_tab() {
        $screen = get_current_screen();

        if (!$screen || !is_ltrs_page()) {
            return;
        }

        $screen->add_help_tab(array(
            'id' => 'livemesh_table_rate_shipping_help',
            'title' => __('Livemesh Table Rate Shipping', 'livemesh-tr-shipping'),
            'content' => '<h2>' . __('Livemesh Table Rate Shipping', 'livemesh-tr-shipping') . '</h2>' .
                '<p>
						<strong>' . __('Where do I configure my Livemesh Table Rate Shipping rates?', 'livemesh-tr-shipping') . '</strong><br/>' .
                __('Livemesh Table Rate Shipping rates can be setup within the shipping zones', 'livemesh-tr-shipping') .
                '</p>
					<p>
						<a href="https://livemeshwp.com/table-rate-shipping/doc/faq/" target="_blank" class="button">' . __('Frequently Asked Questions', 'livemesh-tr-shipping') . '</a>
						<a href="https://livemeshwp.com/table-rate-shipping/doc/" class="button button-primary" target="_blank">' . __('Online documentation', 'livemesh-tr-shipping') . '</a>
						<a href="https://livemeshwp.com/table-rate-shipping/support/" target="_blank" class="button">' . __('Contact support', 'livemesh-tr-shipping') . '</a>
					</p>',
        ));

        $screen->set_help_sidebar(
            '<p><strong>' . __('More links', 'livemesh-tr-shipping') . '</strong></p>' .
            '<p><a href="https://livemeshwp.com/table-rate-shipping/pricing" target="_blank">' . __('Purchase/renew license', 'livemesh-tr-shipping') . '</a></p>' .
            '<p><a href="https://livemeshwp.com/table-rate-shipping/doc/" target="_blank">' . __('Documentation', 'livemesh-tr-shipping') . '</a></p>' .
            '<p><a href="https://livemeshwp.com/table-rate-shipping/support/" target="_blank">' . __('Support', 'livemesh-tr-shipping') . '</a></p>' .
            '<p><a href="https://livemeshwp.com/" target="_blank">' . __('More plugins by the Author', 'livemesh-tr-shipping') . '</a></p>'
        );

        // Make sure to not show Woo help to not confuse users
        $screen->remove_help_tab('woocommerce_support_tab');
        $screen->remove_help_tab('woocommerce_bugs_tab');
        $screen->remove_help_tab('woocommerce_education_tab');
        $screen->remove_help_tab('woocommerce_onboard_tab');
    }
}
