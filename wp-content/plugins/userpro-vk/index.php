<?php
/*
Plugin Name: VK.com Social Connect for UserPro
Plugin URI: http://codecanyon.net/user/DeluxeThemes/portfolio?ref=DeluxeThemes
Description: Enables login via the most popular Russian social network with UserPro plugin.
Version: 1.1
Author: Deluxe Themes
Author URI: http://codecanyon.net/user/DeluxeThemes/portfolio?ref=DeluxeThemes
*/

define('userpro_vk_url',plugin_dir_url(__FILE__ ));
define('userpro_vk_path',plugin_dir_path(__FILE__ ));

	/* init */
	function userpro_vk_init() {
		load_plugin_textdomain('userpro-vk', false, dirname(plugin_basename(__FILE__)) . '/languages');
	}
	add_action('init', 'userpro_vk_init');
	
	/* functions */
	foreach (glob(userpro_vk_path . 'functions/*.php') as $filename) { require_once $filename; }
	
	/* administration */
	if (is_admin()){
		foreach (glob(userpro_vk_path . 'admin/*.php') as $filename) { include $filename; }
	}