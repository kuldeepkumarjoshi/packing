<?php
 
    global $post, $product, $ros_opt;

    $permalinks     = get_option( 'woocommerce_permalinks' );
    $category_slug  = empty( $permalinks['category_base'] ) ? _x( 'product-category', 'slug', 'woocommerce' ) : $permalinks['category_base'];
 
?>

<div itemscope itemtype="http://schema.org/Product" id="product-<?php the_ID(); ?>" <?php post_class(); ?>> 
    
<div class="row">    
        <?php
             do_action( 'woocommerce_before_single_product' );
        ?>   

        <div class="large-9 columns product-sidebar-left">     
            <div class="row">
                <div class="large-7 small-12 columns product-gallery">        
                
                    <?php
                        do_action( 'woocommerce_before_single_product_summary' );
                    ?>
                
                </div>
                
                <div class="product-info large-5 small-12 columns left" style="position:relative;">
                        <?php
                            do_action( 'woocommerce_single_product_summary' );
                        ?>
                
                </div>
            </div> 

    
            <div class="row">
                <div class="large-12 columns">
                    <div class="product-details">
                           <div class="row">

                                <div class="large-12 columns ">
                                <?php woocommerce_get_template('single-product/tabs/tabs.php'); ?>
                                </div>
                            
                           </div>
                    </div>

                </div>
            </div>


            <div class="related-product">
                <?php
                    
                    do_action( 'woocommerce_after_single_product_summary' );

                ?>
            </div>
        </div>


        <div class="large-3 columns">
            <div class="inner">   
                 <?php dynamic_sidebar('product-sidebar'); ?>
            </div>

        </div>
    </div>
 
         
    
    <?php
    $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), false, '' );
?>



</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>