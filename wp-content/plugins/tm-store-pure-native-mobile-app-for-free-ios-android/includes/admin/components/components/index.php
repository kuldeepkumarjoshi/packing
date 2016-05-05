<?php
/*!
* WordPress TM Store
*

*/

/**
* Components Manager 
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function tms_component_help()
{
	// HOOKABLE: 
	do_action( "tms_component_help_start" );

	include "tms.components.help.setup.php";
	include "tms.components.help.gallery.php";

	tms_component_components_setup();
	
	tms_component_components_gallery();

	// HOOKABLE: 
	do_action( "tms_component_help_end" );
}

tms_component_help();

// --------------------------------------------------------------------	
