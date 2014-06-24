<?php
if (!class_exists("wpdreamsUpload")) {
  class wpdreamsUpload extends wpdreamsType {
  	function getType() {
  		parent::getType();
  		echo "<div>";
  		if ($this->data != "") {
  			echo "<img class='preview' rel='#overlay_" . self::$_instancenumber . "' src=" . $this->data . " />";
  		} else {
  			echo "<img class='preview' style='display:none;'  rel='#overlay_" . self::$_instancenumber . "' />";
  		}
  		echo "<label for='wpdreamsUpload_" . self::$_instancenumber . "'>" . $this->label . "</label>";
  		echo "<input type='text' class='wpdreamsUpload' id='wpdreamsUpload_" . self::$_instancenumber . "' name='" . $this->name . "' value='" . $this->data . "' />";
  		echo "<input class='wpdreamsUpload_button'type='button' value='Upload Image' />";
  		echo "<br />Enter an URL or upload an image!";
  		echo "<div class='overlay' id='overlay_" . self::$_instancenumber . "'><img src=" . $this->data . " /></div>";
  		echo "</div>";
  	}
  }
}
?>