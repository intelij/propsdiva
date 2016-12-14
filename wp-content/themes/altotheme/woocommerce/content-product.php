<?php
/**
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 	WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.6.1
 */

global $product, $woocommerce_loop, $lt_opt, $post;
if ( ! $product->is_visible() )
    return;

if (isset($_GET['hover-flip'])){
    $lt_opt['animated_products'] = 'hover-flip';
}elseif (isset($_GET['hover-fade'])){
    $lt_opt['animated_products'] = 'hover-fade';
}elseif (isset($_GET['hover-bottom-to-top'])){
    $lt_opt['animated_products'] = 'hover-bottom-to-top';
}

if(isset($is_deals) && $is_deals) $time_sale = get_post_meta( $product->id, '_sale_price_dates_to', true );
$attachment_ids = $product->get_gallery_attachment_ids();

//$post_id = $post->ID;
$stock_status = get_post_meta($post->ID, '_stock_status',true) == 'outofstock';

$_wrapper = 'div';
if(isset($wrapper) && $wrapper == 'li') 
    $_wrapper = $wrapper;
?>

<?php if (isset($lt_opt['animated_products'])){?>
    <<?php echo $_wrapper.' ';?> class="wow fadeInUp product-item <?php echo $lt_opt['animated_products']; ?> grid1 <?php if($stock_status == "1"){ ?>out-of-stock<?php }?>" data-wow-duration="1s" data-wow-delay="<?php echo esc_attr($_delay);?>ms">
<?php }else{ ?>
    <<?php echo $_wrapper.' ';?> class="product-item <?php echo $lt_opt['animated_products']; ?> grid1 <?php if($stock_status == "1"){ ?>out-of-stock<?php }?>" data-wow-duration="1s" data-wow-delay="<?php echo esc_attr($_delay);?>ms">
<?php } ?>

<?php //do_action( 'woocommerce_before_shop_loop_item' ); ?>

<div class="inner-wrap<?php echo (isset($is_deals) && $is_deals) ? ' product-deals':'';?>">
    <div class="product-img <?php echo (isset($lt_opt['product-hover-overlay']) && $lt_opt['product-hover-overlay']) ? 'hover-overlay' : '' ?>">
        <a href="<?php the_permalink(); ?>">
            <div class="main-img"><?php echo $product->get_image('shop_catalog');?></div>
            <?php
            if ( $attachment_ids ) {
                $loop = 0;				
                foreach ( $attachment_ids as $attachment_id ) {
                    $image_link = wp_get_attachment_url( $attachment_id );
                    if ( ! $image_link )
                        continue;
                    $loop++;
                    printf( '<div class="back-img back">%s</div>', wp_get_attachment_image( $attachment_id, 'shop_catalog' ) );
                    if ($loop == 1) break;
                }
            } else {?>
                <div class="back-img"><?php echo $product->get_image('shop_catalog'); ?></div>
            <?php }?>
            <?php if(isset($is_deals) && $is_deals){?><span class="countdown" data-countdown="<?php echo esc_attr(date('M j Y H:i:s O',$time_sale)); ?>"></span><?php }?>
        </a>
        <?php if($stock_status == "1") { ?>
            <div class="out-of-stock-label">
                <div class="text"><?php esc_html_e( 'Sold out', 'altotheme' ); ?></div>
            </div>
        <?php }?>
        <?php woocommerce_get_template( 'loop/sale-flash.php' ); ?>
        
        <!-- Product interactions button-->
        <?php do_action('lt_product_group_button');?>
    </div>

    <div class="info">
        <div class="info_main">
            <p class="name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
            <?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
            <div class="product-des">
                <?php echo lt_limit_words(apply_filters('woocommerce_short_description', str_replace(['<em>', '</em>'], '', $post->post_excerpt)), 50); ?>
            </div> 
        </div>
        <!-- Product interactions button-->
        <?php do_action('lt_product_group_button');?>
    </div>
</div>
</<?php echo $_wrapper;?>>
