<?php
/**
 * Shortcode attributes
 * @var $atts
 * @var $tab_id
 * @var $title
 * @var $content - shortcode content
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Tab
 */
$output = $title = $tab_id = $tabicon = '';
extract(shortcode_atts($this->predefined_atts, $atts));
global $lee_tab_item;
$lee_tab_item[] = array('tab-id'=>$tab_id,'title'=>$title,'content'=>wpb_js_remove_wpautop($content));