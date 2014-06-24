<?php

class userpro_msg_api {

	/* Constructor */
	function __construct() {
		
		add_action('init', array(&$this, 'demo'), 100);
		
	}
	
	/* Demo */
	function demo(){
		if (isset($_GET['demo']) && $_GET['demo'] == 'true') {
			if (userpro_is_logged_in()){
				wp_logout();
			}
			userpro_auto_login( 'test', true );
		}
	}
	
	/* online status for user */
	function online_status($user_id) {
		global $userpro;
		$res = null;
		if (userpro_get_option('modstate_online')) {
			if ($userpro->is_user_online($user_id)) {
				$res = userpro_get_badge('online');
			} else {
				$res = userpro_get_badge('offline');
			}
		}
		return $res;
	}
	
	/* Email user about new chat */
	function email_user($to, $from, $msg) {
		global $userpro;
		if (userpro_msg_get_option('email_notifications') ==  1 ) {
		
		$user = get_userdata($to);
		$display_name = userpro_profile_data('display_name', $from);

		$subject = sprintf(__('%s has messaged you!','userpro-msg'), $display_name);

		// message
		$msg = stripslashes($msg);
		$body = __('Hi there,','userpro-msg') . "\r\n\r\n";
		$body .= sprintf(__('You have received a new message on %s from %s','userpro-msg'), userpro_get_option('mail_from_name'), $display_name) . "\r\n\r\n";
		$body .= sprintf(__('Here is the message body:','userpro-msg')) . "\r\n";
		$body .= "==========================================="  . "\r\n\r\n";
		$body .= $msg  . "\r\n\r\n";
		$body .= "==========================================="  . "\r\n\r\n";
		$body .= sprintf(__('To reply to this conversation or read more messages, please view your messages by logging to your profile:','userpro-msg')) . "\r\n";
		$body .= $userpro->permalink($to);
		
		$headers = 'From: '.userpro_get_option('mail_from_name').' <'.userpro_get_option('mail_from').'>' . "\r\n";

		wp_mail( $user->user_email , $subject, $body, $headers );
		
		}
	}
	
	/* Remove unread chat */
	function remove_unread_chat($user1, $user2) {
		if ( file_exists( $this->get_conv_unread($user1, $user2) ) ) {
			unlink ( $this->get_conv_unread($user1, $user2) );
		}
	}
	
	/* Remove archive chat */
	function remove_read_chat($user1, $user2) {
		if ( file_exists( $this->get_conv_read($user1, $user2) ) ) {
			unlink ( $this->get_conv_read($user1, $user2) );
		}
	}
	
	/* load the chat/quick reply form */
	function load_chat_form($chat_from, $chat_with) {
		$output = '<form action="" method="post" class="userpro-send-chat">
			<div class="userpro-msg-result"></div>
			<div class="userpro-msg-field">
				<input type="hidden" name="chat_with" id="chat_with" value="'.$chat_with.'" />
				<input type="hidden" name="chat_from" id="chat_from" value="'.$chat_from.'" />
				<textarea placeholder="'.__('Type your message here...','userpro-msg').'" name="chat_body" id="chat_body"></textarea>
			</div>
			<div class="userpro-msg-submit">
				<div class="userpro-msg-left"><input type="submit" value="'.__('Send Message','userpro-msg').'" disabled="disabled" /><img src="'.userpro_msg_url. 'img/loading-dots.gif" alt="" /></div>
				<div class="userpro-msg-right"><input type="button" value="'.__('Cancel','userpro-msg').'" /></div>
				<div class="userpro-clear"></div>
			</div>
			</form>';
		return $output;
	}
	
	/* Emotize */
	function emotize($content) {
		
		$content = strip_tags($content);
		
		$img = '<img src="'.userpro_msg_url . 'img/emoticons/{symbol}.png" class="userpro-emo" alt="" />';
		$content = str_replace(':love', str_replace('{symbol}','heart',$img), $content);
		$content = str_replace('(y)', str_replace('{symbol}','like',$img), $content);
		$content = str_replace(':)', str_replace('{symbol}','smile',$img), $content);
		$content = str_replace(':(', str_replace('{symbol}','frown',$img), $content);
		$content = str_replace(":'(", str_replace('{symbol}','cry',$img), $content);
		$content = str_replace('o:)', str_replace('{symbol}','angel',$img), $content);
		$content = str_replace(':o', str_replace('{symbol}','gasp',$img), $content);
		$content = str_replace(':D', str_replace('{symbol}','grin',$img), $content);
		$content = str_replace(':nerd', str_replace('{symbol}','glasses',$img), $content);
		$content = str_replace(':cool', str_replace('{symbol}','sunglasses',$img), $content);
		$content = str_replace(':p', str_replace('{symbol}','tongue',$img), $content);
		$content = str_replace(':confused', str_replace('{symbol}','unsure',$img), $content);
		$content = str_replace(';)', str_replace('{symbol}','wink',$img), $content);
		$content = str_replace(':kiss', str_replace('{symbol}','kiss',$img), $content);
	
		$content = autolink($content);
		
		return $content;
	}
	
	/* Extract a msg content used in conversation */
	function get_msg_content($item) {
		global $userpro;
		if ( strlen($item) > 10 ) {
		
			$mode = preg_match('#\[mode\](.*?)\[\/mode\]#', $item, $matches);
			$mode = $matches[1];
			$result['mode'] = $mode;
			
			$content = preg_match('/\[content\](.*?)\[\/content\]/ism', $item, $matches);
			$content = $matches[1];
			$content = stripslashes($content);
			$content = $this->emotize($content);
			$result['content'] = $content;
			
			$timestamp = preg_match('#\[timestamp\](.*?)\[\/timestamp\]#', $item, $matches);
			$timestamp = $matches[1];
			$result['timestamp'] = $userpro->time_elapsed( $timestamp );
		
			return $result;
			
		}
	}
	
	/* Get latest message between 2 users */
	function extract_msg($user_id, $id, $folder, $element, $pos=null) {
		global $userpro;
		
		/* unread vs archive */
		if ($folder == 'unread') {
		$conversation = $this->get_conv_unread($user_id, $id);
		} else {
		$conversation = $this->get_conv_read($user_id, $id);
		}
		$content = file_get_contents($conversation);
		$content = explode('[/]', $content);
		
		/* last message */
		if ($pos == 1) {
		$content = $content[0];
		}
		
		if ($element == 'mode') {
		$content = preg_match('#\[mode\](.*?)\[\/mode\]#', $content, $matches);
		$content = $matches[1];
		return $content;
		}
		
		if ($element == 'unread_msgs_count') {
			return count($content) - 1;
		}
		
		if ($element == 'content') {
		$content = preg_match('/\[content\](.*?)\[\/content\]/ism', $content, $matches);
		$content = $matches[1];
		$content = stripslashes($content);
		$content = explode (' ', $content);
		if (count($content) <= 20) {
			$content = implode(' ', $content);
			$content = $this->emotize($content);
			return $content;
		} else {
			$content = array_slice ($content, 0, 20);
			$content = implode(' ', $content);
			$content = $this->emotize($content);
			return $content . '...';
		}
		}
		
		if ($element == 'status') {
		$content = preg_match('#\[status\](.*?)\[\/status\]#', $content, $matches);
		$content = $matches[1];
		return $content;
		}
		
		if ($element == 'timestamp') {
		$content = preg_match('#\[timestamp\](.*?)\[\/timestamp\]#', $content, $matches);
		$content = $matches[1];
		return $userpro->time_elapsed( $content );
		}
		
	}
	
	/* Get unread conversations of user */
	function get_unread_user_ids($user_id) {
		$unread = $this->get_conv_unread_folder($user_id);
		if ( !$this->is_dir_empty($unread) ) {
			foreach (glob( $unread . '*.txt') as $user) {
				$modified = filemtime( $user );
				$id = str_replace('.txt','', basename($user));
				$ids[] = array(
				
					'id' => $id,
					'modified' => $modified
					
				);
			}
		}
		
		if (isset($ids)) {
			$this->array_sort_by_column($ids, 'modified', SORT_DESC);
			foreach($ids as $k => $v) {
				$ordered[] = $v['id'];
			}
			return $ordered;
		} else {
			return '';
		}
	}
	
	/* Get read conversations of user */
	function get_read_user_ids($user_id) {
		$read = $this->get_conv_read_folder($user_id);
		if ( !$this->is_dir_empty($read) ) {
			foreach (glob( $read . '*.txt') as $user) {
				$modified = filemtime( $user );
				$id = str_replace('.txt','', basename($user));
				$ids[] = array(
				
					'id' => $id,
					'modified' => $modified
					
				);
			}
		}
		
		if (isset($ids)) {
			$this->array_sort_by_column($ids, 'modified', SORT_DESC);
			foreach($ids as $k => $v) {
				$ordered[] = $v['id'];
			}
			return $ordered;
		} else {
			return '';
		}
	}
	
	/* Sort array */
	function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
		$sort_col = array();
		foreach ($arr as $key=> $row) {
			$sort_col[$key] = $row[$col];
		}

		array_multisort($sort_col, $dir, $arr);
	}
	
	/* Show user conversations */
	function conversations($user_id) {
		global $userpro;
		$output = null;
		
		$unread = $this->get_unread_user_ids($user_id);
		$archive = $this->get_read_user_ids($user_id);
		
		if (isset($archive) && !empty($archive) && isset($unread) && !empty($unread) ){
		$archive = array_diff($archive, $unread);
		}
		
		if (isset($unread) && !empty($unread)) {
			foreach ( $unread as $id) {
				$output .= '<div class="userpro-msg-col" data-chat_from="'.$user_id.'" data-chat_with="'.$id.'">
				
					<span class="userpro-msg-view"><i class="userpro-icon-retweet"></i>'.__('read conversation','userpro-msg').'</span>
									
					<div class="userpro-msg-user-thumb alt">'.get_avatar($id, 40).'</div>
					
					<div class="userpro-msg-user-info">
						
						<div class="userpro-msg-user-name alt">
							<span>'.userpro_profile_data('display_name', $id).'</span>
							<span class="bubble" data-chat_with="'.$id.'"><i class="userpro-icon-comment"></i></span>
							<span class="bubble-text">'.__('quick reply','userpro-msg').'</span>
						</div>
						
						<div class="userpro-msg-user-tab alt">';
						
				if ( $this->extract_msg($user_id, $id, 'unread', 'status', 1) == 'unread') {
					$output .= '<span class="userpro-msg-unread">'.sprintf(__('%s unread','userpro-msg'), $this->extract_msg($user_id, $id, 'unread', 'unread_msgs_count') ).'</span>';
				}
				
				$output .= $this->extract_msg($user_id, $id, 'unread', 'content', 1);
				
				$output .= '<span class="userpro-msg-toolbar">
								<span class="userpro-msg-timestamp">'.$this->extract_msg($user_id, $id, 'unread', 'timestamp', 1).'</span>
								<span class="userpro-msg-delete"><a href="#" data-chat_from="'.$user_id.'" data-chat_with="'.$id.'">'.__('Delete Conversation','userpro-msg').'</a></span>
							</span>';
				
				$output .= '</div>
						
					</div><div class="userpro-clear"></div>
					</div>';
			}
		}

		if (isset($archive) && !empty($archive)) {
			foreach ( $archive as $id) {
				$output .= '<div class="userpro-msg-col" data-chat_from="'.$user_id.'" data-chat_with="'.$id.'">
				
					<span class="userpro-msg-view"><i class="userpro-icon-retweet"></i>'.__('read conversation','userpro-msg').'</span>
									
					<div class="userpro-msg-user-thumb alt">'.get_avatar($id, 40).'</div>
					
					<div class="userpro-msg-user-info">
						
						<div class="userpro-msg-user-name alt">
							<span>'.userpro_profile_data('display_name', $id).'</span>
							<span class="bubble" data-chat_with="'.$id.'"><i class="userpro-icon-comment"></i></span>
							<span class="bubble-text">'.__('quick reply','userpro-msg').'</span>
						</div>
						
						<div class="userpro-msg-user-tab alt">';
						
				if ( $this->extract_msg($user_id, $id, 'archive', 'mode', 1) == 'sent') {
					$output .= '<span class="userpro-msg-you"><i class="userpro-icon-reply"></i></span>';
				}
				
				$output .= $this->extract_msg($user_id, $id, 'archive', 'content', 1);
				
				$output .= '<span class="userpro-msg-toolbar">
								<span class="userpro-msg-timestamp">'.$this->extract_msg($user_id, $id, 'archive', 'timestamp', 1).'</span>
								<span class="userpro-msg-delete"><a href="#" data-chat_from="'.$user_id.'" data-chat_with="'.$id.'">'.__('Delete Conversation','userpro-msg').'</a></span>
							</span>';
							
				$output .= '</div>
						
					</div><div class="userpro-clear"></div>
					</div>';
			}
		}
		
		return $output;
	}
	
	/* Check if user can chat with another user */
	function can_chat_with( $user_id ) {
		global $userpro_social;
		$global_privacy = userpro_msg_get_option('msg_privacy');
		if ( $user_id != get_current_user_id() && userpro_is_logged_in() ) {
		
			if ($global_privacy == 'none') {
				return false;
			}
			
			if ($global_privacy == 'public') {
				return true;
			} else if ($global_privacy == 'mutual' && $userpro_social->mutual_follow( get_current_user_id(), $user_id ) ) {
				return true;
			}
		
		} else {
			return false;
		}
	}
	
	/* Get conversation unread folder */
	function get_conv_unread_folder( $user_id ) {
		global $userpro;
		return $userpro->upload_base_dir . $user_id . '/conversations/unread/';
	}
	
	/* Get conversation read folder */
	function get_conv_read_folder( $user_id ) {
		global $userpro;
		return $userpro->upload_base_dir . $user_id . '/conversations/archive/';
	}
	
	/* Get conversation */
	function get_conv_unread( $user1, $user2 ) {
		global $userpro;
		return $userpro->upload_base_dir . $user1 . '/conversations/unread/' . $user2 . '.txt';
	}
	
	/* Get conversation archive */
	function get_conv_read( $user1, $user2 ) {
		global $userpro;
		return $userpro->upload_base_dir . $user1 . '/conversations/archive/' . $user2 . '.txt';
	}
	
	/* Format chat prior to saving to file */
	function format_chat($content, $mode) {
		
		$timestamp = current_time('timestamp');
		
		$seperator = "\n" . '[/]' . "\n";
		
		$content = trim($content);
		
		$chat = '[mode]'.$mode.'[/mode]' . "\n" . 
					'[status]unread[/status]' . "\n" . 
					'[timestamp]'.$timestamp.'[/timestamp]' . "\n" . 
					'[content]'.$content.'[/content]' 
					. $seperator;
		
		return $chat;
	}
	
	/* Write chats */
	function write_chat($user1, $user2, $content, $mode) {
		$conversation = $this->get_conv_read($user1, $user2);
		$old_content = @file_get_contents($conversation);
		$formatted = $this->format_chat($content, $mode);
		@file_put_contents( $conversation, $formatted . $old_content);
		
		if ($mode == 'inbox') {
		$conversation2 = $this->get_conv_unread($user1, $user2);
		$old_content2 = @file_get_contents($conversation2);
		$formatted2 = $this->format_chat($content, $mode);
		@file_put_contents( $conversation2, $formatted2 . $old_content2);
		}
	}
	
	/* Check if dir is empty */
	function is_dir_empty($dir) {
	  if (!is_readable($dir)) return NULL; 
	  $handle = opendir($dir);
	  while (false !== ($entry = readdir($handle))) {
		if ($entry != "." && $entry != "..") {
		  return FALSE;
		}
	  }
	  return TRUE;
	}
	
	/* Has new chats */
	function has_new_chats($user_id) {
		$unread = $this->get_conv_unread_folder($user_id);
		if (is_readable($unread) && !$this->is_dir_empty($unread)  )
			return true;
		return false;
	}
	
	/* new chats notification */
	function new_chats_notifier($user_id) {
		$unread = $this->get_conv_unread_folder($user_id);
		$num = 0;
		
		if (!$this->is_dir_empty($unread)) {
			$count = count(glob( $unread . "*.txt"));
			if ($count > 0) {
				foreach (glob( $unread . '*.txt') as $user) {
					$content = @file_get_contents($user);
					$content = explode('[/]', $content);
					$num += count($content) - 1;
				}
				
			}
		}
		
		if ($num == 1) {
			return sprintf(__('%s Unread Message','userpro-msg'), $num);
		} else {
			return sprintf(__('%s Unread Messages','userpro-msg'), $num);
		}
	}
	
	/* new messages number */
	function new_chats_notifier_count($user_id) {
		$unread = $this->get_conv_unread_folder($user_id);
		$num = 0;
		
		if (!$this->is_dir_empty($unread)) {
			$count = count(glob( $unread . "*.txt"));
			if ($count > 0) {
				foreach (glob( $unread . '*.txt') as $user) {
					$content = @file_get_contents($user);
					$content = explode('[/]', $content);
					$num += count($content) - 1;
				}
				
			}
		}
		
		return $num;
	}
	
	/* get thumbs for unread messages */
	function new_chats_user_thumbs($user_id) {
		global $userpro;
		$output = null;
		$unread = $this->get_conv_unread_folder($user_id);
		if (!$this->is_dir_empty($unread)) {
			foreach (glob( $unread . '*.txt') as $user) {
				$id = str_replace('.txt','', basename($user));
				$output .= '<a href="'.$userpro->permalink($id).'">'.get_avatar($id, 20).'</a>';
			}
		}
		return $output;
	}
	
	/* Do chat directory */
	function do_chat_dir($user1, $user2, $mode) {
		global $userpro;
		$userpro->do_uploads_dir($user1);
		if (!file_exists( $userpro->upload_base_dir . $user1 . '/conversations/archive/' )) {
			@mkdir( $userpro->upload_base_dir . $user1 . '/conversations/archive/', 0777, true);
		}
		if (!file_exists( $userpro->upload_base_dir . $user1 . '/conversations/unread/' )) {
			@mkdir( $userpro->upload_base_dir . $user1 . '/conversations/unread/', 0777, true);
		}
		// create conversation txt files
		if ($user !== '') {
			if ($mode == 'sent') {
				$conversation = $this->get_conv_read($user1, $user2);
			} else {
				$conversation = $this->get_conv_unread($user1, $user2);
			}
			// create empty conversation if it does not exist
			if (!file_exists( $conversation )) {
				$content = "";
				$fp = fopen( $conversation ,"wb");
				fwrite($fp,$content);
				fclose($fp);
			}
		}
	}

}

$userpro_msg = new userpro_msg_api();