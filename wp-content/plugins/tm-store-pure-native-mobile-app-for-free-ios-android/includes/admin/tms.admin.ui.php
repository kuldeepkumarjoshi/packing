<?php
/*!
* WordPress TM Store
*

*/

/**
* The LOC in charge of displaying TMS Admin GUInterfaces
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Generate tms admin pages
*
* wp-admin/admin.php?page=wordpress-tm-store&..
*/
function tms_admin_main()
{
	// HOOKABLE:
	do_action( "tms_admin_main_start" );

	if ( ! current_user_can('manage_options') )
	{
		wp_die( 'You do not have sufficient permissions to access this page.' );
	}

	if( ! tms_check_requirements() )
	{
		tms_admin_ui_fail();

		exit;
	}

	GLOBAL $WORDPRESS_TM_STORE_ADMIN_TABS;
	GLOBAL $WORDPRESS_TM_STORE_COMPONENTS;
	GLOBAL $WORDPRESS_TM_STORE_PROVIDERS_CONFIG;
	GLOBAL $WORDPRESS_TM_STORE_VERSION;

	if( isset( $_REQUEST["enable"] ) && isset( $WORDPRESS_TM_STORE_COMPONENTS[ $_REQUEST["enable"] ] ) )
	{
		$component = $_REQUEST["enable"];

		$WORDPRESS_TM_STORE_COMPONENTS[ $component ][ "enabled" ] = true;

		update_option( "tms_components_" . $component . "_enabled", 1 );

		tms_register_components();
	}

	if( isset( $_REQUEST["disable"] ) && isset( $WORDPRESS_TM_STORE_COMPONENTS[ $_REQUEST["disable"] ] ) )
	{
		$component = $_REQUEST["disable"];

		$WORDPRESS_TM_STORE_COMPONENTS[ $component ][ "enabled" ] = false;

		update_option( "tms_components_" . $component . "_enabled", 2 );

		tms_register_components();
	}

	$tmsp            = "tms-plugin-settings";
	$tmsdwp          = 0;
	$assets_base_url = WORDPRESS_TM_STORE_PLUGIN_URL . 'assets/img/16x16/';

	if( isset( $_REQUEST["tmsp"] ) )
	{
		$tmsp = trim( strtolower( strip_tags( $_REQUEST["tmsp"] ) ) );
	}

	tms_admin_ui_header( $tmsp );

	if( isset( $WORDPRESS_TM_STORE_ADMIN_TABS[$tmsp] ) && $WORDPRESS_TM_STORE_ADMIN_TABS[$tmsp]["enabled"] )
	{
		if( isset( $WORDPRESS_TM_STORE_ADMIN_TABS[$tmsp]["action"] ) && $WORDPRESS_TM_STORE_ADMIN_TABS[$tmsp]["action"] )
		{
			do_action( $WORDPRESS_TM_STORE_ADMIN_TABS[$tmsp]["action"] );
		}
		else
		{
			include "components/$tmsp/index.php";
		}
	}
	else
	{
		tms_admin_ui_error();
	}

	tms_admin_ui_footer();

	// HOOKABLE:
	do_action( "tms_admin_main_end" );
}

// --------------------------------------------------------------------

/**
* Render tms admin pages header (label and tabs)
*/
function tms_admin_ui_header( $tmsp = null )
{
	// HOOKABLE:
	do_action( "tms_admin_ui_header_start" );

	GLOBAL $WORDPRESS_TM_STORE_VERSION;
	GLOBAL $WORDPRESS_TM_STORE_ADMIN_TABS;

?>
<a name="tmstop"></a>
<div class="tms-container">

	<?php
		// nag

		if( in_array( $tmsp, array( 'networks', 'login-widget' ) ) and ( isset( $_REQUEST['settings-updated'] ) or isset( $_REQUEST['enable'] ) ) )
		{
			$active_plugins = implode('', (array) get_option('active_plugins') );
			$cache_enabled  =
				strpos( $active_plugins, "w3-total-cache"   ) !== false |
				strpos( $active_plugins, "wp-super-cache"   ) !== false |
				strpos( $active_plugins, "quick-cache"      ) !== false |
				strpos( $active_plugins, "wp-fastest-cache" ) !== false |
				strpos( $active_plugins, "wp-widget-cache"  ) !== false |
				strpos( $active_plugins, "hyper-cache"      ) !== false;

			if( $cache_enabled )
			{
				?>
					<div class="fade updated" style="margin: 4px 0 20px;">
						<p>
							<?php _tms_e("<b>Note:</b> TMS has detected that you are using a caching plugin. If the saved changes didn't take effect immediately then you might need to empty the cache", 'wordpress-tm-store') ?>.
						</p>
					</div>
				<?php
			}
		}

		if( get_option( 'tms_settings_development_mode_enabled' ) )
		{
			?>
				<div class="fade error tms-error-dev-mode-on" style="margin: 4px 0 20px;">
					<p>
						<?php _tms_e('<b>Warning:</b> You are now running TM Store with DEVELOPMENT MODE enabled. This mode is not intend for live websites as it might raise serious security risks', 'wordpress-tm-store') ?>.
					</p>
					<p>
						<a class="button-secondary" href="admin.php?page=wordpress-tm-store&tmsp=tools#dev-mode"><?php _tms_e('Change this mode', 'wordpress-tm-store') ?></a>
						<a class="button-secondary" href="troubleshooting-advanced.html" target="_blank"><?php _tms_e('Read about the development mode', 'wordpress-tm-store') ?></a>
					</p>
				</div>
			<?php
		}

		if( get_option( 'tms_settings_debug_mode_enabled' ) )
		{
			?>
				<div class="fade updated tms-error-debug-mode-on" style="margin: 4px 0 20px;">
					<p>
						<?php _tms_e('<b>Note:</b> You are now running TM Store with DEBUG MODE enabled. This mode is not intend for live websites as it might add to loading time and store unnecessary data on your server', 'wordpress-tm-store') ?>.
					</p>
					<p>
						<a class="button-secondary" href="admin.php?page=wordpress-tm-store&tmsp=tools#debug-mode"><?php _tms_e('Change this mode', 'wordpress-tm-store') ?></a>
						<a class="button-secondary" href="admin.php?page=wordpress-tm-store&tmsp=watchdog"><?php _tms_e('View TMS logs', 'wordpress-tm-store') ?></a>
						<a class="button-secondary" href="troubleshooting-advanced.html" target="_blank"><?php _tms_e('Read about the debug mode', 'wordpress-tm-store') ?></a>
					</p>
				</div>
			<?php
		}
	?>

	<div class="alignright">
		<?php /*?><a style="font-size: 0.9em; text-decoration: none;" target="_blank" href="documentation.html"><?php _tms_e('Docs', 'wordpress-tm-store') ?></a> -
		<a style="font-size: 0.9em; text-decoration: none;" target="_blank" href="support.html"><?php _tms_e('Support', 'wordpress-tm-store') ?></a> -
		<a style="font-size: 0.9em; text-decoration: none;" target="_blank" href=""><?php _tms_e('Github', 'wordpress-tm-store') ?></a><?php */?>
	</div>

	<h1 <?php if( is_rtl() ) echo 'style="margin: 20px 0;"'; ?>>
		<?php _tms_e( 'TM Store', 'wordpress-tm-store' ) ?>

		<small><?php echo $WORDPRESS_TM_STORE_VERSION ?></small>
	</h1>

	<h2 class="nav-tab-wrapper">
		&nbsp;
		<?php
			$css_pull_right = "";
			
			foreach( $WORDPRESS_TM_STORE_ADMIN_TABS as $name => $settings )
			{
				if( $settings["enabled"] && ( $settings["visible"] || $tmsp == $name ) )
				{
					if( isset( $settings["pull-right"] ) && $settings["pull-right"] )
					{
						$css_pull_right = "float:right";

						if( is_rtl() )
						{
							$css_pull_right = "float:left";
						}
					}
					if( ($settings["label"] != "Bouncer" ) && ( empty($settings["ico"]) )){
						?><a class="nav-tab <?php if( $tmsp == $name ) echo "nav-tab-active"; ?>" style="<?php echo $css_pull_right; ?>" href="admin.php?page=wordpress-tm-store&tmsp=<?php echo $name ?>"><?php if( isset( $settings["ico"] ) ) echo '<img style="margin: 0px; padding: 0px; border: 0px none;width: 16px; height: 16px;" src="' . WORDPRESS_TM_STORE_PLUGIN_URL . '/assets/img/' . $settings["ico"] . '" />'; else _tms_e( $settings["label"], 'wordpress-tm-store' ); ?></a><?php
					}
				}
			}
		?>
	</h2>

	<div id="tms_admin_tab_content">
<?php
	// HOOKABLE:
	do_action( "tms_admin_ui_header_end" );
}

// --------------------------------------------------------------------

/**
* Renders tms admin pages footer
*/
function tms_admin_ui_footer()
{
	// HOOKABLE:
	do_action( "tms_admin_ui_footer_start" );

	GLOBAL $WORDPRESS_TM_STORE_VERSION;
?>
	</div> <!-- ./tms_admin_tab_content -->

<div class="clear"></div>

<?php
	tms_admin_help_us_localize_note();

	// HOOKABLE:
	do_action( "tms_admin_ui_footer_end" );

	if( get_option( 'tms_settings_development_mode_enabled' ) )
	{
		tms_display_dev_mode_debugging_area();
 	}
}

// --------------------------------------------------------------------

/**
* Renders tms admin error page
*/
function tms_admin_ui_error()
{
	// HOOKABLE:
	do_action( "tms_admin_ui_error_start" );
?>
<div id="tms_div_warn">
	<h3 style="margin:0px;"><?php _tms_e('Oops! We ran into an issue.', 'wordpress-tm-store') ?></h3>

	<hr />

	<p>
		<?php _tms_e('Unknown or Disabled <b>Component</b>! Check the list of enabled components or the typed URL', 'wordpress-tm-store') ?> .
	</p>

	<p>
		<?php _tms_e("If you believe you've found a problem with <b>WordPress TM Store</b>, be sure to let us know so we can fix it", 'wordpress-tm-store') ?>.
	</p>

	<hr />

	<div>
		<a class="button-secondary" href="support.html" target="_blank"><?php _tms_e( "Report as bug", 'wordpress-tm-store' ) ?></a>
		<a class="button-primary" href="admin.php?page=wordpress-tm-store&tmsp=components" style="float:<?php if( is_rtl() ) echo 'left'; else echo 'right'; ?>"><?php _tms_e( "Check enabled components", 'wordpress-tm-store' ) ?></a>
	</div>
</div>
<?php
	// HOOKABLE:
	do_action( "tms_admin_ui_error_end" );
}

// --------------------------------------------------------------------

/**
* Renders TMS #FAIL page
*/
function tms_admin_ui_fail()
{
	// HOOKABLE:
	do_action( "tms_admin_ui_fail_start" );
?>
<div class="tms-container">
		<div style="background: none repeat scroll 0 0 #fff;border: 1px solid #e5e5e5;box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);padding:20px;">
			<h1><?php _e("TM Store - FAIL!", 'wordpress-tm-store') ?></h1>

			<hr />

			<p>
				<?php _e('Despite the efforts, put into <b>WordPress TM Store</b> in terms of reliability, portability, and maintenance by the plugin <a href="http://profiles.wordpress.org/" target="_blank">author</a> and <a href="https://github.com/hybridauth/WordPress-Social-Login/graphs/contributors" target="_blank">contributors</a>', 'wordpress-tm-store') ?>.
				<b style="color:red;"><?php _e('Your server failed the requirements check for this plugin', 'wordpress-tm-store') ?>:</b>
			</p>

			<p>
				<?php _e('These requirements are usually met by default by most "modern" web hosting providers, however some complications may occur with <b>shared hosting</b> and, or <b>custom wordpress installations</b>', 'wordpress-tm-store') ?>.
			</p>

			<p>
				<?php _tms_e("The minimum server requirements are", 'wordpress-tm-store') ?>:
			</p>

			<ul style="margin-left:60px;">
				<li><?php _tms_e("PHP >= 5.2.0 installed", 'wordpress-tm-store') ?></li>
				<li><?php _tms_e("TMS Endpoint URLs reachable", 'wordpress-tm-store') ?></li>
				<li><?php _tms_e("PHP's default SESSION handling", 'wordpress-tm-store') ?></li>
				<li><?php _tms_e("PHP/CURL/SSL Extension enabled", 'wordpress-tm-store') ?></li>
				<li><?php _tms_e("PHP/JSON Extension enabled", 'wordpress-tm-store') ?></li>
				<li><?php _tms_e("PHP/REGISTER_GLOBALS Off", 'wordpress-tm-store') ?></li>
				<li><?php _tms_e("jQuery installed on WordPress backoffice", 'wordpress-tm-store') ?></li>
			</ul>
		</div>

<?php
	include_once( WORDPRESS_TM_STORE_ABS_PATH . 'includes/admin/components/tools/tms.components.tools.actions.job.php' );

	tms_component_tools_do_diagnostics();
?>
</div>
<style>.tms-container .button-secondary { display:none; }</style>
<?php
	// HOOKABLE:
	do_action( "tms_admin_ui_fail_end" );
}

// --------------------------------------------------------------------

/**
* Renders tms admin welcome panel
*/
function tms_admin_welcome_panel()
{
	if( isset( $_REQUEST["tmsdwp"] ) && (int) $_REQUEST["tmsdwp"] )
	{
		$tmsdwp = (int) $_REQUEST["tmsdwp"];

		update_option( "tms_settings_welcome_panel_enabled", tms_get_version() );

		return;
	}

	// if new user or tms updated, then we display tms welcome panel
	if( get_option( 'tms_settings_welcome_panel_enabled' ) == tms_get_version() )
	{
		return;
	}

	$tmsp = "networks";

	if( isset( $_REQUEST["tmsp"] ) )
	{
		$tmsp = $_REQUEST["tmsp"];
	}
?>
<?php
}

// --------------------------------------------------------------------

/**
* Renders tms localization note
*/
function tms_admin_help_us_localize_note()
{
	return; // nothing, until I decide otherwise..

	$assets_url = WORDPRESS_TM_STORE_PLUGIN_URL . 'assets/img/';

	?>
		<div id="l10n-footer">
			<br /><br />
			<img src="<?php echo $assets_url ?>flags.png">
			<a href="https://www.transifex.com/projects/p/wordpress-tm-store/" target="_blank"><?php _tms_e( "Help us translate TM Store into your language", 'wordpress-tm-store' ) ?></a>
		</div>
	<?php
}

// --------------------------------------------------------------------

/**
* Renders an editor in a page in the typical fashion used in Posts and Pages.
* wp_editor was implemented in wp 3.3. if not found we fallback to a regular textarea
*
* Utility.
*/
function tms_render_wp_editor( $name, $content )
{
	if( ! function_exists( 'wp_editor' ) )
	{
		?>
			<textarea style="width:100%;height:100px;margin-top:6px;" name="<?php echo $name ?>"><?php echo htmlentities( $content ); ?></textarea>
		<?php
		return;
	}
?>
<div class="postbox">
	<div class="wp-editor-textarea" style="background-color: #FFFFFF;">
		<?php
			wp_editor(
				$content, $name,
				array( 'textarea_name' => $name, 'media_buttons' => true, 'tinymce' => array( 'theme_advanced_buttons1' => 'formatselect,forecolor,|,bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink' ) )
			);
		?>
	</div>
</div>
<?php
}

// --------------------------------------------------------------------

/**
* Display TM Store on settings as submenu
*/
function tms_admin_menu()
{	
	$assets_url = WORDPRESS_TM_STORE_PLUGIN_URL . 'assets/img/';
	add_menu_page( 'TM Store', 'TM Store', 'manage_options', 'wordpress-tm-store', 'tms_admin_main', $assets_url. 'menu-icon.png', 56.8 );
	add_action( 'admin_init', 'tms_register_setting' );
}

add_action('admin_menu', 'tms_admin_menu' );

// --------------------------------------------------------------------

/**
* Enqueue TMS admin CSS file
*/
function tms_add_admin_stylesheets()
{
	if( ! wp_style_is( 'tms-admin', 'registered' ) )
	{
		wp_register_style( "tms-admin", WORDPRESS_TM_STORE_PLUGIN_URL . "assets/css/admin.css" );
	}

	wp_enqueue_style( "tms-admin" );
}

add_action( 'admin_enqueue_scripts', 'tms_add_admin_stylesheets' );

// --------------------------------------------------------------------