<?php
require_once ABSPATH . 'wp-admin/includes/nav-menu.php';
 
class Lee_Nav_Menu_Item_Custom_Fields {

    static $options = array(
        'item_tpl' => '
            <p class="additional-menu-field-{name} description description-{type_show}">
                <label for="edit-menu-item-{name}-{id}">
                    {label}<br />
                    <input
                        type="{input_type}"
                        id="edit-menu-item-{name}-{id}"
                        class="widefat code edit-menu-item-{name}"
                        name="menu-item-{name}[{id}]"
                        value="{value}" />
                </label>
            </p>
        ',
        'checkbox' => '
            <p class="additional-menu-field-{name} description description-{type_show}">
                <label for="edit-menu-item-{name}-{id}">
                    <br /><input
                        type="checkbox"
                        id="edit-menu-item-{name}-{id}"
                        class="widefat code edit-menu-item-{name}"
                        name="menu-item-{name}[{id}]"
                        value="1"{checked} />{label}
                </label>
            </p>
        '
    );
 
    static function setup() {
        if ( !is_admin() )
            return;
 
        $new_fields = apply_filters( 'lee_nav_menu_item_fields', array() );
        if ( empty($new_fields) )
            return;

        self::$options['fields'] = self::get_fields_schema( $new_fields );
        function lee_walker_nav_menu_edit(){
            return 'Lee_Walker_Nav_Menu_Edit';
        }
        add_filter( 'wp_edit_nav_menu_walker', 'lee_walker_nav_menu_edit');

        add_action( 'save_post', array( __CLASS__, '_save_post' ), 10, 2 );
    }
 
    static function get_fields_schema( $new_fields ) {
        $schema = array();
        foreach( $new_fields as $name => $field) {
            if (empty($field['name'])) {
                $field['name'] = $name;
            }
            $schema[] = $field;
        }
        return $schema;
    }
 
    static function get_menu_item_postmeta_key($name) {
        return '_menu_item_lee_' . $name;
    }
 
    /**
     * Inject the 
     * @hook {action} save_post
     */
    static function get_field( $item, $depth, $args ) {
        $new_fields = '';
        foreach( self::$options['fields'] as $field ) {
            $field['value'] = get_post_meta($item->ID, self::get_menu_item_postmeta_key($field['name']), true);
            $field['id'] = $item->ID;

            switch ($field['input_type']) {
                case 'select-widget':
                    $new_fields .= self::getWidgets($field);
                    break;

                case 'select':
                    $new_fields .= self::getSelect($field);
                    break;
                
                case 'checkbox':
                    $field['checked'] = '';
                    if($field['value'] == 1) $field['checked'] = ' checked';
                    $new_fields .= str_replace(
                        array_map(function($key){ return '{' . $key . '}'; }, array_keys($field)),
                        array_values(array_map('esc_attr', $field)),
                        self::$options['checkbox']
                    );

                    break;

                default:
                    $new_fields .= str_replace(
                        array_map(function($key){ return '{' . $key . '}'; }, array_keys($field)),
                        array_values(array_map('esc_attr', $field)),
                        self::$options['item_tpl']
                    );
                    break;
            }
        }
        return $new_fields;
    }
    
    static function getSelect($field){
        $select = '<p class="additional-menu-field-'.$field['name'].' description description-'.$field['type_show'].'">
            <label for="edit-menu-item-'.$field['name'].'-'.$field['id'].'">
                '.$field['label'].'<br />
                <select id="edit-menu-item-'.$field['name'].'-'.$field['id'].'" class="widefat code edit-menu-item-'.$field['name'].'" name="menu-item-'.$field['name'].'['.$field['id'].']">';
                if(!isset($field['default']) || $field['default'] == true) $select .= '<option value="0">'.$field['label'].'</option>';
        if(!empty($field['values']) && is_array($field['values'])){
            foreach($field['values'] as $k => $v){
                $select .= '<option value="'.esc_attr($k).'" '.selected( $field['value'] , $k, false ).'>'.esc_html($v).'</option>';
            }
        }
        $select .= '</select></lable></p>';
        return $select;
    }

    static function getWidgets($field){
        global $wp_registered_sidebars;
        //print_r($wp_registered_sidebars);
        $select = '<p class="additional-menu-field-'.$field['name'].' description description-'.$field['type_show'].'">
            <label for="edit-menu-item-'.$field['name'].'-'.$field['id'].'">
                '.$field['label'].'<br />
                <select id="edit-menu-item-'.$field['name'].'-'.$field['id'].'" class="widefat code edit-menu-item-'.$field['name'].'" name="menu-item-'.$field['name'].'['.$field['id'].']">
                    <option value="0">Select Widget Area</option>';
        if( ! empty( $wp_registered_sidebars ) && is_array( $wp_registered_sidebars ) ){
            foreach( $wp_registered_sidebars as $sidebar ){
                $select .= '<option value="'.esc_attr($sidebar['id']).'" '.selected( $field['value'] , $sidebar['id'], false ).'>'.esc_html($sidebar['name']).'</option>';
            }
        }
        $select .= '</select></lable></p>';
        return $select;
    }

    /**
     * Save the newly submitted fields
     * @hook {action} save_post
     */
    static function _save_post($post_id, $post) {
        if ( $post->post_type !== 'nav_menu_item' ) {
            return $post_id; // prevent weird things from happening
        }
 
        foreach( self::$options['fields'] as $field_schema ) {
            $form_field_name = 'menu-item-' . $field_schema['name'];

            // @todo FALSE should always be used as the default $value, otherwise we wouldn't be able to clear checkboxes
            if ($field_schema['input_type'] == 'checkbox') {
                if(!isset($_POST[$form_field_name][$post_id])) $_POST[$form_field_name][$post_id] = false;
            }

            if (isset($_POST[$form_field_name][$post_id])) {
                $key = self::get_menu_item_postmeta_key($field_schema['name']);
                $value = stripslashes($_POST[$form_field_name][$post_id]);
                update_post_meta($post_id, $key, $value);
            }
        }
    }
 
}


class Lee_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit {
    private $style = '';
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $item_output = '';
        parent::start_el($item_output, $item, $depth, $args, $id);

        if($new_fields = Lee_Nav_Menu_Item_Custom_Fields::get_field($item, $depth, $args))
            $item_output = preg_replace('/(?=<div[^>]+class="[^"]*submitbox)/', $new_fields, $item_output);
        $output .= $item_output;
    }
}

function lee_megamenu_admin_styles() {
    wp_enqueue_style( 'lee_back_end', get_template_directory_uri() .'/includes/lee_mega_menu/lee_mega_menu_backend.css');
}  

add_action( 'admin_enqueue_scripts', 'lee_megamenu_admin_styles' );

// Config more custom fields 
add_filter( 'lee_nav_menu_item_fields', 'lee_menu_item_additional_fields' );
function lee_menu_item_additional_fields() {
    $fields = array(
        'lee_megamenu' => array(
            'name' => 'enable_mega',
            'label' => __('Enable megamenu', 'lthemem_domain'),
            'container_class' => 'enable-widget',
            'input_type' => 'checkbox',
            'type_show' => 'thin'
        ),

        'lee_fullwidth' => array(
            'name' => 'enable_fullwidth',
            'label' => __('Enable Fullwidth', 'lthemem_domain'),
            'container_class' => 'enable-fullwidth',
            'input_type' => 'checkbox',
            'type_show' => 'thin'
        ),

        'lee_select_widget' => array(
            'name' => 'widget',
            'label' => __('Select widget', 'lthemem_domain'),
            'container_class' => 'select-widget',
            'input_type' => 'select-widget',
            'type_show' => 'wide'
        ),
        
        'lee_select_width' => array(
            'name' => 'columns_mega',
            'label' => __('Select number columns in megamenu', 'lthemem_domain'),
            'container_class' => 'select-columns',
            'input_type' => 'select',
            'values' => array(
                '3' => '3 Columns',
                '4' => '4 Columns',
                '5' => '5 Columns',
            ),
            'default' => false,
            'type_show' => 'wide'
        )
    );
 
    return $fields;
}
 
 
add_action( 'init', array( 'Lee_Nav_Menu_Item_Custom_Fields', 'setup' ) );