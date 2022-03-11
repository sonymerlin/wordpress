<?php


class Ultimate_Reviews_Rocket_Activator {
	/* 
	- Create plugin pages if current user is admin.
	- Add the shortcodes when they are published.
	- Create a table named 'urr_form_submissions' to save the feedback form submissions.
	*/
	public static function activate() {

		if ( ! current_user_can( 'activate_plugins' ) ) return;
  
		global $wpdb;
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		foreach ( wp_load_alloptions() as $option => $value ) {
			if ( strpos( $option, 'ultimate_reviews_rocket_' ) === 0 ) {
				delete_option( $option );
			}
		}
		
		
		$current_user = wp_get_current_user(); 
		if ( current_user_can( 'activate_plugins' ) ) {
			$urr_create_ratings_page = array(
				'post_title'    => wp_strip_all_tags( 'Rate and Review Us' ),
				'post_content'  => '[urr-review]',// Add the shortcode here 
				'post_status'   => 'publish',
				'post_author'   => $current_user->ID,
				'post_type'     => 'page',
			);

			$urr_create_bad_ratings_page = array(
				'post_title'    => wp_strip_all_tags( 'Your Feedback' ),
				'post_content'  => '[urr-feedback]', // Add the shortcode here
				'post_status'   => 'publish',
				'post_author'   => $current_user->ID,
				'post_type'     => 'page',
			);

			$urr_create_bad_ratings_thank_you_page = array(
				'post_title'    => wp_strip_all_tags( 'Thank You' ),
				'post_content'  => '[urr-feedback-thank-you]', // Add the shortcode here
				'post_status'   => 'publish',
				'post_author'   => $current_user->ID,
				'post_type'     => 'page',
			);

			$urr_create_good_ratings_page = array(
				'post_title'    => wp_strip_all_tags( 'Social Media Reviews' ),
				'post_content'  => '[urr-social-media-reviews]',// Add the shortcode here
				'post_status'   => 'publish',
				'post_author'   => $current_user->ID,
				'post_type'     => 'page',
			);

			// Insert the page into the database - create the ratings main page
			if (! (new self)->urr_get_page_by_slug('rate-and-review-us')) {
				wp_insert_post( $urr_create_ratings_page );
			}

			// Insert the page into the database - create the bad ratings page
			if (! (new self)->urr_get_page_by_slug('your-feedback')) {
				wp_insert_post( $urr_create_bad_ratings_page );
			}
			// Insert the page into the database - create the bad ratings page
			if (! (new self)->urr_get_page_by_slug('thank-you')) {
				wp_insert_post( $urr_create_bad_ratings_thank_you_page );
			}
	    
			// Insert the page into the database - create the good ratings page
			if (! (new self)->urr_get_page_by_slug('social-media-reviews')) {
				wp_insert_post( $urr_create_good_ratings_page );
			}
	}

		// Create custom table upon plugin activation.
		
		$charset_collate = $wpdb->get_charset_collate();
		$urr_custom_table_name = $wpdb->prefix . 'urr_form_submissions';
		$urr_sql = "CREATE TABLE $urr_custom_table_name (
			id BIGINT UNSIGNED NOT NULL auto_increment,
			urr_username varchar(50) NOT NULL,
			urr_phone_no varchar(50) NOT NULL,
			urr_email varchar(50) NOT NULL,
			urr_subject varchar(100) NOT NULL,
			urr_comments text NOT NULL,
			urr_form_submission_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			urr_user_ip varchar(50) NOT NULL,
			urr_review_type varchar (50) NOT NULL, 
			urr_rating_value varchar (50) NOT NULL, 
			UNIQUE KEY id (id)
		) $charset_collate;";
		dbDelta( $urr_sql );

		set_transient( 'urr-admin-notice-activation', true, 10 );
		
	}
	
	
	// Check the page slug if it exists in DB.
	public function urr_get_page_by_slug($slug) {
			if ($pages = get_pages())
			foreach ($pages as $page)
				if ($slug === $page->post_name) return $page;
		return false;
	}
}


