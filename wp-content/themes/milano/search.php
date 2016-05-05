<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package leetheme
 */

get_header();
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

		
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'ltheme_domain' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header>

			<?php while ( have_posts() ) : the_post(); ?>
			<?php
					get_template_part( 'content', get_post_format() );
				?>

			<?php endwhile; ?>

			<?php leetheme_content_nav( 'nav-below' ); ?>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'search' ); ?>

		<?php endif; ?>
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