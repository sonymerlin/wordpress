<?php

/**
 * Admin Part of Plugin, dashboard and options.
 *
 * @package    Ultimate_Reviews_Rocket
 * @subpackage Ultimate_Reviews_Rocket/admin
 */
class Ultimate_Reviews_Rocket_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0 
	 * @access   private
	 * @var      string    $ultimate_reviews_rocket    The ID of this plugin.
	 */
	private $ultimate_reviews_rocket;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	public $plugin_settings_tabs = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $ultimate_reviews_rocket       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $ultimate_reviews_rocket, $version ) {

		$this->ultimate_reviews_rocket = $ultimate_reviews_rocket;
		$this->version = $version;

		$this->plugin_settings_tabs['general'] = 'General';
		$this->plugin_settings_tabs['feedback_loop'] = 'Feedback Loop';
		$this->plugin_settings_tabs['social_media'] = 'Social Media';
		$this->plugin_settings_tabs['settings'] = 'Settings';
		$this->plugin_settings_tabs['feedback_submissions'] = 'Entries';
	}

	/**
	 * Register the Settings page.
	 *
	 * @since    1.0.0
	 */
	public function ultimate_reviews_rocket_admin_menu() {

		 add_options_page( __('Ultimate Reviews Rocket', $this->ultimate_reviews_rocket), __('Ultimate Reviews Rocket', $this->ultimate_reviews_rocket), 'manage_options', $this->ultimate_reviews_rocket, array($this, 'display_plugin_admin_page'));

	}
	
	/**
	* Register the JavaScript for the dashboard.
	*
	* @since 		1.0.0
	*/
	public function enqueue_scripts( $hook_suffix ) {
		global $post_type;
		if( isset($_GET['page']) == 'ultimate_reviews_rocket') {
			wp_enqueue_script( URR_PLUGIN_SLUG, plugin_dir_url( __FILE__ ) . 'js/jquery.radios-to-slider.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( URR_PLUGIN_SLUG . '-admin', plugin_dir_url( __FILE__ ) . 'js/urr-admin.js', array( 'jquery' ), $this->version, true );			
			wp_enqueue_script( URR_PLUGIN_SLUG . '-admin-datatable-min', plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.min.js', array( 'jquery' ), $this->version, true );
		}	
						
	} // enqueue_scripts()
	
	
	
	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since 		1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_media();
		if( isset($_GET['page']) == 'ultimate_reviews_rocket') {
			wp_enqueue_style( URR_PLUGIN_SLUG, plugin_dir_url( __FILE__ ) . 'css/ultimate-reviews-rocket-admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( URR_PLUGIN_SLUG.'fa', plugin_dir_url( __FILE__ ) . 'css/font-awesome.css', array(), $this->version, 'all' );			
			wp_enqueue_style( URR_PLUGIN_SLUG.'bootstrap-datatables', plugin_dir_url( __FILE__ ) . 'css/dataTables.bootstrap4.min.css', array(), $this->version, 'all' );			
		}

	} // enqueue_styles()
	

	/**
	 * Settings - Validates saved options
	 *
	 * @since 		1.0.0
	 * @param 		array 		$input 			array of submitted plugin options
	 * @return 		array 						array of validated plugin options
	 */
	public function settings_sanitize( $input ) {

		// Initialize the new array that will hold the sanitize values
		$new_input = array();

		if(isset($input)) {
			// Loop through the input and sanitize each of the values
			foreach ( $input as $key => $val ) {

				if($key == 'post-type') { // dont sanitize array
					$new_input[ $key ] = $val;
				} else {
					$new_input[ $key ] = sanitize_text_field( $val );
				}
				
			}

		}

		return $new_input;

	} // sanitize()
	


	
	/**
	 * Renders Settings Tabs
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	function ultimate_reviews_rocket_render_tabs() {
		
		$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'general';				

		//screen_icon();
		echo '<h2 class="nav-tab-wrapper">';
		foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
			$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
			echo '<a class="nav-tab ' . esc_attr($active) . '" href="?page=' . esc_attr($this->ultimate_reviews_rocket) . '&tab=' . esc_attr($tab_key) . '">' . esc_html($tab_caption) . '</a>';	
		}
		echo '</h2>';
	}

	/**
	 * Plugin Settings Link on plugin page
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	function add_settings_link( $links ) {

		$mylinks = array(
			'<a href="' . admin_url( 'options-general.php?page=ultimate_reviews_rocket' ) . '">Settings</a>',
		);
		return array_merge( $links, $mylinks );
	}


	/**
	 * Callback function for the admin settings page.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page(){

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/ultimate-reviews-rocket-admin-display.php';

	}

	/**
	 * Callback function for the redirect settings page after installation.
	 *
	 * @since    1.0.0
	 */
	public function urr_redirect_after_installation() {
	
		if ( intval( get_option( 'urr_activation_redirect', false ) ) === wp_get_current_user()->ID ) {			
			delete_option( 'urr_activation_redirect' );
			wp_safe_redirect( admin_url( '/options-general.php?page=ultimate_reviews_rocket' ) );
			exit;
		}
	}
}
