<?php
/*
Template name: Page Checkout
*/
get_header(); ?>

<?php leetheme_get_breadcrumb(); ?>

<div  class="container-wrap page-checkout">
<div class="row">
<div id="content" class="large-12 columns" role="main">

<?php while ( have_posts() ) : the_post(); ?>

	<?php  if(function_exists('is_wc_endpoint_url')){ ?>
		<?php if (!is_wc_endpoint_url('order-received')){ ?>
			<div class="checkout-breadcrumb">
				<a href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>">
					<div class="title-cart">
						<span>1</span>
						<p><?php _e('Shopping Cart', 'ltheme_domain'); ?></p>
					</div>
				</a>
				<a href="<?php echo esc_url( $woocommerce->cart->get_checkout_url()); ?>">
					<div class="title-checkout">
						<span>2</span>
						<p><?php _e('Checkout details', 'ltheme_domain'); ?></p>
					</div>
				</a>
				<div class="title-thankyou">
					<span>3</span>
					<p><?php _e('Order Complete', 'ltheme_domain'); ?></p>
				</div>
			</div>
		<?php } ?>
	<?php } else { ?> 
		<div class="checkout-breadcrumb">
			<div class="title-cart">
					<span>1</span>
					<p><?php _e('Shopping Cart', 'ltheme_domain'); ?></p>
				</div>
				<div class="title-checkout">
					<span>2</span>
					<p><?php _e('Checkout details', 'ltheme_domain'); ?></p>
				</div>
				<div class="title-thankyou">
					<span>3</span>
					<p><?php _e('Order Complete', 'ltheme_domain'); ?></p>
				</div>
		</div>
	<?php } ?>
<?php the_content(); ?>

			
<?php endwhile; ?>
</div>
</div>
</div>

<?php get_footer(); ?>