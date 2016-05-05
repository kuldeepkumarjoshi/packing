<?php
/**
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.4.0
 */

global $product, $woocommerce_loop, $ros_opt, $post;
if(isset($is_deals) && $is_deals) $time_sale = get_post_meta( $product->id, '_sale_price_dates_to', true );
$attachment_ids = $product->get_gallery_attachment_ids();

if ( ! $product->is_visible() )
	return;
//$post_id = $post->ID;
$stock_status = get_post_meta($post->ID, '_stock_status',true) == 'outofstock';

$_wrapper = 'div';
if(isset($wrapper) && $wrapper == 'li')
	$_wrapper = $wrapper;
?>

<?php if (isset($ros_opt['animated_products'])){?>
	<<?php echo $_wrapper.' ';?> class="wow fadeInUp product-item <?php echo $ros_opt['animated_products']; ?> grid1 <?php if($stock_status == "1"){ ?>out-of-stock<?php }?>" data-wow-duration="1s" data-wow-delay="<?php echo esc_attr($_delay);?>ms">
<?php }else{ ?>
	<<?php echo $_wrapper.' ';?> class="product-item <?php echo $ros_opt['animated_products']; ?> grid1 <?php if($stock_status == "1"){ ?>out-of-stock<?php }?>" data-wow-duration="1s" data-wow-delay="<?php echo esc_attr($_delay);?>ms">
<?php } ?>

<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

<div class="inner-wrap<?php echo (isset($is_deals) && $is_deals) ? ' product-deals':'';?>">
    <div class="product-img">

      	<a href="<?php the_permalink(); ?>"><div class="image-overlay"></div>
         <div class="main-img"><?php echo $product->get_image('shop_catalog');?></div>
			<?php
				if ( $attachment_ids ) {
					$loop = 0;
					foreach ( $attachment_ids as $attachment_id ) {
						$image_link = wp_get_attachment_url( $attachment_id );
						if ( ! $image_link )
							continue;
						$loop++;
						printf( '<div class="back-img back">%s</div>', wp_get_attachment_image( $attachment_id, 'shop_catalog' ) );
						if ($loop == 1) break;
					}
				} else {
				?>
                <div class="back-img"><?php echo $product->get_image('shop_catalog'); ?></div>
                <?php
				}
			?>
			<?php if(isset($is_deals) && $is_deals){?><span class="countdown" data-countdown="<?php echo esc_attr(date('M j Y H:i:s O',$time_sale)); ?>"></span><?php }?>
		</a>
	   	<?php if($stock_status == "1") { ?><div class="out-of-stock-label"><div class="text"><?php _e( 'Sold out', 'woocommerce' ); ?></div></div><?php }?>
		<?php woocommerce_get_template( 'loop/sale-flash.php' ); ?>

		<!-- Product interactions button-->
		<div class="product-interactions">
			<?php //add_to_cart_btn();?>
			<div class="add_to_cart_button product_type_simple tip-top add-to-cart-grid btn-link" data-tip="<?php _e('View Product', 'ltheme_domain'); ?>">
				<a href="<?php the_permalink(); ?>">
				<div class="cart-icon">
					<span class="fa fa-shopping-cart"></span>
				</div>
			</a>
			</div>
		<!--	<div class="btn-link quick-view tip-top" data-prod="<?php// echo $post->ID; ?>" data-tip="<?php //_e('Quick View', 'ltheme_domain'); ?>">
				<div class="quick-view-icon">
					<span class="fa fa-search"></span>
				</div>
			</div>-->
			<div class="btn-link btn-compare tip-top" data-prod="<?php echo $post->ID; ?>" data-tip="Compare">
				<div class="compare-icon">
					<span class="fa fa-exchange"></span>
				</div>
			</div>
			<div class="btn-link btn-wishlist tip-top" data-prod="<?php echo $post->ID; ?>" data-tip="Wishlist">
				<div class="wishlist-icon">
					<span class="fa fa-heart-o"></span>
				</div>
			</div>

			<div class="add-to-link">
				<?php
					if (
						in_array( 'yith-woocommerce-wishlist/init.php', apply_filters('active_plugins', get_option( 'active_plugins' )))){
                   		echo do_shortcode('[yith_wcwl_add_to_wishlist]');
                   	}
               		?>
               		<div class="woocommerce-compare-button">
               			<?php
	                   	if (in_array( 'yith-woocommerce-compare/init.php', apply_filters('active_plugins', get_option('active_plugins')))){
	                   		echo do_shortcode('[yith_compare_button]');
	                   	}
		            	?>
	            	</div>
            </div>
        </div>

      </div>

      <div class="info">
			<p class="name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
          	<?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
          	<div class ="product-des">
				<?php echo esc_attr(substr(str_replace('<p>','',apply_filters( 'woocommerce_short_description', $post->post_excerpt )),0,250)).'...'; ?>
			</div>
		   <?php //} ?>
      </div>

</div>
</<?php echo $_wrapper;?>>
