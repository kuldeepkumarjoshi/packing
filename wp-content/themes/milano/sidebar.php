<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package leetheme
 */
?>
<div id="secondary" class="widget-area" role="complementary">
	<?php do_action( 'before_sidebar' ); ?>
	<?php if ( ! dynamic_sidebar( 'sidebar-main' ) ) : ?>
	<?php endif;?>
</div>
