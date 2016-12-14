<?php
// **********************************************************************// 
// ! Header Type
// **********************************************************************// 
function lt_get_header_type() {
    global $lt_opt;
    if (isset($lt_opt['header-type'])) {return $lt_opt['header-type'];}
}
add_filter('custom_header_filter', 'lt_get_header_type', 10);

function lt_get_header_structure($ht) {
    switch ($ht) {
        case 1:
            return 1;
            break;
        case 2:
            return 2;
            break;
        case 3:
            return 3;
            break;
        case 4:
            return 4;
            break;
        case 5:
            return 5;
            break;
        case 6:
            return 6;
            break;
        case 7:
            return 7;
            break;
        case 8:
            return 8;
            break;
        default:
            return 1;
            break;
    }
}

function lt_get_header_theme(){
    global $woocommerce, $woo_options, $lt_opt, $post, $wp_query;
    
    include_once get_template_directory() . '/headers/header-main.php';
}
add_action('lt_get_header_theme', 'lt_get_header_theme');

// **********************************************************************// 
// ! Footer Type
// **********************************************************************//
add_action('lt_footer_layout_style', 'lt_footer_layout_style_function');
function lt_footer_layout_style_function(){
    global $lt_opt, $wp_query;
    
    if(!isset($lt_opt['footer-type']) || !$lt_opt['footer-type']){
        $pageid = $wp_query->get_queried_object_id();
        $footer_id = get_post_meta($pageid, '_lee_custom_footer', true);
    }else{
        $footer_id = $lt_opt['footer-type'];
    }
    
    if(!(int)$footer_id){
        return get_template_part('templates/footer/default');
    }
    
    echo do_shortcode(get_post($footer_id)->post_content);
}

// **********************************************************************// 
// ! Fix IE
// **********************************************************************// 
function lt_add_ieFix() {
    $ie_css = get_template_directory_uri() .'/css/ie-fix.css';
    echo '<!--[if lt IE 9]>';
    echo '<link rel="stylesheet" type="text/css" href="'.$ie_css.'" />';
    echo '<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>';
    echo "<script>var head = document.getElementsByTagName('head')[0],style = document.createElement('style');style.type = 'text/css';style.styleSheet.cssText = ':before,:after{content:none !important';head.appendChild(style);setTimeout(function(){head.removeChild(style);}, 0);</script>";
    echo '<![endif]-->';
}
add_action('wp_head', 'lt_add_ieFix');

// **********************************************************************// 
// ! Remove message Woocommerce
// **********************************************************************//
function lt_remove_upgrade_nag() {
    global $lt_opt;
    if (isset($lt_opt['plugin_update_notice']) && $lt_opt['plugin_update_notice']){
        echo '<style type="text/css">' .
            '.woocommerce-message.updated, .plugin-update-tr, .rs-update-notice-wrap {display: none}' .
        '</style>';
    }
}
add_action('admin_head', 'lt_remove_upgrade_nag');

// **********************************************************************// 
// ! Escape HTML in post and comments
// **********************************************************************// 
// Escape HTML tags in post content
add_filter('the_content', 'lt_escape_code_fragments');

// Escape HTML tags in comments
add_filter('pre_comment_content', 'lt_escape_code_fragments');

function lt_escape_code_fragments($source) {
    $encoded = preg_replace_callback(
        '/<script(.*?)>(.*?)<\/script>/ims',
        create_function(
            '$matches',
            '$matches[2] = preg_replace(
                array("/^[\r|\n]+/i", "/[\r|\n]+$/i"), "",
                $matches[2]);
            return "<pre" . $matches[1] . ">" . esc_html( $matches[2] ) . "</pre>";'
        ),
        $source
    );
    
    return $encoded ? $encoded : $source;
}

// **********************************************************************// 
// ! Remove Plugin update
// **********************************************************************// 
//add_action('admin_menu','lt_wphidenag');
function lt_wphidenag() {
    remove_action( 'admin_notices', 'update_nag', 3 );
    remove_filter( 'update_footer', 'core_update_footer' );
}

// **********************************************************************// 
// ! Filter add  property='stylesheet' to the wp enqueue style
// **********************************************************************//
function lt_mycustom_wpenqueue( $src ){
    return str_replace("rel='stylesheet'", "rel='stylesheet' property='stylesheet'", $src);
}
add_filter('style_loader_tag', 'lt_mycustom_wpenqueue');

// **********************************************************************// 
// ! Filter add LT_NavDropdown to the widget Custom Menu
// **********************************************************************// 
function lt_myplugin_custom_walker( $args ) {
    return array_merge( $args, array(
        //'walker' => new LT_NavDropdown()
    ) );
}
add_filter( 'wp_nav_menu_args', 'lt_myplugin_custom_walker' );

// **********************************************************************// 
// ! Add Logout URL
// **********************************************************************// 
function lt_new_logout_url($logouturl, $redir) {
    $redir = get_option('siteurl');
    return $logouturl . '&amp;redirect_to=' . urlencode($redir);
}
add_filter('logout_url', 'lt_new_logout_url', 10, 2);

// **********************************************************************// 
// ! Add Font Awesome, Font Pe7s, Font Elegant
// **********************************************************************// 
function lt_add_font_awesome() {   
    wp_register_style('lt-font-awesome-style', get_template_directory_uri() . '/css/font-awesome-4.2.0/css/font-awesome.min.css');
    wp_enqueue_style('lt-font-awesome-style');
}
add_action('wp_enqueue_scripts', 'lt_add_font_awesome');

function lt_add_font_pe7s() {   
    wp_register_style('lt-font-pe7s-style', get_template_directory_uri() . '/css/pe-icon-7-stroke/css/pe-icon-7-stroke.css');
    wp_register_style('lt-font-pe7s-helper-style', get_template_directory_uri() . '/css/pe-icon-7-stroke/css/helper.css');
    wp_enqueue_style('lt-font-pe7s-style');
    wp_enqueue_style('lt-font-pe7s-helper-style');
}
add_action('wp_enqueue_scripts', 'lt_add_font_pe7s');

function lt_add_font_flaticon(){
    wp_register_style('lt-font-flaticon', get_template_directory_uri() . '/css/font-flaticon/flaticon.css');
    wp_enqueue_style('lt-font-flaticon');
}
add_action('wp_enqueue_scripts','lt_add_font_flaticon');

// **********************************************************************// 
// ! Other functions
// **********************************************************************// 
function lt_enhanced_image_navigation( $url, $id ) {
    if ( ! is_attachment() && ! wp_attachment_is_image( $id ) )
        return $url;

    $image = get_post( $id );
    if ( ! empty( $image->post_parent ) && $image->post_parent != $id )
        $url .= '#main';

    return $url;
}
add_filter( 'attachment_link', 'lt_enhanced_image_navigation', 10, 2 );

if(function_exists('get_term_meta')){
    function lt_pippin_taxonomy_edit_meta_field($term) {
        $t_id = $term->term_id;
        if(!$term_meta = get_term_meta($t_id, 'cat_meta'))
            $term_meta = add_term_meta($t_id, 'cat_meta', '');?>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="term_meta[cat_header]"><?php esc_html_e( 'Top Content', 'altotheme' ); ?></label>
            </th>
            <td>                
                <?php
                $content = (isset($term_meta[0]['cat_header']) && esc_attr( $term_meta[0]['cat_header'] )) ? 
                    esc_attr( $term_meta[0]['cat_header'] ) : ''; 
                echo '<textarea id="term_meta[cat_header]" name="term_meta[cat_header]">'.$content.'</textarea>';
                ?>
                <p class="description"><?php esc_html_e( 'Enter a value for this field. Shortcodes are allowed. This will be displayed at top of the category.','altotheme' ); ?></p>
            </td>
        </tr>
    <?php
    }
    add_action( 'product_cat_edit_form_fields', 'lt_pippin_taxonomy_edit_meta_field', 10, 2 );

    function lt_save_taxonomy_custom_meta( $term_id ) {
        if ( isset( $_POST['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = get_term_meta($t_id,'cat_meta');
            $cat_keys = array_keys( $_POST['term_meta'] );
            foreach ( $cat_keys as $key ) {
                if ( isset ( $_POST['term_meta'][$key] ) ) {
                    $term_meta[$key] = $_POST['term_meta'][$key];
                }
            }
            update_term_meta($term_id, 'cat_meta', $term_meta);

        }
    }  
    add_action( 'edited_product_cat', 'lt_save_taxonomy_custom_meta', 10, 2 );
}

if(!is_home()) {
    function lt_share_meta_head() {
        global $post; ?>
        <meta property="og:title" content="<?php the_title(); ?>" />
        <?php if (isset($post->ID)){ ?>
            <?php if (has_post_thumbnail( $post->ID ) ): ?>
                <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
                <meta property="og:image" content="<?php echo $image[0]; ?>" />
            <?php endif; ?>
        <?php } ?>
        <meta property="og:url" content="<?php the_permalink(); ?>" />
    <?php 
    }
    add_action('wp_head', 'lt_share_meta_head');
}

function lt_short_excerpt($limit) {
    $excerpt = explode(' ', get_the_excerpt(), $limit);
    $count = count($excerpt);
    if ($count >= $limit) {
        array_pop($excerpt);
        $excerpt = implode(" ",$excerpt).'...';
    } else {
        $excerpt = implode(" ",$excerpt);
    } 
    $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
    return $excerpt;
}

function lt_content($limit) {
    $content = explode(' ', get_the_content(), $limit);
    $count = count($content);
    if ($count >= $limit) {
        array_pop($content);
        $content = implode(" ",$content).'...';
    } else {
        $content = implode(" ",$content);
    } 
    $content = preg_replace('/\[.+\]/','', $content);
    $content = apply_filters('the_content', $content); 
    $content = str_replace(']]>', ']]&gt;', $content);
    return $content;
}

function lt_hex2rgba($color, $opacity = false) {
    $default = 'rgb(0,0,0)';
    if(empty($color))
        return $default; 
    if ($color[0] == '#' ) {
        $color = substr( $color, 1 );
    }

    if (strlen($color) == 6) {
        $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
    } elseif ( strlen( $color ) == 3 ) {
        $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
    } else {
        return $default;
    }

    $rgb = array_map('hexdec', $hex);

    if($opacity){
        if(abs($opacity) > 1)
            $opacity = 1.0;
        $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
    } else {
        $output = 'rgb('.implode(",",$rgb).')';
    }

    return $output;
}

add_filter('sod_ajax_layered_nav_product_container', 'lt_bery_product_container');
function lt_bery_product_container($product_container){
    return 'ul.products';
}