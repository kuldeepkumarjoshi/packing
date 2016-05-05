<?php
/**
 * @package leetheme
 */

global $ros_opt,$page;

if (isset($_GET['list-style'])){
	$ros_opt['blog_type'] = 'blog-list';
}
?>
<?php 
if(!isset($ros_opt['blog_type']) || $ros_opt['blog_type'] == 'blog-standard'){ ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( has_post_thumbnail() ) {?>
        	<div class="entry-image">
	        	<a href="<?php the_permalink();?>">
		        	<?php if(isset($ros_opt['blog_parallax'])) { ?><div class="parallax_img" style="overflow:hidden"><div class="parallax_img_inner" data-velocity="0.15"><?php } ?>
		            	<?php the_post_thumbnail('normal-thumb'); ?>
		            	<div class="image-overlay"></div>
		      		<?php if(isset($ros_opt['blog_parallax'])) { ?></div></div><?php } ?>
	        	</a>
        </div>
    <?php } ?>
	<header class="entry-header">
		<div class="row">
			<div class="large-2 columns text-center">
				<div class="post-date-wrapper">
					<div class="post-date">
						<span class="post-date-month"><?php echo get_the_time('M', get_the_ID()); ?></span>
		                <span class="post-date-day"><?php echo get_the_time('d', get_the_ID()); ?></span>
			        </div>
		        </div>
		        <div class="meta-author"><?php echo 'By '.get_the_author().''; ?></div>
	        </div>
	        <div class="large-10 columns">
				<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
				<div class="entry-summary">
					<?php the_excerpt(); ?>
				</div>
			</div>
			<?php  ?>
		</div>
	</header>

	<footer class="entry-meta">
		<?php if ( 'post' == get_post_type() ) :?>
			<?php
				$categories_list = get_the_category_list( __( ', ', 'ltheme_domain' ) );
			?>
			<span class="cat-links">
				<?php printf( __( 'Posted in %1$s', 'ltheme_domain' ), $categories_list ); ?>
			</span>

			<?php
				$tags_list = get_the_tag_list( '', __( ', ', 'ltheme_domain' ) );
				if ( $tags_list ) :
			?>
			<span class="sep"> | </span>
			<span class="tags-links">
				<?php printf( __( 'Tagged %1$s', 'ltheme_domain' ), $tags_list ); ?>
			</span>
			<?php endif; ?>
		<?php endif;?>

		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
		<span class="comments-link right"><?php comments_popup_link( __( 'Leave a comment', 'ltheme_domain' ), __( '<strong>1</strong> Comment', 'ltheme_domain' ), __( '<strong>%</strong> Comments', 'ltheme_domain' ) ); ?></span>
		<?php endif; ?>
	</footer>
</article>


<?php } 
else if($ros_opt['blog_type'] == 'blog-list') { ?>
<div class="blog-list-style">
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="row">
	<?php if ( has_post_thumbnail() ) { ?>
	<div class="large-4 columns">
        <div class="entry-image">
        	<a href="<?php the_permalink();?>">
            <?php the_post_thumbnail('list-thumb'); ?>
            <div class="image-overlay"></div>
        	</a>
        </div>
      </div>
    <?php } ?>

    <div class="large-8 columns">

	<div class="entry-content">
		<h3 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
		<?php the_excerpt(); ?>
		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<?php leetheme_posted_on(); ?>  <?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
		<span class="comments-link right"><?php comments_popup_link( __( 'Leave a comment', 'ltheme_domain' ), __( '<strong>1</strong> Comment', 'ltheme_domain' ), __( '<strong>%</strong> Comments', 'ltheme_domain' ) ); ?></span>
		<?php endif; ?>
		</div>
		<?php endif; ?>
	</div>
	</div>
</div>
	
</article>
</div>
<?php } ?>
