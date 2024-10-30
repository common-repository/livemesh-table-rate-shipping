<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

if (!class_exists('Livemesh_Table_Rate_Shipping')) :

    /**
     * Main Livemesh_Table_Rate_Shipping Class
     *
     */
    final class Livemesh_Table_Rate_Shipping {

        /** Singleton *************************************************************/

        private static $instance;

        /**
         * @var object|Livemesh_Table_Rate_Shipping_Method
         */
        public $shipping_method;

        /**
         * @var object|LTRS_Ajax_Helper
         */
        public $ajax_helper;

        /**
         * @var object|LTRS_Admin
         */
        public $admin;

        /**
         * @var object|LTRS_Table_Rates_Helper
         */
        public $table_rates_helper;

        /**
         * Livemesh_Table_Rate_Shipping Singleton Instance
         *
         * Allow only one instance of the class to be created.
         */
        public static function instance() {

            if (!isset(self::$instance) && !(self::$instance instanceof Livemesh_Table_Rate_Shipping)) {

                self::$instance = new Livemesh_Table_Rate_Shipping;

                if (!self::$instance->is_woocommerce_active())
                    return;

                self::$instance->setup_debug_constants();

                add_action('plugins_loaded', array(self::$instance, 'load_plugin_textdomain'));

                self::$instance->includes();

                self::$instance->hooks();

                self::$instance->init();

            }
            return self::$instance;
        }

        /**
         * Throw error if someone tries to clone the object since this is a singleton class
         *
         */
        public function __clone() {
            // Cloning instances of the class is forbidden
            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'livemesh-tr-shipping'), '1.2');
        }

        /**
         * Disable deserialization
         */
        public function __wakeup() {

            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'livemesh-tr-shipping'), '1.2');
        }

        private function is_woocommerce_active(): bool {

            if (!function_exists('is_plugin_active'))
                require_once(ABSPATH . '/wp-admin/includes/plugin.php');

            if (!is_plugin_active('woocommerce/woocommerce.php') && !function_exists('WC')) {

                add_action('admin_notices', array($this, 'woocommerce_required_notice'));

                return false;
            }

            return true;
        }

        public function woocommerce_required_notice() {

            $class = 'notice notice-error';

            $message = esc_html__('WooCommerce is required for Livemesh Table Rate Shipping plugin to work. Please install or activate WooCommerce plugin', 'livemesh-tr-shipping');

            printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);

        }

        /**
         * Setup debug constants required for the plugin
         */
        private function setup_debug_constants() {

            $enable_debug = true;

            $settings = get_option('ltrs_settings');

            if ($settings && isset($settings['ltrs_enable_debug']) && $settings['ltrs_enable_debug'] == "true")
                $enable_debug = true;

            // Enable script debugging
            if (!defined('LTRS_SCRIPT_DEBUG')) {
                define('LTRS_SCRIPT_DEBUG', $enable_debug);
            }

            // Minified JS file name suffix
            if (!defined('LTRS_JS_SUFFIX')) {
                if ($enable_debug)
                    define('LTRS_JS_SUFFIX', '');
                else
                    define('LTRS_JS_SUFFIX', '.min');
            }
        }

        /**
         * Include required files
         *
         */
        private function includes() {

            require_once LTRS_PLUGIN_DIR . '/includes/core-functions.php';

            require_once LTRS_PLUGIN_DIR . '/includes/method-conditions/functions.php';

            require_once LTRS_PLUGIN_DIR . '/includes/class-ltrs-table-rates-helper.php';

        }

        /**
         * Load Plugin Text Domain
         *
         * Looks for the plugin translation files in certain directories and loads
         * them to allow the plugin to be localised
         */
        public function load_plugin_textdomain() {

            $lang_dir = apply_filters('ltrs_lang_dir', trailingslashit(LTRS_PLUGIN_DIR . 'languages'));

            // Traditional WordPress plugin locale filter
            $locale = apply_filters('plugin_locale', get_locale(), 'livemesh-tr-shipping');
            $mofile = sprintf('%1$s-%2$s.mo', 'livemesh-tr-shipping', $locale);

            // Setup paths to current locale file
            $mofile_local = $lang_dir . $mofile;

            if (file_exists($mofile_local)) {
                // Look in the /wp-content/plugins/livemesh-table-rate-shipping/languages/ folder
                load_textdomain('livemesh-tr-shipping', $mofile_local);
            }
            else {
                // Load the default language files
                load_plugin_textdomain('livemesh-tr-shipping', false, $lang_dir);
            }

            return false;
        }

        /**
         * Setup the default hooks and actions
         */
        private function hooks() {

            // Initialize shipping method class
            add_action('woocommerce_shipping_init', array($this, 'init_shipping_method'));

            // Add shipping method
            add_filter('woocommerce_shipping_methods', array($this, 'add_shipping_method'));

        }

        private function init() {

            $this->table_rates_helper = new LTRS_Table_Rates_Helper();

            if (defined('DOING_AJAX') && DOING_AJAX) {
                require_once LTRS_PLUGIN_DIR . '/includes/class-ltrs-ajax-helper.php';
                $this->ajax_helper = new LTRS_Ajax_Helper();
            }

            if (is_admin()) {
                require_once LTRS_PLUGIN_DIR . '/includes/admin/class-ltrs-condition.php';
                require_once LTRS_PLUGIN_DIR . '/includes/admin/class-ltrs-admin.php';
                $this->admin = new LTRS_Admin();
            }
        }


        /**
         * Initialize shipping method.
         *
         */
        public function init_shipping_method() {

            require_once LTRS_PLUGIN_DIR . '/includes/class-ltrs-shipping-method.php';

            $this->shipping_method = new Livemesh_Table_Rate_Shipping_Method();

        }

        /**
         * Add shipping method to the available shipping methods in WooCommerce
         *
         */
        public function add_shipping_method($methods) {

            if (class_exists('Livemesh_Table_Rate_Shipping_Method')) {
                $methods['livemesh_table_rate_shipping'] = 'Livemesh_Table_Rate_Shipping_Method';
            }
            return $methods;
        }
    }

endif; // End if class_exists check


/**
 * The main function responsible for returning the one true Livemesh_Table_Rate_Shipping
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 */
function LTRS() {
    return Livemesh_Table_Rate_Shipping::instance();
}

// Get LTRS Running
LTRS();