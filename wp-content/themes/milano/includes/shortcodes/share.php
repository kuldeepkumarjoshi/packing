<?php
function shareShortcode($atts, $content = null) {
	extract(shortcode_atts(array(
		'title'  => '',
		'size' => '',
		'tooltip' => 'top',
		'style' => '',
	), $atts));
	global $post, $ros_opt;
	if (isset($post->ID )) {
		$permalink = get_permalink($post->ID );
		$featured_image =  wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
		$featured_image_2 = $featured_image['0'];
		$post_title = rawurlencode(get_the_title($post->ID));
	}
	if($title) $title = '<span>'.$title.'</span>';

	ob_start();
	?>

	<ul class="social-icons share-row <?php echo esc_attr($size); ?> <?php echo esc_attr($style); ?>">
		<?php echo esc_attr($title); ?>
		<?php if($ros_opt['social_icons']['facebook']) { ?>
			<li>
			  	<a href="http://www.facebook.com/sharer.php?u=<?php echo esc_url($permalink); ?>" target="_blank" class="icon tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Share on Facebook','ltheme_domain'); ?>"><span class="icon-facebook"></span>
			  		<svg class="circle" xmlns="http://www.w3.org/2000/svg" height="38" width= "38">
		  				<circle stroke="#3a589d" fill="none" r="18" cy="19" cx="19">
	  				</svg>
			  	</a>
			</li>
		<?php } ?>
		<?php if($ros_opt['social_icons']['twitter']) { ?>
			<li>
			  	<a href="https://twitter.com/share?url=<?php echo esc_url($permalink); ?>" target="_blank" class="icon tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Share on Twitter','ltheme_domain'); ?>"><span class="icon-twitter"></span>
			  		<svg class="circle" xmlns="http://www.w3.org/2000/svg" height="38" width= "38">
		  				<circle stroke="#2478ba" fill="none" r="18" cy="19" cx="19">
	  				</svg>
			  	</a>
			</li>
		<?php } ?>
		<?php if($ros_opt['social_icons']['email']) { ?>
			<li>
			  	<a href="mailto:enteryour@addresshere.com?subject=<?php echo esc_attr($post_title); ?>&amp;body=Check%20this%20out:%20<?php echo esc_url($permalink); ?>" class="icon tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Email to a Friend','ltheme_domain'); ?>"><span class="icon-envelop"></span>
			  		<svg class="circle" xmlns="http://www.w3.org/2000/svg" height="38" width= "38">
		  				<circle stroke="<?php echo $ros_opt['color_primary'];?>" fill="none" r="18" cy="19" cx="19">
	  				</svg>
			  	</a>
			</li>
		<?php } ?>
		<?php if($ros_opt['social_icons']['pinterest']) { ?>
			<li>
			  	<a href="//pinterest.com/pin/create/button/?url=<?php echo esc_url($permalink); ?>&amp;media=<?php echo esc_attr($featured_image_2); ?>&amp;description=<?php echo esc_attr($post_title); ?>" target="_blank" class="icon tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Pin on Pinterest','ltheme_domain'); ?>"><span class="icon-pinterest"></span>
			  		<svg class="circle" xmlns="http://www.w3.org/2000/svg" height="38" width= "38">
		  				<circle stroke="#cb2320" fill="none" r="18" cy="19" cx="19">
	  				</svg>
			  	</a>
			</li>	
		<?php } ?>
		<?php if($ros_opt['social_icons']['googleplus']) { ?>
			<li>
			  	<a href="//plus.google.com/share?url=<?php echo esc_url($permalink); ?>" target="_blank" class="icon tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Share on Google+','ltheme_domain'); ?>"><span class="icon-google-plus"></span>
			  		<svg class="circle" xmlns="http://www.w3.org/2000/svg" height="38" width= "38">
		  				<circle stroke="#dd4e31" fill="none" r="18" cy="19" cx="19">
	  				</svg>
			  	</a>
			</li>
		<?php } ?>
    </ul>
    
    <?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
} 
add_shortcode('share','shareShortcode');


function followShortcode($atts, $content = null) {
	$sliderrandomid = rand();
	extract(shortcode_atts(array(
		'size' => 'normal',
		'tooltip' => 'top',
		'style' => '',
		'title' => '',
		'twitter' => '',
		'facebook' => '',
		'pinterest' => '',
		'email' => '',
		'googleplus' => '',
		'instagram' => '',
		'rss' => '',
		'linkedin' => '',
		'youtube' => '',
		'flickr' => '',
	), $atts));
	ob_start();
	?>

    <div class="social-icons <?php echo esc_attr($size);?> <?php echo esc_attr($style); ?>">

    	<?php if($title){?>
    	<span><?php echo esc_attr($title); ?></span>
		<?php }?>

    	<?php if($facebook){?>
    	<a href="<?php echo esc_url($facebook); ?>" target="_blank"  class="icon icon_facebook tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Follow us on Facebook','ltheme_domain') ?>"><span class="icon-facebook"></span></a>
		<?php }?>
		<?php if($twitter){?>
		       <a href="<?php echo esc_url($twitter); ?>" target="_blank" class="icon icon_twitter tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Follow us on Twitter','ltheme_domain') ?>"><span class="icon-twitter"></span></a>
		<?php }?>
		<?php if($email){?>
		       <a href="mailto:<?php echo esc_url($email); ?>" target="_blank" class="icon icon_email tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Send us an email','ltheme_domain') ?>"><span class="icon-envelop"></span></a>
		<?php }?>
		<?php if($pinterest){?>
		       <a href="<?php echo esc_url($pinterest); ?>" target="_blank" class="icon icon_pintrest tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Follow us on Pinterest','ltheme_domain') ?>"><span class="icon-pinterest"></span></a>
		<?php }?>
		<?php if($googleplus){?>
		       <a href="<?php echo esc_url($googleplus); ?>" target="_blank" class="icon icon_googleplus tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Follow us on Google+','ltheme_domain')?>"><span class="icon-google-plus"></span></a>
		<?php }?>
		<?php if($instagram){?>
		       <a href="<?php echo esc_url($instagram); ?>" target="_blank" class="icon icon_instagram tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Follow us on Instagram','ltheme_domain')?>"><span class="icon-instagram"></span></a>
		<?php }?>
		<?php if($rss){?>
		       <a href="<?php echo esc_url($rss); ?>" target="_blank" class="icon icon_rss tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Subscribe to RSS','ltheme_domain') ?>"><span class="icon-feed"></span></a>
		<?php }?>
		<?php if($linkedin){?>
		       <a href="<?php echo esc_url($linkedin); ?>" target="_blank" class="icon icon_linkedin tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('LinkedIn','ltheme_domain') ?>"><span class="icon-linkedin"></span></a>
		<?php }?>
		<?php if($youtube){?>
		       <a href="<?php echo esc_url($youtube); ?>" target="_blank" class="icon icon_youtube tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('YouTube','ltheme_domain') ?>"><span class="icon-youtube"></span></a>
		<?php }?>
		<?php if($flickr){?>
		       <a href="<?php echo esc_url($flickr); ?>" target="_blank" class="icon icon_flickr tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Flickr','ltheme_domain') ?>"><span class="icon-flickr"></span></a>
		<?php }?>
     </div>
    	

	<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode("follow", "followShortcode");

?>
