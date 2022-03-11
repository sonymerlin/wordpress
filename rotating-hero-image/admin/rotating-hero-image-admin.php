<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package           Rotating_Hero_Image
 * @since             1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} 

class rotating_hero_image{

    function __construct() {

        // Action to add script at front side
        add_action( 'admin_enqueue_scripts', 'wsxhmi_enqueue_custom_admin_style',99 );
        
        //Action to add Post type
        add_action( 'init', 'wsxhi_hero_image_post_type' );

        //Action to add meta boxes
        add_action( 'add_meta_boxes', 'wsxhi_add_button_fields_metabox' );

        //Action to save metabox data
        add_action('save_post', 'wsxhi_build_a_meta_box_button_fields_save',99);

        //Action to add setting submenu
        add_action( 'admin_menu', 'wsxhi_add_settings_submenu' );

        //Action to add register settings
        add_action( 'admin_init', 'wsxhi_register_settings' );

        //Action to add category shortcode
        add_action( 'admin_init', 'wsxhi_category_add_dynamic_hooks' );

        //Action to add
        add_action( 'wsxhi_categories_edit_form_fields','wsxhi_taxonomy_edit_custom_meta_field', 10, 2 );

    }
}

/**
 * Admin custom stylesheet.
 */
function wsxhmi_enqueue_custom_admin_style() {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_style( 'custom_wsxhi_wp_admin', plugin_dir_url( __FILE__ ). 'css/admin.css', false, '1.0.0' );
 	wp_enqueue_script('custom_wsxhi_wp_admin_js', plugin_dir_url( __FILE__ ).'js/admin.js', array( 'jquery','wp-color-picker' )) ;
}

/**
 *  Create hero_image custom post type
*/

function wsxhi_hero_image_post_type() {

  register_post_type( 'hero_image',
    array(
      'labels' => array(
        'name'          => __( 'Hero Image', '' ),
        'singular_name' => __( 'Hero Image', '' ),
        'add_new'      => __( 'Add New', 'textdomain' ),
        'add_new_item' => __( 'Add New Hero Image', 'textdomain' ),
        'new_item'     => __( 'New Hero Image', 'textdomain' ),
        'edit_item'    => __( 'Edit Hero Image', 'textdomain' ),
        'view_item'    => __( 'View Hero Image', 'textdomain' ),
        'all_items'    => __( 'All Hero Images', 'textdomain' ),
      ),
      'has_archive'  => true,
      'hierarchical' => true,
      'menu_icon'    => 'dashicons-format-gallery',
      'public'       => true,
      'rewrite'      => array( 'slug' => 'hero_image', 'with_front' => false ),
      'supports'     => array( 'title', 'author','editor', 'thumbnail' ),
      'taxonomies'   => array( 'wsxhi_categories' ),


    )
  );
  

  register_taxonomy( 'wsxhi_categories', 'hero_image',
		array(
			'labels' => array(
				'name'          => _x( 'Categories', 'taxonomy general name', '' ),
				'add_new_item'  => __( 'Add Category', '' ),
				'new_item_name' => __( 'New Category', '' ),
			),
			'exclude_from_search' => true,
			'has_archive'         => true,
			'hierarchical'        => true,
			'rewrite'             => array( 'slug' => 'hero_image', 'with_front' => false ),
			'show_ui'             => true,
            'show_tagcloud'       => false,
            'show_admin_column' => true

		)
  );

}

/**
 *  Adding Meta boxes
*/
function wsxhi_add_button_fields_metabox() {
  global $post;

  add_meta_box( 'button-fields', 'Button Settings', 'wsxhi_button_filters_meta_box_display', 'hero_image', 'normal', 'high');

}

/**
 *  Adding Meta boxes input field
*/
function wsxhi_button_filters_meta_box_display() {
	global $post;

	$button_text = get_post_meta($post->ID, 'button_text', true);
    $button_link = get_post_meta($post->ID, 'button_link', true);
    $link_type = get_post_meta($post->ID, 'link_type', true);

	wp_nonce_field( 'button_fields_meta_box_nonce', 'button_fields_meta_box_nonce' );
		?>

			<table id="wsxhi-build-button-fieldset widefat" width="100%">
				<tbody>

					<tr>
						<td class="clsLabel">Button Text:</td>
						<td><input type="text" name="button_text" value="<?php if($button_text != '') echo $button_text; ?>" /></td>

                    </tr>

                    <tr class="clsLabel">
                        <td class="clsLabel"><label for="wsxsml_icon"> Link Type: </label></td>
                        <td>
                            <label><input type="radio" class="link_type" name="link_type" value="clsInternal" <?php if($link_type == 'clsInternal') echo 'checked'; ?>>Internal</label>
                            <label><input type="radio" class="link_type" name="link_type" value="clsExternal" <?php if($link_type == 'clsExternal') echo 'checked'; ?>>External</label>
                        </td>

                    </tr>

                    <tr>
						<td class="clsLabel">Button Link:</td>
						<td><input type="text"   name="button_link" value="<?php if($button_link != '') echo esc_url( $button_link ); ?>" /></td>

                    </tr>

				</tbody>
			</table>
		<?php


}

/**
 *  Save Meta boxes values
*/

function wsxhi_build_a_meta_box_button_fields_save($post_id) {
	if ( ! isset( $_POST['button_fields_meta_box_nonce'] ) ||
	! wp_verify_nonce( $_POST['button_fields_meta_box_nonce'], 'button_fields_meta_box_nonce' ) )
		return;

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;

	if (!current_user_can('edit_post', $post_id))
		return;


	$button_text = $_POST['button_text'];
    $button_link = $_POST['button_link'];
    $link_type = $_POST['link_type'];


	update_post_meta( $post_id, 'button_text', $button_text );
    update_post_meta( $post_id, 'button_link', $button_link );
    update_post_meta( $post_id, 'link_type', $link_type );

}

/**
 *  Adding settings submenu
*/


function wsxhi_add_settings_submenu() {
    add_submenu_page(
        'edit.php?post_type=hero_image',
        __( 'Settings', 'menu-test' ),
        __( 'Settings', 'menu-test' ),
        'manage_options',
        'options',
        'wsxhi_options_page'
    );
}

/**
 *  Register all the setting options 
*/

function wsxhi_register_settings() {

    add_option( 'show_banner_title', 'Yes');
    add_option( 'show_banner_text', 'Yes');
    add_option( 'show_banner_button', 'Yes');
    add_option( 'width', '100%');
    add_option( 'height', '100%');
    add_option( 'opacity', '0');
    add_option( 'title_font_style', 'h1');
    add_option( 'banner_text_font_style', 'p');
    add_option( 'button_color', '');
    add_option( 'button_text_color', '');
    add_option( 'title_font_color', '');
    add_option( 'banner_text_font_color', '');

    add_option( 'time_interval', '1');
    add_option( 'banner_text_align', 'left');
 
    register_setting( 'wsxhi_options_group', 'show_banner_title', 'myplugin_callback' );
    register_setting( 'wsxhi_options_group', 'show_banner_text', 'myplugin_callback' );
    register_setting( 'wsxhi_options_group', 'show_banner_button', 'myplugin_callback' );
    register_setting( 'wsxhi_options_group', 'width', 'myplugin_callback' );
    register_setting( 'wsxhi_options_group', 'height', 'myplugin_callback' );
    register_setting( 'wsxhi_options_group', 'opacity', 'myplugin_callback' );
    register_setting( 'wsxhi_options_group', 'title_font_style', 'myplugin_callback' );
    register_setting( 'wsxhi_options_group', 'banner_text_font_style', 'myplugin_callback' );
    register_setting( 'wsxhi_options_group', 'button_color', 'myplugin_callback' );
    register_setting( 'wsxhi_options_group', 'button_text_color', 'myplugin_callback' );
    register_setting( 'wsxhi_options_group', 'title_font_color', 'myplugin_callback' );
    register_setting( 'wsxhi_options_group', 'banner_text_font_color', 'myplugin_callback' );
 
    register_setting( 'wsxhi_options_group', 'time_interval', 'myplugin_callback' );
    register_setting( 'wsxhi_options_group', 'banner_text_align', 'myplugin_callback' );

}


/**
 *  Display all the setting options 
*/
function wsxhi_options_page() {


     if( isset($_GET['settings-updated']) ) { 
         ?>
        <div id="message" class="updated">
        <p><strong><?php _e('Settings saved.') ?></strong></p>
        </div>
    <?php } ?>
    <h2>Image Settings:</h2>
    <form method="post" action="options.php">
        <?php settings_fields( 'wsxhi_options_group' ); ?>
        <div class="clsHerImageOptions">

			<table>

                <tr>
                    <th><label for="width">Width: </label></th>
                    <td><input type="number" id="width" name="width" value="<?php echo get_option('width'); ?>" /> %</td>
                </tr>

                <tr>
                    <th><label for="width">Height: </label></th>
                    <td><input type="number" id="height" name="height" value="<?php echo get_option('height'); ?>" /> px</td>
                </tr>

                <tr>
                    <th><label for="width">Opacity: </label></th>
                    <td><input type="number" id="opacity" name="opacity"  min="0" max="1" step="0.1" value="<?php echo get_option('opacity'); ?>" /></td>
                </tr>

            </table>

            <h2>Banner Text Settings:</h2>

            <table>

                <tr>
                    <th><label for="wsxsml_icon">Show Banner Title: </label></th>
                    <td>
                        <label><input type="radio"   name="show_banner_title" value="Yes" <?php if(get_option('show_banner_title')== 'Yes') echo 'checked'; ?>>Yes</label>
                        <label><input type="radio"    name="show_banner_title" value="No" <?php if(get_option('show_banner_title')== 'No') echo 'checked'; ?>> No</label>
                    </td>
                </tr>

                <tr>
                    <th><label for="wsxsml_icon">Show Banner Text: </label></th>
                    <td>
                        <label><input type="radio"   name="show_banner_text" value="Yes" <?php if(get_option('show_banner_text')== 'Yes') echo 'checked'; ?>>Yes</label>
                        <label><input type="radio"    name="show_banner_text" value="No" <?php if(get_option('show_banner_text')== 'No') echo 'checked'; ?>> No</label>
                    </td>
                </tr>

                <tr>
                    <th><label for="wsxsml_icon">Show Banner Button: </label></th>
                    <td>
                        <label><input type="radio"   name="show_banner_button" value="Yes" <?php if(get_option('show_banner_button')== 'Yes') echo 'checked'; ?>>Yes</label>
                        <label><input type="radio"    name="show_banner_button" value="No" <?php if(get_option('show_banner_button')== 'No') echo 'checked'; ?>> No</label>
                    </td>
                </tr>

                <tr>
                    <th><label for="wsxsml_icon">Title Font Style: </label></th>
                    <td>
                        <label><input type="radio"   name="title_font_style" value="h1" <?php if(get_option('title_font_style')== 'h1') echo 'checked'; ?>>H1</label>
                        <label><input type="radio"   name="title_font_style" value="h2" <?php if(get_option('title_font_style')== 'h2') echo 'checked'; ?>>H2</label>
                        <label><input type="radio"   name="title_font_style" value="h3" <?php if(get_option('title_font_style')== 'h3') echo 'checked'; ?>>H3</label>
                    </td>
                </tr>

                <tr>
                    <th><label for="wsxsml_icon">Text Font Style: </label></th>
                    <td>
                        <label><input type="radio"   name="banner_text_font_style" value="p" <?php if(get_option('banner_text_font_style')== 'p') echo 'checked'; ?>>p</label>
                        <label><input type="radio"   name="banner_text_font_style" value="span" <?php if(get_option('banner_text_font_style')== 'span') echo 'checked'; ?>>span</label>
                        <label><input type="radio"   name="banner_text_font_style" value="h3" <?php if(get_option('banner_text_font_style')== 'h3') echo 'checked'; ?>>h3</label>
                    </td>
                </tr>

                <tr>
                    <th><label for="wsxsml_icon">Text Align: </label></th>
                    <td>
                        <label><input type="radio"   name="banner_text_align" value="left" <?php if(get_option('banner_text_align')== 'left') echo 'checked'; ?>>Left</label>
                        <label><input type="radio"   name="banner_text_align" value="center" <?php if(get_option('banner_text_align')== 'center') echo 'checked'; ?>>Center</label>
                        <label><input type="radio"   name="banner_text_align" value="right" <?php if(get_option('banner_text_align')== 'right') echo 'checked'; ?>>Right</label>
                    </td>
                </tr>
            </table>

            <h2>Color Settings:</h2>

            <table>

                <tr>
                    <th><label for="button-color">Button Color: </label></th>
                    <td><input type="text" id="button-color" name="button_color" value="<?php echo get_option('button_color'); ?>" class="button-color-picker"  /></td>
                </tr>

                <tr>
                    <th><label for="button-color">Button Text Color: </label></th>
                    <td><input type="text" id="button-color" name="button_text_color" value="<?php echo get_option('button_text_color'); ?>" class="button-text-color-picker"  /></td>
                </tr>

                <tr>
                    <th><label for="button-color">Title Font Color: </label></th>
                    <td><input type="text" id="button-color" name="title_font_color" value="<?php echo get_option('title_font_color'); ?>" class="title-font-color-picker"  /></td>
                </tr>

                <tr>
                    <th><label for="button-color">Text font color: </label></th>
                    <td><input type="text" id="button-color" name="banner_text_font_color" value="<?php echo get_option('banner_text_font_color'); ?>" class="banner-text-font-color-picker"  /></td>
                </tr>


            </table>

           
            <h2>Hero Image Settings:</h2>

            <table>

                <tr>
                    <th><label for="wsxsml_icon">Time Interval: </label></th>
                    <td>
                    <input type="number" id="time_interval" name="time_interval" maxlength="2" min="1" max="24" value="<?php echo get_option('time_interval'); ?>" class="time_interval"  /> Hr(s)
                    </td>
                </tr>
			</table>


        </div>

        <?php submit_button(); ?>
    </form>
    <?php
}


/**
 *  Register all the column 
*/
function wsxhi_category_add_dynamic_hooks() {
    $taxonomy = 'wsxhi_categories';
    add_filter( 'manage_' . $taxonomy . '_custom_column', 'wsxhi_category_taxonomy_rows',15, 3 );
    add_filter( 'manage_edit-' . $taxonomy . '_columns',  'wsxhi_category_taxonomy_columns' );
}

/**
 *  Add category shortcode column
*/

function wsxhi_category_taxonomy_columns( $original_columns ) {
    $new_columns = $original_columns;
    array_splice( $new_columns, 1 );
    $new_columns['wsxhi_shortcode'] = esc_html__( 'Shortcode', 'taxonomy-images' );
    unset($original_columns['name']);
    unset($original_columns['description']);
    unset($original_columns['slug']);
    unset($original_columns['short_code']);
    unset($original_columns['posts']);
    return array_merge ( $original_columns, array (
		'name' => __ ('Name'),
		'wsxhi_shortcode' => __ ( 'Shortcode' ),
		'slug'   => __ ( 'Slug' ),
		'posts' => __('Count')
	) );
}

/**
 *  Display shortcode column in category list page
*/

function wsxhi_category_taxonomy_rows( $row, $column_name, $term_id ) {
    $t_id = $term_id;

    if ( 'wsxhi_shortcode' === $column_name ) {

        return '<input type="text" value="[wsx_hero_image catid='.$term_id.']" readonly="">';

    }
}

/**
 *  Edit shortcode column in category list page
*/


function wsxhi_taxonomy_edit_custom_meta_field($term) {

    $t_id = $term->term_id;

   ?>
    <tr class="form-field">
    <th scope="row" valign="top"><label for="clswsxhi-shortcode"><?php _e( 'Shortcode', '' ); ?></label></th>
        <td>
            <strong><span class="clsWsxhiShortcode"><?php echo '[wsx_hero_image catid="'.$t_id.'"]'; ?></span></strong>
        </td>
    </tr>
<?php
}

?>