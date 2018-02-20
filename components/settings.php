<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WC_Settings_Tab_Shift8 {

    /**
     * Bootstraps the class and hooks required actions & filters.
     *
     */
    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_settings_tab_shift8', __CLASS__ . '::settings_tab' );
        add_action( 'woocommerce_update_options_settings_tab_shift8', __CLASS__ . '::update_settings' );
    }
    
    
    /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     */
    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['settings_tab_shift8'] = __( 'Shift8', 'woocommerce-settings-tab-shift8' );
        return $settings_tabs;
    }


    /**
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::get_settings()
     */
    public static function settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }


    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public static function update_settings() {
        woocommerce_update_options( self::get_settings() );
    }


    /**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public static function get_settings() {

        // Get available payment gateways
        $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
        $gatway_list = array();
        foreach ($available_gateways as $gateway) {
            if ($gateway->enabled == 'yes') {
                $gateway_list[$gateway->id] = $gateway->id; 
            }
        }

        $settings = array(
            'section_title' => array(
                'name'     => __( 'Shift8 Woocommerce Postal Blocker', 'woocommerce-settings-tab-shift8' ),
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'wc_settings_tab_shift8_wooblock_section_title'
            ),
            'enable' => array(
                'name' => __( 'Enable', 'woocommerce-settings-tab-shift8' ),
                'type' => 'checkbox',
                'desc' => __( 'Enable the blocker', 'woocommerce-settings-tab-shift8' ),
                'id'   => 'wc_settings_tab_shift8_wooblock_enable'
            ),
            'gateway' => array(
                'name' => __( 'Gateways to Remove', 'woocommerce-settings-tab-shift8' ),
                'type' => 'multiselect',
                'desc' => __( 'Select multiple payment gateways to disable if a postal / zip code matches', 'woocommerce-settings-tab-shift8' ),
                'options' => $gateway_list,
                'css' => 'width:150px;height:40px;',
                'id'   => 'wc_settings_tab_shift8_wooblock_gateway'
            ),
            'daysremember' => array(
                'name' => __( 'Ban Length (days)', 'woocommerce-settings-tab-shift8' ),
                'type' => 'number',
                'css'  => 'width:150px;height:40px;',
                'desc' => __( 'Enter the number of days for the end-user\'s cookie to remember the ban' ),
                'id'   => 'wc_settings_tab_shift8_wooblock_daysremember',
                'default' => '30'
            ),
            'postals' => array(
                'name' => __( 'Postal Codes', 'woocommerce-settings-tab-shift8' ),
                'type' => 'textarea',
                'css'  => 'width:500px;height:250px;',
                'desc' => __( 'Enter a list of postal / zip codes to block, one per line. Spaces and case will be stripped out when pattern matching.' ),
                'id'   => 'wc_settings_tab_shift8_wooblock_postals'
            ),
            'section_end' => array(
                 'type' => 'sectionend',
                 'id' => 'wc_settings_tab_shift8_wooblock_section_end'
            )
        );

        return apply_filters( 'wc_settings_tab_shift8_settings', $settings );
    }

}

WC_Settings_Tab_Shift8::init();
