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
			'callback' 	=> array( __CLASS__, 'woo_new_product_tab_content' )
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
/*
		echo '$post->ID = ' . $post_id;
		echo '{';
			foreach ( $itineraries as $key=>$itinerary ) {
				echo $key.':{';
				foreach ( $itinerary as $key=>$value ) {
					echo '{'.$key.':'.$value.'},';
				}
				echo '},';
			}
		echo '}';
*/		
		?>
		<?php esc_html_e( 'Trip Code : ', 'wp-travel' ); ?>
		<?php echo esc_attr( $trip_code ); ?><br>
		<?php esc_html_e( 'Itinerary : ', 'wp-travel' ); ?><?php
		if ( is_array( $itineraries ) && count( $itineraries ) > 0 ) { ?>
			<ul><?php
			foreach ( $itineraries as $x=>$itinerary ) { ?>
				<li><?php esc_attr( $itineraries[$x]['title'] ); ?><br><?php
				esc_attr( $itineraries[$x]['desc'] );
				echo '
				<table class="update-itinerary" style="width:100%">
					<tbody>
					<tr>
						<th>Itinerary title</th>
						<td><input type="text" class="item-title" name="itinerary_item_title-' . $x . '" value="' . esc_attr( $itineraries[$x]['title'] ) . '" class="regular-text"></td>
					</tr>
					<tr>
						<th>Itinerary description</th>
						<td><textarea rows="3" name="itinerary_item_desc-' . $x . '" class="regular-text">' . esc_attr( $itineraries[$x]['desc'] ) . '</textarea></td>
					</tr>
					<tr>
						<th>Itinerary date</th>
						<td><input type="text" class="itinerary_item_date" name="itinerary_item_date-' . $x . '" value="' . esc_attr( $itineraries[$x]['date'] ) . '" class="regular-text"></td>
					</tr>
					</tbody>
				</table>';
			}
		} else {?>
			<span><?php esc_html_e( 'No Itineraries found.', 'wp-travel' ); ?></span><?php
		}?>



		<table style="width:100%">
			<tr>
				<td>
					<?php esc_html_e( 'Trip Code : ', 'wp-travel' ); ?>
					<input type="text" id="wp-travel-trip-code" disabled="disabled" value="<?php echo esc_attr( $trip_code ); ?>" />
				</td>
			</tr>

		<?php
		if ( is_array( $itineraries ) && count( $itineraries ) > 0 ) {?>
			<tr class="init-rows">
				<td><h3><?php esc_html_e( 'Itinerary', 'wp-travel' ); ?></h3></td>
			</tr><?php
			foreach ( $itineraries as $x=>$itinerary ) {
				echo '
				<tr><td>
				<table class="update-itinerary" style="width:100%">
					<tbody>
					<tr>
						<th>Itinerary title</th>
						<td><input type="text" class="item-title" name="itinerary_item_title-' . $x . '" value="' . esc_attr( $itineraries[$x]['title'] ) . '" class="regular-text"></td>
					</tr>
					<tr>
						<th>Itinerary description</th>
						<td><textarea rows="3" name="itinerary_item_desc-' . $x . '" class="regular-text">' . esc_attr( $itineraries[$x]['desc'] ) . '</textarea></td>
					</tr>
					<tr>
						<th>Itinerary date</th>
						<td><input type="text" class="itinerary_item_date" name="itinerary_item_date-' . $x . '" value="' . esc_attr( $itineraries[$x]['date'] ) . '" class="regular-text"></td>
					</tr>
					</tbody>
				</table>
				</td></tr>';
			}
		} else {?>
			<tr class="no-itineraries"><td>
				<span><h3><?php esc_html_e( 'Itinerary', 'wp-travel' ); ?></h3></span><br>
				<span><?php esc_html_e( 'No Itineraries found.', 'wp-travel' ); ?></span>
			</td></tr><?php
		}?>

		</table>
		<?php

	}	
}

//Trip_Options_View_Metabox::init();
new Trip_Options_View_Metabox;

