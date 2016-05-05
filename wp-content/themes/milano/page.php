<?php
/**
 * The template for displaying all pages.
 *
 * @package leetheme
 */

get_header(); ?>
<?php leetheme_get_breadcrumb(); ?>
<div class="page-header">
<?php if( has_excerpt() ) the_excerpt();?>
</div>

<div  class="container-wrap">
<div class="row">

	
<div id="content" class="large-12 left columns" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php
					if ( comments_open() || '0' != get_comments_number() )
						comments_template();
				?>

		<?php endwhile; ?>

</div>

</div>
</div>


<?php get_footer(); ?>
