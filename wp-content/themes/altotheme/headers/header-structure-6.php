<?php
$header_classes = '';
if ($header_transparent = get_post_meta($wp_query->get_queried_object_id(), '_lee_header_transparent', true)){
    $header_classes = ' header-transparent';
}

if (get_post_meta($wp_query->get_queried_object_id(), '_lee_main_menu_transparent', true)){
    $menu_transparent = ' lt-menu-transparent';
    $header_classes .= ' lt-has-menu-transparent';
}else{
    $menu_transparent = '';
    if(isset($lt_opt['main_menu_transparent']) && $lt_opt['main_menu_transparent']){
        $menu_transparent = ' lt-menu-transparent';
        $header_classes .= ' lt-has-menu-transparent';
    }
}
?>
<div class="header-wrapper header-type-6<?php echo esc_attr($header_classes); ?>">
    <!-- Top bar -->
    <?php 
        include get_template_directory() . '/headers/top-bar-structure-3.php';
    ?>
    <!-- Masthead -->
    <div class="sticky-wrapper">
        <header id="masthead" class="row site-header">
            <div class="large-12 columns header-container">
                <!-- Mobile Menu -->
                <div class="mobile-menu">
                    <?php lt_mobile_header(); ?>
                </div>
                <!-- Logo -->
                <div class="logo-wrapper">
                    <?php lt_logo(); ?>
                </div>
                
                <!-- Group icons -->
                <div class="header-utilities">
                    <!-- Search -->
                    <div class="header-search">
                        <?php lt_search('full'); ?>
                    </div>
                    <?php lt_mini_cart('full') ?>
                </div>
            </div>
            <div class="large-12 columns">
                <!-- Main navigation - Full width style -->
                <div class="wide-nav lt-bg-dark <?php echo $menu_transparent;?>">
                    <?php lt_get_main_menu('2'); ?>
                    
                    <div class="lt-share-column">
                        <div class="inline-block"><?php if (shortcode_exists('share')) : echo do_shortcode('[share tooltip="none"]'); endif; ?></div>
                    </div>
                </div>
                
            </div>
        </header>
    </div>
</div>



