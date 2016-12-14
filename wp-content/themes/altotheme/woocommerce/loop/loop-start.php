<?php
/**
 * Product Loop Start
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
global $lt_opt, $woocommerce_loop, $products_per_row;
$list_class = isset($_COOKIE['gridcookie']) ? ' '.$_COOKIE['gridcookie'] : '';
if(is_cart()){
    $woocommerce_loop['columns'] = 4;
} 
if($woocommerce_loop['columns'] == "") {$woocommerce_loop['columns'] = $lt_opt['products_per_row'];}

/* Add 1 column if no sidebar */
if ($lt_opt['category_sidebar'] == "no-sidebar") {
    $products_per_row = $lt_opt['products_per_row'] + 1;
    $woocommerce_loop['columns'] = $woocommerce_loop['columns'] + 1;
}
?>
<div class="row"><div class="large-12 columns lt-content-page-products">
<?php if(!empty($woocommerce_loop)): ?>
    <ul class="products<?php echo esc_attr($list_class);?> thumb small-block-grid-2 medium-block-grid-3 large-block-grid-<?php echo $woocommerce_loop["columns"];?>" data-product-per-row="<?php echo (int)$woocommerce_loop['columns'];?>">
<?php elseif (isset($lt_opt['products_per_row'])): ?>
    <ul class="products<?php echo esc_attr($list_class);?> thumb small-block-grid-2 large-block-grid-<?php echo (int)$products_per_row;?>" data-product-per-row="<?php echo (int)$products_per_row; ?>">
<?php else : ?>
    <ul class="products<?php echo esc_attr($list_class);?> thumb small-block-grid-2 large-block-grid-3" data-product-per-row="3">
<?php endif; ?>
