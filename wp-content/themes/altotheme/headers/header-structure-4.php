<div class="header-wrapper header-type-4">
    <a class="header-fold-btn">
        <span class="btn_menu_line"></span>
        <span class="btn_menu_line"></span>
        <span class="btn_menu_line"></span>
    </a>
    <!-- Top bar -->
    <?php 
    include get_template_directory() . '/headers/top-bar.php';
    //get_template_part('headers/top-bar');
    ?>
    <div class="header-utilities">
        <?php //lt_search('icon'); ?>
        <?php lt_mini_cart('simple') ?>
        <?php lt_header_setting_switcher(); ?>
    </div>
    <!-- Masthead -->
    <div class="sticky-wrapper">
        <header id="masthead" class="site-header">
            <div class="row header-container"> 
                <div class="mobile-menu">
                    <?php lt_mobile_header(); ?>
                </div><!-- end mobile menu -->
                <!-- Logo -->
                <div class="large-12 columns logo-wrapper">
                    <?php lt_logo(); ?>
                </div>
            </div>
        </header>
    </div>

    <!-- Main navigation - Full width style -->
    <div class="wide-nav light-header nav-left">
        <div class="row">
            <div class="large-12 columns">
                <?php lt_get_main_menu(); ?>
            </div><!-- .large-12 -->
        </div><!-- .row -->
    </div><!-- .wide-nav -->
    <div class="share-icon">
        <?php if (shortcode_exists('share')) : echo do_shortcode('[share]'); endif; ?>
    </div>
</div>