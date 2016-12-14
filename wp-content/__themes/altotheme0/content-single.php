<?php
/**
 * @package leetheme
 */
//global $lt_opt;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if ( has_post_thumbnail() ) { ?>
        <div class="entry-image">
            <?php if(isset($lt_opt['blog_parallax'])) { ?><div class="parallax_img" style="overflow:hidden"><div class="parallax_img_inner" data-velocity="0.15"><?php } ?>
            <?php the_post_thumbnail('large'); ?>
            <div class="image-overlay"></div>
            <?php if(isset($lt_opt['blog_parallax'])) { ?></div></div><?php } ?>
        </div>
    <?php } ?>
    <header class="entry-header text-center">
        <h1 class="entry-title"><?php the_title(); ?></h1>
        <div class="entry-meta">
            <?php lt_posted_on(); ?>
        </div>
    </header>

    <div class="entry-content">
        <?php the_content(); ?>
        <?php
        wp_link_pages( array(
            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'altotheme' ),
            'after'  => '</div>',
        ) );
        ?>
    </div>
    
    <?php 
        echo '<div class="blog-share text-center">';
        if (shortcode_exists('share')) : echo do_shortcode('[share]'); endif;
        echo '</div>';
    ?>

   <footer class="entry-meta">
        <?php
            $category_list = get_the_category_list( esc_html__( ', ', 'altotheme' ) );
            $tag_list = get_the_tag_list( '', esc_html__( ', ', 'altotheme' ) );
            $allowed_html = array(
                'a' => array('href' => array(), 'rel' => array(), 'title' => array())
            );

            if ( '' != $tag_list ) {
                $meta_text = esc_html__( 'Posted in %1$s and tagged %2$s.', 'altotheme' );
            } else {
                $meta_text = wp_kses(__( 'Posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'altotheme' ), $allowed_html);
            }
            
            printf(
                $meta_text,
                $category_list,
                $tag_list,
                get_permalink(),
                the_title_attribute( 'echo=0' )
            );
        ?>
    </footer>
		
</article>
