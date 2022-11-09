<?php

/**
 * The backend functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    ACS_Advanced_Search
 * @subpackage ACS_Advanced_Search/admin
 */

/**
 * The backend functionality of the plugin.
 *
 * Defines the plugin name, version, and  hooks for how to
 * enqueue the backend stylesheet and JavaScript.
 *
 * @package    ACS_Advanced_Search
 * @subpackage ACS_Advanced_Search/admin
 * @author     Ritesh Jain <jainritesh143@gmail.com>
 * 
 */
class ACS_Advanced_Search_Backend {
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
	 * Register the stylesheets for the backend area.
	 *
	 * @since    1.0.0
	 */
        public function acs_enqueue_styles( $hook ) {
            wp_enqueue_style( $this->plugin_name, WP_ACS_URL . 'assets/css/acs-admin.css', array(), $this->version, 'all' );
        }
        
        /**
	 * Register the scripts for the backend area.
	 *
	 * @since    1.0.0
	 */
        public function acs_enqueue_scripts( $hook ) {
            
        }
        
        /**
	 * Create the options page for the backend area.
	 *
	 * @since    1.0.0
	 */
        public function acs_option_page() {
           add_submenu_page ( 'options-general.php', 'ACS Advanced Search', 'ACS Advanced Search', 'manage_options', 'acs-advanced-search-options', array( $this, 'display_option_page' ) ); 
        }
        
        /**
	 * Display the option page for the backend area.
	 *
	 * @since    1.0.0
	 */
	public function display_option_page() {
            
               	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/acs-display-options-page.php';

	}
        
        /**
	 * ACS Register settings.
	 *
	 * @since    1.0.0
	 */
        public function acs_register_settings() {
            // Settings
		register_setting(
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings',
			array( $this, 'sanitize_setting_callback' )
		);
                /* Add Section */
                add_settings_section(
                        $this->plugin_name . '-settings-section',
                        __('Select Fields to include in Advanced Search', 'acs-advanced-custom-search' ),
                        array($this, 'acs_settings_section_callback'),
                        $this->plugin_name . '-settings'
                        );
                /* Add fields */
                add_settings_field(
			'acs-search-override',
			__( 'Do you want us to add advanced search link at your search field:', 'acs-advanced-custom-search' ),
			array( $this, 'acs_search_override_callback' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => $this->plugin_name . '-settings[acs-search-override]'
			)
		);
                
                 /* Author fields */
                add_settings_field(
			'acs-disable-search-field',
			__( 'Do you want to disable search input field:', 'acs-advanced-custom-search' ),
			array( $this, 'acs_search_input_disable_callback' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => $this->plugin_name . '-settings[acs-disable-search-field]'
			)
		);
                
                /* Author fields */
                add_settings_field(
			'acs-author-select-field',
			__( 'Do you want to disable author select box:', 'acs-advanced-custom-search' ),
			array( $this, 'acs_search_author_disable_callback' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => $this->plugin_name . '-settings[acs-author-select-field]'
			)
		);
                
                /*Posts per page*/
                   add_settings_field(
			'acs-post-per-page',
			__( 'Number of posts show on per page:', 'acs-advanced-custom-search' ),
			array( $this, 'acs_number_of_post_search_callback' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => $this->plugin_name . '-settings[acs-post-per-page]'
			)
		);
                   
                add_settings_field(
			'acs-custom-post-types',
			__( 'Select post types in which user can search:', 'acs-advanced-custom-search' ),
			array( $this, 'acs_post_types_search_callback' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => $this->plugin_name . '-settings[taxonomy]'
			)
		);
                add_settings_field(
			'taxonomy',
			__( 'Select taxonomies in which user can search:', 'acs-advanced-custom-search' ),
			array( $this, 'acs_taxonomies_search_callback' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => $this->plugin_name . '-settings[taxonomy]'
			)
		);
                 add_settings_field(
			'select-date-field',
			__( 'Select date field type for display in search form:', 'acs-advanced-custom-search' ),
			array( $this, 'acs_date_field_type_callback' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => $this->plugin_name . '-settings[select-date-field]'
			)
		);
                 add_settings_field(
			'acs-custom-css-field',
			__( 'Put custom css if you want to change in layout of advanced search form:', 'acs-advanced-custom-search' ),
			array( $this, 'acs_css_field_type_callback' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => $this->plugin_name . '-settings[css_field]'
			)
		); 
        }
        
        /**
	 * Sanitize callbacks.
	 *
	 * @since    1.0.0
	 */
	public function sanitize_setting_callback( $inputs ) {

		$sanitize_array = array();

		if ( isset( $inputs ) ) {
			foreach ( $inputs as $key => $value ) {
				if ( $key == 'taxonomy' || $key == 'acs-custom-post-types' || $key=='acs-custom-css-field' ) {
					$sanitize_array[ $key ] = $value;
				} 
                                else {
					$sanitize_array[ $key ] = sanitize_text_field( $value );
				}
			}
		}

		return $sanitize_array;

	}
        
        /**
	 * ACS Section callbacks.
	 *
	 * @since    1.0.0
	 */
	public function acs_settings_section_callback() {

		return;

	}
        
        /**
	 * Setting Field search override callbacks.
	 *
	 * @since    1.0.0
	 */
	public function acs_search_override_callback() {

		$options = get_option( $this->plugin_name . '-settings' );
		$option = 0;

		if ( ! empty( $options['acs-search-override'] ) ) {
			$option = $options['acs-search-override'];
		}

		?>

		<input type="checkbox" name="<?php echo $this->plugin_name . '-settings'; ?>[acs-search-override]" id="<?php echo $this->plugin_name . '-settings'; ?>[acs-search-override]" <?php checked( $option, 1, true ); ?> value="1" />

		<?php

	}
        
        /**
         * Search field disbale
        */
        public function acs_search_input_disable_callback(){
            $options = get_option( $this->plugin_name . '-settings' );
            $option = 0;
            if ( ! empty( $options['acs-disable-search-field'] ) ) {
			$option = $options['acs-disable-search-field'];
		}
                ?>
            <input type="checkbox" name="<?php echo $this->plugin_name . '-settings'; ?>[acs-disable-search-field]" id="<?php echo $this->plugin_name . '-settings'; ?>[acs-disable-search-field]" <?php checked( $option, 1, true ); ?> value="1" />    
        <?php
        }
        
        /**
         * Author field disable
         */
        public function acs_search_author_disable_callback(){
            $options = get_option( $this->plugin_name . '-settings' );
            $option = 0;
            if ( ! empty( $options['acs-author-select-field'] ) ) {
			$option = $options['acs-author-select-field'];
		}
                ?>
            <input type="checkbox" name="<?php echo $this->plugin_name . '-settings'; ?>[acs-author-select-field]" id="<?php echo $this->plugin_name . '-settings'; ?>[acs-author-select-field]" <?php checked( $option, 1, true ); ?> value="1" />    
        <?php
        }

        /**
         * Number of posts field
         */
        public function acs_number_of_post_search_callback(){
            $options = get_option( $this->plugin_name . '-settings' );
		$option = 0;
                if ( ! empty( $options['acs-post-per-page'] ) ) {
			$option = $options['acs-post-per-page'];
		}
            echo '<select name="'.$this->plugin_name . '-settings[acs-post-per-page]">';
                     echo '<option value="0">select</option>';
                     for($i=1;$i<=10;$i++){
                         $selected='';
                         if($option == $i){
                             $selected='selected="selected"';
                         }
                        echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
                     }
                                          
            echo '</select>';    
        }

        /**
	 * Setting Field taxonomy callbacks.
	 *
	 * @since    1.0.0
	 */
        public function acs_taxonomies_search_callback(){
                $options = get_option( $this->plugin_name . '-settings' );
                $option = array();
                if ( ! empty( $options['taxonomy'] ) ) {
			$option = $options['taxonomy'];
		}
                
                $acs_taxonomies = get_taxonomies( '', 'objects' );
                if ( ! empty( $acs_taxonomies ) && ! is_wp_error( $acs_taxonomies ) ) {
			foreach ( $acs_taxonomies as $tax ) {
				if ( $tax->name != 'nav_menu' && $tax->name != 'link_category' && $tax->name != 'post_format' && $tax->name != 'post_tag' ) {
					$checked="";
					$checked = in_array( $tax->name, $option ) ? 'checked="checked"' : '';

					?>

						<div class="acs-taxonomies">
							<input type="checkbox" name="<?php echo $this->plugin_name; ?>-settings[taxonomy][]" id="<?php echo $this->plugin_name; ?>-settings[taxonomy]" value="<?php echo esc_attr( $tax->name ); ?>" <?php echo $checked; ?> />
							<span class="acs-input-label"><?php echo esc_html( $tax->name ); ?></span>
						</div>

					<?php
				}
			}
		}
        }
        
        /**
         * Date field type like date or date range
         */
        public function acs_date_field_type_callback(){
            $options = get_option( $this->plugin_name . '-settings' );
            if ( ! empty( $options['select-date-field'] ) ) {
                $option = $options['select-date-field'];
                $date_selected = $date_range_selected ='';
                if(isset($option) && $option =='date'){ 
                    $date_selected = 'selected="selected"';
                 }
                 else if(isset($option) && $option =='date-range'){
                     $date_range_selected = 'selected="selected"';
                 }
              }
                        echo '<select name="'.$this->plugin_name . '-settings[select-date-field]">';
                                   echo '<option value="">select</option>
                                         <option value="date" '.$date_selected.'>Date</option>
                                          <option value="date-range" '.$date_range_selected.'>Date Range</option>';
                        echo '</select>';
		
        }
        
        /**
         * Select post types
         */
        public function acs_post_types_search_callback(){
            $options = get_option( $this->plugin_name . '-settings' );
                $option = array();
                if ( ! empty( $options['acs-custom-post-types'] ) ) {
			$option = $options['acs-custom-post-types'];
		}
            $args = array('public'   => true);
	    $output = 'object'; // names or objects, note names is the default
            $operator = 'and'; // 'and' or 'or'

            $post_types_objs = get_post_types( $args, $output, $operator );
            //echo '<pre>'; print_r($post_types_objs);die;
            if ( ! empty( $post_types_objs ) && ! is_wp_error( $post_types_objs ) ) {
                foreach ( $post_types_objs  as $post_type )
                {   $checked='';
                    if($post_type->name!="attachment"){
                        $checked = in_array( $post_type->name, $option ) ? 'checked="checked"' : '';
                        ?>
                       <div class="acs-post-types">
			<input type="checkbox" name="<?php echo $this->plugin_name; ?>-settings[acs-custom-post-types][]" id="<?php echo $this->plugin_name; ?>-settings[acs-custom-post-types]" value="<?php echo esc_attr( $post_type->name ); ?>" <?php echo $checked; ?> />
			<span class="acs-input-label"><?php echo esc_html( $post_type->labels->name ); ?></span>
			</div> 
                  <?php  }
                }
            }
        }
        
        /**
         * Css textara field
        */
        public function acs_css_field_type_callback(){
            $options = get_option( $this->plugin_name . '-settings' );
            $option="";
            if ( ! empty( $options['acs-custom-css-field'] ) ) {
			$option = $options['acs-custom-css-field'];
                        $option = str_replace('<br />', "\n", $option);
		}
            ?>
                
            <div class="acs-css-area">
              
                <textarea id="acs-custom-css" name="<?php echo $this->plugin_name; ?>-settings[acs-custom-css-field]" row="30" cols="20" placeholder="For example:-  .acs-advanced-search-form{width:100%}"><?php echo $option;?></textarea>
             </div>

        <?php        
        }

        /**
         * Get page url by shortcode
         * 
         * @since    1.0.0
         */
        public function acs_get_page_by_shortcode($shortcode){
            global $wpdb;

            $url = '';
            $sql = $wpdb->prepare('SELECT ID
                    FROM ' . $wpdb->posts . '
                    WHERE
                            post_type = "page"
                            AND post_status="publish"
                            AND post_content LIKE %s','%'.$shortcode.'%');
           
            if ($id = $wpdb->get_var($sql)) {
                   $url = get_permalink($id);
            }

            return $url;
        }
        
       /**
	 * Override search form with custom search form.
	 *
	 * @since    1.0.0
	 */
	public function acs_override_search_form( $html ) {
           $unique_key = esc_attr( uniqid( 'search-form-' ) );
           $options = get_option( $this->plugin_name . '-settings' );
           $override_form =0;
           $override_form = isset($options['acs-search-override']) ? $options['acs-search-override']:"";
           $acs_page_url = $this->acs_get_page_by_shortcode('[acs-advanced-custom-search]');
           $link =  "javascript:void(0);";
           $advance_search_link ="";
           if ( !empty($acs_page_url) ) { 
                 $link = $acs_page_url;
                 $advance_search_link = '<a href="'.$link.'">Advanced Search</a>';
           }
           
           if ( $override_form == 1 ) {
           $html = '<form role="search" method="get" class="search-form acs-advanced-search-form" action="'.esc_url( home_url( '/' ) ).'">';
           $html .= '<input type="search" class="search-field" id="'.esc_attr($unique_key).'" placeholder="' . esc_attr_x( 'Search...', 'placeholder', 'wp-advanced-custom-search' ) . '" name="s" />';
           $html .='<button type="submit" class="search-submit"><span class="screen-text">'._x( 'Search', 'submit button', 'wp-advanced-custom-search' ).'</span></button>';
           $html .= '</form>';
           $html .= $advance_search_link;
            return $html;
           }
           else {
               return $html;
           }
        }
}

