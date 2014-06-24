<div id="wpdreams" class='wpdreams wrap'> 
<div class="wpdreams-box">
  <?php ob_start(); ?>
  <div class="item">
  <p class='infoMsg'>Highly recommended, mainly for big databases.</p>
  <?php $o = new wpdreamsYesNo("asp_dbusefulltext", "Use fulltext search when possible", postval_or_getoption('asp_dbusefulltext')); ?>
  </div>
  <div class="item">
  <p class='infoMsg'>Only set to YES if you have performance issues!</p>
  <?php $o = new wpdreamsYesNo("asp_dbuseregularwhenshort", "Use regular search if the phrase is lower then the min. char count, instead of Boolean mode ", postval_or_getoption('asp_dbuseregularwhenshort')); ?>
  </div>
  <div class="item">
    <input type='submit' class='submit' value='Save options'/>
  </div>
  <?php $_r = ob_get_clean(); ?>
  
  
  <?php
  
    $fulltext = wpdreams_fulltext::getInstance();
    $fulltext_enabled = $fulltext->check(array('posts'));
    $fulltext_indexed = $fulltext->indexExists('posts', 'asp_title');
  
    $updated = false;
    if (isset($_POST) && isset($_POST['fulltext']) && (wpdreamsType::getErrorNum()==0)) {
      foreach($_POST as $key=>$value) {
        if (is_string($key) && (strpos('asp_', $key)==0)) {
          update_option($key, $value);
          $updated = true;
        }
      }
    }
  ?>
  <?php
  $_comp = wpdreamsCompatibility::Instance();
  if ($_comp->has_errors()): 
  ?>
  <div class="wpdreams-slider errorbox">
          <p class='errors'>Possible incompatibility! Please go to the <a href="<?php echo get_admin_url()."admin.php?page=ajax-search-pro/comp_check.php"; ?>">error check</a> page to see the details and solutions!</p> 
  </div>
  <?php endif; ?>
  <div class='wpdreams-slider'>
  <?php if($fulltext_enabled): ?>
    <?php if(!$fulltext_indexed): ?>
    <fieldset>
      <legend>Create Fulltext Indexes</legend>
      <div class="item">
        <p class='infoMsg'>Will create the fulltext indexes on the <b>posts</b> table. It can take a long time!</p>
        <input type='submit' class="red" name='Create Indexes' id='createindexes' value='Create Indexes'>
      </div>                                                            
    </fieldset>
    <?php else: ?>
    <fieldset>
      <legend>Remove Fulltext Indexes</legend>
      <div class="item">
        <p class='psuccessMsg'>You have fulltext indexes created!</p>
        <p class='infoMsg'>Will remove the fulltext indexes from the <b>posts</b> table.</p>
        <input type='submit' class="red" name='Remove Indexes' id='removeindexes' value='Remove Indexes'>
      </div>                                                            
    </fieldset>
    <?php endif; ?>
  
  <form name='fulltext' method='post'>
    <?php if($updated): ?><div class='successMsg'>Fulltext options successfuly updated!</div><?php endif; ?>
    <fieldset>
      <legend>Fulltext search options</legend>
      <?php print $_r; ?> 
      <input type='hidden' name='fulltext' value='1' />
    </fieldset>
  </form>

  <?php else: ?>
    <div class='perrorMsg'>MyIsam tables are disabled, fulltext search not available!</div>
  <?php endif; ?> 
  </div> 
  <script>
     
     jQuery(document).ready((function($) {

        $('#createindexes').on('click', function(){
          var r = confirm('Do you really want to start creating indexes?');
          if (r!=true) return;
          var button = $(this);
          var data = {
            action: 'ajaxsearchpro_activate_fulltext' 
          };
          button.attr("disabled", true);
          
          button.attr("value", "Loading...");
          button.addClass('blink');
          $.post(ajaxsearchpro.backend_ajaxurl, data, function(response) {
              button.parent().append(response);
              button.attr("value", "Done!");
              button.removeClass('blink');
          }, "text");  
        });
        
        $('#removeindexes').on('click', function(){
          var r = confirm('Do you really want to remove indexes?');
          if (r!=true) return;
          var button = $(this);
          var data = {
            action: 'ajaxsearchpro_deactivate_fulltext' 
          };
          button.attr("disabled", true);
          
          button.attr("value", "Loading...");
          button.addClass('blink');
          $.post(ajaxsearchpro.backend_ajaxurl, data, function(response) {
              button.parent().append(response);
              button.attr("value", "Done!");
              button.removeClass('blink');
          }, "text");  
        });
        
     })(jQuery));     
     
  </script>        
</div>
</div>