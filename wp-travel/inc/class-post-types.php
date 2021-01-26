<?php
class WP_Travel_Post_Types {

	public function __construct() {

	}

	/**
	 * Init.
	 *
	 * @return void
	 */
	public static function init() {
		self::register_bookings();
		self::register_trip();
		// self::register_enquiries();
		self::register_payment();
		// self::register_tour_extras();
    }
    
	/**
	 * Register Post Type Trip.
	 *
	 * @return void
	 */
	public static function register_trip() {
		$settings = wp_travel_get_settings();
		$switch_to_react = $settings['wp_travel_switch_to_react'];
		$permalink = wp_travel_get_permalink_structure();
		$labels    = array(
			'name'               => _x( 'Trips', 'post type general name', 'text-domain' ),
			'singular_name'      => _x( 'Trip', 'post type singular name', 'text-domain' ),
			'menu_name'          => _x( 'Trips', 'admin menu', 'text-domain' ),
			'name_admin_bar'     => _x( 'Trip', 'add new on admin bar', 'text-domain' ),
			'add_new'            => _x( 'New Trip', 'text-domain', 'text-domain' ),
			'add_new_item'       => __( 'Add New Trip', 'text-domain' ),
			'new_item'           => __( 'New Trip', 'text-domain' ),
			'edit_item'          => __( 'Edit Trip', 'text-domain' ),
			'view_item'          => __( 'View Trip', 'text-domain' ),
			'all_items'          => __( 'All Trips', 'text-domain' ),
			'search_items'       => __( 'Search Trips', 'text-domain' ),
			'parent_item_colon'  => __( 'Parent Trips:', 'text-domain' ),
			'not_found'          => __( 'No Trips found.', 'text-domain' ),
			'not_found_in_trash' => __( 'No Trips found in Trash.', 'text-domain' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'text-domain' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array(
				'slug'       => $permalink['wp_travel_trip_base'],
				'with_front' => true,
			),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'comments', 'excerpt' ),
			'menu_icon'          => 'dashicons-location',
			'menu_position'      => 30,
			'show_in_rest'       => true,
		);
		if ( 'yes' === $switch_to_react ) {
			$args['supports'][] = 'editor';
			$args['show_in_rest'] = false;
		}
		/**
		 * Register a itineraries post type.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_post_type
		 */
		register_post_type( WP_TRAVEL_POST_TYPE, $args );
	}

	/**
	 * Register Post Type Bookings.
	 *
	 * @return void
	 */
	public static function register_bookings() {
		$labels = array(
			'name'               => _x( 'Bookings', 'post type general name', 'text-domain' ),
			'singular_name'      => _x( 'Booking', 'post type singular name', 'text-domain' ),
			'menu_name'          => _x( 'WP Travel', 'admin menu', 'text-domain' ),
			'name_admin_bar'     => _x( 'Booking', 'add new on admin bar', 'text-domain' ),
			'add_new'            => _x( 'Add New', 'text-domain', 'text-domain' ),
			'add_new_item'       => __( 'Add New booking', 'text-domain' ),
			'new_item'           => __( 'New booking', 'text-domain' ),
			'edit_item'          => __( 'View booking', 'text-domain' ),
			'view_item'          => __( 'View booking', 'text-domain' ),
			'all_items'          => __( 'Bookings', 'text-domain' ),
			'search_items'       => __( 'Search bookings', 'text-domain' ),
			'parent_item_colon'  => __( 'Parent bookings:', 'text-domain' ),
			'not_found'          => __( 'No bookings found.', 'text-domain' ),
			'not_found_in_trash' => __( 'No bookings found in Trash.', 'text-domain' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'text-domain' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			// 'show_in_menu'       => 'edit.php?post_type=' . WP_TRAVEL_POST_TYPE,
			'query_var'          => true,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title' ),
			'menu_icon'          => 'dashicons-wp-travel',
			'with_front'         => true,
			'menu_position'      => 30,
			'show_in_rest'       => true,
		);
		/**
		 * Register a itinerary-booking post type.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_post_type
		 */
		register_post_type( 'itinerary-booking', $args );
	}

	/**
	 * Register Post Type Enquiries.
	 *
	 * @return void
	 */
	public static function register_enquiries() {
		$labels = array(
			'name'               => _x( 'Enquiries', 'post type general name', 'text-domain' ),
			'singular_name'      => _x( 'Enquiry', 'post type singular name', 'text-domain' ),
			'menu_name'          => _x( 'Enquiries', 'admin menu', 'text-domain' ),
			'name_admin_bar'     => _x( 'Enquiry', 'add new on admin bar', 'text-domain' ),
			'add_new'            => _x( 'Add New', 'text-domain', 'text-domain' ),
			'add_new_item'       => __( 'Add New Enquiry', 'text-domain' ),
			'new_item'           => __( 'New Enquiry', 'text-domain' ),
			'edit_item'          => __( 'View Enquiry', 'text-domain' ),
			'view_item'          => __( 'View Enquiry', 'text-domain' ),
			'all_items'          => __( 'Enquiries', 'text-domain' ),
			'search_items'       => __( 'Search Enquiries', 'text-domain' ),
			'parent_item_colon'  => __( 'Parent Enquiries:', 'text-domain' ),
			'not_found'          => __( 'No Enquiries found.', 'text-domain' ),
			'not_found_in_trash' => __( 'No Enquiries found in Trash.', 'text-domain' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'text-domain' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => 'edit.php?post_type=itinerary-booking',
			'query_var'          => true,
			// 'rewrite'            => array( 'slug' => 'itinerary-booking' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title' ),
			'menu_icon'          => 'dashicons-help',
			'with_front'         => true,
			'show_in_rest'       => true,
		);
		/**
		 * Register a itinerary-booking post type.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_post_type
		 */
		register_post_type( 'itinerary-enquiries', $args );
	}

	/**
	 * Register Post Type Payment.
	 *
	 * @return void
	 */
	public static function register_payment() {
		$labels = array(
			'name'               => _x( 'Payments', 'post type general name', 'text-domain' ),
			'singular_name'      => _x( 'Payment', 'post type singular name', 'text-domain' ),
			'menu_name'          => _x( 'Payments', 'admin menu', 'text-domain' ),
			'name_admin_bar'     => _x( 'Payment', 'add new on admin bar', 'text-domain' ),
			'add_new'            => _x( 'Add New', 'text-domain', 'text-domain' ),
			'add_new_item'       => __( 'Add New Payment', 'text-domain' ),
			'new_item'           => __( 'New Payment', 'text-domain' ),
			'edit_item'          => __( 'Edit Payment', 'text-domain' ),
			'view_item'          => __( 'View Payment', 'text-domain' ),
			'all_items'          => __( 'All Payments', 'text-domain' ),
			'search_items'       => __( 'Search Payments', 'text-domain' ),
			'parent_item_colon'  => __( 'Parent Payments:', 'text-domain' ),
			'not_found'          => __( 'No Payments found.', 'text-domain' ),
			'not_found_in_trash' => __( 'No Payments found in Trash.', 'text-domain' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'text-domain' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'itinerary-payment' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'comments' ),
			'menu_icon'          => 'dashicons-cart',
		);
		/**
		 * Register a Payments post type.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_post_type
		 */
		register_post_type( 'wp-travel-payment', $args );
	}

	/**
	 * Register Post Type WP Travel Tour Extras.
	 *
	 * @return void
	 */
	public static function register_tour_extras() {
		$labels = array(
			'name'               => _x( 'Trip Extras', 'post type general name', 'text-domain' ),
			'singular_name'      => _x( 'Trip Extra', 'post type singular name', 'text-domain' ),
			'menu_name'          => _x( 'Trip Extras', 'admin menu', 'text-domain' ),
			'name_admin_bar'     => _x( 'Trip Extra', 'add new on admin bar', 'text-domain' ),
			'add_new'            => _x( 'Add New', 'text-domain', 'text-domain' ),
			'add_new_item'       => __( 'Add New Trip Extra', 'text-domain' ),
			'new_item'           => __( 'New Trip Extra', 'text-domain' ),
			'edit_item'          => __( 'Edit Trip Extra', 'text-domain' ),
			'view_item'          => __( 'View Trip Extra', 'text-domain' ),
			'all_items'          => __( 'Trip Extras', 'text-domain' ),
			'search_items'       => __( 'Search Trip Extras', 'text-domain' ),
			'parent_item_colon'  => __( 'Parent Trip Extras:', 'text-domain' ),
			'not_found'          => __( 'No Trip Extras found.', 'text-domain' ),
			'not_found_in_trash' => __( 'No Trip Extras found in Trash.', 'text-domain' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'text-domain' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => 'edit.php?post_type=itinerary-booking',
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'tour-extras' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'thumbnail' ),
			'menu_icon'          => 'dashicons-wp-travel',
			'show_in_rest'       => true,
		);

		$args = apply_filters( 'wp_travel_tour_extras_post_type_args', $args );
		/**
		 * Register a WP Travel Tour Extras post type.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_post_type
		 */
		register_post_type( 'tour-extras', $args );
	}
}
