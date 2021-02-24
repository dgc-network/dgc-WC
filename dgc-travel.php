<?php
/**
 * Plugin Name: dgc-travel
 * Description: Fully responsive and mobile friendly. Easily customizable - color,background,title,text color etc.
 * Author: dgc.network
 * Author URI: https://dgc.network
 * Tags: woocommerce product,woocommerce product table, product table
 * 
 * Version: 1.0.0
 * Requires at least:    4.0.0
 * Tested up to:         5.6.1
 * WC requires at least: 3.0.0
 * WC tested up to: 	 5.0.0
 * 
 * Text Domain: text-domain
 * Domain Path: /languages/
 */

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// This enables debugging.
define( 'WP_DEBUG', true );

/**
 * Defining constant
 */
define( 'DEFAULT_ITINERARY_LABEL', __( 'Day X', 'text-domain' ) );
define( 'DEFAULT_ITINERARY_TITLE', __( 'My plan', 'text-domain' ) );
define( 'DEFAULT_PRICING', __( 'Pricing Name', 'text-domain' ) );
define( 'DEFAULT_FAQ_QUESTION', __( 'FAQ Questions', 'text-domain' ) );


/**
 * Including Plugin file for security
 * @since 1.0.0
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Load these bellow file, Only woocommerce installed
 */
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    require_once 'includes/class-trip-options.php';
    require_once 'cpwtfw/includes/class-raise-prices-with-time-for-woocommmerce.php';
}

if ( !function_exists( 'dgc_add_action_links' ) ) {
    /**
     * Adding Link for Documentation
     *
     * @param [type] $links
     *
     * @return array
     */
    function dgc_add_action_links( $links )
    {
        //$mylinks = array( '<a target="_blank" href="https://dgc.network/change-prices-with-time-for-woocommerce">' . __( 'Documentation', 'text-domain' ) . '</a>' );
        $mylinks = array( '<a target="_blank" href="https://docs.google.com/document/d/151Ci9M-0nF8KixbErfqwkzFahhzv9Ra9S4v2L120bRM/edit">' . __( 'Documentation', 'text-domain' ) . '</a>' );
		
        return array_merge( $links, $mylinks );
    }
    add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'dgc_add_action_links' );
}

if ( !function_exists( 'run_trip_options' ) ) {
    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    1.0.0
     */
    function run_trip_options()
    {
        $plugin = new Trip_Options();
        $plugin->run();
    }
    
    run_trip_options();
}

if ( !function_exists( 'run_raise_prices_with_time_for_woocommmerce' ) ) {
    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    1.0.0
     */
    function run_raise_prices_with_time_for_woocommmerce()
    {
        $plugin = new Raise_Prices_With_Time_For_Woocommmerce();
        $plugin->run();
    }
    
    run_raise_prices_with_time_for_woocommmerce();
}
