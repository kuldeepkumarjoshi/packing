<?php
    global $product;
    $time_sale = get_post_meta( $product->id, '_sale_price_dates_to', true );

?>
<div class="product-block product-grid product product-deals">
    <div class="product-image">
        <div class="image">
            <a href="<?php the_permalink(); ?>">
                <?php
                    /**
                     * woocommerce_before_shop_loop_item_title hook
                     *
                     * @hooked woocommerce_show_product_loop_sale_flash - 10
                     * @hooked woocommerce_template_loop_product_thumbnail - 10
                     */
                    do_action( 'woocommerce_before_shop_loop_item_title' );

                ?>
                <span class="countdown" data-countdown="<?php echo esc_attr(date('M j Y H:i:s O',$time_sale)); ?>"></span>
            </a>
        </div>
    </div>
    
    <h4 class="name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
    
    <div class="product-meta">
        <?php
        /**
         * woocommerce_after_shop_loop_item_title hook
         *
         * @hooked woocommerce_template_loop_rating - 5
         * @hooked woocommerce_template_loop_price - 10
         */
        do_action('woocommerce_after_shop_loop_item_title');
        ?>
    </div>


    <div class="button-groups woocommerce-action">
        <div class="button-item clearfix">
            <?php do_action('woocommerce_after_shop_loop_item'); ?>
        </div>
    </div>
    
</div>