<?php
function lt_sc_products( $atts, $content = null ){
    global $woocommerce, $lt_opt;
    
    if(!$woocommerce) 
        return '';
    
    extract( shortcode_atts( array(
        'number'=> '8',
        'icon' => '',
        'el_class' => '',
        'cat' => '',
        'type' => 'best_selling',
        'style' => 'grid',
        'columns_number' => '4',
        'columns_number_small' => '1',
        'columns_number_tablet' => '3',
        ), $atts ) 
    );
    ob_start();
    if($type=='') return;
  	
    switch ($columns_number) {
        case '5':
            $class_column='large-block-grid-5 medium-block-grid-3 small-block-grid-2';
            break;
        case '4':
            $class_column='large-block-grid-4 medium-block-grid-3 small-block-grid-2';
            break;
        case '3':
            $class_column='large-block-grid-3 small-block-grid-2';
            break;
        case '2':
            $class_column='large-block-grid-2 small-block-grid-2';
            break;
        default:
            $class_column='large-block-grid-1 small-block-grid-1';
            break;
    }

    $_id = rand();
    $_count = 1;
    $show_rating = $is_deals = false;
    if($type == 'top_rate') $show_rating = true;
    if($type == 'deals') $is_deals = true;
    $loop = lt_woocommerce_query($type, $number, $cat);
    if ( $loop->have_posts() ) :
        $_total = $loop->found_posts; ?>
        <div class="woocommerce<?php echo ($el_class != '') ? ' ' . $el_class : ''; ?>">
            <div class="inner-content">
                <?php wc_get_template('lee-product-layout/'.$style.'.php', array(
                    'show_rating' => $show_rating,
                    '_id' => $_id,
                    'loop' => $loop,
                    'columns_number' => $columns_number,
                    'columns_number_small' => $columns_number_small,
                    'columns_number_tablet' => $columns_number_tablet,
                    'class_column' => $class_column,
                    '_total' => $_total,
                    'number' => $number,
                    'is_deals' => $is_deals,
                    'type' => $type,
                    'cat' => $cat,
                    'ros_opt' => $lt_opt
                ));?>
            </div>
        </div>
    <?php endif;
    wp_reset_postdata();
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode('lee_products', 'lt_sc_products');

function moreProduct(){
    global $lt_opt;
    $_delay = 0;
    $_delay_item = (isset($lt_opt['delay_overlay']) && (int) $lt_opt['delay_overlay']) ? (int) $lt_opt['delay_overlay'] : 100;
    $type = $_POST['type'];
    $post_per_page = $_POST['post_per_page'];
    $page = $_POST['page'];
    $cat = (isset($_POST['cat']) && (int)$_POST['cat']) ? (int)$_POST['cat'] : null;
    $is_deals = $_POST['is_deals'];
    ob_start();
    
    if ( in_array('yith-woocommerce-compare/init.php', apply_filters('active_plugins', get_option('active_plugins')))){
        // Contruct compare shortcode
        new YITH_Woocompare_Frontend();
    }
    
    $loop = lt_woocommerce_query($type, $post_per_page, $cat, $page);
    if ($loop->have_posts()) :
        if($_total = $loop->found_posts):
            while ($loop->have_posts()):
                $loop->the_post();
                wc_get_template('content-product.php', array(
                    'is_deals' => $is_deals, 
                    '_delay' => $_delay, 
                    'wrapper' => 'li'
                ));
                $_delay += $_delay_item;
            endwhile; 
        endif;
    endif;
    wp_reset_postdata();
    $output = ob_get_contents();
    ob_end_clean();
    echo $output;
    die();
}

add_action( 'wp_ajax_moreProduct', 'moreProduct' );
add_action( 'wp_ajax_nopriv_moreProduct', 'moreProduct' );