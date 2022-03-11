<?php

/**
 * Admin Part of Plugin, dashboard and options.
 *
 * @package    ultimate_reviews_rocket
 * @subpackage ultimate_reviews_rocket/admin
 */
class ultimate_reviews_rocket_Setting_Settings extends ultimate_reviews_rocket_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0 
	 * @access   private
	 * @var      string    $ultimate_reviews_rocket    The ID of this plugin.
	 */
	private $ultimate_reviews_rocket;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $ultimate_reviews_rocket       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $ultimate_reviews_rocket ) {

		$this->label = __( 'Enable/Disable', 'ultimate_reviews_rocket' );
		$this->ultimate_reviews_rocket = $ultimate_reviews_rocket.'_setting';
		$this->plugin_settings_tabs[$this->ultimate_reviews_rocket] = $this->label;
		
	}

	/**
	 * Creates our settings sections with fields etc. 
	 *
	 * @since    1.0.0
	 */
	public function settings_api_init(){
		// register_setting( $option_group, $option_name, $settings_sanitize_callback );
		register_setting(
			$this->ultimate_reviews_rocket . '_options',
			$this->ultimate_reviews_rocket . '_options',
			array( $this, 'settings_sanitize' )
		);
		
		// add_settings_section( $id, $title, $callback, $menu_slug );
		add_settings_section(
			$this->ultimate_reviews_rocket . '-options', // section
			apply_filters( $this->ultimate_reviews_rocket . '-display-section-title', __( '', $this->ultimate_reviews_rocket ) ),
			array( $this, 'display_options_section' ),
			$this->ultimate_reviews_rocket . '-setting'
		);

		add_settings_field(
			'set-the-threshold-rating',
			apply_filters( $this->ultimate_reviews_rocket . '-set-the-threshold-rating', __( 'Set the Threshold Rating:', $this->ultimate_reviews_rocket ) ),
			array( $this, 'set_the_threshold_rating' ),
			$this->ultimate_reviews_rocket . '-setting',
			$this->ultimate_reviews_rocket . '-options'
		);
		
		add_settings_field(
			'ratings-page-heading',
			apply_filters( $this->ultimate_reviews_rocket . '-ratings-page-heading', __( 'Ratings Page Heading:', $this->ultimate_reviews_rocket ) ),
			array( $this, 'ratings_page_heading' ),
			$this->ultimate_reviews_rocket . '-setting',
			$this->ultimate_reviews_rocket . '-options'
		);
		add_settings_field(
			'shortcode_expo',
			apply_filters( $this->ultimate_reviews_rocket . '-shortcode-expo', __( '', $this->ultimate_reviews_rocket ) ),
			array( $this, 'shortcode_expo' ),
			$this->ultimate_reviews_rocket . '-setting',
			$this->ultimate_reviews_rocket . '-options'
		);
		add_settings_field(
			'ratings-page',
			apply_filters( $this->ultimate_reviews_rocket . '-ratings-page', __( 'Ratings Page:', $this->ultimate_reviews_rocket ) ),
			array( $this, 'ratings_page' ),
			$this->ultimate_reviews_rocket . '-setting',
			$this->ultimate_reviews_rocket . '-options'
		);
		add_settings_field(
			'your-feedback-page',
			apply_filters( $this->ultimate_reviews_rocket . '-your-feedback-page', __( 'Your Feedback Page:', $this->ultimate_reviews_rocket ) ),
			array( $this, 'your_feedback_page' ),
			$this->ultimate_reviews_rocket . '-setting',
			$this->ultimate_reviews_rocket . '-options'
		);
		
		add_settings_field(
			'your-feedback-thank-you-page',
			apply_filters( $this->ultimate_reviews_rocket . '-your-feedback-thank-you-page', __( 'Your Feedback Thank You Page:', $this->ultimate_reviews_rocket ) ),
			array( $this, 'your_feedback_thank_you_page' ),
			$this->ultimate_reviews_rocket . '-setting',
			$this->ultimate_reviews_rocket . '-options'
		);
		
		add_settings_field(
			'social_media_reviews_page',
			apply_filters( $this->ultimate_reviews_rocket . '', __( 'Social Media Reviews Page:', $this->ultimate_reviews_rocket ) ),
			array( $this, 'social_media_reviews_page' ),
			$this->ultimate_reviews_rocket . '-setting',
			$this->ultimate_reviews_rocket . '-options'
		);
		
		add_settings_field(
			'enable-a-feedback-form',
			apply_filters( $this->ultimate_reviews_rocket . '-enable-a-feedback-form', __( '', $this->ultimate_reviews_rocket ) ),
			//Enable a feedback form on the Social Media Reviews page:
			array( $this, 'enable_a_feedback_form' ),
			$this->ultimate_reviews_rocket . '-setting',
			$this->ultimate_reviews_rocket . '-options'
		);
	}

	/**
	 * Creates a settings section
	 *
	 * @since 		1.0.0
	 * @param 		array 		$params 		Array of parameters for the section
	 * @return 		mixed 						The settings section
	 */
	public function display_options_section( $params ) {

		//echo '<p>' . $params['title'] . '</p>';

	} // display_options_section()


	/**
	 * Disable Author Box
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function set_the_threshold_rating() {
		
		?>
		<div class="clearfix clsImage">
			<img src="<?php echo plugins_url(); ?>/ultimate-reviews-rocket/admin/img/threshold.png"/>
		</div>
		<p><a href="#" style="font-weight:bold !important;">Upgrade to Pro</a> to unlock the threshold setting feature.</p>
		<?php
	} // set_the_threshold_rating()

	/**
	 * Disable Share Box
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function your_feedback_page() {
		$options 	= get_option( $this->ultimate_reviews_rocket . '_options' );
		if ( ! empty( $options['your-feedback-page'] ) ) {
			$option = $options['your-feedback-page'];
		}else{$option= null; }
		
		$urr_ratings_page_id = $this->urr_get_id_by_slug('your-feedback');
		$pages = get_pages();
		
		$options = get_option( $this->ultimate_reviews_rocket . '_options' );
		if(!empty($options['urr_your_feedback_page'])){ $urr_ratings_page_id = $options['urr_your_feedback_page']; }
		?><div class='urr-pages-wrap'>
			<select id="<?php echo $this->ultimate_reviews_rocket; ?>_options[urr_your_feedback_page]" name="<?php echo $this->ultimate_reviews_rocket; ?>_options[urr_your_feedback_page]"> 
				<option disabled="disabled" value=""><?php echo esc_attr( __( 'Select a page' ) ); ?></option> 
				<?php
				foreach ( $pages as $page ) {
					$pagekey = '<option value="' . $page->ID . '" ';
					$pagekey .= ( $page->ID == $urr_ratings_page_id ) ? 'selected="selected"' : '';
					$pagekey .= '>';
					$pagekey .= $page->post_title;
					$pagekey .= '</option>';
					echo $pagekey;
				}
			?>
			</select>
			<a href="<?php echo get_permalink($urr_ratings_page_id); ?>" target="_blank"><small>View Page</small></a>
			</div>
			<div class='urr-pages-wrap'>
		<input type="text" id="<?php echo $this->ultimate_reviews_rocket; ?>_options[your-feedback-page]" name="<?php echo $this->ultimate_reviews_rocket; ?>_options[your-feedback-page]" readonly value="[urr-feedback]"/>
		[<a href="" onclick="return false;" class="click-to-copy"><small>Copy to clipboard</small></a>]
		</div>
		<?php
	} //feedback_form_submissions()



	/**
	 * Disable Comments Box
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function ratings_page_heading() {

		$options 	= get_option( $this->ultimate_reviews_rocket . '_options' );
		if ( ! empty( $options['ratings-page-heading'] ) ) {
			$option = $options['ratings-page-heading'];
		}else{
		$option = 'Please Rate Our Service';
		}

		?>
		<input type="text" id="<?php echo $this->ultimate_reviews_rocket; ?>_options[ratings-page-heading]" name="<?php echo $this->ultimate_reviews_rocket; ?>_options[ratings-page-heading]" value="<?php echo esc_attr($option);?>" >
		<?php
	} //ratings_page_heading()

	/**
	 * Disable Comments Box
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function shortcode_expo() {
	?>
	<p> Here is a list of pages this plugin created that your visitors will see as they leave a review. The Ratings Page is the main page to send people to. If you would like to use different pages, you can assign them here. Click the "Save Changes" button to save all these settings.<br/><br/>
	Each page already contains the necessary shortcode, so if you create your own pages, make sure you copy the corresponding shortcode over if you want to use the settings set here.</p>
	<?php
	} //shortcode_expo()

	/**
	 * Use old share style
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function ratings_page() {

		$options 	= get_option( $this->ultimate_reviews_rocket . '_options' );
		if ( ! empty( $options['ratings-page'] ) ) {
			$option = $options['ratings-page'];
		}else{$option= null; }
			$urr_ratings_page_id = $this->urr_get_id_by_slug('rate-and-review-us');			
			$pages = get_pages();
			
		$options = get_option( $this->ultimate_reviews_rocket . '_options' );
		if(!empty($options['urr_main_ratings_page'])){ $urr_ratings_page_id = $options['urr_main_ratings_page']; }
		?><div class='urr-pages-wrap'>			
					
			<select id="<?php echo $this->ultimate_reviews_rocket; ?>_options[urr_main_ratings_page]" name="<?php echo $this->ultimate_reviews_rocket; ?>_options[urr_main_ratings_page]"> 

				<option disabled="disabled" value=""><?php echo esc_attr( __( 'Select a page' ) ); ?></option> 
				<?php
					foreach ( $pages as $page ) {
						$pagekey = '<option value="' . $page->ID . '" ';
						$pagekey .= ( $page->ID == $urr_ratings_page_id ) ? 'selected="selected"' : '';
						$pagekey .= '>';
						$pagekey .= $page->post_title;
						$pagekey .= '</option>';
						echo $pagekey;
					}
				?>
			</select>
			<a href="<?php echo get_permalink($urr_ratings_page_id); ?>" target="_blank"><small>View Page</small></a>
		</div>
		<div class='urr-pages-wrap'>
			<input type="text" id="<?php echo $this->ultimate_reviews_rocket; ?>_options[ratings-page]" name="<?php echo $this->ultimate_reviews_rocket; ?>_options[ratings-page]" readonly value="[urr-review]" >
			[<a href="" onclick="return false;" class="click-to-copy"><small>Copy to clipboard</small></a>]
		</div>
		<?php
	} //ratings_page()
	
	/**
	 * Use old share style
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	 public function your_feedback_thank_you_page() {

		$options 	= get_option( $this->ultimate_reviews_rocket . '_options' );

		if ( ! empty( $options['your-feedback-thank-you-page'] ) ) {
			$option = $options['your-feedback-thank-you-page'];
		}else{$option =  null;}
			$urr_ratings_page_id = $this->urr_get_id_by_slug('thank-you');			
			$pages = get_pages();
			
		$options = get_option( $this->ultimate_reviews_rocket . '_options' );
		if(!empty($options['urr_feedback_thank_page'])){ $urr_ratings_page_id = $options['urr_feedback_thank_page']; }
		?>
		
		<div class='urr-pages-wrap'>			
			<select id="<?php echo $this->ultimate_reviews_rocket; ?>_options[urr_feedback_thank_page]" name="<?php echo $this->ultimate_reviews_rocket; ?>_options[urr_feedback_thank_page]">
			
			<option disabled="disabled" value=""><?php echo esc_attr( __( 'Select a page' ) ); ?></option> 
			<?php
			foreach ( $pages as $page ) {
					$pagekey = '<option value="' . $page->ID . '" ';
					$pagekey .= ( $page->ID == $urr_ratings_page_id ) ? 'selected="selected"' : '';
					$pagekey .= '>';
					$pagekey .= $page->post_title;
					$pagekey .= '</option>';
					echo $pagekey;
			}
			?>
			</select>
			<a href="<?php echo get_permalink($urr_ratings_page_id); ?>" target="_blank"><small>View Page</small></a>
			</div>

			<div class='urr-pages-wrap'>
				<input type="text" id="<?php echo $this->ultimate_reviews_rocket; ?>_options[your-feedback-thank-you-page]" name="<?php echo $this->ultimate_reviews_rocket; ?>_options[your-feedback-thank-you-page]" readonly value="[urr-feedback-thank-you]" >
				[<a href="" onclick="return false;" class="click-to-copy"><small>Copy to clipboard</small></a>]
			</div>
		<?php
	} //your_feedback_thank_you_page()	
	
	/**
	 * Use old share style
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function social_media_reviews_page() {

		$options 	= get_option( $this->ultimate_reviews_rocket . '_options' );

		if ( ! empty( $options['social-media-reviews-page'] ) ) {
			$option = $options['social-media-reviews-page'];
		}else{$option =  null;}
			$urr_ratings_page_id = $this->urr_get_id_by_slug('social-media-reviews');			
			$pages = get_pages();
			$options = get_option( $this->ultimate_reviews_rocket . '_options' );
		if(!empty($options['urr_social_media_page'])){ $urr_ratings_page_id = $options['urr_social_media_page']; }

		?><div class='urr-pages-wrap'>		
			<select id="<?php echo $this->ultimate_reviews_rocket; ?>_options[urr_social_media_page]" name="<?php echo $this->ultimate_reviews_rocket; ?>_options[urr_social_media_page]"> 
				<option disabled="disabled" value=""><?php echo esc_attr( __( 'Select a page' ) ); ?></option> 
				<?php
				foreach ( $pages as $page ) {
					$pagekey = '<option value="' . $page->ID . '" ';
					$pagekey .= ( $page->ID == $urr_ratings_page_id ) ? 'selected="selected"' : '';
					$pagekey .= '>';
					$pagekey .= $page->post_title;
					$pagekey .= '</option>';
					echo $pagekey;
				}
				?>
			</select>
			<a href="<?php echo get_permalink($urr_ratings_page_id); ?>" target="_blank"><small>View Page</small></a>
		</div>
		<div class='urr-pages-wrap'>
			<input type="text" id="<?php echo $this->ultimate_reviews_rocket; ?>_options[social-media-reviews-page]" name="<?php echo $this->ultimate_reviews_rocket; ?>_options[social-media-reviews-page]" readonly value="[urr-social-media-reviews]" >
			[<a href="" onclick="return false;" class="click-to-copy"><small>Copy to clipboard</small></a>]
		</div>
		<?php
	} //social_account_link()	
	
	/**
	 * Use old share style
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function enable_a_feedback_form() {
		$options 	= get_option( $this->ultimate_reviews_rocket . '_options' );

		if ( ! empty( $options['enable-a-feedback-form'] ) ) {
			$option = $options['enable-a-feedback-form'];
		}else{$option =  null;}
		?>
		<input type="checkbox" id="<?php echo $this->ultimate_reviews_rocket; ?>_options[enable-a-feedback-form]" name="<?php echo $this->ultimate_reviews_rocket; ?>_options[enable-a-feedback-form]" value="1" <?php checked('1', $option); ?> ><label for="<?php echo $this->ultimate_reviews_rocket; ?>_options[enable-a-feedback-form]"><strong>Enable a feedback form on the Social Media Reviews page</strong></label>
		<?php
	} //enable_a_feedback_form()
	
	// Get page id by slug.
	function urr_get_id_by_slug($page_slug) {
		$page = get_page_by_path($page_slug);
		if ($page) {
			return $page->ID;
		} else {    
			return null;
		}   
	}
}
