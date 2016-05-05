<?php
/**
 * The Template for displaying all single posts.
 *
 * @package leetheme
 */

get_header(); 

global $ros_opt;
if(!isset($ros_opt['blog_layout'])){$ros_opt['blog_layout'] = '';}
?>

<div class="container-wrap page-<?php if($ros_opt['blog_layout']){ echo esc_attr($ros_opt['blog_layout']);} else {echo 'right-sidebar';} ?>">
	<div class="row">


		<?php if($ros_opt['blog_layout'] == 'left-sidebar') {
		 	echo '<div id="content" class="large-9 right columns" role="main">';
		 } else if($ros_opt['blog_layout'] == 'right-sidebar'){
		 	echo '<div id="content" class="large-9 left columns" role="main">';
		 } else if($ros_opt['blog_layout'] == 'no-sidebar'){
		 	echo '<div id="content" class="large-10 columns large-offset-1" role="main">';
		 } else {
		 	echo '<div id="content" class="large-9 left columns" role="main">';
		 }
		?>
		
		<div class="page-inner">
		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single' ); ?>

			<?php
				if ( comments_open() || '0' != get_comments_number() )
					comments_template();
			?>

		<?php endwhile; ?>
		</div>
	</div>

	<div class="large-3 columns left">
		<?php if($ros_opt['blog_layout'] == 'left-sidebar' || $ros_opt['blog_layout'] == 'right-sidebar'){
			get_sidebar();
		}?>
	</div>


</div>
</div>


<?php get_footer(); ?>