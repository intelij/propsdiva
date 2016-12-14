<?php

/* Mega menu */

class LT_NavDropdown extends Walker_Nav_Menu
{
    private $_mega = array();
    
    private function getOption($itemID, $field = '') { 
        return get_post_meta($itemID, '_menu_item_lee_'.$field, true);
    }

    function start_lvl(&$output, $depth = 0, $args = array()) {
        if($depth == '0'){$class_names = 'nav-dropdown';}
        else {$class_names = 'nav-column-links';}
        $indent = str_repeat("\t", $depth);
        $output .= '<div class="'.$class_names.'"><div class="div-sub"><ul class="sub-menu">';
    }

    function end_lvl(&$output, $depth = 1, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= $indent.'</ul></div></div>';
    }
    
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0){
        $megamenu_class = $widget_class = $megacolumns = $mega_top = $hr = '';
        $indent = $depth ? str_repeat( "\t", $depth ) : '';
        $megamenu = false;
        
        if($depth == 0){
            $megamenu = $this->getOption($item->ID, 'enable_mega');
            $megamenu_class = ' default-menu root-item';
        }

        if($megamenu){
            $megamenu_class = ' lt-megamenu root-item';
            $megacolumnsfix = $this->getOption($item->ID, 'columns_mega');
            $megacolumns = !$megacolumnsfix ? ' cols-3' : ' cols-' . $megacolumnsfix;
            $full = $this->getOption($item->ID, 'enable_fullwidth');
            $megacolumns .= $full ? ' fullwidth' : '';
            $this->_mega[] = $item->ID;
        }
        
        $image_mega = $bg = '';
        $title_menu = apply_filters( 'the_title', $item->title, $item->ID );
        $position = '';
        $title_disable = false;
        if($this->getOption($item->ID, 'image_mega_enable')){
            $position = $this->getOption($item->ID, 'position_image_mega');
            $image_mega = $this->getOption($item->ID, 'image_mega');
            $title_disable = $this->getOption($item->ID, 'disable_title_image_mega');
            if(($image_mega = $this->getOption($item->ID, 'image_mega')) && $position != 'bg'){
                $image_mega = '<img src="' . esc_attr($image_mega) . '" alt="' . esc_attr($title_menu) . '" />';
            }elseif($position == 'bg'){
                $image_mega = esc_attr($image_mega);
            }
        }
        
        $menu_icon = $this->getOption($item->ID, 'icon_menu');
        $icon = $menu_icon ? '<i class="' . esc_attr($menu_icon) . '"></i>&nbsp;&nbsp;' : '';
        
        $widget_class = '';
        /*$widget = $this->getOption($item->ID, 'widget');
        if($widget && is_active_sidebar($widget)){
            $widget_class = ' has_widget_item';
        }*/
        
        if($depth == 1 && in_array($item->menu_item_parent, $this->_mega)){
            $mega_top = ' megatop';
            $hr = '<hr class="hr-lt-megamenu" />';
        }
        
        $classes = empty($item->classes) ? array() : (array)$item->classes;
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item));
        $class_names = ' class="'. esc_attr($class_names) . $widget_class. $megamenu_class . $megacolumns . $mega_top. '"';
        
        if($position == 'bg'){
            $bg = ' style="background: url(\''.$image_mega.'\') center center no-repeat"';
        }
        
        $output .= $indent . '<li ' . $class_names . $bg . '>';

        $attributes  = !empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title). '"' : '';
        $attributes .= !empty($item->target)     ? ' target="' . esc_attr($item->target)    . '"' : '';
        $attributes .= !empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn)       . '"' : '';
        $attributes .= !empty($item->url)        ? ' href="'   . esc_attr($item->url)       . '"' : '';
        
        $description = ($depth != 0) ? "" : (!empty( $item->description ) ? '<span>'.esc_attr( $item->description ).'</span>' : '');
        
        $prepend = '';
        $prepend .= !empty($item->menu_icon) ? '<span class="' . esc_attr($item->menu_icon) .' lt-menu_icon"></span>' : '';
        
        $item_output = '';
        if($position == 'before'){
            $item_output .= '<a class="lt-hide-for-mobile" '. $attributes .'>'.$image_mega.'</a>';
        }
        
        /*if($widget && is_active_sidebar($widget)) {
            $item_output .= '<div class="lt-megamenu_widget">';
            ob_start();
            dynamic_sidebar( $widget );
            $item_output .= ob_get_clean() . '</div>';
        }else{*/
        $item_output .= isset($args->before) ? $args->before : '';
        if(!$title_disable){
            $item_output .= '<a'. $attributes .'>'.$icon;
            $item_output .= isset($args->link_before) ? $args->link_before . $prepend . $title_menu : '';
            $item_output .= '</a>';
        }
        /*}*/
        
        $item_output .= isset($args->link_after) ? $description.$args->link_after : '';
        $item_output .= !$title_disable ? $hr : '';
        $item_output .= isset($args->after) ? $args->after : '';
        $item_output .= ($position == 'after') ? '<a class="lt-hide-for-mobile" '. $attributes .'>'.$image_mega.'</a>' : '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args, $id );
    }
}