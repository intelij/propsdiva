<?php
/**
 * Simple product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $product, $lt_opt;

if ( ! $product->is_purchasable() ) return;

$head_type = $lt_opt['header-type'];
if (isset($_POST['head_type'])){
    $head_type = $_POST['head_type'];
}
?>

<?php
// Availability
$availability = $product->get_availability();

if ($availability['availability']) :
    echo apply_filters( 'woocommerce_stock_html', '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html($availability['availability']) . '</p>', $availability['availability'] );
endif;
?>

<?php if ( $product->is_in_stock() ) : ?>
    <?php do_action('woocommerce_before_add_to_cart_form'); ?>
    <form class="cart" method="post" enctype='multipart/form-data' id="lt_form_add_product_<?php echo esc_attr( $product->id );?>" data-type="single">
        <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
        <?php
        if ( ! $product->is_sold_individually() )
            woocommerce_quantity_input( array(
                'min_value' => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
                'max_value' => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product )
            ) );
        ?>

        <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id );?>" />
        <button type="submit" class="single_add_to_cart_button lt_add_to_cart lt_add_to_cart_single button" data-product_id="<?php echo esc_attr( $product->id );?>" data-head_type="<?php echo esc_attr($head_type);?>"><?php echo $product->single_add_to_cart_text(); ?></button>

        <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
    </form>

    <?php do_action('woocommerce_after_add_to_cart_form'); ?>
<?php endif; ?>