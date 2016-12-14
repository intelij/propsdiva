<?php
/**
 * The Template for displaying all single posts.
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
<div class="container-wrap page-<?php if($lt_opt['blog_layout']){ echo esc_attr($lt_opt['blog_layout']);} else {echo 'right-sidebar';} ?>">
    
    <?php if($hasSidebar):?>
        <div class="div-toggle-sidebar center"><a class="toggle-sidebar" href="javascript:void(0);"><i class="icon-menu"></i> <?php esc_html_e('Sidebar', 'altotheme');?></a></div>
    <?php endif;?>
        
    <div class="row">
        <div id="content" <?php echo $attr;?> role="main">
            <div class="page-inner">
                <?php 
                while ( have_posts() ) : the_post();
                    include get_template_directory() . '/content-single.php';
                    //get_template_part( 'content', 'single' );

                    if ( comments_open() || '0' != get_comments_number() )
                        comments_template();

                endwhile;
                ?>
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