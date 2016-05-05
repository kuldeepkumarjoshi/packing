<?php global $ros_opt; ?>
<?php do_action( 'before' ); ?>
		<?php if(!isset($ros_opt['topbar_show']) || $ros_opt['topbar_show']){ ?>
	<div id="top-bar">
		<div class="row">
			<div class="large-12 columns">
				<div class="show-for-small small-3 columns text-center">
					<a href="<?php echo esc_url(home_url('/'));?>my-account/">
						<i class="fa fa-user"></i>
				</a>
				</div>
				<div class="show-for-small small-3 columns text-center">
					<a href="<?php echo esc_url(home_url('/'));?>my-account/view-order/">
						<i class="fa fa-archive"></i>
				</a>
				</div>
				<div class="show-for-small small-3 columns text-center">
					<a href="<?php echo esc_url(home_url('/'));?>my-account/wishlist/">
						<i class="fa fa-heart"></i>
				</a>
				</div>
				<div class="show-for-small small-3 columns text-center">
					<a href="<?php echo esc_url(home_url('/'));?>my-account/track-your-order/">
						<i class="fa fa-truck"></i>
				</a>
				</div>
				<div class="left-text left hide-for-small" style=" text-transform: lowercase !important;">
					<div ><?php if(isset($ros_opt['topbar_left'])){echo do_shortcode($ros_opt['topbar_left']);}?></div>
				</div>
				<div class="right-text right">
					<?php if (isset($ros_opt['switch_lang']) && $ros_opt['switch_lang']) {?>
						<div class="language-filter">
							<?php echo lee_language_flages(); ?>
						</div>
					<?php } ?>
					 <?php if ( has_nav_menu( 'top_bar_nav' ) ) : ?>
					<?php
							wp_nav_menu(array(
								'theme_location' => 'top_bar_nav',
								'menu_class' => 'top-bar-nav',
								'before' => '',
								'after' => '',
								'link_before' => '',
								'link_after' => '',
								'depth' => 2,
								'fallback_cb' => false,
								'walker' => new LeethemeNavDropdown
							));
					?>
					 <?php else: ?>
                        Define your top bar navigation in <b>Apperance > Menus</b>
                    <?php endif; ?>
				</div>
			</div>
		</div>
	</div>
<?php }?>
