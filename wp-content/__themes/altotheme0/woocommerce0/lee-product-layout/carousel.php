<?php 
$_delay = 0;
$_delay_item = (isset($lt_opt['delay_overlay']) && (int) $lt_opt['delay_overlay']) ? (int) $lt_opt['delay_overlay'] : 100;
?>
<div class="row group-slider">
    <div class="slider products-group lt-slider owl-carousel" data-columns="<?php echo esc_attr($columns_number);?>" data-columns-small="<?php echo esc_attr($columns_number_small); ?>" data-columns-tablet="<?php echo esc_attr($columns_number_tablet); ?>">
        <?php while ( $loop->have_posts() ) : $loop->the_post(); 
            global $product;
            wc_get_template( 'content-product.php', array(
                'is_deals' => $is_deals, 
                '_delay' => $_delay, 
                '_delay_item' => $_delay_item
            ) );
                
            $_delay += $_delay_item;
        endwhile; ?>
    </div>
</div>
