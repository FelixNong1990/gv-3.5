<?php
/**
 * Allows users to set free form news items
 * 
 * @author Eric Daams <eric@164a.com>
 */

class jNewsticker_Free_Form extends jNewsticker_Data_Source {

	    /**
     * Instantiate object
     * @return void
     */
    public function __construct($jnewsticker = null) {
        parent::__construct( $jnewsticker );        

        $this->id = 'jnews_source_free_form';
        $this->title = __('Free Form News Item', 'jnews');
        $this->name = 'jNewsticker_Free_Form';
        $this->assist = __('Create your own news items', 'jnews');
        $this->allows_extra = true;
        $this->variables = array();
    }
    
    /**
     * Return items for newsticker
     * @return array
     */
    public function get_items() {
        $settings = $this->get_instance_settings();
        return $this->get_item_array( $settings['instance'] );
    }
    
    /**
     * Configure specific settings for this data source
     * @return array
     */
    public function get_settings() {
        $settings = array();

        // Text
        $settings[] = array(
            'id' => 'text',
            'title' => __('Text', 'jnews'),
            'type' => 'text'
        );

        // Date / time
        $settings[] = array(
            'id' => 'date',
            'title' => __('Date', 'jnews'),
            'type' => 'text',
            'class' => 'timepicker'
        );
        
        return $settings;
    }  

    /**
     * Get item timestamp
     */
    public function get_item_timestamp( $item ) {
    	return strtotime( $item['date'] );
    }

    /**
     * Display item
     * @param mixed $item_data
     * @return string
     */
    public function display_item( $item_data ) {
        if ( is_null( $this->jnewsticker ) ) {
            return;
        }
        
        return stripslashes_deep( $item_data['text'] );
    }
}