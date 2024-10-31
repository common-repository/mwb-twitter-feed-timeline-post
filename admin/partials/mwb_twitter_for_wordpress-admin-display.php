<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://makewebbetter.com
 * @since      1.0.0
 *
 * @package     mwb-twitter-for-wordpress
 * @subpackage  mwb-twitter-for-wordpress/admin/partials
 */
$mwb_twitter_image_url = MWB_TWITTER_URL.'admin/images/Twitter.png';

if(!isset($_GET['tab'])){
	$_GET['tab'] = 'default_tab';
}

if(isset($_POST['mwb_twitter_auth_submit']))
{
	if(wp_verify_nonce($_REQUEST['mwb_twitter_nonce_verify'],'mwb-twitter-nonce')){
		
		$mwb_twitter_consumer_app_key = isset($_POST['mwb_twitter_consumer_app_id']) ? sanitize_text_field($_POST['mwb_twitter_consumer_app_id']) : '';
		$mwb_twitter_consumer_app_secret = isset($_POST['mwb_twitter_consumer_app_sec']) ? sanitize_text_field($_POST['mwb_twitter_consumer_app_sec']) : '';
		$mwb_twitter_consumer_access_token = isset($_POST['mwb_twitter_app_access']) ? sanitize_text_field($_POST['mwb_twitter_app_access']) : '';
		$mwb_twitter_consumer_access_token_secret = isset($_POST['mwb_twitter_app_access_sec']) ? sanitize_text_field($_POST['mwb_twitter_app_access_sec']) : '';
		$mwb_twitter_tweet_button = isset($_POST['mwb_twitter_post_tweet']) ? 1 : 0;
		$mwb_twitter_tweet_button_position = isset($_POST['mwb_twitter_tweet_button_position']) ? sanitize_text_field($_POST['mwb_twitter_tweet_button_position']) : '';
		$mwb_twitter_follow_button = isset($_POST['mwb_twitter_follow_button']) ? 1 : 0;
		$mwb_twitter_consumer_account_name = isset($_POST['mwb_twitter_account_name']) ? sanitize_text_field($_POST['mwb_twitter_account_name']) : '';

		$mwb_twitter_credentials = array(
			'mwb_twitter_consumer_appid'=>$mwb_twitter_consumer_app_key,
			'mwb_twitter_consumer_appseceret'=>$mwb_twitter_consumer_app_secret,
			'mwb_twitter_consumer_acess_token'=>$mwb_twitter_consumer_access_token,
			'mwb_twitter_consumer_token_secret'=>$mwb_twitter_consumer_access_token_secret,
			'mwb_twitter_tweet_button_enable' =>$mwb_twitter_tweet_button,
			'mwb_twitter_tweet_button_position'=> $mwb_twitter_tweet_button_position,
			'mwb_twitter_follow_button_enable' => $mwb_twitter_follow_button,
			'mwb_twitter_account_name_text' => $mwb_twitter_consumer_account_name
			);

		update_option('mwb_twitter_consumer_app_details',$mwb_twitter_credentials);
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php _e('Settings Saved Successfully','mwb_twitter_for_wordpress'); ?></p>
		</div>
		<?php
	}
}
if(isset($_POST['mwb_twitter_share_post_submit']))
{
	if(wp_verify_nonce($_REQUEST['mwb_twitter_nonce_share_post_submit'],'mwb_twitter_nonce_share_post')){

		$MwbTwitterSharepostonPublish = isset($_POST['mwb_twitter_share_your_posts']) ? 1 : 0;
		update_option('mwb_twitter_share_post_on_publish',$MwbTwitterSharepostonPublish);
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php _e('Settings Saved Successfully','mwb_twitter_for_wordpress'); ?></p>
		</div>
		<?php
	}
}
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="mwb_twitter_main_wrapper">

	<div class="mwb_twitter_image">
		<img src="<?php echo $mwb_twitter_image_url; ?>">
	</div>
	<div id="mwb_twitter_admin_notices" class="notice notice-success is-dismissible">
		<p><?php _e('Your message Successfully tweet on your twitter','mwb_twitter_for_wordpress'); ?></p>
	</div>
	<div id="mwb_twitter_admin_error_notices" class="notice notice-error is-dismissible"></div>

	<div id="mwb_twitter_profile_notices" class="notice is-dismissible"></div>

	<form action="" method="post">
		<ul class="mwb_twitter_tabs">
			<li class="mwb_twitter_tablink <?php if(isset($_GET['tab']) && ($_GET['tab'] == 'mwb_general_settings' || $_GET['tab'] == 'default_tab')){  echo 'active'; }?>" data-tab="mwb_twitter_general_settings"><a class="mwb_twitter_active_settings_tab" href="<?php echo get_admin_url()?>admin.php?page=mwb-twitter-for-wordpress-settings&tab=mwb_general_settings"><?php _e('General Settings','mwb_twitter_for_wordpress'); ?></a></li>
			<li class="mwb_twitter_tablink <?php if(isset($_GET['tab']) && $_GET['tab'] == 'mwb_tweet_message'){  echo 'active'; }?>" data-tab="mwb_twitter_dashboard_tweet"><a class="mwb_twitter_active_settings_tab" href="<?php echo get_admin_url()?>admin.php?page=mwb-twitter-for-wordpress-settings&tab=mwb_tweet_message"><?php _e('Update Status','mwb_twitter_for_wordpress'); ?></a></li>
			<li class="mwb_twitter_tablink <?php if(isset($_GET['tab']) && $_GET['tab'] == 'mwb_account_settings'){  echo 'active'; }?>" data-tab="mwb_twitter_account_settings"><a class="mwb_twitter_active_settings_tab" href="<?php echo get_admin_url()?>admin.php?page=mwb-twitter-for-wordpress-settings&tab=mwb_account_settings"><?php _e('Profile Settings','mwb_twitter_for_wordpress'); ?></a></li>
			<li class="mwb_twitter_tablink <?php if(isset($_GET['tab']) && $_GET['tab'] == 'mwb_post_sharing'){  echo 'active'; }?>" data-tab="mwb_twitter_search_tweets"><a class="mwb_twitter_active_settings_tab" href="<?php echo get_admin_url()?>admin.php?page=mwb-twitter-for-wordpress-settings&tab=mwb_post_sharing"><?php _e('Share Posts','mwb_twitter_for_wordpress'); ?></a></li>
		</ul>

		<!-- Twitter general settings -->
		<?php 
		$mwb_twitter_general_details = get_option('mwb_twitter_consumer_app_details',false);

		if((isset($_GET['tab']) && $_GET['tab'] == 'default_tab') || (isset($_GET['tab']) && $_GET['tab'] == 'mwb_general_settings') )
		{
			?>
			<div id="mwb_twitter_general_settings" class="mwb_twitter_tabcontent active">
				<h3><?php _e('General Settings For Your Twitter Account','mwb_twitter_for_wordpress'); ?></h3>
				<p><?php _e('Enter your account credentials for authentication','mwb_twitter_for_wordpress'); ?></p>
				<div class="mwb_twitter_authentication_details">
					<table class="form-table">
						<tr>
							<th scope="row"><label for="appid"><?php _e("Consumer Key (API Key)","mwb_twitter_for_wordpress"); ?></label></th>
							<td><input class="regular-text" name="mwb_twitter_consumer_app_id" type="text" value="<?php if(isset($mwb_twitter_general_details['mwb_twitter_consumer_appid']) && $mwb_twitter_general_details['mwb_twitter_consumer_appid'] != ''){ echo $mwb_twitter_general_details['mwb_twitter_consumer_appid'];}?>" required>
								<p> <?php _e("Don't Have Twitter app","mwb_twitter_for_wordpress"); ?> <a href="https://apps.twitter.com/"><?php _e('Click here.','mwb_twitter_for_wordpress'); ?></a></p>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="appsec"><?php _e("Consumer Secret (API Secret)","mwb_twitter_for_wordpress"); ?></label></th>
							<td><input class="regular-text" name="mwb_twitter_consumer_app_sec" type="password" value="<?php if(isset($mwb_twitter_general_details['mwb_twitter_consumer_appseceret']) && $mwb_twitter_general_details['mwb_twitter_consumer_appseceret'] != ''){ echo $mwb_twitter_general_details['mwb_twitter_consumer_appseceret'];}?>" required></td>
						</tr>
						<tr>
							<th scope="row"><label for="pageid"><?php _e("Access Token","mwb_twitter_for_wordpress"); ?></label> </th>
							<td><input class="regular-text" name="mwb_twitter_app_access" type="text" value="<?php if(isset($mwb_twitter_general_details['mwb_twitter_consumer_acess_token']) && $mwb_twitter_general_details['mwb_twitter_consumer_acess_token'] != ''){ echo $mwb_twitter_general_details['mwb_twitter_consumer_acess_token'];}?>" required></td>
						</tr>
						<tr>
							<th scope="row"><label for=""><?php _e("Access Token Secret","mwb_twitter_for_wordpress"); ?></label> </th>
							<td><input class="regular-text" name="mwb_twitter_app_access_sec" type="password" value="<?php if(isset($mwb_twitter_general_details['mwb_twitter_consumer_token_secret']) && $mwb_twitter_general_details['mwb_twitter_consumer_token_secret'] != ''){ echo $mwb_twitter_general_details['mwb_twitter_consumer_token_secret'];}?>" required></td>
						</tr>
						<tr>
							<th scope="row"><label><?php _e('Enable Tweet Button For Post Text sharing','mwb_twitter_for_wordpress'); ?></label></th>
							<td>
								<input type="checkbox" name="mwb_twitter_post_tweet" <?php if(isset($mwb_twitter_general_details['mwb_twitter_tweet_button_enable'])){checked($mwb_twitter_general_details['mwb_twitter_tweet_button_enable'],1);}?>><?php _e('Select checkbox for sharing of your posts text on twitter','mwb_twitter_for_wordpress'); ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><label><?php _e('Select Position For Tweet Button','mwb_twitter_for_wordpress'); ?></label></th>
							<td>
								<select name="mwb_twitter_tweet_button_position" id="mwb_twitter_tweet_button_position">
									<option value="both" <?php if(isset($mwb_twitter_general_details['mwb_twitter_tweet_button_position']) && $mwb_twitter_general_details['mwb_twitter_tweet_button_position'] == 'both'){ echo "selected= selected"; }?>><?php _e('Both','mwb_twitter_for_wordpress'); ?></option>
									<option value="top" <?php if(isset($mwb_twitter_general_details['mwb_twitter_tweet_button_position']) && $mwb_twitter_general_details['mwb_twitter_tweet_button_position'] == 'top'){ echo "selected= selected"; }?>><?php _e('Top','mwb_twitter_for_wordpress'); ?></option>
									<option value="bottom" <?php if(isset($mwb_twitter_general_details['mwb_twitter_tweet_button_position']) && $mwb_twitter_general_details['mwb_twitter_tweet_button_position'] == 'bottom'){ echo "selected= selected"; }?>><?php _e('Bottom','mwb_twitter_for_wordpress'); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row"><label><?php _e('Enable Follow Button','mwb_twitter_for_wordpress'); ?></label></th>
							<td>
								<input type="checkbox" name="mwb_twitter_follow_button" <?php if(isset($mwb_twitter_general_details['mwb_twitter_follow_button_enable'])){checked($mwb_twitter_general_details['mwb_twitter_follow_button_enable'],1);}?>><?php _e('Select checkbox for enabling follow button widget','mwb_twitter_for_wordpress'); ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><label><?php _e('Enter Your Twitter Account Name','mwb_twitter_for_wordpress'); ?></label></th>
							<td>
								<input type="text" name="mwb_twitter_account_name" value="<?php if(isset($mwb_twitter_general_details['mwb_twitter_account_name_text'])){echo $mwb_twitter_general_details['mwb_twitter_account_name_text'];}?>" placeholder="<?php _e('Ex. @example','mwb_twitter_for_wordpress'); ?>">
							</td>
						</tr>

					</table>
					<input type="hidden" name="mwb_twitter_nonce_verify" id="mwb_twitter_nonce_verify" value="<?php echo wp_create_nonce('mwb-twitter-nonce');?>">
					<p class="submit"><input type="submit" value="<?php _e('Save Changes','mwb_twitter_for_wordpress'); ?>" class="button button-primary" id="submit" name="mwb_twitter_auth_submit"></p>
				</div>
			</div>
			<!-- End Twitter general settings -->
			<?php
		}
		if(isset($_GET['tab']) && $_GET['tab'] == 'mwb_tweet_message')
		{
			?>
			<!-- Dashboard tweet settings -->
			<div id="mwb_twitter_dashboard_tweet" class="mwb_twitter_tabcontent active">
				<h3><?php _e('Update Status Right From Here','mwb_twitter_for_wordpress'); ?></h3>
				<p><?php _e('Update your status directly from your dashboard to your twitter account','mwb_twitter_for_wordpress'); ?></p> 

				<div class="mwb_twitter_tweet_messages">
					<table class="form-table">
						<?php
						if(is_array($mwb_twitter_general_details) && ($mwb_twitter_general_details['mwb_twitter_consumer_appid'] == '' || $mwb_twitter_general_details['mwb_twitter_consumer_appseceret'] == '' || $mwb_twitter_general_details['mwb_twitter_consumer_acess_token'] == '' || $mwb_twitter_general_details['mwb_twitter_consumer_token_secret'] == ''))
						{
							?>
							<tr>
								<th scope="row"><label><?php _e('Please Enter Your Credentials First','mwb_twitter_for_wordpress'); ?></th></label>
							</tr>
							<?php	
						} 
						else
						{
							?>
							<tr>
								<th scope="row"><label><?php _e('Enter Message To TextArea You Want To Tweet','mwb_twitter_for_wordpress'); ?></th></label>
								<td>
									<textarea rows="4" cols="50" id="mwb_twitter_tweet_messages" placeholder="<?php _e('Enter Some Text Here','mwb_twitter_for_wordpress'); ?>"></textarea>
								</td>
							</tr>
							<tr>
								<th scope="row"><label><?php _e('Select Image To Upload','mwb_twitter_for_wordpress'); ?></label></th>
								<td>
									<input type="text" readonly class="mwb_twitter_upload_status_image" id="mwb_twitter_upload_status_image_upload" name="mwb_twitter_upload_status_image_upload" value=""/>
									<input id="demo" class="mwb_twitter_setting_upload_status_image button" type="button" value=<?php _e("Upload Status Image","mwb_twitter_for_wordpress");?> />

									<p id="mwb_twitter_status_setting_remove_logo">
										<span class="mwb_twitter_status_setting_remove_logo">
											<img src="" width="50px" height="50px" id="mwb_twitter_status_setting_upload_image">
											<!-- <span class="mwb_tyo_other_setting_remove_logo_span">X</span> -->
										</span>
									</p>
								</td>
							</tr>
							<?php 
						}
						?>
					</table>

					<p class="mwb_twitter_direct_msg"><i class="dashicons dashicons-twitter mwb_twitter_direct_msg_icon"></i><input type="button" value="<?php _e('Tweet','mwb_twitter_for_wordpress'); ?>" class="button button-primary" id="mwb_twitter_tweet_msg" name="mwb_twitter_tweet_msg"></p>
					<div class="mwb_twitter_for_wordpress_loader_wrapper">
						<div id="mwb_twitter_for_wordpress">
							<img src="<?php echo MWB_TWITTER_URL.'admin/images/loading.gif';?>">
						</div>
					</div>
				</div>
			</div>
			<!-- End Dashboard tweet settings -->
			<?php
		}
		if(isset($_GET['tab']) && $_GET['tab'] == 'mwb_account_settings')
		{
			?>
			<!-- Twitter Account Settings -->
			<div id="mwb_twitter_account_settings" class="mwb_twitter_tabcontent active">
				<h3><?php _e('Profile settings for various updation','mwb_twitter_for_wordpress');?></h3>
				<p><?php _e('Update Your Profile Image And Banner','mwb_twitter_for_wordpress');?></p>

				<div class="mwb_twitter_upload_profile_image">
					<table class="form-table">
						<tr>
							<th scope="row"><label><?php _e('Update Your Profile Image','mwb_twitter_for_wordpress');?></label></th>
							<td>
								<input type="text" readonly class="mwb_twitter_upload_profile_image" id="mwb_twitter_upload_profile_image_upload" name="mwb_twitter_upload_profile_image_upload" value=""/>
								<input class="mwb_twitter_setting_upload_pro_image button" type="button" value=<?php _e("Upload Profile Image","mwb_twitter_for_wordpress");?> />

								<p id="mwb_twitter_other_setting_remove_logo">
									<span class="mwb_twitter_other_setting_remove_logo">
										<img src="" width="50px" height="50px" id="mwb_twitter_other_setting_upload_image">
										<!-- <span class="mwb_tyo_other_setting_remove_logo_span">X</span> -->
									</span>
								</p>
							</td>	
						</tr>

						<tr>
							<th scope="row"><label><?php _e('Update Your Profile Banner','mwb_twitter_for_wordpress');?></label></th>
							<td>
								<input type="text" readonly class="mwb_twitter_upload_profile_banner" id="mwb_twitter_upload_profile_banner_upload" name="mwb_twitter_upload_profile_banner_upload" value=""/>
								<input class="mwb_twitter_setting_upload_pro_banner button" type="button" value=<?php _e("Upload Profile Banner","mwb_twitter_for_wordpress");?> />

								<p id="mwb_twitter_banner_setting_remove_logo">
									<span class="mwb_twitter_banner_setting_remove_logo">
										<img src="" width="50px" height="50px" id="mwb_twitter_banner_setting_upload_image">
										<!-- <span class="mwb_tyo_other_setting_remove_logo_span">X</span> -->
									</span>
								</p>
							</td>
						</tr>
					</table>
					<p id="mwb_twitter_submit_pro_image">
						<span class="dashicons dashicons-twitter"></span>
						<input type="button" value="<?php _e('Update Your Profile','mwb_twitter_for_wordpress'); ?>" class="button button-primary" id="mwb_twitter_profile_img" name="mwb_twitter_profile_img">
					</p>
					<div class="mwb_twitter_for_wordpress_loader_wrapper">
						<div id="mwb_twitter_for_wordpress">
							<img src="<?php echo MWB_TWITTER_URL.'admin/images/loading.gif';?>">
						</div>
					</div>
				</div>
			</div>
			<!-- End Twitter Account Settings -->
			<?php
		}
		if(isset($_GET['tab']) && $_GET['tab'] == 'mwb_post_sharing')
		{
			$MwbTwitterSharepostonPublishEnable = get_option('mwb_twitter_share_post_on_publish',false);
			?>
			<!-- Twitter Search Settings -->
			<div id="mwb_twitter_search_tweets" class="mwb_twitter_tabcontent active">
				<h3><?php _e('Welcome To Sharing World','mwb_twitter_for_wordpress');?></h3>
				<p><?php _e('Want To Share Your Post\'s Image And Content On Twitter', 'mwb_twitter_for_wordpress'); ?></p>
			</div>
			<div class="mwb_twitter_all_posts_wrapper">
				<label><?php _e('Enable CheckBox To Share Your Post\'s On Publish','mwb_twitter_for_wordpress'); ?></label>
				<span class="mwb_twitter_enable_checkbox">
					<input type="checkbox" name="mwb_twitter_share_your_posts" id="mwb_twitter_share_your_posts" <?php if(isset($MwbTwitterSharepostonPublishEnable) && $MwbTwitterSharepostonPublishEnable != ''){ checked($MwbTwitterSharepostonPublishEnable,1); }?>>
					<label for="mwb_twitter_share_your_posts"></label>
				</span>
				<span class="mwb_twitter_checkbox_text">
					<?php _e('Enable checkbox to share on twitter when publish your post first time','mwb_twitter_for_wordpress'); ?>
				</span>
			</div>
			<p class="mwb_twitter_post_sharing_button">
				<input type="hidden" name="mwb_twitter_nonce_share_post_submit" id="mwb_twitter_nonce_share_post_submit" value="<?php echo wp_create_nonce('mwb_twitter_nonce_share_post'); ?>">
				<input type="submit" name="mwb_twitter_share_post_submit" id="submit" class="button button-primary mwb_twitter_share_post" value="Save Changes">
			</p>
			<!-- End Twitter Search Settings -->
			<?php
		}
		?>
	</form>	
</div>
