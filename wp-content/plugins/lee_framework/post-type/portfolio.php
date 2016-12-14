<?php

/**
*
* Portfolio
*
*/
add_action('init', 'lt_portfolio_init', 1); 
function lt_portfolio_init(){
    $labels = array(
        'name' => _x('Projects', 'post type general name', 'lee_framework'),
        'singular_name' => _x('Portfolio', 'post type singular name', 'lee_framework'),
        'add_new' => _x('Add New', 'project', 'lee_framework'),
        'add_new_item' => __('Add New Project', 'lee_framework'),
        'edit_item' => __('Edit Project', 'lee_framework'),
        'new_item' => __('New Project', 'lee_framework'),
        'view_item' => __('View Project', 'lee_framework'),
        'search_items' => __('Search Projects', 'lee_framework'),
        'not_found' =>  __('No projects found', 'lee_framework'),
        'not_found_in_trash' => __('No projects found in Trash', 'lee_framework'),
        'parent_item_colon' => '',
        'menu_name' => 'Portfolio'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title','editor','author','thumbnail','excerpt','comments'),
        'rewrite' => array('slug' => 'portfolio')
    );

    register_post_type('portfolio', $args);

    $labels = array(
        'name' => _x( 'Tags', 'taxonomy general name', 'lee_framework' ),
        'singular_name' => _x( 'Tag', 'taxonomy singular name', 'lee_framework' ),
        'search_items' =>  __( 'Search Types', 'lee_framework' ),
        'all_items' => __( 'All Tags', 'lee_framework' ),
        'parent_item' => __( 'Parent Tag', 'lee_framework' ),
        'parent_item_colon' => __( 'Parent Tag:', 'lee_framework' ),
        'edit_item' => __( 'Edit Tags', 'lee_framework' ),
        'update_item' => __( 'Update Tag', 'lee_framework' ),
        'add_new_item' => __( 'Add New Tag', 'lee_framework' ),
        'new_item_name' => __( 'New Tag Name', 'lee_framework' ),
    );

    $labels2 = array(
        'name' => _x( 'Portfolio Categories', 'taxonomy general name', 'lee_framework' ),
        'singular_name' => _x( 'Category', 'taxonomy singular name', 'lee_framework' ),
        'search_items' =>  __( 'Search Types', 'lee_framework' ),
        'all_items' => __( 'All Categories', 'lee_framework' ),
        'parent_item' => __( 'Parent Category', 'lee_framework' ),
        'parent_item_colon' => __( 'Parent Category:', 'lee_framework' ),
        'edit_item' => __( 'Edit Categories', 'lee_framework' ),
        'update_item' => __( 'Update Category', 'lee_framework' ),
        'add_new_item' => __( 'Add New Category', 'lee_framework' ),
        'new_item_name' => __( 'New Category Name', 'lee_framework' ),
    );

    register_taxonomy('portfolio_category', array('portfolio'), array(
        'hierarchical' => true,
        'labels' => $labels2,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'portfolio-category' ),
    ));
}

add_shortcode('portfolio', 'lt_portfolio_shortcode');
function lt_portfolio_shortcode($atts) {
    $a = shortcode_atts( array(
       'title' => 'Recent Works',
       'limit' => 12
    ), $atts );
    
    return lt_get_recent_portfolio($a['limit'], $a['title']);
}

function lt_get_recent_portfolio($limit, $title = 'Recent Works', $not_in = 0) {
    $args = array(
        'post_type' => 'portfolio',
        'order' => 'DESC',
        'orderby' => 'date',
        'posts_per_page' => $limit,
        'post__not_in' => array( $not_in )
    );

    return lt_create_portfolio_recent($args, $title);
}

function lt_create_portfolio_recent($args, $title = false, $width = 540, $height = 340, $crop = true){
    global $wpdb, $lt_opt, $post;
    $box_id = rand(1000,10000);
    $multislides = new WP_Query( $args );
    $sliderHeight = 200;
    $class = '';

    ob_start();
    if ( $multislides->have_posts() ) :
        $title_output = '';
        if ($title) {
            $title_output = 
            '<div class="title-block text-left">' .
                '<h4 class="heading-title"><span>'.$title.'</span></h4>' .
                '<div class="bery-hr medium text-left"></div>' .
            '</div>';
        }   
        echo '<div class="slider-container carousel-area '.$class.'">';
            echo $title_output;
            echo '<div class="items-slide items-slider-portfolio slider-'.$box_id.'">';
                echo '<div class="lt-slider owl-carousel recentPortfolio" data-columns="3" data-columns-small="1" data-columns-tablet="3">';
                $delay = 200;
                while ($multislides->have_posts()) : $multislides->the_post();
                    include LEE_FRAMEWORK_PLUGIN_PATH . '/post-type/portfolio/portfolio-recent.php';
                endwhile; 
                echo '</div><!-- slider -->'; 
            echo '</div><!-- products-slider -->';
        echo '</div><!-- slider-container -->';
    endif;
    wp_reset_query();

    $html = ob_get_contents();
    ob_end_clean();

    return $html;
}

function print_item_cats($id) {
    //Returns Array of Term Names for "categories"
    $term_list = wp_get_post_terms($id, 'portfolio_category');
    $_i = 0;
    if($count = count($term_list)){
        foreach ($term_list as $value) { 
            $_i++;
            echo '<a href="'.get_term_link($value).'">' . $value->name . '</a>';
            if($_i != $count) echo ', ';
        }
    }
}

add_shortcode('portfolio_grid', 'lt_portfolio_grid_shortcode');
function lt_portfolio_grid_shortcode() {
    
    $a = shortcode_atts( array(
        'categories' => '',
        'limit' => -1,
        'show_pagination' => 1
    ), $atts );
    
    return get_lt_portfolio($a['categories'], $a['limit'], $a['show_pagination']);
}

add_action( 'wp_ajax_get_more_portfolio', 'get_more_portfolio' );
add_action( 'wp_ajax_nopriv_get_more_portfolio', 'get_more_portfolio' );
function get_more_portfolio(){
    global $lt_opt;
    
    $page = (isset($_POST['page']) && (int)$_POST['page']) ? (int)$_POST['page'] : 1;
    $limit = (isset($lt_opt['portfolio_count']) && (int)$lt_opt['portfolio_count']) ? (int)$lt_opt['portfolio_count'] : 20;
    $cat = (isset($_POST['category']) && (int)$_POST['category']) ? (int)$_POST['category'] : 0;
    
    $args = array(
        'post_type' => 'portfolio',
        'paged' => $page,	
        'posts_per_page' => $limit,
        'tax_query' => !empty($cat) ? array(array(
            'taxonomy' => 'portfolio_category',
            'field' => 'id',
            'terms' => $cat
        )) : array()
    );
    
    $loop = new WP_Query($args);
    ob_start();
    if ($loop->have_posts()) {
        while ( $loop->have_posts() ){ 
            $loop->the_post();
            include LEE_FRAMEWORK_PLUGIN_PATH . '/post-type/portfolio/portfolio-content.php';
            //get_template_part( 'content', 'portfolio' );
        }
        $output = ob_get_contents();
        ob_end_clean();
        $alert = ($page >= $loop->max_num_pages) ? __('ALL PORTFOLIOS LOADED', 'lee_framework') : __('LOAD MORE', 'lee_framework');
        echo json_encode(array('success' => true, 'result' => $output, 'max' => $loop->max_num_pages, 'alert' => $alert));
    } else {
        echo json_encode(array('success' => false, 'alert' => 'No portfolio were found!'));
    }
    wp_reset_postdata();
    die();
}

function string_limit($string, $word_limit = 10)
{
    $words = explode(' ', $string, ($word_limit + 1));
    if(count($words) > $word_limit){
        array_pop($words);
        return implode(' ', $words) . ' [...]';
    } else {
        return implode(' ', $words);
    }
}


// **********************************************************************// 
// ! Project links
// **********************************************************************// 

add_shortcode('project_links', 'lt_project_links');
function lt_project_links($atts, $content = null) {
    $next_post = get_next_post();
    $prev_post = get_previous_post(); ?>
    <div class="portfolio-navigation">
        <?php if(!empty($prev_post)) : ?>
            <div class="pull-left prev-portfolio">
                <a href="<?php echo get_permalink($prev_post->ID); ?>" class="btn border-grey btn-xmedium portfolio-nav"><?php _e('Prev', 'lee_framework'); ?></a> 
                <div class="hide-info">
                    <?php echo get_the_post_thumbnail( $prev_post->ID, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) ); ?>
                    <span class="price"><?php echo get_the_title($prev_post->ID); ?></span>
                </div>
            </div>
        <?php endif; ?>
        <?php if(!empty($next_post)) : ?>
            <div class="pull-right next-portfolio">
                <a href="<?php echo get_permalink($next_post->ID); ?>" class="btn border-grey btn-xmedium portfolio-nav"><?php _e('Next', 'lee_framework'); ?></a>
                <div class="hide-info">
                    <span class="price"><?php echo get_the_title($next_post->ID); ?></span>
                    <?php echo get_the_post_thumbnail( $next_post->ID, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) ); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php }