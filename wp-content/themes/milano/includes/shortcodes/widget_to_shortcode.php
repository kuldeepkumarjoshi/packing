<?php

// **********************************************************************// 
// Recent Tweets
// **********************************************************************// 
include_once(ABSPATH.'wp-admin/includes/plugin.php');
if (is_plugin_active('recent-tweets-widget/recent-tweets.php')){
    add_shortcode('recent_tweets_widget','recent_tweets_widget_shortcode');
}

function recent_tweets_widget_shortcode($atts, $content = null) {
    $a = shortcode_atts(array(
        'title' => '',
        'consumerkey' => '',
        'consumersecret' => '',
        'accesstoken' => '',
        'accesstokensecret' => '',
        'cachetime' => '',
        'username' => '',
        'tweetstoshow' => '',
        'excludereplies' => '',
      
    ),$atts);
    
    $widget = new tp_widget_recent_tweets();

    $args = array(
        'before_widget' => '<div class="tp_widget_recent_tweets">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="section-title"><span>',
        'after_title'   => '</span></h3><div class="bery-hr medium"></div>',
        'widget_id' => 'tp_widget_recent_tweets',
    ); 
    $instance = array(
        'title' => $a['title'],
        'consumerkey' => $a['consumerkey'],
        'consumersecret' => $a['consumersecret'],
        'accesstoken' => $a['accesstoken'],
        'accesstokensecret' => $a['accesstokensecret'],
        'cachetime' => $a['cachetime'],
        'username' => $a['username'],
        'tweetstoshow' => $a['tweetstoshow'],
        'excludereplies' => $a['excludereplies']
    );

    ob_start();
    $widget->widget($args, $instance);
    $output = ob_get_contents();
    ob_end_clean();
    
    return $output;
}

// **********************************************************************// 
// Contact Us
// **********************************************************************// 
add_shortcode('contact_us','contact_us_shortcode');

function contact_us_shortcode($atts, $content = null) {
    $a = shortcode_atts(array(
        'title' => '',
        'contact_address' => '',
        'contact_phone' => '',
        'contact_email' => '',
        'contact_icon_style' => '',
    ),$atts);
    
    $widget = new Contact_Us_Widget();

    $args = array(
        'before_widget' => '<div class="contact_us_widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="section-title"><span>',
        'after_title'   => '</span></h3><div class="bery-hr medium"></div>',
        'widget_id' => 'contact_us_widget',
    ); 
    $instance = array(
        'title' => $a['title'],
        'contact_address' => $a['contact_address'],
        'contact_phone' => $a['contact_phone'],
        'contact_email' => $a['contact_email'],
        'contact_icon_style' => $a['contact_icon_style'],
    );

    ob_start();
    $widget->widget($args, $instance);
    $output = ob_get_contents();
    ob_end_clean();
    
    return $output;
}



