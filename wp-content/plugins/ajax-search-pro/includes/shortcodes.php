<?php
  add_shortcode( 'wpdreams_ajaxsearchpro', 'add_ajaxsearchpro');
  add_shortcode( 'wpdreams_ajaxsearchpro_results', 'add_ajaxsearchpro_results');

  function wpdreams_asp_echo_out() {
    global $asp_head_out;
    ?>
    <style type="text/css">
        <?php echo $asp_head_out; ?>
    </style>
    <?php 
  }

  function search_stylesheets() {
    global $wpdb;
    global $asp_head_out;
    
    $force_inline = get_option('asp_forceinlinestyles');
    wp_enqueue_style('wpdreams_animations', plugins_url('css/animations.css' , dirname(__FILE__)), false);
   
    if (isset($wpdb->base_prefix)) {
      $_prefix = $wpdb->base_prefix;
    } else {
      $_prefix = $wpdb->prefix;
    }
    $search = $wpdb->get_results("SELECT * FROM ".$_prefix."ajaxsearchpro", ARRAY_A);
    if (is_array($search)) {
      foreach ($search as $s) {       
        
        if ($force_inline==1) {
          $s['data'] = json_decode($s['data'], true);  
          $style = $s['data'];
          $id = $s['id']; 
          ob_start();
          include(AJAXSEARCHPRO_PATH."/css/style.css.php");
        	$out = ob_get_contents();
          $asp_head_out .= $out;
        	ob_end_clean(); 

        } else {
          wp_enqueue_style('wpdreams-ajaxsearchpro'.$s['id'], plugins_url('css/style'.$s['id'].'.css' , dirname(__FILE__)), false);
        } 
        
      }
      
      if ($force_inline==1)
        add_action('wp_head', 'wpdreams_asp_echo_out', 10, 0);
    }   
  }     
  add_action('wp_print_styles', 'search_stylesheets');
  

  
  function add_ajaxsearchpro_results( $atts ) {
  	extract( shortcode_atts( array(
  		'id' => '0',
      'element' => 'div'
  	), $atts ) ); 
    if ($id == 0) return;
    return "<".$element." id='wpdreams_asp_results_".$id."'></".$element.">"; 
  }
  
  function add_ajaxsearchpro( $atts ) {
    ob_start();
    $style = null;
  	extract( shortcode_atts( array(
  		'id' => 'something'
  	), $atts ) );    
    if (isset($_POST['action']) && $_POST['action']=="ajaxsearchpro_preview") {
      require_once(AJAXSEARCHPRO_PATH."backend".DIRECTORY_SEPARATOR."settings".DIRECTORY_SEPARATOR."types.inc.php");
      parse_str($_POST['formdata'], $style);
      $file = AJAXSEARCHPRO_PATH.DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."style-preview".$id.".css";
      ob_start();
      include(AJAXSEARCHPRO_PATH."/css/style.css.php");
    	$out = ob_get_contents();
    	ob_end_clean();
      file_put_contents($file, $out, FILE_TEXT);   
      ?>
      <style>
          @import url('<?php echo plugin_dir_url(__FILE__); ?>../css/style-preview<?php echo $id; ?>.css?r=<?php echo rand(1, 123123123); ?>');
      </style>
      <?php
    } else {
      global $wpdb;
      if (isset($wpdb->base_prefix)) {
        $_prefix = $wpdb->base_prefix;
      } else {
        $_prefix = $wpdb->prefix;
      } 
      $search = $wpdb->get_results("SELECT * FROM ".$_prefix."ajaxsearchpro WHERE id=".$id, ARRAY_A);
      if (!isset($search[0])) {
        echo "This search form does not exist!";
        $return = ob_get_clean();
        return $return;
      } 
      if (isset($search[0]['id']) && isset($wpdreams_ajaxsearchpros[$search[0]['id']])) {
        echo "This search form is already on the page! You cannot use the same form twice on one page!";
        $return = ob_get_clean();
        return $return;
      } 
      $wpdreams_ajaxsearchpros[$search[0]['id']] = 1;
      
      $search[0]['data'] = json_decode($search[0]['data'], true);  
      $style = $search[0]['data'];     
    }
    
        


 
    $settingsHidden = ((
      $style['showexactmatches']!=1 &&
      $style['showsearchintitle']!=1 &&
      $style['showsearchincontent']!=1 &&
      $style['showsearchinexcerpt']!=1 &&
      $style['showsearchinposts']!=1 &&
      $style['showsearchinpages']!=1 &&
      $style['showsearchinproducts']!=1 &&
      $style['showsearchinbpusers']!=1 &&
      $style['showsearchinbpgroups']!=1 &&
      $style['showsearchinbpforums']!=1 &&
      count($style['selected-showcustomtypes'])<=0
      )?true:false);
                                                                    
    do_action('asp_layout_before_shortcode', $id);
    
    if (AJAXSEARCHPRO_DEBUG) {
      //$style['resultstype'] = 'horizontal';
    }
      
    ?>

    
    <div id='ajaxsearchpro<?php echo $id; ?>'>
         <div class="probox"> 
              

              
              <?php do_action('asp_layout_before_magnifier', $id);  ?> 
              
              <div class='promagnifier'>
                <?php do_action('asp_layout_in_magnifier', $id);  ?>
                <div class='innericon'></div>
              </div>
              
              <?php do_action('asp_layout_after_magnifier', $id);  ?> 
              
              <?php do_action('asp_layout_before_settings', $id);  ?>
                            
              <div class='prosettings' <?php echo ($settingsHidden?"style='display:none;'":""); ?>opened=0>
                <?php do_action('asp_layout_in_settings', $id);  ?>
                <div class='innericon'></div>
              </div>
              
              <?php do_action('asp_layout_after_settings', $id);  ?>
              
              <?php do_action('asp_layout_before_input', $id);  ?>
              
              <div class='proinput'>
                <form action='' autocomplete = "off">
                <input type='search' class='orig' name='phrase' value='' autocomplete = "off" />
                <input type='text' class='autocomplete' name='phrase' value='' autocomplete = "off" />
                <span class='loading'></span>
                <input type='submit' style='width:0; height: 0; visibility: hidden;'>
                </form>
              </div>
              
              <?php do_action('asp_layout_after_input', $id);  ?>              
              
              <?php do_action('asp_layout_before_loading', $id);  ?>
              
              <div class='proloading'>
                <?php do_action('asp_layout_in_loading', $id);  ?>
              </div> 
              
              <?php do_action('asp_layout_after_loading', $id);  ?>                           
         
         </div>
         <div id='ajaxsearchprosettings<?php echo $id; ?>' class="searchsettings">
          <form name='options'>
          
             <?php do_action('asp_layout_settings_before_first_item', $id);  ?>

             <div class="option hiddend">
              	<input type='hidden' name='qtranslate_lang' id='qtranslate_lang' value='<?php echo (function_exists('qtrans_getLanguage')?qtrans_getLanguage():'0'); ?>'/>
             </div> 
             
             <div class="option<?php echo (($style['showexactmatches']!=1)?" hiddend":""); ?>">
              	<input type="checkbox" value="checked" id="set_exactonly<?php echo $id; ?>" name="set_exactonly" <?php echo (($style['exactonly']==1)?'checked="checked"':''); ?>/>
              	<label for="set_exactonly<?php echo $id; ?>"></label>
             </div>            
             <div class="label<?php echo (($style['showexactmatches']!=1)?" hiddend":""); ?>">
                <?php echo $style['exactmatchestext']; ?>
             </div>
             <div class="option<?php echo (($style['showsearchintitle']!=1)?" hiddend":""); ?>">
              	<input type="checkbox" value="None" id="set_intitle<?php echo $id; ?>" name="set_intitle" <?php echo (($style['searchintitle']==1)?'checked="checked"':''); ?>/>
              	<label for="set_intitle<?php echo $id; ?>"></label>
             </div>
             <div class="label<?php echo (($style['showsearchintitle']!=1)?" hiddend":""); ?>">
                <?php echo $style['searchintitletext']; ?>
             </div> 
             <div class="option<?php echo (($style['showsearchincontent']!=1)?" hiddend":""); ?>">
              	<input type="checkbox" value="None" id="set_incontent<?php echo $id; ?>" name="set_incontent" <?php echo (($style['searchincontent']==1)?'checked="checked"':''); ?>/>
              	<label for="set_incontent<?php echo $id; ?>"></label>
             </div>
             <div class="label<?php echo (($style['showsearchincontent']!=1)?" hiddend":""); ?>">
                <?php echo $style['searchincontenttext']; ?>
             </div>
             <div class="option<?php echo (($style['showsearchincomments']!=1)?" hiddend":""); ?>">
              	<input type="checkbox" value="None" id="set_incomments<?php echo $id; ?>" name="set_incomments" <?php echo (($style['searchincomments']==1)?'checked="checked"':''); ?>/>
              	<label for="set_incomments<?php echo $id; ?>"></label>
             </div>
             <div class="label<?php echo (($style['showsearchincomments']!=1)?" hiddend":""); ?>">
                <?php echo $style['searchincommentstext']; ?>
             </div>
             <div class="option<?php echo (($style['showsearchinexcerpt']!=1)?" hiddend":""); ?>">
              	<input type="checkbox" value="None" id="set_inexcerpt<?php echo $id; ?>" name="set_inexcerpt" <?php echo (($style['searchinexcerpt']==1)?'checked="checked"':''); ?>/>
              	<label for="set_inexcerpt<?php echo $id; ?>"></label>
             </div>
             <div class="label<?php echo (($style['showsearchinexcerpt']!=1)?" hiddend":""); ?>">
                <?php echo $style['searchinexcerpttext']; ?>
             </div>
             <div class="option<?php echo (($style['showsearchinposts']!=1)?" hiddend":""); ?>">
              	<input type="checkbox" value="None" id="set_inposts<?php echo $id; ?>" name="set_inposts" <?php echo (($style['searchinposts']==1)?'checked="checked"':''); ?>/>
              	<label for="set_inposts<?php echo $id; ?>"></label>
             </div>
             <div class="label<?php echo (($style['showsearchinposts']!=1)?" hiddend":""); ?>">
                <?php echo $style['searchinpoststext']; ?>
             </div>
             <div class="option<?php echo (($style['showsearchinpages']!=1)?" hiddend":""); ?>">
              	<input type="checkbox" value="None" id="set_inpages<?php echo $id; ?>" name="set_inpages" <?php echo (($style['searchinpages']==1)?'checked="checked"':''); ?>/>
              	<label for="set_inpages<?php echo $id; ?>"></label>
             </div>
             <div class="label<?php echo (($style['showsearchinpages']!=1)?" hiddend":""); ?>">
                <?php echo $style['searchinpagestext']; ?>
             </div>
             <div class="option<?php echo (($style['showsearchinbpgroups']!=1)?" hiddend":""); ?>">
              	<input type="checkbox" value="None" id="set_inbpgroups<?php echo $id; ?>" name="set_inbpgroups" <?php echo (($style['searchinbpgroups']==1)?'checked="checked"':''); ?>/>
              	<label for="set_inbpgroups<?php echo $id; ?>"></label>
             </div>
             <div class="label<?php echo (($style['showsearchinbpgroups']!=1)?" hiddend":""); ?>">
                <?php echo $style['searchinbpgroupstext']; ?>
             </div>
             <div class="option<?php echo (($style['showsearchinbpusers']!=1)?" hiddend":""); ?>">
              	<input type="checkbox" value="None" id="set_inbpusers<?php echo $id; ?>" name="set_inbpusers" <?php echo (($style['searchinbpusers']==1)?'checked="checked"':''); ?>/>
              	<label for="set_inbpusers<?php echo $id; ?>"></label>
             </div>
             <div class="label<?php echo (($style['showsearchinbpusers']!=1)?" hiddend":""); ?>">
                <?php echo $style['searchinbpuserstext']; ?>
             </div>                 
             <div class="option<?php echo (($style['showsearchinbpforums']!=1)?" hiddend":""); ?>">
              	<input type="checkbox" value="None" id="set_inbpforums<?php echo $id; ?>" name="set_inbpforums" <?php echo (($style['searchinbpforums']==1)?'checked="checked"':''); ?>/>
              	<label for="set_inbpforums<?php echo $id; ?>"></label>
             </div>
             <div class="label<?php echo (($style['showsearchinbpforums']!=1)?" hiddend":""); ?>">
                <?php echo $style['searchinbpforumstext']; ?>
             </div> 
             <?php
                
                  $types = get_post_types(array(
                    '_builtin'=>false
                  )); 
                  $i = 1;
                  if (!is_array($style['selected-customtypes'])) $style['selected-customtypes'] = array();
                  if (is_array($style['selected-showcustomtypes'])) {   
                    foreach ($style['selected-showcustomtypes'] as $k=>$v) { 
                      $selected = in_array($v[0], $style['selected-customtypes']);
                      $hidden = "";
                      ?>
                       <div class="option<?php echo $hidden; ?>">
                        	<input type="checkbox" value="<?php echo $v[0]; ?>" id="<?php echo $id; ?>customset_<?php echo $id.$i; ?>" name="customset[]" <?php echo (($selected)?'checked="checked"':''); ?>/>
                        	<label for="<?php echo $id; ?>customset_<?php echo $id.$i; ?>"></label>
                       </div>
                       <div class="label<?php echo $hidden; ?>">
                          <?php echo $v[1]; ?>
                       </div> 
                      <?php
                      $i++;
                    }
                  }      
                  if (!is_array($style['selected-showcustomtypes'])) $style['selected-showcustomtypes'] = array();
                  if (is_array($types)) {
                    foreach($types as $k=>$v) {
                      if (is_array($style['selected-customtypes']) && !in_array($v, $style['selected-showcustomtypes'])) {
                        if (!is_array($style['selected-customtypes'])) $style['selected-customtypes'] = array();
                        $selected = in_array($v, $style['selected-customtypes']);
                        $hidden = " hiddend";
                        ?>
                         <div class="option<?php echo $hidden; ?>">
                          	<input type="checkbox" value="<?php echo $v; ?>" id="<?php echo $id; ?>customset_<?php echo $id.$i; ?>" name="customset[]" <?php echo (($selected)?'checked="checked"':''); ?>/>
                          	<label for="<?php echo $id; ?>customset_<?php echo $id.$i; ?>"></label>
                         </div>
                         <div class="label<?php echo $hidden; ?>">
                            <?php echo $val; ?>
                         </div> 
                        <?php
                      }
                      $i++;
                    }
                  }
                  
                  
             ?> 
             <?php
             /* Category and term filters */
             if ($style['showsearchincategories']) {
               ?>
               
               <fieldset>
                  <?php if ($style['exsearchincategoriestext']!=""): ?>
                  <legend><?php echo $style['exsearchincategoriestext']; ?></legend>
                  <?php endif; ?>
                  <div class='categoryfilter'>
               <?php
             
             }
             
             /* Categories */
             if (!is_array($style['selected-exsearchincategories'])) $style['selected-exsearchincategories'] = array();
             if (!is_array($style['selected-excludecategories'])) $style['selected-excludecategories'] = array();  
             $_all_cat = get_all_category_ids();
             $_needed_cat = array_diff($_all_cat, $style['selected-exsearchincategories']);
             foreach ($_needed_cat as $k=>$v) {
                $selected = !in_array($v, $style['selected-excludecategories']);
                $cat = get_category($v);
                $val = $cat->name;
                $hidden = (($style['showsearchincategories'])==0?" hiddend":"");
                if ($style['showuncategorised']==0 && $v==1) {
                   $hidden = ' hiddend';
                }
                ?>
                 <div class="option<?php echo $hidden; ?>">
                  	<input type="checkbox" value="<?php echo $v; ?>" id="<?php echo $id; ?>categoryset_<?php echo $v; ?>" name="categoryset[]" <?php echo (($selected)?'checked="checked"':''); ?>/>
                  	<label for="<?php echo $id; ?>categoryset_<?php echo $v; ?>"></label>
                 </div>
                 <div class="label<?php echo $hidden; ?>">
                    <?php echo $val; ?>
                 </div> 
                <?php
             }
             
             if ($style['showsearchincategories'] && $style['showseparatefilterboxes']!=0) {
             ?>
               </div>
             </fieldset>
             
             <?php do_action('asp_layout_settings_after_last_item', $id);  ?>
             
             <?php
             }
             
             
             /* Terms */ 
             if ($style['showsearchintaxonomies']==1) {
               if (!is_array($style['selected-excludeterms'])) $style['selected-excludeterms'] = array();
               if (!is_array($style['selected-showterms'])) $style['selected-showterms'] = array();
                 
               $_all_terms = wpdreams_get_all_terms();
               $_all_term_ids = wpdreams_get_all_term_ids();
               //var_dump($_all_terms);
               $_needed_terms = array_diff($_all_term_ids, $style['selected-excludeterms']);
               $_invisible_terms =  array_diff($_needed_terms, $style['selected-showterms']);
               //$counter = 0;                                                             
               
               
      
               $_close_fieldset = false;
               foreach ($style['selected-showterms'] as $taxonomy=>$terms) {
                 if (is_array($terms)) {
                 
                   if ($style['showseparatefilterboxes']!=0) {
                    $_x_term = get_taxonomies(array("name"=>$taxonomy), "objects");
                    //var_dump($_x_term);
                    if (isset($_x_term[$taxonomy])) 
                      $_tax_name = $_x_term[$taxonomy]->label; 
                       ?>
                   <fieldset>
                    <legend><?php echo $style['exsearchintaxonomiestext']." ".$_tax_name; ?></legend>
                    <div class='categoryfilter'>                 
                       <?php
                   }
  
                   foreach ($terms as $k=>$term) {      
                      ?>
                       <div class="option">  
                          <input type="hidden" value="<?php echo $taxonomy; ?>" name="termset[<?php echo $term; ?>]" />  
                        	<input type="checkbox" value="0" id="<?php echo $id; ?>termset_<?php echo $term; ?>" name="termset[<?php echo $term; ?>]" checked/>
                        	<label for="<?php echo $id; ?>termset_<?php echo $term; ?>"></label>
                       </div>
                       <div class="label">
                          <?php echo $_all_terms[$term]->name; ?>
                       </div> 
                      <?php 
                      //$counter++;            
                   }
  
                   if ($style['showseparatefilterboxes']!=0) {
                       ?>
                     </div>
                   </fieldset>              
                       <?php
                   }                    
                   
                 }
               }
               
  
               
               
               foreach ($style['selected-excludeterms'] as $taxonomy=>$terms) {
                  if (is_array($terms)) {
                    foreach ($terms as $k=>$term) {
                      ?>
                       <div class="option" style='display:none;'>
                          <input type="hidden" value="<?php echo $taxonomy; ?>" name="termset[<?php echo $term; ?>]" />
                       </div>
                       <div class="label"></div> 
                      <?php            
                    }
                  }
               }
  
               if ($style['showsearchincategories'] && $style['showseparatefilterboxes']!=1) {
               ?>
                 </div>
               </fieldset>
               
               <?php do_action('asp_layout_settings_after_last_item', $id);  ?>
               
               <?php
               }  
             }
             ?>     
          </form> 
         </div>
    </div> 
    <div id='ajaxsearchprores<?php echo $id; ?>' class='<?php echo $style['resultstype']; ?>'>
    
        <?php do_action('asp_layout_before_results', $id);  ?>
        
        <div class="results">
        
          <?php do_action('asp_layout_before_first_result', $id);  ?>
        
          <div class="resdrg">                                  
          </div>
        
          <?php do_action('asp_layout_after_last_result', $id);  ?>
        
        </div>
        
        <?php do_action('asp_layout_after_results', $id);  ?>
       
        <?php if($style['showmoreresults']==1): ?>
          <?php do_action('asp_layout_before_showmore', $id);  ?>
          <p class='showmore'>
            <a href='<?php home_url('/'); ?>?s='><?php echo $style['showmoreresultstext']; ?></a>
          </p>
          <?php do_action('asp_layout_after_showmore', $id);  ?>
        <?php endif; ?>
        
    </div>    
    <?php
    /*if (isset($_POST['action']) && $_POST['action']=="ajaxsearchpro_preview") {
      ;
    } else if(1) { */
    ?>
      <script>
      aspjQuery(document).ready(function() {
         aspjQuery("#ajaxsearchpro<?php echo $id; ?>").ajaxsearchpro({
          homeurl: '<?php echo home_url('/'); ?>',
          resultstype: '<?php echo ((isset($style['resultstype']) && $style['resultstype']!="")?$style['resultstype']:"vertical"); ?>',
          resultsposition: '<?php echo ((isset($style['resultsposition']) && $style['resultsposition']!="")?$style['resultsposition']:"vertical"); ?>',
          itemscount: <?php echo ((isset($style['itemscount']) && $style['itemscount']!="")?$style['itemscount']:"2"); ?>,
          imagewidth: <?php echo ((isset($style['settings-imagesettings']['width']))?$style['settings-imagesettings']['width']:"70"); ?>,
          imageheight: <?php echo ((isset($style['settings-imagesettings']['height']))?$style['settings-imagesettings']['height']:"70"); ?>,
          resultitemheight: '<?php echo ((isset($style['resultitemheight']) && $style['resultitemheight']!="")?$style['resultitemheight']:"70"); ?>',
          showauthor: <?php echo ((isset($style['showauthor']) && $style['showauthor']!="")?$style['showauthor']:"1"); ?>,
          showdate: <?php echo ((isset($style['showdate']) && $style['showdate']!="")?$style['showdate']:"1"); ?>,
          showdescription: <?php echo ((isset($style['showdescription']) && $style['showdescription']!="")?$style['showdescription']:"1"); ?>,
          charcount:  <?php echo ((isset($style['charcount']) && $style['charcount']!="")?$style['charcount']:"3"); ?>,
          noresultstext: '<?php echo ((isset($style['noresultstext']) && $style['noresultstext']!="")?$style['noresultstext']:"3"); ?>',
          didyoumeantext: '<?php echo ((isset($style['didyoumeantext']) && $style['didyoumeantext']!="")?$style['didyoumeantext']:"3"); ?>',
          highlight: <?php echo ((isset($style['highlight']) && $style['highlight']!="")?$style['highlight']:1); ?>,
          highlightwholewords: <?php echo ((isset($style['highlightwholewords']) && $style['highlightwholewords']!="")?$style['highlightwholewords']:1); ?>,
          resultareaclickable: <?php echo ((isset($style['resultareaclickable']) && $style['resultareaclickable']!="")?$style['resultareaclickable']:0); ?>,    
          defaultsearchtext: '<?php echo ((isset($style['defaultsearchtext']) && $style['defaultsearchtext']!="")?$style['defaultsearchtext']:""); ?>',
          autocomplete: <?php echo ((isset($style['autocomplete']) && $style['autocomplete']!="")?$style['autocomplete']:1); ?>,
          triggerontype: <?php echo ((isset($style['triggerontype']) && $style['triggerontype']!="")?$style['triggerontype']:1); ?>,
          triggeronclick: <?php echo ((isset($style['triggeronclick']) && $style['triggeronclick']!="")?$style['triggeronclick']:1); ?>,
          redirectonclick: <?php echo ((isset($style['redirectonclick']) && $style['redirectonclick']!="")?$style['redirectonclick']:0); ?>,
          settingsimagepos: '<?php echo ((isset($style['settingsimagepos']) && $style['settingsimagepos']!="")?$style['settingsimagepos']:0); ?>',
          hresultanimation: '<?php echo ((isset($style['hresultinanim']) && $style['hresultinanim']!="")?$style['hresultinanim']:0); ?>',
          vresultanimation: '<?php echo ((isset($style['vresultinanim']) && $style['vresultinanim']!="")?$style['vresultinanim']:0); ?>',
          hresulthidedesc: '<?php echo ((isset($style['hhidedesc']) && $style['hhidedesc']!="")?$style['hhidedesc']:1); ?>',
          prescontainerheight: '<?php echo ((isset($style['prescontainerheight']) && $style['prescontainerheight']!="")?$style['prescontainerheight']:"400px"); ?>',
          pshowsubtitle: '<?php echo ((isset($style['pshowsubtitle']) && $style['pshowsubtitle']!="")?$style['pshowsubtitle']:0); ?>',
          pshowdesc: '<?php echo ((isset($style['pshowdesc']) && $style['pshowdesc']!="")?$style['pshowdesc']:1); ?>'   
         });
      });       
      </script> 
    <?php
       
    do_action('asp_layout_after_shortcode', $id);
    
    $return = ob_get_clean();
    return $return;
  }  
  
?>