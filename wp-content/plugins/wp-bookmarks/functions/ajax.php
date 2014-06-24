<?php

	add_action('wp_head','wpb_ajax_url');
	function wpb_ajax_url() { ?>
		<script type="text/javascript">
		var wpb_ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
		</script>
	<?php
	}
	
	/* switch collection */
	add_action('wp_ajax_nopriv_wpb_change_collection', 'wpb_change_collection');
	add_action('wp_ajax_wpb_change_collection', 'wpb_change_collection');
	function wpb_change_collection(){
		global $wpb;
		$output = '';
		extract($_POST);
		
		$output['res'] = $wpb->print_bookmarks($collection_id);
	
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/* remove collection */
	add_action('wp_ajax_nopriv_wpb_hard_remove_collection', 'wpb_hard_remove_collection');
	add_action('wp_ajax_wpb_hard_remove_collection', 'wpb_hard_remove_collection');
	function wpb_hard_remove_collection(){
		global $wpb;
		$output = '';
		extract($_POST);
		
		$wpb->hard_remove_collection( $collection_id );
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/* soft-remove collection */
	add_action('wp_ajax_nopriv_wpb_soft_remove_collection', 'wpb_soft_remove_collection');
	add_action('wp_ajax_wpb_soft_remove_collection', 'wpb_soft_remove_collection');
	function wpb_soft_remove_collection(){
		global $wpb;
		$output = '';
		extract($_POST);
		
		$wpb->soft_remove_collection( $collection_id );
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/* add new collection */
	add_action('wp_ajax_nopriv_wpb_addcollection', 'wpb_addcollection');
	add_action('wp_ajax_wpb_addcollection', 'wpb_addcollection');
	function wpb_addcollection(){
		global $wpb;
		$output = '';
		extract($_POST);
		
		$wpb->new_collection( $collection_name );
		
		$output['options'] = '<select class="chosen-select-collections" name="wpb_bm_collection" id="wpb_bm_collection" data-placeholder="">' . $wpb->collection_options( $default_collection, $post_id ) . '</select>';
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/* add new bookmark */
	add_action('wp_ajax_nopriv_wpb_newbookmark', 'wpb_newbookmark');
	add_action('wp_ajax_wpb_newbookmark', 'wpb_newbookmark');
	function wpb_newbookmark(){
		global $wpb;
		$output = '';
		extract($_POST);
		
		$user_id = get_current_user_id();
		$collections = $wpb->get_collections( $user_id );
		$bookmarks = $wpb->get_bookmarks( $user_id );
		
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
				
		update_user_meta($user_id, '_wpb_collections', $collections);
		update_user_meta($user_id, '_wpb_bookmarks', $bookmarks);

		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/* remove bookmark */
	add_action('wp_ajax_nopriv_wpb_removebookmark', 'wpb_removebookmark');
	add_action('wp_ajax_wpb_removebookmark', 'wpb_removebookmark');
	function wpb_removebookmark(){
		global $wpb;
		$output = '';
		extract($_POST);
		
		$user_id = get_current_user_id();
		$collections = $wpb->get_collections( $user_id );
		$bookmarks = $wpb->get_bookmarks( $user_id );
		
		if (isset($bookmarks[$post_id])){
			$curcollection_id = $bookmarks[$post_id];
			unset($collections[$curcollection_id][$post_id]); // remove from collections
			unset($bookmarks[$post_id]); // remove from bookmarks
		}
		
		if (isset($collections[$collection_id][$post_id])){
			unset($collections[$collection_id][$post_id]);
		}
				
		update_user_meta($user_id, '_wpb_collections', $collections);
		update_user_meta($user_id, '_wpb_bookmarks', $bookmarks);

		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}