<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 *
 */

// If uninstall not called from WordPress, then exit.
if( !defined( 'WP_UNINSTALL_PLUGIN' ) )
exit ();


global $wpdb;
$urr_custom_table_name = $wpdb->prefix . 'urr_form_submissions';
$urr_drop_sql = "DROP TABLE IF EXISTS $urr_custom_table_name";
$wpdb->query($urr_drop_sql);

//~ delete_option('ultimate_reviews_rocket_setting_options');
//~ delete_option('ultimate_reviews_rocket_enable_options')
//~ delete_option('ultimate_reviews_rocket_social_media_options');

foreach ( wp_load_alloptions() as $option => $value ) {
    if ( strpos( $option, 'ultimate_reviews_rocket_' ) === 0 ) {
        delete_option( $option );
    }
}

// Delete pages
$ratings_id = urr_get_page_by_slugg('review');
wp_delete_post($ratings_id->ID, true);

$bad_ratings_id = urr_get_page_by_slugg('your-feedback');
wp_delete_post($bad_ratings_id->ID, true);

$bad_ratings_thankyou_id = urr_get_page_by_slugg('feedback-thank-you');
wp_delete_post($bad_ratings_thankyou_id->ID, true);

$good_ratings_id = urr_get_page_by_slugg('social-media-reviews');
wp_delete_post($good_ratings_id->ID, true);

// Check the page slug if it exists in DB.
function urr_get_page_by_slugg($slug) {
    if ($pages = get_pages())
        foreach ($pages as $page)
            if ($slug === $page->post_name) return $page;
    return false;
}
