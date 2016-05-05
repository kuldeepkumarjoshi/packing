<?php 
	global $woocommerce;
	global $woo_options;
	global $ros_opt;
	$header_transparent = '';
	$header_transparent = get_post_meta($wp_query->get_queried_object_id(), '_lee_header_transparent', true);
	if ($header_transparent){
		$header_classes = 'header-transparent';
	}
?>


<div class="header-wrapper header-type-2 <?php echo esc_attr($header_classes); ?>">
	<!-- Masthead -->
	<div class="sticky-wrapper">
		<header id="masthead" class="site-header">
			
			<div class="row header-container"> 
				<div class="mobile-menu show-for-small"><a href="#open-menu"><span class="icon-menu"></span></a></div><!-- end mobile menu -->
				<!-- Logo -->
				<div class="logo-wrapper">
					<?php leetheme_logo(); ?>
				</div>

				<!-- Main navigation - Full width style -->
				<div class="large-12 columns">
					<div class="wide-nav light-header hide-for-small hide-for-medium">
						<?php leetheme_get_main_menu(); ?>
					</div>
					<div class="cart-wishlist">
						<?php if(function_exists('wc_print_notices')) { ?> 
							<div class="mini-cart">
								<?php leetheme_mini_cart(); ?>
							</div>
						<?php } ?>
					</div>
				</div>
				<!-- Mini cart -->
			</div>
		</header>
	</div>
</div>