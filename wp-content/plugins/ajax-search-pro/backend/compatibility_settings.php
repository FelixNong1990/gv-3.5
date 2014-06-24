<div id="wpdreams" class='wpdreams wrap'> 
<div class="wpdreams-box">
  <?php ob_start(); ?>
  <div class="item">
  <p class='infoMsg'>If you need debugging with the javascript code, you can turn this off.</p>
  <?php $o = new wpdreamsYesNo("asp_jsminified", "Load minified javascript?", postval_or_getoption('asp_jsminified')); ?>
  </div>
  <div class="item">
  <p class='infoMsg'>Set to yes if you are experiencing issues with the <b>search styling</b>, or if the styles are <b>not saving</b>!</p>
  <?php $o = new wpdreamsYesNo("asp_forceinlinestyles", "Force inline styles?", postval_or_getoption('asp_forceinlinestyles')); ?>
  </div>
  <div class="item">
  <p class='infoMsg'>You can turn this off, if you are not using the polaroid-styled result list.</p>
  <?php $o = new wpdreamsYesNo("asp_loadpolaroidjs", "Load the polaroid gallery js?", postval_or_getoption('asp_loadpolaroidjs')); ?>
  </div>
  <div class="item">
    <input type='submit' class='submit' value='Save options'/>
  </div>
  <?php $_r = ob_get_clean(); ?>
  
  
  <?php
    $updated = false;
    if (isset($_POST) && isset($_POST['compatibility']) && (wpdreamsType::getErrorNum()==0)) {
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
  <form name='caching' method='post'>
    <?php if($updated): ?><div class='successMsg'>Search caching settings successfuly updated!</div><?php endif; ?>
    <fieldset>
      <legend>Compatibility Options</legend>
      <?php print $_r; ?> 
      <input type='hidden' name='compatibility' value='1' />
    </fieldset>
  </form>
  </div>        
</div>
</div>