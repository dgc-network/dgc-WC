<?php
class Wp_Travel_Taxonomies {

	public static function init() {
		self::register_itinerary_types();
	}

	public static function register_itinerary_types() {
		$permalink = wp_travel_get_permalink_structure();
		// Add new taxonomy, make it hierarchical (like categories).
		$labels = array(
			'name'              => _x( 'Trip Types', 'taxonomy general name', 'text-domain' ),
			'singular_name'     => _x( 'Trip Type', 'taxonomy singular name', 'text-domain' ),
			'search_items'      => __( 'Search Trip Types', 'text-domain' ),
			'all_items'         => __( 'All Trip Types', 'text-domain' ),
			'parent_item'       => __( 'Parent Trip Type', 'text-domain' ),
			'parent_item_colon' => __( 'Parent Trip Type:', 'text-domain' ),
			'edit_item'         => __( 'Edit Trip Type', 'text-domain' ),
			'update_item'       => __( 'Update Trip Type', 'text-domain' ),
			'add_new_item'      => __( 'Add New Trip Type', 'text-domain' ),
			'new_item_name'     => __( 'New Tour Trip Name', 'text-domain' ),
			'menu_name'         => __( 'Trip Types', 'text-domain' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => $permalink['wp_travel_trip_type_base'] ),
		);

		register_taxonomy( 'itinerary_types', apply_filters( 'wp_travel_trip_type_post_types', array( WP_TRAVEL_POST_TYPE ) ), $args );

		$labels = array(
			'name'              => _x( 'Destinations', 'general name', 'text-domain' ),
			'singular_name'     => _x( 'Destination', 'singular name', 'text-domain' ),
			'search_items'      => __( 'Search Destinations', 'text-domain' ),
			'all_items'         => __( 'All Destinations', 'text-domain' ),
			'parent_item'       => __( 'Parent Destination', 'text-domain' ),
			'parent_item_colon' => __( 'Parent Destination:', 'text-domain' ),
			'edit_item'         => __( 'Edit Destination', 'text-domain' ),
			'update_item'       => __( 'Update Destination', 'text-domain' ),
			'add_new_item'      => __( 'Add New Destination', 'text-domain' ),
			'new_item_name'     => __( 'New Destination', 'text-domain' ),
			'menu_name'         => __( 'Destinations', 'text-domain' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => $permalink['wp_travel_destination_base'] ),
		);

		register_taxonomy( 'travel_locations', apply_filters( 'wp_travel_destinations_post_types', array( WP_TRAVEL_POST_TYPE ) ), $args );

		$labels = array(
			'name'              => _x( 'Keywords', 'general name', 'text-domain' ),
			'singular_name'     => _x( 'Keyword', 'singular name', 'text-domain' ),
			'search_items'      => __( 'Search Keywords', 'text-domain' ),
			'all_items'         => __( 'All Keywords', 'text-domain' ),
			'parent_item'       => __( 'Parent Keyword', 'text-domain' ),
			'parent_item_colon' => __( 'Parent Keyword:', 'text-domain' ),
			'edit_item'         => __( 'Edit Keyword', 'text-domain' ),
			'update_item'       => __( 'Update Keyword', 'text-domain' ),
			'add_new_item'      => __( 'Add New Keyword', 'text-domain' ),
			'new_item_name'     => __( 'New Keyword', 'text-domain' ),
			'menu_name'         => __( 'Keywords', 'text-domain' ),
		);

		$args = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => false,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'travel-keywords' ),
		);

		register_taxonomy( 'travel_keywords', apply_filters( 'wp_travel_kewords_post_types', array( WP_TRAVEL_POST_TYPE ) ), $args );

		$labels = array(
			'name'              => _x( 'Activities', 'general name', 'text-domain' ),
			'singular_name'     => _x( 'Activity', 'singular name', 'text-domain' ),
			'search_items'      => __( 'Search Activities', 'text-domain' ),
			'all_items'         => __( 'All Activities', 'text-domain' ),
			'parent_item'       => __( 'Parent Activity', 'text-domain' ),
			'parent_item_colon' => __( 'Parent Activity:', 'text-domain' ),
			'edit_item'         => __( 'Edit Activity', 'text-domain' ),
			'update_item'       => __( 'Update Activity', 'text-domain' ),
			'add_new_item'      => __( 'Add New Activity', 'text-domain' ),
			'new_item_name'     => __( 'New Activity', 'text-domain' ),
			'menu_name'         => __( 'Activities', 'text-domain' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => false,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => $permalink['wp_travel_activity_base'] ),
		);

		register_taxonomy( 'activity', apply_filters( 'wp_travel_activity_post_types', array( WP_TRAVEL_POST_TYPE ) ), $args );
	}
}
