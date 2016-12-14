<div class="product_list_widget <?php echo esc_attr($class_column);?>">
    <?php
    $_delay = 0;
    $_delay_item = (isset($lt_opt['delay_overlay']) && (int) $lt_opt['delay_overlay']) ? (int) $lt_opt['delay_overlay'] : 100;
    ?>
    <?php while ( $loop->have_posts() ) : 
        $loop->the_post();
        global $product;

        wc_get_template(
            'content-widget-product.php', 
            array( 
                //'show_rating' => $show_rating,
                'show_category'=> true,
                'is_animate' => true,
                'wapper' => 'div',
                'delay' => $_delay,
                '_delay_item' => $_delay_item
            ) 
        ); ?>
        <?php $_delay += $_delay_item; ?>
    <?php endwhile; ?>
</div>