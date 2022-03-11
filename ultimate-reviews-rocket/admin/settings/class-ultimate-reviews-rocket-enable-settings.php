<?php

/**
 * Admin Part of Plugin, dashboard and options.
 *
 * @package    ultimate_reviews_rocket
 * @subpackage ultimate_reviews_rocket/admin
 */
class ultimate_reviews_rocket_Enable_Settings extends ultimate_reviews_rocket_Admin {

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
		$this->ultimate_reviews_rocket = $ultimate_reviews_rocket.'_enable';
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
			$this->ultimate_reviews_rocket . '-enable'
		);

		add_settings_field(
			'feedback-loop-page-heading',
			apply_filters( $this->ultimate_reviews_rocket . '-feedback-loop-page-heading', __( 'Feedback Loop Page Heading:', $this->ultimate_reviews_rocket ) ),
			array( $this, 'feedback_loop_page_heading' ),
			$this->ultimate_reviews_rocket . '-enable',
			$this->ultimate_reviews_rocket . '-options'
		);
		add_settings_field(
			'special-offers-section',
			apply_filters( $this->ultimate_reviews_rocket . '-special-offers-section', __( 'Special Offers Section', $this->ultimate_reviews_rocket ) ),
			array( $this, 'special_offers_section' ),
			$this->ultimate_reviews_rocket . '-enable',
			$this->ultimate_reviews_rocket . '-options'
		);
		add_settings_field(
			'thank-you-page-text-bad',
			apply_filters( $this->ultimate_reviews_rocket . '-thank-you-page-text-bad', __( 'Thank You Page Text (Bad Reviews):', $this->ultimate_reviews_rocket ) ),
			array( $this, 'thank_you_page_text_bad' ),
			$this->ultimate_reviews_rocket . '-enable',
			$this->ultimate_reviews_rocket . '-options'
		);
		add_settings_field(
			'thank-you-page-text-good',
			apply_filters( $this->ultimate_reviews_rocket . '-thank-you-page-text-good', __( 'Thank You Page Text (Good Reviews):', $this->ultimate_reviews_rocket ) ),
			array( $this, 'thank_you_page_text_good' ),
			$this->ultimate_reviews_rocket . '-enable',
			$this->ultimate_reviews_rocket . '-options'
		);
		add_settings_field(
			'feedback-form-submissions',
			apply_filters( $this->ultimate_reviews_rocket . '-feedback-form-submissions', __( 'Feedback Form Submissions Notification Email:', $this->ultimate_reviews_rocket ) ),
			array( $this, 'feedback_form_submissions' ),
			$this->ultimate_reviews_rocket . '-enable',
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

		echo '<p>' . esc_attr($params['title']). '</p>';

	} // display_options_section()


	/**
	 * Disable Author Box
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function feedback_loop_page_heading() {

		$options 	= get_option( $this->ultimate_reviews_rocket . '_options' );
		$option 	= 0;

		if ( ! empty( $options['feedback-loop-page-heading'] ) ) {
			$option  = $options['feedback-loop-page-heading'];
		}else{
			$option= "We're sorry to hear that. What can we do to get a higher rating?";
		}

		?><textarea id="<?php echo $this->ultimate_reviews_rocket; ?>_options[feedback-loop-page-heading]" name="<?php echo $this->ultimate_reviews_rocket; ?>_options[feedback-loop-page-heading]" rows="4" cols="50"  ><?php echo esc_attr($option);?></textarea>
		  <p class="description">This text appears at the top of the page if people didn't give you a great rating. You can ask them how you can improve.</p>
		<?php
	} // feedback_loop_page_heading()

	/**
	 * Disable TTR Box
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function special_offers_section() {

		$options 	= get_option( $this->ultimate_reviews_rocket . '_options' );
		if ( ! empty( $options['special-offers-section'] ) ) {
			$option = $options['special-offers-section'];
		}else{
			$option= null;
		}

		?>
		<textarea  id="<?php echo $this->ultimate_reviews_rocket; ?>_options[special-offers-section]" name="<?php echo $this->ultimate_reviews_rocket; ?>_options[special-offers-section]" rows="4" cols="50" ><?php echo esc_attr($option);?></textarea>
		<p class="description">This optional message appears at the top of the feedback loop page. You can offer people an entry for a drawing, coupon or whatever you want.</p>
		<?php
	} // special_offers_section()

	/**
	 * Disable Share Box
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function feedback_form_submissions() {

		$options 	= get_option( $this->ultimate_reviews_rocket . '_options' );

		if ( ! empty( $options['feedback-form-submissions'] ) ) {
			$option = $options['feedback-form-submissions'];
		} else{
			 
		}?>
		<input type="email" id="<?php echo $this->ultimate_reviews_rocket; ?>_options[feedback-form-submissions]" name="<?php echo $this->ultimate_reviews_rocket; ?>_options[feedback-form-submissions]" value="<?php if(empty($option)){trim(bloginfo('admin_email'));}else{ echo esc_attr($option);} ?> "/>
		<p class="description">This is where feedback submissions are emailed to.</p>

		<?php
	} //feedback_form_submissions()



	/**
	 * Disable Comments Box
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function thank_you_page_text_bad() {

		$options 	= get_option( $this->ultimate_reviews_rocket . '_options' );
		$option 	= 0;

		if ( ! empty( $options['thank-you-page-text-bad'] ) ) {
			$option = $options['thank-you-page-text-bad'];
		}else{
			$option= 'Thank you for contacting us. Someone will contact you shortly.';
		}

		?>
		<textarea id="<?php echo $this->ultimate_reviews_rocket; ?>_options[thank-you-page-text-bad]" name="<?php echo $this->ultimate_reviews_rocket; ?>_options[thank-you-page-text-bad]" rows="4" cols="50" ><?php print($option);?></textarea>
		<p class="description">After people submit feedback, this is the message they will see on your website for bad reviews.</p>
		<?php
	} // thank_you_page_text_bad()

	/**
	 * Use old share style
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function thank_you_page_text_good() {

		$options 	= get_option( $this->ultimate_reviews_rocket . '_options' );
		$option 	= 0;

		if ( ! empty( $options['thank-you-page-text-good'] ) ) {
			$option = $options['thank-you-page-text-good'];
		}else{
			$option= 'Thank you for submitting the review!';
		}

		?>
		<textarea id="<?php echo $this->ultimate_reviews_rocket; ?>_options[thank-you-page-text-good]" name="<?php echo $this->ultimate_reviews_rocket; ?>_options[thank-you-page-text-good]" rows="4" cols="50" ><?php echo esc_attr($option);?></textarea>
		<p class="description">After people submit feedback, this is the message they will see on your website for good reviews.</p>

		<?php
	} // thank_you_page_text_good()
}
