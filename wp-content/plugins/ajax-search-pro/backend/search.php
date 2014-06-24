<?php
    
    $params = array();
    
    $_themes = file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'settings'.DIRECTORY_SEPARATOR.'themes.json');

    if (isset($wpdb->base_prefix)) {
      $_prefix = $wpdb->base_prefix;
    } else {
      $_prefix = $wpdb->prefix;
    }    

    $search = $wpdb->get_row("SELECT * FROM ".$_prefix."ajaxsearchpro WHERE id=".$_GET['asp_sid'], ARRAY_A);
    $sd = json_decode($search['data'], true);
    //var_dump($_sd);
    $_def = get_option('asp_defaults');
     
?>
<script>
(function($) {  
  $(document).ready(function() {
  
   var ajaxurl = '<?php bloginfo("url"); ?>' + "/wp-content/plugins/ajax-search-pro/ajax_search.php";
   
   jQuery(jQuery('.tabs a')[0]).trigger('click');
   $('.tabs a[tabid=6]').click(function(){
        $('.tabs a[tabid=8]').click();
   });
   $('#wpdreams .settings').click(function(){
      $("#preview input[name=refresh]").attr('searchid', $(this).attr('searchid'));
   });
   $("select[id^=wpdreamsThemeChooser]").change(function(){
     $("#preview input[name=refresh]").click();
   });
   $("#preview .refresh").click(function(e){
      e.preventDefault();
      var $this =  $(this).parent();
      var id = <?php echo $search['id']; ?>;
      var loading = $('.big-loading', $this);
      $('.data', $this).html("");
      $('.data', $this).addClass('hidden');
      loading.removeClass('hidden');
      var data = {
    	  action: 'ajaxsearchpro_preview',
        id: id,
        formdata: $('form[name="asp_data"]').serialize()
    	};
    	$.post(ajaxurl, data, function(response) {
          loading.addClass('hidden');
          $('.data', $this).html(response);
          $('.data', $this).removeClass('hidden');
         //$(window).resize();
         //$(window).scroll();
          setTimeout(
            function(){
              aspjQuery(window).resize();
            },
          1000); 		
    	}); 
   });
   $("#preview .maximise").click(function(e){
      e.preventDefault();
      $this = $(this.parentNode);
      if (parseInt($this.css('bottom'))<0) {
        $this.animate({
          bottom: "0px"
        });
        $(this).html('Hide');
        $("#preview a.refresh").trigger('click');
      } else {
        $this.animate({
          bottom: "-470px"
        });
        $(this).html('Show');
      }  
   });
  $("#preview a.maximise").trigger('click');

  });
}(jQuery));
</script>



<div id="wpdreams" class='wpdreams wrap'>
  <?php if(AJAXSEARCHPRO_DEBUG==1): ?>
  <p class='infoMsg'>Debug mode is on!</p>
  <?php endif; ?>
  <div id='preview'>
    <a name='refresh' class='refresh' searchid='0' href='#'>Refresh</a>
    <a name='hide' class='maximise' />Show</a>
    <div class='big-loading hidden'></div>
    <div class="data hidden">
    </div>
  </div>

  <a class='back' href='<?php echo get_admin_url()."admin.php?page=ajax-search-pro/backend/settings.php"; ?>'>Back to the search list</a>
  <a class='statistics' href='<?php echo get_admin_url()."admin.php?page=ajax-search-pro/backend/statistics.php"; ?>'>Search Statistics</a>
  <a class='error' href='<?php echo get_admin_url()."admin.php?page=ajax-search-pro/backend/comp_check.php"; ?>'>Compatibility checking</a>
  <a class='cache' href='<?php echo get_admin_url()."admin.php?page=ajax-search-pro/backend/cache_settings.php"; ?>'>Caching options</a>
  <?php ob_start(); ?>
  <div class="wpdreams-box">
     <fieldset>
      <legend>
        <?php echo $search['name']; ?>
      </legend>
      <label class="shortcode">Search shortcode:</label>
      <input type="text" class="shortcode" value="[wpdreams_ajaxsearchpro id=<?php echo $search['id']; ?>]" readonly="readonly" />
      <label class="shortcode">Search shortcode for templates:</label>
      <input type="text" class="shortcode" value="&lt;?php echo do_shortcode('[wpdreams_ajaxsearchpro id=<?php echo $search['id']; ?>]'); ?&gt;" readonly="readonly" />
      <p style='margin:19px 10px 9px;'>Shortcodes for placing the result box elswhere. (only works if the result layout position is <b>block</b> - see in layout options tab)</p>
      <label class="shortcode">Result box shortcode:</label>
      <input type="text" class="shortcode" value="[wpdreams_ajaxsearchpro_results id=<?php echo $search['id']; ?> element='div']" readonly="readonly" />
      <label class="shortcode">Result shortcode for templates:</label>
      <input type="text" class="shortcode" value="&lt;?php echo do_shortcode('[wpdreams_ajaxsearchpro_results id=<?php echo $search['id']; ?> element=&quot;div&quot;]'); ?&gt;" readonly="readonly" />

     </fieldset>
  </div> 
  <div class="wpdreams-box">
    <form action='' method='POST' name='asp_data'>
    <ul id="tabs"  class='tabs'>
        <li><a tabid="1" class='current general'>General Options</a></li>
        <li><a tabid="2" class='multisite'>Multisite Options</a></li>
        <li><a tabid="3" class='frontend'>Frontend Search Settings</a></li>
        <li><a tabid="4" class='layout'>Layout options</a></li>
        <li><a tabid="5" class='autocomplete'>Autocomplete options</a></li>
        <li><a tabid="6" class='theme'>Theme options</a></li>
        <li><a tabid="20" class='advanced'>Relevance options</a></li> 
        <li><a tabid="7" class='advanced'>Advanced options</a></li>     
    </ul>
    <div id="content" class='tabscontent'> 
      <div tabid="1">
      <fieldset>
      <legend>Genearal Options</legend>
          <div class="item">
          <?php
          $o = new wpdreamsYesNo("searchinposts", "Search in posts?", setval_or_getoption($sd, 'searchinposts'));
          $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <div class="item">
          <?php
          $o = new wpdreamsYesNo("searchinpages", "Search in pages?", setval_or_getoption($sd, 'searchinpages'));
          $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <div class="item"><?php 
          $o = new wpdreamsCustomPostTypes("customtypes", "Search in custom post types", setval_or_getoption($sd, 'customtypes'));
          $params[$o->getName()] = $o->getData();
          $params['selected-'.$o->getName()] = $o->getSelected();
          ?></div>
          <div class="item">
          <?php
          $o = new wpdreamsYesNo("searchintitle", "Search in title?", setval_or_getoption($sd, 'searchintitle'));
          $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <div class="item">    
          <?php
          $o = new wpdreamsYesNo("searchincontent", "Search in content?", setval_or_getoption($sd, 'searchincontent'));
          $params[$o->getName()] = $o->getData();
          ?>
          </div>
         <div class="item">
          <?php
          $o = new wpdreamsYesNo("searchincomments", "Search in comments?", setval_or_getoption($sd, 'searchincomments'));
          $params[$o->getName()] = $o->getData();
          ?>
         </div>
         <div class="item">
          <?php
          $o = new wpdreamsYesNo("searchinexcerpt", "Search in post excerpts?", setval_or_getoption($sd, 'searchinexcerpt'));
          $params[$o->getName()] = $o->getData();
          ?>
         </div>
         <div class="item"><?php 
          $o = new wpdreamsCustomFields("customfields", "Search in custom fields", setval_or_getoption($sd, 'customfields'));
          $params[$o->getName()] = $o->getData();
          $params['selected-'.$o->getName()] = $o->getSelected();
         ?></div>      
          <div class="item">
          <fieldset>
             <legend>BuddyPress Options</legend>
              <p class='infoMsg'>You must have BuddyPress installed to use these functions!</p>
              <div class="item">
                <?php
                $o = new wpdreamsYesNo("searchinbpusers", "Search in BuddyPress users?", setval_or_getoption($sd, 'searchinbpusers'));
                $params[$o->getName()] = $o->getData();
                ?>
              </div>
              <div class="item">
                <?php
                $o = new wpdreamsYesNo("searchinbpgroups", "Search in BuddyPress groups?", setval_or_getoption($sd, 'searchinbpgroups'));
                $params[$o->getName()] = $o->getData();
                ?>
              </div>
              <div class="item">
                <?php
                $o = new wpdreamsYesNo("searchinbpprivategroups", "Search in BuddyPress private groups?", setval_or_getoption($sd, 'searchinbpprivategroups'));
                $params[$o->getName()] = $o->getData();
                ?>
              </div>
              <div class="item">
                <?php
                $o = new wpdreamsCustomSelect("bpgroupstitle", "Result title", array('selects'=>setval_or_getoption($sd, 'bpgroupstitle_def'), 'value'=>setval_or_getoption($sd, 'bpgroupstitle')));
                $params[$o->getName()] = $o->getData();
                ?>
              </div>
          </fieldset>  
          </div>
          <div class="item">
          <?php
          $o = new wpdreamsYesNo("searchindrafts", "Search in draft posts?", setval_or_getoption($sd, 'searchindrafts'));
          $params[$o->getName()] = $o->getData();
          ?>
          </div>             
          <div class="item">
          <?php
          $o = new wpdreamsYesNo("searchinpending", "Search in pending posts?", setval_or_getoption($sd, 'searchinpending'));
          $params[$o->getName()] = $o->getData();          
          ?>
          </div>            
          <div class="item">
          <?php
          $o = new wpdreamsYesNo("exactonly", "Show exact matches only?", setval_or_getoption($sd, 'exactonly'));
          $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <div class="item">
          <?php
          $o = new wpdreamsYesNo("searchinterms", "Search in terms? (categories, tags)", setval_or_getoption($sd, 'searchinterms'));
          $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <div class="item">
          <?php
          $o = new wpdreamsYesNo("keywordsuggestions", "Keyword suggestions on no results?", setval_or_getoption($sd, 'keywordsuggestions'));
          $params[$o->getName()] = $o->getData();
          ?>
          </div>                                       
          <div class="item"><?php
          $o = new wpdreamsLanguageSelect("keywordsuggestionslang", "Keyword suggestions language", setval_or_getoption($sd, 'keywordsuggestionslang'));
          $params[$o->getName()] = $o->getData();
          ?></div> 
          <div class="item"><?php
          //$o = new wpdreamsCustomSelect("titlefield", "Title Field", array('selects'=>setval_or_getoption($sd, 'titlefield_def'), 'value'=>setval_or_getoption($sd, 'titlefield')) );

          $o = new wpdreamsCustomSelect("orderby", "Result ordering",  array('selects'=>setval_or_getoption($sd, 'orderby_def'), 'value'=>setval_or_getoption($sd, 'orderby')) );
          $params[$o->getName()] = $o->getData();
          ?></div> 
          <div class="item">
          <?php
          $o = new wpdreamsYesNo("triggeronclick", "Trigger search when clicking on search icon?", setval_or_getoption($sd, 'triggeronclick'));
          $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <div class="item">
          <?php
          $o = new wpdreamsYesNo("redirectonclick", "<b>Redirect</b> to search results page when clicking on search icon?", setval_or_getoption($sd, 'redirectonclick'));
          $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <div class="item">
          <?php
          $o = new wpdreamsYesNo("triggerontype", "Trigger search when typing?", setval_or_getoption($sd, 'triggerontype'));
          $params[$o->getName()] = $o->getData();
          ?>
          </div>                   
          <div class="item"><?php
          $o = new wpdreamsTextSmall("charcount", "Minimal character count to trigger search", setval_or_getoption($sd, 'charcount'), array( array("func"=>"ctype_digit", "op"=>"eq", "val"=>true) ));
          $params[$o->getName()] = $o->getData();
          ?></div>
          <div class="item"><?php
          $o = new wpdreamsTextSmall("maxresults", "Max. results", setval_or_getoption($sd, 'maxresults'), array( array("func"=>"ctype_digit", "op"=>"eq", "val"=>true) ));
          $params[$o->getName()] = $o->getData();
          ?></div>  
          <div class="item"><?php
          $o = new wpdreamsTextSmall("itemscount", "Results box viewport (in item numbers)", setval_or_getoption($sd, 'itemscount'), array( array("func"=>"ctype_digit", "op"=>"eq", "val"=>true) ));
          $params[$o->getName()] = $o->getData();
          ?></div>  
                
          <div class="item">
          <?php      
          $o = new wpdreamsImageSettings("imagesettings", "Image Settings", setval_or_getoption($sd, 'imagesettings'));
          $params[$o->getName()] = $o->getData();
          $params["settings-".$o->getName()] = $o->getSettings();
          ?>
          </div>
          <div class="item"><?php 
          $o = new wpdreamsColorPicker("imagebg","Transparent images background", setval_or_getoption($sd, 'imagebg'));
          $params[$o->getName()] = $o->getData();
          ?></div>
          <div class="item">      
            <input name="submit_<?php echo $search['id']; ?>" type="submit" value="Save all tabs!" />
          </div> 
      </fieldset>
      </div>
      <div tabid="2">
      <fieldset>
        <legend>Multisite Options</legend>
        <div class='item'>
          <p class='infoMsg'>
             If you not choose any site, then the <strong>currently active</strong> blog will be used!<br />
             Also, you can use the same search on multiple blogs!
          </p>
        </div>
        <div class="item">
        <?php
        $o = new wpdreamsYesNo("searchinblogtitles", "Search in blog titles?", setval_or_getoption($sd, 'searchinblogtitles'));
        $params[$o->getName()] = $o->getData();
        ?>
        </div>
        <div class="item"><?php
        $o = new wpdreamsCustomSelect("blogtitleorderby", "Result ordering", array('selects'=>setval_or_getoption($sd, 'blogtitleorderby_def'), 'value'=>setval_or_getoption($sd, 'blogtitleorderby')) );
        $params[$o->getName()] = $o->getData();
        ?></div> 
        <div class="item">
        <?php
          $o = new wpdreamsText("blogresultstext", "Blog results group default text", setval_or_getoption($sd, 'blogresultstext'));
          $params[$o->getName()] = $o->getData();
        ?>
        </div>
        <div class="item"><?php
        $o = new wpdreamsBlogselect("blogs", "Blogs", setval_or_getoption($sd, 'blogs'));
        $params[$o->getName()] = $o->getData();
        $params['selected-'.$o->getName()] = $o->getSelected();  
        ?></div>
        <div class="item">
          <input name="submit_<?php echo $search['id']; ?>" type="submit" value="Save all tabs!" />
        </div>
      </fieldset>
      </div>
      <div tabid="3">
      <fieldset>
      <legend>Frontend Search Settings options</legend>
          <div class="item" style="text-align:center;"> 
            The default values of the checkboxes on the frontend are the values set above.
          </div>
          <div class="item">
          <?php
          $o = new wpdreamsYesNo("showexactmatches", "Show exact matches selector?", setval_or_getoption($sd, 'showexactmatches'));
          $params[$o->getName()] = $o->getData();
          $o = new wpdreamsText("exactmatchestext", "Text", setval_or_getoption($sd, 'exactmatchestext'));
          $params[$o->getName()] = $o->getData();
          ?></div>
          <div class="item">
          <?php 
          $o = new wpdreamsYesNo("showsearchinposts", "Show search in posts selector?", setval_or_getoption($sd, 'showsearchinposts'));
          $params[$o->getName()] = $o->getData();
          $o = new wpdreamsText("searchinpoststext", "Text", setval_or_getoption($sd, 'searchinpoststext'));
          $params[$o->getName()] = $o->getData();
          ?></div>
          <div class="item">
          <?php
          $o = new wpdreamsYesNo("showsearchinpages", "Show search in pages selector?", setval_or_getoption($sd, 'showsearchinpages'));
          $params[$o->getName()] = $o->getData();
          $o = new wpdreamsText("searchinpagestext", "Text", setval_or_getoption($sd, 'searchinpagestext'));
          $params[$o->getName()] = $o->getData();
          ?></div>
          <div class="item">
          <?php 
          $o = new wpdreamsYesNo("showsearchintitle", "Show search in title selector?", setval_or_getoption($sd, 'showsearchintitle'));
          $params[$o->getName()] = $o->getData();
          $o = new wpdreamsText("searchintitletext", "Text", setval_or_getoption($sd, 'searchintitletext'));
          $params[$o->getName()] = $o->getData();
          ?></div>
          <div class="item">
          <?php
          $o = new wpdreamsYesNo("showsearchincontent", "Show search in content selector?", setval_or_getoption($sd, 'showsearchincontent'));
          $params[$o->getName()] = $o->getData();
          $o = new wpdreamsText("searchincontenttext", "Text", setval_or_getoption($sd, 'searchincontenttext'));
          $params[$o->getName()] = $o->getData();
          ?></div>
          <div class="item">
          <?php
          $o = new wpdreamsYesNo("showsearchincomments", "Show search in comments selector?", setval_or_getoption($sd, 'showsearchincomments'));
          $params[$o->getName()] = $o->getData();
          $o = new wpdreamsText("searchincommentstext", "Text", setval_or_getoption($sd, 'searchincommentstext'));
          $params[$o->getName()] = $o->getData();
          ?></div>
          <div class="item">
          <?php
          $o = new wpdreamsYesNo("showsearchinexcerpt", "Show search in excerpt selector?", setval_or_getoption($sd, 'showsearchinexcerpt'));
          $params[$o->getName()] = $o->getData();
          $o = new wpdreamsText("searchinexcerpttext", "Text", setval_or_getoption($sd, 'searchinexcerpttext'));
          $params[$o->getName()] = $o->getData();
          ?></div>
          <div class="item">
          <?php
          $o = new wpdreamsYesNo("showsearchinbpusers", "Show search in BuddyPress users selector?", setval_or_getoption($sd, 'showsearchinbpusers'));
          $params[$o->getName()] = $o->getData();
          $o = new wpdreamsText("searchinbpuserstext", "Text", setval_or_getoption($sd, 'searchinbpuserstext'));
          $params[$o->getName()] = $o->getData();
          ?></div>
          <div class="item">
          <?php
          $o = new wpdreamsYesNo("showsearchinbpgroups", "Show search in BuddyPress groups selector?", setval_or_getoption($sd, 'showsearchinbpgroups'));
          $params[$o->getName()] = $o->getData();
          $o = new wpdreamsText("searchinbpgroupstext", "Text", setval_or_getoption($sd, 'searchinbpgroupstext'));
          $params[$o->getName()] = $o->getData();
          ?></div>
          <div class="item">
          <?php
          $o = new wpdreamsYesNo("showsearchinbpforums", "Show search in BuddyPress forums selector?", setval_or_getoption($sd, 'showsearchinbpforums'));
          $params[$o->getName()] = $o->getData();
          $o = new wpdreamsText("searchinbpforumstext", "Text", setval_or_getoption($sd, 'searchinbpforumstext'));
          $params[$o->getName()] = $o->getData();
          ?></div>
          <div class="item"><?php
          $o = new wpdreamsCustomPostTypesEditable("showcustomtypes", "Show search in custom post types selectors", setval_or_getoption($sd, 'showcustomtypes'));
          $params[$o->getName()] = $o->getData();
          $params['selected-'.$o->getName()] = $o->getSelected();
          ?></div>
          <div class="item">
          <p class='infoMsg'>Nor recommended if you have more than 500 categories! (the HTML output will get too big)</p>                                            
          <?php
          $o = new wpdreamsYesNo("showsearchincategories", "Show the categories selectors?", setval_or_getoption($sd, 'showsearchincategories'));
          $params[$o->getName()] = $o->getData();
          ?></div>
          <div class="item">
          <?php
          $o = new wpdreamsYesNo("showuncategorised", "Show the uncategorised category?", setval_or_getoption($sd, 'showuncategorised'));
          $params[$o->getName()] = $o->getData();
          ?></div>
          <div class="item"><?php 
          $o = new wpdreamsCategories("exsearchincategories", "Select which categories exclude", setval_or_getoption($sd, 'exsearchincategories'));
          $params[$o->getName()] = $o->getData();
          $params['selected-'.$o->getName()] = $o->getSelected();
          ?></div>
          <div class="item">
          <?php
          $o = new wpdreamsYesNo("showsearchintaxonomies", "Show the taxonomy selectors?", setval_or_getoption($sd, 'showsearchintaxonomies'));
          $params[$o->getName()] = $o->getData();
          ?></div>
          <div class="item">
          <?php
            $o = new wpdreamsCustomTaxonomyTerm("showterms", "Show the following taxonomy term selectors on frontend", array("value"=>setval_or_getoption($sd, 'showterms'), "type"=>"include"));
            $params[$o->getName()] = $o->getData();
            $params['selected-'.$o->getName()] = $o->getSelected();
          ?> 
          </div>
          <div class="item">
          <?php
          $o = new wpdreamsText("exsearchincategoriesheight", "Filter boxes max-height (0 for auto, default 200)", setval_or_getoption($sd, 'exsearchincategoriesheight'));
          $params[$o->getName()] = $o->getData();
          ?></div>
          <div class="item">
          <?php
          $o = new wpdreamsYesNo("showseparatefilterboxes", "Show separate filter boxes for each taxonomy?", setval_or_getoption($sd, 'showseparatefilterboxes'));
          $params[$o->getName()] = $o->getData();
          ?></div>
          <div class="item">
          <?php
          $o = new wpdreamsText("exsearchincategoriestext", "Categories filter box header text", setval_or_getoption($sd, 'exsearchincategoriestext'));
          $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <div class="item">
          <?php
          $o = new wpdreamsText("exsearchintaxonomiestext", "Taxonomies filter box header text", setval_or_getoption($sd, 'exsearchintaxonomiestext'));
          ?>{taxonomy name}<?php
          $params[$o->getName()] = $o->getData();
          ?>
          </div>
        <div class="item">
          <input name="submit_<?php echo $search['id']; ?>" type="submit" value="Save all tabs!" />
        </div>       
      </fieldset>
      </div>
      <div tabid="4">
      <fieldset>
      <legend>Layout Options</legend>
          <div class="item">
            <?php
            $o = new wpdreamsCustomSelect("resultstype", "Results layout type", array('selects'=>setval_or_getoption($sd, 'resultstype_def'), 'value'=>setval_or_getoption($sd, 'resultstype')));
            $params[$o->getName()] = $o->getData();
            ?>
          </div>              
          <p class='infoMsg'>If you are using <b>Polaroid</b> layout type, then <b>block</b> position is highly recommended!</p>
          <div class="item">
            <?php
            $o = new wpdreamsCustomSelect("resultsposition", "Results layout position", array('selects'=>setval_or_getoption($sd, 'resultsposition_def'), 'value'=>setval_or_getoption($sd, 'resultsposition')));
            $params[$o->getName()] = $o->getData();
            ?>
          </div>
          <div class="item">
            <?php
            $o = new wpdreamsNumericUnit("resultsmargintop", "Block layout margin top", array('value' => setval_or_getoption($sd, 'resultsmargintop'), 'units'=>array('px'=>'px')));
            $params[$o->getName()] = $o->getData();
            ?>
          </div>
          <div class="item">          
          <?php
          $o = new wpdreamsText("defaultsearchtext", "Default search text", setval_or_getoption($sd, 'defaultsearchtext'));
          $params[$o->getName()] = $o->getData();
          ?>
          </div> 
          <div class="item">          
          <?php
          $o = new wpdreamsYesNo("showmoreresults", "Show 'More results..' text in the bottom of the search box?", setval_or_getoption($sd, 'showmoreresults'));
          $params[$o->getName()] = $o->getData();
          ?>
          </div>  
          <div class="item">          
          <?php
          $o = new wpdreamsText("showmoreresultstext", "' Show more results..' text", setval_or_getoption($sd, 'showmoreresultstext'));
          $params[$o->getName()] = $o->getData();
          ?>
          </div>     
          <div class="item">          
          <?php
          $o = new wpdreamsYesNo("resultareaclickable", "Make the whole result area clickable?", setval_or_getoption($sd, 'resultareaclickable'));
          $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <div class="item">          
          <?php
          $o = new wpdreamsYesNo("showauthor", "Show author in results?", setval_or_getoption($sd, 'showauthor'));
          $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <div class="item">               
          <?php
          $o = new wpdreamsYesNo("showdate", "Show date in results?", setval_or_getoption($sd, 'showdate'));
          $params[$o->getName()] = $o->getData();
          ?>
          </div> 
          <div class="item">               
          <?php
          $o = new wpdreamsYesNo("showdescription", "Show description in results?", setval_or_getoption($sd, 'showdescription'));
          $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <div class="item">               
          <?php
          $o = new wpdreamsTextSmall("descriptionlength", "Description length", setval_or_getoption($sd, 'descriptionlength'));
          $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <div class="item"><?php
          $o = new wpdreamsText("noresultstext", "No results text", setval_or_getoption($sd, 'noresultstext'));
          $params[$o->getName()] = $o->getData();
          ?></div>             
          <div class="item"><?php
          $o = new wpdreamsText("didyoumeantext", "Did you mean text", setval_or_getoption($sd, 'didyoumeantext'));
          $params[$o->getName()] = $o->getData();
          ?></div> 
          <div class="item"><?php
          $o = new wpdreamsYesNo("highlight", "Highlight search text in results?", setval_or_getoption($sd, 'highlight'));
          $params[$o->getName()] = $o->getData();
          ?></div>
          <div class="item"><?php 
          $o = new wpdreamsYesNo("highlightwholewords", "Highlight only whole words?", setval_or_getoption($sd, 'highlightwholewords'));
          $params[$o->getName()] = $o->getData();
          ?></div>
          <div class="item"><?php
          $o = new wpdreamsColorPicker("highlightcolor", "Highlight text color", setval_or_getoption($sd, 'highlightcolor'));
          $params[$o->getName()] = $o->getData();
          ?></div> 
          <div class="item"><?php 
          $o = new wpdreamsColorPicker("highlightbgcolor", "Highlight-text background color", setval_or_getoption($sd, 'highlightbgcolor'));
          $params[$o->getName()] = $o->getData();
          ?></div>
        <div class="item">
          <input name="submit_<?php echo $search['id']; ?>" type="submit" value="Save all tabs!" />
        </div> 
      </fieldset>
      </div>
      <div tabid="5">
      <fieldset>
        <legend>Autocomplete Options</legend>
        <div class="item"><?php
        $o = new wpdreamsYesNo("autocomplete", "Turn on search autocomplete?", setval_or_getoption($sd, 'autocomplete'));
        $params[$o->getName()] = $o->getData();
        ?></div>
        <div class="item"><?php
        $o = new wpdreamsCustomSelect("autocompletesource", "Autocomplete source", array('selects'=>setval_or_getoption($sd, 'autocompletesource_def'), 'value'=>setval_or_getoption($sd, 'autocompletesource')));
        $params[$o->getName()] = $o->getData();
        $params["selected-".$o->getName()] = $o->getSelected();
        ?></div> 
        <div class="item"><?php
        $o = new wpdreamsTextarea("autocompleteexceptions", "Keyword exceptions (comma separated)", setval_or_getoption($sd, 'autocompleteexceptions'));
        $params[$o->getName()] = $o->getData();
        ?></div>
        <div class="item">
          <input name="submit_<?php echo $search['id']; ?>" type="submit" value="Save all tabs!" />
        </div>
      </fieldset>
      </div>
      <div tabid="6">
      <fieldset>
      <legend>Theme Options</legend>
      
        <ul id="tabs"  class='tabs'>
            <li><a tabid="8" class='subtheme current'>Overall box layout</a></li>
            <li><a tabid="9" class='subtheme'>Input field layout</a></li>
            <li><a tabid="10" class='subtheme'>Settings icon & dropdown</a></li>
            <li><a tabid="11" class='subtheme'>Magnifier & loading icon</a></li>
            <li><a tabid="12" class='subtheme'>Vertical Results</a></li>
            <li><a tabid="14" class='subtheme'>Horizontal Results</a></li> 
            <li><a tabid="15" class='subtheme'>Polaroid Results</a></li> 
            <li><a tabid="13" class='subtheme'>Typography</a></li>
        </ul>  
        <div class='tabscontent'>
          <div tabid="8">
            <div class="item">
            <?php
            $o = new wpdreamsThemeChooser("themes", "Theme Chooser", $_themes);
            //$params[$o->getName()] = $o->getData();
            ?>
            </div>
            <div class="item"><?php
            $o = new wpdreamsNumericUnit("boxheight", "Search box height", array('value' => setval_or_getoption($sd, 'boxheight'), 'units'=>array('px'=>'px')));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>
            <div class="item"><?php
            $o = new wpdreamsNumericUnit("boxmargin", "Search box margin", array('value' => setval_or_getoption($sd, 'boxmargin'), 'units'=>array('px'=>'px', '%'=>'%')));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>
            <div class="item"><?php
            $o = new wpdreamsGradient("boxbackground", "Search box background gradient", setval_or_getoption($sd, 'boxbackground'));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>           
            <div class="item">
            <?php    
            $o = new wpdreamsBorder("boxborder", "Search box border", setval_or_getoption($sd, 'boxborder'));
            $params[$o->getName()] = $o->getData(); 
            ?>
            </div>
            <div class="item">
            <?php       
            $o = new wpdreamsBoxShadow("boxshadow", "Search box Shadow", setval_or_getoption($sd, 'boxshadow'));
            $params[$o->getName()] = $o->getData();
            ?>
            </div> 
          </div> <!-- tab 8 -->
          <div tabid="9">
            <div class="item"><?php
            $o = new wpdreamsGradient("inputbackground", "Search input field background gradient", setval_or_getoption($sd, 'inputbackground'));
            $params[$o->getName()] = $o->getData();
            ?></div>
            <div class="item">
            <?php       
            $o = new wpdreamsBorder("inputborder", "Search input field border", setval_or_getoption($sd, 'inputborder'));
            $params[$o->getName()] = $o->getData(); 
            ?>
            </div> 
            <div class="item">
            <?php       
            $o = new wpdreamsBoxShadow("inputshadow", "Search input field Shadow", setval_or_getoption($sd, 'inputshadow'));
            $params[$o->getName()] = $o->getData();
            ?>
            </div> 
            <div class="item"><?php       
            $o = new wpdreamsFont("inputfont", "Search input font", setval_or_getoption($sd, 'inputfont'));
            $params[$o->getName()] = $o->getData();
            $params["script-".$o->getName()] = $o->getImport();
            ?>
            </div>
          </div> <!-- tab 9 -->
          <div tabid="10">
            <div class="item">
            <?php
              $o = new wpdreamsCustomSelect("settingsimagepos", "Settings icon position", array('selects'=>setval_or_getoption($sd, 'settingsimagepos_def'), 'value'=>setval_or_getoption($sd, 'settingsimagepos')) );
              $params[$o->getName()] = $o->getData();
            ?>
            </div>
            <div class="item">
            <?php       
            $o = new wpdreamsImageRadio("settingsimage", "Settings icon", array(
                  'images'  => $_def['settingsimage_selects'],
                  'value'=> setval_or_getoption($sd, 'settingsimage') 
              )
            );
            $params[$o->getName()] = $o->getData();
            ?>
            </div>
            <div class="item"><?php
            $o = new wpdreamsGradient("settingsbackground", "Settings-icon background gradient", setval_or_getoption($sd, 'settingsbackground'));
            $params[$o->getName()] = $o->getData();
            ?></div>
            <div class="item">
            <?php    
            $o = new wpdreamsBorder("settingsbackgroundborder", "Settings-icon border", setval_or_getoption($sd, 'settingsbackgroundborder'));
            $params[$o->getName()] = $o->getData(); 
            ?>
            </div>
            <div class="item">
            <?php       
            $o = new wpdreamsBoxShadow("settingsboxshadow", "Settings-icon box-shadow", setval_or_getoption($sd, 'settingsboxshadow'));
            $params[$o->getName()] = $o->getData();
            ?>
            </div> 
            <div class="item"><?php
            $o = new wpdreamsGradient("settingsdropbackground", "Settings drop-down background gradient", setval_or_getoption($sd, 'settingsdropbackground'));
            $params[$o->getName()] = $o->getData();
            ?></div>
            <div class="item">
            <?php       
            $o = new wpdreamsBoxShadow("settingsdropboxshadow", "Settings drop-down box-shadow", setval_or_getoption($sd, 'settingsdropboxshadow'));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>
            <div class="item"><?php  
              $o = new wpdreamsFont("settingsdropfont", "Settings drop down font", setval_or_getoption($sd, 'settingsdropfont'));
              $params[$o->getName()] = $o->getData();
              $params["script-".$o->getName()] = $o->getImport();
            ?>
            </div> 
            <div class="item"><?php  
              $o = new wpdreamsFont("exsearchincategoriestextfont", "Settings box header text font", setval_or_getoption($sd, 'exsearchincategoriestextfont'));
              $params[$o->getName()] = $o->getData();
              $params["script-".$o->getName()] = $o->getImport();
            ?>
            </div> 
            <div class="item"><?php
              $o = new wpdreamsColorPicker("settingsdroptickcolor","Settings drop-down tick color", setval_or_getoption($sd, 'settingsdroptickcolor'));
              $params[$o->getName()] = $o->getData();
            ?></div>             
            <div class="item"><?php
              $o = new wpdreamsGradient("settingsdroptickbggradient", "Settings drop-down tick background", setval_or_getoption($sd, 'settingsdroptickbggradient'));
              $params[$o->getName()] = $o->getData();
            ?></div>
                         
          </div> <!-- tab 10 -->
          <div tabid="11">          
            <div class="item">
            <?php      
            $o = new wpdreamsImageRadio("magnifierimage", "Magnifier image", array(
                  'images'  => $_def['magnifierimage_selects'],
                  'value'=> setval_or_getoption($sd, 'magnifierimage') 
              )
            );
            $params[$o->getName()] = $o->getData();
            ?>
            </div>            
            <div class="item"><?php
            $o = new wpdreamsGradient("magnifierbackground", "Magnifier background gradient", setval_or_getoption($sd, 'magnifierbackground'));
            $params[$o->getName()] = $o->getData();
            ?>
            </div> 
            <div class="item">
            <?php    
            $o = new wpdreamsBorder("magnifierbackgroundborder", "Magnifier-icon border", setval_or_getoption($sd, 'magnifierbackgroundborder'));
            $params[$o->getName()] = $o->getData(); 
            ?>
            </div>
            <div class="item">
            <?php       
            $o = new wpdreamsBoxShadow("magnifierboxshadow", "Magnifier-icon box-shadow", setval_or_getoption($sd, 'magnifierboxshadow'));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>                         
            <div class="item">
            <?php      
            $o = new wpdreamsImageRadio("loadingimage", "Loading image", array(
                  'images'  => $_def['loadingimage_selects'],
                  'value'=> setval_or_getoption($sd, 'loadingimage') 
              )
            );
            $params[$o->getName()] = $o->getData();
            ?>
            </div>
          </div> <!-- tab 11 -->
          <div tabid="12">
            <div class="item">
            <?php       
            $o = new wpdreamsBorder("resultsborder", "Results box border", setval_or_getoption($sd, 'resultsborder'));
            $params[$o->getName()] = $o->getData(); 
            ?>
            </div>
            <div class="item">
            <?php       
            $o = new wpdreamsBoxShadow("resultshadow", "Results box Shadow", setval_or_getoption($sd, 'resultshadow'));
            $params[$o->getName()] = $o->getData();
            ?>
            </div> 
            <div class="item"><?php
            $o = new wpdreamsAnimations("vresultinanim", "Result item incoming animation", setval_or_getoption($sd, 'vresultinanim'));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>
            <div class="item"><?php
            $o = new wpdreamsNumericUnit("resultitemheight", "One result item height", array('value' => setval_or_getoption($sd, 'resultitemheight'), 'units'=>array('px'=>'px')));
            $params[$o->getName()] = $o->getData();
            ?></div>
            <div class="item"><?php
            $o = new wpdreamsColorPicker("resultsbackground","Results box background color", setval_or_getoption($sd, 'resultsbackground'));
            $params[$o->getName()] = $o->getData();
            ?></div> 
            <div class="item"><?php
            $o = new wpdreamsColorPicker("resultscontainerbackground","Result items container box background color", setval_or_getoption($sd, 'resultscontainerbackground'));
            $params[$o->getName()] = $o->getData();
            ?></div>
            <div class="item"><?php
            $o = new wpdreamsGradient("vresulthbg", "Result item mouse hover box background gradient", setval_or_getoption($sd, 'vresulthbg'));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>
            <div class="item"><?php
            $o = new wpdreamsColorPicker("spacercolor","Spacer color between results", setval_or_getoption($sd, 'spacercolor'));
            $params[$o->getName()] = $o->getData();
            ?></div>  
            <div class="item"><?php
            $o = new wpdreamsColorPicker("arrowcolor","Resultbar arrow color", setval_or_getoption($sd, 'arrowcolor'));
            $params[$o->getName()] = $o->getData();
            ?></div>   
            <div class="item"><?php
            $o = new wpdreamsColorPicker("overflowcolor","Resultbar overflow color", setval_or_getoption($sd, 'overflowcolor'));
            $params[$o->getName()] = $o->getData();
            ?></div>   
          </div> <!-- tab 12 -->
          <div tabid="13">
            <div class="item"><?php       
            $o = new wpdreamsFont("titlefont", "Results title link font", setval_or_getoption($sd, 'titlefont'));
            $params[$o->getName()] = $o->getData();
            $params["script-".$o->getName()] = $o->getImport();
            ?>
            </div>        
            <div class="item"><?php       
            $o = new wpdreamsFont("titlehoverfont", "Results title hover link font", setval_or_getoption($sd, 'titlehoverfont'));
            $params[$o->getName()] = $o->getData();
            $params["script-".$o->getName()] = $o->getImport();
            ?>
            </div>
            <div class="item"><?php       
            $o = new wpdreamsFont("authorfont", "Author text font", setval_or_getoption($sd, 'authorfont'));
            $params[$o->getName()] = $o->getData();
            $params["script-".$o->getName()] = $o->getImport();
            ?>
            </div>
            <div class="item"><?php       
            $o = new wpdreamsFont("datefont", "Date text font", setval_or_getoption($sd, 'datefont'));
            $params[$o->getName()] = $o->getData();
            $params["script-".$o->getName()] = $o->getImport();
            ?>
            </div>
            <div class="item"><?php       
            $o = new wpdreamsFont("descfont", "Description text font", setval_or_getoption($sd, 'descfont'));
            $params[$o->getName()] = $o->getData();
            $params["script-".$o->getName()] = $o->getImport();
            ?>
            </div>
            <div class="item"><?php
            $o = new wpdreamsColorPicker("exsearchincategoriesboxcolor","Grouping box header background color", setval_or_getoption($sd, 'exsearchincategoriesboxcolor'));
            $params[$o->getName()] = $o->getData();
            ?></div>
            <div class="item"><?php
            $o = new wpdreamsColorPicker("groupingbordercolor","Grouping box border color", setval_or_getoption($sd, 'groupingbordercolor'));
            $params[$o->getName()] = $o->getData();
            ?></div>
            <div class="item"><?php      
            $o = new wpdreamsFont("groupbytextfont", "Grouping font color", setval_or_getoption($sd, 'groupbytextfont'));
            $params[$o->getName()] = $o->getData();
            $params["script-".$o->getName()] = $o->getImport();
            ?>
            </div>
            <div class="item"><?php      
            $o = new wpdreamsFont("showmorefont", "Show more font", setval_or_getoption($sd, 'showmorefont'));
            $params[$o->getName()] = $o->getData();
            $params["script-".$o->getName()] = $o->getImport();
            ?>
            </div>
          </div> <!-- tab 13 -->
          
          <div tabid="14">
            <div class="item">
            <?php
              $o = new wpdreamsYesNo("hhidedesc", "Hide description if images are available", setval_or_getoption($sd, 'hhidedesc'));
              $params[$o->getName()] = $o->getData();
            ?>
            </div> 
            <div class="item"><?php
            $o = new wpdreamsNumericUnit("hreswidth", "Result width", array('value' => setval_or_getoption($sd, 'hreswidth'), 'units'=>array('px'=>'px')));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>
            <div class="item"><?php
            $o = new wpdreamsNumericUnit("hresheight", "Result height", array('value' => setval_or_getoption($sd, 'hresheight'), 'units'=>array('px'=>'px')));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>
            <div class="item"><?php
            $o = new wpdreamsNumericUnit("hressidemargin", "Result side margin", array('value' => setval_or_getoption($sd, 'hressidemargin'), 'units'=>array('px'=>'px')));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>            
            <div class="item"><?php
            $o = new wpdreamsNumericUnit("hrespadding", "Result padding", array('value' => setval_or_getoption($sd, 'hrespadding'), 'units'=>array('px'=>'px')));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>
            <div class="item"><?php
            $o = new wpdreamsGradient("hboxbg", "Result container background gradient", setval_or_getoption($sd, 'hboxbg'));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>
            <div class="item">
            <?php       
            $o = new wpdreamsBorder("hboxborder", "Results container border", setval_or_getoption($sd, 'hboxborder'));
            $params[$o->getName()] = $o->getData(); 
            ?>
            </div>
            <div class="item">
            <?php       
            $o = new wpdreamsBoxShadow("hboxshadow", "Results container box shadow", setval_or_getoption($sd, 'hboxshadow'));
            $params[$o->getName()] = $o->getData();
            ?>
            </div> 
            <div class="item"><?php
            $o = new wpdreamsAnimations("hresultinanim", "Result item incoming animation", setval_or_getoption($sd, 'hresultinanim'));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>
            <div class="item"><?php
            $o = new wpdreamsGradient("hresultbg", "Result item background gradient", setval_or_getoption($sd, 'hresultbg'));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>
            <div class="item"><?php
            $o = new wpdreamsGradient("hresulthbg", "Result item mouse hover background gradient", setval_or_getoption($sd, 'hresulthbg'));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>
            <div class="item">
            <?php       
            $o = new wpdreamsBorder("hresultborder", "Results item border", setval_or_getoption($sd, 'hresultborder'));
            $params[$o->getName()] = $o->getData(); 
            ?>
            </div>
            <div class="item">
            <?php       
            $o = new wpdreamsBoxShadow("hresultshadow", "Results item box shadow", setval_or_getoption($sd, 'hresultshadow'));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>
            <div class="item">
            <?php       
            $o = new wpdreamsBorder("hresultimageborder", "Results image border", setval_or_getoption($sd, 'hresultimageborder'));
            $params[$o->getName()] = $o->getData(); 
            ?>
            </div>
            <div class="item">
            <?php       
            $o = new wpdreamsBoxShadow("hresultimageshadow", "Results image box shadow", setval_or_getoption($sd, 'hresultimageshadow'));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>
            <div class="item"><?php
            $o = new wpdreamsColorPicker("harrowcolor","Resultbar arrow color", setval_or_getoption($sd, 'harrowcolor'));
            $params[$o->getName()] = $o->getData();
            ?></div>   
            <div class="item"><?php
            $o = new wpdreamsColorPicker("hoverflowcolor","Resultbar overflow color", setval_or_getoption($sd, 'hoverflowcolor'));
            $params[$o->getName()] = $o->getData();
            ?></div>               
          </div> <!-- tab 14 -->
          
          <div tabid="15">
            <div class="item"><?php
              $o = new wpdreamsCustomSelect("pifnoimage", "If no image found",  array('selects'=>setval_or_getoption($sd, 'pifnoimage_def'), 'value'=>setval_or_getoption($sd, 'pifnoimage')) );
              $params[$o->getName()] = $o->getData();
            ?>
            </div> 
            <div class="item">
            <?php
              $o = new wpdreamsYesNo("pshowdesc", "Show descripton on the back of the polaroid", setval_or_getoption($sd, 'pshowdesc'));
              $params[$o->getName()] = $o->getData();
            ?>
            </div> 
            <div class="item"><?php
            $o = new wpdreamsNumericUnit("prescontainerheight", "Container height", array('value' => setval_or_getoption($sd, 'prescontainerheight'), 'units'=>array('px'=>'px')));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>
            <div class="item"><?php
            $o = new wpdreamsNumericUnit("preswidth", "Result width", array('value' => setval_or_getoption($sd, 'preswidth'), 'units'=>array('px'=>'px')));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>
            <div class="item"><?php
            $o = new wpdreamsNumericUnit("presheight", "Result height", array('value' => setval_or_getoption($sd, 'presheight'), 'units'=>array('px'=>'px')));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>         
            <div class="item"><?php
            $o = new wpdreamsNumericUnit("prespadding", "Result padding", array('value' => setval_or_getoption($sd, 'prespadding'), 'units'=>array('px'=>'px')));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>
            <div class="item">
            <?php
              $o = new wpdreamsYesNo("pshowsubtitle", "Show date/author", setval_or_getoption($sd, 'pshowsubtitle'));
              $params[$o->getName()] = $o->getData();
            ?>
            </div> 
            <div class="item"><?php      
            $o = new wpdreamsFont("prestitlefont", "Result title font", setval_or_getoption($sd, 'prestitlefont'));
            $params[$o->getName()] = $o->getData();
            $params["script-".$o->getName()] = $o->getImport();
            ?>
            </div> 
            <div class="item"><?php      
            $o = new wpdreamsFont("pressubtitlefont", "Result sub-title font", setval_or_getoption($sd, 'pressubtitlefont'));
            $params[$o->getName()] = $o->getData();
            $params["script-".$o->getName()] = $o->getImport();
            ?>
            </div>  
                                                                         
            <div class="item"><?php      
            $o = new wpdreamsFont("presdescfont", "Result description font", setval_or_getoption($sd, 'presdescfont'));
            $params[$o->getName()] = $o->getData();
            $params["script-".$o->getName()] = $o->getImport();
            ?>
            </div> 
            <div class="item"><?php
            $o = new wpdreamsGradient("prescontainerbg", "Container background", setval_or_getoption($sd, 'prescontainerbg'));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>  
            <div class="item"><?php
            $o = new wpdreamsGradient("pdotssmallcolor", "Nav dot colors", setval_or_getoption($sd, 'pdotssmallcolor'));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>  
            <div class="item"><?php
            $o = new wpdreamsGradient("pdotscurrentcolor", "Nav active dot color", setval_or_getoption($sd, 'pdotscurrentcolor'));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>  
            <div class="item"><?php
            $o = new wpdreamsGradient("pdotsflippedcolor", "Nav flipped dot color", setval_or_getoption($sd, 'pdotsflippedcolor'));
            $params[$o->getName()] = $o->getData();
            ?>
            </div>  
                        
             
          </div> <!-- tab 15 -->
          
        </div> <!-- .tabscontent --> 
        
        <?php if(AJAXSEARCHPRO_DEBUG==1): ?>    
        <textarea class='previewtextarea' style='display:block;width:600px;'>  
        </textarea>
        <?php endif; ?>
        
        <script>
        jQuery(document).ready(function() {
          (function( $ ){
            $(".previewtextarea").click(function(){
               var parent = $(this).parent().parent();
               var content = "";
                var v = "";
               $("input[isparam=1], select[isparam=1]", parent).each(function(){
                    var name = $(this).attr("name");
                    var val = $(this).val().replace(/(\r\n|\n|\r)/gm,"");
                    content += '"'+name+'":"'+val+'",\n';
               });
               $(this).val(content+v);
            });
          }(jQuery))
        });
        </script>
      <div class="item">
        <input name="submit_<?php echo $search['id']; ?>" type="submit" value="Save this search!" />
      </div>
      </fieldset>
      </div>
      <div tabid="20">
      <div class='item'>
        <p class='infoMsg'>
           Every result gets a relevance value based on the weight numbers set below. The weight is the measure of importance.<br />
           When two results have the same relevance value, then the <strong>default ordering</strong> will be used to determine their position.<br />
           You can change this ordering on the general options tab. (<strong>Result ordering</strong> option)
        </p>
      </div>
      <div class='item'>
        <p class='infoMsg'>
          Also note: If you have <b>fulltext</b> search enabled, then these settings are irrelevant.
        </p>
      </div>       
      <div class="item">        
      <?php
        $o = new wpdreamsYesNo("userelevance", "Sort results by relevance", setval_or_getoption($sd, 'userelevance'));
        $params[$o->getName()] = $o->getData();
      ?>
      </div>      
      <fieldset>
      <legend>Exact matches weight</legend>      
          <div class="item">
          <?php
            $o = new wpdreamsCustomSelect("etitleweight", "Title weight", array('selects'=>setval_or_getoption($sd, 'weight_def'), 'value'=>setval_or_getoption($sd, 'etitleweight')) );
            $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <div class="item">
          <?php
            $o = new wpdreamsCustomSelect("econtentweight", "Content weight", array('selects'=>setval_or_getoption($sd, 'weight_def'), 'value'=>setval_or_getoption($sd, 'econtentweight')) );
            $params[$o->getName()] = $o->getData();
          ?>
          </div>     
          <div class="item">
          <?php
            $o = new wpdreamsCustomFSelect("eexcerptweight", "Excerpt weight", array('selects'=>setval_or_getoption($sd, 'weight_def'), 'value'=>setval_or_getoption($sd, 'eexcerptweight')) );
            $params[$o->getName()] = $o->getData();
          ?>
          </div>     
          <div class="item">
          <?php
            $o = new wpdreamsCustomFSelect("etermsweight", "Terms weight", array('selects'=>setval_or_getoption($sd, 'weight_def'), 'value'=>setval_or_getoption($sd, 'etermsweight')) );
            $params[$o->getName()] = $o->getData();
          ?>
          </div>
      </fieldset>            
      <fieldset>
      <legend>Random matches weight</legend>      
          <div class="item">
          <?php
            $o = new wpdreamsCustomFSelect("titleweight", "Title weight", array('selects'=>setval_or_getoption($sd, 'weight_def'), 'value'=>setval_or_getoption($sd, 'titleweight')) );
            $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <div class="item">
          <?php
            $o = new wpdreamsCustomFSelect("contentweight", "Content weight", array('selects'=>setval_or_getoption($sd, 'weight_def'), 'value'=>setval_or_getoption($sd, 'contentweight')) );
            $params[$o->getName()] = $o->getData();
          ?>
          </div>     
          <div class="item">
          <?php
            $o = new wpdreamsCustomFSelect("excerptweight", "Excerpt weight", array('selects'=>setval_or_getoption($sd, 'weight_def'), 'value'=>setval_or_getoption($sd, 'excerptweight')) );
            $params[$o->getName()] = $o->getData();
          ?>
          </div>     
          <div class="item">
          <?php
            $o = new wpdreamsCustomFSelect("termsweight", "Terms weight", array('selects'=>setval_or_getoption($sd, 'weight_def'), 'value'=>setval_or_getoption($sd, 'termsweight')) );
            $params[$o->getName()] = $o->getData();
          ?>
          </div>
      </fieldset>
      <div class="item">
        <input name="submit_<?php echo $search['id']; ?>" type="submit" value="Save this search!" />
      </div>
      </div>                 
      <div tabid="7">
      <fieldset>
      <legend>Advanced Options</legend>
      
          <div class="item">
          <?php
            $o = new wpdreamsYesNo("runshortcode", "Run shortcodes found in post content", setval_or_getoption($sd, 'runshortcode'));
            $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <div class="item">
          <?php
            $o = new wpdreamsYesNo("stripshortcode", "Strip shortcodes from post content", setval_or_getoption($sd, 'stripshortcode'));
            $params[$o->getName()] = $o->getData();
          ?>
          </div> 
          <div class="item">
          <p class='infoMsg'>If you have a plugin/tweak which enables categories on pages, then you should turn this on.</p>
          <?php
            $o = new wpdreamsYesNo("pageswithcategories", "Enable pages with categories/tags", setval_or_getoption($sd, 'pageswithcategories'));
            $params[$o->getName()] = $o->getData();
          ?>
          </div> 
          <div class="item">
          <?php
            $o = new wpdreamsText("striptagsexclude", "HTML Tags exclude from stripping content", setval_or_getoption($sd, 'striptagsexclude'));
            $params[$o->getName()] = $o->getData();
          ?>
          </div> 
          <div class="item">
          <?php
            $o = new wpdreamsCustomFSelect("titlefield", "Title Field", array('selects'=>setval_or_getoption($sd, 'titlefield_def'), 'value'=>setval_or_getoption($sd, 'titlefield')) );
            $params[$o->getName()] = $o->getData();
          ?>
          </div> 
          <div class="item">
          <?php
            $o = new wpdreamsCustomFSelect("descriptionfield", "Description Field", array('selects'=>setval_or_getoption($sd, 'descriptionfield_def'), 'value'=>setval_or_getoption($sd, 'descriptionfield')) );
            $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <fieldset>
          <legend>Advanced fields</legend>
            <p class='infoMsg'>Example: <b>{titlefield} - {_price}</b> will show the title and price for a woocommerce product. More info in the documentation.</p>
            <div class="item">
            <?php
              $o = new wpdreamsText("advtitlefield", "Advanced Title Field (default: {titlefield})", setval_or_getoption($sd, 'advtitlefield'));
              $params[$o->getName()] = $o->getData();
            ?>
            </div>
            <div class="item">
            <?php
              $o = new wpdreamsText("advdescriptionfield", "Advanced Description Field (default: {descriptionfield})", setval_or_getoption($sd, 'advdescriptionfield'));
              $params[$o->getName()] = $o->getData();
            ?>
            </div>
          </fieldset>    
          <div class="item">
          <?php 
            $o = new wpdreamsCategories("excludecategories", "Exclude categories", setval_or_getoption($sd, 'excludecategories'));
            $params[$o->getName()] = $o->getData();
            $params['selected-'.$o->getName()] = $o->getSelected();
          ?>
          </div>
          <div class="item">
          <?php
            $o = new wpdreamsCustomTaxonomyTerm("excludeterms", "Exclude taxonomy terms", array("value"=>setval_or_getoption($sd, 'excludeterms'), "type"=>"exclude"));
            $params[$o->getName()] = $o->getData();
            $params['selected-'.$o->getName()] = $o->getSelected();
          ?> 
          </div>
          <div class="item">
            <p class='infoMsg'>Only if you have "Search in terms" enabled on general otpions page!</p>
          <?php
            $o = new wpdreamsYesNo("excludewoocommerceskus", "Exclude Woocommerce SKU-s from search?", setval_or_getoption($sd, 'excludewoocommerceskus'));
            $params[$o->getName()] = $o->getData();
          ?> 
          </div>          
          <div class="item">
          <?php
            $o = new wpdreamsTextarea("excludeposts", "Exclude Posts by ID's (comma separated post ID-s)", setval_or_getoption($sd, 'excludeposts'));
            $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <div class="item">
          <?php
            $o = new wpdreamsCustomSelect("groupby", "Group results by", array('selects'=>setval_or_getoption($sd, 'groupby_def'), 'value'=>setval_or_getoption($sd, 'groupby')) );
            $params[$o->getName()] = $o->getData();
          ?>
          </div> 
          <div class="item">
          <?php
            $o = new wpdreamsText("groupbytext", "Group by default text (%GROUP% is changed into the current cateogry/post type name)", setval_or_getoption($sd, 'groupbytext'));
            $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <div class="item">
          <?php
            $o = new wpdreamsText("bbpressreplytext", "BBPress replies results group default text", setval_or_getoption($sd, 'bbpressreplytext'));
            $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <div class="item">
          <?php
            $o = new wpdreamsText("bbpressgroupstext", "BBPress group results group default text", setval_or_getoption($sd, 'bbpressgroupstext'));
            $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <div class="item">
          <?php
            $o = new wpdreamsText("bbpressuserstext", "BBPress user results group default text", setval_or_getoption($sd, 'bbpressuserstext'));
            $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <div class="item">
          <?php
            $o = new wpdreamsText("commentstext", "Comments results group default text", setval_or_getoption($sd, 'commentstext'));
            $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <div class="item">
          <?php                                                 
            $o = new wpdreamsText("uncategorizedtext", "Uncategorized group text", setval_or_getoption($sd, 'uncategorizedtext'));
            $params[$o->getName()] = $o->getData();
          ?>
          </div>
          <div class="item">        
          <?php
            $o = new wpdreamsYesNo("showpostnumber", "Show Post Numbering", setval_or_getoption($sd, 'showpostnumber'));
            $params[$o->getName()] = $o->getData();
          ?>
          </div>              
          <div class="item">
            <input type="hidden" name='asp_submit' value=1 />
            <input name="submit_<?php echo $search['id']; ?>" type="submit" value="Save this search!" />
          </div>
      </fieldset>
      </div>
    </div>
    </form>
  </div>
  <?php $output = ob_get_clean(); ?>
  <?php
    if (isset($_POST['submit_'.$search['id']])) {
      /* update data */
      foreach ($_POST as $k=>$v) {
        $params[$k] = $v;
      }  
      foreach ($params as $k=>$v) {
        $_tmp = explode('classname-', $k);
        if ($_tmp!=null && count($_tmp)>1) {
          ob_start();
          $c = new $v('0', '0', $params[$_tmp[1]]);
          $out = ob_get_clean();
          $params['selected-'.$_tmp[1]] = $c->getSelected();
        }
      }
      //print_r($params);
      $data = mysql_real_escape_string(json_encode($params));
      //print_r($_POST);
      $wpdb->query("
        UPDATE ".$_prefix."ajaxsearchpro 
        SET data = '".$data."'
        WHERE id = ".$search['id']."
      ");
      
     /*if (isset($wpdb->base_prefix)) {
        $_prefix = $wpdb->base_prefix;
      } else {
        $_prefix = $wpdb->prefix;
      } */
      /*$search = $wpdb->get_results("SELECT * FROM ".$_prefix."ajaxsearchpro WHERE id=".$id, ARRAY_A);
      if (!isset($search[0])) {
        echo "This search form does not exist!";
        $return = ob_get_clean();
        return $return;
      } */
      /*if (isset($search[0]['id']) && isset($wpdreams_ajaxsearchpros[$search[0]['id']])) {
        echo "This search form is already on the page! You cannot use the same form twice on one page!";
        $return = ob_get_clean();
        return $return;
      } */
      /*$wpdreams_ajaxsearchpros[$search[0]['id']] = 1;
      $search[0]['data'] = json_decode($search[0]['data'], true);  */

      $style = $params;
      $id = $search['id'];
      //print_r($style); return;
      $file = AJAXSEARCHPRO_PATH."/css/style".$id.".css";      
      ob_start();
      include(AJAXSEARCHPRO_PATH."/css/style.css.php");
    	$out = ob_get_contents();
    	ob_end_clean();
      file_put_contents($file, $out, FILE_TEXT); 
            
      echo "<div class='successMsg'>Search settings saved!</div>";
    }  
    echo $output;
  ?>
</div>      