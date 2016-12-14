<?php
    $_delay = 0;
    $_delay_item = (isset($lt_opt['delay_overlay']) && (int) $lt_opt['delay_overlay']) ? (int) $lt_opt['delay_overlay'] : 100;
    $_count = 1;
?>
<div class="row">
    <ul class="<?php echo esc_attr($class_column);?> products-group">
    <?php 
        while ( $loop->have_posts() ) : $loop->the_post();
            $class_fix = '';
            // Store loop count we're currently on
            if ( 0 == ( $_count - 1 ) % $columns_number || 1 == $columns_number )
                $class_fix .= ' first';
            if ( 0 == $_count % $columns_number )
                $class_fix .= ' last';
            
            /* -- Product Item -- */
            wc_get_template( 'content-product.php', array(
                'is_deals' => $is_deals,
                '_delay' => $_delay,
                '_delay_item' => $_delay_item,
                'wrapper' => 'li'
            ));
            $_delay += $_delay_item;
            /* -- End Product Item -- */
            
            if($_count == $columns_number):
                $_count=0;
                $_delay = 0;
            endif;
            $_count++;
        endwhile;
    ?>
    </ul>
</div>