<?php
/*
Template name: Left sidebar
*/
get_header(); ?>

<div class="page-header">
<?php if( has_excerpt() ) the_excerpt();?>
</div>

<div  class="container-wrap page-left-sidebar">
<div class="row">

<div id="content" class="large-9 right columns" role="main">
	<div class="page-inner">
			<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'page' ); ?>

					<?php
						if ( comments_open() || '0' != get_comments_number() )
							comments_template();
					?>

			<?php endwhile; ?>
	</div>
</div>

<div class="large-3 columns left">
<?php get_sidebar(); ?>
</div>

</div>
</div>


<?php get_footer(); ?>
