<?php
if(class_exists('WooCommerce')){
    class LT_EXT_WC_Query{
        
        private $_product;
        
        public function __construct() {
            global $woocommerce;
            $this->_product = $woocommerce->query;
        }

        public function lt_product_filter_variations() {
            global $woocommerce;
            if( version_compare( $woocommerce->version, '2.6.1', ">=" ) ) {
                return;
            }
            if (is_active_widget(false, false, 'lt_woocommerce_filter_variations', true) && !is_admin() ) {
                
                global $_chosen_attributes;

                if(!isset($_chosen_attributes)){
                    $_chosen_attributes = array();
                }
                
                $attribute_taxonomies = wc_get_attribute_taxonomies();
                if ( $attribute_taxonomies ) {
                    foreach ( $attribute_taxonomies as $tax ) {

                        $attribute       = wc_sanitize_taxonomy_name( $tax->attribute_name );
                        $taxonomy        = wc_attribute_taxonomy_name( $attribute );
                        $name            = 'filter_' . $attribute;
                        $query_type_name = 'query_type_' . $attribute;

                        if ( ! empty( $_GET[ $name ] ) && taxonomy_exists( $taxonomy ) ) {

                            $_chosen_attributes[ $taxonomy ]['terms'] = explode( ',', $_GET[ $name ] );

                            if ( empty( $_GET[ $query_type_name ] ) || ! in_array( strtolower( $_GET[ $query_type_name ] ), array( 'and', 'or' ) ) )
                                $_chosen_attributes[ $taxonomy ]['query_type'] = apply_filters( 'woocommerce_layered_nav_default_query_type', 'and' );
                            else
                                $_chosen_attributes[ $taxonomy ]['query_type'] = strtolower( $_GET[ $query_type_name ] );

                        }
                    }
                }

                add_filter('loop_shop_post_in', array($this->_product, 'layered_nav_query'));
            }
        }
        
        public function lt_product_filter_price() {
            if (is_active_widget(false, false, 'lt_woocommerce_price_filter', true) && !is_admin()){
		
                wp_register_script('wc-jquery-ui-touchpunch', get_template_directory_uri() . '/admin/assets/js/woocommerce/jquery-ui-touch-punch.min.js', array('jquery-ui-slider'), WC_VERSION, true);
                wp_register_script('wc-price-slider', get_template_directory_uri() . '/admin/assets/js/woocommerce/price-slider.min.js', array( 'jquery-ui-slider', 'wc-jquery-ui-touchpunch'), WC_VERSION, true);

                wp_localize_script('wc-price-slider', 'woocommerce_price_slider_params', array(
                    'currency_symbol' 	=> get_woocommerce_currency_symbol(),
                    'currency_pos'      => get_option('woocommerce_currency_pos'),
                    'min_price'		=> isset($_GET['min_price']) ? esc_attr($_GET['min_price']) : '',
                    'max_price'		=> isset($_GET['max_price']) ? esc_attr($_GET['max_price']) : ''
                ));
                
                global $woocommerce;
                if( version_compare( $woocommerce->version, '2.6.1', "<" ) ) {
                    add_filter('loop_shop_post_in', array($this->_product, 'price_filter'));
                }
            }
        }
        
        /**
         * lt_filter_by_variations
         *
         * @param array $_chosen_attributes
         * @param array $filtered_posts
         * @return array
         */
        public function lt_filter_by_variations($_chosen_attributes, $filtered_posts = array()) {
            if ( sizeof( $_chosen_attributes ) > 0 ) {

                $matched_products   = array(
                    'and' => array(),
                    'or'  => array()
                );
                $filtered_attribute = array(
                    'and' => false,
                    'or'  => false
                );

                foreach ( $_chosen_attributes as $attribute => $data ) {
                    $matched_products_from_attribute = array();
                    $filtered = false;

                    if ( sizeof( $data['terms'] ) > 0 ) {
                        foreach ( $data['terms'] as $value ) {
                            $posts = get_posts(
                                array(
                                    'post_type' 	=> 'product',
                                    'numberposts' 	=> -1,
                                    'post_status' 	=> 'publish',
                                    'fields' 		=> 'ids',
                                    'no_found_rows' => true,
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' 	=> $attribute,
                                            'terms' 	=> $value,
                                            'field' 	=> 'term_id'
                                        )
                                    )
                                )
                            );

                            if ( ! is_wp_error( $posts ) ) {

                                if ( sizeof( $matched_products_from_attribute ) > 0 || $filtered ) {
                                    $matched_products_from_attribute = $data['query_type'] == 'or' ? array_merge( $posts, $matched_products_from_attribute ) : array_intersect( $posts, $matched_products_from_attribute );
                                } else {
                                    $matched_products_from_attribute = $posts;
                                }

                                $filtered = true;
                            }
                        }
                    }

                    if ( sizeof( $matched_products[ $data['query_type'] ] ) > 0 || $filtered_attribute[ $data['query_type'] ] === true ) {
                        $matched_products[ $data['query_type'] ] = ( $data['query_type'] == 'or' ) ? array_merge( $matched_products_from_attribute, $matched_products[ $data['query_type'] ] ) : array_intersect( $matched_products_from_attribute, $matched_products[ $data['query_type'] ] );
                    } else {
                        $matched_products[ $data['query_type'] ] = $matched_products_from_attribute;
                    }

                    $filtered_attribute[ $data['query_type'] ] = true;

                    $this->filtered_product_ids_for_taxonomy[ $attribute ] = $matched_products_from_attribute;
                }

                // Combine our AND and OR result sets
                if ( $filtered_attribute['and'] && $filtered_attribute['or'] )
                    $results = array_intersect( $matched_products[ 'and' ], $matched_products[ 'or' ] );
                else
                    $results = array_merge( $matched_products[ 'and' ], $matched_products[ 'or' ] );

                if ( $filtered ) {

                    $this->_product->layered_nav_post__in   = $results;
                    $this->_product->layered_nav_post__in[] = 0;

                    if ( sizeof( $filtered_posts ) == 0 ) {
                        $filtered_posts   = $results;
                        $filtered_posts[] = 0;
                    } else {
                        $filtered_posts   = array_intersect( $filtered_posts, $results );
                        $filtered_posts[] = 0;
                    }

                }
            }

            return (array) $filtered_posts;
        }
        
        public function get_catalog_ordering_args($orderby = '', $order = ''){
            return $this->_product->get_catalog_ordering_args($orderby, $order);
        }
        
        public function price_filter($filtered_posts = array()){
            global $woocommerce;
            if( version_compare( $woocommerce->version, '2.6.1', "<" ) ) {
                return $this->_product->price_filter($filtered_posts);;
            }
            
            return false;
        }
        
        public function get_Parent_Obj(){
            return $this->_product;
        }
        
        public function lt_getPostSearch($s, $old = array()){
            $posts = get_posts(
                array(
                    'post_type' 	=> 'product',
                    'numberposts' 	=> -1,
                    'post_status' 	=> 'publish',
                    'fields' 		=> 'ids',
                    'no_found_rows' => true,
                    's'             => $s
                )
            );
            
            if ( ! is_wp_error( $posts ) && count($posts) ) {

                foreach ($posts as $v){
                    if(!in_array($v, $old)){
                        $old[] = $v;
                    }
                }
            }
            
            return $old;
        }
    
    }
    
    $lt_wc_query = new LT_EXT_WC_Query();
}