<?php
if (!class_exists("wpdreamsColorPicker")) {
  class wpdreamsColorPicker extends wpdreamsType {
  	function getType() {
  		$this->name = $this->name;
  		parent::getType();
      $this->data = wpdreams_admin_hex2rgb($this->data);
      echo "<div class='wpdreamsColorPicker'>";
  		if ($this->label != "")
  			echo "<label for='wpdreamscolorpicker_" . self::$_instancenumber . "'>" . $this->label . "</label>";
  		echo "<input isparam=1 type='text' class='color' id='" . $this->name . "' id='wpdreamscolorpicker_" . self::$_instancenumber . "'  name='" . $this->name . "' id='wpdreamscolorpicker_" . self::$_instancenumber . "' value='" . $this->data . "' />";
  		//echo "<input type='button' class='wpdreamscolorpicker button-secondary' value='Select Color'>";
  		//echo "<div class='' style='z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;'></div>";
      echo "<div class='triggerer'></div>
      </div>";
  	}
  }
}
?>