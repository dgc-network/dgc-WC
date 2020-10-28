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
			array( __CLASS__, 'horizontal_example_metabox' ), // callback function
			'product', // post type or post types in array
			'normal', // position (normal, side, advanced)
			'default' // priority (default, low, high, core)
		);
		//wp_enqueue_script( 'mytabs', get_bloginfo( 'stylesheet_directory' ). '/mytabs.js', array( 'jquery-ui-tabs' ) );
		wp_enqueue_script( 'mytabs', 'mytabs.js', array( 'jquery-ui-tabs' ) );
	}
 
	function my_example_metabox() {
		?>
		<style>
			* {box-sizing: border-box}
			body {font-family: "Lato", sans-serif;}

			/* Style the tab */
			.tab {
  				float: left;
  				border: 1px solid #ccc;
  				background-color: #f1f1f1;
  				width: 30%;
  				height: 300px;
			}

			/* Style the buttons inside the tab */
			.tab button {
  				display: block;
  				background-color: inherit;
  				color: black;
  				padding: 22px 16px;
  				width: 100%;
  				border: none;
  				outline: none;
  				text-align: left;
  				cursor: pointer;
  				transition: 0.3s;
  				font-size: 17px;
			}

			/* Change background color of buttons on hover */
			.tab button:hover {
  				background-color: #ddd;
			}

			/* Create an active/current "tab button" class */
			.tab button.active {
  				background-color: #ccc;
			}

			/* Style the tab content */
			.tabcontent {
  				float: left;
  				padding: 0px 12px;
  				border: 1px solid #ccc;
  				width: 70%;
  				border-left: none;
  				height: 300px;
			}
		</style>

		<body>
			<h2>Vertical Tabs</h2>
			<p>Click on the buttons inside the tabbed menu:</p>

			<div class="tab">
  				<button class="tablinks" id="defaultOpen">London</button>
  				<button class="tablinks" >Paris</button>
  				<button class="tablinks" >Tokyo</button>
			</div>

			<div class="tabcontent" id="London">
  				<h3>London</h3>
  				<p>London is the capital city of England.</p>
			</div>

			<div class="tabcontent" id="Paris">
  				<h3>Paris</h3>
  				<p>Paris is the capital of France.</p> 
			</div>

			<div class="tabcontent" id="Tokyo">
  				<h3>Tokyo</h3>
  				<p>Tokyo is the capital of Japan.</p>
			</div>

		<script>
			(function ($) {
        		// constants
        		var SHOW_CLASS = 'show',
      			HIDE_CLASS = 'hide',
      			ACTIVE_CLASS = 'active';

				$(".tabcontent").removeClass('hidden');
				$(".tabcontent #London").removeClass('show');
        		$('.tab').on('click', 'tablinks', function (e) {
            		e.preventDefault();
            		var $tab = $(this),
					href = $tab.attr('href');
         			button = $tab.attr('button');

            		$('.active').removeClass(ACTIVE_CLASS);
            		$tab.addClass(ACTIVE_CLASS);

            		$('.show')
        			.removeClass(SHOW_CLASS)
        			.addClass(HIDE_CLASS)
        			.hide();

            		$(href)
        			.removeClass(HIDE_CLASS)
        			.addClass(SHOW_CLASS)
        			.hide()
        			.fadeIn(0); // changed from 550 to 0 since there was a jump while switching tabs. Try adding back 550 and see the jumping, if you dont want this to happen leave it as 0 itslef, else change to 550.
        		});
    		})(jQuery);			
/*
			function openCity(evt, cityName) {
  				var i, tabcontent, tablinks;
  				tabcontent = document.getElementsByClassName("tabcontent");
  				for (i = 0; i < tabcontent.length; i++) {
    				tabcontent[i].style.display = "none";
  				}
  				tablinks = document.getElementsByClassName("tablinks");
  				for (i = 0; i < tablinks.length; i++) {
    				tablinks[i].className = tablinks[i].className.replace(" active", "");
  				}
  				document.getElementById(cityName).style.display = "block";
  				evt.currentTarget.className += " active";
			}

			// Get the element with id="defaultOpen" and click on it
			document.getElementById("defaultOpen").click();
*/			
		</script>
   
		</body>
		<?php
	}

	function horizontal_example_metabox( $post ) {
		?>
		<div id="mytabs">
			<ul class="category-tabs">
				<li><a href="#frag1">Itinerary</a></li>
				<li><a href="#frag2">Prices & Dates</a></li>
				<li><a href="#frag3">Includes/Excludes</a></li>
				<li><a href="#frag4">Facts</a></li>
				<li><a href="#frag5">Gallery</a></li>
				<li><a href="#frag6">Locations</a></li>
				<li><a href="#frag7">FAQs</a></li>
				<li><a href="#frag8">Misc. Options</a></li>
				<li><a href="#frag9">Tabs</a></li>
			</ul>
			<br class="clear" />
			<div id="frag1">
				<p>Trip Code</p>
				<p>Trip Outline</p>
				<p>Itinerary
				No Itineraries found. Add Itinerary</p>
			</div>
			<div class="hidden" id="frag2">
				<?php wp_travel_trip_info( $post )?>
			</div>
			<div class="hidden" id="frag3">
				<p>#3 - Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>
			</div>
		</div>

		<script src="vertical.js"></script>
		<script>
			jQuery(document).ready(function($) {
    			$("#mytabs .hidden").removeClass('hidden');
    			$("#mytabs").tabs();
			});
		</script>
		<?php
	}

	function vertical_example_metabox() {
		?>
		<div class="flat-form">
            <ul class="tabs">
                <li><a href="#tab1" class="active" >Tab1 </a></li>
                <li><a href="#tab2" >Tab2</a></li>
            </ul>
            <div id="tab1" class="form-action show">
                Hello1
            </div>
            <div id="tab2" class="form-action hide">
                Hello2
            </div>
        </div>
				
		<script>
			(function ($) {
        		// constants
        		var SHOW_CLASS = 'show',
      			HIDE_CLASS = 'hide',
      			ACTIVE_CLASS = 'active';

        		$('.tabs').on('click', 'li a', function (e) {
            		e.preventDefault();
            		var $tab = $(this),
         			href = $tab.attr('href');

            		$('.active').removeClass(ACTIVE_CLASS);
            		$tab.addClass(ACTIVE_CLASS);

            		$('.show')
        			.removeClass(SHOW_CLASS)
        			.addClass(HIDE_CLASS)
        			.hide();

            		$(href)
        			.removeClass(HIDE_CLASS)
        			.addClass(SHOW_CLASS)
        			.hide()
        			.fadeIn(0); // changed from 550 to 0 since there was a jump while switching tabs. Try adding back 550 and see the jumping, if you dont want this to happen leave it as 0 itslef, else change to 550.
        		});
    		})(jQuery);			
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

/*
    Code assumes it will be in the theme functions.php file
    Update the enqueue path if using it elsewhere
*/
add_action( 'add_meta_boxes_post', 'add_post_metabox' );

function add_post_metabox() {
    //wp_enqueue_script( 'mytabs', get_bloginfo( 'stylesheet_directory' ). '/mytabs.js', array( 'jquery-ui-tabs' ) );
    wp_enqueue_script( 'mytabs', plugins_url( '/includes/mytabs.js', __FILE__ ), array( 'jquery-ui-tabs' ) );
    add_meta_box( 'examplebox' , __('Example box'), 'my_example_metabox', 'post', 'side', 'core'/*,array()*/);
}

function my_example_metabox() {
    ?>
    <div id="mytabs">
        <ul class="category-tabs">
            <li><a href="#frag1">Tab 1</a></li>
            <li><a href="#frag2">Tab 2</a></li>
            <li><a href="#frag3">Tab 3</a></li>
        </ul>
        <br class="clear" />
        <div id="frag1">
            <p>#1 - Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>
        </div>
        <div class="hidden" id="frag2">
            <p>#2 - Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>
        </div>
        <div class="hidden" id="frag3">
            <p>#3 - Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>
        </div>
    </div>
    <?php
}


