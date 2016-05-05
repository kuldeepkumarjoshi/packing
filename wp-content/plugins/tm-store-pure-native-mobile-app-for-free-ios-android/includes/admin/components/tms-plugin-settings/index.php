<?php
/*!
* WordPress TM Store
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function tms_component_tms_plugin_settings()
{

	tms_admin_welcome_panel();
	
	$assets_setup_base_url = WORDPRESS_TM_STORE_PLUGIN_URL . 'assets/img/';
	$options = get_option( 'tms_settings_data' );
	$options = maybe_unserialize( $options );
?>
<script>
	function toggleproviderkeys(idp)
	{
		if(typeof jQuery=="undefined")
		{
			alert( "Error: TM Store require jQuery to be installed on your wordpress in order to work!" );

			return;
		}

		if(jQuery('#tms_settings_' + idp + '_enabled').val()==1)
		{
			jQuery('.tms_tr_settings_' + idp).show();
		}
		else
		{
			jQuery('.tms_tr_settings_' + idp).hide();
			jQuery('.tms_div_settings_help_' + idp).hide();
		}

		return false;
	}

	function toggleproviderhelp(idp)
	{
		if(typeof jQuery=="undefined")
		{
			alert( "Error: TM Store require jQuery to be installed on your wordpress in order to work!" );

			return false;
		}

		jQuery('.tms_div_settings_help_' + idp).toggle();

		return false;
	}
	
	jQuery(document).ready(function($){
	
		$('#tms_setup_form').submit(function(e) {
			var data = $('#tms_setup_form').serialize();
			$.ajax({
				url         :	ajaxurl,
				data        :	data + '&action=save_tms_data',
				type        :	'POST',
				async: false,
				beforeSend: function(){
					$('#save-tms-settings').val('Sending...');
					$('#save-tms-settings').attr('disabled', 'disabled');
				},
				success     : function(data){
					$('#save-tms-settings').val('Submit');
					$('#save-tms-settings').removeAttr('disabled');
					try {
						var returned = jQuery.parseJSON(data);
						if(returned.results == 1){
							alert('Thank You! You will receive an email from TM Store soon.');
						} else {
							alert(returned.error);
						}
					} catch (e) {
						alert('Not able to send the data.');
					}
					return true;
				}
			});
		});
		
	});
	
</script>



<form method="post" id="tms_setup_form" action="">
	<div class="metabox-holder columns-2" id="post-body">
		<table width="100%">
			<tr valign="top">
				<td><div id="post-body-content">
						<div id="namediv" class="stuffbox">
							<h2 style="padding-top:25px; padding-left:30px; color:#424242">Register your website to receive Mobile App on your email address.</h2>
							<br>
								<h3 style="padding-left:25px; color:#424242">We will require details to complete process of creating Pure Native Mobile App. Please fill form and submit to receive your Mobile App. </h3>
										
						
							<div class="inside">
								<table class="form-table editcomment">
									<tbody>
										<tr class="tms_tr_settings_Facebook" style="background-color:#EEE">
											<td>Username:  <a onclick="toggleproviderhelp('Facebook')" href="#help">(?)</a></td>
											<td><input type="text" id="user-api" name="username" dir="ltr" value="<?php echo isset($options['username']) ?  esc_attr($options['username']) : ''; ?>"></td>
										    <td><p class="description" style="font-size:90%">Enter a unique username.</p></td>
										</tr>
										<tr class="tms_tr_settings_Facebook" style="background-color:#EEE">
											<td>Email:  <a onclick="toggleproviderhelp('Facebook')" href="#help">(?)</a></td>
											<td><input type="text" id="email-api" name="email-api" dir="ltr" value="<?php echo isset($options['email-api']) ?  esc_attr($options['email-api']) : ''; ?>"></td>
										     <td><p class="description" style="font-size:90%">Select your email for Registeration.</p></td>
										</tr>
										<tr class="tms_tr_settings_Facebook" style="background-color:#EEE">
											<td>Website Url:  <a onclick="toggleproviderhelp('Facebook')" href="#help">(?)</a></td>
											<td><input type="text" id="website-api" name="website-api" dir="ltr" value="<?php echo isset($options['website-api']) ? esc_url_raw($options['website-api']) : ''; ?>"></td>
										    <td><p class="description" style="font-size:90%">Enter your shop url to convert into Native Mobile App.</p></td>
										</tr>
										<tr class="tms_tr_settings_Facebook" style="background-color:#E1E1E1">
											<td>Woocommerce API Consumer Key:  <a onclick="toggleproviderhelp('Facebook')" href="#help">(?)</a></td>
											<td><input type="text" id="wc-api-key" name="wc-api-key" dir="ltr" value="<?php echo isset($options['wc-api-key']) ? esc_attr($options['wc-api-key']) : ''; ?>"></td>
											<td><p class="description" style="font-size:90%">Create a new Woocommerce API Consumer Key.</p></td>
											<td style="background-color:#FFFFFF;"><a onclick="toggleproviderhelp('Facebook')" href="#help">Where do I get this info?</a></td>
										</tr>
										<tr class="tms_tr_settings_Facebook" style="background-color:#E1E1E1">
											<td>Woocommerce API Consumer Secret:  <a onclick="toggleproviderhelp('Facebook')" href="#help">(?)</a></td>
											<td><input type="text" id="wc-api-key" name="wc-api-secret" dir="ltr" value="<?php echo isset($options['wc-api-secret']) ? esc_attr($options['wc-api-secret']) : ''; ?>"></td>
											<td><p class="description" style="font-size:90%">Create a new Woocommerce API Consumer Secret.</p></td>
											<td style="background-color:#FFFFFF;"><a onclick="toggleproviderhelp('Facebook')" href="#help">Where do I get this info?</a></td>
										</tr>
										<tr class="tms_tr_settings_Facebook">
											<input type="hidden" name="site_url" value="<?php echo get_bloginfo('url'); ?>" />
											<input type="hidden" name="reference_site_cms" value="wordpress" />
											<td><input type="submit" value="SUBMIT" name="Save" class="button-primary" id="save-tms-settings" style="width: 100px;">
											<p class="description">Click "SUBMIT" to get started with your Mobile App.</p></td>								
										</tr>
									</tbody>
								</table>
							</div>
									<div id="help">
										<h2 style="padding-left:25px; color:#424242">Follow below Steps to receive your application:</h2>
											<h4 style="padding-left:45px; color:#424242"><b>Step 1:</b> Fill form above with unique username, email and website url for which you need mobile app.</b></h4>
											<h4 style="padding-left:45px; color:#424242"><b>Step 2:</b> TM Store plugin requires WooCommerce Consumer/Secert Keys to create application.</h4>
												<p style="padding-left:92px; color:#424242">Simple steps for getting WooCommerce Consumer Key and Secert Key.</p>
												<p style="padding-left:92px; color:#424242">Step 2.1 You enable REST API access in WooCommerce Plugin.</p>
												<p style="padding-left:92px; color:#424242">Step 2.2 You create new KEY in WooCommerce and give access of read/write in it.</p>
												<p style="padding-left:92px; color:#424242">Step 2.3 Then you copy/paste API code in TM Store plugin</p>
											<h4 style="padding-left:125px; color:#424242"><b>Detailed steps to get WooCommerce "CONSUMER KEY" and "CONSUMER SECRET".</b></h4>
												<p style="padding-left:150px; color:#424242">a. Open new window on web browser of Wordpress Admin Panel to follow below steps.</p>
												<p style="padding-left:150px; color:#424242">b. Go to WooCommerce Plugin in new window and find WooCommerce > Settings > API</p>
												<p style="padding-left:150px; color:#424242">c. In "API" Page, find Settings, Keys/Apps, and Webhooks Tabs.</p>
												<p style="padding-left:150px; color:#424242">d. Go to "Settings" tab, Click on "Enable REST API" and then click on "Save Changes".</p>
												<p style="padding-left:150px; color:#424242">e. Go to WooCommerce > Settings > API > Key/Apps > Add Key. Click Button Add Key.</p>
												<p style="padding-left:150px; color:#424242">f. Add Key Name and Set Read/Write permission in permission field.</p>
												<p style="padding-left:150px; color:#424242">g. Press "Generate API Key" button</p>
												<p style="padding-left:150px; color:#424242">h. Nice now you have Consumer Key and Consumer Secert with you.</p>
												<p style="padding-left:150px; color:#424242">i. Just Copy paste them in TM Store Plugin form.</p>
												<p style="padding-left:150px; color:#424242">j. Incase of problem email. support@twistmobile.in</p>
											<h4 style="padding-left:45px; color:#424242"><b>Step 3:</b> Just press Submit and wait for Demo Mobile app.</h4>
											<h4 style="padding-left:45px; color:#424242"><b>Step 4:</b> Once you approve Demo App we will create thetmstore free account for your application.</b></h4>
											<h4 style="padding-left:45px; color:#424242"><b>Step 5:</b> Start publishing your App in Google Play and iOS App Store.</h4>
									</div>
							<br>
							
							<p style="padding-left: 20px;">Incase of any issues in understanding the above steps, Please email us at <a href="mailto:support@twistmobile.in">support@twistmobile.in</a><br/>
							Also please note in order to allow the Mobile App APIs to work properly please <span style="color:#CB4B16;">do not</span> set the "<b>Permalink Settings</b>" to "<b>Plain options</b>".</p>
						</div>
					</div></td>
			</tr>
		</table>
	</div>
	<?php wp_nonce_field( 'tms_setup_form' ); ?>
</form>
<?php
	
}
tms_component_tms_plugin_settings();

// --------------------------------------------------------------------	
