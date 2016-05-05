<?php
/**
 * Related Products
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

$_delay = 200;
$_count = 1;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop, $ros_opt;

$related = $product->get_related(12);

if ( sizeof( $related ) == 0 ) return;

$args = apply_filters('woocommerce_related_products_args', array(
	'post_type'				=> 'product',
	'ignore_sticky_posts'	=> 1,
	'no_found_rows' 		=> 1,
	'orderby' 				=> $orderby,
	'post__in' 				=> $related,
	'post__not_in'			=> array($product->id)
) );

$products = new WP_Query( $args );


if ( $products->have_posts() ) : ?>

	<div class="related products">
		<div class="row">
			<div class="large-12 columns">
				<div class="title-block text-center">
					<h4 class="heading-title"><span><?php _e( 'Related Products', 'woocommerce' ); ?></span></h4>
					<div class="bery-hr medium text-center"></div>
				</div>
			</div>
		</div>
		<div class="row group-slider">
            <div class="slider lee-slider products-group" data-columns="4" data-columns-small = "1" data-columns-tablet="3">
            	<!-- Product Item -->
					<?php while ( $products->have_posts() ) : $products->the_post(); ?>
						<?php wc_get_template( 'content-product.php', array('_delay' => $_delay, 'wrapper' => 'div') ); ?>
						<?php 
							$_delay+=200;
						?>	
					<?php endwhile; ?>
				<!-- End Product Item -->
            </div>  
   		</div> 
	</div>

<?php endif;

wp_reset_postdata();
