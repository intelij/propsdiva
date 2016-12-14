<?php
/**
 * The main template file.
 *
 * @package leetheme
 */

if (isset($_GET['right-sidebar'])){
    $lt_opt['blog_layout'] = 'right-sidebar';
}else if(isset($_GET['no-sidebar'])){
    $lt_opt['blog_layout'] = 'no-sidebar';
}
if(!isset($lt_opt['blog_layout'])) {
    $lt_opt['blog_layout'] = '';
}

$hasSidebar = true;
$left = true;
switch ($lt_opt['blog_layout']):
    case 'right-sidebar':
        $left = false;
        $attr = 'class="large-9 left columns"';
        break;
    case 'no-sidebar':
        $hasSidebar = false;
        $left = false;
        $attr = 'class="large-12 columns"';
        break;
    default:
        $attr = 'class="large-9 right columns"';
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
                <?php if ( have_posts() ) :
                    while ( have_posts() ) : the_post();
                        //get_template_part( 'content', get_post_format() );
                        include get_template_directory() . '/content.php';
                    endwhile;
                else :
                    get_template_part( 'no-results', 'index' );
                endif; ?>

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
