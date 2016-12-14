<?php

// **********************************************************************//
// ! Get logo
// **********************************************************************//
if (!function_exists('lt_logo')){
    function lt_logo($echo = true){
        global $lt_opt, $wp_query;
        $logo_link = get_post_meta($wp_query->get_queried_object_id(), '_lee_custom_logo', true);
        if ($logo_link == ''){
            $logo_link = $lt_opt['site_logo'];
        }
        $site_title = esc_attr( get_bloginfo( 'name', 'display' ) );
        $content = '<div class="logo">';
        $content .= '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . $site_title . ' - ' . esc_attr(get_bloginfo( 'description', 'display' )) . '" rel="home">';
        if($logo_link != ''){
            $content .= '<img src="'.esc_attr($logo_link).'" class="header_logo" alt="'.$site_title.'"/>';
        } else {
            $content .= get_bloginfo( 'name', 'display' );
        }
        $content .= '</a>';
        $content .= '</div>';
        
        if(!$echo){
            return $content;
        }
        
        echo $content;
    }
}

// **********************************************************************//
// ! Get header search
// **********************************************************************//
if (!function_exists('lt_search')){
    function lt_search($search_type = 'icon'){
        $class = '';?>
        <div class="lt-search-space inline-block lt_search_<?php echo esc_attr($search_type); ?>">
            <?php if ($search_type == 'icon'):
                $class = ' hidden-tag lt-over-hide';
                ?>
                <!-- <a class="icon pe7-icon pe-7s-search desk-search" href="javascript:void(0);"></a> -->
                <a class="search-icon desk-search" href="javascript:void(0);">
                    <span class="circle"></span>
                    <span class="handle"></span>
                </a>
            <?php endif; ?>
            <?php if($search_type == 'full') :?>
                <span class="icon pe7-icon pe-7s-search"></span>
            <?php endif; ?>
            <div class="lt-show-search-form<?php echo $class;?>">
                <?php get_search_form(); ?>
            </div>
        </div>
    <?php }
}


// **********************************************************************// 
// ! Get main menu
// **********************************************************************// 
if (!function_exists('lt_get_main_menu')){
    function lt_get_main_menu($main_menu_type = '1'){
        static $has_main_menu = false;
        $id_menu = $has_main_menu ? ' id="site-navigation" ' : ' ';
        $has_main_menu = true;
        $allowed_html = array(
            'li' => array(),
            'b' => array()
        );
        echo '<div class="nav-wrapper inline-block main-menu-type-'.$main_menu_type.'">';
            echo '<ul' . $id_menu . 'class="header-nav">';
            if ( has_nav_menu( 'primary' ) ) :
                wp_nav_menu(array(
                    'theme_location'    => 'primary',
                    'container'         => false,
                    'items_wrap'        => '%3$s',
                    'depth'             => 5,
                    'walker'            => new LT_NavDropdown()
                ));
            else:
                echo wp_kses( __('<li>Please Define main navigation in <b>Apperance > Menus</b></li>','altotheme'), $allowed_html );
            endif;
            echo '</ul>';
        echo '</div><!-- nav-wrapper -->';
    }
}


// **********************************************************************// 
// ! Mobile account menu
// **********************************************************************//
if(!function_exists('lt_mobile_account')) {
    function lt_mobile_account() {
        require get_template_directory() .'/includes/mobile-account.php';
    }
}

// **********************************************************************// 
// ! Header account menu
// **********************************************************************//
if(!function_exists('lt_header_account')){
    function lt_header_account(){
        require get_template_directory() . '/headers/includes/header-account.php';   
    }
}

// **********************************************************************// 
// ! Header Setting Switcher
// **********************************************************************//
if(!function_exists('lt_header_setting_switcher')){
    function lt_header_setting_switcher(){
        require get_template_directory() . '/headers/includes/header-setting-switcher.php';
    }
}


// **********************************************************************// 
// ! Get shop by category menu
// **********************************************************************// 
if (!function_exists('lt_get_shop_by_category_menu')){
    function lt_get_shop_by_category_menu(){
        $allowed_html = array(
            'li' => array(),
            'b' => array()
        );
        echo '<div class="nav-wrapper">';
            echo '<ul id="" class="shop-by-category">';
            if ( has_nav_menu( 'shop_by_category' ) ) :
                wp_nav_menu(array(
                    'theme_location' => 'shop_by_category',
                    'container'       => false,
                    'items_wrap'      => '%3$s',
                    'depth'           => 3,
                    'walker'          => new LT_NavDropdown()
                ));
            else:
                echo wp_kses( __('<li>Please Define Shop by Category menu in <b>Apperance > Menus</b></li>', 'altotheme'), $allowed_html );
            endif;                             
            echo '</ul>';
        echo '</div><!-- nav-wrapper -->';
    }
}

// **********************************************************************// 
// ! Get shop by Footer menu
// **********************************************************************// 
if (!function_exists('lt_get_footer_menu')){
    function lt_get_footer_menu(){
        $allowed_html = array(
            'li' => array(),
            'b' => array()
        );
        echo '<div class="nav-wrapper">';
            echo '<ul id="" class="footer-menu">';
            if ( has_nav_menu( 'footer_menu' ) ) :
                wp_nav_menu(array(
                    'theme_location' => 'footer_menu',
                    'container'       => false,
                    'items_wrap'      => '%3$s',
                    'depth'           => 3,
                    'walker'          => new LT_NavDropdown()
                ));
            else:
                echo wp_kses(__('<li>Please Define Footer menu in <b>Apperance > Menus</b></li>','altotheme'), $allowed_html);
            endif;
            echo '</ul>';
        echo '</div><!-- nav-wrapper -->';
    }
}

function lt_tpl2id($tpl){
    global $wpdb;

    $pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => $tpl
    ));
    
    if(empty($pages)) return null;
    
    foreach($pages as $page){
        return $page->ID;
    }
}

// **********************************************************************// 
// ! Site breadcrumbs
// **********************************************************************//
if(!function_exists('lt_breadcrumbs')) {
    function lt_breadcrumbs() {
	global $post, $lt_opt, $wp_query;
	
        $showOnHome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
        $delimiter = '<span class="fa fa-angle-right "></span>'; // delimiter between crumbs
        $home = esc_html__('Home', 'altotheme'); // text for the 'Home' link
        $blogPage = esc_html__('Blog', 'altotheme');
        $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
        $before = '<span class="current">'; // tag before the current crumb
        $after = '</span>'; // tag after the current crumb
        
        $homeLink = esc_url(home_url('/'));
	
        if (is_front_page()) {
            if ($showOnHome == 1)
                echo '<div><a href="' . $homeLink . '">' . $home . '</a></div>';
        } else if (class_exists('bbPress') && is_bbpress()) {
            $bbp_args = array(
                'before' => '<div class="bread">',
                'after' => '</div>'
            );        
            bbp_breadcrumb($bbp_args);
        } else {
            do_action('lt_before_breadcrumbs');

            echo '<div class="bread">';
            echo '<div class="row">';
            echo '<div class="large-12 columns">';
            echo '<div class="breadcrumb-row">';
            echo '<h3 class="breadcrumb">';
            echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . '';

            if ( is_category() ) {
                $thisCat = get_category(get_query_var('cat'), false);
                
                if ($thisCat->parent != 0)
                    echo get_category_parents($thisCat->parent, TRUE, ' ' . $delimiter . ' ');
                
                echo $before . esc_html__('Archive by category', 'altotheme') . ' "' . single_cat_title('', false) . '"' . $after;

            } elseif ( is_search() ) {
                echo $before . esc_html__('Search results for', 'altotheme') . ' "' . get_search_query() . '"' . $after;
            } elseif ( is_day() ) {
                echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
                echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
                echo $before . get_the_time('d') . $after;

            } elseif ( is_month() ) {
                echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
                echo $before . get_the_time('F') . $after;

            } elseif ( is_year() ) {
                echo $before . get_the_time('Y') . $after;

            } elseif ( is_single() && !is_attachment() ) {
                
                if ( get_post_type() != 'post' ) {
                    $post_type = get_post_type_object(get_post_type());
                    $slug = $post_type->rewrite;
                    echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
                    if ($showCurrent == 1)
                        echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
                    
                } else {
                    $cat = get_the_category(); 
                    if(isset($cat[0])) {
                        $cat = $cat[0];
                        $cats = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
                        if ($showCurrent == 0)
                            $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
                        echo $cats;
                    }
                    if ($showCurrent == 1)
                        echo $before . get_the_title() . $after;
                }

            } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
                $post_type = get_post_type_object(get_post_type());
                echo $before . $post_type->labels->singular_name . $after;
            } elseif ( is_attachment() ) {
                $parent = get_post($post->post_parent);
                if ($showCurrent == 1)
                    echo ' '  . $before . get_the_title() . $after;

            } elseif ( is_page() && !$post->post_parent ) {
                if ($showCurrent == 1) echo $before . get_the_title() . $after;
            } elseif ( is_page() && $post->post_parent ) {
                $parent_id  = $post->post_parent;
                $breadcrumbs = array();
                while ($parent_id) {
                    $page = get_page($parent_id);
                    $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                    $parent_id  = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                for ($i = 0; $i < count($breadcrumbs); $i++) {
                    echo $breadcrumbs[$i];
                    if ($i != count($breadcrumbs)-1)
                        echo ' ' . $delimiter . ' ';
                }
                if ($showCurrent == 1)
                    echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;

            } elseif ( is_tag() ) {
                echo $before . esc_html__('Posts tagged', 'altotheme') . ' "' . single_tag_title('', false) . '"' . $after;

            } elseif ( is_author() ) {
                global $author;
                $userdata = get_userdata($author);
                echo $before . esc_html__('Articles posted by', 'altotheme') . ' ' . $userdata->display_name . $after;

            } elseif ( is_404() ) {
                echo $before . esc_html__('Error 404', 'altotheme') . $after;
            }else{
                echo $blogPage;
            }

            if ( get_query_var('paged') ) {
                if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() )
                    echo ' (';
                echo ' ('.esc_html__('Page','altotheme') . ' ' . get_query_var('paged').')';
                if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() )
                    echo ')';
            }
            
            echo '</h3>';
            echo '</div>';
            lt_back_to_page();
            echo '</div></div></div>';
        }
    }
}

if(!function_exists('lt_back_to_page')) {
    function lt_back_to_page() {
        echo '<a class="back-history" href="javascript: history.go(-1)">'.esc_html__('Return to Previous Page','altotheme').'</a>';
    }
}

// **********************************************************************// 
// ! Get breadcrumb
// **********************************************************************// 
add_action('lee_get_breadcrumb', 'lt_get_breadcrumb');
if (!function_exists('lt_get_breadcrumb')){
    function lt_get_breadcrumb($ajax = false){
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if (!is_plugin_active( 'woocommerce/woocommerce.php' )){
            return;
        }
        global $post, $lt_opt, $wp_query;
        $enable = true;
        $override = false;
        if(isset($lt_opt['breadcrumb_show']) && !$lt_opt['breadcrumb_show']){
            $enable = false;
        }

        if (isset($post->ID) && $post->post_type == 'page'){
            $queryObj = $wp_query->get_queried_object_id();
            $show_breadcrumb = get_post_meta($queryObj, '_lee_show_breadcrumb', true);
            $enable = ($show_breadcrumb != 'on') ? false : true;
            $override = true;
        }

        if(!$enable){
            return;
        }
        
	    $bg = false;
	    $bg_cl = false;
	    $h_bg = false;
	    $style_custom = '';
	    $txt_color = false;
	    
	    // Theme option
	    if(isset($lt_opt['breadcrumb_type']) && $lt_opt['breadcrumb_type'] == 'Has background'){
            $bg = $lt_opt['breadcrumb_bg'];
	    }

	    if(isset($lt_opt['breadcrumb_bg_color']) && $lt_opt['breadcrumb_bg_color']){
            $bg_cl = $lt_opt['breadcrumb_bg_color'];
	    }
	    
	    if(isset($lt_opt['breadcrumb_height']) && (int)$lt_opt['breadcrumb_height']){
            $h_bg = (int)$lt_opt['breadcrumb_height'];
	    }

	    if(isset($lt_opt['breadcrumb_color']) && $lt_opt['breadcrumb_color']){
            $txt_color = $lt_opt['breadcrumb_color'];
	    }
	    
	    // Override
	    if($override){
            $type_bg = get_post_meta($queryObj, '_lee_type_breadcrumb', true);
            $bg_override = get_post_meta($queryObj, '_lee_bg_breadcrumb', true);
            $bg_cl_override = get_post_meta($queryObj, '_lee_bg_color_breadcrumb', true);
            $h_override = get_post_meta($queryObj, '_lee_height_breadcrumb', true);
            $color_override = get_post_meta($queryObj, '_lee_color_breadcrumb', true);

            if($type_bg == '1'){
                $bg = $bg_override ? $bg_override : $bg;
                $bg_cl = $bg_cl_override ? $bg_cl_override : $bg_cl;
                $h_bg = (int)$h_override ? (int)$h_override : $h_bg;
                $txt_color = $color_override ? $color_override : $txt_color;
            }
	    }
	    
	    // set style by option breadcrumb
	    if($bg){
            $style_custom = 'background:url(\'' . $bg . '\') center center no-repeat;';
            if($bg_cl){
                $style_custom .= 'background-color:' . $bg_cl . ';';
            }
            $style_custom .= ($h_bg) ? 'height:' . $h_bg . 'px' : 'height:auto';
            $style_custom .= ($txt_color) ? ';color:' . $txt_color : '';
	    }
	    
	    $defaults = array(
            'delimiter'  => '&nbsp;&nbsp;/&nbsp;&nbsp;',
            'wrap_before'	=> '<h3 class="breadcrumb">',
            'wrap_after'	=> '</h3>',
            'before'    => '',
            'after'	    => '',
            'home'	    => 'Home',
            'ajax'	    => $ajax
	    );
	    $args = wp_parse_args($defaults);
	    ?>

        <div class="bread lt-breadcrumb<?php if($bg): echo ' lt-breadcrumb-has-bg'; endif;?>"<?php echo ($style_custom) ? ' style="'.esc_attr($style_custom).'"' : '';?>>
            <div class="row">
                <div class="large-12 columns">
                    <div class="breadcrumb-row">
                        <?php woocommerce_get_template('global/breadcrumb.php', $args);?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

// **********************************************************************// 
// ! Countdown
// **********************************************************************// 
function lt_woocoomerce_countdown() {
    wp_enqueue_script('lt-countdown', get_template_directory_uri() .'/js/countdown.js', array(), false, true);
    wp_localize_script(
        'lt-countdown',
        'lee_countdown_l10n',
        array(
            'days' => 'days',
            'months' => 'Months',
            'weeks' => 'Weeks',
            'years' => 'Years',
            'hours' => 'hours',
            'minutes' => 'mins',
            'seconds' => 'secs',
            'day' => 'day',
            'month' => 'Month',
            'week' => 'Week',
            'year' => 'Year',
            'hour' => 'hour',
            'minute' => 'min',
            'second' => 'sec',
        )
    );
}
add_action('wp_enqueue_scripts','lt_woocoomerce_countdown');

// **********************************************************************// 
// ! Add body class
// **********************************************************************// 
function lt_body_classes( $classes ) {
    global $lt_opt;

    $classes[] = 'antialiased';
    if ( is_multi_author() ) {
        $classes[] = 'group-blog';
    }

    if ($lt_opt['site_layout'] == 'boxed'){
        $classes[] = 'boxed';
    }

    if($lt_opt['promo_popup'] == 1){
        $classes[] = 'open-popup';
    }

    if (LEE_WOOCOMMERCE_ACTIVED && function_exists('is_product')){
        if (is_product() && isset($lt_opt['product-zoom']) && $lt_opt['product-zoom']){
            $classes[] = 'product-zoom';
        }
    }

    return $classes;
}
add_filter( 'body_class', 'lt_body_classes' );

// **********************************************************************// 
// ! Add hr to the widget title
// **********************************************************************// 
function lt_widget_title($title){
    if (!empty($title)){
        return ''.$title.'<span class="bery-hr medium"></span>';
    }   
}
add_filter('widget_title', 'lt_widget_title', 10, 3);

// **********************************************************************// 
// ! Comments
// **********************************************************************//  
if ( ! function_exists( 'lt_comment' ) ) :
function lt_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    switch ( $comment->comment_type ) :
        case 'pingback' :
        case 'trackback' :
    ?>
    <li class="post pingback">
        <p><?php esc_html_e( 'Pingback:', 'altotheme' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( esc_html__( 'Edit', 'altotheme' ), '<span class="edit-link">', '<span>' ); ?></p>
    <?php
            break;
        default :
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <article id="comment-<?php comment_ID(); ?>" class="comment-inner">
            <div class="row collapse">
                <div class="large-2 columns">
                    <div class="comment-author">
                        <?php echo get_avatar( $comment, 80 ); ?>
                    </div>
                </div>
                <div class="large-10 columns">
                    <?php printf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ;?>
                    <div class="comment-meta commentmetadata right">
                        <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time datetime="<?php comment_time( 'c' ); ?>">
                        <?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'altotheme' ), get_comment_date(), get_comment_time() ); ?>
                        </time></a>
                        <?php edit_comment_link( esc_html__( 'Edit', 'altotheme' ), '<span class="edit-link">', '<span>' ); ?>
                    </div>
                    <div class="reply">
                        <?php
                            comment_reply_link( array_merge( $args,array(
                                'depth'     => $depth,
                                'max_depth' => $args['max_depth'],
                            ) ) );
                        ?>
                    </div>
                    <?php if ( $comment->comment_approved == '0' ) : ?>
                        <em><?php esc_html_e( 'Your comment is awaiting moderation.', 'altotheme' ); ?></em>
                        <br />
                    <?php endif; ?>

                    <div class="comment-content"><?php comment_text(); ?></div>
                </div>
            </div>
        </article>
    <?php
        break;
    endswitch;
}
endif; 

// **********************************************************************// 
// ! Post meta top
// **********************************************************************//  
if ( ! function_exists( 'lt_posted_on' ) ) :
function lt_posted_on() {
    $allowed_html = array(
        'span' => array('class' => array()),
        'strong' => array(),
        'a' => array('class' => array(), 'href' => array(), 'title' => array(), 'rel' => array()),
        'time' => array('class' => array(), 'datetime' => array())
    );
    printf(wp_kses(__( '<span class="meta-author">by <strong><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></strong>.</span> Posted on <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>', 'altotheme' ), $allowed_html),
        esc_url( get_permalink() ),
        esc_attr( get_the_time() ),
        esc_attr( get_the_date( 'c' ) ),
        esc_html( get_the_date() ),
        esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
        esc_attr( sprintf( esc_html__( 'View all posts by %s', 'altotheme' ), get_the_author() ) ),
        get_the_author()
    );
}
endif;


// **********************************************************************// 
// ! Promo Popup
// **********************************************************************// 
add_action('after_page_wrapper', 'lt_promo_popup');
if(!function_exists('lt_promo_popup')) {
    function lt_promo_popup() {
        global $lt_opt;
        ?>
        <style type="text/css">
        #lt-popup{
            width: <?php echo (isset($lt_opt['pp_width'])) ? $lt_opt['pp_width'] : 700; ?>px;
            height: <?php echo (isset($lt_opt['pp_height'])) ? $lt_opt['pp_height'] : 350; ?>px;
            background-color: <?php echo (isset($lt_opt['pp_background_color'])) ? $lt_opt['pp_background_color']:''?>;
            background-image: url(<?php echo (isset($lt_opt['pp_background_image'])) ? $lt_opt['pp_background_image']:''?>);
        }
        </style>
        <div id="lt-popup" class="white-popup-block mfp-hide mfp-with-anim zoom-anim-dialog">
            <?php echo (isset($lt_opt['pp_content'])) ? do_shortcode($lt_opt['pp_content']) : '';?>
            <p class="checkbox-label align-center">
                <input type="checkbox" value="do-not-show" name="showagain" id="showagain" class="showagain" />
                <label for="showagain"><?php esc_html_e("Don't show this popup again", 'altotheme'); ?></label>
            </p>
        </div>
    <?php
    }
}

add_filter( 'wp_nav_menu_objects', 'lt_add_menu_parent_class' );
function lt_add_menu_parent_class( $items ) {
    $parents = array();
    foreach ( $items as $item ) {
        if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
            $parents[] = $item->menu_item_parent;
        }
    }

    foreach ( $items as $item ) {
        if ( in_array( $item->ID, $parents ) ) {
            $item->classes[] = 'menu-parent-item';
        }
    }
    
    return $items;
}



function lt_ProductShowReviews(){
    if ( comments_open() ) {
        global $wpdb, $post;
    
        $count = $wpdb->get_var( $wpdb->prepare("
            SELECT COUNT(meta_value) FROM $wpdb->commentmeta
            LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
            WHERE meta_key = %s
            AND comment_post_ID = %s
            AND comment_approved = %s
            AND meta_value > %s",
            'rating', $post->ID, '1', '0'
        ));
    
        $rating = $wpdb->get_var( $wpdb->prepare("
            SELECT SUM(meta_value) FROM $wpdb->commentmeta
            LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
            WHERE meta_key = %s
            AND comment_post_ID = %s
            AND comment_approved = %s",
            'rating', $post->ID, '1'
        ));
    
        if ( $count > 0 ) {
            $average = number_format($rating / $count, 2);
    
            echo '<a href="#tab-reviews" class="scroll-to-reviews"><div class="star-rating tip-top" data-tip="'.$count.' review(s)"><span style="width:'.($average*16).'px"><span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="rating"><span itemprop="ratingValue">'.$average.'</span><span itemprop="reviewCount" class="hidden">'.$count.'</span></span> '.esc_html__('out of 5', 'altotheme').'</span></div></a>';
        }
        
    }
}
add_action('woocommerce_single_product_summary','lt_ProductShowReviews', 15);
add_action('woocommerce_single_review','lt_ProductShowReviews', 10);

function lt_get_adjacent_post_product( $in_same_cat = false, $excluded_categories = '', $previous = true ) {
    global $wpdb;

    if ( ! $post = get_post() )
        return null;

    $current_post_date = $post->post_date;
    $join = '';
    $posts_in_ex_cats_sql = '';
    if ( $in_same_cat || ! empty( $excluded_categories ) ) {
        $join = " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";

        if ( $in_same_cat ) {
            if ( ! is_object_in_taxonomy( $post->post_type, 'product_cat' ) )
                return '';
            $cat_array = wp_get_object_terms($post->ID, 'product_cat', array('fields' => 'ids'));
            if ( ! $cat_array || is_wp_error( $cat_array ) )
                return '';
            $join .= " AND tt.taxonomy='product_cat' AND tt.term_id IN (" . implode(',', $cat_array) . ")";
        }

        $posts_in_ex_cats_sql = "AND tt.taxonomy = 'product_cat'";
        if ( ! empty( $excluded_categories ) ) {
            if ( ! is_array( $excluded_categories ) ) {
                if ( strpos( $excluded_categories, ' and ' ) !== false ) {
                    _deprecated_argument( __FUNCTION__, '3.3', esc_html_e('Use commas instead of and to separate excluded categories.','altotheme'));
                    $excluded_categories = explode( ' and ', $excluded_categories );
                } else {
                    $excluded_categories = explode( ',', $excluded_categories );
                }
            }

            $excluded_categories = array_map( 'intval', $excluded_categories );

            if ( ! empty( $cat_array ) ) {
                $excluded_categories = array_diff($excluded_categories, $cat_array);
                $posts_in_ex_cats_sql = '';
            }

            if ( !empty($excluded_categories) ) {
                $posts_in_ex_cats_sql = " AND tt.taxonomy = 'product_cat' AND tt.term_id NOT IN (" . implode($excluded_categories, ',') . ')';
            }
        }
    }

    $adjacent = $previous ? 'previous' : 'next';
    $op = $previous ? '<' : '>';
    $order = $previous ? 'DESC' : 'ASC';

    $join  = apply_filters( "get_{$adjacent}_post_join", $join, $in_same_cat, $excluded_categories );
    $where = apply_filters( "get_{$adjacent}_post_where", $wpdb->prepare("WHERE p.post_date $op %s AND p.post_type = %s AND p.post_status = 'publish' $posts_in_ex_cats_sql", $current_post_date, $post->post_type), $in_same_cat, $excluded_categories );
    $sort  = apply_filters( "get_{$adjacent}_post_sort", "ORDER BY p.post_date $order LIMIT 1" );

    $query = "SELECT p.id FROM $wpdb->posts AS p $join $where $sort";
    $query_key = 'adjacent_post_' . md5($query);
    $result = wp_cache_get($query_key, 'counts');
    if ( false !== $result ) {
        if ( $result )
            $result = get_post( $result );
        return $result;
    }
    
    $result = $wpdb->get_var( $wpdb->prepare($query) );
    if ( null === $result )
        $result = '';

    wp_cache_set($query_key, $result, 'counts');

    if ( $result )
        $result = get_post( $result );

    return $result;
}

// **********************************************************************// 
// ! Blog - Add "Read more" links
// **********************************************************************// 
function lt_add_morelink_class($link, $text){
    return str_replace('more-link', 'more-link button small', $link);
}
add_action( 'the_content_more_link', 'lt_add_morelink_class', 10, 2 );

// **********************************************************************// 
// ! Language Flags
// **********************************************************************//
add_action('lt_language_switcher','lt_language_flages', 1);
if (!function_exists('lt_language_flages')){
    function lt_language_flages(){
        $language_output = '<ul>';
        if (function_exists('icl_get_languages')){
            $languages = icl_get_languages('skip_missing=0&orderby=code');
            if (!empty($languages)){
                foreach($languages as $l){
                    // if(!$l['active']) $language_output .= '<li><a href="'.$l['url'].'">';
                    $language_output.='<li><a href="'.$l['url'].'"><img src="'.$l['country_flag_url'].'" height="12" alt="'.$l['language_code'].'" width="24" /></a></li>';
                    // if(!$l['active']) $language_output.='</a></li>';
                }
            }
        }else{
            $language_output .= '
                <li><a href="#"><img src="'.get_template_directory_uri().'/images/flag_icons/en_flag.jpg'.'" alt=""></a></li>
                <li><a href="#"><img src="'.get_template_directory_uri().'/images/flag_icons/gr_flag.jpg'.'" alt=""></a></li>
                <li><a href="#"><img src="'.get_template_directory_uri().'/images/flag_icons/fr_flag.jpg'.'" alt=""></a></li>
            ';
        }
        $language_output .= '</ul>';
        echo $language_output;
    }
}

// **********************************************************************// 
// ! Product Quick View
// **********************************************************************// 
add_action('wp_head', 'lt_wpse83650_lazy_ajax', 0, 0);
function lt_wpse83650_lazy_ajax(){
    echo '<script type="text/javascript">var ajaxurl="'.esc_js(admin_url('admin-ajax.php')).'";</script>';
}

add_action('wp_ajax_jck_quickview', 'lt_jck_quickview');
add_action('wp_ajax_nopriv_jck_quickview', 'lt_jck_quickview');
function lt_jck_quickview() {
    global $post, $product, $woocommerce;
    $prod_id = $_POST["product"];
    $post = get_post($prod_id);
    $product = get_product($prod_id);
    //ob_start();
    woocommerce_get_template('content-single-product-lightbox.php');
    //$output = ob_get_contents();
    //ob_end_clean();
    
    //echo $output;
    die();
}

add_action( 'woocommerce_single_product_lightbox_summary', 'woocommerce_template_single_price', 10 );
add_action( 'woocommerce_single_product_lightbox_summary', 'woocommerce_template_single_excerpt', 20 );
if ( isset($lt_opt['disable-cart']) && !$lt_opt['disable-cart']){
    add_action( 'woocommerce_single_product_lightbox_summary', 'woocommerce_template_single_add_to_cart', 30 );
}
add_action( 'woocommerce_single_product_lightbox_summary', 'woocommerce_template_single_sharing', 40 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 20 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 25 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 35 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

if(isset($_GET["catalog-mode"])){
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
    remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
    remove_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
    remove_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
    remove_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );
    remove_action( 'woocommerce_single_product_lightbox_summary', 'woocommerce_template_single_add_to_cart', 30 );

    if(isset($_GET["catalog-mode"])){
        remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
        remove_action( 'woocommerce_single_product_lightbox_summary', 'woocommerce_template_single_price', 10 );
    }

    function lt_catalog_mode_product(){
        global $lt_opt;
        echo '<div class="catalog-product-text">';
        echo do_shortcode($lt_opt['catalog_mode_product']);
        echo '</div>';
    }
    add_action('woocommerce_single_product_summary', 'lt_catalog_mode_product', 30);

    function lt_catalog_mode_lightbox(){
        global $lt_opt;
        echo '<div class="catalog-product-text">';
        echo do_shortcode($lt_opt['catalog_mode_lightbox']);
        echo '</div>';
    }
    add_action( 'woocommerce_single_product_lightbox_summary', 'lt_catalog_mode_lightbox', 30 );
}

function lt_pre_get_posts_action( $query ) {
    global $lt_opt;
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    if($action == 'woocommerce_json_search_products') {
        return;
    }
    if(defined('DOING_AJAX') && DOING_AJAX && !empty($query->query_vars['s'])){
        if(isset($query->query_vars['post_type'])) 
            $query->query_vars['post_type'] = array( $query->query_vars['post_type'], 'post', 'page' );
        if(isset($query->query_vars['meta_query'])) 
            $query->query_vars['meta_query'] = new WP_Meta_Query( array( 'relation' => 'OR', $query->query_vars['meta_query'] ) );
    }
}
add_action('pre_get_posts', 'lt_pre_get_posts_action');

function lt_posts_results_filter( $posts, $query ) {
    global $lt_opt;
    if (defined('DOING_AJAX') && DOING_AJAX && !empty($query->query_vars['s'])) {
        foreach ($posts as $key => $post) {
            foreach (array('myaccount', 'edit_address', 'change_password', 'lost_password', 'shop', 'cart', 'checkout', 'pay', 'view_order', 'thanks', 'terms') as $wc_page_type) {
                if( $post->ID == woocommerce_get_page_id($wc_page_type) ) unset($posts[$key]);
            }
        }
    }
    return $posts;
}
add_filter( 'posts_results', 'lt_posts_results_filter', 10, 2 );

// This extending class is for solving a problem when "getElementById()" returns NULL
class LTDOMDocument extends DOMDocument {
    function getElementById( $id ) {
        //thanks to: http://www.php.net/manual/en/domdocument.getelementbyid.php#96500
        $xpath = new DOMXPath( $this );
        return $xpath->query( "//*[@id='$id']" )->item(0);
    }

    function output() {
        // thanks to: http://www.php.net/manual/en/domdocument.savehtml.php#85165
        $output = preg_replace( '/^<!DOCTYPE.+?>/', '',
            str_replace( 
                array('<html>', '</html>', '<body>', '</body>'),
                array('', '', '', ''), $this->saveHTML()
            )
        );

        return trim( $output );
    }
}

function lt_pagination($page = '', $range = 2, $page_total = ''){
    global $paged;
    
    $showitems = ($range * 2)+1; 
    if(empty($paged))
        $paged = 1;

    if($pages == ''){
        $pages = $page_total;
        if(!$pages)
            $pages = 1;
    }   

    if(1 != $pages){
        //echo "<div class='pagination'>";
        if($paged > 2 && $paged > $range+1 && $showitems < $pages)
            echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
        if($paged > 1 && $showitems < $pages)
            echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";

        for ($i=1; $i <= $pages; $i++){
            if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
                echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
        }

        if ($paged < $pages && $showitems < $pages)
            echo "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";  
        if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages)
            echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
        //echo "</div>\n";
    }
}

/*==========================================================================
 ADD VIDEO PLAY BUTTON ON PRODUCT DETAIL PAGE
==========================================================================*/
if(!function_exists('lt_product_video_btn_function')){
    function lt_product_video_btn_function(){
        global $wc_cpdf;
        if($wc_cpdf->get_value(get_the_ID(), '_product_video_link')){ ?>
            <a class="product-video-popup tip-top" data-tip="<?php esc_html_e('View video','altotheme'); ?>" href="<?php echo $wc_cpdf->get_value(get_the_ID(), '_product_video_link'); ?>"><span class="fa fa-play"></span><?php esc_html_e('Play Video','altotheme'); ?></a>
            <?php
            $height = '800';
            $width = '800';
            $iframe_scale = '100%';
            $custom_size = $wc_cpdf->get_value(get_the_ID(), '_product_video_size');
            if($custom_size){
                $split = explode("x", $custom_size);
                $height = $split[0];
                $width = $split[1];
                $iframe_scale = ($width/$height*100).'%';
            }
            $style = '.has-product-video .mfp-iframe-holder .mfp-content{max-width: '.$width.'px;}';
            $style .= '.has-product-video .mfp-iframe-scaler{padding-top: '.$iframe_scale.'}';
            wp_add_inline_style('product_detail_css_custom', $style);
        }
    }
}
add_action('product_video_btn','lt_product_video_btn_function', 1);

/* ======================================================================= */
/* NEXT - PREV PRODUCTS */
/* ======================================================================= */
add_action('next_prev_product', 'lt_prev_product');
add_action('next_prev_product', 'lt_next_product');

/* NEXT / PREV NAV ON PRODUCT PAGES */
function lt_next_product() {
    $next_post = get_next_post(true,'','product_cat');
    if ( is_a( $next_post , 'WP_Post' ) ) { 
        $product_obj = new WC_Product($next_post->ID);
        ?>
        <div class="next-product next-prev-buttons">
            <a href="<?php echo get_the_permalink( $next_post->ID ); ?>" rel="next" class="icon-next-prev icon-angle-right next" title="<?php echo get_the_title( $next_post->ID ); ?>"></a>
            <div class="dropdown-wrap">
                <a title="<?php echo get_the_title( $next_post->ID ); ?>" href="<?php echo get_the_permalink( $next_post->ID ); ?>">
                    <?php echo get_the_post_thumbnail($next_post->ID, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' )); ?>
                </a>
                <div>
                    <span class="product-name"><?php echo get_the_title( $next_post->ID ); ?></span>
                    <span class="price"><?php echo $product_obj->get_price_html(); ?></span>
                </div>
            </div>
        </div>
    <?php
    }
}

function lt_prev_product() {
    $prev_post = get_previous_post(true,'','product_cat');
    if ( is_a( $prev_post , 'WP_Post' ) ) {
        $product_obj = new WC_Product($prev_post->ID);
        ?>
        <div class="prev-product next-prev-buttons">
            <a href="<?php echo get_the_permalink( $prev_post->ID ); ?>" rel="prev" class="icon-next-prev icon-angle-left prev" title="<?php echo get_the_title( $prev_post->ID ); ?>"></a>
            <div class="dropdown-wrap">
                <a title="<?php echo get_the_title( $prev_post->ID ); ?>" href="<?php echo get_the_permalink( $prev_post->ID ); ?>">
                    <?php echo get_the_post_thumbnail($prev_post->ID, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' )); ?>
                </a>
                <div>
                    <span class="product-name"><?php echo get_the_title( $prev_post->ID ); ?></span>
                    <span class="price"><?php echo $product_obj->get_price_html(); ?></span>
                </div>
            </div>
        </div>
    <?php
    }
}

// Ajax search
function lt_live_search_products(){
    //var_dump($_GET);
    if ( isset( $_GET['s'] ) && trim( $_GET['s'] ) != '' ) {
        $query_args = array(
            'posts_per_page' 	=> 10,
            'no_found_rows' 	=> true,
            //'post_type'         => 'product',
            's'                 => $_GET['s']
        );
        
        $search_query = new WP_Query( $query_args );
        $search_query->set('post_type', 'product');
        
        $results = array( );
        
        if ( $the_posts = $search_query->get_posts() ) {
            foreach ( $the_posts as $the_post ) {
                $title = get_the_title( $the_post->ID );
                if ( has_post_thumbnail( $the_post->ID ) ) {
                    $post_thumbnail_ID = get_post_thumbnail_id( $the_post->ID );
                    $post_thumbnail_src = wp_get_attachment_image_src( $post_thumbnail_ID, 'thumbnail' );
                }else{
                    $size = wc_get_image_size( 'thumbnail' );
                    $post_thumbnail_src = array(
                        wc_placeholder_img_src(),
                        esc_attr( $size['width'] ),
                        esc_attr( $size['height'] )
                    );
                }

                $product = new WC_Product( $the_post->ID );

                $results[] = array(
                    'title'     => html_entity_decode( $title, ENT_QUOTES, 'UTF-8' ),
                    'tokens' 	=> explode( ' ', $title ),
                    'url'       => get_permalink( $the_post->ID ),
                    'image'     => $post_thumbnail_src[0],
                    'price'     => $product->get_price_html()
                );
            }
        }

        wp_reset_postdata();
        
        die(json_encode( $results ));
    }
}

add_action('wp_head', 'lt_search_live_options', 0, 0);
function lt_search_live_options(){
    global $lt_opt;
    
    if( $enable = isset($lt_opt['enable_live_search']) ? $lt_opt['enable_live_search'] : true ) {
        wp_enqueue_script( 'lt-typeahead-js', get_template_directory_uri() . '/js/min/typeahead.bundle.min.js', array( 'jquery' ), '', true );
        wp_enqueue_script( 'lt-handlebars', get_template_directory_uri() . '/js/min/handlebars.min.js', array( 'lt-typeahead-js' ), '', true );
    }
    
    $search_options = array(
        'live_search_template'	=> '<div class="item-search"><a href="{{url}}" class="lt-link-item-search" title="{{title}}"><img src="{{image}}" class="lt-item-image-search" height="60" width="60" /><div class="lt-item-title-search"><p>{{title}}</p></div></a></div>',
        'enable_live_search'	=> $enable,
        //'ajax_loader_url'	=> get_template_directory_uri() . '/uploads/ajax-loader.gif',
    );
    echo ($enable) ? '<script type="text/javascript">var search_options='.json_encode($search_options).';</script>' : '';
}
add_action( 'wp_ajax_nopriv_live_search_products', 'lt_live_search_products' );
add_action( 'wp_ajax_live_search_products', 'lt_live_search_products' );


/* Change category image size */
// unload the original one
remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail');
// load mine
add_action( 'woocommerce_before_subcategory_title', 'lt_woocommerce_subcategory_thumbnail', 10);
if ( ! function_exists( 'lt_woocommerce_subcategory_thumbnail' ) ) {
    function lt_woocommerce_subcategory_thumbnail( $category  ) {
        global $woocommerce;
        $small_thumbnail_size   = apply_filters( 'single_product_small_thumbnail_size', 'lt-category-thumb' );
        $dimensions             = wc_get_image_size( $small_thumbnail_size );
        $thumbnail_id           = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true  );

        if ( $thumbnail_id ) {
            $image = wp_get_attachment_image_src( $thumbnail_id, $small_thumbnail_size  );
            $image = $image[0];
        } else {
            $image = wc_placeholder_img_src();
        }

        if ( $image ) {
            // Prevent esc_url from breaking spaces in urls for image embeds
            // Ref: http://core.trac.wordpress.org/ticket/23605
            $image = str_replace( ' ', '%20', $image );

            echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" />';
        }
    }
}

/* Mobile header */
if ( ! function_exists('lt_mobile_header')){
    function lt_mobile_header(){?>
        <a href="javascript:void(0);" class="mobile_toggle"><span class="icon-menu"></span></a>
        <a class="icon pe-7s-search mobile-search" href="javascript:void(0);"></a>
        
        <div class="logo-wrapper">
            <?php lt_logo();?>
        </div>
        
        <div class="mini-cart">
            <?php lt_mini_cart('simple');?>
        </div>
    <?php
    }
}

/* cut string limit */
if(!function_exists('lt_limit_words')){
    function lt_limit_words($string, $word_limit) {
        $words = explode(' ', $string, ($word_limit + 1));
        if(count($words) <= $word_limit){
            return $string;
        }
        array_pop($words);
        return implode(' ', $words) . ' ...';
    }
}

// Product group button
function lt_product_group_button(){
    global $lt_opt, $post, $product, $wp_query;
    $_cart_btn = lt_add_to_cart_btn('small', false);
    $head_type = $lt_opt['header-type'];
    if (isset($post->ID)){
        $custom_header = get_post_meta($wp_query->get_queried_object_id(), '_lee_custom_header', true);
        if (!empty($custom_header)){
            $head_type = $custom_header;
        }
    }
    
    include get_template_directory() . '/woocommerce/customs/product-group-button.php';
}
add_action('lt_product_group_button', 'lt_product_group_button');

/* Remove WP Version Param From Any Enqueued Scripts */
if (!isset($lt_opt['cache_version']) || $lt_opt['cache_version'] == ''){
    if( !function_exists('lt_remove_wp_ver_css_js') ){
        function lt_remove_wp_ver_css_js( $src ) {
            if ( strpos( $src, 'ver=' ) ){
                $src = esc_url( remove_query_arg( 'ver', $src ) );
            }
            return $src;
        }
    }
    add_filter( 'style_loader_src', 'lt_remove_wp_ver_css_js', 9999 );
    add_filter( 'script_loader_src', 'lt_remove_wp_ver_css_js', 9999 );
}

// **********************************************************************// 
// ! Blog post navigation
// **********************************************************************//  
if ( ! function_exists( 'lt_content_nav' ) ) {
    function lt_content_nav( $nav_id ) {
        global $wp_query, $post;
        $allowed_html = array(
            'span' => array('class' => array())
        );

        if ( is_single() ) {
            $previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
            $next = get_adjacent_post( false, '', false );

            if ( ! $next && ! $previous )
                return;
        }

        if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
            return;

        $nav_class = ( is_single() ) ? 'navigation-post' : 'navigation-paging';

        ?>
        <nav role="navigation" id="<?php echo esc_attr($nav_id); ?>" class="<?php echo esc_attr($nav_class); ?>">
        <?php if ( is_single() ) :?>

            <?php previous_post_link( '<div class="nav-previous left">%link</div>', '<span class="fa fa-caret-left">' . _x( '', 'Previous post link', 'altotheme' ) . '</span> %title' ); ?>
            <?php next_post_link( '<div class="nav-next right">%link</div>', '%title <span class="fa fa-caret-right">' . _x( '', 'Next post link', 'altotheme' ) . '</span>' ); ?>

        <?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

            <?php if ( get_next_posts_link() ) : ?>
                <div class="nav-previous"><?php next_posts_link(wp_kses(__( 'Next <span class="fa fa-caret-right"></span>', 'altotheme' ), $allowed_html) ); ?></div>
            <?php endif; ?>

            <?php if ( get_previous_posts_link() ) : ?>
                <div class="nav-next"><?php previous_posts_link(wp_kses(__( '<span class="fa fa-caret-left"></span> Previous' , 'altotheme' ), $allowed_html) ); ?></div>
            <?php endif; ?>

        <?php endif; ?>

        </nav>
        <?php
    }
}

//Add shortcode Top bar Promotion news
function lt_promotion_recent_post(){
    global $lt_opt;
    
    if(isset($lt_opt['enable_post_top']) && !$lt_opt['enable_post_top']){
        return '';
    }
    
    $content = '';
    $posts = null;
    $_id = rand();
    if(!isset($lt_opt['type_display']) || $lt_opt['type_display'] == 'My content custom'){
        $content = $lt_opt['content_custom'];
    }elseif (isset($lt_opt['type_display']) && $lt_opt['type_display'] == 'List posts') {
        if(!isset($lt_opt['category_post']) || !$lt_opt['category_post']){
            $lt_opt['category_post'] = null;
        }

        if(!isset($lt_opt['number_post']) || !$lt_opt['number_post']){
            $lt_opt['number_post'] = 4;
        }

        $args = array(
            'post_status'       => 'publish',
            'post_type'         => 'post',
            'orderby'           => 'date',
            'order'             => 'DESC',
            'category'          => ((int)$lt_opt['category_post'] != 0) ? (int)$lt_opt['category_post'] : null,
            'posts_per_page'    => $lt_opt['number_post']
        );

        $posts = get_posts( $args );
    }
    
    include get_template_directory() . '/includes/blogs_layout/lt_blogs_carousel.php';
}
//add_shortcode("lt_promotion_recent_post", "lt_promotion_recent_post");

