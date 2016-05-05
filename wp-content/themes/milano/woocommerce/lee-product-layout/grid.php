<?php 
	$_delay = 200;
	$_count = 1;
?>
<div class="row">
	<ul class="<?php echo esc_attr($class_column);?> products-group">
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
</div>