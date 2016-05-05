<?php
$output = $title = $title_align = $el_class = '';
extract(shortcode_atts(array(
    'title' => __("Title", "js_composer"),
    'title_align' => 'separator_align_center',
    'el_class' => '',
), $atts));
$el_class = $this->getExtraClass($el_class);
switch ($title_align) {
	case 'separator_align_center':
		$el_class .= ' text-center';
		break;
	case 'separator_align_left':
		$el_class .= ' text-left';
		break;
	case 'separator_align_right':
		$el_class .= ' text-right';
		break;
	default:
		$el_class .= ' text-center';
		break;
}

?>

<div class="title-block <?php echo esc_attr($el_class); ?>">
	<h4 class="heading-title">
		<span><?php echo esc_attr($title); ?></span>
	</h4>
	<div class="bery-hr medium <?php echo esc_attr($el_class); ?>"></div>
</div>
