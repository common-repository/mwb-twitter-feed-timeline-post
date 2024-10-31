<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makewebbetter.com
 * @since             1.0.0
 * @package           mwb-twitter-for-wordpress
 *
 * @wordpress-plugin
 * Plugin Name:       MWB Twitter toolkit for wordpress -Tweets, Feeds, Timeline, posts
 * Plugin URI:        https://makewebbetter.com/mwb-twitter-toolkit-for-wordpress
 * Description:       Manage your twitter account directly from your dashboard and share your posts, products on your twitter account.
 * Version:           1.0.0
 * Author:            makewebbetter
 * Author URI:        http://makewebbetter.com
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       mwb_twitter_for_wordpress
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MWB_TWITTER_VERSION', '1.0.0' );
define( 'MWB_TWITTER_URL', plugin_dir_url(__FILE__) );
define( 'MWB_TWITTER_PATH', plugin_dir_path(__FILE__) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mwb_twitter_for_wordpress-activator.php
 */
function activate_mwb_twitter_for_wordpress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mwb_twitter_for_wordpress-activator.php';
	Mwb_twitter_for_wordpress_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mwb_twitter_for_wordpress-deactivator.php
 */
function deactivate_mwb_twitter_for_wordpress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mwb_twitter_for_wordpress-deactivator.php';
	Mwb_twitter_for_wordpress_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mwb_twitter_for_wordpress' );
register_deactivation_hook( __FILE__, 'deactivate_mwb_twitter_for_wordpress' );


/**
* Function for creating shortcodes for follow button
**/
function mwb_twitter_follow_button_shortcode($atts)
{
	$mwb_twitter_consumer_details = get_option('mwb_twitter_consumer_app_details',false);
	if(is_array($mwb_twitter_consumer_details) && isset($mwb_twitter_consumer_details['mwb_twitter_account_name_text']) && $mwb_twitter_consumer_details['mwb_twitter_account_name_text'] != ''){

		$mwb_twitter_consumer_twitter_handle = $mwb_twitter_consumer_details['mwb_twitter_account_name_text'];
		$mwb_twitter_consumer_twitter_handle = ltrim($mwb_twitter_consumer_twitter_handle, '@');

		return '<div><a class="mwb-twitter-follow-button twitter-follow-button" href="https://twitter.com/'.$mwb_twitter_consumer_twitter_handle.'" data-size="large" data-show-count="true" target="_blank"><i class="dashicons dashicons-twitter"></i> Follow '.$mwb_twitter_consumer_details['mwb_twitter_account_name_text'].'</a></div>';
	}
}
add_shortcode('MWB_FOLLOW_BUTTON','mwb_twitter_follow_button_shortcode');
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mwb_twitter_for_wordpress.php';
// include_once plugin_dir_path( __FILE__ ) . 'includes/class-twitter-for-wordpress-shortcode.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mwb_twitter_for_wordpress() {

	$plugin = new Mwb_twitter_for_wordpress();
	$plugin->run();

}
run_mwb_twitter_for_wordpress();

add_filter('plugin_action_links','mwb_twitter_wordpress_admin_settings', 10, 2 );

/**
 * Show settings link on plugin listing section
 * @since 1.0.0
 * @name mwb_twitter_wordpress_admin_settings()
 * @author makewebbetter<webmaster@makewebbetter.com>
 * @link http://www.makewebbetter.com/
 */
function mwb_twitter_wordpress_admin_settings($actions, $plugin_file){
	static $plugin;
	if (! isset ( $plugin )) {

		$plugin = plugin_basename ( __FILE__ );

	}
	if($plugin === $plugin_file){
		$settings = array (
			'settings' => '<a href="' . admin_url().'admin.php?page=mwb-twitter-for-wordpress-settings'. '">' . __ ( 'Settings', 'mwb_twitter_for_wordpress' ) . '</a>',
			);
		$actions = array_merge ( $settings, $actions );
	}
	return $actions;
}
