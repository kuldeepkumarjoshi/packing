<?php 

// **********************************************************************// 
// ! Flickr Photos widget
// **********************************************************************// 

add_action( 'widgets_init', 'leetheme_flickr_widget' );

function leetheme_flickr_widget() {
    register_widget( 'leetheme_Flickr_Widget' );
}

class leetheme_Flickr_Widget extends WP_Widget {
    
    function __construct()
    {
        $widget_ops = array('classname' => 'flickr', 'description' => 'Photos from flickr.');
        $control_ops = array('id_base' => 'leetheme_flickr-widget');
        parent::__construct('leetheme_flickr-widget', __('Leetheme Flickr Photos', 'ltheme_domain'), $widget_ops, $control_ops);
    }
    
    function widget($args, $instance)
    {
        extract($args);

        $title = apply_filters('widget_title', empty( $instance['title'] ) ? __('Flickr', 'ltheme_domain') : $instance['title'], $instance, $this->id_base);
        $screen_name = @$instance['screen_name'];
        $number = @$instance['number'];
        $show_button = @$instance['show_button'];
        
        if(!$screen_name || $screen_name == '') {
            $screen_name = '107945286@N06';
        }
        
        echo $before_widget;
        if ( $title )
                echo $before_title . $title . $after_title;
        
        if($screen_name && $number) {
            echo '<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count='.$number.'&display=latest&size=s&layout=x&source=user&user='.$screen_name.'"></script>';
        }
        
        echo $after_widget;
    }
    
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags($new_instance['title']);
        $instance['screen_name'] = $new_instance['screen_name'];
        $instance['number'] = $new_instance['number'];
        
        return $instance;
    }

    function form($instance)
    {
        $defaults = array('title' => 'Photos from Flickr', 'screen_name' => '', 'number' => 6, 'show_button' => 1);
        $instance = wp_parse_args((array) $instance, $defaults); ?>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:','ltheme'); ?></label>
            <input class="widefat" style="width: 216px;" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('screen_name')); ?>"><?php _e('Flickr ID','ltheme_domain'); ?></label>
            <input class="widefat" style="width: 216px;" id="<?php echo esc_attr($this->get_field_id('screen_name')); ?>" name="<?php echo esc_attr($this->get_field_name('screen_name')); ?>" value="<?php echo esc_attr($instance['screen_name']); ?>" />
            <br/>
            <p class="help"><?php _e('To find your flickID visit ','ltheme_domain'); ?><strong>http://idgettr.com</strong></p>
        </p>


        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php _e('Number of photos to show:','ltheme_domain'); ?></label>
            <input class="widefat" style="width: 30px;" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" value="<?php echo esc_attr($instance['number']); ?>" />
        </p>
        
        
    <?php
    }
}

 ?>