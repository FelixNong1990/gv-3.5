<?php

	/* Enqueue Scripts */
	add_action('wp_enqueue_scripts', 'userpro_vk_enqueue_scripts', 99);
	function userpro_vk_enqueue_scripts(){
	
		wp_register_style('userpro_vk', userpro_vk_url . 'css/userpro-vk.css');
		wp_enqueue_style('userpro_vk');
	
	}