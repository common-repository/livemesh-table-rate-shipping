<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Display the shipping settings in shipping method edit page
 *
 */


?>
<div class='ltrs ltrs_settings ltrs_shipping_settings'>

    <p class='ltrs-option ltrs-shipping-title'>
        <label for='shipping_title'><?php _e('Shipping title', 'livemesh-tr-shipping'); ?></label>
        <input
                type='text'
                class=''
                id='shipping_title'
                name='_ltrs_shipping_method[shipping_title]'
                style='width: 190px;'
                value='<?php echo esc_attr($shipping_title); ?>'
                placeholder='<?php _e('E.g. Expedited shipping', 'livemesh-tr-shipping'); ?>'
        >
    </p>

    <p class='ltrs-option'>
        <label for='taxable'><?php _e('Tax status', 'livemesh-tr-shipping'); ?></label>
        <select name='_ltrs_shipping_method[taxable]' style='width: 189px;'>
            <option value='taxable' <?php selected($taxable, 'taxable'); ?>><?php _e('Taxable', 'livemesh-tr-shipping'); ?></option>
            <option value='not_taxable' <?php selected($taxable, 'not_taxable'); ?>><?php _e('Not taxable', 'livemesh-tr-shipping'); ?></option>
        </select>
    </p>

    <p class='ltrs-option'>
        <label for='minimum_cost'><?php _e('Minimum cost', 'livemesh-tr-shipping'); ?></label>
        <span class='lwc-currency'><?php echo get_woocommerce_currency_symbol(); ?></span>
        <input
                type='text'
                step='any'
                class='wc_input_price'
                id='minimum_cost'
                name='_ltrs_shipping_method[minimum_cost]'
                value='<?php echo esc_attr(wc_format_localized_price($minimum_cost)); ?>'
                placeholder='0'>
        <img class="help_tip"
             data-tip="<?php _e('Set a minimum rate to be set if the calculated rate is lower than the minimum set here.<br/>Leave zero to not set a minimum.', 'livemesh-tr-shipping'); ?>"
             src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16"/>
    </p>

    <p class='ltrs-option'>
        <label for='maximum_cost'><?php _e('Maximum cost', 'livemesh-tr-shipping'); ?></label>
        <span class='lwc-currency'><?php echo get_woocommerce_currency_symbol(); ?></span>
        <input
                type='text'
                step='any'
                class='wc_input_price'
                id='maximum_cost'
                name='_ltrs_shipping_method[maximum_cost]'
                value='<?php echo esc_attr(wc_format_localized_price($maximum_cost)); ?>'
                placeholder='0'>
        <img class="help_tip"
             data-tip="<?php _e('Set a maximum rate to be set if the calculated rate is higher than the maximum set here.<br/>Leave zero to not set a maximum.', 'livemesh-tr-shipping'); ?>"
             src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16"/>
    </p>

    <h3 style='padding-left:0;'><?php _e('Table Rates', 'livemesh-tr-shipping'); ?></h3>

    <div class='ltrs-table-rates-settings-wrap'><?php

        $table_rates_options = LTRS()->table_rates_helper->get_table_rates_options();

        $first_option = reset($table_rates_options);
        ?>
        <div class='ltrs-table-rates-settings'>

            <div class='inside'>

                <div class='ltrs-table-rates-sections-wrap'>

                    <div class='ltrs-table-rates-sections'><?php

                        /** @var LTRS_Table_Rate_Abstract $table_rates_option */
                        foreach ($table_rates_options as $table_rates_option) :
                            $name = $table_rates_option->name;
                            ?>
                        <div id='<?php echo esc_attr($table_rates_option->id); ?>' class='ltrs-table-rates-section'>
                            <h3 class='ltrs-table-rate-option'><?php echo esc_html($name); ?></h3><?php
                            $table_rates_option->output();
                            ?></div><?php
                        endforeach;

                        ?></div>

                    <div class='clear'></div>

                </div>

            </div>

        </div>

    </div>

    <?php if (ltrs_fs()->is_not_paying()) : ?>
        <section class="ltrs-upgrade-notice">
            <h3><?php echo esc_html__('Awesome Premium Features with top-notch support!', 'livemesh-tr-shipping'); ?></h3>
            <p><?php echo esc_html__('Set table rates specific to Shipping Class, Category or a Product! Also, set shipping rates based on weight of the items in the cart.', 'livemesh-tr-shipping'); ?></p>
            <a href="<?php echo ltrs_fs()->get_upgrade_url(); ?>"><?php echo __('Upgrade to the Premium Version!', 'livemesh-tr-shipping'); ?></a>
        </section>
    <?php endif; ?>

</div>
