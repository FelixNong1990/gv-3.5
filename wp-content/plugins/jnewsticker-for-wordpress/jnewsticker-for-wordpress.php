<?php
/*
Plugin Name: jNewsticker
Plugin URI: http://164a.com/jnewsticker/
Description: Spice up your site with jNewsticker. Create as many news tickers as you want and display them using shortcodes, widgets or PHP.
Version: 1.1.12
Author: Eric Daams
Author URI: http://164a.com/
*/

class jNewsticker_Bootstrap {
    
    /**
     * jNewsticker_Bootstrap instance
     * @static
     * @access private
     * @var jNewsticker_Bootstrap|null
     */
    private static $instance = null;
    
    /**
     * The stored value in jnewsticker in wp_options
     * @static
     * @access private
     * @var array
     */
    private static $settings = array();
    
    /**
     * Data sources
     * @static
     * @access private
     * @var array
     */
    private static $data_sources = array();

    /**
     * Skins
     * @static
     * @access private
     * @var array
     */
    private static $skins = array();    

    /**
     * Ticker instance
     * @static
     * @access private
     * @var int
     */
    private static $ticker_instance = 0;
    
    /**
     * Create object. jNewsticker_Bootstrap instance should be retrieved through jNewsticker_Bootstrap::get_instance() method.
     * @access private
     */
    private function __construct() {
        
        // Set up multi-lingualism
        load_plugin_textdomain( 'jnews', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

        // Load newsticker data
        self::$settings = get_option('jnewsticker');
        
        // Include required files
        require_once( 'jnewsticker.class.php' );
        require_once( 'widget.class.php' );
        require_once( 'data_source.class.php' );
        require_once( 'data_sources/recent_posts.class.php' );
        require_once( 'data_sources/recent_comments.class.php' );
        require_once( 'data_sources/recent_custom_post_types.class.php' );
        require_once( 'data_sources/twitter.class.php' );
        require_once( 'data_sources/rss.class.php' );
        require_once( 'data_sources/facebook.class.php' );
        require_once( 'data_sources/free_form.class.php' );
        
        // Register hooks
        register_deactivation_hook( __FILE__, array(&$this, 'deactivation') );
        add_action( 'init', array(&$this, 'init') );
        add_action( 'admin_init', array(&$this, 'admin_init'));
        add_action( 'wp_enqueue_scripts', array(&$this, 'enqueue_scripts') );
        add_action( 'admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts') );
        add_action( 'widgets_init', array(&$this, 'register_widget') );
        add_action( 'admin_menu', array(&$this, 'admin_menu') );
        add_action( 'wp_ajax_create_data_source_instance', array(&$this, 'create_data_source_instance'));

        // Register action hooks
        add_action( 'jnewsticker_save_settings', array('jNewsticker_Data_Source', 'save_ticker_settings' ), 10, 2 );

        // Register filters
        add_filter( 'jnewsticker_ticker_settings', array( 'jNewsticker_Facebook', 'jnewsticker_ticker_settings' ), 10, 2);

        add_filter( 'jnewsticker_item_time_ago_format', array(&$this, 'time_ago_format') );        
        add_filter( 'jnewsticker_string', 'wptexturize' );
        add_filter( 'jnewsticker_string', 'convert_smilies' );
        add_filter( 'jnewsticker_string', 'convert_chars' );
        add_filter( 'jnewsticker_string', 'shortcode_unautop' );
        add_filter( 'jnewsticker_string', 'prepend_attachment' );        

        add_shortcode('newsticker', array(&$this, 'shortcode') );
    }
    
    /**
     * Retrieve object instance
     * @return jNewsticker
     */
    public static function get_instance() {
        if ( is_null(self::$instance) ) {
            self::$instance = new jNewsticker_Bootstrap();
        }
        return self::$instance;
    }

    /**
     * On plugin deactivation, delete jNewsticker fields from wp_options
     */
    public function deactivation() {
        delete_option('jnewsticker');
    }
    
    /**
     * Return jNewsticker settings
     * @static
     * @return array
     */
    public static function get_settings() {
        if ( is_null(self::$instance) ) {
            self::get_instance();
        }
        return self::$settings;
    }

    /**
     * Return Twitter API settings
     * @static
     * @return array
     */
    public static function get_twitter_settings() {
        $settings = self::get_settings();
        return array_key_exists('twitter', $settings) ? $settings['twitter'] : array();
    }
    
    /**
     * Update jNewsticker settigns
     * @static
     * @return void
     */
    public static function update_settings() {
        self::$settings = get_option('jnewsticker');
    }

    /**
     * Get ticker instance
     * @return int
     */
    public static function get_ticker_instance() {
        $ticker_instance = self::$ticker_instance;
        self::$ticker_instance += 1;
        return $ticker_instance;
    }

    /**
     * Return all jNewsticker instances
     * @return array
     * @static
     */
    public static function get_all_tickers() {
        return isset( self::$settings['tickers'] ) ? self::$settings['tickers'] : array();
    }
    
    /**
     * Register a jNewsticker data source
     * @static
     * @param string $class_name
     * @return void
     */
    public static function register_data_source($class_name) {
        if (!in_array($class_name, self::$data_sources)) {
            self::$data_sources[] = $class_name;
        }
    }
    
    /**
     * Return all jNewsticker data sources
     * @static
     * @return array
     */
    public static function get_data_sources() {
        return self::$data_sources;
    }

    /**
     * Register a jNewsticker skin
     * @static
     * @param string $name
     * @param string $src
     * @return void
     */    
    public static function register_skin($name, $src) {
        if (!in_array($name, self::$skins)) {
            self::$skins[$src] = $name;
        }
    }

    /**
     * Return all jNewsticker data sources
     * @static
     * @return array
     */
    public static function get_skins() {
        return self::$skins;
    }    
    
    /**
     * Perform actions on init hook
     * @return void
     */
    public function init() {
        // Register data sources
        jnewsticker_register_data_source('jNewsticker_Free_Form');
        jnewsticker_register_data_source('jNewsticker_Recent_Posts');
        jnewsticker_register_data_source('jNewsticker_Recent_Custom_Post_Types');
        jnewsticker_register_data_source('jNewsticker_Recent_Comments');
        jnewsticker_register_data_source('jNewsticker_Twitter');
        jnewsticker_register_data_source('jNewsticker_Rss');
        jnewsticker_register_data_source('jNewsticker_Facebook');

        // Register skins
        jnewsticker_register_skin('123ecology', plugins_url('media/jnewsticker/skins/123ecology.css', __FILE__));
        jnewsticker_register_skin('Clean Red', plugins_url('media/jnewsticker/skins/clean_red.css', __FILE__));
        jnewsticker_register_skin('Corporate Blue', plugins_url('media/jnewsticker/skins/corporate_blue.css', __FILE__));
        jnewsticker_register_skin('Dark', plugins_url('media/jnewsticker/skins/dark.css', __FILE__));
    }    

    /** 
     * Perform actions on admin init hook
     * @return void
     */
    public function admin_init() {
        if ( isset($_GET['page']) && $_GET['page'] == 'jnewsticker' ) {

            // Saving a news ticker
            if ( isset( $_POST['save_jnewsticker'] ) ) {
                jNewsticker::save_from_post();
            }
            // Deleting a news ticker
            elseif ( isset( $_POST['delete_jnewsticker'] ) ) {
                if ( wp_verify_nonce( $_POST['jnewsticker'], 'delete_jnewsticker' ) && isset( $_POST['ticker_id'] ) ) {
                    jNewsticker::delete_ticker( $_POST['ticker_id'] );
                    wp_redirect( add_query_arg( 'm', 'ticker_deleted', 'options-general.php?page=jnewsticker'));
                    exit();
                }
            }
            // Saving news ticker skin
            elseif ( isset( $_POST['save_jnewsticker_general_settings'] ) ) {
                if ( wp_verify_nonce( $_POST['jnewsticker'], 'save_jnewsticker_general_settings' ) ) {
                    $settings = self::$settings;
                    $settings['skin'] = isset( $_POST['skin'] ) ? $_POST['skin'] : '';
                    $settings['twitter'] = isset( $_POST['twitter'] ) ? $_POST['twitter'] : array();
                    update_option( 'jnewsticker', $settings );
                    wp_redirect( add_query_arg( 'm', 'general_settings_updated', 'options-general.php?page=jnewsticker'));
                    exit();
                }
            }
        }
    }
    
    /**
     * Enqueue news ticker stylesheet and scripts
     * @return void
     */
    public function enqueue_scripts() {
        wp_register_script('jnewsticker', plugins_url('media/jnewsticker/newsticker.jquery.min.js', __FILE__), array('jquery'));
        wp_enqueue_script('jnewsticker');     

        if ( isset( self::$settings['skin'] ) && strlen(self::$settings['skin']) && count( self::get_all_tickers() ) ) {
            wp_register_style('jnewsticker_css', self::$settings['skin'] );
            wp_enqueue_style('jnewsticker_css');
        }
    }    

    /**
     * Enqueue admin-area scripts & stylesheets
     * @param string $hook
     */
    public function admin_enqueue_scripts($hook) {
        if ($hook == 'settings_page_jnewsticker') {
            wp_register_style('jnewsticker-admin-css', plugins_url('media/admin.css', __FILE__));
            wp_enqueue_style('jnewsticker-admin-css');

            // Check if jquery-ui-datepicker and jquery-ui-slider are registered. If not, this is probably an old version of
            // Wordpress, so we need to register them ourselves. Note that we are deliberately loading older versions of these
            // plugins -- the version that was included in Wordpress 3.3, when these scripts were first introduced
            if ( !wp_script_is('jquery-ui-core', 'registered') ) {
                wp_register_script('jquery-ui-core', plugins_url('media/legacy/jquery.ui.core.min.js', __FILE__ ), array('jquery'), '1.8.16');
            }
            if ( !wp_script_is('jquery-ui-datepicker', 'registered') ) {
                wp_register_script('jquery-ui-datepicker', plugins_url('media/legacy/jquery.ui.datepicker.min.js', __FILE__ ), array('jquery', 'jquery-ui-core'), '1.8.16');
            }
            if ( !wp_script_is('jquery-ui-slider', 'registered') ) {
                if ( !wp_script_is('jquery-ui-mouse', 'registered') ) {
                    wp_register_script('jquery-ui-widget', plugins_url('media/legacy/jquery.ui.widget.min.js', __FILE__ ), array('jquery'), '1.8.16');
                    wp_register_script('jquery-ui-mouse', plugins_url('media/legacy/jquery.ui.mouse.min.js', __FILE__ ), array('jquery-ui-widget'), '1.8.16');
                }                
                wp_register_script('jquery-ui-slider', plugins_url('media/legacy/jquery.ui.slider.min.js', __FILE__ ), array('jquery-ui-core', 'jquery-ui-mouse'), '1.8.16');
            }

            wp_register_script('jquery-ui-timepicker-addon', plugins_url('media/timepicker/jquery-ui-timepicker-addon.js', __FILE__ ), array('jquery', 'jquery-ui-datepicker', 'jquery-ui-slider'));
            
            wp_register_script('jnewsticker-admin-js', plugins_url('media/admin.js', __FILE__), array('jquery-ui-timepicker-addon'));
            wp_enqueue_script('jnewsticker-admin-js');

            wp_register_script('jnewsticker', plugins_url('media/jnewsticker/newsticker.jquery.js', __FILE__), array('jquery'));
            wp_enqueue_script('jnewsticker');     

            wp_register_style('jquery_ui', plugins_url('media/ui-lightness/jquery-ui-1.7.3.custom.css', __FILE__ ) );
            wp_enqueue_style('jquery_ui');

            if ( isset( self::$settings['skin'] ) && strlen(self::$settings['skin']) && count( self::get_all_tickers() ) ) {
                wp_register_style('jnewsticker_css', self::$settings['skin'] );
                wp_enqueue_style('jnewsticker_css');
            }
        }
    }    

    /**
     * Register news ticker widget
     * @return void
     */
    public function register_widget() {   
        register_widget( 'jNewsticker_Widget' );
    }    

    /**
     * Newsticker shortcode
     * @param array $args
     * @return string
     */
    public function shortcode($args) {
        $defaults = array( 'id' => 0 );
        $args = wp_parse_args( $args, $defaults );

        $jNewsticker = new jNewsticker($args['id']);
        return $jNewsticker->display( false );        
    }    

    /**
     * Add admin menu item under Settings
     * @return void
     */
    public function admin_menu() {
        add_options_page(__('jNewsticker', 'jnews'), __('jNewsticker', 'jnews'), 'activate_plugins', 'jnewsticker', array(&$this, 'admin_page') );
    }    

    /**
     * Return default formatting for time_ago variables
     * @param string $date_posted
     * @param mixed $data_source
     * @return string
     */
    public function time_ago_format( $date_posted ) {
        $time_posted = strtotime( $date_posted );
        $elapsed = date('U', current_time('timestamp', 1) ) - $time_posted;

        // Less than an hour
        if ( $elapsed < 3600 ) {
            $minutes = ceil( $elapsed / 60 );
            $timeago = $minutes > 1 ? sprintf( '%d '. __( 'minutes ago', 'jnews' ), $minutes ) : __( 'a minute ago', 'jnews' );
        }
        // Less than a day
        elseif ( $elapsed < 86400 ) {
            $hours = ceil( $elapsed / 3600 );
            $timeago = $hours > 1 ? sprintf( '%d '. __( 'hours ago', 'jnews' ), $hours ) : __( 'an hour ago', 'jnews' );
        }
        // Everything else
        else {
            $days = ceil( $elapsed / 86400 );
            $timeago = $days > 1 ? sprintf( '%d '. __( 'days ago', 'jnews' ), $days ) : __( 'yesterday', 'jnews' );
        }

        return $timeago;
    }

    /** 
     * Create a new data source instance. Called via Ajax.
     * @return void
     */
    public function create_data_source_instance() {
        $newsticker = new jNewsticker( $_POST['ticker_id'] );
        $data_source = new $_POST['source'];
        echo $data_source->display_form( $newsticker, $_POST['instance_id'] );
        exit();
    }

    /**
     * The HTML for the jNewsticker admin page
     * @return void
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h2><?php _e('jNewsticker', 'jnews') ?></h2>        
            
            <?php $action = isset( $_GET['action'] ) ? $_GET['action'] : 'index' ?>

            <?php if ( $action == 'edit' ) : ?>
            
                <?php $newsticker = new jNewsticker( $_GET['ticker_id'] ) ?>
                <?php echo $newsticker->display_form() ?>
            
            <?php elseif ( $action == 'create' ) : ?>
            
                <?php $newsticker = new jNewsticker() ?>
                <?php echo $newsticker->display_form() ?>
                                
            <?php else : ?>
                
                <?php if ( isset( $_GET['m'] ) ) : ?>
                    <div class="<?php echo in_array( $_GET['m'], array( 'ticker_update_failed' ) ) ? 'error' : 'updated' ?>">
                        <p>                            
                        <?php switch ( $_GET['m'] ) : 
                            case 'ticker_deleted' :
                                _e( 'News ticker has been deleted.', 'jnews' );
                                break;

                            case 'ticker_created' : 
                                _e( 'News ticker has been created', 'jnews' );
                                break;

                            case 'general_settings_updated' :
                                _e( 'Settings have been updated', 'jnews' );
                                break;
                        endswitch ?>
                        </p>
                    </div>
                <?php endif ?>

                <?php // Overview of newsticker instances ?>            
                <?php $newstickers = $this->get_all_tickers() ?>    

                <?php if ( count($newstickers) ) : ?>

                    <table class="widefat">
                        <thead>
                            <tr>
                                <th class="manage-column"><?php _e('ID', 'jnews') ?></th>
                                <th class="manage-column"><?php _e('Title', 'jnews') ?></th>
                                <th class="manage-column"><?php _e('Style', 'jnews') ?></th>
                                <th class="manage-column"><?php _e('Data sources', 'jnews') ?></th>
                                <th colspan=2></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="6">
                                    <a href="<?php echo admin_url('options-general.php?page=jnewsticker&action=create') ?>" class="button-primary"><?php _e( '+ Create news ticker', 'jnews' ) ?></a>
                                </td>
                            </tr>
                        </tfoot>
                        <tbody>
                        <?php foreach ( $newstickers as $newsticker_id => $newsticker ) : ?>
                            <tr>
                                <td><?php echo $newsticker_id ?></td>
                                <td><?php echo isset( $newsticker['internalTitle'] ) && strlen($newsticker['internalTitle']) ? $newsticker['internalTitle'] : sprintf( '<em>%s</em>', __( 'None set', 'jnews') ) ?></td>
                                <td><?php echo $newsticker['style'] ?></td>
                                <td>
                                    <?php if ( count($newsticker['data_sources']) ) : ?>
                                    <ul>
                                    <?php foreach ( $newsticker['data_sources'] as $class_name => $settings ) : ?>
                                        <?php if ( isset( $settings['enable'] ) && $settings['enable'] == 'on' ) : ?>
                                            <?php $source = new $class_name() ?>
                                            <li><?php echo $source->get_source_title() ?></li>                                    
                                        <?php endif ?>
                                    <?php endforeach ?>
                                    </ul>
                                    <?php endif ?>                                
                                </td>
                                <td>
                                    <a href="<?php echo admin_url('options-general.php?page=jnewsticker&action=edit&ticker_id='.$newsticker_id) ?>" class="button"><?php _e('Edit', 'jnews') ?></a>
                                </td>
                                <td>
                                    <form method="post" action="<?php echo admin_url('options-general.php?page=jnewsticker&action=delete') ?>">
                                        <?php wp_nonce_field('delete_jnewsticker', 'jnewsticker') ?>
                                        <input type="hidden" name="ticker_id" value="<?php echo $newsticker_id ?>"  />    
                                        <button class="button" name="delete_jnewsticker"><?php _e('Delete', 'jnews') ?></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>

                <?php else : ?>

                <p><?php _e( 'You haven\'t created any news tickers yet. Get started by creating one.' ) ?></p>
                <p><a href="<?php echo admin_url('options-general.php?page=jnewsticker&action=create') ?>" class="button-primary"><?php _e( '+ Create news ticker', 'jnews' ) ?></a></p>

                <?php endif ?>                

                <form method="post" action="<?php echo admin_url('options-general.php?page=jnewsticker&action=general_settings') ?>">
                    <?php wp_nonce_field('save_jnewsticker_general_settings', 'jnewsticker') ?>

                    <section class="general-options">

                        <?php $skins = jNewsticker_Bootstrap::get_skins() ?>
                        <?php $skins[] = __('None', 'jnews') ?>
                        <?php $skin = isset( self::$settings['skin'] ) ? self::$settings['skin'] : '' ?>

                        <h3><?php _e( 'General Options', 'jnews' ) ?></h3>                                                
                        <label for="jnewsticker_skin"><?php _e('Select skin:') ?></label>
                        <select id="jnewsticker_skin" name="skin">
                            <?php foreach ($skins as $src => $name) : ?>
                            <option value="<?php echo $src ?>" <?php selected( $skin, $src ) ?>><?php echo $name ?></option>
                            <?php endforeach ?>
                        </select>                      

                    </section>

                    <!-- Twitter settings -->
                    <section>

                        <?php $twitter = isset( self::$settings['twitter'] ) ? self::$settings['twitter'] : array() ?>                        

                        <h3><?php _e( 'Twitter API Settings', 'jnews' ) ?></h3>
                        <p><?php _e( 'In order to fetch tweets from Twitter, you have to create a Twitter application. This is much easier than it sounds!', 'jnews' ) ?></p>
                        <p><?php _e( 'Follow these simple steps:', 'jnews' ) ?></p>
                        <ol>
                            <li><?php printf( __( 'Go to %s to register your app. You may be asked to log in.', 'jnews' ), '<a href="https://twitter.com/apps" target="_blank">https://twitter.com/apps</a>' ) ?></li>
                            <li><?php printf( __( 'Once you are logged in, click the button that says %sCreate a new application%s.', 'jnews' ), '<strong>', '</strong>' )?></li>
                            <li><?php _e( 'Complete the form, tick the terms and conditions and solve the Captcha. Create your application.', 'jnews' ) ?></li>
                            <li><?php _e( 'You have now created a Twitter application. The final step is to create an access token. Do this by clicking the button at the bottom of the page.', 'jnews' ) ?></li>
                            <li><?php _e( 'Finally, copy and paste the relevant details about your Twitter application into the fields below.', 'jnews' ) ?></li>
                        </ol>
                        <div>
                            <label for="jnewsticker_twitter_consumer_key"><?php _e( 'Consumer Key:', 'jnews' ) ?></label>
                            <input style="width:400px;" type="text" name="twitter[consumer_key]" id="jnewsticker_twitter_consumer_key" <?php if ( array_key_exists( 'consumer_key', $twitter ) ) : ?>value="<?php echo $twitter['consumer_key'] ?>"<?php endif ?> />
                        </div>
                        <div>
                            <label for="jnewsticker_twitter_consumer_secret"><?php _e( 'Consumer Secret:', 'jnews' ) ?></label>
                            <input style="width:400px;" type="text" name="twitter[consumer_secret]" id="jnewsticker_twitter_consumer_secret" <?php if ( array_key_exists( 'consumer_secret', $twitter ) ) : ?>value="<?php echo $twitter['consumer_secret'] ?>"<?php endif ?> />
                        </div>
                        <div>
                            <label for="jnewsticker_twitter_access_token"><?php _e( 'Access Token:', 'jnews' ) ?></label>
                            <input style="width:400px;" type="text" name="twitter[access_token]" id="jnewsticker_twitter_access_token" <?php if ( array_key_exists( 'access_token', $twitter ) ) : ?>value="<?php echo $twitter['access_token'] ?>"<?php endif ?> />
                        </div>
                        <div>
                            <label for="jnewsticker_twitter_access_token_secret"><?php _e( 'Access Token Secret:', 'jnews' ) ?></label>
                            <input style="width:400px;" type="text" name="twitter[access_token_secret]" id="jnewsticker_twitter_access_token_secret" <?php if ( array_key_exists( 'access_token_secret', $twitter ) ) : ?>value="<?php echo $twitter['access_token_secret'] ?>"<?php endif ?> />
                        </div>
                    </section>

                    <footer class="button-container">
                        <button class="button" name="save_jnewsticker_general_settings"><?php _e('Save Settings', 'jnews') ?></button>
                    </footer>       

                </form>

            <?php endif ?>
        </div>            
        <?php
    }
}

jNewsticker_Bootstrap::get_instance();

/**
 * Register a new data source
 * @param string $class_name
 * @return void
 */
function jnewsticker_register_data_source($class_name) {
    if ( !class_exists($class_name) ) {
        throw new Exception( sprintf( _e('No class with class name "%s" found', 'jnews'), $class_name ) );        
    }
    
    jNewsticker_Bootstrap::register_data_source($class_name);
}

/**
 * Register a new skin
 * @param string $name
 * @param string $src
 * @return void
 */
function jnewsticker_register_skin($name, $src) {    
    jNewsticker_Bootstrap::register_skin($name, $src);
}

/**
 * Display a news ticker
 * @param id $ticker_id     Set to 0 by default
 * @return string
 */
function jnewsticker_display($ticker_id = 0) {
    $jNewsticker = new jNewsticker($ticker_id);
    return $jNewsticker->display();
}