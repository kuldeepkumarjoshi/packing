<?php
/**
 * WordPress TM Store
 * @package TMS
 * @author  TM Store
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class tms_main_class{

	/**
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = '1.0.2';
	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;
	
	/**
	 * Unique identifier for plugin.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	public $plugin_slug = 'wordpress-tm-store';
	
	public $api_endpoint_base = 'wp-tm-store-notify/api';
	
	public $allowed_post_types = array( 'plugin-active', 'social-login', 'register', 'login', 'forget-password','cart_items','countries_list',
	                                                    'splash_products','login_website','load_products','pole_products','calculate_shipping',
														'woo_version');

	/**
	 * Initialize the plugin by setting localization, filters.
	 *
	 * @since     1.0.0
	 */

	public $platform_ = 'web';
	public $user_platform = ''; // Default
	function __construct() {
		
		// Database variables
		global $wpdb;
		$this->db 					= &$wpdb;
	    add_action( 'admin_init', array( &$this, 'tms_register_settings' ) );	
		add_action( 'woocommerce_thankyou', array( &$this, 'tms_notify_tm_store_abt_new_order' ), 11, 1 );
		add_action( 'woocommerce_cancelled_order', array( &$this, 'tms_notify_tm_store_abt_new_order' ), 11, 1 );
		//add_action( 'woocommerce_new_order', 'tms_notify_tm_store_abt_new_order', 10, 1 );
		add_action( 'wp_ajax_save_tms_data', array( &$this, 'tms_save_tms_data' ) );	
		add_action( 'init', array( &$this, 'tms_add_api_endpoint' ) );
		add_action( 'template_redirect', array( &$this, 'tms_handle_api_endpoints' ) );
		add_action( 'admin_init', array( &$this, 'tms_send_social_login_api_details' ) );
		add_action( 'init', array( &$this, 'tms_set_checkout_page_cookie' ) );
		add_action( 'tms_admin_ui_footer_end', array( &$this, 'tms_add_support_link' ) );
		
		add_action('init',array( &$this, 'tms_myStartSession'), 1);
        add_action('wp_logout', array( &$this, 'tms_myEndSession'));
        add_action('wp_login', array( &$this, 'tms_myEndSession'));

		add_action('init',array( &$this,'tms_get_user_plateform'));
		add_action('admin_head',array( &$this,'tms_get_user_plateform'));
        add_action('wp_head',array( &$this,'tms_get_user_plateform'));
		
		add_action('wp_login', array( &$this,'tms_my_login_success'));
		
	}
	function tms_get_user_plateform() {
    if(isset($_GET['user_platform']))
	{
	 	$user_platform=$_GET['user_platform'];
		$_SESSION['platform'] = 'mobile';
		$_SESSION['user_platform'] = $user_platform;
	}
	
/*
* Check if plateform is set 
*/
    if(isset($_SESSION['platform']))
		{  
			$platform_=$_SESSION['platform'];
			
			if(isset($_SESSION['user_platform']))
			{
				 $user_platform=$_SESSION['user_platform'];
			}else
			{
				// $user_platform='Android';
			}
		}
			else
		{   
			$platform_='web';
			
		}
    } 
	function tms_my_login_success() {
		
      global $current_user;
      get_currentuserinfo();
      $user_email_id=$current_user->user_email;

		if(isset($_SESSION['user_platform']))
		{
			 $platform_ = $_SESSION['platform'];
			 $user_platform = $_SESSION['user_platform'];
			 if(!strcmp($platform_,'mobile'))
			   {
				if(!strcmp($user_platform,'Android'))
				{
					?>
					<script type="text/javascript">    
					var email_id = '<?php echo $user_email_id; ?>';
				   Android.showToast("Login Successful"+email_id);  
				   </script>
			   <?php
				  echo $user_email_id;
				   if(!strcmp($platform_,'mobile'))
				   {
					  exit();
				   }
			   }
			   
				if(!strcmp($user_platform,'IOS'))
				   {
					?>

					<script type="text/javascript">    
					var email_id_ios = '<?php echo $user_email_id; ?>';
					sendToApp( "Login","Successful email:"+email_id_ios);
					function sendToApp(_key, _val) 
					{
					var iframe = document.createElement("IFRAME"); 
					iframe.setAttribute("src", _key + ":##sendToApp##" + _val); 
					document.documentElement.appendChild(iframe); 
					iframe.parentNode.removeChild(iframe); 
					iframe = null; 
					}    
					</script>
					<?php 		
					exit();
				   if(!strcmp($platform_,'mobile'))
				   {
					  exit();
				   }
				} 
			   }
		}	
    }
	
	function tms_myStartSession() {
		if(!session_id()) {
			session_start();
		}
	}
	function tms_myEndSession() {
		session_destroy ();
	}
	
	/**
	 * Function to register activation actions
	 * 
	 * @since 1.0.0
	 */
	function tms_plugin_activate(){
			
		//Check for WooCommerce Installment
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) and current_user_can( 'activate_plugins' ) ) {
			// Stop activation redirect and show error
			wp_die('Sorry, but this plugin requires the Woocommerce to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
		}
		update_option('tms_plugin_activate', true);
		
		$data = array(
						'site_url' => get_bloginfo('url'),
						'plugin_version' =>'1.0.2',
					); 
			$data = http_build_query($data);
			$url='http://thetmstore.com/tmadmin/pluginUrlDetails.php';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url );
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($ch );
			curl_close ($ch);
	}	
   
 	/**
	 * Function to register deactivation actions
	 * 
	 * @since 1.0.0
	 */
	function tms_plugin_deactivate_plugin(){ 
	
		delete_option('tms_plugin_activate');
		delete_option('tms_settings');
	}
	
	function tms_add_support_link(){
		echo '<p style="float: right;">For more Details or Queries regarding TM Store Plugin, Contact Us <a href="mailto:support@twistmobile.in">support@twistmobile.in</a></p>';
	}
	
	/**
	 * Function to register the plugin settings options
	 * 
	 * @since 1.0.0
	 */
	public function tms_register_settings() {
		register_setting('tms_register_settings', 'tms_settings' );
	}	
	
	/**
	 * Function to get end-point of API
	 * 
	 * @since 1.0.0
	 */
	function tms_getApiUrl(){
		if(file_exists(plugin_dir_path( __FILE__ ).'config.txt')){
			$response = file_get_contents(plugin_dir_path( __FILE__ ).'config.txt');
			$response = json_decode($response);
			if(!empty($response)){
				return $response->api_endpoint;
			}
		} 
	}
	/**
	 * Function to get userkey
	 * 
	 * @since 1.0.0
	 */
	public function tms_getUserKey(){
		$sq_options = get_option('tms_settings');
		$user_key = $sq_options['user_key'];
		return $user_key;
	}

	/**
	 * Function to check if plugin is enabled
	 * 
	 * @since 1.0.0
	 */
     public function tms_isEnabled(){
		$sq_options = get_option('tms_settings');
		$enable = $sq_options['enable'];
		return $enable;
	}	
	
	
	function tms_set_checkout_page_cookie(){
		
		if(isset($_REQUEST['device_type'])){
		
			$device_type = (!empty($_REQUEST['device_type'])) ? $_REQUEST['device_type']: '';
			if(!empty($device_type)){
				$tm = intval( 3600 * 24 );
				setcookie("TMSDEVICE", $device_type, time()+$tm, "/");
			}
		}
	}
	
	function tms_notify_tm_store_abt_new_order( $order_id  )
	{
		//echo 'Payment Success ful-------------------------------->'.$order_id;
		if(!empty($order_id)){
			///echo 'Payment Success ful-------------------------------->'.$order_id;
			global $current_user;
		  get_currentuserinfo();
		  $user_email_id=$current_user->user_email;
			
			
			global $woocommerce;
			$order = new WC_Order( $order_id );
			$order_status = $order->get_status();
			$user_platform = (isset($_COOKIE['TMSDEVICE'])) ? $_COOKIE['TMSDEVICE'] : '';   
			if(!empty($user_platform)){
				?>
				<script type="text/javascript">
				//<![CDATA[
				var orderid = <?php echo $order_id; ?>;    
				var orderstatus = '<?php echo $order_status; ?>';      
				function sendResponse_IOS(_key, _val) {
					var iframe = document.createElement("IFRAME"); 
					iframe.setAttribute("src", _key + ":##sendToApp##" + _val); 
					document.documentElement.appendChild(iframe); 
					iframe.parentNode.removeChild(iframe); 
					iframe = null; 
				}
				function sendResponse_ANDROID(resposne)
				{
					Android.showToast(""+resposne);
				} 
				//]]>
				</script>
				<?php
				if($user_platform == 'android')
				{
				//echo 'ggoogog';
				?>
					<script type="text/javascript">    
					var email_id = '<?php echo $user_email_id; ?>';
					Android.showToast("["+orderid + "]Purchase " + orderstatus+" emailid:"+email_id);    
					</script>
				<?php 
				exit();
				}
				if( $user_platform == 'ios' )
				{
							?>

				<script type="text/javascript">    
				sendToApp( "purchase","orderid:"+orderid + ",orderstatus:" + orderstatus);
				function sendToApp(_key, _val) 
				{
				var iframe = document.createElement("IFRAME"); 
				iframe.setAttribute("src", _key + ":##sendToApp##" + _val); 
				document.documentElement.appendChild(iframe); 
				iframe.parentNode.removeChild(iframe); 
				iframe = null; 
				}    
				</script>
				<?php 		
				exit();
			   if(!strcmp($platform_,'mobile'))
			   {
				  exit();
			   }
				}
			}
		}
	}
	
	function tms_save_tms_data(){
		
		$response_array =  array( 
								   'results' => 0,
								   'error' => 'Data not send.'
								);
		
		$nonce = $_POST['_wpnonce'];
								
		if ( ! wp_verify_nonce( $nonce, 'tms_setup_form' ) ) {

			$response_array =  array( 
								   'results' => 0,
								   'error' => 'Security error.'
								);
								
			$res = json_encode($response_array);
			die($res);
			
		}
		
		if ( !current_user_can( 'manage_options' )){
			
          	$response_array =  array( 
								   'results' => 0,
								   'error' => 'Security error.'
								);
								
			$res = json_encode($response_array);
			die($res);
		}

		
		if(isset($_POST)){
		
			$data = $_POST; 
			
			$insertdata = array();
			$insertdata['username'] =  (isset($_POST['username'])) ? sanitize_text_field($_POST['username']) : '';
			$insertdata['email-api'] =  (isset($_POST['email-api'])) ? sanitize_email($_POST['email-api']) : '';
			$insertdata['website-api'] =  (isset($_POST['website-api'])) ? esc_url_raw($_POST['website-api']) : '';
			$insertdata['wc-api-key'] =  (isset($_POST['wc-api-key'])) ? sanitize_text_field($_POST['wc-api-key']) : '';
			$insertdata['wc-api-secret'] =  (isset($_POST['wc-api-secret'])) ? sanitize_text_field($_POST['wc-api-secret']) : '';
			$insertdata['site_url'] =  (isset($_POST['site_url'])) ? sanitize_text_field($_POST['site_url']) : '';
			
			$data = http_build_query($insertdata);
			$url='http://thetmstore.com/tmadmin/PluginFormDetails.php';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url );
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($ch);
			
			if(curl_exec($ch) === false)
			{
				$response_array =  array( 
									   'results' => 0,
									   'error' => curl_error($ch)
									);
			} else {
				curl_close ($ch);
				if(!is_serialized( $insertdata ) ){
					//insert/update data
					$insertdata = maybe_serialize( $insertdata );
					
					update_option( 'tms_settings_data', $insertdata );
									
				} 
				$response_array =  array( 
									   'results' => 1,
									   'error' => ''
									);
			}
		}
		
		echo json_encode($response_array);
		
		die();
	}
	
	/**
	 * Create our json endpoint by adding new rewrite rules to WordPress
	 */
	function tms_add_api_endpoint(){
		
		global $wp_rewrite;
		
		$post_type_tag = $this->api_endpoint_base . '_type';
		$post_id_tag   = $this->api_endpoint_base . '_id';

		add_rewrite_tag( "%{$post_type_tag}%", '([^&]+)' );
		add_rewrite_tag( "%{$post_id_tag}%", '([0-9]+)' );

		add_rewrite_rule(
			$this->api_endpoint_base . '/([^&]+)/([0-9]+)/?',
			'index.php?'.$post_type_tag.'=$matches[1]&'.$post_id_tag.'=$matches[2]',
			'top' );

	
		add_rewrite_rule(
			$this->api_endpoint_base . '/([^&]+)/?',
			'index.php?'.$post_type_tag.'=$matches[1]',
			'top' );
			
		$wp_rewrite->flush_rules( false );
	}

	/**
	 * Handle the request of an endpoint
	 */
	function tms_handle_api_endpoints()
	{
		
		global $wp_query;

		// get the query args and sanitize them for confidence
		$type = sanitize_text_field( $wp_query->get( $this->api_endpoint_base . '_type' ) );
		$id   = intval( $wp_query->get( $this->api_endpoint_base . '_id' ) );
		
		// only allowed post_types
		if ( ! in_array( $type, $this->allowed_post_types ) ) {
			return;
		}

		switch ( $type ) {
			
			case "plugin-active":
				$data = $this->tms_api_plugin_activate_status_action( $_POST );
				break;
			case "social-login":
				$data = $this->tms_api_social_login_action( $_POST );
				break;
			case "register":
				$data = $this->tms_api_register_action( $_POST );
				break;
			case "login":
				$data = $this->tms_api_login_action( $_POST );
				break;
			case "forget-password":
				$data = $this->tms_api_forget_password_action( $_POST );
				break;
			case "cart_items":
			    $data=$this->tms_push_to_cart($_POST);
				break;
		    case "countries_list":
			   $data=$this->tms_get_countries_list( $_POST);
			   break;
			case "splash_products":
			   $data=$this->tms_get_woocommerce_product_list($_POST);
			   break;
			case "payment_gateway_list":
			   $data=$this->tms_get_available_payment_gateways();
			   break;
			case "login_website":
			   $data=$this->tms_login_website($_POST);
			   break;
			case "load_products":
			   $data=$this->tms_load_products();
			    break;
			case "pole_products":
			   $data=$this->tms_load_pole_products($_POST);
               break;				
			case "calculate_shipping":
			   $data=$this->tms_calculate_shipping();
			   break;
			case "woo_version":
			   $data=$this->tms_get_woo_version();
			   break;
		}	
		// data is built. print as json and stop
		if(isset($data) && !empty($data)){
			wp_send_json( $data ); 
		} else {
			$data = array(
									'status' => 'failed',
									'error' => 1,
									'message' => 'No data received.'
								);
			wp_send_json( $data ); 
		}
		
		exit;
	}
	function escapeJsonString($value) 
	{
     
		$escapers =     array("\\",     "/",   "\"",  "\n",  "\r",  "\t", "\x08", "\x0c");
		$replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t",  "\\f",  "\\b");
		$result = str_replace($escapers, $replacements, $value);
		return $result;
    }

	 function tms_push_to_cart($postData= array()) 
     {
		 if(isset($postData))
		{
			global $woocommerce;
			$woocommerce->cart->empty_cart();
			$str='
			[
			{
				"pid":458,
				"variation_id":297,
				"quantity":3,
				"attributes":[
					{
						"name":"color",
						"value":"red"
					},
					{
						"name":"size",
						"value":"small"
					}
				]
			},
			{
				"pid":459,
				"variation_id":297,
				"quantity":1,
				"attributes":[
					{
						"name":"color",
						"value":"red"
					},
					{
						"name":"size",
						"value":"small"
					}
				]
			}
	
         ]';
       //$str_temp=$postData['cart_data'];
	   //str_replace(array("/"),array(""),$str_temp);
	   //$json = (array) json_decode($str_temp);
	   //echo ''.count($json);
	   
	   $recived_str = stripslashes($postData['cart_data']);  
	   
	 //  echo 'ship str '.$ship_str;
	   
	   $json =json_decode($recived_str,true);
	   for($i=0;$i<count($json);$i++)
		{
			
			$product_id=$json[$i]["pid"];
			//echo 'inside  '.$product_id;
			$variation_id=$json[$i]['variation_id'];
			$quantity=$json[$i]['quantity'];
			//echo 'pallavi'.$quantity;
			  $spec = array();
			for($j=0;$j<count($json[$i]['attributes']);$j++)
			{
				$spec[$json[$i]['attributes'][$j]['name']]=$json[$i]['attributes'][$j]['value'];
			}
			
		   // echo 'product id '.$product_id.' sss'.$quantity.' variation id '.$variation_id;
			if($variation_id!=-1)
			{
				$woocommerce->cart->add_to_cart( $product_id, $quantity, $variation_id, $spec, null );
			}else
			{
				$woocommerce->cart->add_to_cart( $product_id, $quantity);
			}
			
		}
	   
		}
		  
	    
		$shipping_methods=$this->tms_calculate_shipping($postData['ship_data']);
		$payment_gateWays=$this->tms_get_available_payment_gateways();			
		$arr_data = array("shipping_data" => $shipping_methods,"payment"=>$payment_gateWays);
		return  $arr_data;
	 }
	 function tms_get_woo_version()
	 {
		 global $woocommerce;
		 
		 $meta_data= array(
            'woo_version' => $woocommerce->version,
			'ssl_enabled'    	 => ( 'yes' === get_option( 'woocommerce_force_ssl_checkout' ) ),
            'permalinks_enabled' => ( '' !== get_option( 'permalink_structure' ) ),
			'tm_version'=>'1.0.2',
        );
		return $meta_data;
	 }
	 function tms_get_metadata()
	 {
		 global $woocommerce;
         $cart_url = $woocommerce->cart->get_cart_url();
		 $meta_data= array(
            'tz'			 => wc_timezone_string(),
            'c'       	 => get_woocommerce_currency(),
            'c_f'    => get_woocommerce_currency_symbol(),
            't_i'   	 => ( 'yes' === get_option( 'woocommerce_prices_include_tax' ) ),
            'w_u'    	 => get_option( 'woocommerce_weight_unit' ),
            'd_u' 	 => get_option( 'woocommerce_dimension_unit' ),
            //'ssl_enabled'    	 => ( 'yes' === get_option( 'woocommerce_force_ssl_checkout' ) ),
            //'permalinks_enabled' => ( '' !== get_option( 'permalink_structure' ) ),
			'd_s' =>get_option('woocommerce_price_decimal_sep'),
			't_s' =>get_option('woocommerce_price_thousand_sep'),
			'p_d'=>absint(get_option('woocommerce_price_num_decimals', 2)),
			'c_p'=>get_option( 'woocommerce_currency_pos'),
			'cart_url'=> $cart_url,
        );
		return $meta_data;
	 }
	 function tms_login_website($postData = array()){
		if(isset($postData) && !empty($postData['user_emailID']))
		{
			$_SESSION['user_platform']=$postData['user_platform'];
			$_SESSION['platform']='mobile';
			
			$email_id=$postData['user_emailID'];
			$user = get_user_by('email', $email_id);
			$user_id = $user->ID;
			if($user) 
			{
			wp_set_current_user( $user_id, $user->user_login );
			wp_set_auth_cookie( $user_id );
			do_action( 'wp_login', $user->user_login );
	      }
		}		
	 }
	 function tms_get_available_payment_gateways()
	 {
		$available_payment_gateways = WC()->payment_gateways()->get_available_payment_gateways();
		//return $available_payment_gateways;
		$gateway_list = array();
	    foreach($available_payment_gateways as $key => $gateway)
		{
         
                $gateway_list['gateways'][]= array(
                    "id" => $gateway->id,
                    "title" => $gateway->get_title(),
                    "description" =>$gateway->get_description(),
                    "icon" =>$gateway->get_icon(),
                    "chosen" =>$gateway->chosen,
                    "order_button_text" =>$gateway->order_button_text,
                    "enabled" =>$gateway->enabled,
                     "testmode" =>$gateway->testmode,
                    // "availability" =>$gateway->availability,
                    //"supports" =>$gateway->supports,
                );
           
        }
	    return $gateway_list;
	 }
	 function tms_get_countries_list($postData = array())
	 {
		global $woocommerce;
		$list_countries = WC()->countries->get_allowed_countries();
        $specific_states =  WC()->countries->get_allowed_country_states();
        $list_array['list'] = array();
        $i=-1;
        foreach($list_countries as $key=>$country) {
            $list_array['list'][++$i]=array("id"=>$key,"n"=>html_entity_decode($country),"s"=>array());
            if(isset($specific_states[$key]) && is_array($specific_states[$key])){
                foreach($specific_states[$key] as $key=>$state) {
                    $list_array['list'][$i]["s"][] = array("id"=>$key,"n"=>html_entity_decode($state));
                }
            }
        }
        return $list_array;
	 }
	 function tms_get_states_list($postData = array())
	 {
		  if(isset($postData) && !empty($postData['country_code']))
		  {
			  global $woocommerce;
			  $countries_obj   = new WC_Countries();
			  $countries   = $countries_obj->get_states($postData['country_code']);
			  return $countries;
		  }
		 return null;	 
	 }
	 function tms_load_pole_products($postData = array())
	 {
		  if(isset($postData) && !empty($postData['pole_param']))
		  {
			  $pole_parm_string=$postData['pole_param'];
			
			  $pole_parma_array=explode(';', $pole_parm_string);
			 
			  $productlist = array();
			   for ($i = 0; $i < count($pole_parma_array); $i++) 
				{
					if($pole_parma_array[$i]!="")
					{
					$product = wc_get_product( $pole_parma_array[$i]);
					$product_info= $this->tms_get_product_short_info($product,2);
					array_push($productlist,$product_info);
					}
					}
			  return $productlist;
			  
		  }
		  return "";
	 }
	 function tms_load_products($postData = array())
	 {
		 $product_limit=10;
		if(isset($postData) && !empty($postData['product_limit']))
		  {
			  $product_limit=$postData['product_limit'];
		  }
		 // $product_limit=2;
		$product_category_list = array();
		
			$args = array(
			'number'     => $number,
			'orderby'    => $orderby,
			'order'      => $order,
			'hide_empty' => $hide_empty,
			'include'    => $ids
			);

			$product_categories = get_terms( 'product_cat', $args );
		    //return $product_categories;
			//exit;
			foreach( $product_categories as $cat ) 
			{ 
			         $category_info= $this->tms_get_category_short_info($cat,'0');  
					 $product_list=array();
					 $children = get_term_children($cat->term_id, $cat->taxonomy);
					  if(empty( $children))
					 {
					 
					  $args = array(
						'posts_per_page' =>$product_limit,
						'post_type' => 'product',
						'tax_query'     => array(
						array(
							'taxonomy'  => 'product_cat',
							'field'     => 'id', 
							'terms'     =>$cat->term_id
						)
						)
						);
						$r = new WP_Query( $args );
						if ($r->have_posts()) {
						while ($r->have_posts()) : $r->the_post(); global $product; 
					    $product_info=  $this->tms_get_product_short_info($product,0);
						array_push($product_list, $product_info);
						endwhile;
						} 
					  }
					  $arr = array("category" => $category_info,"products" => $product_list);
					array_push($product_category_list, $arr);
			}
			return $product_category_list;
	 }
	 
	 // according to splash requirement.
	function tms_get_woocommerce_product_list($postData = array()) 
	{
		$product_limit=10;
		if(isset($postData) && !empty($postData['product_limit']))
		  {
			  $product_limit=$postData['product_limit'];
		  }
		 // $product_limit=2;
		$product_category_list = array();
		
			$args = array(
			'number'     => $number,
			'orderby'    => $orderby,
			'order'      => $order,
			'hide_empty' => $hide_empty,
			'include'    => $ids
			);

			$product_categories = get_terms( 'product_cat', $args );
			//return $product_categories;
			//exit;
			foreach( $product_categories as $cat ) 
			{ 
			         $category_info= $this->tms_get_category_short_info($cat,'1');
					   
					 $product_list=array();
					// if($cat->parent==0)
					 {
					 
					  $args = array(
						'posts_per_page' =>$product_limit,
						'post_type' => 'product',
						'tax_query'     => array(
						array(
							'taxonomy'  => 'product_cat',
							'field'     => 'id', 
							'terms'     =>$cat->term_id
						)
						)
						);
						$r = new WP_Query( $args );
						if ($r->have_posts()) {
						while ($r->have_posts()) : $r->the_post(); global $product; 
						//echo 'product id'.the_ID();
						
						//echo 'count '.count($category_info['img_url']);
						if($category_info['img_url'][0]==""||$category_info['img_url'][0]=='')
						{
							$img_url= wp_get_attachment_image_src(get_post_thumbnail_id($r->post->ID));
							if(count($img_url)>0)
							{
							 $category_info['img_url'] = $img_url[0];
						     break;
							}
							
						}
						
						 //$product_info=  $this->tms_get_product_short_info($product);
							//array_push($product_list, $product_info);
						endwhile;
						} 
					  }
					 //  $arr = array("category" => $category_info,"products" => $product_list);
					   array_push($product_category_list, $category_info);				  
			}
			$meta_data=$this->tms_get_metadata();
			$best_selling= $this->tms_get_best_selling_products($product_limit);              //trending 
		    $new_arrivals=$this->tms_get_recent_products($product_limit);                  //new_arrivals
		    $new_sales=$this->tms_get_sale_products($product_limit);    
            $payment_gateWays=$this->tms_get_available_payment_gateways();			
			$arr_meta_product = array("category" => $product_category_list,"meta_data" => $meta_data,"best_selling" => $best_selling,"new_arrivals" => $new_arrivals,"new_sales" => $new_sales
			                           ,"payment"=>$payment_gateWays);
				
			return $arr_meta_product;
			/*$args = array('post_status' =>'publish','tax_query' =>array( 'taxonomy' => 'categories','field'    => 'id','term'=> '15'));
			$the_query = new wp_query($args);
			echo ''.json_encode($the_query);*/

  }

  function tms_get_category_short_info($cat,$format)
	{
		  $category_info['id']=$cat->term_id;
		  if($format!='0')
		  {
			$thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true ); 
		  // get the image URL
		  $image = wp_get_attachment_url( $thumbnail_id );  
		  $category_info['parent']=$cat->parent;
		  $category_info['id']=$cat->term_id;
		  $category_info['name']=$cat->name;
		  $category_info['slug']=$cat->slug;
		  $category_info['img_url']=$image;
		  $category_info['count']=$cat->count;
		  }
		  return $category_info;
  }
  function tms_get_product_short_info($product,$format)
  {
			//$product=96;
			if(!is_a($product,"WC_Product"))
			//$product =  get_product($product);
			$details['p_temp'] = 'zzz';
			$details['stock'] =$product->is_in_stock();
			$details['url'] =$product->get_permalink();
			if($format!=2)//for pole products
			{
			$short_desc = apply_filters('woocommerce_mobapp_short_description', $product->get_post_data()->post_excerpt);	
			$details['desc'] =do_shortcode($short_desc);
			}
			$details['title'] = $product->post->post_title;
			//echo 'id is '.$product->post->ID;
			$details['id'] = $product->post->ID;
			$temp_url=wp_get_attachment_image_src(get_post_thumbnail_id($product->post->ID));
			if(count($temp_url)>0)
			{
				$details['img'] = $temp_url[0];
			}
			//$details['img_0'] = $details['featured_src'][0];
			$details['type'] = $product->product_type;
			$details['price'] = $product->get_price();
			$details['regular_price'] = $product->get_regular_price();
			$details['sale_price'] = $product->get_sale_price();
			if($format!=0)//categories are sent for best selling etc products
			{
				
				$cat_data=  wp_get_post_terms( $product->post->ID, 'product_cat' );
				
				$catarray = array();
				foreach( $cat_data as $cat ) 
				{
					array_push($catarray,$cat->term_id);
				}
				$details['category_ids'] =$catarray;
				
				/*if($product->product_type == 'variable')
				{
					$details['var']=$product->get_available_variations();
				}*/
			}	
			return $details;
}

  public function tms_get_recent_products($product_limit){
        $atts =  array(
            'per_page' 	=> '12',
            'columns' 	=> '4',
            'orderby' 	=> 'date',
            'order' 	=> 'desc'
        );
        extract($atts);
        $meta_query = WC()->query->get_meta_query();
        $args = array(
            'post_type'				=> 'product',
            'post_status'			=> 'publish',
            'ignore_sticky_posts'	=> 1,
            'posts_per_page' 		=> $product_limit,
            'orderby' 				=> $orderby,
            'order' 				=> $order,
            'meta_query' 			=> $meta_query
        );
        $products = $this->tms_get_ids($args,$atts);
        return $products;
    }
    public function tms_get_featured_products(){
        $atts = array(
            'per_page' 	=> '12',
            'columns' 	=> '4',
            'orderby' 	=> 'date',
            'order' 	=> 'desc'
        );
        extract($atts);
        $args = array(
            'post_type'				=> 'product',
            'post_status' 			=> 'publish',
            'ignore_sticky_posts'	=> 1,
            'posts_per_page' 		=> $per_page,
            'orderby' 				=> $orderby,
            'order' 				=> $order,
            'meta_query'			=> array(
                array(
                    'key' 		=> '_visibility',
                    'value' 	=> array('catalog', 'visible'),
                    'compare'	=> 'IN'
                ),
                array(
                    'key' 		=> '_featured',
                    'value' 	=> 'yes'
                )
            )
        );

        $products = $this->tms_get_ids($args,$atts);
        return $products;
    }
    public function tms_get_sale_products($product_limit){
        $atts =  array(
            'per_page'      => '12',
            'columns'       => '4',
            'orderby'       => 'title',
            'order'         => 'asc'
        );
        extract($atts);
        // Get products on sale
        $product_ids_on_sale = wc_get_product_ids_on_sale();

        $meta_query   = array();
        $meta_query[] = WC()->query->visibility_meta_query();
        $meta_query[] = WC()->query->stock_status_meta_query();
        $meta_query   = array_filter( $meta_query );

        $args = array(
            'posts_per_page'	=> $product_limit,
            'orderby' 			=> $orderby,
            'order' 			=> $order,
            'no_found_rows' 	=> 1,
            'post_status' 		=> 'publish',
            'post_type' 		=> 'product',
            'meta_query' 		=> $meta_query,
            'post__in'			=> array_merge( array( 0 ), $product_ids_on_sale )
        );
        $products = $this->tms_get_ids($args,$atts);
        return $products;
    }
    public function tms_get_best_selling_products($product_limit){
        $atts = array(
            'per_page'      => '12',
            'columns'       => '4'
        );
        extract($atts);
        $args = array(
            'post_type' 			=> 'product',
            'post_status' 			=> 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page'		=> $product_limit,
            'meta_key' 		 		=> 'total_sales',
            'orderby' 		 		=> 'meta_value_num',
            'meta_query' 			=> array(
                array(
                    'key' 		=> '_visibility',
                    'value' 	=> array( 'catalog', 'visible' ),
                    'compare' 	=> 'IN'
                )
            )
        );

        $products = $this->tms_get_ids($args,$atts);
        return $products;
    }

    public function tms_top_rated_products(){
        $atts =  array(
            'per_page'      => '12',
            'columns'       => '4',
            'orderby'       => 'title',
            'order'         => 'asc'
        );
        extract($atts);
        $args = array(
            'post_type' 			=> 'product',
            'post_status' 			=> 'publish',
            'ignore_sticky_posts'   => 1,
            'orderby' 				=> $orderby,
            'order'					=> $order,
            'posts_per_page' 		=> $per_page,
            'meta_query' 			=> array(
                array(
                    'key' 			=> '_visibility',
                    'value' 		=> array('catalog', 'visible'),
                    'compare' 		=> 'IN'
                )
            )
        );

        $products = $this->tms_get_ids($args,$atts);
        return $products;
    }

    private function tms_get_ids($args,$atts){
        $r = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );
        $product_info = array();
        if ($r->have_posts()) {
						while ($r->have_posts()) : $r->the_post(); global $product; 
						//echo 'product id'.the_ID();
						    $product_info[]=  $this->tms_get_product_short_info($product,1);
							//array_push($product_list, $product_info);
						endwhile;
						 } 
		
						
		
		
		
        return $product_info;
    }
	public function tms_get_shipping_methods()
	{	
          global $woocommerce;
		 WC()->customer->calculated_shipping( true );
		 $this->shipping_calculated = true;
		 do_action( 'woocommerce_calculated_shipping' );
		 $woocommerce->cart->calculate_shipping();
            $packages = WC()->shipping()->get_packages();
		   //return  $packages;
		
        $return = array();
        if($woocommerce->cart->needs_shipping() ){
            $return['show_shipping'] = 1;
            $woocommerce->cart->calculate_shipping();
            $packages = WC()->shipping()->get_packages();
		   ///return  $packages;
            foreach ( $packages as $i => $package ) {
                $chosen_method = isset( WC()->session->chosen_shipping_methods[ $i ] ) ? WC()->session->chosen_shipping_methods[ $i ] : '';
                $return['shipping'][] = array('methods'=>$this->tms_getMethodsInArray($package['rates']),
                    'chosen'=>$chosen_method,'index'=>$i
                );
            }
        }else{
            $return['show_shipping'] = 0;
            $return['shipping'] = array();
        }
        if(empty($return['shipping']) || is_null($return['shipping']) || !is_array($return['shipping'])) {
            $return['show_shipping'] = 0;
            $return['shipping'] = array();
        }
		//$chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
       return $return;		
    }
    private function tms_getMethodsInArray($methods){
        $return = array();
        foreach($methods as $method){
            $return[]=array(
                'id'=>$method->id,
                'label'=>$method->label,
                'cost'=>$method->cost,
                'taxes'=>$method->taxes,
                'method_id'=>$method->method_id,
			//	'title'=>$method->method_title,
            );
        }
        return $return;
    }
	 function cart()
	{
		global $woocommerce;
		return $woocommerce->cart;
	}
	public function tms_get_cart_meta($data)
	{
	    $this->cart()->calculate_shipping();
        global $woocommerce;
        $return = array(
            "count"=>$this->cart()->get_cart_contents_count(),
            "shipping_fee" =>!empty($this->cart()->shipping_total)?$this->cart()->shipping_total:0,
            "tax"=>$this->cart()->get_cart_tax(),
			"total_tax"=>WC()->cart->tax_total,
			"shipping_tax"=> WC()->cart->shipping_tax_total,
            "fees"=>$this->cart()->get_fees(),
            "currency" =>get_woocommerce_currency(),
            "currency_symbol"=>get_woocommerce_currency_symbol(),
            "total"=>$this->cart()->get_cart_subtotal(true),
            "cart_total"=>$this->cart()->cart_contents_total,
            "order_total"=>$woocommerce->cart->get_cart_total(),
            "price_format"=>get_woocommerce_price_format(),
            'timezone'			 => wc_timezone_string(),
            'tax_included'   	 => ( 'yes' === get_option( 'woocommerce_prices_include_tax' ) ),
            'weight_unit'    	 => get_option( 'woocommerce_weight_unit' ),
            'dimension_unit' 	 => get_option( 'woocommerce_dimension_unit' ),
            "can_proceed"   => true,
            "error_message"   => "",
        );
		

        return $return;
    }
	function tms_api_plugin_activate_status_action(){
		
		$response = array(
					'status' => 'success',
					'error' => '',
					'message' => 'Plugin is active.'
				);
				
		return $response;
	}
	   public function tms_get_cart_api() {

		global $woocommerce;
        $cart = array_filter( (array)$woocommerce->cart->cart_contents );
        $return =array();
        foreach($cart as $key=>$item){
            $item["key"] = $key;
            $variation = array();
            if(isset($item["variation"]) && is_array($item["variation"])){
                foreach($item["variation"] as $id=>$variation_value){
                    $variation[] = array(
                        "id" => str_replace('attribute_', '', $id),
                        "name"   =>  wc_attribute_label(str_replace('attribute_', '', $id)),
                        "value_id"  => $variation_value,
                        "value"  => trim(esc_html(apply_filters('woocommerce_variation_option_name', $variation_value)))
                    );
                }
            }
            $item["variation"] = $variation;
            $item = array_merge($item,$this->tms_get_product_short_info($item["data"],0));
            unset($item["data"]);
            $return[] = $item;
        }
        return $return;
    }
	function tms_calculate_shipping($postData1= array()){
	 //'AF','CG','500029','Indore'
	/* $_POST = 
	 array(
	 'cal_shipping_postcode'=>'500029',
	 'cal_shipping_country'=>'IN',
	 'cal_shipping_state'=>'CG',
	 'cal_shipping_city'=>'Indore', 
	 'cal_chosen_method'=>'flat_rate'
	 );
  */
   $ship_str = stripslashes($postData1);

   $postData= json_decode($ship_str,true);
  // echo 'country  '.$postData['cal_shipping_country'].' state '.$postData['cal_shipping_state'].' post code '.$postData['cal_shipping_postcode'].' city  '.$postData['cal_shipping_city'];
   /*$postData['cal_shipping_country']='AT';
   $postData['cal_shipping_state']='CG';
   $postData['cal_shipping_postcode']='500029';
   $postData['cal_shipping_city']='Indore';
  //$postData['cal_chosen_method']='flat_rate';
  
   /*$postData['cal_shipping_country']='AF';
   $postData['cal_shipping_state']='KA';
   $postData['cal_shipping_postcode']='12345';
   $postData['cal_shipping_city']='kabul';*/
				 
        $reponseData = array();
        $data = array();
        try {
           
                     WC()->shipping->reset_shipping();
					 if(isset($postData['cal_chosen_method'])  && !empty($postData['cal_chosen_method']))
					 {
						 WC()->session->set('chosen_shipping_methods', array($postData['cal_chosen_method']));
					 }
                    $country  = $postData['cal_shipping_country'];
                    $state    = $postData['cal_shipping_state'];
                    $postcode = $postData['cal_shipping_postcode'];
                     $city     = $postData['cal_shipping_city'];
                     
					 //echo 'region Data  '. $country.'  -- '.$state.'  -- '.$postcode.' --  ' .$city;
					 
                     if ( !empty($postcode) && ! WC_Validation::is_postcode( $postcode, $country ) ) {                       
								$reponseData = array(
									'status' => 'failed',
									'error' => '',
									'message' => 'Please enter a valid postcode/ZIP.'
								);
						return $reponseData;
                     } elseif ( !empty($postcode) ) {
                         $postcode = wc_format_postcode( $postcode, $country );
                     }
                     if ( $country ) {
                         WC()->customer->set_location( $country, $state, $postcode, $city );
                         WC()->customer->set_shipping_location( $country, $state, $postcode, $city );
                     } else {
                         WC()->customer->set_to_base();
                         WC()->customer->set_shipping_to_base();
                     }

                     WC()->customer->calculated_shipping( true );
                     $this->shipping_calculated = true;
                     do_action( 'woocommerce_calculated_shipping' );
					 
					  WC()->session->set('wc_shipping_calculate_details',$postData);
					   //$this->cart()->calculate_totals();
					
                     //$reponseData=array('cart_data'=>$this->tms_get_cart_api(),'cart_meta_data'=>$this->tms_get_cart_meta($postData),'cart_shipping_methods'=>$this->tms_get_shipping_methods());
					 $reponseData=$this->tms_get_shipping_methods();
					 
             
        }catch (Exception $e){
          
        }
        return $reponseData;
    }
	function tms_api_social_login_action( $postData = array() ){
		
		$response = array();
		if(isset($postData) && !empty($postData['user_emailID'])){
			
			$username = base64_decode($postData['user_emailID']);
			$username = sanitize_user($username, true);
			
			if(is_email($username)){
				if( username_exists( $username ) || email_exists( $username ) ) {
					
					$response = array(
									'status' => 'success',
									'error' => '',
									'message' => 'Login Successful.'
								);
				} else {
					
					$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
					
					$user_email = ( is_email( $username ) ) ? $username : '';
					if(!empty($user_email)){
						$user_id = wp_create_user( $username, $random_password, $user_email );
					} else {
						$user_id = wp_create_user( $username, $random_password );
					}
					if($user_id){
						
						wp_new_user_notification( $user_id, null, 'both' );
						$response = array(
										'status' => 'success',
										'error' => '',
										'message' => 'Registration Successful for user: '.$username.'.'
									);
					} else {
						$response = array(
										'status' => 'failed',
										'error' => 1,
										'message' => 'Not able to register user: '.$username.'.'
									);
					}
				}
			} else {
				
				$response = array(
										'status' => 'failed',
										'error' => 1,
										'message' => 'Invalid email address.'
									);
			}
		}
		
		return $response;
	}
	
	function tms_api_register_action( $postData = array()){
		
		$response = array();
		
		if(isset($postData) && !empty($postData['user_name']) && !empty($postData['user_emailID']) && !empty($postData['user_pass'])){
			
			$username = base64_decode($postData['user_name']);
			$password = base64_decode( $postData['user_pass'] );
			$user_email = base64_decode( $postData['user_emailID'] );
			
			$username = sanitize_user($username, true);
			$user_email = sanitize_user($user_email, true);
			
			if(is_email($user_email)){
			
				if( username_exists( $username ) ) {
					
					$response = array(
									'status' => 'failed',
									'error' => 1,
									'message' => 'Username already exists.'
								);
				} else if(email_exists( $user_email )){
					
					$response = array(
									'status' => 'failed',
									'error' => 1,
									'message' => 'Email address already exists.'
								);
					
				} else{
					
					$user_email = ( is_email( $user_email ) ) ? $user_email : '';
					
					if(!empty($user_email)){
						$user_id = wp_create_user( $username, $password, $user_email );
					} else {
						$user_id = wp_create_user( $username, $password );
					}
					if($user_id){
						wp_new_user_notification( $user_id, null, 'both' );
						$response = array(
										'status' => 'success',
										'error' => '',
										'message' => 'Registration Successful for user: '.$username.'.'
									);
					} else {
						$response = array(
										'status' => 'failed',
										'error' => 1,
										'message' => 'Not able to register user: '.$username.'.'
									);
					}
				}
			} else {
				
				$response = array(
										'status' => 'failed',
										'error' => 1,
										'message' => 'Invalid email address.'
									);
			}
		}
		
		return $response;
	}
	
	function tms_api_login_action( $postData = array()){
		
		$response = array();
		if(isset($postData) && !empty($postData['user_emailID']) && !empty($postData['user_pass'])){
			
			$emailID = base64_decode( $postData['user_emailID'] );
			
			$emailID  = sanitize_user($emailID , true);
			
			if(is_email($emailID)){
			
				if(email_exists( $emailID )){
					
					$user = get_user_by( 'email', $emailID );
					$user_id = $user->ID;
					$password = base64_decode( $postData['user_pass'] );
					
					$creds = array();
					$creds['user_login'] = $user->data->user_login;
					$creds['user_password'] = $password;
					$user = wp_signon( $creds, false );
					if ( is_wp_error($user) ){
						
						$erStr = 'ERROR: ';
						$error_message =    str_replace($erStr, "", strip_tags($user->get_error_message()));
						$erStr1 = 'Lost your password?';
						$error_message =    str_replace($erStr1, "", $error_message);
						$response = array(
											'status' => 'failed',
											'error' => 1,
											'message' => $error_message
									);
					} else {
						//for testing.
								//if($user) 
								{
								wp_set_current_user( $user_id, $user->user_login );
								wp_set_auth_cookie( $user_id );
								do_action( 'wp_login', $user->user_login );
								}
						$response = array(
											'status' => 'success',
											'error' => '',
											'message' => 'Login Successful.'
									);
					}
				} else {
					$response = array(
										'status' => 'failed',
										'error' => 1,
										'message' => 'Email address does not exists.'
								);
				}
			} else {
				
				$response = array(
										'status' => 'failed',
										'error' => 1,
										'message' => 'Invalid email address.'
									);
			}
		}
		
		return $response;
	}
	
	function tms_api_forget_password_action( $postData = array() ){
		
		global $woocommerce;
		
		$response = array();
		
		if(isset($postData) && !empty($postData['user_emailID'])){
		
			$user_email = base64_decode($postData['user_emailID']);
			
			$user_email  = sanitize_user($user_email , true);
			
			if(is_email($user_email)){
			
				if( email_exists( $user_email ) ){
				
					$user_login = $user_email;
				
					$htmlValueNoonce = wp_nonce_field( 'lost_password', '_wpnonce', '', false );
					$dom = new DOMDocument();
					$dom->loadHTML( $htmlValueNoonce );
					$xp = new DOMXpath( $dom );
					$nodes = $xp->query('//input[@name="_wpnonce"]');
					$node = $nodes->item(0);
					$wpnonceVal = $node->getAttribute('value');
					 
					$url = esc_url( wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) ) );
					
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url );
					curl_setopt($ch, CURLOPT_POST, false);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$server_output = curl_exec ($ch );
					curl_close ($ch);
					
					libxml_use_internal_errors(true);
					$dom = new DOMDocument();
					$dom->loadHTML($server_output);
					$xpath = new DOMXPath($dom);
					foreach($xpath->query("//title") as $node) {
						$pageNotFound = $node->textContent;
					}
					
					$pattern = '/Page not found/';
					
					if (!preg_match($pattern, $pageNotFound)) {
					
						$siteURL = get_bloginfo('url');
						$wp_http_referer = explode( $siteURL, $url );
						$wp_http_referer =(isset($wp_http_referer[1])) ? $wp_http_referer[1] : ''; 
					
						if( !empty($wpnonceVal) ){
							$data = array(
											'user_login' => $user_login,
											'wc_reset_password' => true,
											'_wpnonce' => $wpnonceVal,
											'_wp_http_referer' => $wp_http_referer
										); 
										
							$data = http_build_query($data);
							
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, $url );
							curl_setopt($ch, CURLOPT_POST, 1);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							$server_output = curl_exec ($ch );
							curl_close ($ch);
							
							$response = array(
										'status' => 'success',
										'error' => '',
										'message' => 'Lost password email sent successfully.'
									);
							
							
						} else {
							$response = array(
										'status' => 'failed',
										'error' => 1,
										'message' => 'Unable to send lost password email.'
									);
						}
					} else {
						$response = array(
										'status' => 'failed',
										'error' => 404,
										'message' => 'Lost password page not set for WooCommerce.'
									);
					}
				} else {
					
					$response = array(
									'status' => 'failed',
									'error' => 1,
									'message' => 'Email address does not exists.'
								);
					
				}
			
			} else {
				
				$response = array(
										'status' => 'failed',
										'error' => 1,
										'message' => 'Invalid email address.'
									);
			}
		
		} 
		return $response;
	}
	
	function tms_send_social_login_api_details(){
		
		if(isset($_POST['option_page']) && $_POST['option_page'] == 'tms-settings-group' ){
			
			if ( !current_user_can( 'manage_options' )){
				
				return;
			}
			
			$data = array(
						'site_url' => get_bloginfo('url'),
						'Facebook_enabled' => $_POST['tms_settings_Facebook_enabled'],
						'Facebook_app_id' => sanitize_text_field($_POST['tms_settings_Facebook_app_id']),
						'Facebook_app_secret' => sanitize_text_field($_POST['tms_settings_Facebook_app_secret']),
						'Google_enabled' => $_POST['tms_settings_Google_enabled'],
						'Google_app_id' => sanitize_text_field($_POST['tms_settings_Google_app_id']),
						'Google_app_secret' => sanitize_text_field($_POST['tms_settings_Google_app_secret']),
						'Twitter_enabled' => $_POST['tms_settings_Twitter_enabled'],
						'Twitter_app_key' => sanitize_text_field($_POST['tms_settings_Twitter_app_key']),
						'Twitter_app_secret' => sanitize_text_field($_POST['tms_settings_Twitter_app_secret']),
					); 
					
			$data = http_build_query($data);
			$url='http://thetmstore.com/tmadmin/socialLoginDetails.php';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url );
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($ch );
			curl_close ($ch);
		}
		
	}
	
	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	function test()
	{
	
	}
	
	function setAddress()
	{
    global $woocommerce;
    $woocommerce->customer->set_shipping_postcode( 12345 );
    $woocommerce->customer->set_postcode( 12345 );

    //get it
    $woocommerce->customer->get_shipping_postcode();    
    $woocommerce->customer->get_postcode();
	}
	
}