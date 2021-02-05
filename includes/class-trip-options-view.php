<?php
class Trip_Options_View {
	/**
	 * Constructor.
	 */
	function __construct() {
		add_filter( 'woocommerce_product_tabs', array( __CLASS__, 'custom_product_tab' ) );
		add_action( 'woocommerce_single_product_summary', array( __CLASS__, 'custom_after_single_product_title' ), 6 );
		add_action( 'woocommerce_before_add_to_cart_form', array( __CLASS__, 'custom_before_add_to_cart_button' ), 10, 0 );	
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'custom_datepicker' ) );

		//add_action( 'wp_enqueue_scripts', array( __CLASS__, 'dgc_custom_script' ) );
		add_action( 'woocommerce_before_single_product', array( __CLASS__, 'dgc_custom_script' ), 10 );
		add_action( 'wp_ajax_woocommerce_ajax_add_to_cart', array( __CLASS__, 'woocommerce_ajax_add_to_cart' ) );
		add_action( 'wp_ajax_nopriv_woocommerce_ajax_add_to_cart', array( __CLASS__, 'woocommerce_ajax_add_to_cart' ) );

		add_action( 'woocommerce_before_add_to_cart_button', array( __CLASS__, 'add_fields_before_add_to_cart' ) );
		add_filter( 'woocommerce_add_cart_item_data', array( __CLASS__, 'custom_add_cart_item_data' ), 25, 2 );
		add_filter( 'woocommerce_get_item_data', array( __CLASS__, 'custom_get_item_data' ), 25, 2 );
		//add_action( 'woocommerce_before_checkout_process', array( __CLASS__, 'custom_before_checkout_process' ) );
		add_action( 'woocommerce_checkout_create_order_line_item', array( __CLASS__, 'custom_checkout_create_order_line_item' ), 20, 4 );
		//add_action( 'woocommerce_checkout_process', array( __CLASS__, 'custom_checkout_process' ) );
		//add_action( 'woocommerce_thankyou', array( __CLASS__, 'wc_auto_complete_paid_order' ), 20, 1 );
		add_filter( 'woocommerce_email_recipient_new_booking', array( __CLASS__, 'additional_customer_email_recipient' ), 10, 2 ); 
		add_filter( 'woocommerce_email_recipient_new_order', array( __CLASS__, 'additional_customer_email_recipient' ), 10, 2 ); // Optional (testing)
		
		add_action( 'woocommerce_after_shop_loop_item', array( __CLASS__, 'remove_add_to_cart_buttons' ), 1 );
	}

    function remove_add_to_cart_buttons() {
		if( is_product_category() || is_shop()) { 
		  	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
	  	} ?>
		<style>
			.woocommerce ul.products li.product a.button { display: none; }
		</style>
		<?php
	}

	function custom_datepicker() {

		// Load the datepicker script (pre-registered in WordPress).
		wp_enqueue_script( 'jquery-ui-datepicker' );

		// You need styling for the datepicker. For simplicity I've linked to the jQuery UI CSS on a CDN.
		wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );
		wp_enqueue_style( 'jquery-ui' );  
		
	}

	/**
	 * Add a bit of script.
	 */
	function dgc_custom_script() {
		?>
		<script>
			jQuery(document).ready(function($) {

				/*
				 * AJAX for Woocommerce Add To Cart button
				 */
				$( '.single_add_to_cart_button' ).on( 'click', function(e) {
					e.preventDefault();
/*
					var myInput = document.getElementById("start_date_input");
					if !(myInput && myInput.value) {
						  alert("My input has no value!");
						  return;
					}
*/					
					var $thisbutton = $(this),
                	$form = $thisbutton.closest('form.cart'),
					id = $thisbutton.val(),
                	product_qty = $form.find('input[name=quantity]').val() || 1,
                	product_id = $form.find('input[name=product_id]').val() || id,
                	variation_id = $form.find('input[name=variation_id]').val() || 0;

					var itinerary_date_array = [];
					$( '.itinerary-li' ).each( function( index, element ) {
						var itinerary_date = $( '#itinerary-date-'+index ).val();
						itinerary_date_array.push( itinerary_date );
					})
					var start_date_input = $( '#start_date_input' ).val();

        			var data = {
            			action: 'woocommerce_ajax_add_to_cart',
            			product_id: product_id,
            			product_sku: '',
            			quantity: product_qty,
						variation_id: variation_id,
						itinerary_date_array: itinerary_date_array,
						start_date_input: start_date_input,
        			};

        			$(document.body).trigger('adding_to_cart', [$thisbutton, data]);

        			$.ajax({
            			type: 'post',
						url: '/wp-admin/admin-ajax.php',
            			data: data,
            			beforeSend: function (response) {
                			$thisbutton.removeClass('added').addClass('loading');
            			},
            			complete: function (response) {
                			$thisbutton.addClass('added').removeClass('loading');
            			},
            			success: function (response) {
                			if (response.error && response.product_url) {
                    			window.location = response.product_url;
                    			return;
                			} else {
                    			$(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                			}
            			},
        			});

        			return false;
				});
		
				/*
				 * Update the Itinerary Date after the Datepicker value changed
				 */
				$( '.start_date' ).datepicker();
				$( '.start_date' ).on( 'change', function() {
					var start_date = new Date(this.value);
					var updated_start_date = new Date(this.value);
					//$( '#start_date_input' ).val(updated_start_date.toLocaleDateString());
					$( '.itinerary-li' ).each( function( index, element ) {
						updated_start_date.setDate(start_date.getDate() + index);
						$( 'input', element ).val(updated_start_date.toLocaleDateString());
						$( '#itinerary-date-'+index ).datepicker();
						$( '#itinerary-date-'+index ).on( 'change', function() {
							var trip_date = new Date(this.value);
							var updated_trip_date = new Date(this.value);
							$( '.itinerary-li' ).each( function( index2, element2 ) {
								if (index2 > index) {
									updated_trip_date.setDate(trip_date.getDate() + index2 - index);
									$( 'input', element2 ).val(updated_trip_date.toLocaleDateString());
								}
							});
						});
					});
				});
			});
		</script>
		<?php
	}

	/*
	 * Added Custom fields on product view page
	 */
	function custom_after_single_product_title() { 

		global $post;
		$post_id = $post->ID;
		$is_itinerary = get_post_meta( $post_id, '_itinerary', true );

		if ($is_itinerary=='yes') {
			$trip_code = get_trip_code( $post->ID );
			echo '<div align="left"><h4>';
			echo __( 'Trip Code : ', 'text-domain' ) . $trip_code;
			echo '</h4></div>';	
		}
	}
	
	function custom_before_add_to_cart_button() {

		global $post;
		$post_id = $post->ID;
		$is_itinerary = get_post_meta( $post_id, '_itinerary', true );
		$itineraries = get_post_meta( $post_id, 'wp_travel_trip_itinerary_data', true );

		$itinerary_date_array = array();
		if ( is_array( $itineraries ) && count( $itineraries ) > 0 ) {
			foreach ( $itineraries as $x=>$itinerary ) {
				if( !empty( $itineraries[$x]['date'] ) ) {
					array_push( $itinerary_date_array, $itineraries[$x]['date'] );
				}
			}
		}
		if ( is_array( $itinerary_date_array ) && count( $itinerary_date_array ) > 0 ) {
			echo __( 'Itinerary : ', 'text-domain' );
			echo '<ul>';
			foreach ( $itineraries as $x=>$itinerary ) {
				echo '<li>' . $itineraries[$x]['date'] . ': ' . $itineraries[$x]['title'] . '</li>';
			}
			echo '</ul>';
		} else {
			echo __( 'Start Date : ', 'text-domain' );
			if ($is_itinerary=='yes') {
				echo '<div class="start_date"></div>';
			} else {
				echo '<input type="text" style="color:blue; width:fit-content" class="start_date" id="start_date_input" name="start_date_input" />';
			}
		}
	}
         
	/*
	 * Clicked the Add-to-Card button
	 */
	function woocommerce_ajax_add_to_cart() {
	
		$product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
		$quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
		$variation_id = absint($_POST['variation_id']);
		$passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
		$product_status = get_post_status($product_id);

		$post_id = $product_id;
		$is_itinerary = get_post_meta( $post_id, '_itinerary', true );
		$itineraries = get_post_meta( $post_id, 'wp_travel_trip_itinerary_data', true );

    	if( !empty( $_POST['itinerary_date_array'] ) ) {
			foreach( $_POST['itinerary_date_array'] as $x => $itinerary_date ) {
				$itineraries[$x]['itinerary_date'] = $itinerary_date;
			}
		} else {
			if( !empty( $_POST['start_date_input'] ) ) {
				$itineraries[0]['itinerary_date'] = $_POST['start_date_input'];
			} else {
				foreach( $itineraries as $x => $itinerary ) {
					$itineraries[$x]['itinerary_date'] = $itineraries[$x]['date'];
				}
			} 
		}
		update_post_meta( $post_id, 'wp_travel_trip_itinerary_data', $itineraries );

		if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {
	
			do_action('woocommerce_ajax_added_to_cart', $product_id);
	
			if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
				wc_add_to_cart_message(array($product_id => $quantity), true);
			}
	
			WC_AJAX :: get_refreshed_fragments();

		} else {
	
			$data = array(
				'error' => true,
				'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
			);
	
			echo wp_send_json($data);
		}

		wp_die();
	}
	
	/*
	 * Add data to cart item
	 */
	//add_filter( 'woocommerce_add_cart_item_data', 'custom_add_cart_item_data', 25, 2 );
	function custom_add_cart_item_data( $cart_item_data, $product_id ) {
		$post_id = $product_id;
		$is_itinerary = get_post_meta( $post_id, '_itinerary', true );
		$itineraries = get_post_meta( $post_id, 'wp_travel_trip_itinerary_data', true );

    	// Set the data for the cart item in cart object
    	$data = array() ;
		$cart_item_data['custom_data']['_itinerary'] = $data['_itinerary'] = $is_itinerary;
		$cart_item_data['custom_data']['itineraries'] = $data['itineraries'] = $itineraries;

		// Add the data to session and generate a unique ID
    	if( count( $data > 0 ) ){
        	$cart_item_data['custom_data']['unique_key'] = md5( microtime().rand() );
        	WC()->session->set( 'custom_data', $data );
    	}
    	return $cart_item_data;
	}

	/*
	 * Display custom data on cart and checkout page.
	 */
	//add_filter( 'woocommerce_get_item_data', 'custom_get_item_data' , 25, 2 );
	function custom_get_item_data ( $cart_data, $cart_item ) {

		if( ! empty( $cart_item['custom_data'] ) ){
			$values = '<span>';
        	foreach( $cart_item['custom_data']['itineraries'] as $x => $itinerary ) {
				$itinerary_date = $cart_item['custom_data']['itineraries'][$x]['itinerary_date'];
				$values .= $itinerary_date.', ';
			}
			$values .= '</span>';
			if( $cart_item['custom_data']['_itinerary']=='yes' ){
				$values .= '<ul>';
	        	foreach( $cart_item['custom_data']['itineraries'] as $x => $itinerary ) {
					$label = $cart_item['custom_data']['itineraries'][$x]['label'];
					$title = $cart_item['custom_data']['itineraries'][$x]['title'];
					$assignments = $cart_item['custom_data']['itineraries'][$x]['assignment'];
					$itinerary_date = $cart_item['custom_data']['itineraries'][$x]['itinerary_date'];
					if( ! empty( $assignments ) ){
						foreach( $assignments as $y => $assignment ) {
							$category = $assignments[$y]['category'];
							$product_id = $assignments[$y]['resource'];
							$product_title = get_the_title( $assignments[$y]['resource'] );
							$values .= '<li>'.$itinerary_date.', '.$category.', '.$product_title.'</li>';
						}
					}
				}
				$values .= '</ul>';
			}

			$cart_data[] = array(
				'name'    => __( 'Date', 'text-domain' ),
				'display' => $values				
			);
    	}

    	return $cart_data;
	}

	/* 
	 * woocommerce_checkout_create_order_line_item It has 4 available arguments and available after version WooCommerce 3.3+.
	 * $item is an instance of WC_Order_Item_Product new introduced Class
	 * $cart_item_key is the cart item unique hash key
	 * $values is the cart item
	 * $order an instance of the WC_Order object (This is a very useful additional argument in some specific cases)
	 */
	//add_action( 'woocommerce_checkout_create_order_line_item', 'custom_checkout_create_order_line_item', 20, 4 );
	function custom_checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {

    	if( isset( $values['custom_data'] ) ) {
			
			//$customer_id = $order->get_customer_id();
			$product_id = $values['product_id']; // Product ID
			$product_qty = $values['quantity']; // Product quantity
			$vendor_id = get_post_field( 'post_author', $product_id );
			  
			foreach( $values['custom_data']['itineraries'] as $x => $itinerary ) {
				$assignments = $values['custom_data']['itineraries'][$x]['assignment'];
				$itinerary_date = $values['custom_data']['itineraries'][$x]['itinerary_date'];
				if( ! empty( $assignments ) ){
					foreach( $assignments as $y => $assignment ) {
						$product_id_resource = $assignments[$y]['resource'];
						self::create_purchase_order($vendor_id, $product_id_resource, $product_qty, $itinerary_date);
					}
				}
			}

			$display_itinerary_date = '<span>';
			foreach( $values['custom_data']['itineraries'] as $x => $itinerary ) {
				$itinerary_date = $values['custom_data']['itineraries'][$x]['itinerary_date'];
				$display_itinerary_date .= $itinerary_date.', ';
			}
			$display_itinerary_date .= '</span>';
			$item->update_meta_data( __( 'Date', 'text-domain' ), $display_itinerary_date );
			
		}
	}

	function create_purchase_order( $customer_id, $product_id, $quantity, $itinerary_date ) {
	
		global $woocommerce;
  
	  	// Get an instance of the WC_Customer Object
	  	//$vendor_id = get_post_field( 'post_author', $product_id );
	  	$customer = new WC_Customer( $customer_id );

	  	//$quantity = 1;
	  
	  	$args = array( 
		  'variation' => array( __( 'Date', 'text-domain' ) => $itinerary_date ),
	  	); 
	  
	  	$order = wc_create_order( array( 'customer_id' => $customer_id ) );
	  	$order->add_product( wc_get_product( $product_id ), $quantity, $args );
		$order->set_address( $customer->get_billing(), 'billing' );
		$order->calculate_totals();

		update_post_meta( $order->id, '_payment_method', 'dgc-payment' );
		update_post_meta( $order->id, '_payment_method_title', 'dgcPay' );

		// Store Order ID in session so it can be re-used after payment failure
		WC()->session->order_awaiting_payment = $order->id;
	
		// Process Payment
		$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
		$result = $available_gateways[ 'dgc-payment' ]->process_payment( $order->id );
	
		// Redirect to success/confirmation/payment page
		if ( $result['result'] == 'success' ) {
	
			$result = apply_filters( 'woocommerce_payment_successful_result', $result, $order->id );
	
			//wp_redirect( $result['redirect'] );
			//exit;
		}

	}
  
	//add_action('woocommerce_checkout_process', 'custom_checkout_process');
	function custom_checkout_process() {
	
	  	global $woocommerce;

		foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$product_id = $cart_item['product_id']; // Product ID
			$product_obj = wc_get_product($product_id); // Product Object
			$product_qty = $cart_item['quantity']; // Product quantity
			$product_price = $cart_item['data']->price; // Product price
			$product_total_stock = $cart_item['data']->total_stock; // Product stock
			$product_type = $cart_item['data']->product_type; // Product type
			$product_name = $cart_item['data']->post->post_title; // Product Title (Name)
			$product_slug = $cart_item['data']->post->post_name; // Product Slug
			$product_description = $cart_item['data']->post->post_content; // Product description
			$product_excerpt = $cart_item['data']->post->post_excerpt; // Product short description
			$product_post_type = $cart_item['data']->post->post_type; // Product post type
		
			$cart_line_total = $cart_item['line_total']; // Cart item line total
			$cart_line_tax = $cart_item['line_tax']; // Cart item line tax total
			$cart_line_subtotal = $cart_item['line_subtotal']; // Cart item line subtotal
			$cart_line_subtotal_tax = $cart_item['line_subtotal_tax']; // Cart item line tax subtotal
		
			// variable products
			$variation_id = $cart_item['variation_id']; // Product Variation ID
			if($variation_id != 0){
				$product_variation_obj = wc_get_product($variation_id); // Product variation Object
				$variation_array = $cart_item['variation']; // variation attributes + values
			}
		}

	  	//$order->update_status( 'Completed', 'Imported order', TRUE );  
	}
	

	//add_action( 'woocommerce_thankyou', 'wc_auto_complete_paid_order', 20, 1 );
	function wc_auto_complete_paid_order( $order_id ) {
		if ( ! $order_id )
			return;
		
		// Get an instance of the WC_Product object
		$order = wc_get_order( $order_id );
		
		// No updated status for orders delivered with Bank wire, Cash on delivery and Cheque payment methods.
		if ( in_array( $order->get_payment_method(), array( 'bacs', 'cod', 'cheque', '' ) ) ) {
			return;
		} 
		// For paid Orders with all others payment methods (paid order status "processing")
		elseif( $order->has_status('processing') ) {
			$order->update_status( 'completed' );
		}
	}		

	//add_filter( 'woocommerce_email_recipient_new_booking', 'additional_customer_email_recipient', 10, 2 ); 
	//add_filter( 'woocommerce_email_recipient_new_order', 'additional_customer_email_recipient', 10, 2 ); // Optional (testing)
	function additional_customer_email_recipient( $recipient, $order ) {
		if ( ! is_a( $order, 'WC_Order' ) ) return $recipient;

		$additional_recipients = array(); // Initializing…
	
		// Iterating though each order item
		foreach( $order->get_items() as $item_id => $line_item ){
			// Get the vendor ID
			$vendor_id = get_post_field( 'post_author', $line_item->get_product_id());
			$vendor = get_userdata( $vendor_id );
			$email = $vendor->user_email;
	
			// Avoiding duplicates (if many items with many emails)
			// or an existing email in the recipient
			if( ! in_array( $email, $additional_recipients ) && strpos( $recipient, $email ) === false )
				$additional_recipients[] = $email;
		}
	
		// Convert the array in a coma separated string
		$additional_recipients = implode( ',', $additional_recipients);
	
		// If an additional recipient exist, we add it
		if( count($additional_recipients) > 0 )
			$recipient .= ','.$additional_recipients;
	
		return $recipient;
	}

	/**
 	* Add a custom product data tab
 	*/
	 function custom_product_tab() {

		global $post;
		$post_id = $post->ID;
		$is_itinerary = get_post_meta( $post_id, '_itinerary', true );
		if ($is_itinerary=='yes') {
			$tabs = array();
			$post_id = get_the_ID();
			$trip_tabs = wp_travel_get_admin_trip_tabs( $post_id );
			if ( is_array( $trip_tabs ) && count( $trip_tabs ) > 0 ) {
				foreach ( $trip_tabs as $key=>$value ) {
					$tabs[$key] = array(
						'title' 	=> __( $trip_tabs[$key]['label'], 'text-domain' ),
						'priority' 	=> 20,
						'callback' 	=> array( __CLASS__, $key . '_tab_content' )
					);		
				}
			}
			return $tabs;
		}
	}
	
	function itinerary_tab_content() {

		global $post;
		$post_id = $post->ID;
		$itineraries = get_post_meta( $post_id, 'wp_travel_trip_itinerary_data', true );
		echo '<h2>';
		esc_html_e( 'Itinerary', 'text-domain' );
		echo '</h2>';

		if ( is_array( $itineraries ) && count( $itineraries ) > 0 ) {
			?>
			<ul><?php
			foreach ( $itineraries as $x=>$itinerary ) {
				echo '<li class="itinerary-li">';
				if ( empty($itineraries[$x]['date']) ) {
					echo '<input type="text" style="color:blue; width:fit-content" name="itinerary-date-'.$x.'" id="itinerary-date-'.$x.'">';
				} else {
					echo $itineraries[$x]['date'] . '<br>';
				}
				echo esc_attr( $itineraries[$x]['label'] ) . ', ' . esc_attr( $itineraries[$x]['title'] );
				echo '<br>';
				echo esc_attr( $itineraries[$x]['desc'] );
				echo '</li>';
			} ?>
			</ul><?php
		} else {
			echo __( 'No Itineraries found.', 'text-domain' );
			?>
			<span><?php esc_html_e( 'No Itineraries found.', 'text-domain' ); ?></span><?php
		}
	}

	function trip_includes_tab_content() {

		global $post;
		$post_id = $post->ID;
		$trip_include = get_post_meta( $post_id, 'wp_travel_trip_include', true );
		echo '<h2>';
		esc_html_e( 'Trip Includes', 'text-domain' );
		echo '</h2>';
		//echo '<br>';
		if (!empty($trip_include)) {
			echo esc_attr( $trip_include );
		} else {
			esc_html_e( 'No Trip Include found.', 'text-domain' );
		}
		//echo '<br>';
	}	

	function trip_excludes_tab_content() {

		global $post;
		$post_id = $post->ID;
		$trip_exclude = get_post_meta( $post_id, 'wp_travel_trip_exclude', true );
		echo '<h2>';
		esc_html_e( 'Trip Excludes', 'text-domain' );
		echo '</h2>';
		//echo '<br>';
		if (!empty($trip_exclude)) {
			echo esc_attr( $trip_exclude );
		} else {
			esc_html_e( 'No Trip Exclude found.', 'text-domain' );
		}
	}	

	function faq_tab_content() {

		global $post;
		$post_id = $post->ID;
		$faqs = wp_travel_get_faqs( $post_id );
		echo '<h2>';
		esc_html_e( 'FAQ : ', 'text-domain' );
		echo '</h2>';
		if ( is_array( $faqs ) && count( $faqs ) > 0 ) { 
			?>
			<ul><?php
			foreach ( $faqs as $key=>$value ) { ?>
				<li><?php echo esc_attr( $faqs[$key]['question'] ); ?><br><?php
				echo esc_attr( $faqs[$key]['answer'] ); ?></li><?php
			} ?>
			</ul><?php
		} else { ?>
			<span><?php esc_html_e( 'No FAQs found.', 'text-domain' ); ?></span><?php
		}
	}

	function booking_tab_content() {

		echo '<h2>' . __( 'Booking : ', 'text-domain' ) . '</h2>';
		?>
		<div class="tabs">
			<ul class="tab-links">
				<li class="active"><a href="#tab1"><?php esc_html_e( 'Completed', 'text-domain' ); ?></a></li>
				<li><a href="#tab2"><?php esc_html_e( 'Processing', 'text-domain' ); ?></a></li>
				<li><a href="#tab3"><?php esc_html_e( 'On hold', 'text-domain' ); ?></a></li>
			</ul>
	
			<div class="tab-content">
				<div id="tab1" class="tab active">
					<?php self::completed_tab_content(); ?>
				</div>
	
				<div id="tab2" class="tab">
					<?php self::processing_tab_content(); ?>
				</div>
	
				<div id="tab3" class="tab">
					<?php self::on_hold_tab_content(); ?>
				</div>
			</div>
		</div>

		<style>
		/*----- Tabs -----*/
		.tabs {
			width:100%;
			display:inline-block;
		}

		/*----- Tab Links -----*/
		/* Clearfix */
		.tab-links {
			list-style-type:none;
		}

		.tab-links:after {
			display:block;
			clear:both;
			content:'';
		}

		.tab-links li {
			margin:0px 5px;
			float:left;
			float:left;
		}

		.tab-links a {
			padding:9px 15px;
			display:inline-block;
			border-radius:3px 3px 0px 0px;
			background:#7FB5DA;
			font-size:16px;
			font-weight:600;
			color:#4c4c4c;
			transition:all linear 0.15s;
		}

		.tab-links a:hover {
			background:#a7cce5;
			text-decoration:none;
		}

		li.active a, li.active a:hover {
			background:#fff;
			color:#4c4c4c;
		}

		/*----- Content of Tabs -----*/
		.tab-content {
			padding:15px;
			border-radius:3px;
			box-shadow:-1px 1px 1px rgba(0,0,0,0.15);
			background:#fff;
		}

		.tab {
			display:none;
		}

		.tab.active {
			display:block;
		}
		</style>

		<script>
		jQuery(document).ready(function() {
			jQuery('.tabs .tab-links a').on('click', function(e) {
				var currentAttrValue = jQuery(this).attr('href');

				// Show/Hide Tabs
				jQuery('.tabs ' + currentAttrValue).show().siblings().hide();

				// Change/remove current tab to active
				jQuery(this).parent('li').addClass('active').siblings().removeClass('active');

				e.preventDefault();
			});
		});
		</script>
		<?php
	}

	function completed_tab_content() {

		global $post;
		$post_id = $post->ID;
		// Set the orders statuses
		$statuses = array( 'wc-completed' );

		//$orders_ids = get_orders_ids_by_product_id( 37, $statuses );
		$orders = self::get_orders_ids_by_product_id( $post_id, $statuses );
		echo '<h2>' . __( 'Booking : ', 'text-domain' ) . '</h2>';
		if ( is_array( $orders ) && count( $orders ) > 0 ) { 
			echo '<ul>';
			foreach ( $orders as $key=>$value ) { 
				echo '<li>';
				echo esc_attr($value);
				echo '</li>';
			}
			echo '</ul>';
		} else { 
			echo __( 'No Completed Orders found.', 'text-domain' );
		}
	}

	function processing_tab_content() {

		global $post;
		$post_id = $post->ID;
		// Set the orders statuses
		//$statuses = array( 'wc-completed', 'wc-processing', 'wc-on-hold' );
		$statuses = array( 'wc-processing' );
		$order_ids = self::get_orders_ids_by_product_id( $post_id, $statuses );
		if ( is_array( $order_ids ) && count( $order_ids ) > 0 ) { 
			echo '<table>';
			echo '<tr>';
			echo '<th>'. __( 'Date', 'text-domain' ) .'</th>';
			echo '<th>'. __( 'QTY', 'text-domain' ) .'</th>';
			echo '<th>'. __( 'Order ID', 'text-domain' ) .'</th>';
			echo '<th>'. __( 'Customer', 'text-domain' ) .'</th>';
			echo '<th>'. __( 'Email', 'text-domain' ) .'</th>';
			echo '</tr>';
			foreach ( $order_ids as $order_id ) {
				$order = wc_get_order( $order_id );
				$itinerary_date = '';
				$quantity = '';
				$billing_first_name = $order->get_billing_first_name();
				$billing_last_name  = $order->get_billing_last_name();
				$billing_email  = $order->get_billing_email();

				// Iterating though each order item
				foreach( $order->get_items() as $item_id => $line_item ) {
					if ($line_item->get_product_id()==$post_id) {
						$itinerary_date = get_post_meta( 'itinerary_date', $line_item->get_product_id());
						$quantity = get_post_field( 'quantity', $line_item->get_product_id());
						// Get the vendor ID
						$vendor_id = get_post_field( 'post_author', $line_item->get_product_id());
						$vendor = get_userdata( $vendor_id );
						$email = $vendor->user_email;	
						// Avoiding duplicates (if many items with many emails)
						// or an existing email in the recipient
						if( ! in_array( $email, $additional_recipients ) && strpos( $recipient, $email ) === false )
							$additional_recipients[] = $email;
					}
				}
	
				echo '<tr>';
				echo '<td>'. $itinerary_date .'</td>';
				echo '<td>'. $quantity .'</td>';
				echo '<td>'. $order_id .'</td>';
				echo '<td>'. $billing_first_name . ' ' . $billing_last_name .'</td>';
				echo '<td>'. $billing_email .'</td>';
				echo '</tr>';
			}
			echo '</table>';
		} else { 
			echo __( 'No Processing Orders found.', 'text-domain' );
		}
	}

	function on_hold_tab_content() {

		global $post;
		$post_id = $post->ID;
		// Set the orders statuses
		$statuses = array( 'wc-on-hold' );

		//$orders_ids = get_orders_ids_by_product_id( 37, $statuses );
		$orders = self::get_orders_ids_by_product_id( $post_id, $statuses );
		echo '<h2>' . __( 'Booking : ', 'text-domain' ) . '</h2>';
		if ( is_array( $orders ) && count( $orders ) > 0 ) { 
			echo '<ul>';
			foreach ( $orders as $key=>$value ) { 
				echo '<li>';
				echo esc_attr($value);
				echo '</li>';
			}
			echo '</ul>';
		} else { 
			echo __( 'No On Hold Orders found.', 'text-domain' );
		}
	}

/**
 * Get All orders IDs for a given product ID.
 *
 * @param  integer  $product_id (required)
 * @param  array    $order_status (optional) Default is 'wc-completed'
 *
 * @return array
 */
function get_orders_ids_by_product_id( $product_id, $order_status = array( 'wc-completed' ) ){
    global $wpdb;

    $results = $wpdb->get_col("
        SELECT order_items.order_id
        FROM {$wpdb->prefix}woocommerce_order_items as order_items
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
        LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
        WHERE posts.post_type = 'shop_order'
        AND posts.post_status IN ( '" . implode( "','", $order_status ) . "' )
        AND order_items.order_item_type = 'line_item'
        AND order_item_meta.meta_key = '_product_id'
        AND order_item_meta.meta_value = '$product_id'
    ");

    return $results;
}

	/**
 	* Add a booking sub_tab
 	*/
	function booking_sub_tab() {

		// Set the orders statuses
		$tabs = array();
		$statuses = array( 'completed', 'processing', 'on_hold' );
		foreach ( $statuses as $key=>$value ) {
			$tabs[$key] = array(
				'title' 	=> __( $value, 'text-domain' ),
				'priority' 	=> 20,
				'callback' 	=> array( __CLASS__, $key . '_tab_content' )
			);		
		}
		return $tabs;
	}
	

}
new Trip_Options_View;