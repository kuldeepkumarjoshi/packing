<?php

global $ros_opt;

// **********************************************************************// 
// ! Blog post navigation
// **********************************************************************//  
if ( ! function_exists( 'leetheme_content_nav' ) ) :


function leetheme_content_nav( $nav_id ) {
    global $wp_query, $post;

    if ( is_single() ) {
        $previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
        $next = get_adjacent_post( false, '', false );

        if ( ! $next && ! $previous )
            return;
    }

    if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
        return;

    $nav_class = ( is_single() ) ? 'navigation-post' : 'navigation-paging';

    ?>
    <nav role="navigation" id="<?php echo esc_attr($nav_id); ?>" class="<?php echo esc_attr($nav_class); ?>">
    <?php if ( is_single() ) :?>

        <?php previous_post_link( '<div class="nav-previous left">%link</div>', '<span class="fa fa-caret-left">' . _x( '', 'Previous post link', 'ltheme_domain' ) . '</span> %title' ); ?>
        <?php next_post_link( '<div class="nav-next right">%link</div>', '%title <span class="fa fa-caret-right">' . _x( '', 'Next post link', 'ltheme_domain' ) . '</span>' ); ?>

    <?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

        <?php if ( get_next_posts_link() ) : ?>
        <div class="nav-previous"><?php next_posts_link( __( 'Next <span class="fa fa-caret-right"></span>', 'ltheme_domain' ) ); ?></div>
        <?php endif; ?>

        <?php if ( get_previous_posts_link() ) : ?>
        <div class="nav-next"><?php previous_posts_link( __( '<span class="fa fa-caret-left"></span> Previous' , 'ltheme_domain' ) ); ?></div>
        <?php endif; ?>

    <?php endif; ?>

    </nav>
    <?php
}
endif; 


// **********************************************************************// 
// ! Comments
// **********************************************************************//  
if ( ! function_exists( 'leetheme_comment' ) ) :
function leetheme_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'ltheme_domain' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'ltheme_domain' ), '<span class="edit-link">', '<span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment-inner">

            <div class="row collapse">
                <div class="large-2 columns">
                    <div class="comment-author">
                    <?php echo get_avatar( $comment, 80 ); ?>
                </div>
                </div>

                <div class="large-10 columns">
                    <?php printf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ; ?>

                    <div class="comment-meta commentmetadata right">
                        <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time datetime="<?php comment_time( 'c' ); ?>">
                        <?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'ltheme_domain' ), get_comment_date(), get_comment_time() ); ?>
                        </time></a>

                        <?php edit_comment_link( __( 'Edit', 'ltheme_domain' ), '<span class="edit-link">', '<span>' ); ?>
                        
                     </div>
                     <div class="reply">
                            <?php
                                comment_reply_link( array_merge( $args,array(
                                    'depth'     => $depth,
                                    'max_depth' => $args['max_depth'],
                                ) ) );
                            ?>
                        </div>

                    <?php if ( $comment->comment_approved == '0' ) : ?>
                    <em><?php _e( 'Your comment is awaiting moderation.', 'ltheme_domain' ); ?></em>
                    <br />
                     <?php endif; ?>

                <div class="comment-content"><?php comment_text(); ?></div>

                </div>

            </div>

		</article>


	<?php
			break;
	endswitch;
}
endif; 

// **********************************************************************// 
// ! Post meta top
// **********************************************************************//  
if ( ! function_exists( 'leetheme_posted_on' ) ) :
function leetheme_posted_on() {
	printf( __( '<span class="meta-author">by <strong><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></strong>.</span> Posted on <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>', 'ltheme_domain' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'ltheme_domain' ), get_the_author() ) ),
		get_the_author()
	);
}
endif;


// **********************************************************************// 
// ! Promo Popup
// **********************************************************************// 
add_action('after_page_wrapper', 'leetheme_promo_popup');
if(!function_exists('leetheme_promo_popup')) {
    
    function leetheme_promo_popup() {
        global $ros_opt;
        ?>

        <div id="leetheme-popup" class="white-popup-block mfp-hide mfp-with-anim zoom-anim-dialog">
            <?php echo (isset($ros_opt['pp_content'])) ? do_shortcode($ros_opt['pp_content']) : '';?>
            <p class="checkbox-label">
                <input type="checkbox" value="do-not-show" name="showagain" id="showagain" class="showagain" />
                <label for="showagain"><?php _e("Don't show this popup again", 'ltheme_domain'); ?></label>
            </p>
        </div>
        <style type="text/css">
        #leetheme-popup{
            width: <?php echo (isset($ros_opt['pp_width'])) ? $ros_opt['pp_width'] : 700; ?>px;
            height: <?php echo (isset($ros_opt['pp_height'])) ? $ros_opt['pp_height'] : 350; ?>px;
            background-image: url(<?php echo (isset($ros_opt['pp_background'])) ? $ros_opt['pp_background'] : ''?>);
        }
        </style>
    <?php
    }
}



add_filter( 'wp_nav_menu_objects', 'add_menu_parent_class' );
function add_menu_parent_class( $items ) {
	$parents = array();
	foreach ( $items as $item ) {
		if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
			$parents[] = $item->menu_item_parent;
		}
	}
	
	foreach ( $items as $item ) {
		if ( in_array( $item->ID, $parents ) ) {
			$item->classes[] = 'menu-parent-item'; 
		}

	}
	return $items;    
}

// **********************************************************************// 
// ! Add to cart dropdown
// **********************************************************************// 
add_filter('add_to_cart_fragments', 'leetheme_add_to_cart_dropdown');
function leetheme_add_to_cart_dropdown( $fragments ) {
    global $woocommerce;
    global $ros_opt;
    ob_start(); 
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
                <div class="cart_title"><?php _e('Recently Added Item(s)','ltheme_domain'); ?></div>
            <div class="nav-dropdown-inner">
            <div class="cart_list">
            <?php                               
                if (sizeof($woocommerce->cart->cart_contents)>0) : foreach ($woocommerce->cart->cart_contents as $cart_item_key => $cart_item) :
                    $_product = $cart_item['data'];                                            
                    if ($_product->exists() && $cart_item['quantity']>0) :  ?>  
                    <div class="row mini-cart-item collapse">
                        <div class="small-3 large-3 columns">
                            <?php  echo '<a class="cart_list_product_img" href="'.get_permalink($cart_item['product_id']).'">' . str_replace( array( 'http:', 'https:' ), '', $_product->get_image() ).'</a>'; ?>
                        </div>
                        <div class="small-7 large-7 columns">
                            <div class="mini-cart-info">
                                <?php
                                     $product_title = $_product->get_title();
                                     echo '<a class="cart_list_product_title" href="'.get_permalink($cart_item['product_id']).'">' . apply_filters('woocommerce_cart_widget_product_title', $product_title, $_product) . '</a>';
                                     echo '<div class="cart_list_product_quantity">'.$cart_item['quantity'].' x '.woocommerce_price($_product->get_price()).'</div>';
                                 ?>
                             </div>
                        </div>
                        <div class="small-2 large-2 columns"> 
                            
                            <?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s"><i class="fa fa-trash-o"></i></a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __('Remove this item', 'woocommerce') ), $cart_item_key ); ?>
                        </div>
                        
                        
                    </div>

            <?php                                        
                endif;                                        
                endforeach; 
            ?>

            </div>
                <div class="minicart_total_checkout">                                        
                    <?php _e('Subtotal', 'woocommerce'); ?><span><?php echo $woocommerce->cart->get_cart_total(); ?></span>                                   
                </div>
                    <div class="btn-mini-cart inline-lists">
                            <a href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" class="button btn-viewcart"><?php _e('View Cart', 'ltheme_domain'); ?></a>
                            <?php 
                                global $woocommerce;
                                if ( sizeof( $woocommerce->cart->cart_contents) > 0 ) { ?>
                                <a href="<?php echo $woocommerce->cart->get_checkout_url() ?>" class="button btn-checkout" title="<?php _e( 'Checkout','ltheme_domain' ) ?>"><?php _e( 'Checkout', 'ltheme_domain'); ?></a>
                            <?php } ?>
                    </div>
                </div>
                <?php                                        
                else: echo '<p class="empty">'.__('No products in the cart.','woocommerce').'</p>'; endif;
            ?>                                                                        
        </div>
    </div>
</div>

    <?php
    $fragments['.cart-inner'] = ob_get_clean();
    return $fragments;
}

//add_action('add_to_cart_btn', 'add_to_cart_btn_function');
function add_to_cart_btn($type = 'small'){
    global $product;
    
    $link = array(
        'url'   => '',
        'label' => '',
        'class' => ''
    );

    $handler = apply_filters( 'woocommerce_add_to_cart_handler', $product->product_type, $product );
    $check = false;
    switch ( $handler ) {
        case "variable" :
            $link['url']    = apply_filters( 'variable_add_to_cart_url', get_permalink( $product->id ) );
            $link['label']  = apply_filters( 'variable_add_to_cart_text', __( 'Select options', 'woocommerce' ) );
        break;
        case "grouped" :
            $link['url']    = apply_filters( 'grouped_add_to_cart_url', get_permalink( $product->id ) );
            $link['label']  = apply_filters( 'grouped_add_to_cart_text', __( 'View options', 'woocommerce' ) );
        break;
        case "external" :
            $link['url']    = apply_filters( 'external_add_to_cart_url', get_permalink( $product->id ) );
            $link['label']  = apply_filters( 'external_add_to_cart_text', __( 'Read More', 'woocommerce' ) );
        break;
        default :
            if ( $product->is_purchasable() ) {
                $link['url']    = apply_filters( 'add_to_cart_url', esc_url( $product->add_to_cart_url() ) );
                $link['label']  = apply_filters( 'add_to_cart_text', __( 'Add to cart', 'woocommerce' ) );
                $link['class']  = apply_filters( 'add_to_cart_class', 'add_to_cart_button' );
            } else {
                $link['url']    = apply_filters( 'not_purchasable_url', get_permalink( $product->id ) );
                $link['label']  = apply_filters( 'not_purchasable_text', __( 'Read More', 'woocommerce' ) );
            }
            $check = true;
        break;
    }
    
    switch($type){
		case 'large':
			if($check){
                echo apply_filters( 'woocommerce_loop_add_to_cart_link', sprintf('<div rel="nofollow" data-product_id="%s" data-product_sku="%s" class="%s product_type_%s button tip-top add-to-cart-grid btn-link add-to-cart-list text-center" data-tip="%s">
                <span class="add_to_cart_text">ADD TO CART</span>
                <span class="cart-icon-handle"></span>
                </div>', esc_attr( $product->id ), esc_attr( $product->get_sku() ), esc_attr( $link['class'] ), esc_attr( $product->product_type ), esc_html( $link['label'] ) ), $product, $link );
            }else{
				
            }
			break;
		
		case 'small':
		default:
            if($check){
    			echo apply_filters( 'woocommerce_loop_add_to_cart_link', sprintf('<div rel="nofollow" data-product_id="%s" data-product_sku="%s" class="%s product_type_%s tip-top add-to-cart-grid btn-link" data-tip="%s">
                <span class="add_to_cart_text">ADD TO CART</span>
    	        <div class="cart-icon">
    	        <span class="fa fa-shopping-cart"></span>
    	        <span class="cart-icon-handle"></span>
    	        </div></div>', esc_attr( $product->id ), esc_attr( $product->get_sku() ), esc_attr( $link['class'] ), esc_attr( $product->product_type ), esc_html( $link['label'] ) ), $product, $link );
            }else{
				echo apply_filters( 'woocommerce_loop_add_to_cart_link', sprintf('<div rel="nofollow" data-product_id="%s" data-product_sku="%s" class="%s product_type_%s tip-top add-to-cart-grid btn-link" data-tip="%s">
                <a href="%s" title="%s"><span class="add_to_cart_text">ADD TO CART</span>
    	        <div class="cart-icon">
    	        <span class="fa fa-shopping-cart"></span>
    	        <span class="cart-icon-handle"></span>
    	        </div></a></div>', esc_attr( $product->id ), esc_attr( $product->get_sku() ), esc_attr( $link['class'] ), esc_attr( $product->product_type ), esc_html( $link['label'] ), esc_url( $link['url'] ),  esc_attr( $product->post->post_title )), $product, $link );
            }
			break;
	}
    
}

function ProductShowReviews(){
    if ( comments_open() ) {
        global $wpdb;
        global $post;
    
        $count = $wpdb->get_var( $wpdb->prepare("
            SELECT COUNT(meta_value) FROM $wpdb->commentmeta
            LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
            WHERE meta_key = %s
            AND comment_post_ID = %s
            AND comment_approved = %s
            AND meta_value > %s
        ",
        'rating', $post->ID, '1', '0'
        ));
    
        $rating = $wpdb->get_var( $wpdb->prepare("
            SELECT SUM(meta_value) FROM $wpdb->commentmeta
            LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
            WHERE meta_key = %s
            AND comment_post_ID = %s
            AND comment_approved = %s
        ",
        'rating', $post->ID, '1'
        ));
    
        if ( $count > 0 ) {
    
            $average = number_format($rating / $count, 2);
    
            echo '<a href="#tab-reviews" class="scroll-to-reviews"><div class="star-rating tip-top" data-tip="'.$count.' review(s)"><span style="width:'.($average*16).'px"><span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="rating"><span itemprop="ratingValue">'.$average.'</span><span itemprop="reviewCount" class="hidden">'.$count.'</span></span> '.__('out of 5', 'woocommerce').'</span></div></a>';

        }
        
    }
}

add_action('woocommerce_single_product_summary','ProductShowReviews', 15);
add_action('woocommerce_single_review','ProductShowReviews', 10);
            


function get_adjacent_post_product( $in_same_cat = false, $excluded_categories = '', $previous = true ) {
    global $wpdb;

    if ( ! $post = get_post() )
        return null;

    $current_post_date = $post->post_date;
    $join = '';
    $posts_in_ex_cats_sql = '';
    if ( $in_same_cat || ! empty( $excluded_categories ) ) {
        $join = " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";

        if ( $in_same_cat ) {
            if ( ! is_object_in_taxonomy( $post->post_type, 'product_cat' ) )
                return '';
            $cat_array = wp_get_object_terms($post->ID, 'product_cat', array('fields' => 'ids'));
            if ( ! $cat_array || is_wp_error( $cat_array ) )
                return '';
            $join .= " AND tt.taxonomy = 'product_cat' AND tt.term_id IN (" . implode(',', $cat_array) . ")";
        }

        $posts_in_ex_cats_sql = "AND tt.taxonomy = 'product_cat'";
        if ( ! empty( $excluded_categories ) ) {
            if ( ! is_array( $excluded_categories ) ) {
                if ( strpos( $excluded_categories, ' and ' ) !== false ) {
                    _deprecated_argument( __FUNCTION__, '3.3', _e('Use commas instead of and to separate excluded categories.','ltheme_domain'));
                    $excluded_categories = explode( ' and ', $excluded_categories );
                } else {
                    $excluded_categories = explode( ',', $excluded_categories );
                }
            }

            $excluded_categories = array_map( 'intval', $excluded_categories );

            if ( ! empty( $cat_array ) ) {
                $excluded_categories = array_diff($excluded_categories, $cat_array);
                $posts_in_ex_cats_sql = '';
            }

            if ( !empty($excluded_categories) ) {
                $posts_in_ex_cats_sql = " AND tt.taxonomy = 'product_cat' AND tt.term_id NOT IN (" . implode($excluded_categories, ',') . ')';
            }
        }
    }

    $adjacent = $previous ? 'previous' : 'next';
    $op = $previous ? '<' : '>';
    $order = $previous ? 'DESC' : 'ASC';

    $join  = apply_filters( "get_{$adjacent}_post_join", $join, $in_same_cat, $excluded_categories );
    $where = apply_filters( "get_{$adjacent}_post_where", $wpdb->prepare("WHERE p.post_date $op %s AND p.post_type = %s AND p.post_status = 'publish' $posts_in_ex_cats_sql", $current_post_date, $post->post_type), $in_same_cat, $excluded_categories );
    $sort  = apply_filters( "get_{$adjacent}_post_sort", "ORDER BY p.post_date $order LIMIT 1" );

    $query = "SELECT p.id FROM $wpdb->posts AS p $join $where $sort";
    $query_key = 'adjacent_post_' . md5($query);
    $result = wp_cache_get($query_key, 'counts');
    if ( false !== $result ) {
        if ( $result )
            $result = get_post( $result );
        return $result;
    }

    $result = $wpdb->get_var( $wpdb->prepare($query) );
    if ( null === $result )
        $result = '';

    wp_cache_set($query_key, $result, 'counts');

    if ( $result )
        $result = get_post( $result );

    return $result;
}

// **********************************************************************// 
// ! Blog - Add "Read more" links
// **********************************************************************// 
function leetheme_add_morelink_class( $link, $text )
{
    return str_replace(
         'more-link'
        ,'more-link button small'
        ,$link
    );
}
add_action( 'the_content_more_link', 'leetheme_add_morelink_class', 10, 2 );


// **********************************************************************// 
// ! Language Flags
// **********************************************************************// 
function lee_language_flages(){
    $language_output = '<select id="lee-language-switch">';
    if (function_exists('icl_get_languages')){
        $languages = icl_get_languages('skip_missing=0&orderby=code');
        if (!empty($languages)){
            foreach ($languages as $l){
                if ($l['country_flag_url']){
                    if ($l['active']){
                        $language_output .= '<option selected="selected" value="'.$l['url'].'">'.$l['translated_name'].'</option>'."\n";
                    }else{
                        $language_output .= '<option value="'.$l['url'].'">'.$l['translated_name'].'</option>'."\n";
                    }
                }
            }
        }
    }else{
        $language_output .= '
            <option value="">English</option>
            <option value="">German</option>
            <option value="">Spanish</option>
            <option value="">Demo</option>
        ';
    }
    $language_output .= '</select>';
    return $language_output;
}



// **********************************************************************// 
// ! Product Quick View
// **********************************************************************// 
add_action('wp_head', 'wpse83650_lazy_ajax', 0, 0);
function wpse83650_lazy_ajax()
{
    ?>
    <script type="text/javascript">
    /* <![CDATA[ */
    var ajaxurl = "<?php echo esc_js(admin_url('admin-ajax.php')); ?>";
    /* ]]> */
    </script>
    <?php
}
add_action('wp_ajax_jck_quickview', 'jck_quickview');
add_action('wp_ajax_nopriv_jck_quickview', 'jck_quickview');



function jck_quickview() {
    global $post, $product, $woocommerce;
    $prod_id =  $_POST["product"];
    $post = get_post($prod_id);
    $product = get_product($prod_id);
    ob_start();
?>
<?php woocommerce_get_template( 'content-single-product-lightbox.php'); ?>

<?php
    $output = ob_get_contents();
    ob_end_clean();
    echo $output;
    die();
}


add_action( 'woocommerce_single_product_lightbox_summary', 'woocommerce_template_single_price', 10 );
add_action( 'woocommerce_single_product_lightbox_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_single_product_lightbox_summary', 'woocommerce_template_single_add_to_cart', 30 );
add_action( 'woocommerce_single_product_lightbox_summary', 'woocommerce_template_single_sharing', 40 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );


add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 20 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 25 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 35 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );


if(isset($_GET["catalog-mode"])){
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
    remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
    remove_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
    remove_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
    remove_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );
    remove_action( 'woocommerce_single_product_lightbox_summary', 'woocommerce_template_single_add_to_cart', 30 );

        if(isset($_GET["catalog-mode"])){
                remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
                remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
                remove_action( 'woocommerce_single_product_lightbox_summary', 'woocommerce_template_single_price', 10 );
        }

        function catalog_mode_product(){
            global $ros_opt;
            echo '<div class="catalog-product-text">';
            echo do_shortcode($ros_opt['catalog_mode_product']);
            echo '</div>';
        }
        add_action('woocommerce_single_product_summary', 'catalog_mode_product', 30);

        function catalog_mode_lightbox(){
            global $ros_opt;
            echo '<div class="catalog-product-text">';
            echo do_shortcode($ros_opt['catalog_mode_lightbox']);
            echo '</div>';
        }
        add_action( 'woocommerce_single_product_lightbox_summary', 'catalog_mode_lightbox', 30 );

}

function leetheme_pre_get_posts_action( $query ) {
    global $ros_opt;
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    if($action == 'woocommerce_json_search_products') {
        return;
    }
    if(defined('DOING_AJAX') && DOING_AJAX && !empty($query->query_vars['s'])){
        $query->query_vars['post_type'] = array( $query->query_vars['post_type'], 'post', 'page' );
        $query->query_vars['meta_query'] = new WP_Meta_Query( array( 'relation' => 'OR', $query->query_vars['meta_query'] ) );
    }
}
add_action('pre_get_posts', 'leetheme_pre_get_posts_action');

function leetheme_posts_results_filter( $posts, $query ) {
    global $ros_opt;
    if (defined('DOING_AJAX') && DOING_AJAX && !empty($query->query_vars['s'])) {
        foreach ($posts as $key => $post) {
            foreach (array('myaccount', 'edit_address', 'change_password', 'lost_password', 'shop', 'cart', 'checkout', 'pay', 'view_order', 'thanks', 'terms') as $wc_page_type) {
                if( $post->ID == woocommerce_get_page_id($wc_page_type) ) unset($posts[$key]);
            }
        }
    }
    return $posts;
}
add_filter( 'posts_results', 'leetheme_posts_results_filter', 10, 2 );


add_action('wp_ajax_get_shortcode', 'get_shortcode');
add_action('wp_ajax_nopriv_get_shortcode', 'get_shortcode');

function get_shortcode() {
    $content =  $_POST["content"];
    print do_shortcode($content);
    die();
}

// This extending class is for solving a problem when "getElementById()" returns NULL
class MyDOMDocument extends DOMDocument {

    function getElementById( $id ) {

        //thanks to: http://www.php.net/manual/en/domdocument.getelementbyid.php#96500
        $xpath = new DOMXPath( $this );
        return $xpath->query( "//*[@id='$id']" )->item(0);
        
    }

    function output() {

        // thanks to: http://www.php.net/manual/en/domdocument.savehtml.php#85165
        $output = preg_replace( '/^<!DOCTYPE.+?>/', '',
                    str_replace( array('<html>', '</html>', '<body>', '</body>'),
                            array('', '', '', ''), $this->saveHTML()
                        )
                    );

        return trim( $output );

    }

}

/*==========================================================================
 WooCommerce - Function get Query
==========================================================================*/
function lee_woocommerce_query($type,$post_per_page=-1,$cat='',$paged=''){
    $args = lee_woocommerce_query_args($type, $post_per_page, $cat, $paged);
    return new WP_Query($args);
}

function lee_woocommerce_query_args($type,$post_per_page=-1,$cat='',$paged=''){
    global $woocommerce;
    remove_filter( 'posts_clauses', array( $woocommerce->query, 'order_by_popularity_post_clauses' ) );
    remove_filter( 'posts_clauses', array( $woocommerce->query, 'order_by_rating_post_clauses' ) );
    if($paged == '') {
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    }
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $post_per_page,
        'post_status' => 'publish',
        'paged' => $paged
    );
    switch ($type) {
        case 'best_selling':
            $args['meta_key']='total_sales';
            $args['orderby']='meta_value_num';
            $args['ignore_sticky_posts']   = 1;
            $args['meta_query'] = array();
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            break;
        case 'featured_product':
            $args['ignore_sticky_posts']=1;
            $args['meta_query'] = array();
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = array(
                         'key' => '_featured',
                         'value' => 'yes'
                     );
            $query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            break;
        case 'top_rate':
            add_filter( 'posts_clauses',  array( $woocommerce->query, 'order_by_rating_post_clauses' ) );
            $args['meta_query'] = array();
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            break;
        case 'recent_product':
            $args['meta_query'] = array();
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            break;
        case 'on_sale':
            $args['meta_query'] = array();
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            $args['post__in'] = wc_get_product_ids_on_sale();
            break;
        case 'recent_review':
            if($post_per_page == -1) $_limit = 4;
            else $_limit = $post_per_page;
            global $wpdb;
            $query = "SELECT c.comment_post_ID FROM {$wpdb->prefix}posts p, {$wpdb->prefix}comments c WHERE p.ID = c.comment_post_ID AND c.comment_approved > 0 AND p.post_type = %s AND p.post_status = %s AND p.comment_count > %s ORDER BY c.comment_date ASC LIMIT 0, ". $_limit;
            $results = $wpdb->get_results($wpdb->prepare($query, 'product', 'publish', '0', OBJECT));
            $_pids = array();
            foreach ($results as $re) {
                $_pids[] = $re->comment_post_ID;
            }

            $args['meta_query'] = array();
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            $args['post__in'] = $_pids;
            break;
        case 'deals':
            $args['meta_query'] = array();
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            $args['meta_query'][] = array(
                                 'key' => '_sale_price_dates_to',
                                 'value' => '0',
                                 'compare' => '>');
            $args['post__in'] = wc_get_product_ids_on_sale();
            break;
    }

    if($cat!=''){
        $args['product_cat']= $cat;
    }
    //print_r($woocommerce->query->meta_query);
    return $args;
}

function lee_pagination($page = '', $range = 2, $page_total = ''){
    $showitems = ($range * 2)+1;  

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         //global $wp_query;
         $pages = $page_total;
         if(!$pages)
         {
             $pages = 1;
         }
     }   

     if(1 != $pages)
     {
         //echo "<div class='pagination'>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
             }
         }

         if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";  
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
         //echo "</div>\n";
     }
}


/*==========================================================================
 ADD VIDEO PLAY BUTTON ON PRODUCT DETAIL PAGE
==========================================================================*/
if(!function_exists('product_video_btn_function')){
function product_video_btn_function(){
    global $wc_cpdf;
    if($wc_cpdf->get_value(get_the_ID(), '_product_video_link')){ ?>
        <a class="product-video-popup tip-top" data-tip="<?php _e('View video','ltheme_domain'); ?>" href="<?php echo $wc_cpdf->get_value(get_the_ID(), '_product_video_link'); ?>">
        </a>
        <style>
        <?php
            $height = '800';
            $width = '800';
            $iframe_scale = '100%';
            $custom_size = $wc_cpdf->get_value(get_the_ID(), '_product_video_size');
            if($custom_size){
                $split = explode("x", $custom_size);
                $height = $split[0];
                $width = $split[1];
                $iframe_scale = ($width/$height*100).'%';
            }
            echo '.has-product-video .mfp-iframe-holder .mfp-content{max-width: '.$width.'px;}';
            echo '.has-product-video .mfp-iframe-scaler{padding-top: '.$iframe_scale.'}';
        ?>
        </style>
        <?php }
    }
}
add_action('product_video_btn','product_video_btn_function', 1);

