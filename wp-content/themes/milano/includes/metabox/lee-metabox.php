<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'lee_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function lee_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_lee_';
	/* Get Footer style */
	$footers_type = '';
	$footers_type = get_posts(array('posts_per_page'=>-1, 'post_type'=>'footer'));
	$footers_option = array();
	$footers_option['default'] = 'Default';
	foreach ($footers_type as $key => $value){
		$footers_option[$value->ID] = $value->post_title;
	}

	$meta_boxes['lee_metabox'] = array(
		'id'         => 'lee_metabox',
		'title'      => __( 'Options Page', 'ltheme_domain' ),
		'pages'      => array( 'page', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     =>  array(
			array(
				'name'    => __( 'Header Type', 'ltheme_domain' ),
				'desc'    => __( 'Description (optional)', 'ltheme_domain' ),
				'id'      => $prefix . 'custom_header',
				'type'    => 'select',
				'options' => array(
					'' => __( 'Default', 'ltheme_domain' ),
					'1'   => __( 'Header Type 1', 'ltheme_domain' ),
					'2'     => __( 'Header Type 2', 'ltheme_domain' ),
					'3'     => __( 'Header Type 3', 'ltheme_domain' ),
				),
				'default' => 'custom'
			),
			array(
				'name' => __( 'Header Transparent', 'ltheme_domain' ),
				'desc' => 'Only for Header type 2',
				'id'   => $prefix . 'header_transparent',
				'type' => 'checkbox',
			),
			array(
				'name' => __( 'Show Breadcrumb', 'ltheme_domain' ),
				'desc' => 'Yes, please',
				'id'   => $prefix . 'show_breadcrumb',
				'type' => 'checkbox',
			),
			array(
				'name' => __( 'Custom Logo', 'ltheme_domain' ),
				'desc' => __( 'Upload an image for override default logo.', 'ltheme_domain' ),
				'id'   => $prefix . 'custom_logo',
				'allow' => false,
				'type' => 'file',
			),
			array(
				'name'    => __( 'Footer Type', 'ltheme_domain' ),
				'desc'    => __( 'Description (optional)', 'ltheme_domain' ),
				'id'      => $prefix . 'custom_footer',
				'type'    => 'select',
				'options' => $footers_option,
				'default' => 'custom'
			),
		)
	);

	return $meta_boxes;
}

add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'init.php';

}
