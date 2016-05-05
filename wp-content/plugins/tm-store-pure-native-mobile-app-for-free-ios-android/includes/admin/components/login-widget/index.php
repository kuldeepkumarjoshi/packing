<?php
/*!
* WordPress TM Store
*

*/

/**
* Widget Customization
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function tms_component_loginwidget()
{
	// HOOKABLE: 
	do_action( "tms_component_loginwidget_start" );

	include "tms.components.loginwidget.setup.php";
	include "tms.components.loginwidget.sidebar.php";
?>
<form method="post" id="tms_setup_form" action="options.php"> 
	<?php settings_fields( 'tms-settings-group-customize' ); ?> 

	<div class="metabox-holder columns-2" id="post-body">
		<table width="100%"> 
			<tr valign="top">
				<td>
					
					<?php 
						tms_component_loginwidget_sidebar();
					?>
					<?php
						tms_component_loginwidget_setup();
					?> 
				</td>
			</tr>
		</table>
	</div>
</form>
<?php
	// HOOKABLE: 
	do_action( "tms_component_loginwidget_end" );
}

tms_component_loginwidget();

// --------------------------------------------------------------------	
