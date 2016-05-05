<?php
/**
 * Cross-sells
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
$_delay = 200;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

$crosssells = WC()->cart->get_cross_sells();

if ( sizeof( $crosssells ) == 0 ) return;

$meta_query = WC()->query->get_meta_query();

$args = array(
	'post_type'           => 'product',
	'ignore_sticky_posts' => 1,
	'no_found_rows'       => 1,
	'posts_per_page'      => apply_filters( 'woocommerce_cross_sells_total', $posts_per_page ),
	'orderby'             => $orderby,
	'post__in'            => $crosssells,
	'meta_query'          => $meta_query
);

$products = new WP_Query( $args );

$woocommerce_loop['columns'] = apply_filters( 'woocommerce_cross_sells_columns', $columns );

if ( $products->have_posts() ) : ?>

	<div class="cross-sells">

		<h2><?php _e( 'You may be interested in&hellip;', 'woocommerce' ) ?></h2>

		<?php //woocommerce_product_loop_start(); ?>
		<ul class="products-group large-block-grid-4 small-block-grid-2">
			
			<?php while ( $products->have_posts() ) : $products->the_post(); ?>

				<?php
					wc_get_template( 'content-product.php', array('is_deals' => $is_deals, '_delay' => $_delay, 'wrapper' => 'li') );
					$_delay+=200;
				?>

			<?php endwhile; // end of the loop. ?>
		</ul>
		<?php //woocommerce_product_loop_end(); ?>

	</div>

<?php endif;

wp_reset_query();
