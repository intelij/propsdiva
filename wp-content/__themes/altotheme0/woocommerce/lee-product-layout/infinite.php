<?php 
    $_delay = 0;
    $_delay_item = (isset($lt_opt['delay_overlay']) && (int) $lt_opt['delay_overlay']) ? (int) $lt_opt['delay_overlay'] : 100;
    $_count = 1;
    $infinite_id = rand();
?>

<div class="row">
    <ul class="<?php echo esc_attr($class_column);?> products-infinite products-group shortcode_<?php echo $infinite_id;?>" data-next-page="2" data-product-type="<?php echo $type; ?>" data-post-per-page="<?php echo $number; ?>" data-is-deals="<?php echo $is_deals; ?>" data-max-pages="<?php echo $loop->max_num_pages; ?>" data-cat="<?php echo esc_attr($cat);?>">
        <?php while ( $loop->have_posts() ) : $loop->the_post();
            
            $class_fix = '';
            // Store loop count we're currently on
            if ( 0 == ( $_count - 1 ) % $columns_number || 1 == $columns_number )
                $class_fix .= ' first';
            if ( 0 == $_count % $columns_number )
                $class_fix .= ' last';
            
            //Product Item
            wc_get_template( 'content-product.php', array('is_deals' => $is_deals, '_delay' => $_delay, '_delay_item' => $_delay_item, 'wrapper' => 'li') );
            $_delay += $_delay_item;
            //End Product Item
            
            if($_count == $columns_number){
                $_count=0;
                $_delay = 0;
            }
            $_count++;
            
        endwhile; ?>
    </ul>
    <div class="large-12 columns text-center">
        <?php if ($loop->max_num_pages > 1) {?>
            <div class="load-more-btn load-more <?php echo $infinite_id; ?>" data-infinite="<?php echo $infinite_id;?>"><span><?php esc_html_e('Load More','altotheme'); ?></span><span class="load-more-icon fa fa-angle-double-down"></span></div>
        <?php } ?>
    </div>
</div>