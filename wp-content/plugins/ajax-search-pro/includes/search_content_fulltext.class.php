<?php
if (!class_exists('wpdreams_searchContentFulltext')) {
  class wpdreams_searchContentFulltext extends wpdreams_searchContent {  
    
    protected function do_search() { 
      global $wpdb;
      global $q_config;
      
      if (isset($wpdb->base_prefix)) {
        $_prefix = $wpdb->base_prefix;
      } else {
        $_prefix = $wpdb->prefix;
      } 
    
      $options = $this->options;
      $searchData = $this->searchData;     
      $s = $this->s;
      $_s = $this->_s;

      $q_config['language'] = $options['qtranslate_lang'];

       
      $rel_title = "";
      $rel_content = "";
      $rel_excerpt = "";
      $rel_terms = "";
         
      $like = "";
      
      $rel_title = "";
      
      /**
       * Fallback or Boolean when strlen < N      
       */    
      
      $is_too_short = false;
      $not_exact_phrase = '';
      $fulltext = wpdreams_fulltext::getInstance();
      foreach($_s as $_pp) {
        if (strlen($_pp) < $fulltext->getMinWordLength()) {
          $is_too_short = true;
          $not_exact_phrase .= " ".$_pp."*";
        } else {
          $not_exact_phrase .= " ".$_pp;
        }           
      }   
      
      $not_exact_phrase = trim($not_exact_phrase);
      $exact_phrase = '"'.$s.'"';             
      
      if (get_option('asp_dbuseregularwhenshort')==1 && $is_too_short) 
          return parent::do_search();

      /**
       * Construct the INDEX name to search
       */
      $match_against = '1'; 
      $relevance = '';
      $fixed_phrase = (!$options['set_exactonly'])?$exact_phrase:$not_exact_phrase;
      $boolean_mode = (get_option('asp_fulltext_indexed')==0 || $is_too_short || !$options['set_exactonly'])?' IN BOOLEAN MODE':'';             
      $index_name = ($options['set_intitle'])?"$wpdb->posts.post_title":'';
      if ($index_name=='')
        $index_name .= ($options['set_incontent'])?"$wpdb->posts.post_content":'';
      else
        $index_name .= ($options['set_incontent'])?", $wpdb->posts.post_content":'';
      if ($index_name=='')  
        $index_name .= ($options['set_inexcerpt'])?"$wpdb->posts.post_excerpt":'';
      else
        $index_name .= ($options['set_inexcerpt'])?", $wpdb->posts.post_excerpt":'';
        
      if ($index_name!='')
        $match_against = " MATCH(".$index_name.") AGAINST ('".$fixed_phrase."'".$boolean_mode.") ";
        
      if ($match_against!='1') {
        $relevance = "
          (
           MATCH(".$index_name.") AGAINST ('".$exact_phrase."'".$boolean_mode.") + 
           MATCH(".$index_name.") AGAINST ('".$not_exact_phrase."'".$boolean_mode.")
           )
        ";
      }    

      
      $selected_customs = array();
      if (isset($options['customset']))
        $selected_customs = $options['customset'];
      if (is_array($selected_customs)) {
        foreach($selected_customs as $k=>$v){
            if ($where!="") {
              $where.= " OR $wpdb->posts.post_type='".$v."'";
            } else {
              $where = "$wpdb->posts.post_type='".$v."'";
            } 
        }
      }
      
      $where = ($where=='')?'1':$where;
      
      $_sr = implode("%' OR lower($wpdb->terms.name) like '%",$_s);
      $rel_terms = "";
      if ($searchData['searchinterms']) {
       if ($options['set_exactonly']) {
        $sr = $_sr;
        if ($like!="") {
          $sr =  " OR lower($wpdb->terms.name) like '%".$sr."%'";
        } else {
          $sr =  " lower($wpdb->terms.name) like '%".$sr."%'";
        }
       } else {
        if ($like!="") {
          $sr =  " OR lower($wpdb->terms.name) like '%".$s."%'";
        } else {
          $sr =  " lower($wpdb->terms.name) like '%".$s."%'";
        }
       }
       $like .= $sr;      
      }
    
      $selected_customfields = $searchData['selected-customfields'];
      if (is_array($selected_customfields) && count($selected_customfields)>0) {
         if ($options['set_exactonly']) {
          $sr = implode("%' OR lower($wpdb->postmeta.meta_value) like '%",$_s);
          $sr =  "lower($wpdb->postmeta.meta_value) like '%".$sr."%'";
         } else {
          $sr =  "lower($wpdb->postmeta.meta_value) like '%".$s."%'";
         }
        $ws = "";
        foreach($selected_customfields as $k=>$v){
            if ($ws!="") {
              $ws.= " OR $wpdb->postmeta.meta_key='".$v."'";
            } else {
              $ws.= "$wpdb->postmeta.meta_key='".$v."'";
            } 
        }
        if ($like!="") {
          $like .= " OR ((".$sr.") AND (".$ws."))";
        } else {
          $like .= "((".$sr.") AND (".$ws."))";
        }
      }     
       
      if (isset($searchData['excludeposts']) && $searchData['excludeposts']!="") {
        $exclude_posts = "$wpdb->posts.ID NOT IN (".$searchData['excludeposts'].")";
      } else {
        $exclude_posts = "$wpdb->posts.ID NOT IN (-55)";
      }      

      $like = ($like=='')?0:$like;
        
      $orderby = ((isset($searchData['selected-orderby']) && $searchData['selected-orderby']!='')?$searchData['selected-orderby']:"post_date DESC");   
    	//$s=strtolower(addslashes($_POST['aspp']));
    	$querystr = "
    		SELECT 
          $wpdb->posts.post_title as title,
          $wpdb->posts.ID as id,
          $wpdb->posts.post_date as date,               
          $wpdb->posts.post_content as content,
          $wpdb->posts.post_excerpt as excerpt,
          $wpdb->users.user_nicename as author,
          GROUP_CONCAT(DISTINCT $wpdb->terms.term_id) as term_id,
          $wpdb->posts.post_type as post_type,";
       if ($searchData['userelevance']==1 && $relevance!='') {
         $querystr .= $relevance;
       } else {
         $querystr .= "1 ";                                   
       }
       $querystr .= " 
          as relevance
    		FROM $wpdb->posts
        LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID
        LEFT JOIN $wpdb->users ON $wpdb->users.ID = $wpdb->posts.post_author
        LEFT JOIN $wpdb->term_relationships ON $wpdb->posts.ID = $wpdb->term_relationships.object_id
        LEFT JOIN $wpdb->term_taxonomy ON $wpdb->term_taxonomy.term_taxonomy_id = $wpdb->term_relationships.term_taxonomy_id
        LEFT JOIN $wpdb->terms ON $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id
    		WHERE
        ($wpdb->posts.post_status='publish' $searchin) AND
        (".$where.") 
        AND (".$match_against." OR (".$like."))
        AND (".$exclude_posts.")
        GROUP BY
          $wpdb->posts.ID";
    		$querystr .= " ORDER BY relevance DESC, ".$wpdb->posts.".".$orderby."
        LIMIT ".$searchData['maxresults']; 
      
  	 	$pageposts = $wpdb->get_results($querystr, OBJECT);
       
      //var_dump($querystr); var_dump($pageposts);die("!!");  
      
      $this->results = $pageposts;
      
      
      return $pageposts;     
    
    }
    
  }
}
?>