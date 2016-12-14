<?php

/**
 * Show messages
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; 

if ( ! $messages ) return;
global $lt_opt;

?>

<?php foreach ( $messages as $message ) : ?>
	<div class="row">
		<div class="large-12 columns">
			<div class="woocommerce-message message-success">
				<?php echo wp_kses_post( $message ); ?>
				<?php if (is_product()) { ?>  
				    <script>
				    jQuery('.woocommerce-message a').remove();
				    jQuery('.mini-cart').addClass('active cart-active');
				    jQuery('#main-content').click(function(){ jQuery('.mini-cart').removeClass('active cart-active');});
				    jQuery('.mini-cart').hover(function(){jQuery('.cart-active').removeClass('cart-active');});
				    setTimeout(function(){jQuery('.cart-active').removeClass('active')}, 6000);
				    </script>
				<?php } ?>
			</div>
		</div>
	</div>
<?php endforeach; ?>