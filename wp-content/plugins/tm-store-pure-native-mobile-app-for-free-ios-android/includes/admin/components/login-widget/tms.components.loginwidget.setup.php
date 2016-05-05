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

function tms_component_loginwidget_setup()
{
	// HOOKABLE: 
	do_action( "tms_component_loginwidget_setup_start" );

	$sections = array(
		'basic_settings'    => 'tms_component_loginwidget_setup_basic_settings',
		'advanced_settings' => 'tms_component_loginwidget_setup_advanced_settings',
		'custom_css'        => 'tms_component_loginwidget_setup_custom_css', 
	);

	$sections = apply_filters( 'tms_component_loginwidget_setup_alter_sections', $sections );

	foreach( $sections as $section => $action )
	{
		add_action( 'tms_component_loginwidget_setup_sections', $action );
	} 
?>

<div>
	<?php
		// HOOKABLE: 
		do_action( 'tms_component_loginwidget_setup_sections' );
	?>
	<br />
	<div style="margin-left:5px;margin-top:-20px;">
		<input type="submit" class="button-primary" value="<?php _tms_e("Save Settings", 'wordpress-tm-store') ?>" />
	</div>
</div>
<?php
}

// --------------------------------------------------------------------	

function tms_component_loginwidget_setup_basic_settings()
{
	//
}

// --------------------------------------------------------------------	

function tms_component_loginwidget_setup_advanced_settings()
{
	$assets_setup_base_url = WORDPRESS_TM_STORE_PLUGIN_URL . 'assets/img/';
?>
<div class="stuffbox">
	<h3>
		<label>
			<?php _tms_e("Advanced Setting", 'wordpress-tm-store') ?>
		</label>
	</h3>
	<div class="inside">
		<p>
			<?php _tms_e("<b>Widget display :</b> Determines where you want to show the authentication widget", 'wordpress-tm-store') ?>
			. </p>
		<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">
			<tr style="display:none;">
				<td width="180" align="right"><strong>
					<?php _tms_e("Redirect URL", 'wordpress-tm-store') ?>
					:</strong></td>
				<td><input type="text" name="tms_settings_redirect_url" class="inputgnrc" style="width:535px" value="<?php echo get_option( 'tms_settings_redirect_url' ); ?>"></td>
			</tr>
			<tr style="display:none;">
				<td align="right"><strong>
					<?php _tms_e("Force redirection", 'wordpress-tm-store') ?>
					:</strong></td>
				<td><select name="tms_settings_force_redirect_url" style="width:100px">
						<option <?php if( get_option( 'tms_settings_force_redirect_url' ) == 1 ) echo "selected"; ?> value="1">
						<?php _tms_e("Yes", 'wordpress-tm-store') ?>
						</option>
						<option <?php if( get_option( 'tms_settings_force_redirect_url' ) == 2 ) echo "selected"; ?> value="2">
						<?php _tms_e("No", 'wordpress-tm-store') ?>
						</option>
					</select></td>
			</tr>
			<tr style="display:none;">
				<td align="right"><strong>
					<?php _tms_e("Authentication display", 'wordpress-tm-store') ?>
					:</strong></td>
				<td><select name="tms_settings_use_popup" style="width:100px">
						<option <?php if( get_option( 'tms_settings_use_popup' ) == 1 ) echo "selected"; ?> value="1">
						<?php _tms_e("Popup", 'wordpress-tm-store') ?>
						</option>
						<option <?php if( get_option( 'tms_settings_use_popup' ) == 2 ) echo "selected"; ?> value="2">
						<?php _tms_e("In Page", 'wordpress-tm-store') ?>
						</option>
					</select></td>
			</tr>
			<tr>
				<td><strong>
					<?php _tms_e("Widget display", 'wordpress-tm-store') ?>
					:</strong></td>
				<td><?php
					$widget_display = array(
						4 => "Do not display the widget anywhere, I'll use shortcodes",
						1 => "Display the widget in the comments area, login and register forms",
						3 => "Display the widget only in the login and register forms",
						2 => "Display the widget only in the comments area",
					);

					$widget_display = apply_filters( 'tms_component_loginwidget_setup_alter_widget_display', $widget_display );
					
					$tms_settings_widget_display = get_option( 'tms_settings_widget_display' );
				?>
					<select name="tms_settings_widget_display" style="width:535px">
						<?php
						foreach( $widget_display as $display => $label )
						{
							?>
						<option <?php if( $tms_settings_widget_display == $display ) echo "selected"; ?>   value="<?php echo $display; ?>">
						<?php _tms_e( $label, 'wordpress-tm-store' ) ?>
						</option>
						<?php
						}
					?>
					</select></td>
			</tr>
			<tr style="display:none;">
				<td align="right"><strong>
					<?php _tms_e("Notification", 'wordpress-tm-store') ?>
					:</strong></td>
				<td><?php
					$users_notification = array(
						1 => "Notify ONLY the blog admin of a new user",
					);

					$users_notification = apply_filters( 'tms_component_loginwidget_setup_alter_users_notification', $users_notification );
					
					$tms_settings_users_notification = get_option( 'tms_settings_users_notification' );
				?>
					<select name="tms_settings_users_notification" style="width:535px">
						<option <?php if( $tms_settings_users_notification == 0 ) echo "selected"; ?> value="0">
						<?php _tms_e("No notification", 'wordpress-tm-store') ?>
						</option>
						<?php
						foreach( $users_notification as $type => $label )
						{
							?>
						<option <?php if( $tms_settings_users_notification == $type ) echo "selected"; ?>   value="<?php echo $type; ?>">
						<?php _tms_e( $label, 'wordpress-tm-store' ) ?>
						</option>
						<?php
						}
					?>
					</select></td>
			</tr>
		</table>
		<div style="" class="tms_div_settings_help_Facebook">
			<hr class="tms">
			<p>Below are the images for the <span style="color:#CB4B16;">Login Form, Register Form and Comment Area</span>.</p>
			<table style="text-align: center;margin-bottom:12px;">
				<tbody>
						<td><a target="_blank" href="<?php echo $assets_setup_base_url . 'login-form.png' ?>" class="span4 thumbnail"><img src="<?php echo $assets_setup_base_url . 'login-form.png' ?>"></a></td>
						<td><a target="_blank" href="<?php echo $assets_setup_base_url . 'register-form.png' ?>" class="span4 thumbnail"><img src="<?php echo $assets_setup_base_url . 'register-form.png' ?>"></a></td>
						<td><a target="_blank" href="<?php echo $assets_setup_base_url . 'comment-form.png' ?>" class="span4 thumbnail"><img src="<?php echo $assets_setup_base_url . 'comment-form.png' ?>"></a></td>
					</tr>
				</tbody>
			</table>
			<br>
		</div>
	</div>
</div>
<?php
}

// --------------------------------------------------------------------	

function tms_component_loginwidget_setup_custom_css()
{
	//
}

// --------------------------------------------------------------------	

