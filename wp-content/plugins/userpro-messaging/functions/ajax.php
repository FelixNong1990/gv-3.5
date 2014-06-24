<?php

	/* delete a chat */
	add_action('wp_ajax_nopriv_userpro_delete_conversation', 'userpro_delete_conversation');
	add_action('wp_ajax_userpro_delete_conversation', 'userpro_delete_conversation');
	function userpro_delete_conversation(){
		global $userpro, $userpro_msg;
		$output = '';
		extract($_POST);
		
		if ( !userpro_is_logged_in() || $chat_from != get_current_user_id() ) die();
		if (!$userpro_msg->can_chat_with( $chat_with )) die();
		
		$userpro_msg->remove_unread_chat($chat_from, $chat_with);
		$userpro_msg->remove_read_chat($chat_from, $chat_with);
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}

	/* init a chat */
	add_action('wp_ajax_nopriv_userpro_init_chat', 'userpro_init_chat');
	add_action('wp_ajax_userpro_init_chat', 'userpro_init_chat');
	function userpro_init_chat(){
		global $userpro, $userpro_msg;
		$output = '';
		extract($_POST);
		
		if ( !userpro_is_logged_in() || $chat_from != get_current_user_id() ) die();
		if (!$userpro_msg->can_chat_with( $chat_with )) die();
		
		ob_start();
		
		require_once userpro_msg_path . 'templates/new-message.php';

		$output['html'] = ob_get_contents();
		
		ob_end_clean();
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/* show conversation */
	add_action('wp_ajax_nopriv_userpro_view_conversation', 'userpro_view_conversation');
	add_action('wp_ajax_userpro_view_conversation', 'userpro_view_conversation');
	function userpro_view_conversation(){
		global $userpro, $userpro_msg;
		$output = '';
		extract($_POST);
		
		if ( !userpro_is_logged_in() || $chat_from != get_current_user_id() ) die();
		
		$userpro_msg->remove_unread_chat($chat_from, $chat_with);
		
		ob_start();
		require_once userpro_msg_path . 'templates/conversation.php';
		$output['html'] = ob_get_contents();
		ob_end_clean();
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/* show chat */
	add_action('wp_ajax_nopriv_userpro_show_chat', 'userpro_show_chat');
	add_action('wp_ajax_userpro_show_chat', 'userpro_show_chat');
	function userpro_show_chat(){
		global $userpro, $userpro_msg;
		$output = '';
		extract($_POST);
		
		if ( !userpro_is_logged_in() || $user_id != get_current_user_id() ) die();
		
		ob_start();
		
		require_once userpro_msg_path . 'templates/messages.php';

		$output['html'] = ob_get_contents();
		
		ob_end_clean();
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/* start a chat */
	add_action('wp_ajax_nopriv_userpro_start_chat', 'userpro_start_chat');
	add_action('wp_ajax_userpro_start_chat', 'userpro_start_chat');
	function userpro_start_chat(){
		global $userpro, $userpro_msg;
		$output = '';
		extract($_POST);
		
		if ( !userpro_is_logged_in() || $chat_from != get_current_user_id() ) die();
		if (!$userpro_msg->can_chat_with( $chat_with )) die();
		
		/* Create folders to store conversations */
		$userpro_msg->do_chat_dir( $chat_from, $chat_with, $mode='sent' );
		$userpro_msg->do_chat_dir( $chat_with, $chat_from, $mode='inbox' );
		
		$userpro_msg->write_chat( $chat_from, $chat_with, $chat_body, $mode='sent' );
		$userpro_msg->write_chat( $chat_with, $chat_from, $chat_body, $mode='inbox' );
		
		$userpro_msg->email_user($chat_with, $chat_from, $chat_body);
		
		$userpro_msg->remove_unread_chat($chat_from, $chat_with);
		
		/* Status for browser */
		$output['message'] = '<div class="userpro-msg-notice">'.__('Your message has been sent successfully.','userpro-msg').'</div>';
		
		ob_start();
		require_once userpro_msg_path . 'templates/conversation.php';
		$output['html'] = ob_get_contents();
		ob_end_clean();
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}