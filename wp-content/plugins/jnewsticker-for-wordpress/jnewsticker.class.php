<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class jNewsticker {       
    
    /**
     * The stored value in jnewsticker in wp_options
     * @var array
     */
    protected $settings = array();
    
    /**
     * Newsticker settings
     * @var array
     */
    protected $ticker_settings = array();
    
    /**
     * Newsticker ID
     * @var int
     */
    protected $ticker_id;
    
    /**
     * Data sources
     * @var array
     */
    protected $data_sources = array();
    
    /**
     * Create jNewsticker instance
     * @return void
     */
    public function __construct( $ticker_id = '' ) {
        $this->update_ticker_settings();        
        
        if ( strlen($ticker_id) ) {
            $this->ticker_id = $ticker_id;
            $this->ticker_settings = $this->get_ticker_settings();
        }
    }                
    
    /**
     * Return the newsticker's settings
     * @return array
     */
    public function get_ticker_settings() {    
        return isset( $this->settings['tickers'][$this->ticker_id] ) ? $this->settings['tickers'][$this->ticker_id] : array();
    }
    
    /**
     * Return the ticker ID
     * @return int
     */
    public function get_ticker_id() {
        return $this->ticker_id;
    }

    /**
     * Return the newsticker's data sources
     * @return array
     */
    public function get_ticker_data_sources() {
        return isset( $this->settings['tickers'][$this->ticker_id]['data_sources'] ) ? $this->settings['tickers'][$this->ticker_id]['data_sources'] : array();
    }

    /**
     * Update newsticker's data sources
     * @return void
     */
    public function update_ticker_settings() {
        $this->settings = jNewsticker_Bootstrap::get_settings();
        $this->data_sources = jNewsticker_Bootstrap::get_data_sources();
    }

    /**
     * Delete a newsticker
     * @static
     * @return void
     */
    public static function delete_ticker( $ticker_id ) {
        $settings = jNewsticker_Bootstrap::get_settings();
        if ( isset( $settings['tickers'][$ticker_id] ) ) {
            unset( $settings['tickers'][$ticker_id]);
        }

        return update_option( 'jnewsticker', $settings );
    }

    /**
     * Save a newsticker
     * @param int $ticker_id
     * @param array $ticker_data
     * @return bool
     */
    public function save_ticker_settings($ticker_settings) {    
        if ( isset($this->ticker_id) ) {
            $this->settings['tickers'][$this->ticker_id] = apply_filters( 'jnewsticker_ticker_settings', $ticker_settings, $this->ticker_id );
            do_action('jnewsticker_save_settings', $this->ticker_id, $ticker_settings);
        }
        else {            
            $this->settings['tickers'][] = apply_filters( 'jnewsticker_ticker_settings', $ticker_settings, null);
            do_action('jnewsticker_save_settings', false, $ticker_settings);
        }        
        return update_option('jnewsticker', $this->settings);
    }    
    
    /**
     * Return value of newsticker setting
     * @param string $key
     * @return string
     */
    public function get_value($key) {
        if (isset( $this->ticker_id )) {
            $settings = $this->get_ticker_settings();            
            return isset( $settings[$key] ) ? $settings[$key] : '';
        }
        return $this->get_default($key);
    }
    
    /**
     * Return value of newsticker setting for data source
     * @param string $key
     * @param string $data_source
     * @param array $args
     * @return string
     */
    public function get_source_value($key, $data_source, $args = array()) {

        $defaults = array(
            'default' => '',
            'escape_html' => false,
            'instance_count' => false,
            'is_checkbox' => false,
            'is_array' => false
        );

        extract( wp_parse_args( $args, $defaults ) );

        if (isset( $this->ticker_id )) {
            $data_sources = $this->get_ticker_data_sources();            
            if ( $instance_count === false ) {
                
                if (isset( $data_sources[$data_source][$key] )) {                               
                    if ( $is_array ) {
                        return is_array( $data_sources[$data_source][$key] ) ? $data_sources[$data_source][$key] : array( $data_sources[$data_source][$key] );
                    }

                    $value = stripslashes( $data_sources[$data_source][$key] );
                    return $escape_html === true ? htmlspecialchars( $value ) : $value;
                }

                return $is_checkbox === false ? $default : false;
            }
            else {                 
                if ( isset( $data_sources[$data_source]['instance'][$instance_count][$key] ) ) {

                    if ( $is_array ) {
                        return is_array( $data_sources[$data_source]['instance'][$instance_count][$key] ) 
                            ? $data_sources[$data_source]['instance'][$instance_count][$key] 
                            : array( $data_sources[$data_source]['instance'][$instance_count][$key] );
                    }

                    $value = stripslashes( $data_sources[$data_source]['instance'][$instance_count][$key] );                    
                    return $escape_html === true ? htmlspecialchars( $value ) : $value;
                }                
                
                return $is_checkbox === false ? $default : false;                
            }
        }
        return $default;
    }
    
    /**
     * Return default value for given key
     * @param string $key
     * @return string
     */
    public function get_default($key) {
        switch ($key) {
            case 'showControls':
                return true;
                break;
            case 'autoStart':
                return true;
                break;
            case 'sortBy':
                return 'timestamp';
                break;
            case 'timeAgoFormat':
                return '';
                break;
            case 'dateFormat':
                return '';
                break;
            case 'pauseOnHover': 
                return true;
                break;
            case 'fadeFadeOutSpeed':
                return 400;
                break;
            case 'fadeFadeInSpeed':
                return 400;
                break;
            case 'fadeTransitionSpeed':
                return 4000;
                break;
            case 'revealLetterRevealSpeed':
                return 70;
                break;
            case 'revealTransitionSpeed':
                return 2000;
                break;
            case 'scrollScrollSpeed':
                return 50;
                break;
            case 'scrollSlideSpeed':
                return 1000;
                break;
            case 'scrollSlideEasing':
                return 'swing';
                break;
            default:
                return '';
                break;
        }
    }
    
    /**
     * Form for creating or updating a newsticker
     * @return void
     */
    public function display_form() {
        $newsticker = isset( $this->ticker_id ) ? $this->get_ticker_settings() : null;
        $is_update = $_GET['action'] === 'create' ? false : true;
        ?>

        <?php if ( isset( $_GET['m'] ) ) : ?>
            <div class="<?php echo in_array( $_GET['m'], array( 'ticker_update_failed' ) ) ? 'error' : 'updated' ?>">
                <p>                            
                <?php switch ( $_GET['m'] ) : 
                    case 'ticker_updated' :
                        _e( 'News ticker has been successfully updated', 'jnews' );
                        break;

                    case 'ticker_update_failed' :
                        _e( sprintf( '%sOops%s. Something went wrong while updating the newsticker.', '<strong>', '</strong>') , 'jnews' );
                        break;
                endswitch ?>
                </p>
            </div>
        <?php endif ?>

        <p><a href="<?php echo admin_url('options-general.php?page=jnewsticker') ?>" class="button-secondary"><?php _e('View all news tickers', 'jnews') ?></a></p>

        <?php if ( $is_update ) : ?>

            <div id="newsticker_preview">
                <div class="title_wrap">
                    <h3><?php _e( 'Preview', 'jnews' ) ?></h3>
                </div>
                <?php $this->display() ?>                
            </div>            

        <?php endif ?>

        <div class="title_wrap">
            <h3><?php $is_update ? _e('Update newsticker', 'jnews') : _e('Create a newsticker', 'jnews') ?></h3>
        </div>

        <form method="post" action="<?php echo admin_url('options-general.php?page=jnewsticker') ?>" class="newsticker_form">
            <?php wp_nonce_field('save_jnewsticker', 'jnewsticker') ?>
            <?php if ( isset($this->ticker_id) ) : ?>
                <input type="hidden" name="tickerId" value="<?php echo $this->ticker_id ?>" />
            <?php endif ?>
            
            <h4><?php _e('General Settings', 'jnews') ?></h4>
            <div>
                <label for="jnews_internal_title"><?php _e('Internal Title', 'jnews') ?></label>
                <input type="text" id="jnews_internal_title" name="internalTitle" value="<?php echo $this->get_value('internalTitle') ?>" />
                <span class="jnews_assist"><?php _e('The internal title for the newsticker. This is how you will identify it in the admin area.', 'jnews') ?></span>              
            </div>               
            <div>
                <label for="jnews_ticker_title"><?php _e('Title on ticker', 'jnews') ?></label>
                <input type="text" id="jnews_ticker_title" name="tickerTitle" value="<?php echo $this->get_value('tickerTitle') ?>" />
                <span class="jnews_assist"><?php _e('The title for the newsticker. Leave blank for no title.', 'jnews') ?></span>              
            </div>          
            <div>
                <?php $value = $this->get_value('style') ?>
                <label for="jnews_style"><?php _e('Animation', 'jnews') ?></label>
                <select id="jnews_style" name="style">
                    <option value="fade" <?php echo $value == 'fade' ? 'selected' : '' ?>><?php _e('Fade', 'jnews') ?></option>
                    <option value="scroll" <?php echo $value == 'scroll' ? 'selected' : '' ?>><?php _e('Scroll', 'jnews') ?></option>
                    <option value="reveal" <?php echo $value == 'reveal' ? 'selected' : '' ?>><?php _e('Reveal', 'jnews') ?></option>
                </select>
                <span class="jnews_assist"><?php _e('The type of animation the newsticker uses.', 'jnews') ?></span>
            </div>
            <div>
                <label for="jnews_show_controls"><?php _e('Display controls', 'jnews') ?></label>
                <input type="checkbox" name="showControls" id="jnews_show_controls" <?php if ($this->get_value('showControls')) echo 'checked' ?> />
                <span class="jnews_assist"><?php _e('Whether to display controls for manually pausing, resuming 
                  and moving to next and previous news items.', 'jnews') ?></span>
            </div>
            <div>
                <label for="jnews_autostart"><?php _e('Start animation automatically', 'jnews') ?></label>
                <input type="checkbox" name="autoStart" id="jnews_autostart" <?php if ($this->get_value('autoStart')) echo 'checked' ?> />
                <span class="jnews_assist"><?php _e('Whether animation should start automatically when the newsticker 
                  is loaded.', 'jnews') ?></span>
            </div>
            <div>
                <?php $value = $this->get_value('sortBy') ?>
                <label for="jnews_sort_by"><?php _e('Sort news items', 'jnews') ?></label>
                <select id="jnews_sort_by" name="sortBy">
                    <option value="timestamp" <?php echo $value == 'timestamp' ? 'selected' : '' ?>><?php _e('By date posted (newest items shown first)', 'jnews') ?></option>
                    <option value="timestamp_reverse" <?php echo $value == 'timestamp_reverse' ? 'selected' : '' ?>><?php _e('By date posted (oldest items shown first)', 'jnews') ?></option>
                    <option value="random" <?php echo $value == 'random' ? 'selected' : '' ?>><?php _e('Random', 'jnews') ?></option>
                </select>
                <span class="jnews_assist"><?php _e('How to sort news items.', 'jnews') ?></span>
            </div>
            <div>
                <label for="jnews_pause_on_hover"><?php _e('Pause on hover', 'jnews') ?></label>
                <input type="checkbox" id="jnews_pause_on_hover" name="pauseOnHover" <?php if ($this->get_value('pauseOnHover')) echo 'checked'; ?>  />
                <span class="jnews_assist"><?php _e('If checked, the animation will pause when the user hovers over the newsticker.', 'jnews') ?></span>
            </div>          
            <div class="hidden jnews_fade_settings jnews_animation_settings">                
                <h4><?php _e('Fade Animation Settings', 'jnews') ?></h4>                
                <div>
                    <label for="jnews_fade_fade_out_speed"><?php _e('Fade out speed', 'jnews') ?></label>
                    <input type="number" id="jnews_fade_fade_out_speed" name="fadeFadeOutSpeed" value="<?php echo $this->get_value('fadeFadeOutSpeed') ?>" />
                    <span class="jnews_assist"><?php _e('How fast a news item fades out, in milliseconds.', 'jnews') ?></span>
                </div>
                
                <div>
                    <label for="jnews_fade_fade_in_speed"><?php _e('Fade in speed', 'jnews') ?></label>
                    <input type="number" id="jnews_fade_fade_in_speed" name="fadeFadeInSpeed" value="<?php echo $this->get_value('fadeFadeInSpeed') ?>" />
                    <span class="jnews_assist"><?php _e('How fast a news item fades in, in milliseconds.', 'jnews') ?></span>
                </div>
                
                <div>
                    <label for="jnews_fade_transition_speed"><?php _e('Speed of transition', 'jnews') ?></label>
                    <input type="number" id="jnews_fade_transition_speed" name="fadeTransitionSpeed" value="<?php echo $this->get_value('fadeTransitionSpeed') ?>" />
                    <span class="jnews_assist"><?php _e('Length of time in milliseconds a news item is displayed before the next one.', 'jnews') ?></span>
                </div>                
            </div>

            <div class="hidden jnews_reveal_settings jnews_animation_settings">
                <h4><?php _e('Reveal Animation Settings', 'jnews') ?></h4>

                <div>
                    <label for="jnews_reveal_letter_reveal_speed"><?php _e('Speed at which letters are revealed', 'jnews') ?></label>
                    <input type="number" id="jnews_reveal_letter_reveal_speed" name="revealLetterRevealSpeed" value="<?php echo $this->get_value('revealLetterRevealSpeed') ?>" />
                    <span class="jnews_assist"><?php _e('How fast letters are revealed, in milliseconds.', 'jnews') ?></span>                    
                </div>
                
                <div>
                    <label for="jnews_reveal_transition_speed"><?php _e('Speed of transition', 'jnews') ?></label>
                    <input type="number" id="jnews_reveal_transition_speed" name="revealTransitionSpeed" value="<?php echo $this->get_value('revealTransitionSpeed') ?>" />
                    <span class="jnews_assist"><?php _e('Length of time in milliseconds a news item is displayed before the next one.', 'jnews') ?></span>                    
                </div>                
            </div>

            <div class="hidden jnews_scroll_settings jnews_animation_settings">
                <h4><?php _e('Scroll Animation Settings', 'jnews') ?></h4>
                
                <div>
                    <label for="jnews_scroll_scroll_speed"><?php _e('Speed of scroll', 'jnews') ?></label>
                    <input type="text" id="jnews_scroll_scroll_speed" name="scrollScrollSpeed" value="<?php echo $this->get_value('scrollScrollSpeed') ?>" />
                    <span class="jnews_assist"><?php _e('Speed in milliseconds at which the scrolling action occurs.', 'jnews') ?></span>                        
                </div>
                <div>
                    <label for="jnews_scroll_slide_speed"><?php _e('Speed of slide', 'jnews') ?></label>
                    <input type="text" id="jnews_scroll_slide_speed" name="scrollSlideSpeed" value="<?php echo $this->get_value('scrollSlideSpeed') ?>" />
                    <span class="jnews_assist"><?php _e('Speed in milliseconds at which the slide between news items should occur.', 'jnews') ?></span>
                </div>
                <div>
                    <label for="jnews_scroll_slide_easing"><?php _e('Easing function', 'jnews') ?></label>
                    <input type="text" id="jnews_scroll_slide_easing" name="scrollSlideEasing" value="<?php echo $this->get_value('scrollSlideEasing') ?>" />
                    <span class="jnews_assist"><?php _e('The easing function used to slide between news items. jQuery comes with two available easing functions: <em>swing</em> and <em>linear</em>.', 'jnews') ?></span>
                </div>                
            </div>
            
            <h4><?php _e('Data Sources', 'jnews') ?></h4>
            
            <?php foreach ( $this->data_sources as $class_name ) : ?>
            
                <?php $source = new $class_name() ?>
            
                <div class="jnews_data_source">
                    <label for="<?php echo $source->get_source_id() ?>"><?php echo $source->get_source_title() ?></label>                    
                    <input type="checkbox" id="<?php echo $source->get_source_id() ?>" class="enable" name="data_sources[<?php echo $source->get_source_name() ?>][enable]" <?php if ($this->get_source_value('enable', $source->get_source_name(), '', true) == 'on') echo 'checked' ?> />
                    <?php if ( strlen($source->get_source_assist()) ) : ?>
                        <span class="jnews_assist"><?php echo $source->get_source_assist() ?></span>
                    <?php endif ?>

                    <?php if ( $source->allows_extra() && isset( $this->ticker_id ) && array_key_exists( 'instance', $this->settings['tickers'][$this->ticker_id]['data_sources'][$class_name] )) : ?>
                        <?php foreach ( $this->settings['tickers'][$this->ticker_id]['data_sources'][$class_name]['instance'] as $instance ) : ?>
                            <?php $source->display_form( $this ) ?> 
                        <?php endforeach ?>
                    <?php else : ?>
                        <?php $source->display_form( $this ) ?>
                    <?php endif ?>

                    <?php if ( $source->allows_extra() === true ) : ?>
                        <a href="#" class="add_data_source button hidden" data-source="<?php echo $class_name ?>" data-ticker_id="<?php echo $this->ticker_id ?>"><?php echo $source->get_extra_instance_text() ?></a>
                    <?php endif ?>
                        
                </div>

            <?php endforeach ?>
            
            <div class="button-container">
                <input type="submit" value="<?php _e('Save jNewsticker') ?>" class="button-primary" name="save_jnewsticker" />
            </div>

        </form>

        <?php if ( $is_update ) : ?>

            <div class="title_wrap">
                <h3><?php _e( 'Integration', 'jnews' ) ?></h3>
            </div>
            <h4><?php _e( 'Widget', 'jnews' ) ?></h4>
            <p><?php _e( sprintf( 'Add the <strong>Newsticker</strong> widget to a widget-ready area and select <strong>%s</strong>.',  $this->get_value('internalTitle') ), 'jnews' ) ?></p>
            <h4><?php _e( 'Shortcode', 'jnews' ) ?></h4>
            <code>[newsticker id=<?php echo $this->ticker_id ?>]</code>
            <h4><?php _e( 'PHP', 'jnews' ) ?></h4>
            <code><?php echo htmlspecialchars( sprintf( "<?php jnewsticker_display( %d ) ?>", $this->ticker_id ) ) ?></code>

        <?php endif; 
    }
    
    /**
     * Save newsticker after form submission
     * @static
     * @return void
     */
    public static function save_from_post() {
        if (wp_verify_nonce($_POST['jnewsticker'], 'save_jnewsticker')) {
            $submitted = $_POST;
            $ticker_id = isset($_POST['tickerId']) && strlen($_POST['tickerId']) ? $_POST['tickerId'] : null;
            unset(
                    $submitted['jnewsticker'], 
                    $submitted['_wp_http_referer'], 
                    $submitted['save_jnewsticker'],
                    $submitted['tickerId']
            );

            $jNewsticker = is_null($ticker_id) ? new jNewsticker() : new jNewsticker($ticker_id);
            $jNewsticker->save_ticker_settings($submitted);

            // Redirect with message
            if ( is_null($ticker_id) ) {
                wp_redirect( add_query_arg( 'm', 'ticker_created', 'admin.php?page=jnewsticker' ) );   
            }
            else {
                wp_redirect( add_query_arg( 'm', 'ticker_updated', 'admin.php?page=jnewsticker&action=edit&ticker_id='.$ticker_id ) );
            }
            exit();
        }

        wp_redirect( add_query_arg( 'm', 'ticker_update_failed', 'admin.php?page=jnewsticker'));
        exit();
    }

    /** 
     * Sort items
     * @param array $items
     * @return array
     */ 
    protected function sort_items( array $items ) {
        $sort_option = $this->get_value( 'sortBy');        
        switch ($sort_option) {
            case 'timestamp':
            case 'timestamp_reverse': 
                usort( $items, array( &$this, 'sort_by_time' ));
                break;
            case 'random':
                shuffle( $items );
                break;
        }       
        return $items; 
    }

    /**
     * Sort items by timestamp
     * @return bool
     */
    protected function sort_by_time( $a, $b ) { 
        $a = is_array( $a ) ? $a['jnews_timestamp'] : $a->jnews_timestamp;
        $b = is_array( $b ) ? $b['jnews_timestamp'] : $b->jnews_timestamp;

        if ( $a == $b ) {
            return 0;
        }

        if ( $this->get_value( 'sortBy') == 'timestamp' ) {
            return ( $a < $b ) ? 1 : -1;
        }
        else {
            return ( $a < $b ) ? -1 : 1;
        }
    }

    /**
     * Display newsticker
     * @param bool $echo
     * @return string
     */
    public function display( $echo = true ) {
        $ticker_settings = $this->get_ticker_settings();
        $data_sources = $this->get_ticker_data_sources();        
        $newsticker_id = 'newsticker_'.jNewsticker_Bootstrap::get_ticker_instance();

        // Settings
        $ticker_title = $this->get_value( 'tickerTitle' );
        $ticker_style = $this->get_value( 'style' );
        $show_controls = $this->get_value( 'showControls' ) == 'on' ? 'true' : 'false';
        $pause_on_hover = $this->get_value( 'pauseOnHover' ) == 'on' ? 'true' : 'false';        
        $auto_start = $this->get_value( 'autoStart' ) == 'on' ? 'true' : 'false';
        $fade_fade_out_speed = $ticker_style == 'fade' ? $this->get_value( 'fadeFadeOutSpeed' ) : '';
        $fade_fade_in_speed = $ticker_style == 'fade' ? $this->get_value( 'fadeFadeInSpeed' ) : '';
        $fade_transition_speed = $ticker_style == 'fade' ? $this->get_value( 'fadeTransitionSpeed' ) : '';
        $reveal_letter_reveal_speed = $ticker_style == 'reveal' ? $this->get_value( 'revealLetterRevealSpeed' ) : '';
        $reveal_transition_speed = $ticker_style == 'reveal' ? $this->get_value( 'revealTransitionSpeed' ) : '';
        $scroll_scroll_speed = $ticker_style == 'scroll' ? $this->get_value( 'scrollScrollSpeed' ) : '';        
        $scroll_slide_speed = $ticker_style == 'scroll' ? $this->get_value( 'scrollSlideSpeed' ) : '';
        $scroll_slide_easing = $ticker_style == 'scroll' ? $this->get_value( 'scrollSlideEasing' ) : '';

        $list_items = array();
        
        foreach ($data_sources as $class_name => $settings) {            
            if ( isset($settings['enable']) && $settings['enable'] == 'on') {
                $source = new $class_name($this);
                $source_items = $source->get_items();
                // if ($source_items )
                $list_items = array_merge( $list_items, $source->get_items() );
            }
        }
        
        $list_items = $this->sort_items( $list_items );

        // Set up Javascript object settings
        $js_settings = array(
            array( 'tickerTitle', "\"$ticker_title\"" ), 
            array( 'style', "\"$ticker_style\"" ), 
            array( 'pauseOnHover', $pause_on_hover ), 
            array( 'showControls', $show_controls ), 
            array( 'autoStart', $auto_start ),             
        );

        if ( $ticker_style == 'fade' ) {
            $js_settings[] = array( 'fadeOutSpeed', "\"$fade_fade_out_speed\"" );
            $js_settings[] = array( 'fadeInSpeed', "\"$fade_fade_in_speed\"" );
            $js_settings[] = array( 'transitionSpeed', "\"$fade_transition_speed\"" );
        }
        elseif ( $ticker_style == 'scroll' ) {
            $js_settings[] = array( 'scrollSpeed', "\"$scroll_scroll_speed\"" );
            $js_settings[] = array( 'slideSpeed', "\"$scroll_slide_speed\"" );
            $js_settings[] = array( 'slideEasing', "\"$scroll_slide_easing\"" );
        }
        elseif ( $ticker_style == 'reveal' ) {
            $js_settings[] = array( 'letterRevealSpeed', "\"$reveal_letter_reveal_speed\"" );
            $js_settings[] = array( 'transitionSpeed', "\"$reveal_transition_speed\"" );
        }

        // Start output buffering
        ob_start();
        ?>
<script>
(function($) {    
    $(document).ready(function() {
        $('#<?php echo $newsticker_id ?>').newsticker({
            <?php foreach( $js_settings as $i => $setting ) : ?>
                '<?php echo $setting[0] ?>' : <?php echo $setting[1] ?><?php if ($i + 1 < count($js_settings) ) echo ",\r" ?>
            <?php endforeach ?>            
        });
    });    
})( jQuery );
</script>
<ul class="newsticker" id="<?php echo $newsticker_id ?>">
    <?php foreach ($list_items as $item) : ?>
        <?php $output = is_array( $item ) ? $item['jnews_datasource']->display_item( $item ) : $item->jnews_datasource->display_item( $item ) ?>
        <li><?php echo $output ?></li>
    <?php endforeach ?>
</ul>
        <?php 
        $content = ob_get_contents();
        ob_end_clean();

        if ( $echo ) {
            echo $content;
        }

        return $content;
    }
}