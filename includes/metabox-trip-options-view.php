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
			'priority' 	=> 10,
			'callback' 	=> array( __CLASS__, 'itineraries_tab_content' )
		);
		$tabs['prices_date_tab'] = array(
			'title' 	=> __( 'Prices & Dates', 'wp-travel' ),
			'priority' 	=> 50,
			'callback' 	=> array( __CLASS__, 'woo_new_product_tab_content' )
		);
		$tabs['includes_excludes_tab'] = array(
			'title' 	=> __( 'Includes/Excludes', 'wp-travel' ),
			'priority' 	=> 50,
			'callback' 	=> array( __CLASS__, 'woo_new_product_tab_content' )
		);
		$tabs['facts_tab'] = array(
			'title' 	=> __( 'Facts', 'wp-travel' ),
			'priority' 	=> 50,
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

	function itineraries_tab_content( $post ) {
		if ( ! $post ) {
			return;
		}
		$trip_code = wp_travel_get_trip_code( $post->ID );
		$itineraries = get_post_meta( $post->ID, 'wp_travel_trip_itinerary_data', true );
		?>
		<table style="width:100%" class="form-table trip-info">
			<tr>
				<td>
					<?php esc_html_e( 'Trip Code : ', 'wp-travel' ); ?>
					<input type="text" id="wp-travel-trip-code" disabled="disabled" value="<?php echo esc_attr( $trip_code ); ?>" />
				</td>
			</tr>
			<tr style="display:none" class="init-rows">
				<td><h3><?php esc_html_e( 'Itinerary', 'wp-travel' ); ?></h3></td>
				<td style="text-align:right"><button id="add-itinerary" type="button"><?php esc_html_e( '+ Add Itinerary', 'wp-travel' ); ?></button></td>
			</tr>

		<?php
		if ( is_array( $itineraries ) && count( $itineraries ) > 0 ) {
			foreach ( $itineraries as $x=>$itinerary ) {
				if (($itineraries[$x]['title'] != $default_title) && ($itineraries[$x]['title'] != "")) {
					$xx++;
				}
			}
		} else {?>
			<tr class="no-itineraries"><td colspan="2">
				<span><h3><?php esc_html_e( 'Itinerary', 'wp-travel' ); ?></h3></span><br>
				<span><?php esc_html_e( 'No Itineraries found.', 'wp-travel' ); ?></span>
				<span id="first-itinerary"><?php esc_html_e( 'Add Itinerary', 'wp-travel' ); ?></span>
			</td></tr><?php
		}?>

			<tr style="display:none" class="init-rows"><td colspan="2">

				<ul id="sortable"><?php
			  
				for ($x = 0; $x < 100; $x++) {
					echo '<li class="itinerary-li" id="itinerary-li-' . $x . '"><span><i class="fas fa-bars"></i>';
					if ($xx<=0) {
						$itinerary_title = __( 'Day X, My plan', 'wp-travel' );
						echo $itinerary_title . '</span><p style="display:none"></p>';
					} else {
						$itinerary_title = esc_attr( $itineraries[$x]['title'] );
						echo $itinerary_title . '</span><p style="display:none">' . $x . '</p>';
					}
					$itinerary_desc = esc_attr( $itineraries[$x]['desc'] );
					$xx--;
					echo '
					<table class="update-itinerary" style="width:100%">
				  	  <tbody>
						<tr>
							<th>Itinerary title</th>
							<td><input type="text" class="item-title-input" name="itinerary_item_title-' . $x . '" value="' . $itinerary_title . '" class="regular-text"></td>
						</tr>
						<tr>
							<th>Itinerary description</th>
							<td><textarea rows="3" name="itinerary_item_desc-' . $x . '" class="regular-text">' . esc_attr( $itineraries[$x]['desc'] ) . '</textarea></td>
						</tr>
						<tr>
							<th>Itinerary date</th>
							<td><input type="text" class="itinerary_item_date" name="itinerary_item_date-' . $x . '" value="' . esc_attr( $itineraries[$x]['date'] ) . '" class="regular-text"></td>
						</tr>
						<tr>
							<th><label for="itinerary_item_tobots">Itinerary robots</label></th>
							<td>
								<select id="itinerary_item_robots" name="itinerary_item_label-' . $x . '">
									<option value="">Home Stay ...</option>
									<option value="index,follow"' . selected( 'index,follow', $itineraries[$x]['label'], false ) . '>Show for search engines</option>
									<option value="noindex,nofollow"' . selected( 'noindex,nofollow', $itineraries[$x]['label'], false ) . '>Hide for search engines</option>
								</select>
							</td>
						</tr>
						<tr>
							<td></td>
							<td class="remove-itinerary" style="text-align:right"><button id="remove-itinerary-' . $x . '" style="color:red" type="button">' . $remove_itinerary . '</button></td>
						</tr>
				  	  </tbody>
					</table>
			  		</li>';
				}?>			
				</ul>

			</td></tr>

		</table>
		<?php

	}	
}

Metabox_Trip_Options_View::init();

