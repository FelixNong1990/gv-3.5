<?php
  if (isset($_GET) && isset($_GET['asp_sid'])){
    include('search.php');
    return; 
  }

  global $sliders;
  global $wpdb;
  //include "types.class.php";
  $params = array();
  
  if (isset($wpdb->base_prefix)) {
    $_prefix = $wpdb->base_prefix;
  } else {
    $_prefix = $wpdb->prefix;
  }
  
  $_comp = wpdreamsCompatibility::Instance();
  ?>
<div id="wpdreams" class='wpdreams wrap'>   
 
  <?php if ($_comp->has_errors()): ?>
  <div class="wpdreams-box errorbox">
          <p class='errors'>Possible incompatibility! Please go to the <a href="<?php echo get_admin_url()."admin.php?page=ajax-search-pro/backend/comp_check.php"; ?>">error check</a> page to see the details and solutions!</p> 
  </div>
  <?php endif; ?>  
  
  <div class="wpdreams-box">
     <form name="add-slider" action="" method="POST">
       <fieldset>
       <legend>Add a new Search!</legend>
           <?php
            $new_slider = new wpdreamsText("addsearch", "Search form name:", "",array( array("func"=>"isEmpty", "op"=>"eq", "val"=>false) ), "Please enter a valid form name!");
           ?>
       <input name="submit" type="submit" value="Add" /> 
          <?php
            if (isset($_POST['addsearch']) && !$new_slider->getError()) {
              $_search_default = get_option('asp_defaults');
              $wpdb->query("INSERT INTO ".$_prefix."ajaxsearchpro
                (name, data) VALUES
                ('".$_POST['addsearch']."', '".mysql_real_escape_string(json_encode($_search_default))."')
              ");
              echo "<div class='successMsg'>Search Form Successfuly added!</div>";
            }          
          ?> 
       </fieldset>  
     </form>     
  </div>
 
  <?php
  
  if (isset($_POST['delete'])) {
    $wpdb->query("DELETE FROM ".$_prefix."ajaxsearchpro WHERE id=".$_POST['did']);
  }
    
 
  $searchforms = $wpdb->get_results("SELECT * FROM ".$_prefix."ajaxsearchpro", ARRAY_A);
  if (is_array($searchforms))
  foreach ($searchforms as $search) {
      $search['data'] = json_decode($search['data'], true);   
      
    ?>
      <div class="wpdreams-box">
        <div class="slider-info">
          <a href='<?php echo get_admin_url()."admin.php?page=ajax-search-pro/backend/settings.php"; ?>&asp_sid=<?php echo $search['id']; ?>'><img title="Click on this icon for search settings!" src="<?php echo plugins_url('/settings/assets/icons/settings.png', __FILE__) ?>" class="settings" searchid="<?php echo $search['id']; ?>" /></a>
          <img title="Click here if you want to delete this search!" src="<?php echo plugins_url('/settings/assets/icons/delete.png', __FILE__) ?>" class="delete" />
          <form name="polaroid_slider_del_<?php echo $search['id']; ?>" action="" style="display:none;" method="POST">
            <?php 
            new wpdreamsHidden("z", "z", time());
            new wpdreamsHidden("delete", "delete", "delete"); 
            new wpdreamsHidden("did", "did", $search['id']);
            ?>        
          </form>      
          <span><?php
             echo $search['name'];
          ?>
          </span>
          <span style='float:right;'>
             <label class="shortcode">Quick shortcode:</label>
             <input type="text" class="shortcode" value="[wpdreams_ajaxsearchpro id=<?php echo $search['id']; ?>]" readonly="readonly" />
          </span> 
        </div>
        
        <form name="polaroid_slider_<?php echo $search['id']; ?>" action="" method="POST">

        </form>
        
      </div>
    <?php
    

  } 
?>
</div>

