<?php

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Force Visual Composer to initialize as "built into the theme".
 * This will hide certain tabs under the Settings->Visual Composer page
 */
add_action( 'vc_before_init', 'lt_your_prefix_vcSetAsTheme' );
function lt_your_prefix_vcSetAsTheme() {
    /* Hide update notice */
    vc_set_as_theme( false ); // $disable_updater = false
}

// **********************************************************************// 
// ! Customize the VC rows and columns to use theme's Foundation framework
// **********************************************************************//
if ( ! function_exists( 'lt_customize_custom_css_classes' ) ) {
	
    function lt_customize_vc_rows_columns( $class_string, $tag ) {
        // vc_row 
        if ( $tag == 'vc_row' || $tag == 'vc_row_inner' ) {
            
            $replace = array(
                'vc_row-fluid' 	=> 'row',
                'wpb_row' 	=> '',
                'vc_row'	=> '',
                'vc_inner'	=> '',
            );
            
            $class_string = lt_replace_string_with_assoc_array( $replace, $class_string );
        }
        
        // vc_column
        if ( $tag == 'vc_column' || $tag == 'vc_column_inner' ) {
            $replace = array(
                'wpb_column' 		=> '',
                'column_container' 	=> '',
            );
            
            $to_be_replaced = array( '', '' );
            
            $class_string = lt_replace_string_with_assoc_array(
                $replace, preg_replace( '/vc_span(\d{1,2})/', 'lt-col large-$1 columns', $class_string )
            );
							
            // Custom columns	
            $class_string = lt_replace_string_with_assoc_array(
                $replace, preg_replace( '/vc_(\d{1,2})\\/12/', 'lt-col large-$1 columns', $class_string )
            );

            $class_string = lt_replace_string_with_assoc_array(
                $replace, preg_replace( '/vc_hidden-xs/', 'hide-for-small', $class_string )
            );
							
            // VC 4.3.x (it changed the tags)
            $class_string = lt_replace_string_with_assoc_array(
                $replace, preg_replace('/vc_col-(xs|sm|md|lg)-(\d{1,2})/', 'lt-col large-$2 columns', $class_string)
            );					
        }
        
        return $class_string;
    }
}


// Used in "lt_customize_vc_rows_columns()" [plugin-custom-functions.php]
if ( ! function_exists( 'lt_replace_string_with_assoc_array' ) ) {
    function lt_replace_string_with_assoc_array( array $replace, $subject ) { 
        return str_replace( array_keys( $replace ), array_values( $replace ), $subject );    
    }
}

// **********************************************************************// 
// ! Visual Composer Setup
// **********************************************************************//
if(!function_exists('getCSSAnimation')) {
    function getCSSAnimation($css_animation) {
        $output = '';
        if ( $css_animation != '' ) {
            wp_enqueue_script( 'waypoints' );
            $output = ' wpb_animate_when_almost_visible wpb_'.$css_animation;
        }
        return $output;
    }
}
if(!function_exists('buildStyle')) {
    function buildStyle($bg_image = '', $bg_color = '', $bg_image_repeat = '', $font_color = '', $padding = '', $margin_bottom = '') {
        $has_image = false;
        $style = '';
        if((int)$bg_image > 0 && ($image_url = wp_get_attachment_url( $bg_image, 'large' )) !== false) {
            $has_image = true;
            $style .= "background-image: url(".$image_url.");";
        }
        if(!empty($bg_color)) {
            $style .= vc_get_css_color('background-color', $bg_color);
        }
        if(!empty($bg_image_repeat) && $has_image) {
            if($bg_image_repeat === 'cover') {
                $style .= "background-repeat:no-repeat;background-size: cover;";
            } elseif($bg_image_repeat === 'contain') {
                $style .= "background-repeat:no-repeat;background-size: contain;";
            } elseif($bg_image_repeat === 'no-repeat') {
                $style .= 'background-repeat: no-repeat;';
            }
        }
        if( !empty($font_color) ) {
            $style .= vc_get_css_color('color', $font_color); // 'color: '.$font_color.';';
        }
        if( $padding != '' ) {
            $style .= 'padding: '.(preg_match('/(px|em|\%|pt|cm)$/', $padding) ? $padding : $padding.'px').';';
        }
        if( $margin_bottom != '' ) {
            $style .= 'margin-bottom: '.(preg_match('/(px|em|\%|pt|cm)$/', $margin_bottom) ? $margin_bottom : $margin_bottom.'px').';';
        }
        return empty($style) ? $style : ' style="'.$style.'"';
    }
}
/* Remove Tabs - Accordions elements */
add_action('vc_build_admin_page', 'lt_vc_remove_elements_tabs_accordions', 11);
function lt_vc_remove_elements_tabs_accordions(){
    // remove params tabs
    vc_remove_param('vc_tta_tabs', 'shape');
    vc_remove_param('vc_tta_tabs', 'style');
    vc_remove_param('vc_tta_tabs', 'color');
    //vc_remove_param('vc_tta_tabs', 'alignment');
    vc_remove_param('vc_tta_tabs', 'autoplay');
    vc_remove_param('vc_tta_tabs', 'active_section');
    vc_remove_param('vc_tta_tabs', 'no_fill_content_area');
    vc_remove_param('vc_tta_tabs', 'spacing');
    vc_remove_param('vc_tta_tabs', 'gap');
    vc_remove_param('vc_tta_tabs', 'tab_position');
    vc_remove_param('vc_tta_tabs', 'pagination_style');
    vc_remove_param('vc_tta_tabs', 'pagination_color');
    
    // remove params accordions
    vc_remove_param('vc_tta_accordion', 'style');
    vc_remove_param('vc_tta_accordion', 'shape');
    vc_remove_param('vc_tta_accordion', 'color');
    vc_remove_param('vc_tta_accordion', 'c_align');
    vc_remove_param('vc_tta_accordion', 'no_fill');
    vc_remove_param('vc_tta_accordion', 'spacing');
    vc_remove_param('vc_tta_accordion', 'gap');
    vc_remove_param('vc_tta_accordion', 'autoplay');
    vc_remove_param('vc_tta_accordion', 'c_position');
    vc_remove_param('vc_tta_accordion', 'collapsible_all');
    vc_remove_param('vc_tta_accordion', 'c_icon');
    vc_remove_param('vc_tta_accordion', 'active_section');
    
    // remove params section
    vc_remove_param('vc_tta_section', 'add_icon');
    vc_remove_param('vc_tta_section', 'i_position');
    vc_remove_param('vc_tta_section', 'i_type');
    vc_remove_param('vc_tta_section', 'i_icon_fontawesome');
    vc_remove_param('vc_tta_section', 'i_icon_openiconic');
    vc_remove_param('vc_tta_section', 'i_icon_typicons');
    vc_remove_param('vc_tta_section', 'i_icon_entypo');
    vc_remove_param('vc_tta_section', 'i_icon_linecons');
    vc_remove_param('vc_tta_section', 'i_icon_monosocial');
}

/* Remove Woocommerce elements */
function lee_vc_remove_woocommerce(){
    if (is_plugin_active('woocommerce/woocommerce.php')){
        vc_remove_element('recent_products');
        vc_remove_element('featured_products');
        vc_remove_element('best_selling');
        vc_remove_element('product');
        vc_remove_element('products');
        vc_remove_element('sale_products');
        vc_remove_element('best_selling_products');
        vc_remove_element('top_rated_products');
    }
}
// Hook for admin editor
add_action('vc_build_admin_page', 'lee_vc_remove_woocommerce', 11);
// Hook for frontend editor
add_action('vc_load_shortcode', 'lee_vc_remove_woocommerce', 11);

add_action( 'init', 'lt_bery_VC_setup');
if(!function_exists('lt_bery_VC_setup')) {
    function lt_bery_VC_setup() {
        if (!class_exists('WPBakeryVisualComposerAbstract')) return;
        global $vc_params_list;
        $vc_params_list[] = 'icon';
        vc_remove_element("vc_carousel");
        vc_remove_element("vc_images_carousel");
        vc_remove_element("vc_tour");
        vc_remove_element("vc_cta");
        vc_remove_element("vc_tta_tour");
        vc_remove_element("vc_tta_pageable");
        vc_remove_element("vc_cta_button");
        vc_remove_element("vc_cta_button2");
        vc_remove_element("vc_button");
        vc_remove_element("vc_button2");
        vc_remove_element("vc_wp_search");
        vc_remove_element("vc_wp_meta");
        vc_remove_element("vc_wp_recentcomments");
        vc_remove_element("vc_wp_calendar");
        vc_remove_element("vc_wp_posts");
        vc_remove_element("vc_wp_links");
        vc_remove_element("vc_wp_archives");
        vc_remove_element("vc_wp_rss");
        vc_remove_param( "vc_row", "full_width" );
        vc_remove_param( "vc_row", "parallax_speed_bg" );
        vc_remove_param( "vc_row", "parallax_speed_video" );
        vc_remove_param( "vc_row", "full_height" );
        vc_remove_param( "vc_row", "gap" );
        vc_remove_param( "vc_row", "equal_height" );
        vc_remove_param( "vc_row", "content_placement" );
        vc_remove_param( "vc_row", "video_bg" );
        vc_remove_param( "vc_row", "video_bg_parallax" );
        vc_remove_param( "vc_row", "video_bg_url" );


        $target_arr = array(__("Same window", 'lee_framework') => "_self", __("New window", 'lee_framework') => "_blank");
        $add_css_animation = array(
            "type" => "dropdown",
            "heading" => __("CSS Animation", 'lee_framework'),
            "param_name" => "css_animation",
            "admin_label" => true,
            "value" => array(
                __("No", 'lee_framework') => '', 
                __("Top to bottom", 'lee_framework') => "top-to-bottom", 
                __("Bottom to top", 'lee_framework') => "bottom-to-top", 
                __("Left to right", 'lee_framework') => "left-to-right", 
                __("Right to left", 'lee_framework') => "right-to-left", 
                __("Appear from center", 'lee_framework') => "appear"
            ),
            "description" => __("Select animation type if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.", 'lee_framework')
        );

        // **********************************************************************// 
        // ! Add the parent element
        // **********************************************************************//
        //Your "container" content element should extend WPBakeryShortCodesContainer class to inherit all required functionality
        if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
            class WPBakeryShortCode_Your_Gallery extends WPBakeryShortCodesContainer {};
            class WPBakeryShortCode_bery_slider extends WPBakeryShortCodesContainer {};
            class WPBakeryShortCode_bery_banner_grid extends WPBakeryShortCodesContainer {};
            class WPBakeryShortCode_col extends WPBakeryShortCodesContainer {};
            class WPBakeryShortCode_row extends WPBakeryShortCodesContainer {};
        }
        if ( class_exists( 'WPBakeryShortCode' ) ) {
            class WPBakeryShortCode_Single_Img extends WPBakeryShortCode {};
            class WPBakeryShortCode_bery_banner extends WPBakeryShortCode {};
        }


        // **********************************************************************// 
        // ! Row (add fullwidth, parallax option)
        // **********************************************************************//
        vc_add_param('vc_row', array(
            "type" => 'checkbox',
            "heading" => __("Fullwidth?", 'lee_framework'),
            "param_name" => "fullwidth",
            "value" => array(
                'Yes, please' => true
            )
        ));

        vc_add_param('vc_row', array(
            "type" => "checkbox",
            "heading" => __("Parallax",'lee_framework'),
            "param_name" => "parallax",
            "value" => array(
                'Yes, please' => true
            )
        ));

        vc_add_param('vc_row', array(
            "type" => "textfield",
            "heading" => __("Parallax speed",'lee_framework'),
            "param_name" => "parallax_speed",
            "value" => "0.6",
            "dependency" => array(
                "element" => 'parallax',
                "not_empty" => true,
            ),
            "description" => __( 'Enter parallax speed ratio (Note: Default value is 0.6, min value is 0)', 'lee_framework' ),
        ));

        //Add param from tab element
        vc_add_param( 'vc_tabs', array(
            "type" => "dropdown",
            "heading" => __("Tab title align",'lee_framework'),
            "param_name" => "align_tab",
            "value" => array(
                __('Align Left', 'lee_framework') => '',
                __('Align Center', 'lee_framework') => 'text-center',
                __('Align Right', 'lee_framework') => 'text-right',
            )
        ));

        //Add param from columns element
        // Column
        vc_add_param('vc_column', array(
            "type" => "dropdown",
            "heading" => __("Effect",'lee_framework'),
            "param_name" => "lee_effect",
            'value' => array(
                'none' => 'none',
                'bounce' => 'bounce',
                'flash' => 'flash',
                'pulse' => 'pulse',
                'rubberBand' => 'rubberBand',
                'shake' => 'shake',
                'swing' => 'swing',
                'tada' => 'tada',
                'wobble' => 'wobble',
                'bounceIn' => 'bounceIn',
                'fadeIn' => 'fadeIn',
                'fadeInDown' => 'fadeInDown',
                'fadeInDownBig' => 'fadeInDownBig',
                'fadeInLeft' => 'fadeInLeft',
                'fadeInLeftBig' => 'fadeInLeftBig',
                'fadeInRight' => 'fadeInRight',
                'fadeInRightBig' => 'fadeInRightBig',
                'fadeInUp' => 'fadeInUp',
                'fadeInUpBig' => 'fadeInUpBig',
                'flip' => 'flip',
                'flipInX' => 'flipInX',
                'flipInY' => 'flipInY',
                'lightSpeedIn' => 'lightSpeedIn',
                'rotateInrotateIn' => 'rotateIn',
                'rotateInDownLeft' => 'rotateInDownLeft',
                'rotateInDownRight' => 'rotateInDownRight',
                'rotateInUpLeft' => 'rotateInUpLeft',
                'rotateInUpRight' => 'rotateInUpRight',
                'slideInDown' => 'slideInDown',
                'slideInLeft' => 'slideInLeft',
                'slideInRight' => 'slideInRight',
                'rollIn' => 'rollIn'
            )
        ));

        vc_add_param('vc_column', array(
            "type" => "textfield",
            "heading" => __("Duration",'lee_framework'),
            "param_name" => "lee_duration",
            'value' => '1000'
        ));

        vc_add_param('vc_column', array(
            "type" => "textfield",
            "heading" => __("Delay",'lee_framework'),
            "param_name" => "lee_delay",
            'value' => '200'
        ));

        // **********************************************************************// 
        // ! Register New Element: Slider
        // **********************************************************************//

        $slider_params = array(
            "name" => __("Lee Slider", 'lee_framework'),
            "base" => "bery_slider",
            "as_parent" => array('except' => 'bery_slider'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
            "content_element" => true,
            'category' => 'Lee Theme',
            "params" => array(
                array(
                    "type" => "textfield",
                    "heading" => __("Title", 'lee_framework'),
                    "param_name" => "title"
                ),
                array(
                    "type" => "dropdown",
                    "heading" => "Title align",
                    "param_name" => "align",
                    "value" => array(
                        __('Left', 'lee_framework') => '',
                        __('Center', 'lee_framework') => 'center',
                    )
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __('Display Bullets','lee_framework'),
                    "param_name" => "bullets",
                    "value" => array(
                        __('Enable', 'lee_framework') => 'true',
                        __('Disable', 'lee_framework') => 'false'
                    ),
                    "description" => 'You only use bullets or arrows for navigation. If disable bullets. You can select arrow navigation at bellow.'
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __('Bullets type','lee_framework'),
                    "param_name" => "bullets_type",
                    "value" => array(
                        __('Center', 'lee_framework') => '',
                        __('Left', 'lee_framework') => 'bullets_type_2'
                    ),
                    "description" => 'Select bullets display type.'
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __('Display arrows','lee_framework'),
                    "param_name" => "navigation",
                    "value" => array(
                        __('Enable', 'lee_framework') => 'true',
                        __('Disable', 'lee_framework') => 'false'
                    )
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __('Number columns','lee_framework'),
                    "param_name" => "column_number",
                    "value" => array(
                        __('1', 'lee_framework') => '1',
                        __('2', 'lee_framework') => '2',
                        __('3', 'lee_framework') => '3',
                        __('4', 'lee_framework') => '4',
                        __('5', 'lee_framework') => '5',
                        __('6', 'lee_framework') => '6',
                    )
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __('Responsive item numbers for mobile', 'lee_framework'),
                    "param_name" => "column_number_small",
                    "std" => "2",
                    "value" => array(
                        __('1', 'lee_framework') => '1',
                        __('2', 'lee_framework') => '2',
                        __('3', 'lee_framework') => '3',
                        __('4', 'lee_framework') => '4',
                        __('5', 'lee_framework') => '5',
                        __('6', 'lee_framework') => '6',
                    )
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __('Responsive item numbers for tablet','lee_framework'),
                    "param_name" => "column_number_tablet",
                    "std" => "2",
                    "value" => array(
                        __('1', 'lee_framework') => '1',
                        __('2', 'lee_framework') => '2',
                        __('3', 'lee_framework') => '3',
                        __('4', 'lee_framework') => '4',
                        __('5', 'lee_framework') => '5',
                        __('6', 'lee_framework') => '6',
                    )
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __('Auto Play','lee_framework'),
                    "param_name" => "autoplay",
                    "value" => array(
                        __('Disable', 'lee_framework') => 'false',
                        __('Enable', 'lee_framework') => 'true'
                    )
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __('Pagination Speed','lee_framework'),
                    "param_name" => "paginationspeed",
                    "std" => '800',
                    "value" => array(
                        __('0.4s', 'lee_framework') => '400',
                        __('0.6s', 'lee_framework') => '600',
                        __('0.8s', 'lee_framework') => '800',
                        __('1s', 'lee_framework') => '1000',
                        __('1.2s', 'lee_framework') => '1200',
                        __('1.4s', 'lee_framework') => '1400',
                        __('1.6s', 'lee_framework') => '1800',
                    )
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Extra class name", 'lee_framework'),
                    "param_name" => "el_class",
                    "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'lee_framework')
                )
            ),
            "js_view" => 'VcColumnView'
        );
        vc_map($slider_params);

        // **********************************************************************// 
        // ! Register New Element: Lee products
        // **********************************************************************//
        vc_map( array(
            "name" => __("Lee Products", 'lee_framework'),
            "base" => "lee_products",
            "class" => "",
            "category" => __('Lee Theme','lee_framework'),
            "params" => array(
                array(
                    "type" => "dropdown",
                    "heading" => __("Type", 'lee_framework'),
                    "param_name" => "type",
                    "value" => array(
                        'Best Selling' => 'best_selling',
                        'Featured Products' => 'featured_product',
                        'Top Rate' => 'top_rate',
                        'Recent Products' => 'recent_product',
                        'On Sale' => 'on_sale',
                        'Recent Review' => 'recent_review',
                        'Product Deals' => 'deals'
                    ),
                    "admin_label" => true,
                    "description" => __("Select type product to show.", 'lee_framework')
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __("Style", 'lee_framework'),
                    "param_name" => "style",
                    "value" => array(
                        'Grid'=>'grid',
                        'List'=>'list',
                        'Carousel'=>'carousel',
                        'Ajax Infinite'=>'infinite'
                    ),
                    "admin_label" => true
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Number of products to show", 'lee_framework'),
                    "param_name" => "number",
                    "value" => '8'
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __("Columns number", 'lee_framework'),
                    "param_name" => "columns_number",
                    "value" => array(5, 4, 3, 2, 1),
                    "std" => 4,
                    "admin_label" => true,
                    "description" => __("Select columns count.", 'lee_framework')
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __("Product Category", 'lee_framework'),
                    "param_name" => "cat",
                    "value" => lt_get_cat_product_array(),
                    "description" => __("Input the category name here.", 'lee_framework')
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Extra class name", 'lee_framework'),
                    "param_name" => "el_class",
                    "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'lee_framework')
                )
            )
        ));
        
        // **********************************************************************// 
        // ! Register New Element: Lee product Deal
        // **********************************************************************//
        vc_map( array(
            "name" => __("Lee Product Deal", 'lee_framework'),
            "base" => "lt_products_deal",
            "class" => "",
            "category" => __('Lee Theme','lee_framework'),
            "params" => array(
                array(
                    "type" => "dropdown",
                    "heading" => __("Select a product deal", 'lee_framework'),
                    "param_name" => "id",
                    "value" => vc_getListProductDeals(),
                    "admin_label" => true,
                    //"description" => __("Select product deal", 'lee_framework'),
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __("Layout", 'lee_framework'),
                    "param_name" => "layout",
                    "value" => array(
                        'Block grid' => 'block',
                        'Full width' => 'full'
                    ),
                    "admin_label" => true,
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __("Product deal Thumbnail Position", 'lee_framework'),
                    "param_name" => "thumbs_absolute",
                    "value" => array(
                        'Outside image' => false,
                        'Inside image' => true
                    ),
                    "admin_label" => true,
                    'group' => 'Block grid layout'
                ),
                array(
                    "type" => 'dropdown',
                    "heading" => __("Display products grid", 'lee_framework'),
                    "param_name" => "enable_grid",
                    "value" => array(
                        'Yes' => true,
                        'No' => false
                    ),
                    "std" => true,
                    "admin_label" => true,
                    'group' => 'Block grid layout'
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __("Type show for grid", 'lee_framework'),
                    "param_name" => "type_grid",
                    "value" => array(
                        'Best Selling' => 'best_selling',
                        'Featured Products' => 'featured_product',
                        'Top Rate' => 'top_rate',
                        'Recent Products' => 'recent_product',
                        'On Sale' => 'on_sale',
                        'Recent Review' => 'recent_review',
                        'Product Deals' => 'deals'
                    ),
                    "std" => 'best_selling',
                    "admin_label" => true,
                    "description" => __("Select type products grid to show.", 'lee_framework'),
                    'group' => 'Block grid layout'
                ),
                
                array(
                    'type' => 'attach_image',
                    "heading" => __("Banner Image", 'lee_framework'),
                    "param_name" => "banner_src",
                    'group' => 'Full width layout'
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Height Banner Image", 'lee_framework'),
                    "param_name" => "banner_height",
                    'group' => 'Full width layout'
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Block content margin top (%)", 'lee_framework'),
                    "param_name" => "position_top",
                    'group' => 'Full width layout'
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Block content margin left (%)", 'lee_framework'),
                    "param_name" => "position_left",
                    'group' => 'Full width layout'
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Block content margin right (%) only when margin left as 0 or empty", 'lee_framework'),
                    "param_name" => "position_right",
                    'group' => 'Full width layout'
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __("Text position", 'lee_framework'),
                    "param_name" => "text_align",
                    "std" => 'left',
                    "value" => array(
                        'Left' => 'left',
                        'Right' => 'right'
                    ),
                    "admin_label" => true,
                    "description" => __("Select align for Text.", 'lee_framework'),
                    'group' => 'Full width layout'
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Extra class name", 'lee_framework'),
                    "param_name" => "el_class",
                    "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'lee_framework')
                )
            )
        ));

        /*==========================================================================
        Lee Brands
        ==========================================================================*/
        vc_map( array(
            "name" => __("Lee Brands",'lee_framework'),
            "base" => "lee_brands",
            "class" => "",
            "category" => __('Lee Theme','lee_framework'),
            "params" => array(
                array(
                    "type" => "textfield",
                    "heading" => __("Title", 'lee_framework'),
                    "param_name" => "title"
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __("Layout", 'lee_framework'),
                    "param_name" => "layout",
                    "value" => array(
                        'Carousel' => 'carousel',
                        'Grid'     => 'grid',
                    ),
                    "admin_label" => true,
                    "description" => __("Select columns count.", 'lee_framework')
                ),
                array(
                    'type' => 'attach_images',
                    'heading' => __( 'Images', 'lee_framework' ),
                    'param_name' => 'images',
                    'value' => '',
                    'description' => __( 'Select images from media library.', 'lee_framework' )
                ),
                array(
                    'type' => 'exploded_textarea',
                    'heading' => __( 'Custom links', 'lee_framework' ),
                    'param_name' => 'custom_links',
                    'description' => __( 'Enter links for each slide here. Divide links with linebreaks (Enter) . ', 'lee_framework' ),
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __("Columns number", 'lee_framework'),
                    "param_name" => "columns_number",
                    "value" => array(6, 5, 4, 3, 2),
                    "admin_label" => true,
                    "description" => __("Select columns count.", 'lee_framework')
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Extra class name", 'lee_framework'),
                    "param_name" => "el_class",
                    "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'lee_framework')
                )
            )
        ));

        // **********************************************************************// 
        // ! Register New Element: Banner Grid
        // **********************************************************************//
        $banner_grid_params = array(
            "name" => "Lee Banner Grid",
            "base" => "bery_banner_grid",
            "icon" => "icon-wpb-leetheme",
            "category" => "Lee Theme",
            "content_element" => true,
            "as_parent" => array('only','col, bery_banner'),
            "params" => array(
                array(
                    "type" => "dropdown",
                    "heading" => __('Padding','lee_framework'),
                    "param_name" => "padding",
                    "value" => array(
                        __('10px','lee_framework') => '10px',
                        __('15px','lee_framework') => '15px',
                        __('20px','lee_framework') => '20px',
                        __('30px','lee_framework') => '30px'
                    ),
                    "description" => __('Distance elements grid','lee_framework')
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Extra class name", 'lee_framework'),
                    "param_name" => "el_class",
                    "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'lee_framework')
                )
            ),
            "js_view" => 'VcColumnView'
        );
        vc_map($banner_grid_params);


        // **********************************************************************// 
        // ! Register New Element: Lee Row
        // **********************************************************************//
        $lee_row_params = array(
            "name" => "Lee Row",
            "base" => "row",
            "icon" => "icon-wpb-leetheme",
            "category" => "Lee Theme",
            "content_element" => true,
            "show_settings_on_create" => false,
            "as_parent" => array('only' => 'col'),
            "params" => array(
                array(
                    "type" => "textfield",
                    "heading" => __('Row padding', 'lee_framework'),
                    "param_name" => "padding",
                    "description" => __("Insert a style row padding.", 'lee_framework')
                )
            ),
            "js_view" => 'VcColumnView'
        );
        vc_map($lee_row_params);

        // **********************************************************************// 
        // ! Register New Element: Lee Columns
        // **********************************************************************//
        $lee_columns_params = array(
            "name" => "Lee Columns",
            "base" => "col",
            "icon" => "icon-wpb-leetheme",
            "category" => "Lee Theme",
            "content_element" => true,
            "as_parent" => array('only','bery_banner'),
            "params" => array(
                array(
                    "type" => "dropdown",
                    "heading" => __('Column', 'lee_framework'),
                    "param_name" => "span",
                    "value" => array(
                        __('1 Column', 'lee_framework') => '1/12',
                        __('2 Columns', 'lee_framework') => '1/6',
                        __('3 Columns', 'lee_framework') => '1/4',
                        __('4 Columns', 'lee_framework') => '1/3',
                        __('5 Columns', 'lee_framework') => '5/12',
                        __('6 Columns', 'lee_framework') => '1/2',
                        __('7 Columns', 'lee_framework') => '7/12',
                        __('8 Columns', 'lee_framework') => '2/3',
                        __('9 Columns', 'lee_framework') => '3/4',
                        __('10 Columns', 'lee_framework') => '5/6',
                        __('11 Columns', 'lee_framework') => '11/12',
                        __('12 Columns', 'lee_framework') => '1/1'
                    )
                )
            ),
            "js_view" => 'VcColumnView'
        );
        vc_map($lee_columns_params);

        // **********************************************************************// 
        // ! Register New Element: Banner 
        // **********************************************************************//
        $banner_params = array(
            'name' => 'Lee Banner',
            'base' => 'berybanner',
            'icon' => 'icon-wpb-leetheme',
            'category' => 'Lee Theme',
            'as_parent' => array('except' => 'berybanner'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
            'params' => array(
                array(
                    'type' => 'attach_image',
                    "heading" => __("Banner Image", 'lee_framework'),
                    "param_name" => "img_src"
                ),
                array(
                    'type' => 'textfield',
                    "heading" => __("Banner Height", 'lee_framework'),
                    "param_name" => "height",
                    "edit_field_class" => "vc_col-sm-4 vc_column",
                    "value" => "",
                    "heading" => __('Banner height','lee_framework')
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Link", 'lee_framework'),
                    "edit_field_class" => "vc_col-sm-4 vc_column",
                    "param_name" => "link"
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Content width (%)", 'lee_framework'),
                    "edit_field_class" => "vc_col-sm-4 vc_column",
                    "param_name" => "content-width",
                    "value" => '',
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __("Horizontal alignment", 'lee_framework'),
                    "param_name" => "align",
                    "edit_field_class" => "vc_col-sm-6 vc_column",
                    "value" => array(
                        __("Left", 'lee_framework') => "left", 
                        __("Center", 'lee_framework') => "center", 
                        __("Right", 'lee_framework') => "right"
                    )
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Move Horizontal a distance (%)","lee_framework"),
                    "param_name" => "move_x",
                    "value" => "",
                    "edit_field_class" => "vc_col-sm-6 vc_column",
                    "dependency" => array(
                        "element" => "align",
                        "value" => array(
                            "left",
                            "right"
                        )
                    ),
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __("Vertical alignment", 'lee_framework'),
                    "param_name" => "valign",
                    "edit_field_class" => "vc_col-sm-6 vc_column",
                    "value" => array( 
                        __("Top", 'lee_framework') => "top", 
                        __("Middle", 'lee_framework') => "middle", 
                        __("Bottom", 'lee_framework') => "bottom"
                    )
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __("Text alignment","lee_framework"),
                    "param_name" => "text-align",
                    "edit_field_class" => "vc_col-sm-6 vc_column",
                    "value" => array(
                        __("Left", 'lee_framework') => "text-left", 
                        __("Center", 'lee_framework') => "text-center", 
                        __("Right", 'lee_framework') => "text-right"   
                    )
                ),
                array(
                    "type" => "dropdown",
                    "heading" => "Text Color",
                    "param_name" => "text_color",
                    "edit_field_class" => "vc_col-sm-6 vc_column",
                    "value" => array(
                        __('Black', 'lee_framework') => 'light',
                        __('White', 'lee_framework') => 'dark',
                    )
                ),
                array(
                    "type" => "textarea_html",
                    "holder" => "div",
                    //"admin_label" => true,
                    "heading" => "Banner Text",
                    "param_name" => "content",
                    "value" => "Some promo text",
                ),
                
                array(
                    "type" => "animation_style",
                    "heading" => __("Effect banner content", 'lee_framework'),
                    "param_name" => "effect_text",
                    "value" => "fadeIn",
                    "description" => __( "Select initial loading animation for text content.", "lee_framework" ),
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __("Effect banner hover.", 'lee_framework'),
                    "param_name" => "hover",
                    "value" => array(
                        __('None', 'lee_framework') => '',
                        __('Zoom', 'lee_framework') => 'zoom',
                        __('Zoom Out', 'lee_framework') => 'reduction',
                        __('Fade', 'lee_framework') => 'fade',
                        __('Carousel', 'lee_framework') => 'carousel',
                        __('Parallax Lax', 'lee_framework') => 'lax'
                    )
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __('Animation delay', 'lee_framework'),
                    "param_name" => "data_delay",
                    "value" => array(
                        __('None', 'lee_framework') => '',
                        __('100ms', 'lee_framework') => '100ms',
                        __('200ms', 'lee_framework') => '200ms',
                        __('300ms', 'lee_framework') => '300ms',
                        __('400ms', 'lee_framework') => '400ms',
                        __('500ms', 'lee_framework') => '500ms',
                        __('600ms', 'lee_framework') => '600ms',
                        __('700ms', 'lee_framework') => '700ms',
                        __('800ms', 'lee_framework') => '800ms',
                    ),
                    "description" => __("Delay time animation display text content","lee_framework")
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __("Seam icon", 'ltheme_domain'),
                    "param_name" => "seam_icon",
                    "value" => array(
                        __('None', 'ltheme_domain') => '',
                        __('Left alignment', 'ltheme_domain') => 'align_left',
                        __('Right alignment', 'ltheme_domain') => 'align_right',
                    )
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Extra Class", 'lee_framework'),
                    "param_name" => "class",
                    "description" => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'lee_framework')
                )
            )
        );
        vc_map($banner_params);


        // **********************************************************************// 
        // ! Register New Element: Categories name list
        // **********************************************************************//
        $products_categories_list_params = array(
            "name" => "Lee Categories name list",
            "base" => "lt_product_categories",
            "icon" => "icon-wpb-leetheme",
            "category" => "Lee Theme",
            "params" => array(
                array(
                    "type" => "textfield",
                    "heading" => __('Title', 'lee_framework'),
                    "param_name" => 'title'
                ),
                array(
                    "type" => "textfield",
                    "heading" => __('Categories number for display', 'lee_framework'),
                    "param_name" => 'number',
                    "value" => '5'
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __('Parent level', 'lee_framework'),
                    "param_name" => 'parent',
                    "value" => array(
                        "0" => '0',
                        "1" => '1',
                        "2" => '2'
                    )
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __('Products Columns', 'lee_framework'),
                    "param_name" => 'columns_number',
                    "value" => array(
                        "4" => '4',
                        "5" => '5'
                    )
                )
            )
        );
        vc_map($products_categories_list_params);


        // **********************************************************************// 
        // ! Register New Element: Testimonials
        // **********************************************************************//
        $client_params = array(
            "name" => __("Lee Testimonials", 'lee_framework'),
            "base" => "client",
            "content_element" => true,
            "category" => 'Lee Theme',
            "params" => array(
                array(
                    "type" => "attach_image",
                    "heading" => __("Testimonials avatar image", 'lee_framework'),
                    "param_name" => "img_src",
                    "description" => __("Choose Avatar image.", 'lee_framework')
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Testimonials name", 'lee_framework'),
                    "param_name" => "name",
                    "description" => __("Enter name.", 'lee_framework')
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Testimonials job", 'lee_framework'),
                    "param_name" => "company",
                    "description" => __("Enter job.", 'lee_framework')
                ),
                array(
                    "type" => "colorpicker",
                    "heading" => __("Testimonials text color", 'lee_framework'),
                    "param_name" => "text_color",
                    "value" => "#fff",
                    "description" => __("Choose text color.", 'lee_framework')
                ),
                array(
                    "type" => "textarea_html",
                    "holder" => "div",
                    "heading" => "Testimonials content say",
                    "param_name" => "content_say",
                    "value" => "Some promo text",
                    "description" => __("Enter client content say.", 'lee_framework')
                ),
            )
        );
        vc_map($client_params);


        // **********************************************************************// 
        // ! Register New Element: Service Box
        // **********************************************************************//
        $service_box_params = array(
            "name" => __("Lee Service Box", 'lee_framework'),
            "base" => "service_box",
            "content_element" => true,
            "category" => 'Lee Theme',
            "params" => array(
                array(
                    "type" => "textfield",
                    "heading" => __("Service title", 'lee_framework'),
                    "param_name" => "service_title",
                    "admin_label" => true,
                    "description" => __("Enter service title.", 'lee_framework'),
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Service Description", 'lee_framework'),
                    "param_name" => "service_desc",
                    "description" => __("Enter service Description.", 'lee_framework'),
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Icon", 'lee_framework'),
                    "param_name" => "service_icon",
                    "description" => __("Enter icon class name. You can find it at http://themes-pixeden.com/font-demos/7-stroke/", 'lee_framework')
                ),
                array(
                    "type" => "dropdown",
                    "heading" => "Service Hover Effect",
                    "param_name" => "service_hover",
                    "description" => __("Select effect when hover service icon", 'lee_framework'),
                    "value" => array(
                        __('None', 'lee_framework') => '',
                        __('Fly', 'lee_framework') => 'fly_effect',
                        __('Buzz', 'lee_framework') => 'buzz_effect',
                        __('Rotate', 'lee_framework') => 'rotate_effect',
                    )
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Extra class name", 'lee_framework'),
                    "param_name" => "el_class",
                    "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'lee_framework')
                )
            )
        );
        vc_map($service_box_params);

        // **********************************************************************// 
        // ! Register New Element: Share
        // **********************************************************************//
        $share_params = array(
            "name" => __("Lee Share", 'lee_framework'),
            "base" => "share",
            "content_element" => true,
            "category" => 'Lee Theme',
            "show_settings_on_create" => false,
            "params" => array(
                array(
                    "type" => "dropdown",
                    "heading" => __('Size', 'lee_framework'),
                    "param_name" => 'size',
                    "value" => array(
                        __('Normal', 'lee_framework') => '',
                        __('Large', 'lee_framework') => 'large'
                    )
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __('Style', 'lee_framework'),
                    "param_name" => 'style',
                    "value" => array(
                        __('Normal', 'lee_framework') => '',
                        __('Light', 'lee_framework') => 'light'
                    )
                )
            )
        );
        vc_map($share_params);

        // **********************************************************************// 
        // ! Register New Element: Google Map
        // **********************************************************************//
        $map_params = array(
            "name" => __("Lee Google Map", 'lee_framework'),
            "base" => "map",
            "content_element" => true,
            "category" => 'Lee Theme',
            "params" => array(
                array(
                    "type" => "textfield",
                    "heading" => __("Lat", 'lee_framework'),
                    "param_name" => "lat",
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Long", 'lee_framework'),
                    "param_name" => "long",
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Height", 'lee_framework'),
                    "param_name" => "height",
                    "value" => '400px'
                ),
                array(
                    "type" => "colorpicker",
                    "heading" => __("Color", 'lee_framework'),
                    "param_name" => "color",
                ),
                array(
                    "type" => "dropdown",
                    "heading" => "Map type",
                    "param_name" => "type",
                    "value" => array(
                        __('ROADMAP', 'lee_framework') => 'ROADMAP',
                        __('SATELLITE', 'lee_framework') => 'SATELLITE',
                        __('TERRAIN', 'lee_framework') => 'TERRAIN'
                    )
                )
            )
        );
        vc_map($map_params);

        // **********************************************************************// 
        // ! Register New Element: Menu vertical
        // **********************************************************************//
        $menus = wp_get_nav_menus( array( 'orderby' => 'name' ) );
        $option_menu = array();
        foreach ($menus as $menu_option) {
            $option_menu[$menu_option->name]=$menu_option->term_id;
        }
        $vertical_menu_params = array(
            "name" => __("Lee Menu Vertical", 'lee_framework'),
            "base" => "lt_menu_vertical",
            "category" => 'Lee Theme',
            "params" => array(
                array(
                    "type" => "textfield",
                    "heading" => __("Title", 'lee_framework'),
                    "param_name" => "title"
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Menu', 'lee_framework' ),
                    'param_name' => 'menu',
                    "value" => $option_menu,
                        "description" => __("Select Menu.", 'lee_framework')
                    )
                )
        );
        vc_map($vertical_menu_params);

        // **********************************************************************// 
        // ! Register New Element: Recent Posts
        // **********************************************************************//
        $lastest_params = array(
            "name" => __("Lee Latest posts", 'lee_framework'),
            "base" => "recent_post",
            "content_element" => true,
            "category" => 'Lee Theme',
            "params" => array(
                array(
                    "type" => "dropdown",
                    "heading" => "Show Type",
                    "param_name" => "show_type",
                    "value" => array(
                        __('Carousel', 'lee_framework') => '0',
                        __('Grid', 'lee_framework') => '1',
                    )
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Post number", 'lee_framework'),
                    "param_name" => "posts",
                    "value" => "8"
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Categories", 'lee_framework'),
                    "param_name" => "category",
                    "value" => '',
		    "description" => __('Input categories slug Divide links with ","', 'lee_framework')
                )
            )
        );
        vc_map($lastest_params);



        // **********************************************************************// 
        // ! Register New Element: Team Member
        // **********************************************************************//

        $team_member_params = array(
            'name' => 'Lee Team member',
            'base' => 'team_member',
            'category' => 'Lee Theme',
            'params' => array(
                array(
                    'type' => 'textfield',
                    "heading" => __("Member name", 'lee_framework'),
                    "param_name" => "name"
                ),
                array(
                    'type' => 'textfield',
                    "heading" => __("Member email", 'lee_framework'),
                    "param_name" => "email"
                ),
                array(
                    'type' => 'textfield',
                    "heading" => __("Position", 'lee_framework'),
                    "param_name" => "position"
                ),
                array(
                    'type' => 'attach_image',
                    "heading" => __("Avatar", 'lee_framework'),
                    "param_name" => "img"
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Image size", 'lee_framework'),
                    "param_name" => "img_size",
                    "description" => __("Enter image size. Example in pixels: 200x100 (Width x Height).", 'lee_framework')
                ),
                array(
                    "type" => "textarea_html",
                    "holder" => "div",
                    "heading" => __("Member information", 'lee_framework'),
                    "param_name" => "content",
                    "value" => __("Member description", 'lee_framework')
                ),
                // array(
                //     'type' => 'textfield',
                //     "heading" => __("Twitter link", 'lee_framework'),
                //     "param_name" => "twitter"
                // ),
                // array(
                //     'type' => 'textfield',
                //     "heading" => __("Facebook link", 'lee_framework'),
                //     "param_name" => "facebook"
                // ),
                // array(
                //     'type' => 'textfield',
                //     "heading" => __("Skype name", 'lee_framework'),
                //     "param_name" => "skype"
                // ),
                // array(
                //     'type' => 'textfield',
                //     "heading" => __("Instagram", 'lee_framework'),
                //     "param_name" => "instagram"
                // ),
                array(
                    "type" => "textfield",
                    "heading" => __("Extra Class", 'lee_framework'),
                    "param_name" => "class",
                    "description" => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'lee_framework')
                )
            )
        );  
        vc_map($team_member_params);

        // **********************************************************************// 
        // ! Register New Element: Recent Twitter
        // **********************************************************************//
        /*$twitter_params = array(
            "name" => __("Recent Tweets", 'lee_framework'),
            'base' => 'recent_twitter',
            'category' => 'Lee Theme',
            'params' => array(
                array(
                    "type" => "textfield",
                    "heading" => __("Title", 'lee_framework'),
                    "param_name" => "title",
                    "value" => "Recent Tweets"
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("User account name", 'lee_framework'),
                    "param_name" => "user"
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Consumer Key", 'lee_framework'),
                    "param_name" => "consumer_key"
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Consumer Secret", 'lee_framework'),
                    "param_name" => "consumer_secret"
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("User Token", 'lee_framework'),
                    "param_name" => "user_token"
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("User Secret", 'lee_framework'),
                    "param_name" => "user_secret"
                ),
                array(
                    "type" => 'checkbox',
                    "heading" => __("Display carousel?", 'lee_framework'),
                    "param_name" => "carousel",
                    "value" => array(
                        'Yes, please' => true
                    )
                ),
                array(
                    "type" => "dropdown",
                    "heading" => "Tweets to display",
                    "param_name" => "limit",
                    "value" => array(
                        __('1', 'lee_framework') => '1',
                        __('2', 'lee_framework') => '2',
                        __('3', 'lee_framework') => '3',
                        __('4', 'lee_framework') => '4',
                        __('5', 'lee_framework') => '5',
                        __('6', 'lee_framework') => '6',
                        __('7', 'lee_framework') => '7',
                        __('8', 'lee_framework') => '8',
                        __('9', 'lee_framework') => '9',
                        __('10', 'lee_framework') => '10'
                    )
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Extra Class", 'lee_framework'),
                    "param_name" => "class"
                )
            )
        );  
        vc_map($twitter_params);*/

        // **********************************************************************// 
        // ! Register New Element: Instagram
        // **********************************************************************//
        $instagram_params = array(
            "name" => __("Lee Instagram", 'lee_framework'),
            'base' => 'lee_instagram',
            'category' => 'Lee Theme',
            'params' => array(
                array(
                    "type" => "textfield",
                    "heading" => __("User name", 'lee_framework'),
                    "param_name" => "username",
                    "value" => ""
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Picture numbers", 'lee_framework'),
                    "param_name" => "photos"
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Extra Class", 'lee_framework'),
                    "param_name" => "class"
                )
            )
        );  
        vc_map($instagram_params);

        // **********************************************************************// 
        // ! Register New Element: Contact Footer
        // **********************************************************************//
        $contact_us_params = array(
            "name" => __("Lee Contact Footer", 'lee_framework'),
            'base' => 'contact_us',
            'category' => 'Lee Theme',
            'params' => array(
                array(
                    "type" => "textfield",
                    "heading" => __("Contact Logo", 'lee_framework'),
                    "param_name" => "title",
                    "value" => ""
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Address", 'lee_framework'),
                    "param_name" => "contact_address"
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Phone", 'lee_framework'),
                    "param_name" => "contact_phone"
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Email", 'lee_framework'),
                    "param_name" => "contact_email"
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Extra Class", 'lee_framework'),
                    "param_name" => "class"
                )
            )
        );  
        vc_map($contact_us_params);
    }
}

// Visual Composer plugin
if ( is_plugin_active( 'js_composer/js_composer.php' ) ) {
    add_filter( 'vc_shortcodes_css_class', 'lt_customize_vc_rows_columns', 10, 2 );
}

// Include custom new shortcodes
foreach (glob(LEE_FRAMEWORK_PLUGIN_PATH . '/includes/shortcodes/*.php') as $file) {
    include_once $file;
}

if(!function_exists('vc_getListProductDeals')){
    function vc_getListProductDeals(){
        global $woocommerce;
        $list = array();
        if(!$woocommerce) return $list;
        
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 100,
            'post_status' => 'publish',
            'paged' => 1
        );
        
        $args['meta_query'] = array();
        $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
        $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
        $args['meta_query'][] = array(
            'key' => '_sale_price_dates_to',
            'value' => '0',
            'compare' => '>'
        );
        $args['post__in'] = wc_get_product_ids_on_sale();
        
        $products = new WP_Query($args);
        if ( $products->have_posts() ){
            while ( $products->have_posts() ) {
                $products->the_post();
                global $product;
                if ( ! $product->is_visible() )
                    continue;
                
                $list[get_the_title()] = $product->id;
            }
        }
        
        return $list;
    }
}

if(!function_exists('lt_get_cat_product_array')){
    function lt_get_cat_product_array(){
        $categories = get_categories( array(
            'taxonomy' => 'product_cat',
            'orderby' => 'name'
        ) );
        $list = array(
            esc_html__('Select category', 'bleutheme') => ''
        );

        if(!empty($categories)){
            foreach ($categories as $v){
                $list[$v->name] = $v->term_id;
            }
        }

        return $list;
    }
}