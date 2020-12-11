<?php
class Trip_Options_View_Metabox {
	/**
	 * Constructor.
	 */
	function __construct() {
		add_filter( 'woocommerce_product_tabs', array( __CLASS__, 'woo_new_product_tab' ) );
		add_action( 'woocommerce_before_add_to_cart_button', array( __CLASS__, 'action_woocommerce_before_add_to_cart_button' ), 10, 0 );	
	}

	// define the woocommerce_before_add_to_cart_button callback 
	function action_woocommerce_before_add_to_cart_button(  ) { ?>
		<div class="container">
		<div class="row">
			<div class='col-sm-6'>
				<div class="form-group">
					<div class='input-group date' id='datetimepicker2'>
						<input type='text' class="form-control" />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-time"></span>
						</span>
					</div>
				</div>
			</div>
		</div>
		</div>	
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$('#datetimepicker2').datepicker();
				$('#datetimepicker3').datetimepicker({
						format: 'HH:mm'
				});
			});
		</script>
		<?php
	}
         
	/**
 	* Add a custom product data tab
 	*/
	//function woo_new_product_tab( $tabs ) {
	function woo_new_product_tab() {

		$tabs = array();
		$post_id = get_the_ID();
		$trip_tabs = wp_travel_get_admin_trip_tabs( $post_id );
		if ( is_array( $trip_tabs ) && count( $trip_tabs ) > 0 ) {
			foreach ( $trip_tabs as $key=>$value ) {
				$tabs[$key] = array(
					'title' 	=> __( $trip_tabs[$key]['label'], 'wp-travel' ),
					'priority' 	=> 20,
					'callback' 	=> array( __CLASS__, $key . '_tab_content' )
				);		
			}
		}
		return $tabs;
	}
	
	//function itineraries_tab_content() {
	function overview_tab_content() {
		$post_id = get_the_ID();
		$trip_code = wp_travel_get_trip_code( $post_id );
		$itineraries = get_post_meta( $post_id, 'wp_travel_trip_itinerary_data', true );
		echo '<div align="left"><h4>';
		esc_html_e( 'Trip Code : ', 'wp-travel' );
		echo esc_attr( $trip_code ); ?><br><?php
		esc_html_e( 'Itinerary', 'wp-travel' );
		echo '</h4></div>';
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

	function trip_includes_tab_content() {
		$post_id = get_the_ID();
		$trip_include = get_post_meta( $post_id, 'wp_travel_trip_include', true );
		echo '<h3>';
		esc_html_e( 'Trip Includes', 'wp-travel' );
		echo '</h3>';
		echo '<br>';
		if (!empty($trip_include)) {
			echo esc_attr( $trip_include );
		} else {
			esc_html_e( 'No Trip Include found.', 'wp-travel' );
		}
		echo '<br>';
	}	

	function trip_excludes_tab_content() {
		$post_id = get_the_ID();
		$trip_exclude = get_post_meta( $post_id, 'wp_travel_trip_exclude', true );
		echo '<h3>';
		esc_html_e( 'Trip Excludes', 'wp-travel' );
		echo '</h3>';
		echo '<br>';
		if (!empty($trip_exclude)) {
			echo esc_attr( $trip_exclude );
		} else {
			esc_html_e( 'No Trip Exclude found.', 'wp-travel' );
		}
	}	

	function faq_tab_content() {
		$post_id = get_the_ID();
		$faqs = wp_travel_get_faqs( $post_id );
		echo '<h3>';
		esc_html_e( 'FAQ : ', 'wp-travel' );
		echo '</h3>';
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