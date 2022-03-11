<?php

/**
 * @package           Ultimate_Reviews_Rocket
 * @link              https://www.webstix.com
 * @since             1.0.0
 * 
 * @wordpress-plugin
 * Plugin Name:       Ultimate Reviews Rocket
 * Plugin URI:        https://www.webstix.com/wordpress-plugin-development
 * Description:       Have your customers give you reviews to rate your service and make sure only the good ones are posted on social media or where you want. Capture low ratings and ask for feedback to help make sure your customers are happy and so bad reviews don't see the light of day.
 * Version:           1.0.0
 * Author:            Webstix
 * Author URI:        https://www.webstix.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Name of the plugin
if( ! defined( 'URR_PLUGIN_NAME' ) ) {
    define( 'URR_PLUGIN_NAME', 'Ultimate Reviews Rocket' );
}

// Unique identifier for the plugin. Used as Text Domain
if( ! defined( 'URR_PLUGIN_SLUG' ) ) {
    define( 'URR_PLUGIN_SLUG', 'ultimate-reviews-rocket' );
}

// Path to the plugin directory
if ( ! defined( 'URR_PLUGIN_DIR' ) ) {
    define( 'URR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

// URL of the plugin
if( ! defined( 'URR_PLUGIN_URL' ) ) {
    define( 'URR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// The actuall plugin version
if( ! defined( 'URR_PLUGIN_VERSION' ) ) {
    define( 'URR_PLUGIN_VERSION', '1.0.0' );
}

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ultimate-reviews-rocket.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ultimate_reviews_rocket() {

	$plugin = new Ultimate_Reviews_Rocket();
	$plugin->run();

}

/* Settings link on the plugins page
 */ 
add_filter('plugin_action_links_' . plugin_basename(__FILE__) , 'urr_plugin_settings_link');
function urr_plugin_settings_link($wsx_mcpu_link)
{
    $wsx_mcpu_link[] = '<a href="' . esc_url(get_admin_url(null, 'options-general.php?page=ultimate_reviews_rocket')) . '">' . __('Settings', 'Ultimate_Reviews_Rocket') . '</a>';
    return $wsx_mcpu_link;
}



register_activation_hook( __FILE__, 'activate_ultimate_reviews_rocket' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ultimate-reviews-rocket-activator.php
 */
function activate_ultimate_reviews_rocket() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ultimate-reviews-rocket-activator.php';
	Ultimate_Reviews_Rocket_Activator::activate();
    
    // Don't do redirects when multiple plugins are bulk activated
    if (
		( isset( $_REQUEST['action'] ) && 'activate-selected' === $_REQUEST['action'] ) &&
		( isset( $_POST['checked'] ) && count( $_POST['checked'] ) > 1 ) ) {
		return;
	}
	add_option( 'urr_activation_redirect', wp_get_current_user()->ID );
}

run_ultimate_reviews_rocket();
