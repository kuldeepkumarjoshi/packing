<?php 
	$_delay = 200;
	$_count = 1;
	$infinite_id = rand();
?>

<div class="row">
	<ul class="<?php echo esc_attr($class_column);?> products-infinite products-group shortcode_<?=$infinite_id;?>" data-next-page="2" data-product-type="<?php echo $type; ?>" data-post-per-page="<?php echo $number; ?>" data-is-deals="<?php echo $is_deals; ?>" data-max-pages="<?php echo $loop->max_num_pages; ?>">
		<?php while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
			<?php
		        $class_fix = '';
		        // Store loop count we're currently on
		        if ( 0 == ( $_count - 1 ) % $columns_number || 1 == $columns_number )
		            $class_fix .= ' first';
		        if ( 0 == $_count % $columns_number )
		            $class_fix .= ' last';
		    ?>
			<!-- Product Item -->
			
			<?php 
				wc_get_template( 'content-product.php', array('is_deals' => $is_deals, '_delay' => $_delay, 'wrapper' => 'li') );
			?>
			<?php $_delay+=200; ?>
			<!-- End Product Item -->
			<?php
				if($_count==$columns_number){
					$_count=0;$_delay=200;
				}
				$_count++;
			?>
		<?php endwhile; ?>
	</ul>
	<div class="text-center">
		<?php if ($loop->max_num_pages > 1) {
		?>
			<div class="load-more-btn load-more <?php echo $infinite_id; ?>" data-infinite="<?php echo $infinite_id;?>"><?php _e('LOAD MORE','ltheme_domain'); ?><div class="load-more-icon fa fa-angle-double-down"></div></div>
		<?php } ?>
	</div>
	<!-- <nav>
	    <ul class="infinite-pagination">
	        <li class="prev"><?php previous_posts_link( '&laquo; PREV', $loop->max_num_pages) ?></li> 
	        <li class="next"><?php next_posts_link( 'NEXT &raquo;', $loop->max_num_pages) ?></li>
    	</ul>
	</nav> -->
</div>