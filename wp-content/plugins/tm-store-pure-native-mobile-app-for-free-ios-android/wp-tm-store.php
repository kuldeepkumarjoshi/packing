<?php
/*
Plugin Name: TMStore - WooCommerce Native Mobile App
Plugin URI: http://thetmstore.com/
Description: Plugin that converts your WooCommerce Store into a Pure Native Mobile App for FREE on Android and iOS Platforms.
Version: 1.0.2
Author: TM Store
Author URI: http://thetmstore.com/
License: MIT License
Text Domain: wordpress-tm-store
Domain Path: /languages
*/

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

session_id() or session_start();

global $WORDPRESS_TM_STORE_VERSION;
global $WORDPRESS_TM_STORE_PROVIDERS_CONFIG;
global $WORDPRESS_TM_STORE_COMPONENTS;
global $WORDPRESS_TM_STORE_ADMIN_TABS;

$WORDPRESS_TM_STORE_VERSION = "1.0.2";

$_SESSION["tms::plugin"] = "TM Store " . $WORDPRESS_TM_STORE_VERSION;

// --------------------------------------------------------------------

/**
* This file might be used to :
*     1. Redefine TMS constants, so you can move TMS folder around.
*     2. Define TMS Pluggable PHP Functions. See http://thetmstore.com/developer-api-functions.html
*     5. Implement your TMS hooks.
*/
if( file_exists( WP_PLUGIN_DIR . '/wp-tm-store-custom.php' ) )
{
	include_once( WP_PLUGIN_DIR . '/wp-tm-store-custom.php' );
}

// --------------------------------------------------------------------

/**
* Define TMS constants, if not already defined
*/
defined( 'WORDPRESS_TM_STORE_ABS_PATH' )
	|| define( 'WORDPRESS_TM_STORE_ABS_PATH', plugin_dir_path( __FILE__ ) );

defined( 'WORDPRESS_TM_STORE_PLUGIN_URL' )
	|| define( 'WORDPRESS_TM_STORE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

defined( 'WORDPRESS_TM_STORE_HYBRIDAUTH_ENDPOINT_URL' )
	|| define( 'WORDPRESS_TM_STORE_HYBRIDAUTH_ENDPOINT_URL', WORDPRESS_TM_STORE_PLUGIN_URL . 'hybridauth/' );

// --------------------------------------------------------------------


require_once( WORDPRESS_TM_STORE_ABS_PATH . 'class-wp-tm-store.php' );
// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'tms_main_class', 'tms_plugin_activate' ) );
//Code For Deactivation 
register_deactivation_hook( __FILE__, array( 'tms_main_class', 'tms_plugin_deactivate_plugin' ) );
tms_main_class::get_instance();



//added for redirect after plugin activation.
register_activation_hook(__FILE__, 'my_plugin_activate');
add_action('admin_init', 'my_plugin_redirect');

function my_plugin_activate() {
    add_option('my_plugin_do_activation_redirect', true);
}
// Solution 1
function my_plugin_redirect() {
    if (get_option('my_plugin_do_activation_redirect', false)) {
        delete_option('my_plugin_do_activation_redirect');
        wp_redirect('admin.php?page=wordpress-tm-store');
			exit;
         //wp_redirect() does not exit automatically and should almost always be followed by exit.
        // exit;
    }
}

/**
* Check for Wordpress 3.0
*/
function tms_activate()
{
	if( ! function_exists( 'register_post_status' ) )
	{
		deactivate_plugins( basename( dirname( __FILE__ ) ) . '/' . basename (__FILE__) );

		wp_die( __( "This plugin requires WordPress 3.0 or newer. Please update your WordPress installation to activate this plugin.", 'wordpress-tm-store' ) );
	}
}

register_activation_hook( __FILE__, 'tms_activate' );

// --------------------------------------------------------------------

/**
* Attempt to install/migrate/repair TMS upon activation
*
* Create tms tables
* Migrate old versions
* Register default components
*/
function tms_install()
{
	tms_database_install();

	tms_update_compatibilities();

	tms_register_components();
}

register_activation_hook( __FILE__, 'tms_install' );

// --------------------------------------------------------------------

/**
* Add a settings to plugin_action_links
*/
function tms_add_plugin_action_links( $links, $file )
{
	static $this_plugin;

	if( ! $this_plugin )
	{
		$this_plugin = plugin_basename( __FILE__ );
	}

	if( $file == $this_plugin )
	{
		$tms_links  = '<a href="admin.php?page=wordpress-tm-store">' . __( "Settings" ) . '</a>';

		array_unshift( $links, $tms_links );
	}

	return $links;
}

add_filter( 'plugin_action_links', 'tms_add_plugin_action_links', 10, 2 );

// --------------------------------------------------------------------

/**
* Add faq and user guide links to plugin_row_meta
*/
function tms_add_plugin_row_meta( $links, $file )
{
	static $this_plugin;

	if( ! $this_plugin )
	{
		$this_plugin = plugin_basename( __FILE__ );
	}
	
	if( $file == $this_plugin )
	{
		
		$links[2] = '<a href="http://thetmstore.com/">'._tms__( "View details" , 'wordpress-tm-store' ).'</a>';
	}

	return $links;
}

add_filter( 'plugin_row_meta', 'tms_add_plugin_row_meta', 10, 2 );

// --------------------------------------------------------------------

/**
* Loads the plugin's translated strings.
*
* http://codex.wordpress.org/Function_Reference/load_plugin_textdomain
*/
if( ! function_exists( 'tms_load_plugin_textdomain' ) )
{
	function tms_load_plugin_textdomain()
	{
		load_plugin_textdomain( 'wordpress-tm-store', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

add_action( 'plugins_loaded', 'tms_load_plugin_textdomain' );

// --------------------------------------------------------------------

/**
* _e() wrapper
*/
function _tms_e( $text, $domain )
{
	echo __( $text, $domain );
}

// --------------------------------------------------------------------

/**
* __() wrapper
*/
function _tms__( $text, $domain )
{
	return __( $text, $domain );
}

// --------------------------------------------------------------------

/* includes */

# TMS Setup & Settings
require_once( WORDPRESS_TM_STORE_ABS_PATH . 'includes/settings/tms.providers.php'            ); // List of supported providers (mostly provided by hybridauth library)
require_once( WORDPRESS_TM_STORE_ABS_PATH . 'includes/settings/tms.database.php'             ); // Install/Uninstall TMS database tables
require_once( WORDPRESS_TM_STORE_ABS_PATH . 'includes/settings/tms.initialization.php'       ); // Check TMS requirements and register TMS settings
require_once( WORDPRESS_TM_STORE_ABS_PATH . 'includes/settings/tms.compatibilities.php'      ); // Check and upgrade TMS database/settings (for older versions)

# Services & Utilities
require_once( WORDPRESS_TM_STORE_ABS_PATH . 'includes/services/tms.authentication.php'       ); // Authenticate users via social networks. <- that's the most important script
require_once( WORDPRESS_TM_STORE_ABS_PATH . 'includes/services/tms.mail.notification.php'    ); // Emails and notifications
require_once( WORDPRESS_TM_STORE_ABS_PATH . 'includes/services/tms.user.avatar.php'          ); // Display users avatar
require_once( WORDPRESS_TM_STORE_ABS_PATH . 'includes/services/tms.user.data.php'            ); // User data functions (database related)
require_once( WORDPRESS_TM_STORE_ABS_PATH . 'includes/services/tms.utilities.php'            ); // Unclassified functions & utilities
require_once( WORDPRESS_TM_STORE_ABS_PATH . 'includes/services/tms.watchdog.php'             ); // TMS logging agent

# TMS Widgets & Front-end interfaces
require_once( WORDPRESS_TM_STORE_ABS_PATH . 'includes/widgets/tms.auth.widgets.php'          ); // Authentication widget generators (where TMS widget/icons are displayed)
require_once( WORDPRESS_TM_STORE_ABS_PATH . 'includes/widgets/tms.users.gateway.php'         ); // Accounts linking + Profile Completion
require_once( WORDPRESS_TM_STORE_ABS_PATH . 'includes/widgets/tms.error.pages.php'           ); // Generate TMS notices end errors pages
require_once( WORDPRESS_TM_STORE_ABS_PATH . 'includes/widgets/tms.loading.screens.php'       ); // Generate TMS loading screens

# TMS Admin interfaces
if( is_admin() && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) )
{
	require_once( WORDPRESS_TM_STORE_ABS_PATH . 'includes/admin/tms.admin.ui.php'        ); // The entry point to TMS Admin interfaces
}

// --------------------------------------------------------------------
