<?php

// Custom WooCommerce product fields
if(!function_exists('wc_custom_product_data_fields')){

  function wc_custom_product_data_fields(){

    $custom_product_data_fields = array();

    $custom_product_data_fields[] = array(
          'tab_name'    => __('Additional', 'ltheme_domain'),
    );

   $custom_product_data_fields[] = array(
          'id'          => '_product_video_link',
          'type'        => 'text',
          'placeholder' => 'https://www.youtube.com/watch?v=link-test',
          'label'       => __('Product Video Link', 'ltheme_domain'),
          'style'       => 'width:100%;',
          'description' => __('Enter a Youtube or Vimeo Url of the product video here.', 'ltheme_domain'),
    );

    $custom_product_data_fields[] = array(
          'id'          => '_product_video_size',
          'type'        => 'text',
          'label'       => __('Product Video Size', 'ltheme_domain'),
          'placeholder' => __('800x800', 'ltheme_domain'),
          'class'       => 'large',
          'style'       => 'width:100%;',
          'description' => __('Default is 800x800. (Width X Height)', 'ltheme_domain'),
          //'desc_tip'    => true,
    );

    return $custom_product_data_fields;
  }
}
?>
