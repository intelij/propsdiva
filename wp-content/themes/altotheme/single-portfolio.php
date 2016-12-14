<?php
/**
 * The Template for displaying single portfolio project.
 *
 */

get_header();
lt_get_breadcrumb();
?>

<div class="row">
    <div class="content large-12 columns margin-bottom-50">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <div class="portfolio-single-item">
                    <?php the_content(); ?>
                </div>
            <?php endwhile;?>
        <?php else: ?>
            <h3><?php esc_html_e('No pages were found!', 'altotheme') ?></h3>
        <?php endif; ?>
        <div class="clear"></div>
        <?php
            if(!isset($lt_opt['portfolio_comments']) || $lt_opt['portfolio_comments']) {
                    comments_template( '', true );
                }
            if(!isset($lt_opt['recent_projects']) || $lt_opt['recent_projects']) {
                echo lt_get_recent_portfolio(8, esc_html__('Recent Works', 'altotheme'), $post->ID);
            }
        ?>
    </div>
</div>
<?php get_footer(); ?>