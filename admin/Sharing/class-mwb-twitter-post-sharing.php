<?php
if( !defined('ABSPATH'))
{
	exit;
}
/**
* Define class name for post sharing.
*/
class MwbTwitterPostSharing 
{
	
	public function __construct()
	{
		require_once( MWB_TWITTER_PATH.'Api/tmhOAuth.php' );
	}

	public function MwbTwitterAllPostSharing($post)
	{
		if(isset($post)){
			$post_ID = $post->ID;
			$post = get_post($post_ID );
			$MwbTwitterPostLink = get_permalink($post->ID);
			$mwb_twitter_account_details = get_option('mwb_twitter_consumer_app_details',false);
			if(isset($mwb_twitter_account_details['mwb_twitter_consumer_appid']) && $mwb_twitter_account_details['mwb_twitter_consumer_appid'] != '')
			{
				$settings = array(
					'consumer_key' => $mwb_twitter_account_details['mwb_twitter_consumer_appid'],
					'consumer_secret' => $mwb_twitter_account_details['mwb_twitter_consumer_appseceret'],
					'user_token' => $mwb_twitter_account_details['mwb_twitter_consumer_acess_token'],
					'user_secret' => $mwb_twitter_account_details['mwb_twitter_consumer_token_secret'],
					'curl_ssl_verifypeer'   => false
					);
				$MwbTwitterObj = new tmhOAuth($settings);
				$MwbTwitterImgurl = wp_get_attachment_url( get_post_thumbnail_id($post_ID) );
				$MwbTwitterPostfields = array();
				$MwbtwitterMsg = html_entity_decode(get_the_title($post_ID), ENT_COMPAT, 'UTF-8');
				$MwbtwitterContentmsg = get_post_meta($post_ID,'mwb_twitter_saved_message',true);

				$mwbtwitterarrContextOptions=array(
					"ssl"=>array(
						"verify_peer"=>false,
						"verify_peer_name"=>false,
						),
					);  
				if(isset($MwbTwitterImgurl) && $MwbTwitterImgurl != "")
				{
					$MwbTwitterImgurl = file_get_contents($MwbTwitterImgurl,false,stream_context_create($mwbtwitterarrContextOptions));
					$MwbTwitterPostfields = array(
						'status' => $MwbtwitterMsg."\n".$MwbtwitterContentmsg."\n".$MwbTwitterPostLink,
						'media[]' => $MwbTwitterImgurl
						);
					$tmh = 'https://api.twitter.com/1.1/statuses/update_with_media.json';
					try{
						$MwbTwitterPostShare = $MwbTwitterObj->request('POST',$tmh,$MwbTwitterPostfields,true,true);
						$MwbTwitterPostShareResponse = json_decode($MwbTwitterPostShare,true);
						
						if(isset($MwbTwitterPostShareResponse['return_code']) && $MwbTwitterPostShareResponse['return_code'] == 200)
						{
							update_post_meta($post_ID,'mwb_twitter_shared_post',$MwbTwitterPostShareResponse['return_code']);
							$mwb_twitter_success_message = __("Post Shared Successfully On Twitter.","mwb_twitter_for_wordpress");
							update_option('mwb_twitter_share_error',$mwb_twitter_success_message);
							return true;
						}
						else
						{
							$mwb_twitter_error_message = __("Some error occured with tweet please verify your Api keys.","mwb_twitter_for_wordpress");
							update_option('mwb_twitter_share_error',$mwb_twitter_error_message);
							return false;
						}
					}
					catch(exception $ex){
						$mwb_twitter_error_message = __("Some error occured with tweet please verify your Api keys.","mwb_twitter_for_wordpress");
						update_option('mwb_twitter_share_error',$mwb_twitter_error_message);
						return  false;
					}
				}
				else
				{
					$mwb_twitter_error_message = __("Please Insert Post Image.","mwb_twitter_for_wordpress");
					update_option('mwb_twitter_share_error',$mwb_twitter_error_message);
					return false;

				}
			}
			else
			{
				$mwb_twitter_error_message = __('Please Enter Your Twitter Credentials First','mwb_twitter_for_wordpress');
				update_option('mwb_twitter_share_error',$mwb_twitter_error_message);
				return false;
			}
		}
	}

}