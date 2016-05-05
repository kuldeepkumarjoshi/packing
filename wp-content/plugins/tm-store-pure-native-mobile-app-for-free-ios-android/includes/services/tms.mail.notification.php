<?php
/*!
* WordPress TM Store
*

*/

/** 
* Email notifications to send. so far only the admin one is implemented
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Send a notification to blog administrator when a new user register using TMS 
*
* also borrowed from http://wordpress.org/extend/plugins/oa-social-login/
* 
* Note: 
*   You may redefine this function
*/
if( ! function_exists( 'tms_admin_notification' ) )
{
	function tms_admin_notification( $user_id, $provider )
	{
		//Get the user details
		$user = new WP_User($user_id);
		$user_login = stripslashes( $user->user_login );

		// The blogname option is escaped with esc_html on the way into the database
		// in sanitize_option we want to reverse this for the plain text arena of emails.
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

		$message  = sprintf(__('New user registration on your site: %s', 'wordpress-tm-store'), $blogname        ) . "\r\n\r\n";
		$message .= sprintf(__('Username: %s'                          , 'wordpress-tm-store'), $user_login      ) . "\r\n";
		$message .= sprintf(__('Provider: %s'                          , 'wordpress-tm-store'), $provider        ) . "\r\n";
		$message .= sprintf(__('Profile: %s'                           , 'wordpress-tm-store'), $user->user_url  ) . "\r\n";
		$message .= sprintf(__('Email: %s'                             , 'wordpress-tm-store'), $user->user_email) . "\r\n";
		$message .= "\r\n--\r\n";
		$message .= "WordPress TM Store\r\n";
		$message .= "http://wordpress.org/extend/plugins/wordpress-tm-store/\r\n";

		@ wp_mail(get_option('admin_email'), '[WordPress TM Store] '.sprintf(__('[%s] New User Registration', 'wordpress-tm-store'), $blogname), $message);
	}
}

// --------------------------------------------------------------------
