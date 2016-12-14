<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'lt_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function lt_metaboxes( array $meta_boxes ) {

    // Start with an underscore to hide fields from custom fields list
    $prefix = '_lee_';
    /* Get Footer style */
    $footers_type = '';
    $footers_type = get_posts(array('posts_per_page'=>-1, 'post_type'=>'footer'));
    $footers_option = array();
    $footers_option['default'] = 'Default';
    foreach ($footers_type as $key => $value){
        $footers_option[$value->ID] = $value->post_title;
    }

    $meta_boxes['lee_metabox'] = array(
        'id'         => 'lee_metabox',
        'title'      => esc_html__( 'Options Page', 'lee_framework' ),
        'pages'      => array( 'page', ), // Post type
        'context'    => 'normal',
        'priority'   => 'high',
        'show_names' => true, // Show field names on the left
        'fields'     =>  array(
            array(
                'name'    => esc_html__( 'Header Type', 'lee_framework' ),
                'desc'    => esc_html__( 'Description (optional)', 'lee_framework' ),
                'id'      => $prefix . 'custom_header',
                'type'    => 'select',
                'options' => array(
                    ''  => esc_html__( 'Default', 'lee_framework' ),
                    '1' => esc_html__( 'Header Type 1', 'lee_framework' ),
                    '2' => esc_html__( 'Header Type 2', 'lee_framework' ),
                    '3' => esc_html__( 'Header Type 3', 'lee_framework' ),
                    '4' => esc_html__( 'Header Type 4 (Vertical)', 'lee_framework' ),
                    '5' => esc_html__( 'Header Type 5', 'lee_framework' ),
                    '6' => esc_html__( 'Header Type 6', 'lee_framework' ),
                    '7' => esc_html__( 'Header Type 7 (Fullwidth)', 'lee_framework' ),
                    '8' => esc_html__( 'Header Type 8', 'lee_framework' ),
                ),
                'default' => ''
            ),
            
            array(
                'name' => esc_html__( 'Header Transparent', 'lee_framework' ),
                'desc' => 'Only for Header type 2',
                'id'   => $prefix . 'header_transparent',
                'type' => 'checkbox',
            ),
            
            // array(
            //     'name' => esc_html__( 'Main menu Transparent', 'lee_framework' ),
            //     'desc' => 'Only for Header type 6',
            //     'id'   => $prefix . 'main_menu_transparent',
            //     'type' => 'checkbox',
            // ),
            
            array(
                'name'	=> esc_html__( 'Show Breadcrumb', 'lee_framework' ),
                'desc'	=> 'Yes, please',
                'id'	=> $prefix . 'show_breadcrumb',
                'default' => '0',
                'type'	=> 'checkbox',
                'class' => 'lt-breadcrumb-flag'
            ),
            array(
                'name'    => esc_html__( 'Breadcrumb Type', 'lee_framework' ),
                'desc'    => esc_html__( 'Type override breadcrumb', 'lee_framework' ),
                'id'      => $prefix . 'type_breadcrumb',
                'type'    => 'select',
                'options' => array(
                    ''  => esc_html__( 'Default', 'lee_framework' ),
                    '1' => esc_html__( 'Has breadcrumb background', 'lee_framework' )
                ),
                'default' => '',
                'class' => 'hidden-tag lt-breadcrumb-type'
            ),
            array(
                'name' => esc_html__( 'Override background for breadcrumb', 'lee_framework' ),
                'desc' => esc_html__( 'Background for breadcrumb', 'lee_framework' ),
                'id'   => $prefix . 'bg_breadcrumb',
                'allow' => false,
                'type' => 'file',
                'class' => 'hidden-tag lt-breadcrumb-bg'
            ),
            array(
                'name'	    => esc_html__( 'Breadcrumb background color', 'lee_framework' ),
                'desc'	    => esc_html__( 'Breadcrumb background color', 'lee_framework' ),
                'id'	    => $prefix . 'bg_color_breadcrumb',
                'type'	    => 'text',
                'default'   => '',
                'class'	    => 'hidden-tag lt-breadcrumb-bg-color'
            ),
            array(
                'name'	    => esc_html__( 'Height breadcrumb', 'lee_framework' ),
                'desc'	    => esc_html__( 'Height (Pixel)', 'lee_framework' ),
                'id'	    => $prefix . 'height_breadcrumb',
                'type'	    => 'text',
                'default'   => '150',
                'class'	    => 'hidden-tag lt-breadcrumb-height'
            ),
            array(
                'name'	    => esc_html__( 'Breadcrumb text color', 'lee_framework' ),
                'desc'	    => esc_html__( 'Text color', 'lee_framework' ),
                'id'	    => $prefix . 'color_breadcrumb',
                'type'	    => 'text',
                'default'   => '#FFF',
                'class'	    => 'hidden-tag lt-breadcrumb-color'
            ),
            array(
                'name' => esc_html__( 'Override Logo', 'lee_framework' ),
                'desc' => esc_html__( 'Upload an image for override default logo.', 'lee_framework' ),
                'id'   => $prefix . 'custom_logo',
                'allow' => false,
                'type' => 'file',
            ),
            array(
                'name'    => esc_html__( 'Footer Type', 'lee_framework' ),
                'desc'    => esc_html__( 'Description (optional)', 'lee_framework' ),
                'id'      => $prefix . 'custom_footer',
                'type'    => 'select',
                'options' => $footers_option,
                'default' => '7478'
            )
        )
    );

    return $meta_boxes;
}

add_action( 'init', 'lt_init_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function lt_init_cmb_meta_boxes() {
    if ( ! class_exists( 'cmb_Meta_Box' ) )
        require_once LEE_FRAMEWORK_PLUGIN_PATH . '/admin/metabox/init.php';
}
