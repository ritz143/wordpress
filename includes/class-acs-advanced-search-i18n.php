<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 *
 * @package    ACS_Advanced_Search
 * @subpackage ACS_Advanced_Search/includes
 */

/**
 * Define the internationalization functionality.
 *
 * @since      1.0.0
 * @package    ACS_Advanced_Search
 * @subpackage ACS_Advanced_Search/includes
 * @author     Ritesh Jain <jainritesh143@gmail.com>
 */
class ACS_Advanced_Search_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
                   
		load_plugin_textdomain(
			'acs-advanced-custom-search',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) )
		);

	}

}