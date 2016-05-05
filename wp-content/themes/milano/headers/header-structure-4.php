<?php 
	global $woocommerce;
	global $woo_options;
	global $ros_opt;
?>


	<div class="header-wrapper header-type-4">
		<!-- Top bar -->
		<?php get_template_part('headers/top-bar'); ?>

		<!-- Masthead -->
		<div class="sticky-wrapper">
			<header id="masthead" class="site-header">
				<div class="row header-container"> 
					<div class="mobile-menu show-for-small"><a href="#open-menu"><span class="icon-menu"></span></a></div><!-- end mobile menu -->

					<!-- Logo -->
					<div class="large-12 columns text-center">
						<?php leetheme_logo(); ?>
					</div>
					
				</div>


			</header>
		</div>

		<!-- Main navigation - Full width style -->
		<div class="wide-nav light-header nav-left">
			<div class="row">
				<div class="large-12 columns">
					<?php leetheme_get_main_menu(); ?>
				</div><!-- .large-12 -->
			</div><!-- .row -->
		</div><!-- .wide-nav -->
	</div>