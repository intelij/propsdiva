<?php
function lt_sc_posts($atts, $content = null) {
    extract(shortcode_atts(array(
        "title" => '',
        "align" => '',
        'show_type' => '0',
        "posts" => '8',
        "category" => '',
        'columns_number' => '2',
        'columns_number_small' => '1',
        'columns_number_tablet' => '2',
    ), $atts));
    ob_start();
	
    if ($align == 'center') $align = 'text-center'; ?>

    <?php if($title != ''){?> 
        <div class="row">
            <div class="large-12 columns <?php echo esc_attr($align); ?>">
                <h3 class="section-title"><span><?php echo esc_attr($title); ?></span></h3>
                <div class="bery-hr medium"></div>
            </div>
        </div>
    <?php } ?>
    <?php
    $args = array(
        'post_status' => 'publish',
        'post_type' => 'post',
        'category_name' => $category,
        'posts_per_page' => $posts
    );

    $recentPosts = new WP_Query( $args );

    if ( $recentPosts->have_posts() ) {
        if($show_type == 1) include LEE_FRAMEWORK_PLUGIN_PATH. '/includes/blogs/latestblog_grid.php';
        else include LEE_FRAMEWORK_PLUGIN_PATH . '/includes/blogs/latestblog_carousel.php';
    }
    
    wp_reset_postdata();
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode("recent_post", "lt_sc_posts");

if(!function_exists('lt_limit_words')){
    function lt_limit_words($string, $word_limit) {
        $words = explode(' ', $string, ($word_limit + 1));
        if(count($words) <= $word_limit){
            return $string;
        }
        array_pop($words);
        return implode(' ', $words) . ' ...';
    }
}