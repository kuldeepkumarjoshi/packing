<?php

add_action( 'widgets_init', 'contact_us_widget' );

function contact_us_widget() {
	register_widget( 'Contact_Us_Widget' );
}

/**
 * @since 2.8.0
 */
class Contact_Us_Widget extends WP_Widget {
	
	function __construct() {

		$widget_ops = array( 'classname' => 'contact_us_widget', 'description' => __('A widget that displays footer contact us ', 'ltheme_domain') );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'contact_us_widget' );
		parent::__construct( 'Contact_Us_Widget', __('Leetheme Contact Us', 'ltheme_domain'), $widget_ops, $control_ops );
		
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */

	function widget($args, $instance) {

		$cache = wp_cache_get('contact_us_widget', 'widget');

		if ( !is_array($cache) ) $cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		if ( empty( $instance['contact_logo'] ) || ! $contact_logo = $instance['contact_logo'] )
 			$contact_logo = '';
		if ( empty( $instance['contact_address'] ) || ! $contact_address = $instance['contact_address'] )
 			$contact_address = 'Address...';
 		if ( empty( $instance['contact_phone'] ) || ! $contact_phone = $instance['contact_phone'] )
 			$contact_phone = 'Phone...';
 		if ( empty( $instance['contact_email'] ) || ! $contact_email = $instance['contact_email'] )
 			$contact_email = 'Email...';
 		if ( empty( $instance['contact_icon_style'] ) || ! $contact_icon_style = $instance['contact_icon_style'] )
 			$contact_icon_style = '';

		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title; ?>

		<ul class="contact-information">
			<?php if (isset($contact_logo) && $contact_logo != null) { ?>
				<li class="contact-logo">
					<img src="<?php echo esc_attr($contact_logo); ?>" alt="Logo" />
				</li>
			<?php } ?>
			<li class="media">
				<!-- <div class="contact-icon"><i class="fa fa-paper-plane"></i></div> -->
				<div class="contact-text"><span><strong><?php _e('ADD.&nbsp;&nbsp;','ltheme_domain'); ?></strong><?php if (isset($contact_address)) echo esc_attr($contact_address); ?></span></div>
			</li>
			<li class="media">
				<!-- <div class="contact-icon"><i class="fa fa-phone"></i></div> -->
				<div class="contact-text"><span><strong><?php _e('TEL.&nbsp;&nbsp;','ltheme_domain'); ?></strong><?php if (isset($contact_phone)) echo esc_attr($contact_phone); ?></span></div>
			</li>
			<li class="media">
				<!-- <div class="contact-icon"><i class="fa fa-envelope"></i></div> -->
				<div class="contact-text"><span><strong><?php _e('MAIL.&nbsp;','ltheme_domain'); ?></strong><?php if (isset($contact_email)) echo esc_attr($contact_email); ?></span></div>
			</li>
		</ul>
<?php 
		echo $after_widget;

		wp_reset_postdata();

		$content = ob_get_clean();

		if ( isset( $args['widget_id'] ) ) $cache[$args['widget_id']] = $content;

		echo $content;

		wp_cache_set('contact_us_widget', $cache, 'widget');
	}

	/**
	 *
	 * @see WP_Widget->update
	 * @access public
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);

		$instance['contact_logo'] = $new_instance['contact_logo'];
		$instance['contact_address'] = $new_instance['contact_address'];
		$instance['contact_phone'] = $new_instance['contact_phone'];
		$instance['contact_email'] = $new_instance['contact_email'];
		$instance['contact_icon_style'] = $new_instance['contact_icon_style'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['contact_us_widget']) ) delete_option('contact_us_widget');

		return $instance;
	}

	/**
	 *
	 * @access public
	 * @return void
	 */

	function flush_widget_cache() {
		wp_cache_delete( 'contact_us_widget', 'widget' );
	}


	/**
	 *
	 * @see WP_Widget->form
	 * @access public
	 * @param array $instance
	 * @return void
	 */
	
	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$contact_logo     = isset( $instance['contact_logo'] ) ? esc_attr( $instance['contact_logo'] ) : '';
		$contact_address     = isset( $instance['contact_address'] ) ? esc_attr( $instance['contact_address'] ) : '';
		$contact_phone     = isset( $instance['contact_phone'] ) ? esc_attr( $instance['contact_phone'] ) : '';
		$contact_email     = isset( $instance['contact_email'] ) ? esc_attr( $instance['contact_email'] ) : '';
		$contact_icon_style     = isset( $instance['contact_icon_style'] ) ? esc_attr( $instance['contact_icon_style'] ) : '';

		?>
		<p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e( 'Title:', 'woocommerce' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo esc_attr($this->get_field_id( 'contact_logo' )); ?>"><?php _e( 'Logo:', 'ltheme_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'contact_logo ' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'contact_logo' )); ?>" type="text" value="<?php echo esc_attr($contact_logo) ; ?>" /></p>

		<p><label for="<?php echo esc_attr($this->get_field_id( 'contact_address' )); ?>"><?php _e( 'Address:', 'ltheme_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'contact_address' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'contact_address' )); ?>" type="text" value="<?php echo esc_attr($contact_address); ?>" /></p>

		<p><label for="<?php echo esc_attr($this->get_field_id( 'contact_phone' )); ?>"><?php _e( 'Phone:', 'ltheme_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'contact_phone' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'contact_phone' )); ?>" type="text" value="<?php echo esc_attr($contact_phone); ?>" /></p>

		<p><label for="<?php echo esc_attr($this->get_field_id( 'contact_email' )); ?>"><?php _e( 'Email:', 'ltheme_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'contact_email' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'contact_email' )); ?>" type="text" value="<?php echo esc_attr($contact_email); ?>" /></p>

		<!-- <p><label for="<?php echo esc_attr($this->get_field_id( 'contact_icon_style' )); ?>"><?php _e( 'Contact icon style:', 'ltheme_domain' ); ?></label>
			<select name="<?php echo esc_attr($this->get_field_name( 'contact_icon_style' )); ?>" id="<?php echo esc_attr($this->get_field_name( 'contact_icon_style' )); ?>" type="text">
				<option value='' <?php echo ($contact_icon_style == '') ? 'selected' : ''; ?>><?php _e('Title text', 'ltheme_domain'); ?></option>
				<option value='title-icon' <?php echo ($contact_icon_style == 'title-icon') ? 'selected' : ''; ?>><?php _e('Title Icon', 'ltheme_domain'); ?></option>
			</select>
		</p> -->
<?php
	}
}
?>