<?php
/*
Template name: Full Width (100%)
*/
get_header(); ?>

<?php

/* Display popup window */
if (isset($ros_opt['promo_popup']) && $ros_opt['promo_popup'] == 1){?>
	<div class="popup_link hide"><a class="leetheme-popup open-click" href="#leetheme-popup"><?php _e('Newsletter', 'ltheme_domain'); ?></a></div>
	<?php do_action('after_page_wrapper'); ?>
<?php } ?>


<div class="page-header">
<?php if( has_excerpt() ) the_excerpt();?>
</div>


<div id="content" role="main">
	<?php while ( have_posts() ) : the_post(); ?>

		<?php the_content(); ?>
	
	<?php endwhile; ?>
			
</div>
<?php get_footer(); ?>
