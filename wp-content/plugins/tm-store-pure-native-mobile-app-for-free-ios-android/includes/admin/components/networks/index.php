<?php
/*!
* WordPress TM Store
*

*/

/**
* Social networks configuration and setup
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function tms_component_networks()
{
	// HOOKABLE: 
	do_action( "tms_component_networks_start" );

	include "tms.components.networks.setup.php";
	include "tms.components.networks.sidebar.php"; 

	tms_admin_welcome_panel();
?>

<form method="post" id="tms_setup_form" action="options.php"> 
	<?php settings_fields( 'tms-settings-group' ); ?>

	<div class="metabox-holder columns-2" id="post-body">
		<table width="100%"> 
			<tr valign="top">
				<td> 
					<div id="post-body-content">
						<?php
							tms_component_networks_setup();
						?>
						<a name="tmssettings"></a> 
					</div>
				</td>
			</tr>
		</table> 
	</div>
</form>
<?php
	// HOOKABLE: 
	do_action( "tms_component_networks_end" );
}

tms_component_networks();

// --------------------------------------------------------------------	
