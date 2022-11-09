<?php

/**
 * @since    1.0
 * @package  ACS_Advanced_Search
 *
 * Plugin Name: ACS Advanced Search
 * Plugin URI:  
 * Description: Add custom form for Advanced Custom Search anywhere in page,posts,sidebar. Search and Filtering for Posts and Pages by author, Categories, Taxonomies, Post Dates, Post Dates Range  and Post Types.
 * Version:     1.0
 * Author:      Ritesh Jain,Lokesh Solanki
 * Text Domain: acs-advanced-search
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * wp-acl
 *
 * @author   Ritesh Jain
 */ 
/* Define plugin constants */
if (!defined('WP_ACS_DIR')) {
    //Plugin path
     define('WP_ACS_DIR', plugin_dir_path(__FILE__));
}
if (!defined('WP_ACS_URL')) {
    //Plugin url
    define('WP_ACS_URL', plugin_dir_url(__FILE__));
}

//form prefix for plugin
if (!defined('ACS_SF'))
    define('ACS_SF', 'wp_acs_');
/**
 * The code that runs during plugin activation.
 */
function activate_acs_advanced_search() {
    if(!get_option('acs-advanced-custom-search-settings')){
        $option['acs-search-override'] = 1;
	add_option('acs-advanced-custom-search-settings', $option);
    }
}
/**
 * The code that runs during plugin deactivation.
 */
function deactivate_acs_advanced_search() {
	
}

register_activation_hook( __FILE__, 'activate_acs_advanced_search' );
register_deactivation_hook( __FILE__, 'deactivate_acs_advanced_search' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require WP_ACS_DIR . '/includes/class-acs-advanced-search.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_acs_advanced_search() {
	$WP_ACS = new WP_ACS_Advanced_Search();
	}
run_acs_advanced_search();