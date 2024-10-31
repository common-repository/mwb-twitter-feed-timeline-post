<?php
if( !defined("ABSPATH")){
	exit;
}

if( !class_exists('Mwb_Twitter_Follow_Button_Shortcode')){

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */

	class Mwb_Twitter_Follow_Button_Shortcode 
	{
		
		public function __construct()
		{

			if ( defined( 'MWB_TWITTER_VERSION' ) ) {
				$this->version = PLUGIN_NAME_VERSION;
			} else {
				$this->version = '1.0.0';
			}
			$this->plugin_name = 'mwb_twitter_for_wordpress';
			add_shortcode('MWB_TWITTER_FOLLOW_BUTTON',array($this,'mwb_twitter_follow_button_shortcode_function'));
			
		}


		public function mwb_twitter_follow_button_shortcode_function($atts)
		{

			$mwb_twitter_general_details = get_option('mwb_twitter_consumer_app_details',false);
			if(is_array($mwb_twitter_general_details) && isset($mwb_twitter_general_details['mwb_twitter_account_name_text']) && $mwb_twitter_general_details['mwb_twitter_account_name_text'] != ''){

				$mwb_twitter_consumer_name = $mwb_twitter_general_details['mwb_twitter_account_name_text'];
				$mwb_twitter_consumer_name = ltrim($mwb_twitter_consumer_name, '@');
				
				return '<div><i class="dashicons dashicons-twitter"></i><a class="mwb-twitter-follow-button twitter-follow-button" href="https://twitter.com/'.$mwb_twitter_consumer_name.'" data-size="large" data-show-count="true"> Follow '.$mwb_twitter_general_details['mwb_twitter_account_name_text'].'</a></div>';
			}
		}
	}
	$mwb_twitter_follow_button_shortcode = new Mwb_Twitter_Follow_Button_Shortcode();
}