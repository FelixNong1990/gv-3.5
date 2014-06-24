<?php
if (!class_exists("wpdreamsColorPickerDummy")) {
  class wpdreamsColorPickerDummy extends wpdreamsType {
  	function getType() {
      $this->data = wpdreams_admin_hex2rgb($this->data);
  		$this->name = $this->name . "_colorpicker";
      echo "<span class='wpdreamsColorPicker'>";
  		if ($this->label != "")
  			echo "<label for='wpdreamscolorpicker_" . self::$_instancenumber . "'>" . $this->label . "</label>";
  		echo "<input type='text' class='color' id='" . $this->name . "' id='wpdreamscolorpicker_" . self::$_instancenumber . "'  name='" . $this->name . "' id='wpdreamscolorpicker_" . self::$_instancenumber . "' value='" . $this->data . "' />";
  		//echo "<input type='button' class='wpdreamscolorpicker button-secondary' value='Select Color'>";
  		//echo "<div class='' style='z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;'></div>";
      echo "<div class='triggerer'></div>
      </span>";
  	}
  }
}
?>