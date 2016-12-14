<?php

// **********************************************************************//
// ! Is Active WooCommmerce
// **********************************************************************//
if( !function_exists('lt_has_woocommerce') ){
    function lt_has_woocommerce(){
        $_actived = apply_filters('active_plugins', get_option('active_plugins'));
        if( in_array("woocommerce/woocommerce.php", $_actived) || class_exists('WooCommerce') ){
            return true;
        }
        return false;
    }
}


// **********************************************************************//
// ! Tiny account
// **********************************************************************//
if( !function_exists('lt_tiny_account') ){
    function lt_tiny_account(){
	$login_url = '#';
	$register_url = '#';
	$profile_url = '#';
	$logout_url = wp_logout_url(get_permalink());

	if( lt_has_woocommerce() ){ /* Active woocommerce */
	    $myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
	    if ( $myaccount_page_id ) {
		$login_url = get_permalink( $myaccount_page_id );
		$register_url = $login_url;
		$profile_url = $login_url;
	    }
	}
	else{
	    $login_url = wp_login_url();
	    $register_url = wp_registration_url();
	    $profile_url = admin_url( 'profile.php' );
	}

	$redirect_to = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	$_user_logged = is_user_logged_in();
	ob_start();

	if( !$_user_logged ): ?>
	    <li class="menu-item color"><a href="<?php echo esc_url($login_url); ?>" title="<?php esc_html_e('Login','altotheme'); ?>"><span class="pe7-icon pe-7s-lock"></span><?php esc_html_e('Sign in / Register', 'altotheme'); ?></a></li>
	<?php else: ?>
	    <li class="menu-item"><a href="<?php echo esc_url($profile_url); ?>" title="<?php esc_html_e('My Account', 'altotheme') ?>"><span class="pe7-icon pe-7s-user"></span><?php esc_html_e('My Account', 'altotheme') ?></a></li>
	    <li class="menu-item"><a class="nav-top-link" href="<?php echo esc_url($logout_url); ?>" title="<?php esc_html_e('Logout','altotheme'); ?>"><span class="pe7-icon pe-7s-unlock"></span><?php esc_html_e('Logout','altotheme'); ?></a></li>
	<?php endif;

	return ob_get_clean();
    }
}

// **********************************************************************//
// ! Mini cart header icon - 1: only show cart icon / 2: show number item, total cart price
// **********************************************************************//
if(!function_exists('lt_mini_cart')){
    function lt_mini_cart($mini_cart_type = 'full', $echo = true){
        global $woocommerce, $lt_opt;
        if(!$woocommerce || (isset($lt_opt['disable-cart']) && $lt_opt['disable-cart'])){
            return;
        }
        $items = $price = '';
        if($mini_cart_type == 'full'){
            $items = esc_html__('item(s) - ','altotheme');
            $price = 
            '<span class="cart-count">' .
                '<span class="total-price primary-color">' . 
                    $woocommerce->cart->get_cart_total() . 
                '</span>' .
            '</span>';
        }
        
        $content =
        '<div class="mini-cart cart-inner mini-cart-type-' . $mini_cart_type . ' inline-block">' .
            '<a href="javascript:void(0);" class="cart-link">' .
                '<div>' .
                    '<span class="cart-icon icon flaticon-cart"></span>' .
                    '<span class="products-number">' .
                        '<span class="lt-sl">' .
                            $woocommerce->cart->cart_contents_count .
                        '</span>' .
                        $items .
                    '</span>' .
                    $price .
                '</div>' .
            '</a>' .
        '</div>';
        
        if(!$echo){
            return $content;
        }
        
        echo $content;
    }
}

// **********************************************************************//
// ! Add to cart dropdown - Refresh mini cart content. Input from header type
// **********************************************************************//
add_filter('woocommerce_add_to_cart_fragments', 'lt_add_to_cart_refresh');
function lt_add_to_cart_refresh( $fragments ) {
    global $lt_opt;
    if(isset($_POST['head_type'])){
        $lt_opt['header-type'] = $_POST['head_type'];
    }
    
    switch ($lt_opt['header-type']){
        case '2':
        case '7':
        case '4':
            $mini_cart_type = 'simple';
            break;
        
        default:
            $mini_cart_type = 'full';
            break;
    }
    
    $fragments['.cart-inner'] = lt_mini_cart($mini_cart_type, false);
    $fragments['div.widget_shopping_cart_content'] = lt_mini_cart_sidebar(true);
    
    return $fragments;
}

// **********************************************************************//
// ! Mini cart sidebar
// **********************************************************************//
if(!function_exists('lt_mini_cart_sidebar')) {
    function lt_mini_cart_sidebar($str = false) {
        global $woocommerce, $lt_opt;
        if(!$woocommerce || (isset($lt_opt['disable-cart']) && $lt_opt['disable-cart']))
            return;
        ob_start();
        $empty = '<p class="empty">'.esc_html__('No products in the cart.','altotheme').'</p>';
        require get_template_directory() .'/headers/includes/mini-cart-sidebar.php';
        $content = ob_get_clean();
        if($str){
            return $content;
        }
        echo '<div class="empty hidden-tag">' . $empty. '</div>';
        echo $content;
    }
}

// **********************************************************************//
//	Mini cart - AJAX: Remove product from cart
// **********************************************************************//
function lt_cart_remove_item() {
    $array = array();
    if(isset($_POST['item_key']) && ($item_key = $_POST['item_key'])){
        $cart = WC()->instance()->cart;
        if ($removed = $cart->remove_cart_item( $item_key ))	{
            $array['succes'] = true;
            $array['sl'] = $cart->get_cart_contents_count();
            $array['pr'] = $cart->get_cart_subtotal();
        } else {
            $array['succes'] = false;
        }
    }
    
    exit(json_encode($array));
}
add_action( 'wp_ajax_lt_cart_remove_item' , 'lt_cart_remove_item' );
add_action( 'wp_ajax_nopriv_lt_cart_remove_item', 'lt_cart_remove_item' );

// **********************************************************************//
//	single add to cart - AJAX
// **********************************************************************//
function lt_single_add_to_cart() {
    if(!isset($_POST['product_id']) || (int)$_POST['product_id'] <= 0){
        echo json_encode(array('error' => true));
        die();
    }
    
    $error = false;
    $product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
    $quantity          = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );
    $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
    $product_status    = get_post_status( $product_id );
    
    $type = (!isset($_POST['product_type']) || !in_array($_POST['product_type'], array('single', 'variations'))) ? 'single' : $_POST['product_type'];
    
    $variation_id = (int)$_POST['variation_id'];
    $variation = isset($_POST['variation']) ? $_POST['variation'] : array();
    
    if(!$variation && $variation_id && $type == 'variations'){
        $error = true;
    }
    
    if ( !$error && $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation) && 'publish' === $product_status ) {
        
        do_action( 'woocommerce_ajax_added_to_cart', $product_id );
        if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
            wc_add_to_cart_message( $product_id );
        }
        
        // Return fragments
        WC_AJAX::get_refreshed_fragments();
    }else {
        // If there was an error adding to the cart, redirect to the product page to show any errors
        $data = array(
            'error' => true,
            'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
        );
        echo json_encode( $data );
    }
    die();
}
add_action( 'wp_ajax_lt_single_add_to_cart' , 'lt_single_add_to_cart' );
add_action( 'wp_ajax_nopriv_lt_single_add_to_cart', 'lt_single_add_to_cart' );


// **********************************************************************//
// ! Catalog mode - None e-Commerce
// **********************************************************************//
if(isset($lt_opt['disable-cart']) && $lt_opt['disable-cart']){
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
}

// **********************************************************************//
// ! Add to cart button
// **********************************************************************//
function lt_add_to_cart_btn($type = 'small', $echo = true){
    global $product, $post, $wp_query, $lt_opt;
    $head_type = $lt_opt['header-type'];
    if (isset($post->ID)){
        $custom_header = get_post_meta($wp_query->get_queried_object_id(), '_lee_custom_header', true);
        if (!empty($custom_header)){
            $head_type = lt_get_header_structure($custom_header);
        }
    }
    
    $link = array(
        'url'   => '',
        'label' => '',
        'class' => ''
    );

    $handler = apply_filters( 'woocommerce_add_to_cart_handler', $product->product_type, $product );
    $result = '';
    switch($type){
        case 'large':
            $result .= 
                apply_filters( 'woocommerce_loop_add_to_cart_link',
                    sprintf( '
                        <div class="add-to-cart-btn">
                            <a href="%s" rel="nofollow" data-product_id="%s" class="%s button small product_type_%s add-to-cart-grid" data-head_type="%s">
                                <span class="add_to_cart_text">%s</span>
                                <span class="cart-icon-handle"></span>
                            </a>
                        </div>',
                        esc_url( $product->add_to_cart_url() ),//link
                        esc_attr( $product->id ),//product id
                        ($product->is_purchasable() && $product->is_in_stock() && $product->product_type == 'simple') ?
                        'ajax_add_to_cart add_to_cart_button' : '',	//class name
                        esc_attr( $product->product_type ), //product type
                        esc_html( $head_type ),
                        esc_html( $product->add_to_cart_text() ) //add to cart text
                    ),
                $product );
            break;
        case 'small':
        default:
            $result .= 
                apply_filters( 'woocommerce_loop_add_to_cart_link',
                    sprintf( '
                        <div class="add-to-cart-btn tip-top" data-tip="%s">
                            <div class="btn-link">
                                <a href="%s" rel="nofollow" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s product_type_%s add-to-cart-grid" data-head_type="%s">
                                    <span class="cart-icon pe-icon pe-7s-shopbag"></span>
                                    <span class="add_to_cart_text">%s</span>
                                    <span class="cart-icon-handle"></span>
                                </a>
                            </div>
                        </div>',
                        esc_html( $product->add_to_cart_text() ), //data tip
                        esc_url( $product->add_to_cart_url() ),//link
                        esc_attr( isset( $quantity ) ? $quantity : 1 ),//link
                        esc_attr( $product->id ),//product id
                        esc_attr( $product->get_sku() ), //product sku
                        ($product->is_purchasable() && $product->is_in_stock() && $product->product_type == 'simple') ? 'ajax_add_to_cart add_to_cart_button' : '', //class name
                        esc_attr( $product->product_type ), //product type
                        esc_html( $head_type ),
                        esc_html( $product->add_to_cart_text() ) //add to cart text
                    ),
                $product );
            break;
    }
    
    if(!$echo){
        return $result;
    }
    echo $result;
}


// **********************************************************************//
// ! Wishlist link
// **********************************************************************//
function lt_tini_wishlist(){
    if( !(lt_has_woocommerce() && class_exists('YITH_WCWL')) ){
        return;
    }
    $tini_wishlist = '';
    
    $wishlist_page_id = get_option( 'yith_wcwl_wishlist_page_id' );
    if( function_exists( 'icl_object_id' ) ){
        $wishlist_page_id = icl_object_id( $wishlist_page_id, 'page', true );
    }
    $wishlist_page = get_permalink( $wishlist_page_id );
    
    //$count = yith_wcwl_count_products(); // count wishlist
    
    $tini_wishlist .= '<a href="' . esc_url($wishlist_page) . '" title="' . esc_html__('Wishlist', 'altotheme') . '">' .
        '<span class="pe7-icon pe-7s-like"></span>' .
        esc_html__('Wishlist', 'altotheme') .
    '</a>';

    return $tini_wishlist;
}


// **********************************************************************//
//	Load products page - AJAX - filter
// **********************************************************************//
function lt_products_page() {
    global $lt_opt, $wp_query, $woocommerce, $product, $lt_wc_query;
    
    if(!$lt_wc_query){
        die();
    }
    $compare_ver = version_compare( $woocommerce->version, '2.6.1', ">=" );
    $baseUrl = $_GET['baseUrl'];
    $paged = (int)$_GET['paged'];
    
    $args = $lt_wc_query->get_catalog_ordering_args();
    if(!$args){
        $args['orderby']  = 'menu_order title';
        $args['order']    = 'ASC';
    }
    $args['post_type'] = 'product';
    $args['posts_per_page'] = $lt_opt['products_pr_page'];
    $args['post_status'] = 'publish';
    if(!$paged){
        $args['paged'] = '1';
    }else{
        $args['paged'] = $paged;
    }
    
    $args['tax_query'] = array();
    $args['meta_query'] = array();
    $visibility = 'catalog';
    
    if((int)$_GET['catId'] > 0){
        // Filter by Category
        $args['tax_query'][] = array(
            'taxonomy'  => 'product_cat',
            'field'     => 'id', 
            'terms'     => array((int)$_GET['catId'])
        );
    }elseif($_GET['hasSearch'] == 1 && isset($_GET['s'])){
        if(!isset($args['post__in'])){
            $args['post__in'] = array();
        }
        
        // Search product
        $args['post__in'] = $lt_wc_query->lt_getPostSearch($_GET['s'], $args['post__in']);
        $visibility = 'search';
    }
    
    $args['meta_query'][] = array(
        'key' => '_visibility',
        'value' => array('visible', $visibility),
        'compare' => 'IN'
    );
    
    $_chosen_attributes = array();
    if(!empty($_GET['variations'])){
        foreach ($_GET['variations'] as $v){
            $_chosen_attributes['pa_' . $v['taxonomy']] = array(
                'terms' 		=> $v['values'],
                'query_type'    => $v['type'] === 'or' ? 'or' : 'and'
            );
        }
        // Filter by variations
        if( !$compare_ver ) {
            if(!isset($args['post__in'])){
                $args['post__in'] = array();
            }
            
            $args['post__in'] = $lt_wc_query->lt_filter_by_variations($_chosen_attributes, $args['post__in']);
        }else{
            foreach ($_GET['variations'] as $v){
                $args['tax_query'][] = array(
                    'taxonomy' => 'pa_' . $v['taxonomy'],
                    'field'    => 'slug',
                    'terms'    => $v['slug'],
                    'operator' => $v['type'] === 'or' ? 'IN' : 'AND',
                    'include_children' => false,
                );
            }
        }
    }
    
    // Filter by price
    if($_GET['hasPrice'] && ($_GET['min_price'] || $_GET['min_price'])){
        if( $compare_ver ) {
            $min = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
            $max = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 9999999999;

            $args['meta_query'][] = array(
                'key'          => '_price',
                'value'        => array($min, $max),
                'compare'      => 'BETWEEN',
                'type'         => 'DECIMAL',
                'price_filter' => true,
            );
        }else{
            if(!isset($args['post__in'])){
                $args['post__in'] = array();
            }
            $args['post__in'] = $lt_wc_query->price_filter($args['post__in']);
        }
    }
    
    $wp_query = new WP_Query($args);
    
    if ( in_array('yith-woocommerce-compare/init.php', apply_filters('active_plugins', get_option('active_plugins')))){
        // Contruct compare shortcode
        new YITH_Woocompare_Frontend();
    }
    
    $_delay = 0;
    $_delay_item = (isset($lt_opt['delay_overlay']) && (int) $lt_opt['delay_overlay']) ? (int) $lt_opt['delay_overlay'] : 100;
    $json = array();
    $count = 0;
    ob_start();
    if(have_posts()){
        while ( have_posts() ){
            the_post();
            wc_get_template('content-product.php', array('_delay' => $_delay, 'wrapper' => 'li'));
            $_delay += $_delay_item;
            $count++;
        }
        wp_reset_postdata();
        
    }else{
        echo '<li class="row">';
        woocommerce_get_template( 'loop/no-products-found.php' );
        echo '</li>';
    }
    $json['content'] = ob_get_clean();
    
    if($count <= 1 && $paged <= 1){
        $json['select_order'] = '';
    }else{
        ob_start(); // Refresh Select order
        woocommerce_catalog_ordering();
        $json['select_order'] = ob_get_clean();
    }
    
    ob_start(); // Refresh Pagination
    wc_get_template('loop/pagination.php', array(
        'baseUrl' => $baseUrl,
        'paged' => $paged
    ));
    $json['pagination'] = ob_get_clean();
    
    ob_start(); // Refresh Breadcrumb
    lt_get_breadcrumb(true);
    $json['breadcrumb'] = ob_get_clean();
    
    $shop_page_id = woocommerce_get_page_id( 'shop' );
    $shop_page    = get_post( $shop_page_id );
    $json['shop_url'] = get_permalink($shop_page);
    $json['base_url'] = home_url('/');
    
    $attribute_taxonomies = wc_get_attribute_taxonomies();
    $results = array();
    if ($attribute_taxonomies) {
        foreach ($attribute_taxonomies as $tax) {
            if (taxonomy_exists(wc_attribute_taxonomy_name($tax->attribute_name))) {
                $attribute_array[$tax->attribute_name] = $tax->attribute_name;
                $query_type = isset($_chosen_attributes['pa_' . $tax->attribute_name]['query_type']) ? $_chosen_attributes['pa_' . $tax->attribute_name]['query_type'] : 'and';
                
                $results = lt_count_by_variations('pa_'.$tax->attribute_name, $args, $results, $query_type);
            }
        }
    }
    
    $json['results'] = $results;
    
    die(json_encode($json));
}
add_action( 'wp_ajax_lt_products_page' , 'lt_products_page' );
add_action( 'wp_ajax_nopriv_lt_products_page', 'lt_products_page' );

function lt_count_by_variations($taxonomy, $args = array(), $result = array(), $query_type = 'and'){
    $get_terms_args = array( 'hide_empty' => '1' );
    $orderby = wc_attribute_orderby( $taxonomy );

    switch ( $orderby ) {
        case 'name' :
            $get_terms_args['orderby']    = 'name';
            $get_terms_args['menu_order'] = false;
        break;
        case 'id' :
            $get_terms_args['orderby']    = 'id';
            $get_terms_args['order']      = 'ASC';
            $get_terms_args['menu_order'] = false;
        break;
        case 'menu_order' :
            $get_terms_args['menu_order'] = 'ASC';
        break;
    }
    unset($args['paged']);
    $args['posts_per_page'] = -1;
    $args['fields'] = 'ids';
    
    $attr = str_replace('pa_', '', $taxonomy);
    $result[$attr] = array();
    //var_dump($args);
    if ( 0 < count( $terms = get_terms( $taxonomy, $get_terms_args ) ) ) {
        global $woocommerce;
        $compare_ver = version_compare( $woocommerce->version, '2.6.1', ">=" );
        $term_counts = $compare_ver ? get_filtered_term_product_counts(wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type, $args, true) : null;
        
        foreach ( $terms as $k => $term ) {
            if(!$compare_ver){
                $args['tax_query'][1] = array(
                    array(
                        'taxonomy' 	=> $taxonomy,
                        'terms' 	=> $term->term_id,
                        'field' 	=> 'term_id'
                    )
                );
                $result[$attr]['lt_'.$attr.'_'.$term->term_id] = count(get_posts($args));
                unset($args['tax_query'][1]);
            }else{
                $result[$attr]['lt_'.$attr.'_'.$term->term_id] = isset($term_counts[$term->term_id]) ? $term_counts[$term->term_id] : 0;
            }
        }
    }
    
    return $result;
}

function get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type, $args, $ajax = false ) {
    global $wpdb;
    
    if(!$ajax){
        $meta_query = WC_Query::get_main_meta_query();
        $tax_query  = WC_Query::get_main_tax_query();
    }else{
        $meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();
        $tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
    }
    
    if ( 'or' === $query_type ) {
        foreach ( $tax_query as $key => $query ) {
            if ( $taxonomy === $query['taxonomy'] ) {
                unset( $tax_query[ $key ] );
            }
        }
    }
    
    $meta_query      = new WP_Meta_Query( $meta_query );
    $tax_query       = new WP_Tax_Query( $tax_query );
    $meta_query_sql  = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
    $tax_query_sql   = $tax_query->get_sql( $wpdb->posts, 'ID' );
    
    // Generate query
    $query           = array();
    $query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
    
    $query['from']   = "FROM {$wpdb->posts}";
    
    $query['join']   = "
        INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
        INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
        INNER JOIN {$wpdb->terms} AS terms USING( term_id )
        " . $tax_query_sql['join'] . $meta_query_sql['join'];
    
    $query['where']   = "
        WHERE {$wpdb->posts}.post_type IN ( 'product' )
        AND {$wpdb->posts}.post_status = 'publish'
        " . $tax_query_sql['where'] . $meta_query_sql['where'] . "
        AND terms.term_id IN (" . implode( ',', array_map( 'absint', $term_ids ) ) . ")
    ";

    // For search case
    if(isset($_GET['s']) && $_GET['s']){
        $s = stripslashes($_GET['s']);
        $s = str_replace(array( "\r", "\n" ), '', $s);
        $query['where'] .= " AND ( {$wpdb->posts}.post_title LIKE '%$s%' OR {$wpdb->posts}.post_excerpt LIKE '%$s%' OR {$wpdb->posts}.post_content LIKE '%$s%')";
    }

    $query['group_by'] = "GROUP BY terms.term_id";
    $query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
    $query             = implode( ' ', $query );
    //echo $query;
    $results           = $wpdb->get_results( $query );
   
    return wp_list_pluck( $results, 'term_count', 'term_count_id' );
}

function lt_get_pagination_ajax(
    $total = 1,
    $current = 1,
    $type = 'list',
    $prev_text = 'PREV', 
    $next_text = 'NEXT',
    $end_size = 3, 
    $mid_size = 3,
    $prev_next = true,
    $show_all = false
) {
    
    if ( $total < 2 ) {
        return;
    }
    
    if ( $end_size < 1 ) {
        $end_size = 1;
    }
    
    if ( $mid_size < 0 ) {
        $mid_size = 2;
    }
    
    $r = '';
    $page_links = array();
    
    // PREV Button
    if ( $prev_next && $current && 1 < $current ){
        $page_links[] = '<a class="lt-prev prev page-numbers" data-page="' . ((int)$current - 1) . '" href="javascript:void(0);">' . $prev_text . '</a>';
    }
    
    // PAGE Button
    for ( $n = 1; $n <= $total; $n++ ){
        $page = number_format_i18n( $n );
        if ( $n == $current ){
            $page_links[] = '<a class="lt-current current page-numbers" data-page="' . $page . '" href="javascript:void(0);">' . $page . '</a>';
        } elseif ($show_all || ($n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size) ){
            $page_links[] = '<a class="lt-page page-numbers" data-page="' . $page . '" href="javascript:void(0);">' . $page . "</a>";
        }
    }
    
    // NEXT Button
    if ( $prev_next && $current && ( $current < $total || -1 == $total ) ){
        $page_links[] = '<a class="lt-next next page-numbers" data-page="' . ((int)$current + 1)  . '" href="javascript:void(0);">' . $next_text . '</a>';
    }
    
    // DATA Return
    switch ($type) {
        case 'array' :
            return $page_links;

        case 'list' :
            $r .= '<ul class="page-numbers lt-pagination-ajax"><li>';
            $r .= implode('</li><li>', $page_links);
            $r .= '</li></ul>';
            break;

        default :
            $r = implode('', $page_links);
            break;
    }
    
    return $r;
}