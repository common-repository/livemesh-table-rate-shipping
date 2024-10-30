<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class LTRS_Table_Rate_Per_Default.
 *
 * Rate per default pricing option.
 *
 * @class       LTRS_Table_Rate_Per_Default
 */
class LTRS_Table_Rate_Per_Default extends LTRS_Table_Rate_Abstract {


	/**
	 * Constructor.
	 *
	 */
	public function __construct() {

		$this->id   = 'rate_per_default';
		$this->name = __( 'Default Rates', 'livemesh-tr-shipping' );

		parent::__construct();

	}

    /**
     * Output the settings HTML related for this rate option.
     *
     */
	public function output() {

		$table_rates = $this->get_table_rates();

		?><div class='rate-per-default-wrap'>

        <div class='repeater-header ltrs-input-group-list'>
            <div class='ltrs-input-group ltrs-input-group-rate-type'>
                <label>
                    <span class='label-text'><?php _e('Rate Type', 'livemesh-tr-shipping'); ?></span>
                    <img class="help_tip"
                         data-tip="<?php _e('Choose the rate type.<br/>By default, subtotal or price is assumed.', 'livemesh-tr-shipping'); ?>"
                         src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16"/>
                </label>
            </div>
            <div class='ltrs-input-group ltrs-input-group-min-value'>
                <label>
                    <span class='label-text'><?php _e('Min', 'livemesh-tr-shipping'); ?></span>
                    <img class="help_tip"
                         data-tip="<?php _e('Set a minimum for total amount (volume, subtotal or quantity) of products per row before the rate specified is applied.<br/>Leave empty to not set a minimum.', 'livemesh-tr-shipping'); ?>"
                         src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16"/>
                </label>
            </div>
            <div class='ltrs-input-group ltrs-input-group-max-value'>
                <label>
                    <span class='label-text'><?php _e('Max', 'livemesh-tr-shipping'); ?></span>
                    <img class="help_tip"
                         data-tip="<?php _e('Set a maximum for total amount (volume, subtotal or quantity) of products per row before the rate specified is applied.<br/>Leave empty to not set a maximum.', 'livemesh-tr-shipping'); ?>"
                         src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16"/>
                </label>
            </div>
            <div class='ltrs-input-group ltrs-input-group-basic-cost'>
                <label>
                    <span class='label-text'><?php _e('Basic Cost', 'livemesh-tr-shipping'); ?></span>
                    <img class="help_tip"
                         data-tip="<?php _e('Set a basic rate to be applied for shipping items in the cart. Options are:<br/>5 : Flat amount<br/>5% : percentage of subtotal', 'livemesh-tr-shipping'); ?>"
                         src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16"/>
                </label>
            </div>
            <div class='ltrs-input-group ltrs-input-group-additional-cost'>
                <label>
                    <span class='label-text'><?php _e('Additional Cost', 'livemesh-tr-shipping'); ?></span>
                    <img class="help_tip"
                         data-tip="<?php _e('Specify additional rate to be applied for shipping items in the cart (optional)', 'livemesh-tr-shipping'); ?>"
                         src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16"/>
                </label>
            </div>
            <div class='ltrs-input-group ltrs-input-group-per-value'>
                <label>
                    <span class='label-text'><?php _e('Per', 'livemesh-tr-shipping'); ?></span>
                    <img class="help_tip"
                         data-tip="<?php _e('Provide a value that needs to be multiplied by Additional Cost set for the products in the cart (optional)', 'livemesh-tr-shipping'); ?>"
                         src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16"/>
                </label>
            </div>
            <div class='ltrs-input-group ltrs-input-group-threshold-value'>
                <label>
                    <span class='label-text'><?php _e('Over', 'livemesh-tr-shipping'); ?></span>
                    <img class="help_tip"
                         data-tip="<?php _e('Specify a threshold value that needs to be met before the Additional Cost multiplied by Per Value takes effect. (optional)', 'livemesh-tr-shipping'); ?>"
                         src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16"/>
                </label>
            </div>

            <div class='ltrs-input-group-inline'></div>
        </div>
			<div class='repeater-wrap'><?php

                $volume_unit = get_option( 'woocommerce_dimension_unit' );
                $currency_symbol = get_woocommerce_currency_symbol();

				$i = 0;
				if ( is_array( $table_rates ) ) :
					foreach ( $table_rates as $values ) :

						$i++;
                        $rate_type  = isset( $values['condition']['rate_type'] ) ? esc_attr( $values['condition']['rate_type'] ) : 'subtotal_rate';
						$min  = isset( $values['condition']['min'] ) ? esc_attr( $values['condition']['min'] ) : '';
						$max  = isset( $values['condition']['max'] ) ? esc_attr( $values['condition']['max'] ) : '';
						$base_cost = isset( $values['action']['base_cost'] ) ? esc_attr( $values['action']['base_cost'] ) : '';
                        $additional_cost = isset( $values['action']['additional_cost'] ) ? esc_attr( $values['action']['additional_cost'] ) : '';
                        $per_value = isset( $values['action']['per_value'] ) ? esc_attr( $values['action']['per_value'] ) : '';
                        $threshold_value = isset( $values['action']['threshold_value'] ) ? esc_attr( $values['action']['threshold_value'] ) : '';

						?>
                        <div class='rate-per-default-option repeater-row ltrs-input-group-list'>
                            <div class='ltrs-input-group  ltrs-input-group-rate-type'>
                                <select class='ltrs-select-rate-type rate-per-default-rate-type' name='table_rates_<?php echo esc_attr($this->id); ?>[<?php echo absint($i); ?>][condition][rate_type]'>
                                    <?php $options = $this->get_rate_type_options(); ?>
                                    <?php
                                    foreach ($options as $key => $value) {
                                        echo "<option value='" . esc_attr($key) . "' " . selected($rate_type, $key, false) . ">" . esc_html($value) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
							<div class='ltrs-input-group ltrs-input-group-min-value'>
                                <span class='ltrs-input-addon ltrs-input-addon-right'  data-rate-type='volume_rate'><?php echo esc_html ($volume_unit); ?><sup>3</sup></span>
                                <span class='ltrs-input-addon ltrs-input-addon-right'  data-rate-type='quantity_rate'><?php echo esc_html__('qty', 'livemesh-tr-shipping'); ?></span>
                                <span class='ltrs-input-addon ltrs-input-addon-left'  data-rate-type='subtotal_rate'><?php echo esc_html($currency_symbol); ?></span>
								<input type='text' class='rate-per-default-min ltrs_input_decimal' name='table_rates_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][condition][min]' value='<?php echo esc_attr($min); ?>'>
							</div>
							<div class='ltrs-input-group ltrs-input-group-max-value'>
                                <span class='ltrs-input-addon ltrs-input-addon-right'  data-rate-type='volume_rate'><?php echo esc_html ($volume_unit); ?><sup>3</sup></span>
                                <span class='ltrs-input-addon ltrs-input-addon-right'  data-rate-type='quantity_rate'><?php echo esc_html__('qty', 'livemesh-tr-shipping'); ?></span>
                                <span class='ltrs-input-addon ltrs-input-addon-left'  data-rate-type='subtotal_rate'><?php echo esc_html($currency_symbol); ?></span>
								<input type='text' class='rate-per-default-max ltrs_input_decimal' name='table_rates_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][condition][max]' value='<?php echo esc_attr($max); ?>'>
							</div>
							<div class='ltrs-input-group ltrs-input-group-basic-cost'>
                                <span class='ltrs-input-addon ltrs-input-addon-left'><?php echo esc_html($currency_symbol); ?></span>
								<input type='text' class='rate-per-default-cost ltrs_input_price' name='table_rates_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][action][base_cost]' value='<?php echo esc_attr( wc_format_localized_price( $base_cost ) ); ?>' placeholder='0'>
							</div>
                        <div class='ltrs-input-group ltrs-input-group-additional-cost'>
                            <span class='ltrs-input-addon ltrs-input-addon-left'><?php echo esc_html($currency_symbol); ?></span>
                            <input type='text' class='rate-per-default-additional-cost ltrs_input_price' name='table_rates_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][action][additional_cost]' value='<?php echo esc_attr( wc_format_localized_price( $additional_cost ) ); ?>' placeholder='0'>
                        </div>
                        <div class='ltrs-input-group  ltrs-input-group-per-value'>
                            <span class='ltrs-input-addon ltrs-input-addon-right'  data-rate-type='volume_rate'><?php echo esc_html ($volume_unit); ?><sup>3</sup></span>
                            <span class='ltrs-input-addon ltrs-input-addon-right'  data-rate-type='quantity_rate'><?php echo esc_html__('qty', 'livemesh-tr-shipping'); ?></span>
                            <span class='ltrs-input-addon ltrs-input-addon-left'  data-rate-type='subtotal_rate'><?php echo esc_html($currency_symbol); ?></span>
                            <input type='text' class='rate-per-default-per-value ltrs_input_decimal' name='table_rates_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][action][per_value]' value='<?php echo esc_attr($per_value); ?>' placeholder='1'>
                        </div>
                        <div class='ltrs-input-group ltrs-input-group-threshold-value'>
                            <span class='ltrs-input-addon ltrs-input-addon-right'  data-rate-type='volume_rate'><?php echo esc_html ($volume_unit); ?><sup>3</sup></span>
                            <span class='ltrs-input-addon ltrs-input-addon-right'  data-rate-type='quantity_rate'><?php echo esc_html__('qty', 'livemesh-tr-shipping'); ?></span>
                            <span class='ltrs-input-addon ltrs-input-addon-left'  data-rate-type='subtotal_rate'><?php echo esc_html($currency_symbol); ?></span>
                            <input type='text' class='rate-per-default-threshold-value ltrs_input_decimal' name='table_rates_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][action][threshold_value]' value='<?php echo esc_attr($threshold_value); ?>' placeholder='0'>
                        </div>
							<div class='ltrs-input-group-inline'>
								<span class='dashicons dashicons-no-alt delete-repeater-row' style='line-height: 29px;'></span>
							</div>

						</div><?php

					endforeach;
				else :

					?><div class='rate-per-default-option repeater-row ltrs-input-group-list'>
                        <div class='ltrs-input-group  ltrs-input-group-rate-type'>
                            <select class='ltrs-select-rate-type rate-per-default-rate-type' name='table_rates_<?php echo esc_attr($this->id); ?>[<?php echo absint($i); ?>][condition][rate_type]'>
                                <?php $options = $this->get_rate_type_options(); ?>
                                <?php
                                foreach ($options as $key => $value) {
                                    echo "<option value='" . esc_attr($key) . "' " . selected($key, 'subtotal_rate', false) . ">" . esc_html($value) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
						<div class='ltrs-input-group ltrs-input-group-min-value'>
                            <span class='ltrs-input-addon ltrs-input-addon-right'  data-rate-type='volume_rate'><?php echo esc_html ($volume_unit); ?><sup>3</sup></span>
                            <span class='ltrs-input-addon ltrs-input-addon-right'  data-rate-type='quantity_rate'><?php echo esc_html__('qty', 'livemesh-tr-shipping'); ?></span>
                            <span class='ltrs-input-addon ltrs-input-addon-left'  data-rate-type='subtotal_rate'><?php echo esc_html($currency_symbol); ?></span>
							<input type='text' class='rate-per-default-min ltrs_input_decimal' name='table_rates_<?php echo esc_attr( $this->id ); ?>[0][condition][min]' value=''>
						</div>
						<div class='ltrs-input-group ltrs-input-group-max-value'>
                            <span class='ltrs-input-addon ltrs-input-addon-right'  data-rate-type='volume_rate'><?php echo esc_html ($volume_unit); ?><sup>3</sup></span>
                            <span class='ltrs-input-addon ltrs-input-addon-right'  data-rate-type='quantity_rate'><?php echo esc_html__('qty', 'livemesh-tr-shipping'); ?></span>
                            <span class='ltrs-input-addon ltrs-input-addon-left'  data-rate-type='subtotal_rate'><?php echo esc_html($currency_symbol); ?></span>
							<input type='text' class='rate-per-default-max ltrs_input_decimal' name='table_rates_<?php echo esc_attr( $this->id ); ?>[0][condition][max]' value=''>
						</div>
						<div class='ltrs-input-group ltrs-input-group-basic-cost'>
                            <span class='ltrs-input-addon ltrs-input-addon-left'><?php echo esc_html($currency_symbol); ?></span>
							<input type='text' class='rate-per-default-cost ltrs_input_price' name='table_rates_<?php echo esc_attr( $this->id ); ?>[0][action][base_cost]' value='' placeholder='0'>
						</div>
                    <div class='ltrs-input-group ltrs-input-group-additional-cost'>
                        <span class='ltrs-input-addon ltrs-input-addon-left'><?php echo esc_html($currency_symbol); ?></span>
                        <input type='text' class='rate-per-default-additional-cost ltrs_input_price' name='table_rates_<?php echo esc_attr( $this->id ); ?>[0][action][additional_cost]' value='' placeholder='0'>
                    </div>
                    <div class='ltrs-input-group  ltrs-input-group-per-value'>
                        <span class='ltrs-input-addon ltrs-input-addon-right'  data-rate-type='volume_rate'><?php echo esc_html ($volume_unit); ?><sup>3</sup></span>
                        <span class='ltrs-input-addon ltrs-input-addon-right'  data-rate-type='quantity_rate'><?php echo esc_html__('qty', 'livemesh-tr-shipping'); ?></span>
                        <span class='ltrs-input-addon ltrs-input-addon-left'  data-rate-type='subtotal_rate'><?php echo esc_html($currency_symbol); ?></span>
                        <input type='text' class='rate-per-default-per-value ltrs_input_decimal' name='table_rates_<?php echo esc_attr( $this->id ); ?>[0][action][per_value]' value='' placeholder='1'>
                    </div>
                    <div class='ltrs-input-group ltrs-input-group-threshold-value'>
                        <span class='ltrs-input-addon ltrs-input-addon-right'  data-rate-type='volume_rate'><?php echo esc_html ($volume_unit); ?><sup>3</sup></span>
                        <span class='ltrs-input-addon ltrs-input-addon-right'  data-rate-type='quantity_rate'><?php echo esc_html__('qty', 'livemesh-tr-shipping'); ?></span>
                        <span class='ltrs-input-addon ltrs-input-addon-left'  data-rate-type='subtotal_rate'><?php echo esc_html($currency_symbol); ?></span>
                        <input type='text' class='rate-per-default-threshold-value ltrs_input_decimal' name='table_rates_<?php echo esc_attr( $this->id ); ?>[0][action][threshold_value]' value='' placeholder='0'>
                    </div>

                    <div class='ltrs-input-group-inline'>
                        <span class='dashicons dashicons-no-alt delete-repeater-row' style='line-height: 29px;'></span>
                    </div>

					</div><?php

				endif;

			?></div>

			<a href='javascript:void(0);' class='button secondary-button add add-repeat-row'><?php _e( 'Add new', 'livemesh-tr-shipping' ); ?></a>

		</div><?php

		// Add new repeater row
		wc_enqueue_js( "
			jQuery( '.ltrs-table-rates-settings #rate_per_default ' ).on( 'click', '.add-repeat-row', function() {

				var repeater_wrap = $( this ).prev( '.repeater-wrap' );
				var clone = repeater_wrap.find( '.repeater-row' ).first().clone();
				var repeater_index = repeater_wrap.find( '.repeater-row' ).length;
				repeater_index++;
				clone.find( '[name*=\"[condition][min]\"]' ).attr( 'name', 'table_rates_rate_per_default[' + parseInt( repeater_index ) + '][condition][min]' ).val( '' );
				clone.find( '[name*=\"[condition][max]\"]' ).attr( 'name', 'table_rates_rate_per_default[' + parseInt( repeater_index ) + '][condition][max]' ).val( '' );
				clone.find( '[name*=\"[action][base_cost]\"]' ).attr( 'name', 'table_rates_rate_per_default[' + parseInt( repeater_index ) + '][action][base_cost]' ).val( '' );
				clone.find( '[name*=\"[action][additional_cost]\"]' ).attr( 'name', 'table_rates_rate_per_default[' + parseInt( repeater_index ) + '][action][additional_cost]' ).val( '' );
				clone.find( '[name*=\"[action][per_value]\"]' ).attr( 'name', 'table_rates_rate_per_default[' + parseInt( repeater_index ) + '][action][per_value]' ).val( '' );
				clone.find( '[name*=\"[action][threshold_value]\"]' ).attr( 'name', 'table_rates_rate_per_default[' + parseInt( repeater_index ) + '][action][threshold_value]' ).val( '' );
				repeater_wrap.append( clone ).find( '.repeater-row' ).last().hide().slideDown( 'fast' );

			});
		" );

	}

    /**
     * Calculate the shipping costs based on the table rates specified in this Default Rates section
     *
     */
	public function calculate_table_rates_shipping_cost( $shipping_rate_id, $package ) {

        $shipping_cost = 0;

		$table_rates = $this->get_table_rates( $shipping_rate_id );

		if ( is_array( $table_rates ) ) :
			foreach ( $table_rates as $values ) :

                $rate_type = isset( $values['condition']['rate_type'] ) ? esc_attr( $values['condition']['rate_type'] ) : 'subtotal_rate';
				$min  = isset( $values['condition']['min'] ) ? esc_attr( $values['condition']['min'] ) : null;
				$max  = isset( $values['condition']['max'] ) ? esc_attr( $values['condition']['max'] ) : null;
				$base_cost = isset( $values['action']['base_cost'] ) ? esc_attr( $values['action']['base_cost'] ) : null;
                $additional_cost = isset( $values['action']['additional_cost'] ) ? esc_attr( $values['action']['additional_cost'] ) : null;
                $per_value = isset( $values['action']['per_value'] ) ? esc_attr( $values['action']['per_value'] ) : null;
                $threshold_value = isset( $values['action']['threshold_value'] ) ? esc_attr( $values['action']['threshold_value'] ) : null;

                $subtotal = $this->get_cart_amount( $package, null, 'subtotal_rate' );
                $cart_amount = $this->get_cart_amount( $package, null, $rate_type );

				// Bail if cost is not set
				if ( empty( $base_cost ) && empty($additional_cost)) :
					continue;
				endif;

				// Bail if minimum is not set, or item amount is not met
				if ( is_null( $min ) || ( ! empty( $min ) && $cart_amount < $min ) ) :
					continue;
				endif;

				// Bail if maximum is not set, or item amount is not met
				if ( is_null( $max ) || ( ! empty( $max ) && $cart_amount > $max ) ) :
					continue;
				endif;

                $rate_params = array(
                    'rate_type' => $rate_type,
                    'base_cost' => $base_cost,
                    'additional_cost' => $additional_cost,
                    'per_value' => $per_value,
                    'threshold_value' => $threshold_value
                );

                $shipping_cost += ltrs_calculate_table_rate_shipping_cost($cart_amount, $subtotal, $rate_params);

			endforeach;
		endif;

		return $shipping_cost;

	}


}
