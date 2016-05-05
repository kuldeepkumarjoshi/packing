<?php global $product, $post;?>
<?php 
    $class = '';
    if(isset($is_animate) && $is_animate){
        $class = ' wow fadeInUp';
    }
    if(!isset($delay)){
        $delay = 0;
    }
?>
<div class="row item-product-widget clearfix<?php echo esc_attr($class); ?>" data-wow-duration="1s" data-wow-delay="<?php echo esc_attr($delay); ?>ms">
    <div class="large-4 medium-6 small-4 columns images">
        <?php echo $product->get_image('shop_thumbnail'); ?>
        <?php //echo get_the_post_thumbnail($post->ID, 'shop_catalog'); ?>
        <div class="btn-link quick-view tip-top" data-prod="<?php echo $post->ID; ?>" data-tip="<?php _e('Quick View', 'ltheme_domain'); ?>">
            <div class="quick-view-icon">
                <span class="fa fa-search"></span>
            </div>
        </div>
    </div>
    <div class="large-8 medium-6 small-8 columns product-meta">
        <div class="product-title separator">
            <a href="<?php echo esc_url( get_permalink( $product->id ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
                <?php echo esc_attr($product->get_title()); ?>
            </a>
        </div>
        <?php if ( $rating_html = $product->get_rating_html() ) { ?>
        <?php echo $rating_html; ?>
        <?php } else { ?>
            <div class="star-rating"></div>
        <?php } ?>
        <div class="price separator">
            <?php echo $product->get_price_html(); ?>
        </div>
        <?php add_to_cart_btn('large');?>
    </div>
</div>