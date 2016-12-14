<?php
if($enable_grid):
    if($type_grid == ''){
        $type_grid = 'best_selling';
    }
    
    $product_grid = lt_getProductGrid($id, $catids, $type_grid);
    
    $_delay_item = (isset($lt_opt['delay_overlay']) && (int) $lt_opt['delay_overlay']) ? (int) $lt_opt['delay_overlay'] : 100;
?>
<div class="row">
    <div class="large-5 columns">
<?php endif;?>
        <div class="lt-sc-pdeal lt-sc-pdeal-block wow fadeInUp animated" data-wow-duration="1s" data-wow-delay="<?php echo $_delay_item;?>ms" data-id="<?php echo $_id;?>">
            <div class="lt-sc-p-img">
                <?php if($time_sale):?>
                    <div class="lt-sc-pdeal-countdown">
                        <span class="countdown" data-fomart="dhms" data-countdown="<?php echo esc_attr(date('M j Y H:i:s O',$time_sale));?>"></span>
                    </div>
                <?php endif?>
                <div class="product-images-slider images-popups-gallery">
                    <div class="main-images-<?php echo $_id;?> owl-carousel">
                        <?php if($image_pri):?>
                            <a href="<?php echo esc_url($link);?>" title="<?php echo esc_attr($title);?>" class="woocommerce-additional-image product-image">
                                <img class="lt-pri-img lt-pri-<?php echo $_id;?> lazyOwl" src="<?php echo esc_attr($image_pri['link']);?>" alt="<?php echo esc_attr($title);?>" />
                            </a>
                        <?php endif;?>
                        <?php
                        if($count_imgs) :
                            foreach($img_disp as $key => $img): ?>
				<a href="<?php echo esc_url($link);?>" title="<?php echo esc_attr($title);?>" class="woocommerce-additional-image product-image">
				    <img class="lt-pri-img lt-pri-<?php echo $_id;?> lazyOwl" src="<?php echo esc_attr($img['link']);?>" alt="<?php echo esc_attr($title);?>" />
				</a>
                            <?php endforeach;
                        else :
                            echo sprintf('<a href="%s" class="active-thumbnail"><img src="%s" /></a>', wc_placeholder_img_src(), wc_placeholder_img_src());
                        endif;?>
                    </div>
                </div>
                <?php if($thumbs_absolute):
                    echo $thumbs;
                endif;?>
            </div>
            <?php if(!$thumbs_absolute):
                echo $thumbs;
            endif;?>
            <div class="lt-sc-p-info<?php echo ($thumbs_absolute) ? ' '.esc_attr('has_absolute_thumbs') : '';?>">
                <div class="row">
                    <div class="large-8 columns left">
                        <h3>
                            <a href="<?php echo esc_url($link);?>" title="<?php echo esc_attr($title);?>">
                                <?php echo $title;?>
                            </a>
                        </h3>
                        <div class="lt-sc-p-price"><?php echo $product->get_price_html();?></div>
                    </div>
                    <div class="large-4 columns right">
                        <?php lt_add_to_cart_button_sc($product);?>
                    </div>
                </div>
            </div>
        </div>
<?php if($enable_grid):?>
    </div>
    <div class="large-7 columns lt-sc-product-deals-grid">
        <?php // Content Products grid; ?>
        <?php if ( $product_grid->have_posts() ) :
            $_total = $product_grid->found_posts; ?>
            <div class="woocommerce">
                <div class="inner-content<?php echo ($thumbs_absolute) ? ' '.esc_attr('has_absolute_thumbs') : '';?>">
                    <?php wc_get_template('lee-product-layout/grid.php', array(
                        'show_rating' => ($type_grid == 'top_rate') ? true : false,
                        '_id' => $_id,
                        'loop' => $product_grid,
                        'columns_number' => 3,
                        'columns_number_small' => 2,
                        'columns_number_tablet' => 2,
                        'class_column' => 'large-block-grid-3 small-block-grid-2',
                        '_total' => $_total,
                        'is_deals' => ($type_grid == 'deals') ? true : false,
                        'type' => $type_grid,
                        'ros_opt' => $lt_opt
                    ));?>
                </div>
            </div>
        <?php endif;?>
    </div>
</div>
<?php endif;?>