<?php
/**
 * The template for displaying search forms in leetheme
 *
 * @package leetheme
 */
?>


<div class="row collapse search-wrapper">
<form method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
	<input type="search" class="field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" id="s" placeholder="<?php echo _e( 'Search', 'woocommerce' ); ?>&hellip;" />
    <button class="button secondary"><i class="icon-search"></i></button>
</form>
</div>

