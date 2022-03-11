<?php
global $wpdb;
global $urr_search_results;

/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    ultimate_reviews_rocket
 * @subpackage ultimate_reviews_rocket/admin/partials
 */
?>
<?php
// Get page id by slug.
function urr_get_id_by_slug($page_slug) {
	$page = get_page_by_path($page_slug);
		if ($page) {
			return $page->ID;
		} else {    
	return null;
	}   
}
flush_rewrite_rules();
?>
<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<?php		
		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'general';	
		$this->ultimate_reviews_rocket_render_tabs(); 
	?>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2 urr-main-wrap">
			<div id="postbox-container-2" class="postbox-container" >
				<?php 
				// If no tab or general
				switch ($tab) {
					case 'feedback_loop': ?>
						<form method="post" action="options.php">
							<div id="normal-sortables" class="meta-box-sortables ui-sortable">
								<div id="itsec_get_started" class="postbox " >
									<div class="inside">
										<?php
											settings_fields( 'ultimate_reviews_rocket_enable_options' );

											do_settings_sections( 'ultimate_reviews_rocket_enable-enable' );
											
											submit_button( 'Save Settings' );
										?>
										<div class="clear"></div>
									</div>
								</div>
							</div>
						</form>
					<?php
						break;
					case 'premium': ?>
				
					<?php
						break;
					default: ?>
						<div id="top-sortables" class="meta-box-sortables ui-sortable">
							<div id="itsec_self_protect" class="postbox ">
								
								<div class="inside">
									<p>Thanks for installing Ultimate Reviews Rocket</p>
									<p> Have great reviews posted online and send the rest through a feedback loop back to you. Good reviews from customers are sent to social media and bad reviews are held back. You set the threshold.</p>
								</div>
							</div>
						</div>
						<form method="post" action="options.php">				
							<div id="normal-sortables" class="meta-box-sortables ui-sortable">
								<div id="itsec_get_started" class="postbox ">
									<div class="inside">
										<?php $tick_count1 = 0;
										$option_setting = get_option('ultimate_reviews_rocket_setting_options');
										$option_feedback_loop = get_option('ultimate_reviews_rocket_enable_options');
										$option_social_media = get_option('ultimate_reviews_rocket_social_media_options');
										if( !empty($option_setting['ratings-page-heading'])) {
											$settings_true = '<i class="fa fa-check-circle fa-2x" aria-hidden="true"></i> ';
											$tick_count1 = 1;
										} else {
											$settings_true = '<i class="fa fa-times-circle fa-2x" aria-hidden="true"></i> ';
											$tick_count1 = 0;
										} 

										// Your Feedback Page
										$tick_count2 = 0;
										if( !empty($option_feedback_loop['feedback-loop-page-heading']) && !empty($option_feedback_loop['feedback-form-submissions']) && !empty($option_feedback_loop['thank-you-page-text-bad']) ) {
											$feedback_true = '<i class="fa fa-check-circle fa-2x" aria-hidden="true"></i> ';
											$tick_count2 = 1;
										} else {
											$feedback_true = '<i class="fa fa-times-circle fa-2x" aria-hidden="true"></i> ';
											$tick_count2 = 0;
										}

										// Social Media Reviews Page
										$tick_count3 = 0;
										if( !empty($option_social_media['facebook']) || !empty($option_social_media['google']) ) {
											$good_rating_true = '<i class="fa fa-check-circle fa-2x" aria-hidden="true"></i> ';
											$tick_count3 = 1;
										} else {
											$good_rating_true = '<i class="fa fa-times-circle fa-2x" aria-hidden="true"></i> ';
											$tick_count3 = 0;
										} ?>

										<ul class="urr-gen-list">

											<li> <?php echo $feedback_true; ?> <span> Configure the <a href="<?php get_admin_url(null, 'options-general.php');?>?page=ultimate_reviews_rocket&tab=feedback_loop"><strong>Feedback Loop</strong></a> settings or click <a href="<?php get_admin_url(null, 'options-general.php');?>?page=ultimate_reviews_rocket&tab=feedback_loop"><strong>here</strong></a>.</span></li>

											<li> <?php echo $good_rating_true; ?> <span> Configure the <a href="<?php get_admin_url(null, 'options-general.php');?>?page=ultimate_reviews_rocket&tab=social_media"><strong>Social Media</strong></a> settings or click <a href="<?php get_admin_url(null, 'options-general.php');?>?page=ultimate_reviews_rocket&tab=social_media"><strong>here</strong></a>.</span></li>

											<li> <?php echo $settings_true; ?> <span> Configure the <a href="<?php get_admin_url(null, 'options-general.php');?>?page=ultimate_reviews_rocket&tab=settings"><strong>Settings</strong></a> or click <a href="<?php get_admin_url(null, 'options-general.php');?>?page=ultimate_reviews_rocket&tab=settings"><strong>here</strong></a>.</span></li>				
											
											<li> <?php if($tick_count3 == 1 && $tick_count1 == 1 && $tick_count2 == 1) {
												$urr_ratings_page_id = urr_get_id_by_slug('rate-and-review-us');
												echo '<i class="fa fa-check-circle fa-2x" aria-hidden="true"></i>';
												echo "<span><strong> You are done! </strong>Here is the <a href=".get_permalink($urr_ratings_page_id)." target='_blank'><strong>page</strong></a> to send people to.!</span>";
											} else {
												echo '<i class="fa fa-times-circle fa-2x" aria-hidden="true"></i>';
												echo "<span> Not complete.</span>";
											} ?>
											</li>

										</ul>
									<div class="clear"></div>
									</div>
								</div>
							</div>
						</form>
					<?php
						break;
					case 'social_media': ?>
						<form method="post" action="options.php">
							<div id="normal-sortables" class="meta-box-sortables ui-sortable">
								<div id="itsec_get_started" class="postbox ">
									<div class="inside">

										<?php
											settings_fields( 'ultimate_reviews_rocket_social_media_options' );
											do_settings_sections( 'ultimate_reviews_rocket_social_media-social-media' );
											submit_button( 'Save Settings' );
										?>

										<div class="clear"></div>
									</div>
								</div>
							</div>
						</form>
					<?php
						break;
						case 'settings': ?>
						<form method="post" action="options.php">
							<div id="normal-sortables" class="meta-box-sortables ui-sortable">
								<div id="itsec_get_started" class="postbox ">
									<div class="inside">

										<?php
											settings_fields( 'ultimate_reviews_rocket_setting_options' );
											do_settings_sections( 'ultimate_reviews_rocket_setting-setting' );
											submit_button( 'Save Settings' );
										?>

										<div class="clear"></div>
									</div>
								</div>
							</div>
						</form>
					<?php
						break;
						case 'feedback_submissions': ?>
							<div id="normal-sortables" class="meta-box-sortables ui-sortable">
								<div id="itsec_get_started" class="postbox">
									<div>
										<?php 
											$urr_table_name = $wpdb->prefix."urr_form_submissions";
											
											$urr_select_bad_entries = "SELECT * FROM ".$urr_table_name." WHERE urr_review_type ='Bad' ORDER BY urr_rating_value DESC";
											$urr_results_bad = $wpdb->get_results($urr_select_bad_entries);
											$urr_select_good_entries = "SELECT * FROM ".$urr_table_name." WHERE urr_review_type ='Good' ORDER BY urr_rating_value DESC";
											$urr_results_good = $wpdb->get_results($urr_select_good_entries);
											
											if(!empty($urr_results_good) && !empty($urr_results_bad)) {
												$urr_search_results = array_merge($urr_results_good, $urr_results_bad); 
											} elseif(!empty($urr_results_good)) {
												$urr_search_results = $urr_results_good;
											} else {
												$urr_search_results = $urr_results_bad;
											}
										
										?>		
		
										<table class="wp-list-table widefat fixed striped urr_form_entries" id="urr-form-entries">
											
											<thead>
												<tr>
												<th><strong><?php _e('Name', 'ultimate-reviews-rocket'); ?></strong></th>
												<th><strong><?php _e('Phone', 'ultimate-reviews-rocket'); ?></strong></th>
												<th><strong><?php _e('Email', 'ultimate-reviews-rocket'); ?></strong></th>
												<th><strong><?php _e('Subject', 'ultimate-reviews-rocket'); ?></strong></th>
												<th><strong><?php _e('Message', 'ultimate-reviews-rocket'); ?></strong></th>
												<th><strong><?php _e('Review', 'ultimate-reviews-rocket'); ?></strong></th>
												<th><strong><?php _e('Rating', 'ultimate-reviews-rocket'); ?></strong></th>
												<th><strong><?php _e('Date', 'ultimate-reviews-rocket'); ?></strong></th>
												
											</tr></thead>
											<?php
											$numofstar  = array();
											if(!empty($tempe))
											$urr_search_results =$tempe;
											
											if(!empty($urr_search_results)) {
											foreach( $urr_search_results as $urr_result )  {
												for($starcount = 0 ;$urr_result->urr_rating_value > $starcount;$starcount++){ $numofstar[] ="<span style='font-size: 20px;color:#FFD700;'>★</span>";}
												?>
												<tr>
													<td><?php _e($urr_result->urr_username, 'ultimate-reviews-rocket'); ?></td>
													<td><?php _e($urr_result->urr_phone_no, 'ultimate-reviews-rocket'); ?></td>
													<td><?php _e($urr_result->urr_email, 'ultimate-reviews-rocket'); ?></td>
													<td><?php _e($urr_result->urr_subject, 'ultimate-reviews-rocket'); ?></td>
													<td><?php echo stripslashes(nl2br($urr_result->urr_comments)); ?></td>
													<td><?php _e($urr_result->urr_review_type, 'ultimate-reviews-rocket'); ?></td>
													<td><?php foreach($numofstar as $numofstar){ print($numofstar); } ?></td>
													<td><?php 
													$date = strtotime($urr_result->urr_form_submission_time);
													_e(date("m/d/Y", $date), 'ultimate-reviews-rocket');
													echo " at ";
													_e(date("h:i a", $date), 'ultimate-reviews-rocket'); ?></td>			        
												</tr>
												<?php
												unset($numofstar);
												}
											}  ?>

											<tfoot>
												<tr>
													<th><strong><?php _e('Name', 'ultimate-reviews-rocket'); ?></strong></th>
													<th><strong><?php _e('Phone', 'ultimate-reviews-rocket'); ?></strong></th>
													<th><strong><?php _e('Email', 'ultimate-reviews-rocket'); ?></strong></th>
													<th><strong><?php _e('Subject', 'ultimate-reviews-rocket'); ?></strong></th>
													<th><strong><?php _e('Message', 'ultimate-reviews-rocket'); ?></strong></th>
													<th><strong><?php _e('Review', 'ultimate-reviews-rocket'); ?></strong></th>
													<th><strong><?php _e('Rating', 'ultimate-reviews-rocket'); ?></strong></th>
													<th><strong><?php _e('Date', 'ultimate-reviews-rocket'); ?></strong></th>					
													
												</tr>
											</tfoot>
										
										</table>
	
										<div class="clear"></div>
									</div>
								</div>
							</div>

					<?php
						break;
				} ?>

			</div>
			<?php (isset($_GET['tab']))? $wsx_tab = $_GET['tab'] : $wsx_tab = null;  ?>
			<div id="postbox-container-1" class="postbox-container">
					
				<div class="left-price-block gray-bg shadow">
					<div class="our-team-work">
						<h4>Put <span>Our Team</span></h4>
						<h3>To Work For You</h3>
						<p><a href="https://www.webstix.com/contact-us" class="quote-btn" target="_blank">Click Here</a></p>
						<p></p>
					</div>
				</div>
				
				<div class="left-price-block gray-bg shadow">
					<h2>Need Website <span>Maintenance?</span></h2>
					<div class="text-center"><a href="https://www.maintenanceblocks.com/webstix/task.php?opt=newuser" target="_blank" class="quote-btn">Free Quote</a></div>&nbsp;
				</div>

			</div>							
			
		</div>
	</div>
</div>



