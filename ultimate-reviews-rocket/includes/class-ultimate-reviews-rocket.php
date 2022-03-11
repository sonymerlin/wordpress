<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @since      1.0.0
 *
 * @package    ultimate_reviews_rocket
 * @subpackage ultimate_reviews_rocket/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    ultimate_reviews_rocket
 * @subpackage ultimate_reviews_rocket/includes
 * @author     Webstix <testing@webstix.com>
 */
class ultimate_reviews_rocket {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      ultimate_reviews_rocket_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $ultimate_reviews_rocket    The string used to uniquely identify this plugin.
	 */
	protected $ultimate_reviews_rocket;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->ultimate_reviews_rocket = 'ultimate_reviews_rocket';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->define_global_hooks(); // Call to new method that will show for both front and back
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - ultimate_reviews_rocket_Loader. Orchestrates the hooks of the plugin.
	 * - ultimate_reviews_rocket_i18n. Defines internationalization functionality.
	 * - ultimate_reviews_rocket_Admin. Defines all hooks for the dashboard.
	 * - ultimate_reviews_rocket_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ultimate-reviews-rocket-loader.php';
		
		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ultimate-reviews-rocket-admin.php';

		/**
		 * The class responsible for defining all Settings.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/settings/class-ultimate-reviews-rocket-enable-settings.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/settings/class-ultimate-reviews-rocket-social-media-settings.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/settings/class-ultimate-reviews-rocket-setting-settings.php';
		
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ultimate-reviews-rocket-public.php';

		/**
		 * The class responsible for storing and providing shared count info.
		 */
		$this->loader = new ultimate_reviews_rocket_Loader();

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		
		$plugin_admin = new ultimate_reviews_rocket_Admin( $this->get_ultimate_reviews_rocket(), $this->get_version() );
		$settings_init_enable = new ultimate_reviews_rocket_Enable_Settings( $this->get_ultimate_reviews_rocket() );
		$settings_init_social_media = new ultimate_reviews_rocket_Social_Media_Settings( $this->get_ultimate_reviews_rocket() );
		$settings_init_setting = new ultimate_reviews_rocket_Setting_Settings( $this->get_ultimate_reviews_rocket() );
		
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'ultimate_reviews_rocket_admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin,'urr_redirect_after_installation' );
		$this->loader->add_action( 'admin_init', $settings_init_enable, 'settings_api_init' );
		$this->loader->add_action( 'admin_init', $settings_init_social_media, 'settings_api_init' );
		$this->loader->add_action( 'admin_init', $settings_init_setting, 'settings_api_init' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new ultimate_reviews_rocket_Public( $this->get_ultimate_reviews_rocket(), $this->get_version() );
		
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );
		$this->loader->add_action( 'urr-rate-us', $plugin_public, 'rateUs' );
		$this->loader->add_action( 'urr-feedback', $plugin_public, 'feedBack' );
		//$this->loader->add_action( 'urr-feedback-thank-you', $plugin_public, 'feedbackThankYou' );
		$this->loader->add_action( 'urr-social-media-reviews', $plugin_public, 'socialMediaReviews' );
	}

	/**
	 * Register all of the hooks related to every page of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_global_hooks() {
	    
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
	public function get_ultimate_reviews_rocket() {
		return $this->ultimate_reviews_rocket;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    ultimate_reviews_rocket_Loader    Orchestrates the hooks of the plugin.
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
