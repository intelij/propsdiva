<?php
/**
 * @package leetheme
 */
get_header(); ?>

<div  class="container-wrap">
    <div class="row">
        <div id="content" class="large-12 left columns" role="main">
            <article id="post-0" class="post error404 not-found text-center">
                <header class="entry-header">
                    <img src="<?php echo get_template_directory_uri().'/css/images/404.jpg'; ?>" />
                    <h1 class="entry-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'altotheme' ); ?></h1>
                </header><!-- .entry-header -->
                <div class="entry-content">
                    <p><?php esc_html_e( 'Sorry, but the page you are looking for is not found. Please, make sure you have typed the current URL.', 'altotheme' ); ?></p>
                    <?php get_search_form(); ?>
                    <a class="button medium" href="<?php echo esc_url(home_url('/'));?>"><?php esc_html_e('GO TO HOME','altotheme');?></a>
                </div>
            </article>
        </div>
    </div>
</div>

<?php get_footer(); ?>