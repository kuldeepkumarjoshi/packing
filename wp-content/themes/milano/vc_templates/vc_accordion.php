<?php
global $lee_collapses_item;
$output = $title = $interval = $el_class = $collapsible = $active_tab = '';
extract(shortcode_atts(array(
    'title' => '',
    'interval' => 0,
    'el_class' => '',
    'collapsible' => 'no',
    'active_tab' => '1'
), $atts));
wpb_js_remove_wpautop($content);
?>
<div class="collapses-group">
<?php foreach($lee_collapses_item as $k => $v){?>
	<div class="collapses<?php echo ($k == 0)?' active':'';?>">
		<div class="collapses-title">
			<a class="lee_collapses" href="javascript:void(0);"><?php echo esc_attr($v['title']);?></a>
		</div>
		<div class="collapses-inner"<?php echo ($k == 0)?' style="display:block"':'';?>><?php echo $v['content'];?></div>
	</div>
<?php }?>
</div>