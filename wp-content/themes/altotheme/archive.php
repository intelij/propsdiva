<?php
/**
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package leetheme
 */

if(!isset($lt_opt['blog_layout'])){
    $lt_opt['blog_layout'] = '';
}

$hasSidebar = true;
$left = true;
switch ($lt_opt['blog_layout']):
    case 'left-sidebar':
        $attr = 'class="large-9 right columns"';
        break;
    case 'right-sidebar':
        $left = false;
        $attr = 'class="large-9 left columns"';
        break;
    case 'no-sidebar':
        $hasSidebar = false;
        $left = false;
        $attr = 'class="large-10 columns large-offset-1"';
        break;
    default:
        $left = false;
        $attr = 'class="large-9 left columns"';
        break;
endswitch;

get_header();
lt_get_breadcrumb();
?>

<div class="container-wrap page-<?php if($lt_opt['blog_layout']){ echo esc_attr($lt_opt['blog_layout']);} else {echo esc_attr('right-sidebar');} ?>">
    
    <?php if($hasSidebar):?>
        <div class="div-toggle-sidebar center"><a class="toggle-sidebar" href="javascript:void(0);"><i class="icon-menu"></i> <?php esc_html_e('Sidebar', 'altotheme');?></a></div>
    <?php endif;?>
    
    <div class="row">
        <div id="content" <?php echo $attr;?> role="main">
        <?php if ( have_posts() ) : ?>
            <header class="page-header">
                <h1 class="page-title">
                    <?php
                    if ( is_category() ) :
                        printf( esc_html__( 'Category Archives: %s', 'altotheme' ), '<span>' . single_cat_title( '', false ) . '</span>' );

                    elseif ( is_tag() ) :
                        printf( esc_html__( 'Tag Archives: %s', 'altotheme' ), '<span>' . single_tag_title( '', false ) . '</span>' );

                    elseif ( is_author() ) : the_post();
                        printf( esc_html__( 'Author Archives: %s', 'altotheme' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
                        rewind_posts();

                    elseif ( is_day() ) :
                        printf( esc_html__( 'Daily Archives: %s', 'altotheme' ), '<span>' . get_the_date() . '</span>' );

                    elseif ( is_month() ) :
                        printf( esc_html__( 'Monthly Archives: %s', 'altotheme' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

                    elseif ( is_year() ) :
                        printf( esc_html__( 'Yearly Archives: %s', 'altotheme' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

                    elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
                        esc_html_e( 'Asides', 'altotheme' );

                    elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
                        esc_html_e( 'Images', 'altotheme');

                    elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
                        esc_html_e( 'Videos', 'altotheme' );

                    elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
                        esc_html_e( 'Quotes', 'altotheme' );

                    elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
                        esc_html_e( 'Links', 'altotheme' );

                    else :
                        esc_html_e( '', 'altotheme' );

                    endif;
                    ?>
                </h1>
                <?php
                if ( is_category() ) :
                    $category_description = category_description();
                    if ( ! empty( $category_description ) ) :
                        echo apply_filters( 'category_archive_meta', '<div class="taxonomy-description">' . $category_description . '</div>' );
                    endif;

                elseif ( is_tag() ) :
                    $tag_description = tag_description();
                    if ( ! empty( $tag_description ) ) :
                        echo apply_filters( 'tag_archive_meta', '<div class="taxonomy-description">' . $tag_description . '</div>' );
                    endif;

                endif;
                ?>
            </header>

            <div class="page-inner">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'content', get_post_format() ); ?>
                <?php endwhile; ?>

                <?php else : ?>
                    <?php get_template_part( 'no-results', 'archive' ); ?>
                <?php endif; ?>

                <div class="large-12 columns navigation-container">
                    <?php lt_content_nav( 'nav-below' ); ?>
                </div>
            </div>

        </div>

        <?php if($lt_opt['blog_layout'] == 'left-sidebar' || $lt_opt['blog_layout'] == 'right-sidebar'):?>
            <div class="large-3 columns <?php echo ($left) ? 'left' : 'right';?> col-sidebar">
                <?php get_sidebar();?>
            </div>
        <?php endif;?>

    </div>
</div>

<?php get_footer(); ?>