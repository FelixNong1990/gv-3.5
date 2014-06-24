<?php
if (!class_exists('wpdreams_searchComments')) {
  class wpdreams_searchComments extends wpdreams_search {
    
    protected function do_search() { 
      global $wpdb;
      
      if (isset($wpdb->base_prefix)) {
        $_prefix = $wpdb->base_prefix;
      } else {
        $_prefix = $wpdb->prefix;
      } 
    
      $options = $this->options;
      $searchData = $this->searchData;     
      $s = $this->s;
      $_s = $this->_s;
      
      $allcommentsresults = array();
      
      if ($options['set_incomments']) {
       $like = "";
       if ($not_exactonly) {
        $sr = implode("%' OR lower($wpdb->comments.comment_content) like '%",$_s);
        if ($like!="") {
          $sr =  " OR lower($wpdb->comments.comment_content) like '%".$sr."%'";
        } else {
          $sr =  " lower($wpdb->comments.comment_content) like '%".$sr."%'";
        }
       } else {
        if ($like!="") {
          $sr =  " OR lower($wpdb->comments.comment_content) like '%".$s."%'";
        } else {
          $sr =  " lower($wpdb->comments.comment_content) like '%".$s."%'";
        }
       }
       $like .= $sr;
      	$querystr = "
      		SELECT 
            $wpdb->comments.comment_ID as id,
            $wpdb->comments.comment_post_ID as post_id,
            $wpdb->comments.user_id as user_id,
            $wpdb->comments.comment_content as content,
            $wpdb->comments.comment_date as date
      		FROM $wpdb->comments
      		WHERE
          ($wpdb->comments.comment_approved=1)
          AND
          (".$like.")
      		ORDER BY $wpdb->comments.comment_ID DESC
      		LIMIT ".$searchData['maxresults'];
                                                                                                
    	 	$commentsresults = $wpdb->get_results($querystr, OBJECT); 
        if (is_array($commentsresults)) {
          foreach ($commentsresults as $k=>$v) {
             $commentsresults[$k]->link = get_comment_link($v->id);
             $commentsresults[$k]->author = get_comment_author($v->id);
             if ($searchData['settings-imagesettings']['show']==1) {
               $imgs = $searchData['settings-imagesettings'];
               ksort($imgs['from']);
               foreach($imgs['from'] as $kk=>$source) {
                $img = new wpdreamsImageCache($v->content, "comment".$v->id, AJAXSEARCHPRO_PATH.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR, $imgs['width'], $imgs['height'], $imgs['imagenum'], $searchData['imagebg']);
                $res = $img->get_image();
                if ($res!='') {
                  $commentsresults[$k]->image = plugins_url('/ajax-search-pro/cache/'.$res );
                  break;
                }
               } 
             }
             $commentsresults[$k]->title = substr($commentsresults[$k]->content, 0, 15)."...";
                 
          }
        }
        $allcommentsresults = array_merge($allcommentsresults, $commentsresults);
      }
      $this->results = $allcommentsresults;
      return $allcommentsresults; 
    }   
    
  }
}
?>