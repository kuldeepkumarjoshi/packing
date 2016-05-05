<?php
function shortcode_recent_post($atts, $content = null) {
	global $ros_opt;
	extract(shortcode_atts(array(
		"title" => '',
		"align" => '',
		'show_type' => '0',
		"posts" => '8',
		"category" => '',
		'columns_number' => '2',
      	'columns_number_small' => '1',
     	'columns_number_tablet' => '2',
	), $atts));
	ob_start();
	?>
	<?php if ($align == 'center') $align = 'text-center'; ?>

		<?php if($title != ''){?> 
			<div class="row">
				<div class="large-12 columns <?php echo esc_attr($align); ?>">
					<h3 class="section-title"><span><?php echo esc_attr($title); ?></span></h3>
					<div class="bery-hr medium"></div>
				</div>
			</div>
		<?php } ?>
		<?php
	        $args = array(
	            'post_status' => 'publish',
	            'post_type' => 'post',
				'category_name' => $category,
	            'posts_per_page' => $posts
	        );

	        $recentPosts = new WP_Query( $args );

	        if ( $recentPosts->have_posts() ) {
	        	if($show_type == 1) include get_template_directory() . '/blogs/latestblog_grid.php';
	        	else include get_template_directory() . '/blogs/latestblog_carousel.php';
	        }
	        wp_reset_postdata();
        ?>
	    
	<?php
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	?>
<?php 
}
add_shortcode("recent_post", "shortcode_recent_post");

function string_limit_words($string, $word_limit) {
	$words = explode(' ', $string, ($word_limit + 1));
	if(count($words) > $word_limit)
	array_pop($words);
	return implode(' ', $words);
}
