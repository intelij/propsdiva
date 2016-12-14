<?php
if(class_exists('WC_Widget')){

    add_action( 'widgets_init', 'lt_product_categories_widget' );

    function lt_product_categories_widget() {
        register_widget( 'LT_Product_Categories_Widget' );
    }

    class LT_Product_Categories_Widget extends WC_Widget{
	
        /**
         * Category ancestors
         *
         * @var array
         */
        public $cat_ancestors;

        /**
         * Current Category
         *
         * @var bool
         */
        public $current_cat;

        /**
         * Constructor
         */
        public function __construct() {
            $this->widget_cssclass    = 'woocommerce widget_product_categories';
            $this->widget_description = esc_html__( 'Display product categories with Accordion or List type.', 'altotheme' );
            $this->widget_id          = 'lee_product_categories';
            $this->widget_name        = esc_html__( 'LT WC Categories', 'altotheme' );
            $this->settings           = array(
                'title'  => array(
                    'type'  => 'text',
                    'std'   => esc_html__( 'Product Categories', 'altotheme' ),
                    'label' => esc_html__( 'Title', 'altotheme' )
                ),
                'orderby' => array(
                    'type'  => 'select',
                    'std'   => 'name',
                    'label' => esc_html__( 'Order by', 'altotheme' ),
                    'options' => array(
                        'order' => esc_html__( 'Category Order', 'altotheme' ),
                        'name'  => esc_html__( 'Name', 'altotheme' )
                    )
                ),
                'count' => array(
                    'type'  => 'checkbox',
                    'std'   => 0,
                    'label' => esc_html__( 'Show product counts', 'altotheme' )
                ),
                'hierarchical' => array(
                    'type'  => 'checkbox',
                    'std'   => 1,
                    'label' => esc_html__( 'Show hierarchy', 'altotheme' )
                ),
                'show_children_only' => array(
                    'type'  => 'checkbox',
                    'std'   => 0,
                    'label' => esc_html__( 'Only show children of the current category', 'altotheme' )
                ),
                'accordion' => array(
                    'type'  => 'checkbox',
                    'std'   => 1,
                    'label' => esc_html__( 'Show as Accordion', 'altotheme' )
                )
            );
            parent::__construct();
        }
	
        /**
         * Updates a particular instance of a widget.
         *
         * @see WP_Widget->update
         *
         * @param array $new_instance
         * @param array $old_instance
         *
         * @return array
         */
        public function update($new_instance, $old_instance) {
            $this->lt_settings($new_instance);
            
            return parent::update($new_instance, $old_instance);
        }

        /**
         * form function.
         *
         * @see WP_Widget->form
         * @param array $instance
         */
        public function form($instance) {
            $this->lt_settings($instance);

            if (empty($this->settings)) {
                return;
            }

            foreach ($this->settings as $key => $setting) {
                $value = isset($instance[$key]) ? $instance[$key] : $setting['std'];
                $_id = $this->get_field_id($key);
                $_name = $this->get_field_name($key);

                switch ($setting['type']) {

                    case 'text' :
                        ?>
                        <p>
                            <label for="<?php echo $_id;?>"><?php echo $setting['label'];?></label>
                            <input class="widefat" id="<?php echo esc_attr($_id);?>" name="<?php echo $_name;?>" type="text" value="<?php echo esc_attr($value);?>" />
                        </p>
                        <?php
                        break;

                    case 'number' :
                        ?>
                        <p>
                            <label for="<?php echo $_id;?>"><?php echo $setting['label'];?></label>
                            <input class="widefat" id="<?php echo esc_attr($_id);?>" name="<?php echo $_name;?>" type="number" step="<?php echo esc_attr($setting['step']);?>" min="<?php echo esc_attr($setting['min']);?>" max="<?php echo esc_attr($setting['max']);?>" value="<?php echo esc_attr($value);?>" />
                        </p>
                        <?php
                        break;

                    case 'select' :
                        ?>
                        <p>
                            <label for="<?php echo $_id;?>"><?php echo $setting['label'];?></label>
                            <select class="widefat" id="<?php echo esc_attr($_id); ?>" name="<?php echo $_name;?>">
                                <?php foreach($setting['options'] as $o_key => $o_value):?>
                                    <option value="<?php echo esc_attr($o_key); ?>" <?php selected($o_key, $value); ?>><?php echo esc_html($o_value);?></option>
                                <?php endforeach; ?>
                            </select>
                        </p>
                        <?php
                        break;

                    case 'checkbox' :
                        ?>
                        <p>
                            <input id="<?php echo esc_attr($_id); ?>" name="<?php echo esc_attr($_name);?>" type="checkbox" value="1" <?php checked($value, 1);?> />
                            <label for="<?php echo $_id;?>"><?php echo $setting['label'];?></label>
                        </p>
                        <?php
                        break;

                    // Button chosen icon font
                    case 'icons':
                        echo LT_getTemplateAdminCategoriyIcon($_name, $_id, $setting['label'], $value);
                        break;
                }
            }
        }

        /**
         * Init settings after post types are registered.
         */
        public function lt_settings($instance) {
            // Default setting color
            if(empty($instance)){
                if($default = get_option('widget_' . $this->widget_id, true)){
                    foreach ($default as $k => $v){
                        $instance = $v;
                        break;
                    }
                }
            }
            
            if($top_level = get_terms('product_cat',
                array(
                    //'fields'       => 'term_id, title',
                    //'parent'       => 0,
                    'hierarchical' => true,
                    'hide_empty'   => false
                )
            )){
                foreach ($top_level as $v){
                    // Change settings
                    $this->settings['cat_'.$v->term_id] = array(
                        'type'  => 'icons',
                        'std'   => isset($instance['cat_'.$v->term_id]) ? $instance['cat_'.$v->term_id] : '',
                        'label' => '<b>' . $v->name . '</b>'
                    );
                }
            }

        }

        /**
         * widget function.
         *
         * @see WP_Widget
         *
         * @param array $args
         * @param array $instance
         *
         * @return void
         */
        public function widget( $args, $instance ) {
            global $wp_query, $post;
            $a          = isset( $instance['accordion'] ) ? $instance['accordion'] : $this->settings['accordion']['std'];
            $c          = isset( $instance['count'] ) ? $instance['count'] : $this->settings['count']['std'];
            $h          = isset( $instance['hierarchical'] ) ? $instance['hierarchical'] : $this->settings['hierarchical']['std'];
            $s          = isset( $instance['show_children_only'] ) ? $instance['show_children_only'] : $this->settings['show_children_only']['std'];
            $o          = isset( $instance['orderby'] ) ? $instance['orderby'] : $this->settings['orderby']['std'];
            $list_args  = array( 'show_count' => $c, 'hierarchical' => $h, 'taxonomy' => 'product_cat', 'hide_empty' => false );

            // Menu Order
            $list_args['menu_order'] = false;
            if ( $o == 'order' ) {
                $list_args['menu_order'] = 'asc';
            } else {
                $list_args['orderby']    = 'title';
            }

            // Setup Current Category
            $this->current_cat   = false;
            $this->cat_ancestors = array();

            if ( is_tax( 'product_cat' ) ) {
                $this->current_cat   = $wp_query->queried_object;
                $this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'product_cat' );
            } elseif ( is_singular( 'product' ) ) {
                $product_category = wc_get_product_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent' ) );

                if ( $product_category ) {
                    $this->current_cat   = end( $product_category );
                    $this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'product_cat' );
                }
            }

            // Show Siblings and Children Only
            if ( $s && $this->current_cat ) {

                // Top level is needed
                $top_level = get_terms(
                    'product_cat',
                    array(
                        'fields'       => 'ids',
                        'parent'       => 0,
                        'hierarchical' => true,
                        'hide_empty'   => false
                    )
                );

                // Direct children are wanted
                $direct_children = get_terms(
                    'product_cat',
                    array(
                        'fields'       => 'ids',
                        'parent'       => $this->current_cat->term_id,
                        'hierarchical' => true,
                        'hide_empty'   => false
                    )
                );

                // Gather siblings of ancestors
                $siblings  = array();
                if ( $this->cat_ancestors ) {
                    foreach ( $this->cat_ancestors as $ancestor ) {
                        $ancestor_siblings = get_terms(
                            'product_cat',
                            array(
                                'fields'       => 'ids',
                                'parent'       => $ancestor,
                                'hierarchical' => false,
                                'hide_empty'   => false
                            )
                        );
                        $siblings = array_merge( $siblings, $ancestor_siblings );
                    }
                }

                if ( $h ) {
                    $include = array_merge($top_level, $this->cat_ancestors, $siblings, $direct_children, array($this->current_cat->term_id));
                } else {
                    $include = array_merge($direct_children);
                }
                
                if (empty($include)) {
                    return;
                }

                $list_args['include']     = implode( ',', $include );
            }

            $this->widget_start($args, $instance);
            $menu_cat = new LT_Product_Cat_List_Walker();
            $menu_cat->setIcons($instance);
            $list_args['walker']                     = $menu_cat;
            $list_args['title_li']                   = '';
            $list_args['pad_counts']                 = 1;
            $list_args['show_option_none']           = esc_html__('No product categories exist.', 'altotheme' );
            $list_args['current_category']           = $this->current_cat ? $this->current_cat->term_id : '';
            $list_args['current_category_ancestors'] = $this->cat_ancestors;
            $accordion = '';
            if($a) $accordion = ' lt-accordion';
            echo '<div class="widget woocommerce widget_product_categories"><ul class="product-categories'.$accordion.'">';
            wp_list_categories( apply_filters( 'woocommerce_product_categories_widget_args', $list_args ) );
            echo '</ul></div>';

            $this->widget_end($args);
        }
    }
    
    if(!class_exists('WC_Product_Cat_List_Walker')){
        include_once WC()->plugin_path() . '/includes/walkers/class-product-cat-list-walker.php';
    }
    
    class LT_Product_Cat_List_Walker extends WC_Product_Cat_List_Walker{
	
        protected $_icons = array();

        public function setIcons($instance){
            $this->_icons = $instance;
        }

        /**
         * @see Walker::start_el()
         * @since 2.1.0
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param int $depth Depth of category in reference to parents.
         * @param integer $current_object_id
         */
        public function start_el(&$output, $cat, $depth = 0, $args = array(), $current_object_id = 0) {
            $output .= '<li class="cat-item cat-item-' . $cat->term_id;
            $lt_active = $accodion = $icon = '';
	    
            if(isset($this->_icons['cat_'.$cat->term_id]) && trim($this->_icons['cat_'.$cat->term_id]) != ''){
                $icon = '<i class="'.$this->_icons['cat_'.$cat->term_id].'"></i>';
                $icon .= '&nbsp;&nbsp;';
            }
	    
            if($args['current_category'] == $cat->term_id) {
                $output .= ' current-cat active';
                $lt_active = ' lt-active';
            }

            if ($args['has_children'] && $args['hierarchical']) {
                $output .= ' cat-parent li_accordion';
                $accodion = '<a href="javascript:void(0);" class="accordion"><span class="icon fa pe-7s-plus"></span></a>';
                if ($args['current_category'] == $cat->term_id) {
                    $accodion = '<a href="javascript:void(0);" class="accordion"><span class="icon pe-7s-less"></span></a>';
                }
            }

            if ($args['current_category_ancestors'] && $args['current_category'] && in_array($cat->term_id, $args['current_category_ancestors'])) {
                $output .= ' current-cat-parent active';
                $accodion = '<a href="javascript:void(0);" class="accordion"><span class="icon pe-7s-less"></span></a>';
            }
            
            $output .= '">'.$accodion;
	    
            $output .=  '<a href="' . get_term_link((int)$cat->term_id, $this->tree_type) . '" data-id="'.esc_attr((int)$cat->term_id).'" class="lt-filter-by-cat' . $lt_active . '">' . $icon . $cat->name . '</a>';

            if ( $args['show_count'] ) {
                $output .= ' <span class="count">(' . $cat->count . ')</span>';
            }
        }
    }
}

function LT_getTemplateAdminCategoriyIcon($_name, $_id, $label, $value){
    $content = '<p>';
        $content .= '<a class="lt-chosen-icon" data-fill="' . esc_attr($_id) . '">' . esc_html__('Click select icon for ', 'altotheme') . '</a>';
        $content .= '<span id="ico-' . esc_attr($_id) . '">';
            if($value):
                $content .= '<i class="' . esc_attr($value) . '"></i>';
                $content .= '<a href="javascript:void(0);" class="lt-remove-icon" data-id="' . esc_attr($_id) . '">';
                    $content .= '<i class="fa fa-remove"></i>';
                $content .= '</a>';
            endif;
        $content .= '</span>';
        $content .= '<label for="' . $_id . '">' . $label . '</label><br />';
        $content .= '<input class="widefat" id="' . esc_attr($_id) . '" name="' . $_name . '" type="hidden" readonly="true" value="' . esc_attr($value) . '" />';
    $content .= '</p>';
    
    return $content;
}