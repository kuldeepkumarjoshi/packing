<?php

/**
 * Plugin Name: wrapper for custom request
 *
 * Description: Control your WooCommerce custom request.
 * Version: 1.0.0
 * Author: kuldeep
 * Requires at least: wc 2.4
 * Tested up to: wc 2.5
 *
 *
 * @package WooCommerce custom request
 * @category custom
 * @author kuldeep
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define Constants
define('WC_CUSTOM_WRAPPER_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WC_CUSTOM_WRAPPER_PLUGIN_URL', plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__)));
define('WC_CUSTOM_WRAPPER_VERSION', '1.0.0');
define('WC_CUSTOM_WRAPPER_OPTIONS_VERSION', '1');

if (!class_exists('WC_CUSTOM_WRAPPER')) {

    /**
     * Main plugin class
     *
     * @package WooCommerce Dynamic Pricing Pro
     * @author RightPress
     */
    class WC_CUSTOM_WRAPPER
    {
        private static $instance = false;

        /**
         * Singleton control
         */
        public static function get_instance()
        {
            if (!self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * Class constructor
         *
         * @access public
         * @return void
         */
        public function __construct()
        {

            // Activation hook
            register_activation_hook(__FILE__, array($this, 'activate'));

            // Initialize plugin configuration


          // Some hooks need to be attached after init is triggered
            add_action('init', array($this, 'on_init'));
        }

        /**
         * Function hooked to init
         *
         * @access public
         * @return void
         */
        public function on_init()
        {

        }

        public function getCartByAddingItToSession($sessionData){
    //      	error_log('get_cart:: '.print_R($sessionData,TRUE), 3, "var/log/my-errors.log");
            global $woocommerce;
            $basketData = $sessionData['basketData'];
            foreach($basketData as $addedItem){
            //	error_log('get_cart:: '.print_R($woocommerce->cart->get_applied_coupons,TRUE), 3, "var/log/my-errors.log");
              $woocommerce->cart->add_to_cart($addedItem['id'],$addedItem['quantity']);
            }
          //	$appliedCoupon = $woocommerce->cart->get_applied_coupons();
        //		error_log('discount :: '. apply_filters( 'woocommerce_coupon_code', $sessionData['couponCode'] ), 3, "var/log/my-errors.log");

            if($sessionData['couponCode']){
              $cart['couponStatus']='Coupon is not valid.';
               $coupon_code = $sessionData['couponCode'];
               if(!$woocommerce->cart->has_discount( sanitize_text_field($coupon_code ) )){
                 if ($woocommerce->cart->add_discount( sanitize_text_field($coupon_code ))){
                   $cart['couponStatus']='Coupon successfully applied on cart.';
                   $discountAmount = $woocommerce->cart->get_coupon_discount_amount( sanitize_text_field($coupon_code ),false  );
                    $cart['discountAmount'] = round($discountAmount);
                }
              }
            }
            // $my_coupon = $woocommerce->cart->applied_coupons;
    //error_log('get_cart:: '.print_R($my_coupon,TRUE), 3, "var/log/my-errors.log");
            $wcPrinceRule = RP_WCDPD::get_instance();
            $wcPrinceRule->apply_discounts();

            $woocommerce->cart->calculate_totals();
             $items =$woocommerce->cart->get_cart();
             foreach($items as $item){
               $cartItems[] =$item;
             }
            // 	error_log('create_cart:: '.sanitize_text_field( $coupon_code ), 3, "var/log/my-errors.log");
            $totalCartValue = $woocommerce->cart->cart_contents_total+$woocommerce->cart->tax_total;
            $cart['totalCartValue'] =  round($totalCartValue);
            $cart['totalCartHtml'] = $woocommerce->cart->get_cart_total();
            $cart['cartItems'] = $cartItems;
              //	error_log('total amount:: '.$amount, 3, "var/log/my-errors.log");
            return $cart;
        }

        public function addToCartWithSession( $data ) {
      			//error_log('get_cart:: '.$data['id'].'quantity::'.$data['quantity'], 3, "var/log/my-errors.log");
      			error_log('get_cart:: ', 3, "var/log/my-errors.log");
      			$session = $data['session'];

      		 session_start();
      		 if(isset($session)){
      			//  if(isset($session.get_current_user())){
      			// 	 		if(isset($session.get_cart())){
      			// 				addItemToCart($session);
      			// 			}else{
      			// 				createCart($session);
      			// 				addItemToCart($session);
      			// 			}
      			//  }else{
      			// 	 createUser($session);
      			// 	 createCart($session);
      			// 	 addItemToCart($session);
      			//  }
      		 }else{
      			 $session = createSession($data);
      			 createUser($session);
      			 createCart($session);
      			 addItemToCart($session);
      		 }
           return $session;
      	}

      	public function addItemToCart( $data ) {
      				global $woocommerce;
      		$woocommerce->cart->add_to_cart($data['id'],$data['quantity']);
          return $data;
      	}
      	public function createCart( $data ) {
          return $data;
      	}
      	public function createUser( $data ) {
          return $data;
      	}
      	public function createSession( $data ) {
          return $data;
      	}

}
}
