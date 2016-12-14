<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// It is required to be before tabs-list-top/left/bottom/right for tabs/tours
$alignment = $alignment ? ' text-'.$alignment : '';
$el_class = (trim($el_class) != '') ? ' '.$el_class : '';
$output = $this->getTemplateVariable('title');
if(WPBakeryShortCode_VC_Tta_Section::$section_info):
    $output .= '<div class="lt-tabs-content' . esc_attr($el_class) . '">';
        $output .= '<ul class="lt-tabs' . esc_attr($alignment) . '">';
        foreach (WPBakeryShortCode_VC_Tta_Section::$section_info as $k => $v):
            $lt_attr = ($k == 0) ? ' class="lt-tab active first" data-show="1"' : ' class="lt-tab" data-show="0"';
            $output .= '<li' . $lt_attr . '>';
                $output .= '<a href="javascript:void(0);" data-id="#lt-secion-'. esc_attr($v['tab_id']) . '"><h5>' . $v['title'] . '</h5></a><span class="bery-hr small"></span>';
            $output .= '</li><li class="separator">|</li>';
        endforeach;
        $output .= '</ul>';
        
        $output .= '<div class="lt-panels">';
            $output .= $prepareContent; // Content
        $output .= '</div>';
    $output .= '</div>';
endif;

echo $output;
