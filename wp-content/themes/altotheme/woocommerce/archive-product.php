<?php
/**
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $lt_opt, $wp_query;

$_delay = 0;
$_delay_item = (isset($lt_opt['delay_overlay']) && (int) $lt_opt['delay_overlay']) ? (int) $lt_opt['delay_overlay'] : 100;
$_count = 1;

$cat = $wp_query->get_queried_object();

if (isset($_GET['right'])){
    $lt_opt['category_sidebar'] = 'right-sidebar';
}
if (isset($_GET['no-sidebar'])){
    $lt_opt['category_sidebar'] = 'no-sidebar';
}
$typelist = (isset($_COOKIE['gridcookie']) && $_COOKIE['gridcookie'] == 'list') ? true : false;

$hasSidebar = true;
switch ($lt_opt['category_sidebar']):
    case 'right-sidebar':
        $attr = 'class="large-9 columns left"';
        break;
    
    case 'left-sidebar':
        $attr = 'class="large-9 columns right"';
        break;
    
    default :
        $hasSidebar = false;
        $attr = 'class="large-12 columns no-sidebar"';
        break;
endswitch;

get_header('shop');
lt_get_breadcrumb(); ?>

<div class="cat-header">
    <?php if(isset($lt_opt['cat_bg']) && $lt_opt['cat_bg'] != ''):
        if($wp_query->query_vars['paged'] == 1 || $wp_query->query_vars['paged'] < 1):
            echo do_shortcode($lt_opt['cat_bg']);
        endif;
    endif;?>
</div>
<div class="row category-page">
<?php do_action('woocommerce_before_main_content');?>
<div <?php echo $attr;?>>
    <div class="row filters-container">
        <div class="large-6 columns">
            <ul class="filter-tabs">
                <li class="productGrid<?php echo (!$typelist) ? ' active' : '';?>">
                    <i class="fa fa-th"></i>
                </li>
                <li class="productList<?php echo ($typelist) ? ' active' : '';?>">
                    <i class="fa fa-th-list"></i>
                </li>
            </ul>
        </div>
        <div class="large-6 columns">
            <ul class="sort-bar">
                <?php if($hasSidebar):?>
                    <li class="li-toggle-sidebar">
                        <a class="toggle-sidebar" href="javascript:void(0);">
                            <i class="icon-menu"></i> <?php esc_html_e('Sidebar', 'altotheme');?>
                        </a>
                    </li>
                <?php endif;?>
                <li class="sort-bar-text"></li>
                <li class="lt-filter-order filter-order"><?php do_action( 'woocommerce_before_shop_loop' );?></li>
            </ul>
        </div>
    </div>

    <?php do_action( 'woocommerce_archive_description' ); ?>
    
    <?php woocommerce_product_loop_start(); ?>
        <?php if ( have_posts() ) : ?>
            <?php woocommerce_product_subcategories(); ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <!-- Product Item -->
                <?php wc_get_template('content-product.php', array('_delay' => $_delay, 'wrapper' => 'li'));?>
                <!-- End Product Item -->
                <?php $_delay += $_delay_item; ?>
            <?php endwhile;?>
        <?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>
            <li class="row"><?php woocommerce_get_template( 'loop/no-products-found.php' ); ?></li>
        <?php endif; ?>
    <?php woocommerce_product_loop_end(); ?>
    <div class="row filters-container-down">
        <!-- Pagination -->
        <?php do_action( 'woocommerce_after_shop_loop' );?>
        <!-- End Pagination -->
    </div>
    
<?php do_action('woocommerce_after_main_content');?>
</div>

<?php if($lt_opt['category_sidebar'] == 'right-sidebar') :?>
    <div class="large-3 right columns col-sidebar">
        <?php if (is_active_sidebar('shop-sidebar')) : dynamic_sidebar('shop-sidebar'); endif;?>
    </div>
<?php elseif ($lt_opt['category_sidebar'] == 'left-sidebar') :?>
    <div class="large-3 left columns col-sidebar">
        <?php if (is_active_sidebar('shop-sidebar')) : dynamic_sidebar('shop-sidebar'); endif;?>
    </div>
<?php endif;?>

</div>

<div class="lt-has-filter-ajax hidden-tag">
    <div class="current-cat hidden-tag">
        <a data-id="<?php echo isset($cat->term_id) ? (int)$cat->term_id : '';?>" href="<?php echo isset($cat->term_id) ? esc_url(get_term_link((int)$cat->term_id, 'product_cat')) : '';?>" class="lt-filter-by-cat" id="lt-hidden-current-cat"></a>
    </div>
    <p><?php esc_html_e('No products were found matching your selection.', 'altotheme');?></p>
    <?php if($s = get_search_query()):?>
        <input type="hidden" name="lt_hasSearch" id="lt_hasSearch" value="<?php echo esc_attr($s);?>" />
    <?php endif;?>
</div>

<?php get_footer('shop'); ?>