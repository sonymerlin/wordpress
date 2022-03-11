<?php
/*
Plugin Name:	Social Media Links
Description:	Manage your social media links. Add them, group them, and use it as shortcode.
Version:		1.0.0
License:		GPL-2.0+
License URI:	http://www.gnu.org/licenses/gpl-2.0.txt

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

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Frontend custom stylesheet.
*/
add_action( 'wp_enqueue_scripts', 'wsxsml_custom_style' );

function wsxsml_custom_style() {

    wp_enqueue_style('wsxsml-custom-css', WP_PLUGIN_URL. '/social-media-plugin/assets/css/custom.css',false,'1.0',"all");

}


/**
 * Admin custom stylesheet.
 */
function wsxsml_enqueue_custom_admin_style() {
    wp_enqueue_style( 'custom_wsxsml_wp_admin_css', WP_PLUGIN_URL. '/social-media-plugin/assets/css/admin.css', false, '1.0.0' );
    wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
    wp_enqueue_media();
 	wp_enqueue_script('wsxsml-media-upload', WP_PLUGIN_URL.'/social-media-plugin/assets/js/admin.js', array( 'jquery' )) ;
    ?><script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script><?php
}

add_action( 'admin_enqueue_scripts', 'wsxsml_enqueue_custom_admin_style',99 );

//* Create Social Media Links custom post type
add_action( 'init', 'wsxsml_post_type' );
function wsxsml_post_type() {

  register_post_type( 'social_media_link',
    array(
      'labels' => array(
        'name'          => __( 'Social Media Links', '' ),
        'singular_name' => __( 'Social Media Link', '' ),
        'add_new'      => __( 'Add New', 'textdomain' ),
        'add_new_item' => __( 'Add New Social Media Link', 'textdomain' ),
        'new_item'     => __( 'New Social Media Link', 'textdomain' ),
        'edit_item'    => __( 'Edit Social Media Link', 'textdomain' ),
        'view_item'    => __( 'View Social Media Link', 'textdomain' ),
        'all_items'    => __( 'All Social Media Links', 'textdomain' ),
      ),
      'has_archive'  => true,
      'hierarchical' => true,
      'menu_icon'    => 'dashicons-portfolio',
      'public'       => true,
      'rewrite'      => array( 'slug' => 'social_media_link', 'with_front' => false ),
      'supports'     => array( 'title', 'author' ),


    )
  );
}

add_action( 'add_meta_boxes', 'add_events_metaboxes_social_media_link' );

function add_events_metaboxes_social_media_link() {
  global $post;

  add_meta_box('social_media_link_details', 'Social Media Links', 'social_media_link_details', 'social_media_link', 'normal', 'high');
}

// Html code for options
function social_media_link_details(){

    global $post;

	$build_text_fields_wsxsml = get_post_meta($post->ID, 'build_text_fields_wsxsml', true);
    wp_nonce_field( 'wsxsml_meta_box_nonce', 'wsxsml_meta_box_nonce' ); ?>

    <div class="clsSocialMediaOptions">
        <?php if ( $build_text_fields_wsxsml ) :

            foreach ( $build_text_fields_wsxsml as $wsxsml ) { ?>

				<table>

                    <tr>
                        <th scope="row" style="padding: 0px 0px 0px!important;"><label for="wsxsml_name">Name: </label></th>
                        <td><input type="text" id="wsxsml_name" class="widefat" name="wsxsml_name[]" value="<?php if($wsxsml['wsxsml_name'] != '') echo esc_attr( $wsxsml['wsxsml_name'] ); ?>" /></td>
                    </tr>

                    <?php
                        $checked1 = '';$class1 ='';
                        if (!empty($wsxsml['wsxsml_image'])){
                            $checked1 = 'checked="checked"';
                            $class1 = "checked";
                        }
                        $checked2 = ''; $class2 ='';
                        if (!empty($wsxsml['wsxsml_icon'])){
                            $checked2 = 'checked="checked"';
                            $class2 = "checked";
                        }
                    ?>

                    <tr class="clsOptions">
                        <th scope="row" style="padding: 0px 0px 0px!important;"><label for="wsxsml_icon">Select Image / Icon: </label></th>
                        <td>
                            <label><input type="radio" class="wsxsml_type <?php echo $class1; ?>" name="wsxsml_type[]" value="clsSMLImage" <?php echo $checked1; ?>>Image</label>
                            <label><input type="radio" class="wsxsml_type <?php echo $class2; ?>" name="wsxsml_type[]" value="clsSMLIcon" <?php echo $checked2; ?>> Icon</label>
                        </td>
                    </tr>

                    <?php

                        if (!empty($wsxsml['wsxsml_image'])){
                            $imagestyle = 'style=""';
                        } else {
                            $imagestyle = 'style="display:none;"';
                        }

                        if (!empty($wsxsml['wsxsml_icon'])){
                            $iconstyle = 'style=""';
                        }else{
                            $iconstyle = 'style="display:none;"';
                        }
                    ?>

                    <tr  class="clsSMLImage clshide" <?php echo $imagestyle; ?>>
                        <th scope="row"><label for="image">Image: </label></th>
                        <td><input type="hidden" class="icon-image-hidden-input widefat" name="wsxsml_image[]" id="image" value="<?php if($wsxsml['wsxsml_image'] != '') echo esc_url( $wsxsml['wsxsml_image'] ); ?>" />

                            <input type="button" class="icon-image-upload-button button button-primary" value="Choose Image">
                            &nbsp;
                            <input type="button" class="icon-image-delete-button button" value="Remove Image">
                            <img class="icon-image-media" src="<?php echo esc_url( $wsxsml['wsxsml_image'] ); ?>"  />
                        </td>

                    </tr>


                    <tr class="clsSMLIcon clshide" <?php echo $iconstyle; ?>>
                        <th scope="row"><label for="icon">Select Icon: </label></th>
                        <td>
                        <select id="resources-class" name="wsxsml_icon[]" class="widefat" >
                            <option value=''> Choose Icon</option>
                            <option value='facebook-f' <?php selected( $wsxsml['wsxsml_icon'], 'facebook-f'  ); ?>>Facebook</option>
                            <option value='twitter' <?php selected( $wsxsml['wsxsml_icon'], 'twitter'  ); ?>>Twitter</option>
                            <option value='instagram' <?php selected( $wsxsml['wsxsml_icon'], 'instagram'  ); ?>>Instagram</option>
                            <option value='linkedin' <?php selected( $wsxsml['wsxsml_icon'], 'linkedin'  ); ?>>Linkedin</option>
                            <option value='rss' <?php selected( $wsxsml['wsxsml_icon'], 'rss'  ); ?>>RSS Feed</option>
                            <option value='youtube' <?php selected( $wsxsml['wsxsml_icon'], 'youtube'  ); ?>> Youtube</option>
                            <option value='pinterest-p' <?php selected( $wsxsml['wsxsml_icon'], 'pinterest-p'  ); ?>> Pinterest</option>

                        </select>
                        </td>
                        <td><span><i class="fa fa-<?php echo $wsxsml['wsxsml_icon']; ?>"></i></span></td>
                    </tr>

                    <tr>
                        <th scope="row" style="width:35%;padding: 0px 0px 0px!important;"><label for="wsxsml_link">Link: </label></th>
                        <td style="width:88%"><input type="text" id="wsxsml_link" name="wsxsml_link[]" class="widefat" value="<?php if($wsxsml['wsxsml_link'] != '') echo esc_url( $wsxsml['wsxsml_link'] ); ?>" /></td>

                        <td width="50%"><a id="wsxsml-remove" href="#" >Remove this Link</a></td>
                    </tr>

				</table>
			<?php } ?>
        <?php endif; ?>
    </div>

    <div class="clsNewTable"></div>

	<p class="clearfix"><a id="wsxsml-add" class="button" style="" href="#">Add New Link</a></p>

	<table  class="wsxsml-empty-row" style="display:none;">

        <tr>
            <th scope="row"><label for="wsxsml_name">Name: </label></th>
            <td><input type="text" id="wsxsml_name" name="wsxsml_name[]" class="widefat" value="" /></td>
        </tr>

        <tr>
            <th scope="row" style="padding: 0px 0px 0px!important;"><label for="wsxsml_icon">Select Image / Icon: </label></th>
            <td>
                <label><input type="radio" class="wsxsml_type" name="wsxsml_type[]" value="clsSMLImage">Image</label>
                <label><input type="radio" class="wsxsml_type" name="wsxsml_type[]" value="clsSMLIcon"> Icon</label>
            </td>
        </tr>

        <tr  class="clsSMLImage clshide" style="display:none;">
            <th scope="row" style="padding: 0px 0px 10px;" ><label for="wsxsml_image">Image: </label></th>
                <td><input type="hidden" class="icon-image-hidden-input widefat" class="widefat" name="wsxsml_image[]" id="wsxsml_image" />

                <input type="button" class="icon-image-upload-button button button-primary" value="Choose Image">
                &nbsp;
                <input type="button" class="icon-image-delete-button button" value="Remove Image" style="display:none;">
                <img class="icon-image-media" src=""  />
            </td>
        </tr>


        <tr class="clsSMLIcon clshide" style="display:none;">
            <th scope="row" style="padding: 0px 0px 0px!important;"><label for="wsxsml_icon">Select Icon: </label></th>
            <td>
                <select id="resources-class" class="widefat" name="wsxsml_icon[]" style="width:100%">
                    <option value=''> Choose Icon</option>
                    <option value='facebook-f'>Facebook</option>
                    <option value='twitter'>Twitter</option>
                    <option value='instagram'>Instagram</option>
                    <option value='linkedin'>Linkedin</option>
                    <option value='rss'>RSS Feed</option>
                    <option value='youtube'> Youtube</option>
                    <option value='pinterest-p'> Pinterest</option>

                </select>
            </td>
        </tr>

        <tr>
            <th scope="row" style="width:35%;padding: 0px 0px 0px!important;"><label for="wsxsml_link">Link: </label></th>
            <td style="width:88%;"><input type="text" id="wsxsml_link" name="wsxsml_link[]" class="widefat" value="" /></td><br>
        <td width="50%"><a id="wsxsml-remove" href="#">Remove this Link</a></td></tr>

    </table>



    <?php
}

add_action('save_post', 'build_a_quote_meta_box_wsxsml_save',99);
function build_a_quote_meta_box_wsxsml_save($post_id) {
	if ( ! isset( $_POST['wsxsml_meta_box_nonce'] ) ||
	! wp_verify_nonce( $_POST['wsxsml_meta_box_nonce'], 'wsxsml_meta_box_nonce' ) )
		return;

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;

	if (!current_user_can('edit_post', $post_id))
		return;

	$holder_txt = get_post_meta($post_id, 'build_text_fields_wsxsml', true);
	$holder_txt_new = array();

	$wsxsml = $_POST['wsxsml_name'];
	$wsxsml_link = $_POST['wsxsml_link'];
    $wsxsml_image = $_POST['wsxsml_image'];
    $wsxsml_icon = $_POST['wsxsml_icon'];
    $wsxsml_type = $_POST['wsxsml_type'];


	$count = count( $wsxsml );

	for ( $i = 0; $i < $count; $i++ ) {
		if ( $wsxsml[$i] != '' ) :

			$holder_txt_new[$i]['wsxsml_name'] = $wsxsml[$i];
			$holder_txt_new[$i]['wsxsml_link'] = $wsxsml_link[$i];
            $holder_txt_new[$i]['wsxsml_image'] = $wsxsml_image[$i];
            $holder_txt_new[$i]['wsxsml_icon'] = $wsxsml_icon[$i];
            $holder_txt_new[$i]['wsxsml_type'] = $wsxsml_type[$i];

        endif;

	}
    // print_r($holder_txt_new);
    // exit;
	if ( !empty( $holder_txt_new ) && $holder_txt_new != $holder_txt ) {
		update_post_meta( $post_id, 'build_text_fields_wsxsml', $holder_txt_new );
	} elseif ( empty($holder_txt_new) && $holder_txt ) {
		delete_post_meta( $post_id, 'build_text_fields_wsxsml', $holder_txt );
	}
}



// Social media shortcode

function wsx_social_media($atts){

    $a = shortcode_atts( array(
        'id' => 'id',
    ), $atts );

    $args = array(
        'post_type'      => 'social_media_link',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'post__in'       => array($a['id'])
    );


    $loop = new WP_Query( $args );

    echo '<div class="clsSocialMediaOptions">';

    while ( $loop->have_posts() ) : $loop->the_post(); global $post;

    $build_text_fields_wsxsml = get_post_meta($post->ID, 'build_text_fields_wsxsml', true);


			if ( $build_text_fields_wsxsml ) :

                foreach ( $build_text_fields_wsxsml as $wsxsml ) {


                    if(!empty($wsxsml['wsxsml_image'])){
                        ?>
                            <a title="<?php echo $wsxsml['wsxsml_name']; ?>" href="<?php echo esc_url($wsxsml['wsxsml_link']); ?>" target="_blank" data-wpel-link="external" rel="nofollow noopener noreferrer">
                                <img src="<?php echo esc_url($wsxsml['wsxsml_image']); ?>" alt="<?php echo $wsxsml['wsxsml_name']; ?>">
                            </a>
                        <?php
                    } elseif($wsxsml['wsxsml_icon']){
                        ?>
                            <a title="<?php echo $wsxsml['wsxsml_name']; ?>" href="<?php echo esc_url($wsxsml['wsxsml_link']); ?>" target="_blank" data-wpel-link="external" rel="nofollow noopener noreferrer">
                            <i class="fa fa-<?php echo $wsxsml['wsxsml_icon']; ?>"></i>
                            </a>
                        <?php
                    } elseif( !empty($wsxsml['wsxsml_image'] ) && !empty($wsxsml['wsxsml_icon'])) {
                        ?>
                            <a title="<?php echo $wsxsml['wsxsml_name']; ?>" href="<?php echo esc_url($wsxsml['wsxsml_link']); ?>" target="_blank" data-wpel-link="external" rel="nofollow noopener noreferrer">
                                <img src="<?php echo esc_url($wsxsml['wsxsml_image']); ?>" alt="<?php echo $wsxsml['wsxsml_name']; ?>">
                            </a>
                        <?php
                    }
                }
            endif;

    endwhile;

    echo '</div>';

    wp_reset_postdata();

}

function wsx_social_media_shortcode( $atts ){
    ob_start();
    wsx_social_media( $atts );
    return ob_get_clean();
}
add_shortcode('wsx_social_media', 'wsx_social_media_shortcode');

function add_publish_meta_options($post_obj) {

    global $post;
    $post_type = 'social_media_link'; // If you want a specific post type


    if($post_type==$post->post_type) {
      echo  '<div class="clsSocialIns">
      <p>Social Media Shortcode:</p><p><strong>[wsx_social_media id="'.$post->ID.'"]</strong></p>
      </div>';
    }
  }

  /*
   * Add the extra options to the 'Publish' box
   */
  add_action('post_submitbox_misc_actions', 'add_publish_meta_options');

  // Add custom column with file size info to attachment page
add_filter( 'manage_social_media_link_posts_columns', 'manage_media_columns', 10, 2 );
function manage_media_columns( $columns )
{
    $columns['wsxsml_shortcode'] = ( 'Shortcode');
    return $columns;
}

// Add shortcode in columns
add_action( 'manage_social_media_link_posts_custom_column', 'manage_media_custom_column', 10, 2 );
function manage_media_custom_column( $column_name, $id )
{
    switch ( $column_name )
    {
        case 'wsxsml_shortcode' :
            $is_home_banner=get_post_meta( $id, 'is_home_banner', true );
            echo '<strong>[wsx_social_media id="'.$id.'"]</strong>';

        break;

        default :
        break;
    }
}

function wsxsml_columns_order($defaults) {

    global $post_type;    
    $posttype = 'social_media_link'; // If you want a specific post type

    if($posttype==$post_type) {
        $new = array();
        $shortcode = $defaults['wsxsml_shortcode'];

        foreach($defaults as $key=>$value) {
            if($key=='author') {  // when we find the author column
            $new['wsxsml_shortcode'] = $shortcode;  // put the shortcode column before it
            }
            $new[$key]=$value;
        }

        return $new;
    } else {
        return $defaults;
    }
}
add_filter('manage_posts_columns', 'wsxsml_columns_order');
