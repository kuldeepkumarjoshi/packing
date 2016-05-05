<?php
/**
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit;
global $ros_opt;

if (isset($_GET['product-right-sidebar'])){
	$ros_opt['product_sidebar'] = 'right_sidebar';
}elseif (isset($_GET['product-left-sidebar'])){
	$ros_opt['product_sidebar'] = 'left_sidebar';
}elseif (isset($_GET['product-no-sidebar'])){
	$ros_opt['product_sidebar'] = 'no_sidebar';
}

get_header('shop'); ?>
<?php leetheme_get_breadcrumb(); ?>

<div class="row product-page">
<div class="large-12 columns">

	<?php
		do_action('woocommerce_before_main_content');
	?>

		<?php while ( have_posts() ) : the_post(); ?>

		<?php 
		if($ros_opt['product_sidebar'] == "right_sidebar") {
			woocommerce_get_template_part( 'content', 'single-product-right-sidebar'); 
		} else if($ros_opt['product_sidebar'] == "left_sidebar") {
			woocommerce_get_template_part( 'content', 'single-product-left-sidebar'); 
		} else if($ros_opt['product_sidebar'] == "no_sidebar"){
			woocommerce_get_template_part( 'content', 'single-product' ); 
		}
		?>

		<?php endwhile;?>

	<?php
		do_action('woocommerce_after_main_content');
	?>


</div>
</div>

<?php get_footer('shop'); ?>