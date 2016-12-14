<?php

get_header(); ?>

<div  class="container-wrap page-left-sidebar page-featured-item">
    <div class="row">
        <div id="content" class="large-3 columns left" role="main">
            <header class="entry-header">
                <div class="featured_item_cats">
                    <?php echo get_the_term_list( get_the_ID(), 'featured_item_category', '', ', ', '' ); ?> 
                </div>
                <h1 class="entry-title"><?php the_title(); ?></h1>
                <div class="bery-hr small"></div>
            </header>

            <div class="entry-summary">
                <?php the_excerpt();?>

                <?php if (shortcode_exists('share')) : echo do_shortcode('[share]'); endif; ?>

                <?php if(get_the_term_list( get_the_ID(), 'featured_item_tag')) { ?> 
                <div class="item-tags">
                    <span>Tags:</span><?php echo strip_tags (get_the_term_list( get_the_ID(), 'featured_item_tag', '', ' / ', '' )); ?>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="large-9 right columns">
            <div class="page-inner">
                <?php while ( have_posts() ) : the_post(); ?>
                <?php endwhile;?>
            </div>
        </div>
    </div>
</div>

<?php 
global $lt_opt;
$cat = get_the_terms( get_the_ID(), 'featured_item_category', '', ', ', '' );
if($lt_opt['featured_items_related'] == 'style1') {
    echo do_shortcode('[featured_items_slider style="1" height="'.$lt_opt['featured_items_related_height'].'" cat="'.current($cat)->slug.'"]');
} elseif($lt_opt['featured_items_related'] == 'style2') {
    echo do_shortcode('[featured_items_slider style="2" height="'.$lt_opt['featured_items_related_height'].'" cat="'.current($cat)->slug.'"]');
}

get_footer();