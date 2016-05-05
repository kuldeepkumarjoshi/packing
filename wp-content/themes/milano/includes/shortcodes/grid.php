<?php
function rowShortcode($atts, $content = null) {
  extract( shortcode_atts( array(
    'style' => '',
    'margin' => '',
    'padding' => '' 
  ), $atts ) );

  $margin_html ='';
  if($margin){
    $margin_html = 'margin-top:'.$margin.'!important;margin-bottom:'.$margin.'!important;';
  }

  $padding_html = '';
  if ($padding) {
    $padding_html = 'padding-left: '.$padding.' !important; padding-right: '.$padding.' !important;';
  }

	$content = do_shortcode($content);
	$container = '<div class="row container '.$style.' " style=" '.$margin_html. ' '.$padding_html.'">'.$content.'</div>';
	return $container;
} 

function bannergridShortCode($atts, $content = null) {
    extract( shortcode_atts( array(
    'padding' => '10px',
    'width' => '',
    'el_class' => ''
    ), $atts ) );
    $shortcode_id = rand();
    ob_start();
  ?>
  <?php if($padding){ $padding_w = $padding/2; ?>
          <style scoped>#banner_grid_<?php echo esc_attr($shortcode_id); ?> .bery_banner-grid .columns > div{margin-left: <?php echo esc_attr($padding_w); ?>px !important; margin-right: <?php echo esc_attr($padding_w) ?>px !important;} #banner_grid_<?php echo esc_attr($shortcode_id); ?> .bery_banner{margin-bottom: <?php echo esc_attr($padding); ?>px;} </style>
  <?php } ?>
  <?php if($width){ ?>
          <style scoped>#banner_grid_<?php echo esc_attr($shortcode_id); ?> > .row {max-width:<?php echo esc_attr($width); ?>} <?php if($width == '100%') {?> #banner_grid_<?php echo esc_attr($shortcode_id); ?> > .row > .large-12{padding:0!important;} <?php } ?></style>
  <?php } ?>

  <div id="banner_grid_<?php echo esc_attr($shortcode_id); ?>">
  <div class="row">
      <div class="large-12 columns">
        <div class="row collapse bery_banner-grid <?php echo (($el_class!='')?' '.$el_class:''); ?>"><?php echo do_shortcode($content); ?></div>
      </div>
    </div>
    <script>
  jQuery(document).ready(function ($) {
      var $container = $("#banner_grid_<?php echo esc_attr($shortcode_id); ?> .bery_banner-grid");
      $container.packery({
        itemSelector: ".columns",
        gutter: 0
      });
   });
  </script>
  </div><!-- .banner-grid -->
	
	<?php
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
} 

function colShortcode($atts, $content = null) {	
	extract( shortcode_atts( array(
    'span' => '3',
    'animate' => '',
  	), $atts ) );

  	switch ($span) {
    case "1/1":
        $span = "12"; break;
    case "1/4":
        $span = '3'; break;
    case "2/4":
         $span ='6'; break;
    case "3/4":
        $span = '9'; break;
    case "1/3":
        $span = '4'; break;
    case "2/3":
         $span = '8'; break;
    case "1/2":
        $span = '6'; break;
    case "1/6":
        $span = '2'; break;
    case "2/6":
        $span = '4'; break;
    case "3/6":
        $span = '6'; break;
    case "4/6":
        $span = '8'; break;
    case "5/6":
        $span = '10'; break;
    case "1/12":
        $span = '1'; break;
    case "2/12":
        $span = '2'; break;
    case "3/12":
        $span = '3'; break;
    case "4/12":
        $span = '4'; break;
    case "5/12":
        $span = '5'; break;
    case "6/12":
        $span = '6'; break;
    case "7/12":
        $span = '7'; break;
    case "8/12":
        $span = '8'; break;
    case "9/12":
        $span = '9'; break;
    case "10/12":
        $span = '10'; break;
     case "11/12":
        $span = '11'; break;
	}

	$content = do_shortcode($content);
	$column = '<div class="large-'.$span.' columns">'.$content.'</div>';
	return $column;
}
add_shortcode('bery_banner_grid', 'bannergridShortcode');
add_shortcode('col', 'colShortcode');
add_shortcode('row', 'rowShortcode');
