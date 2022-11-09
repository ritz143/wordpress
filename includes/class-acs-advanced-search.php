<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0
 *
 * @package    ACS_Advanced_Search
 * @subpackage ACS_Advanced_Search/includes
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
 * @since      1.0
 * @package    ACS_Advanced_Search
 * @subpackage ACS_Advanced_Search/includes
 * @author     ritesh jain <jainritesh143@gmail.com>
 */
class WP_ACS_Advanced_Search {

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
	 * The unique slug of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_text_domain    The string used to uniquely identify this plugin functions.
	 */
	protected $plugin_text_domain;
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

		$this->plugin_name = 'acs-advanced-custom-search';
		$this->version = '1.0.0';
                $this->plugin_text_domain = 'ACS';

		$this->load_dependencies_files();
		$this->set_text_domain();
		$this->define_backend_hooks();
		$this->define_frontend_hooks();

		//$this->define_widget_hooks();

	}
        
        /**
	 * Load the required dependencies files for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - ACS_Advanced_Search_i18n. Defines internationalization functionality.
	 * - ACS_Advanced_Search_Backend. Defines all hooks for the backend area.
	 * - ACS_Advanced_Search_Frontend. Defines all hooks for the frontend side of the site.
	 *
	 * 
	 *
	 * @since    1.0.0
	 * @access   private
	 */
        private function load_dependencies_files() {
            /**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-acs-advanced-search-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-acs-advanced-search-backend.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-acs-advanced-search-frontend.php';
        }
        
        /**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the ACS_Advanced_Search_i18n class in order to set the text domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_text_domain() {

		$ACS_plugin_loaded = new ACS_Advanced_Search_i18n();

		add_action( 'plugins_loaded', array($ACS_plugin_loaded, 'load_plugin_textdomain') );

	}
        
        /**
	 * Register all of the hooks related to the backend area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
        private function define_backend_hooks() {
            $ACS_plugin_backend = new ACS_Advanced_Search_Backend( $this->get_plugin_name(), $this->get_version() );
                add_action( 'admin_menu', array($ACS_plugin_backend, 'acs_option_page' ));
		add_action( 'admin_init', array($ACS_plugin_backend, 'acs_register_settings' ));
		add_action( 'admin_enqueue_scripts', array($ACS_plugin_backend, 'acs_enqueue_styles' ));
		add_action( 'admin_enqueue_scripts', array($ACS_plugin_backend, 'acs_enqueue_scripts' ));

                add_filter( 'get_search_form', array($ACS_plugin_backend, 'acs_override_search_form' ));
        }
        
        /**
	 * Register all of the hooks related to the frontend functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
        private function define_frontend_hooks() {
            $ACS_plugin_frontend = new ACS_Advanced_Search_Frontend( $this->get_plugin_name(), $this->get_version() );
                add_action( 'wp_enqueue_scripts', array($ACS_plugin_frontend, 'acs_frontend_enqueue_styles') );
                add_action( 'wp_enqueue_scripts', array($ACS_plugin_frontend, 'acs_frontend_enqueue_scripts') );
                add_action( 'get_header', array( $ACS_plugin_frontend, 'acs_check_posts_data' ) );
                add_filter( 'query_vars', array($ACS_plugin_frontend,'acs_custom_query_vars_filter' ));
                add_filter('pre_get_posts', array($ACS_plugin_frontend,'acs_filter_query_post_types'));
                add_filter('pre_get_posts', array($ACS_plugin_frontend,'acs_filter_query_taxonomy'));
                add_filter('pre_get_posts', array($ACS_plugin_frontend,'acs_filter_query_post_date'));
                add_shortcode( 'acs-advanced-custom-search', array($ACS_plugin_frontend, 'acs_advanced_custom_search_shortcode' ));
        }
        
        /**
	 * Retrieve the plugin name.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
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