<?php 
  if (!class_exists('wpdreamsCompatibility')) {
    class wpdreamsCompatibility {
    
      public static function Instance() {
          static $inst = null;
          if ($inst === null) {
              $inst = new wpdreamsCompatibility();
          }
          return $inst;
      }
      
      function __construct() {
        $this->errorNum = 0;
        $this->errors = array();
        $this->consequences = array();
        $this->solutions = array();
      }  
      
      function has_errors() {
        return (count($this->errors)>0?true:false);
      }       
      
      function get_last_error() {
        if ($this->has_errors()) {
          return array(end($this->errors), end($this->consequences), end($this->solutions));
        }
        return false;
      }
      
      function get_errors() {
        if ($this->has_errors()) {
          return array('errors'=>$this->errors,'cons'=>$this->consequences, 'solutions'=>$this->solutions);
        }
        return false;
      }
           
      function check_dir_w($path, $cons = '') {
        $writeable = is_writeable($path);
        if ($writeable===false) {
          $this->errors[] = "The <b>".$path."</b> directory is not writeable!";
          $this->consequences[] = $cons;
          $this->solutions[] = "
            Use an ftp clien to chmod (change permissions) the <b>".$path."</b> directory to 666, 755, or 777<br />
            Read <a href='http://www.siteground.com/tutorials/ftp/ftp_chmod.htm'>this siteground</a> article if you need help.
          ";
        }
        return $writeable;
      }
      
      function can_open_url($cons='') {
        if (function_exists('curl_init')){
          return true;
        } else if (ini_get('allow_url_fopen')==true) {
          return true;
        }
        $this->errors[] = "Curl and url fopen is disabled on this server!";
        $this->consequences[] = $cons;
        $this->solutions[] = "You might need to contact the server administrator to resolve this issue for you.";
        return false;
      }          
    }
  }  