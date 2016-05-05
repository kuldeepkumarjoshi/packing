<?php
global $lee_tab_item;
$lee_tab_item = array();
$output = $title = $interval = $el_class = $align_tab = '';
extract(shortcode_atts(array(
    'title' => '',
    'interval' => 0,
    'el_class' => '',
    'align_tab' => ''
), $atts));
wpb_js_remove_wpautop($content);
$el_class = $this->getExtraClass($el_class);
$element = 'tabs-top';
if ('vc_tour' == $this->shortcode) $element = 'tabs-left';
if($align_tab == '') $align_tab = 'text-left';
?>

<div class="lee-tabs-content shortcode_tabgroup <?php echo esc_attr($element) . esc_attr($el_class);?>">
	<ul class="lee-tabs <?php echo esc_attr($align_tab);?>">
		<?php foreach($lee_tab_item as $key=>$tab){ ?>
			<li<?php echo ($key==0)?' class="lee-tab active first"':' class="lee-tab"'; ?>>
				<a href="javascript:void(0);" data-id="#lee-panel<?php echo esc_attr($tab['tab-id']); ?>"><h4><?php echo esc_attr($tab['title']); ?></h4></a>
				<span class="bery-hr medium <?php echo esc_attr($align_tab); ?>"></span>
			</li>
			<li class="separator">X</li>
		<?php } ?>
	</ul>

	<div class="lee-panels">
		<?php foreach($lee_tab_item as $key=>$tab){ ?>
			<div<?php echo ($key==0)?' style="display: block" class=" first lee-panel active"':' style="display: none" class="lee-panel"';?> id="lee-panel<?php echo esc_attr($tab['tab-id']); ?>">
				<?php echo $tab['content']; ?>
			</div>
		<?php } ?>
	</div>
</div>