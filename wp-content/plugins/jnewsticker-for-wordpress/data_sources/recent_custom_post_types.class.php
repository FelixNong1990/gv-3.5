<?php
/**
 * Retrieves and formats recent items from one or more custom post types for use in a jNewsticker instance
 * 
 * @author Eric Daams <eric@164a.com>
 */

class jNewsticker_Recent_Custom_Post_Types extends jNewsticker_Recent_Posts {

    /**
     * Instantiate object
     * @return void
     */
    public function __construct($jnewsticker = null) {
        parent::__construct( $jnewsticker );
        $this->id = 'jnews_source_recent_custom_post_types';
        $this->title = __('Recent Posts (Custom post types)', 'jnews');
        $this->name = 'jNewsticker_Recent_Custom_Post_Types';
        $this->assist = __('Displays the most recent items in one or more custom post types', 'jnews');
        $this->variables['title']['description'] = __('Display item title', 'jnews');
        $this->variables['excerpt']['description'] = __('Display item excerpt', 'jnews');
        $this->variables['author']['description'] = __('Display item author', 'jnews');
        $this->variables['link']['description'] = __('Display item URL', 'jnews');
        $this->variables['date']['description'] = __('Display item post date', 'jnews');
        $this->variables['time_ago']['description'] = __('Display the time elapsed since the item was created', 'jnews');
    }
    
    /**
     * Return items for newsticker
     * @return array
     */
    public function get_items() {
        if (is_null($this->jnewsticker)) {
            return;
        }
        
        $data_sources = $this->jnewsticker->get_ticker_data_sources();
        
        if (!isset($data_sources[$this->name])) {
            return;
        }
        
        $settings = $data_sources[$this->name];
        $post_types = $settings['post_types'];
        
        $posts = new WP_Query( array(
            'post_type' => $post_types, 
            'posts_per_page' => isset($settings['count']) ? $settings['count'] : 5
        ));

        return $this->get_item_array( $posts->posts );    
    }
    
    /**
     * Configure specific settings for this data source
     * @return array
     */
    public function get_settings() {
        $settings = array();
        
        // Count
        $settings[] = array(
            'id' => 'count',
            'title' => __('Number of items to display', 'jnews'),
            'type' => 'number',
            'default' => 5
        );
        
        // Post types
        $post_types = get_post_types( array( '_builtin' => false ), 'objects');
        $options = array();        
        foreach ( $post_types as $name => $details ) {
            $options[$name] = $details->labels->name;
        }
        
        $settings[] = array(
            'id' => 'post_types',
            'title' => __('Post types', 'jnews'),
            'type' => 'multi_checkbox', 
            'options' => $options
        );
        
        return $settings;
    }    
    
    /**
     * Return item's post date as timestamp
     * @param stdObject $item
     * @return int
     */
    public function get_item_timestamp( $item ) {
	    return strtotime( $item->post_date );
    }
    
    /**
     * Return post title 
     * @param stdOject $post
     * @return string
     */
    public function get_item_title( $post ) {
        return $post->post_title;
    }
    
    /**
     * Return post title 
     * @param stdOject $post
     * @return string
     */
    public function get_item_author( $post ) {
        $author = new WP_User( $post->post_author );
        return $author->display_name;
    }
    
    /**
     * Return post permalink
     * @param stdOject $post
     * @return string
     */
    public function get_item_url( $post ) {
        return get_permalink( $post->ID );
    }        
    
    /**
     * Return post date
     * @param stdObject $post
     * @return string
     */
    public function get_item_date( $post ) {
        $format = apply_filters( 'jnewsticker_item_date_format', 'M d' );
        return date( $format, strtotime( $post->post_date ) );
    }
}