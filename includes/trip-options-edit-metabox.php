<?php
class Trip_Options_Edit_Metabox {

	/**
	 * Constructor.
	 */
	function __construct() {
		add_action( 'admin_menu', array( __CLASS__, 'trip_options_add_metabox' ) );
		add_action( 'save_post', array( __CLASS__, 'trip_options_save_metabox' ), 10, 2 );

		add_filter( 'product_type_options', array( __CLASS__, 'add_remove_product_options' ) );
		add_filter( 'woocommerce_product_data_tabs', array( __CLASS__, 'custom_product_data_tabs' ), 10, 1 );
		add_filter( 'woocommerce_allow_marketplace_suggestions', '__return_false' );

		add_action( 'woocommerce_product_data_panels', array( __CLASS__, 'trip_options_callback_itinerary' ) );
		add_action( 'woocommerce_product_data_panels', array( __CLASS__, 'trip_options_callback_includes_excludes' ) );
		add_action( 'woocommerce_product_data_panels', array( __CLASS__, 'trip_options_callback_faqs' ) );

		add_action( 'admin_head', array( __CLASS__, 'dgc_custom_script' ) );
		add_action( 'admin_head', array( __CLASS__, 'dgc_custom_style' ) );

		add_action( 'wp_ajax_get_categories', array( __CLASS__, 'get_categories' ) );
		add_action( 'wp_ajax_nopriv_get_categories', array( __CLASS__, 'get_categories' ) );
		add_action( 'wp_ajax_get_resources_by_category', array( __CLASS__, 'get_resources_by_category' ) );
		add_action( 'wp_ajax_nopriv_get_resources_by_category', array( __CLASS__, 'get_resources_by_category' ) );
		
		//add_action( 'wp_enqueue_scripts', array( __CLASS__, 'cortez_enqueue_script' ) );
		//add_action( 'wp_ajax_cortez_get_terms', array( __CLASS__, 'cortez_get_terms' ) );
		//add_action( 'wp_ajax_nopriv_cortez_get_terms', array( __CLASS__, 'cortez_get_terms' ) );

	}

	/**
	 * Remove 'Virtual','Downloadable' product options
	 * Add 'Itinerary' product options
	 */
	function add_remove_product_options( $options ) {

		// remove "Virtual" checkbox
		if( isset( $options[ 'virtual' ] ) ) {
			unset( $options[ 'virtual' ] );
		}
 
		// remove "Downloadable" checkbox
		if( isset( $options[ 'downloadable' ] ) ) {
			unset( $options[ 'downloadable' ] );
		}

		// Create Categories
		wp_insert_term(
			__( "Itinerary", "wp-travel" ), // the term 
			'product_cat', // the taxonomy
			array(
	  			'description'=> __( "Category of Itinerary", "wp-travel" ),
	  			'slug' => 'itinerary'
			)
  		);

		wp_insert_term(
			__( "Stay", "wp-travel" ), // the term 
			'product_cat', // the taxonomy
			array(
	  			'description'=> __( "Category of Stay", "wp-travel" ),
	  			'slug' => 'stay'
			)
  		);

		wp_insert_term(
			__( "Dinner", "wp-travel" ), // the term 
			'product_cat', // the taxonomy
			array(
	  			'description'=> __( "Category of Dinner", "wp-travel" ),
	  			'slug' => 'dinner'
			)
  		);

		wp_insert_term(
			__( "Lunch", "wp-travel" ), // the term 
			'product_cat', // the taxonomy
			array(
	  			'description'=> __( "Category of Lunch", "wp-travel" ),
	  			'slug' => 'lunch'
			)
  		);

		  wp_insert_term(
			__( "Breakfast", "wp-travel" ), // the term 
			'product_cat', // the taxonomy
			array(
	  			'description'=> __( "Category of Breakfast", "wp-travel" ),
	  			'slug' => 'breakfast'
			)
  		);

		// Gets term object from Category in the database. 
/*
		$term = get_term_by('name', __( "Itinerary", "wp-travel" ), 'product_cat');
		wp_set_object_terms( $post_id, $term->term_id, 'product_cat' );
*/

		$options['itinerary'] = array(
			'id'            => '_itinerary',
			'wrapper_class' => 'show_if_simple show_if_variable',
			'label'         => __( 'Itinerary', 'dgc-domain' ),
			'description'   => __( 'Itinerary allow users to put in personalised messages.', 'dgc-domain' ),
			'default'       => 'no'
		);

		return $options;
	}

	/**
	 * Remove "Shipping","Attributes" Product Data tabs
	 * Add "Itinerary","Includes/Excludes","FAQs" Product Data tabs
 	 */
	function custom_product_data_tabs( $tabs ) {

		// remove "Shipping" tab
		if( isset( $tabs[ 'shipping' ] ) ) {
			unset( $tabs[ 'shipping' ] );
		}

		// remove "Attributes" tab
		if( isset( $tabs[ 'attribute' ] ) ) {
			unset( $tabs[ 'attribute' ] );
		}

    	$tabs['itinerary'] = array(
        	'label'   =>  __( 'Itinerary', 'dgc-domain' ),
        	'target'  =>  'itinerary_panel',
        	'priority' => 60,
        	'class'   => array( 'show_if_itinerary')
    	);
    	$tabs['include_exclude'] = array(
        	'label'   =>  __( 'Includes/Excludes', 'dgc-domain' ),
        	'target'  =>  'include_exclude_panel',
        	'priority' => 60,
        	'class'   => array( 'show_if_itinerary')
    	);
    	$tabs['faq'] = array(
        	'label'   =>  __( 'FAQs', 'dgc-domain' ),
        	'target'  =>  'faq_panel',
        	'priority' => 60,
        	'class'   => array( 'show_if_itinerary')
    	);
    	return $tabs;
	}

	/**
	 * Add a bit of script.
	 */
	function dgc_custom_script() {
		?>
		<script>
			jQuery(document).ready(function($) {
				/*
				 * Woocommerce Product Data Metabox Options
				 */
				$( 'input#_itinerary' ).change( function() {
					var is_itinerary = $( 'input#_itinerary:checked' ).length;
					$( '.show_if_itinerary' ).hide();
					$( '.hide_if_itinerary' ).hide();

					if ( is_itinerary ) {
						$( '.hide_if_itinerary' ).hide();
					}
					if ( is_itinerary ) {
						$( '.show_if_itinerary' ).show();
					}
				});
				$( 'input#_itinerary' ).trigger( 'change' );

				/*
				 * FAQs Tab
				 */
				//$( ".faq-li" ).hide();

				$( ".faq-li" ).each( function( index, element ) {
					if ( !$( 'p', element ).is(":empty") ) {
						$( ".faq-init-rows" ).show();
						$( element ).show();
						$( element ).delegate("span", "click", function(){
							$( 'table', element ).toggleClass('toggle-access');
						});
					};

					$( element ).delegate(".item-title", "keyup", function(){
						$( 'span', element ).text($(this).val());
					});
				});

				$( '#first-faq' ).on( 'click', function() {
					$(".no-faqs").hide();
					$(".faq-init-rows").show();
					$(".faq-li").hide();
					$("#faq-li-0").show();
					$('span','#faq-li-0').on('click', function() {
						$('table','#faq-li-0').toggleClass('toggle-access');
					});
				});

				$( '.add-faq' ).on( 'click', function() {
					$( ".faq-li" ).each( function( index, element ) {
						if ( $( element ).is(":hidden") ) {
							$( element ).show();
							$( element ).delegate("span", "click", function(){
								$( 'table', element ).toggleClass('toggle-access');
							});
							return false;
						};
					});
				});

				$( '.remove-faq' ).on( 'click', function() {
					if (confirm('Are you sure?') == true) {
						$( this ).closest('.faq-li').remove();
					};
				});

				/*
				 * Itinerary Tab
				 */
				//$( '.itinerary-li' ).hide();
				var x = 0;
				$( '.itinerary-li' ).each( function( index, element ) {
					x = x + 1;

					if ( !$( 'p', element ).is( ':empty' ) ) {
						$( '.itinerary-rows' ).show();
						$( element ).show();
						$( element ).delegate( 'span', 'click', function() {
							$( 'table', element ).toggleClass( 'toggle-access' );
						});
					};

					$( element ).delegate( '.item-label', 'keyup', function() {
						$( '.span-label', element ).text($(this).val());
					});

					$( element ).delegate( '.item-title', 'keyup', function() {
						$( '.span-title', element ).text($(this).val());
					});

					$( element ).delegate( '#first-assignment', 'click', function() {
						$( '.no-assignments', element ).hide();
						$( '.assignment-header', element ).show();
					});

					$( element ).delegate( '.add-assignment', 'click', function() {
						//var ajax_url = '/wp-admin/admin-ajax.php';
        				$.ajax({
            				type: 'POST',
            				url: '/wp-admin/admin-ajax.php',
            				dataType: "json",
            				data: {
								'action': 'get_categories',
        					},
            				success: function (data) {
								var add_assignment = '<tr class="assignment-rows"><td>';
								add_assignment += '<select style="width:100%" class="opt-categorias" id="opt-category-'+ index +'" name="itinerary_item_assignment-'+ index +'-category-'+ index +'">';
								add_assignment += '<option>- Select Category -</option>';
								$.each(data, function (i, item) {
									add_assignment += '<option value="' + item + '">' + item + '</option>';
                				});
								add_assignment += '</select></td><td id="td-resource-'+ index +'">';
								//add_assignment += '<select style="width:100%" class="opt-tipo" name="itinerary_item_assignment-'+ index +'-resource-'+ index +'">';
								//add_assignment += '<option>- Select Resource -</option></select>';
								add_assignment += '</td></tr>';
								$( '#end-of-assignment', element ).before(add_assignment);				
								$( '#opt-category-'+index, element ).on( 'change', function() {
									alert(this.value);
									add_resource = '<select style="width:100%" class="opt-tipo" name="itinerary_item_assignment-'+ index +'-resource-'+ index +'">';
									add_resource += '<option>- Select Resource -</option></select>';
									$( '#td-resource-'+index, element ).append(add_resource);						
								});
            				},
            				error: function(error){
								alert(error);
							},
            				complete: function () {}
        				});
/*
						$( '.assignment-rows', element ).each( function( sub_index, sub_element ) {

							//$( '.opt-categorias' ).on( 'change', function() {
							$( sub_element ).delegate( '.opt-categorias', 'change', function() {
								var opt_categorias = this.value;
								alert(opt_categorias+'-'+sub_index+'-'+sub_element);
						
								//var ajax_url = '/wp-admin/admin-ajax.php';
        						$.ajax({
            						type: 'POST',
            						url: '/wp-admin/admin-ajax.php',
            						dataType: "json",
            						data: {
										'action': 'get_resources_by_category',
                						'term_chosen': opt_categorias,
            						},
            						success: function (data) {
										//alert(data.length);
										$( '.opt_tipo', '.assignment-rows' ).empty();
                						$( '.opt_tipo', '.assignment-rows' ).append("<option value=''>- Select Resource -</option>");

                						$.each(data, function (i, item) {
                    						$( '.opt_tipo', '.assignment-rows' ).append('<option value="' + item + '">' + item + '</option>');
                						});
									
            						},
            						error: function(error){
										alert(error);
            						},
            						complete: function () {
            						}
        						});											
    						});
						});
*/						
					});
				});

				$( '#first-itinerary' ).click( function() {
					$( '.no-itineraries' ).hide();
					$( '.itinerary-header' ).show();
					/*
					$( '.itinerary-rows' ).show();					
					$( '.itinerary-li' ).hide();
					$( '#itinerary-li-0' ).show();
					$( 'span', '#itinerary-li-0' ).on( 'click', function() {
						$( 'table', '#itinerary-li-0' ).toggleClass( 'toggle-access' );
					});*/
				});

				$( '.add-itinerary' ).on( 'click', function() {
					//var itinerary_label = DEFAULT_ITINERARY_LABEL;
					//var itinerary_title = DEFAULT_ITINERARY_TITLE;
					var itinerary_label = 'Day X';
					var itinerary_title = 'My Plan';
					var add_itinerary = '<li class="itinerary-li" id="itinerary-li-' + x + '">';
					add_itinerary += '<span class="fas fa-bars"> </span>';
					add_itinerary += '<span class="span-label">' + itinerary_label + '</span>, ';
					add_itinerary += '<span class="span-title">' + itinerary_title + '</span>';
					add_itinerary += '<table style="display:none">';
					add_itinerary += '<tr>';
					add_itinerary += '<th>Itinerary label</th>';
					add_itinerary += '<td><input type="text" class="item-label" name="itinerary_item_label-' + x + '" value="' + itinerary_label + '"></td>';
					add_itinerary += '</tr>';
					add_itinerary += '<tr>';
					add_itinerary += '<th>Itinerary title</th>';
					add_itinerary += '<td><input type="text" class="item-title" name="itinerary_item_title-' + x + '" value="' + itinerary_title + '"></td>';
					add_itinerary += '</tr>';
					add_itinerary += '<tr>';
					add_itinerary += '<th>Itinerary date</th>';
					add_itinerary += '<td><input type="text" class="item-date" name="itinerary_item_date-' + x + '"></td>';
					add_itinerary += '</tr>';
					add_itinerary += '</table>';

					$( '#end-of-itinerary' ).before(add_itinerary);
/*
					var element = '#itinerary-li-' + x ;
					//alert(element);
					//$( element ).delegate( 'span', 'click', function() {
						//$( 'table', element ).toggleClass( 'toggle-access' );
					$( 'span', '#itinerary-li-4' ).on( 'click', function() {
						$( 'table', '#itinerary-li-4' ).toggleClass( 'toggle-access' );
					});
					x = x + 1;
*/
					
					$( '.itinerary-li' ).each( function( index, element ) {
						//if ( $( element ).is( ':hidden' ) ) {
						//	$( element ).show();
							$( element ).delegate( 'span', 'click', function() {
								$( 'table', element ).toggleClass( 'toggle-access' );
							});
						//	return false;
						//};
					});
				});

				$( '.remove-itinerary' ).on( 'click', function() {
					if (confirm('Are you sure?') == true) {
						$( this ).closest('.itinerary-li').remove();
					};
				});

				$( '.item_date' ).datepicker();
				$( '.item_time' ).timepicker({format: 'HH:mm'});

			});

		</script>
		<?php
	}

	function get_categories() {
		$args = array(
			'taxonomy'   => "product_cat",
			'number'     => $number,
			'orderby'    => $orderby,
			'order'      => $order,
			'hide_empty' => $hide_empty,
			'include'    => $ids
		);
		$product_categories = get_terms($args);

		$titles = array();
		foreach( $product_categories as $cat ) {
			if ($cat->name != 'Uncategorized') {
				array_push($titles, $cat->name);
			}
		}
		$json = json_encode( $titles );
		echo $json;
		
		die();		
	}
		
	function get_resources_by_category() {

		$product_category_slug = ( isset($_POST['term_chosen']) && !empty( $_POST['term_chosen']) ? $_POST['term_chosen'] : false );
		
		$query = new WC_Product_Query( array(
			'category' => array( $product_category_slug ),
			'limit' => 10,
			'orderby' => 'date',
			'order' => 'DESC'
		) );
		
		$products = $query->get_products();
		
		$titles = array();
		foreach( $products as $product ) {
			array_push($titles, $product->get_title());
		}	
		$json = json_encode( $titles );
		echo $json;
		
		die();
		
	}
		
	/**
	 * Add a bit of style.
	 */
	function dgc_custom_style() {
		?>
		<style>
			#woocommerce-product-data ul.wc-tabs li.itinerary_tab a:before { font-family: WooCommerce; content: '\e900'; }
			#woocommerce-product-data ul.wc-tabs li.include_exclude_tab a:before { font-family: WooCommerce; content: '\e604'; }
			#woocommerce-product-data ul.wc-tabs li.faq_tab a:before { font-family: WooCommerce; content: '\e000'; }
			.fa-bars:before { content: "\f0c9"; }

			/*#first-itinerary { color:blue; text-decoration:underline; cursor:pointer; }*/
			#first-itinerary { background:#ffffff; color:blue; border: none; cursor:pointer; text-decoration:underline; }
			/*button.add-itinerary { font-size:0.8em; color:blue; }*/
			#itineraries-ul { list-style-type:none; margin:0; padding:0; width:100%; }
  			#itineraries-ul li { background:#f2f2f2; border:1px solid #ccc; margin:0 3px 3px 3px; padding:0.4em; padding-left:1.5em; font-size:1.4em; }
			#itineraries-ul li span { cursor:pointer; }
			#itineraries-ul li span.fas.fa-bars { margin-left:-1.3em; }
			#itineraries-ul li table { display:none; background:#ffffff; border:1px solid #ccc; margin-left:-1.3em; margin-top:0.3em; padding:0.5em; font-size:1.0em; }
			#itineraries-ul li table.toggle-access { display:block; }
			#itineraries-ul li th { width:20%; }
			#itineraries-ul li input { width:100%; }
			#itineraries-ul li textarea { width:100%; }
			#itineraries-ul li button.remove-itinerary { font-size:0.8em; color:red; width:100% }

			#itineraries-ul li th.assignment-row-head { width:30%; }
			#itineraries-ul li button#first-assignment { background:#ffffff; color:blue; border: none; cursor:pointer; }
			#itineraries-ul li button.add-assignment { font-size:0.7em; color:blue; }

			#first-faq { color:blue; text-decoration:underline; cursor:pointer; }
			/*button.add-faq { font-size:0.8em; color:blue; }*/
			#faqs-ul { list-style-type:none; margin:0; padding:0; width:100%; }
  			#faqs-ul li { background:#f2f2f2; border:1px solid #ccc; margin:0 3px 3px 3px; padding:0.4em; padding-left:1.5em; font-size:1.4em; }
			#faqs-ul li span { cursor:pointer; }
			#faqs-ul li span.fas.fa-bars { margin-left:-1.3em; }
			#faqs-ul li table { display:none; background:#ffffff; border:1px solid #ccc; margin-left:-1.3em;  margin-top:0.3em; padding:0.5em; font-size:1.0em; }
			#faqs-ul li .toggle-access { display:block; }
			#faqs-ul li th { width:25%; }
			#faqs-ul li input { width:100%; }
			#faqs-ul li textarea { width:100%; }
			#faqs-ul li button.remove-faq { font-size:0.8em; color:red; width:100% }

		</style>
		<?php
	}

	/**
	 * Product Categories List
	 */
	function product_categories_name_options( $product_category_slug ) {
		// since wordpress 4.5.0
		$args = array(
			'taxonomy'   => "product_cat",
			'number'     => $number,
			'orderby'    => $orderby,
			'order'      => $order,
			'hide_empty' => $hide_empty,
			'include'    => $ids
		);
		$product_categories = get_terms($args);
		//$product_category_slug='Itinerary';
		echo '<option value="" selected disabled hidden>' .  __( "- Select Category -", "wp-travel" ) . '</option>';
		foreach( $product_categories as $cat ) {
			//if ($cat->name != 'Uncategorized') {
/*				
				if ($cat->name == $product_category_slug) {
					echo '<option value="' . $cat->name . '" selected="selected">' . $cat->name . '</option>';
				} else {
					echo '<option value="' . $cat->name . '">' . $cat->name . '</option>';
				}
*/				
			//}
			echo '<option value="' . $cat->name . '">' . $cat->name . '</option>';
		}
	}

	/**
	 * Product List by Category
	 */
	function product_name_options_by_category( $product_category_slug=false, $resource=false ) {

		$query = new WC_Product_Query( array(
			'category' => array( $product_category_slug ),
			'limit' => 10,
			'orderby' => 'date',
			'order' => 'DESC'
		) );
	   
		$products = $query->get_products();
		
		if (isset($resource)) {
			echo '<option value="" selected disabled hidden>' .  __( "- Select Resource -", "wp-travel" ) . '</option>';
		}
		foreach( $products as $product ) {
			$title = $product->get_title();
			if ($title == $resource) {
				echo '<option value="' . $title . '" selected>' . $title . '</option>';
			} else {
				echo '<option value="' . $title . '">' . $title . '</option>';
			}
		}		
	}


	/**
	 * Itinerary metabox callback
	 */
	function trip_options_callback_itinerary( $post ) {
		if ( ! $post ) {
			global $post;
		}
		$trip_code = wp_travel_get_trip_code( $post->ID );
		$trip_outline = get_post_meta( $post->ID, 'wp_travel_outline', true );
		$itineraries = get_post_meta( $post->ID, 'wp_travel_trip_itinerary_data', true );
		$remove_itinerary = __( "- Remove Itinerary", "wp-travel" );
		$product_categories = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false ) );
		?>
		<div id='itinerary_panel' class='panel woocommerce_options_panel'>
		<table style="width:100%; padding:1em">
			<tr>
				<td><h3><?php esc_html_e( 'Trip Code : ', 'wp-travel' ); ?></h3></td>
				<td><input type="text" disabled="disabled" value="<?php echo esc_attr( $trip_code ); ?>" /></td>
			</tr>

		<?php
		$x = 0;
		if ( is_array( $itineraries ) && count( $itineraries ) > 0 ) {?>
			<tr class="itinerary-header">
				<td><h3><?php esc_html_e( 'Itinerary', 'wp-travel' ); ?></h3></td>
				<td style="text-align:right"><button class="add-itinerary" type="button"><?php esc_html_e( '+ Add Itinerary', 'wp-travel' ); ?></button></td>
			</tr>
			<tr class="itinerary-rows"><td colspan="2">
				<ul id="itineraries-ul">
			<?php
			foreach ( $itineraries as $itinerary ) {
				echo '<li class="itinerary-li" id="itinerary-li-' . $x . '">';
				$itinerary_label = esc_attr( $itineraries[$x]['label'] );
				$itinerary_title = esc_attr( $itineraries[$x]['title'] );
				echo '<span class="fas fa-bars"> </span>';
				echo '<span class="span-label">' . $itinerary_label . '</span>, ';
				echo '<span class="span-title">' . $itinerary_title . '</span>';
				echo '<table>					
						<tr>
							<th>' . __( 'Itinerary label', 'wp-travel' ) .'</th>
							<td><input type="text" class="item-label" name="itinerary_item_label-' . $x . '" value="' . $itinerary_label . '"></td>
						</tr>
						<tr>
							<th>' . __( 'Itinerary title', 'wp-travel' ) .'</th>
							<td><input type="text" class="item-title" name="itinerary_item_title-' . $x . '" value="' . $itinerary_title . '"></td>
						</tr>
						<tr>
							<th>' . __( 'Itinerary date', 'wp-travel' ) .'</th>
							<td><input type="text" class="item_date" name="itinerary_item_date-' . $x . '" value="' . esc_attr( $itineraries[$x]['date'] ) . '"></td>
						</tr>
						<tr>
							<th>' . __( 'Itinerary time', 'wp-travel' ) .'</th>
							<td><input type="text" class="item_time" name="itinerary_item_time-' . $x . '" value="' . esc_attr( $itineraries[$x]['time'] ) . '"></td>
						</tr>
						<tr>
							<td colspan="2"><b>' . __( 'Description', 'wp-travel' ) .'</b><br>
							<textarea rows="5" name="itinerary_item_desc-' . $x . '">' . esc_attr( $itineraries[$x]['desc'] ) . '</textarea></td>
						</tr>
						<tr>
							<td colspan="2">';
								$y=0;
								echo '<table style="width:100%;margin-left:0">';
								if (isset($itineraries[$x]['assignment'])) {
									echo '<tr class="assignment-header">';
									echo '<th class="assignment-row-head">' . __( 'Resources Assignment', 'wp-travel' ) . '</th>';
									echo '<td style="text-align:right"><button class="add-assignment" type="button">' . __( '+ Add Assignment', 'wp-travel' ) .'</button></td>';
									echo '</tr>';
									foreach ( $itineraries[$x]['assignment'] as $assignment ) {
										echo '<tr class="assignment-rows" id="assignment-row-' . $y . '">
										<td>';
										echo '<select style="width:100%" class="opt-categorias" name="itinerary_item_assignment-' . $x . '-category-' . $y . '">';
										foreach( $product_categories as $cat ) {
											if ($cat->name != 'Uncategorized') {
												if ($cat->name == $itineraries[$x]['assignment'][$y]['category']) {
													echo '<option value="' . $cat->name . '" selected>' . $cat->name . '</option>';
												} else {
													echo '<option value="' . $cat->name . '">' . $cat->name . '</option>';
												}
											}
										}
										echo '</select>
										</td>
										<td>';
										echo '<select style="width:100%" class="opt_tipo" name="itinerary_item_assignment-' . $x . '-resource-' . $y . '">';
										self::product_name_options_by_category( $itineraries[$x]['assignment'][$y]['category'], $itineraries[$x]['assignment'][$y]['resource'] );
										echo '</select>';
										echo '</td>
										</tr>';
										$y++;
									}															
								} else {
									echo '<tr style="display:none" class="assignment-header">';
									echo '<th class="assignment-row-head">' . __( 'Resources Assignment', 'wp-travel' ) . '</th>';
									echo '<td style="text-align:right"><button class="add-assignment" type="button">' . __( '+ Add Assignment', 'wp-travel' ) .'</button></td>';
									echo '</tr>';
									echo '<tr class="no-assignments"><td colspan="2">';
									esc_html_e( 'No Assignments found. ', 'wp-travel' );
									echo '<button class="add-assignment" id="first-assignment" type="button">' . __( 'Add Assignment', 'wp-travel' ) . '</button>';
									echo '</td></tr>';
								}

								echo '<tr id="end-of-assignment"></tr>';
								echo '</table>';
							echo '</td>
						</tr>
						<tr>
							<td colspan ="2"><button class="remove-itinerary" type="button">' . $remove_itinerary . '</button></td>
						</tr>
					</table>
			  		</li>';
				$x++;
			}?>			
				<li id="end-of-itinerary" style="display:none"></li>
			</ul>
			</td>
			</tr>
			<tr class="itinerary-header">
				<td></td>
				<td style="text-align:right"><button class="add-itinerary" type="button"><?php esc_html_e( "+ Add Itinerary", "wp-travel" ); ?></button></td>
			</tr>

		<?php
		} else {?>
			<tr style="display:none" class="itinerary-header">
				<td><h3><?php esc_html_e( 'Itinerary', 'wp-travel' ); ?></h3></td>
				<td style="text-align:right"><button class="add-itinerary" type="button"><?php esc_html_e( '+ Add Itinerary', 'wp-travel' ); ?></button></td>
			</tr>
			<tr class="no-itineraries"><td colspan="2">
				<h3><?php esc_html_e( 'Itinerary', 'wp-travel' ); ?></h3><br>
				<span><?php esc_html_e( 'No Itineraries found.', 'wp-travel' ); ?></span>
				<button class="add-itinerary" id="first-itinerary" type="button"><?php esc_html_e( 'Add Itinerary', 'wp-travel' ); ?></span>
			</td></tr><?php
		}?>

			<tr style="display:none" class="itinerary-header">
				<td></td>
				<td style="text-align:right"><button class="add-itinerary" type="button"><?php esc_html_e( "+ Add Itinerary", "wp-travel" ); ?></button></td>
			</tr>
			

		</table>
		</div>
		<?php
	}

	/**
	 * Includes/Excludes metabox callback
	 */
	function trip_options_callback_includes_excludes( $post ) {
		if ( ! $post ) {
			global $post;
		}
		$trip_include = get_post_meta( $post->ID, 'wp_travel_trip_include', true );
		$trip_exclude = get_post_meta( $post->ID, 'wp_travel_trip_exclude', true );
		$settings = array ( 
			"media_buttons" => true, 
			'textarea_rows' => 10
		);
		?>
		<div id='include_exclude_panel' class='panel woocommerce_options_panel'>
			<h3><?php esc_html_e( 'Trip Includes', 'wp-travel' );?></h3>
			<?php wp_editor ( $trip_include , 'wp_travel_trip_include', $settings );?>
			<br><br>
			<h3><?php esc_html_e( 'Trip Excludes', 'wp-travel' );?></h3>
			<?php wp_editor ( $trip_exclude , 'wp_travel_trip_exclude', array ( "media_buttons" => true ) );?>
			<br><br>
		</div>
		<?php		
	}


	/**
	 * FAQs metabox callback
	 *
	 * @param  Object $post Post object.
	 */
	function trip_options_callback_faqs( $post ) {
		if ( ! $post ) {
			global $post;
		}
		$faqs = wp_travel_get_faqs( $post->ID );
		$remove_faq = __( "- Remove FAQ", "wp-travel" );
		?>
		<div id='faq_panel' class='panel woocommerce_options_panel'>
		<table style="width:100%; padding:1em">
			<tr style="display:none" class="faq-itinerary-rows">
				<td><h3><?php esc_html_e( 'FAQ', 'wp-travel' ); ?></h3></td>
				<td style="text-align:right"><button class="add-faq" type="button"><?php esc_html_e( '+ Add FAQ', 'wp-travel' ); ?></button></td>
			</tr>

		<?php
		$xx = 0;
		if ( is_array( $faqs ) && count( $faqs ) > 0 ) {
			foreach ( $faqs as $faq ) {
				$xx++;
			}
		} else {?>
			<tr class="no-faqs"><td colspan="2">
				<span><h3><?php esc_html_e( 'FAQ', 'wp-travel' ); ?></h3></span><br>
				<span><?php esc_html_e( 'Please add new FAQ here.', 'wp-travel' ); ?></span>
				<span id="first-faq"><?php esc_html_e( 'Add FAQ', 'wp-travel' ); ?></span>
			</td></tr><?php
		}?>

			<tr style="display:none" class="faq-itinerary-rows"><td colspan="2">
				<ul id="faqs-ul"><?php			  
				for ($x = 0; $x < 100; $x++) {
					echo '<li style="display:none" class="faq-li" id="faq-li-' . $x . '">';
						echo '<span class="fas fa-bars"> </span>';
					if ($xx<=0) {
						$faq_question = DEFAULT_FAQ_QUESTION;
						echo '<span class="title">' . $faq_question . '</span><p style="display:none"></p>';
					} else {
						$faq_question = esc_attr( $faqs[$x]['question'] );
						echo '<span class="title">' . $faq_question . '</span><p style="display:none">' . $x . '</p>';
					}
					$xx--;
					echo '
					<table>
						<tr>
							<th>' . __( 'Your question', 'wp-travel' ) . '</th>
							<td><input type="text" width="100%" class="item-title" name="faq_item_question-' . $x . '" value="' . $faq_question . '" class="regular-text"></td>
						</tr>
						<tr>
							<th>' . __( 'Your answer', 'wp-travel' ) . '</th>
							<td><textarea rows="5" name="faq_item_answer-' . $x . '" class="regular-text">' . esc_attr( $faqs[$x]['answer'] ) . '</textarea></td>
						</tr>
						<tr>
							<td colspan="2"><button class="remove-faq" type="button">' . $remove_faq . '</button></td>
						</tr>
					</table>
			  		</li>';
				}?>			
				</ul>
			</td></tr>

			<tr style="display:none" class="faq-itinerary-rows">
				<td></td>
				<td style="text-align:right"><button class="add-faq" type="button"><?php esc_html_e( "+ Add FAQ", "wp-travel" ); ?></button></td>
			</tr>
		</table>
		</div>
		<?php		
	}

	function trip_options_save_metabox( $post_id, $post ) {
	
		// nonce check
		if ( ! isset( $_POST[ '_tripnonce' ] ) || ! wp_verify_nonce( $_POST[ '_tripnonce' ], 'somerandomstr' ) ) {
			return $post_id;
		}

		// check current use permissions
		$post_type = get_post_type_object( $post->post_type );

		if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return $post_id;
		}

		// Do not save the data if autosave
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// define your own post type here
		if( $post->post_type != 'product' ) {
			return $post_id;
		}

		// save the checkbox of product data option
		$is_itinerary = isset( $_POST['_itinerary'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, '_itinerary', $is_itinerary );

		/**
		 * Updates a post meta field based on the given post ID.
		 * update_post_meta( int $post_id, string $meta_key, mixed $meta_value, mixed $prev_value = '' )
		 */

		$itineraries = array();
		$xx = 0;
		for ($x = 0; $x < 100; $x++) {
			//if ($_POST['itinerary_item_label-' . $x]!="" && $_POST['itinerary_item_label-' . $x] != DEFAULT_ITINERARY_LABEL && $_POST['itinerary_item_title-' . $x]!="" && $_POST['itinerary_item_title-' . $x] != DEFAULT_ITINERARY_TITLE) {
			if ($_POST['itinerary_item_label-' . $x]!="" && $_POST['itinerary_item_label-' . $x] != DEFAULT_ITINERARY_LABEL) {
				$itineraries[$xx]['label'] = sanitize_text_field( $_POST['itinerary_item_label-' . $x] );
				$itineraries[$xx]['title'] = sanitize_text_field( $_POST['itinerary_item_title-' . $x] );
				$itineraries[$xx]['date'] = sanitize_text_field( $_POST['itinerary_item_date-' . $x] );
				$itineraries[$xx]['time'] = sanitize_text_field( $_POST['itinerary_item_time-' . $x] );
				$itineraries[$xx]['desc'] = sanitize_text_field( $_POST['itinerary_item_desc-' . $x] );
				$yy = 0;
				for ($y = 0; $y < 10; $y++) {
					if ($_POST['itinerary_item_assignment-' . $x . '-category-' . $y]!="") {
						$itineraries[$xx]['assignment'][$yy]['category'] = sanitize_text_field( $_POST['itinerary_item_assignment-' . $x . '-category-' . $y] );
					}
					if ($_POST['itinerary_item_assignment-' . $x . '-resource-' . $y]!="") {
						$itineraries[$xx]['assignment'][$yy]['resource'] = sanitize_text_field( $_POST['itinerary_item_assignment-' . $x . '-resource-' . $y] );
					}
					$yy++;
				}
				$xx++;
			}
		}
		//delete_post_meta( $post_id, 'wp_travel_trip_itinerary_data' );
		update_post_meta( $post_id, 'wp_travel_trip_itinerary_data', $itineraries );

		$pricings = array();
		$xx = 0;
		for ($x = 0; $x < 100; $x++) {
			if ($_POST['pricing_item_title-' . $x]!="" && $_POST['pricing_item_title-' . $x] != DEFAULT_PRICING) {
				$pricings[$xx]['title'] = sanitize_text_field( $_POST['pricing_item_title-' . $x] );
				$pricings[$xx]['min_pax'] = sanitize_text_field( $_POST['pricing_min_pax-' . $x] );
				$pricings[$xx]['max_pax'] = sanitize_text_field( $_POST['pricing_max_pax-' . $x] );
				$xx++;
			}
		}

		if (!empty($_POST['wp_travel_trip_include'])) {
			$includes = sanitize_text_field( $_POST['wp_travel_trip_include'] );
			update_post_meta($post_id, 'wp_travel_trip_include', $includes);
		}

		if (!empty($_POST['wp_travel_trip_exclude'])) {
			$excludes = sanitize_text_field( $_POST['wp_travel_trip_exclude'] );
			update_post_meta($post_id, 'wp_travel_trip_exclude', $excludes);
		}

		$faqs = array();
		$xx = 0;
		for ($x = 0; $x < 100; $x++) {
			if ($_POST['faq_item_question-' . $x] != "" && $_POST['faq_item_question-' . $x] != DEFAULT_FAQ_QUESTION) {
				$faqs['question'][$xx] = sanitize_text_field( $_POST['faq_item_question-' . $x] );
				$faqs['answer'][$xx] = sanitize_text_field( $_POST['faq_item_answer-' . $x] );
				$xx++;
			}
		}
		$question = isset( $faqs['question'] ) ? $faqs['question'] : array();
		$answer   = isset( $faqs['answer'] ) ? $faqs['answer'] : array();
		update_post_meta( $post_id, 'wp_travel_faq_question', $question );
		update_post_meta( $post_id, 'wp_travel_faq_answer', $answer );

		return $post_id;
	}

	/**
	 * Tabs metabox callback
	 */
	function trip_options_callback_tabs( $post ) {
		if ( ! $post ) {
			return;
		}
		$trip_tabs = wp_travel_get_admin_trip_tabs( $post->ID );

		echo '$post->ID = ' . $post->ID;
		echo '{';
			foreach ( $trip_tabs as $key=>$values ) {
				echo $key.':{';
				foreach ( $values as $key=>$value ) {
					echo '{'.$key.':'.$value.'},';
				}
				echo '},';
			}
		echo '}';

		?>
		<ul id="tabs-ul" style="width:100%" >
		<?php
		if ( is_array( $trip_tabs ) && count( $trip_tabs ) > 0 ) {
			foreach ( $trip_tabs as $key=>$value ) {
				echo '<li class="tab-li" id="tab-li-' . $key . '"><span class="fas fa-bars">';
				$tab_label = esc_attr( $trip_tabs[$key]['label'] );
				echo $tab_label . '</span><p style="display:none">' . $key . '</p>';

				echo '
				<table class="update-tab" style="width:100%">
					<tbody>
					<tr>
						<th>Default Trip Title</th>
						<td><input type="text" name="tab_item_default-' . $key . '" value="' . esc_attr( $trip_tabs[$key]['label'] ) . '"></td>
					</tr>
					<tr>
						<th>Custom Trip Title</th>
						<td><input type="text" class="item-title" name="tab_item_custom-' . $key . '" value="' . $tab_label . '"></td>
					</tr>
					<tr>
						<th>Display</th>
						<td><input type="checkbox" checked name="tab_item_show_in_menu-' . $key . '" value="' . esc_attr( $trip_tabs[$key]['show_in_menu'] ) . '"></td>
					</tr>
					</tbody>
				</table>
				</li>';

			}
		}?>			
		</ul>

		<script>
			jQuery(document).ready(function($) {
    			$( "#tabs-ul" ).sortable();
				$( "#tabs-ul" ).disableSelection();
				$( ".tab-li" ).hide();

				$( ".tab-li" ).each( function( index, element ) {
					if ( !$( 'p', element ).is(":empty") ) {
						$( ".itinerary-rows" ).show();
						$( element ).show();
						$( element ).delegate("span", "click", function(){
							$( 'table', element ).toggleClass('toggle-access');
						});
					};

					$( element ).delegate(".item-title", "keyup", function(){
						$( 'span', element ).text($(this).val());
					});
				});
			} );
		</script>
	
		<style>
  			#tabs-ul { list-style-type:none; margin:0; padding:0; width:100%; }
  			#tabs-ul li { background:#f2f2f2; border:1px solid #ccc; margin:0 3px 3px 3px; padding:0.4em; padding-left:1.5em; font-size:1.4em; }
			#tabs-ul li span { margin-left:-1.3em; cursor:pointer; }
			#tabs-ul li table { background:#ffffff; border:1px solid #ccc; width:100%; display:none; margin-left:-1.2em; padding-left:1.5em; }
			#tabs-ul li .toggle-access { display:block; }
			/*#first-tab { color:blue; text-decoration:underline; cursor:pointer;}*/
			/*.fa-bars:before { content: "\f0c9"; }*/
  		</style>
		<?php
	}

	/**
	 * Prices & Dates metabox callback
	 */
	function trip_options_callback_prices_dates( $post ) {
		if ( ! $post ) {
			global $post;
		}
		$trip_data = WP_Travel_Helpers_Trips::get_trip( $post->ID );
		$pricings = $trip_data['trip']['pricings'];
/*		
		echo '$post->ID = ' . $post->ID;
		echo '{';
			foreach ( $pricings as $key=>$values ) {
				echo $key.':{';
				foreach ( $values as $key=>$value ) {
					echo '{'.$key.':'.$value.'},';
				}
				echo '},';
			}
		echo '}';
*/
		?>
		<table style="width:100%" class="form-table trip-info">
		<?php
		$remove_pricing = __( "- Remove Price", "wp-travel" );
		$xx = 0;
		if ( is_array( $pricings ) && count( $pricings ) > 0 ) {
			foreach ( $pricings as $pricing ) {
				$xx++;
			}
		} else {?>
			<tr class="no-pricings"><td colspan="2">
				<span><?php esc_html_e( 'No Pricings found.', 'wp-travel' ); ?></span>
				<span id="first-pricing"><?php esc_html_e( 'Add Price', 'wp-travel' ); ?></span>
			</td></tr><?php
		}?>

			<tr style="display:none" class="itinerary-rows">
				<td></td>
				<td style="text-align:right"><button id="add-pricing" type="button"><?php esc_html_e( '+ Add Price', 'wp-travel' ); ?></button></td>
			</tr>
			
			<tr style="display:none" class="itinerary-rows"><td colspan="2">
				<ul id="pricings-ul"><?php
				for ($x = 0; $x < 100; $x++) {
					echo '<li style="display:none" class="pricing-li" id="pricing-li-' . $x . '"><span><i class="fas fa-bars"></i>';
					if ($xx<=0) {
						$pricing_title = DEFAULT_PRICING;
						echo $pricing_title . '</span><p style="display:none"></p>';
					} else {
						$pricing_title = esc_attr( $pricings[$x]['title'] );
						echo $pricing_title . '</span><p style="display:none">' . $x . '</p>';
					}
					$xx--;
					echo '
					<table class="update-pricing" style="width:100%">
				  	  <tbody>
						<tr>
							<th>Pricing Name</th>
							<td><input type="text" class="item-title" name="pricing_item_title-' . $x . '" value="' . $pricing_title . '"></td>
						</tr>
						<tr>
							<th>Min. Pax</th>
							<td><input type="text" name="pricing_min_pax-' . $x . '" value="' . esc_attr( $pricings[$x]['min_pax'] ) . '"></td>
						</tr>
						<tr>
							<th>Max. Pax</th>
							<td><input type="text" name="pricing_max_pax-' . $x . '" value="' . esc_attr( $pricings[$x]['max_pax'] ) . '"></td>
						</tr>
						<tr>
							<th>Price Categories</th>
							<td></td>
						</tr>
						<tr>
							<th>' . esc_html_e( 'No Categories found.', 'wp-travel' ) . '</th>
							<td></td>
						</tr>
						<tr>
							<th>Trip Extras</th>
							<td></td>
						</tr>
						<tr>
							<th>' . esc_html_e( 'Please add extras first', 'wp-travel' ) . '</th>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td class="remove-pricing" style="text-align:right"><button id="remove-pricing-' . $x . '" style="color:red" type="button">' . $remove_pricing . '</button></td>
						</tr>
				  	  </tbody>
					</table>
			  		</li>';
				}?>			
				</ul>
			</td></tr>
		</table>

		<script>
			jQuery(document).ready(function($) {
				$( ".pricing-li" ).hide();

				$( ".pricing-li" ).each( function( index, element ) {
					if ( !$( 'p', element ).is(":empty") ) {
						$( ".itinerary-rows" ).show();
						$( element ).show();
						$( element ).delegate("span", "click", function(){
							$( 'table', element ).toggleClass('toggle-access');
						});
					};

					$( element ).delegate(".item-title", "keyup", function(){
						$( 'span', element ).text($(this).val());
					});
				});

				$( ".remove-pricing" ).each( function( index, element ) {
					$( element ).delegate("button", "click", function(){
						$( this ).closest('.pricing-li').remove();
					});					
				});

				$("#first-pricing").click( function(){
					$(".no-pricings").hide();
					$(".itinerary-rows").show();
					$(".pricing-li").hide();
					$("#pricing-li-0").show();
					$('span','#pricing-li-0').on('click', function() {
						$('table','#pricing-li-0').toggleClass('toggle-access');
					});
				} );
			
				$("#add-pricing").click( function(){
					$( ".pricing-li" ).each( function( index, element ) {
						if ( $( this ).is(":hidden") ) {
							$( this ).show();
							$( element ).delegate("span", "click", function(){
								$( 'table', element ).toggleClass('toggle-access');
							});
							return false;
						};
					});
				} );

				$( '.pricing_item_date' ).datepicker();
			} );
		</script>
	
		<style>
  			#pricings-ul { list-style-type:none; margin:0; padding:0; width:100%; }
  			#pricings-ul li { background:#f2f2f2; border:1px solid #ccc; margin:0 3px 3px 3px; padding:0.4em; padding-left:1.5em; font-size:1.4em; }
			#pricings-ul li span { margin-left:-1.3em; cursor:pointer; }
			#pricings-ul li table { background:#ffffff; border:1px solid #ccc; width:100%; display:none; margin-left:-1.2em; padding-left:1.5em; }
			#pricings-ul li .toggle-access { display:block; }
			#first-pricing { color:blue; text-decoration:underline; cursor:pointer;}
			.fa-bars:before { content: "\f0c9"; }
  		</style>
		<?php
	}


function trip_options_metabox_callback( $post ) {

	echo "<h3>Add Your Content Here</h3>";
	$content = get_post_meta($post->ID, 'custom_editor', true);
	
	//This function adds the WYSIWYG Editor 
	wp_editor ( $content , 'custom_editor', array ( "media_buttons" => true ) );
	
	/**
	 * Retrieves a post meta field for the given post ID.
	 * get_post_meta( int $post_id, string $key = '', bool $single = false )
	 */
	$seo_title = get_post_meta( $post->ID, 'seo_title', true );
	$seo_robots = get_post_meta( $post->ID, 'seo_robots', true );

	// nonce, actually I think it is not necessary here
	wp_nonce_field( 'somerandomstr', '_tripnonce' );

	echo '<table class="form-table">
		<tbody>
			<tr>
				<th><label for="seo_title">SEO title</label></th>
				<td><input type="text" id="seo_title" name="seo_title" value="' . esc_attr( $seo_title ) . '" class="regular-text"></td>
			</tr>
			<tr>
				<th><label for="seo_tobots">SEO robots</label></th>
				<td>
					<select id="seo_robots" name="seo_robots">
						<option value="">Select...</option>
						<option value="index,follow"' . selected( 'index,follow', $seo_robots, false ) . '>Show for search engines</option>
						<option value="noindex,nofollow"' . selected( 'noindex,nofollow', $seo_robots, false ) . '>Hide for search engines</option>
					</select>
				</td>
			</tr>
		</tbody>
	</table>'; 
}

/**
 * Add a new meta box for product
 * Step 1. add_meta_box()
 * Step 2. Callback function with meta box HTML
 * Step 3. Save meta box data
 */
function trip_options_add_metabox() {
	add_meta_box(
		'trip-options', // metabox ID
		esc_html__( 'Trip Options', 'dgc-domain' ), // title
		array( __CLASS__, 'trip_options_metabox_callback' ), // callback function
		//array( __CLASS__, 'horizontal_tabs_metabox' ), // callback function
		//array( __CLASS__, 'vertical_example_metabox' ), // callback function
		'product', // post type or post types in array
		'normal', // position (normal, side, advanced)
		'default' // priority (default, low, high, core)
	);
	//wp_enqueue_script( 'mytabs', get_bloginfo( 'stylesheet_directory' ). '/mytabs.js', array( 'jquery-ui-tabs' ) );
	wp_enqueue_script( 'mytabs', 'mytabs.js', array( 'jquery-ui-tabs' ) );
	//wp_enqueue_script( 'mytabs', '', array( 'jquery-ui-tabs' ) );
}

}
new Trip_Options_Edit_Metabox;


function horizontal_tabs_metabox( $post ) {
	?>
	<div id="mytabs">
		<ul class="category-tabs">
			<li><a href="#tab_itinerary"><?php esc_html_e( 'Itinerary', 'wp-travel' ); ?></a></li>
<!--			<li><a href="#tab_prices_dates"><?php //esc_html_e( 'Prices & Dates', 'wp-travel' ); ?></a></li>  -->
			<li><a href="#tab_includes_excludes"><?php esc_html_e( 'Includes/Excludes', 'wp-travel' ); ?></a></li>
			<li><a href="#tab_facts"><?php esc_html_e( 'Facts', 'wp-travel' ); ?></a></li>
			<li><a href="#tab_gallery"><?php esc_html_e( 'Gallery', 'wp-travel' ); ?></a></li>
			<li><a href="#tab_locations"><?php esc_html_e( 'Locations', 'wp-travel' ); ?></a></li>
			<li><a href="#tab_faqs"><?php esc_html_e( 'FAQs', 'wp-travel' ); ?></a></li>
			<li><a href="#tab_misc"><?php esc_html_e( 'Misc. Options', 'wp-travel' ); ?></a></li>
			<li><a href="#tab_tabs"><?php esc_html_e( 'Tabs', 'wp-travel' ); ?></a></li>
		</ul>
		<br class="clear" />
		<div id="tab_itinerary">
			<?php //self::trip_options_callback_itinerary( $post )?>
		</div>

		<div class="hidden" id="tab_prices_dates">
			<?php //self::trip_options_callback_prices_dates( $post )?>
		</div>

		<div class="hidden" id="tab_includes_excludes">
			<?php //self::trip_options_callback_includes_excludes( $post )?>
		</div>
		
		<div class="hidden" id="tab_facts">
			<?php //self::trip_options_metabox_callback( $post )?>
		</div>
		
		<div class="hidden" id="tab_gallery">
			<?php //self::trip_options_metabox_callback( $post )?>
		</div>
		
		<div class="hidden" id="tab_locations">
			<?php //self::trip_options_metabox_callback( $post )?>
		</div>
		
		<div class="hidden" id="tab_faqs">
			<?php //self::trip_options_callback_faqs( $post )?>
		</div>
		
		<div class="hidden" id="tab_misc">
			<?php //self::trip_options_metabox_callback( $post )?>
		</div>
		
		<div class="hidden" id="tab_tabs">
			<?php //self::trip_options_callback_tabs( $post )?>
		</div>
	</div>
	<table style="width:100%">
		<tr>
			<td><?php esc_html_e( '* Please save the changes', 'wp-travel' ); ?></td>
			<td style="text-align:right"><button id="save-changes" style="background:blue;color:white" type="button"><?php esc_html_e( 'Save Changes', 'wp-travel' ); ?></button></td>
		</tr>
	</table>

	<script>
		jQuery(document).ready(function($) {
			$("#mytabs .hidden").removeClass('hidden');
			$("#mytabs").tabs();
			
			$("#save-changes").click( function(){
				$.ajax({
					url:"test.php",    //the page containing php script
					type: "post",    //request type,
					dataType: 'json',
					data: {registration: "success", name: "xyz", email: "abc@gmail.com"},
					success:function(result){
						console.log(result.abc);
					}
				});
			});
		});
	</script>
	<?php
}

function vertical_example_metabox( $post ) {
	?>
	<div id="tabs">
		<ul class="category-tabs">
			<li><a href="#tab1"><?php esc_html_e( 'Itinerary', 'wp-travel' ); ?></a></li>
			<li><a href="#tab2"><?php esc_html_e( 'Prices & Dates', 'wp-travel' ); ?></a></li>
			<li><a href="#tab3"><?php esc_html_e( 'Includes/Excludes', 'wp-travel' ); ?></a></li>
			<li><a href="#tab4"><?php esc_html_e( 'Facts', 'wp-travel' ); ?></a></li>
			<li><a href="#tab5"><?php esc_html_e( 'Gallery', 'wp-travel' ); ?></a></li>
			<li><a href="#tab6"><?php esc_html_e( 'Locations', 'wp-travel' ); ?></a></li>
			<li><a href="#tab7"><?php esc_html_e( 'FAQs', 'wp-travel' ); ?></a></li>
			<li><a href="#tab8"><?php esc_html_e( 'Misc. Options', 'wp-travel' ); ?></a></li>
			<li><a href="#tab9"><?php esc_html_e( 'Tabs', 'wp-travel' ); ?></a></li>
		</ul>
		<br class="clear" />
		<div id="tab1">
			<?php wp_travel_trip_info( $post )?>
		</div>

		<div class="hidden" id="tab2">
			<?php wp_travel_trip_info( $post )?>				
		</div>

		<div class="hidden" id="tab3">
			<?php wp_travel_trip_info( $post )?>
		</div>
		
		<div class="hidden" id="tab4">
			<?php wp_travel_trip_info( $post )?>
		</div>
		
		<div class="hidden" id="tab5">
			<?php wp_travel_trip_info( $post )?>
		</div>
		
		<div class="hidden" id="tab6">
			<?php wp_travel_trip_info( $post )?>
		</div>
		
		<div class="hidden" id="tab7">
			<?php wp_travel_trip_info( $post )?>
		</div>
		
		<div class="hidden" id="tab8">
			<?php wp_travel_trip_info( $post )?>
		</div>
		
		<div class="hidden" id="tab9">
			<?php wp_travel_trip_info( $post )?>
		</div>
	</div>

	<script>
		jQuery(document).ready(function($) {
			$( "#tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
			$( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
		});
	</script>
	<style>
		.ui-tabs-vertical { width: 55em; }
		.ui-tabs-vertical .ui-tabs-nav { padding: .2em .1em .2em .2em; float: left; width: 12em; }
		.ui-tabs-vertical .ui-tabs-nav li { clear: left; width: 100%; border-bottom-width: 1px !important; border-right-width: 0 !important; margin: 0 -1px .2em 0; }
		.ui-tabs-vertical .ui-tabs-nav li a { display:block; }
		.ui-tabs-vertical .ui-tabs-nav li.ui-tabs-active { padding-bottom: 0; padding-right: .1em; border-right-width: 1px; }
		.ui-tabs-vertical .ui-tabs-panel { padding: 1em; float: right; width: 40em;}
	</style>
	<?php
}

