<?php

class userpro_fav_api {

	function __construct() {

	}
	
	/* New collection */
	function new_collection($name) {
		$user_id = get_current_user_id();
		$collections = $this->get_collections($user_id);
		$collections[] = array('label' => $name);
		update_user_meta($user_id, '_userpro_collections', $collections);
	}
	
	/* Remove a collection */
	function hard_remove_collection($id){
		
		$user_id = get_current_user_id();
		$collections = $this->get_collections($user_id);
		$bookmarks = $this->get_bookmarks( $user_id );
		
		// remove bookmarks
		foreach($collections[$id] as $k => $arr) {
			if ($k != 'label') {
				if (isset($bookmarks[$k])){
					unset($bookmarks[$k]);
				}
			}
		}
		
		// remove collection
		if ($id > 0){
			unset($collections[$id]);
		}
		
		update_user_meta($user_id, '_userpro_bookmarks', $bookmarks);
		update_user_meta($user_id, '_userpro_collections', $collections);
	}
	
	/* Soft-Remove a collection */
	function soft_remove_collection($id){
		
		$user_id = get_current_user_id();
		$collections = $this->get_collections($user_id);
		$bookmarks = $this->get_bookmarks( $user_id );
		
		// transfer bookmarks to default collection
		foreach($collections[$id] as $k => $arr) {
			if ($k != 'label') {
				$collections[0][$k] = 1;
			}
		}
		
		// remove collection
		if ($id > 0){
			unset($collections[$id]);
		}
		
		update_user_meta($user_id, '_userpro_bookmarks', $bookmarks);
		update_user_meta($user_id, '_userpro_collections', $collections);
	}
	
	/* Get bookmarks by collection */
	function get_bookmarks_by_collection($id){
		$collections = $this->get_collections( get_current_user_id() );
		return $collections[$id];
	}
	
	function get_bookmarks_count_by_collection($id){
		$collections = $this->get_collections( get_current_user_id() );
		if ($id == 0){
			if (empty($collections[$id])){
				return 0;
			} else {
				return (int)count($collections[$id]);
			}
		} else {
			return (int)count($collections[$id])-1;
		}
	}
	
	/* print bookmarks */
	function print_bookmarks($coll_id) {
		global $userpro;
		$output = '';
		
		$output .= '<div class="userpro-coll-count">';
		$output .= sprintf(__('%s Bookmarks in Collection','userpro-fav'), $this->get_bookmarks_count_by_collection($coll_id));
		
		if ($coll_id != 0) { // default cannot be removed
		$output .= '<a href="#" class="userpro-bm-btn bookmarked userpro-remove-collection" data-undo="'.__('Undo','userpro-fav').'" data-remove="'.__('Remove Collection','userpro-fav').'">'.__('Remove Collection','userpro-fav').'</a>';
		
		/* To hide a collection */
		$output .= '<div class="userpro-coll-remove">';
		$output .= __('Choose how do you want to remove this collection. This action cannot be undone!','userpro-fav');
		$output .= '<div class="userpro-coll-remove-btns">';
		if ($this->get_bookmarks_count_by_collection($coll_id) > 0) {
		$output .= '<a href="#" class="userpro-bm-btn userpro-hard-remove" data-collection_id="'.$coll_id.'">'.__('Remove collection and all bookmarks in it','userpro-fav').'</a>';
		$output .= '<a href="#" class="userpro-bm-btn secondary userpro-soft-remove" data-collection_id="'.$coll_id.'">'.__('Remove collection only','userpro-fav').'</a>';
		} else {
		$output .= '<a href="#" class="userpro-bm-btn secondary userpro-hard-remove" data-collection_id="'.$coll_id.'">'.__('Remove collection','userpro-fav').'</a>';
		}
		$output .= '</div>';
		$output .= '</div>';
		
		}
		
		$output .= '</div>';
		
		$bks = $this->get_bookmarks_by_collection( $coll_id );
		$results = 0;
		if (is_array($bks)){
		$bks = array_reverse($bks, true);
		foreach($bks as $id => $array) {
			if ($id != 'label') {
			$results++;
			if (get_post_status($id) == 'publish') { // active post
			
				$output .= '<div class="userpro-coll-item">';
				$output .= '<a href="#" class="userpro-coll-abs userpro-bm-btn secondary" data-post_id="'.$id.'" data-collection_id="'.$coll_id.'">'.__('Remove','userpro-fav').'</a>';
				
				$output .= '<div class="uci-thumb"><a href="'.get_permalink($id).'">'.$userpro->post_thumb($id, 50).'</a></div>';
				
				$output .= '<div class="uci-content">';
				$output .= '<div class="uci-title"><a href="'.get_permalink($id).'">'. get_the_title($id) . '</a></div>';
				$output .= '<div class="uci-url"><a href="'.get_permalink($id).'">'.get_permalink($id).'</div>';
				$output .= '</div><div class="userpro-clear"></div>';
				
				$output .= '</div><div class="userpro-clear"></div>';
			
			} else {
			
				$output .= '<div class="userpro-coll-item">';
				$output .= '<a href="#" class="userpro-coll-abs userpro-bm-btn secondary" data-post_id="'.$id.'" data-collection_id="'.$coll_id.'">'.__('Remove','userpro-fav').'</a>';
				
				$output .= '<div class="uci-thumb"></div>';
				
				$output .= '<div class="uci-content">';
				$output .= '<div class="uci-title">'.__('Content Removed','userpro-fav').'</div>';
				$output .= '<div class="uci-url"></div>';
				$output .= '</div><div class="userpro-clear"></div>';
				
				$output .= '</div><div class="userpro-clear"></div>';
			
			}
			
			}
		}
		}
		
		if ($results == 0){
			$output .= '<div class="userpro-coll-item">';
			$output .= __('You did not add any content to this collection yet.','userpro-fav');
			$output .= '<div class="userpro-clear"></div></div><div class="userpro-clear"></div>';
		}
		
		return $output;
	}
	
	/* Get collections for user */
	function collection_options($default_collection, $post_id){
		$output = '';
		$user_id = get_current_user_id();
		$collections = $this->get_collections($user_id);
		$bookmarks = (array) get_user_meta($user_id, '_userpro_bookmarks', true);
		if (isset($bookmarks[$post_id])){
			$cur_collection = $bookmarks[$post_id];
		} else {
			$cur_collection = 0;
		}
		foreach($collections as $k => $v) {
			if (!isset($v['label'])) $v['label'] = $default_collection;
			$output .= '<option value="'.$k.'" '.selected($k, $cur_collection, 0).' >'.$v['label'];
			$output .= '</option>';
		}
		return $output;
	}
	
	/* Find collection ID */
	function collection_id($post_id){
		$user_id = get_current_user_id();
		$bookmarks = (array) get_user_meta($user_id, '_userpro_bookmarks', true);
		if (isset($bookmarks[$post_id])){
			return $bookmarks[$post_id];
		}
	}
	
	/**
		Is post bookmarked
	**/
	function bookmarked($post_id){
		$user_id = get_current_user_id();
		$bookmarks = (array) get_user_meta($user_id, '_userpro_bookmarks', true);
		if (isset($bookmarks[$post_id])){
			return true;
		}
		return false;
	}
	
	/* Delete collection */
	function delete_collection($collection_id, $user_id) {
		$array = $this->get_collections($user_id);
		unset($array[$collection_id]);
		update_user_meta($user_id, '_userpro_collections', $array);
	}
	
	/* Get collections */
	function get_collections($user_id) {
		return (array)get_user_meta($user_id, '_userpro_collections', true);
	}
	
	/* Get bookmarks */
	function get_bookmarks($user_id) {
		return (array)get_user_meta($user_id, '_userpro_bookmarks', true);
	}
	
	/* Count bookmarks */
	function bookmarks_count($user_id) {
		$bookmarks = (array)get_user_meta($user_id, '_userpro_bookmarks', true);
		unset($bookmarks[0]);
		if (!empty($bookmarks) ){
			return count($bookmarks);
		} else {
			return 0;
		}
	}
	
	/* Get current page url */
	function get_permalink(){
		global $post;
		if (is_home()){
			$permalink = home_url();
		} else {
			if (isset($post->ID)){
				$permalink = get_permalink($post->ID);
			} else {
				$permalink = '';
			}
		}
		return $permalink;
	}
	
	/**
		Display the bookmarks in
		organized collections
	**/
	function bookmarks( $args = array() ){
		global $userpro, $post;
		$defaults = array(
			'default_collection' => userpro_fav_get_option('default_collection'),
		);
		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );
	
		/* output */
		$output = '';
		
		// logged in
		if (userpro_is_logged_in()){
		
		$output .= '<div class="userpro-coll">';
		
		$output .= '<div class="userpro-coll-list">';
		
		$collections = $this->get_collections( get_current_user_id() );
		$active_coll = 0;
		foreach($collections as $id => $array) {
			if (!isset($array['label'])) { $array['label'] = $default_collection; }
			if ($id == $active_coll) { $class = 'active'; } else { $class = ''; }
			$output .= '<a href="#collection_'.$id.'" data-collection_id="'.$id.'" class="'.$class.'">';
			if ($class == 'active'){
			$output .= '<i class="userpro-icon-caret-left"></i>';
			$output .= '<span class="userpro-coll-list-count userpro-coll-hide">'.$this->get_bookmarks_count_by_collection($id).'</span>';
			} else {
			$output .= '<i class="userpro-icon-caret-left userpro-coll-hide"></i>';
			$output .= '<span class="userpro-coll-list-count">'.$this->get_bookmarks_count_by_collection($id).'</span>';
			}
			$output .= $array['label'].'</a>';
		}
		
		$output .= '</div>';		
		$output .= '<div class="userpro-coll-body">';
		$output .= '<div class="userpro-coll-body-inner">';
		
		$output .= $this->print_bookmarks($coll_id = 0);
		
		$output .= '</div></div><div class="userpro-clear"></div>';
		
		$output .= '</div>';
		
		// guest
		} else {
			
			$output .= '<p>'.sprintf(__('You need to <a href="%s">login</a> or <a href="%s">register</a> to view and manage your bookmarks.','userpro-fav'), $userpro->permalink(0, 'login').'?redirect_to='.$this->get_permalink(), $userpro->permalink(0, 'register')).'</p>';
		
		}
		
		return $output;
	}
	
	/**
		Bookmark: display the widget that allow
		bookmarks
	**/
	function bookmark( $args = array() ){
		global $userpro, $post;
		$defaults = array(
			'width' => userpro_fav_get_option('width'),
			'align' => userpro_fav_get_option('align'),
			'inline' => userpro_fav_get_option('inline'),
			'no_top_margin' => userpro_fav_get_option('no_top_margin'),
			'no_bottom_margin' => userpro_fav_get_option('no_bottom_margin'),
			'pct_gap' => userpro_fav_get_option('pct_gap'),
			'px_gap' => userpro_fav_get_option('px_gap'),
			'widgetized' => userpro_fav_get_option('widgetized'),
			'remove_bookmark' => userpro_fav_get_option('remove_bookmark'),
			'dialog_bookmarked' => userpro_fav_get_option('dialog_bookmarked'),
			'dialog_unbookmarked' => userpro_fav_get_option('dialog_unbookmarked'),
			'default_collection' => userpro_fav_get_option('default_collection'),
			'add_to_collection' => userpro_fav_get_option('add_to_collection'),
			'new_collection' => userpro_fav_get_option('new_collection'),
			'new_collection_placeholder' => userpro_fav_get_option('new_collection_placeholder'),
			'add_new_collection' => userpro_fav_get_option('add_new_collection'),
		);
		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );
		
		/* options */
		if (strstr($width, 'px')) { $px = 'px'; } else { $px = '%'; }
		if ($px == '%') {
			$btn_width = 50 - $pct_gap . $px;
		} else {
			$btn_width = ($width / 2 ) - $px_gap . $px;
		}
		if ($widgetized == 1){
			$btn_width = '100%';
		}

		/* output */
		$output = '';
		
		// logged in
		if (userpro_is_logged_in()){
		
		if (isset($post->ID)){
			$post_id = $post->ID;
		} else {
			$post_id = null;
		}
		
		$output .= '<div class="userpro-bm userpro-bm-nobottommargin-'.$no_bottom_margin.' userpro-bm-notopmargin-'.$no_top_margin.' userpro-bm-inline-'.$inline.' userpro-bm-'.$align.' userpro-bm-widgetized-'.(int)$widgetized.'" style="width:'.$width.' !important;" data-add_new_collection="'.$add_new_collection.'" data-default_collection="'.$default_collection.'" data-new_collection_placeholder="'.$new_collection_placeholder.'" data-dialog_unbookmarked="'.$dialog_unbookmarked.'" data-dialog_bookmarked="'.$dialog_bookmarked.'" data-add_to_collection="'.$add_to_collection.'" data-remove_bookmark="'.$remove_bookmark.'" data-post_id="'.$post_id.'">';
		
		$output .= '<div class="userpro-bm-inner">';
		
		/* collections list */
		$output .= '<div class="userpro-bm-list">';
		$output .= '<select class="chosen-select-collections" name="userpro_bm_collection" id="userpro_bm_collection" data-placeholder="">';
		$output .= $this->collection_options( $default_collection, $post_id );
		$output .= '</select>';
		$output .= '</div>';
		
		/* action buttons */
		$output .= '<div class="userpro-bm-act">';
		
		if ($this->bookmarked($post_id)) {
			$output .= '<input type="hidden" name="collection_id" id="collection_id" value="'.$this->collection_id($post_id).'" />';
			$output .= '<div class="userpro-bm-btn-contain" style="width:'.$btn_width.' !important;"><a href="#" class="userpro-bm-btn primary bookmarked" data-action="bookmark">'.$remove_bookmark.'</a></div>';
		} else {
			$output .= '<div class="userpro-bm-btn-contain" style="width:'.$btn_width.' !important;"><a href="#" class="userpro-bm-btn primary unbookmarked" data-action="bookmark">'.$add_to_collection.'</a></div>';
		}
		$output .= '<div class="userpro-bm-btn-contain bm-right" style="width:'.$btn_width.' !important;"><a href="#" class="userpro-bm-btn secondary" data-action="newcollection">'.$new_collection.'</a></div>';
		
		$output .= '</div><div class="userpro-clear"></div>';
		
		$output .= '</div>';
		$output .= '</div>';
		
		if (!$inline) {
			$output .= '<div class="userpro-clear"></div>';
		}
		
		// guest
		} else {
		
			$output .= '<p>'.sprintf(__('You need to <a href="%s">login</a> or <a href="%s">register</a> to bookmark/favorite this content.','userpro-fav'), $userpro->permalink(0, 'login').'?redirect_to='.$this->get_permalink(), $userpro->permalink(0, 'register')).'</p>';

		}
		
		return $output;
	}
	
}

$userpro_fav = new userpro_fav_api();