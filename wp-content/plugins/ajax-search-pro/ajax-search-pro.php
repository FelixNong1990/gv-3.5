<?php
/*
Plugin Name: Ajax Search Pro
Plugin URI: http://wp-dreams.com
Description: The most powerful ajax powered search engine for WordPress.
Version: 2.4
Author: Ernest Marcinko
Author URI: http://wp-dreams.com
*/
?>
<?php

  define( 'AJAXSEARCHPRO_PATH', plugin_dir_path(__FILE__) );
  define( 'AJAXSEARCHPRO_DIR', 'ajax-search-pro');
  define( 'AJAXSEARCHPRO_CURRENT_VERSION', 240);
  define( 'AJAXSEARCHPRO_DEBUG', 0);
  
  global $asp_admin_pages;
   
  $asp_admin_pages = array(
    "ajax-search-pro/backend/settings.php",
    "ajax-search-pro/backend/statistics.php",
    "ajax-search-pro/backend/comp_check.php",
    "ajax-search-pro/backend/fulltext.php",
    "ajax-search-pro/backend/cache_settings.php",
    "ajax-search-pro/backend/compatibility_settings.php"
  );
                                                               
  require_once(AJAXSEARCHPRO_PATH."/functions.php");
  require_once(AJAXSEARCHPRO_PATH."/backend/settings/functions.php");
  require_once(AJAXSEARCHPRO_PATH."/includes/wpdreams-fulltext.class.php");
  
  /*A headerbe érkezõ scripteket és css fájlokat csak itt lehet hozzáadni, alpageken nem! Ott már az az action lefutott! */
 
  /* Includes only on ASP ajax requests  */
  if (isset($_POST) && isset($_POST['action']) && 
      (
        $_POST['action']=='ajaxsearchpro_search' ||
        $_POST['action']=='ajaxsearchpro_autocomplete' ||
        $_POST['action']=='ajaxsearchpro_preview' ||
        $_POST['action']=='ajaxsearchpro_precache'||
        $_POST['action']=='ajaxsearchpro_deletecache'
      )
      ) {
    require_once(AJAXSEARCHPRO_PATH."/search.php");
    return;
  } 
  
  
  $funcs = new ajaxsearchproFuncCollector();
  
  /* Includes only on ASP admin pages */
  if (wpdreams_on_backend_page($asp_admin_pages)==true) {
    require_once(AJAXSEARCHPRO_PATH."/backend/settings/types.inc.php");
    require_once(AJAXSEARCHPRO_PATH."/includes/compatibility.class.php");
    require_once(AJAXSEARCHPRO_PATH."/compatibility.php");
    add_action( 'admin_enqueue_scripts', array($funcs, 'scripts') );
  }
  
  /* Includes only on full backend, frontend, non-ajax requests */
  if (is_admin() || (!is_admin() && !isset($_POST['action']) )) {
    require_once(AJAXSEARCHPRO_PATH."/backend/settings/default_options.php");
    require_once(AJAXSEARCHPRO_PATH."/backend/settings/admin-ajax.php");
    require_once(AJAXSEARCHPRO_PATH."/includes/shortcodes.php");
    
     
    add_action( 'admin_menu', array($funcs, 'navigation_menu') );
    register_activation_hook( __FILE__, array($funcs, 'ajaxsearchpro_activate') );
    add_action('wp_print_styles', array($funcs, 'styles'));
    add_action('wp_enqueue_scripts', array($funcs, 'scripts'));
    add_action('wp_footer', array($funcs, 'footer'));
  }
    
  require_once(AJAXSEARCHPRO_PATH."/includes/widgets.php");
  
  
  class ajaxsearchproFuncCollector {
  
    function ajaxsearchpro_activate() {

      global $wpdb;
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      $table_name = $wpdb->prefix . "ajaxsearchpro";
      $query = "
        CREATE TABLE IF NOT EXISTS `$table_name` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` text NOT NULL,
          `data` text NOT NULL,
          PRIMARY KEY (`id`)                                          
        ) DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
      "; 
      dbDelta($query);
      $table_name = $wpdb->prefix . "ajaxsearchpro_statistics";
      $query = "
        CREATE TABLE IF NOT EXISTS `$table_name` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `search_id` int(11) NOT NULL,
          `keyword` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
          `num` int(11) NOT NULL,
          `last_date` int(11) NOT NULL,
          PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
      "; 
      dbDelta($query);
      $query = "ALTER TABLE `$table_name` MODIFY `keyword` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL"; 
      dbDelta($query);
      $wpdb->query($query);
      $this->fulltext();
    }
        
    function navigation_menu() {
      if (current_user_can('manage_options'))  {
      	if (!defined("EMU2_I18N_DOMAIN")) define('EMU2_I18N_DOMAIN', "");
        add_menu_page( 
      	 __('Ajax Search Pro', EMU2_I18N_DOMAIN),
      	 __('Ajax Search Pro', EMU2_I18N_DOMAIN),
      	 'manage_options',
      	 AJAXSEARCHPRO_DIR.'/backend/settings.php',
      	 '',
      	 plugins_url('/icon.png', __FILE__),
         "207.9"
        );   
      	add_submenu_page( 
        	AJAXSEARCHPRO_DIR.'/backend/settings.php',
        	__("Ajax Search Pro", EMU2_I18N_DOMAIN),
        	__("Search Statistics", EMU2_I18N_DOMAIN),
        	'manage_options',
        	AJAXSEARCHPRO_DIR.'/backend/statistics.php'
      	);
      	add_submenu_page( 
        	AJAXSEARCHPRO_DIR.'/backend/settings.php',
        	__("Ajax Search Pro", EMU2_I18N_DOMAIN),
        	__("Error check", EMU2_I18N_DOMAIN),
        	'manage_options',
        	AJAXSEARCHPRO_DIR.'/backend/comp_check.php'
      	);
      	add_submenu_page( 
        	AJAXSEARCHPRO_DIR.'/backend/settings.php',
        	__("Ajax Search Pro", EMU2_I18N_DOMAIN),
        	__("Fulltext search Settings", EMU2_I18N_DOMAIN),
        	'manage_options',
        	AJAXSEARCHPRO_DIR.'/backend/fulltext.php'
      	);        
      	add_submenu_page( 
        	AJAXSEARCHPRO_DIR.'/backend/settings.php',
        	__("Ajax Search Pro", EMU2_I18N_DOMAIN),
        	__("Cache Settings", EMU2_I18N_DOMAIN),
        	'manage_options',
        	AJAXSEARCHPRO_DIR.'/backend/cache_settings.php'
      	);
      	add_submenu_page( 
        	AJAXSEARCHPRO_DIR.'/backend/settings.php',
        	__("Ajax Search Pro", EMU2_I18N_DOMAIN),
        	__("Compatibility Settings", EMU2_I18N_DOMAIN),
        	'manage_options',
        	AJAXSEARCHPRO_DIR.'/backend/compatibility_settings.php'
      	);           
      }          
    }
     
    function styles() {
      wp_register_style('wpdreams-scroller', plugin_dir_url(__FILE__).'css/jquery.mCustomScrollbar.css');
      wp_enqueue_style('wpdreams-scroller');   
    }
    
    function scripts() {
      global $wp_version;
      //Load special jQuery for avoiding conflicts :)
      //jQuery 1.7.2 in aspjQuery object :)
      $js_load_polaroid = get_option('asp_loadpolaroidjs');
      if ($js_load_polaroid==1 && $js_load_polaroid!==false) { 
        wp_register_script('wpdreams-modernizr',  plugins_url('js/nomin/modernizr.min.js' , __FILE__));
        wp_enqueue_script('wpdreams-modernizr');
        wp_register_script('wpdreams-classie',  plugins_url('js/nomin/classie.js' , __FILE__));
        wp_enqueue_script('wpdreams-classie');
        wp_register_script('wpdreams-photostack',  plugins_url('js/nomin/photostack.js' , __FILE__));
        wp_enqueue_script('wpdreams-photostack');           
      }
           
      $js_minified = get_option('asp_jsminified');
      if (AJAXSEARCHPRO_DEBUG || ($js_minified==0 && $js_minified!==false)) {       
        wp_register_script('wpdreams-aspjquery',  plugins_url('js/nomin/aspjquery.js' , __FILE__));
        wp_enqueue_script('wpdreams-aspjquery');
        wp_register_script('wpdreams-gestures', plugins_url('js/nomin/jquery.gestures.js' , __FILE__), array('wpdreams-aspjquery'));
        wp_enqueue_script('wpdreams-gestures');
        wp_register_script('wpdreams-easing', plugins_url('js/nomin/jquery.easing.js' , __FILE__), array('wpdreams-aspjquery'));
        wp_enqueue_script('wpdreams-easing');
        wp_register_script('wpdreams-mousewheel',plugins_url('js/nomin/jquery.mousewheel.js' , __FILE__), array('wpdreams-aspjquery'));
        wp_enqueue_script('wpdreams-mousewheel');
        wp_register_script('wpdreams-scroll', plugins_url('js/nomin/jquery.mCustomScrollbar.js' , __FILE__), array('wpdreams-aspjquery', 'wpdreams-mousewheel'));
        wp_enqueue_script('wpdreams-scroll');
        wp_register_script('wpdreams-highlight', plugins_url('js/nomin/jquery.highlight.js' , __FILE__), array('wpdreams-aspjquery'));
        wp_enqueue_script('wpdreams-highlight');
        wp_register_script('wpdreams-ajaxsearchpro', plugins_url('js/nomin/jquery.ajaxsearchpro.js' , __FILE__), array('wpdreams-aspjquery', "wpdreams-scroll"));
        wp_enqueue_script('wpdreams-ajaxsearchpro');
      } else {
        wp_register_script('wpdreams-ajaxsearchpro', plugins_url('js/jquery.ajaxsearchpro.min.js' , __FILE__));
        wp_enqueue_script('wpdreams-ajaxsearchpro');
      }  
      wp_localize_script( 'wpdreams-ajaxsearchpro', 'ajaxsearchpro', array( 
        'ajaxurl' => plugins_url('ajax_search.php' , __FILE__), 
        'backend_ajaxurl' => admin_url( 'admin-ajax.php')
      ));

    } 
    
    function fulltext() {
        global $wpdb;
        $fulltext = wpdreams_fulltext::getInstance();
        $tables = array('posts');
        $blogs = wpdreams_get_blog_list(0, 'all');
        
        update_option('asp_fulltext', 0);
        update_option('asp_fulltext_indexed', 0);
        
        if (is_multisite() && is_array($blogs) && count($blogs)) {
           foreach($blogs as $k=>$blog) {
             switch_to_blog($blog['blog_id']);
             if($fulltext->check($tables)) {
                update_option('asp_fulltext', 1);
             } 
           }
           restore_current_blog();
        } else {
           if($fulltext->check($tables)) {
              update_option('asp_fulltext', 1);
           }
        }
    }
    
    function footer() {
      if (get_option('asp_precacheimages')!=1) return;
      ?>
      <script type='text/javascript'>
         $(document).ready(function($) {
            function preCache() {
              var data = {
            	  action: 'ajaxsearchpro_precache'  
            	};
            	$.post(ajaxsearchpro.ajaxurl, data, function(response) {
                 console.log(response);
            	}, "json"); 
            }
            preCache()
            setInterval(function(){
               preCache();
            }, 15000);
         });
      </script>
      <?php     
    }  
  }
  


?>
