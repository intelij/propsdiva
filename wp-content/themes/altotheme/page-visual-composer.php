<?php
/*
Template name: Visual Composer Template
*/
get_header();
lt_get_breadcrumb();

/* Display popup window */
if (isset($lt_opt['promo_popup']) && $lt_opt['promo_popup'] == 1){?>
    <div class="popup_link hide"><a class="lt-popup open-click" href="#lt-popup"><?php esc_html_e('Newsletter', 'altotheme'); ?></a></div>
    <?php do_action('after_page_wrapper'); ?>
<?php } ?>
<div class="page-header">
    <?php if( has_excerpt() ) the_excerpt();?>
</div>
<div id="content" role="main">
    <?php while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
    <?php endwhile; ?>
</div>
<?php get_footer(); ?>

