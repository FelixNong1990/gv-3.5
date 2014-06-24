<?php
if (!class_exists("wpdreamsFontMini")) {
  class wpdreamsFontMini extends wpdreamsType {
  	function getType() {
  		parent::getType();

      $this->data = str_replace('\\', "", stripcslashes($this->data));
  		preg_match("/family:(.*?);/", $this->data, $_fonts);
  		$this->font = $_fonts[1];
  		preg_match("/weight:(.*?);/", $this->data, $_weight);
  		$this->weight = $_weight[1];
  		preg_match("/color:(.*?);/", $this->data, $_color);
  		$this->color = $_color[1];
  		preg_match("/size:(.*?);/", $this->data, $_size);
  		$this->size    = $_size[1];
  		preg_match("/height:(.*?);/", $this->data, $_lineheight);
  		$this->lineheight    = $_lineheight[1];
  		$applied_style = "font-family:" . ($this->font) . ";font-weight:" . $this->weight . ";line-height:".$this->lineheight.";color:" . $this->color;
  		echo $this->getScript();
  		echo "<div class='wpdreamsFont mini'>
        <fieldset>
        <legend>" . $this->label . "</legend>
      ";
      echo "<label for='wpdreamsfont_" . self::$_instancenumber . "' style=\"" . $applied_style . "\">Test Text :)</label>";
      new wpdreamsColorPickerDummy( self::$_instancenumber . "_color", "", (isset($this->color) ? $this->color : "#000000"));
  		echo "<select class='wpdreamsfont' id='wpdreamsfont_" . self::$_instancenumber . "' name='" . self::$_instancenumber . "_select'>";
  		$options = '
        <option disabled>-------Classic Webfonts-------</option>
        <option value="\'Arial\', Helvetica, sans-serif" style="font-family:Arial, Helvetica, sans-serif">Arial, Helvetica, sans-serif</option>
        <option value="\'Arial Black\', Gadget, sans-serif" style="font-family:\'Arial Black\', Gadget, sans-serif">"Arial Black", Gadget, sans-serif</option>
        <option value="\'Comic Sans MS\', cursive" style="font-family:\'Comic Sans MS\', cursive">"Comic Sans MS", cursive</option>
        <option value="\'Courier New\', Courier, monospace" style="font-family:\'Courier New\', Courier, monospace">"Courier New", Courier, monospace</option>
        <option value="\'Georgia\', serif" style="font-family:Georgia, serif">Georgia, serif</option>
        <option value="\'Impact\', Charcoal, sans-serif" style="font-family:Impact, Charcoal, sans-serif">Impact, Charcoal, sans-serif</option>
        <option value="\'Lucida Console\', Monaco, monospace" style="font-family:\'Lucida Console\', Monaco, monospace">"Lucida Console", Monaco, monospace</option>
        <option value="\'Lucida Sans Unicode\', \'Lucida Grande\', sans-serif" style="font-family:\'Lucida Sans Unicode\', \'Lucida Grande\', sans-serif">"Lucida Sans Unicode", "Lucida Grande", sans-serif</option>
        <option value="\'Palatino Linotype\', \'Book Antiqua\', Palatino, serif" style="font-family:\'Palatino Linotype\', \'Book Antiqua\', Palatino, serif">"Palatino Linotype", "Book Antiqua", Palatino, serif</option>
        <option value="\'Tahoma\', Geneva, sans-serif" style="font-family:Tahoma, Geneva, sans-serif">Tahoma, Geneva, sans-serif</option>
        <option value="\'Times New Roman\', Times, serif" style="font-family:\'Times New Roman\', Times, serif">"Times New Roman", Times, serif</option>
        <option value="\'Trebuchet MS\', Helvetica, sans-serif" style="font-family:\'Trebuchet MS\', Helvetica, sans-serif">"Trebuchet MS", Helvetica, sans-serif</option>
        <option value="\'Verdana\', Geneva, sans-serif" style="font-family:Verdana, Geneva, sans-serif">Verdana, Geneva, sans-serif</option>
        <option value="\'Symbol\'" style="font-family:Symbol">Symbol</option>
        <option value="\'Webdings\'" style="font-family:Webdings">Webdings</option>
        <option value="\'Wingdings\', \'Zapf Dingbats\'" style="font-family:Wingdings, \'Zapf Dingbats\'">Wingdings, "Zapf Dingbats"</option>
        <option value="\'MS Sans Serif\', Geneva, sans-serif" style="font-family:\'MS Sans Serif\', Geneva, sans-serif">"MS Sans Serif", Geneva, sans-serif</option>
        <option value="\'MS Serif\', \'New York\', serif" style="font-family:\'MS Serif\', \'New York\', serif">"MS Serif", "New York", serif</option>
        <option disabled>-------Google Webfonts-------</option>
        <option  value="Allan" style="font-family: Allan,Allan;"> Allan</option>
        <option  value="Allerta" style="font-family: Allerta,Allerta;"> Allerta</option>
        <option  value="Allerta Stencil" style="font-family: Allerta Stencil,Allerta Stencil;"> Allerta Stencil</option>
        <option  value="Anonymous Pro" style="font-family: Anonymous Pro,Anonymous Pro;"> Anonymous Pro</option>
        <option  value="Arimo" style="font-family: Arimo,Arimo;"> Arimo</option>
        <option  value="Arvo" style="font-family: Arvo,Arvo;"> Arvo</option>
        <option  value="Bentham" style="font-family: Bentham,Bentham;"> Bentham</option>
        <option  value="Buda" style="font-family: Buda,Buda;"> Buda</option>
        <option  value="Cabin" style="font-family: Cabin,Cabin;"> Cabin</option>
        <option  value="Calligraffitti" style="font-family: Calligraffitti,Calligraffitti;"> Calligraffitti</option>
        <option  value="Cantarell" style="font-family: Cantarell,Cantarell;"> Cantarell</option>
        <option  value="Cardo" style="font-family: Cardo,Cardo;"> Cardo</option>
        <option  value="Cherry Cream Soda" style="font-family: Cherry Cream Soda,Cherry Cream Soda;"> Cherry Cream Soda</option>
        <option  value="Chewy" style="font-family: Chewy,Chewy;"> Chewy</option>
        <option  value="Coda" style="font-family: Coda,Coda;"> Coda</option>
        <option  value="Coming Soon" style="font-family: Coming Soon,Coming Soon;"> Coming Soon</option>
        <option  value="Copse" style="font-family: Copse,Copse;"> Copse</option>
        <option  value="Corben" style="font-family: Corben,Corben;"> Corben</option>
        <option  value="Cousine" style="font-family: Cousine,Cousine;"> Cousine</option>
        <option  value="Covered By Your Grace" style="font-family: Covered By Your Grace,Covered By Your Grace;"> Covered By Your Grace</option>
        <option  value="Crafty Girls" style="font-family: Crafty Girls,Crafty Girls;"> Crafty Girls</option>
        <option  value="Crimson Text" style="font-family: Crimson Text,Crimson Text;"> Crimson Text</option>
        <option  value="Crushed" style="font-family: Crushed,Crushed;"> Crushed</option>
        <option  value="Cuprum" style="font-family: Cuprum,Cuprum;"> Cuprum</option>
        <option  value="Droid Sans" style="font-family: Droid Sans,Droid Sans;"> Droid Sans</option>
        <option  value="Droid Sans Mono" style="font-family: Droid Sans Mono,Droid Sans Mono;"> Droid Sans Mono</option>
        <option  value="Droid Serif" style="font-family: Droid Serif,Droid Serif;"> Droid Serif</option>
        <option  value="Fontdiner Swanky" style="font-family: Fontdiner Swanky,Fontdiner Swanky;"> Fontdiner Swanky</option>
        <option  value="GFS Didot" style="font-family: GFS Didot,GFS Didot;"> GFS Didot</option>
        <option  value="GFS Neohellenic" style="font-family: GFS Neohellenic,GFS Neohellenic;"> GFS Neohellenic</option>
        <option  value="Geo" style="font-family: Geo,Geo;"> Geo</option>
        <option  value="Gruppo" style="font-family: Gruppo,Gruppo;"> Gruppo</option>
        <option  value="Hanuman" style="font-family: Hanuman,Hanuman;"> Hanuman</option>
        <option  value="Homemade Apple" style="font-family: Homemade Apple,Homemade Apple;"> Homemade Apple</option>
        <option  value="IM Fell DW Pica" style="font-family: IM Fell DW Pica,IM Fell DW Pica;"> IM Fell DW Pica</option>
        <option  value="IM Fell DW Pica SC" style="font-family: IM Fell DW Pica SC,IM Fell DW Pica SC;"> IM Fell DW Pica SC</option>
        <option  value="IM Fell Double Pica" style="font-family: IM Fell Double Pica,IM Fell Double Pica;"> IM Fell Double Pica</option>
        <option  value="IM Fell Double Pica SC" style="font-family: IM Fell Double Pica SC,IM Fell Double Pica SC;"> IM Fell Double Pica SC</option>
        <option  value="IM Fell English" style="font-family: IM Fell English,IM Fell English;"> IM Fell English</option>
        <option  value="IM Fell English SC" style="font-family: IM Fell English SC,IM Fell English SC;"> IM Fell English SC</option>
        <option  value="IM Fell French Canon" style="font-family: IM Fell French Canon,IM Fell French Canon;"> IM Fell French Canon</option>
        <option  value="IM Fell French Canon SC" style="font-family: IM Fell French Canon SC,IM Fell French Canon SC;"> IM Fell French Canon SC</option>
        <option  value="IM Fell Great Primer" style="font-family: IM Fell Great Primer,IM Fell Great Primer;"> IM Fell Great Primer</option>
        <option  value="IM Fell Great Primer SC" style="font-family: IM Fell Great Primer SC,IM Fell Great Primer SC;"> IM Fell Great Primer SC</option>
        <option  value="Inconsolata" style="font-family: Inconsolata,Inconsolata;"> Inconsolata</option>
        <option  value="Irish Growler" style="font-family: Irish Growler,Irish Growler;"> Irish Growler</option>
        <option  value="Josefin Sans" style="font-family: Josefin Sans,Josefin Sans;"> Josefin Sans</option>
        <option  value="Josefin Slab" style="font-family: Josefin Slab,Josefin Slab;"> Josefin Slab</option>
        <option  value="Just Another Hand" style="font-family: Just Another Hand,Just Another Hand;"> Just Another Hand</option>
        <option  value="Just Me Again Down Here" style="font-family: Just Me Again Down Here,Just Me Again Down Here;"> Just Me Again Down Here</option>
        <option  value="Kenia" style="font-family: Kenia,Kenia;"> Kenia</option>
        <option  value="Kranky" style="font-family: Kranky,Kranky;"> Kranky</option>
        <option  value="Kristi" style="font-family: Kristi,Kristi;"> Kristi</option>
        <option  value="Lato" style="font-family: Lato,Lato;"> Lato</option>
        <option  value="Lekton" style="font-family: Lekton,Lekton;"> Lekton</option>
        <option  value="Lobster" style="font-family: Lobster,Lobster;"> Lobster</option>
        <option  value="Luckiest Guy" style="font-family: Luckiest Guy,Luckiest Guy;"> Luckiest Guy</option>
        <option  value="Merriweather" style="font-family: Merriweather,Merriweather;"> Merriweather</option>
        <option  value="Molengo" style="font-family: Molengo,Molengo;"> Molengo</option>
        <option  value="Mountains of Christmas" style="font-family: Mountains of Christmas,Mountains of Christmas;"> Mountains of Christmas</option>
        <option  value="Neucha" style="font-family: Neucha,Neucha;"> Neucha</option>
        <option  value="Neuton" style="font-family: Neuton,Neuton;"> Neuton</option>
        <option  value="Nobile" style="font-family: Nobile,Nobile;"> Nobile</option>
        <option  value="OFL Sorts Mill Goudy TT" style="font-family: OFL Sorts Mill Goudy TT,OFL Sorts Mill Goudy TT;"> OFL Sorts Mill Goudy TT</option>
        <option  value="Old Standard TT" style="font-family: Old Standard TT,Old Standard TT;"> Old Standard TT</option>
        <option  value="Orbitron" style="font-family: Orbitron,Orbitron;"> Orbitron</option>
        <option  value="PT Sans" style="font-family: PT Sans,PT Sans;"> PT Sans</option>
        <option  value="PT Sans Caption" style="font-family: PT Sans Caption,PT Sans Caption;"> PT Sans Caption</option>
        <option  value="PT Sans Narrow" style="font-family: PT Sans Narrow,PT Sans Narrow;"> PT Sans Narrow</option>
        <option  value="Permanent Marker" style="font-family: Permanent Marker,Permanent Marker;"> Permanent Marker</option>
        <option  value="Philosopher" style="font-family: Philosopher,Philosopher;"> Philosopher</option>
        <option  value="Puritan" style="font-family: Puritan,Puritan;"> Puritan</option>
        <option  value="Raleway" style="font-family: Raleway,Raleway;"> Raleway</option>
        <option  value="Reenie Beanie" style="font-family: Reenie Beanie,Reenie Beanie;"> Reenie Beanie</option>
        <option  value="Rock Salt" style="font-family: Rock Salt,Rock Salt;"> Rock Salt</option>
        <option  value="Schoolbell" style="font-family: Schoolbell,Schoolbell;"> Schoolbell</option>
        <option  value="Slackey" style="font-family: Slackey,Slackey;"> Slackey</option>
        <option  value="Sniglet" style="font-family: Sniglet,Sniglet;"> Sniglet</option>
        <option  value="Sunshiney" style="font-family: Sunshiney,Sunshiney;"> Sunshiney</option>
        <option  value="Syncopate" style="font-family: Syncopate,Syncopate;"> Syncopate</option>
        <option  value="Tangerine" style="font-family: Tangerine,Tangerine;"> Tangerine</option>
        <option  value="Tinos" style="font-family: Tinos,Tinos;"> Tinos</option>
        <option  value="Ubuntu" style="font-family: Ubuntu,Ubuntu;"> Ubuntu</option>
        <option  value="UnifrakturCook" style="font-family: UnifrakturCook,UnifrakturCook;"> UnifrakturCook</option>
        <option  value="UnifrakturMaguntia" style="font-family: UnifrakturMaguntia,UnifrakturMaguntia;"> UnifrakturMaguntia</option>
        <option  value="Unkempt" style="font-family: Unkempt,Unkempt;"> Unkempt</option>
        <option  value="Vibur" style="font-family: Vibur,Vibur;"> Vibur</option>
        <option  value="Vollkorn" style="font-family: Vollkorn,Vollkorn;"> Vollkorn</option>
        <option  value="Walter Turncoat" style="font-family: Walter Turncoat,Walter Turncoat;"> Walter Turncoat</option>
        <option  value="Yanone Kaffeesatz" style="font-family: Yanone Kaffeesatz,Yanone Kaffeesatz;"> Yanone Kaffeesatz</option> 
      ';
  		$options = explode("<option", $options);
  		unset($options[0]);
  		foreach ($options as $option) {
  			if (strpos(stripslashes($option), '"' . stripslashes($this->font) . '"') !== false) {
  				echo "<option selected='selected' " . $option;
  			} else {
  				echo "<option " . $option;
  			}
  		}
  		if ($this->weight == "")
  			$this->weight = "normal";
  		echo "</select>";
      
  		echo "<br><input isparam=1 type='hidden' value=\"" . $this->data . "\" name='" . $this->name . "'>";
  		echo "<input class='wpdreans-fontweight' name='" .self::$_instancenumber . "_font-weight' type='radio' value='normal' " . (($this->weight == 'normal') ? 'checked' : '') . ">Normal</input>";
  		echo "<input class='wpdreans-fontweight' name='" .self::$_instancenumber . "_font-weight' type='radio' value='bold' " . (($this->weight == 'bold') ? 'checked' : '') . ">Bold</input>";
  		echo "<br><span>Font size: </span>";
  		echo "<input type='text' class='wpdreams-fontsize threedigit' name='" . self::$_instancenumber . "_size' value='" . $this->size . "' />";
      echo "<br><span class='wpdreams-hint'>(ex.:10em, 10px or 110%)</span>";
      echo "<span>Line height: </span><input type='text' class='wpdreams-lineheight threedigit' name='" . self::$_instancenumber . "_lineheight' value='" . $this->lineheight . "' />";
  		echo "
        <div class='triggerer'></div>
      </fieldset>
      </div>";
  	}
  	final function getData() {
  		return $this->data;
  	}
  	final function getScript() {
  		if (strpos($this->font, "'"))
  			return;
  		$font = str_replace(" ", "+", trim($this->font));
  		ob_start();
  ?>
    <style>
      @import url(https://fonts.googleapis.com/css?family=<?php echo $font; ?>:300|<?php echo $font; ?>:400|<?php echo $font; ?>:700);
    </style>
    <?php
  		$out = ob_get_contents();
  		ob_end_clean();
  		return $out;
  	}
  	final function getImport() {
  		if (strpos($this->font, "'"))
  			return;
  		$font = str_replace(" ", "+", trim($this->font));
  		ob_start();
  ?>
      @import url(https://fonts.googleapis.com/css?family=<?php echo $font; ?>:300|<?php echo $font; ?>:400|<?php echo $font; ?>:700);
    <?php
  		$out = ob_get_contents();
  		ob_end_clean();
  		return $out;
  	}
  }
}
?>