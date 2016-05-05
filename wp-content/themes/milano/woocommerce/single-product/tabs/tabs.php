<?php
/**
 * Single Product tabs / and sections
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );
global $ros_opt;

if (!empty( $tabs ) )  : ?>

	<div class="lee-tabs-content woocommerce-tabs">
		<ul class="lee-tabs text-center">
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<li class="<?php echo $key ?>_tab lee-tab<?php echo ($key=='description')? ' active':'';?>">
					<a href="javascript:void(0);" data-id="#lee-tab-<?php echo $key ?>">
						<h4><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ?></h4>
						<span class="bery-hr medium text-center"></span>
					</a>
				</li>
				<li class="separator">X</li>
			<?php endforeach; ?>

			<?php 
			
			if($ros_opt['tab_title']){
				?> 
				<li class="additional-tab lee-tab">
					<a href="javascript:void(0);" data-id="#lee-tab-additional">
						<h4><?php echo esc_attr($ros_opt['tab_title'])?></h4>
						<span class="bery-hr medium text-center"></span>
					</a>
				</li>
				<li class="separator">/</li>
			<?php } ?>
		</ul>
		<div class="lee-panels">
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<div class="lee-panel entry-content<?php echo ($key=='description')? ' active':'';?>" id="lee-tab-<?php echo $key ?>">
					<?php call_user_func( $tab['callback'], $key, $tab ) ?>
				</div>
			<?php endforeach; ?>

			<?php 
				if($ros_opt['tab_title']){ ?> 
				<div class="lee-panel entry-content" id="lee-tab-additional">
					<?php echo do_shortcode($ros_opt['tab_content']);?>
				</div>	
			<?php } ?>
		</div>
	</div>

<?php endif;?>