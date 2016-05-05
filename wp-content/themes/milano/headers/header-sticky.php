<?php global $ros_opt; ?>
<div class="fixed-header-area hide-for-small hide-for-medium">
	<div class="fixed-header">
		<div class="row header-container"> 
			<!-- Logo -->
			<div class="logo-wrapper">
				 <div class="logo">
			        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?> - <?php bloginfo( 'description' ); ?>" rel="home">
			            <?php if(isset($ros_opt['site_logo'])){
			                $site_title = esc_attr( get_bloginfo( 'name', 'display' ) );
			                echo '<img src="'.$ros_opt['site_logo'].'" class="header_logo" alt="'.$site_title.'"/>';
			            } else {bloginfo( 'name' );}?>
			        </a>
			    </div>
			</div>
			<!-- Main navigation - Full width style -->
			<div class="large-12 columns">
				<div class="wide-nav light-header">
					 <div class="nav-wrapper">
			            <ul class="header-nav">
			                <?php if ( has_nav_menu( 'primary' ) ) : ?>
			                    <?php  
			                    wp_nav_menu(array(
			                        'theme_location' => 'primary',
			                        'container'       => false,
			                        'items_wrap'      => '%3$s',
			                        'depth'           => 5,
			                        'walker'          => new LeethemeNavDropdown
			                    ));
			                ?>
			              <?php else: ?>
			                  <li>Please Define main navigation in <b>Apperance > Menus</b></li>
			              <?php endif; ?>                               
			            </ul>
			        </div><!-- nav-wrapper -->
				</div><!-- .wide-nav -->
				<div class="cart-wishlist">
					<!-- HEADER/Show mini cart -->
					<?php if(function_exists('wc_print_notices')) { ?> 
						<div class="wish-list-link">
							<a href="<?php echo get_permalink(5);?>" title="">
								<ul class="wish-list-inner">
									<li class="wish-list-icon"><i class="pe-7s-like"></i></li>
					            </ul>
				            </a>
						</div>
						<div class="mini-cart">
							<?php leetheme_mini_cart(); ?>
						</div><!-- .mini-cart -->
					<?php } ?>
				</div>
			</div>
		</div>
		<!-- </header> -->
	</div>
</div>