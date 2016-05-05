<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $ros_opt;

wc_print_notices();

?>


<div class="row">
<div class="large-12 columns">

		<?php do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}

// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', $woocommerce->cart->get_checkout_url() ); ?>


<!-- LOGIN -->
<?php	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( is_user_logged_in()  || ! $checkout->enable_signup ) {} else {
$info_message = apply_filters( 'woocommerce_checkout_login_message', __( 'Returning customer?', 'woocommerce' ) );
?>

<p class="woocommerce-info"><?php echo esc_html( $info_message ); ?> <a href="#" class="showlogin"><?php _e( 'Click here to login', 'woocommerce' ); ?></a></p>

<?php
	woocommerce_login_form(
		array(
			'message'  => __( 'If you have shopped with us before, please enter your details in the boxes below. If you are a new customer please proceed to the Billing &amp; Shipping section.', 'woocommerce' ),
			'redirect' => get_permalink( woocommerce_get_page_id( 'checkout') ),
			'hidden'   => true
		)
	);

}
?>


</div><!-- .large-12 -->
</div>


	<form name="checkout" method="post" class="checkout" action="<?php echo esc_url( $get_checkout_url ); ?>">
	
	<div class="row">
		<div id="customer_details" class="large-6 columns">
			<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>
				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
			<div class="checkout-group woo-billing">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>
		</div><!-- .large-6 -->
		<div class="large-6 columns">
			<div class="checkout-group woo-shipping">
				 <?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>
		</div><!-- .large-6 -->
	</div><!-- .row -->
	<div class="row">
		<div class="large-12 columns">
			<div class="order-review">
				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
				<h3 id="order_review_heading"><?php _e( 'Your order', 'woocommerce' ); ?></h3>
			</div>
		</div><!-- .large-12 -->
	</div><!-- .row -->
			<?php endif; ?>

			<?php do_action( 'woocommerce_checkout_order_review' ); ?>

    </form><!-- .checkout -->
    <?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>



