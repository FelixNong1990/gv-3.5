<?php
  $_comp = wpdreamsCompatibility::Instance();
  $_comp->check_dir_w(
    AJAXSEARCHPRO_PATH.'css'.DIRECTORY_SEPARATOR,
    "You might not be able to change the search style."
  );
  $_comp->check_dir_w(
    AJAXSEARCHPRO_PATH.'cache'.DIRECTORY_SEPARATOR,
    "Images may not show in results."
  );
  $_comp->can_open_url("Images may not show in results.");
?>