<?php
/*
Plugin Name: Mobile.Nav 
Plugin URI: http://codecanyon.net/user/sommersethdesign
Description: Easy to setup and maintain mobile navigation for Wordpress
Version: 1.4.2
Author: Konrad Węgrzyniak
Author URI: http://codecanyon.net/user/sommersethdesign
Copyright: Sommerseth Design
*/



/**
 *
 * Enable translating
 *
 */
load_plugin_textdomain('sdrn', false, basename( dirname( __FILE__ ) ) . '/lang' );




/**
 *
 * Add admin settings
 *
 */
if(is_admin()) require dirname(__FILE__).'/admin/settings.php';








/**
 *
 * Add scripts and styles for the frontend
 *
 */
if (!is_admin()) {
	wp_enqueue_script('jquery.transit', plugins_url( '/js/jquery.transit.min.js', __FILE__ ), array( 'jquery' ));
	wp_enqueue_script('touchwipe', plugins_url( '/js/jquery.touchwipe.min.js', __FILE__ ), array( 'jquery' ));
	wp_enqueue_script('sidr', plugins_url( '/js/jquery.sidr.js', __FILE__ ), array( 'jquery' ));
	wp_enqueue_script('mobile.nav.frontend.js', plugins_url( '/js/mobile.nav.frontend.js', __FILE__ ), array( 'jquery' ));

	wp_register_style( 'mobile.nav.frontend.css', plugins_url('css/mobile.nav.frontend.css', __FILE__) );
	wp_enqueue_style( 'mobile.nav.frontend.css' );
}










/**
 *
 * Add scripts and styles for the backend
 *
 */
if (is_admin()) {
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'mobile.nav.colors.js', plugins_url('js/mobile.nav.colors.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
	wp_enqueue_script( 'mobile.nav.backend.js', plugins_url('js/mobile.nav.backend.js', __FILE__ ), array( 'jquery' ));
}





$options = get_option('sdrn_options');



add_action('wp_footer', 'sdrn_menu', 100);




function sdrn_menu() {
	$options = get_option('sdrn_options');
	if($options['enabled']) :
		?>
		<div id="sdrn_bar" class="sdrn_bar" data-from_width="<?php echo $options['from_width'] ?>">
			<div class="sdrn_ic">
				<span class="sdrn_ic_1"></span>
				<span class="sdrn_ic_2"></span>
				<span class="sdrn_ic_3"></span>
			</div>
			<!--<span class="sdrn_icon sdrn_icon_menu" data-icon="m"></span>-->
			<span class="menu_title">
				<?php echo $options['bar_title'] ?>
				<?php if($options['bar_logo']) echo '<img class="bar_logo" src="'.$options['bar_logo'].'"/>' ?>
			</span>
		</div>

		<div id="sdrn_menu" class="sdrn_levels <?php echo $options['position'] ?> <?php if($options["nesting_icon"] != '') echo 'sdrn_custom_icons'  ?>" data-custom_icon="<?php echo $options["nesting_icon"]  ?>" data-custom_icon_open="<?php echo $options["nesting_icon_open"]  ?>" data-zooming="<?php echo $options["zooming"] ?>" >
			<ul id="sdrn_menu_ul">
				<?php
				$menus = get_terms('nav_menu',array('hide_empty'=>false));
				if($menus) : foreach($menus as $m) :
					if($m->term_id == $options['menu']) $menu = $m;
				endforeach; endif;
				if(is_object($menu)) :
					wp_nav_menu( array('menu'=>$menu->name,'container'=>false,'items_wrap'=>'%3$s'));
				endif;
				?>
			</ul>
		</div>
		<?php
	endif;
}


function sdrn_header_styles() {
	$options = get_option('sdrn_options');
	if($options['enabled']) :
		?>
		<style id="sdrn_css" type="text/css" >
			/* apply appearance settings */
			#sdrn_bar {
				background: <?php echo $options["bar_bgd"] ?>;
				<?php if (is_admin_bar_showing()) : ?>
					top:28px;
				<?php endif; ?>
			}
			#sdrn_bar .menu_title, #sdrn_bar .sdrn_icon_menu {
				color: <?php echo $options["bar_color"] ?>;
			}
			#sdrn_menu {
				background: <?php echo $options["menu_bgd"] ?>!important;
			}
			#sdrn_menu.sdrn_levels ul li {
				border-bottom:1px solid <?php echo $options["menu_border_bottom"] ?>;
				border-top:1px solid <?php echo $options["menu_border_top"] ?>;
			}
			#sdrn_menu ul li a {
				color: <?php echo $options["menu_color"] ?>;
			}
			#sdrn_menu ul li a:hover {
				color: <?php echo $options["menu_color_hover"] ?>;
			}
			#sdrn_menu.sdrn_levels a.sdrn_parent_item {
				border-left:1px solid <?php echo $options["menu_border_top"] ?>;
			}
			#sdrn_menu .sdrn_icon_par {
				color: <?php echo $options["menu_color"] ?>;
			}
			#sdrn_menu .sdrn_icon_par:hover {
				color: <?php echo $options["menu_color_hover"] ?>;
			}
			#sdrn_menu.sdrn_levels ul li ul {
				border-top:1px solid <?php echo $options["menu_border_bottom"] ?>;
			}
			<?php
			//when option "hide bottom borders is on...
			if($options["menu_border_bottom_show"] === 'no') { ?>
				#sdrn_menu, #sdrn_menu ul, #sdrn_menu li {
					border-bottom:none!important;
				}
				#sdrn_menu.sdrn_levels > ul {
					border-bottom:1px solid <?php echo $options["menu_border_top"] ?>!important;
				}
				.sdrn_no_border_bottom {
					border-bottom:none!important;
				}
				#sdrn_menu.sdrn_levels ul li ul {
					border-top:none!important;
				}
			<?php } ?>

			#sdrn_menu.left {
				width:<?php echo $options["how_wide"] ?>%;
				left: -<?php echo $options["how_wide"] ?>%;
			    right: auto;
			}
			<?php if (is_admin_bar_showing()) : ?>
				#sdrn_menu.left ul#sdrn_menu_ul, #sdrn_menu.right ul#sdrn_menu_ul {
					padding-top: 70px; /* 42 + 28 */
				}
			<?php endif; ?>
			#sdrn_menu.right {
				width:<?php echo $options["how_wide"] ?>%;
			    right: -<?php echo $options["how_wide"] ?>%;
			    left: auto;
			}


			<?php if($options["nesting_icon"] != '') : ?>
				#sdrn_menu .sdrn_icon:before {
					font-family: 'fontawesome'!important;
				}
			<?php endif; ?>

			<?php if($options["menu_symbol_pos"] == 'right') : ?>
				#sdrn_bar .sdrn_ic {
					float: <?php echo $options["menu_symbol_pos"] ?>!important;
					margin-right:0px!important;
				}
				#sdrn_bar .bar_logo {
					pading-left: 0px;
				}
			<?php endif; ?>


			/* show the bar and hide othere navigation elements */
			@media only screen and (max-width: <?php echo $options["from_width"] ?>px) {
				html { padding-top: 42px!important; }
				#sdrn_bar { display: block!important; }
				<?php
				if(count($options['hide']) > 0) {
					echo implode(', ', $options['hide']);
					echo ' { display:none!important; }';
				}
				?>
			}
			/* hide the bar & the menu */
			@media only screen and (min-width: <?php echo (int)$options["from_width"]+1 ?>px) {
			}

		</style>
		<?php
	endif;
}
add_action('wp_head', 'sdrn_header_styles');



