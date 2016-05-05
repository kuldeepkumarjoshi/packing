<?php
function bery_category_banner( $atts, $content = null){
  extract(shortcode_atts(array(
      'img' => '#',
      'padding' => '15px',
      'link' => '#',
      'height' => '365px',
      'title' => '',
    ), $atts));

  if (strpos($img,'http://') !== false || strpos($img,'https://') !== false) {
        $img = $img;
      }
  else {
    $img = wp_get_attachment_image_src($img, 'large');
    $img = $img[0];
  }


  $content = '<div class="bery_category_banner"><a title="'.$title.'" href="'.$link.'" style="padding:0px '.$padding.';"><img src="'.$img.'" alt="'.$title.'" style="max-height:'.$height.';min-height:'.$height.'" /></a></div>';
    return $content;
}

add_shortcode('category_banner', 'bery_category_banner');

/* SERVICE BOX */
add_shortcode("service_box","service_box_function");
function service_box_function($atts, $content = null){
  extract(Shortcode_atts(array(
      'service_icon' => '',
      'service_title' => '',
      'service_hover' => '',
      'el_class' => ''
    ), $atts));
  ob_start();
 
  ?>
    <div class="service-block <?php echo esc_attr($el_class); ?>">
        <div class="box">
          <div class="title">
            <span class="icon <?php echo esc_attr($service_hover); ?> <?php echo esc_attr($service_icon)?>"></span>
            <span class="text"><?php echo esc_attr($service_title); ?></span>
          </div>
        </div>
    </div>

 <?php 
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}

function shortcode_client($params = array(), $content = null) {
  extract(shortcode_atts(array(
    "image" => '',
    "name" => '',
    "company" => '',
    "stars" => '',
    "content_say" => 'Some promo text',


  ), $params));
  $content = preg_replace('#<br\s*/?>#', "", $content);

  $star_row = '';
  if ($stars == '1'){$star_row = '<div class="star-rating"><span style="width:25%"><strong class="rating"></strong></span></div>';}
  else if ($stars == '2'){$star_row = '<div class="star-rating"><span style="width:35%"><strong class="rating"></strong></span></div>';}
  else if ($stars == '3'){$star_row = '<div class="star-rating"><span style="width:55%"><strong class="rating"></strong></span></div>';}
  else if ($stars == '4'){$star_row = '<div class="star-rating"><span style="width:75%"><strong class="rating"></strong></span></div>';}
  else if ($stars == '5'){$star_row = '<div class="star-rating"><span style="width:100%"><strong class="rating"></strong></span></div>';}


  $client='

    <div class="client large-12 columns">
      <div class="client-inner">
        
        <img class="wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1s" src="'.esc_url($image).'" alt="" />
        <div class="client-info wow fadeInUp" data-wow-delay="800ms" data-wow-duration="1s">
          <div class="client-content">'.$content_say.'</div>
          <div class="client-star">'.$star_row.'</div>
          <h3 class="client-name">'.esc_attr($name).'</h3>
          <div class="client-pos">'.esc_attr($company).'</div>
        </div>
      </div>
    </div>
  ';

  return $client;
}

add_shortcode('client','shortcode_client');


