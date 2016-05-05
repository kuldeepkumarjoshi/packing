<?php
function lee_products_shortcode( $atts, $content = null ){
  	extract( shortcode_atts( array(
		'number'=> '8',
		'icon' => '',
		'el_class' => '',
		'cat' => '',
		'type'=>'best_selling',
		'style'=>'grid',
		'columns_number' => '5',
		'columns_number_small' => '1',
		'columns_number_tablet' => '3',
		), $atts ) 
  	);
  	ob_start();
  	if($type=='') return;
  	
	switch ($columns_number) {
		case '5':
			$class_column='large-block-grid-5 small-block-grid-2';
			break;
		case '4':
			$class_column='large-block-grid-4 small-block-grid-2';
			break;
		case '3':
			$class_column='large-block-grid-3 small-block-grid-1';
			break;
		case '2':
			$class_column='large-block-grid-2 small-block-grid-1';
			break;
		default:
			$class_column='large-block-grid-1 small-block-grid-1';
			break;
	}
	
	global $woocommerce;

	
	

	$_id = rand();
	$_count = 1;
	$show_rating = $is_deals = false;
	if($type=='top_rate') $show_rating=true;
	if($type=='deals') $is_deals=true;
	$loop = lee_woocommerce_query($type,$number, $cat);
	if ( $loop->have_posts() ) : ?>
		<?php $_total = $loop->found_posts; ?>
	    <div class="woocommerce<?php echo (($el_class!='')?' '.$el_class:''); ?>">
			<div class="inner-content">
				<?php wc_get_template(
					'lee-product-layout/'.$style.'.php',
					array( 
						'show_rating' => $show_rating,
						'_id'=>$_id,
						'loop'=>$loop,
						'columns_number'=>$columns_number,
						'columns_number_small'=>$columns_number_small,
						'columns_number_tablet'=>$columns_number_tablet,
						'class_column' => $class_column,
						'_total'=>$_total,
						'number'=>$number,
						'is_deals' => $is_deals,
						'type' => $type
					)
				);?>
			</div>
		</div>
	<?php endif;
	wp_reset_postdata();
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode('lee_products', 'lee_products_shortcode');


/* ADD MORE PRODUCT AJAX */
function infinite_scroll_header_function(){?>
		
	<script type="text/javascript">
		/* <![CDATA[ */
	    var ajaxurl = "<?php echo esc_js(admin_url('admin-ajax.php')); ?>";
	    /* ]]> */
	</script>

<?php }
add_action('wp_head', 'infinite_scroll_header_function');

function moreProduct(){
	$_delay = 0;
	$type = $_POST['type'];
	$post_per_page = $_POST['post_per_page'];
	$page = $_POST['page'];
	$is_deals = $_GET['is_deals'];
	ob_start();

	$loop = lee_woocommerce_query($type, $post_per_page, '', $page);
	if ( $loop->have_posts() ) : ?>
		<?php $_total = $loop->found_posts;

		if($_total){
	    	while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
				<?php 
					wc_get_template( 'content-product.php', array('is_deals' => $is_deals, '_delay' => $_delay, 'wrapper' => 'li') );
					$_delay+=200;
					if($_count==$columns_number){
						$_count=0;$_delay=200;
					}
					$_count++;

				?>
			<?php endwhile; ?>
		<?php }?>
	<?php endif;
	wp_reset_postdata();
	$output = ob_get_contents();
    ob_end_clean();
    echo $output;
	die();
}


add_action( 'wp_ajax_moreProduct', 'moreProduct' );
add_action( 'wp_ajax_nopriv_moreProduct', 'moreProduct' );