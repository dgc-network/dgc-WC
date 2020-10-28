<?php
/**
 * Plugin Name: dgc-WC
 * Description: WooCommerce all products display as a table in one page by shortcode. Fully responsive and mobile friendly. Easily customizable - color,background,title,text color etc.
 * Author: dgc.network
 * Author URI: https://dgc.network
 * Tags: woocommerce product,woocommerce product table, product table
 * 
 * Version: 1.0
 * Requires at least:    4.0.0
 * Tested up to:         5.2.3
 * WC requires at least: 3.0.0
 * WC tested up to: 	 3.7.0
 * 
 * Text Domain: dgc_domain
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
$dir = dirname( __FILE__ ); //dirname( __FILE__ )
define( "BASE_DIR", str_replace( '\\', '/', $dir . '/' ) );
define( 'WP_TRAVEL_POST_TYPE', 'itineraries' );
define( 'WP_TRAVEL_ABSPATH', dirname( __FILE__ ) . '/wp-travel' . '/' );

/**
 * Including Plugin file for security
 * @since 1.0.0
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Load these bellow file, Only woocommerce installed
 */
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    require_once BASE_DIR . 'includes/metabox-trip-options-edit.php';
    require_once BASE_DIR . 'includes/metabox-trip-options-view.php';
    //require_once BASE_DIR . 'wp-travel/wp-travel.php';
    require_once BASE_DIR . 'wp-travel/inc/helpers.php';
    require_once BASE_DIR . 'wp-travel/inc/class-itinerary.php';
    require_once BASE_DIR . 'wp-travel/inc/email-template-functions.php';
    require_once BASE_DIR . 'wp-travel/inc/admin/class-admin-metaboxes.php';
    require_once BASE_DIR . 'wp-travel/inc/helpers/helpers-price.php';
} else {
    //require_once $this->path('BASE_DIR','includes/no_woocommerce.php');
}






/**
 * Defining constant
 */
define( 'WPT_PLUGIN_BASE_FOLDER', plugin_basename( dirname( __FILE__ ) ) );
define( 'WPT_PLUGIN_BASE_FILE', plugin_basename( __FILE__ ) );
define( "WPT_BASE_URL", plugins_url() . '/'. plugin_basename( dirname( __FILE__ ) ) . '/' );
define( "WPT_DIR_BASE", dirname( __FILE__ ) . '/' );
define( "WPT_BASE_DIR", str_replace( '\\', '/', WPT_DIR_BASE ) );

define( "WPT_PLUGIN_FOLDER_NAME",plugin_basename( dirname( __FILE__ ) ) ); //aDDED TO NEW VERSION
define( "WPT_PLUGIN_FILE_NAME", __FILE__ ); //aDDED TO NEW VERSION

/**
* Plugin Install and Uninstall
*/
register_activation_hook(__FILE__, array( 'WOO_Product_Table','install' ) );
register_deactivation_hook( __FILE__, array( 'WOO_Product_Table','uninstall' ) );
