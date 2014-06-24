<?php

	/* get a global option */
	function userpro_vk_get_option( $option ) {
		$userpro_default_options = userpro_vk_default_options();
		$settings = get_option('userpro_vk');
		switch($option){
		
			default:
				if (isset($settings[$option])){
					return $settings[$option];
				} else {
					return $userpro_default_options[$option];
				}
				break;
	
		}
	}
	
	/* set a global option */
	function userpro_vk_set_option($option, $newvalue){
		$settings = get_option('userpro_vk');
		$settings[$option] = $newvalue;
		update_option('userpro_vk', $settings);
	}
	
	/* default options */
	function userpro_vk_default_options(){
		$array = array();
		$array['vk_connect'] = 1;
		$array['vk_api_id'] = '';
		$array['vk_api_secret'] = '';
		return apply_filters('userpro_vk_default_options_array', $array);
	}