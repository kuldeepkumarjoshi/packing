<?php
/*!
* WordPress TM Store
*

*/

/**
* Authentication widgets generator
*
* widget.html
* themes.html
* developer-api-widget.html
*/

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Generate the HTML content of TMS Widget
*
* Note:
*   TMS shortcode arguments are still experimental and might change in future versions.
*
*   [wordpress_tm_login
*        auth_mode="login"
*        caption="Connect with"
*        enable_providers="facebook|google"
*        restrict_content="tms_user_logged_in"
*        assets_base_url="http://example.com/wp-content/uploads/2022/01/"
*   ]
*
*   Overall, TMS widget work with these simple rules :
*      1. Shortcode arguments rule over the defaults
*      2. Filters hooks rule over shortcode arguments
*      3. Bouncer rules over everything
*/
function tms_render_auth_widget( $args = array() )
{
	$auth_mode = isset( $args['mode'] ) && $args['mode'] ? $args['mode'] : 'login';

	// validate auth-mode
	if( ! in_array( $auth_mode, array( 'login', 'link', 'test' ) ) )
	{
		return;
	}

	// auth-mode eq 'login' => display tms widget only for NON logged in users
	// > this is the default mode of tms widget.
	if( $auth_mode == 'login' && is_user_logged_in() )
	{
		return;
	}

	// auth-mode eq 'link' => display tms widget only for LOGGED IN users
	// > this will allows users to manually link other social network accounts to their WordPress account
	if( $auth_mode == 'link' && ! is_user_logged_in() )
	{
		return;
	}

	// auth-mode eq 'test' => display tms widget only for LOGGED IN users only on dashboard
	// > used in Authentication Playground on TMS admin dashboard
	if( $auth_mode == 'test' && ! is_user_logged_in() && ! is_admin() )
	{
		return;
	}

	// Bouncer :: Allow authentication?
	if( get_option( 'tms_settings_bouncer_authentication_enabled' ) == 2 )
	{
		return;
	}

	// HOOKABLE: This action runs just before generating the TMS Widget.
	do_action( 'tms_render_auth_widget_start' );

	GLOBAL $WORDPRESS_TM_STORE_PROVIDERS_CONFIG;

	ob_start();

	// Icon set. If eq 'none', we show text instead
	$social_icon_set = get_option( 'tms_settings_social_icon_set' );

	// wpzoom icons set, is shown by default
	if( empty( $social_icon_set ) )
	{
		$social_icon_set = "wpzoom/";
	}

	$assets_base_url  = WORDPRESS_TM_STORE_PLUGIN_URL . 'assets/img/32x32/' . $social_icon_set . '/';

	$assets_base_url  = isset( $args['assets_base_url'] ) && $args['assets_base_url'] ? $args['assets_base_url'] : $assets_base_url;

	// HOOKABLE:
	$assets_base_url = apply_filters( 'tms_render_auth_widget_alter_assets_base_url', $assets_base_url );

	// get the current page url, which we will use to redirect the user to,
	// unless Widget::Force redirection is set to 'yes', then this will be ignored and Widget::Redirect URL will be used instead
	$redirect_to = tms_get_current_url();

	// Use the provided redirect_to if it is given and this is the login page.
	if ( in_array( $GLOBALS["pagenow"], array( "wp-login.php", "wp-register.php" ) ) && !empty( $_REQUEST["redirect_to"] ) )
	{
		$redirect_to = $_REQUEST["redirect_to"];
	}

	// build the authentication url which will call for tms_process_login() : action=wordpress_tm_authenticate
	$authenticate_base_url = site_url( 'wp-login.php', 'login_post' ) 
                                        . ( strpos( site_url( 'wp-login.php', 'login_post' ), '?' ) ? '&' : '?' ) 
                                                . "action=wordpress_tm_authenticate&mode=login&";

	// if not in mode login, we overwrite the auth base url
	// > admin auth playground
	if( $auth_mode == 'test' )
	{
		$authenticate_base_url = home_url() . "/?action=wordpress_tm_authenticate&mode=test&";
	}

	// > account linking
	elseif( $auth_mode == 'link' )
	{
		$authenticate_base_url = home_url() . "/?action=wordpress_tm_authenticate&mode=link&";
	}

	// Connect with caption
	$connect_with_label = _tms__( get_option( 'tms_settings_connect_with_label' ), 'wordpress-tm-store' );

	$connect_with_label = isset( $args['caption'] ) ? $args['caption'] : $connect_with_label;

	// HOOKABLE:
	$connect_with_label = apply_filters( 'tms_render_auth_widget_alter_connect_with_label', $connect_with_label );
?>

<!--
	tms_render_auth_widget
	TM Store <?php echo tms_get_version(); ?>.
	http://wordpress.org/plugins/wordpress-tm-store/
-->
<?php
	// Widget::Custom CSS
	$widget_css = get_option( 'tms_settings_authentication_widget_css' );

	// HOOKABLE:
	$widget_css = apply_filters( 'tms_render_auth_widget_alter_widget_css', $widget_css, $redirect_to );

	// show the custom widget css if not empty
	if( ! empty( $widget_css ) )
	{
?>

<style type="text/css">
<?php
	echo
		preg_replace(
			array( '%/\*(?:(?!\*/).)*\*/%s', '/\s{2,}/', "/\s*([;{}])[\r\n\t\s]/", '/\\s*;\\s*/', '/\\s*{\\s*/', '/;?\\s*}\\s*/' ),
				array( '', ' ', '$1', ';', '{', '}' ),
					$widget_css );
?>
</style>
<?php
	}
?>

<div class="wp-social-login-widget">

	<div class="wp-social-login-connect-with"><?php echo $connect_with_label; ?></div>

	<div class="wp-tm-login-provider-list">
<?php
	// Widget::Authentication display
	$tms_settings_use_popup = get_option( 'tms_settings_use_popup' );

	// if a user is visiting using a mobile device, TMS will fall back to more in page
	$tms_settings_use_popup = function_exists( 'wp_is_mobile' ) ? wp_is_mobile() ? 2 : $tms_settings_use_popup : $tms_settings_use_popup;

	$no_idp_used = true;

	// display provider icons
	foreach( $WORDPRESS_TM_STORE_PROVIDERS_CONFIG AS $item )
	{
		$provider_id    = isset( $item["provider_id"]    ) ? $item["provider_id"]   : '' ;
		$provider_name  = isset( $item["provider_name"]  ) ? $item["provider_name"] : '' ;

		// provider enabled?
		if( get_option( 'tms_settings_' . $provider_id . '_enabled' ) )
		{
			// restrict the enabled providers list
			if( isset( $args['enable_providers'] ) )
			{
				$enable_providers = explode( '|', $args['enable_providers'] ); // might add a couple of pico seconds

				if( ! in_array( strtolower( $provider_id ), $enable_providers ) )
				{
					continue;
				}
			}

			// build authentication url
			$authenticate_url = $authenticate_base_url . "provider=" . $provider_id . "&redirect_to=" . urlencode( $redirect_to );

			// http://codex.wordpress.org/Function_Reference/esc_url
			$authenticate_url = esc_url( $authenticate_url );

			// in case, Widget::Authentication display is set to 'popup', then we overwrite 'authenticate_url'
			// > /assets/js/connect.js will take care of the rest
			if( $tms_settings_use_popup == 1 &&  $auth_mode != 'test' )
			{
				$authenticate_url= "javascript:void(0);";
			}

			// HOOKABLE: allow user to rebuilt the auth url
			$authenticate_url = apply_filters( 'tms_render_auth_widget_alter_authenticate_url', $authenticate_url, $provider_id, $auth_mode, $redirect_to, $tms_settings_use_popup );

			// HOOKABLE: allow use of other icon sets
			$provider_icon_markup = apply_filters( 'tms_render_auth_widget_alter_provider_icon_markup', $provider_id, $provider_name, $authenticate_url );

			if( $provider_icon_markup != $provider_id )
			{
				echo $provider_icon_markup;
			}
			else
			{
?>

		<a rel="nofollow" href="<?php echo $authenticate_url; ?>" title="<?php echo sprintf( _tms__("Connect with %s", 'wordpress-tm-store'), $provider_name ) ?>" class="wp-tm-login-provider wp-tm-login-provider-<?php echo strtolower( $provider_id ); ?>" data-provider="<?php echo $provider_id ?>">
			<?php if( $social_icon_set == 'none' ){ echo apply_filters( 'tms_render_auth_widget_alter_provider_name', $provider_name ); } else { ?><img alt="<?php echo $provider_name ?>" title="<?php echo sprintf( _tms__("Connect with %s", 'wordpress-tm-store'), $provider_name ) ?>" src="<?php echo $assets_base_url . strtolower( $provider_id ) . '.png' ?>" /><?php } ?>

		</a>
<?php
			}

			$no_idp_used = false;
		}
	}

	// no provider enabled?
	if( $no_idp_used )
	{
?>
		<p style="background-color: #FFFFE0;border:1px solid #E6DB55;padding:5px;">
			<?php _tms_e( '<strong>TM Store is not configured yet</strong>.<br />Please navigate to <strong>TM Store</strong> to configure this plugin.', 'wordpress-tm-store') ?>.
		</p>
		<style>#wp-social-login-connect-with{display:none;}</style>
<?php
	}
?>

	</div>

	<div class="wp-social-login-widget-clearing"></div>

</div>

<?php
	// provide popup url for hybridauth callback
	if( $tms_settings_use_popup == 1 )
	{
?>
<input type="hidden" id="tms_popup_base_url" value="<?php echo esc_url( $authenticate_base_url ) ?>" />
<input type="hidden" id="tms_login_form_uri" value="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" />

<?php
	}

	// HOOKABLE: This action runs just after generating the TMS Widget.
	do_action( 'tms_render_auth_widget_end' );
?>
<!-- tms_render_auth_widget -->

<?php
	// Display TMS debugging area bellow the widget.
	// tms_display_dev_mode_debugging_area(); // ! keep this line commented unless you know what you are doing :)

	return ob_get_clean();
}

// --------------------------------------------------------------------

/**
* TMS wordpress_tm_login action
*
* Ref: http://codex.wordpress.org/Function_Reference/add_action
*/
function tms_action_wordpress_tm_login( $args = array() )
{
	echo tms_render_auth_widget( $args );
}

add_action( 'wordpress_tm_login', 'tms_action_wordpress_tm_login' );

// --------------------------------------------------------------------

/**
* TMS wordpress_tm_login shortcode
*
* Note:
*   TMS shortcode arguments are still experimental and might change in future versions.
*
* Ref: http://codex.wordpress.org/Function_Reference/add_shortcode
*/
function tms_shortcode_wordpress_tm_login( $args = array(), $content = null )
{
	$restrict_content = isset( $args['restrict_content'] ) && $args['restrict_content'] ? true : false;

	if( 'wp_user_logged_in' == $restrict_content && is_user_logged_in() )
	{
		return do_shortcode( $content );
	}

	if( 'tms_user_logged_in' == $restrict_content && tms_get_stored_hybridauth_user_profiles_by_user_id( get_current_user_id() ) )
	{
		return do_shortcode( $content );
	}

	return tms_render_auth_widget( $args );
}

add_shortcode( 'wordpress_tm_login', 'tms_shortcode_wordpress_tm_login' );

// --------------------------------------------------------------------

/**
* TMS wordpress_tm_login_meta shortcode
*
* Note:
*   This shortcode is experimental and might change in future versions.
*
*   [wordpress_tm_login_meta
*        user_id="215"
*        meta="tms_current_user_image"
*        display="html"
*        css_class="my_style_is_better"
*   ]
*/
function tms_shortcode_wordpress_tm_login_meta( $args = array() )
{
	// wordpress user id default to current user connected
	$user_id = isset( $args['user_id'] ) && $args['user_id'] ? $args['user_id'] : get_current_user_id();

	// display default to plain text
	$display = isset( $args['display'] ) && $args['display'] ? $args['display'] : 'plain';

	// when display is set to html, css_class will be used for the main dom el
	$css_class = isset( $args['css_class'] ) && $args['css_class'] ? $args['css_class'] : '';

	// tms user meta to display
	$meta = isset( $args['meta'] ) && $args['meta'] ? $args['meta'] : null;

	if( ! is_numeric( $user_id ) )
	{
		return;
	}

	if( ! $meta )
	{
		return;
	}

	$assets_base_url  = WORDPRESS_TM_STORE_PLUGIN_URL . 'assets/img/16x16/';

	$assets_base_url  = isset( $args['assets_base_url'] ) && $args['assets_base_url'] ? $args['assets_base_url'] : $assets_base_url;

	$return = '';

	if( 'current_avatar' == $meta )
	{
		if( 'plain' == $display )
		{
			$return = tms_get_user_custom_avatar( $user_id );
		}
		else
		{
			$return = '<img class="wordpress_tm_login_meta_user_avatar ' . $css_class . '" src="' . tms_get_user_custom_avatar( $user_id ) . '" />';
		}
	}

	if( 'current_provider' == $meta )
	{
		$provider = get_user_meta( $user_id, 'tms_current_provider', true );

		if( 'plain' == $display )
		{
			$return = $provider;
		}
		else
		{
			$return = '<img class="wordpress_tm_login_meta_user_provider ' . $css_class . '" src="' . $assets_base_url . strtolower( $provider ) . '.png"> ' . $provider;
		}
	}

	if( 'user_identities' == $meta )
	{
		ob_start();

		$linked_accounts = tms_get_stored_hybridauth_user_profiles_by_user_id( $user_id );

		if( $linked_accounts )
		{
			?><table class="wp-social-login-linked-accounts-list <?php echo $css_class; ?>"><?php

			foreach( $linked_accounts AS $item )
			{
				$identity = $item->profileurl;
				$photourl = $item->photourl;

				if( ! $identity )
				{
					$identity = $item->identifier;
				}

				?><tr><td><?php if( $photourl ) { ?><img  style="vertical-align: top;width:16px;height:16px;" src="<?php echo $photourl ?>"> <?php } else { ?><img src="<?php echo $assets_base_url . strtolower(  $item->provider ) . '.png' ?>" /> <?php } ?><?php echo ucfirst( $item->provider ); ?> </td><td><?php echo $identity; ?></td></tr><?php

				echo "\n";
			}

			?></table><?php
		}

		$return = ob_get_clean();

		if( 'plain' == $display )
		{
			$return = strip_tags( $return );
		}
	}

	return $return;
}

add_shortcode( 'wordpress_tm_login_meta', 'tms_shortcode_wordpress_tm_login_meta' );

// --------------------------------------------------------------------

/**
* Display on comment area
*/
function tms_render_auth_widget_in_comment_form()
{
	$tms_settings_widget_display = get_option( 'tms_settings_widget_display' );

	if( comments_open() )
	{
		if(
			!  $tms_settings_widget_display
		||
			$tms_settings_widget_display == 1
		||
			$tms_settings_widget_display == 2
		)
		{
			echo tms_render_auth_widget();
		}
	}
}

add_action( 'comment_form_top'              , 'tms_render_auth_widget_in_comment_form' );
add_action( 'comment_form_must_log_in_after', 'tms_render_auth_widget_in_comment_form' );

// --------------------------------------------------------------------

/**
* Display on login form
*/
function tms_render_auth_widget_in_wp_login_form()
{
	$tms_settings_widget_display = get_option( 'tms_settings_widget_display' );

	if( $tms_settings_widget_display == 1 || $tms_settings_widget_display == 3 )
	{
		echo tms_render_auth_widget();
	}
}

add_action( 'login_form'                      , 'tms_render_auth_widget_in_wp_login_form' );
add_action( 'bp_before_account_details_fields', 'tms_render_auth_widget_in_wp_login_form' );
add_action( 'bp_before_sidebar_login_form'    , 'tms_render_auth_widget_in_wp_login_form' );

// --------------------------------------------------------------------

/**
* Display on login & register form
*/
function tms_render_auth_widget_in_wp_register_form()
{
	$tms_settings_widget_display = get_option( 'tms_settings_widget_display' );

	if( $tms_settings_widget_display == 1 || $tms_settings_widget_display == 3 )
	{
		echo tms_render_auth_widget();
	}
}

add_action( 'register_form'    , 'tms_render_auth_widget_in_wp_register_form' );
add_action( 'after_signup_form', 'tms_render_auth_widget_in_wp_register_form' );

// --------------------------------------------------------------------

/**
* Enqueue TMS CSS file
*/
function tms_add_stylesheets()
{
	if( ! wp_style_is( 'tms-widget', 'registered' ) )
	{
		wp_register_style( "tms-widget", WORDPRESS_TM_STORE_PLUGIN_URL . "assets/css/style.css" );
	}

	wp_enqueue_style( "tms-widget" );
}

add_action( 'wp_enqueue_scripts'   , 'tms_add_stylesheets' );
add_action( 'login_enqueue_scripts', 'tms_add_stylesheets' );

// --------------------------------------------------------------------

/**
* Enqueue TMS Javascript, only if we use popup
*/
function tms_add_javascripts()
{
	if( get_option( 'tms_settings_use_popup' ) != 1 )
	{
		return null;
	}

	if( ! wp_script_is( 'tms-widget', 'registered' ) )
	{
		wp_register_script( "tms-widget", WORDPRESS_TM_STORE_PLUGIN_URL . "assets/js/widget.js" );
	}

	wp_enqueue_script( "jquery" );
	wp_enqueue_script( "tms-widget" );
}

add_action( 'wp_enqueue_scripts'   , 'tms_add_javascripts' );
add_action( 'login_enqueue_scripts', 'tms_add_javascripts' );

// --------------------------------------------------------------------