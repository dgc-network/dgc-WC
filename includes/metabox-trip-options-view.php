<?php
class Metabox_Trip_Options_View {
	public static function init() {
		/**
 		* Add a custom product data tab
 		*/
		add_filter( 'woocommerce_product_tabs', array( __CLASS__, 'woo_new_product_tab' ) );
	}

	function woo_new_product_tab( $tabs ) {
		// Adds the new tab
		$tabs['itineraries_tab'] = array(
			'title' 	=> __( 'Itineraries', 'woocommerce' ),
			'priority' 	=> 50,
			'callback' 	=> 'woo_new_product_tab_content'
		);
		$tabs['prices_date_tab'] = array(
			'title' 	=> __( 'Prices & Dates', 'wp-travel' ),
			'priority' 	=> 50,
			'callback' 	=> 'woo_new_product_tab_content'
		);
		$tabs['includes_excludes_tab'] = array(
			'title' 	=> __( 'Includes/Excludes', 'wp-travel' ),
			'priority' 	=> 50,
			'callback' 	=> 'woo_new_product_tab_content'
		);
		$tabs['facts_tab'] = array(
			'title' 	=> __( 'Facts', 'wp-travel' ),
			'priority' 	=> 50,
			'callback' 	=> 'woo_new_product_tab_content'
		);
		$tabs['gallery_tab'] = array(
			'title' 	=> __( 'Gallery', 'wp-travel' ),
			'priority' 	=> 50,
			'callback' 	=> 'woo_new_product_tab_content'
		);
		$tabs['locations_tab'] = array(
			'title' 	=> __( 'Locations', 'wp-travel' ),
			'priority' 	=> 50,
			'callback' 	=> 'woo_new_product_tab_content'
		);
		$tabs['faqs_tab'] = array(
			'title' 	=> __( 'FAQs', 'wp-travel' ),
			'priority' 	=> 50,
			'callback' 	=> 'woo_new_product_tab_content'
		);
		$tabs['misc_options_tab'] = array(
			'title' 	=> __( 'Misc. Options', 'wp-travel' ),
			'priority' 	=> 50,
			'callback' 	=> 'woo_new_product_tab_content'
		);
		return $tabs;
	}

	function woo_new_product_tab_content() {
		// The new tab content
		echo '<h2>New Product Tab</h2>';
		echo '<p>Here\'s your new product tab.</p>';
	}	

	//function itineraries_tab_content( $post ) {
	function itineraries_tab_content() {
		//if ( ! $post ) {
		//	return;
		//}
		//$trip_code = wp_travel_get_trip_code( $post->ID );
		//$itineraries = get_post_meta( $post->ID, 'wp_travel_trip_itinerary_data', true );
		// The new tab content
		echo '<h2>New Product Tab</h2>';
		echo '<p>Here\'s your new product tab.</p>';
	}	
}

Metabox_Trip_Options_View::init();

