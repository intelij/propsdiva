<?php
if(class_exists('WP_Widget_Tag_Cloud')){

    add_action( 'widgets_init', 'lt_tag_cloud_widget' );

    function lt_tag_cloud_widget() {
        register_widget('LT_Widget_Tag_Cloud');
        unregister_widget('WP_Widget_Tag_Cloud');
    }

    class LT_Widget_Tag_Cloud extends WP_Widget_Tag_Cloud{

        /**
         * Constructor
         */
        public function __construct() {
            parent::__construct();
        }
	
        /**
         * @param array $args
         * @param array $instance
         */
        public function widget( $args, $instance ) {
            $class = ($instance['taxonomy'] == 'product_cat') ? ' lt-tag-cloud' : '';
            $current_taxonomy = $this->_get_current_taxonomy($instance);
            if ( !empty($instance['title']) ) {
                $title = $instance['title'];
            } else {
                if ( 'post_tag' == $current_taxonomy ) {
                    $title = esc_html__('Tags', 'altotheme');
                } else {
                    $tax = get_taxonomy($current_taxonomy);
                    $title = $tax->labels->name;
                }
            }

            /** This filter is documented in wp-includes/default-widgets.php */
            $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

            echo $args['before_widget'];
            if ( $title ) {
                echo $args['before_title'] . $title . $args['after_title'];
            }
            echo '<div class="tagcloud' . $class . '">';
            
            wp_tag_cloud( apply_filters( 'widget_tag_cloud_args', array(
                'taxonomy' => $current_taxonomy
            ) ) );

            echo "</div>\n";
            echo $args['after_widget'];
        }
    }
}