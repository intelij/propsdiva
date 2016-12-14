<?php

if(class_exists('WooCommerce')){

    add_action( 'widgets_init', 'lt_product_filter_price_widget' );
    function lt_product_filter_price_widget() {
        register_widget('LT_WC_Widget_Price_Filter');
        unregister_widget('WC_Widget_Price_Filter');
    }
    
    add_action( 'init', 'lt_init_filter_price' );
    function lt_init_filter_price(){
        global $lt_wc_query;
        $lt_wc_query->lt_product_filter_price();
    }

    /**
     * Price Filter Widget and related functions
     *
     * Generates a range slider to filter products by price.
     *
     * @author   WooThemes
     * @category Widgets
     * @package  WooCommerce/Widgets
     * @version  2.3.0
     * @extends  WC_Widget
     */
    class LT_WC_Widget_Price_Filter extends WC_Widget {

        /**
         * Constructor
         */
        public function __construct() {
            $this->widget_cssclass    = 'woocommerce widget_price_filter';
            $this->widget_description = esc_html__( 'Shows a price filter slider in a widget which lets you narrow down the list of shown products when viewing product categories.', 'altotheme' );
            $this->widget_id          = 'lt_woocommerce_price_filter';
            $this->widget_name        = esc_html__( 'LT WC Price Filter', 'altotheme' );
            $this->settings           = array(
                'title'  => array(
                    'type'  => 'text',
                    'std'   => esc_html__( 'Filter by price', 'altotheme' ),
                    'label' => esc_html__( 'Title', 'altotheme' )
                )
            );

            parent::__construct();
        }

        /**
         * widget function.
         *
         * @see WP_Widget
         *
         * @param array $args
         * @param array $instance
         */
        public function widget( $args, $instance ) {
            global $_chosen_attributes, $wpdb, $wp;

            if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
                return;
            }
            
            /*if ( sizeof( WC()->query->unfiltered_product_ids ) == 0 ) {
                return; // None shown - return
            }*/

            $min_price = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '';
            $max_price = isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : '';
            $hasPrice = ($min_price || $max_price) ? '1' : '0';

            wp_enqueue_script( 'wc-price-slider' );

            // Remember current filters/search
            $fields = '';

            if ( get_search_query() ) {
                $fields .= '<input type="hidden" name="s" value="' . get_search_query() . '" />';
            }

            if ( ! empty( $_GET['post_type'] ) ) {
                $fields .= '<input type="hidden" name="post_type" value="' . esc_attr( $_GET['post_type'] ) . '" />';
            }

            if ( ! empty ( $_GET['product_cat'] ) ) {
                $fields .= '<input type="hidden" name="product_cat" value="' . esc_attr( $_GET['product_cat'] ) . '" />';
            }

            if ( ! empty( $_GET['product_tag'] ) ) {
                $fields .= '<input type="hidden" name="product_tag" value="' . esc_attr( $_GET['product_tag'] ) . '" />';
            }

            if ( ! empty( $_GET['orderby'] ) ) {
                $fields .= '<input type="hidden" name="orderby" value="' . esc_attr( $_GET['orderby'] ) . '" />';
            }

            if ( $_chosen_attributes ) {
                foreach ( $_chosen_attributes as $attribute => $data ) {
                    $taxonomy_filter = 'filter_' . str_replace( 'pa_', '', $attribute );

                    $fields .= '<input type="hidden" name="' . esc_attr( $taxonomy_filter ) . '" value="' . esc_attr( implode( ',', $data['terms'] ) ) . '" />';

                    if ( 'or' == $data['query_type'] ) {
                        $fields .= '<input type="hidden" name="' . esc_attr( str_replace( 'pa_', 'query_type_', $attribute ) ) . '" value="or" />';
                    }
                }
            }
            
            $min = floor( $wpdb->get_var( "
                SELECT min(meta_value + 0)
                FROM {$wpdb->posts} as posts
                LEFT JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.post_id
                WHERE meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price', '_min_variation_price' ) ) ) ) . "')
                AND meta_value != ''
            " ) );
            $max = ceil( $wpdb->get_var( "
                SELECT max(meta_value + 0)
                FROM {$wpdb->posts} as posts
                LEFT JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.post_id
                WHERE meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
            " ) );

            if ( $min == $max ) {
                return;
            }

            $this->widget_start( $args, $instance );

            if ( '' == get_option( 'permalink_structure' ) ) {
                $form_action = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
            } else {
                $form_action = preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ) );
            }

            if ( wc_tax_enabled() && 'incl' === get_option( 'woocommerce_tax_display_shop' ) && ! wc_prices_include_tax() ) {
                $tax_classes = array_merge( array( '' ), WC_Tax::get_tax_classes() );
                $min         = 0;

                foreach ( $tax_classes as $tax_class ) {
                    $tax_rates = WC_Tax::get_rates( $tax_class );
                    $class_min = $min + WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $min, $tax_rates ) );
                    $class_max = $max + WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $max, $tax_rates ) );

                    if ( $min === 0 || $class_min < $min ) {
                        $min = $class_min;
                    }
                    if ( $class_max > $max ) {
                        $max = $class_max;
                    }
                }
            }
            $datamin = esc_attr( apply_filters( 'woocommerce_price_filter_widget_min_amount', $min ) );
            $datamax = esc_attr( apply_filters( 'woocommerce_price_filter_widget_max_amount', $max ) );
            echo '<form method="get" action="' . esc_url( $form_action ) . '">' .
                '<div class="price_slider_wrapper">' .
                    '<div class="price_slider" style="display:none;"></div>' .
                    '<div class="price_slider_amount">' .
                        '<input type="text" id="min_price" name="min_price" value="" data-min="' . $datamin . '" placeholder="' . esc_attr__('Min price', 'altotheme' ) . '" />' .
                        '<input type="text" id="max_price" name="max_price" value="" data-max="' . $datamax . '" placeholder="' . esc_attr__( 'Max price', 'altotheme' ) . '" />' .
                        '<button type="submit" class="button">' . esc_html__( 'Filter', 'altotheme' ) . '</button>' .
                        '<div class="price_label" style="display:none;">' .
                            esc_html__( 'Price:', 'altotheme' ) . ' <span class="from"></span> &mdash; <span class="to"></span>' .
                        '</div>' .
                        $fields .
                        '<div class="clear"></div>' .
                    '</div>' .
                '</div>' .
                '<input type="hidden" class="lt_hasPrice" name="lt_hasPrice" value="' . $hasPrice . '" />' .
            '</form>';

            $this->widget_end( $args );
        }
    }
}
