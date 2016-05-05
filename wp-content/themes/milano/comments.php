<?php
/**
 * The template for displaying Comments.
 *
 * @package leetheme
 */

if ( post_password_required() )
	return;
?>

<div id="comments" class="comments-area">


	<?php if ( have_comments() ) : ?>
		<h3 class="comments-title">
			<?php
				echo 'COMMENTS ('.get_comments_number().')';
			?>
		</h3>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :?>
		<nav id="comment-nav-above" class="navigation-comment" role="navigation">
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'ltheme_domain' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'ltheme_domain' ) ); ?></div>
		</nav>
		<?php endif; ?>

		<ol class="comment-list">
			<?php
				wp_list_comments( array( 'callback' => 'leetheme_comment' ) );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<nav id="comment-nav-below" class="navigation-comment" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'ltheme_domain' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'ltheme_domain' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'ltheme_domain' ) ); ?></div>
		</nav>
		<?php endif;  ?>

	<?php endif; ?>

	<?php
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'ltheme_domain' ); ?></p>
	<?php endif; ?>

	<?php  comment_form();  ?>

</div>
