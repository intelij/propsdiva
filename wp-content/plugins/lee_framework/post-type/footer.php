<?php
function lee_register_footer(){
    $labels = array(
        'name' => __( 'Footer', 'lee_framework' ),
        'singular_name' => __( 'Footer', 'lee_framework' ),
        'add_new' => __( 'Add New Footer', 'lee_framework' ),
        'add_new_item' => __( 'Add New Footer', 'lee_framework' ),
        'edit_item' => __( 'Edit Footer', 'lee_framework' ),
        'new_item' => __( 'New Footer', 'lee_framework' ),
        'view_item' => __( 'View Footer', 'lee_framework' ),
        'search_items' => __( 'Search Footers', 'lee_framework' ),
        'not_found' => __( 'No Footers found', 'lee_framework' ),
        'not_found_in_trash' => __( 'No Footers found in Trash', 'lee_framework' ),
        'parent_item_colon' => __( 'Parent Footer:', 'lee_framework' ),
        'menu_name' => __( 'Footers', 'lee_framework' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'description' => 'List Footer',
        'supports' => array( 'title', 'editor' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_nav_menus' => false,
        'publicly_queryable' => false,
        'exclude_from_search' => false,
        'has_archive' => false,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => false
    );
    register_post_type( 'footer', $args );

    if($options = get_option('wpb_js_content_types')){
        $check = true;
        foreach ($options as $key => $value) {
            if($value=='footer') $check = false;
        }
        if($check)
            $options[] = 'footer';
    }else{
        $options = array('page','footer');
    }
    update_option( 'wpb_js_content_types',$options );

}

add_action('init','lee_register_footer');