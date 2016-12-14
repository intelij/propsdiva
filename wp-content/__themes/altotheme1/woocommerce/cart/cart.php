<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $woocommerce, $lt_opt;
wc_print_notices();
?>

<?php do_action( 'woocommerce_before_cart' ); ?>
<form action="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" method="post">
<div class="row">
<div class="large-12 small-12 columns">

<?php do_action( 'woocommerce_before_cart_table' ); ?>
<?php do_action( 'woocommerce_before_cart_contents' ); ?>
<div class="cart-wrapper">
<table class="shop_table cart responsive">
    <thead>
        <tr>
            <th class="product-name" colspan="3"><?php esc_html_e( 'Product', 'altotheme' ); ?></th>
            <th class="product-price hide-for-small"><?php esc_html_e( 'Price', 'altotheme' ); ?></th>
            <th class="product-quantity"><?php esc_html_e( 'Quantity', 'altotheme' ); ?></th>
            <th class="product-subtotal hide-for-small"><?php esc_html_e( 'Total', 'altotheme' ); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php //do_action( 'woocommerce_before_cart_contents' ); ?>
        <?php
            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                $product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {?>
                    <tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                        <td class="remove-product">
                            <?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s"><span class="icon-close"></span></a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), esc_html__( 'Remove this item', 'altotheme' ) ), $cart_item_key );?>
                        </td>
                        <td class="product-thumbnail">
                        <?php
                            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', str_replace( array( 'http:', 'https:' ), '', $_product->get_image() ), $cart_item, $cart_item_key );
                            if ( ! $_product->is_visible() )
                                echo $thumbnail;
                            else
                                printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
                        ?>
                        </td>

                        <td class="product-name">
                        <?php
                            if ( ! $_product->is_visible() )
                                echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
                            else
                                echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title() ), $cart_item, $cart_item_key );

                            // Meta data
                            echo WC()->cart->get_item_data( $cart_item );
							
							
                            // Backorder notification
                            if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
                                echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'altotheme' ) . '</p>';
                        ?>
                            <div class="mobile-price text-center show-for-small">
                                <?php
                                    echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
                            </div>
                        </td>

                        <td class="product-price hide-for-small">
                            <?php
                                echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                            ?>
                        </td>

                        <td class="product-quantity">
                            <?php
                                if ( $_product->is_sold_individually() ) {
                                    $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                } else {
                                    $product_quantity = woocommerce_quantity_input( array(
                                        'input_name'  => "cart[{$cart_item_key}][qty]",
                                        'input_value' => $cart_item['quantity'],
                                        'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
                                    ), $_product, false );
                                }

                                echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
                            ?>
                        </td>

                        <td class="product-subtotal hide-for-small">
                                <?php
                                        echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
                                ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            do_action( 'woocommerce_cart_contents' );
        ?>

        <?php //do_action( 'woocommerce_after_cart_contents' ); ?>
    </tbody>
</table>

<?php do_action('woocommerce_after_cart_contents'); ?>

</div><!-- .cart-wrapper -->
</div><!-- .large-12 -->
</div><!-- .row -->

<div class="row">
    <div class="large-6 columns">
        <?php //wp_nonce_field( 'woocommerce-cart' ); ?>
        <?php if ( WC()->cart->coupons_enabled() ) { ?>
            <div class="coupon">
                <h5 class="heading-title"><?php esc_html_e( 'Coupon', 'altotheme' ); ?></h5>
                <div class="bery-hr medium  margin-bottom-30 text-left"></div>
                <input type="text" name="coupon_code"  id="coupon_code" value="" placeholder="<?php esc_html_e( 'Enter Coupon', 'altotheme' ); ?>"/> 
                <input type="submit" class="button" name="apply_coupon" value="<?php esc_html_e( 'Apply Coupon', 'altotheme' ); ?>" />
                <?php do_action('woocommerce_cart_coupon'); ?>

            </div>
        <?php } ?>
    <?php //woocommerce_shipping_calculator(); ?>
    <?php //do_action( 'woocommerce_after_cart_table' ); ?>
    </div><!-- .large-6 -->

    <div class="large-6 columns">
        <div class="cart-sidebar">
            <?php woocommerce_cart_totals(); ?>
            <input type="submit" class="button" name="update_cart" value="<?php esc_html_e( 'Update Cart', 'altotheme' ); ?>" /> 
            <input type="submit" class="checkout-button button" name="proceed" value="<?php esc_html_e( 'Proceed to Checkout', 'altotheme' ); ?>" />

            <?php wp_nonce_field( 'woocommerce-cart' ); ?>
        </div><!-- .cart-sidebar -->
    </div><!-- .large-6 -->
</div><!-- .row -->

<?php do_action( 'woocommerce_after_cart_table' ); ?>
<?php do_action('woocommerce_cart_collaterals'); ?>
</form>

<?php do_action( 'woocommerce_after_cart' ); ?>