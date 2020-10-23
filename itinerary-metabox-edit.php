<?php
class Itinerary_Metabox_Edit {
	public static function init() {
		add_action( 'add_meta_boxes', array( __CLASS__, 'register_meta_box' ) );
		//add_action( 'admin_menu', 'trip_add_metabox' );
		add_action( 'admin_menu', array( __CLASS__, 'trip_add_metabox' ) );
		//add_action( 'save_post', 'trip_save_meta', 10, 2 );
		add_action( 'save_post', array( __CLASS__, 'trip_save_metabox' ) );
	}

	public static function register_meta_box() {
		$settings        = wp_travel_get_settings();
		$switch_to_react = $settings['wp_travel_switch_to_react'];
		if ( 'yes' === $switch_to_react ) {
			add_meta_box( 
				'wp-travel-trip-options', 
				esc_html__( 'Trip Options', 'wp-travel' ), 
				array( __CLASS__, 'meta_box_callback' ), // callback function
				WP_TRAVEL_POST_TYPE, 
				'advanced', 
				'high' 
			);
		}
	}

	public static function meta_box_callback() {
		echo '<div id="wp-travel-trip-options-wrap"></div>';
	}

/**
 * Add a new meta box for product
 * Step 1. add_meta_box()
 * Step 2. Callback function with meta box HTML
 * Step 3. Save meta box data
 */
function trip_add_metabox() { 
	add_meta_box(
		'trip_metabox', // metabox ID
		'Itineraries', // title
		//'trip_metabox_callback', // callback function
		array( __CLASS__, 'trip_metabox_callback' ), // callback function
		'product', // post type or post types in array
		'normal', // position (normal, side, advanced)
		'default' // priority (default, low, high, core)
	); 
}
 
function trip_metabox_callback( $post ) {

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

public static function trip_save_metabox( $post_id, $post ) {

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

Itinerary_Metabox_Edit::init();
