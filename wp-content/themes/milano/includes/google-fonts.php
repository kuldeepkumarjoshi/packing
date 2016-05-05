<?php

if(isset($ros_opt['type_headings'])){
$customfont = '';
$default = array(
					'arial',
					'verdana',
					'trebuchet',
					'georgia',
					'times',
					'tahoma',
					'helvetica'
				);

$googlefonts = array(
					$ros_opt['type_headings'],
					$ros_opt['type_texts'],
					$ros_opt['type_nav'],
					$ros_opt['type_alt']
);



$customfontset = '';

if(isset($ros_opt['type_subset'])){
$subsets= array('latin');
if(isset($ros_opt['type_subset']['latin'])){array_push($subsets, 'latin');}
if(isset($ros_opt['type_subset']['cyrillic-ext'])){array_push($subsets, 'cyrillic-ext');}
if(isset($ros_opt['type_subset']['greek-ext'])){array_push($subsets, 'greek-ext');}
if(isset($ros_opt['type_subset']['greek'])){array_push($subsets, 'greek');}
if(isset($ros_opt['type_subset']['vietnamese'])){array_push($subsets, 'vietnamese');}
if(isset($ros_opt['type_subset']['cyrillic'])){array_push($subsets, 'cyrillic');}
foreach($subsets as $fontset) {
	$customfontset = $fontset.','. $customfontset;
}	
$customfontset = '&subset='.substr_replace($customfontset ,"",-1);
}

			
foreach($googlefonts as $googlefont) {
	if(!in_array($googlefont, $default)) {
			$customfont = str_replace(' ', '+', $googlefont). ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,900,900italic|' . $customfont;
	}
}	


if ($customfont != "") {	
	function google_fonts() {	
		global $customfont, $customfontset;		
		$protocol = is_ssl() ? 'https' : 'http';
			wp_enqueue_style( 'leetheme-googlefonts', "$protocol://fonts.googleapis.com/css?family=". substr_replace($customfont ,"",-1) . $customfontset);}
			add_action( 'wp_enqueue_scripts', 'google_fonts' );
	
}

}

?>
