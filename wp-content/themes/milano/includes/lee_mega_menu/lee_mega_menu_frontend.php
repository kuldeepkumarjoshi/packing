<?php

/* Mega menu */

class LeethemeNavDropdown extends Walker_Nav_Menu
{
    //private $_k = 1;
    //private $_newdepth = 0;
    private $_mega = array();
    private function getOption( $itemID, $field = '') { 
        return get_post_meta($itemID, '_menu_item_lee_'.$field, true);
    }

    function start_lvl( &$output, $depth = 0, $args = array() ) {
        //$depth = ($depth + 1); // because it counts the first submenu as 0
        $item = '';
        if($depth == '0'){$class_names = 'nav-dropdown';}
        else {$class_names = 'nav-column-links';}
        $indent = str_repeat("\t", $depth);
        $output .= '<div class="'.$class_names.'"><div class="div-sub"><ul class="sub-menu">';
    }

    function end_lvl(&$output, $depth = 1, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= $indent.'</ul></div></div>';
    }
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0){
        global $wp_query;

        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $class_names = $megamenu_class = $widget_class = $megacolumns = $mega_top = $hr = '';
        $widget = $this->getOption($item->ID, 'widget');
        $megamenu = 0;

        if($depth == 0){
            $megamenu = $this->getOption($item->ID, 'enable_mega');
            $megamenu_class = ' default-menu root-item';
        }

        if($megamenu){
            $megamenu_class = ' lee_megamenu root-item';
            $full = $this->getOption($item->ID, 'enable_fullwidth');
            $megacolumns = $this->getOption($item->ID, 'columns_mega');
            if(!$megacolumns) $megacolumns = ' cols-3';
            else $megacolumns = ' cols-'.$megacolumns;
            if($full) $megacolumns .= ' fullwidth';
            $this->_mega[] = $item->ID;
        }

        $classes = empty($item->classes) ? array() : (array)$item->classes;      
          
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item));
        
        if($widget && is_active_sidebar($widget)) $widget_class = ' has_widget_item';

        if($depth == 1 && in_array($item->menu_item_parent, $this->_mega)){
            $mega_top = ' megatop';
            $hr = '<hr class="hr_lee-megamenu" />';
        }

        $class_names = ' class="'. esc_attr( $class_names ) . $widget_class.$megamenu_class.$megacolumns.$mega_top. '"';

        $output .= $indent . '<li ' . $class_names .'>';

        $attributes  = !empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title).'"' : '';
        $attributes .= !empty($item->target)     ? ' target="' . esc_attr($item->target)    .'"' : '';
        $attributes .= !empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn)       .'"' : '';
        $attributes .= !empty($item->url)        ? ' href="'   . esc_attr($item->url)       .'"' : '';

        $prepend = '';
        
        $description  = !empty( $item->description ) ? '<span>'.esc_attr( $item->description ).'</span>' : '';
        if($depth != 0) $description = $prepend = "";

        if(!empty($item->menu_icon)) $prepend .= '<span class="' . esc_attr($item->menu_icon) .' lee_menu_icon"></span>';

        $item_output = '';
        
        if($widget && is_active_sidebar( $widget ) ) {
            $item_output .= '<div class="lee_megamenu_widget">';
            ob_start();
            dynamic_sidebar( $widget );
            $item_output .= ob_get_clean() . '</div>';
        }else{
            if(isset($args->before)) $item_output = $args->before; 
            $item_output .= '<a'. $attributes .'>';
            if(isset($args->link_before)) $item_output .= $args->link_before.$prepend.apply_filters( 'the_title', $item->title, $item->ID );
            $item_output .= '</a>';
        }

        if(isset($args->link_after)) $item_output .= $description.$args->link_after;

        $item_output .= $hr;

        if(isset($args->before)) $item_output .= $args->after;
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args, $id );
    }

    function __destruct(){
        //print_r($this->_mega);
    }
}