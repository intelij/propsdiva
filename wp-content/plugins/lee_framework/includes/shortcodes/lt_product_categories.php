<?php
add_shortcode("lt_product_categories", "lt_sc_product_categories");
function lt_sc_product_categories($atts, $content = null) {
    global $delay_animation_product;
    $delay_animation_product = 200;
    extract( shortcode_atts( array (
        'number'        => '5',
        'title'         => '',
        'orderby'       => 'name',
        'order'         => 'ASC',
        'hide_empty'    => 1,
        'parent'        => '0',
        'infinitive'    => 'false',
        'columns_number' => '4',
        'columns_number_small' => '1',
        'columns_number_tablet' => '3'
    ), $atts ) );
    if ( isset( $atts[ 'ids' ] ) ) {
        $ids = explode( ',', $atts[ 'ids' ] );
        $ids = array_map( 'trim', $ids );
    } else {
        $ids = array();
    }

    $hide_empty = ( $hide_empty == true || $hide_empty == 1 ) ? 1 : 0;

    $args = array(
        'orderby'    => $orderby,
        'order'      => $order,
        'hide_empty' => $hide_empty,
        'include'    => $ids,
        'pad_counts' => true,
        'child_of'   => $parent
    );

    $product_categories = get_terms( 'product_cat', $args );

    if ( $parent !== "" )
        $product_categories = wp_list_filter( $product_categories, array( 'parent' => $parent ) );

    if ( $number )
        $product_categories = array_slice( $product_categories, 0, $number );
    if (isset($woocommerce_loop['columns'])){
        $woocommerce_loop['columns'] = $columns;
    }
    ob_start();
    if(function_exists('wc_print_notices')) :
        if($title):?> 
            <div class="row">
                <div class="large-12 columns">
                    <h3 class="section-title"><span><?php echo esc_attr($title); ?></span></h3>
                    <div class="bery-hr medium"></div>
                </div>
            </div>
        <?php endif; ?>
        <div class="group-slider category-slider">
            <div class="lt-slider products-group owl-carousel" data-autoplay="false" data-loop="true" data-disable-nav="true" data-columns="<?php echo esc_attr($columns_number);?>" data-columns-small="<?php echo esc_attr($columns_number_small); ?>" data-columns-tablet="<?php echo esc_attr($columns_number_tablet); ?>" data-margin="10">
                <?php
                if ( $product_categories ) :
                    foreach ( $product_categories as $category ) :
						/*if (!$thumbnail_id = get_woocommerce_term_meta($category->term_id, 'thumbnail_id', true)){
							continue;
						}*/
                        woocommerce_get_template( 'content-product_cat.php', array( 'category' => $category ) );
                        $delay_animation_product += 200; 
                    endforeach;
                endif;
                woocommerce_reset_loop();  
                ?>
            </div> 
        </div>
    <?php endif;
    
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}