<?php
/**
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

global $product, $woocommerce_loop;

$attachment_ids = $product->get_gallery_attachment_ids();

?>         

<li class="product small">
<a href="<?php the_permalink(); ?>" style="display:block" class="tip-top"  data-tip='<?php the_title(); ?> / <?php echo strip_tags($product->get_price_html()); ?>'>
      <div class="product-img">
        <?php echo get_the_post_thumbnail($post->ID, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' )) ?>
      </div>
</a>
       <?php woocommerce_get_template( 'loop/sale-flash.php' ); ?>
</li>

