<?php
// **********************************************************************// 
// ! Team Member
// **********************************************************************// 

add_shortcode('team_member', 'team_member_shortcode');
function team_member_shortcode($atts, $content = null) {
    $a = shortcode_atts(array(
        'class' => '',
        'type' => 1,
        'name' => '',
        'email' => '',
        'twitter' => '',
        'facebook' => '',
        'skype' => '',
        'instagram' => '',
        'position' => '',
        'content' => '',
        'img' => '',
        'img_src' => '',
        'img_size' => ''
    ), $atts);

    $src = ''; $image = ''; $width = ''; $height = ''; $image_size = '';
    if ($a['img_size'] != ''){
    	$img_size = explode('x', $a['img_size']);
	    $width = $img_size[0];
	    $height = $img_size[1];
        $image_size = 'width = "'.$width.'" height = "'.$height.'"';
	}
    if($a['img'] != '') {
    	$image = wp_get_attachment_image_src($a['img'],'full');
        $src = $image[0];
    }elseif ($a['img_src'] != '') {
        $src = do_shortcode($a['img_src']);
    }

    if ($a['content'] != '') {
        $content = $a['content'];
    }

    
    $html = '';
    $span = 12;
    $html .= '<div class="team-member member-type-'.$a['type'].' '.$a['class'].'">';

        if($a['type'] == 2) {
            $html .= '<div class="row">';
        }
	    if($src != ''){

            if($a['type'] == 2) {
                $html .= '<div class="large-6 columns">';
                $span = 6;
            }
            $html .= '<div class="member-image">';
                $html .= '<img src="'.$src.'" '.$image_size.' alt=""/>';
            $html .= '</div>';
            $html .= '<div class="clear"></div>';
            if($a['type'] == 2) {
                $html .= '</div>';
            }		      
	    }

    
        if($a['type'] == 2) {
            $html .= '<div class="large-'.$span.' columns">';
        }
        $html .= '<div class="member-details">';
            if($a['position'] != ''){
                $html .= '<h3>'.$a['name'].'</h3>';
            }

		    if($a['name'] != ''){
			    $html .= '<h3 class="member-position">'.$a['position'].'</h3>';
		    }
            if ($a['twitter'] != '' || $a['facebook'] != '' || $a['skype'] != '' || $a['instagram'] != '') {
                $html .= '<ul class="social-icons">';
                    $html .= '';
                        if ($a['facebook'] != '') {
                        	$html .= '<li><a href="'.$a['facebook'].'" target="_blank" class="icon tip-top" data-tip="'.__('Facebook','ltheme_domain').'"><span class="icon-facebook"></span><svg class="circle" xmlns="http://www.w3.org/2000/svg" height="38" width= "38"><circle stroke="#3a589d" fill="#fff" r="18" cy="19" cx="19"></svg></a></li>';
                        }
                        if ($a['twitter'] != '') {
                            $html .= '<li><a href="'.$a['twitter'].'" target="_blank" class="icon tip-top" data-tip="'.__('Twitter','ltheme_domain').'"><span class="icon-twitter"></span><svg class="circle" xmlns="http://www.w3.org/2000/svg" height="38" width= "38"><circle stroke="#3a589d" fill="#fff" r="18" cy="19" cx="19"></svg></a></li>';
                        }
                        if ($a['skype'] != '') {
                            $html .= '<li><a href="'.$a['skype'].'" target="_blank" class="icon tip-top" data-tip="'.__('Skype','ltheme_domain').'"><span class="icon-skype"></span><svg class="circle" xmlns="http://www.w3.org/2000/svg" height="38" width= "38"><circle stroke="#00aff0" fill="#fff" r="18" cy="19" cx="19"></svg></a></li>';
                        }
                        if ($a['instagram'] != '') {
                            $html .= '<li><a href="'.$a['instagram'].'" target="_blank" class="icon tip-top" data-tip="'.__('Instagram','ltheme_domain').'"><span class="icon-instagram"></span><svg class="circle" xmlns="http://www.w3.org/2000/svg" height="38" width= "38"><circle stroke="#6a453c" fill="#fff" r="18" cy="19" cx="19"></svg></a></li>';
                        }
                $html .= '</ul>';
            }
            if($a['email'] != ''){
                $html .= '<p class="member-email"><span>'.__('Email:', 'ltheme_domain').'</span> <a href="'.$a['email'].'">'.$a['email'].'</a></p>';
            }
		    $html .= '<p class="member-desciption">'.do_shortcode($content).'</p>';
    	$html .= '</div>';

        if($a['type'] == 2) {
                $html .= '</div>';
            $html .= '</div>';
        }
    $html .= '</div>';
    
    
    return $html;
}
