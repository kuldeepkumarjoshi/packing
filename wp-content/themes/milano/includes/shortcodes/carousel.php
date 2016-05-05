<?php
function shortcode_slider($atts, $content=null){
    $sliderid = rand();
    ob_start();
    extract(shortcode_atts( array(
      'title' => '',
      'align' => '',
      'column_number' => '1',
      'column_number_tablet' => '2',
      'column_number_small' => '1',
      'navigation' => 'true',
      'bullets' => 'true',
      'slideSpeed' => '300',
      'paginationSpeed' => '400',
      'autoplay' => 'true',
      'stoponhover' => 'true',
      'bullets_type' => '',
      'data_delay' => '0ms',
      'border'  => ''
    ), $atts));
  ?>
    <?php
      $align = '';
      if ($align == 'center') $align = 'text-center';
    ?>
    <?php if($title){?>
      <div class="row">
        <div class="large-12 columns">
              <div class="title-inner <?php echo esc_attr($align); ?>"> 
                <h3 class="section-title <?php echo esc_attr($align); ?>"><span><?php echo esc_attr($title); ?></span></h3>
                <div class="bery-hr medium"></div>
              </div>
        </div>
      </div>
    <?php } ?>
      <div class="row">
        <div class="group-slider content_slider_wrap wow fadeInUp <?php echo esc_attr($border);?> <?php echo esc_attr($bullets); ?>" data-wow-delay="<?php echo esc_attr($data_delay); ?>" >
          <div class="item-slider<?php echo esc_attr($sliderid); ?>-<?php echo esc_attr($column_number); ?> <?php echo esc_attr($bullets_type); ?> ">
            <?php echo fixShortcode($content); ?>
          </div>
        </div>
      </div>


  <script type="text/javascript">
    (function($){
        var autoplay = $(this).data('autoplay');
      $(window).load(function(){
      $('.item-slider<?php echo esc_attr($sliderid); ?>-<?php echo esc_attr($column_number); ?>').owlCarousel({
        navigation : <?php echo esc_attr($navigation); ?>,
        slideSpeed : <?php echo esc_attr($slideSpeed); ?>,
        pagination: <?php echo esc_attr($bullets); ?>,
        paginationSpeed : <?php echo esc_attr($paginationSpeed); ?>,
        autoPlay : <?php echo esc_attr($autoplay); ?>,
        stopOnHover : <?php echo esc_attr($stoponhover); ?>,
        itemsCustom : [
          [0, <?php echo esc_attr($column_number_small); ?>],
          [450, <?php echo esc_attr($column_number_small); ?>],
          [600, <?php echo esc_attr($column_number_tablet); ?>],
          [700, <?php echo esc_attr($column_number_tablet); ?>],
          [1000, <?php echo esc_attr($column_number); ?>],
          [1200, <?php echo esc_attr($column_number); ?>],
          [1400, <?php echo esc_attr($column_number); ?>],
          [1600, <?php echo esc_attr($column_number); ?>]
        ],
        navigationText: ["", ""]
        });
    })
    })(jQuery);
  </script>


  <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
  }
add_shortcode("bery_slider","shortcode_slider");

?>