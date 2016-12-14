<?php
/**
 * Related Products
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop, $lt_opt;

$_delay = 0;
$_delay_item = (isset($lt_opt['delay_overlay']) && (int) $lt_opt['delay_overlay']) ? (int) $lt_opt['delay_overlay'] : 100;
$_count = 1;

if(sizeof($related = $product->get_related(12)) == 0) return;

$args = apply_filters('woocommerce_related_products_args', array(
	'post_type'				=> 'product',
	'ignore_sticky_posts'	=> 1,
	'no_found_rows' 		=> 1,
	'orderby' 				=> $orderby,
	'post__in' 				=> $related,
	'post__not_in'			=> array($product->id)
) );

$products = new WP_Query($args);

if ($products->have_posts()):?>
	<div class="related products">
		<div class="row">
			<div class="large-12 columns">
				<div class="title-block">
					<h5 class="heading-title"><span><?php esc_html_e('Related Products', 'altotheme'); ?></span></h5>
					<div class="bery-hr medium"></div>
				</div>
			</div>
		</div>
		<div class="row group-slider">
            <div class="lt-slider owl-carousel products-group" data-columns="4" data-columns-small="1" data-columns-tablet="3">
                <?php while ($products->have_posts()):
                    $products->the_post();
                    // Product Item -->
                    wc_get_template('content-product.php', array('_delay' => $_delay, 'wrapper' => 'div'));
                    // End Product Item -->
                    $_delay += $_delay_item;
                endwhile;?>
            </div>  
   		</div> 
	</div>
<?php endif;

wp_reset_postdata();
