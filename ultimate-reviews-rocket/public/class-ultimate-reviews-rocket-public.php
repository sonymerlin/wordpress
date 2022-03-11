<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    ultimate_reviews_rocket
 * @subpackage ultimate_reviews_rocket/public
 * @author     Webstix <testing@webstix.com>
 */
class ultimate_reviews_rocket_Public {

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

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $customeKey;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $ultimate_reviews_rocket       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $ultimate_reviews_rocket, $version ) {

		$this->ultimate_reviews_rocket = $ultimate_reviews_rocket;
		$this->version = $version;
		if(is_array(get_option('ultimate_reviews_rocket_setting_options')) && is_array(get_option('ultimate_reviews_rocket_enable_options')) && is_array(get_option('ultimate_reviews_rocket_social_media_options'))){
			$this->customeKey = array_merge(get_option('ultimate_reviews_rocket_setting_options'),get_option('ultimate_reviews_rocket_enable_options'),get_option('ultimate_reviews_rocket_social_media_options'));
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		$options = (get_option('ultimate_reviews_rocket_options') ? get_option('ultimate_reviews_rocket_options') : false);
		// Post Type
		$posttype = array();
		if(isset($options["post-type"]) && $options["post-type"] != '') {
			$posttype = $options["post-type"];
		}

		wp_enqueue_style( 'awesome', plugin_dir_url( __FILE__ ) . 'assets/css/font-awesome.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'urrpublic', plugin_dir_url( __FILE__ ) . 'assets/css/ultimate-reviews-rocket-public.css', array(), $this->version, 'all' );
		
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $post;

		$options = (get_option('ultimate_reviews_rocket_options') ? get_option('ultimate_reviews_rocket_options') : false);
		//Post Type
		$posttype = array();
		if(isset($options["post-type"]) && $options["post-type"] != '') {
			$posttype = $options["post-type"];
		}
		
		wp_enqueue_script( $this->ultimate_reviews_rocket, plugin_dir_url( __FILE__ ) . 'assets/js/urr-rating.js', array( 'jquery'));
	}

	/**
	 * Registers all shortcodes at once
	 *
	 * @return [type] [description]
	 */
	public function register_shortcodes() {
		add_shortcode( 'urr-review', array( $this, 'rateUs' ) );
		add_shortcode( 'urr-feedback', array( $this, 'feedBack' ) );
		add_shortcode( 'urr-feedback-thank-you', array( $this, 'feedbackThankYou' ) );
		add_shortcode( 'urr-social-media-reviews', array( $this, 'socialMediaReviews' ) );

	} // register_shortcodes()


   /**
	* Registers all shortcodes at once
	*
	* @return [type] [description]
	*/	
	public function rateUs() {
		$error =null;
		if(empty(get_option('ultimate_reviews_rocket_setting_options'))){
			$error =  "<span style='color:red'>You must configure the setting option in Ultimate Review Rocket Plugin.</span></br>";
			}
		elseif(empty(get_option('ultimate_reviews_rocket_enable_options'))){
			$error =  "<span style='color:red'>You must configure the feedback loop setting in Ultimate Review Rocket Plugin.</span></br>";
		}
		elseif(empty(get_option('ultimate_reviews_rocket_social_media_options'))){
			$error =  "<span style='color:red'>You must configure the social media setting in Ultimate Review Rocket Plugin.</span></br>";
			
		}
		$content  ='<div class="rating-widget-wrap">';
		$content .= '<section class="rating-widget home-page">';
		$content .= '<form class="rating-widget" method="post" action="options.php">';
		$content .= $error; 
		if($this->customeKey['ratings-page-heading'] !="") {
			$content .= '<label>'.$this->customeKey['ratings-page-heading'].':</label>';
		} else {
			
			$content .= "Please Rate Our Service";
		}
		$content .= '<input name="rating_value" value="3" id="rating_set_value" type="hidden" />';
		$content .= '<input type="text" value="" id="ratingme" name="ratingme" style="display: none;" /><br>';
		$content .= '</form>';
		$content .= '</section>';
		$content .= '</div>';

       return $content;
	}
	
   /**
	* Registers all shortcodes at once
	*
	* @return [type] [description]
	*/
	public function feedBack($atts) {
		extract( shortcode_atts( array("email" => get_bloginfo( 'admin_email' ),"subject" => "","label_name" => "Name","label_phone" => "Phone","label_email" => "Email","label_subject" => "Subject","label_message" => "Comments","label_submit" => "Submit"), $atts ) );
			if($_REQUEST['urr_rating_value'] <= 2) {
				if($this->customeKey['your-feedback-thank-you-page']){
					$email_form = "<p><strong>".$this->customeKey['feedback-loop-page-heading']."</strong></p>";
				} else {
					$email_form = "<p><strong>".strip_tags("We're sorry to hear that. What can we do to get a higher rating?")."</strong></p>";
				}
				$email_form .= "<p><strong>".$this->customeKey['special-offers-section']."</strong></p>";
				$email_form .= $this->feedback_form('bad');
			}
	return $email_form;
	}
	
	/**
	* Registers all shortcodes at once
	*
	* @return [type] [description]
	*/
	public function feedbackThankYou() {
		global $wpdb;

		if(!empty($_POST['urr_user_name']) && !empty($_POST['urr_email'])){

			$_POST = stripslashes_deep($_POST);
			if (empty($_POST["urr_user_name"])) {
				$nameErr = '<br><span class="urr-field-error">Please enter a valid name.</span>';
			}
			if (empty($_POST["urr_email"])) {
				$emailErr = '<br><span class="urr-field-error">Please enter a valid email address.</span>';
			} else {
				$email = sanitize_email($_POST["urr_email"]);
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$emailErr = "Email format is invalid."; 
				}
			}
		
			//database entry
			$urr_table_name = $wpdb->prefix . "urr_form_submissions";			
			$_POST['urr_user_name'] = sanitize_text_field($_POST['urr_user_name']);
			$_POST['urr_phone'] = sanitize_text_field($_POST['urr_phone']);
			$_POST['urr_email'] = sanitize_email($_POST['urr_email']);
			$_POST['urr_subject'] = preg_replace("/[^A-Za-z0-9 '\-]/", '',stripslashes_deep($_POST['urr_subject']));
			$_POST['urr_message'] = sanitize_textarea_field($_POST['urr_message']);
			($_POST['urr_rating_value'] >= 3)? $review_type = 'Good' : $review_type = 'Bad';
			$urr_data = array(
				'urr_username'				=> sanitize_text_field($_POST['urr_user_name']),
				'urr_phone_no'				=> sanitize_text_field($_POST['urr_phone']),
				'urr_email'					=> sanitize_email($_POST['urr_email']),
				'urr_subject'   			=> sanitize_text_field($_POST['urr_subject']), 		
				'urr_comments'				=> sanitize_textarea_field($_POST['urr_message']),
				'urr_form_submission_time'  => sanitize_text_field($_POST['urr_timestamp']),
				'urr_user_ip'  				=> sanitize_text_field($_POST['urr_user_ip']),
				'urr_review_type'  			=> $review_type,
				'urr_rating_value'		 => $_POST['urr_rating_value'],
			);
			$urr_format = array('%s','%s','%s','%s','%s','%s','%s');
			$wpdb->insert( $urr_table_name, $urr_data, $urr_format );
			//database entry
			
	    if ( sanitize_email($_POST['urr_email']) != "" && sanitize_text_field($_POST["urr_user_name"]) != "") {
	        $email_subject = "Feedback Submission From ". sanitize_text_field($urr_data['urr_username']);
			$date = strtotime($urr_data['urr_form_submission_time']);
			$date_submitted = date("m/d/Y", $date);
		    $time_submitted =  " at ". date("h:i a", $date);
			if($urr_data['urr_username'] != "" ) {				
				$email_message = "Name: ". preg_replace("/[^A-Za-z0-9 '\-]/", '',sanitize_text_field($urr_data['urr_username'])) . "<br/><br/>";
			}
			if($urr_data['urr_phone_no'] != "" ) {				
				$email_message .= "Phone: ". sanitize_text_field($urr_data['urr_phone_no']) . "<br/><br/>";
			}
			if($urr_data['urr_email'] != "" ) {				
				$email_message .= "Email: ". sanitize_email($urr_data['urr_email']) . "<br/><br/>";
			}
			if($urr_data['urr_subject'] != "" ) {
				$email_message .= "Subject: ". preg_replace("/[^A-Za-z0-9 '\-]/", '',sanitize_text_field($urr_data['urr_subject'])) . "<br/><br/>";
			}

			if($urr_data['urr_comments'] != "" ) {				
				$email_message .= "Comments: ".  nl2br($urr_data['urr_comments']) . "<br/><br/>";
			}	   
			for($starcount = 0 ;$urr_data['urr_review_type'] > $starcount;$starcount++){ 
				$numofstar .="<span style='font-size: 30px;color:#FFD700;'>★</span>";
			}
			($_POST['urr_review_type'] == "good")? $review_type = 'Good' : $review_type = 'Bad';
			$email_message .= "Review: " . $review_type . "<br/><br/>";
			$email_message .= "Rating: ".$urr_data['urr_rating_value'] . "<br/><br/>";
			$email_message .= "Date: ".$date_submitted.$time_submitted. "<br/><br/>";
			$email_message .= "IP Address: " . $urr_data['urr_user_ip'] . "<br/><br/>";
			if(!empty($this->customeKey['special-offers-section'])) {
				$email_message .= "Special Offers: " .$this->customeKey['special-offers-section'];
			}
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
			$headers .= "From: ".$this->customeKey['feedback-form-submissions']."\r\n";
			if(!empty($this->customeKey['feedback-form-submissions'])) {
				$urr_form_admin_email = $this->customeKey['feedback-form-submissions'];
			} else {
				if(get_option('admin_email')) {
					$urr_form_admin_email = get_option('admin_email');
				} else {
					$urr_form_admin_email = get_option('new_admin_email');
				}
			}
			$to = $urr_form_admin_email;
			mail( $to, $email_subject, $email_message, $headers );
			
			//$result = $success;
			$sent = true;
			if($sent == true) {
				$post = $this->customeKey['thank-you-page-text-bad']; 
			}

		}
		if ( !empty($result) ) {
			$info = '<div class="info">' . $result . '</div>';
		}		
		if($_REQUEST['urr_review_type'] == 'good'){
			if(!empty($this->customeKey['thank-you-page-text-good'])) {
				$good_content = "<p>".$this->customeKey['thank-you-page-text-good']."</p>";
				return $good_content;
			} else {
				$good_content = "Thank you for submitting a review.";
				return $good_content;
			}
		}
		elseif($_REQUEST['urr_review_type'] == 'bad'){
			if(!empty($this->customeKey['thank-you-page-text-bad'])) {
				$bad_content = "<p>".$this->customeKey['thank-you-page-text-bad']."</p>";
				return $bad_content;
			} else {
				$bad_content = "Thank you for contacting us. Someone will contact you shortly";
			return $bad_content;
			}
		}
		
		return $content;
	}
	}
   
   /**
	* Registers all shortcodes at once
	*
	* @return [type] [description]
	*/
	public function socialMediaReviews() {
		global $wpdb;
		if(!empty($this->customeKey['section-heading'])) {
			$urr_good_ratings_heading = $this->customeKey['section-heading'];
		} else {
			$urr_good_ratings_heading = "Please take a moment to give us a review on at least one of these social websites. Remember to include what you liked best about our service:";
		}
		if(!empty($this->customeKey['facebook']) || !empty($this->customeKey['google'])) {
				$content = "<h3>Great!</h3>";
				$content .= "<p>".$urr_good_ratings_heading."</p>";
		}
		if(empty($this->customeKey['facebook']) && empty($this->customeKey['google'])) {
				$content = "<p>Please add any social links on the admin side. </p>";
		}
		$urr_good_rating_social_links = get_option('urr_social_options');
		
		$content .="<ul id='urr-public-social-icons'>";
		if(!empty(trim($this->customeKey['facebook']))) {
			$content .='<li><a href="'.esc_url($this->customeKey['facebook']).'" target="_blank" title="Click here to submit your reviews on Facebook" target="_blank"><i class="fa fa-facebook-square fa-2x"></i></a></li>';
		}
		if(!empty(trim($this->customeKey['google']))) {
			$content .='<li><a href="'.esc_url($this->customeKey['google']).'" target="_blank" title="Click here to submit your reviews on Google+" target="_blank" class="fa fa-google-plus-square fa-2x"></a></li>';
		}
		
		$wsx_is_http = array();
		$inc=0;
				
		if ( ! empty( $urr_good_rating_social_links ) ) {
			foreach($urr_good_rating_social_links['urr_social_name'] as $optKey=>$optVal) {
				if($urr_good_rating_social_links['urr_social_name'][$optKey] != "" && $urr_good_rating_social_links['urr_social_url'][$optKey] != "" && $urr_good_rating_social_links['urr_social_icon'][$optKey] != "" ) {
					$content .= "<li><a href='".$urr_good_rating_social_links['urr_social_url'][$optKey]."' title='Click here to submit your reviews on ".$urr_good_rating_social_links['urr_social_name'][$optKey]."' target='_blank'><img style='width:32px;height:27px;' src='".$urr_good_rating_social_links['urr_social_icon'][$optKey]."' /></a></li>";
			}
		}
		$content .="</ul>";
		}
		if($this->customeKey['enable-a-feedback-form']) {
			$content .= "<strong style='display: block; margin-bottom: 20px;'>Or leave a review here...</strong>";
			$content .= $this->feedback_form('good');
		}
		return $content;
	}
	
	public function feedback_form( $review_type  ) {
		$action_url = site_url()."/thank-you/ ";
		(isset($_REQUEST['urr_rating_value']))? $urr_rating_value = $_REQUEST['urr_rating_value']: $urr_rating_value = null;
		$feedback_form = '<div id="urr-social-media-ratings-form">
		
		<p style="margin: 20px 0;">Fields marked with an asterisk (<span style="color: #ff0000;">*</span>) are required.</p>
		<form  class="urr-bad-ratings-form"  id="urr-social-media-ratings-form" method="post" action='.$action_url.'>
							<div class="urr-fields">
								<label for="urr_user_name"> Name:<span class="urr-label-error" style="color: #ff0000;">*</span></label><br>
								<input type="text" name="urr_user_name" id="urr_user_name" size="50" maxlength="50" value="" required />
							</div>
							<div class="urr-fields">
								<label for="urr_phone"> Phone: </label><br>
								<input type="tel" name="urr_phone" id="urr_phone" size="50" maxlength="50" value="" />
							</div>
							<div class="urr-fields">
								<label for="urr_email"> Email: <span class="urr-label-error" style="color: #ff0000;">*</span></label><br>
								<input type="email" name="urr_email" id="urr_email" size="50" maxlength="50" value="" required />
								
							</div>
							<div class="urr-fields">
								<label for="urr_subject"> Subject: </label><br>
								<input type="text" name="urr_subject" id="urr_subject" size="50" maxlength="50" value="" />
							</div>
							<div class="urr-fields">
								<label for="urr_message"> Comments: </label><br>
								<textarea name="urr_message" id="urr_message" cols="50" rows="15"></textarea>
							</div>
							<div>
								<input type="hidden" name="urr_timestamp" id="urr_timestamp" value="'. date("Y-m-d H:i:s").'" />
							</div>
							<div>
								<input type="hidden" name="urr_user_ip" id="urr_user_ip" value="'. esc_attr($this->urr_get_the_ip()).'" /> 
								<input type="hidden" name="urr_rating_value" id="urr_rating_value" value="'.esc_attr($urr_rating_value).'" />
								<input type="hidden" name="urr_review_type" id="urr_review_type" value="'. esc_attr($review_type).'" />
							</div>
							<div class="urr-fields urr-submit">
								<input type="submit" class="button button-primary urr-plugin-admin-submit-btn" value=" Submit " name="send" id="cf_send" />
							</div>
						</form></div>';
		return $feedback_form;	
	}
	
	// Get the network IP of the user who submits the form.
	public function urr_get_the_ip() {
		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			return $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
			return $_SERVER["HTTP_CLIENT_IP"];
		}
		else {
			return $_SERVER["REMOTE_ADDR"];
		}
	}

}
