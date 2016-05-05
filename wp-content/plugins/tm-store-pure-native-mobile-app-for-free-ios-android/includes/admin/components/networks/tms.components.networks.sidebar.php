<?php
/*!
* WordPress TM Store
*

*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

function tms_component_networks_sidebar()
{
	// HOOKABLE:
	do_action( "tms_component_networks_sidebar_start" );

	$sections = array(
		'what_is_this'   => 'tms_component_networks_sidebar_what_is_this',
		'add_more_idps'  => 'tms_component_networks_sidebar_add_more_idps',
		'basic_insights' => 'tms_component_networks_sidebar_basic_insights',
	);

	$sections = apply_filters( 'tms_component_networks_sidebar_alter_sections', $sections );

	foreach( $sections as $section => $action )
	{
		add_action( 'tms_component_networks_sidebar_sections', $action );
	}

	// HOOKABLE:
	do_action( 'tms_component_networks_sidebar_sections' );
}

// --------------------------------------------------------------------

function tms_component_networks_sidebar_what_is_this()
{
	//
}

add_action( 'tms_component_networks_sidebar_what_is_this', 'tms_component_networks_sidebar_what_is_this' );

// --------------------------------------------------------------------

function tms_component_networks_sidebar_add_more_idps()
{
	//
}

add_action( 'tms_component_networks_sidebar_add_more_idps', 'tms_component_networks_sidebar_add_more_idps' );

// --------------------------------------------------------------------

function tms_component_networks_sidebar_basic_insights()
{
	//
}

add_action( 'tms_component_networks_sidebar_basic_insights', 'tms_component_networks_sidebar_basic_insights' );

// --------------------------------------------------------------------
