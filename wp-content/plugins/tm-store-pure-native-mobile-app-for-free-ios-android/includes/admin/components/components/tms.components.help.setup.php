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

function tms_component_components_setup()
{
	// HOOKABLE: 
	do_action( "tms_component_components_setup_start" );

	GLOBAL $WORDPRESS_TM_STORE_COMPONENTS;
?>
<div style="padding: 15px; margin-bottom: 8px; border: 1px solid #ddd; background-color: #fff;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
	<?php _tms_e( "By default, only the three TMS core components are enabled. You can selectively enable or disable any of the non-core components by using the form below. Your TMS installation will continue to function. However, the features of the disabled components will no longer be accessible", 'wordpress-tm-store' ) ?>.
</div>

<form action="" method="post">
	<table class="widefat fixed plugins" cellspacing="0">
		<thead>
			<tr>
				<th scope="col" class="manage-column column-label" style="width: 190px;"><?php _tms_e( "Component", 'wordpress-tm-store' ) ?></th>
				<th scope="col" class="manage-column column-description"><?php _tms_e( "Description", 'wordpress-tm-store' ) ?></th>
				<th scope="col" class="manage-column column-action" style="width: 140px;">&nbsp;</th>
			</tr>
		</thead>

		<tfoot>
			<tr>
				<th scope="col" class="manage-column column-label" style="width: 190px;"><?php _tms_e( "Component", 'wordpress-tm-store' ) ?></th>
				<th scope="col" class="manage-column column-description"><?php _tms_e( "Description", 'wordpress-tm-store' ) ?></th>
				<th scope="col" class="manage-column column-action" style="width: 140px;">&nbsp;</th>
			</tr>
		</tfoot>

		<tbody id="the-list"> 
			<?php
				foreach( $WORDPRESS_TM_STORE_COMPONENTS as $name => $settings )
				{ 
					$plugin_tr_class  = '';
					$plugin_notices   = '';
					$plugin_enablable = true;

					if( $name == "core" )
					{
						continue;
					}

					$plugin_tr_class = $settings["enabled"] ? "active" : "inactive"; 
			?>
				<tr id="<?php echo $name ?>" class="<?php echo $name ?> <?php echo $plugin_tr_class ?>"> 
					<td class="component-label" style="width: 190px;"> &nbsp;
						<?php if( $settings["type"] == "core" ): ?>
							<div class="icon16 icon-generic"></div>
						<?php elseif( $settings["type"] == "addon" ): ?>
							<div class="icon16 icon-plugins"></div>
						<?php else: ?>
							<div class="icon16 icon-appearance"></div>
						<?php endif; ?>
						
						<strong><?php _tms_e( $settings["label"], 'wordpress-tm-store' ) ?></strong> 
					</td>
					<td class="column-description">
						<p><?php _tms_e( $settings["description"], 'wordpress-tm-store' ) ?></p>
						<?php
							$meta = array();

							if( isset( $settings["version"] ) )
							{
								$meta[] = sprintf( _tms__( "Version %s", 'wordpress-tm-store' ), $settings["version"] );
							}

							if( isset( $settings["author"] ) )
							{
								if( isset( $settings["author_url"] ) )
								{
									$meta[] = sprintf( _tms__( 'By <a href="%s" target="_blank">%s</a>', 'wordpress-tm-store' ), $settings["author_url"], $settings["author"] );
								}
								else
								{
									$meta[] = sprintf( _tms__( 'By %s', 'wordpress-tm-store' ), $settings["author"] );
								}
							}

							if( isset( $settings["component_url"] ) )
							{
								$meta[] = sprintf( _tms__( '<a href="%s" target="_blank">Visit component site</a>', 'wordpress-tm-store' ), $settings["component_url"] );
							}

							if( $meta )
							{
								?><p><?php echo implode( ' | ', $meta  ); ?></p><?php 
							}
						?>
					</td>
					<td class="column-action" align="right" style="width: 120px;">
						<p>
							<?php if( $plugin_enablable && $settings["type"] != "core" ): ?>
								<?php if( $settings["enabled"] ): ?> 
									<a class="button-secondary" href="options-general.php?page=wordpress-tm-store&tmsp=components&disable=<?php echo $name ?>"><?php _tms_e( "Disable", 'wordpress-tm-store' ) ?></a>
								<?php else: ?>
									<a class="button-primary" style="color:#ffffff" href="options-general.php?page=wordpress-tm-store&tmsp=components&enable=<?php echo $name ?>"><?php _tms_e( "Enable", 'wordpress-tm-store' ) ?>&nbsp;</a>
								<?php endif; ?>
							<?php endif; ?>
							&nbsp;
						</p>
					</td>
				</tr>
			<?php
				} 
			?>
		</tbody>
	</table>
</form> 
<?php
	// HOOKABLE: 
	do_action( "tms_component_components_setup_end" );
}

// --------------------------------------------------------------------	
