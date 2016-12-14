<?php
function lt_sc_products_deal( $atts, $content = null ){
    global $lt_opt;
    extract( shortcode_atts( array(
        'id' => '',
        'layout' => 'block',
        'thumbs_absolute' => false,
        'enable_grid' => true,
        'type_grid' => 'best_selling',
        'banner_src' => '',
        'banner_height' => '',
        'position_top' => '10%',
        'position_left' => '',
        'position_right' => '',
        'text_align' => 'left',
        'el_class' => ''
        ), $atts ) 
    );
    
    if(!in_array($layout, array('block', 'full'))){
        $layout = 'block';
    }
    
    $_id = rand();
    $product = lt_getProductDeals($id);
    $catids = array();
    
    ob_start();
    if ($product && $product->is_visible()) :
        $time_sale = get_post_meta( $product->id, '_sale_price_dates_to', true );
        
        $attachment_ids = $product->get_gallery_attachment_ids();
        $count_imgs = count($attachment_ids);
        $img_thumbs = $img_disp = [];
        $thumbs = '';
        $title = $product->get_title();
        $link = get_permalink($product->id);
        
        $image_pri = array();
        if($primaryImg = get_post_thumbnail_id( $product->id )){
            $image_pri['link'] = wp_get_attachment_url( $primaryImg );
            $image_pri['src']  = wp_get_attachment_image_src( $primaryImg, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) );
            $image_pri['thumb']  = wp_get_attachment_image_src($primaryImg, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
        }
        
        if($count_imgs && $layout == 'block'){
            $terms = get_the_terms($id, 'product_cat');
            if(!empty($terms)){
                foreach ($terms as $v){
                    $catids[] = $v->term_taxonomy_id;
                }
            }
	    
            // primary image
            foreach ($attachment_ids as $key => $img){
                $img_disp[$key]['link']  = wp_get_attachment_url( $img );
                $img_disp[$key]['src']   = wp_get_attachment_image_src(
                    $img,
                    apply_filters('catalog_product_large_thumbnail_size', 'shop_single'),
                    array(
                        'title' => $title
                    )
                );
            }
            // thumbnails
            foreach ($attachment_ids as $key => $img){
                $img_thumbs[$key]['link']  = wp_get_attachment_url($img);
                $img_thumbs[$key]['src']   = wp_get_attachment_image_src(
                    $img,
                    apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail'),
                    array(
                        'title' => $title
                    )
                );
            }

            $thumbs = lt_getThumbs($_id, $image_pri, $count_imgs, $img_thumbs);
        }
        
        if($layout == 'full'){
            lt_getProductGrid();
            if($banner_src){
                $banner_src = wp_get_attachment_image_src($banner_src, 'full');
                $banner_src = $banner_src[0];
                if(!$banner_height){
                    $banner_height = '400px';
                }
                if(is_numeric($banner_height)){
                    $banner_height .= 'px';
                }
            }
        }
        ?>
        <div class="woocommerce<?php echo ' lt-products-deal-'.$_id; ?><?php echo (($el_class != '') ? ' '.$el_class : ''); ?>">
            <div class="inner-content">
                <?php include LEE_FRAMEWORK_PLUGIN_PATH . '/includes/product_layouts/product_deal_' . $layout . '.php';?>
            </div>
        </div>
    <?php endif;
    wp_reset_postdata();
    $content = ob_get_contents();
    ob_end_clean();
    
    return $content;
}
add_shortcode('lt_products_deal', 'lt_sc_products_deal');

function lt_getProductDeals($id = null){
    if(!$id || !class_exists('WC_Product_Factory')){
        return null;
    }
    
    return (new WC_Product_Factory())->get_product((int)$id);
}

function lt_getProductGrid($notid = null, $catIds = null, $type = 'best_selling'){
    global $woocommerce;
    if(!$woocommerce){
        return null;
    }
    
    remove_filter('posts_clauses', array($woocommerce->query, 'order_by_popularity_post_clauses'));
    remove_filter('posts_clauses', array($woocommerce->query, 'order_by_rating_post_clauses'));
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 6,
        'post_status' => 'publish',
        'post__not_in' => array((int)$notid),
        'paged' => 1,
        'meta_query' => array(),
        'tax_query' => array()
    );
    
    if(!empty($catIds)){
	$args['tax_query'][] = array(
            'taxonomy'  => 'product_cat',
            'field'     => 'id', 
            'terms'     => $catIds
        );
    }
    
    switch ($type) {
        case 'featured_product':
            $args['ignore_sticky_posts'] = 1;
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = array(
                'key' => '_featured',
                'value' => 'yes'
            );
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            break;
        case 'top_rate':
            add_filter('posts_clauses', array($woocommerce->query, 'order_by_rating_post_clauses'));
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            break;
        case 'recent_product':
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            break;
        case 'on_sale':
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            $args['post__in'] = wc_get_product_ids_on_sale();
            break;
        case 'recent_review':
            if($post_per_page == -1) $_limit = 4;
            else $_limit = $post_per_page;
            global $wpdb;
            $query = "SELECT c.comment_post_ID FROM {$wpdb->prefix}posts p, {$wpdb->prefix}comments c WHERE p.ID = c.comment_post_ID AND c.comment_approved > 0 AND p.post_type = %s AND p.post_status = %s AND p.comment_count > %s ORDER BY c.comment_date ASC LIMIT 0, ". $_limit;
            $results = $wpdb->get_results($wpdb->prepare($query, 'product', 'publish', '0', OBJECT));
            $_pids = array();
            foreach ($results as $re) {
                $_pids[] = $re->comment_post_ID;
            }
            
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            $args['post__in'] = $_pids;
            break;
        case 'deals':
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            $args['meta_query'][] = array(
                'key' => '_sale_price_dates_to',
                'value' => '0',
                'compare' => '>'
            );
            $args['post__in'] = wc_get_product_ids_on_sale();
            break;
        
        case 'best_selling':
        default :
            $args['meta_key'] = 'total_sales';
            $args['orderby'] = 'meta_value_num';
            $args['ignore_sticky_posts'] = 1;
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            break;
    }
    
    //igone id deals
    if(isset($args['post__in'])){
        $keys = array_keys($args['post__in'], (int)$notid);
        if($keys){
            foreach ($keys as $v){
                unset($args['post__in'][$v]);
            }
        }
    }
    
    return new WP_Query($args);
}

function lt_add_to_cart_button_sc($product, $quickview = true){
    global $post, $lt_opt, $wp_query;
    $head_type = $lt_opt['header-type'];
    if (isset($post->ID)){
        $custom_header = get_post_meta($wp_query->get_queried_object_id(), '_lee_custom_header', true);
        if (!empty($custom_header)){
            $head_type = $custom_header;
        }
    }
    
    if($quickview){
        echo '<div class="add-to-cart-btn"><a href="javascript:void(0);" class="button small quick-view" data-prod="' . esc_attr($product->id) . '" data-head_type="'.esc_attr($head_type).'">' . esc_html__('Shop now', 'lee_framework') . '</a></div>';
        return;
    }
    
    echo apply_filters( 'woocommerce_loop_add_to_cart_link',
        sprintf('
            <div class="add-to-cart-btn">' .
                '<a href="%s" rel="nofollow" data-product_id="%s" class="%s button small product_type_%s add-to-cart-grid" data-head_type="%s">' .
                    '<span class="add_to_cart_text">%s</span>' .
                    '<span class="cart-icon-handle"></span>' .
                '</a>%s' .
            '</div>',
            esc_url($product->add_to_cart_url()),
            esc_attr($product->id),
            ($product->is_purchasable() && $product->is_in_stock() && $product->product_type == 'simple') ? 
                'ajax_add_to_cart add_to_cart_button' : 
                    (($product->product_type == 'variable') ? 'ajax_add_to_cart_variable' : ''),
            esc_attr($product->product_type),
            esc_attr($head_type),
            esc_html($product->add_to_cart_text()),
            ($product->product_type == 'variable') ? '<a class="hidden-tag quick-view" data-prod="'.esc_attr($product->id).'" data-head_type="'.esc_attr($head_type).'"></a>' : ''
        ),
    $product);
}

function lt_getThumbs($_id, $image_pri, $count_imgs, $img_thumbs){
    $thumbs = '<div class="lt-sc-p-thumbs">';
    $thumbs .= '<div class="product-thumbnails-'.$_id.' images-popups-gallery owl-carousel">';
    
    if($image_pri){
        $thumbs .= '<a href="javascript:void(0);" class="active-thumbnail lt-thumb-a">';
        $thumbs .= '<img class="lt-thumb-img" src="'.esc_attr($image_pri['thumb'][0]).'" />';
        $thumbs .= '</a>';
    }

    if ($count_imgs) {
        foreach ($img_thumbs as $key => $thumb){
            $thumbs .= '<a href="javascript:void(0);" class="lt-thumb-a">';
            $thumbs .= '<img class="lt-thumb-img" src="'.esc_attr($thumb['src'][0]).'" />';
            $thumbs .= '</a>';
        }
    } else {
        $thumbs .= sprintf('<a href="%s" class="active-thumbnail"><img src="%s" /></a>', wc_placeholder_img_src(), wc_placeholder_img_src());    
    }
    
    $thumbs .= '</div>';
    $thumbs .= '</div>';
    return $thumbs;
}