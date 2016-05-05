<?php

/*global $wp_filesystem;
// Initialize the WP filesystem, no more using 'file-put-contents' function
if (empty($wp_filesystem)) {
    require_once (ABSPATH . '/wp-admin/includes/file.php');
    WP_Filesystem();
}*/

// **********************************************************************// 
// ! Header Type
// **********************************************************************// 
function get_header_type() {
    global $ros_opt;
    if (isset($ros_opt['header-type'])) {return $ros_opt['header-type'];}
}

add_filter('custom_header_filter', 'get_header_type',10);

function bery_get_header_structure($ht) {

    switch ($ht) {
        case 1:
            return 1;
            break;
        case 2:
            return 2;
            break;
        case 3:
            return 3;
            break;
        case 4:
            return 4;
            break;
        default:
            return 1;
            break;
    }
}


// **********************************************************************// 
// ! Footer Type
// **********************************************************************//
add_action('lee_footer_layout_style', 'lee_footer_layout_style_function');
function lee_footer_layout_style_function(){
    global $ros_opt, $wp_query;
    $pageid = $wp_query->get_queried_object_id();

    $footer_id = get_post_meta($pageid,'_lee_custom_footer', true);

    if ($footer_id == '' || $footer_id == 'default'){
        if (isset($ros_opt['footer-type']) && $ros_opt['footer-type'] != ''){
            $footer_id = $ros_opt['footer-type'];
        }else{
            get_template_part( 'templates/footer/default');
            return;
        }
    }
    
    if ($footer_id){
        echo do_shortcode(get_post($footer_id)->post_content);
    }
}


// **********************************************************************// 
// ! Mini cart
// **********************************************************************// 

if(!function_exists('leetheme_mini_cart')) {
    function leetheme_mini_cart() {
        global $woocommerce;
        global $ros_opt;
        //ob_start(); 
        ?>
        <div class="cart-inner">
            <a href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" class="cart-link">
                <ul>
                    <li class="cart-icon">
                        <span class="shopping-cart pe-7s-cart"></span>
                        <span class="products-number"><strong><?php echo $woocommerce->cart->cart_contents_count;?></strong></span>
                    </li>
                    <li>
                        <div class="cart-count">
                            <?php echo $woocommerce->cart->get_cart_total(); ?>
                        </div>
                     </li>
                </ul>   

            </a>
            <div class="nav-dropdown hide-for-small">
                <div class="nav-dropdown-inner">
                <!-- Add a spinner before cart ajax content is loaded -->
                    <?php if ($woocommerce->cart->cart_contents_count == 0) {
                        echo '<p class="empty">'.__('No products in the cart.','woocommerce').'</p>';
                        ?> 
                    <?php } else { //add a spinner ?> 
                        <div class="loading"><i></i><i></i><i></i><i></i></div>
                    <?php } ?>
                    </div><!-- nav-dropdown-innner -->
            </div><!-- .nav-dropdown -->
        </div><!-- .cart-inner -->
        <?php
    }
}

// **********************************************************************// 
// ! Get logo
// **********************************************************************// 

if (!function_exists('leetheme_logo')){
    function leetheme_logo(){
        global $ros_opt, $logo_link, $wp_query;
        $logo_link = get_post_meta($wp_query->get_queried_object_id(), '_lee_custom_logo', true);
        if ($logo_link == ''){
            $logo_link = $ros_opt['site_logo'];
        }
        ?>
        <div class="logo">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?> - <?php bloginfo( 'description' ); ?>" rel="home">
                <?php if($logo_link != ''){
                    $site_title = esc_attr( get_bloginfo( 'name', 'display' ) );
                    echo '<img src="'.$logo_link.'" class="header_logo" alt="'.$site_title.'"/>';
                } else {bloginfo( 'name' );}?>
            </a>
        </div>
    <?php
    }
}


// **********************************************************************// 
// ! Get main menu
// **********************************************************************// 
if (!function_exists('leetheme_get_main_menu')){
    function leetheme_get_main_menu(){
        ?>
        <div class="nav-wrapper">
            <ul id="site-navigation" class="header-nav">
                <?php if ( has_nav_menu( 'primary' ) ) : ?>
                    <?php  
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container'       => false,
                        'items_wrap'      => '%3$s',
                        'depth'           => 5,
                        'walker'          => new LeethemeNavDropdown
                    ));
                ?>
              <?php else: ?>
                  <li>Please Define main navigation in <b>Apperance > Menus</b></li>
              <?php endif; ?>                               
            </ul>
        </div><!-- nav-wrapper -->
    <?php
    }
}

// **********************************************************************// 
// ! Get shop by category menu
// **********************************************************************// 
if (!function_exists('leetheme_get_shop_by_category_menu')){
    function leetheme_get_shop_by_category_menu(){
        ?>
        <div class="nav-wrapper">
            <ul id="" class="shop-by-category">
                <?php if ( has_nav_menu( 'shop_by_category' ) ) : ?>
                    <?php  
                    wp_nav_menu(array(
                        'theme_location' => 'shop_by_category',
                        'container'       => false,
                        'items_wrap'      => '%3$s',
                        'depth'           => 3,
                        'walker'          => new LeethemeNavDropdown
                    ));
                ?>
              <?php else: ?>
                  <li>Please Define Shop by Category menu in <b>Apperance > Menus</b></li>
              <?php endif; ?>                               
            </ul>
        </div><!-- nav-wrapper -->
    <?php
    }
}

// **********************************************************************// 
// ! Get shop by Footer menu
// **********************************************************************// 
if (!function_exists('leetheme_get_footer_menu')){
    function leetheme_get_footer_menu(){
        ?>
        <div class="nav-wrapper">
            <ul id="" class="footer-menu">
                <?php if ( has_nav_menu( 'footer_menu' ) ) : ?>
                    <?php  
                    wp_nav_menu(array(
                        'theme_location' => 'footer_menu',
                        'container'       => false,
                        'items_wrap'      => '%3$s',
                        'depth'           => 3,
                        'walker'          => new LeethemeNavDropdown
                    ));
                ?>
              <?php else: ?>
                  <li>Please Define Footer menu in <b>Apperance > Menus</b></li>
              <?php endif; ?>
            </ul>
        </div><!-- nav-wrapper -->
    <?php
    }
}


// **********************************************************************// 
// ! Get breadcrumb
// **********************************************************************// 
add_action('lee_get_breadcrumb', 'leetheme_get_breadcrumb');
if (!function_exists('leetheme_get_breadcrumb')){
    function leetheme_get_breadcrumb(){
        global $wp_query;
        if (is_plugin_active( 'woocommerce/woocommerce.php' )){
        ?>
            <div class="bread text-center">
                <div class="row">
                    <div class="large-12 columns">
                        <div class="breadcrumb-row">
                            <?php 
                                $defaults = array(
                                    'delimiter'  => '<span class="fa fa-long-arrow-right "></span>',
                                    'wrap_before'  => '<h3 class="breadcrumb">',
                                    'wrap_after' => '</h3>',
                                    'before'   => '',
                                    'after'   => '',
                                    'home'    => 'Home'
                                );
                                $args = wp_parse_args(  $defaults  );
                                woocommerce_get_template( 'global/breadcrumb.php', $args );
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
    }
}

// **********************************************************************// 
// ! Countdown
// **********************************************************************// 
function lee_woocoomerce_countdown() {
    wp_enqueue_script( 'leetheme-countdown', get_template_directory_uri() .'/js/countdown.js', array(), false, true );
    wp_localize_script(
        'leetheme-countdown',
        'lee_countdown_l10n',
        array(
            'days' => 'Days',
            'months' => 'Months',
            'weeks' => 'Weeks',
            'years' => 'Years',
            'hours' => 'Hours',
            'minutes' => 'Minutes',
            'seconds' => 'Seconds',
            'day' => 'Day',
            'month' => 'Month',
            'week' => 'Week',
            'year' => 'Year',
            'hour' => 'Hour',
            'minute' => 'Minute',
            'second' => 'Second',
        )
    );
}
add_action('wp_enqueue_scripts','lee_woocoomerce_countdown');


// **********************************************************************// 
// ! Add body class
// **********************************************************************// 
function leetheme_body_classes( $classes ) {
    global $ros_opt;

    $classes[] = 'antialiased';
    if ( is_multi_author() ) {
        $classes[] = 'group-blog';
    }

    if ($ros_opt['site_layout'] == 'boxed'){
        $classes[] = 'boxed';
    }

    if($ros_opt['promo_popup'] == 1){
        $classes[] = 'open-popup';
    }

    if (LEE_WOOCOMMERCE_ACTIVED && function_exists('is_product')){
        if (is_product() && isset($ros_opt['product-zoom']) && $ros_opt['product-zoom']){
            $classes[] = 'product-zoom';
        }
    }

    return $classes;
}
add_filter( 'body_class', 'leetheme_body_classes' );


// **********************************************************************// 
// ! Add hr to the widget title
// **********************************************************************// 
function lee_widget_title($title)
{
    if (!empty($title)){
        return ''.$title.'<span class="bery-hr medium"></span>';
    }   
}
add_filter('widget_title', 'lee_widget_title', 10, 3);


// **********************************************************************// 
// ! Fix shortcode content
// **********************************************************************// 
function fixShortcode($content){
      $fix = array (
            '&nbsp;' => '', 
            '<p>' => '', 
            '</p>' => '', 
            '<p></p>' => '', 
       );
    $content = strtr($content, $fix);
    $content = wpautop( preg_replace( '/<\/?p\>/', "\n", $content ) . "\n" );

    return do_shortcode(shortcode_unautop($content));
}


// **********************************************************************// 
// ! Fix IE
// **********************************************************************// 
function add_ieFix () {
    $ie_css = get_template_directory_uri() .'/css/ie-fix.css';
    echo '<!--[if lt IE 9]>';
    echo '<link rel="stylesheet" type="text/css" href="'.$ie_css.'">';
    echo '<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>';
    echo "<script>var head = document.getElementsByTagName('head')[0],style = document.createElement('style');style.type = 'text/css';style.styleSheet.cssText = ':before,:after{content:none !important';head.appendChild(style);setTimeout(function(){head.removeChild(style);}, 0);</script>";
    echo '<![endif]-->';
}
add_action('wp_head', 'add_ieFix');


// **********************************************************************// 
// ! Remove message Woocommerce
// **********************************************************************// 
function remove_upgrade_nag() {
   echo '<style type="text/css">
           .woocommerce-message.updated, .plugin-update-tr, .rs-update-notice-wrap {display: none}
         </style>';
}
add_action('admin_head', 'remove_upgrade_nag');


// **********************************************************************// 
// ! Escape HTML in post and comments
// **********************************************************************// 
// Escape HTML tags in post content
add_filter('the_content', 'escape_code_fragments');

// Escape HTML tags in comments
add_filter('pre_comment_content', 'escape_code_fragments');

function escape_code_fragments($source) {
  $encoded = preg_replace_callback('/<script(.*?)>(.*?)<\/script>/ims',
  create_function(
    '$matches',
    '$matches[2] = preg_replace(
        array("/^[\r|\n]+/i", "/[\r|\n]+$/i"), "",
        $matches[2]);
      return "<pre" . $matches[1] . ">" . esc_html( $matches[2] ) . "</pre>";'
  ),
  $source);

  if ($encoded)
    return $encoded;
  else
    return $source;
}


// **********************************************************************// 
// ! Remove Wordpress update
// **********************************************************************// 
add_action('admin_menu','wphidenag');
function wphidenag() {
    remove_action( 'admin_notices', 'update_nag', 3 );
    remove_filter( 'update_footer', 'core_update_footer' );
}


// **********************************************************************// 
// ! Filter add  property='stylesheet' to the wp enqueue style
// **********************************************************************//
function mycustom_wpenqueue( $src ){
    return str_replace("rel='stylesheet'","rel='stylesheet' property='stylesheet'",$src);
}
add_filter('style_loader_tag', 'mycustom_wpenqueue');



// **********************************************************************// 
// ! Filter add LeethemeNavDropdown to the widget Custom Menu
// **********************************************************************// 
function myplugin_custom_walker( $args ) {
    return array_merge( $args, array(
        //'walker' => new LeethemeNavDropdown()
    ) );
}
add_filter( 'wp_nav_menu_args', 'myplugin_custom_walker' );



// **********************************************************************// 
// ! Add Logout URL
// **********************************************************************// 
function new_logout_url($logouturl, $redir)
{
    $redir = get_option('siteurl');
    return $logouturl . '&amp;redirect_to=' . urlencode($redir);
}
add_filter('logout_url', 'new_logout_url', 10, 2);


// **********************************************************************// 
// ! Add Font Awesome and Font Pe7s
// **********************************************************************// 
function add_font_awesome() {   
    wp_register_style('font-awesome-style', get_template_directory_uri() . '/css/font-awesome-4.2.0/css/font-awesome.min.css');
    wp_enqueue_style('font-awesome-style');
}
add_action('wp_enqueue_scripts', 'add_font_awesome');

function add_font_pe7s() {   
    wp_register_style('font-pe7s-style', get_template_directory_uri() . '/css/pe-icon-7-stroke/css/pe-icon-7-stroke.css');
    wp_register_style('font-pe7s-helper-style', get_template_directory_uri() . '/css/pe-icon-7-stroke/css/helper.css');
    wp_enqueue_style('font-pe7s-style');
    wp_enqueue_style('font-pe7s-helper-style');
}
add_action('wp_enqueue_scripts', 'add_font_pe7s');



// **********************************************************************// 
// ! Remove Wordpress update
// **********************************************************************// 




// **********************************************************************// 
// ! Other functions
// **********************************************************************// 
function leetheme_enhanced_image_navigation( $url, $id ) {
    if ( ! is_attachment() && ! wp_attachment_is_image( $id ) )
        return $url;

    $image = get_post( $id );
    if ( ! empty( $image->post_parent ) && $image->post_parent != $id )
        $url .= '#main';

    return $url;
}
add_filter( 'attachment_link', 'leetheme_enhanced_image_navigation', 10, 2 );


if(function_exists('get_term_meta')){
function pippin_taxonomy_edit_meta_field($term) {
    $t_id = $term->term_id;
    $term_meta = get_term_meta($t_id,'cat_meta');
    if(!$term_meta){$term_meta = add_term_meta($t_id, 'cat_meta', '');}
     ?>
    <tr class="form-field">
    <th scope="row" valign="top"><label for="term_meta[cat_header]"><?php _e( 'Top Content', 'pippin' ); ?></label></th>
        <td>                
                <?php 

                $content = esc_attr( $term_meta[0]['cat_header'] ) ? esc_attr( $term_meta[0]['cat_header'] ) : ''; 
                echo '<textarea id="term_meta[cat_header]" name="term_meta[cat_header]">'.$content.'</textarea>'; ?>
            <p class="description"><?php _e( 'Enter a value for this field. Shortcodes are allowed. This will be displayed at top of the category.','pippin' ); ?></p>
        </td>
    </tr>
<?php
}
add_action( 'product_cat_edit_form_fields', 'pippin_taxonomy_edit_meta_field', 10, 2 );

function save_taxonomy_custom_meta( $term_id ) {
    if ( isset( $_POST['term_meta'] ) ) {
        $t_id = $term_id;
        $term_meta = get_term_meta($t_id,'cat_meta');
        $cat_keys = array_keys( $_POST['term_meta'] );
        foreach ( $cat_keys as $key ) {
            if ( isset ( $_POST['term_meta'][$key] ) ) {
                $term_meta[$key] = $_POST['term_meta'][$key];
            }
        }
        update_term_meta($term_id, 'cat_meta', $term_meta);

    }
}  
add_action( 'edited_product_cat', 'save_taxonomy_custom_meta', 10, 2 );  
}


if(!is_home()) {
function share_meta_head() {
    global $post; ?>
    <meta property="og:title" content="<?php the_title(); ?>" />
    <?php if (isset($post->ID)){ ?>
        <?php if (has_post_thumbnail( $post->ID ) ): ?>
            <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
            <meta property="og:image" content="<?php echo $image[0]; ?>" />
        <?php endif; ?>
    <?php } ?>
    <meta property="og:url" content="<?php the_permalink(); ?>" />
<?php 
}
add_action('wp_head', 'share_meta_head');
}


function short_excerpt($limit) {
      $excerpt = explode(' ', get_the_excerpt(), $limit);
      if (count($excerpt)>=$limit) {
        array_pop($excerpt);
        $excerpt = implode(" ",$excerpt).'...';
      } else {
        $excerpt = implode(" ",$excerpt);
      } 
      $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
      return $excerpt;
    }

    function content($limit) {
      $content = explode(' ', get_the_content(), $limit);
      if (count($content)>=$limit) {
        array_pop($content);
        $content = implode(" ",$content).'...';
      } else {
        $content = implode(" ",$content);
      } 
      $content = preg_replace('/\[.+\]/','', $content);
      $content = apply_filters('the_content', $content); 
      $content = str_replace(']]>', ']]&gt;', $content);
      return $content;
}


function hex2rgba($color, $opacity = false) {
    $default = 'rgb(0,0,0)';
    if(empty($color))
          return $default; 
        if ($color[0] == '#' ) {
            $color = substr( $color, 1 );
        }

        if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        }


        $rgb =  array_map('hexdec', $hex);


        if($opacity){
            if(abs($opacity) > 1)
                $opacity = 1.0;
            $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
            $output = 'rgb('.implode(",",$rgb).')';
        }

        return $output;
}



add_filter('sod_ajax_layered_nav_product_container', 'bery_product_container');
function bery_product_container($product_container){
    return 'ul.products';
}

?>