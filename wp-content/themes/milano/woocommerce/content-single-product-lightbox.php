 <?php
	global $post, $product, $woocommerce;
	$attachment_ids = $product->get_gallery_attachment_ids();
	
?> 
           
<div class="row collapse">

<div class="large-5 columns">
	<div class="product-lightbox-inner product-info">
		<h1 itemprop="name" class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
		<?php do_action( 'woocommerce_single_product_lightbox_summary' ); ?>
	</div>    
    
 
</div>

<div class="large-7 columns">
	<div class="product-img">

    	<div class="owl-carousel owl-theme main-image-slider-1">
			

				<?php if ( has_post_thumbnail() ) : ?>
            	
				<?php
					$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), false, '' );
				?>
                
                <div>
                	<?php echo get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) ) ?>
                </div>
				
				<?php endif; ?>	
                
				<?php

					if ( $attachment_ids ) {
				
						$loop = 0;
						$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );						
						
						foreach ( $attachment_ids as $attachment_id ) {

							$classes = array( 'zoom' );
				
							if ( $loop == 0 || $loop % $columns == 0 )
								$classes[] = 'first';
				
							if ( ( $loop + 1 ) % $columns == 0 )
								$classes[] = 'last';
				
							$image_link = wp_get_attachment_url( $attachment_id );
				
							if ( ! $image_link )
								continue;
				
							$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
							$image_class = esc_attr( implode( ' ', $classes ) );
							$image_title = esc_attr( get_the_title( $attachment_id ) );
							
							printf( '<div>%s</div>', wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) ), wp_get_attachment_url( $attachment_id ) );
							
							$loop++;
						}
					}
				?>
			
		</div>
	</div>
	
</div>



