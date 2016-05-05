<?php

function shortcode_map($atts, $content=null, $code) {

    $mapsrandomid = rand();
	extract(shortcode_atts(array(
		'lat'  => '',
    'long' => '',
    'height' => '400px',
		'color' => '',
    'zoom' => '17',
    'controls' => 'false',
    'pan' => 'false',
    'type' => 'ROADMAP'
	), $atts));
	ob_start();
	?> 
    
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
	<script type="text/javascript">
    
    function initialize() {
        var styles = {
            'leetheme':  [{
            "featureType": "administrative",
            "stylers": [
              { "visibility": "on" }
            ]
          },
          {
            "featureType": "road",
            "stylers": [
              { "visibility": "on" },
              { "hue": "<?php echo esc_attr($color) ?>" }
            ]
          },
          {
            "stylers": [
			  { "visibility": "on" },
			  { "hue": "<?php echo esc_attr($color); ?>" },
			  { "saturation": -30 }
            ]
          }
        ]};
        
        var myLatlng = new google.maps.LatLng(<?php echo esc_attr($lat); ?>, <?php echo esc_attr($long); ?>);
        var myOptions = {
            zoom: <?php echo esc_attr($zoom); ?>,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.<?php echo esc_attr($type); ?>,
            disableDefaultUI: true,
            draggable: true,
            zoomControl: false,
      			panControl: false,
      			mapTypeControl: false,
      			scaleControl: false,
      			streetViewControl: false,
      			overviewMapControl: false,
            scrollwheel: false,
            disableDoubleClickZoom: true
        }
        var map = new google.maps.Map(document.getElementById("<?php echo esc_attr($mapsrandomid); ?>"), myOptions);
        var styledMapType = new google.maps.StyledMapType(styles['leetheme'], {name: 'leetheme'});
        map.mapTypes.set('leetheme', styledMapType);
        
        var marker = new google.maps.Marker({
            position: myLatlng, 
            map: map,
            title:""
        });   
    }
    
    google.maps.event.addDomListener(window, 'load', initialize);
    google.maps.event.addDomListener(window, 'resize', initialize);
    
    </script>
    
    <div id="map_container">
        <div id="<?php echo esc_attr($mapsrandomid); ?>" style="height:<?php echo esc_attr($height); ?>;"></div>
        <div id="map_overlay_top"></div>
        <div id="map_overlay_bottom"></div>
    </div>

	<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_shortcode('map', 'shortcode_map');
