<?php
/**
 * This file is for license panel. Include this file if license is not validated.   
 * If license is validated then show you setting page.
 * Otherwise show the same file.
 * 
 */ 
global $wp_version;
global $current_user;
$mwb_twitter_image_url = MWB_TWITTER_URL.'admin/images/Twitter.png';
?>
<div class="mwb_twitter_image">
	<img src="<?php echo $mwb_twitter_image_url; ?>">
</div>
<div id="mwb_pas_license_verify">	
<h3><?php _e('MWB Twitter For Wordpress License Panel','mwb_twitter_for_wordpress');?></h3>
	<hr/>
	<div style="text-align: justify; float: left; width: 66%; font-size: 16px; line-height: 25px; padding-right: 4%;">
		<?php 
		_e('This is the License Activation Panel. After purchasing extension from Codecanyon you will get the purchase code of this extension. Please verify your purchase below so that you can use feature of this plugin.','mwb_twitter_for_wordpress');
		?>

	</div>
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th class="titledesc" scope="row">
					<label><?php _e('Enter Purchase Code','mwb_twitter_for_wordpress');?></label>
				</th>
				<td class="forminp">
					<fieldset>
						<input type="text" id="mwb_twitter_license_key" class="input-text regular-input" placeholder="<?php _e('Enter your Purchase code here...','mwb_twitter_for_wordpress'); ?>">
						<input type="submit" value="<?php _e('Validate','mwb_twitter_for_wordpress'); ?>" class="button-primary" id="mwb_twitter_license_save">
						<img class="loading_image" src="<?php echo MWB_TWITTER_URL.'admin/images/loading.gif';?>" style="height: 28px;vertical-align: middle;display:none;">
						<b class="licennse_notification"></b>
					</fieldset>
				</td>
			</tr>
		</tbody>
	</table>
</div>