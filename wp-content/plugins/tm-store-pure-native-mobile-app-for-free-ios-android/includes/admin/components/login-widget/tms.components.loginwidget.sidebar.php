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

function tms_component_loginwidget_sidebar()
{
	$sections = array(
		'what_is_this'        => 'tms_component_loginwidget_sidebar_what_is_this',
		'auth_widget_preview' => 'tms_component_loginwidget_sidebar_auth_widget_preview',
		'custom_integration'  => 'tms_component_loginwidget_sidebar_custom_integration',
	);

	$sections = apply_filters( 'tms_component_loginwidget_sidebar_alter_sections', $sections );

	foreach( $sections as $section => $action )
	{
		add_action( 'tms_component_loginwidget_sidebar_sections', $action );
	}

	// HOOKABLE: 
	do_action( 'tms_component_loginwidget_sidebar_sections' );
}

// --------------------------------------------------------------------	

function tms_component_loginwidget_sidebar_what_is_this()
{
	//
}

// --------------------------------------------------------------------	

function tms_component_loginwidget_sidebar_auth_widget_preview()
{
?>
<?php /*?><style>
.wp-tm-store-provider-list { padding: 10px; }
.wp-tm-store-provider-list a {text-decoration: none; }
.wp-tm-store-provider-list img{ border: 0 none; }
</style>
<div class="postbox">
	<div class="inside">
		<h3><?php _tms_e("Widget preview", 'wordpress-tm-store') ?></h3>

		<div style="padding:0 20px;">
			<p>
				<?php _tms_e("This is a preview of what should be on the comments area", 'wordpress-tm-store') ?>. 
			</p>

			<div style="width: 380px; padding: 10px; border: 1px solid #ddd; background-color: #fff;">
				<?php do_action( 'wordpress_tm_login', array( 'mode' => 'test' ) ); ?> 
			</div> 
		</div>
	</div> 
</div> 	<?php */?>	
<?php
}

// --------------------------------------------------------------------	

function tms_component_loginwidget_sidebar_custom_integration()
{
?>
<div class="postbox" style="margin-bottom:20px;">
	<div class="inside">
		<h3><?php _tms_e("Custom integration", 'wordpress-tm-store') ?></h3>

		<div style="padding:0 20px;">
			<p>
				<?php _tms_e("If you want to add the widget to another location in your website, you can insert the following code in that location", 'wordpress-tm-store') ?>: 
				<pre dir="ltr" style="width: 380px;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin-top:15px;margin-left:10px;"> &lt;?php do_action( 'wordpress_tm_login' ); ?&gt; </pre> 
				<?php _tms_e("For posts and pages, you may use this shortcode", 'wordpress-tm-store') ?>:
				<div dir="ltr" style="width: 380px;border:1px solid #6B84B4; border-radius: 3px;padding: 10px;margin-top:15px;margin-left:10px;">[wordpress_tm_login]</div> 
			</p>

			<p>
				<b><?php _tms_e('Notes', 'wordpress-tm-store') ?>:</b>
				<br />
				1. <?php _tms_e('TM Store Widget will only show up for non connected users', 'wordpress-tm-store') ?>.
			</p>
		</div>
	</div> 
</div> 		
<?php
}

// --------------------------------------------------------------------	
