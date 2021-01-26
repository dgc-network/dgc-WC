<?php
/**
 * Admin Settings.
 *
 * @package inc/admin
 */

/**
 * Class for admin settings.
 */
class WP_Travel_Admin_Settings {
	/**
	 * Parent slug.
	 *
	 * @var string
	 */
	public static $parent_slug;

	/**
	 * Page.
	 *
	 * @var string
	 */
	public static $collection = 'settings';
	/**
	 * Constructor.
	 */
	public function __construct() {

		self::$parent_slug = 'edit.php?post_type=itinerary-booking';
		add_filter( 'wp_travel_admin_tabs', array( $this, 'add_tabs' ) );
		// Save Settings.
		add_action( 'load-itinerary-booking_page_settings', array( $this, 'save_settings' ) );
	}

	/**
	 * Call back function for Settings menu page. [ inc > admin > class-admin-menu.php]
	 */
	public static function setting_page_callback() {

		$args['settings']       = wp_travel_get_settings();
		$url_parameters['page'] = self::$collection;
		$url                    = admin_url( self::$parent_slug );
		$url                    = add_query_arg( $url_parameters, $url );
		$sysinfo_url            = add_query_arg( array( 'page' => 'sysinfo' ), $url );

		echo '<div class="wrap wp-trave-settings-warp">';
			echo '<h1>' . __( 'WP Travel Settings', 'text-domain' ) . '</h1>';
			echo '<div class="wp-trave-settings-form-warp">';
			do_action( 'wp_travel_before_admin_setting_form' );
			echo '<form method="post" action="' . esc_url( $url ) . '">';
				echo '<div class="wp-travel-setting-buttons">';
				submit_button( __( 'Save Settings', 'text-domain' ), 'primary', 'save_settings_button', false, array( 'id' => 'save_settings_button_top' ) );
				echo '</div>';
				WP_Travel()->tabs->load( self::$collection, $args );
				echo '<div class="wp-travel-setting-buttons">';
				echo '<div class="wp-travel-setting-system-info">';
					echo '<a href="' . esc_url( $sysinfo_url ) . '" title="' . __( 'View system information', 'text-domain' ) . '"><span class="dashicons dashicons-info"></span>';
						esc_html_e( 'System Information', 'text-domain' );
					echo '</a>';
				echo '</div>';
				echo '<input type="hidden" name="current_tab" id="wp-travel-settings-current-tab">';
				wp_nonce_field( 'wp_travel_settings_page_nonce' );
				submit_button( __( 'Save Settings', 'text-domain' ), 'primary', 'save_settings_button', false );
				echo '</div>';
			echo '</form>';
			do_action( 'wp_travel_after_admin_setting_form' );
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Call back function for Settings menu page.
	 */
	public static function setting_page_callback_new() {
		?>
			<div id="wp-travel-settings-block-wrapper">
				<div id="wp-travel-settings-block"></div>
				<div id="aside-wrap" class="single-module-side">
					<div id="wp_travel_support_block_id" class="postbox ">
						<button type="button" class="handlediv" aria-expanded="true">
							<span class="screen-reader-text"><?php esc_html_e( 'Toggle panel: Support', 'text-domain' ); ?></span>
							<span class="toggle-indicator-acc" aria-hidden="true"></span>
						</button>
						<h2 class="hndle ui-sortable-handle">
							<span><?php esc_html_e( 'Support', 'text-domain' ); ?></span>
						</h2>
						<div class="inside">

							<div class="thumbnail">
								<img src="<?php echo plugins_url( '/wp-travel/assets/images/support-image.png' ); ?>">
									<p class="text-justify"><?php esc_html_e( 'Click Below for support.', 'text-domain' ); ?> </p>
									<p class="text-center"><a href="http://wptravel.io/support/" target="_blank" class="button button-primary"><?php esc_html_e( 'Get Support Here', 'text-domain' ); ?></a></p>
							</div>

						</div>
					</div>

					<div id="wp_travel_doc_block_id" class="postbox ">
						<button type="button" class="handlediv" aria-expanded="true">
							<span class="screen-reader-text"><?php esc_html_e( 'Toggle panel: Documentation', 'text-domain' ); ?></span>
							<span class="toggle-indicator" aria-hidden="true"></span>
						</button>
						<h2 class="hndle ui-sortable-handle">
							<span><?php esc_html_e( 'Documentation', 'text-domain' ); ?></span>
						</h2>
						<div class="inside">

							<div class="thumbnail">
								<img src="<?php echo plugins_url( '/wp-travel/assets/images/docico.png' ); ?>">
									<p class="text-justify"><?php esc_html_e( 'Click Below for our full Documentation about logo slider.', 'text-domain' ); ?> </p>
									<p class="text-center"><a href="http://wptravel.io/documentations/" target="_blank" class="button button-primary"><?php esc_html_e( 'Get Documentation Here', 'text-domain' ); ?></a></p>
							</div>

						</div>
					</div>

					<div id="wp_travel_review_block_id" class="postbox ">
						<button type="button" class="handlediv" aria-expanded="true">
							<span class="screen-reader-text"><?php esc_html_e( 'Toggle panel: Reviews', 'text-domain' ); ?></span>
							<span class="toggle-indicator" aria-hidden="true"></span>
						</button>
						<h2 class="hndle ui-sortable-handle">
							<span><?php esc_html_e( 'Reviews', 'text-domain' ); ?></span>
						</h2>
						<div class="inside">
							<div class="thumbnail">
								<p class="text-center">
									<i class="dashicons dashicons-star-filled" aria-hidden="true"></i>
									<i class="dashicons dashicons-star-filled" aria-hidden="true"></i>
									<i class="dashicons dashicons-star-filled" aria-hidden="true"></i>
									<i class="dashicons dashicons-star-filled" aria-hidden="true"></i>
									<i class="dashicons dashicons-star-filled" aria-hidden="true"></i>
								</p>
								<h5>
								<?php
								esc_html_e(
									'"The plugin is very intuitive and fresh. The layout fits well into theme with flexibility to different shortcodes. Its great plugin for travel or tour agent websites."',
									'text-domain'
								)
								?>
									</h5>
								<span class="by"><strong> <a href="https://profiles.wordpress.org/muzdat" target="_blank"><?php esc_html_e( 'muzdat', 'text-domain' ); ?></a></strong></span>

							</div>
							<div class="thumbnail last">
								<h5><?php esc_html_e( '"Please fill free to leave us a review, if you found this plugin helpful."', 'text-domain' ); ?></h5>
								<p class="text-center"><a href="https://wordpress.org/plugins/wp-travel/#reviews" target="_blank" class="button button-primary"><?php esc_html_e( 'Leave a Review', 'text-domain' ); ?></a></p>
							</div>
						</div>
					</div>
				</div>
			</div>

		<?php
	}

	/**
	 * Add Tabs to settings page.
	 *
	 * @param array $tabs Tabs array list.
	 */
	public function add_tabs( $tabs ) {
		$settings_fields['general'] = array(
			'tab_label'     => __( 'General', 'text-domain' ),
			'content_title' => __( 'General Settings', 'text-domain' ),
			'priority'      => 10,
			'callback'      => 'wp_travel_settings_callback_general',
			'icon'          => 'fa-sticky-note',
		);

		$settings_fields['itinerary'] = array(
			'tab_label'     => ucfirst( WP_TRAVEL_POST_TITLE_SINGULAR ),
			'content_title' => __( ucfirst( WP_TRAVEL_POST_TITLE_SINGULAR ) . ' Settings', 'text-domain' ),
			'priority'      => 20,
			'callback'      => 'wp_travel_settings_callback_itinerary',
			'icon'          => 'fa-hiking',
		);

		$settings_fields['email'] = array(
			'tab_label'     => __( 'Email', 'text-domain' ),
			'content_title' => __( 'Email Settings', 'text-domain' ),
			'priority'      => 25,
			'callback'      => 'wp_travel_settings_callback_email',
			'icon'          => 'fa-envelope',
		);

		$settings_fields['account_options_global'] = array(
			'tab_label'     => __( 'Account', 'text-domain' ),
			'content_title' => __( 'Account Settings', 'text-domain' ),
			'priority'      => 30,
			'callback'      => 'wp_travel_settings_callback_account_options_global',
			'icon'          => 'fa-lock',
		);

		$settings_fields['tabs_global'] = array(
			'tab_label'     => __( 'Tabs', 'text-domain' ),
			'content_title' => __( 'Global Tabs Settings', 'text-domain' ),
			'priority'      => 40,
			'callback'      => 'wp_travel_settings_callback_tabs_global',
			'icon'          => 'fa-window-maximize',
		);
		$settings_fields['payment']     = array(
			'tab_label'     => __( 'Payment', 'text-domain' ),
			'content_title' => __( 'Payment Settings', 'text-domain' ),
			'priority'      => 50,
			'callback'      => 'wp_travel_settings_callback_payment',
			'icon'          => 'fa-credit-card',
		);
		$settings_fields['facts']       = array(
			'tab_label'     => __( 'Facts', 'text-domain' ),
			'content_title' => __( 'Facts Settings', 'text-domain' ),
			'priority'      => 60,
			'callback'      => 'wp_travel_settings_callback_facts',
			'icon'          => 'fa-industry',
		);
		// if ( ! is_multisite() ) :
			$settings_fields['license'] = array(
				'tab_label'     => __( 'License', 'text-domain' ),
				'content_title' => __( 'License Details', 'text-domain' ),
				'priority'      => 70,
				'callback'      => 'wp_travel_settings_callback_license',
				'icon'          => 'fa-id-badge',
			);
		// endif;
		$settings_fields['field_editor']                  = array(
			'tab_label'     => __( 'Field Editor', 'text-domain' ),
			'content_title' => __( 'Field Editor', 'text-domain' ),
			'priority'      => 75,
			'callback'      => 'wp_travel_settings_callback_field_editor',
			'icon'          => 'fa-newspaper',
		);
		$settings_fields['utilities_faq_global']          = array(
			'tab_label'     => __( 'FAQs', 'text-domain' ),
			'content_title' => __( 'Global FAQs', 'text-domain' ),
			'priority'      => 80,
			'callback'      => 'wp_travel_settings_callback_utilities_faq_global',
			'icon'          => 'fa-question-circle',
		);
		$settings_fields['cart_checkout_settings_global'] = array(
			'tab_label'     => __( 'Cart & Checkout', 'text-domain' ),
			'content_title' => __( 'Cart & Checkout Process Options', 'text-domain' ),
			'priority'      => 85,
			'callback'      => 'wp_travel_settings_callback_cart_checkout_settings_global',
			'icon'          => 'fa-shopping-cart',
		);

		$settings_fields['addons_settings']     = array(
			'tab_label'     => __( 'Addons Settings', 'text-domain' ),
			'content_title' => __( 'Addons Settings', 'text-domain' ),
			'priority'      => 90,
			'callback'      => 'wp_travel_settings_callback_addons_settings',
			'icon'          => 'fa-plug',
		);
		$settings_fields['misc_options_global'] = array(
			'tab_label'     => __( 'Misc. Options', 'text-domain' ),
			'content_title' => __( 'Miscellaneous Options', 'text-domain' ),
			'priority'      => 95,
			'callback'      => 'wp_travel_settings_callback_misc_options_global',
			'icon'          => 'fa-thumbtack',
		);
		$settings_fields['debug']               = array(
			'tab_label'     => __( 'Debug', 'text-domain' ),
			'content_title' => __( 'Debug Options', 'text-domain' ),
			'priority'      => 100,
			'callback'      => 'wp_travel_settings_callback_debug',
			'icon'          => 'fa-bug',
		);

		$tabs[ self::$collection ] = wp_travel_sort_array_by_priority( apply_filters( 'wp_travel_settings_tabs', $settings_fields ) );
		return $tabs;
	}

	/**
	 * Save settings.
	 *
	 * @return void
	 */
	public function save_settings() {
		if ( isset( $_POST['save_settings_button'] ) ) {
			$current_tab = isset( $_POST['current_tab'] ) ? $_POST['current_tab'] : '';
			check_admin_referer( 'wp_travel_settings_page_nonce' );
			// Getting saved settings first.
			$settings        = wp_travel_get_settings();
			$settings_fields = array_keys( wp_travel_settings_default_fields() );

			foreach ( $settings_fields as $settings_field ) {
				if ( 'wp_travel_trip_facts_settings' === $settings_field ) {
					continue;
				}
				if ( isset( $_POST[ $settings_field ] ) ) {
					// Default pages settings. [only to get page in - wp_travel_get_page_id()] // Need enhanchement.
					$page_ids = array( 'cart_page_id', 'checkout_page_id', 'dashboard_page_id', 'thank_you_page_id' );
					if ( in_array( $settings_field, $page_ids ) && ! empty( $_POST[ $settings_field ] ) ) {
						$page_id = $_POST[ $settings_field ];
						/**
						 * @since 3.1.8 WPML configuration.
						 */
						if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
							update_option( 'wp_travel_' . $settings_field . '_' . ICL_LANGUAGE_CODE, $page_id );
							continue;
						} else {
							update_option( 'wp_travel_' . $settings_field, $page_id );
						}
					}

					$settings[ $settings_field ] = wp_unslash( $_POST[ $settings_field ] );
				}
			}

			// Email Templates
			// Booking Admin Email Settings.
			if ( isset( $_POST['booking_admin_template_settings'] ) && '' !== $_POST['booking_admin_template_settings'] ) {
				$settings['booking_admin_template_settings'] = stripslashes_deep( $_POST['booking_admin_template_settings'] );
			}

			// Booking Client Email Settings.
			if ( isset( $_POST['booking_client_template_settings'] ) && '' !== $_POST['booking_client_template_settings'] ) {
				$settings['booking_client_template_settings'] = stripslashes_deep( $_POST['booking_client_template_settings'] );
			}

			// Payment Admin Email Settings.
			if ( isset( $_POST['payment_admin_template_settings'] ) && '' !== $_POST['payment_admin_template_settings'] ) {
				$settings['payment_admin_template_settings'] = stripslashes_deep( $_POST['payment_admin_template_settings'] );
			}

			// Payment Client Email Settings.
			if ( isset( $_POST['payment_client_template_settings'] ) && '' !== $_POST['payment_client_template_settings'] ) {
				$settings['payment_client_template_settings'] = stripslashes_deep( $_POST['payment_client_template_settings'] );
			}

			// Enquiry Admin Email Settings.
			if ( isset( $_POST['enquiry_admin_template_settings'] ) && '' !== $_POST['enquiry_admin_template_settings'] ) {
				$settings['enquiry_admin_template_settings'] = stripslashes_deep( $_POST['enquiry_admin_template_settings'] );
			}

			// Trip Fact.
			$indexed = $_POST['wp_travel_trip_facts_settings'];
			if ( array_key_exists( '$index', $indexed ) ) {
				unset( $indexed['$index'] );
			}
			foreach ( $indexed as $key => $index ) {
				if ( ! empty( $index['name'] ) ) {
					$index['id']      = $key;
					$index['initial'] = ! empty( $index['initial'] ) ? $index['initial'] : $index['name'];
					if ( is_array( $index['options'] ) ) {
						$options = array();
						$i       = 1;
						foreach ( $index['options'] as $option ) {
							$options[ 'option' . $i ] = $option;
							$i++;
						}
						$index['options'] = $options;
					}
					$indexed[ $key ] = $index;
					continue;
				}
				unset( $indexed[ $key ] );
			}
			$settings['wp_travel_trip_facts_settings'] = $indexed;

			if ( ! isset( $_POST['wp_travel_bank_deposits'] ) ) {
				$settings['wp_travel_bank_deposits'] = array();
			}

			// @since 1.0.5 Used this filter below.
			$settings = apply_filters( 'wp_travel_before_save_settings', $settings );

			update_option( 'wp_travel_settings', $settings );
			WP_Travel()->notices->add( 'error ' );
			$url_parameters['page']    = self::$collection;
			$url_parameters['updated'] = 'true';
			$redirect_url              = admin_url( self::$parent_slug );
			$redirect_url              = add_query_arg( $url_parameters, $redirect_url ) . '#' . $current_tab;
			wp_redirect( $redirect_url );
			exit();
		}
	}

	/**
	 * System info.
	 */
	public static function get_system_info() {
		require_once sprintf( '%s/inc/admin/views/status.php', WP_TRAVEL_ABSPATH );
	}

	public function get_files() {
		if ( $_FILES ) {
			print_r( $_FILES );
		}
	}
}

new WP_Travel_Admin_Settings();
