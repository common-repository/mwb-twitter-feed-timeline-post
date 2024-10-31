<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://makewebbetter.com
 * @since      1.0.0
 *
 * @package     mwb-twitter-for-wordpress
 * @subpackage  mwb-twitter-for-wordpress/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    mwb_twitter_for_wordpress
 * @subpackage mwb_twitter_for_wordpress/public
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Mwb_twitter_for_wordpress_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mwb_twitter_for_wordpress-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mwb_twitter_for_wordpress-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/jquery-ui.js', array(), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/jquery-ui.min.js', array(), $this->version, false );
		wp_enqueue_script( 'mwb_twitter_text_share_js', plugin_dir_url( __FILE__ ) . 'js/quoteShare.min.js', array(), $this->version, false );

	}

	public function mwb_twitter_tweet_button($content)
	{	
		if(in_array('woocommerce/woocommerce.php',get_option('active_plugins',array()))){

			if(is_page('cart')){
				return $content;
			}
			elseif(is_checkout()){
				return $content;
			}
		}

		$mwb_twitter_general_details = get_option('mwb_twitter_consumer_app_details',false);
		$mwb_twitter_content = $content;
		$mwb_twitter_content = strip_tags($mwb_twitter_content);
		$mwb_twitter_tweet_button_content = '<div class="mwb-twitter-tweet-button"><a class="post-edit-link mwb-twitter-share-button" href="https://twitter.com/intent/tweet?text='.$mwb_twitter_content.'" target="_blank"><i class="dashicons dashicons-twitter mwb-twitter-icon"></i>Tweet</a></div>';

		if(is_array($mwb_twitter_general_details) && !empty($mwb_twitter_general_details)){
			if(isset($mwb_twitter_general_details['mwb_twitter_tweet_button_enable']) && $mwb_twitter_general_details['mwb_twitter_tweet_button_enable'] == 1){
				if($mwb_twitter_general_details['mwb_twitter_tweet_button_position'] == 'top'){

					return $mwb_twitter_tweet_button_content.$content;
				}
				elseif($mwb_twitter_general_details['mwb_twitter_tweet_button_position'] == 'bottom'){

					return $content.$mwb_twitter_tweet_button_content;

				}else{
					return $mwb_twitter_tweet_button_content.$content.$mwb_twitter_tweet_button_content;
				}
			}
		}
	}
}
