<?php
/*!
* WordPress TM Store
*/

// ------------------------------------------------------------------------
//	TMS End Point
// ------------------------------------------------------------------------

/**
* If for whatever reason you want to debug apis call made by hybridauth during the auth process, you can add the block of code below.
*
* <code>
*    include_once( '/path/to/file/wp-load.php' );
*    define( 'WORDPRESS_TM_STORE_DEBUG_API_CALLS', true );
*    add_action( 'tms_log_provider_api_call', 'tms_watchdog_tms_log_provider_api_call', 10, 8 );
*    do_action( 'tms_log_provider_api_call', 'ENDPOINT', 'Hybridauth://endpoint', null, null, null, null, $_SERVER["QUERY_STRING"] );
* </code>
*/

//- Re-parse the QUERY_STRING for custom endpoints.
if( defined( 'WORDPRESS_TM_STORE_CUSTOM_ENDPOINT' ) && ! isset( $_REQUEST['hauth_start'] ) ) 
{
	$_SERVER["QUERY_STRING"] = 'hauth_done=' . WORDPRESS_TM_STORE_CUSTOM_ENDPOINT . '&' . str_ireplace( '?', '&', $_SERVER["QUERY_STRING"] );

	parse_str( $_SERVER["QUERY_STRING"], $_REQUEST );
}

//- Hybridauth required includes
require_once( "Hybrid/Storage.php"   );
require_once( "Hybrid/Error.php"     );
require_once( "Hybrid/Auth.php"      );
require_once( "Hybrid/Exception.php" );
require_once( "Hybrid/Endpoint.php"  );


//- Custom TMS endpoint class
require_once( "endpoints/TMS_Endpoint.php" );


//- Entry point to the End point 
TMS_Hybrid_Endpoint::process();
