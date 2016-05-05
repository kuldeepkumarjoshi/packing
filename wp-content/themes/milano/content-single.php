<?php
/**
 * @package leetheme
 */

global $ros_opt;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	

	<?php if ( has_post_thumbnail() ) { ?>
    <div class="entry-image">
    		<?php if(isset($ros_opt['blog_parallax'])) { ?><div class="parallax_img" style="overflow:hidden"><div class="parallax_img_inner" data-velocity="0.15"><?php } ?>
            <?php the_post_thumbnail('large'); ?>
            <div class="image-overlay"></div>
            <?php if(isset($ros_opt['blog_parallax'])) { ?></div></div><?php } ?>
    </div>
    <?php } ?>
    <header class="entry-header text-center">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<div class="entry-meta">
			<?php leetheme_posted_on(); ?>
		</div>
	</header>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'ltheme_domain' ),
				'after'  => '</div>',
			) );
		?>
	</div>


	<?php 
		echo '<div class="blog-share text-center">';
		echo do_shortcode('[share]');
		echo '</div>';
	?>

	<footer class="entry-meta">
		<?php
			$category_list = get_the_category_list( __( ', ', 'ltheme_domain' ) );

			$tag_list = get_the_tag_list( '', __( ', ', 'ltheme_domain' ) );

		
			if ( '' != $tag_list ) {
				$meta_text = __( 'Posted in %1$s and tagged %2$s.', 'ltheme_domain' );
			} else {
				$meta_text = __( 'Posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'ltheme_domain' );
			}


			printf(
				$meta_text,
				$category_list,
				$tag_list,
				get_permalink(),
				the_title_attribute( 'echo=0' )
			);
		?>


	</footer>
		
</article>
