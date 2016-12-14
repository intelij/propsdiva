<?php 
$postId = get_the_ID();
$categories = wp_get_post_terms($postId, 'categories');
$catsClass = '';
if(!is_wp_error( $categories )) {
    foreach($categories as $category) {
        $catsClass .= ' sort-'.$category->slug;
    }
}

$delay += 200;
$lightbox = (!isset($lt_opt['portfolio_lightbox']) || $lt_opt['portfolio_lightbox']) ? true : false;
?>

<div class="wow slider-item portfolio-item <?php echo $catsClass; ?>" data-wow-duration="1s" data-wow-delay="<?php echo $delay;?>ms">
    <div class="portfolio-image">
        <?php if (has_post_thumbnail( $postId ) ): ?>
            <?php $image = lt_get_image(get_post_thumbnail_id($post->ID), 400, 350, true); ?>
            <img src="<?php echo $image; ?>" />	
            <div class="zoom">
                <div class="btn_group">
                <?php if($lightbox): ?>
                    <a href="javascript:void(0);" class="btn portfolio-image-view" data-src="<?php echo lt_get_image(get_post_thumbnail_id($postId)); ?>"><span><?php _e('View large', 'lee_framework'); ?></span></a>
                <?php endif; ?>
                <a href="<?php the_permalink(); ?>" class="btn portfolio-link"><span><?php _e('More details', 'lee_framework'); ?></span></a>
                </div>
                <i class="bg"></i>
            </div>
        <?php endif; ?>
    </div>
</div>