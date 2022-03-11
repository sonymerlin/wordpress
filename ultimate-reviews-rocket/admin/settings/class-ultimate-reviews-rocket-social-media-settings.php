<?php

/**
 * Admin Part of Plugin, dashboard and options.
 *
 * @package    ultimate_reviews_rocket
 * @subpackage ultimate_reviews_rocket/admin
 */
class ultimate_reviews_rocket_Social_Media_Settings extends ultimate_reviews_rocket_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0 
	 * @access   private
	 * @var      string    $ultimate_reviews_rocket    The ID of this plugin.
	 */
	private $ultimate_reviews_rocket;
	private $inc= 0;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $ultimate_reviews_rocket       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $ultimate_reviews_rocket ) {

		$this->label = __( 'Enable/Disable', 'ultimate_reviews_rocket' );
		$this->ultimate_reviews_rocket = $ultimate_reviews_rocket.'_social_media';
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
			array( $this, 'validate_options' )
		);
		
		// add_settings_section( $id, $title, $callback, $menu_slug );
		add_settings_section(
			$this->ultimate_reviews_rocket . '-options', // section
			apply_filters( $this->ultimate_reviews_rocket . '-display-section-title', __( '', $this->ultimate_reviews_rocket ) ),
			array( $this, 'display_options_section' ),
			$this->ultimate_reviews_rocket . '-social-media'
		);

		add_settings_field(
			'section-heading',
			apply_filters( $this->ultimate_reviews_rocket . '-section-heading', __( 'Section Heading:', $this->ultimate_reviews_rocket ) ),
			array( $this, 'section_heading' ),
			$this->ultimate_reviews_rocket . '-social-media',
			$this->ultimate_reviews_rocket . '-options'
		);


		add_settings_field(
			'section-social-media-heading',
			apply_filters( $this->ultimate_reviews_rocket . '-section-social-media-heading', __( 'Social Media Links:', $this->ultimate_reviews_rocket ) ),
			array( $this, 'section_social_media_heading' ),
			$this->ultimate_reviews_rocket . '-social-media',
			$this->ultimate_reviews_rocket . '-options'
		);
		
		add_settings_field(
			'facebook',
			apply_filters( $this->ultimate_reviews_rocket . '-facebook', __( 'Facebook:', $this->ultimate_reviews_rocket ) ),
			array( $this, 'Facebook' ),
			$this->ultimate_reviews_rocket . '-social-media',
			$this->ultimate_reviews_rocket . '-options'
		);
		
		add_settings_field(
			'google',
			apply_filters( $this->ultimate_reviews_rocket . '-google', __( 'Google+:', $this->ultimate_reviews_rocket ) ),
			array( $this, 'Google' ),
			$this->ultimate_reviews_rocket . '-social-media',
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

		echo '<p>' . esc_attr($params['title']) . '</p>';

	} // display_options_section()


	/**
	 * Disable Author Box
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function section_heading() {
		$options 	= get_option( $this->ultimate_reviews_rocket . '_options' );
		if ( ! empty( $options['section-heading'] ) ) {
			$option  = $options['section-heading'];
		}else {
			$option = 'Please take a moment to give us a review on at least one of these social websites. Remember to include what you liked best about our service:';  
		}
		?><textarea id="<?php echo $this->ultimate_reviews_rocket; ?>_options[section-heading]" name="<?php echo $this->ultimate_reviews_rocket; ?>_options[section-heading]"  ><?php echo esc_attr($option);?> </textarea>
		<?php
	} // section_heading()

	/**
	 * Display the Social Media Heading
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function section_social_media_heading() {
		$options 	= get_option( $this->ultimate_reviews_rocket . '_options' );		
		
	} // section_heading()

	/**
	 * Disable Comments Box
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function Facebook() {

		$options 	= get_option( $this->ultimate_reviews_rocket . '_options' );
		if(!empty($options)){
			if ( ! empty( $options['facebook'] ) ) {
				$option = $options['facebook'];
			} else {
				$option = null;
			}
		} else {
			$option = "facebook.com";
		}
		?>
		<label for="<?php echo $this->ultimate_reviews_rocket; ?>_options[facebook]"></label><input type="text" id="<?php echo $this->ultimate_reviews_rocket; ?>_options[facebook]" name="<?php echo $this->ultimate_reviews_rocket; ?>_options[facebook]" value="<?php echo esc_url($option);?>" >
		<?php
	} //Facebook()

	/**
	 * Disable Comments Box
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function Google() {

		$options 	= get_option( $this->ultimate_reviews_rocket . '_options' );

		if(!empty($options)){
			if ( ! empty( $options['google'] ) ) {
				$option = $options['google'];
			} else {
				$option = null;
			}
		} else {
			$option = "google.com";
		}

		?>
		<label for="<?php echo $this->ultimate_reviews_rocket; ?>_options[google]"></label><input type="text" id="<?php echo $this->ultimate_reviews_rocket; ?>_options[google]" name="<?php echo $this->ultimate_reviews_rocket; ?>_options[google]" value="<?php echo esc_url($option);?>">
		<?php
	} //google()

	/**
	 * Returns an array of options names, fields types, and default values
	 *
	 * @return 		array 			An array of options
	 */
	public static function get_options_list() {

		$options = array();
		$options[] = array( 'section-heading', 'text', '' );
		$options[] = array( 'facebook', 'text', '' );
		$options[] = array( 'google', 'text', '' );		

		return $options;

	} // get_options_list()
	


} 
