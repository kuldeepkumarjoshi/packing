<?php
/**
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package leetheme
 */

get_header(); 
if(!isset($ros_opt['blog_layout'])){$ros_opt['blog_layout'] = '';}
?>


<div class="container-wrap page-<?php if($ros_opt['blog_layout']){ echo esc_attr($ros_opt['blog_layout']);} else {echo esc_attr('right-sidebar');} ?>">
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


		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php
						if ( is_category() ) :
							printf( __( 'Category Archives: %s', 'ltheme_domain' ), '<span>' . single_cat_title( '', false ) . '</span>' );

						elseif ( is_tag() ) :
							printf( __( 'Tag Archives: %s', 'ltheme_domain' ), '<span>' . single_tag_title( '', false ) . '</span>' );

						elseif ( is_author() ) :
							the_post();
							printf( __( 'Author Archives: %s', 'ltheme_domain' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
							rewind_posts();

						elseif ( is_day() ) :
							printf( __( 'Daily Archives: %s', 'ltheme_domain' ), '<span>' . get_the_date() . '</span>' );

						elseif ( is_month() ) :
							printf( __( 'Monthly Archives: %s', 'ltheme_domain' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

						elseif ( is_year() ) :
							printf( __( 'Yearly Archives: %s', 'ltheme_domain' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

						elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
							_e( 'Asides', 'ltheme_domain' );

						elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
							_e( 'Images', 'ltheme_domain');

						elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
							_e( 'Videos', 'ltheme_domain' );

						elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
							_e( 'Quotes', 'ltheme_domain' );

						elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
							_e( 'Links', 'ltheme_domain' );

						else :
							_e( '', 'ltheme_domain' );

						endif;
					?>
				</h1>
				<?php
					if ( is_category() ) :
						$category_description = category_description();
						if ( ! empty( $category_description ) ) :
							echo apply_filters( 'category_archive_meta', '<div class="taxonomy-description">' . $category_description . '</div>' );
						endif;

					elseif ( is_tag() ) :
						$tag_description = tag_description();
						if ( ! empty( $tag_description ) ) :
							echo apply_filters( 'tag_archive_meta', '<div class="taxonomy-description">' . $tag_description . '</div>' );
						endif;

					endif;
				?>
			</header>


	<div class="page-inner">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					get_template_part( 'content', get_post_format() );
				?>

			<?php endwhile; ?>


		<?php else : ?>

			<?php get_template_part( 'no-results', 'archive' ); ?>

		<?php endif; ?>

		 <div class="large-12 columns navigation-container">
			<?php leetheme_content_nav( 'nav-below' ); ?>
		</div>
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