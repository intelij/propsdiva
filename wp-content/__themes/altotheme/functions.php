<?php
/**
 *
 * @package leetheme
 */

/* Check if WooCommerce is active */
define( 'LEE_WOOCOMMERCE_ACTIVED', in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) );
/* Check if Lee_framework is active */
define( 'LEE_FRAMEWORK_ACTIVED', in_array( 'lee_framework/lee_framework.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) );

ob_start();

if ( ! isset( $content_width ) ) $content_width = 1000; /* pixels */
require_once(get_template_directory() . '/admin/index.php');

/************ Plugin recommendations **********/
require_once (get_template_directory() . '/includes/class-tgm-plugin-activation.php');
add_action( 'tgmpa_register', 'lt_register_required_plugins' );
function lt_register_required_plugins() {
    $plugins = array(
        array(
            'name'     			=> esc_html__('WooCommerce', 'altotheme'), 
            'slug'     			=> 'woocommerce',
            'source'   			=> get_template_directory() . '/includes/plugins/woocommerce.zip', 
            'required' 			=> true,
            'version' 			=> '2.5.0', 
            'force_activation' 		=> false,
            'force_deactivation' 	=> false,
            'external_url' 		=> '',
        ),
        array(
            'name'     			=> esc_html__('Lee Framework', 'altotheme'), 
            'slug'     			=> 'lee_framework',
            'source'   			=> get_template_directory() . '/includes/plugins/lee_framework.zip',
            'required' 			=> true,
            'version' 			=> '1.0',
            'force_activation' 	=> false,
            'force_deactivation' => false,
            'external_url' 		=> '',
        ),
        array(
            'name'     			=> esc_html__('WPBakery Visual Composer', 'altotheme'), 
            'slug'     			=> 'js_composer',
            'source'   			=> get_template_directory() . '/includes/plugins/js_composer.zip',
            'required' 			=> true,
            'version' 			=> '4.11.2',
            'force_activation' 	=> false,
            'force_deactivation' => false,
            'external_url' 		=> '',
        ),
        array(
            'name'     			=> esc_html__('Ninja Forms', 'altotheme'), 
            'slug'     			=> 'ninja-forms',
            'source'   			=> get_template_directory() . '/includes/plugins/ninja-forms.zip',
            'required' 			=> true,
            'version' 			=> '2.9.31',
            'force_activation' 	=> false,
            'force_deactivation' => false,
            'external_url' 		=> '',
        ),
        array(
            'name'     			=> esc_html__('Taxonomy Metadata', 'altotheme'), 
            'slug'     			=> 'taxonomy-metadata',
            'source'   			=> get_template_directory() . '/includes/plugins/taxonomy-metadata.zip',
            'required' 			=> true,
            'version' 			=> '0.5',
            'force_activation' 	=> false,
            'force_deactivation' => false,
            'external_url' 		=> '',
        ),
        array(
            'name'     			=> esc_html__('Unlimited Sidebars Woosidebars', 'altotheme'), 
            'slug'     			=> 'woosidebars',
            'source'   			=> get_template_directory() . '/includes/plugins/woosidebars.zip',
            'required' 			=> true,
            'version' 			=> '1.4.3',
            'force_activation' 	=> false,
            'force_deactivation' => false,
            'external_url' 		=> '',
        ),
        array(
            'name'     			=> esc_html__('YITH WooCommerce Wishlist', 'altotheme'), 
            'slug'     			=> 'yith-woocommerce-wishlist',
            'source'   			=> get_template_directory() . '/includes/plugins/yith-woocommerce-wishlist.zip',
            'required' 			=> true,
            'version' 			=> '2.0.13',
            'force_activation' 	=> false,
            'force_deactivation' => false,
            'external_url' 		=> '',
        ),
        array(
            'name'     			=> esc_html__('Regenerate Thumbnails', 'altotheme'), 
            'slug'     			=> 'regenerate-thumbnails',
            'source'   			=> get_template_directory() . '/includes/plugins/regenerate-thumbnails.zip',
            'required' 			=> true,
            'version' 			=> '2.2.5',
            'force_activation' 	=> false,
            'force_deactivation' => false,
            'external_url' 		=> '',
        ),
        array(
            'name'     			=> esc_html__('WP Instagram Widget', 'altotheme'), 
            'slug'     			=> 'wp-instagram-widget',
            'source'   			=> get_template_directory() . '/includes/plugins/wp-instagram-widget.zip',
            'required' 			=> true,
            'version' 			=> '1.9.1',
            'force_activation' 	=> false,
            'force_deactivation' => false,
            'external_url' 		=> '',
        )
    );
    
    $config = array(
        'domain'       	=> 'altotheme',         	// Text domain - likely want to be the same as your theme.
        'default_path' 	=> '',                         	// Default absolute path to pre-packaged plugins
        'parent_slug' 	=> 'themes.php', 		// Default parent menu slug
        'menu'         	=> 'install-required-plugins', 	// Menu slug
        'has_notices'   => true,                       	// Show admin notices or not
        'is_automatic'  => false,		// Automatically activate plugins after installation or not
        'message'       => '',				// Message to output right before the plugins table
        'strings'      	=> array(
            'page_title'    => esc_html__( 'Install Required Plugins', 'altotheme' ),
            'menu_title'    => esc_html__( 'Install Plugins', 'altotheme' ),
            'installing'    => esc_html__( 'Installing Plugin: %s', 'altotheme' ), // %1$s = plugin name
            'oops'          => esc_html__( 'Something went wrong with the plugin API.', 'altotheme' ),
            'notice_can_install_required' => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'altotheme' ), // %1$s = plugin name(s)
            'notice_can_install_recommended' => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'altotheme' ), // %1$s = plugin name(s)
            'notice_cannot_install' => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'altotheme' ), // %1$s = plugin name(s)
            'notice_can_activate_required' => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' , 'altotheme'), // %1$s = plugin name(s)
            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'altotheme' ), // %1$s = plugin name(s)
            'notice_cannot_activate' => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'altotheme' ), // %1$s = plugin name(s)
            'notice_ask_to_update' => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'altotheme' ), // %1$s = plugin name(s)
            'notice_cannot_update' => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'altotheme' ), // %1$s = plugin name(s)
            'install_link' => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'altotheme' ),
            'activate_link' => _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'altotheme' ),
            'return' => esc_html__( 'Return to Required Plugins Installer', 'altotheme' ),
            'plugin_activated' => esc_html__( 'Plugin activated successfully.', 'altotheme' ),
            'complete' => esc_html__( 'All plugins installed and activated successfully. %s', 'altotheme' ), // %1$s = dashboard link
            'nag_type' => 'updated' // Determines admin notice type - can only be 'updated' or 'error'
        )
    );

    tgmpa( $plugins, $config );
}

if ( ! function_exists( 'lt_setup' ) ) :
    function lt_setup() {
        global $lt_opt;
        require( get_template_directory() . '/includes/dynamic-css.php' );
        require( get_template_directory() . '/includes/theme-functions.php' );
        require( get_template_directory() . '/includes/woo-functions.php' );
        require( get_template_directory() . '/includes/images.php' );
        require( get_template_directory() . '/includes/theme-options.php' );
        if(is_admin()){
            require_once(get_template_directory() . '/includes/lee_mega_menu/lee_mega_menu.php');
        }
        require_once(get_template_directory() . '/includes/lee_mega_menu/lee_mega_menu_frontend.php' );

        load_theme_textdomain( 'altotheme', get_template_directory() . '/languages' );
        add_theme_support( 'woocommerce' );
        add_theme_support( 'automatic-feed-links' );

        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'custom-background' );
        add_theme_support( 'custom-header' );

        add_image_size( 'lt-normal-thumb', 900, 250, true );
        add_image_size( 'lt-list-thumb', 250, 200, true );
        add_image_size( 'lt-grid-thumb', 280, 175, true );
        add_image_size( 'lt-category-thumb', 480, 900, true );

        register_nav_menus( array(
            'primary' => esc_html__('Main Menu', 'altotheme'),
            'my_account' => esc_html__('My Account', 'altotheme'),
            'footer_menu' => esc_html__('Footer Menu', 'altotheme'),
        ) );
    }
endif; 
add_action( 'after_setup_theme', 'lt_setup' );

/**
 * Enqueue scripts and styles
 */
function lt_dequeue_wc_fragments() {
    wp_dequeue_script( 'wc-cart-fragments' );
}
add_action( 'wp_enqueue_scripts', 'lt_dequeue_wc_fragments', 100 );

function lt_scripts() {
    global $lt_opt;
    //$enable_live_search = isset($lt_opt['enable_live_search']) ? $lt_opt['enable_live_search'] : true;
    
    wp_enqueue_style( 'lt-icons', get_template_directory_uri() .'/css/fonts.css', array(), null, 'all' );
    wp_enqueue_style( 'lt-animate', get_template_directory_uri() .'/css/animate.css', array(), null, 'all' );
    wp_enqueue_style( 'lt-owlcarousel', get_template_directory_uri() .'/css/owl.carousel.min.css', array(), null, 'all');

    wp_enqueue_style( 'lt-style', get_stylesheet_uri(), array(), null , 'all');

    wp_enqueue_script( 'lt-cookie', get_template_directory_uri() .'/js/min/jquery.cookie.min.js', array(), null, true );
    wp_enqueue_script( 'lt-modernizer', get_template_directory_uri() .'/js/modernizr.js', array(), null, true );
    wp_enqueue_script( 'lt-scrollTo', get_template_directory_uri() .'/js/min/jquery.scrollTo.min.js', array(), null, true );
    wp_enqueue_script( 'lt-JRespond', get_template_directory_uri() .'/js/min/jquery.jRespond.min.js', array(), null, true );
    wp_enqueue_script( 'lt-hoverIntent', get_template_directory_uri() .'/js/min/jquery.hoverIntent.min.js', array(), null, true );
    wp_enqueue_script( 'lt-jpanelmenu', get_template_directory_uri() .'/js/min/jquery.jpanelmenu.min.js', array(), null, true );
    wp_enqueue_script( 'lt-waypoints', get_template_directory_uri() .'/js/min/jquey.waypoints.js', array(), null, true );
    wp_enqueue_script( 'lt-packer', get_template_directory_uri() .'/js/min/jquery.packer.js', array(), null, true );
    wp_enqueue_script( 'lt-tipr', get_template_directory_uri() .'/js/min/jquery.tipr.min.js', array(), null, true );
    wp_enqueue_script( 'lt-variations', get_template_directory_uri() .'/js/min/jquery.variations.min.js', array(), null, true );
    wp_enqueue_script( 'lt-magnific-popup', get_template_directory_uri() .'/js/min/jquery.magnific-popup.js', array(), null, true );
    wp_enqueue_script( 'lt-owlcarousel', get_template_directory_uri() .'/js/min/owl.carousel.min.js', array(), null, true );
    wp_enqueue_script( 'lt-parallax', get_template_directory_uri() .'/js/min/jquery.stellar.min.js', array(), null, true );
    wp_enqueue_script( 'lt-countdown', get_template_directory_uri() .'/js/min/countdown.min.js', array(), null, true );
    wp_enqueue_script( 'lt-easyzoom', get_template_directory_uri() .'/js/min/jquery.easyzoom.min.js', array(), null, true );
    wp_enqueue_script( 'lt-masonry', get_template_directory_uri().'/js/min/jquery.masonry.min.js', array(), false, true);
    wp_enqueue_script( 'lt-wow-js', get_template_directory_uri() .'/js/min/wow.min.js', array(), false, true );
    wp_enqueue_script( 'lt-scrollbar-js', get_template_directory_uri() .'/js/min/jquery.slimscroll.min.js', array(), false, true );
    wp_enqueue_script( 'lt-theme-js', get_template_directory_uri() .'/js/min/main.min.js', array(), '1.0' , true );

    wp_deregister_style('yith-wcwl-font-awesome');
    wp_deregister_style('yith-wcwl-font-awesome-ie7');
    wp_deregister_style('yith-wcwl-main');

    if ( ! is_admin() ) {
        wp_deregister_style('woocommerce-layout');	
        wp_deregister_style('woocommerce-smallscreen');	
        wp_deregister_style('woocommerce-general');	
    }

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

}
add_action( 'wp_enqueue_scripts', 'lt_scripts' );

//* Enqueue script to activate WOW.js
if (!isset($lt_opt['disable_wow']) || !$lt_opt['disable_wow']) {
    //* Add JavaScript before </body>
    function lt_wow_init() {
        echo '<script type="text/javascript">var wow_enable = true; new WOW().init();</script>';
    }
} else {
    function lt_wow_init() {
        echo '<script type="text/javascript">var wow_enable = false;</script>';
    }
}

add_action('wp_enqueue_scripts', 'lt_wow_init_in_footer');
function lt_wow_init_in_footer() {
    add_action( 'print_footer_scripts', 'lt_wow_init' );
}

/* UNREGISTRER DEFAULT WOOCOMMERCE HOOKS */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_show_messages', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

add_filter('widget_text', 'do_shortcode');
add_filter('the_excerpt', 'do_shortcode');

add_action( 'lt_shop_category_count', 'woocommerce_result_count', 20 );
add_action('init', 'lt_post_type_support');
function lt_post_type_support() {
    add_post_type_support( 'page', 'excerpt' );
}

include_once get_template_directory() . '/includes/google-fonts.php';
require_once get_template_directory() . '/includes/class-wc-product-data-fields.php';
require_once get_template_directory() . '/includes/custom-wc-fields.php';
require_once get_template_directory() . '/includes/lt-ext-wc-query.php';

if(isset($lt_opt['products_pr_page'])){
    $products = $lt_opt['products_pr_page'];
    add_filter( 'loop_shop_per_page', create_function( '$cols', "return $products;" ), 20 );
}

// Default sidebars
function lt_widgets_sidebars_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'altotheme' ),
        'id'            => 'sidebar-main',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Shop Sidebar', 'altotheme' ),
        'id'            => 'shop-sidebar',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Product Sidebar', 'altotheme' ),
        'id'            => 'product-sidebar',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Home Sidebar', 'altotheme' ),
        'id'            => 'home-sidebar',
        'before_widget' => '<aside id="%1$s" class="home-sidebar">',
        'after_widget'  => '</aside>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Custom Sidebar', 'altotheme' ),
        'id'            => 'custom-sidebar',
        'before_widget' => '<aside id="%1$s" class="custom-sidebar">',
        'after_widget'  => '</aside>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer', 'altotheme' ),
        'id'            => 'sidebar-footer',
        'before_widget' => '<div id="%1$s" class="large-3 columns widget left %2$s">',
        'after_widget'  => '</div>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer 1', 'altotheme' ),
        'id'            => 'footer-1',
        'before_widget' => '<aside id="%1$s" class="widget">',
        'after_widget'  => '</aside>',
        'before_title'	=> '',
        'after_title'	=> ''
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer 2', 'altotheme' ),
        'id'            => 'footer-2',
        'before_widget' => '<aside id="%1$s" class="widget">',
        'after_widget'  => '</aside>',
        'before_title'	=> '',
        'after_title'	=> ''
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer 3', 'altotheme' ),
        'id'            => 'footer-3',
        'before_widget' => '<aside id="%1$s" class="widget">',
        'after_widget'  => '</aside>',
        'before_title'	=> '',
        'after_title'	=> ''
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer 4', 'altotheme' ),
        'id'            => 'footer-4',
        'before_widget' => '<aside id="%1$s" class="widget">',
        'after_widget'  => '</aside>',
        'before_title'	=> '',
        'after_title'	=> ''
    ) );

}
add_action( 'widgets_init', 'lt_widgets_sidebars_init' );

// Woocommerce widgets custom
// Includes
foreach (glob(get_template_directory() . '/woocommerce/widgets/*.php') as $file) {
    include_once $file;
}

add_filter('show_admin_bar', '__return_false');