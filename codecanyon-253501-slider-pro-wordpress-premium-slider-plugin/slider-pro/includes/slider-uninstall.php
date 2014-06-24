<?php
// on uninstall delete the slider tables from the database
global $wpdb;
$prefix = $wpdb->prefix;

$sliders_table = $prefix . 'sliderpro_sliders';
$slides_table = $prefix . 'sliderpro_slides';
$skins_table = $prefix . 'sliderpro_skins';

$wpdb->query("DROP TABLE $sliders_table, $slides_table, $skins_table");

// delete the options
delete_option('slider_pro_enable_timthumb');
delete_option('slider_pro_show_admin_bar_links');
delete_option('slider_pro_enqueue_jquery');
delete_option('slider_pro_enqueue_jquery_easing');
delete_option('slider_pro_enqueue_jquery_mousewheel');
delete_option('slider_pro_use_compressed_scripts');
delete_option('slider_pro_generate_xml_file');
delete_option('slider_pro_role_access');
delete_option('slider_pro_version');
delete_option('slider_pro_is_assets_dir');
delete_option('slider_pro_purchase_code_status');
delete_option('slider_pro_purchase_code');
delete_option('slider_pro_display_all_slider_options');
delete_option('slider_pro_visual_editor');
delete_option('slider_pro_multisite_path_rewrite');
delete_option('slider_pro_show_getting_started_message');
?>