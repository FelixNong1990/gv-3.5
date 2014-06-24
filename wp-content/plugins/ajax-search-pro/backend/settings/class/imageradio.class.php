<?php
if (!class_exists("wpdreamsImageRadio")) {
  class wpdreamsImageRadio extends wpdreamsType {
  	function getType() {
  		parent::getType();
      $this->processData();
      echo "<div class='wpdreamsImageRadio'>";
  		echo "<span class='radioimage'>" . $this->label . "</span>";
      
      
      foreach ($this->selects as $radio) {
        $radio = trim($radio);
        echo "
          <img num='".$i."' src='".plugins_url().$radio."' class='radioimage".(($this->selected==$radio)?' selected':'')."'/>
        ";
      }
  		echo "<input isparam=1 type='hidden' class='realvalue' value='" . $this->selected . "' name='" . $this->name . "'>";
  		echo "<input type='hidden' value='wpdreamsImageRadio' name='classname-" . $this->name . "'>";
      echo "<div class='triggerer'></div>
      </div>";
  	}
  	function processData() {
  		//$this->data = str_replace("\n","",$this->data); 
  		
  		$this->selects  = $this->defaultData['images'];
  		$this->selected = $this->data['value'];
  	}
  	final function getData() {
  		return $this->data;
  	}
  	final function getSelected() {
  		return $this->selected;
  	}
  }
}
?>