<?php
/**
 * Plugin Name: Shift8 Woocommerce Postal Blocker
 * Plugin URI: https://github.com/stardothosting/shift8-wooblock
 * Description: Plugin that allows you to input a list of postal / zip codes to block from ordering on your Woocommerce site. You can determine if you want to hide multiple payment gateways (i.e. paypal or stripe) if the postal or zip code matches during the checkout process. The gateway is removed during an ajax post seamlessly. You can also define the number of days to "Block" the payment gateway, defined as a browser cookie.
 * Version: 1.04
 * Author: Shift8 Web 
 * Author URI: https://www.shift8web.ca
 * 
 * WC requires at least: 2.2
 * WC tested up to: 2.3
 *
 * License: GPLv3
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Only include dependencies if Woocommerce is active
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    require_once(plugin_dir_path(__FILE__).'components/enqueuing.php' );
    require_once(plugin_dir_path(__FILE__).'components/settings.php' );
    require_once(plugin_dir_path(__FILE__).'components/functions.php' );
} else {
	add_action( 'admin_notices', 'shift8_wooblock_admin_notice__error' );
}

// Display error if Woocommerce is not active
function shift8_wooblock_admin_notice__error() {
    $class = 'notice notice-error';
    $message = __( 'Shift8 Wooblock cannot be activated if Woocommerce is not active!', 'shift8-wooblock-error' );
    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
}
