<?php
/*!
* WordPress TM Store
*

*/

/**
* Authentication Playground
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*!
	Important

	Direct access to providers apis is newly introduced into TMS and we are still experimenting, so they may change in future releases.
*/

// --------------------------------------------------------------------

function tms_component_authtest()
{
	// HOOKABLE:
	do_action( "tms_component_authtest_start" );

	$adapter      = null;
	$provider_id  = isset( $_REQUEST["provider"] ) ? $_REQUEST["provider"] : null;
	$user_profile = null;

	$assets_base_url = WORDPRESS_TM_STORE_PLUGIN_URL . 'assets/img/';

	if( ! class_exists( 'Hybrid_Auth', false ) )
	{
		require_once WORDPRESS_TM_STORE_ABS_PATH . "hybridauth/Hybrid/Auth.php";
	}

	try
	{
		$provider = Hybrid_Auth::getAdapter( $provider_id );

		// make as few call as possible
		if( ! ( isset( $_SESSION['tms::userprofile'] ) && $_SESSION['tms::userprofile'] && $user_profile = json_decode( $_SESSION['tms::userprofile'] ) ) )
		{
			$user_profile = $provider->getUserProfile();

			$_SESSION['tms::userprofile'] = json_encode( $user_profile );
		}

		$adapter = $provider->adapter;
	}
	catch( Exception $e )
	{
	}

	$ha_profile_fields = array(
		array( 'field' => 'identifier'  , 'label' => _tms__( "Provider user ID" , 'wordpress-tm-store') ),
		array( 'field' => 'profileURL'  , 'label' => _tms__( "Profile URL"      , 'wordpress-tm-store') ),
		array( 'field' => 'webSiteURL'  , 'label' => _tms__( "Website URL"      , 'wordpress-tm-store') ),
		array( 'field' => 'photoURL'    , 'label' => _tms__( "Photo URL"        , 'wordpress-tm-store') ),
		array( 'field' => 'displayName' , 'label' => _tms__( "Display name"     , 'wordpress-tm-store') ),
		array( 'field' => 'description' , 'label' => _tms__( "Description"      , 'wordpress-tm-store') ),
		array( 'field' => 'firstName'   , 'label' => _tms__( "First name"       , 'wordpress-tm-store') ),
		array( 'field' => 'lastName'    , 'label' => _tms__( "Last name"        , 'wordpress-tm-store') ),
		array( 'field' => 'gender'      , 'label' => _tms__( "Gender"           , 'wordpress-tm-store') ),
		array( 'field' => 'language'    , 'label' => _tms__( "Language"         , 'wordpress-tm-store') ),
		array( 'field' => 'age'         , 'label' => _tms__( "Age"              , 'wordpress-tm-store') ),
		array( 'field' => 'birthDay'    , 'label' => _tms__( "Birth day"        , 'wordpress-tm-store') ),
		array( 'field' => 'birthMonth'  , 'label' => _tms__( "Birth month"      , 'wordpress-tm-store') ),
		array( 'field' => 'birthYear'   , 'label' => _tms__( "Birth year"       , 'wordpress-tm-store') ),
		array( 'field' => 'email'       , 'label' => _tms__( "Email"            , 'wordpress-tm-store') ),
		array( 'field' => 'phone'       , 'label' => _tms__( "Phone"            , 'wordpress-tm-store') ),
		array( 'field' => 'address'     , 'label' => _tms__( "Address"          , 'wordpress-tm-store') ),
		array( 'field' => 'country'     , 'label' => _tms__( "Country"          , 'wordpress-tm-store') ),
		array( 'field' => 'region'      , 'label' => _tms__( "Region"           , 'wordpress-tm-store') ),
		array( 'field' => 'city'        , 'label' => _tms__( "City"             , 'wordpress-tm-store') ),
		array( 'field' => 'zip'         , 'label' => _tms__( "Zip"              , 'wordpress-tm-store') ),
	);
?>
<style>
	.widefat td, .widefat th { border: 1px solid #DDDDDD; }
	.widefat th label { font-weight: bold; }

	.wp-tm-store-provider-list { padding: 10px; }
	.wp-tm-store-provider-list a {text-decoration: none; }
	.wp-tm-store-provider-list img{ border: 0 none; }
</style>

<div class="metabox-holder columns-2" id="post-body">
	<table width="100%">
		<tr valign="top">
			<td>
				<?php if( ! $adapter ): ?>
					<div style="padding: 15px; margin-bottom: 8px; border: 1px solid #ddd; background-color: #fff;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
						<p><?php _tms_e("Connect with a provider to get started", 'wordpress-tm-store') ?>.</p>
					</div>
				<?php else: ?>
					<div class="stuffbox">
						<h3>
							<label><?php _tms_e("Connected adapter specs", 'wordpress-tm-store') ?></label>
						</h3>
						<div class="inside">
							<table class="wp-list-table widefat">
								<tr>
									<th width="200"><label><?php _tms_e("Provider", 'wordpress-tm-store') ?></label></th>
									<td><?php echo $adapter->providerId; ?></td>
								</tr>

								<?php if( isset( $adapter->openidIdentifier ) ): ?>
									<tr>
										<th width="200"><label><?php _tms_e("OpenID Identifier", 'wordpress-tm-store') ?></label></th>
										<td><?php echo $adapter->openidIdentifier; ?></td>
									</tr>
								<?php endif; ?>

								<?php if( isset( $adapter->scope ) ): ?>
									<tr>
										<th width="200"><label><?php _tms_e("Scope", 'wordpress-tm-store') ?></label></th>
										<td><?php echo $adapter->scope; ?></td>
									</tr>
								<?php endif; ?>

								<?php if( isset( $adapter->config['keys'] ) ): ?>
									<tr>
										<th width="200"><label><?php _tms_e("Application keys", 'wordpress-tm-store') ?></label></th>
										<td><div style="max-width:650px"><?php echo json_encode( $adapter->config['keys'] ); ?></div></td>
									</tr>
								<?php endif; ?>

								<?php if( $adapter->token("access_token") ): ?>
									<tr>
										<th width="200"><label><?php _tms_e("Access token", 'wordpress-tm-store') ?></label></th>
										<td><div style="max-width:650px"><?php echo $adapter->token("access_token"); ?></div></td>
									</tr>
								<?php endif; ?>

								<?php if( $adapter->token("access_token_secret") ): ?>
									<tr>
										<th width="200"><label><?php _tms_e("Access token secret", 'wordpress-tm-store') ?></label></th>
										<td><?php echo $adapter->token("access_token_secret"); ?></td>
									</tr>
								<?php endif; ?>

								<?php if( $adapter->token("expires_in") ): ?>
									<tr>
										<th width="200"><label><?php _tms_e("Access token expires in", 'wordpress-tm-store') ?></label></th>
										<td><?php echo (int) $adapter->token("expires_at") - time(); ?> <?php _tms_e("second(s)", 'wordpress-tm-store') ?></td>
									</tr>
								<?php endif; ?>

								<?php if( $adapter->token("expires_at") ): ?>
									<tr>
										<th width="200"><label><?php _tms_e("Access token expires at", 'wordpress-tm-store') ?></label></th>
										<td><?php echo date( DATE_W3C, $adapter->token("expires_at" ) ); ?></td>
									</tr>
								<?php endif; ?>
							</table>
						</div>
					</div>

					<?php
						$console = false;

						if( ! isset( $adapter->openidIdentifier ) ):
					?>
						<div class="stuffbox">
							<h3>
								<label><?php _tms_e("Connected adapter console", 'wordpress-tm-store') ?></label>
							</h3>
							<div class="inside">
								<?php
									$path   = isset( $adapter->api->api_base_url ) ? $adapter->api->api_base_url : '';
									$path   = isset( $_REQUEST['console-path']   ) ? $_REQUEST['console-path']   : $path;
									$method = isset( $_REQUEST['console-method'] ) ? $_REQUEST['console-method'] : '';
									$query  = isset( $_REQUEST['console-query']  ) ? $_REQUEST['console-query']  : '';

									$response = '';

									if( $path && in_array( $method, array( 'GET', 'POST' ) ) )
									{
										$console = true;

										try
										{
											if( $method == 'GET' )
											{
												$response = $adapter->api->get( $path . ( $query ? '?' . $query : '' ) );
											}
											else
											{
												$response = $adapter->api->get( $path, $query );
											}

											$response = $response ? $response : Hybrid_Error::getApiError();
										}
										catch( Exception $e )
										{
											$response = "ERROR: " . $e->getMessage();
										}
									}
								?>
								<form action="" method="post"/>
									<table class="wp-list-table widefat">
										<tr>
											<th width="200"><label><?php _tms_e("Path", 'wordpress-tm-store') ?></label></th>
											<td><input type="text" style="width:96%" name="console-path" value="<?php echo htmlentities( $path ); ?>"><a href="https://apigee.com/providers" target="_blank"><img src="<?php echo $assets_base_url . 'question.png' ?>" style="vertical-align: text-top;" /></a></td>
										</tr>
										<tr>
											<th width="200"><label><?php _tms_e("Method", 'wordpress-tm-store') ?></label></th>
											<td><select style="width:100px" name="console-method"><option value="GET" <?php if( $method == 'GET' ) echo 'selected'; ?>>GET</option><!-- <option value="POST" <?php if( $method == 'POST' ) echo 'selected'; ?>>POST</option>--></select></td>
										</tr>
										<tr>
											<th width="200"><label><?php _tms_e("Query", 'wordpress-tm-store') ?></label></th>
											<td><textarea style="width:100%;height:60px;margin-top:6px;" name="console-query"><?php echo htmlentities( $query ); ?></textarea></td>
										</tr>
									</table>

									<br />

									<input type="submit" value="<?php _tms_e("Submit", 'wordpress-tm-store') ?>" class="button">
								</form>
							</div>
						</div>

						<?php if( $console ): ?>
							<div class="stuffbox">
								<h3>
									<label><?php _tms_e("API Response", 'wordpress-tm-store') ?></label>
								</h3>
								<div class="inside">
									<textarea rows="25" cols="70" wrap="off" style="width:100%;height:400px;margin-bottom:15px;font-family: monospace;font-size: 12px;"><?php echo htmlentities( print_r( $response, true ) ); ?></textarea>
								</div>
							</div>
						<?php if( 0 ): ?>
							<div class="stuffbox">
								<h3>
									<label><?php _tms_e("Code PHP", 'wordpress-tm-store') ?></label>
								</h3>
								<div class="inside">
<textarea rows="25" cols="70" wrap="off" style="width:100%;height:210px;margin-bottom:15px;font-family: monospace;font-size: 12px;"
>include_once WORDPRESS_TM_STORE_ABS_PATH . 'hybridauth/Hybrid/Auth.php';

/*!
	Important

	Direct access to providers apis is newly introduced into TMS and we are still experimenting, so they may change in future releases.
*/

try
{
    $<?php echo strtolower( $adapter->providerId ); ?> = Hybrid_Auth::getAdapter( '<?php echo htmlentities( $provider_id ); ?>' );

<?php if( $method == 'GET' ): ?>
    $response = $<?php echo strtolower( $adapter->providerId ); ?>->api()->get( '<?php echo htmlentities( $path . ( $query ? '?' . $query : '' ) ); ?>' );
<?php else: ?>
    $response = $<?php echo strtolower( $adapter->providerId ); ?>->api()->post( '<?php echo htmlentities( $path ); ?>', (array) $query );
<?php endif; ?>
}
catch( Exception $e )
{
    echo "Ooophs, we got an error: " . $e->getMessage();
}</textarea>
								</div>
							</div>
							<div class="stuffbox">
								<h3>
									<label><?php _tms_e("Connected adapter debug", 'wordpress-tm-store') ?></label>
								</h3>
								<div class="inside">
									<textarea rows="25" cols="70" wrap="off" style="width:100%;height:400px;margin-bottom:15px;font-family: monospace;font-size: 12px;"><?php echo htmlentities( print_r( $adapter, true ) ); ?></textarea>
								</div>
							</div>
							<div class="stuffbox">
								<h3>
									<label><?php _tms_e("PHP Session", 'wordpress-tm-store') ?></label>
								</h3>
								<div class="inside">
									<textarea rows="25" cols="70" wrap="off" style="width:100%;height:350px;margin-bottom:15px;font-family: monospace;font-size: 12px;"><?php echo htmlentities( print_r( $_SESSION, true ) ); ?></textarea>
								</div>
							</div>
						<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>

					<?php if( ! $console ): ?>
						<div class="stuffbox">
							<h3>
								<label><?php _tms_e("Connected user social profile", 'wordpress-tm-store') ?></label>
							</h3>
							<div class="inside">
								<table class="wp-list-table widefat">
									<?php
										$user_profile = (array) $user_profile;

										foreach( $ha_profile_fields as $item )
										{
											$item['field'] = $item['field'];
										?>
											<tr>
												<th width="200">
													<label><?php echo $item['label']; ?></label>
												</th>
												<td>
													<?php
														if( isset( $user_profile[ $item['field'] ] ) && $user_profile[ $item['field'] ] )
														{
															$field_value = $user_profile[ $item['field'] ];

															if( in_array( strtolower( $item['field'] ), array( 'profileurl', 'websiteurl', 'email' ) ) )
															{
																?>
																	<a href="<?php if( $item['field'] == 'email' ) echo 'mailto:'; echo $field_value; ?>" target="_blank"><?php echo $field_value; ?></a>
																<?php
															}
															elseif( strtolower( $item['field'] ) == 'photourl' )
															{
																?>
																	<a href="<?php echo $field_value; ?>" target="_blank"><img width="36" height="36" align="left" src="<?php echo $field_value; ?>" style="margin-right: 5px;" > <?php echo $field_value; ?></a>
																<?php
															}
															else
															{
																echo $field_value;
															}
														}
													?>
												</td>
											</tr>
										<?php
										}
									?>
								</table>
							</div>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</td>
			<td width="10"></td>
			<td width="400">
				<div class="postbox">
					<div class="inside">
						<h3><?php _tms_e("Authentication Playground", 'wordpress-tm-store') ?></h3>

						<div style="padding:0 20px;">
							<p>
								<?php _tms_e('Authentication Playground will let you authenticate with the enabled social networks without creating any new user account', 'wordpress-tm-store') ?>.
							</p>
							<p>
								<?php _tms_e('This tool will also give you a direct access to social networks apis via a lightweight console', 'wordpress-tm-store') ?>.
							</p>
						</div>
					</div>
				</div>

				</style>
				<div class="postbox">
					<div class="inside">
						<div style="padding:0 20px;">
							<p>
								<?php _tms_e("Connect with", 'wordpress-tm-store') ?>:
							</p>

							<div style="width: 380px; padding: 10px; border: 1px solid #ddd; background-color: #fff;">
								<?php do_action( 'wordpress_tm_login', array( 'mode' => 'test', 'caption' => '' ) ); ?>
							</div>
						</div>
					</div>
				</div>
			</td>
		</tr>
	</table>
</div>
<?php
	// HOOKABLE:
	do_action( "tms_component_authtest_end" );
}

tms_component_authtest();

// --------------------------------------------------------------------
