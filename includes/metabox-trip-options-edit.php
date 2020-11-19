<?php
class Metabox_Trip_Options_Edit {
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'trip_options_add_metabox' ) );
		add_action( 'save_post', array( __CLASS__, 'trip_options_save_metabox' ), 10, 2 );
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
			//array( __CLASS__, 'trip_options_metabox_callback' ), // callback function
			array( __CLASS__, 'horizontal_tabs_metabox' ), // callback function
			//array( __CLASS__, 'vertical_example_metabox' ), // callback function
			'product', // post type or post types in array
			'normal', // position (normal, side, advanced)
			'default' // priority (default, low, high, core)
		);
		//wp_enqueue_script( 'mytabs', get_bloginfo( 'stylesheet_directory' ). '/mytabs.js', array( 'jquery-ui-tabs' ) );
		wp_enqueue_script( 'mytabs', 'mytabs.js', array( 'jquery-ui-tabs' ) );
		//wp_enqueue_script( 'mytabs', '', array( 'jquery-ui-tabs' ) );
	}
 
	function horizontal_tabs_metabox( $post ) {
		?>
		<div id="mytabs">
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
				<?php self::wp_travel_trip_info( $post )?>
			</div>

			<div class="hidden" id="tab2">
				<?php self::trip_options_metabox_callback( $post )?>
			</div>

			<div class="hidden" id="tab3">
				<?php self::trip_options_metabox_callback( $post )?>
			</div>
			
			<div class="hidden" id="tab4">
				<?php self::trip_options_metabox_callback( $post )?>
			</div>
			
			<div class="hidden" id="tab5">
				<?php self::trip_options_metabox_callback( $post )?>
			</div>
			
			<div class="hidden" id="tab6">
				<?php self::trip_options_metabox_callback( $post )?>
			</div>
			
			<div class="hidden" id="tab7">
				<?php self::wp_travel_faqs( $post )?>
			</div>
			
			<div class="hidden" id="tab8">
				<?php self::trip_options_metabox_callback( $post )?>
			</div>
			
			<div class="hidden" id="tab9">
				<?php self::wp_travel_tabs( $post )?>
			</div>
		</div>

		<script>
			jQuery(document).ready(function($) {
    			$("#mytabs .hidden").removeClass('hidden');
    			$("#mytabs").tabs();
			});
		</script>
		<?php
	}

	function trip_options_metabox_callback( $post ) {

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
	
		/**
		 * Updates a post meta field based on the given post ID.
		 * update_post_meta( int $post_id, string $meta_key, mixed $meta_value, mixed $prev_value = '' )
		 */
		$itineraries = array();
		for ($x = 0; $x < 100; $x++) {
			$itineraries[$x]['label'] = $_POST['itinerary_item_label-' . $x];
			$itineraries[$x]['title'] = $_POST['itinerary_item_title-' . $x];
			$itineraries[$x]['date'] = $_POST['itinerary_item_date-' . $x];
			$itineraries[$x]['time'] = $_POST['itinerary_item_time-' . $x];
			$itineraries[$x]['desc'] = $_POST['itinerary_item_desc-' . $x];
		}
		update_post_meta( $post_id, 'wp_travel_trip_itinerary_data', $itineraries );

		$faqs = array();
		for ($x = 0; $x < 100; $x++) {
			$faqs['question'][$x] = $_POST['faq_item_question-' . $x];
			$faqs['answer'][$x] = $_POST['faq_item_answer-' . $x];
		}
		$question = isset( $faqs['question'] ) ? $faqs['question'] : array();
		$answer   = isset( $faqs['answer'] ) ? $faqs['answer'] : array();
		update_post_meta( $post_id, 'wp_travel_faq_question', $question );
		update_post_meta( $post_id, 'wp_travel_faq_answer', $answer );

/*
		if( isset( $_POST[ 'wp_travel_trip_itinerary_data' ] ) ) {
			update_post_meta( $post_id, 'wp_travel_trip_itinerary_data', sanitize_text_field( $_POST[ 'wp_travel_trip_itinerary_data' ] ) );
		} else {
			delete_post_meta( $post_id, 'wp_travel_trip_itinerary_data' );
		}

		if( isset( $_POST[ 'seo_robots' ] ) ) {
			update_post_meta( $post_id, 'seo_robots', sanitize_text_field( $_POST[ 'seo_robots' ] ) );
		} else {
			delete_post_meta( $post_id, 'seo_robots' );
		}
*/
		return $post_id;
	}

	/**
	 * Trip Info metabox.
	 */
	function wp_travel_trip_info( $post ) {
		if ( ! $post ) {
			return;
		}
		$trip_code = wp_travel_get_trip_code( $post->ID );
		$itineraries = get_post_meta( $post->ID, 'wp_travel_trip_itinerary_data', true );
		$default_title = __( 'Day X, My plan', 'wp-travel' );
		$remove_itinerary = __( "- Remove Itinerary", "wp-travel" );
		$xx = 0;
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

				<ul id="itineraries-ul"><?php
			  
				for ($x = 0; $x < 100; $x++) {
					echo '<li class="itinerary-li" id="itinerary-li-' . $x . '"><span><i class="fas fa-bars"></i>';
					if ($xx<=0) {
						$itinerary_title = __( 'Day X, My plan', 'wp-travel' );
						echo $itinerary_title . '</span><p style="display:none"></p>';
					} else {
						$itinerary_title = esc_attr( $itineraries[$x]['title'] );
						echo $itinerary_title . '</span><p style="display:none">' . $x . '</p>';
					}
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

			<tr style="display:none" class="init-rows">
				<td></td>
				<td style="text-align:right"><button id="add-itinerary" type="button"><?php esc_html_e( "+ Add Itinerary", "wp-travel" ); ?></button></td>
			</tr>
		</table>

		<script>
			jQuery(document).ready(function($) {
    			//$( "#itineraries-ul" ).sortable();
				$( "#itineraries-ul" ).disableSelection();
				$( ".itinerary-li" ).hide();

				$( ".itinerary-li" ).each( function( index, element ) {
					if ( !$( 'p', element ).is(":empty") ) {
						$( ".init-rows" ).show();
						$( element ).show();
						$( element ).delegate("span", "click", function(){
							$( 'table', element ).toggleClass('toggle-access');
						});
					};

					$( element ).delegate(".item-title-input", "keyup", function(){
						$( 'span', element ).text($(this).val());
					});
/*
					$( element ).delegate(".remove-itinerary", "click", function(){
						$( this ).closest('.itinerary-li').remove();
					});
*/					
				});

				$( ".remove-itinerary" ).each( function( index, element ) {
					$( element ).delegate("button", "click", function(){
						$( this ).closest('.itinerary-li').remove();
					});	
					
				});

				$("#first-itinerary").click( function(){
					$(".no-itineraries").hide();
					$(".init-rows").show();
					$(".itinerary-li").hide();
					$("#itinerary-li-0").show();
					$('span','#itinerary-li-0').on('click', function() {
						$('table','#itinerary-li-0').toggleClass('toggle-access');
					});
				} );
			
				$("#add-itinerary").click( function(){
					$( ".itinerary-li" ).each( function( index, element ) {
						if ( $( this ).is(":hidden") ) {
							$( this ).show();
							$( element ).delegate("span", "click", function(){
								$( 'table', element ).toggleClass('toggle-access');
							});
							return false;
						};
					});
				} );

				$( '.itinerary_item_date' ).datepicker();
			} );
		</script>
	
		<style>
  			#itineraries-ul { list-style-type: none; margin: 0; padding: 0; width: 100%; }
  			#itineraries-ul li { background: #f2f2f2; border: 1px solid #ccc; margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em;}
			#itineraries-ul li span { margin-left: -1.3em; cursor: pointer;}
			#itineraries-ul li table { background: #ffffff; border: 1px solid #ccc; width: 100%; display: none; margin-left: -1.2em; padding-left: 1.5em; }
			#itineraries-ul li .toggle-access { display: block; }
			#first-itinerary { color: blue; text-decoration: underline; cursor: pointer;}
			/*i.fas*/
			.fa-bars:before { content: "\f0c9"; }
  		</style>
		<?php
	}

	/**
	 * Trip Info metabox.
	 */
	function wp_travel_tabs( $post ) {
		if ( ! $post ) {
			return;
		}
		$tabs = wp_travel_get_admin_trip_tabs($post->ID);
		?>
		<table style="width:100%" class="form-table tabs">
		<tr style="display:none" class="init-rows"><td colspan="2">
		<ul id="tabs-ul">
		<?php
		if ( is_array( $tabs ) && count( $tabs ) > 0 ) {
			foreach ( $tabs as $x=>$tab ) {?>
			<?php
				echo '<li class="tab-li" id="tab-li-' . $x . '"><span><i class="fas fa-bars"></i>';
				$tab_label = esc_attr( $tabs[$x]['label'] );
				echo $tab_label . '</span><p style="display:none">' . $x . '</p>';
				echo '
				<table class="update-tab" style="width:100%">
					<tbody>
					<tr>
						<th>Tab label</th>
						<td><input type="text" class="item-title-input" name="tab_item_label-' . $x . '" value="' . $tab_label . '" class="regular-text"></td>
					</tr>
					<tr>
						<th>Tab description</th>
						<td><textarea rows="3" name="tab_item_desc-' . $x . '" class="regular-text">' . esc_attr( $tabs[$x]['custom'] ) . '</textarea></td>
					</tr>
					<tr>
						<th>Tab date</th>
						<td><input type="text" class="tab_item_date" name="tab_item_date-' . $x . '" value="' . esc_attr( $tabs[$x]['global'] ) . '" class="regular-text"></td>
					</tr>
					<tr>
						<th><label for="tab_item_tobots">Tab robots</label></th>
						<td>
							<select id="tab_item_robots" name="tab_item_label-' . $x . '">
								<option value="">Home Stay ...</option>
								<option value="index,follow"' . selected( 'index,follow', $tabs[$x]['label'], false ) . '>Show for search engines</option>
								<option value="noindex,nofollow"' . selected( 'noindex,nofollow', $tabs[$x]['label'], false ) . '>Hide for search engines</option>
							</select>
						</td>
					</tr>
					</tbody>
				</table>
				</li>';
			}
		}?>			
		</ul></td></tr></table>

		<script>
			jQuery(document).ready(function($) {
    			$( "#tabs-ul" ).sortable();
				$( "#tabs-ul" ).disableSelection();
				$( ".tab-li" ).hide();

				$( ".tab-li" ).each( function( index, element ) {
					if ( !$( 'p', element ).is(":empty") ) {
						$( ".init-rows" ).show();
						$( element ).show();
						$( element ).delegate("span", "click", function(){
							$( 'table', element ).toggleClass('toggle-access');
						});
					};

					$( element ).delegate(".item-title-input", "keyup", function(){
						$( 'span', element ).text($(this).val());
					});
				});

				$( ".remove-tab" ).each( function( index, element ) {
					$( element ).delegate("button", "click", function(){
						$( this ).closest('.tab-li').remove();
					});	
					
				});

				$("#first-tab").click( function(){
					$(".no-tabs").hide();
					$(".init-rows").show();
					$(".tab-li").hide();
					$("#tab-li-0").show();
					$('span','#tab-li-0').on('click', function() {
						$('table','#tab-li-0').toggleClass('toggle-access');
					});
				} );
			
				$("#add-tab").click( function(){
					$( ".tab-li" ).each( function( index, element ) {
						if ( $( this ).is(":hidden") ) {
							$( this ).show();
							$( element ).delegate("span", "click", function(){
								$( 'table', element ).toggleClass('toggle-access');
							});
							return false;
						};
					});
				} );

				$( '.tab_item_date' ).datepicker();
			} );
		</script>
	
		<style>
  			#tabs-ul { list-style-type: none; margin: 0; padding: 0; width: 100%; }
  			#tabs-ul li { background: #f2f2f2; border: 1px solid #ccc; margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em;}
			#tabs-ul li span { margin-left: -1.3em; cursor: pointer;}
			#tabs-ul li table { background: #ffffff; border: 1px solid #ccc; width: 100%; display: none; margin-left: -1.2em; padding-left: 1.5em; }
			#tabs-ul li .toggle-access { display: block; }
			#first-tab { color: blue; text-decoration: underline; cursor: pointer;}
			.fa-bars:before { content: "\f0c9"; }
  		</style>
		<?php
	}

	/**
	 * FAQs metabox.
	 *
	 * @param  Object $post Post object.
	 */
	function wp_travel_faqs( $post ) {
		if ( ! $post ) {
			return;
		}
		$faqs = wp_travel_get_faqs( $post->ID );

		$default_question = __( 'FAQ Questions', 'wp-travel' );
		$remove_faq = __( "- Remove FAQ", "wp-travel" );
		$xx = 0;
		?>
		<table style="width:100%" class="form-table">
			<tr style="display:none" class="faq-init-rows">
				<td><h3><?php esc_html_e( 'FAQ', 'wp-travel' ); ?></h3></td>
				<td style="text-align:right"><button id="add-faq" type="button"><?php esc_html_e( '+ Add FAQ', 'wp-travel' ); ?></button></td>
			</tr>

		<?php
		if ( is_array( $faqs ) && count( $faqs ) > 0 ) {
			foreach ( $faqs as $x=>$faq ) {
				if (($faqs['question'][$x] != $default_question) && ($faqs['question'][$x] != "")) {
					$xx++;
				}
			}
		} else {?>
			<tr class="no-faqs"><td colspan="2">
				<span><h3><?php esc_html_e( 'FAQ', 'wp-travel' ); ?></h3></span><br>
				<span><?php esc_html_e( 'Please add new FAQ here.', 'wp-travel' ); ?></span>
				<span id="first-faq"><?php esc_html_e( 'Add FAQ', 'wp-travel' ); ?></span>
			</td></tr><?php
		}?>

			<tr style="display:none" class="faq-init-rows"><td colspan="2">

				<ul id="faqs-ul"><?php
			  
				for ($x = 0; $x < 100; $x++) {
					echo '<li class="faq-li" id="faq-li-' . $x . '"><span><i class="fas fa-bars"></i>';
					if ($xx<=0) {
						$faq_question = $default_question;
						echo $faq_question . '</span><p style="display:none"></p>';
					} else {
						$faq_question = esc_attr( $faqs['question'][$x] );
						echo $faq_question . '</span><p style="display:none">' . $x . '</p>';
					}
					$xx--;
					echo '
					<table class="update-faq" style="width:100%">
				  	  <tbody>
						<tr>
							<th>Enter your question</th>
							<td><input type="text" class="item-title-input" name="faq_item_question-' . $x . '" value="' . $faq_question . '" class="regular-text"></td>
						</tr>
						<tr>
							<th>Your answer</th>
							<td><textarea rows="3" name="faq_item_answer-' . $x . '" class="regular-text">' . esc_attr( $faqs['answer'][$x] ) . '</textarea></td>
						</tr>
						<tr>
							<td></td>
							<td class="remove-faq" style="text-align:right"><button id="remove-faq-' . $x . '" style="color:red" type="button">' . $remove_faq . '</button></td>
						</tr>
				  	  </tbody>
					</table>
			  		</li>';
				}?>			
				</ul>

			</td></tr>

			<tr style="display:none" class="faq-init-rows">
				<td></td>
				<td style="text-align:right"><button id="add-faq" type="button"><?php esc_html_e( "+ Add FAQ", "wp-travel" ); ?></button></td>
			</tr>
		</table>

		<script>
			jQuery(document).ready(function($) {
    			//$( "#sortable" ).sortable();
				//$( "#sortable" ).disableSelection();
				$( ".faq-li" ).hide();

				$( ".faq-li" ).each( function( index, element ) {
					if ( !$( 'p', element ).is(":empty") ) {
						$( ".faq-init-rows" ).show();
						$( element ).show();
						$( element ).delegate("span", "click", function(){
							$( 'table', element ).toggleClass('toggle-access');
						});
					};

					$( element ).delegate(".item-title-input", "keyup", function(){
						$( 'span', element ).text($(this).val());
					});
				});

				$( ".remove-faq" ).each( function( index, element ) {
					$( element ).delegate("button", "click", function(){
						$( this ).closest('.faq-li').remove();
					});						
				});

				$("#first-faq").click( function(){
					$(".no-faqs").hide();
					$(".faq-init-rows").show();
					$(".faq-li").hide();
					$("#faq-li-0").show();
					$('span','#faq-li-0').on('click', function() {
						$('table','#faq-li-0').toggleClass('toggle-access');
					});
				} );
			
				$("#add-faq").click( function(){
					$( ".faq-li" ).each( function( index, element ) {
						if ( $( this ).is(":hidden") ) {
							$( this ).show();
							$( element ).delegate("span", "click", function(){
								$( 'table', element ).toggleClass('toggle-access');
							});
							return false;
						};
					});
				} );

				$( '.faq_item_date' ).datepicker();
			} );
		</script>
	
		<style>
  			#faqs-ul { list-style-type: none; margin: 0; padding: 0; width: 100%; }
  			#faqs-ul li { background: #f2f2f2; border: 1px solid #ccc; margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em;}
			#faqs-ul li span { margin-left: -1.3em; cursor: pointer;}
			#faqs-ul li table { background: #ffffff; border: 1px solid #ccc; width: 100%; display: none; margin-left: -1.2em; padding-left: 1.5em; }
			#faqs-ul li .toggle-access { display: block; }
			#first-faq { color: blue; text-decoration: underline; cursor: pointer;}
			.fa-bars:before { content: "\f0c9"; }
  		</style>
		<?php
		
	}

	//Please add new FAQ here.Add FAQ

}

Metabox_Trip_Options_Edit::init();


/**
 * Add a custom Product Data tab
 */
add_filter( 'woocommerce_product_data_tabs', 'wk_custom_product_tab', 10, 1 );
function wk_custom_product_tab( $default_tabs ) {
    $default_tabs['custom_tab'] = array(
        'label'   =>  __( 'Itineraries', 'domain' ),
        'target'  =>  'wk_custom_tab_data',
        'priority' => 60,
        'class'   => array()
    );
    return $default_tabs;
}

add_action( 'woocommerce_product_data_panels', 'wk_custom_tab_data' );
function wk_custom_tab_data() {
   echo '<div id="wk_custom_tab_data" class="panel woocommerce_options_panel">// add content here</div>';
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

