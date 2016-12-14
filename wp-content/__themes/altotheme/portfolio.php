<?php
/**
 * Template Name: Portfolio
 *
 */

if (isset($_GET['portfolio_2columns'])){
    $lt_opt['portfolio_columns'] = '2';
}elseif (isset($_GET['portfolio_3columns'])){
    $lt_opt['portfolio_columns'] = '3';
}elseif (isset($_GET['portfolio_4columns'])){
    $lt_opt['portfolio_columns'] = '4';
};

$cat = (get_query_var('portfolio_category')) ? get_queried_object_id() : 0;
$categories = get_terms('portfolio_category');
$catsCount = count($categories);

get_header();
lt_get_breadcrumb();
?>

<div class="row">
    <div class="content large-12 columns margin-top-20<?php /*margin-bottom-60*/?>">
        <div class="lt-tabs-content">
            <?php if(!$cat):?>
            <ul class="lt-tabs portfolio-tabs">
                <li class="description_tab lt-tab first active">
                    <a href="javascript:void(0);" data-filter="*" class="btn big">
                        <h5><?php esc_html_e('Show All', 'altotheme'); ?></h5>
                        <span class="bery-hr medium"></span>
                    </a>
                </li>
                <?php if($catsCount > 0):
                    echo '<li class="separator">|</li>';
                    foreach($categories as $category) :?>
                        <li class="description_tab lt-tab">
                            <a href="javascript:void(0);" data-filter=".sort-<?php echo $category->slug; ?>" class="btn big">
                                <h5><?php echo $category->name; ?></h5>
                                <span class="bery-hr medium"></span>
                            </a>
                        </li>
                        <li class="separator">|</li>
                    <?php endforeach;?>
                <?php endif;?>
            </ul>
            <?php endif;?>

            <div class="row portfolio collapse portfolio-list" data-columns="<?php echo ((int)$lt_opt['portfolio_columns']) ? $lt_opt['portfolio_columns'] : 3;?>"></div>

            <div class="row">
                <div class="large-12 columns">
                    <div class="text-center load-more loadmore-portfolio" data-category="<?php echo (int)$cat;?>">
                        <span><?php esc_html_e('LOAD MORE','altotheme'); ?></span>
                        <span class="load-more-icon fa fa-angle-double-down"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer();?>