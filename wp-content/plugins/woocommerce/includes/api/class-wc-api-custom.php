<?php
/**
 * WooCommerce API custom Class
 *
 * Handles requests to the /custom endpoint
 *
 * @author      WooThemes
 * @category    API
 * @package     WooCommerce/API
 * @since       2.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WC_API_Custom extends WC_API_Resource {

	/** @var string $base the route base */
	protected $base = '/custom';

	public function register_routes( $routes ) {

		# GET/POST /cart
		$routes[ $this->base.'/cart' ] = array(
			array( array( $this, 'get_cart' ), WC_API_Server::READABLE ),
			array( array( $this, 'create_cart' ), WC_API_SERVER::CREATABLE | WC_API_Server::ACCEPT_DATA ),
		);

# GET/POST /custom/loginAuthentication
		$routes[ $this->base.'/loginAuthentication' ] = array(
			array( array( $this, 'check_login' ), WC_API_SERVER::CREATABLE | WC_API_Server::ACCEPT_DATA  ),
		);

		return $routes;
	}

	public function check_login($data){
		try{

					// 	$customer = get_user_by( 'email',  $data['username'] );
					// 	if ( ! is_object( $customer ) ) {
					// 		throw new WC_API_Exception( 'woocommerce_api_invalid_customer_email', __( 'Invalid customer Email', 'woocommerce' ), 404 );
					// 	}
					// }
//
					$user = wp_authenticate( $data['username'], $data['password'] );

					if($user->id){
					//	error_log('$user kid found:: ', 3, "var/log/my-errors.log");
						$customer = $this->get_customer($user);
					}else{
						$customer = $user;
					}
						return $customer;
				} catch ( WC_API_Exception $e ) {
					return new WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
				}
	}


	/**
	 * Create a new create_cart.
	 *
	 * @since 2.2
	 * @param array $data posted data
	 * @return array
	 */
	public function create_cart( $data ) {
		try{
			$sessionData =$data;
			$wcCustomWrapper =WC_CUSTOM_WRAPPER::get_instance();
			$cart = $wcCustomWrapper->getCartByAddingItToSession($sessionData);
			return $cart;
		} catch ( WC_API_Exception $e ) {
				return new WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
		}
	}

	/**
	 * Get the customer for the given ID
	 *
	 * @since 2.1
	 * @param int $id the customer ID
	 * @param array $fields
	 * @return array
	 */
	public function get_customer( $customer ) {
		global $wpdb;
		// Get info about user's last order
		$last_order = $wpdb->get_row( "SELECT id, post_date_gmt
						FROM $wpdb->posts AS posts
						LEFT JOIN {$wpdb->postmeta} AS meta on posts.ID = meta.post_id
						WHERE meta.meta_key = '_customer_user'
						AND   meta.meta_value = {$customer->ID}
						AND   posts.post_type = 'shop_order'
						AND   posts.post_status IN ( '" . implode( "','", array_keys( wc_get_order_statuses() ) ) . "' )
						ORDER BY posts.ID DESC
					" );

		$customer_data = array(
			'id'               => $customer->ID,
			'email'            => $customer->user_email,
			'first_name'       => $customer->first_name,
			'last_name'        => $customer->last_name,
			'username'         => $customer->user_login,
			'role'             => $customer->roles[0],
			'last_order_id'    => is_object( $last_order ) ? $last_order->id : null,
			'orders_count'     => wc_get_customer_order_count( $customer->ID ),
			'total_spent'      => wc_format_decimal( wc_get_customer_total_spent( $customer->ID ), 2 ),
			'billing_address'  => array(
				'first_name' => $customer->billing_first_name,
				'last_name'  => $customer->billing_last_name,
				'company'    => $customer->billing_company,
				'address_1'  => $customer->billing_address_1,
				'address_2'  => $customer->billing_address_2,
				'city'       => $customer->billing_city,
				'state'      => $customer->billing_state,
				'postcode'   => $customer->billing_postcode,
				'country'    => $customer->billing_country,
				'email'      => $customer->billing_email,
				'phone'      => $customer->billing_phone,
			),
			'shipping_address' => array(
				'first_name' => $customer->shipping_first_name,
				'last_name'  => $customer->shipping_last_name,
				'company'    => $customer->shipping_company,
				'address_1'  => $customer->shipping_address_1,
				'address_2'  => $customer->shipping_address_2,
				'city'       => $customer->shipping_city,
				'state'      => $customer->shipping_state,
				'postcode'   => $customer->shipping_postcode,
				'country'    => $customer->shipping_country,
			),
		);
		return array( 'customer' => apply_filters( 'woocommerce_api_customer_response', $customer_data, $customer, $fields, $this->server ) );
	}
}
