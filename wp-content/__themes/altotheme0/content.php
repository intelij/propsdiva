<?php
/**
 * @package leetheme
 */

global $lt_opt,$page;

if (isset($_GET['list-style'])){
    $lt_opt['blog_type'] = 'blog-list';
}
$allowed_html = array(
	'strong' => array()
);
if(!isset($lt_opt['blog_type']) || $lt_opt['blog_type'] == 'blog-standard'){ ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if ( has_post_thumbnail() ) {?>
        <div class="entry-image">
            <a href="<?php the_permalink();?>">
                <?php if(isset($lt_opt['blog_parallax'])) { ?><div class="parallax_img" style="overflow:hidden"><div class="parallax_img_inner" data-velocity="0.15"><?php } ?>
                <?php the_post_thumbnail('lt-normal-thumb'); ?>
                <div class="image-overlay"></div>
                <?php if(isset($lt_opt['blog_parallax'])) { ?></div></div><?php } ?>
            </a>
        </div>
    <?php } ?>
    <header class="entry-header">
        <div class="row">
            <div class="large-2 columns text-center">
                <a href="<?php the_permalink(); ?>">
                    <div class="post-date-wrapper">
                        <div class="post-date">
                            <span class="post-date-month"><?php echo get_the_time('M', get_the_ID()); ?></span>
                            <span class="post-date-day"><?php echo get_the_time('d', get_the_ID()); ?></span>
                        </div>
                    </div>
                </a>
                <div class="meta-author"><?php esc_html_e('By ', 'altotheme'); ?><?php echo get_the_author();?></div>
            </div>
            <div class="large-10 columns">
                <h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
                <div class="entry-summary">
                    <?php the_excerpt(); ?>
                </div>
            </div>
        </div>
    </header>

    <footer class="entry-meta">
        <?php if ( 'post' == get_post_type() ) :?>
            <?php $categories_list = get_the_category_list( esc_html__( ', ', 'altotheme' ) ); ?>
            <span class="cat-links">
                <?php printf( esc_html__( 'Posted in %1$s', 'altotheme' ), $categories_list ); ?>
            </span>

            <?php
            $tags_list = get_the_tag_list( '', esc_html__( ', ', 'altotheme' ) );
            if ( $tags_list ) :
            ?>
                <span class="sep"> | </span>
                <span class="tags-links">
                    <?php printf( esc_html__( 'Tagged %1$s', 'altotheme' ), $tags_list ); ?>
                </span>
            <?php endif; ?>
        <?php endif;?>

        <?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
            <span class="comments-link right"><?php comments_popup_link( esc_html__( 'Leave a comment', 'altotheme' ), wp_kses( __( '<strong>1</strong> Comment', 'altotheme' ), $allowed_html), wp_kses( __( '<strong>%</strong> Comments', 'altotheme' ), $allowed_html) ); ?></span>
        <?php endif; ?>
    </footer>
</article>
<?php } else if($lt_opt['blog_type'] == 'blog-list') { ?>
<div class="blog-list-style">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="row">
            <?php if ( has_post_thumbnail() ) { ?>
            <div class="large-4 columns">
                <div class="entry-image">
                    <a href="<?php the_permalink();?>">
                        <?php the_post_thumbnail('lt-list-thumb'); ?>
                        <div class="image-overlay"></div>
                    </a>
                </div>
            </div>
            <?php } ?>

            <div class="large-8 columns">
                <div class="entry-content">
                    <h3 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
                    <?php the_excerpt(); ?>
                    <?php if ( 'post' == get_post_type() ) : ?>
                        <div class="entry-meta">
                            <?php lt_posted_on(); ?>
                            <?php if (!post_password_required() && (comments_open() || '0' != get_comments_number())):?>
                                <span class="comments-link right"><?php comments_popup_link( esc_html__( 'Leave a comment', 'altotheme' ), wp_kses( __( '<strong>1</strong> Comment', 'altotheme' ), $allowed_html), wp_kses( __( '<strong>%</strong> Comments', 'altotheme' ), $allowed_html) ); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </article>
</div>
<?php } ?>
