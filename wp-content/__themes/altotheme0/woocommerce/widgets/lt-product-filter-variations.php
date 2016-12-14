<?php

if(class_exists('WooCommerce')){

    add_action( 'widgets_init', 'lt_product_variations_widget' );

    function lt_product_variations_widget() {
        register_widget('LT_Product_Variations_Widget');
        unregister_widget('WC_Widget_Layered_Nav');
    }

    function lt_init_chosen_attributes(){
        global $lt_wc_query;
        $lt_wc_query->lt_product_filter_variations();
    }
    add_action( 'init', 'lt_init_chosen_attributes' );

    /**
     * Layered Navigation Widget
     *
     * @author   WooThemes
     * @category Widgets
     * @package  WooCommerce/Widgets
     * @version  2.3.0
     * @extends  WC_Widget
     */
    class LT_Product_Variations_Widget extends WC_Widget{
        
        public static $lt_widget_id = 'lt_woocommerce_filter_variations';

        /**
         * Constructor
         */
        public function __construct() {
            $this->widget_cssclass    = 'woocommerce widget_layered_nav';
            $this->widget_description = esc_html__( 'Shows a custom attribute in a widget which lets you narrow down the list of products when viewing product categories.', 'altotheme' );
            $this->widget_id          = LT_Product_Variations_Widget::$lt_widget_id;
            $this->widget_name        = esc_html__( 'LT WC Variations Filter', 'altotheme' );

            parent::__construct();
        }

        /**
         * update function.
         *
         * @see WP_Widget->update
         *
         * @param array $new_instance
         * @param array $old_instance
         *
         * @return array
         */
        public function update($new_instance, $old_instance) {
            $this->init_settings($new_instance);
            
            return parent::update( $new_instance, $old_instance );
        }

        /**
         * form function.
         *
         * @see WP_Widget->form
         *
         * @param array $instance
         */
        public function form( $instance ) {
            $this->init_settings($instance);
            if ( empty( $this->settings ) ) {
                return;
            }
            
            echo "<p class='lt-widget-instance' data-instance='". esc_attr(json_encode($instance)) . "'></p>";
            foreach ( $this->settings as $key => $setting ) {
                $value = isset( $instance[ $key ] ) ? $instance[ $key ] : $setting['std'];
                $clss = isset($setting['class']) ? ' ' . $setting['class'] : '';
                switch ( $setting['type'] ) {

                    case 'text' :
                        ?>
                        <p>
                            <label for="<?php echo $this->get_field_id( $key ); ?>"><?php echo $setting['label']; ?></label>
                            <input class="widefat<?php echo esc_attr($clss);?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo $this->get_field_name( $key ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>" />
                        </p>
                        <?php
                        break;

                    case 'number' :
                        ?>
                        <p>
                            <label for="<?php echo $this->get_field_id( $key ); ?>"><?php echo $setting['label']; ?></label>
                            <input class="widefat<?php echo esc_attr($clss);?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo $this->get_field_name( $key ); ?>" type="number" step="<?php echo esc_attr( $setting['step'] ); ?>" min="<?php echo esc_attr( $setting['min'] ); ?>" max="<?php echo esc_attr( $setting['max'] ); ?>" value="<?php echo esc_attr( $value ); ?>" />
                        </p>
                        <?php
                        break;

                    case 'select' :
                        ?>
                        <p>
                            <label for="<?php echo $this->get_field_id( $key ); ?>"><?php echo $setting['label']; ?></label>
                            <select class="widefat<?php echo esc_attr($clss);?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo $this->get_field_name( $key ); ?>" data-num="<?php echo esc_attr($this->number);?>">
                                <?php foreach ( $setting['options'] as $option_key => $option_value ) : ?>
                                    <option value="<?php echo esc_attr( $option_key ); ?>" <?php selected( $option_key, $value ); ?>><?php echo esc_html( $option_value ); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </p>
                        <?php
                        break;

                    case 'checkbox' :
                        ?>
                        <p>
                            <input class="widefat<?php echo esc_attr($clss);?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="checkbox" value="1" <?php checked( $value, 1 ); ?> />
                            <label for="<?php echo $this->get_field_id( $key ); ?>"><?php echo $setting['label']; ?></label>
                        </p>
                        <?php
                        break;
                
                    case 'color' :
                        echo LT_getTemplateAdminColor(
                            $this->get_field_name($key),
                            $this->get_field_id($key),
                            $setting['label'],
                            $clss,
                            $value
                        );
                        
                        break;
                }
            }
        }
        
        private function settingListColors($instance){
            if(!isset($instance['vari_type']) || $instance['vari_type'] != '1'){
                return;
            }
            
            $taxonomy = wc_attribute_taxonomy_name($instance['attribute']);
            if ( ! taxonomy_exists( $taxonomy ) ) {
                return;
            }

            $get_terms_args = array( 'hide_empty' => false );
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

            $terms = get_terms( $taxonomy, $get_terms_args );
            
            if(!empty($terms)){
                foreach ($terms as $v){
                    $this->settings['color_'.$v->term_id] = array(
                        'type'  => 'color',
                        'std'   => '#FF0000',
                        'label'   => esc_html__( 'Select color for ', 'altotheme' ) . '<b>' . $v->name . '</b>'
                    );
                }
            }
        }

        /**
         * Init settings after post types are registered
         */
        public function init_settings($instance) {
            $attribute_array      = array();
            $attribute_taxonomies = wc_get_attribute_taxonomies();

            if ( $attribute_taxonomies ) {
                foreach ( $attribute_taxonomies as $tax ) {
                    if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
                        $attribute_array[ $tax->attribute_name ] = $tax->attribute_name;
                    }
                }
            }

            $this->settings = array(
                'title' => array(
                    'type'  => 'text',
                    'std'   => esc_html__( 'Filter by', 'altotheme' ),
                    'label' => esc_html__( 'Title', 'altotheme' )
                ),
                'attribute' => array(
                    'type'    => 'select',
                    'class'   => 'lt-select-attr',
                    'std'     => '',
                    'label'   => esc_html__( 'Attribute', 'altotheme' ),
                    'options' => $attribute_array
                ),
                'vari_type' => array(
                    'type'  => 'select',
                    'class'   => 'lt-vari-type',
                    'std'   => 0,
                    'label'   => esc_html__( 'Variation type', 'altotheme' ),
                    'options' => array(
                        '0' => esc_html__( 'None', 'altotheme' ),
                        '1'  => esc_html__( 'Color', 'altotheme' ),
                        '2'  => esc_html__( 'Size', 'altotheme' ),
                    )
                ),
                'query_type' => array(
                    'type'    => 'select',
                    'std'     => 'and',
                    'label'   => esc_html__( 'Query type', 'altotheme' ),
                    'options' => array(
                        'and' => esc_html__( 'AND', 'altotheme' ),
                        'or'  => esc_html__( 'OR', 'altotheme' )
                    )
                )
            );
            
            $this->settingListColors($instance);
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
            global $lt_opt, $woocommerce;
            
            $compare_ver = version_compare( $woocommerce->version, '2.6.1', ">=" );
            
            if (!is_post_type_archive('product') && !is_tax( get_object_taxonomies('product'))){
                return;
            }
            
            $taxonomy     = isset($instance['attribute']) ? wc_attribute_taxonomy_name($instance['attribute']) : $this->settings['attribute']['std'];
            $query_type   = isset($instance['query_type']) ? $instance['query_type'] : $this->settings['query_type']['std'];

            if ( ! taxonomy_exists( $taxonomy ) ) {
                return;
            }
            
            $get_terms_args = array( 'hide_empty' => false );
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

            $terms = get_terms( $taxonomy, $get_terms_args );
            
            //$s = isset($_GET['s']) ? $_GET['s'] : false;
            
            $term_counts = $compare_ver ? get_filtered_term_product_counts(wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type, []) : null;
            
            $hasResult = false;
            if ( 0 < count( $terms ) ) {
                $taxonomy_filter = str_replace( 'pa_', '', $taxonomy );
                $current_filter = isset($_GET['filter_'.$taxonomy_filter]) ? explode(',', $_GET['filter_'.$taxonomy_filter]) : array();
                $vari_type = '';
                if(isset($instance['vari_type'])){
                    switch ($instance['vari_type']){
                        case '1':
                            $vari_type = 'color';
                            break;
                        case '2':
                            $vari_type = 'size';
                            break;
                        default : break;
                    }
                }
                
                $content = '<ul>';
                foreach ( $terms as $k => $term ) {
                    if($compare_ver) {
                        $count = isset($term_counts[$term->term_id]) ? $term_counts[$term->term_id] : 0;
                    }else{
                        // Get count based on current view - uses transients
                        $transient_name = 'wc_ln_count_' . md5( sanitize_key( $taxonomy ) . sanitize_key( $term->term_taxonomy_id ) );
                        if ( false === ( $_products_in_term = get_transient( $transient_name ) ) ) {
                            $_products_in_term = get_objects_in_term( $term->term_id, $taxonomy );
                            set_transient( $transient_name, $_products_in_term );
                        }
                        // If this is an AND query, only show options with count > 0
                        if ( 'and' == $query_type ) {
                            $count = sizeof( array_intersect( 
                                $_products_in_term, WC()->query->filtered_product_ids 
                            ) );
                        // If this is an OR query, show all options so search can be expanded
                        } else {
                            $count = sizeof( array_intersect(
                                $_products_in_term, WC()->query->unfiltered_product_ids 
                            ) );
                        }
                    }
                    
                    $attr = esc_attr(str_replace('pa_', '', $term->taxonomy));
                    $termId = esc_attr($term->term_id);
                    $termSlug = $compare_ver ? esc_attr($term->slug) : $termId;
                    
                    $liClass = 'lt-even';
                    if($k % 2 == 0){
                        $liClass = 'lt-odd';
                    }
                    
                    $liClass .= (isset($lt_opt['vari_hide_empty']) && !$lt_opt['vari_hide_empty']) ? ' no-hidden' : '';
                    $style = (isset($instance['color_'.$term->term_id]) && $vari_type == 'color') ? ' style="background:' . $instance['color_'.$term->term_id].'"' : '';

                    // Current Filter = this widget
                    if (isset($current_filter) && is_array($current_filter) && in_array($termSlug, $current_filter)){
                        $class = ' chosen lt-chosen';
                        $aclass = ' lt-filter-var-chosen';
                    } else {
                        $class = $aclass = '';
                    }
                    
                    $countClss = 'count';
                    if($vari_type){
                        $class .= ' lt-li-filter-'.$vari_type;
                        $aclass .= ' lt-filter-'.$vari_type;
                        $countClss .= ' lt-count-filter-'.$vari_type;
                    }
                    
                    if(!$count){
                        $class .= (isset($lt_opt['vari_hide_empty']) && !$lt_opt['vari_hide_empty']) ? '' : ' hidden-tag';
                    }else{
                        $hasResult = true;
                    }

                    $content .= '<li class="' . $liClass . $class . ' lt-attr-' . $attr .' lt_' . $attr . '_' . $termId .'">';

                    $content .= '<a class="lt-filter-by-variations' . $aclass . '" '
                            . 'data-term_id="' . $termId . '" '
                            . 'data-term_slug="' . $termSlug . '" '
                            . 'data-attr="' . $attr . '" '
                            . 'data-type="' . esc_attr($query_type) . '" '
                            . 'href="javascript:void(0);">';
                    
                    if($vari_type == 'color'){
                        $content .= '<span class="lt-filter-color-border"><span class="lt-filter-color-span" '.$style.'></span></span>';
                    }
                    $content .= $term->name;
                    $content .= '</a>';
                    $content .= ' <span class="'.$countClss.'">(' . $count . ')</span></li>';
                }
                $content .= '</ul>';
                
                $clssRes = 'lt_div_attr_' . str_replace('pa_', '', $taxonomy);
                $clssRes .= (isset($lt_opt['vari_hide_empty']) && !$lt_opt['vari_hide_empty']) ? ' no-hidden' : '';
                if(!$hasResult){
                    $clssRes .= (isset($lt_opt['vari_hide_empty']) && !$lt_opt['vari_hide_empty']) ? '' : ' hidden-tag';
                }
                echo '<div class="' . $clssRes . '">';
                $this->widget_start($args, $instance);
                echo $content;
                $this->widget_end($args);
                echo '</div>';
            }
        }
    }
}

function LT_getTemplateAdminColor($name, $key, $label, $clss, $value){
    return 
    '<p class="lt_p_color">' .
        '<label for="' . esc_attr($key) . '">' . $label . '</label><br />' .
        '<input class="widefat lt-color-field' . esc_attr($clss) . '" id="' . esc_attr($key) . '" name="' . $name . '" type="text" value="' . esc_attr($value) . '" />' .
    '</p>';
}

function lt_list_colors_admin(){
    $taxonomy = wc_attribute_taxonomy_name($_POST['taxonomy']);
    if ( ! taxonomy_exists( $taxonomy ) ) {
        die();
    }
    
    $get_terms_args = array( 'hide_empty' => false );
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
    
    $terms = get_terms( $taxonomy, $get_terms_args );
    $out = '';
    $num = (int)$_POST['num'];
    $instance = @json_decode(str_replace('\\', '', $_POST['instance']));
    if(!empty($terms)){
        
        // Default setting color
        if($default = get_option('widget_lt_woocommerce_filter_variations', true)){
            foreach ($default as $k => $v){
                if(isset($v['vari_color']) && $v['vari_color'] == 1 && $v['vari_type'] == 1){
                    $default = $v;
                    break;
                }
            }
        }
        
        foreach ($terms as $v){
            $name = 'widget-' . LT_Product_Variations_Widget::$lt_widget_id . '[' . $num . '][color_' . $v->term_id . ']';
            $key = 'widget-' . LT_Product_Variations_Widget::$lt_widget_id . '-' . $num . '-color_' . $v->term_id;
            $label = esc_html__('Select color for ', 'altotheme') . '<b>' . $v->name . '</b>';
            $clss = '';
            $value = '#FF0000';
            if(isset($instance->{'color_' . $v->term_id})) {
                $value = $instance->{'color_' . $v->term_id};
            } elseif(isset($default['color_' . $v->term_id])) {
                $value = $default['color_' . $v->term_id];
            }
            
            $out .= LT_getTemplateAdminColor($name, $key, $label, $clss, $value);
        }
    }
    
    die($out);
}
add_action('wp_ajax_lt_list_colors_admin', 'lt_list_colors_admin');
