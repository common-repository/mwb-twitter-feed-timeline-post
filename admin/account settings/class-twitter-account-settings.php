<?php 
if( !defined('ABSPATH'))
{
	exit;
}
if(!class_exists('MwbTwitterAccountSettings')){

	/**
	* define class name for twitter account settings.
	*/
	class MwbTwitterAccountSettings 
	{
		
		public function __construct()
		{
			require_once MWB_TWITTER_PATH.'Api/tmhOAuth.php';
		}

		public function mwb_twitter_profile_image_change($profileimgurl)
		{
			$mwb_twitter_user_details = get_option('mwb_twitter_consumer_app_details',false);
			if(is_array($mwb_twitter_user_details) && !empty($mwb_twitter_user_details))
			{

				$mwb_twitter_user_settings = array(
					'consumer_key' => $mwb_twitter_user_details['mwb_twitter_consumer_appid'],
					'consumer_secret' => $mwb_twitter_user_details['mwb_twitter_consumer_appseceret'],
					'user_token' => $mwb_twitter_user_details['mwb_twitter_consumer_acess_token'],
					'user_secret' => $mwb_twitter_user_details['mwb_twitter_consumer_token_secret'],
					'curl_ssl_verifypeer'   => false
					);
				$mwb_twitter_object = new tmhOAuth($mwb_twitter_user_settings);
				$arrContextOptions=array(
					"ssl"=>array(
						"verify_peer"=>false,
						"verify_peer_name"=>false,
						),
					); 
				$MwbTwitterProfileImgurl = file_get_contents($profileimgurl,false,stream_context_create($arrContextOptions));
				
				$postfields = array(
					'image' => $MwbTwitterProfileImgurl
					);
				
				$mwb_twitter_post_profile_img = 'https://api.twitter.com/1.1/account/update_profile_image.json';
				$mwb_twitter_profile_response = $mwb_twitter_object->request('POST',$mwb_twitter_post_profile_img,$postfields,true,true);
				$mwb_twitter_profile_response_return = json_decode($mwb_twitter_profile_response,true);
				if( $mwb_twitter_profile_response_return['return_code'] == '200' ){
					return '200';
				}
				else{
					return 'error';
				}
			}
		}

		public function mwb_twitter_profile_banner_image($profilebannerimg)
		{
			$mwb_twitter_user_details = get_option('mwb_twitter_consumer_app_details',false);
			if(is_array($mwb_twitter_user_details) && !empty($mwb_twitter_user_details))
			{
				$mwb_twitter_user_settings = array(
					'consumer_key' => $mwb_twitter_user_details['mwb_twitter_consumer_appid'],
					'consumer_secret' => $mwb_twitter_user_details['mwb_twitter_consumer_appseceret'],
					'user_token' => $mwb_twitter_user_details['mwb_twitter_consumer_acess_token'],
					'user_secret' => $mwb_twitter_user_details['mwb_twitter_consumer_token_secret'],
					'curl_ssl_verifypeer'   => false
					);
				$mwb_twitter_object = new tmhOAuth($mwb_twitter_user_settings);
				$arrContextOptions=array(
					"ssl"=>array(
						"verify_peer"=>false,
						"verify_peer_name"=>false,
						),
					); 
				$MwbTwitterProfileBannerurl = file_get_contents($profilebannerimg,false,stream_context_create($arrContextOptions));
				
				$postfields = array(
					'banner' => $MwbTwitterProfileBannerurl
					);
				
				$mwb_twitter_post_profile_banner_img = 'https://api.twitter.com/1.1/account/update_profile_banner.json';
				$mwb_twitter_profile_banner_response = $mwb_twitter_object->request('POST',$mwb_twitter_post_profile_banner_img,$postfields,true,true);
				$mwb_twitter_profile_banner_response_return = json_decode($mwb_twitter_profile_banner_response,true);
				
				if( $mwb_twitter_profile_banner_response_return['return_code'] == '200' || $mwb_twitter_profile_banner_response_return['return_code'] == '201' || $mwb_twitter_profile_banner_response_return['return_code'] == '202' ){
					return '200';
				}
				else{
					return 'error';
				}
			}
		}
	}
}
