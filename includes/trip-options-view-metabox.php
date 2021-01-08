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
		add_action( 'wp_ajax_nopriv_mxp_ajax_get_next_page_data', array( __CLASS__, 'mxp_ajax_get_next_page_data' ) );
	}

	function custom_datepicker() {
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-core');          
		wp_enqueue_script('jquery-ui-timepicker-addon',get_stylesheet_directory_uri().'/js/jquery-ui-timepicker-addon.js',array());
		wp_enqueue_style('jquery-ui-timepicker-addon',get_stylesheet_directory_uri().'/css/jquery-ui-timepicker-addon.css',array());
		wp_enqueue_style('jquery-ui',get_stylesheet_directory_uri().'/css/jquery-ui.css',array());
		wp_enqueue_style('jquery-ui','https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css',array());
		//<link href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css">
		wp_enqueue_script('main', get_template_directory_uri() . '/js/main.js');
		wp_localize_script('main', 'WCTPE', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('mxp-ajax-nonce'),
		));	
	}
	
	function mxp_ajax_get_next_page_data() {
		$max_num_pages = $_POST['max_num_pages'];
		$current_page = $_POST['current_page'];
		$found_posts = $_POST['found_posts'];
		$nonce = $_POST['nonce'];
		if (!wp_verify_nonce($nonce, 'mxp-ajax-nonce')) {
			wp_send_json_error(array('code' => 500, 'data' => '', 'msg' => '錯誤的請求'));
		}
		if (!isset($max_num_pages) || $max_num_pages == "" ||
			!isset($current_page) || $current_page == "" ||
			!isset($found_posts) || $found_posts == "") {
			wp_send_json_error(array('code' => 500, 'data' => '', 'msg' => '錯誤的請求'));
		}
		$ids = get_posts(array(
			'fields' => 'ids', // Only get post IDs
			'posts_per_page' => get_option('posts_per_page'),
			'post_type' => 'post',
			'paged' => intval($current_page) + 1,
		));
		$str = '';
		foreach ($ids as $key => $id) {
			$name = get_post_meta($id, 'wctp2018-author-name', true);
			$title = mb_substr(get_post_meta($id, 'wctp2018-post-title', true), 0, 20);
			$content = mb_substr(get_post_meta($id, 'wctp2018-post-content', true), 0, 40) . "...";
			$image_large = get_post_meta($id, 'wctp2018-post-image-large', true);
			$str .= '<div class="col-md-3 m_b_20 post"><div class="box"><div class=" post_img"><a href="' . get_permalink($id) . '"><img src="' . $image_large . '"/></a></div><a href="' . get_permalink($id) . '" class="name"><h2 >' . $content . ' - ' . $name . '</h2></a></div></div>';
		}
		wp_send_json_success(array('code' => 200, 'data' => $str));
	}

	//add_action( 'woocommerce_single_product_summary', 'custom_action_after_single_product_title', 6 );
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
		echo '<table><tr><td>';
		esc_html_e( 'Start Date : ', 'wp-travel' );
		echo '</td><td>';
		echo '<input type="text" class="start_date" name="start_date" />';
		echo '</td></tr></table>';
		?>
		<script>
			jQuery(document).ready(function($) {
				$( '.start_date' ).datepicker();
				$( '.start_date' ).on( 'change', function() {
					var start_date = this.value;
					$( '.itinerary-li' ).each( function( index, element ) {
						$( 'span', element ).append(start_date);
						$( 'span', element ).append(' ');
					});
				});
/*				
					var categories = '';
					$.ajax({
            			type: 'POST',
            			url: '/wp-admin/admin-ajax.php',
            			dataType: "json",
            			data: {
							'action': 'update_start_date',
        				},
            			success: function (data) {
							categories = data;
            			},
            			error: function(error){
							alert(error);
						}
        			});
*/

/*				
				$( '.start_time' ).datetimepicker({
					format: 'HH:mm'
				});

				$('.more_posts').click(function() {
            		$(this).attr('disabled', true);
            		var that = $(this);
            		if (WCTPE.posts.max_num_pages == WCTPE.posts.current_page) {
                		$(this).text('The END! / 最後一頁');
                		$(this).unbind('click');
                		return;
            		}
            		$("body").waitMe({
                		effect: "bounce",
                		text: "Loading...",
            		});
            		var data = {
                		'action': 'mxp_ajax_get_next_page_data',
                		'nonce': WCTPE.nonce,
                		'max_num_pages': WCTPE.posts.max_num_pages,
                		'current_page': WCTPE.posts.current_page,
                		'found_posts': WCTPE.posts.found_posts,
            		};

            		$.post(WCTPE.ajaxurl, data, function(res) {
                		if (res.success) {
                    		$('.tattoo_posts_lists').append(res.data.data);
                    		history.pushState(null, null, '/page/' + (WCTPE.posts.current_page + 1) + '/');
                    		WCTPE.posts.current_page += 1;
                    		that.attr('disabled', false);
                		} else {
                    		alert('Oops! Sorry error occurred!');
                    		location.reload();
                		}
                		$("body").waitMe('hide');
            		}).fail(function() {
                		alert('Oops! Sorry error occurred! Internet issue.');
            		});
				});	
*/							
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
	
	//function overview_tab_content($start_date = false) {
	function overview_tab_content() {
		$post_id = get_the_ID();
		$itineraries = get_post_meta( $post_id, 'wp_travel_trip_itinerary_data', true );

		if ( is_array( $itineraries ) && count( $itineraries ) > 0 ) {
			echo '<h4 style="text-align:right">';
			esc_html_e( 'Itinerary', 'wp-travel' );
			echo '</h4>';
			?>
			<ul class='itinerary-li'><?php
			foreach ( $itineraries as $x=>$itinerary ) { ?>
				<li><?php 
				echo '<span style="color:red"></span>';
				echo esc_attr( $itineraries[$x]['label'] ) . ', ' . 
				esc_attr( $itineraries[$x]['title'] ); ?><br><?php
				echo esc_attr( $itineraries[$x]['desc'] ); ?></li><?php
			} ?>
			</ul><?php
		} else { ?>
			<span><?php esc_html_e( 'No Itineraries found.', 'wp-travel' ); ?></span><?php
		}
	}

	function trip_includes_tab_content() {
		$post_id = get_the_ID();
		$trip_include = get_post_meta( $post_id, 'wp_travel_trip_include', true );
		echo '<h3 style="text-align:right">';
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
		echo '<h3 style="text-align:right">';
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