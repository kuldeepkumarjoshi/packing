<?php 
	global $woocommerce;
	global $woo_options;
	global $ros_opt;
?>


	<div class="header-wrapper header-type-1">
		<!-- Top bar -->
		<?php get_template_part('headers/top-bar'); ?>

		<!-- Masthead -->
		<div class="sticky-wrapper">
			<header id="masthead" class="site-header ">
				
				<div class="row header-container"> 
					<div class="small-4 columns mobile-menu show-for-small"><a href="#open-menu"><span class="icon-menu"></span></a></div><!-- end mobile menu -->
					<!-- Logo -->
					<div class="large-4 hide-for-small hide-for-medium columns">
						<div class="wide-nav-search">
							<?php if(function_exists('get_product_search_form')) { 
									get_product_search_form();
								} ?>		
						</div>
					</div>
					<!-- Search -->
					<div class="large-4 small-4 columns text-center">
						<?php leetheme_logo(); ?>
					</div>

					<!-- Mini cart -->
					<div class="large-4 small-4 columns">
						<div class="cart-wishlist">
							<!-- HEADER/Show mini cart -->
							<?php if(function_exists('wc_print_notices')) { ?> 
								<div class="wish-list-link">
									<a href="<?php echo esc_url(home_url('/'));?>my-account/wishlist/" title="">
										<ul class="wish-list-inner">
											<li class="wish-list-icon"><i class="pe-7s-like"></i></li>
							            </ul>
						            </a>
								</div>
								<div class="mini-cart">
									<?php leetheme_mini_cart(); ?>
								</div><!-- .mini-cart -->
							<?php } ?>
						</div><!-- .header-nav -->
					</div>


				</div>


			</header>
		</div>

		<!-- Main navigation - Full width style -->
		<div class="wide-nav nav-bar-center">
			<div class="row">
				<div class="large-12 columns">
					<?php leetheme_get_main_menu(); ?>
				</div><!-- .large-12 -->
			</div><!-- .row -->
		</div><!-- .wide-nav -->
	</div>