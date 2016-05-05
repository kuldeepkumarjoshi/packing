<?php
/**
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product_cat.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

global $woocommerce_loop;
global $delay_animation_product;

if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

$woocommerce_loop['loop']++;
?>
<li class="product-category wow fadeInUp" data-wow-duration="1s" data-wow-delay="<?php echo esc_attr($delay_animation_product); ?>ms">

	<?php do_action( 'woocommerce_before_subcategory', $category ); ?>
	<div class="inner-wrap">
	<a href="<?php echo get_term_link( $category->slug, 'product_cat' ); ?>">

		<?php
			do_action( 'woocommerce_before_subcategory_title', $category );
		?>
		<div class="header-title">
		<h3>
			<?php
				
				if ( $category->count > 0 )
					echo apply_filters( 'woocommerce_subcategory_count_html', ' <span class="count">' . $category->count . ' '.__('items','woocommerce').'</span>', $category);
				echo $category->name;
			?>
		</h3>
		</div>

		<?php
			do_action( 'woocommerce_after_subcategory_title', $category );
		?>
	</a>
	</div>

	<?php do_action( 'woocommerce_after_subcategory', $category ); ?>

</li>