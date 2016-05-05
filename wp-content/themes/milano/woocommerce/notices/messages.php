<?php


if ( ! defined( 'ABSPATH' ) ) exit; 
if ( ! $messages ) return;
?>
<?php foreach ( $messages as $message ) : ?>
	<div class="row">
	<div class="large-12 columns">
		<div class="woocommerce-message">
			<?php echo wp_kses_post( $message ); ?>
		</div>
	</div>
	</div>
<?php endforeach; ?>
