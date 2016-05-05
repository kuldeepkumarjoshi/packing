<?php
/**
 * The template for displaying search forms in leetheme
 *
 * @package leetheme
 */
?>


 <?php if (shortcode_exists('yith_woocommerce_ajax_search')) {?>
             <?php echo do_shortcode('[yith_woocommerce_ajax_search]'); ?>
 <?php } else { ?>

<div class="row collapse search-wrapper">
<form method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	   		<input type="search" class="field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" id="s" placeholder="<?php echo _e( 'Search', 'woocommerce' ); ?>&hellip;" />
	   		  <?php if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { ?>
              <input type="hidden" name="post_type" value="product">
          	  <?php } ?>
	    <button class="button secondary postfix"><i class="icon-search"></i></button>
</form>
</div>

<?php } ?>