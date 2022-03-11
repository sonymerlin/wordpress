<?php
/*
 * @package           Rotating_Hero_Image
 * @link              https://www.webstix.com
 * @since             1.0.0
 * 
 * @wordpress-plugin
 * Plugin Name:       Rotating Hero Image
 * Plugin URI:        https://www.webstix.com/wordpress-plugin-development
 * Description:       Add hero banner with the help of background image and display wherever you want. The timer helps you to change it whenever you want.
 * Version:           1.0.1
 * Author:            Webstix
 * Author URI:        https://www.webstix.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt

This plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

This plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with This plugin. If not, see {URI to Plugin License}.
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} 

// Name of the plugin
if( ! defined( 'URR_PLUGIN_NAME' ) ) {
    define( 'URR_PLUGIN_NAME', 'Rotating Hero Image' );
}

// Unique identifier for the plugin. Used as Text Domain
if( ! defined( 'URR_PLUGIN_SLUG' ) ) {
    define( 'URR_PLUGIN_SLUG', 'rotating_hero_image' );
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
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

 /**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'admin/rotating-hero-image-admin.php';
require plugin_dir_path( __FILE__ ) . 'public/rotating-hero-image-public.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_rotating_hero_admin() {

	$plugin = new rotating_hero_image();

}


run_rotating_hero_admin();

function wsx_hero_image_shortcode($atts){
  ob_start();
  new display_rotating_hero_image($atts);
  return ob_get_clean();
 
}
add_shortcode('wsx_hero_image', 'wsx_hero_image_shortcode');
