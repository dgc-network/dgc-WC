<?php
class Trip_Options_View_Metabox {
	/**
	 * Constructor.
	 */
	function __construct() {
		add_filter( 'woocommerce_product_tabs', array( __CLASS__, 'woo_new_product_tab' ) );
		add_action( 'woocommerce_single_product_summary', array( __CLASS__, 'custom_action_after_single_product_title' ), 6 );
		add_action( 'woocommerce_before_add_to_cart_form', array( __CLASS__, 'action_woocommerce_before_add_to_cart_button' ), 10, 0 );	
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'custom_datepicker' ) );

		add_action( 'woocommerce_before_add_to_cart_button', array( __CLASS__, 'add_fields_before_add_to_cart' ) );
		add_filter( 'woocommerce_add_cart_item_data', array( __CLASS__, 'add_cart_item_data' ), 25, 2 );
		add_filter( 'woocommerce_get_item_data', array( __CLASS__, 'get_item_data' ), 25, 2 );
		add_action( 'woocommerce_add_order_item_meta', array( __CLASS__, 'add_order_item_meta' ), 10, 3 );
	}

	function custom_datepicker() {

		// Load the datepicker script (pre-registered in WordPress).
		wp_enqueue_script( 'jquery-ui-datepicker' );

		// You need styling for the datepicker. For simplicity I've linked to the jQuery UI CSS on a CDN.
		wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );
		wp_enqueue_style( 'jquery-ui' );  
		
	}

	function custom_action_after_single_product_title() { 
		global $product; 
	
		$product_id = $product->get_id(); // The product ID
	
		// Your custom field "Book author"
		$book_author = get_post_meta($product_id, "product_author", true);
	
		// Displaying your custom field under the title
		echo '<p class="book-author">' . $book_author . '</p>';

		$trip_code = wp_travel_get_trip_code( $post_id );
		echo '<div align="left"><h4>';
		esc_html_e( 'Trip Code : ', 'wp-travel' );
		echo esc_attr( $trip_code );
		echo '</h4></div>';

	}
	
	// define the woocommerce_before_add_to_cart_button callback 
	function action_woocommerce_before_add_to_cart_button() {
		echo esc_html_e( 'Start Date : ', 'wp-travel' );
		echo '<div class="start_date"></div>';
		?>
		<script>
			jQuery(document).ready(function($) {
				$( '.start_date' ).datepicker();
				$( '.start_date' ).on( 'change', function() {
					var start_date = new Date(this.value);
					$( '.itinerary-li' ).each( function( index, element ) {
						start_date.setDate(start_date.getDate() + index);
						//$( 'p', element ).empty();
						//$( 'p', element ).append(start_date.toLocaleDateString());
						$( 'input', element ).val(start_date.toLocaleDateString());
						$( '#itinerary-li-'+index ).datepicker();
						$( '#itinerary-li-'+index ).on( 'change', function() {
							var trip_date = new Date(this.value);
							$( '.itinerary-li' ).each( function( index2, element2 ) {
								if (index2 > index) {
									trip_date.setDate(trip_date.getDate() + index2 - index);
									$( 'input', element2 ).val(trip_date.toLocaleDateString());
								}
							});
						});
					});
				});
			});
		</script>
		<?php
	}
         
	/**
 	* Add a custom product data tab
 	*/
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
	
	function overview_tab_content() {
		$post_id = get_the_ID();
		$itineraries = get_post_meta( $post_id, 'wp_travel_trip_itinerary_data', true );

		if ( is_array( $itineraries ) && count( $itineraries ) > 0 ) {
			echo '<h4 style="text-align:left">';
			esc_html_e( 'Itinerary', 'wp-travel' );
			echo '</h4>';
			?>
			<ul><?php
			foreach ( $itineraries as $x=>$itinerary ) {
				echo '<li class="itinerary-li">';
				//echo '<p style="color:blue"></p>';
				echo '<input type="text" style="color:blue" name="itinerary-date-'.$x.'" id="itinerary-li-'.$x.'">';
				echo esc_attr( $itineraries[$x]['label'] ) . ', ' . esc_attr( $itineraries[$x]['title'] );
				echo esc_attr( $itineraries[$x]['desc'] );
				echo '</li>';
			} ?>
			</ul><?php
		} else { ?>
			<span><?php esc_html_e( 'No Itineraries found.', 'wp-travel' ); ?></span><?php
		}
	}

	function trip_includes_tab_content() {
		$post_id = get_the_ID();
		$trip_include = get_post_meta( $post_id, 'wp_travel_trip_include', true );
		echo '<h4 style="text-align:left">';
		esc_html_e( 'Trip Includes', 'wp-travel' );
		echo '</h4>';
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
		echo '<h4 style="text-align:left">';
		esc_html_e( 'Trip Excludes', 'wp-travel' );
		echo '</h4>';
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
		if ( is_array( $faqs ) && count( $faqs ) > 0 ) { 
			echo '<h4 style="text-align:left">';
			esc_html_e( 'FAQ : ', 'wp-travel' );
			echo '</h4>';
			?>
			<ul><?php
			foreach ( $faqs as $key=>$value ) { ?>
				<li><?php echo esc_attr( $faqs[$key]['question'] ); ?><br><?php
				echo esc_attr( $faqs[$key]['answer'] ); ?></li><?php
			} ?></ul><?php
		} else { ?>
			<span><?php esc_html_e( 'No FAQs found.', 'wp-travel' ); ?></span><?php
		}
	}

// HERE set the array of pairs keys/values for your checkboxes
function custom_checkboxes(){
    return array(
        'mm_chicken_cutlet_bento'       => __( "Chicken Cutlet Bento", "aoim"),
        'mm_roasted_pork_rib_bento'     => __( "Roasted Pork Rib Bento", "aoim"),
    );
}

// Displaying the checkboxes
//add_action( 'woocommerce_before_add_to_cart_button', 'add_fields_before_add_to_cart' );
function add_fields_before_add_to_cart( ) {
    global $product;
    //if( $product->get_id() != 2 ) return; // Only for product ID "2"
/*
    ?>
    <div class="simple-selects">
        <div class="col-md-6">
            <h3><?php _e("Main meals", "aoim"); ?></h3>
            <?php foreach( self::custom_checkboxes() as $key => $value ): ?>
                <p><input type="checkbox" name="<?php echo $key; ?>" id="<?php echo $key; ?>"><?php echo ' ' . $value; ?></p>
            <?php endforeach; ?>
        </div>
    </div>
	<?php 
*/
}


// Add data to cart item
//add_filter( 'woocommerce_add_cart_item_data', 'add_cart_item_data', 25, 2 );
function add_cart_item_data( $cart_item_data, $product_id ) {
    //if( $product_id != 2 ) return $cart_item_data; // Only for product ID "2"
	$post_id = $product_id;
	$itineraries = get_post_meta( $post_id, 'wp_travel_trip_itinerary_data', true );

    // Set the data for the cart item in cart object
    $data = array() ;
/*
    foreach( self::custom_checkboxes() as $key => $value ){
        if( isset( $_POST[$key] ) )
            $cart_item_data['custom_data'][$key] = $data[$key] = $value;
	}
*/
    foreach( $itineraries as $x=>$itinerary ){
        //if( isset( $_POST['itinerary-date-'.$x] ) )
		$cart_item_data['custom_data']['itinerary-date-'.$x] = $data['itinerary-date-'.$x] = $_POST['itinerary-date-'.$x];
		$cart_item_data['custom_data']['itinerary-title-'.$x] = $data['itinerary-title-'.$x] = $itineraries[$x]['title'];
	}

    // Add the data to session and generate a unique ID
    if( count($data > 0 ) ){
        $cart_item_data['custom_data']['unique_key'] = md5( microtime().rand() );
        WC()->session->set( 'custom_data', $data );
    }
    return $cart_item_data;
}


// Display custom data on cart and checkout page.
//add_filter( 'woocommerce_get_item_data', 'get_item_data' , 25, 2 );
function get_item_data ( $cart_data, $cart_item ) {
    //if( $cart_item['product_id'] != 2 ) return $cart_data; // Only for product ID "2"

    if( ! empty( $cart_item['custom_data'] ) ){
        $values =  array();
        foreach( $cart_item['custom_data'] as $key => $value )
            if( $key != 'unique_key' ){
                $values[] = $value;
            }
		//$values = implode( ', ', $values );
        $cart_data[] = array(
            'name'    => __( "Option", "aoim"),
            //'name'    => '',
            'value' => $values
            //'display' => $values
            //'display' => '<ul><li>Itinerary1</li><li>Itinerary2</li><li>Itinerary3</li></ul>'
        );
    }

    return $cart_data;
}

// Add order item meta.
//add_action( 'woocommerce_add_order_item_meta', 'add_order_item_meta' , 10, 3 );
function add_order_item_meta ( $item_id, $cart_item, $cart_item_key ) {
    if ( isset( $cart_item[ 'custom_data' ] ) ) {
        $values =  array();
        foreach( $cart_item[ 'custom_data' ] as $key => $value )
            if( $key != 'unique_key' ){
                $values[] = $value;
            }
        $values = implode( ', ', $values );
        wc_add_order_item_meta( $item_id, __( "Option", "aoim"), $values );
    }
}

}
new Trip_Options_View_Metabox;