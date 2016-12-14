<?php
require_once ABSPATH . 'wp-admin/includes/nav-menu.php';
 
class LT_Nav_Menu_Item_Custom_Fields {

    static $options = array('item_tpl' => '
            <p class="additional-menu-field-{name} description description-{type_show}">
                <label for="edit-menu-item-{name}-{id}">
                    {label}<br />
                    <input
                        type="{input_type}"
                        id="edit-menu-item-{name}-{id}"
                        class="widefat code edit-menu-item-{name}"
                        name="menu-item-{name}[{id}]"
                        value="{value}" />
                </label>
            </p>
        ',
        'checkbox' => '
            <p class="additional-menu-field-{name} description description-{type_show}">
                <label for="edit-menu-item-{name}-{id}">
                    <br /><input
                        type="checkbox"
                        id="edit-menu-item-{name}-{id}"
                        class="widefat code edit-menu-item-{name}"
                        name="menu-item-{name}[{id}]"
                        data-id="{id}"
                        value="1"{checked} />{label}
                </label>
            </p>
        '
    );
 
    static function setup() {
        if ( !is_admin() )
            return;
 
        $new_fields = apply_filters( 'lt_nav_menu_item_fields', array() );
        if ( empty($new_fields) )
            return;

        self::$options['fields'] = self::get_fields_schema( $new_fields );
        function lt_walker_nav_menu_edit(){
            return 'lt_Walker_Nav_Menu_Edit';
        }
        add_filter( 'wp_edit_nav_menu_walker', 'lt_walker_nav_menu_edit');

        add_action( 'save_post', array( __CLASS__, '_save_post' ), 10, 2 );
    }
 
    static function get_fields_schema( $new_fields ) {
        $schema = array();
        foreach( $new_fields as $name => $field) {
            if (empty($field['name'])) {
                $field['name'] = $name;
            }
            $schema[] = $field;
        }
        return $schema;
    }
 
    static function get_menu_item_postmeta_key($name) {
        return '_menu_item_lee_' . $name;
    }
 
    /**
     * Inject the 
     * @hook {action} save_post
     */
    static function get_field( $item, $depth, $args ) {
        $new_fields = '';
        $hidden = true;
        foreach( self::$options['fields'] as $field ) {
            $field['value'] = get_post_meta($item->ID, self::get_menu_item_postmeta_key($field['name']), true);
            $field['id'] = $item->ID;
            if($field['name'] == 'image_mega_enable' && $field['value'] == 1){
                $hidden = false;
            }

            switch ($field['input_type']) {
                case 'select-widget':
                    $new_fields .= self::getWidgets($field);
                    break;

                case 'select':
                    $new_fields .= self::getSelect($field);
                    break;
                
                case 'select_position':
                    $new_fields .= self::getSelectPosition($field, $hidden);
                    break;
                
                case 'image':
                    $new_fields .= self::getMedia($field, $hidden);
                    break;
                
                case 'checkbox':
                    $field['checked'] = '';
                    if($field['value'] == 1){
                        $field['checked'] = ' checked';
                    }
                    $default = self::$options['checkbox'];
                    foreach ($field as $key => $value) {
                        $default = str_replace('{' . $key . '}', $value, $default);
                    }
                    $new_fields .= $default;

                    break;
                
                case 'icons':
                    $new_fields .= self::getIcons($field);
                    break;

                default:
                    $default = self::$options['item_tpl'];
                    foreach ($field as $key => $value) {
                        $default = str_replace('{' . $key . '}', $value, $default);
                    }
                    $new_fields .= $default;
                    break;
            }
        }
        return $new_fields;
    }
    
    static function getIcons($field){
        $field['icon'] = '<span id="ico-edit-menu-item-' . $field['name'] . '-' . $field['id'] . '"></span>';
        if(trim($field['value']) != ''){
            $field['icon'] = '<span id="ico-edit-menu-item-' . $field['name'] . '-' . $field['id'] . '"><i class="' . $field['value'] . '"></i><a href="javascript:void(0);" class="lt-remove-icon" data-id="edit-menu-item-' . $field['name'] . '-' . $field['id'] . '"><i class="fa fa-remove"></i></a></span>';
        }
        
        return 
        '<p class="additional-menu-field-' . $field['name'] . ' description description-'.$field['type_show'].'">' .
            '<label for="edit-menu-item-' . $field['name'] . '-' . $field['id'] . '">' .
                '<a class="lt-chosen-icon" data-fill="edit-menu-item-' . $field['name'] . '-' . $field['id'] . '">' .
                    $field['label'] .
                '</a>' . $field['icon'] .
                '<input
                    type="hidden"
                    id="edit-menu-item-' . $field['name'] . '-' . $field['id'] . '"
                    class="widefat code edit-menu-item-' . $field['name'] . '"
                    name="menu-item-' . $field['name'] . '[' . $field['id'] . ']"
                    value="' . $field['value'] . '" />' .
            '</label>' .
        '</p>';
    }
    
    static function getSelect($field){
        $select = '<p class="additional-menu-field-'.$field['name'] . ' description description-'.$field['type_show'].' select-field-'.$field['id'].'">
            <label for="edit-menu-item-'.$field['name'].'-'.$field['id'].'">
                '.$field['label'].'<br />
                <select id="edit-menu-item-'.$field['name'].'-'.$field['id'].'" class="widefat code edit-menu-item-'.$field['name'].'" name="menu-item-'.$field['name'].'['.$field['id'].']">';
                if(!isset($field['default']) || $field['default'] == true) $select .= '<option value="0">'.$field['label'].'</option>';
        if(!empty($field['values']) && is_array($field['values'])){
            foreach($field['values'] as $k => $v){
                $select .= '<option value="'.esc_attr($k).'" '.selected( $field['value'] , $k, false ).'>'.esc_html($v).'</option>';
            }
        }
        $select .= '</select></lable></p>';
        return $select;
    }
    
    static function getSelectPosition($field, $hidden = false){
        $hidden = ($hidden) ? 'hidden-tag ' : '';
        $select = '<p class="' . $hidden . 'additional-menu-field-'.$field['name'] . ' description description-'.$field['type_show'].' select-field-'.$field['id'].'">
            <label for="edit-menu-item-'.$field['name'].'-'.$field['id'].'">
                '.$field['label'].'<br />
                <select id="edit-menu-item-'.$field['name'].'-'.$field['id'].'" class="widefat code edit-menu-item-'.$field['name'].'" name="menu-item-'.$field['name'].'['.$field['id'].']">';
                if(!isset($field['default']) || $field['default'] == true) $select .= '<option value="0">'.$field['label'].'</option>';
        if(!empty($field['values']) && is_array($field['values'])){
            foreach($field['values'] as $k => $v){
                $select .= '<option value="'.esc_attr($k).'" '.selected( $field['value'] , $k, false ).'>'.esc_html($v).'</option>';
            }
        }
        $select .= '</select></lable></p>';
        return $select;
    }

    static function getWidgets($field){
        global $wp_registered_sidebars;
        $select = '<p class="additional-menu-field-'.$field['name'].' description description-'.$field['type_show'].'">
            <label for="edit-menu-item-'.$field['name'].'-'.$field['id'].'">
                '.$field['label'].'<br />
                <select id="edit-menu-item-'.$field['name'].'-'.$field['id'].'" class="widefat code edit-menu-item-'.$field['name'].'" name="menu-item-'.$field['name'].'['.$field['id'].']">
                    <option value="0">Select Widget Area</option>';
        if( ! empty( $wp_registered_sidebars ) && is_array( $wp_registered_sidebars ) ){
            foreach( $wp_registered_sidebars as $sidebar ){
                $select .= '<option value="'.esc_attr($sidebar['id']).'" '.selected( $field['value'] , $sidebar['id'], false ).'>'.esc_html($sidebar['name']).'</option>';
            }
        }
        $select .= '</select></lable></p>';
        return $select;
    }
    
    static function getMedia($field, $hidden = false){
        $img = $field['value'] ? '<img src="' . $field['value'] . '" />' : '';
        $hidden = ($hidden) ? 'hidden-tag ' : '';
        $media = '<p class="' . $hidden . 'additional-menu-field-' . $field['name'] . ' description description-'.$field['type_show'] . ' menu-field-media-' . $field['id'] . '">' . $field['label'] . '
                <input type="hidden" id="edit-menu-item-' . $field['name'] . '-'.$field['id'].'" name="menu-item-' . $field['name'] . '[' . $field['id'] . ']" value="' . $field['value'] . '" />
                <a href="javascript:void(0);" class="button lt-media-upload-button media_upload_button" data-id="edit-menu-item-' . $field['name'] . '-'.$field['id'] . '">' . esc_html__('Upload', 'altotheme') . '</a>
                <a href="javascript:void(0);" class="button lt-media-remove-button media_remove_button" data-id="edit-menu-item-' . $field['name'] . '-' . $field['id'] . '">' . esc_html__('Remove', 'altotheme') . '</a>
                <span class="imgmega edit-menu-item-' . $field['name'] . '-' . $field['id'] . '">' . $img . '</span>
            </p>';
        return $media;
    }

    /**
     * Save the newly submitted fields
     * @hook {action} save_post
     */
    static function _save_post($post_id, $post) {
        if ( $post->post_type !== 'nav_menu_item' ) {
            return $post_id; // prevent weird things from happening
        }
 
        foreach( self::$options['fields'] as $field_schema ) {
            $form_field_name = 'menu-item-' . $field_schema['name'];

            // @todo FALSE should always be used as the default $value, otherwise we wouldn't be able to clear checkboxes
            if ($field_schema['input_type'] == 'checkbox') {
                if(!isset($_POST[$form_field_name][$post_id])) $_POST[$form_field_name][$post_id] = false;
            }

            if (isset($_POST[$form_field_name][$post_id])) {
                $key = self::get_menu_item_postmeta_key($field_schema['name']);
                $value = stripslashes($_POST[$form_field_name][$post_id]);
                update_post_meta($post_id, $key, $value);
            }
        }
    }
}

class lt_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit {
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $item_output = '';
        parent::start_el($item_output, $item, $depth, $args, $id);

        if($new_fields = lt_Nav_Menu_Item_Custom_Fields::get_field($item, $depth, $args))
            $item_output = preg_replace('/(?=<div[^>]+class="[^"]*submitbox)/', $new_fields, $item_output);
        $output .= $item_output;
    }
}

function lt_megamenu_admin_stylescript() {
    wp_enqueue_style( 'lt_back_end', get_template_directory_uri() .'/includes/lee_mega_menu/lee_mega_menu_backend.css');
    wp_enqueue_script('lt_media_uploader', get_template_directory_uri() .'/includes/lee_mega_menu/lee_mega_menu_js.js' );
}
add_action( 'admin_enqueue_scripts', 'lt_megamenu_admin_stylescript' );

// Config more custom fields 
add_filter( 'lt_nav_menu_item_fields', 'lt_menu_item_additional_fields' );
function lt_menu_item_additional_fields() {
    $fields = array(
        'lee_megamenu' => array(
            'name' => 'enable_mega',
            'label' => esc_html__('Enable megamenu', 'altotheme'),
            'container_class' => 'enable-widget',
            'input_type' => 'checkbox',
            'type_show' => 'thin'
        ),

        'lee_fullwidth' => array(
            'name' => 'enable_fullwidth',
            'label' => esc_html__('Enable Fullwidth', 'altotheme'),
            'container_class' => 'enable-fullwidth',
            'input_type' => 'checkbox',
            'type_show' => 'thin'
        ),
        
        'lee_icon' => array(
            'name' => 'icon_menu',
            'label' => esc_html__('Select icon for menu ', 'altotheme'),
            'container_class' => 'icon-menu',
            'input_type' => 'icons',
            'type_show' => 'wide'
        ),
        
        /*'lee_select_widget' => array(
            'name' => 'widget',
            'label' => esc_html__('Select widget', 'altotheme'),
            'container_class' => 'select-widget',
            'input_type' => 'select-widget',
            'type_show' => 'wide'
        ),*/
        
        'lee_select_width' => array(
            'name' => 'columns_mega',
            'label' => esc_html__('Select number columns in megamenu', 'altotheme'),
            'container_class' => 'select-columns',
            'input_type' => 'select',
            'values' => array(
                '3' => '3 Columns',
                '4' => '4 Columns',
                '5' => '5 Columns',
            ),
            'default' => false,
            'type_show' => 'wide'
        ),
        
        'lee_megamenu_image' => array(
            'name' => 'image_mega_enable',
            'label' => __('Image megamenu', 'altotheme'),
            'container_class' => 'enable-widget',
            'input_type' => 'checkbox',
            'type_show' => 'wide'
        ),
        
        'lee_megamenu_image_btn' => array(
            'name' => 'image_mega',
            'label' => __('', 'altotheme'),
            'container_class' => 'enable-widget',
            'input_type' => 'image',
            'type_show' => 'wide'
        ),
        
        'lee_select_position_image' => array(
            'name' => 'position_image_mega',
            'label' => esc_html__('Image position', 'altotheme'),
            'container_class' => 'select-position',
            'input_type' => 'select_position',
            'values' => array(
                'before' => 'Before title',
                'after' => 'After title',
                'bg' => 'Background menu',
            ),
            'default' => false,
            'type_show' => 'wide'
        ),
        
        'lee_select_disable_title' => array(
            'name' => 'disable_title_image_mega',
            'label' => esc_html__('Show menu title', 'altotheme'),
            'container_class' => 'select-position',
            'input_type' => 'select_position',
            'values' => array(
                '0' => 'Enable',
                '1' => 'Disable',
            ),
            'default' => true,
            'type_show' => 'wide'
        ),
    );
 
    return $fields;
}

add_action('init', array('LT_Nav_Menu_Item_Custom_Fields', 'setup'));