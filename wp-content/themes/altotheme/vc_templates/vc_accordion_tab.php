<?php
global $lt_collapses_item;
$output = $title = '';

extract(shortcode_atts(array(
    'title' => esc_html__("Section", "altotheme")
), $atts));

$lt_collapses_item[] = array('title' => $title,'content' => wpb_js_remove_wpautop($content));