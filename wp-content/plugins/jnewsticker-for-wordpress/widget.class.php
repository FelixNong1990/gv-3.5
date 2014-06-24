<?php

/**
 * The widget
 * 
 * @author Eric Daams <eric@ericnicolaas.com>
 */

class jNewsticker_Widget extends WP_Widget 
{    
    function jNewsticker_Widget() {
        $widget_ops = array(
            'classname' => 'jnewsticker_widget', 
            'description' => __('Add a newsticker style display of recent posts, comments, or various other potential news items')
        );
        $control_ops = array( 'id_base' => 'jnewsticker_widget' );    
        $this->WP_Widget('jNewsticker_Widget', __('Newsticker'), $widget_ops, $control_ops);
        
        
    }
   
    function form($instance) {                      
        $newsticker_id = isset( $instance['newsticker_id'] ) ? $instance['newsticker_id'] : false;
        $newstickers = jNewsticker_Bootstrap::get_all_tickers();        
        ?>
        <p>
            <?php if ( count ( $newstickers ) ) : ?>

                <label for="<?php echo $this->get_field_id('newsticker_id'); ?>"><?php _e('Select news ticker:') ?></label>
                <select id="<?php echo $this->get_field_id('newsticker_id') ?>" name="<?php echo $this->get_field_name('newsticker_id'); ?>">
                    <?php foreach ( $newstickers as $id => $newsticker ) : ?>
                    <option value="<?php echo $id ?>" <?php selected( $newsticker_id, $id ) ?>>
                        <?php echo isset( $newsticker['internalTitle'] ) && strlen($newsticker['internalTitle']) ? $newsticker['internalTitle'] : sprintf( '<em>%s</em>', __( 'None set', 'jnews') ) ?>
                    </option>
                    <?php endforeach ?>
                </select>                            


            <?php else : ?>
                <?php _e( sprintf( 'No news tickers have been configured yet. %sCreate one now%s', '<a href="/options-general.php?page=jnewsticker">', '</a>'), 'jnews' ) ?>
            <?php endif ?>
        </p>                
        <?php
    }
 
    function update($new_instance, $old_instance) {
        $instance = $old_instance;  
        $instance['newsticker_id'] = $new_instance['newsticker_id'];
        return $instance;
    }
 
    function widget($args, $instance) {
        extract( $args, EXTR_SKIP );
        $jNewsticker = new jNewsticker($instance['newsticker_id']);                
        echo $before_widget . $jNewsticker->display(false) . $after_widget;
    }
}