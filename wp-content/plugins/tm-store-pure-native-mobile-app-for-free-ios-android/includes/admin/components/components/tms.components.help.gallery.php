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

function tms_component_components_gallery()
{
	return; // ya men 3ach

	// HOOKABLE: 
	do_action( "tms_component_components_gallery_start" ); 

	$response = wp_remote_get( 'components-' . tms_get_version() . '.json', array( 'timeout' => 15, 'sslverify' => false ) );

	if ( ! is_wp_error( $response ) )
	{
		$response = wp_remote_retrieve_body( $response );

		$components = json_decode ( $response );

		if( $components )
		{
?> 
<br />

<h2><?php _tms_e( "Other Components available", 'wordpress-tm-store' ) ?></h2>

<p><?php _tms_e( "These components and add-ons can extend the functionality of WordPress TM Store", 'wordpress-tm-store' ) ?>.</p>

<?php
	foreach( $components as $item )
	{
		$item = (array) $item;
		?>
			<div class="tms_component_div">
				<h3 style="margin:0px;"><?php _tms_e( $item['name'], 'wordpress-tm-store' ) ?></h3>
				
				<div class="tms_component_about_div">
					<p>
						<?php _tms_e( $item['description'], 'wordpress-tm-store' ) ?>
						<br />
						<?php echo sprintf( _tms__( '<em>By <a href="%s">%s</a></em>' , 'wordpress-tm-store' ), $item['developer_link'], $item['developer_name'] ); ?>
					</p>
				</div>

				<a class="button button-secondary" href="<?php echo $item['download_link']; ?>" target="_blank"><?php _tms_e( "Get this Component", 'wordpress-tm-store' ) ?></a> 
			</div>	
		<?php
	}
?> 

<div class="tms_component_div">
	<h3 style="margin:0px;"><?php _tms_e( "Build yours", 'wordpress-tm-store' ) ?></h3>

	<div class="tms_component_about_div">
		<p><?php _tms_e( "Want to build your own custom <b>WordPress TM Store</b> component? It's pretty easy. Just refer to the online developer documentation.", 'wordpress-tm-store' ) ?></p>
	</div>

	<a class="button button-primary"   href="documentation.html" target="_blank"><?php _tms_e( "TMS Developer API", 'wordpress-tm-store' ) ?></a> 
	<a class="button button-secondary" href="submit-component.html" target="_blank"><?php _tms_e( "Submit your TMS Component", 'wordpress-tm-store' ) ?></a> 
</div>

<?php
		}
	}

	// HOOKABLE: 
	do_action( "tms_component_components_gallery_end" );
}

// --------------------------------------------------------------------	
