<?php

// General Functions for Plugin

if (!function_exists('is_post')) {

    function is_post() {
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post')
            return true;
        else
            return false;
    }

}

if (!function_exists('is_in_post')) {

    function is_in_post($key='', $val='') {
        if ($key == '') {
            return false;
        } else {
            if (isset($_POST[$key])) {
                if ($val == '')
                    return true;
                else if ($_POST[$key] == $val)
                    return true;
                else
                    return false;
            }
            else
                return false;
        }
    }

}

if (!function_exists('is_get')) {

    function is_get() {
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'get')
            return true;
        else
            return false;
    }

}


if (!function_exists('is_in_get')) {

    function is_in_get($key='', $val='') {
        if ($key == '') {
            return false;
        } else {
            if (isset($_GET[$key])) {
                if ($val == '')
                    return true;
                else if ($_GET[$key] == $val)
                    return true;
                else
                    return false;
            }
            else
                return false;
        }
    }

}

if (!function_exists('not_null')) {

    function not_null($value) {
        if (is_array($value)) {
            if (sizeof($value) > 0)
                return true;
            else
                return false;
        }
        else {
            if ((is_string($value) || is_int($value)) && ($value != '') && ($value != 'NULL') && (strlen(trim($value)) > 0))
                return true;
            else
                return false;
        }
    }

}



if (!function_exists('get_value')) {

    function get_value($key='') {
        if ($key != '') {
            if (isset($_GET[$key]) && not_null($_GET[$key])) {
                if (!is_array($_GET[$key]))
                    return trim($_GET[$key]);
                else
                    return $_GET[$key];
            }

            else
                return '';
        }
        else
            return '';
    }

}


if (!function_exists('post_value')) {

    function post_value($key='') {
        if ($key != '') {
            if (isset($_POST[$key]) && not_null($_POST[$key])) {
                if (!is_array($_POST[$key]))
                    return trim($_POST[$key]);
                else
                    return $_POST[$key];
            }
            else
                return '';
        }
        else
            return '';
    }

}


if (!function_exists('is_opera')) {

    function is_opera() {
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        return preg_match('/opera/i', $user_agent);
    }

}

if (!function_exists('is_safari')) {

    function is_safari() {
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        return (preg_match('/safari/i', $user_agent) && !preg_match('/chrome/i', $user_agent));
    }

}

// Check with the magic quotes functionality Start
function stripslashess(&$item) {
    $item = stripslashes($item);
}

if (get_magic_quotes_gpc ()) {
    array_walk_recursive($_GET, 'stripslashess');
    array_walk_recursive($_POST, 'stripslashess');
    array_walk_recursive($_SERVER, 'stripslashess');
}


if (!function_exists('remove_script_tags')) {

    function remove_script_tags($text) {
        $text = str_ireplace("<script>", "", $text);
        $text = str_ireplace("</script>", "", $text);

        return $text;
    }

}

if (!function_exists('upme_date_format_to_standerd')) {

    function upme_date_format_to_standerd($date, $format) {

        switch ($format) {
            case 'mm/dd/yy':
                $new_format = 'm/d/Y';
                break;

            case 'yy/mm/dd':
                $new_format = 'Y/m/d';
                break;

            case 'dd/mm/yy':
                $new_format = 'd/m/Y';
                break;

            case 'yy-mm-dd':
                $new_format = 'Y-m-d';
                break;

            case 'dd-mm-yy':
                $new_format = 'd-m-Y';
                break;

            case 'mm-dd-yy':
                $new_format = 'm-d-Y';
                break;

            case 'MM d, yy':
                $new_format = 'F d, Y';
                break;

            case 'd M, y':
                $new_format = 'd M, y';
                break;

            case 'd MM, y':
                $new_format = 'd F, y';
                break;

            case 'DD, d MM, yy':
                $new_format = 'l, d F, Y';
                break;

            default:
                $new_format = 'm/d/Y';
                break;
        }

        if(function_exists('date_create_from_format')){
            $date = date_create_from_format($new_format, $date);
            $date = date_format($date, 'm/d/Y');
        }
        
        return $date;
    }

}

if (!function_exists('upme_date_format_to_custom')) {

    function upme_date_format_to_custom($date, $format) {

        switch ($format) {
            case 'mm/dd/yy':
                $date = new DateTime($date);
                $datetime = $date->format("m/d/Y");
                break;

            case 'yy/mm/dd':
                $date = new DateTime($date);
                $datetime = $date->format("Y/m/d");
                break;

            case 'dd/mm/yy':
                $date = new DateTime($date);
                $datetime = $date->format("d/m/Y");
                break;

            case 'yy-mm-dd':
                $date = new DateTime($date);
                $datetime = $date->format("Y-m-d");
                break;

            case 'dd-mm-yy':
                $date = new DateTime($date);
                $datetime = $date->format("d-m-Y");
                break;

            case 'mm-dd-yy':
                $date = new DateTime($date);
                $datetime = $date->format("m-d-Y");
                break;

            case 'MM d, yy':
                $date = new DateTime($date);
                $datetime = $date->format("F j, Y");
                break;

            case 'd M, y':
                $date = new DateTime($date);
                $datetime = $date->format("j M, y");
                break;

            case 'd MM, y':
                $date = new DateTime($date);
                $datetime = $date->format("j F, y");
                break;

            case 'DD, d MM, yy':
                $date = new DateTime($date);
                $datetime = $date->format("l, j F, Y");
                break;

            default:
                $date = new DateTime($date);
                $datetime = $date->format("m/d/Y");
                break;
        }

        return $datetime;
    }

}


if (!function_exists('upme_get_uploads_folder_details')) {

    function upme_get_uploads_folder_details() {

        // Checking for valid uploads folder
        if (!( $upload_dir = wp_upload_dir() ))
            return false;

        return $upload_dir;
    }

}

if (!function_exists('upme_manage_string_for_meta')) {

    function upme_manage_string_for_meta($string='') {
        $badChars = array(' ', ',', '$', '&', '\'', ':', '<', '>', '[', ']', '{', '}', '#', '%', '@', '/', ';', '=', '?', '\\', '^', '|', '~', '(', ')', '"', '.');
        $string = str_replace($badChars, '_', trim($string));
        $string = trim($string, '_');
        $string = str_replace('___', '_', trim($string));
        $string = str_replace('__', '_', trim($string));
        return strtolower($string);
    }

}

if (!function_exists('upme_update_user_cache')) {

    function upme_update_user_cache($user_id) {
        global $wpdb;

        $meta_values_query = "SELECT meta_key, meta_value FROM " . $wpdb->usermeta . " WHERE meta_key!='_upme_search_cache' AND user_id=" . esc_sql($user_id);

        $meta_data = $wpdb->get_results($meta_values_query, 'ARRAY_A');


        $profile_fields = get_option('upme_profile_fields');

        $upme_fields_meta = array();
        foreach ($profile_fields as $key => $value) {
            if ($value['type'] == 'usermeta') {

                $upme_fields_meta[] = $value['meta'];
            }
        }

        $search_cache = array();

        foreach ($meta_data as $k => $v) {
            if ($v['meta_key'] == $wpdb->get_blog_prefix() . "capabilities") {
                $roles = unserialize($v['meta_value']);
                foreach ($roles as $role_key => $role_value) {
                    $search_cache[] = 'role::' . $role_key;
                }
            } else {

                if (in_array($v['meta_key'], $upme_fields_meta)) {
                    if ($v['meta_value'] == '' || $v['meta_value'] == '0') {
                        $search_cache[] = $v['meta_key'] . '::' . $v['meta_value'];
                    } else {
                        $multi_data = explode(',', $v['meta_value']);
                        foreach ($multi_data as $data_key => $data_value) {
                            $search_cache[] = $v['meta_key'] . '::' . trim($data_value);
                        }
                    }
                }
            }
        }

        $search_cache_string = '';
        $search_cache_string = implode('||', $search_cache);

        update_user_meta($user_id, '_upme_search_cache', $search_cache_string);
    }

}

if (!function_exists('upme_cron_user_cache')) {

    function upme_cron_user_cache() {
        global $wpdb;

        $current_option = get_option('upme_options');

        // Execute Only if set to yes
        if (isset($current_option['use_cron']) && $current_option['use_cron'] == '1') {
            $last_processed_user = get_option('upme_cron_processed_user_id');
            if ($last_processed_user == '') {
                $last_processed_user = 0;
            }

            $limit = 25;

            $user_query = "SELECT ID FROM " . $wpdb->users . " WHERE ID>'" . esc_sql($last_processed_user) . "' ORDER BY ID ASC LIMIT " . $limit;

            $users = $wpdb->get_results($user_query, 'ARRAY_A');

            $count = 0;
            foreach ($users as $key => $value) {
                upme_update_user_cache($value['ID']);

                update_option('upme_cron_processed_user_id', $value['ID']);

                $count++;
            }

            // All users completed, so resetting value to 0
            if ($count < $limit) {
                update_option('upme_cron_processed_user_id', '0');
            }
        }
    }

}

if (!function_exists('upme_activation')) {

    function upme_activation() {
        if (!wp_next_scheduled('upme_process_cache_cron')) {
            wp_schedule_event(time(), 'hourly', 'upme_process_cache_cron');
        }
    }

}

if (!function_exists('upme_deactivation')) {

    function upme_deactivation() {
        wp_clear_scheduled_hook('upme_process_cache_cron');
    }

}

if (!function_exists('upme_video_url_customizer')) {

    function upme_video_url_customizer($url) {
        $url_parts = parse_url($url);
        if ($url_parts) {
            $host = isset($url_parts['host']) ? $url_parts['host'] : '';
            $query = isset($url_parts['query']) ? $url_parts['query'] : '';
            $path = isset($url_parts['path']) ? $url_parts['path'] : '';
            $player_url = '';
            if ('www.youtube.com' == $host) {
                $player_url = upme_youtube_url_customizer($query);
            } else if ('vimeo.com' == $host) {
                $player_url = upme_vimeo_url_customizer($path);
            } else if('youtu.be' == $host){
                $player_url = upme_youtube_short_url_customizer($path);
            }
            return $player_url;
        } else {
            return false;
        }
    }

}

if (!function_exists('upme_vimeo_url_customizer')) {

    function upme_vimeo_url_customizer($path) {
        $player_url = '//player.vimeo.com/video' . $path;
        return $player_url;
    }

}


if (!function_exists('upme_youtube_url_customizer')) {

    function upme_youtube_url_customizer($query) {

        $query_parts = explode('=', $query);
        $video_str = isset($query_parts[1]) ? $query_parts[1] : '';
        $player_url = '//www.youtube.com/embed/' . $video_str;
        return $player_url;
    }
}

if (!function_exists('upme_video_type_css')) {

    function upme_video_type_css($url) {
        $url_parts = parse_url($url);
        $player_details = array();
        $player_details['height'] = '281';
        $player_details['width'] = '500';
        if ($url_parts) {
            $host = isset($url_parts['host']) ? $url_parts['host'] : '';

            if ('www.youtube.com' == $host) {
                $player_details['height'] = '315';
                $player_details['width'] = '560';
            } else if ('vimeo.com' == $host) {
                $player_details['height'] = '281';
                $player_details['width'] = '500';
            }
            return $player_details;
        } else {
            return $player_details;
        }
    }

}


if (!function_exists('upme_new_user_notification')) {

    function upme_new_user_notification($user_id, $plaintext_pass = '', $activation_status = '', $activation_code = '') {

        $user = new WP_User($user_id);

        $user_login = stripslashes($user->user_login);
        $user_email = stripslashes($user->user_email);

        $message  = sprintf(__('New user registration on %s:','upme'), get_option('blogname')) . "\r\n\r\n";
        $message .= sprintf(__('Username: %s','upme'), $user_login) . "\r\n\r\n";
        $message .= sprintf(__('E-mail: %s','upme'), $user_email) . "\r\n";

        /* UPME Filter for customizing new user activation email content for admins */
        $message  = apply_filters('upme_new_user_act_admin_content',$message,$user_login,$user_email);
        // End Filter

        $subject  = sprintf(__('[%s] New User Registration','upme'), get_option('blogname'));
        /* UPME Filter for customizing new user activation email subject for admins  */
        $subject  = apply_filters('upme_new_user_act_admin_subject',$subject);
        // End Filter
                        

        @wp_mail(
                        get_option('admin_email'),
                        $subject,
                        $message
        );

        if (empty($plaintext_pass))
            return;

        if (!empty($activation_status) && 'INACTIVE' == $activation_status) {

            $current_option = get_option('upme_options');
            $link = get_permalink($current_option['profile_page_id']);

            $query_str = "upme_action=upme_activate&upme_id=" . $user_id . "&upme_activation_code=" . $activation_code;
            $activation_link = upme_add_query_string($link, $query_str);


            $message = sprintf(__("Someone (hopefully you) has used this email to register at %s :",'upme'), get_option('blogname')) . "\r\n\r\n";
            $message .= __('Please click the link below to verify your ownership of this email:','upme') . "\r\n\r\n";
            $message .= __('You will not be able to log in to use your account until you do so:','upme') . "\r\n\r\n";
            $message .= sprintf(__('%s'), $activation_link, $activation_link) . "\r\n\r\n";
            $message .= __('Thanks','upme') . "\r\n";
            $message .= sprintf(__('%s'), get_option('blogname'),'upme') . "\r\n";

            /* UPME Filter for customizing new user activation email content for users  */
            $message  = apply_filters('upme_new_user_act_content',$message,$user_login,$user_email,$activation_link);
            // End Filter

            $subject = sprintf(__('[%s] Action Required: Email Verification','upme'), get_option('blogname'));                    
            /* UPME Filter for customizing new user activation email content for users  */
            $subject  = apply_filters('upme_new_user_act_subject',$subject);
            // End Filter

            wp_mail(
                    $user_email,
                    $subject,
                    $message
            );
        } 
    }

}

if (!function_exists('upme_add_query_string')) {

    function upme_add_query_string($link, $query_str) {

        $build_url = $link;

        $query_comp = explode('&', $query_str);

        foreach ($query_comp as $param) {
            $params = explode('=', $param);
            $key = isset($params[0]) ? $params[0] : '';
            $value = isset($params[1]) ? $params[1] : '';
            $build_url = add_query_arg($key, $value, $build_url);
        }

        return $build_url;
    }

}


if (!function_exists('upme_date_picker_setting')) {

    function upme_date_picker_setting() {
        // Set date format from admin settings
        $upme_settings = get_option('upme_options');
        $upme_date_format = (string) isset($upme_settings['date_format']) ? $upme_settings['date_format'] : 'mm/dd/yy';

        $date_picker_array = array(
            'closeText' => 'Done',
            'prevText' => 'Prev',
            'nextText' => 'Next',
            'currentText' => 'Today',
            'monthNames' => array(
                'Jan' => 'January',
                'Feb' => 'February',
                'Mar' => 'March',
                'Apr' => 'April',
                'May' => 'May',
                'Jun' => 'June',
                'Jul' => 'July',
                'Aug' => 'August',
                'Sep' => 'September',
                'Oct' => 'October',
                'Nov' => 'November',
                'Dec' => 'December'
            ),
            'monthNamesShort' => array(
                'Jan' => 'Jan',
                'Feb' => 'Feb',
                'Mar' => 'Mar',
                'Apr' => 'Apr',
                'May' => 'May',
                'Jun' => 'Jun',
                'Jul' => 'Jul',
                'Aug' => 'Aug',
                'Sep' => 'Sep',
                'Oct' => 'Oct',
                'Nov' => 'Nov',
                'Dec' => 'Dec'
            ),
            'dayNames' => array(
                'Sun' => 'Sunday',
                'Mon' => 'Monday',
                'Tue' => 'Tuesday',
                'Wed' => 'Wednesday',
                'Thu' => 'Thursday',
                'Fri' => 'Friday',
                'Sat' => 'Saturday'
            ),
            'dayNamesShort' => array(
                'Sun' => 'Sun',
                'Mon' => 'Mon',
                'Tue' => 'Tue',
                'Wed' => 'Wed',
                'Thu' => 'Thu',
                'Fri' => 'Fri',
                'Sat' => 'Fri'
            ),
            'dayNamesMin' => array(
                'Sun' => 'Su',
                'Mon' => 'Mo',
                'Tue' => 'Tu',
                'Wed' => 'We',
                'Thu' => 'Th',
                'Fri' => 'Fr',
                'Sat' => 'Sa'
            ),
            'weekHeader' => 'Wk',
            'dateFormat' => $upme_date_format,
            'yearRange'  => '1920:2014'
        );

        /* UPME Filter for customizing date picker settings */
        $date_picker_array = apply_filters('upme_datepicker_settings', $date_picker_array);
        // End Filter

        return $date_picker_array;
    }

}

if(!function_exists('upme_default_socail_links')) {
    function upme_default_socail_links() {
        add_filter('upme_social_url_user_email', 'upme_format_email_link');
        add_filter('upme_social_url_twitter', 'upme_format_twitter_link');
        add_filter('upme_social_url_facebook', 'upme_format_facebook_link');
        add_filter('upme_social_url_googleplus', 'upme_format_google_link');
    }
}

// Hooking default social url
upme_default_socail_links();


if(!function_exists('upme_format_email_link')) {
    function upme_format_email_link($content){
        return 'mailto:'.$content;
    }
}

if(!function_exists('upme_format_twitter_link')) {
    function upme_format_twitter_link($content){
        return 'http://twitter.com/'.$content;
    }
}

if(!function_exists('upme_format_facebook_link')) {
    function upme_format_facebook_link($content){
        return 'http://www.facebook.com/'.$content;
    }
}

if(!function_exists('upme_format_google_link')) {
    function upme_format_google_link($content){
        return 'https://plus.google.com/'.$content;
    }
}

if (!function_exists('upme_sound_cloud_player')) {

    function upme_sound_cloud_player($url){
        $width = '100%';

        $soundcloud_player_url = 'https://w.soundcloud.com/player/?url='.$url;

        $soundcloud_params = array('color' => 'ff5500',
                                    'auto_play' =>  'false',
                                    'hide_related' => 'false',
                                    'show_artwork' => 'false'
                                );

        $height = (preg_match('/^(.+?)\/(sets|groups|playlists)\/(.+?)$/', $soundcloud_player_url) ) ? '400px' : '150px';

        $soundcloud_player_url = add_query_arg( $soundcloud_params, $soundcloud_player_url);

        return sprintf('<iframe width="%s" height="%s" scrolling="no" frameborder="no" src="%s"></iframe>', $width, $height, $soundcloud_player_url);
    }

}

if (!function_exists('upme_youtube_short_url_customizer')) {

    function upme_youtube_short_url_customizer($path) {
        $player_url = '//www.youtube.com/embed' . $path;
        return $player_url;
    }
}

