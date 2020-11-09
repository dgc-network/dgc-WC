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
				<?php self::trip_options_metabox_callback( $post )?>
			</div>
			
			<div class="hidden" id="tab8">
				<?php self::trip_options_metabox_callback( $post )?>
			</div>
			
			<div class="hidden" id="tab9">
				<?php self::trip_options_metabox_callback( $post )?>
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

		if( isset( $_POST[ 'seo_title' ] ) ) {
			update_post_meta( $post_id, 'seo_title', sanitize_text_field( $_POST[ 'seo_title' ] ) );
		} else {
			delete_post_meta( $post_id, 'seo_title' );
		}

		if( isset( $_POST[ 'seo_robots' ] ) ) {
			update_post_meta( $post_id, 'seo_robots', sanitize_text_field( $_POST[ 'seo_robots' ] ) );
		} else {
			delete_post_meta( $post_id, 'seo_robots' );
		}

		return $post_id;
	}

	/**
	 * Trip Info metabox.
	 *
	 * @param  Object $post Post object.
	 */
	function wp_travel_trip_info( $post ) {
		if ( ! $post ) {
			return;
		}
		$trip_code = wp_travel_get_trip_code( $post->ID );
		?>
		<table class="form-table trip-info">
			<tr>
				<td><label for="wp-travel-detail"><?php esc_html_e( 'Trip Code', 'wp-travel' ); ?></label></td>
				<td><input type="text" id="wp-travel-trip-code" disabled="disabled" value="<?php echo esc_attr( $trip_code ); ?>" /></td>
			</tr>
		</table>

		<?php 
		$wp_travel_itinerary = new WP_Travel_Itinerary();
		$trip_outline = $wp_travel_itinerary->get_outline();
		$xx = 0;
		if ( is_array( $trip_outline ) && count( $trip_outline ) > 0 ) {
			foreach ( $trip_outline as $itinerary ) {
				$itinerary_title[$xx] = get_post_meta( $post->ID, 'itinerary_title_'.$xx, true );
				$itinerary_description[$x] = get_post_meta( $post->ID, 'itinerary_description_'.$xx, true );
				$itinerary_robots[$x] = get_post_meta( $post->ID, 'itinerary_robots_'.$xx, true );
				$xx++;
			}
		} else {?>
			<div id="no-itineraries">
			<span id="first-itinerary-title"><h3><?php esc_html_e( 'Itinerary', 'wp-travel' ); ?></h3></label><br>
			<span id="first-itinerary-text"><?php esc_html_e( 'No Itineraries found.', 'wp-travel' ); ?></label>
			<span id="first-itinerary-link"><?php esc_html_e( 'Add Itinerary', 'wp-travel' ); ?></span>
			</div>
		<?php }?>

		<div id="init-itineraries">
		<table style="width:100%" class="form-table trip-outline">
			<tr>
				<td><label for="add-itinerary"><h3><?php esc_html_e( 'Itinerary', 'wp-travel' ); ?></h3></label></td>
				<td style="text-align:right"><button id="add-itinerary" type="button"><?php esc_html_e( '+ Add Itinerary', 'wp-travel' ); ?></button></td>
			</tr>
		</table>
		<ul id="sortable">
		<?php  
		for ($x = 0; $x < 100; $x++) {
			echo "<li class='sort-li' id='sort-li-" . $x . "'><span class='fas fa-bars'></span>";
			if ($xx<=0) {
				echo __( 'Day X, My plan', 'wp-travel' );
			} else{
				echo esc_attr( $itinerary_title[$x] );
			}
			$xx--;
			echo '<button id="click-itinerary-' . $x . '" type="button">Click Me</button>
			<table>
			  <tbody>
				<tr>
					<th><label for="itinerary_title">Itinerary title</label></th>
					<td><input type="text" id="itinerary_title" name="itinerary_title" value="' . esc_attr( $itinerary_title[$x] ) . '" class="regular-text"></td>
				</tr>
				<tr>
					<th><label for="itinerary_description">Itinerary description</label></th>
					<td><input type="textarea" id="itinerary_description" name="itinerary_description" value="' . esc_attr( $itinerary_description[$x] ) . '" class="regular-text"></td>
				</tr>
				<tr>
					<th><label for="itinerary_tobots">Itinerary robots</label></th>
					<td>
						<select id="itinerary_robots" name="itinerary_robots">
							<option value="">Select...</option>
							<option value="index,follow"' . selected( 'index,follow', $itinerary_robots[$x], false ) . '>Show for search engines</option>
							<option value="noindex,nofollow"' . selected( 'noindex,nofollow', $itinerary_robots[$x], false ) . '>Hide for search engines</option>
						</select>
					</td>
				</tr>
			  </tbody>
			</table>' .
		  "</li>";
		}
		?>
		</ul>
		<table style="width:100%" class="form-table trip-outline">
			<tr>
				<td></td>
				<td style="text-align:right"><button id="add-itinerary" type="button"><?php esc_html_e( '+ Add Itinerary', 'wp-travel' ); ?></button></td>
			</tr>
		</table>
		</div>


	
		<script>
			jQuery(document).ready(function($) {
    			$( "#sortable" ).sortable();
				$( "#sortable" ).disableSelection();
			
				$("#init-itineraries").hide();
				$("#first-itinerary-link").click( function(){
					$("#no-itineraries").hide();
					$("#init-itineraries").show();
					$(".sort-li").hide();
					$("#sort-li-0").show();
					//$('#sort-li-0').on('click', function() {
					$('#click-itinerary-0').click(function() {
    					//$('#sort-li-0').toggleClass('active');
    					$('#sort-li-0 table').toggleClass('edit-itinerary');
					});
				} );
			
				$("#add-itinerary").click( function(){
					$( ".sort-li" ).each( function( index, element ) {
						if ( $( this ).is(":hidden") ) {
							$( this ).show();
							$( this ).on("click", function() {
							//$( this ).on("click", "span", function() {
							//$( ".itinerary-title-click" ).on("click", function() {
								$( this ).toggleClass('active');
							});
							return false;
						};
					});
				} );
			} );
		</script>
	
		<style>
  			#sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
  			#sortable li { background: #f2f2f2; border: 1px solid #ccc; margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em;}
			#sortable li span { position: absolute; margin-left: -1.3em; }
			/*.click-me { margin-right: 3px; }*/
			/*#sortable li table { background: #ffffff; border: 1px solid #ccc; width: 100%; display: none; margin-left: -1.2em; padding-left: 1.5em; }
			#sortable li.active table { display: block; }*/
			#sortable li table { background: #ffffff; border: 1px solid #ccc; width: 100%; display: none; margin-left: -1.2em; padding-left: 1.5em; }
			.edit-itinerary { display: block; }
			#first-itinerary-link { color: blue; text-decoration: underline; cursor: pointer;}
			.fa-bars:before { content: "\f0c9"; }
  		</style>
		<?php
	}
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

