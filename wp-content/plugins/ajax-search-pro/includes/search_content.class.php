<?php
if (!class_exists('wpdreams_searchContent')) {
  class wpdreams_searchContent extends wpdreams_search {  
    
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
      
      $_sr = implode("%' OR lower($wpdb->posts.post_title) like '%",$_s);
      //$rel_title = "lower($wpdb->posts.post_title) like '%".$_sr."%'";
      
      $rel_title = "";
         
      if ($options['set_intitle']) {
       if ($options['set_exactonly']) {
        $sr =  $_sr;
        $sr =  " lower($wpdb->posts.post_title) like '%".$sr."%'";
       } else {
        $sr =  " lower($wpdb->posts.post_title) like '%".$s."%'";
       }
       $like .= $sr;      
        foreach ((array)$_s as $_phrase) {
          $rel_title .= " 
          (case when (lower($wpdb->posts.post_title) like '%$_phrase%') then $searchData[titleweight] else 0 end) + ";
        }         
      }
      
      $_sr = implode("%' OR lower($wpdb->posts.post_content) like '%",$_s);
      //$rel_content = "lower($wpdb->posts.post_content) like '%".$_sr."%'";
      
      $rel_content = "";
              
      if ($options['set_incontent']) {
       if ($options['set_exactonly']) {
        $sr = $_sr;
        if ($like!="") {
          $sr =  " OR lower($wpdb->posts.post_content) like '%".$sr."%'";
        } else {
          $sr =  " lower($wpdb->posts.post_content) like '%".$sr."%'";
        }
       } else {
        if ($like!="") {
          $sr =  " OR lower($wpdb->posts.post_content) like '%".$s."%'";
        } else {
          $sr =  " lower($wpdb->posts.post_content) like '%".$s."%'";
        }
       }
       $like .= $sr;      
        foreach ((array)$_s as $_phrase) {
          $rel_content .= "
           (case when (lower($wpdb->posts.post_content) like '%$_phrase%') then $searchData[contentweight] else 0 end) + ";
        } 
      }
      

      $_sr = implode("%' OR lower($wpdb->posts.post_excerpt) like '%",$_s);
      //$rel_excerpt = "lower($wpdb->posts.post_excerpt) like '%".$_sr."%'"; 
      
      $rel_excerpt = "";
         
      if ($options['set_inexcerpt']) {
       if ($options['set_exactonly']) {
        $sr = $_sr;
        if ($like!="") {
          $sr =  " OR lower($wpdb->posts.post_excerpt) like '%".$sr."%'";
        } else {
          $sr =  " lower($wpdb->posts.post_excerpt) like '%".$sr."%'";
        }
       } else {
        if ($like!="") {
          $sr =  " OR lower($wpdb->posts.post_excerpt) like '%".$s."%'";
        } else {
          $sr =  " lower($wpdb->posts.post_excerpt) like '%".$s."%'";
        }
       }
       $like .= $sr;      
        foreach ((array)$_s as $_phrase) {
          $rel_excerpt .= "
           (case when (lower($wpdb->posts.post_excerpt) like '%$_phrase%') then $searchData[excerptweight] else 0 end) + ";
        } 
      }

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
        foreach ((array)$_s as $_phrase) {
          $rel_terms .= "(case when (lower($wpdb->terms.name) like '%$_phrase%') then $searchData[titleweight] else 0 end) +";
        } 
      }
    
      if ($options['set_inposts']) {
        $where = " $wpdb->posts.post_type='post'"; 
      }
      
      if ($options['set_inpages']) {
        if ($where!="")
          $where.= " OR $wpdb->posts.post_type='page'";
        else
          $where.= "$wpdb->posts.post_type='page'"; 
      }
    
      $selected_customs = array();
      if (isset($options['customset']))
        $selected_customs = $options['customset'];
      if (is_array($selected_customs)) {
        foreach($selected_customs as $k=>$v){
            if ($where!="") {
              $where.= " OR $wpdb->posts.post_type='".$v."'";
            } else {
              $where.= "$wpdb->posts.post_type='".$v."'";
            } 
        }
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
     
      $ex_woo_skus = " ";  
     /* if ($searchData['excludewoocommerceskus']==1) {
        $ex_woo_skus = " AND ($wpdb->postmeta.meta_key <> '_sku') ";
      }  */
      
        
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
       if ($searchData['userelevance']==1) {
         $querystr .= " (
            $rel_title
            $rel_content
            $rel_excerpt
            $rel_terms
            (case when lower($wpdb->posts.post_title) like '$s' then $searchData[etitleweight] else 0 end) +
            (case when lower($wpdb->posts.post_title) like '%$s%' then $searchData[etitleweight] else 0 end) +
            (case when lower($wpdb->posts.post_content) like '%$s%' then $searchData[econtentweight] else 0 end) +
            (case when lower($wpdb->posts.post_excerpt) like '%$s%' then $searchData[eexcerptweight] else 0 end) + 
            (case when lower($wpdb->terms.name) like '%$s%' then $searchData[etermsweight] else 0 end)
          )";
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
        AND (".$like.")
        AND (".$exclude_posts.")
        $ex_woo_skus
        GROUP BY
          $wpdb->posts.ID";
    		$querystr .= " ORDER BY relevance DESC, ".$wpdb->posts.".".$orderby."
        LIMIT ".$searchData['maxresults']; 
      
  	 	$pageposts = $wpdb->get_results($querystr, OBJECT);
       
      //var_dump($querystr);die("!!");  
      //var_dump($pageposts);die("!!");
      
      $this->results = $pageposts;
      
      
      return $pageposts;     
    
    } 
    
    protected function post_process() {
      
      $pageposts = $this->results;
      $options = $this->options;
      $searchData = $this->searchData;   
      $s = $this->s;
      $_s = $this->_s;
            
      /* Category filtering */
      $_selected_categories = array();
      if (isset($options['categoryset']))
         $_selected_categories = $options['categoryset'];
      $_all_categories = get_all_category_ids();
      if (count($_selected_categories)>0 && (count($_all_categories)!=count($_selected_categories))) {
        foreach ($pageposts as $k=>$v) {
          if ($v->term_id==null) {
            $v->term_id = "";
            continue;
          }
          if ($v->post_type!='post' && $searchData['pageswithcategories']!=1) {
            continue;
          }  
          $_term_ids = explode(',', $v->term_id);
          if (count($_term_ids)<=0) $_term_ids = array($v->term_id);
          $match = false; 
          $_new_terms = array();
          foreach ($_term_ids as $_term_id) {
            if (in_array($_term_id, $_selected_categories)) {
              $match = true;
              $_new_terms[] = $_term_id;
            }
          }
          if (!$match) {
            unset($pageposts[$k]);
          } else {
            $v->term_id = implode(",", $_new_terms);
          }
        }
      } 
      /* !Category filtering */
      
      /* TERM filtering */

      if (isset($options['termset'])) {
        $terms = array();
        $o_terms = array();
        foreach ($options['termset'] as $term=>$tax) {
          if ($tax!="0")
            $o_terms[$tax][] = $term;
        } 
        //var_dump($o_terms);
        foreach ($pageposts as $k=>$v) {
          $terms = $o_terms; 
          foreach ($terms as $ex_taxonomy=>$ex_terms) {
            $_post_terms = wp_get_post_terms( $v->id, $ex_taxonomy);
            $post_terms = array();
            if (is_array($_post_terms) && isset($_post_terms[0])) {
              foreach ($_post_terms as $_term) {
                $post_terms[] = (int)$_term->term_id;
              }
              
              foreach ($ex_terms as $_term) {
                
                /* Get all child terms of the current term */
                $args = array('child_of' => $_term);
                $termchildren = get_terms( $ex_taxonomy, $args);
                if (isset($termchildren) && count($termchildren)>0) {
                  foreach($termchildren as $child_term)
                    if (!in_array((int)$child_term->term_id, $ex_terms)) 
                      $ex_terms[] = (int)$child_term->term_id;
                }
                
                /* Get the parent term of the current term */
                $parent  = get_term_by( 'id', $_term, $ex_taxonomy);
                if (isset($parent) && isset($parent->term_id) && $parent->term_id!=0)
                   $ex_terms[] = $parent->term_id;   
              }
              
              
              
              $diff = array_unique(array_diff($post_terms, $ex_terms));
              //var_dump($post_terms);
              //var_dump($ex_terms);   
              //var_dump($diff); 
              //exit;

              if (!is_array($diff) || count($diff)<1) {
                unset($pageposts[$k]);
                break;
              } 
            }
          }
        }      
      }
      /* !TERM  filtering */

      /* Images, title, desc */
      foreach ($pageposts as $k=>$v) {   
         
         // Let's simplify things
         $r = &$pageposts[$k];
          
         $r->title = apply_filters( 'asp_result_title_before_prostproc' , $r->title, $r->id);
         $r->content = apply_filters( 'asp_result_content_before_prostproc' , $r->content, $r->id);
         $r->image = apply_filters( 'asp_result_image_before_prostproc' ,$r->image, $r->id);
         $r->author = apply_filters( 'asp_result_author_before_prostproc' ,$r->author, $r->id);
         $r->date = apply_filters( 'asp_result_date_before_prostproc' , $r->date, $r->id);
      
         $r->link = get_permalink($v->id);   
         
         $use_timthumb = get_option('asp_usetimbthumb');
         
         if ($use_timthumb) {
           if ($searchData['settings-imagesettings']['show']==1) {
              $imgs = $searchData['settings-imagesettings'];
              ksort($imgs['from']);
              
              if ($searchData['resultstype'] == 'vertical') {
                  $_width = $imgs['width'];
                  $_height = $imgs['height'];
              } else {
                  $_width = wpdreams_width_from_px($searchData['hreswidth']);
                  $_vimageratio =  $_width / $searchData['selected-imagesettings']['width'];
                  $_height = $_vimageratio * $searchData['selected-imagesettings']['height'];                
              }  
              
              foreach($imgs['from'] as $kk=>$source) {             
                if ($kk == -11) continue;
                if (isset($r->image) || $r->image!='') continue;
                
                if ($source=='featured') { 
                  $im = wp_get_attachment_url( get_post_thumbnail_id($v->id) );
                } else if($source=='content') {
                  $im = wpdreams_get_image_from_content($v->content, $imgs['imagenum']);
                } else if($source=='excerpt') {
                  $im = wpdreams_get_image_from_content($v->excerpt, $imgs['imagenum']);
                } else if($source=='usecustom') {
                  if (isset($imgs['customname']) && $imgs['customname']!='') {
                    $im = get_post_meta($v->id, $imgs['customname'], true);
                    if ($im!='' && $im!=false) 
                      ;                                
                    else
                      continue;
                  } else {
                    continue;
                  }
                }
                if ($im!==false && $im!='') {
                  $r->image = plugins_url('/ajax-search-pro/includes/timthumb.php').'?cc='.str_replace('#', '', wpdreams_rgb2hex($searchData['imagebg'])).'&ct=0&q=95&w='.$_width.'&h='.$_height.'&src='.rawurlencode($im);
                  break;
                } 
              } 
           } 
         } else {
                
           if ($searchData['settings-imagesettings']['show']==1) {
             $imgs = $searchData['settings-imagesettings'];
             ksort($imgs['from']);
             
              if ($searchData['resultstype'] == 'vertical') {
                  $_width = $imgs['width'];
                  $_height = $imgs['height'];
              } else if($searchData['resultstype'] == 'horizontal') {
                  $_width = wpdreams_width_from_px($searchData['hreswidth']);
                  $_vimageratio =  $_width / $searchData['selected-imagesettings']['width'];
                  $_height = $_vimageratio * $searchData['selected-imagesettings']['height'];                
              } else {
                  $_width = wpdreams_width_from_px($searchData['preswidth']) - 2*wpdreams_width_from_px($searchData['prespadding']);
                  $_height = $_width;
              }           
             
             foreach($imgs['from'] as $kk=>$source) {             
              if ($kk == -11) continue;
              if (isset($r->image) || $r->image!='') continue;
              // Calculate the image size
  
              if ($source=='featured') {
                //$im = get_the_post_thumbnail($v->id);  
                $im = wp_get_attachment_url( get_post_thumbnail_id($v->id) );
                //var_dump($im);
                $img = new wpdreamsImageCache($im, "post".$v->id, AJAXSEARCHPRO_PATH.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR, $_width, $_height, -1, $searchData['imagebg']);
              } else if($source=='content') {
                $img = new wpdreamsImageCache($v->content, "post".$v->id, AJAXSEARCHPRO_PATH.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR, $_width, $_height, $imgs['imagenum'], $searchData['imagebg']);
              } else if($source=='excerpt') {
                $img = new wpdreamsImageCache($v->excerpt, "post".$v->id, AJAXSEARCHPRO_PATH.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR, $_width, $_height, $imgs['imagenum'], $searchData['imagebg']);
              } else if($source=='usecustom') {
                if (isset($imgs['customname']) && $imgs['customname']!='') {
                  $im = get_post_meta($v->id, $imgs['customname'], true);
                  if ($im!='' && $im!=false) 
                    $img = new wpdreamsImageCache($im, "post".$v->id, AJAXSEARCHPRO_PATH.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR, $_width, $_height, -1, $searchData['imagebg']);                                
                  else
                    continue;
                } else {
                  continue;
                }
              }
              $res = $img->get_image();
              if ($res!='') {
                $r->image = plugins_url('/ajax-search-pro/cache/'.$res );
                break;
              } 
             } 
           } 
         }
         
          
         if (!isset($searchData['titlefield']) || $searchData['titlefield']=="0" || is_array($searchData['titlefield'])){
           $r->title = get_the_title($r->id); 
         } else {
           if ($searchData['titlefield']=="1") {
             if (strlen($r->excerpt)>=200)
              $r->title = substr($r->excerpt, 0, 200);
             else
              $r->title = $r->excerpt;
           } else {
             $mykey_values = get_post_custom_values($searchData['titlefield'],  $r->id);
             if (isset($mykey_values[0])) {
               $r->title = $mykey_values[0];
             } else {
               $r->title = get_the_title($r->id);  
             }
           }
         }
                                 
         if (!isset($searchData['striptagsexclude'])) $searchData['striptagsexclude'] = "<a><span>";

         if (!isset($searchData['descriptionfield']) || $searchData['descriptionfield']=="0" || is_array($searchData['descriptionfield'])){
            if (function_exists('qtrans_getLanguage'))
              $r->content = apply_filters('the_content', $r->content);
            $_content = strip_tags($r->content, $searchData['striptagsexclude']);
         } else {
           if ($searchData['descriptionfield']=="1") {
             $_content = strip_tags($r->excerpt, $searchData['striptagsexclude']);
           } else if($searchData['descriptionfield']=="2") {
             $_content = strip_tags(get_the_title($r->id), $searchData['striptagsexclude']);
           } else {
             $mykey_values = get_post_custom_values($searchData['descriptionfield'],  $r->id);
             if (isset($mykey_values[0])) {
               $_content = strip_tags($mykey_values[0], $searchData['striptagsexclude']);
             } else {
               $_content = strip_tags(get_content_w($r->content), $searchData['striptagsexclude']);
             }
           }
         }                                                               
         if ($_content=="") $_content = $r->content;
         if (isset($searchData['runshortcode']) && $searchData['runshortcode']==1) {
           if ($_content!="") $_content = apply_filters('the_content', $_content);
           if ($_content!="") $_content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $_content);
         }
         if (isset($searchData['stripshortcode']) && $searchData['stripshortcode']==1) {
           if ($_content!="") $_content = strip_shortcodes($_content);
         } 
         $_content = strip_tags($_content, $searchData['striptagsexclude']);

         if ($_content!='' && (strlen($_content) > $searchData['descriptionlength']))
          $r->content = substr($_content, 0, $searchData['descriptionlength'])."..."; 
         else
          $r->content = $_content."..."; 
          
         $r->title = apply_filters( 'asp_result_title_after_prostproc' , $r->title, $r->id);
         $r->content = apply_filters( 'asp_result_content_after_prostproc' ,$r->content, $r->id);
         $r->image = apply_filters( 'asp_result_image_after_prostproc' ,$r->image, $r->id);
         $r->author = apply_filters( 'asp_result_author_after_prostproc' ,$r->author, $r->id);
         $r->date = apply_filters( 'asp_result_date_after_prostproc' ,$r->date, $r->id);
                   
      }
      /* !Images, title, desc */
      //var_dump($pageposts); die();      
      $this->results = $pageposts;      
      return $pageposts;
      
    }  

    protected function group() {
      
      $pageposts = $this->results;
      $options = $this->options;
      $searchData = $this->searchData;   
      $allpageposts = array();
      $s = $this->s;
      $_s = $this->_s;
      
      if($options['do_group']==false) return;      
      
      // Need a suffix to separate blogs 
      if (isset($blog)) {
        $_key_suff = "_".$blog;
      } else {
        $_key_suff = "";
      }
      /* Regrouping */
      // By category
      if ($searchData['groupby']==1 && count($pageposts)>0) {
         $_pageposts = array();
         foreach ($pageposts as $k=>$v) {
            if ($v->term_id=="" || ($v->post_type!='post' && $searchData['pageswithcategories']!=1)) {
              $_pageposts['99999']['data'][] = $v;
              continue;
            }
            $_term_ids = explode(',', $v->term_id);
            if (count($_term_ids)<=0) $_term_ids = array($v->term_id);
            foreach ($_term_ids as $_term_id) {
              $cat = get_category($_term_id);
              if (!is_object($cat) || trim($cat->name)=="") {
                $_pageposts['99999']['data'][] = $v;
              } else {
                $_pageposts[$_term_id]['data'][] = $v;
              }
            }
         }

         foreach($_pageposts as $k=>$v) {
            if ($searchData['showpostnumber']==1) {
              $num = " (".count($_pageposts[$k]['data']).")";
            } else {
              $num = "";
            }
            if ($k!=99999) {
              $cat = get_category($k);
              $_pageposts[$k]['name'] = str_replace('%GROUP%', $cat->name, $searchData['groupbytext']).$num;
            } else {
              $_pageposts[$k]['name'] = $searchData['uncategorizedtext'].$num;
            }
         }

         $pageposts = null;
         $pageposts['grouped'] = 1;
         $pageposts['items'] = $_pageposts;
         ksort($pageposts['items']);
         if ($_key_suff!="") {
           foreach($pageposts['items'] as $k=>$v) {
            $pageposts['items'][$k.$_key_suff] = $v;
            unset($pageposts['items'][$k]);
           }
         }  
      // By post type
      } else if ($searchData['groupby']==2 && count($pageposts)>0) {
         foreach ($pageposts as $k=>$v) {
            $_pageposts[$v->post_type]['data'][] = $v;
         }
         foreach($_pageposts as $k=>$v) {
            if ($searchData['showpostnumber']==1) {
              $num = " (".count($_pageposts[$k]['data']).")";
            } else {
              $num = "";
            }
            $obj = get_post_type_object($k);
            $_pageposts[$k]['name'] = str_replace('%GROUP%', $obj->labels->singular_name, $searchData['groupbytext']).$num;
         }
         $pageposts = null;
         $pageposts['grouped'] = 1;
         $pageposts['items'] = $_pageposts;
         ksort($pageposts['items']);        
      } 
      
      if (($searchData['groupby']==1 || $searchData['groupby']==2) && count($pageposts)>0 && count($allpageposts)>0)
          $allpageposts['items'] = array_merge($allpageposts['items'], $pageposts['items']);
      else 
          $allpageposts = array_merge($allpageposts, $pageposts); 
      
      $this->results = $allpageposts;
      return $this->results;
      
    }     
    
  }
}
?>