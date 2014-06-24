<?php

	/* Enqueue Scripts */
	add_action('wp_enqueue_scripts', 'wpb_enqueue_scripts', 99);
	function wpb_enqueue_scripts(){
	
		wp_register_style('wpb', wpb_url . 'css/wpb-bookmarks.css');
		wp_enqueue_style('wpb');
		
		wp_register_style('wpb_iconfont', wpb_url . 'css/wpb-iconfont.css');
		wp_enqueue_style('wpb_iconfont');
		
		wp_register_style('wpb_list', wpb_url . 'css/wpb-collections.css');
		wp_enqueue_style('wpb_list');
		
		wp_register_style('wpb_chosen', wpb_url . 'css/wpb-chosen.css' );
		wp_enqueue_style('wpb_chosen');
		
		wp_register_script('wpb_chosen', wpb_url . 'scripts/wpb-chosen.js', array('jquery') );
		wp_enqueue_script('wpb_chosen');
		
		wp_register_script('wpb', wpb_url . 'scripts/wpb-bookmarks.js');
		wp_enqueue_script('wpb');
		
	}
	
	/* Add the bookmark widget to content */
	add_action('the_content', 'wpb_bookmark_content', 100);
	function wpb_bookmark_content($content){
		global $post, $wpb;
		if (wpb_get_option('auto_bookmark')) {
		
			// hard excluded by post type
			if (wpb_get_option('include_post_types')){
				if (is_array( wpb_get_option('include_post_types') ) && !in_array( get_post_type(), wpb_get_option('include_post_types')))
					return $content;
			}
			
			// soft excluded by post id
			if (wpb_get_option('exclude_ids')){
				$array = explode(',', wpb_get_option('exclude_ids'));
				if (in_array($post->ID, $array))
					return $content;
			}
			
			$content .= $wpb->bookmark();
		}
		return $content;
	}