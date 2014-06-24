<?php
/**
 * Fulltext creator/checker class for WP by Ernest Marcinko
 *
 * @author Ernest Marcinko 
 * @copyright Ernest Marcinko
 * @version 1.0 
 *
 */    
if (!class_exists('wpdreams_fulltext')) {
  class wpdreams_fulltext {
    private static $singleton_instance = null;
     
    private function construct__() {

    }
     
    public static function getInstance() {
        static $singleton_instance = null;
        if($singleton_instance === null) {
            $singleton_instance = new wpdreams_fulltext();
        }
         
        return($singleton_instance);
    }
    
    
    /**
     * Creates the indexes on the array of (specified table, index name and 
     * columns)
     *          
     * @return boolean true on success, false on failure        
     * @param 
     *      Array(
     *        Array('table'=>'..', 'index'=>'..', 'columns'='..')    
     *      )
     *
     */              
    public function createIndexes($indexes) {
      global $wpdb;
		  /* Temporarily ensure that errors are not displayed: */
  		$previous_value = $wpdb->hide_errors();
      foreach ($indexes as $index) {
    		$wpdb->query("ALTER TABLE ".$wpdb->{$index['table']}." ADD FULLTEXT `".$index['index']."` (".$index['columns'].")");
    		if (!empty($wpdb->last_error)){
            return false;
        }
      }
  		/* Restore previous setting */
  		$wpdb->show_errors($previous_value);
      
      return true;
    }

    
    /**
     * Removes the selected indexes
     *
     * @return boolean true on success, false on failure
     * @param string[] of index names: Array('tablename'=>Array('index1'..), ..);
     *         
     */                    
    public function removeIndexes($indexes) {
      global $wpdb;
		  /* Temporarily ensure that errors are not displayed: */
  		$previous_value = $wpdb->hide_errors();
      foreach ($indexes as $table=>$_indexes) {
        if (is_array($_indexes)) {
           foreach($_indexes as $index) {
              $wpdb->query("ALTER TABLE ".$wpdb->{$table}." DROP INDEX ".$index);
           }
        } else {
           $wpdb->query("ALTER TABLE ".$wpdb->{$table}." DROP INDEX ".$_indexes); 
        }
      }
  		/* Restore previous setting */
  		$wpdb->show_errors($previous_value);
    }
    
    /**
     * Checks if index exists on the given table
     *  
     */              
    public function indexExists($table, $index) {
  		global $wpdb;
  		$wpdb->get_results("SHOW INDEX FROM ".$wpdb->{$table}." WHERE Key_name = '".$index."'");
  		return ($wpdb->num_rows >= 1);    
    }
    
    /**
     * Checks if the array of table has myisam engine enabled.
     * 
     * @param string[] $tables the string array of tables           
     *
     */              
    public function check($tables) {
        if (!is_array($tables)) return false;
        foreach ($tables as $table) {
           if (!$this->myisamEnabled($table)) return false;
        }
        return true;
    }
    
    /**
     * Retrievs the value of 'ft_min_word_len' variable
     *
     */              
    public function getMinWordLength() {
       global $wpdb;
       $previous_value = $wpdb->hide_errors();
       $res = $wpdb->get_row("SHOW VARIABLES LIKE 'ft_min_word_len'");
       $wpdb->show_errors($previous_value);
       return ($res==null)?4:$res->Value;
    }
    
    /**
     * Checks if the given table has myisam enabled.
     * 
     * @param string $table the database table         
     *
     */ 
    private function myisamEnabled($table) {
  		global $wpdb;
  		$tables = $wpdb->get_results("show table status like '".$wpdb->{$table}."'");
  		foreach ($tables as $table) {
  			if ($table->Engine === 'MyISAM')
  				return true;
        else
          return false;
  		}
  		return false;
    }
  }
}
?>