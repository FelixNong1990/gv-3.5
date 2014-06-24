<?php
if (!class_exists('wpdreams_searchBuddyPress')) {
  class wpdreams_searchBuddyPress extends wpdreams_search {
    
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
      
      $repliesresults = array();
      $userresults = array();
      $groupresults = array();
              
      if (function_exists('bp_core_get_user_domain')) {
        /* BP users */
        $like = "";
        if ($options['set_inbpusers']) {
         if ($not_exactonly) {
          $sr = implode("%' OR lower($wpdb->users.display_name) like '%",$_s);
          if ($like!="") {
            $sr =  " OR lower($wpdb->users.display_name) like '%".$sr."%'";
          } else {
            $sr =  " lower($wpdb->users.display_name) like '%".$sr."%'";
          }
         } else {
          if ($like!="") {
            $sr =  " OR lower($wpdb->users.display_name) like '%".$s."%'";
          } else {
            $sr =  " lower($wpdb->users.display_name) like '%".$s."%'";
          }
         }
         $like .= $sr;
        }
        $querystr = "
           SELECT
             $wpdb->users.ID as id,
             $wpdb->users.display_name as title,
             '' as date,
             '' as author
           FROM
             $wpdb->users
           WHERE 
            (".$like.") 
        ";
        $userresults = $wpdb->get_results($querystr, OBJECT);
        foreach ($userresults as $k=>$v) {
          $userresults[$k]->link = bp_core_get_user_domain($v->id);
          $imgs = $searchData['settings-imagesettings'];
          if ($searchData['settings-imagesettings']['show']==1) {
            $im = bp_core_fetch_avatar( 'item_id='.$userresults[$k]->id);
            
            if ($searchData['resultstype'] == 'vertical') {
                $_width = $imgs['width'];
                $_height = $imgs['height'];
            } else {
                $_width = wpdreams_width_from_px($searchData['hreswidth']);
                $_vimageratio =  $_width / $searchData['selected-imagesettings']['width'];
                $_height = $_vimageratio * $searchData['selected-imagesettings']['height'];                
            }
               
            $img = new wpdreamsImageCache($im, "user".$v->id, AJAXSEARCHPRO_PATH.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR, $_width, $_height, 1, $searchData['imagebg']);
            $res = $img->get_image();
            if ($res!='') {
              $userresults[$k]->image = plugins_url('/ajax-search-pro/cache/'.$res );
            }
          }
          $update=get_usermeta( $v->id, 'bp_latest_update' );
          if (is_array($update) && isset($update['content']))
            $userresults[$k]->content = $update['content'];
          if ($userresults[$k]->content!='') {
           $userresults[$k]->content = substr(strip_tags($userresults[$k]->content), 0, $searchData['descriptionlength'])."...";
          } else {
           $userresults[$k]->content = "";
          }
            
        }
        
        /* BP groups */
        $like = "";
        if ($options['set_inbpgroups']) {
          if ($not_exactonly) {
            $sr = implode("%' OR lower(".$wpdb->prefix."bp_groups.name) like '%",$_s);
            if ($like!="") {
              $sr =  " OR lower(".$wpdb->prefix."bp_groups.name) like '%".$sr."%'";
            } else {
              $sr =  " lower(".$wpdb->prefix."bp_groups.name) like '%".$sr."%'";
            }
          } else {
          if ($like!="") {
              $sr =  " OR lower(".$wpdb->prefix."bp_groups.name) like '%".$s."%'";
            } else {
              $sr =  " lower(".$wpdb->prefix."bp_groups.name) like '%".$s."%'";
            }
          }
          $like .= $sr;
          if ($not_exactonly) {
            $sr = implode("%' OR lower(".$wpdb->prefix."bp_groups.description) like '%",$_s);
            if ($like!="") {
              $sr =  " OR lower(".$wpdb->prefix."bp_groups.description) like '%".$sr."%'";
            } else {
              $sr =  " lower(".$wpdb->prefix."bp_groups.description) like '%".$sr."%'";
            }
          } else {
          if ($like!="") {
              $sr =  " OR lower(".$wpdb->prefix."bp_groups.description) like '%".$s."%'";
            } else {
              $sr =  " lower(".$wpdb->prefix."bp_groups.description) like '%".$s."%'";
            }
          }
          $like .= $sr;
          if (isset($searchData['searchinbpprivategroups']) && $searchData['searchinbpprivategroups']==0) {
              $_and = "AND ".$wpdb->prefix."bp_groups.status = 'public'";
          } else {
              $_and = "";
          }        
          $querystr = "
             SELECT
               ".$wpdb->prefix."bp_groups.id as id,
               ".$wpdb->prefix."bp_groups.name as title,
               ".$wpdb->prefix."bp_groups.description as content,
               ".$wpdb->prefix."bp_groups.date_created as date,
               $wpdb->users.user_nicename as author             
             FROM
               ".$wpdb->prefix."bp_groups
             LEFT JOIN $wpdb->users ON $wpdb->users.ID = ".$wpdb->prefix."bp_groups.creator_id
             WHERE 
              (".$like.")
              ".$_and;    //AND ".$wpdb->prefix."bp_groups.status = 'public'
           
          $groupresults = $wpdb->get_results($querystr, OBJECT);
          foreach ($groupresults as $k=>$v) {
            $group = new BP_Groups_Group($v->id);
            $groupresults[$k]->link = bp_get_group_permalink($group);
            $imgs = $searchData['settings-imagesettings'];
            if ($searchData['settings-imagesettings']['show']==1) {
              $avatar_options = array ( 'item_id' => $v->id, 'object' => 'group', 'type' => 'full', 'html' => true );
              
              if ($searchData['resultstype'] == 'vertical') {
                  $_width = $imgs['width'];
                  $_height = $imgs['height'];
              } else {
                  $_width = wpdreams_width_from_px($searchData['hreswidth']);
                  $_vimageratio =  $_width / $searchData['selected-imagesettings']['width'];
                  $_height = $_vimageratio * $searchData['selected-imagesettings']['height'];                
              }
              
              $im =   bp_core_fetch_avatar($avatar_options);
              $img = new wpdreamsImageCache($im, "bp".$v->id, AJAXSEARCHPRO_PATH.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR, $_width, $_height, 1, $searchData['imagebg']);
              $res = $img->get_image();
              if ($res!='') {
                $groupresults[$k]->image = plugins_url('/ajax-search-pro/cache/'.$res );
              }
            }
           if ($groupresults[$k]->content!='')
            $groupresults[$k]->content = substr(strip_tags($groupresults[$k]->content), 0, $searchData['descriptionlength'])."...";
          }
        }      
  
        do_action( 'bbpress_init' );
        /*
        if ($searchinbpforums && class_exists('BB_Query_Form')) {
          $like = "";
          
          if ($not_exactonly) {
            $sr = implode("%' OR lower(".$wpdb->prefix."bb_forums.forum_name) like '%",$_s);
            if ($like!="") {
              $sr =  " OR lower(".$wpdb->prefix."bb_forums.forum_name) like '%".$sr."%'";
            } else {
              $sr =  " lower(".$wpdb->prefix."bb_forums.forum_name) like '%".$sr."%'";
            }
          } else {
          if ($like!="") {
              $sr =  " OR lower(".$wpdb->prefix."bb_forums.forum_name) like '%".$s."%'";
            } else {
              $sr =  " lower(".$wpdb->prefix."bb_forums.forum_name) like '%".$s."%'";
            }
          }
          $like .= $sr; 
          
          if ($not_exactonly) {
            $sr = implode("%' OR lower(".$wpdb->prefix."bb_topics.topic_title) like '%",$_s);
            if ($like!="") {
              $sr =  " OR lower(".$wpdb->prefix."bb_topics.topic_title) like '%".$sr."%'";
            } else {
              $sr =  " lower(".$wpdb->prefix."bb_topics.topic_title) like '%".$sr."%'";
            }
          } else {
          if ($like!="") {
              $sr =  " OR lower(".$wpdb->prefix."bb_topics.topic_title) like '%".$s."%'";
            } else {
              $sr =  " lower(".$wpdb->prefix."bb_topics.topic_title) like '%".$s."%'";
            }
          }
          $like .= $sr; 
          
          if ($not_exactonly) {
            $sr = implode("%' OR lower(".$wpdb->prefix."bb_posts.post_text) like '%",$_s);
            if ($like!="") {
              $sr =  " OR lower(".$wpdb->prefix."bb_posts.post_text) like '%".$sr."%'";
            } else {
              $sr =  " lower(".$wpdb->prefix."bb_posts.post_text) like '%".$sr."%'";
            }
          } else {
          if ($like!="") {
              $sr =  " OR lower(".$wpdb->prefix."bb_posts.post_text) like '%".$s."%'";
            } else {
              $sr =  " lower(".$wpdb->prefix."bb_posts.post_text) like '%".$s."%'";
            }
          }
          $like .= $sr;
          
          $querystr = "
             SELECT
               ".$wpdb->prefix."bb_posts.post_id as id,
               ".$wpdb->prefix.$searchData['bpgroupstitle'].",
               ".$wpdb->prefix."bb_posts.post_text as content,
               ".$wpdb->prefix."bb_posts.post_time as date,
               ".$wpdb->prefix."bb_topics.topic_id as topic_id,
               $wpdb->users.user_nicename as author             
             FROM
               ".$wpdb->prefix."bb_posts
             LEFT JOIN $wpdb->users ON $wpdb->users.ID = ".$wpdb->prefix."bb_posts.poster_id
             LEFT JOIN ".$wpdb->prefix."bb_topics ON ".$wpdb->prefix."bb_posts.topic_id = ".$wpdb->prefix."bb_topics.topic_id 
             LEFT JOIN ".$wpdb->prefix."bb_forums ON ".$wpdb->prefix."bb_posts.forum_id = ".$wpdb->prefix."bb_forums.forum_id
             WHERE 
              (".$like.")
          ";
           
          $repliesresults = $wpdb->get_results($querystr, OBJECT);
          foreach ($repliesresults as $k=>$v) {
            $topic_query_vars = array( 'topic_id'=> $v->topic_id, 'topic_status' => 'normal', 'open' => 'open');
            $topic_query = new BB_Query_Form( 'topic', $topic_query_vars );
            $topics = $topic_query->results;
            if (is_array($topics)) {
              foreach ( $topics as $topic ) : $first_post = bb_get_first_post( $topic );
                 
                $_qs = "SELECT post_id as id FROM ".$wpdb->prefix."bb_posts WHERE topic_id=".$v->topic_id;
                $_posts = $wpdb->get_results($_qs, OBJECT);
                $_count = 0; 
                foreach ($_posts as $_post) {
                  $_count++;
                  if ($_post->id==$v->id) break;
                }
                $_bbp_post_count = get_option("_bbp_replies_per_page", 15);
                $_page = intval(($_count-1) / $_bbp_post_count);
                if ($_page>0) {
                  $_page = "?topic_page=".($_page+1);
                } else {
                  $_page = "";
                }
                $repliesresults[$k]->link = get_bloginfo('url')."/groups/".$topic->object_slug."/forum/topic/".$topic->topic_slug."/".$_page."#post-".$v->id;
              endforeach;
            }           
            if ($searchData['settings-imagesettings']['show']==1) {
              $im =   $repliesresults[$k]->content;
              $img = new wpdreamsImageCache($im, AJAXSEARCHPRO_PATH.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR, $imgs['width'], $imgs['height'], 1, $searchData['imagebg']);
              $res = $img->get_image();
              if ($res!='') {
                $repliesresults[$k]->image = plugins_url('/ajax-search-pro/cache/'.$res );
              }
            }
           if ($repliesresults[$k]->content!='')
            $repliesresults[$k]->content = substr(strip_tags($repliesresults[$k]->content), 0, $searchData['descriptionlength'])."...";
           $repliesresults[$k]->title = substr(strip_tags($repliesresults[$k]->title), 0, 30)."...";
          }
        }
        Forum search Deprecated */
      }
      
      $this->results = array(
        'repliesresults' => $repliesresults,
        'groupresults' => $groupresults,
        'userresults' => $userresults
      );
      return $this->results;
    }   
    
  }
}
?>