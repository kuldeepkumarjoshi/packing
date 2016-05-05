<?php $_delay = 200;?>
<div class="row group-slider">
    <div class="slider products-group lee-slider" data-columns="<?php echo esc_attr($columns_number);?>" data-columns-small="<?php echo esc_attr($columns_number_small); ?>" data-columns-tablet="<?php echo esc_attr($columns_number_tablet); ?>">
		<?php while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
			<?php
				wc_get_template( 'content-product.php', array('is_deals' => $is_deals, '_delay' => $_delay) );
				$_delay += 200;
			?>
		<?php endwhile; ?>
	</div>
</div>
