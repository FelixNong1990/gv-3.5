<?php
  /* Error Checking*/
  $_comp = wpdreamsCompatibility::Instance();
  $_comp_errors = $_comp->get_errors();
?>
<div id="wpdreams" class='wpdreams wrap'> 
<?php if ($_comp->has_errors()): ?>
  <div class="wpdreams-box errorbox">
       <h3>Possible errors</h3>
       <?php foreach($_comp_errors['errors'] as $k=>$err): ?>
        <div>
          <p class='err'><b>Error: </b><?php echo $err; ?></p> 
          <p class='cons'><b>Possible Consequences:  </b><?php echo $_comp_errors['cons'][$k]; ?></p>
          <p class='sol'><b>Solutions: </b><?php echo $_comp_errors['solutions'][$k]; ?></p>
        </div> 
       <?php endforeach; ?> 
       Please note, that these errors may not be accurate!
  </div>
  <?php else: ?>
  <div class="wpdreams-box errorbox">
       <p class='tick'>No errors found!</p>
  </div>
<?php endif; ?>
</div>