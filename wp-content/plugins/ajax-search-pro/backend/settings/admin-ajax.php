<?php
  /* Ajax functions here */

  add_action('wp_ajax_ajaxsearchpro_deletekeyword', 'ajaxsearchpro_deletekeyword');
  function ajaxsearchpro_deletekeyword() {
    global $wpdb;
    if (isset($wpdb->base_prefix)) {
      $_prefix = $wpdb->base_prefix;
    } else {
      $_prefix = $wpdb->prefix;
    }
    if (isset($_POST['keywordid'])) {
      $wpdb->query("DELETE FROM ".$_prefix."ajaxsearchpro_statistics WHERE id=".$_POST['keywordid']);
      print 1;
    }
    die();
  } 
  
  add_action('wp_ajax_ajaxsearchpro_activate_fulltext', 'ajaxsearchpro_activate_fulltext');
  function ajaxsearchpro_activate_fulltext() {
    global $wpdb;
    $fulltext = wpdreams_fulltext::getInstance();
    $tables = array('posts');
    $indexes = array(
       array('table'=>'posts', 'index'=>'asp_title', 'columns'=>'post_title'),
       array('table'=>'posts', 'index'=>'asp_content', 'columns'=>'post_content'),
       array('table'=>'posts', 'index'=>'asp_excerpt', 'columns'=>'post_excerpt'),
       array('table'=>'posts', 'index'=>'asp_title_content', 'columns'=>'post_title, post_content'),
       array('table'=>'posts', 'index'=>'asp_title_excerpt', 'columns'=>'post_title, post_excerpt'),
       array('table'=>'posts', 'index'=>'asp_content_excerpt', 'columns'=>'post_content, post_excerpt'),
       array('table'=>'posts', 'index'=>'asp_title_content_excerpt', 'columns'=>'post_title, post_content, post_excerpt')
    );
    $blogs = wpdreams_get_blog_list(0, 'all');
    
    if (is_multisite() && is_array($blogs) && count($blogs)) {
       foreach($blogs as $k=>$blog) {
         switch_to_blog($blog['blog_id']);
         if($fulltext->check($tables)) {
            update_option('asp_fulltext', 1);
            if ($fulltext->createIndexes($indexes))
              update_option('asp_fulltext_indexed', 1);
         } 
       }
       restore_current_blog();
    } else {
       if($fulltext->check($tables)) {
          update_option('asp_fulltext', 1);
          if ($fulltext->createIndexes($indexes))
            update_option('asp_fulltext_indexed', 1);
       }
    }
    if (get_option('asp_fulltext')==1) {
      print "<div class='psuccessMsg'>MyIsam tables enabled, fulltext search available!</div>";
      if (get_option('asp_fulltext_indexed')==1)
         print "<div class='psuccessMsg'>Indexes created!</div>";
      else
         print "<div class='perrorMsg'>Couldn't create indexes, using BOOLEAN MODE instead!</div>";
    } else {
      print "<div class='perrorMsg'>MyIsam tables disabled, fulltext search not available!</div>";
    }
    die();
  }
  
  add_action('wp_ajax_ajaxsearchpro_deactivate_fulltext', 'ajaxsearchpro_deactivate_fulltext');
  function ajaxsearchpro_deactivate_fulltext() {
    global $wpdb;
    $fulltext = wpdreams_fulltext::getInstance();
    $indexes = array(
       'posts'=>array(
        'asp_title',
        'asp_content',
        'asp_excerpt',
        'asp_title_content',
        'asp_title_excerpt',
        'asp_content_excerpt',
        'asp_title_content_excerpt'
    ));
    $blogs = wpdreams_get_blog_list(0, 'all');
    
    if (is_multisite() && is_array($blogs) && count($blogs)) {
       foreach($blogs as $k=>$blog) {
          switch_to_blog($blog['blog_id']);
          $fulltext->removeIndexes($indexes); 
       }
       restore_current_blog();
    } else {
       $fulltext->removeIndexes($indexes);
    }
    update_option('asp_fulltext_indexed', 0);
    print "<div class='psuccessMsg'>Indexes removed!</div>";
    die();
  } 
  
  
  
?>