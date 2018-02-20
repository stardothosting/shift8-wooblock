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
     $encryption_key = wp_salt('auth');

    if ( is_admin() ) {
        return $available_gateways;
    }

    // Get settings prior to any processing

    if (shift8_wooblock_check_options()) {

        // Pull the settings for processing
        $gateways_remove = array_map( 'esc_attr', get_option('wc_settings_tab_shift8_wooblock_gateway') );
        $postal_codes = explode(" ", esc_attr( shift8_wooblock_sanitize(get_option('wc_settings_tab_shift8_wooblock_postals') )));
        $user_postal = !empty($woocommerce->customer->get_shipping_postcode()) ? shift8_wooblock_sanitize($woocommerce->customer->get_shipping_postcode()) : shift8_wooblock_sanitize($woocommerce->customer->get_billing_postcode());
        $ban_time = (!empty(esc_attr(get_option('wc_settings_tab_shift8_wooblock_daysremember'))) ? esc_attr(get_option('wc_settings_tab_shift8_wooblock_daysremember')) : 30);

        // If postal code matches
        if (in_array($user_postal, $postal_codes) && shift8_wooblock_array_keys_exists($gateways_remove, $available_gateways)) {
            foreach ($gateways_remove as $gateway_remove) {
                unset( $available_gateways[$gateway_remove]);
            }
            $cookie_data = shift8_wooblock_encrypt($encryption_key, $user_postal . '_' . $woocommerce->customer->get_email());
            if (esc_attr( shift8_wooblock_sanitize(get_option('wc_settings_tab_shift8_wooblock_postals') ))) {}
            setcookie('shift8_wb', $cookie_data, strtotime('+' . $ban_time . ' day'), '/');

        } else if (isset($_COOKIE['shift8_wb'])) {
            $cookie_data = explode('_', shift8_wooblock_decrypt($encryption_key, $_COOKIE['shift8_wb']));
            if (esc_attr($cookie_data[1]) != 'error') {
				unset( $available_gateways[$gateway_remove]);
			}
        }
    }
    return $available_gateways;
}

// Validate admin options
function shift8_wooblock_check_options() {

    // If enabled is not set
    if(esc_attr( get_option('wc_settings_tab_shift8_wooblock_enable') ) == 'no') {
        return false;
    }
    // If gateway is not set
    if(empty(get_option('wc_settings_tab_shift8_wooblock_gateway') ) ) return false;
    // If postal codes are blank
    if(empty(esc_attr( get_option('wc_settings_tab_shift8_wooblock_postals') ) ) ) return false;
    // If none of the above conditions match, return true
    return true;
}

// Sanitize postal field
function shift8_wooblock_sanitize($sanitize_field) {
    $sanitize_field = str_replace(' ', '', $sanitize_field);
    $sanitize_field = strtoupper($sanitize_field);
	$sanitize_field = trim(preg_replace('/\s+/', ' ', $sanitize_field));
    return $sanitize_field;
}

// Check multiple array keys (gateways) to see if they exist (are activated)
function shift8_wooblock_array_keys_exists(array $keys, array $arr) {
   return !array_diff_key(array_flip($keys), $arr);
}
