<?php
/**
 * YITH WooCommerce Ajax Search template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Ajax Search
 * @version 1.0.0
 */

if ( !defined( 'YITH_WCAS' ) ) { exit; }


wp_enqueue_script('yith_wcas_jquery-autocomplete' );
$rand_id = rand();
?>

<div class="row collapse search-wrapper yith-ajaxsearchform-container <?php echo esc_attr($rand_id); ?>_container">
    <form method="get" id="yith-ajaxsearchform" action="<?php echo esc_url( home_url( '/'  ) ) ?>">
        <label>SEARCH</label>
        <button type="submit" id="yith-searchsubmit"></button>
        <input class="search-input" type="search" value="<?php echo get_search_query() ?>" name="s" id="<?php echo esc_attr($rand_id); ?>_yith" placeholder="<?php echo _e('Search here','woocommerce'); ?>&hellip;" />
    </form>
</div>

<script type="text/javascript">
jQuery(function($){
    $('#<?php echo esc_attr($rand_id); ?>_yith').autocomplete({
        minChars: <?php echo get_option('yith_wcas_min_chars') * 1; ?>,
        appendTo: '.<?php echo esc_attr($rand_id); ?>_container',
        serviceUrl: woocommerce_params.ajax_url + '?action=yith_ajax_search_products',
        onSearchStart: function(){
            $('.<?php echo esc_attr($rand_id); ?>_container').append('<div class="please-wait"><i></i><i></i><i></i><i></i></div>');
        },
        onSearchComplete: function(){
            $('.<?php echo esc_attr($rand_id); ?>_container .please-wait').remove();

        },
        onSelect: function (suggestion) {
            if( suggestion.id != -1 ) {
                window.location.href = suggestion.url;
            }
        }
    });
});
</script>