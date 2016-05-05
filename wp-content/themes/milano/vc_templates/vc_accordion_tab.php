<?php
global $lee_collapses_item;
$output = $title = '';

extract(shortcode_atts(array(
	'title' => __("Section", "js_composer")
), $atts));

$lee_collapses_item[] = array('title'=>$title,'content'=>wpb_js_remove_wpautop($content));