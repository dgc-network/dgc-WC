<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    Trip_Options
 * @subpackage Trip_Options/includes
 */


if ( ! class_exists( 'Trip_Options' ) ) {
	/**
	 * The core plugin class.
	 *
	 * This is used to define internationalization, admin-specific hooks, and
	 * public-facing site hooks.
	 *
	 * Also maintains the unique identifier of this plugin as well as the current
	 * version of the plugin.
	 *
	 * @since      1.0.0
	 * @package    Trip_Options
	 * @subpackage Trip_Options/includes
	 * @author     dgc.network
	 */
	class Trip_Options {

		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      Trip_Options_Loader $loader Maintains and registers all hooks for the plugin.
		 */
		protected $loader;

		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string $plugin_name The string used to uniquely identify this plugin.
		 */
		protected $plugin_name;

		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string $version The current version of the plugin.
		 */
		protected $version;

		/**
		 * Define the core functionality of the plugin.
		 *
		 * Set the plugin name and the plugin version that can be used throughout the plugin.
		 * Load the dependencies, define the locale, and set the hooks for the admin area and
		 * the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {

			$this->plugin_name = 'dgc-travel';
			$this->version     = '1.0.0';

			$this->load_dependencies();
			$this->set_locale();
			$this->define_admin_hooks();
			//$this->define_public_hooks();

		}

		/**
		 * Load the required dependencies for this plugin.
		 *
		 * Include the following files that make up the plugin:
		 *
		 * Create an instance of the loader which will be used to register the hooks
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies() {

			/**
			 * The class responsible for orchestrating the actions and filters of the
			 * core plugin.
			 */
			//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-raise-prices-with-time-for-woocommmerce-loader.php';
			require_once 'class-trip-options-loader.php';

			//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rpt-meta.php';
			//require_once 'class-trip-options-meta.php';
			require_once 'helpers.php';

			/**
			 * The class responsible for defining internationalization functionality
			 * of the plugin.
			 */
			//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-raise-prices-with-time-for-woocommmerce-i18n.php';
			require_once 'class-trip-options-i18n.php';

			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-raise-prices-with-time-for-woocommmerce-admin.php';
			require_once 'class-trip-options-admin.php';

			/**
			 * The class responsible for defining all actions that occur in the public-facing
			 * side of the site.
			 */
			//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-raise-prices-with-time-for-woocommmerce-public.php';
			require_once 'class-trip-options-view.php';

			$this->loader = new Trip_Options_Loader();
		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the Raise_Prices_With_Time_For_Woocommmerce_i18n class in order to set the domain and to register the hook
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function set_locale() {

			$plugin_i18n = new Trip_Options_i18n();

			$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

		}

		/**
		 * Register all of the hooks related to the admin area functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_admin_hooks() {

			$plugin_admin = new Trip_Options_Admin( $this->get_plugin_name(), $this->get_version() );
			//$plugin_admin->run();
/*
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
			$this->loader->add_action( 'woocommerce_product_options_pricing', $plugin_admin, 'wc_product_prices' );
			$this->loader->add_action( 'woocommerce_process_product_meta', $plugin_admin, 'wc_product_save', 99, 2 );

			$this->loader->add_filter( 'woocommerce_get_sections_products', $plugin_admin, 'add_settings_section' );
			$this->loader->add_filter( 'woocommerce_get_settings_products', $plugin_admin, 'add_settings', 20, 2 );
*/			
		}


		/**
		 * Register all of the hooks related to the public-facing functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_public_hooks() {

			$plugin_public = new Trip_Options_View( $this->get_plugin_name(), $this->get_version() );
/*
			$this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
			$this->loader->add_action( 'rpt_wc_increase_price', $plugin_public, 'rpt_increase_price_cron', 10, 2 );
			$this->loader->add_action( 'woocommerce_cart_loaded_from_session', $plugin_public, 'apply_prices', 99 );
			$this->loader->add_action( 'woocommerce_add_to_cart', $plugin_public, 'apply_prices_on_add_to_cart', 19, 6 );

			$this->loader->add_filter( 'woocommerce_single_product_summary', $plugin_public, 'show_single_product_countdown', 11 );
			$this->loader->add_filter( 'woocommerce_add_to_cart', $plugin_public, 'add_cart_item_data', 99 );
			$this->loader->add_filter( 'woocommerce_get_cart_item_from_session', $plugin_public, 'load_cart_item_data_from_session', 5, 2 );

			if ( 'yes' === get_option( 'cpwt_show_countdown_on_shop_pages', 'no' ) ) {
				$this->loader->add_action( 'woocommerce_after_shop_loop_item', $plugin_public, 'show_product_countdown_loop', 10 );
			}
*/			
		}

		/**
		 * Run the loader to execute all of the hooks with WordPress.
		 *
		 * @since    1.0.0
		 */
		public function run() {
			$this->loader->run();
		}

		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 *
		 * @since     1.0.0
		 * @return    string    The name of the plugin.
		 */
		public function get_plugin_name() {
			return $this->plugin_name;
		}

		/**
		 * The reference to the class that orchestrates the hooks with the plugin.
		 *
		 * @since     1.0.0
		 * @return    Trip_Options_Loader    Orchestrates the hooks of the plugin.
		 */
		public function get_loader() {
			return $this->loader;
		}

		/**
		 * Retrieve the version number of the plugin.
		 *
		 * @since     1.0.0
		 * @return    string    The version number of the plugin.
		 */
		public function get_version() {
			return $this->version;
		}

	}
}
