<?php
function lee_menu_vertical_shortcode( $atts, $content = null ){
	extract( shortcode_atts( array(
		'title' => '',
		'menu' => ''
	), $atts ) );
	if($menu){
		ob_start();?>
			<div class="vertical-menu">
				<?php if($title){?>
					<div class="title-inner">
						<h4 class="section-title">
							<span><?php echo esc_attr($title);?></span>
						</h4>
					</div>
				<?php }?>
				<div class="vertical-menu-container">
					<ul id="vertical-menu-wrapper">
				        <?php  
				            wp_nav_menu(array(
				                'menu' 			=> $menu,
				                'container'     => false,
				                'items_wrap'    => '%3$s',
				                'depth'         => 3,
				                'walker'        => new LeethemeNavDropdown
				            ));
				        ?>
			        </ul>
				</div>
		    </div>
		<?php 
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
}
add_shortcode('lee_menu_vertical', 'lee_menu_vertical_shortcode');