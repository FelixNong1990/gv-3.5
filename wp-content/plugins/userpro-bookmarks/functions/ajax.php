<?php

	/* switch collection */
	add_action('wp_ajax_nopriv_userpro_change_collection', 'userpro_change_collection');
	add_action('wp_ajax_userpro_change_collection', 'userpro_change_collection');
	function userpro_change_collection(){
		global $userpro_fav;
		$output = '';
		extract($_POST);
		
		$output['res'] = $userpro_fav->print_bookmarks($collection_id);
	
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/* remove collection */
	add_action('wp_ajax_nopriv_userpro_hard_remove_collection', 'userpro_hard_remove_collection');
	add_action('wp_ajax_userpro_hard_remove_collection', 'userpro_hard_remove_collection');
	function userpro_hard_remove_collection(){
		global $userpro_fav;
		$output = '';
		extract($_POST);
		
		$userpro_fav->hard_remove_collection( $collection_id );
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/* soft-remove collection */
	add_action('wp_ajax_nopriv_userpro_soft_remove_collection', 'userpro_soft_remove_collection');
	add_action('wp_ajax_userpro_soft_remove_collection', 'userpro_soft_remove_collection');
	function userpro_soft_remove_collection(){
		global $userpro_fav;
		$output = '';
		extract($_POST);
		
		$userpro_fav->soft_remove_collection( $collection_id );
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/* add new collection */
	add_action('wp_ajax_nopriv_userpro_fav_addcollection', 'userpro_fav_addcollection');
	add_action('wp_ajax_userpro_fav_addcollection', 'userpro_fav_addcollection');
	function userpro_fav_addcollection(){
		global $userpro_fav;
		$output = '';
		extract($_POST);
		
		$userpro_fav->new_collection( $collection_name );
		
		$output['options'] = '<select class="chosen-select-collections" name="userpro_bm_collection" id="userpro_bm_collection" data-placeholder="">' . $userpro_fav->collection_options( $default_collection, $post_id ) . '</select>';
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/* add new bookmark */
	add_action('wp_ajax_nopriv_userpro_fav_newbookmark', 'userpro_fav_newbookmark');
	add_action('wp_ajax_userpro_fav_newbookmark', 'userpro_fav_newbookmark');
	function userpro_fav_newbookmark(){
		global $userpro_fav;
		$output = '';
		extract($_POST);
		
		$user_id = get_current_user_id();
		$collections = $userpro_fav->get_collections( $user_id );
		$bookmarks = $userpro_fav->get_bookmarks( $user_id );
		
		/* add collection (post id relation) */
		if (!isset($collections[$collection_id])){
			$collections[$collection_id] = array();
		}
		$collections[$collection_id][$post_id] = 1;
		
		/* add bookmark with collection id */
		if (!isset($bookmarks[$post_id])){
			$bookmarks[$post_id] = $collection_id;
		} else {
			$prev_collection_id = $bookmarks[$post_id];
			unset($collections[$prev_collection_id][$post_id]); // remove from prev collection
			$bookmarks[$post_id] = $collection_id; // update collection
		}
		
		$output['collection_id'] = $collection_id; // update active collection
				
		update_user_meta($user_id, '_userpro_collections', $collections);
		update_user_meta($user_id, '_userpro_bookmarks', $bookmarks);

		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/* remove bookmark */
	add_action('wp_ajax_nopriv_userpro_fav_removebookmark', 'userpro_fav_removebookmark');
	add_action('wp_ajax_userpro_fav_removebookmark', 'userpro_fav_removebookmark');
	function userpro_fav_removebookmark(){
		global $userpro_fav;
		$output = '';
		extract($_POST);
		
		$user_id = get_current_user_id();
		$collections = $userpro_fav->get_collections( $user_id );
		$bookmarks = $userpro_fav->get_bookmarks( $user_id );
		
		if (isset($bookmarks[$post_id])){
			$curcollection_id = $bookmarks[$post_id];
			unset($collections[$curcollection_id][$post_id]); // remove from collections
			unset($bookmarks[$post_id]); // remove from bookmarks
		}
		
		if (isset($collections[$collection_id][$post_id])){
			unset($collections[$collection_id][$post_id]);
		}
				
		update_user_meta($user_id, '_userpro_collections', $collections);
		update_user_meta($user_id, '_userpro_bookmarks', $bookmarks);

		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}