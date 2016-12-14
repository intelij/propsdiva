<?php
/**
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit;
global $lt_opt;

if (isset($_GET['product-right-sidebar'])){
    $lt_opt['product_sidebar'] = 'right_sidebar';
}elseif (isset($_GET['product-left-sidebar'])){
    $lt_opt['product_sidebar'] = 'left_sidebar';
}elseif (isset($_GET['product-no-sidebar'])){
    $lt_opt['product_sidebar'] = 'no_sidebar';
}

get_header('shop');?>
<div class="product-details-bread">
    <?php lt_get_breadcrumb(); ?>
    <div class="row">
        <div class="large-12 columns">
            <div class="products-arrow">
                <?php do_action('next_prev_product');?>
            </div>
        </div>
    </div>
</div>

<div class="row product-page">
    <div class="large-12 columns">
        <?php do_action('woocommerce_before_main_content'); ?>
        <?php while ( have_posts() ) : the_post(); ?>
            <?php 
            if($lt_opt['product_sidebar'] == "right_sidebar") {
                woocommerce_get_template_part( 'content', 'single-product-right-sidebar'); 
            } else if($lt_opt['product_sidebar'] == "left_sidebar") {
                woocommerce_get_template_part( 'content', 'single-product-left-sidebar'); 
            } else if($lt_opt['product_sidebar'] == "no_sidebar"){
                woocommerce_get_template_part( 'content', 'single-product' ); 
            }
            ?>
        <?php endwhile;?>
        <?php do_action('woocommerce_after_main_content'); ?>
    </div>
</div>

<?php get_footer('shop'); ?>