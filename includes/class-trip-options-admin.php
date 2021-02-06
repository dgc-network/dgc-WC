<?php
class Trip_Options_Admin {

	/**
	 * Constructor.
	 */
	function __construct() {
		add_action( 'admin_head', array( __CLASS__, 'dgc_custom_script' ) );
		add_action( 'admin_head', array( __CLASS__, 'dgc_custom_style' ) );

		add_filter( 'product_type_options', array( __CLASS__, 'add_remove_product_options' ) );
		add_filter( 'woocommerce_product_data_tabs', array( __CLASS__, 'custom_product_data_tabs' ), 10, 1 );
		add_filter( 'woocommerce_allow_marketplace_suggestions', '__return_false' );

		add_action( 'woocommerce_product_data_panels', array( __CLASS__, 'trip_options_callback_itinerary' ) );
		add_action( 'woocommerce_product_data_panels', array( __CLASS__, 'trip_options_callback_includes_excludes' ) );
		add_action( 'woocommerce_product_data_panels', array( __CLASS__, 'trip_options_callback_faqs' ) );
		add_action( 'woocommerce_product_data_panels', array( __CLASS__, 'trip_options_callback_tabs' ) );

		add_action( 'woocommerce_process_product_meta', array( __CLASS__, 'save_woocommerce_product_custom_fields' ) );

		add_action( 'wp_ajax_get_categories', array( __CLASS__, 'get_categories' ) );
		add_action( 'wp_ajax_nopriv_get_categories', array( __CLASS__, 'get_categories' ) );
		add_action( 'wp_ajax_get_product_by_category', array( __CLASS__, 'get_product_by_category' ) );
		add_action( 'wp_ajax_nopriv_get_product_by_category', array( __CLASS__, 'get_product_by_category' ) );

		add_action( 'admin_menu', array( __CLASS__, 'trip_orders_add_metabox' ) );
		add_action( 'save_post', array( __CLASS__, 'trip_orders_save_metabox' ), 10, 2 );
	}

	/**
	 * Remove 'Virtual','Downloadable' product options
	 * Add 'Itinerary' product options
	 * Create Categories
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

		$options['itinerary'] = array(
			'id'            => '_itinerary',
			'wrapper_class' => 'show_if_simple show_if_variable',
			'label'         => __( 'Itinerary', 'text-domain' ),
			'description'   => __( 'Itinerary allow users to put in personalised messages.', 'text-domain' ),
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

		// add "Itinerary" tab
    	$tabs['itinerary'] = array(
        	'label'   =>  __( 'Itinerary', 'text-domain' ),
        	'target'  =>  'itinerary_panel',
        	'priority' => 60,
        	'class'   => array( 'show_if_itinerary')
    	);

		// add "Includes/Excludes" tab
		$tabs['include_exclude'] = array(
        	'label'   =>  __( 'Includes/Excludes', 'text-domain' ),
        	'target'  =>  'include_exclude_panel',
        	'priority' => 60,
        	'class'   => array( 'show_if_itinerary')
    	);

		// add "FAQs" tab
		$tabs['faq'] = array(
        	'label'   =>  __( 'FAQs', 'text-domain' ),
        	'target'  =>  'faq_panel',
        	'priority' => 60,
        	'class'   => array( 'show_if_itinerary')
    	);

		// add "TABs" tab
		$tabs['tab'] = array(
        	'label'   =>  __( 'TABs', 'text-domain' ),
        	'target'  =>  'tab_panel',
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
				var faq_x = 0;
				$( ".faq-li" ).each( function( index, element ) {

					$( element ).delegate("span", "click", function(){
						$( 'table', element ).toggleClass('toggle-access');
					});

					$( element ).delegate(".item_title", "keyup", function(){
						$( '.faq-title', element ).text($(this).val());
					});

					$( '.remove-faq', element ).on( 'click', function() {
						if (confirm('Are you sure?') == true) {
							$( this ).closest('.faq-li').remove();
						};
					});
					faq_x += 1;
				});

				$( '#first-faq' ).on( 'click', function() {
					$(".no-faqs").hide();
					$(".faq-header").show();
					$(".faq-rows").show();
				});

				$( '.add-faq' ).on( 'click', function() {

					var default_faq = 'FAQ Questions';
					var new_faq = '<li class="faq-li" id="faq-li-' + faq_x + '">';
					new_faq += '<span class="fas fa-bars"> </span>';
					new_faq += '<span class="faq-title">'+ default_faq +'</span>';
					new_faq += '<table>';
					new_faq += '<tr>';
					new_faq += '<th>Your question</th>';
					new_faq += '<td><input type="text" width="100%" class="item_title" value="'+ default_faq +'" name="faq_item_question-' + faq_x + '"></td>';
					new_faq += '</tr>';
					new_faq += '<tr>';
					new_faq += '<th>Your answer</th>';
					new_faq += '<td><textarea rows="5" name="faq_item_answer-' + faq_x + '"></textarea></td>';
					new_faq += '</tr>';
					new_faq += '<tr>';
					new_faq += '<td colspan="2"><button class="remove-faq" type="button">- Remove Faq -</button></td>';
					new_faq += '</tr>';
					new_faq += '</table>';
					new_faq += '</li>';

					$( '#end-of-faq' ).before(new_faq);
					var element = '#faq-li-' + faq_x ;
					$( 'span', element ).on( 'click', function() {
						$( 'table', element ).toggleClass( 'toggle-access' );
					});
					$( element ).delegate( '.item_title', 'keyup', function() {
						$( '.faq-title', element ).text($(this).val());
					});
					$( '.remove-faq', element ).on( 'click', function() {
						if (confirm('Are you sure?') == true) {
							$( this ).closest('.faq-li').remove();
						};
					});
					faq_x += 1;
				});


				/*
				 * Itinerary Tab
				 */
					// alerts 'Some string to translate'
					//alert( object_name.remove_itinerary );

				var categories = '';
				$.ajax({
            		type: 'POST',
            		url: '/wp-admin/admin-ajax.php',
            		dataType: "json",
            		data: {
						'action': 'get_categories',
        			},
            		success: function (data) {
						categories = data;
            		},
            		error: function(error){
						alert(error);
					}
        		});

				var x = 0;
				$( '.itinerary-li' ).each( function( index, element ) {

					$( element ).delegate( 'span', 'click', function() {
						$( 'table', element ).toggleClass( 'toggle-access' );
					});

					$( element ).delegate( '.item_label', 'keyup', function() {
						$( '.span-label', element ).text($(this).val());
					});

					$( element ).delegate( '.item_title', 'keyup', function() {
						$( '.span-title', element ).text($(this).val());
					});

					$( '.remove-itinerary', element ).on( 'click', function() {
						if (confirm('Are you sure?') == true) {
							$( this ).closest('.itinerary-li').remove();
						};
					});

					$( '.item_date', element ).datepicker();

					var y = 0;
					$( '.assignment-rows', element ).each( function( sub_index, sub_element ) {
						$( '.opt-categorias', sub_element ).on( 'change', function() {
							var opt_categorias = this.value;
        					$.ajax({
								type: 'POST',
								url: '/wp-admin/admin-ajax.php',
            					dataType: "json",
            					data: {
									'action': 'get_product_by_category',
                					'term_chosen': opt_categorias,
            					},
            					success: function (data) {
									//alert(data);
									$( '.opt_tipo', sub_element ).empty();
            						$( '.opt_tipo', sub_element ).append("<option value=''>- Select Resource -</option>");

									var product_id;
									var product_title;
                					$.each(data, function (m, items) {
                					$.each(items, function (n, item) {
										//alert(item);
										if (n % 2 == 0) {
											product_id = item;
										}
										if (Math.abs(n % 2) == 1) {
											product_title = item;
											$( '.opt_tipo', sub_element ).append('<option value="' + product_id + '">' + product_title + '</option>');
										}
                    					//$( '.opt_tipo', sub_element ).append('<option value="' + item + '">' + item + '</option>');
                					});
                					});
            					},
            					error: function(error){
									alert(error);
            					}
							});
							if (this.value=='_delete_assignment') {
								$( this ).closest( sub_element ).remove();
							}							
						});
						y = y + 1;
					});

					$( element ).delegate( '#first-assignment', 'click', function() {
						$( '.no-assignments', element ).hide();
						$( '.assignment-header', element ).show();
					});

					$( element ).delegate( '.add-assignment', 'click', function() {

						var new_assignment = '<tr class="assignment-rows" id="assignment-row-'+ index +'-'+ y +'"><td>';
						new_assignment += '<select style="width:100%" class="opt_categorias" name="itinerary_item_assignment-'+ index +'-category-'+ y +'">';
						new_assignment += '<option>- Select Category -</option>';
						$.each(categories, function (i, item) {
							new_assignment += '<option value="' + item + '">' + item + '</option>';
                		});
						new_assignment += '<option style="color:red" value="_delete_assignment">- Remove Assignment -</option>';
						new_assignment += '</select></td><td>';
						new_assignment += '<select style="width:100%" class="opt_tipo" name="itinerary_item_assignment-'+ index +'-resource-'+ y +'"></select>';
						new_assignment += '</td></tr>';

						$( '#end-of-assignment', element ).before(new_assignment);
						var sub_element = '#assignment-row-' + index +'-'+ y;
						$( '.opt_categorias', sub_element ).on( 'change', function() {
							var opt_categorias = this.value;
        					$.ajax({
								type: 'POST',
								url: '/wp-admin/admin-ajax.php',
            					dataType: "json",
            					data: {
									'action': 'get_product_by_category',
                					'term_chosen': opt_categorias,
            					},
            					success: function (data) {
									//alert(data);
									$( '.opt_tipo', sub_element ).empty();
            						$( '.opt_tipo', sub_element ).append('<option value="">- Select Resource -</option>');

									var product_id;
									var product_title;
                					$.each(data, function (m, items) {
                					$.each(items, function (n, item) {
										//alert(item);
										if (n % 2 == 0) {
											product_id = item;
										}
										if (Math.abs(n % 2) == 1) {
											product_title = item;
											$( '.opt_tipo', sub_element ).append('<option value="' + product_id + '">' + product_title + '</option>');
										}
                    					//$( '.opt_tipo', sub_element ).append('<option value="' + item + '">' + item + '</option>');
                					});
                					});
            					},
            					error: function(error){
									alert(error);
            					}
        					});					
							if (this.value=='_delete_assignment') {
								$( this ).closest( sub_element ).remove();
							}							
						});
						y = y + 1;
					});
					x = x + 1;
				});

				$( '#first-itinerary' ).on( 'click', function() {
					$( '.no-itineraries' ).hide();
					$( '.itinerary-header' ).show();
					$( '.itinerary-rows' ).show();					
				});

				$( '.add-itinerary' ).on( 'click', function() {
					//var itinerary_label = DEFAULT_ITINERARY_LABEL;
					//var itinerary_title = DEFAULT_ITINERARY_TITLE;
					var itinerary_label = 'Day X';
					var itinerary_title = 'My Plan';
					var new_itinerary = '<li class="itinerary-li" id="itinerary-li-' + x + '">';
					new_itinerary += '<span class="fas fa-bars"> </span>';
					new_itinerary += '<span class="span-label">' + itinerary_label + '</span>, ';
					new_itinerary += '<span class="span-title">' + itinerary_title + '</span>';
					new_itinerary += '<table>';
					new_itinerary += '<tr>';
					new_itinerary +=  '<th>Itinerary label</th>';
					new_itinerary +=  '<td><input type="text" class="item_label" name="itinerary_item_label-' + x + '" value="' + itinerary_label + '"></td>';
					new_itinerary += '</tr>';
					new_itinerary += '<tr>';
					new_itinerary +=  '<th>Itinerary title</th>';
					new_itinerary +=  '<td><input type="text" class="item_title" name="itinerary_item_title-' + x + '" value="' + itinerary_title + '"></td>';
					new_itinerary += '</tr>';
					new_itinerary += '<tr>';
					new_itinerary +=  '<th>Itinerary date</th>';
					new_itinerary +=  '<td><input type="text" class="item_date" name="itinerary_item_date-' + x + '"></td>';
					new_itinerary += '</tr>';
					new_itinerary += '<tr>';
					new_itinerary +=  '<td colspan="2"><b>Description</b><br>';
					new_itinerary +=  '<textarea rows="5" name="itinerary_item_desc-' + x + '"></textarea></td>';
					new_itinerary += '</tr>';
					new_itinerary += '<tr>';
					new_itinerary +=  '<td colspan="2">';
					new_itinerary +=  '<table style="width:100%;margin-left:0">';
					new_itinerary +=  '<tr style="display:none" class="assignment-header">';
					new_itinerary +=   '<th class="assignment-row-head">Resources Assignment</th>';
					new_itinerary +=   '<td style="text-align:right"><button class="add-assignment" type="button">+ Add Assignment</button></td>';
					new_itinerary +=  '</tr>';
					new_itinerary +=  '<tr class="no-assignments">';
					new_itinerary +=   '<td colspan="2">No Assignments found. ';
					new_itinerary +=   '<button class="add-assignment" id="first-assignment" type="button">+ Add Assignment</button></td>';
					new_itinerary +=  '</tr>';
					new_itinerary +=  '<tr id="end-of-assignment"></tr>';
					new_itinerary +=  '</table>';
					new_itinerary += '</tr>';
					new_itinerary += '<tr>';
					new_itinerary += '<td colspan ="2"><button class="remove-itinerary" type="button">- Remove Itinerary -</button></td>';
					new_itinerary += '</tr>';
					new_itinerary += '</table>';

					$( '#end-of-itinerary' ).before(new_itinerary);
					var element = '#itinerary-li-' + x ;
					$( 'span', element ).on( 'click', function() {
						$( 'table', element ).toggleClass( 'toggle-access' );
					});
					$( element ).delegate( '.item_label', 'keyup', function() {
						$( '.span-label', element ).text($(this).val());
					});
					$( element ).delegate( '.item_title', 'keyup', function() {
						$( '.span-title', element ).text($(this).val());
					});
					$( '.remove-itinerary', element ).on( 'click', function() {
						if (confirm('Are you sure?') == true) {
							$( this ).closest('.itinerary-li').remove();
						};
					});
					$( '.item_date', element ).datepicker();

					$( element ).delegate( '#first-assignment', 'click', function() {
						$( '.no-assignments', element ).hide();
						$( '.assignment-header', element ).show();
					});
					var y = 0;
					$( element ).delegate( '.add-assignment', 'click', function() {
						var new_assignment = '<tr class="assignment-rows" id="assignment-row-'+ x +'-'+ y +'"><td>';
						new_assignment += '<select style="width:100%" class="opt_categorias" name="itinerary_item_assignment-'+ x +'-category-'+ y +'">';
						new_assignment += '<option>- Select Category -</option>';
						$.each(categories, function (i, item) {
							new_assignment += '<option value="' + item + '">' + item + '</option>';
                		});
						new_assignment += '<option style="color:red" value="_delete_assignment">- Remove Assignment -</option>';
						new_assignment += '</select></td><td>';
						new_assignment += '<select style="width:100%" class="opt_tipo" name="itinerary_item_assignment-'+ x +'-resource-'+ y +'"></select>';
						new_assignment += '</td></tr>';

						$( '#end-of-assignment', element ).before(new_assignment);
						var sub_element = '#assignment-row-' + x +'-'+ y;
						$( '.opt_categorias', sub_element ).on( 'change', function() {
							var opt_categorias = this.value;
        					$.ajax({
								type: 'POST',
								url: '/wp-admin/admin-ajax.php',
            					dataType: "json",
            					data: {
									'action': 'get_product_by_category',
                					'term_chosen': opt_categorias,
            					},
            					success: function (data) {
									//alert(data);
									$( '.opt_tipo', sub_element ).empty();
            						$( '.opt_tipo', sub_element ).append('<option value="">- Select Resource -</option>');

									var product_id;
									var product_title;
									var product_id;
									var product_title;
                					$.each(data, function (m, items) {
                					$.each(items, function (n, item) {
										//alert(item);
										if (n % 2 == 0) {
											product_id = item;
										}
										if (Math.abs(n % 2) == 1) {
											product_title = item;
											$( '.opt_tipo', sub_element ).append('<option value="' + product_id + '">' + product_title + '</option>');
										}
                    					//$( '.opt_tipo', sub_element ).append('<option value="' + item + '">' + item + '</option>');
                					});
                					});
            					},
            					error: function(error){
									alert(error);
            					}
        					});									
							if (this.value=='_delete_assignment') {
								$( this ).closest( sub_element ).remove();
							}							
						});
						y = y + 1;
					});
					x = x + 1;
				});
			});
		</script>
		<?php
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

			#first-itinerary { background:#ffffff; color:blue; border: none; cursor:pointer; text-decoration:underline; }
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
			#itineraries-ul li button#first-assignment { background:#ffffff; color:blue; border: none; cursor:pointer; text-decoration:underline; }
			#itineraries-ul li button.add-assignment { font-size:0.7em; color:blue; }

			/*#first-faq { color:blue; text-decoration:underline; cursor:pointer; }*/
			#first-faq { background:#ffffff; color:blue; border: none; cursor:pointer; text-decoration:underline; }
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
	 * Product Categories List by AJAX
	 */
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
		
	/**
	 * Product List by Category by AJAX
	 */
	function get_product_by_category() {

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
			$title = array();
			array_push($title, $product->get_id());
			array_push($title, $product->get_title());
			array_push($titles, $title);
		}	
		$json = json_encode( $titles );
		echo $json;
		
		die();		
	}
		
	/**
	 * Product Categories List
	 */
	function product_category_name_options( $product_category_slug=false ) {
		
		$args = array(
			'taxonomy'   => "product_cat",
			'number'     => $number,
			'orderby'    => $orderby,
			'order'      => $order,
			'hide_empty' => $hide_empty,
			'include'    => $ids
		);
		$product_categories = get_terms($args);
		foreach( $product_categories as $cat ) {
			if ($cat->name != 'Uncategorized') {
				if ($cat->name == $product_category_slug) {
					echo '<option value="' . $cat->name . '" selected>' . $cat->name . '</option>';
				} else {
					echo '<option value="' . $cat->name . '">' . $cat->name . '</option>';
				}
			}
		}
		$remove_assignment = __( "- Remove Assignment -", "wp-travel" );
		echo '<option style="color:red" value="_delete_assignment">' . $remove_assignment . '</option>';
	}

	/**
	 * Product List by Category
	 */
	function product_name_options_by_category( $product_category_slug=false, $selected_product_id=false ) {

		$query = new WC_Product_Query( array(
			'category' => array( $product_category_slug ),
			'limit' => 10,
			'orderby' => 'date',
			'order' => 'DESC'
		) );
	   
		$products = $query->get_products();
		
		if (isset($selected_product_id)) {
			echo '<option value="" selected disabled hidden>' .  __( "- Select Resource -", "wp-travel" ) . '</option>';
		}
		foreach( $products as $product ) {
			$product_id = $product->get_id();
			$product_title = $product->get_title();
			if ($product_id == $selected_product_id) {
				echo '<option value="' . $product_id . '" selected>' . $product_title . '</option>';
			} else {
				echo '<option value="' . $product_id . '">' . $product_title . '</option>';
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
		$remove_itinerary = __( "- Remove Itinerary -", "wp-travel" );
		$remove_assignment = __( "- Remove Assignment -", "wp-travel" );

// Register the script
//wp_register_script( 'some_handle', 'path/to/myscript.js' );
 
// Localize the script with new data
$translation_array = array(
	'some_string' => __( 'Some string to translate', 'plugin-domain' ),
	'remove_itinerary' => __( "- Remove Itinerary -", "wp-travel" ),
    'a_value' => '10'
);
wp_localize_script( 'some_handle', 'object_name', $translation_array );
 
// Enqueued script with localized data.
wp_enqueue_script( 'some_handle' );

		$product_categories = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false ) );
		?>
		<div id='itinerary_panel' class='panel woocommerce_options_panel'>
		<table style="width:100%; padding:1em">
			<tr>
				<td><h3><?php esc_html_e( 'Trip Code : ', 'text-domain' ); ?></h3></td>
				<td><input type="text" disabled="disabled" value="<?php echo esc_attr( $trip_code ); ?>" /></td>
			</tr>

		<?php
		$x = 0;
		if ( is_array( $itineraries ) && count( $itineraries ) > 0 ) {?>
			<tr class="itinerary-header">
				<td><h3><?php esc_html_e( 'Itinerary', 'text-domain' ); ?></h3></td>
				<td style="text-align:right"><button class="add-itinerary" type="button"><?php esc_html_e( '+ Add Itinerary', 'text-domain' ); ?></button></td>
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
							<th>' . __( 'Itinerary label', 'text-domain' ) .'</th>
							<td><input type="text" class="item_label" name="itinerary_item_label-' . $x . '" value="' . $itinerary_label . '"></td>
						</tr>
						<tr>
							<th>' . __( 'Itinerary title', 'text-domain' ) .'</th>
							<td><input type="text" class="item_title" name="itinerary_item_title-' . $x . '" value="' . $itinerary_title . '"></td>
						</tr>
						<tr>
							<th>' . __( 'Itinerary date', 'text-domain' ) .'</th>
							<td><input type="text" class="item_date" name="itinerary_item_date-' . $x . '" value="' . esc_attr( $itineraries[$x]['date'] ) . '"></td>
						</tr>
						<tr>
							<td colspan="2"><b>' . __( 'Description', 'text-domain' ) .'</b><br>
							<textarea rows="5" name="itinerary_item_desc-' . $x . '">' . esc_attr( $itineraries[$x]['desc'] ) . '</textarea></td>
						</tr>
						<tr>
							<td colspan="2">';
							$y=0;
							echo '<table style="width:100%;margin-left:0">';
							if (isset($itineraries[$x]['assignment'])) {
								echo '<tr class="assignment-header">';
								echo '<th class="assignment-row-head">' . __( 'Resources Assignment', 'text-domain' ) . '</th>';
								echo '<td style="text-align:right"><button class="add-assignment" type="button">' . __( '+ Add Assignment', 'text-domain' ) .'</button></td>';
								echo '</tr>';
								foreach ( $itineraries[$x]['assignment'] as $assignment ) {
									echo '<tr class="assignment-rows" id="assignment-row-' . $x . '-' . $y . '">
									<td>';
									echo '<select style="width:100%" class="opt-categorias" name="itinerary_item_assignment-' . $x . '-category-' . $y . '">';
										self::product_category_name_options( $itineraries[$x]['assignment'][$y]['category'] );
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
									echo '<th class="assignment-row-head">' . __( 'Resources Assignment', 'text-domain' ) . '</th>';
									echo '<td style="text-align:right"><button class="add-assignment" type="button">' . __( '+ Add Assignment', 'text-domain' ) .'</button></td>';
								echo '</tr>';
								echo '<tr class="no-assignments"><td colspan="2">';
									esc_html_e( 'No Assignments found. ', 'text-domain' );
									echo '<button class="add-assignment" id="first-assignment" type="button">' . __( 'Add Assignment', 'text-domain' ) . '</button>';
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
				<td><h3><?php esc_html_e( 'Itinerary', 'text-domain' ); ?></h3></td>
				<td style="text-align:right"><button class="add-itinerary" type="button"><?php esc_html_e( '+ Add Itinerary', 'text-domain' ); ?></button></td>
			</tr>
			<tr class="no-itineraries"><td colspan="2">
				<h3><?php esc_html_e( 'Itinerary', 'text-domain' ); ?></h3><br>
				<span><?php esc_html_e( 'No Itineraries found.', 'text-domain' ); ?></span>
				<button class="add-itinerary" id="first-itinerary" type="button"><?php esc_html_e( 'Add Itinerary', 'text-domain' ); ?></span>
			</td></tr>
			<tr style="display:none" class="itinerary-rows"><td colspan="2">
				<ul id="itineraries-ul">
					<li id="end-of-itinerary" style="display:none"></li>
				</ul>
			</td></tr>
			<tr style="display:none" class="itinerary-header">
				<td></td>
				<td style="text-align:right"><button class="add-itinerary" type="button"><?php esc_html_e( "+ Add Itinerary", "wp-travel" ); ?></button></td>
			</tr>
			<?php
		}?>			
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
			<h3><?php esc_html_e( 'Trip Includes', 'text-domain' );?></h3>
			<?php wp_editor ( $trip_include , 'wp_travel_trip_include', $settings );?>
			<br><br>
			<h3><?php esc_html_e( 'Trip Excludes', 'text-domain' );?></h3>
			<?php wp_editor ( $trip_exclude , 'wp_travel_trip_exclude', array ( "media_buttons" => true ) );?>
			<br><br>
		</div>
		<?php		
	}


	/**
	 * FAQs metabox callback
	 */
	function trip_options_callback_faqs( $post ) {
		if ( ! $post ) {
			global $post;
		}
		$faqs = wp_travel_get_faqs( $post->ID );
		$remove_faq = __( "- Remove FAQ -", "wp-travel" );
		?>
		<div id='faq_panel' class='panel woocommerce_options_panel'>
		<table style="width:100%; padding:1em">

		<?php
		$x = 0;
		if ( is_array( $faqs ) && count( $faqs ) > 0 ) {?>
			<tr class="faq-header">
				<td><h3><?php esc_html_e( 'FAQ', 'text-domain' ); ?></h3></td>
				<td style="text-align:right"><button class="add-faq" type="button"><?php esc_html_e( '+ Add FAQ', 'text-domain' ); ?></button></td>
			</tr>
			<tr class="faq-rows"><td colspan="2">
			<ul id="faqs-ul"><?php		
			foreach ( $faqs as $faq ) {
				$faq_question = esc_attr( $faqs[$x]['question'] );
				echo '<li class="faq-li" id="faq-li-' . $x . '">';
				echo '<span class="fas fa-bars"> </span>';
				echo '<span class="faq-title">' . $faq_question . '</span>';
				echo '
				<table>
					<tr>
						<th>' . __( 'Your question', 'text-domain' ) . '</th>
						<td><input type="text" width="100%" class="item_title" name="faq_item_question-' . $x . '" value="' . $faq_question . '" class="regular-text"></td>
					</tr>
					<tr>
						<th>' . __( 'Your answer', 'text-domain' ) . '</th>
						<td><textarea rows="5" name="faq_item_answer-' . $x . '" class="regular-text">' . esc_attr( $faqs[$x]['answer'] ) . '</textarea></td>
					</tr>
					<tr>
						<td colspan="2"><button class="remove-faq" type="button">' . $remove_faq . '</button></td>
					</tr>
				</table>
				</li>';
				$x++;
			};?>
				<li id="end-of-faq" style="display:none"></li>
			</ul>
			</td></tr>
			<tr class="faq-header">
				<td></td>
				<td style="text-align:right"><button class="add-faq" type="button"><?php esc_html_e( "+ Add FAQ", "wp-travel" ); ?></button></td>
			</tr><?php
		} else {?>
			<tr style="display:none" class="faq-header">
				<td><h3><?php esc_html_e( 'FAQ', 'text-domain' ); ?></h3></td>
				<td style="text-align:right"><button class="add-faq" type="button"><?php esc_html_e( '+ Add FAQ', 'text-domain' ); ?></button></td>
			</tr>
			<tr class="no-faqs"><td colspan="2">
				<span><h3><?php esc_html_e( 'FAQ', 'text-domain' ); ?></h3></span><br>
				<span><?php esc_html_e( 'Please add new FAQ here.', 'text-domain' ); ?></span>
				<button class="add-faq" type="button" id="first-faq"><?php esc_html_e( 'Add FAQ', 'text-domain' ); ?></button>
			</td></tr>
			<tr style="display:none" class="faq-rows"><td colspan="2">
				<ul id="faqs-ul">
					<li id="end-of-faq" style="display:none"></li>
				</ul>
			</td></tr>
			<tr style="display:none" class="faq-header">
				<td></td>
				<td style="text-align:right"><button class="add-faq" type="button"><?php esc_html_e( "+ Add FAQ", "wp-travel" ); ?></button></td>
			</tr>
			<?php
		}?>
		</table>
		</div>
		<?php		
	}

	/**
	 * Tabs metabox callback
	 */
	function trip_options_callback_tabs( $post ) {
		if ( ! $post ) {
			return;
		}
		$trip_tabs = wp_travel_get_admin_trip_tabs( $post->ID );

		?>
		<div id='tab_panel' class='panel woocommerce_options_panel'>
		<?php
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
						<td><input type="text" class="item_title" name="tab_item_custom-' . $key . '" value="' . $tab_label . '"></td>
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
		</div>

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

					$( element ).delegate(".item_title", "keyup", function(){
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

	/*
	 * Updates a post meta field based on the given post ID.
	 */
	function save_woocommerce_product_custom_fields($post_id) {
		$product = wc_get_product($post_id);
		$is_itinerary = isset( $_POST['_itinerary'] ) ? 'yes' : 'no';
		$product->update_meta_data('_itinerary', sanitize_text_field($is_itinerary));

		$itineraries = array();
		$xx = 0;
		for ($x = 0; $x < 100; $x++) {
			if ($_POST['itinerary_item_label-' . $x]!="" && $_POST['itinerary_item_label-' . $x] != DEFAULT_ITINERARY_LABEL) {
				$itineraries[$xx]['label'] = sanitize_text_field( $_POST['itinerary_item_label-' . $x] );
				$itineraries[$xx]['title'] = sanitize_text_field( $_POST['itinerary_item_title-' . $x] );
				$itineraries[$xx]['date'] = sanitize_text_field( $_POST['itinerary_item_date-' . $x] );
				//$itineraries[$xx]['time'] = sanitize_text_field( $_POST['itinerary_item_time-' . $x] );
				$itineraries[$xx]['desc'] = sanitize_text_field( $_POST['itinerary_item_desc-' . $x] );
				$yy = 0;
				for ($y = 0; $y < 100; $y++) {
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
		//$product->update_meta_data( 'wp_travel_trip_itinerary_data', $itineraries );
		update_post_meta( $post_id, 'wp_travel_trip_itinerary_data', $itineraries );

		if (!empty($_POST['wp_travel_trip_include'])) {
			$includes = sanitize_text_field( $_POST['wp_travel_trip_include'] );
			$product->update_meta_data( 'wp_travel_trip_include', $includes );
		}

		if (!empty($_POST['wp_travel_trip_exclude'])) {
			$excludes = sanitize_text_field( $_POST['wp_travel_trip_exclude'] );
			$product->update_meta_data( 'wp_travel_trip_exclude', $excludes );
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
		//$product->update_meta_data( 'wp_travel_faq_question', $question );
		//$product->update_meta_data( 'wp_travel_faq_answer', $answer );
		update_post_meta( $post_id, 'wp_travel_faq_question', $question );
		update_post_meta( $post_id, 'wp_travel_faq_answer', $answer );

		$product->save();
	}		
	//add_action('woocommerce_process_product_meta', 'save_woocommerce_product_custom_fields');

	/**
	 * Add a new meta box for product
	 * Step 1. add_meta_box()
	 * Step 2. Callback function with meta box HTML
	 * Step 3. Save meta box data
	 */
	function trip_orders_add_metabox() {
		add_meta_box(
			'trip-orders', // metabox ID
			esc_html__( 'Orders', 'dgc-domain' ), // title
			array( __CLASS__, 'trip_orders_metabox_callback' ), // callback function
			'product', // post type or post types in array
			'normal', // position (normal, side, advanced)
			'default' // priority (default, low, high, core)
		);
	}

	function trip_orders_metabox_callback( $post ) {

		global $post;
		echo '<h3>' . __( 'Orders : ', 'text-domain' ) . '</h3>';
		?>
		<div class="tabs">
			<ul class="tab-links">
				<li class="active"><a href="#tab1"><?php esc_html_e( 'Completed', 'text-domain' ); ?></a></li>
				<li><a href="#tab2"><?php esc_html_e( 'Processing', 'text-domain' ); ?></a></li>
				<li><a href="#tab3"><?php esc_html_e( 'On hold', 'text-domain' ); ?></a></li>
			</ul>
	
			<div class="tab-content">
				<div id="tab1" class="tab active">
					<?php self::get_orders_by_product_id( $post->ID, array( 'wc-completed' ) ); ?>
				</div>
	
				<div id="tab2" class="tab">
					<?php self::get_orders_by_product_id( $post->ID, array( 'wc-processing' ) ); ?>
				</div>
	
				<div id="tab3" class="tab">
					<?php self::get_orders_by_product_id( $post->ID, array( 'wc-on-hold' ) ); ?>
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
			/*box-shadow:-1px 1px 1px rgba(0,0,0,0.15);*/
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
	
	/**
 	 * Get All orders for a given product ID.
	 *
	 * @param  integer  $product_id (required)
	 * @param  array    $order_status (optional) Default is 'wc-completed'
	 *
	 */
	function get_orders_by_product_id( $product_id, $order_status = array( 'wc-completed' ) ) {

		global $wpdb;

		$order_ids = $wpdb->get_col("
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
	
		if ( is_array( $order_ids ) && count( $order_ids ) > 0 ) {
			echo '<table style="border: 1px solid black">';
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
				foreach( $order->get_items() as $order_item ) {
					if ($order_item->get_product_id()==$product_id) {
						$order_item_id = $order_item->get_id();
						$quantity = $order_item->get_quantity();
						//$itinerary_date = $order_item->get_meta( 'itinerary_date', true );
						$itinerary_date = $order_item->get_meta( __( 'Date', 'text-domain' ), true );
						
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
			echo __( 'No Orders found.', 'text-domain' );
		}
	}
}
new Trip_Options_Admin;

