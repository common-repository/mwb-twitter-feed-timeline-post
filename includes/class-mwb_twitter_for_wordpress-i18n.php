<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://makewebbetter.com
 * @since      1.0.0
 *
 * @package     mwb-twitter-for-wordpress
 * @subpackage  mwb_twitter_for_wordpress/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package     mwb-twitter-for-wordpress
 * @subpackage  mwb_twitter_for_wordpress/includes
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Mwb_twitter_for_wordpress_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'mwb_twitter_for_wordpress',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
