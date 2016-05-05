<?php
global $woo_options;
global $woocommerce;
global $ros_opt;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php if (function_exists('wp_site_icon')){ ?>
		<link rel="shortcut icon" href="<?php if ($ros_opt['site_favicon']) {echo esc_attr($ros_opt['site_favicon']);} else {echo get_template_directory_uri().'/favicon.png';} ?>" />
	<?php } ?>

	<!-- Demo Purpose Only. Should be removed in production -->
	<?php if (isset($ros_opt['demo_show']) && $ros_opt['demo_show']) {?>
		<link rel="stylesheet" property='stylesheet' href="<?php echo get_template_directory_uri();?>/css/demo/config.css">
	<?php } ?>
	<!-- Demo Purpose Only. Should be removed in production : END -->

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	
<!-- For demo -->
<?php
if (isset($ros_opt['demo_show']) && $ros_opt['demo_show']) {
	get_template_part('css/demo/config');
}
?>
<!-- End For demo -->

<div id="wrapper" class="fixNav-enabled">
	<?php
		if ($ros_opt['fixed_nav']):
			get_template_part('headers/header-sticky');
	 	endif;
	 ?>
	 
	<?php
		$ht = ''; $hstrucutre = ''; $custom_header = '';  $page_slider = ''; $header_classes = '';
		if (isset($post->ID)){
			$custom_header = get_post_meta($wp_query->get_queried_object_id(), '_lee_custom_header', true);
			if (!empty($custom_header)){
				$ht = $custom_header;
			}
			else
			{
				$ht = apply_filters('custom_header_filter',$ht);
			}
			$hstrucutre = bery_get_header_structure($ht);
		}else{
			$hstrucutre = 1;
		}
		
		get_template_part('headers/header-structure', $hstrucutre);
	?>
	

<div id="main-content" class="site-main light">
<?php  if(function_exists('wc_print_notices')) {wc_print_notices();}?>