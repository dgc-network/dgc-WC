<?php
/**
 * Plugin Name: dgc-travel
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
//define( 'WP_TRAVEL_POST_TYPE', 'itineraries' );
define( 'WP_TRAVEL_POST_TYPE', 'product' );
define( 'WP_TRAVEL_POST_TITLE', __( 'trips', 'text-domain' ) );
define( 'WP_TRAVEL_POST_TITLE_SINGULAR', __( 'trip', 'text-domain' ) );
define( 'WP_TRAVEL_PLUGIN_FILE', __FILE__ );
//define( 'WP_TRAVEL_ABSPATH', dirname( __FILE__ ) . '/' );
define( 'WP_TRAVEL_ABSPATH', dirname( __FILE__ ) . '/wp-travel' . '/' );
define( 'WP_TRAVEL_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'WP_TRAVEL_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'WP_TRAVEL_TEMPLATE_PATH', 'wp-travel/' );
//define( 'WP_TRAVEL_VERSION', $this->version );
define( 'WP_TRAVEL_VERSION', '1.0.0' );
define( 'WP_TRAVEL_MINIMUM_PARTIAL_PAYOUT', array( 10 ) ); // In percent.
define( 'WP_TRAVEL_SLIP_UPLOAD_DIR', 'wp-travel-slip' ); // In percent.

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
    require_once BASE_DIR . 'includes/class-trip-options-admin.php';
    require_once BASE_DIR . 'includes/class-trip-options-view.php';
    require_once BASE_DIR . 'includes/helpers.php';
    //require_once BASE_DIR . 'wp-travel/inc/email-template-functions.php';
    //require_once BASE_DIR . 'wp-travel/inc/payments/wp-travel-payments.php';
/*
    require_once BASE_DIR . 'includes/class-trip-options-admin-metabox.php';
    require_once BASE_DIR . 'includes/class-trip-options-view-metabox.php';    
    require_once BASE_DIR . 'wp-travel/inc/helpers.php';
    require_once BASE_DIR . 'wp-travel/inc/class-itinerary.php';
    require_once BASE_DIR . 'wp-travel/inc/email-template-functions.php';
    require_once BASE_DIR . 'wp-travel/inc/payments/wp-travel-payments.php';
*/    
/*    
    require_once BASE_DIR . 'wp-travel/wp-travel.php';
    require_once BASE_DIR . 'wp-travel/inc/class-assets.php';
    require_once BASE_DIR . 'wp-travel/inc/helpers/helpers-price.php';
    require_once BASE_DIR . 'wp-travel/inc/helpers/helpers-fontawesome.php';
    require_once BASE_DIR . 'wp-travel/inc/admin/class-admin-metaboxes.php';
    require_once BASE_DIR . 'wp-travel/inc/admin/class-admin-tabs.php';
    require_once BASE_DIR . 'wp-travel/core/helpers/trips.php';
    require_once BASE_DIR . 'wp-travel/core/helpers/error-codes.php';
    require_once BASE_DIR . 'wp-travel/core/helpers/pricings.php';
    require_once BASE_DIR . 'wp-travel/core/helpers/trip-pricing-categories.php';
    require_once BASE_DIR . 'wp-travel/core/helpers/trip-pricing-categories-taxonomy.php';
    require_once BASE_DIR . 'wp-travel/core/helpers/trip-extras.php';
    require_once BASE_DIR . 'wp-travel/core/helpers/trip-dates.php';
    require_once BASE_DIR . 'wp-travel/core/helpers/trip-excluded-dates-times.php';
    require_once BASE_DIR . 'wp-travel/core/helpers/response-codes.php';
*/    
} else {
    //require_once $this->path('BASE_DIR','includes/no_woocommerce.php');
}
/*
function wpx_add_fontawesome() {
    wp_enqueue_style( 'font-awesome-5', 'https://use.fontawesome.com/releases/v5.0.0/css/all.css', false );
}
add_action( 'wp_enqueue_scripts', 'wpx_add_fontawesome' );
    

$itineraries = get_posts(
    array(
        //'post_type'   => 'itineraries',
        'post_type'   => 'product',
        'post_status' => 'publish',
    )
);
if ( count( $itineraries ) > 0 ) {
    foreach ( $itineraries as $itinerary ) {
        $post_id    = $itinerary->ID;
        $trip_price = get_post_meta( $post_id, 'wp_travel_trip_price', true );
        if ( $trip_price > 0 ) {
            continue;
        }

        $enable_sale = get_post_meta( $post_id, 'wp_travel_enable_sale', true );

        if ( $enable_sale ) {
            $trip_price = wp_travel_get_trip_sale_price( $post_id );
        } else {
            $trip_price = wp_travel_get_trip_price( $post_id );
        }
        update_post_meta( $post_id, 'wp_travel_trip_price', $trip_price );
    }
}
// Added Date Formatting for filter.
if ( count( $itineraries ) > 0 ) {
    foreach ( $itineraries as $itinerary ) {
        $post_id         = $itinerary->ID;
        $fixed_departure = get_post_meta( $post_id, 'wp_travel_fixed_departure', true );
        if ( 'no' == $fixed_departure ) {
            continue;
        }
        $wp_travel_start_date = get_post_meta( $post_id, 'wp_travel_start_date', true );
        $wp_travel_end_date   = get_post_meta( $post_id, 'wp_travel_end_date', true );

        if ( '' !== $wp_travel_start_date ) {

            $wp_travel_start_date = strtotime( $wp_travel_start_date );
            $wp_travel_start_date = date( 'Y-m-d', $wp_travel_start_date );
            update_post_meta( $post_id, 'wp_travel_start_date', $wp_travel_start_date );
        }

        if ( '' !== $wp_travel_end_date ) {

            $wp_travel_end_date = strtotime( $wp_travel_end_date );
            $wp_travel_end_date = date( 'Y-m-d', $wp_travel_end_date );
            update_post_meta( $post_id, 'wp_travel_end_date', $wp_travel_end_date );
        }
    }
}
*/





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

/*
function get_trip_code( $post_id ) {
    if ( ! $post_id ) {
        global $post;
        $post_id = $post->ID;
    }
    if ( (int) $post_id < 10 ) {
        $post_id = '0' . $post_id;
    }
    return apply_filters( 'wp_travel_trip_code', 'WT-CODE ' . $post_id, $post_id );
}
*/

/**
 * @package     Change Prices with Time for WooCommerce
 */
if ( !function_exists( 'cpwtfw_fs' ) ) {
    // Create a helper function for easy SDK access.
    function cpwtfw_fs()
    {
        global  $cpwtfw_fs ;
        
        if ( !isset( $cpwtfw_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $cpwtfw_fs = fs_dynamic_init( array(
                'id'             => '3310',
                'slug'           => 'change-prices-with-time-for-woocommerce',
                'type'           => 'plugin',
                'public_key'     => 'pk_7831b7db8cfba74a8d836b255fb22',
                'is_premium'     => false,
                'premium_suffix' => 'Professional',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                'first-path' => 'plugins.php',
                'support'    => false,
            ),
                'is_live'        => true,
            ) );
        }
        
        return $cpwtfw_fs;
    }
    
    // Init Freemius.
    //cpwtfw_fs();
    // Signal that SDK was initiated.
    //do_action( 'cpwtfw_fs_loaded' );
}


if ( !function_exists( 'activate_raise_prices_with_time_for_woocommmerce' ) ) {
    /**
     * The code that runs during plugin activation.
     * This action is documented in includes/class-raise-prices-with-time-for-woocommmerce-activator.php
     */
    function activate_raise_prices_with_time_for_woocommmerce()
    {
        //require_once plugin_dir_path( __FILE__ ) . 'includes/class-raise-prices-with-time-for-woocommmerce-activator.php';
        require_once 'change-prices-with-time-for-woocommerce/includes/class-raise-prices-with-time-for-woocommmerce-activator.php';
        Raise_Prices_With_Time_For_Woocommmerce_Activator::activate();
    }
    
    //register_activation_hook( __FILE__, 'activate_raise_prices_with_time_for_woocommmerce' );
}


if ( !function_exists( 'deactivate_raise_prices_with_time_for_woocommmerce' ) ) {
    /**
     * The code that runs during plugin deactivation.
     * This action is documented in includes/class-raise-prices-with-time-for-woocommmerce-deactivator.php
     */
    function deactivate_raise_prices_with_time_for_woocommmerce()
    {
        //require_once plugin_dir_path( __FILE__ ) . 'includes/class-raise-prices-with-time-for-woocommmerce-deactivator.php';
        require_once 'change-prices-with-time-for-woocommerce/includes/class-raise-prices-with-time-for-woocommmerce-deactivator.php';
        Raise_Prices_With_Time_For_Woocommmerce_Deactivator::deactivate();
    }
    
    //register_deactivation_hook( __FILE__, 'deactivate_raise_prices_with_time_for_woocommmerce' );
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
//require plugin_dir_path( __FILE__ ) . 'includes/class-raise-prices-with-time-for-woocommmerce.php';
require 'change-prices-with-time-for-woocommerce/includes/class-raise-prices-with-time-for-woocommmerce.php';

if ( !function_exists( 'rptwc_add_action_links' ) ) {
    /**
     * Adding Link for Documentation
     *
     * @param [type] $links
     *
     * @return array
     */
    function rptwc_add_action_links( $links )
    {
        $mylinks = array( '<a target="_blank" href="https://www.ibenic.com/change-prices-with-time-for-woocommerce">' . __( 'Documentation', 'rpt-wc' ) . '</a>' );
        //if ( !defined( 'RPT_PREMIUM' ) || !RPT_PREMIUM || cpwtfw_fs()->is_not_paying() ) {
            //$mylinks[] = '<a target="_blank" href="' . cpwtfw_fs()->get_upgrade_url() . '">' . __( 'Upgrade', 'rpt-wc' ) . '</a>';
        //}
        return array_merge( $links, $mylinks );
    }
    //add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'rptwc_add_action_links' );
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
    
    //run_raise_prices_with_time_for_woocommmerce();
}
