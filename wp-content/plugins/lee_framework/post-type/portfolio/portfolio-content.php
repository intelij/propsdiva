<?php
$postId = get_the_ID();
$categories = wp_get_post_terms($postId, 'portfolio_category');
$catsClass = '';
foreach($categories as $category) {
    $catsClass .= ' sort-'.$category->slug;
}

$columns = isset($lt_opt['portfolio_columns']) ? $lt_opt['portfolio_columns'] : 3;
$lightbox = (!isset($lt_opt['portfolio_lightbox']) || $lt_opt['portfolio_lightbox']) ? true : false;

if(isset($_POST['col']) && (int) $_POST['col'] > 0 && (int) $_POST['col'] <= 12) {
    $columns = (int)$_POST['col'];
}

switch($columns) {
    case 2:
        $span = 'large-6 columns';
    break;
    case 3:
        $span = 'large-4 columns';
    break;
    case 4:
        $span = 'large-3 columns';
    break;
    default:
        $span = 'large-4 columns';
    break;
}
	
$width = 500;
$height = 500;
$crop = true;
?>
<div class="portfolio-item <?php echo $span; ?><?php echo $catsClass;?>">       
    <?php if (has_post_thumbnail( $postId ) ): ?>
        <div class="portfolio-image">
            <?php $imgSrc = lt_get_image(get_post_thumbnail_id($postId), $width, $height, $crop);?>
            <a href="<?php the_permalink(); ?>"><img src="<?php echo $imgSrc; ?>" alt="<?php the_title(); ?>"></a>
            <div class="zoom">
                <div class="btn_group">
                    <?php if($lightbox): ?>
                        <a href="javascript:void(0);" class="btn portfolio-image-view" data-src="<?php echo lt_get_image(get_post_thumbnail_id($postId)); ?>"><span><?php _e('View large', 'lee_framework'); ?></span></a>
                    <?php endif; ?>
                    <a href="<?php the_permalink(); ?>" class="btn portfolio-link"><span><?php _e('More details', 'lee_framework'); ?></span></a>
                </div>
                <i class="bg"></i>
            </div>
        </div>
    <?php endif; ?>
    <div class="portfolio-description text-center">
        <?php if(!isset($lt_opt['project_name']) || $lt_opt['project_name']): ?>
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <?php endif; ?>
        <?php if(!isset($lt_opt['project_byline']) || $lt_opt['project_byline']): ?>
            <span class="portfolio-cat"><?php print_item_cats($postId); ?></span> 
        <?php endif; ?>
    </div>
</div>