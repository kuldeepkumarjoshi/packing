<?php
function berybanner_shortcode($atts, $content) {
    $image = $mask = '';
    $a = shortcode_atts(array(
        'align'  => 'left',
        'valign'  => 'top',
        'class'  => '',
        'link'  => '#',
        'hover'  => '',
        'content'  => '',  
        'font_style'  => '',  
        'banner_style'  => '',  
        'img' => '',
        'img_src' => '',
        'height' => '300px',
        'text_color' => 'light',
        //'parallax' => '0',
        'padding_text' => '',
        'effect_text' => 'fadeInUp',
        'data_delay' => '0ms',
        'seam_icon' => '',
        'border' => '',
        'el_class' => ''
    ), $atts);
    ?>
    <?php
    $border_outner = ''; $border_inner = '';
    if ($a['border'] != ''){
        if ($a['border'] == 'border_outner'){
            $border_outner = $a['border'];
        }
        elseif ($a['border'] = 'border_inner'){
            $border_inner = $a['border'];
        }
        
    }

    $seam_icon = '';
    if ($a['seam_icon'] != ''){
        $seam_icon = '<div class="seam_icon '.$a['seam_icon'].'"><span class="seam seam-up"></span><span class="seam seam-down"></span></div>';
    }

    // $is_parallax = ($a['parallax']) != '0' ?' data-stellar-background-ratio="'.$a['parallax'].'"':'';
    // $parallax = '';
    // if ($a['banner_style'] != '') {
    //   $a['class'] .= ' style-'.$a['banner_style'];
    // }

    // if ($a['parallax'] != ''){
    //     $a['class'] .= ' banner-parallax';
    // }

    if ($a['align'] != '') {
      $a['class'] .= ' align-'.$a['align'];
    }

    if ($a['valign'] != '') {
      $a['class'] .= ' valign-'.$a['valign'];
    }

    $onclick = '';
    if($a['link'] != '') {
        $a['class'] .= ' cursor-pointer';
        $onclick = 'onclick="window.location=\''.$a['link'].'\'"';
    }

    $src = ''; $image = '';
    $style = '';
    // if ($a['img_src'] != ''){
    //     $banner_image = $a['img_src'];
    // }
    
    if($a['img_src'] != '') {
        $image = wp_get_attachment_image_src($a['img_src'],'full');
        $src = $image[0];
    }
    // if ($a['img_src'] != '') {
    //     if ($a['parallax'] != '0'){
    //         $style = 'background: url('.$a['img_src'].') center center no-repeat fixed; -webkit-background-size:cover; -moz-background-size:cover; -o-background-size:cover; background-size:cover;';
    //     }else{
    //         $style = 'background-image: url('.$a['img_src'].'); background-repeat: no-repeat; -webkit-background-size:cover; -moz-background-size:cover; -o-background-size:cover; background-size:cover;';
    //     }
    // }

    $text_color = '';
    if ($a['text_color'] != ''){
        $text_color = $a['text_color'];
    }


    $hover = '';
    if ($a['hover'] != ''){
        $hover = 'hover-'.$a['hover'].'';
    }

    $padding_text = '';
    if ($a['padding_text'] != ''){
        $padding_text = 'padding: 0px '.$a['padding_text'].'';
    }

    $effect_text = '';
    if ($a['effect_text'] != ''){
        $effect_text = $a['effect_text'];
    }else{
        $effect_text = 'fadeIn';
    }

    $data_delay = '';
    if ($a['data_delay'] != ''){
        $data_delay = $a['data_delay'];
    }

    $el_class = '';
    if ($a['el_class'] != ''){
        $el_class = $a['el_class'];
    }

    return '
        '.$seam_icon.'
        <div class="banner wow fadeInUp bery_banner '.$a['class'].' banner-font-'.$a['font_style'].' hover-'.$a['hover'].' '.$border_outner.' '.$el_class.'"  data-wow-delay="'.$data_delay.'"  '.$onclick.'>
            <div class="banner-image" style="'.$style.'" ><img src="'.$src.'" alt=""/></div>
            <div class="banner-content wow '.$effect_text.' '.$text_color.' '.$border_inner.'">
                <div class="banner-inner" style="'.$padding_text.'">
                    '.fixShortcode($content).'
                </div>
            </div>
        </div>';
}

$content = ob_get_contents();
ob_end_clean();

add_shortcode('berybanner','berybanner_shortcode');
