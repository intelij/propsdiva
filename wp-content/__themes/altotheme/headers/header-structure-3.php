<?php
$header_classes = '';
if ($header_transparent = get_post_meta($wp_query->get_queried_object_id(), '_lee_header_transparent', true)){
    $header_classes = ' header-transparent';
}
?>

<div class="header-wrapper header-type-3<?php echo esc_attr($header_classes);?>">
    <!-- Top bar -->
    <?php 
        include get_template_directory() . '/headers/top-bar.php';
    ?>
    <!-- Masthead -->
    <div class="sticky-wrapper">
        <header id="masthead" class="site-header">
            <div class="row header-container">
                <div class="large-12 columns">
                    <div class="mobile-menu">
                        <?php lt_mobile_header(); ?>
                    </div><!-- end mobile menu -->
                    <!-- Logo -->
                    <div class="logo-wrapper text-center">
                        <?php lt_logo(); ?>
                    </div>
                </div>
                <div class="row">
                     <!-- Main navigation - Full width style -->
                    <div class="large-12 columns">
                        <div class="wide-nav light-header text-center">
                            <?php lt_get_main_menu(); ?>
                            <?php lt_search('icon'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    </div>
</div>