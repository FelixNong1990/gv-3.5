<?php

	/* get a global option */
	function userpro_msg_get_option( $option ) {
		$userpro_default_options = userpro_msg_default_options();
		$settings = get_option('userpro_msg');
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
	function userpro_msg_set_option($option, $newvalue){
		$settings = get_option('userpro_msg');
		$settings[$option] = $newvalue;
		update_option('userpro_msg', $settings);
	}
	
	/* default options */
	function userpro_msg_default_options(){
		$array = array();
		$array['msg_privacy'] = 'public';
		$array['msg_notification'] = 'r';
		$array['msg_auto_welcome'] = 0;
		$array['msg_auto_welcome_id'] = 1;
		$array['msg_auto_welcome_text'] = 'Welcome to UserPro! This is an automatic welcome message sent to you using the private messaging add-on. I am happy to have you as a member, If you are interested about the private message add-on you can view more information here: http://bit.ly/1icXlMN Thank you! :)';
		$array['email_notifications'] = 0;
		return apply_filters('userpro_msg_default_options_array', $array);
	}