<?php
/*!
* WordPress TM Store
*

*/

/**
* Check TMS requirements and register TMS settings 
*/

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Check TMS minimum requirements. Display fail page if they are not met.
*
* This function will only test the strict minimal
*/
function tms_check_requirements()
{
	if
	(
		   ! version_compare( PHP_VERSION, '5.2.0', '>=' )
		|| ! isset( $_SESSION["tms::plugin"] )
		|| ! function_exists('curl_init')
		|| ! function_exists('json_decode')
	)
	{
		return false;
	}

	$curl_version = curl_version();

	if( ! ( $curl_version['features'] & CURL_VERSION_SSL ) )
	{
		return false;
	}

	return true;
}

// --------------------------------------------------------------------

/** list of TMS components */
$WORDPRESS_TM_STORE_COMPONENTS = ARRAY(
	"core"           => array( "type" => "core"  , "label" => _tms__("TMS Core"   , 'wordpress-tm-store'), "description" => _tms__("TM Store core."                   , 'wordpress-tm-store') ),
	"tms-plugin-settings"           => array( "type" => "core"  , "label" => _tms__("TMS Plugin Settings"   , 'wordpress-tm-store'), "description" => _tms__("TM Store Plugin Settings."                   , 'wordpress-tm-store') ),
	"networks"       => array( "type" => "core"  , "label" => _tms__("Networks"   , 'wordpress-tm-store'), "description" => _tms__("Social networks setup."                         , 'wordpress-tm-store') ),
	"login-widget"   => array( "type" => "core"  , "label" => _tms__("Widget"     , 'wordpress-tm-store'), "description" => _tms__("Authentication widget customization."           , 'wordpress-tm-store') )
);

/** list of TMS admin tabs */
$WORDPRESS_TM_STORE_ADMIN_TABS = ARRAY(  
	"tms-plugin-settings"     => array( "label" => _tms__("TM Store Plugin Settings"      , 'wordpress-tm-store') , "visible" => true  , "component" => "tms-plugin-settings"       , "default" => true ),
	"networks"     => array( "label" => _tms__("Social Login Settings"      , 'wordpress-tm-store') , "visible" => true  , "component" => "networks" ),
	"login-widget" => array( "label" => _tms__("Widget"        , 'wordpress-tm-store') , "visible" => true  , "component" => "login-widget"   ),
	"auth-paly"    => array( "label" => _tms__("Auth test"     , 'wordpress-tm-store') , "visible" => false , "component" => "core"           , "pull-right" => true , 'ico' => 'magic.png'      ),
);

// --------------------------------------------------------------------

/**
* Register a new TMS component 
*/
function tms_register_component( $component, $label, $description, $version, $author, $author_url, $component_url )
{
	GLOBAL $WORDPRESS_TM_STORE_COMPONENTS;

	$config = array();

	$config["type"]          = "addon"; // < force to addon
	$config["label"]         = $label;
	$config["description"]   = $description;
	$config["version"]       = $version;
	$config["author"]        = $author;
	$config["author_url"]    = $author_url;
	$config["component_url"] = $component_url;

	$WORDPRESS_TM_STORE_COMPONENTS[ $component ] = $config;
}

// --------------------------------------------------------------------

/**
* Register new TMS admin tab
*/
function tms_register_admin_tab( $component, $tab, $label, $action, $visible = false, $pull_right = false ) 
{ 
	GLOBAL $WORDPRESS_TM_STORE_ADMIN_TABS;

	$config = array();

	$config["component"]  = $component;
	$config["label"]      = $label;
	$config["visible"]    = $visible;
	$config["action"]     = $action;
	$config["pull_right"] = $pull_right;

	$WORDPRESS_TM_STORE_ADMIN_TABS[ $tab ] = $config;
}

// --------------------------------------------------------------------

/**
* Check if a component is enabled
*/
function tms_is_component_enabled( $component )
{ 
	if( get_option( "tms_components_" . $component . "_enabled" ) == 1 )
	{
		return true;
	}

	return false;
}

// --------------------------------------------------------------------

/**
* Register TMS components (Bulk action)
*/
function tms_register_components()
{
	GLOBAL $WORDPRESS_TM_STORE_COMPONENTS;
	GLOBAL $WORDPRESS_TM_STORE_ADMIN_TABS;

	// HOOKABLE:
	do_action( 'tms_register_components' );

	foreach( $WORDPRESS_TM_STORE_ADMIN_TABS as $tab => $config )
	{
		$WORDPRESS_TM_STORE_ADMIN_TABS[ $tab ][ "enabled" ] = false; 
	}

	foreach( $WORDPRESS_TM_STORE_COMPONENTS as $component => $config )
	{
		$WORDPRESS_TM_STORE_COMPONENTS[ $component ][ "enabled" ] = false;

		$is_component_enabled = get_option( "tms_components_" . $component . "_enabled" );
		
		if( $is_component_enabled == 1 )
		{
			$WORDPRESS_TM_STORE_COMPONENTS[ $component ][ "enabled" ] = true;
		}

		if( $WORDPRESS_TM_STORE_COMPONENTS[ $component ][ "type" ] == "core" )
		{
			$WORDPRESS_TM_STORE_COMPONENTS[ $component ][ "enabled" ] = true;

			if( $is_component_enabled != 1 )
			{
				update_option( "tms_components_" . $component . "_enabled", 1 );
			}
		}
	}

	foreach( $WORDPRESS_TM_STORE_ADMIN_TABS as $tab => $config )
	{
		$component = $config[ "component" ] ;

		if( $WORDPRESS_TM_STORE_COMPONENTS[ $component ][ "enabled" ] )
		{
			$WORDPRESS_TM_STORE_ADMIN_TABS[ $tab ][ "enabled" ] = true;
		}
	}
}

// --------------------------------------------------------------------

/**
* Register TMS core settings ( options; components )
*/
function tms_register_setting()
{
	GLOBAL $WORDPRESS_TM_STORE_PROVIDERS_CONFIG;
	GLOBAL $WORDPRESS_TM_STORE_COMPONENTS;
	GLOBAL $WORDPRESS_TM_STORE_ADMIN_TABS;

	// HOOKABLE:
	do_action( 'tms_register_setting' );

	tms_register_components();

	// idps credentials
	foreach( $WORDPRESS_TM_STORE_PROVIDERS_CONFIG AS $item )
	{
		$provider_id          = isset( $item["provider_id"]       ) ? $item["provider_id"]       : null;
		$require_client_id    = isset( $item["require_client_id"] ) ? $item["require_client_id"] : null;
		$require_registration = isset( $item["new_app_link"]      ) ? $item["new_app_link"]      : null;
		$default_api_scope    = isset( $item["default_api_scope"] ) ? $item["default_api_scope"] : null;

		/**
		* @fixme
		*
		* Here we should only register enabled providers settings. postponed. patches are welcome.
		***
			$default_network = isset( $item["default_network"] ) ? $item["default_network"] : null;

			if( ! $default_network || get_option( 'tms_settings_' . $provider_id . '_enabled' ) != 1 .. )
			{
				..
			}
		*/

		register_setting( 'tms-settings-group', 'tms_settings_' . $provider_id . '_enabled' );

		// require application?
		if( $require_registration )
		{
			// api key or id ?
			if( $require_client_id )
			{
				register_setting( 'tms-settings-group', 'tms_settings_' . $provider_id . '_app_id' ); 
			}
			else
			{
				register_setting( 'tms-settings-group', 'tms_settings_' . $provider_id . '_app_key' ); 
			}

			// api secret
			register_setting( 'tms-settings-group', 'tms_settings_' . $provider_id . '_app_secret' ); 

			// api scope?
			if( $default_api_scope )
			{
				if( ! get_option( 'tms_settings_' . $provider_id . '_app_scope' ) )
				{
					update_option( 'tms_settings_' . $provider_id . '_app_scope', $default_api_scope );
				}

				register_setting( 'tms-settings-group', 'tms_settings_' . $provider_id . '_app_scope' );
			}
		}
	}

	register_setting( 'tms-settings-group-customize'        , 'tms_settings_connect_with_label'                               ); 
	register_setting( 'tms-settings-group-customize'        , 'tms_settings_social_icon_set'                                  ); 
	register_setting( 'tms-settings-group-customize'        , 'tms_settings_users_avatars'                                    ); 
	register_setting( 'tms-settings-group-customize'        , 'tms_settings_use_popup'                                        ); 
	register_setting( 'tms-settings-group-customize'        , 'tms_settings_widget_display'                                   ); 
	register_setting( 'tms-settings-group-customize'        , 'tms_settings_redirect_url'                                     ); 
	register_setting( 'tms-settings-group-customize'        , 'tms_settings_force_redirect_url'                               ); 
	register_setting( 'tms-settings-group-customize'        , 'tms_settings_users_notification'                               ); 
	register_setting( 'tms-settings-group-customize'        , 'tms_settings_authentication_widget_css'                        ); 

	register_setting( 'tms-settings-group-contacts-import'  , 'tms_settings_contacts_import_facebook'                         ); 
	register_setting( 'tms-settings-group-contacts-import'  , 'tms_settings_contacts_import_google'                           ); 
	register_setting( 'tms-settings-group-contacts-import'  , 'tms_settings_contacts_import_twitter'                          ); 
	register_setting( 'tms-settings-group-contacts-import'  , 'tms_settings_contacts_import_linkedin'                         ); 
	register_setting( 'tms-settings-group-contacts-import'  , 'tms_settings_contacts_import_live'                             ); 
	register_setting( 'tms-settings-group-contacts-import'  , 'tms_settings_contacts_import_vkontakte'                        ); 

	register_setting( 'tms-settings-group-bouncer'          , 'tms_settings_bouncer_registration_enabled'                     ); 
	register_setting( 'tms-settings-group-bouncer'          , 'tms_settings_bouncer_authentication_enabled'                   ); 

	register_setting( 'tms-settings-group-bouncer'          , 'tms_settings_bouncer_accounts_linking_enabled'                 );

	register_setting( 'tms-settings-group-bouncer'          , 'tms_settings_bouncer_profile_completion_require_email'         );
	register_setting( 'tms-settings-group-bouncer'          , 'tms_settings_bouncer_profile_completion_change_username'       );
	register_setting( 'tms-settings-group-bouncer'          , 'tms_settings_bouncer_profile_completion_hook_extra_fields'     );

	register_setting( 'tms-settings-group-bouncer'          , 'tms_settings_bouncer_new_users_moderation_level'               );
	register_setting( 'tms-settings-group-bouncer'          , 'tms_settings_bouncer_new_users_membership_default_role'        );

	register_setting( 'tms-settings-group-bouncer'          , 'tms_settings_bouncer_new_users_restrict_domain_enabled'        );
	register_setting( 'tms-settings-group-bouncer'          , 'tms_settings_bouncer_new_users_restrict_domain_list'           );
	register_setting( 'tms-settings-group-bouncer'          , 'tms_settings_bouncer_new_users_restrict_domain_text_bounce'    );
	register_setting( 'tms-settings-group-bouncer'          , 'tms_settings_bouncer_new_users_restrict_email_enabled'         );
	register_setting( 'tms-settings-group-bouncer'          , 'tms_settings_bouncer_new_users_restrict_email_list'            );
	register_setting( 'tms-settings-group-bouncer'          , 'tms_settings_bouncer_new_users_restrict_email_text_bounce'     );
	register_setting( 'tms-settings-group-bouncer'          , 'tms_settings_bouncer_new_users_restrict_profile_enabled'       );
	register_setting( 'tms-settings-group-bouncer'          , 'tms_settings_bouncer_new_users_restrict_profile_list'          );
	register_setting( 'tms-settings-group-bouncer'          , 'tms_settings_bouncer_new_users_restrict_profile_text_bounce'   );

	register_setting( 'tms-settings-group-buddypress'       , 'tms_settings_buddypress_enable_mapping' ); 
	register_setting( 'tms-settings-group-buddypress'       , 'tms_settings_buddypress_xprofile_map' ); 

	register_setting( 'tms-settings-group-debug'            , 'tms_settings_debug_mode_enabled' ); 
	register_setting( 'tms-settings-group-development'      , 'tms_settings_development_mode_enabled' ); 
}

// --------------------------------------------------------------------
