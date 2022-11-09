<?php

/**
 * The frontend functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    ACS_Advanced_Search
 * @subpackage ACS_Advanced_Search/public
 */

/**
 * The frontend functionality of the plugin.
 *
 * Defines the plugin name, version, and  hooks for how to
 * enqueue the frontend stylesheet and JavaScript.
 *
 * @package    ACS_Advanced_Search
 * @subpackage ACS_Advanced_Search/includes
 * @author     Ritesh Jain <jainritesh143@gmail.com>
 * 
 */

class ACS_Advanced_Search_Frontend {
    
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
         * Form vars
         * @var type 
         */
        private $acs_form_posted = false;
	private $acs_hasqmark = false;
        private $acs_urlparams = "/";
        private $searchvalue = "";
        
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
	 * Register the stylesheets for the frontend area.
	 *
	 * @since    1.0.0
	 */
        public function acs_frontend_enqueue_styles( $hook ) {
            wp_enqueue_style( $this->plugin_name, WP_ACS_URL . 'assets/css/acs-front.css', array(), $this->version, 'all' );
            wp_enqueue_style( $this->plugin_name.'inline', WP_ACS_URL . 'assets/css/acs-inline-front.css', array(), $this->version, 'all' );
            $options = get_option( $this->plugin_name . '-settings' );
            $custom_css="";
            if ( ! empty( $options['acs-custom-css-field'] ) ) {
                    $custom_css = $options['acs-custom-css-field'];
                    wp_add_inline_style( $this->plugin_name.'inline', $custom_css );
		}
            
        }
        
        /**
	 * Register the scripts for the frontend area.
	 *
	 * @since    1.0.0
	 */
        public function acs_frontend_enqueue_scripts( $hook ) {
            wp_enqueue_style( 'jquery.ui',WP_ACS_URL . 'assets/css/jquery-ui.css' , array(), $this->version, 'all' );
            wp_enqueue_script( 'jquery-ui-datepicker' );
            wp_enqueue_script($this->plugin_name, WP_ACS_URL . 'assets/js/acs-utility.js', array('jquery'));
        }
        
        /**
         * 
         * @param type $vars
         */
        public function acs_custom_query_vars_filter($vars){
            $vars[] = 'post_date';
            $vars[] = 'post_types';
            $vars[] = 'cat_name';
            $vars[] = 'author_id';
            return $vars;
        }
        
        /**
         * check the form has been submitted and handle vars
         * 
         * @since    1.0.0
         */
        public function acs_check_posts_data(){
            
            if(isset($_POST[ACS_SF.'submitted']))
		{
		 if($_POST[ACS_SF.'submitted']==="1")
			{
			 //set var to confirm the form was posted
			    $this->acs_form_posted = true;
			}
                      
		}
              
            /* SEARCH BOX */
            if((isset($_POST[ACS_SF.'s']))&&($this->acs_form_posted))
            {
                    $this->searchvalue = trim(stripslashes($_POST[ACS_SF.'s']));

                    if($this->searchvalue!="")
                    {
                            if(!$this->acs_hasqmark)
                            {
                                    $this->acs_urlparams .= "?";
                                    $this->acs_hasqmark = true;
                            }
                            else
                            {
                                    $this->acs_urlparams .= "&";
                            }
                            $this->acs_urlparams .= "s=".urlencode($this->searchvalue);
                            //$this->hassearchquery = true;
                    }
            }    
                
            /*Custom Post Type*/  
            if((isset($_POST[ACS_SF.'post_types']))&&($this->acs_form_posted))
            {
                $search_post_types = ($_POST[ACS_SF.'post_types']);
                //make the post an array for easy looping
		if(!is_array($search_post_types))
		{
		$post_types_arr[] = $search_post_types;
		}
		else
		{
		$post_types_arr = $search_post_types;
		}
                
                if(count($post_types_arr)>0){
                    $post_types = $search_post_types;
                    if(!$this->acs_hasqmark)
			{
			$this->acs_urlparams .= "?";
			$this->acs_hasqmark = true;
			}
			else
			{
			$this->acs_urlparams .= "&";
			}
			$this->acs_urlparams .= "post_types=".$post_types;
                }
               
            }
            
            /*Taxonomy*/
            if((isset($_POST[ACS_SF.'taxonomy_category']))&&($this->acs_form_posted))
            {
                $search_category = ($_POST[ACS_SF.'taxonomy_category']);
                if(!is_array($_POST[ACS_SF.'taxonomy_category']))
		{
                    $cat_arr[] = $search_category;
		}
		else
		{
                    $cat_arr = $search_category;
		}
                if(count($cat_arr)>0)
                {
                    if(count($cat_arr) ==1){
                        if($cat_arr[0]!=""){
                        $categories =$cat_arr[0];
                        }
                    }
                    else {
                        $categories = implode(',',$cat_arr);
                    }
                    
                }
                if(!$this->acs_hasqmark)
		{
                    $this->acs_urlparams .= "?";
                    $this->acs_hasqmark = true;
                }
                else
                {
                        $this->acs_urlparams .= "&";
                }
                $this->acs_urlparams .= "cat_name=".$categories;
                }
            
            /* POST DATE */
            if((isset($_POST[ACS_SF.'post_date']))&&($this->acs_form_posted))
              {
                 $search_post_date = ($_POST[ACS_SF.'post_date']);

		//make the post an array for easy looping
		if(!is_array($search_post_date))
		{
			$post_date_array[] = $search_post_date;
		}
		else
		{
			$post_date_array = $search_post_date;
		}
                if(count($post_date_array) >0){
                    if(count($post_date_array)==2){
                        if(($post_date_array[0]!="")&&($post_date_array[1]==""))
			  {
			        $post_date = $post_date_array[0];
			  }
			else if($post_date_array[1]=="")
			  {    //check if second date range is blank then remove the array element
				unset($post_date_array[1]);
			   }
			else if($post_date_array[0]=="")
                        {
		                $post_date = $post_date_array[1];
			}
			else
			{
				 $post_date = implode("+",array_filter($post_date_array));
			}
                    }
                    else{
                        $post_date = $post_date_array[0];
                    }
                    if(isset($post_date))
		    {
			 if($post_date!="")
			  {
				if(!$this->acs_hasqmark)
				{
				$this->acs_urlparams .= "?";
				$this->acs_hasqmark = true;
				}
				else
				{
				$this->acs_urlparams .= "&";
				}
				$this->acs_urlparams .= "post_date=".$post_date;
			  }
		    }
                }
              }
              
            /* Post Author*/  
            if((isset($_POST[ACS_SF.'author_name']))&&($this->acs_form_posted))
            {
                $this->authorvalue = trim(stripslashes($_POST[ACS_SF.'author_name']));
                 if($this->authorvalue!="")
                    {
                            if(!$this->acs_hasqmark)
                            {
                                    $this->acs_urlparams .= "?";
                                    $this->acs_hasqmark = true;
                            }
                            else
                            {
                                    $this->acs_urlparams .= "&";
                            }
                            $this->acs_urlparams .= "author_id=".urlencode($this->authorvalue);
                            
                    }
            }
              //form posted
              if($this->acs_form_posted){
                 if($this->acs_urlparams=="/"){
                     $this->acs_urlparams .= "?s=";
                 }
                 //echo home_url().$this->acs_urlparams; die;
                 wp_redirect((home_url().$this->acs_urlparams));
              }
        }
        
        /*
         * Filter posts older than today
         * 
         * @since    1.0.0
         */
        public function acs_filter_where( $where = '' ) {
            global $wp_query;
            $post_date = explode("+", esc_attr(urlencode($wp_query->query['post_date'])));
            if (count($post_date) > 1 && $post_date[0] != $post_date[1]){
               $acs_post_dates = array();
                if(!empty($post_date[0])){
                   $acs_post_dates['after'] = date('Y-m-d 00:00:00', strtotime($post_date[0]));
                }
                if(!empty($post_date[1])){
                   $acs_post_dates['before'] = date('Y-m-d 00:00:00', strtotime($post_date[1]));
                }
             }
            $where .= " AND post_date >='" . $acs_post_dates['after'] . "' AND post_date <='" .  $acs_post_dates['before'] . "'";

            return $where;
        }
        
        /**
         * Filter post by post dates
         * 
         * @since    1.0.0
         */
        public function acs_filter_query_post_date($query){
            global $wp_query;
            if( ! is_admin() && $query->is_main_query() ) {
              if(isset($wp_query->query['post_date'])){
                $post_date = explode("+", esc_attr(urlencode($wp_query->query['post_date'])));
                    if(!empty($post_date)){
                        //$query->set( 'order', 'ASC' );
                        if(count($post_date) >1 && $post_date[0]!= $post_date[1]){
                            if((!empty($post_date[0]))&&(!empty($post_date[1]))){
                              add_filter( 'posts_where', array($this,'acs_filter_where' ));
                              // Remove the filter after it is executed.
                              add_action('posts_selection', array($this,'acs_remove_limit_date_range_query'));
                            }

                       }
                       else {
                           if (!empty($post_date[0]))
				{
				 $post_time = strtotime($post_date[0]);
			         $query->set('year', date('Y', $post_time));
				 $query->set('monthnum', date('m', $post_time));
                                 $query->set('day', date('d', $post_time));
				}
                       }
                     }
            }
          }
         return $query;
        }
        
        /**
         * Filter posts by post type
         */
        public function acs_filter_query_post_types($query){
            global $wp_query;
            $options = get_option( $this->plugin_name . '-settings' );
	    $option = 0;
            if ( ! empty( $options['acs-post-per-page'] ) ) {
			$option = $options['acs-post-per-page'];
            }
            if( ! is_admin() && $query->is_main_query() ) 
            {
                if(isset($wp_query->query['post_types']))
                { 
                 $search_all =false;
                 $post_types = explode(",",esc_attr($wp_query->query['post_types']));
                //print_r($post_types); 
                 if(count($post_types)>0)
                 {
                     if(count($post_types)==1)
			{
			if($post_types[0]=="all")
			{
                            $search_all = true;
			}
			}
                 }
                 
                 if($search_all)
		 {
            
                    $post_types = get_post_types( '' ,'names' );
                    unset($post_types['attachment']);
                    unset($post_types['revision']);
                    unset($post_types['nav_menu_item']);
                    $query->set('post_type', $post_types); 
		 }
                 else
		 {
		       $query->set('post_type', $post_types); 
		 }
                }
                if(isset($wp_query->query['author_id'])){
                    $author_id = esc_attr($wp_query->query['author_id']);
                    $query->set('author', $author_id);
                }
                
                if($option >0){
                   $query->set( 'posts_per_page', $option );
                }
                
            }
        }


        /**
	 * Remove the filter after it run so that it doesn't affect any other queries.
	 *
	 */
	function acs_remove_limit_date_range_query()
	{
	 remove_filter( 'posts_where', 'acs_filter_where' );
	}
        
        /**
         * Filter posts by taxonomy
         */
        
        public function acs_filter_query_taxonomy($query){
            global $wp_query;
            $options = get_option( $this->plugin_name . '-settings' );
            $taxonomies = array();
            if ( ! empty( $options['taxonomy'] ) ) {
                    $taxonomies = $options['taxonomy'];
            }
            if( ! is_admin() && $query->is_main_query() ) 
            {
                if(isset($wp_query->query['cat_name']))
                {
                 $search_all =false;
                 $taxonomy_cat = explode(',',esc_attr($wp_query->query['cat_name']));
                 
                 if(count($taxonomy_cat)>0){
                                         
                       $cat_search = array(
				     'relation' => 'OR'
				);
                       if(count($taxonomy_cat)==1){
                           if($taxonomy_cat[0]=='all'){
                            $terms = get_terms( $taxonomies, array( 'hide_empty' => false, 'fields' => 'ids' ) );
                            foreach ( $taxonomies as $tax ) {
                            $cat_search[] = array(
                                        'taxonomy' => $tax,
                                        'field'    => 'term_id',
                                        'terms'    => $terms
                                );
                            }
                           }
                           else if(!is_numeric($taxonomy_cat[0])){
                            $terms = get_terms( $taxonomy_cat[0], array( 'hide_empty' => false, 'fields' => 'ids' ) );
                            $cat_search[] = array(
                                        'taxonomy' => $taxonomy_cat[0],
                                        'field'    => 'term_id',
                                        'terms'    => $terms
                                );
                         }
                         else {
                            if(is_numeric($taxonomy_cat[0])){
                             foreach ( $taxonomies as $tax ) {
                                $cat_search[] = array(
                                            'taxonomy' => $tax,
                                            'field'    => 'term_id',
                                            'terms'    => $taxonomy_cat[0]
                                    ); 
                             }
                            }
                         }
                       }
                       else if(count($taxonomy_cat)>1){
                         foreach ( $taxonomy_cat as $cat ) {
                           if(!is_numeric($cat)){
                            $terms = get_terms( $cat, array( 'hide_empty' => false, 'fields' => 'ids' ) );
                            $cat_search[] = array(
                                        'taxonomy' => $cat,
                                        'field'    => 'term_id',
                                        'terms'    => $terms
                                );
                           }
                           else if(is_numeric($cat)){
                            foreach ( $taxonomies as $tax ) {
                                $cat_search[] = array(
                                            'taxonomy' => $tax,
                                            'field'    => 'term_id',
                                            'terms'    => $cat
                                    ); 
                             }
                           }
                         } 
                       }
                 }
                 
                 $query->set( 'tax_query', $cat_search );
		 return $query;
                }
            }
        }

        /**
	 * Create advanced search form.
	 *
	 * @since    1.0.0
	 */
        public function acs_advanced_custom_search_shortcode(){
            $options = get_option( $this->plugin_name . '-settings' );
            $override_form = isset($options['acs-search-override']) ? $options['acs-search-override']:"";
            $post_type_option=array();
            $taxonomy_option = array();
             if ( ! empty( $options['acs-custom-post-types'] ) ) {
			$post_type_option = $options['acs-custom-post-types'];
		}
            if ( ! empty( $options['taxonomy'] ) ) {
			$taxonomy_option = $options['taxonomy'];
	    }    
            $unique_key = esc_attr( uniqid( 'search-form-' ) );
           $form ='';
           $form = '<form role="search" method="post" class="acs-search-form acs-advanced-search-form" action="">';
           $form .= '<ul>';
           if ( empty( $options['acs-disable-search-field'] ) ) {
	   $form .= '<li>';
           $form .= '<input type="search" class="search-field" id="'.esc_attr($unique_key).'" placeholder="' . esc_attr_x( 'Search...', 'placeholder', 'wp-advanced-custom-search' ) . '" name="'.ACS_SF.'s" />';
           $form .= '</li>';
           }
           $form .= $this->build_custom_post_type_fields('Post Types',$name='post_types',$types='dropdown', $post_type_option);
           $form .= $this->build_taxonomy_dropdown_fields('Categories',$name='taxonomy_category',$types='dropdown', $taxonomy_option);
           if ( empty( $options['acs-author-select-field'] ) ) {
           $form .= $this->build_authors_dropdown_fields('Author',$name='author_name',$types='dropdown', 1);
           }
           if ( ! empty( $options['select-date-field'] ) ) {
           $field= $options['select-date-field'];
           $form .= $this->build_post_date_fields('Post Date',$name='post_date',$types='date-field', $field);
           }
           if(empty( $options['acs-disable-search-field']) || !empty($post_type_option) || !empty($taxonomy_option) || ! empty( $options['select-date-field'] ) || empty( $options['acs-author-select-field'] ) ){
           $form .= '<li>';
           $form .='<input type="hidden" name="'.ACS_SF.'submitted" value="1">';
           $form .='<button type="submit" class="acs-search-submit"><span class="screen-text">'._x( 'Search', 'submit button', 'wp-advanced-custom-search' ).'</span></button>';
           $form .= '</li>';
           }
           $form .= '</ul>';
           $form .= '</form>';
           
           return $form;
        }
        
        /*
         * Post Date Fields
         */
        public function build_post_date_fields($label,$name,$type,$field){
            $returnvar ='';
            $returnvar .= "<li>";
			
		if($label!="")
		{
                    $returnvar .= "<label>".$label."</label>";
		}
            if(isset($field) && $field =='date')
            {    
            $returnvar .= '<input id="acs-d1" class="acs-form" type="date" name="'.ACS_SF.$name.'[]" value="" readonly placeholder="Date"/>';
            }
            if(isset($field) && $field =='date-range'){
            $returnvar .= '<input id="acs-d1" class="acs-form" type="date" name="'.ACS_SF.$name.'[]" value="" readonly placeholder="Date From"/>';
            $returnvar .='</li><li style="margin-top: 10px;">';
            $returnvar .= '<input id="acs-d2" class="acs-form" type="date" name="'.ACS_SF.$name.'[]" value="" readonly placeholder="Date End"/>';
            }
            $returnvar .= "</li>";
            return $returnvar;
            
        }
        
        /**
         * Custom post types select box
         */
        public function build_custom_post_type_fields($label,$name,$type,$post_types){
            $returnvar ='';
            $returnvar .= "<li>";
            if(!empty($post_types) && count($post_types)>0){
            if($label!="")
		{
                    $returnvar .= "<label>".$label."</label>";
		}
            if(count($post_types)>0)
		{
                    $defaultval = implode(",",$post_types);
		}
		else
		{
                    $defaultval = "all";
		}    
            $returnvar .= '<select class="acs-form" name="'.ACS_SF.$name.'">';
            $returnvar .= '<option class="level-0" value="'.$defaultval.'">All Post Types</option>';
            if(!empty($post_types)){
            foreach($post_types as $posts){
                $obj = get_post_type_object( $posts );
                $returnvar .= '<option class="level-0" value="'.$posts.'">'.$obj->labels->singular_name.'</option>';
            }
            }
            $returnvar .= "</select>";
            }
            else {
               $returnvar .= '<input class="acs-form" type="hidden" name="'.ACS_SF.$name.'" value="all"/>'; 
            }
            $returnvar .= "</li>";
            return $returnvar;
        }
        
        /**
         * Categories select box
         */
        public function build_taxonomy_dropdown_fields($label,$name,$type,$taxonomies){
            $returnvar ='';
             if ( ! empty( $taxonomies ) ) {
            $returnvar .= "<li>";
            if($label!="")
		{
                    $returnvar .= "<label>".$label."</label>";
		}
            $defaultval = "all";
           
                $returnvar .= '<select id="'.ACS_SF.$name.'" name="'.ACS_SF.$name.'[]" multiple="multiple">';
                $returnvar .= '<option class="level-0" value="'.$defaultval.'">All Categories</option>';
                foreach ( $taxonomies as $tax ) {
                    $tax_arg = get_taxonomy( $tax );
                    $tax_name = isset($tax_arg->labels->name) ? $tax_arg->labels->name:"";
                    if(!empty($tax_name)){
                     $returnvar .= '<option class="acs-level-tax" value="'.$tax.'">'.$tax_name.'</option>';
                    }
                  $terms = get_terms( $tax, array(  'hide_empty' => false ) );
                  
                  if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                      foreach ( $terms as $term ) {
			$returnvar .= '<option class="acs-cat" value="' . esc_attr( $term->term_id ) . '">' . esc_html( $term->name ) . '</option>';
		    }
                  }
                }
            }
            $returnvar .= '</select>';
            $returnvar .= "</li>";
            return $returnvar;    
        }
        
        /**
         * Authors select box
        */
        
       public function build_authors_dropdown_fields($label,$name,$type,$author_search){
           $returnvar ='';
             if ( ! empty( $author_search ) ) {
             $returnvar .= "<li>";
                if($label!="")
                    {
                        $returnvar .= "<label>".$label."</label>";
                    }
               $allUsers = get_users('orderby=post_count&order=DESC');
               if(!empty($allUsers)){
                   $returnvar .= '<select class="acs-form" name="'.ACS_SF.$name.'">';
                   $returnvar .= '<option class="acs-level-author" value="">Select Author</option>';
                    foreach ($allUsers as $authors){
                      $returnvar .= '<option class="acs-level-author" value="'.$authors->ID.'">'.$authors->display_name.'</option>';
                    }
                    $returnvar .= '</select>';
               }
            $returnvar .= "</li>";
             
             }
             return $returnvar;
       }
}  