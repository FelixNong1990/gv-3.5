<?php echo ((isset($style['script-inputfont']))?$style['script-inputfont']:""); ?>
<?php echo ((isset($style['script-descfont']))?$style['script-descfont']:""); ?>
<?php echo ((isset($style['script-titlefont']))?$style['script-titlefont']:""); ?>
<?php echo ((isset($style['script-titlehoverfont']))?$style['script-titlehoverfont']:""); ?>
<?php echo ((isset($style['script-authorfont']))?$style['script-authorfont']:""); ?>
<?php echo ((isset($style['script-datefont']))?$style['script-datefont']:""); ?>
<?php echo ((isset($style['script-showmorefont']))?$style['script-showmorefont']:""); ?>
<?php echo ((isset($style['script-groupfont']))?$style['script-groupfont']:""); ?>
<?php echo ((isset($style['script-exsearchincategoriestextfont']))?$style['script-exsearchincategoriestextfont']:""); ?>
<?php echo ((isset($style['script-groupbytextfont']))?$style['script-groupbytextfont']:""); ?>  
<?php echo ((isset($style['script-settingsdropfont']))?$style['script-settingsdropfont']:""); ?>
<?php echo ((isset($style['script-prestitlefont']))?$style['script-prestitlefont']:""); ?>  
<?php echo ((isset($style['script-presdescfont']))?$style['script-presdescfont']:""); ?>    
<?php echo ((isset($style['script-pressubtitlefont']))?$style['script-pressubtitlefont']:""); ?>

@font-face {
	font-family: 'icons';
	src:url('<?php echo plugins_url(); ?>/ajax-search-pro/css/fonts/icons/icons.eot');
	src:url('<?php echo plugins_url(); ?>/ajax-search-pro/css/fonts/icons/icons.eot?#iefix') format('embedded-opentype'),
		url('<?php echo plugins_url(); ?>/ajax-search-pro/css/fonts/icons/icons.woff') format('woff'),
		url('<?php echo plugins_url(); ?>/ajax-search-pro/css/fonts/icons/icons.ttf') format('truetype'),
		url('<?php echo plugins_url(); ?>/ajax-search-pro/css/fonts/icons/icons.svg#icons') format('svg');
	font-weight: normal;
	font-style: normal;
}

.clear {
  clear: both;
}

.hiddend {
  display:none;
}

/* General css reset */

#ajaxsearchpro<?php echo $id; ?>,
#ajaxsearchpro<?php echo $id; ?> *,
#ajaxsearchprores<?php echo $id; ?>,
#ajaxsearchprores<?php echo $id; ?> *,
#ajaxsearchprosettings<?php echo $id; ?>,
#ajaxsearchprosettings<?php echo $id; ?> * {
  -webkit-box-sizing: content-box; /* Safari/Chrome, other WebKit */
  -moz-box-sizing: content-box;    /* Firefox, other Gecko */
  -ms-box-sizing: content-box;
  -o-box-sizing: content-box;
  box-sizing: content-box;
  padding: 0;
  margin: 0;
  border: 0;
  border-radius: 0;
  text-transform: none;
  text-shadow: none;
  box-shadown: none;
  text-decoration: none;
  text-align: left;
}

#ajaxsearchpro<?php echo $id; ?> textarea:focus, 
#ajaxsearchpro<?php echo $id; ?> input:focus{
    outline: none;
}

#ajaxsearchpro<?php echo $id; ?> {
  width: 100%;
  height: auto;
  border-radius: 5px;
  background: #d1eaff;
  <?php wpdreams_gradient_css($style['boxbackground']); ?>;
  overflow: hidden;
  <?php echo $style['boxborder']; ?>
  <?php echo $style['boxshadow']; ?>
}

#ajaxsearchpro<?php echo $id; ?> .probox {
  width: auto;
  margin: <?php echo $style['boxmargin']; ?>;
  height: <?php echo $style['boxheight']; ?>;
  border-radius: 5px;
  background: #FFF;
  overflow: hidden;
  border: 1px solid #FFF;
  box-shadow: 1px 0 3px #CCCCCC inset;
  <?php wpdreams_gradient_css($style['inputbackground']); ?>;
  <?php echo $style['inputborder']; ?>
  <?php echo $style['inputshadow']; ?>
}

#ajaxsearchpro<?php echo $id; ?> .probox .proinput {
  width: auto;
  height: 100%;
  margin: 2px 0px 0px 10px;
  padding: 0 5px;
  float: left;
  box-shadow: none;
  position: relative;
  <?php echo $style['inputfont']; ?>
}

#ajaxsearchpro<?php echo $id; ?> .probox .proinput input {
  height: 100%;
  border: 0px;
  background: transparent;
  width: 100%;
  box-shadow: none;
  margin: -1px;
  padding: 0;
  left: 0;
  <?php echo $style['inputfont']; ?>
}

#ajaxsearchpro<?php echo $id; ?> .probox .proinput input.autocomplete {
  border: 0px;
  background: transparent;
  width: 100%;
  box-shadow: none;
  margin: -1px;
  padding: 0;
  left: 0;
  <?php echo $style['inputfont']; ?>
}

#ajaxsearchpro<?php echo $id; ?> .probox .proinput.iepaddingfix {
  padding-top: 0;
}

#ajaxsearchpro<?php echo $id; ?> .probox .proinput .loading {
  width: 32px;
  background: #000;
  height: 100%;
  box-shadow: none;
}

#ajaxsearchpro<?php echo $id; ?> .probox .proloading,
#ajaxsearchpro<?php echo $id; ?> .probox .promagnifier,
#ajaxsearchpro<?php echo $id; ?> .probox .prosettings  {
  width: <?php echo wpdreams_width_from_px($style['boxheight']); ?>px;
  height: <?php echo wpdreams_width_from_px($style['boxheight']); ?>px;
  background: none;
  background-size: 20px 20px;
  float: right;
  box-shadow: none;
  margin: 0;
  padding: 0;
}

#ajaxsearchpro<?php echo $id; ?> .probox .proloading {
  background: url("<?php echo plugins_url().$style['loadingimage']; ?>") no-repeat scroll center center rgba(0, 0, 0, 0) !important;
  background-position:center center;
  visibility: hidden;
  background-size: auto; 
}

#ajaxsearchpro<?php echo $id; ?> .probox .promagnifier {

  width: <?php echo (wpdreams_width_from_px($style['boxheight'])-2*wpdreams_border_width($style['magnifierbackgroundborder'])); ?>px;
  height: <?php echo (wpdreams_width_from_px($style['boxheight'])-2*wpdreams_border_width($style['magnifierbackgroundborder'])); ?>px;
  background-image: -o-<?php wpdreams_gradient_css_rgba($style['magnifierbackground']); ?>;
  background-image: -ms-<?php wpdreams_gradient_css_rgba($style['magnifierbackground']); ?>;
  background-image: -webkit-<?php wpdreams_gradient_css_rgba($style['magnifierbackground']); ?>;
  background-image: <?php wpdreams_gradient_css_rgba($style['magnifierbackground']); ?>; 
  background-position:center center;
  background-repeat: no-repeat;

  <?php echo $style['magnifierbackgroundborder']; ?>
  <?php echo $style['magnifierboxshadow']; ?>
  cursor: pointer;
  background-size: 100% 100%;

  background-position:center center;
  background-repeat: no-repeat;
  cursor: pointer;
}

#ajaxsearchpro<?php echo $id; ?> .probox .promagnifier .innericon {
  background-image: url("<?php echo  plugins_url().$style['magnifierimage']; ?>");
  background-size: 20px 20px;
  background-position:center center;
  background-repeat: no-repeat;
  background-color: transparent;
  width: 100%;
  height: 100%; 
}


#ajaxsearchpro<?php echo $id; ?> .probox .prosettings {
  
  width: <?php echo (wpdreams_width_from_px($style['boxheight'])-2*wpdreams_border_width($style['settingsbackgroundborder'])); ?>px;
  height: <?php echo (wpdreams_width_from_px($style['boxheight'])-2*wpdreams_border_width($style['settingsbackgroundborder'])); ?>px;
  background-image: -o-<?php wpdreams_gradient_css_rgba($style['settingsbackground']); ?>;
  background-image: -ms-<?php wpdreams_gradient_css_rgba($style['settingsbackground']); ?>;
  background-image: -webkit-<?php wpdreams_gradient_css_rgba($style['settingsbackground']); ?>; 
  background-image: <?php wpdreams_gradient_css_rgba($style['settingsbackground']); ?>; 
  background-position:center center;
  background-repeat: no-repeat;
  float: <?php echo $style['settingsimagepos']; ?>;
  <?php echo $style['settingsbackgroundborder']; ?>
  <?php echo $style['settingsboxshadow']; ?>
  cursor: pointer;
  background-size: 100% 100%;
}

#ajaxsearchpro<?php echo $id; ?> .probox .prosettings .innericon {
  background-image: url("<?php echo  plugins_url().$style['settingsimage']; ?>");
  background-size: 20px 20px;
  background-position:center center;
  background-repeat: no-repeat;
  background-color: transparent;
  width: 100%;
  height: 100%; 
}

@media (-webkit-min-device-pixel-ratio: 2), 
(min-resolution: 192dpi) { 
  #ajaxsearchpro<?php echo $id; ?> .probox .promagnifier .innericon {
    background-image: url("<?php echo  wpdreams_x2(plugins_url().$style['magnifierimage']); ?>");
  }
  #ajaxsearchpro<?php echo $id; ?> .probox .promagnifier .innericon {
    background-image: url("<?php echo  wpdreams_x2(plugins_url().$style['magnifierimage']); ?>");
  }
  #ajaxsearchpro<?php echo $id; ?> .probox .proloading {
    background: url("<?php echo wpdreams_x2(plugins_url().$style['loadingimage']); ?>") no-repeat;
  }
}

#ajaxsearchprores<?php echo $id; ?> * {
  text-decoration: none;
  text-shadow: none;
}

#ajaxsearchprores<?php echo $id; ?> {
  padding: 4px;
  background: #D1EAFF;
  background: <?php echo $style['resultsbackground']; ?>;
  border-radius: 3px;
  <?php echo $style['resultsborder']; ?>
  <?php echo $style['resultshadow']; ?>
  position: <?php echo (($style['resultsposition']=='hover')?'absolute':'static'); ?>;
  visibility: hidden;
  z-index:1100;
  display: none;
}

#ajaxsearchprores<?php echo $id; ?>.horizontal {
  <?php wpdreams_gradient_css($style['hboxbg']); ?>;
  <?php echo $style['hboxborder']; ?>
  <?php echo wpdreams_box_shadow_css($style['hboxshadow']); ?>
  margin-top: <?php echo $style['resultsmargintop']; ?>;
}

#ajaxsearchprores<?php echo $id; ?> .results .nores {
  overflow: hidden;
  width: auto;
  height: 100%;
  line-height: auto;
  text-align: center;
  margin: 0;
  background: #FFF;
}

#ajaxsearchprores<?php echo $id; ?>.horizontal .results .nores {
  background: transparent;
}

#ajaxsearchprores<?php echo $id; ?> .results .nores .keyword{
  padding: 0 6px;
  cursor: pointer;
  <?php echo $style['descfont']; ?>
  font-weight: bold;
}

#ajaxsearchprores<?php echo $id; ?> .results {
  overflow: hidden;
  width: auto;
  height: 0;
  margin: 0;
  padding: 0;
}

#ajaxsearchprores<?php echo $id; ?>.horizontal .results {
  height: auto;
  width: auto;
}

#ajaxsearchprores<?php echo $id; ?> .results .item {
  overflow: hidden;
  width: auto;
  height: <?php echo $style['resultitemheight']; ?>;
  margin: 0;
  padding: 3px;
  position: relative;
  background: #f4f4f4;
  background: <?php echo $style['resultscontainerbackground']; ?>;
  border-left: 1px solid rgba(255, 255, 255, 0.6);
  border-right: 1px solid rgba(255, 255, 255, 0.4);
  animation-delay: 0s;
  animation-duration: 1s;
  animation-fill-mode: both;
  animation-timing-function: ease;
  backface-visibility: hidden;
  -webkit-animation-delay: 0s;
  -webkit-animation-duration: 1s;
  -webkit-animation-fill-mode: both;
  -webkit-animation-timing-function: ease;
  -webkit-backface-visibility: hidden;
}

#ajaxsearchprores<?php echo $id; ?>.vertical .results .item:first-child {
  border-radius: 3px 3px 0 0;
}

#ajaxsearchprores<?php echo $id; ?>.vertical .results .item:last-child {
  border-radius: 0 0 3px 3px;
  margin-bottom: 0px;
}

#ajaxsearchprores<?php echo $id; ?>.vertical .results .item:after {
  background: none repeat scroll 0 0 #CCCCCC;
  background: <?php echo $style['spacercolor']; ?>;
  content: "";
  display: block;
  height: 2px;
  margin: 2px -10px;
  width: 10000px;
}

#ajaxsearchprores<?php echo $id; ?>.vertical .results .item:last-child:after {
  height:0;
  margin: 0;
  width: 0;
}

#ajaxsearchprores<?php echo $id; ?> .results .item:hover {
  <?php wpdreams_gradient_css($style['vresulthbg']); ?>;
}

#ajaxsearchprores<?php echo $id; ?>.horizontal .results .item {
  height: <?php echo $style['hresheight']; ?>; 
  width: <?php echo $style['hreswidth']; ?>;
  margin: 10px <?php echo $style['hressidemargin']; ?>;
  padding: <?php echo $style['hrespadding']; ?>;  
  float: left; 
  <?php wpdreams_gradient_css($style['hresultbg']); ?>;
  <?php echo $style['hresultborder']; ?>
  <?php wpdreams_box_shadow_css($style['hresultshadow']); ?>
}

#ajaxsearchprores<?php echo $id; ?>.horizontal .results .item:hover {
  <?php wpdreams_gradient_css($style['hresulthbg']); ?>;
}
      
#ajaxsearchprores<?php echo $id; ?> .results .item .image {
  overflow: hidden;
  width: <?php echo $style['selected-imagesettings']['width']; ?>px;
  height: <?php echo $style['selected-imagesettings']['height']; ?>px;
  background: transparent;
  margin: 1px auto 0px -3px;
  padding: 0;
  float: left;
}

#ajaxsearchprores<?php echo $id; ?>.horizontal .results .item .image {
  margin: 0 auto;
  <?php wpdreams_gradient_css($style['hresultbg']); ?>;
  <?php echo $style['hresultimageshadow']; ?>
}

#ajaxsearchprores<?php echo $id; ?> .results .item .image img {
  width: 100%;
  height: 100%;
}

<?php
  $_vimagew = wpdreams_width_from_px($style['hreswidth']);
  $_vimageratio =  $_vimagew / $style['selected-imagesettings']['width'];
  $_vimageh = $_vimageratio * $style['selected-imagesettings']['height'];
?>

#ajaxsearchprores<?php echo $id; ?>.horizontal .results .item .image {
  width: <?php echo $_vimagew ?>px;
  height: <?php echo $_vimageh; ?>px;
  <?php echo $style['hresultimageborder']; ?>
  float: none;
  margin: 0 auto 6px;
  position: relative;
}

#ajaxsearchprores<?php echo $id; ?>.horizontal .results .item .image img + div {
  box-shadow: 0 0 5px -1px #000000 inset;
  position: absolute;
  width: <?php echo $_vimagew ?>px;
  height: <?php echo $_vimageh; ?>px;
  top: 0;
  left: 0;
}


#ajaxsearchprores<?php echo $id; ?> .results .item .content {
  overflow: hidden;
  width: 50%;
  height: <?php echo $style['resultitemheight']; ?>;
  background: transparent;
  margin: 0;
  padding: 0 10px;
}

#ajaxsearchprores<?php echo $id; ?>.horizontal .results .item .content {
  height: auto;
  width: 100%;
  padding: 0;
}

#ajaxsearchprores<?php echo $id; ?> .results .item .content h3 {
  margin: 0;
  padding: 0;
  line-height: inherit;
  <?php echo $style['titlefont']; ?>
}

#ajaxsearchprores<?php echo $id; ?> .results a span.overlap {
  position:absolute; 
  width:100%;
  height:100%;
  top:0;
  left: 0;
  z-index: 1;
  background-image: url('empty.gif');
}

#ajaxsearchprores<?php echo $id; ?>.horizontal .results .item .content h3 a {
  text-align: center;
}

#ajaxsearchprores<?php echo $id; ?> .results .item .content h3 a {
  margin: 0;
  padding: 0;
  line-height: inherit;
  <?php echo $style['titlefont']; ?>
}

#ajaxsearchprores<?php echo $id; ?> .results .item .content h3 a:hover {
  <?php echo $style['titlehoverfont']; ?>
}

#ajaxsearchprores<?php echo $id; ?> .results .item div.etc {
  padding: 0;
  line-height: 10px;
  <?php echo $style['authorfont']; ?>
}
#ajaxsearchprores<?php echo $id; ?> .results .item .etc .author {
  padding: 0;
  <?php echo $style['authorfont']; ?>
}
#ajaxsearchprores<?php echo $id; ?> .results .item .etc .date {
  margin: 0 0 0 10px;
  padding: 0;
  <?php echo $style['datefont']; ?>  
}
#ajaxsearchprores<?php echo $id; ?> .resdrg {                                                                                                                                     
  height: auto;
}

#ajaxsearchprores<?php echo $id; ?>.horizontal .resdrg {                                                                                                                                     
  height: auto;
  width: auto;
}

#ajaxsearchprores<?php echo $id; ?> .results .item p.desc {
  margin: 2px 0px;
  padding: 0;
  <?php echo $style['descfont']; ?>
}

#ajaxsearchprores<?php echo $id; ?> .mCSB_container{
	width:auto;
	margin-right:20px;
	overflow:hidden;
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_container.mCS_no_scrollbar{
	margin-right:0;
}
#ajaxsearchprores<?php echo $id; ?> .mCustomScrollBox .mCSB_scrollTools{
	width:16px;
	height:100%;
	top:0;
	right:0;
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_draggerContainer{
	height:100%;
	-webkit-box-sizing:border-box;
	-moz-box-sizing:border-box;
	box-sizing:border-box;
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonUp+.mCSB_draggerContainer{
	padding-bottom:40px;
  margin-top: 20px;
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_draggerRail{
	width:2px;
	height:100%;
	margin:0 auto;
	-webkit-border-radius:10px;
	-moz-border-radius:10px;
	border-radius:10px;
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_dragger{
	cursor:pointer;
	width:100%;
	height:30px;
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar{
	width:6px;
  box-shadow: 0 0 2px 0 rgba(0, 0, 0, 0.5);
	height:100%;
	margin:0 auto;
	-webkit-border-radius:10px;
	-moz-border-radius:10px;
	border-radius:10px;
	text-align:center;
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonUp,
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonDown{
	height:20px;
	-overflow:hidden;
	margin:0 auto;
	cursor:pointer;
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonDown{
	bottom:0;
	margin-top:-40px;
}

#ajaxsearchprores<?php echo $id; ?> .mCSB_horizontal .mCSB_container{
	height:auto;
	margin-right:0;
	margin-bottom:20px;
	overflow:hidden;
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_horizontal .mCSB_container.mCS_no_scrollbar{
	margin-bottom:0;
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_horizontal.mCustomScrollBox .mCSB_scrollTools{
	width:100%;
	height:26px;
	top:auto;
	right:auto;
	bottom:0;
	left:0;
	overflow:hidden;
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_horizontal .mCSB_scrollTools .mCSB_draggerContainer{
	height:23px;
	width:auto;
	-webkit-box-sizing:border-box;
	-moz-box-sizing:border-box;
	box-sizing:border-box;
	overflow:hidden;
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_horizontal .mCSB_scrollTools .mCSB_buttonLeft+.mCSB_draggerContainer{
	padding-bottom:0;
	padding-right:20px;
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_horizontal .mCSB_scrollTools .mCSB_draggerRail{
	width:100%;
	height:2px;
	margin:7px 0;
	-webkit-border-radius:10px;
	-moz-border-radius:10px;
	border-radius:10px;
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_horizontal .mCSB_scrollTools .mCSB_dragger{
	width:30px;
	height:100%;
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_horizontal .mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar{
	width:100%;
	height:4px;
	margin:6px auto;
	-webkit-border-radius:10px;
	-moz-border-radius:10px;
	border-radius:10px;
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_horizontal .mCSB_scrollTools .mCSB_buttonLeft,
#ajaxsearchprores<?php echo $id; ?> .mCSB_horizontal .mCSB_scrollTools .mCSB_buttonRight{
	width:20px;
	height:100%;
	overflow:hidden;
	margin:0 auto;
	cursor:pointer;
	float:left;
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_horizontal .mCSB_scrollTools .mCSB_buttonRight{
	right:0;
	bottom:auto;
	margin-left:-40px;
	margin-top:-16px;
	float:right;
}


#ajaxsearchprores<?php echo $id; ?> .mCustomScrollBox .mCSB_scrollTools{
	opacity:0.75;
}
#ajaxsearchprores<?php echo $id; ?> .mCustomScrollBox:hover .mCSB_scrollTools{
	opacity:1;
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_draggerRail{
	background:#000; /* rgba fallback */
	background:rgba(0,0,0,0.4);
	filter:"alpha(opacity=40)"; -ms-filter:"alpha(opacity=40)"; /* old ie */
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar{
	background:#fff; /* rgba fallback */
	background:rgba(<?php echo wpdreams_hex2rgb($style['overflowcolor']); ?>,0.9);
	filter:"alpha(opacity=90)"; -ms-filter:"alpha(opacity=90)"; /* old ie */
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_dragger:hover .mCSB_dragger_bar{
	background:rgba(<?php echo wpdreams_hex2rgb($style['overflowcolor']); ?>,0.95);
	filter:"alpha(opacity=95)"; -ms-filter:"alpha(opacity=95)"; /* old ie */
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_dragger:active .mCSB_dragger_bar,
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_dragger.mCSB_dragger_onDrag .mCSB_dragger_bar{
	background:rgba(<?php echo wpdreams_hex2rgb($style['overflowcolor']); ?>,1);
	filter:"alpha(opacity=100)"; -ms-filter:"alpha(opacity=100)"; /* old ie */
}

#ajaxsearchprores<?php echo $id; ?>.horizontal .mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar{
	background:#fff; /* rgba fallback */
	background:<?php echo $style['hoverflowcolor']; ?>;
  opacity: 0.9;
	filter:"alpha(opacity=90)"; -ms-filter:"alpha(opacity=90)"; /* old ie */
}
#ajaxsearchprores<?php echo $id; ?>.horizontal .mCSB_scrollTools .mCSB_dragger:hover .mCSB_dragger_bar{
	background:<?php echo $style['hoverflowcolor']; ?>;
  opacilty: 0.95;
	filter:"alpha(opacity=95)"; -ms-filter:"alpha(opacity=95)"; /* old ie */
}
#ajaxsearchprores<?php echo $id; ?>.horizontal .mCSB_scrollTools .mCSB_dragger:active .mCSB_dragger_bar,
#ajaxsearchprores<?php echo $id; ?>.horizontal .mCSB_scrollTools .mCSB_dragger.mCSB_dragger_onDrag .mCSB_dragger_bar{
	background: <?php echo $style['hoverflowcolor']; ?>;
	filter:"alpha(opacity=100)"; -ms-filter:"alpha(opacity=100)"; /* old ie */
}


#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonUp,
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonDown,
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonLeft,
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonRight{
	padding: 10px 0 0 0;
  background:0;
	opacity:0.4;
	filter:"alpha(opacity=40)"; -ms-filter:"alpha(opacity=40)"; /* old ie */
}

#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonDown { height:0;position: relative; } 
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonDown:after { top: 100%; border: solid transparent; content: " "; height: 0; width: 0; position: absolute;} 
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonDown:after { border-color: rgba(136, 183, 213, 0); border-top-color: <?php echo $style['arrowcolor']; ?>; border-width: 6px; left: 50%; margin-left: -6px; }
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonUp { position: relative; margin:10px 0 0 0; height: 0; } 
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonUp:after { bottom: 100%; border: solid transparent; content: " "; height: 0; width: 0; position: absolute; } 
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonUp:after { border-color: rgba(136, 183, 213, 0); border-bottom-color:  <?php echo $style['arrowcolor']; ?>; border-width: 6px; left: 50%; margin-left: -6px; }
 
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonUp{
	background-position:0 0;
	/* 
	sprites locations are 0 0/-16px 0/-32px 0/-48px 0 (light) and -80px 0/-96px 0/-112px 0/-128px 0 (dark) 
	*/
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonDown{
	background-position:0 -20px;
	/* 
	sprites locations are 0 -20px/-16px -20px/-32px -20px/-48px -20px (light) and -80px -20px/-96px -20px/-112px -20px/-128px -20px (dark) 
	*/
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonLeft{
	background-position:0 -40px;
	/* 
	sprites locations are 0 -40px/-20px -40px/-40px -40px/-60px -40px (light) and -80px -40px/-100px -40px/-120px -40px/-140px -40px (dark) 
	*/
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonRight{
	background-position:0 -56px;
	/* 
	sprites locations are 0 -56px/-20px -56px/-40px -56px/-60px -56px (light) and -80px -56px/-100px -56px/-120px -56px/-140px -56px (dark) 
	*/
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonUp:hover,
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonDown:hover,
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonLeft:hover,
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonRight:hover{
	opacity:0.75;
	filter:"alpha(opacity=75)"; -ms-filter:"alpha(opacity=75)"; /* old ie */
}
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonUp:active,
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonDown:active,
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonLeft:active,
#ajaxsearchprores<?php echo $id; ?> .mCSB_scrollTools .mCSB_buttonRight:active{
	opacity:0.9;
	filter:"alpha(opacity=90)"; -ms-filter:"alpha(opacity=90)"; /* old ie */
}    

#ajaxsearchprores<?php echo $id; ?> span.highlighted{
    font-weight: bold;
    color: #d9312b;
    background-color: #eee;
    color: <?php echo $style['highlightcolor'] ?>;
    background-color: <?php echo $style['highlightbgcolor'] ?>;
}

#ajaxsearchprores<?php echo $id; ?> p.showmore {
  text-align: center;
  padding: 0;
  margin: 0;
  <?php echo $style['showmorefont']; ?> 
}

#ajaxsearchprores<?php echo $id; ?> p.showmore a{
  <?php echo $style['showmorefont']; ?> 
}

#ajaxsearchprores<?php echo $id; ?> .group {
  background: #DDDDDD;
  background: <?php echo $style['exsearchincategoriesboxcolor']; ?>;
  border-radius: 3px 3px 0 0;
  border-top: 1px solid <?php echo $style['groupingbordercolor']; ?>;
  border-left: 1px solid <?php echo $style['groupingbordercolor']; ?>;
  border-right: 1px solid <?php echo $style['groupingbordercolor']; ?>;
  margin: 10px 0 -3px;
  padding: 7px 0 7px 10px;
  position: relative;
  z-index: 1000;
  <?php echo $style['groupbytextfont']; ?> 
}

#ajaxsearchprores<?php echo $id; ?> .group:first-of-type {
  margin: 0px 0 -3px;
}

#ajaxsearchprosettings<?php echo $id; ?>.searchsettings  {
  width: 200px;
  height: auto;
  background: <?php echo wpdreams_gradient_css($style['settingsdropbackground']); ?>;
  position: absolute;
  display: none;
  z-index: 1101;
  border-radius: 0 0 3px 3px;
  <?php echo $style['settingsdropboxshadow']; ?>;
  visibility: hidden;
  padding: 0 0 8px 0;
}

#ajaxsearchprosettings<?php echo $id; ?>.searchsettings .option {
  margin: 10px;
  *padding-bottom: 10px;
}

#ajaxsearchprosettings<?php echo $id; ?>.searchsettings.ie78 .option {
  margin-bottom: 0 !important;
  padding-bottom: 0 !important;
}

#ajaxsearchprosettings<?php echo $id; ?>.searchsettings .label {
  font-size: 14px;
  line-height: 21px !important;
  margin: -29px 10px 0 38px;
  width: 150px;
  text-shadow: none;
  padding: 0;
  min-height: 20px;
  border: none;
  background: transparent;
  float: left;
  <?php echo $style['settingsdropfont']; ?>
}

/* SQUARED THREE */
#ajaxsearchprosettings<?php echo $id; ?>.searchsettings .option input[type=checkbox] {	
  display:none;
}

#ajaxsearchprosettings<?php echo $id; ?>.searchsettings.ie78 .option input[type=checkbox] {	
  display:block;
}

#ajaxsearchprosettings<?php echo $id; ?>.searchsettings.ie78 .label {	
  float:right !important;
}

#ajaxsearchprosettings<?php echo $id; ?>.searchsettings .option {
	width: 17px;
  height: 17px;	
	position: relative;
}

#ajaxsearchprosettings<?php echo $id; ?>.searchsettings .option label {
	cursor: pointer;
	position: absolute;
	width: 17px;
	height: 17px;
	top: 0;
  padding: 0;
	border-radius: 4px;
	-webkit-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.5), 0px 1px 0px rgba(255,255,255,.4);
	-moz-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.5), 0px 1px 0px rgba(255,255,255,.4);
	box-shadow: inset 0px 1px 1px rgba(0,0,0,0.5), 0px 1px 0px rgba(255,255,255,.4);
  <?php wpdreams_gradient_css($style['settingsdroptickbggradient']); ?>;
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#222222', endColorstr='#45484d',GradientType=0 );
}

#ajaxsearchprosettings<?php echo $id; ?>.searchsettings.ie78 .option label {
  display:none;
}

#ajaxsearchprosettings<?php echo $id; ?>.searchsettings .option label:after {
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
	filter: alpha(opacity=0);
	opacity: 0;
	content: "";
	position: absolute;
	background: transparent;
	border: 3px solid <?php echo $style['settingsdroptickcolor'] ?>;
	border-top: none;
	border-right: none;
  
  height: 3px;
  left: 4px;
  top: 5px;
  width: 6px;

	-webkit-transform: rotate(-45deg);
	-moz-transform: rotate(-45deg);
	-o-transform: rotate(-45deg);
	-ms-transform: rotate(-45deg);
	transform: rotate(-45deg);
}

#ajaxsearchprosettings<?php echo $id; ?>.searchsettings.ie78 .option label:after {
  display:none;
}

#ajaxsearchprosettings<?php echo $id; ?>.searchsettings .option label:hover::after {
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=30)";
	filter: alpha(opacity=30);
	opacity: 0.3;
}

#ajaxsearchprosettings<?php echo $id; ?>.searchsettings .option input[type=checkbox]:checked + label:after {
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
	filter: alpha(opacity=100);
	opacity: 1;
}

#ajaxsearchprosettings<?php echo $id; ?>.searchsettings fieldset {
  position:relative;
  float:left;
}

#ajaxsearchprosettings<?php echo $id; ?>.searchsettings fieldset .categoryfilter {
  max-height: <?php echo $style['exsearchincategoriesheight']; ?>px;
  overflow: auto;
}

#ajaxsearchprosettings<?php echo $id; ?>.searchsettings fieldset {
  background: transparent;
  font-size: 0.9em;
  margin: 5px 0 0;
  padding: 0px;
  width: 192px;
}

#ajaxsearchprosettings<?php echo $id; ?>.searchsettings  fieldset legend {
  padding: 5px 0 0 10px;
  margin: 0;
  <?php echo $style['exsearchincategoriestextfont']; ?>
}

#ajaxsearchprosettings<?php echo $id; ?>.searchsettings fieldset .label {
  width: 130px;
}

/* basic scrollbar styling */
/* vertical scrollbar */
.results .mCSB_container{
	width:auto;
	margin-right:30px;
	overflow:hidden;
}
.results .mCSB_container.mCS_no_scrollbar{
	margin-right:0;
}
.results .mCS_disabled>.mCustomScrollBox>.mCSB_container.mCS_no_scrollbar,
.results .mCS_destroyed>.mCustomScrollBox>.mCSB_container.mCS_no_scrollbar{
	margin-right:30px;
}
.results .mCustomScrollBox>.mCSB_scrollTools{
	width:16px;
	height:100%;
	top:0;
	right:0;
}
.results .mCSB_scrollTools .mCSB_draggerContainer{
	position:absolute;
	top:0;
	left:0;
	bottom:0;
	right:0; 
	height:auto;
}
.results .mCSB_scrollTools a+.mCSB_draggerContainer{
	margin:20px 0;
}
.results .mCSB_scrollTools .mCSB_draggerRail{
	width:2px;
	height:100%;
	margin:0 auto;
	-webkit-border-radius:10px;
	-moz-border-radius:10px;
	border-radius:10px;
}
.results .mCSB_scrollTools .mCSB_dragger{
	cursor:pointer;
	width:100%;
	height:30px;
}
.results .mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar{
	width:4px;
	height:100%;
	margin:0 auto;
	-webkit-border-radius:10px;
	-moz-border-radius:10px;
	border-radius:10px;
	text-align:center;
}
.results .mCSB_scrollTools .mCSB_buttonUp,
.results .mCSB_scrollTools .mCSB_buttonDown{
	display:block;
}
.results .mCSB_scrollTools .mCSB_buttonDown{
	top:100%;
	margin-top:-40px;
}
/* horizontal scrollbar */
.results .mCSB_horizontal>.mCSB_container{
	height:auto;
	margin-right:0;
	margin-bottom:30px;
	overflow:hidden;
}
.results .mCSB_horizontal>.mCSB_container.mCS_no_scrollbar{
	margin-bottom:0;
}
.results .mCS_disabled>.mCSB_horizontal>.mCSB_container.mCS_no_scrollbar,
.results .mCS_destroyed>.mCSB_horizontal>.mCSB_container.mCS_no_scrollbar{
	margin-right:0;
	margin-bottom:30px;
}
.results .mCSB_horizontal.mCustomScrollBox>.mCSB_scrollTools{
	width:100%;
	height:16px;
	top:auto;
	right:auto;
	bottom:0;
	left:0;
	overflow:hidden;
}
.results .mCSB_horizontal>.mCSB_scrollTools a+.mCSB_draggerContainer{
	margin:0 20px;
}
.results .mCSB_horizontal>.mCSB_scrollTools .mCSB_draggerRail{
	width:100%;
	height:2px;
	margin:7px 0;
	-webkit-border-radius:10px;
	-moz-border-radius:10px;
	border-radius:10px;
}
.results .mCSB_horizontal>.mCSB_scrollTools .mCSB_dragger{
	width:30px;
	height:100%;
}
.results .mCSB_horizontal>.mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar{
	width:100%;
	height:4px;
	margin:6px auto;
	-webkit-border-radius:10px;
	-moz-border-radius:10px;
	border-radius:10px;
}
.results .mCSB_horizontal>.mCSB_scrollTools .mCSB_buttonLeft,
.results .mCSB_horizontal>.mCSB_scrollTools .mCSB_buttonRight{
	display:block;
	position:relative;
	width:20px;
	height:100%;
	overflow:hidden;
	margin:0 auto;
	cursor:pointer;
	float:left;
}
.results .mCSB_horizontal>.mCSB_scrollTools .mCSB_buttonRight{
	margin-left:-40px;
	float:right;
}
.results .mCustomScrollBox{
	-ms-touch-action:none; /*MSPointer events - direct all pointer events to js*/
}

/* default scrollbar colors and backgrounds (default theme) */
.results .mCustomScrollBox>.mCSB_scrollTools{
	opacity:0.75;
	filter:"alpha(opacity=75)"; -ms-filter:"alpha(opacity=75)"; /* old ie */
}
.results .mCustomScrollBox:hover>.mCSB_scrollTools{
	opacity:1;
	filter:"alpha(opacity=100)"; -ms-filter:"alpha(opacity=100)"; /* old ie */
}
.results .mCSB_scrollTools .mCSB_draggerRail{
	background:#000; /* rgba fallback */
	background:rgba(0,0,0,0.4);
	filter:"alpha(opacity=40)"; -ms-filter:"alpha(opacity=40)"; /* old ie */
}
.results .mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar{
	background:#fff; /* rgba fallback */
	background:rgba(255,255,255,0.75);
	filter:"alpha(opacity=75)"; -ms-filter:"alpha(opacity=75)"; /* old ie */
}
.results .mCSB_scrollTools .mCSB_dragger:hover .mCSB_dragger_bar{
	background:rgba(255,255,255,0.85);
	filter:"alpha(opacity=85)"; -ms-filter:"alpha(opacity=85)"; /* old ie */
}
.results .mCSB_scrollTools .mCSB_dragger:active .mCSB_dragger_bar,
.results .mCSB_scrollTools .mCSB_dragger.mCSB_dragger_onDrag .mCSB_dragger_bar{
	background:rgba(255,255,255,0.9);
	filter:"alpha(opacity=90)"; -ms-filter:"alpha(opacity=90)"; /* old ie */
}

.results .mCSB_scrollTools .mCSB_buttonUp{
	background-position:0 0;
	/* 
	sprites locations are 0 0/-16px 0/-32px 0/-48px 0 (light) and -80px 0/-96px 0/-112px 0/-128px 0 (dark) 
	*/
}
.results .mCSB_scrollTools .mCSB_buttonDown{
	background-position:0 -20px;
	/* 
	sprites locations are 0 -20px/-16px -20px/-32px -20px/-48px -20px (light) and -80px -20px/-96px -20px/-112px -20px/-128px -20px (dark) 
	*/
}
.results .mCSB_scrollTools .mCSB_buttonLeft{
	/*
  background-position:0 -40px;
	 
	sprites locations are 0 -40px/-20px -40px/-40px -40px/-60px -40px (light) and -80px -40px/-100px -40px/-120px -40px/-140px -40px (dark) 
	*/
}

#ajaxsearchprores<?php echo $id; ?>.horizontal .results .mCSB_scrollTools .mCSB_buttonLeft { position: relative; background: transparent; margin-left: 9px; } 
#ajaxsearchprores<?php echo $id; ?>.horizontal .results .mCSB_scrollTools .mCSB_buttonLeft:after { border: solid transparent; content: " "; height: 0; width: 0; position: absolute; pointer-events: none; } 
#ajaxsearchprores<?php echo $id; ?>.horizontal .results .mCSB_scrollTools .mCSB_buttonLeft:after { border-color: rgba(136, 183, 213, 0); border-right-color: <?php echo $style['harrowcolor']; ?>; border-width: 7px; top: 50%; margin-top:  -7px; left: 5px; }

#ajaxsearchprores<?php echo $id; ?>.horizontal .results .mCSB_scrollTools .mCSB_buttonRight { position: relative; background: transparent; margin-right: 9px; margin-top: 0px;} 
#ajaxsearchprores<?php echo $id; ?>.horizontal .results .mCSB_scrollTools .mCSB_buttonRight:after { border: solid transparent; content: " "; height: 0; width: 0; position: absolute; pointer-events: none; } 
#ajaxsearchprores<?php echo $id; ?>.horizontal .results .mCSB_scrollTools .mCSB_buttonRight:after { border-color: rgba(136, 183, 213, 0); border-left-color: <?php echo $style['harrowcolor']; ?>; border-width: 7px; top: 50%; margin-top:  -7px; left: 5px; }


#ajaxsearchprores<?php echo $id; ?> .results .mCSB_horizontal > .mCSB_scrollTools a + .mCSB_draggerContainer {
  margin: 10px 12px 0 36px
}

.results .mCSB_scrollTools .mCSB_buttonRight{
	background-position:0 -56px;
	/* 
	sprites locations are 0 -56px/-20px -56px/-40px -56px/-60px -56px (light) and -80px -56px/-100px -56px/-120px -56px/-140px -56px (dark) 
	*/
}
.results .mCSB_scrollTools .mCSB_buttonUp:hover,
.results .mCSB_scrollTools .mCSB_buttonDown:hover,
.results .mCSB_scrollTools .mCSB_buttonLeft:hover,
.results .mCSB_scrollTools .mCSB_buttonRight:hover{
	opacity:0.75;
	filter:"alpha(opacity=75)"; -ms-filter:"alpha(opacity=75)"; /* old ie */
}
.results .mCSB_scrollTools .mCSB_buttonUp:active,
.results .mCSB_scrollTools .mCSB_buttonDown:active,
.results .mCSB_scrollTools .mCSB_buttonLeft:active,
.results .mCSB_scrollTools .mCSB_buttonRight:active{
	opacity:0.9;
	filter:"alpha(opacity=90)"; -ms-filter:"alpha(opacity=90)"; /* old ie */
}


/* Polaroid stlyes */
#ajaxsearchprores<?php echo $id; ?> .photostack,
#ajaxsearchprores<?php echo $id; ?> .photostack * {
  -webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
  -moz-box-sizing: border-box;    /* Firefox, other Gecko */
  -ms-box-sizing: border-box;
  -o-box-sizing: border-box;
  box-sizing: border-box;
}

#ajaxsearchprores<?php echo $id; ?> .photostack {
	background: #ddd;
	position: relative;
	text-align: center;
	overflow: hidden;
  <?php wpdreams_gradient_css($style['prescontainerbg']); ?>;
}

.js #ajaxsearchprores<?php echo $id; ?> .photostack {
	height: 580px;
}

#ajaxsearchprores<?php echo $id; ?> .photostack-start {
	cursor: pointer;
}

/* Wrapper and figures */

/* The size of this wrapper can be smaller if the items should not be scattered across the whole container */ 
#ajaxsearchprores<?php echo $id; ?> .photostack > div {
	width: 100%;
	height: 100%;
	margin: 0 auto;
}

#ajaxsearchprores<?php echo $id; ?> .photostack figure {
	width: <?php echo $style['preswidth'] ?>;
	height: <?php echo $style['presheight'] ?>;
	position: relative;
	display: inline-block;
	background: #fff;
	padding: <?php echo $style['prespadding'] ?>;
	text-align: center;
	margin: 5px;
}

.js #ajaxsearchprores<?php echo $id; ?> .photostack figure {
	position: absolute;
	display: block;
	margin: 0;
}

#ajaxsearchprores<?php echo $id; ?> .photostack figcaption h2 {
	margin: 20px 0 0 0;
  <?php echo $style['prestitlefont']; ?>;
}

#ajaxsearchprores<?php echo $id; ?> .photostack figcaption h2 a {
  <?php echo $style['prestitlefont']; ?>;
}
#ajaxsearchprores<?php echo $id; ?> .photostack .etc {
  <?php echo $style['pressubtitlefont']; ?>;
}


#ajaxsearchprores<?php echo $id; ?> .photostack-img {
	outline: none;
	display: block;
	width: <?php echo (wpdreams_width_from_px($style['preswidth'])- 2*wpdreams_width_from_px($style['prespadding'])); ?>px;
	height: <?php echo (wpdreams_width_from_px($style['preswidth'])- 2*wpdreams_width_from_px($style['prespadding'])); ?>px;
	background: #f9f9f9;
  <?php echo $style['pressubtitlefont']; ?>;
}

#ajaxsearchprores<?php echo $id; ?> .photostack-back {
	display: none;
	position: absolute;
	width: 100%;
	height: 100%;
	top: 0;
	left: 0;
	background: #fff;
	padding: 50px 40px;
	text-align: left;
  <?php echo $style['presdescfont']; ?>;
}

#ajaxsearchprores<?php echo $id; ?> .photostack-back p {
	margin: 0;
}

#ajaxsearchprores<?php echo $id; ?> .photostack-back p span {
	text-decoration: line-through;
}

/* Navigation dots */
#ajaxsearchprores<?php echo $id; ?> .photostack nav {
	position: absolute;
	width: 100%;
	bottom: 30px;
	z-index: 90;
	text-align: center;
	left: 0;
	-webkit-transition: opacity 0.3s;
	transition: opacity 0.3s;
}

#ajaxsearchprores<?php echo $id; ?> .photostack-start nav {
	opacity: 0;
}
 
#ajaxsearchprores<?php echo $id; ?> .photostack nav span {
	position: relative;
	display: inline-block;
	margin: 0 5px;
	width: 30px;
	height: 30px;
	cursor: pointer;
	background: #aaa;
	border-radius: 50%;
	text-align: center;
	-webkit-transition: -webkit-transform 0.6s ease-in-out, background 0.3s;
	transition: transform 0.6s ease-in-out, background 0.3s;
	-webkit-transform: scale(0.48);
	transform: scale(0.48);
  <?php wpdreams_gradient_css($style['pdotssmallcolor']); ?>;
}

#ajaxsearchprores<?php echo $id; ?> .photostack nav span:last-child {
	margin-right: 0;
}

#ajaxsearchprores<?php echo $id; ?> .photostack nav span::after {
	content: "\e600";
	font-family: 'icons';
	font-size: 80%;
	speak: none;
	display: inline-block;
	vertical-align: top;
	font-style: normal;
	font-weight: normal;
	font-variant: normal;
	text-transform: none;
	line-height: 30px;
	color: #fff;
	opacity: 0;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	-webkit-transition: opacity 0.3s;
	transition: opacity 0.3s;
}

#ajaxsearchprores<?php echo $id; ?> .photostack nav span.current {
	background: #888;
	-webkit-transform: scale(1);
	transform: scale(1);
  <?php wpdreams_gradient_css($style['pdotscurrentcolor']); ?>;
}

#ajaxsearchprores<?php echo $id; ?> .photostack nav span.current.flip {
	-webkit-transform: scale(1) rotateY(-180deg) translateZ(-1px);
	transform: scale(1) rotateY(-180deg) translateZ(-1px);
	background: #555;
  <?php wpdreams_gradient_css($style['pdotsflippedcolor']); ?>;
}

#ajaxsearchprores<?php echo $id; ?> .photostack nav span.flippable::after {
	opacity: 1;
	-webkit-transition-delay: 0.4s;
	transition-delay: 0.4s;
}

/* Overlays */

/* Initial overlay on photostack container */
.js #ajaxsearchprores<?php echo $id; ?> .photostack::before {
	content: '';
	position: absolute;
	width: 100%;
	height: 100%;
	background: rgba(0,0,0,0.5);
	top: 0;
	left: 0;
	z-index: 100;
	-webkit-transition: opacity 0.3s, visibility 0s 0.3s;
	transition: opacity 0.3s, visibility 0s 0.3s;
}

.js #ajaxsearchprores<?php echo $id; ?> .photostack-start::before {
	-webkit-transition: opacity 0.3s;
	transition: opacity 0.3s;
}

/* Button on photostack container */
.js #ajaxsearchprores<?php echo $id; ?> .photostack::after {
	content: 'View Gallery';
	font-weight: 400;
	position: absolute;
	border: 3px solid #fff;
	text-align: center;
	white-space: nowrap;
	left: 50%;
	top: 50%;
	-webkit-transform: translateY(-50%) translateX(-50%);
	transform: translateY(-50%) translateX(-50%);
	padding: 10px 20px;
	color: #fff;
	text-transform: uppercase;
	letter-spacing: 1px;
	cursor: pointer;
	z-index: 101;
}

.js #ajaxsearchprores<?php echo $id; ?> .photostack::before,
.js #ajaxsearchprores<?php echo $id; ?> .photostack::after {
	opacity: 0;
	visibility: hidden;
}

.js #ajaxsearchprores<?php echo $id; ?> .photostack-start::before,
.js #ajaxsearchprores<?php echo $id; ?> .photostack-start:hover::after,
.touch .photostack-start::after  {
	opacity: 1;
	visibility: visible;
}

/* Overlay on figure */
#ajaxsearchprores<?php echo $id; ?> .photostack figure::after {
	content: '';
	position: absolute;
	width: 100%;
	height: 100%;
	top: 0;
	left: 0;
	visibility: visible;
	opacity: 1;
	background: rgba(0,0,0,0.05);
	-webkit-transition: opacity 0.6s;
	transition: opacity 0.6s;
}

/* Hide figure overlay when it becomes current */
#ajaxsearchprores<?php echo $id; ?> figure.photostack-current::after {
	-webkit-transition: opacity 0.6s, visibility 0s 0.6s;
	transition: opacity 0.6s, visibility 0s 0.6s;
	opacity: 0;
	visibility: hidden;
}

/* Special classes for transitions and perspective */
#ajaxsearchprores<?php echo $id; ?> .photostack-transition figure {
	-webkit-transition: -webkit-transform 0.6s ease-in-out;
	transition: transform 0.6s ease-in-out;
}

#ajaxsearchprores<?php echo $id; ?> .photostack-perspective {
	-webkit-perspective: 1800px;
	perspective: 1800px;
}

#ajaxsearchprores<?php echo $id; ?> .photostack-perspective > div,
#ajaxsearchprores<?php echo $id; ?> .photostack-perspective figure {
	-webkit-transform-style: preserve-3d;
	transform-style: preserve-3d;
}

#ajaxsearchprores<?php echo $id; ?> .photostack-perspective figure,
#ajaxsearchprores<?php echo $id; ?> .photostack-perspective figure div {
	-webkit-backface-visibility: hidden;
	backface-visibility: hidden;
}

#ajaxsearchprores<?php echo $id; ?> .photostack-perspective figure.photostack-flip {
	-webkit-transform-origin: 0% 50%;
	transform-origin: 0% 50%;
}

.csstransformspreserve3d figure.photostack-flip .photostack-back {
	-webkit-transform: rotateY(180deg) !important;
	transform: rotateY(180deg) !important;
	display: block !important;
}

.no-csstransformspreserve3d figure.photostack-showback .photostack-back {
	display: block !important;
}

/* The no-JS fallback look does not need to be boring ;) */
.no-js .photostack figure {
	box-shadow: -2px 2px 0 rgba(0,0,0,0.05) !important;
}

.no-js .photostack figure::after {
	display: none !important;
}

.no-js .photostack figure:nth-child(3n) {
	-webkit-transform: translateX(-10%) rotate(5deg) !important;
	transform: translateX(-10%) rotate(5deg) !important;
}

.no-js .photostack figure:nth-child(3n-2) {
	-webkit-transform: translateY(10%) rotate(-3deg) !important;
	transform: translateY(10%) rotate(-3deg) !important;
}

/* Some custom styles for the demo */

/* Since we don't have back sides for the first photo stack, we don't want the current dot to become too big */
#photostack-1 nav span.current {
	background: #888;
	-webkit-transform: scale(0.61);
	transform: scale(0.61);
}