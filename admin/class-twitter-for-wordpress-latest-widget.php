<?php 
if( !defined('ABSPATH'))
{
	exit;
}
require_once MWB_TWITTER_PATH.'Api/tmhOAuth.php';
if( !class_exists('mwb_twitter_latest_tweet') )
{
	/**
	* define class for latest top 5 tweets widget
	*/
	class mwb_twitter_latest_tweet extends WP_Widget
	{
		
		public function __construct()
		{
			$mwb_twitter_widget_options = array( 
				'classname' => 'mwb_twitter_top5_tweets',
				'description' => __('Widget for showing top 5 tweets','mwb_twitter_for_wordpress'),
				);
			parent::__construct( 'mwb_twitter_top5_tweets', 'Latest Tweets', $mwb_twitter_widget_options );
		}

		/**
		* Function for Entering title of widget from backend
		*/

		public function form( $instance ) {
			if( isset( $instance[ 'title' ] ) ) {
				$title = $instance[ 'title' ];
			}
			else{
				$title = __('Top 5 tweets','mwb_twitter_for_wordpress');
			}
			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<?php 
		}

		/**
		* Function for showing widget on front-end 
		*/
		public function widget( $args, $instance ) {

			$mwb_twitter_widget_title = apply_filters( 'widget_title', $instance['title'] );
			echo $args['before_widget'];
			if ( ! empty( $mwb_twitter_widget_title ) )
				echo $args['before_title'] . $mwb_twitter_widget_title . $args['after_title'];

			$mwb_twitter_consumer_details = get_option('mwb_twitter_consumer_app_details',false);

			if(is_array($mwb_twitter_consumer_details) && ($mwb_twitter_consumer_details['mwb_twitter_consumer_appid'] != null || $mwb_twitter_consumer_details['mwb_twitter_consumer_appid'] != '')){

				$mwb_twitter_settings = array(
					'consumer_key' => $mwb_twitter_consumer_details['mwb_twitter_consumer_appid'],
					'consumer_secret' => $mwb_twitter_consumer_details['mwb_twitter_consumer_appseceret'],
					'user_token' => $mwb_twitter_consumer_details['mwb_twitter_consumer_acess_token'],
					'user_secret' => $mwb_twitter_consumer_details['mwb_twitter_consumer_token_secret'],
					'curl_ssl_verifypeer'   => false
					);

				$mwb_twitter = new tmhOAuth($mwb_twitter_settings);

				$postfields = array();
				$mwb_twitter_get_tweet_list = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
				$mwb_twitter_response = $mwb_twitter->request('GET',$mwb_twitter_get_tweet_list,$postfields,true,true);
				$mwb_twitter_response_decode = json_decode($mwb_twitter_response,true);
				if (is_array($mwb_twitter_response_decode) && ($mwb_twitter_response_decode['return_code']==200)) {
					
					$mwb_twitter_response_decode = array_slice($mwb_twitter_response_decode,0,5);
					?>
					<ul>
						<?php
						if(is_array($mwb_twitter_response_decode) && !empty($mwb_twitter_response_decode))
						{
							foreach($mwb_twitter_response_decode as $tweet_key =>$tweet_value){
								$mwb_twitter_tweetText = $tweet_value['text'];
								preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $mwb_twitter_tweetText,$match);
								$mwb_twitter_regex = "@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?).*$)@";
								$mwb_twitter_tweetText = preg_replace($mwb_twitter_regex,' ',$mwb_twitter_tweetText);
								?>
								<li>
									<span>
										<img src="<?php echo $tweet_value['user']['profile_image_url']; ?>" />
										<a href="<?php echo $match[0][0];?>"><?php echo $mwb_twitter_tweetText; ?></a>
									</span>
								</li>
								<?php
							}
						}
						?>
					</ul>
					<?php
				}
				else{
					$mwb_twitter_not_found_msg = __('Not Authenticate','mwb_twitter_for_wordpress');
					echo $mwb_twitter_not_found_msg;
				}
			}
			else{
				$mwb_twitter_not_found_msg = __('No latest Tweets','mwb_twitter_for_wordpress');
				echo $mwb_twitter_not_found_msg;
			}
		}
	}
}

if( !class_exists('mwb_twitter_follow_button')){

	/**
	*  Define a class to register the follow button widget
	*/
	class mwb_twitter_follow_button extends WP_Widget
	{
		
		public function __construct()
		{
			$mwb_twitter_followButton_widget_options = array( 
				'classname' => 'mwb_twitter_follow_button_widget',
				'description' => __('Widget For Follow Button','mwb_twitter_for_wordpress'),
				);
			parent::__construct( 'mwb_twitter_follow_button', 'Follow Button', $mwb_twitter_followButton_widget_options );
		}

		/**
		* Function for Entering title of widget from backend
		*/

		public function form( $instance ) {

			if ( isset( $instance[ 'title' ] ) ) {
				$title = $instance[ 'title' ];
			}
			else {
				$title = __( 'Follow Button', 'mwb_twitter_for_wordpress' );
			}
			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<?php 
		}


		/**
		* Function for showing follow button widget on front-end 
		*/
		public function widget( $args, $instance ) {
			$mwb_twitter_follow_widget_title = apply_filters( 'widget_title', $instance['title'] );
			echo $args['before_widget'];
			if ( ! empty( $mwb_twitter_follow_widget_title ) )
				echo $args['before_title'] . $mwb_twitter_follow_widget_title . $args['after_title'];

			$mwb_twitter_general_details = get_option('mwb_twitter_consumer_app_details',false);
			if(is_array($mwb_twitter_general_details) && isset($mwb_twitter_general_details['mwb_twitter_account_name_text']) && ($mwb_twitter_general_details['mwb_twitter_account_name_text'] != '' || $mwb_twitter_general_details['mwb_twitter_account_name_text'] != null )){

				$mwb_twitter_consumer_name = $mwb_twitter_general_details['mwb_twitter_account_name_text'];
				$mwb_twitter_consumer_name = ltrim($mwb_twitter_consumer_name, '@');
				
				echo '<div class="mwb_twitter_for_wordpress_follow_button_widget"><a class="mwb-twitter-follow-button twitter-follow-button" href="https://twitter.com/'.$mwb_twitter_consumer_name.'" data-size="large" data-show-count="true" target="_blank"> <i class="dashicons dashicons-twitter"></i> Follow '.$mwb_twitter_general_details['mwb_twitter_account_name_text'].'</a></div>';
			}else{
				$mwb_twitter_folloers_widget = __('No Followers','mwb_twitter_for_wordpress');
				echo $mwb_twitter_folloers_widget;
			}
		}

	}
}

if( !class_exists('mwb_twitter_home_timeline_tweets'))
{
	/**
	* Define a class to register the follow button widget
	*/
	class mwb_twitter_home_timeline_tweets extends WP_Widget
	{
		
		public function __construct()
		{
			$mwb_twitter_hometweet_iframe_widget_options = array( 
				'classname' => 'mwb_twitter_home_timeline_all_tweets',
				'description' => __('Widget For Home TimeLine Tweets','mwb_twitter_for_wordpress'),
				);
			parent::__construct( 'mwb_twitter_home_timeline_tweets', 'TimeLine Tweets', $mwb_twitter_hometweet_iframe_widget_options );
		}

		/**
		* Function for Entering title of widget from backend
		*/

		public function form( $instance ) {
			if ( isset( $instance[ 'title' ] ) ) {
				$title = $instance[ 'title' ];
			}
			else {
				$title = __( 'TimeLine Tweets', 'mwb_twitter_for_wordpress' );
			}

			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<?php 
		}

		/**
		* Function for showing Home timeline tweets in Iframe widget on front-end.
		*/
		public function widget( $args, $instance ) {
			if(isset($instance['title'])){
				$mwb_twitter_follow_widget_title = apply_filters( 'widget_title', $instance['title'] );
			}else{
				$mwb_twitter_follow_widget_title = apply_filters( 'widget_title', __('TimeLine Tweets','mwb_twitter_for_wordpress') );
			}

			echo $args['before_widget'];
			if ( ! empty( $mwb_twitter_follow_widget_title ) )
				echo $args['before_title'].$mwb_twitter_follow_widget_title.$args['after_title'];

			$mwb_twitter_general_details = get_option('mwb_twitter_consumer_app_details',false);
			if(is_array($mwb_twitter_general_details) && isset($mwb_twitter_general_details['mwb_twitter_account_name_text']) && ( $mwb_twitter_general_details['mwb_twitter_account_name_text'] != '' || $mwb_twitter_general_details['mwb_twitter_account_name_text'] != null )){

				$mwb_twitter_user_settings = array(
					'consumer_key' => $mwb_twitter_general_details['mwb_twitter_consumer_appid'],
					'consumer_secret' => $mwb_twitter_general_details['mwb_twitter_consumer_appseceret'],
					'user_token' => $mwb_twitter_general_details['mwb_twitter_consumer_acess_token'],
					'user_secret' => $mwb_twitter_general_details['mwb_twitter_consumer_token_secret'],
					'curl_ssl_verifypeer'   => false
					);
				$mwb_twitter_object = new tmhOAuth($mwb_twitter_user_settings);

				$postfields = array();
				$mwb_twitter_get_home_tweet_list = 'https://api.twitter.com/1.1/statuses/home_timeline.json';
				$mwb_twitter_hometweet_response = $mwb_twitter_object->request('GET',$mwb_twitter_get_home_tweet_list,$postfields,true,true);
				$mwb_twitter_hometweet_response = json_decode($mwb_twitter_hometweet_response,true);
				if(is_array($mwb_twitter_hometweet_response) && ($mwb_twitter_hometweet_response['return_code'] == 200)){

					require_once MWB_TWITTER_PATH.'public/partials/mwb-twitter-home-tweets-iframe.php';
				}
				else{
					$mwb_twitter_not_found_tweets = __('Not Authenticate','mwb_twitter_for_wordpress');
					echo $mwb_twitter_not_found_tweets;
				}
			}
			else{
				$mwb_twitter_not_found_msgs = __('No TimeLine Tweets','mwb_twitter_for_wordpress');
				echo $mwb_twitter_not_found_msgs;
			}
		}
	}
}
?>