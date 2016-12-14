<?php
/**
 * Product loop sale flash
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product, $lt_opt, $wc_cpdf; 
?>

<?php if ( $wc_cpdf->get_value(get_the_ID(), '_bubble_hot')):?>
    <div class="badge">
        <div class="badge-inner hot-label">
            <div class="inner-text">
                <?php echo $wc_cpdf->get_value(get_the_ID(), '_bubble_hot');?>
            </div>
        </div>
    </div>
<?php endif;?>

<?php if ($product->is_on_sale()): ?>
    <?php if ($product->product_type == 'variable') : ?>
        <div class="badge">
            <div class="badge-inner sale-label">
                <div class="inner-text">
                    <?php 
                    $price = '';	
                    $available_variations = $product->get_available_variations();								
                    $maximumper = 0;
                    for ($i = 0; $i < count($available_variations); ++$i) {
                        $variation_id=$available_variations[$i]['variation_id'];
                        $variable_product1= new WC_Product_Variation( $variation_id );
                        $regular_price = $variable_product1->regular_price;
                        $sales_price = $variable_product1->sale_price;
                        $percentage= round((( ( $regular_price - $sales_price ) / $regular_price ) * 100),0) ;
                        if ($percentage > $maximumper) {
                            $maximumper = $percentage;
                        }
                    }
                    echo '-'.$price . sprintf( esc_html__( '%s', 'altotheme' ), $maximumper . '%' ); 
                    ?>
                </div>
            </div>
        </div>
    <?php elseif($product->product_type == 'simple'): ?>
        <div class="badge">
            <div class="badge-inner sale-label">
                <div class="inner-text"><?php echo apply_filters('woocommerce_sale_flash', esc_html__( 'Sale', 'altotheme' ), $post, $product); ?></div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>