<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Function to encrypt session data
function shift8_wooblock_encrypt($key, $payload) {
    if (!empty($key) && !empty($payload)) {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($payload, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    } else {
        return false;
    }
}

// Function to decrypt session data
function shift8_wooblock_decrypt($key, $garble) {
    if (!empty($key) && !empty($garble)) {
        list($encrypted_data, $iv) = explode('::', base64_decode($garble), 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
    } else {
        return false;
    }
}

// Common function to clear the session
function clear_shift8_wooblock_cookie() {
    unset($_COOKIE['shift8_wb']);
    setcookie('shift8_wb', '',  time()-3600, '/');
}

/* Dont allow credit card for specific locations */
add_filter( 'woocommerce_available_payment_gateways', 'shift8_wooblock_payment_gateways_process' );
function shift8_wooblock_payment_gateways_process( $available_gateways ) {
    global $woocommerce;

    if ( is_admin() ) {
        return $available_gateways;
    }

    // Get settings prior to any processing
    if (shift8_wooblock_check_options()) {

        // Pull the settings for processing
        $gateway_remove = esc_attr( get_option('wc_settings_tab_shift8_wooblock_gateway') );
        $postal_codes = explode("\n", shift8_wooblock_sanitize(esc_attr( get_option('wc_settings_tab_shift8_wooblock_postals') )));
        $user_postal = shift8_wooblock_sanitize($woocommerce->customer->get_shipping_postal_code());

        // If postal code matches 
        if (in_array($user_postal, $postal_codes) && isset($available_gateways[$gateway_remove])) {
            $encryption_key = wp_salt('auth');
            $cookie_data = shift8_geoip_encrypt($encryption_key, $ip_address . '_' . $query->lat . '_' . $query->lon . '_' . $query->countryCode);
            unset( $available_gateways[$gateway_remove]);
        }
    }
    return $available_gateways;
}

// Validate admin options
function shift8_wooblock_check_options() {
    // If enabled is not set
    if(esc_attr( get_option('wc_settings_tab_shift8_wooblock_enable') ) != '1') return false;
    // If gateway is not set
    if(empty(esc_attr( get_option('wc_settings_tab_shift8_wooblock_gateway') ) ) ) return false;
    // If postal codes are blank
    if(empty(esc_attr( get_option('wc_settings_tab_shift8_wooblock_postals') ) ) ) return false;
    // If none of the above conditions match, return true
    return true;
}

// Sanitize postal field
function shift8_wooblock_sanitize($sanitize_field) {
    $sanitize_field = preg_replace('/\s+/', '', $sanitize_field);
    $sanitize_field = strtoupper($sanitize_field);
    return $sanitize_field;
}
