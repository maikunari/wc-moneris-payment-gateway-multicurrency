<?php
/*
 Plugin Name: WC Moneris Payment Gateway Multicurrency
 Plugin URI: https://your-site.com
 Description: Custom Moneris gateway with CAD/USD account switching.
 Version: 2.9.1-multicurrency
 Author: Michael Sewell
 Text Domain: wc-moneris-multicurrency
 License: GPL-2.0+
*/

if (!defined('ABSPATH')) exit;

add_action('plugins_loaded', 'wc_moneris_multicurrency_init', 11);

function wc_moneris_multicurrency_init() {
    if (!class_exists('WC_Payment_Gateway')) return;

    class WC_Gateway_Moneris_Multicurrency extends WC_Payment_Gateway {
        public function __construct() {
            $this->id = 'moneris_multicurrency';
            $this->icon = '';
            $this->has_fields = true;
            $this->method_title = __('Moneris Multicurrency', 'wc-moneris-multicurrency');
            $this->method_description = __('Accept payments via Moneris with CAD/USD support.', 'wc-moneris-multicurrency');
            $this->supports = array('products');
            $this->init_form_fields();
            $this->init_settings();
            $this->title = $this->get_option('title');
            $this->enabled = $this->get_option('enabled');
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        }

        public function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'wc-moneris-multicurrency'),
                    'type' => 'checkbox',
                    'label' => __('Enable Moneris Multicurrency Gateway', 'wc-moneris-multicurrency'),
                    'default' => 'yes'
                ),
                'title' => array(
                    'title' => __('Title', 'wc-moneris-multicurrency'),
                    'type' => 'text',
                    'description' => __('This controls the title which the user sees during checkout.', 'wc-moneris-multicurrency'),
                    'default' => __('Credit Card (Moneris)', 'wc-moneris-multicurrency'),
                    'desc_tip' => true,
                ),
                'cad_store_id' => array(
                    'title' => __('CAD Store ID', 'wc-moneris-multicurrency'),
                    'type' => 'text',
                    'description' => __('Your Moneris CAD Store ID.', 'wc-moneris-multicurrency'),
                    'desc_tip' => true,
                ),
                'cad_api_token' => array(
                    'title' => __('CAD API Token', 'wc-moneris-multicurrency'),
                    'type' => 'text',
                    'description' => __('Your Moneris CAD API Token.', 'wc-moneris-multicurrency'),
                    'desc_tip' => true,
                ),
                'cad_hpp_key' => array(
                    'title' => __('CAD HPP Key', 'wc-moneris-multicurrency'),
                    'type' => 'text',
                    'description' => __('Hosted Payment Page Key for CAD account (optional).', 'wc-moneris-multicurrency'),
                    'desc_tip' => true,
                ),
                'usd_store_id' => array(
                    'title' => __('USD Store ID', 'wc-moneris-multicurrency'),
                    'type' => 'text',
                    'description' => __('Your Moneris USD Store ID.', 'wc-moneris-multicurrency'),
                    'desc_tip' => true,
                ),
                'usd_api_token' => array(
                    'title' => __('USD API Token', 'wc-moneris-multicurrency'),
                    'type' => 'text',
                    'description' => __('Your Moneris USD API Token.', 'wc-moneris-multicurrency'),
                    'desc_tip' => true,
                ),
                'usd_hpp_key' => array(
                    'title' => __('USD HPP Key', 'wc-moneris-multicurrency'),
                    'type' => 'text',
                    'description' => __('Hosted Payment Page Key for USD account (optional).', 'wc-moneris-multicurrency'),
                    'desc_tip' => true,
                ),
                'testmode' => array(
                    'title' => __('Test Mode', 'wc-moneris-multicurrency'),
                    'type' => 'checkbox',
                    'label' => __('Enable Test Mode', 'wc-moneris-multicurrency'),
                    'default' => 'no',
                    'description' => __('Use Moneris test environment.', 'wc-moneris-multicurrency'),
                ),
            );
        }

        public function payment_fields() {
            ?>
            <p><?php _e('Enter your credit card details below.', 'wc-moneris-multicurrency'); ?></p>
            <p>
                <label><?php _e('Card Number', 'wc-moneris-multicurrency'); ?></label>
                <input type="text" name="moneris_card_number" maxlength="20" />
            </p>
            <p>
                <label><?php _e('Expiry Date (MMYY)', 'wc-moneris-multicurrency'); ?></label>
                <input type="text" name="moneris_expiry_date" maxlength="4" placeholder="MMYY" />
            </p>
            <?php
        }

        // Placeholder for payment processing (we’ll add next)
        public function process_payment($order_id) {
            return array('result' => 'success', 'redirect' => wc_get_checkout_url()); // Temp stub
        }
    }

    add_filter('woocommerce_payment_gateways', 'wc_moneris_multicurrency_add_gateway');
    function wc_moneris_multicurrency_add_gateway($gateways) {
        $gateways[] = 'WC_Gateway_Moneris_Multicurrency';
        return $gateways;
    }
}