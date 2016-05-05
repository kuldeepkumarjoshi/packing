<?php
/**
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
$_delay = 200;
$_count = 1;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $ros_opt, $wp_query;

if (isset($_GET['right'])){
	$ros_opt['category_sidebar'] = 'right-sidebar';
}
if (isset($_GET['no-sidebar'])){
	$ros_opt['category_sidebar'] = 'no-sidebar';
}

get_header('shop'); ?>

<?php do_action('lee_get_breadcrumb'); ?>

<div class="cat-header">
		<?php 
		?>
		<?php if(isset($ros_opt['cat_bg']) && $ros_opt['cat_bg'] != '') {
			if($wp_query->query_vars['paged'] == 1 || $wp_query->query_vars['paged'] < 1){
				echo do_shortcode($ros_opt['cat_bg']);
			}
		} ?>
</div>
<div class="row category-page">

<?php
	do_action('woocommerce_before_main_content');
?>

<?php if($ros_opt['category_sidebar'] == 'right-sidebar') { ?>
       <div class="large-9 columns left">
<?php } else if ($ros_opt['category_sidebar'] == 'left-sidebar') { ?>
		<div class="large-9 columns right">
<?php } else { ?>
		<div class="large-12 columns no-sidebar">
<?php } ?>
			
	<div class="row filters-container">
		<div class="large-6 columns">
			<ul class="filter-tabs">
				<li class="productGrid active"><i class="fa fa-th"></i></li>
				<!--<li class="productList"><i class="fa fa-th-list"></i></li> -->
			</ul>
		</div>
		<div class="large-6 columns">
			<ul class="sort-bar">
				<li class="sort-bar-text"></li>
				<li><?php if ( have_posts() ) : do_action( 'woocommerce_before_shop_loop' ); ?><?php endif; ?></li>
			</ul>
		</div>
	</div>

    <?php do_action( 'woocommerce_archive_description' ); ?>
		<?php if ( have_posts() ) : ?>
			<?php woocommerce_product_loop_start(); ?>
				<?php woocommerce_product_subcategories(); ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<!-- Product Item -->
					<?php wc_get_template( 'content-product.php', array('_delay' => $_delay, 'wrapper' => 'li') ); ?>
					<!-- End Product Item -->
					<?php  $_delay+=200; ?>
				<?php endwhile;?>
			<?php woocommerce_product_loop_end(); ?>
	<div class="row filters-container-down">
		<!-- Pagination -->
			<?php do_action( 'woocommerce_after_shop_loop' );?>
		<!-- End Pagination -->
	</div>
		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>
			<?php woocommerce_get_template( 'loop/no-products-found.php' ); ?>
		<?php endif; ?>
	<?php
		do_action('woocommerce_after_main_content');
	?>

  <?php if(get_search_query() ) : ?>
    <?php
      query_posts( array( 'post_type' => array( 'post', 'page' ), 's' => get_search_query() ) );

      if(have_posts()){ echo '<div class="row"><div class="large-12 columns"><hr/>'; }

      while ( have_posts() ) : the_post();
        $wc_page = false;
        if($post->post_type == 'page'){
          foreach (array('myaccount', 'edit_address', 'change_password', 'lost_password', 'shop', 'cart', 'checkout', 'pay', 'view_order', 'thanks', 'terms') as $wc_page_type) {
            if( $post->ID == woocommerce_get_page_id($wc_page_type) ) $wc_page = true;
          }
        }
        if( !$wc_page ) get_template_part( 'content', get_post_format() );
      endwhile;

      if(have_posts()){ echo '</div></div>'; }

      wp_reset_query();
    ?>
  <?php endif; ?>
                      
 </div>

<?php if($ros_opt['category_sidebar'] == 'right-sidebar') { ?>
	<div class="large-3 right columns">
		<?php if (is_active_sidebar('shop-sidebar')) { dynamic_sidebar('shop-sidebar');}?>
    </div>
<?php } elseif ($ros_opt['category_sidebar'] == 'left-sidebar') { ?>
	<div class="large-3 left columns">
		<?php if (is_active_sidebar('shop-sidebar')) { dynamic_sidebar('shop-sidebar');}?>
    </div>
<?php } ?>

</div>

<?php get_footer('shop'); ?>