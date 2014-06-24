<?php

	/* Enqueue Scripts */
	add_action('wp_enqueue_scripts', 'userpro_fav_enqueue_scripts', 99);
	function userpro_fav_enqueue_scripts(){
	
		wp_register_style('userpro_fav', userpro_fav_url . 'css/userpro-bookmarks.css');
		wp_enqueue_style('userpro_fav');
		
		wp_register_style('userpro_fav_list', userpro_fav_url . 'css/userpro-collections.css');
		wp_enqueue_style('userpro_fav_list');
		
		wp_register_script('userpro_fav', userpro_fav_url . 'scripts/userpro-bookmarks.js');
		wp_enqueue_script('userpro_fav');
		
	}
	
	/* Add the bookmark widget to content */
	add_action('the_content', 'userpro_fav_bookmark_content', 100);
	function userpro_fav_bookmark_content($content){
		global $post, $userpro_fav;
		if (userpro_fav_get_option('auto_bookmark')) {
		
			// hard excluded by post type
			if (userpro_fav_get_option('include_post_types')){
				if (is_array( userpro_fav_get_option('include_post_types') ) && !in_array( get_post_type(), userpro_fav_get_option('include_post_types')))
					return $content;
			}
			
			// soft excluded by post id
			if (userpro_fav_get_option('exclude_ids')){
				$array = explode(',', userpro_fav_get_option('exclude_ids'));
				if (in_array($post->ID, $array))
					return $content;
			}
			
			$content .= $userpro_fav->bookmark();
		}
		return $content;
	}