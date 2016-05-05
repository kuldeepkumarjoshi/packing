<?php
function lee_brands_shortcode( $atts, $content = null ){
  	extract(
  		shortcode_atts(
	  		array(
				'title' => '',
			    'custom_links' => '',
			    'images' => '',
			    'el_class' => '',
			    'columns_number' => '6',
				'columns_number_small' => '1',
				'columns_number_tablet' => '4',
			    'layout' => 'carousel'
	  		),
	  		$atts 
  		) 
  	);
  	ob_start();
	$custom_links = explode( ',', $custom_links);
	$images = explode( ',', $images);

	if(count($images)>0){
		$delay = 0; ?>
		<div class="lee-brands">
			<div class="row">
				<?php if($layout=='carousel'){ ?>
					<div class="brands-group lee-slider"  data-columns="<?php echo esc_attr($columns_number);?>" data-columns-small = "<?php echo esc_attr($columns_number_small); ?>" data-columns-tablet="<?php echo esc_attr($columns_number_tablet); ?>">
						<?php foreach ($images as $key => $image) { ?>
							<div class="brands-item wow bounceIn text-center" data-wow-duration="1s" data-wow-delay="<?php echo esc_attr( $delay ); ?>ms">
								<?php 
									$img = wpb_getImageBySize(array( 'attach_id' => $image, 'thumb_size' => 'full' ));
									$link_start = $link_end = '';
									if ( isset( $custom_links[$key] ) && $custom_links[$key] != '' ) {
								        $link_start = '<a href="'.$custom_links[$key].'">';
								        $link_end = '</a>';
								    }
								    echo $link_start.$img['thumbnail'].$link_end;
								?>
							</div>
							<?php $delay+=200; ?>
						<?php } ?>
		            </div>
	            <?php }else{ ?>
					<?php foreach ($images as $key => $image) { ?>
						<div class="wow bounceIn <?php echo esc_attr( $class_column ); ?>" data-wow-duration="1s" data-wow-delay="<?php echo esc_attr( $delay ); ?>ms">
							<?php 
								$img = wpb_getImageBySize(array( 'attach_id' => $image, 'thumb_size' => 'full' ));
								$link_start = $link_end = '';
								if ( isset( $custom_links[$key] ) && $custom_links[$key] != '' ) {
							        $link_start = '<a href="'.$custom_links[$key].'">';
							        $link_end = '</a>';
							    }
							    echo $link_start.$img['thumbnail'].$link_end;
							?>
						</div>
						<?php $delay+=200;?>
					<?php }?>
	            <?php }?>
			</div>
		</div>
	<?php }
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode('lee_brands', 'lee_brands_shortcode');