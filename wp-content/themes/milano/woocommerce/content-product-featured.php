<?php
/**
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

global $product, $woocommerce_loop, $ros_opt;

$attachment_ids = $product->get_gallery_attachment_ids();

if ( ! $product->is_visible() )
	return;

$post_id = $post->ID;
$stock_status = get_post_meta($post_id, '_stock_status',true) == 'outofstock';
?>

<li class="product-list grid4 <?php if($stock_status == "1"){ ?>out-of-stock<?php }?>">
<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
<div class="row">
	<div class="large-5 small-5 columns">
		<a href="<?php the_permalink(); ?>">
			
 	 		<div class="product-img">
      		<div class="image-overlay"></div>
      		<div class="quick-view fa fa-search tip-top" data-prod="<?php echo $post->ID; ?>" data-tip="<?php _e('Quick View', 'ltheme_domain'); ?>"></div>
         		<div class="main-img"><?php echo $product->get_image('shop_catalog');?></div>
					
					<?php
						if ( $attachment_ids ) {
							$loop = 0;				
							foreach ( $attachment_ids as $attachment_id ) {
								$image_link = wp_get_attachment_url( $attachment_id );
								if ( ! $image_link )
									continue;
								$loop++;
								if ($loop == 1) break;
							}
						} 
					?>
					
	   	
	   	 	<?php if($stock_status == "1") { ?><div class="out-of-stock-label"><div class="text"><?php _e( 'Sold out', 'woocommerce' ); ?></div></div><?php }?>
  			</div>
		</a>
	</div>
		
		<div class="large-7 small-7 columns">
	      <div class="info">
	      	<?php $product_cats = strip_tags($product->get_categories('|', '', '')); ?>
	         
	          <a href="<?php the_permalink();?>"><p class="name"><?php the_title(); ?></p></a>
	          <?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
		       <?php if ( in_array( 'yith-woocommerce-wishlist/init.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { ?>

			<?php
				$link = array(
					'url'   => '',
					'label' => '',
					'class' => ''
				);

				$handler = apply_filters( 'woocommerce_add_to_cart_handler', $product->product_type, $product );

				switch ( $handler ) {
					case "variable" :
						$link['url'] 	= apply_filters( 'variable_add_to_cart_url', get_permalink( $product->id ) );
						$link['label'] 	= apply_filters( 'variable_add_to_cart_text', __( 'Select options', 'woocommerce' ) );
					break;
					case "grouped" :
						$link['url'] 	= apply_filters( 'grouped_add_to_cart_url', get_permalink( $product->id ) );
						$link['label'] 	= apply_filters( 'grouped_add_to_cart_text', __( 'View options', 'woocommerce' ) );
					break;
					case "external" :
						$link['url'] 	= apply_filters( 'external_add_to_cart_url', get_permalink( $product->id ) );
						$link['label'] 	= apply_filters( 'external_add_to_cart_text', __( 'Read More', 'woocommerce' ) );
					break;
					default :
						if ( $product->is_purchasable() ) {
							$link['url'] 	= apply_filters( 'add_to_cart_url', esc_url( $product->add_to_cart_url() ) );
							$link['label'] 	= apply_filters( 'add_to_cart_text', __( 'Add to cart', 'woocommerce' ) );
							$link['class']  = apply_filters( 'add_to_cart_class', 'add_to_cart_button' );
						} else {
							$link['url'] 	= apply_filters( 'not_purchasable_url', get_permalink( $product->id ) );
							$link['label'] 	= apply_filters( 'not_purchasable_text', __( 'Read More', 'woocommerce' ) );
						}
					break;
				}
				echo apply_filters( 'woocommerce_loop_add_to_cart_link', sprintf('<div data-href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="%s product_type_%s add-to-cart-grid-style2" data-tip="%s">
					<div class="cart-icon">
					<strong>ADD TO CART</strong>
		          	<span class="cart-icon-handle"></span>

		    </div></div>', esc_url( $link['url'] ), esc_attr( $product->id ), esc_attr( $product->get_sku() ), esc_attr( $link['class'] ), esc_attr( $product->product_type ), esc_html( $link['label'] ) ), $product, $link );
			?>
	      </div>

     <?php } ?>
     </div>
 	
    	
</div>
<?php woocommerce_get_template( 'loop/sale-flash.php' ); ?>
</li>
