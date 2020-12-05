<?php
class Trip_Options_View_Metabox {
	/**
	 * Constructor.
	 */
	function __construct() {
		add_filter( 'woocommerce_product_tabs', array( __CLASS__, 'woo_new_product_tab' ) );
	}

	/**
 	* Add a custom product data tab
 	*/
	function woo_new_product_tab( $tabs ) {
	//function woo_new_product_tab() {
		$tabs['itineraries_tab'] = array(
			'title' 	=> __( 'Itineraries', 'woocommerce' ),
			'priority' 	=> 10,
			'callback' 	=> array( __CLASS__, 'itineraries_tab_content' )
		);
		$tabs['prices_date_tab'] = array(
			'title' 	=> __( 'Prices & Dates', 'wp-travel' ),
			'priority' 	=> 20,
			'callback' 	=> array( __CLASS__, 'woo_new_product_tab_content' )
		);
		$tabs['includes_excludes_tab'] = array(
			'title' 	=> __( 'Includes/Excludes', 'wp-travel' ),
			'priority' 	=> 30,
			'callback' 	=> array( __CLASS__, 'woo_new_product_tab_content' )
		);
		$tabs['facts_tab'] = array(
			'title' 	=> __( 'Facts', 'wp-travel' ),
			'priority' 	=> 40,
			'callback' 	=> array( __CLASS__, 'woo_new_product_tab_content' )
		);
		$tabs['gallery_tab'] = array(
			'title' 	=> __( 'Gallery', 'wp-travel' ),
			'priority' 	=> 50,
			'callback' 	=> array( __CLASS__, 'woo_new_product_tab_content' )
		);
		$tabs['locations_tab'] = array(
			'title' 	=> __( 'Locations', 'wp-travel' ),
			'priority' 	=> 50,
			'callback' 	=> array( __CLASS__, 'woo_new_product_tab_content' )
		);
		$tabs['faqs_tab'] = array(
			'title' 	=> __( 'FAQs', 'wp-travel' ),
			'priority' 	=> 50,
			'callback' 	=> array( __CLASS__, 'faqs_tab_content' )
		);
		$tabs['misc_options_tab'] = array(
			'title' 	=> __( 'Misc. Options', 'wp-travel' ),
			'priority' 	=> 50,
			'callback' 	=> array( __CLASS__, 'woo_new_product_tab_content' )
		);
		return $tabs;
	}
	
	function woo_new_product_tab_content() {
		// The new tab content
		echo '<h2>New Product Tab</h2>';
		echo '<p>Here\'s your new product tab.</p>';
	}	

	function itineraries_tab_content() {
		$post_id = get_the_ID();
		$trip_code = wp_travel_get_trip_code( $post_id );
		$itineraries = get_post_meta( $post_id, 'wp_travel_trip_itinerary_data', true );
		esc_html_e( 'Trip Code : ', 'wp-travel' );
		echo esc_attr( $trip_code ); ?><br><?php
		esc_html_e( 'Itinerary : ', 'wp-travel' );
		if ( is_array( $itineraries ) && count( $itineraries ) > 0 ) { ?>
			<ul><?php
			foreach ( $itineraries as $x=>$itinerary ) { ?>
				<li><?php echo esc_attr( $itineraries[$x]['title'] ); ?><br><?php
				echo esc_attr( $itineraries[$x]['desc'] ); ?></li><?php
			} ?></ul><?php
		} else { ?>
			<span><?php esc_html_e( 'No Itineraries found.', 'wp-travel' ); ?></span><?php
		}
	}

	function faqs_tab_content() {
		$post_id = get_the_ID();
		$faqs = wp_travel_get_faqs( $post_id );
/*
		echo '$post->ID = ' . $post_id;
		echo '{';
			foreach ( $faqs as $key=>$vales ) {
				echo $key.':{';
				foreach ( $vales as $key=>$value ) {
					echo '{'.$key.':'.$value.'},';
				}
				echo '},';
			}
		echo '}';
*/		
		esc_html_e( 'FAQ : ', 'wp-travel' );
		if ( is_array( $faqs ) && count( $faqs ) > 0 ) { ?>
			<ul><?php
			foreach ( $faqs as $key=>$value ) { ?>
				<li><?php echo esc_attr( $faqs[$key]['question'] ); ?><br><?php
				echo esc_attr( $faqs[$key]['answer'] ); ?></li><?php
			} ?></ul><?php
		} else { ?>
			<span><?php esc_html_e( 'No FAQs found.', 'wp-travel' ); ?></span><?php
		}
	}
}
new Trip_Options_View_Metabox;