<?php
/**
 * Product Loop Start
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

global $ros_opt, $woocommerce_loop, $products_per_row;
if(is_cart()){$woocommerce_loop['columns'] = 4;} 
if ($woocommerce_loop['columns'] == "") {$woocommerce_loop['columns'] = $ros_opt['products_per_row'];}

/* Add 1 column if no sidebar */
if ($ros_opt['category_sidebar'] == "no-sidebar") {
	$products_per_row = $ros_opt['products_per_row'] + 1;
	$woocommerce_loop['columns'] = $woocommerce_loop['columns'] + 1; 
}
?>
<div class="row"><div class="large-12 columns">

<?php if(!empty($woocommerce_loop)){ ?>
	<ul class="products thumb small-block-grid-1 large-block-grid-<?php echo $woocommerce_loop["columns"]; ?>" data-product-per-row = "<?php echo $woocommerce_loop['columns'] ?>">
<?php } else if (isset($ros_opt['products_per_row'])){ ?>
	<ul class="products thumb small-block-grid-1 large-block-grid-<?php echo $products_per_row; ?>" data-product-per-row = "<?php echo $products_per_row; ?>">
<?php } else { ?>
	<ul class="products thumb small-block-grid-1 large-block-grid-3" data-product-per-row = "3">
<?php } ?>
