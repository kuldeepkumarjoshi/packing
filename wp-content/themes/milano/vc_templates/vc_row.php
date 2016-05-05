<?php
$output = $el_class = $bg_image = $bg_color = $bg_image_repeat = $font_color = $padding = $margin_bottom = '';
extract(shortcode_atts(array(
    'el_class'        => '',
    'bg_image'        => '',
    'bg_color'        => '',
    'bg_image_repeat' => '',
    'font_color'      => '',
    'padding'         => '',
    'margin_bottom'   => '',
    'fullwidth'       => '0',
    'parallax'        => '',
    'css'             => '',
    'rowsm'           => false,
    'footer_css'      => ''
), $atts));
$footer_class='';
if($footer_css!=''){
    $footer_class = ' '.vc_shortcode_custom_css_class( $footer_css, ' ' );
    echo '<style>'.$footer_css.'</style>';
}

$_is_fullwidth = ($fullwidth=='1')?true:false;
$row_sm = ($rowsm) ? ' row-sm' : '';

$is_parallax = ($parallax != '')? ' data-stellar-background-ratio="0.6"':'';
$parallax = ($parallax!='')?' parallax' : '';

$el_class = $this->getExtraClass($el_class).$footer_class;
$style = $this->buildStyle($bg_image, $bg_color, $bg_image_repeat, $font_color, $padding, $margin_bottom);

$output = '';

if($this->settings('base')==='vc_row'){
    $output.='<div class="section-element'.$el_class.$parallax.vc_shortcode_custom_css_class($css, ' ').'" '.$style.$is_parallax.'>';
        $output .= ($_is_fullwidth) ? '<div class="lee-row fullwidth">' : '<div class="row'.$row_sm.'">';
            $output .= wpb_js_remove_wpautop($content);
        $output .= ($_is_fullwidth) ? '</div>': '</div>'.$this->endBlockComment('row');
    $output.='</div>';
}else{
    $output.='<div class="section-element'.$el_class.$parallax.vc_shortcode_custom_css_class($css, ' ').'" '.$style.'>';
        $output .= '<div class="row'.$row_sm.'">';
            $output .= wpb_js_remove_wpautop($content);
        $output .= '</div>'.$this->endBlockComment('row');
    $output.='</div>';
}


echo $output;