<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://makewebbetter.com
 * @since      1.0.0
 *
 * @package     mwb-twitter-for-wordpress
 * @subpackage  mwb-twitter-for-wordpress/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package     mwb-twitter-for-wordpress
 * @subpackage  mwb-twitter-for-wordpress/admin
 * @author      makewebbetter <webmaster@makewebbetter.com>
 */
class Mwb_twitter_for_wordpress_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mwb_twitter_for_wordpress_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mwb_twitter_for_wordpress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mwb_twitter_for_wordpress-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'select2' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mwb_twitter_for_wordpress_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mwb_twitter_for_wordpress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mwb_twitter_for_wordpress-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'mwb_twitter_admin_params', array( 'ajax_url' => admin_url( 'admin-ajax.php' ),'mwb_twitter_saved_message'=>__('Message Saved','mwb_twitter_for_wordpress'),'mwb_twitter_profile_image_saved_message'=>__('Profile image changed','mwb_twitter_for_wordpress'),'mwb_twitter_profile_banner_saved_message'=>__('Profile banner changed','mwb_twitter_for_wordpress'),'mwb_twitter_both_profile_saved_message'=>__('Profile image and banner changed','mwb_twitter_for_wordpress'),'mwb_twitter_error_messages'=>__('Please Insert Image Url First For Changing Profile Image Or Banner  , please update','mwb_twitter_for_wordpress'),'mwb_twitter_update_status_error_msg'=> __('For Updating Status Please Enter Text Or Image Or Both','mwb_twitter_for_wordpress'),'mwb_twitter_nonce'=>wp_create_nonce( "mwb-twitter-verify-nonce" ) ) );
		wp_enqueue_media ();
		wp_enqueue_script( 'select2' );

	}

	/**
	 * Register the Custom post type for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function mwb_tweet_admin_menu_page()
	{
		if(current_user_can('manage_options')){
			add_menu_page('MWB Twitter For Wordpress',__('Twitter ToolKit','mwb_twitter_for_wordpress'),'manage_options','mwb-twitter-for-wordpress-settings',array($this,'mwb_tweet_admin_settings'),'dashicons-twitter','50');
			add_submenu_page('mwb-twitter-for-wordpress-settings','Twitter ToolKit',__('Follower List','mwb_twitter_for_wordpress'),'manage_options','mwb_all_tweets_followers',array($this,'mwb_tweet_admin_all_followers_list'));
		}
	}


	/**
	 * add thickbox for open the wp_media modal.
	 *
	 * @since    1.0.0
	 */
	public function mwb_twitter_thickbox_view()
	{
		add_thickbox();
	}

	/**
	 * Add html file for all admin settings.
	 *
	 * @since    1.0.0
	 */
	public function mwb_tweet_admin_settings()
	{
		require_once MWB_TWITTER_PATH.'admin/partials/mwb_twitter_for_wordpress-admin-display.php';
	}

	/**
	 * Include class file that extends WP_List table for Twitter Followers Listing.
	 *
	 * @since    1.0.0
	 */
	public function mwb_tweet_admin_all_followers_list()
	{
		require_once MWB_TWITTER_PATH.'admin/followers/class-mwb-twitter-for-wordpress-followers.php';
		$mwb_twitter_followers_object = new Mwb_twitter_followres_list();
		$mwb_twitter_followers_object->prepare_items();
		?>
		<form id="mwb-twitter-all-followers" method="post">
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
			<?php
			$mwb_twitter_followers_object->display();
			?>
		</form>
		<?php
	}

	/**
	 * Function for Timeline tweets widgets.
	 *
	 * @since    1.0.0
	 */
	public function mwb_tweet_custom_latest_tweet_widget()
	{
		if(current_user_can('edit_theme_options')){

			require_once MWB_TWITTER_PATH.'admin/class-twitter-for-wordpress-latest-widget.php';
			register_widget( 'mwb_twitter_latest_tweet' );

			$mwb_twitter_general_details = get_option('mwb_twitter_consumer_app_details',false);
			if(is_array($mwb_twitter_general_details) && isset($mwb_twitter_general_details['mwb_twitter_follow_button_enable']) && $mwb_twitter_general_details['mwb_twitter_follow_button_enable'] == 1){
				register_widget('mwb_twitter_follow_button');
			}

			register_widget('mwb_twitter_home_timeline_tweets');
		}
	}

	/**
	 * Function for update status on twitter with and without image from admin pannel.
	 *
	 * @since    1.0.0
	 */
	public function mwb_twitter_tweet_message_from_dashboard(){
		
		$mwb_nonce_security = check_ajax_referer( 'mwb-twitter-verify-nonce', 'mwb_update_nonce' );
		if($mwb_nonce_security){

			$mwb_twitter_consumer_details = get_option('mwb_twitter_consumer_app_details',false);

			if(is_array($mwb_twitter_consumer_details) && !empty($mwb_twitter_consumer_details)){

				$mwb_twitter_settings = array(
					'consumer_key' => $mwb_twitter_consumer_details['mwb_twitter_consumer_appid'],
					'consumer_secret' => $mwb_twitter_consumer_details['mwb_twitter_consumer_appseceret'],
					'user_token' => $mwb_twitter_consumer_details['mwb_twitter_consumer_acess_token'],
					'user_secret' => $mwb_twitter_consumer_details['mwb_twitter_consumer_token_secret'],
					'curl_ssl_verifypeer'   => false
					);
				$mwb_twitter_obj = new tmhOAuth($mwb_twitter_settings);
				$mwb_twitter_message_for_post = isset($_POST['mwb_twitter_send_message']) ? $_POST['mwb_twitter_send_message'] : '';
				$mwb_twitter_image_for_post = isset($_POST['mwb_twitter_send_image']) ? $_POST['mwb_twitter_send_image'] : '';
				$link = get_site_url();
				if(isset($mwb_twitter_image_for_post) && $mwb_twitter_image_for_post != ''){
					$arrContextOptions = array(
						"ssl"=>array(
							"verify_peer"=>false,
							"verify_peer_name"=>false,
							),
						);
					$MwbTwitterStatusImgurl = file_get_contents($mwb_twitter_image_for_post,false,stream_context_create($arrContextOptions));
					$postfields = array(
						'status' => $link."\n".$mwb_twitter_message_for_post,
						'media[]' => $MwbTwitterStatusImgurl
						);
					$mwb_twitter_tweet_url = 'https://api.twitter.com/1.1/statuses/update_with_media.json';
				}
				elseif(isset($mwb_twitter_message_for_post) && $mwb_twitter_message_for_post != ''){

					$postfields = array(
						'status' => $link."\n".$mwb_twitter_message_for_post
						);
					$mwb_twitter_tweet_url = 'https://api.twitter.com/1.1/statuses/update.json';
				}
				else{
					echo 'failed';
					wp_die();
				}

				try{
					$response = $mwb_twitter_obj->request('POST',$mwb_twitter_tweet_url,$postfields,true,true);
					$ResponseReturn = json_decode($response,true);

					if($ResponseReturn['return_code'] == 200){
						echo 'success';
					}
					else{
						echo 'failed';
					}
				}catch(Exception $e){
					$mwb_twitter_exception = __("Some error occured with tweet please verify your Api keys.","mwb_twitter_for_wordpress");
					echo $mwb_twitter_exception;
				}
				wp_die();
			}
		}else{
			echo "failed";
			wp_die();
		}
	}

	/**
	 * Function for sending direct messages to followers from admin area.
	 *
	 * @since    1.0.0
	 */
	public function mwb_twitter_send_message_to_follower(){

		$mwb_nonce_security = check_ajax_referer( 'mwb-twitter-verify-nonce', 'mwb_update_nonce' );
		
		if($mwb_nonce_security){
			$mwb_twitter_follower_id = isset($_POST['mwb_twitter_follower_id']) ? $_POST['mwb_twitter_follower_id'] : '';
			$mwb_twitter_follower_message = isset($_POST['mwb_twitter_follower_text']) ? $_POST['mwb_twitter_follower_text'] : '';
			$mwb_twitter_follower_screenname = isset($_POST['mwb_twitter_follower_screen_name']) ? $_POST['mwb_twitter_follower_screen_name'] : '';

			if((isset($mwb_twitter_follower_message) && isset($mwb_twitter_follower_screenname)) && ($mwb_twitter_follower_message != '' && $mwb_twitter_follower_screenname != ''))
			{
				$mwb_twitter_consumer_details = get_option('mwb_twitter_consumer_app_details',false);

				if(is_array($mwb_twitter_consumer_details) && !empty($mwb_twitter_consumer_details)){

					$mwb_twitter_settings = array(
						'consumer_key' => $mwb_twitter_consumer_details['mwb_twitter_consumer_appid'],
						'consumer_secret' => $mwb_twitter_consumer_details['mwb_twitter_consumer_appseceret'],
						'user_token' => $mwb_twitter_consumer_details['mwb_twitter_consumer_acess_token'],
						'user_secret' => $mwb_twitter_consumer_details['mwb_twitter_consumer_token_secret'],
						'curl_ssl_verifypeer'   => false
						);
				}
				$mwb_twitter_obj = new tmhOAuth($mwb_twitter_settings);
				$postfields = array(
					'text' => $mwb_twitter_follower_message,
					'screen_name'=>$mwb_twitter_follower_screenname
					);

				$mwb_twitter_follower_direct_msg_url = 'https://api.twitter.com/1.1/direct_messages/new.json';
				try{

					$mwb_twitter_msg_response = $mwb_twitter_obj->request('POST',$mwb_twitter_follower_direct_msg_url,$postfields,true,true);
					$mwb_twitter_msg_response = json_decode($mwb_twitter_msg_response,true);
					if($mwb_twitter_msg_response['return_code'] == 200){
						echo $mwb_twitter_follower_id;
					}
					else{
						echo 'failed';
					}
				}catch(Exception $e){
					$mwb_twitter_msg_exception = __("Some error occured with direct message please verify your Api keys.","mwb_twitter_for_wordpress");
					echo $mwb_twitter_msg_exception;
				}
			}
			else
			{
				echo 'failed';
			}
			wp_die();
		}
	}

	/**
	 * Function for changing profile image and banner from admin area.
	 *
	 * @since    1.0.0
	 */
	public function mwb_twitter_profile_settings()
	{
		$mwb_nonce_security = check_ajax_referer( 'mwb-twitter-verify-nonce', 'mwb_update_nonce' );
		if($mwb_nonce_security){

			$MwbReturnresult = '';
			$MwbReturnBannerresult = '';
			$mwb_twitter_profile_image = isset($_POST['mwb_twitter_profile_img']) ? $_POST['mwb_twitter_profile_img'] : '';
			$mwb_twitter_profile_banner_image = isset($_POST['mwb_twitter_profile_banner_img']) ? $_POST['mwb_twitter_profile_banner_img'] : '';
			$mwb_twitter_profile_settings = array(
				'mwb_twitter_profile_image' => $mwb_twitter_profile_image,
				'mwb_twitter_profile_banner' => $mwb_twitter_profile_banner_image
				);

			update_option('mwb_twitter_profile_data_saved',$mwb_twitter_profile_settings);
			require_once MWB_TWITTER_PATH.'admin/account settings/class-twitter-account-settings.php';
			$mwb_twitter_profile_obj = new MwbTwitterAccountSettings();
			$mwb_twitter_profile_data = get_option('mwb_twitter_profile_data_saved',false);

			if(isset($mwb_twitter_profile_data['mwb_twitter_profile_image']) && (!empty($mwb_twitter_profile_data['mwb_twitter_profile_image']) || $mwb_twitter_profile_data['mwb_twitter_profile_image'] != '')){

				$MwbReturnresult = $mwb_twitter_profile_obj->mwb_twitter_profile_image_change($mwb_twitter_profile_data['mwb_twitter_profile_image']);
			}

			if(isset($mwb_twitter_profile_data['mwb_twitter_profile_banner']) && (!empty($mwb_twitter_profile_data['mwb_twitter_profile_banner']) || $mwb_twitter_profile_data['mwb_twitter_profile_banner'] != '')){
				$MwbReturnBannerresult = $mwb_twitter_profile_obj->mwb_twitter_profile_banner_image($mwb_twitter_profile_data['mwb_twitter_profile_banner']);
			}

			if($MwbReturnresult == '200' && $MwbReturnBannerresult == '200'){
				echo 'success';
			}
			elseif($MwbReturnresult == '200'){
				echo 'profile';
			}
			elseif($MwbReturnBannerresult == '200'){
				echo 'banner';
			}
			else{
				echo 'failed';
			}
			wp_die();
		}
	}

	/**
	 * Function for adding custom message meta box on post and prodcut edit page.
	 *
	 * @since    1.0.0
	 */
	public function mwb_twitter_add_custom_message_meta_box($post_type){
		global $post;
		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return;
		}
		add_meta_box('mwb_twitter_custom_message',__('Enter Custom Message For Sharing On Twitter','mwb_twitter_for_wordpress'),array($this,'mwb_twitter_add_custommsg_box'),$post_type,'side','high');
	}

	/**
	 * Function for creating html structure of custom message meta box.
	 *
	 * @since    1.0.0
	 */
	public function mwb_twitter_add_custommsg_box($post){
		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return;
		}
		$psotId = $post->ID;
		$MwbTwitterForWordpressCustomMessages = get_post_meta($psotId,'mwb_twitter_saved_message',true);
		?>
		<div class="mwb_twitter_custom_meta_box_for_message">
			<input type="button" class="preview button" name="mwb_twitter_save_custom_message" id="mwb_twitter_save_custom_message" value="<?php _e('Save Message','mwb_twitter_for_wordpress'); ?>" data-postID="<?php echo $psotId; ?>">
			<textarea name="mwb_twitter_message_for_sharing" rows="4" cols="20" id="mwb_twitter_message_for_sharing_<?php echo $psotId; ?>"><?php if(isset($MwbTwitterForWordpressCustomMessages) ){ echo $MwbTwitterForWordpressCustomMessages; }?></textarea>
			<div class="mwb_twitter_saved_msg"></div>
		</div>
		<?php
	}

	/**
	 * Function for saving message of custom meta box.
	 *
	 * @since    1.0.0
	 */
	public function mwb_twitter_save_message(){
		$mwb_nonce_security = check_ajax_referer( 'mwb-twitter-verify-nonce', 'mwb_update_nonce' );
		if($mwb_nonce_security){
			$MwbTwitterPostid = isset($_POST['mwb_twitter_post_id']) ? $_POST['mwb_twitter_post_id'] : 0;
			$MwbTwitterPostCustomMessage = isset($_POST['mwb_twitter_post_custom_message']) ? $_POST['mwb_twitter_post_custom_message'] : '';
			update_post_meta($MwbTwitterPostid,'mwb_twitter_saved_message',$MwbTwitterPostCustomMessage);
			echo 'success';
			wp_die();
		}
	}

	/**
	 * Function for sharing of post and woocmmerce product on publishing.
	 *
	 * @since    1.0.0
	 */
	public function mwb_twitter_share_all_posts_on_publish($new_status, $old_status, $post){
		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return;
		}
		$postId = $post->ID;
		if(isset($new_status) && $new_status == 'publish' && $old_status == 'draft'){
			if(get_post_meta($postId,'mwb_twitter_shared_post',true) != '200' ){
				require_once MWB_TWITTER_PATH."admin/Sharing/class-mwb-twitter-post-sharing.php";
				$MwbTwitterPostSharingObj = new MwbTwitterPostSharing();
				$MwbTwitterPostSharingResult = $MwbTwitterPostSharingObj->MwbTwitterAllPostSharing($post);
				if($MwbTwitterPostSharingResult){
					$message = __('success','mwb_twitter_for_wordpress');
					$this->mwb_twitter_share_notices($message);
				}
				else{
					$message = __('error','mwb_twitter_for_wordpress');
					$this->mwb_twitter_share_notices($message);
				}
			}
		}
	}

	/**
	 * Function for showing admin notices.
	 *
	 * @since    1.0.0
	 */
	public function mwb_twitter_share_notices($message){
		if(isset($message) && $message == 'success'){

			$mwb_twitter_error_message = get_option('mwb_twitter_share_error');
			?>
			<div class="notice notice-error is-dismissible mwb_twitter_post_share_message">
				<p class="mwb_twitter_share_msg">
					<?php echo $mwb_twitter_error_message; ?>
				</p>
			</div>
			<?php
		}
		else{

			$mwb_twitter_error_message = get_option('mwb_twitter_share_error');
			?>
			<div class="notice notice-error is-dismissible mwb_twitter_post_share_message">
				<p class="mwb_twitter_share_msg">
					<?php echo $mwb_twitter_error_message; ?>
				</p>
			</div>
			<?php
		}
	}
}
