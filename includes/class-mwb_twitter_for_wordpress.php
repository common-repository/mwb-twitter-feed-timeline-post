<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://makewebbetter.com
 * @since      1.0.0
 *
 * @package     mwb-twitter-for-wordpress
 * @subpackage  mwb_twitter_for_wordpress/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package     mwb-twitter-for-wordpress
 * @subpackage  mwb_twitter_for_wordpress/includes
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Mwb_twitter_for_wordpress {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Mwb_twitter_for_wordpress_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'MWB_TWITTER_VERSION' ) ) {
			$this->version = MWB_TWITTER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'mwb_twitter_for_wordpress';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Mwb_twitter_for_wordpress_Loader. Orchestrates the hooks of the plugin.
	 * - Mwb_twitter_for_wordpress_i18n. Defines internationalization functionality.
	 * - Mwb_twitter_for_wordpress_Admin. Defines all hooks for the admin area.
	 * - Mwb_twitter_for_wordpress_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb_twitter_for_wordpress-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb_twitter_for_wordpress-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mwb_twitter_for_wordpress-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mwb_twitter_for_wordpress-public.php';

		$this->loader = new Mwb_twitter_for_wordpress_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Mwb_twitter_for_wordpress_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Mwb_twitter_for_wordpress_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Mwb_twitter_for_wordpress_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action('admin_menu',$plugin_admin,'mwb_tweet_admin_menu_page');
		$this->loader->add_action('widgets_init',$plugin_admin,'mwb_tweet_custom_latest_tweet_widget');
		$this->loader->add_action('wp_ajax_mwb_twitter_tweet_message_from_dashboard',$plugin_admin,'mwb_twitter_tweet_message_from_dashboard');
		$this->loader->add_action('wp_ajax_mwb_twitter_send_message_to_follower',$plugin_admin,'mwb_twitter_send_message_to_follower');
		$this->loader->add_action('wp_ajax_mwb_twitter_profile_settings',$plugin_admin,'mwb_twitter_profile_settings');
		$this->loader->add_action('init',$plugin_admin,'mwb_twitter_thickbox_view',10);
		
		$MwbTwitterSharepostonPublishEnable = get_option('mwb_twitter_share_post_on_publish',false);
		if(isset($MwbTwitterSharepostonPublishEnable) && $MwbTwitterSharepostonPublishEnable == 1){
			$this->loader->add_action( 'add_meta_boxes',$plugin_admin,'mwb_twitter_add_custom_message_meta_box' );
			$this->loader->add_action('transition_post_status',$plugin_admin,'mwb_twitter_share_all_posts_on_publish',10,3);
		}
		$this->loader->add_action('wp_ajax_mwb_twitter_save_message',$plugin_admin,'mwb_twitter_save_message');
		$this->loader->add_action('wp_ajax_nopriv_mwb_twitter_save_message',$plugin_admin,'mwb_twitter_save_message');
	}	


	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Mwb_twitter_for_wordpress_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_filter('the_content',$plugin_public,'mwb_twitter_tweet_button');

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Mwb_twitter_for_wordpress_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
