<?php

// Custom WooCommerce product fields
if(!function_exists('lt_wc_custom_product_data_fields')){

    function lt_wc_custom_product_data_fields(){

        $custom_product_data_fields = array();

        $custom_product_data_fields[] = array(
            'tab_name'    => esc_html__('Additional', 'altotheme'),
        );
        $custom_product_data_fields[] = array(
            'id'          => '_bubble_hot',
            'type'        => 'text',
            'label'       => esc_html__('Custom Bubble Title', 'altotheme'),
            'placeholder' => esc_html__('HOT', 'altotheme'),
            'class'       => 'large',
            'style'       => 'width: 100%;',
            'description' => esc_html__('Enter bubble label (NEW, HOT etc...).', 'altotheme'),
        );
        $custom_product_data_fields[] = array(
            'id'          => '_product_video_link',
            'type'        => 'text',
            'placeholder' => 'https://www.youtube.com/watch?v=link-test',
            'label'       => esc_html__('Product Video Link', 'altotheme'),
            'style'       => 'width:100%;',
            'description' => esc_html__('Enter a Youtube or Vimeo Url of the product video here.', 'altotheme'),
        );

        $custom_product_data_fields[] = array(
            'id'          => '_product_video_size',
            'type'        => 'text',
            'label'       => esc_html__('Product Video Size', 'altotheme'),
            'placeholder' => esc_html__('800x800', 'altotheme'),
            'class'       => 'large',
            'style'       => 'width:100%;',
            'description' => esc_html__('Default is 800x800. (Width X Height)', 'altotheme'),
            //'desc_tip'    => true,
        );

        return $custom_product_data_fields;
    }
}

