<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    rotating-hero-image
 * @subpackage rotating-hero-image/public
 * @author     Webstix <testing@webstix.com>
 */

class display_rotating_hero_image{

    function __construct($atts) {

        add_action( 'wp_enqueue_scripts', 'wsxhi_custom_style' );

        wsx_hero_image($atts);

    }
}

/**
 * Frontend custom stylesheet.
*/


function wsxhi_custom_style() {
    wp_enqueue_style( 'custom_wsxhi_wp_public', plugin_dir_url( __FILE__ ). 'css/custom.css', false, '1.0.0' );
    wp_enqueue_script('custom_wsxhi_wp_jquery', plugin_dir_url( __FILE__ ).'js/jquery-3.5.1.min.js') ;
    wp_register_script('custom_wsxhi_wp_js', plugin_dir_url( __FILE__ ).'js/custom.js', array( 'jquery' )) ;
}

/**
 *  Create hero_image custom post type
*/

function wsx_hero_image($atts){
    
    $show_banner_title      = get_option( 'show_banner_title');
    $show_banner_text       = get_option( 'show_banner_text');
    $show_banner_button     = get_option( 'show_banner_button');
    $width                  = get_option( 'width');
    $height                 = get_option( 'height');
    $title_font_style       = get_option( 'title_font_style');
    $banner_text_font_style = get_option( 'banner_text_font_style');
    $button_color           = get_option( 'button_color');
    $button_text_color      = get_option( 'button_text_color');
    $title_font_color       = get_option( 'title_font_color');
    $banner_text_font_color = get_option( 'banner_text_font_color');
    $opacity                = get_option( 'opacity');
    $text_align             = get_option( 'banner_text_align');
    $a = shortcode_atts( array(
        'catid' => 'catid',
    ), $atts );

     
      $args= array(
        'post_type'      => 'hero_image',
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'asc',
        'posts_per_page' => -1,
        'orderby'        => 'asc'
    );

    $args['tax_query'] = array(
        array (
            'taxonomy' => 'wsxhi_categories',
            'field' => 'id',
            'terms' => array($a['catid']),
        )
    );
    $loop= new WP_Query( $args);
    
    while ( $loop->have_posts() ) : $loop->the_post(); global $post;
    $hero_postid[]=$post->ID;
    endwhile;
  
    
    $current_postid = function ($hour) use ($hero_postid) {
        $index = $hour / get_option('time_interval') % count($hero_postid);
        return $hero_postid[$index];
    };

    $currentHour = date('G');
    
    $post = get_post($current_postid($currentHour)); //assuming $id has been initialized
    setup_postdata($post);
    $featured_img_url = get_the_post_thumbnail_url();
    $button_text = get_post_meta($post->ID, 'button_text', true);
    $button_link = get_post_meta($post->ID, 'button_link', true);
    $link_type   = get_post_meta($post->ID, 'link_type', true);
    $the_title=get_the_title();
    $the_content=get_the_content();
    $target = '';
    if($link_type == 'clsExternal') {
        $target = "target='_blank'";
    }

    $image_id = get_post_thumbnail_id();
    $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
            ?>
        
        <div class="clsHeroImage" style="background-image: url(<?php echo $featured_img_url;?>);background-color: rgba(255, 255, 255, <?php echo $opacity; ?>); background-size: cover;background-repeat:no-repeat;background-blend-mode: overlay;height:<?php echo $height; ?>px; width:<?php echo $width; ?>%;">
            
                    <div class="Bannercontainer" style="text-align: <?php echo get_option('banner_text_align');?>;height:<?php echo $height; ?>px; width:<?php echo $width; ?>%;">
                          <?php 
                          if($show_banner_title == 'Yes'):  
                          echo '<'.$title_font_style.' style="color:'.$title_font_color.'">'; 
                          echo $the_title; 
                          echo '</'.$title_font_style.'>'; 
                          endif; 
                          
                          if($show_banner_text == 'Yes'): 
                          echo '<'.$banner_text_font_style.' style="color:'.$banner_text_font_color.'">';
                          echo $the_content;
                          echo '</'.$banner_text_font_style.'>'; 
                          endif; 
                          
                          if($show_banner_button == 'Yes'): 
                            echo '<br><a href="'.esc_url($button_link).'" class="Herobtn" style="text-decoration: none;background:'.$button_color.';color:'.$button_text_color.'" '.$target.'>';
                            echo $button_text; 
                            echo '</a>';
                          endif; 
                          ?>
                    </div>

                </div>
            
            <?php

    wp_reset_postdata();


}

