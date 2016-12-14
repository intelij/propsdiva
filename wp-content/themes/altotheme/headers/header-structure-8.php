<?php
$header_classes = '';
if ($header_transparent = get_post_meta($wp_query->get_queried_object_id(), '_lee_header_transparent', true)){
    $header_classes = ' header-transparent';
}
?>
<div class="header-wrapper header-type-8<?php echo esc_attr($header_classes); ?>">
    <!-- Top bar -->
    <?php 
        include get_template_directory() . '/headers/top-bar-structure-2.php';
    ?>
    <!-- Masthead -->
    <div class="sticky-wrapper">
        <header id="masthead" class="row site-header">
            <div class="large-12 columns header-container">
                <!-- Mobile Menu -->
                <div class="mobile-menu">
                    <?php lt_mobile_header(); ?>
                </div>
                <div class="row lt-groups">
                    <div class="large-5 columns lt-group-item header-search text-left">
                        <!-- Search -->
                        <?php lt_search('full'); ?>
                    </div>
                    <div class="large-2 columns lt-group-item logo-wrapper text-center">
                        <!-- Logo -->
                        <?php lt_logo(); ?>
                    </div>
                    <div class="large-4 columns lt-group-item cart-mini-icon header-utilities text-right">
                        <?php lt_mini_cart('full') ?>
                    </div>
                </div>
            </div>
            <div class="large-12 columns">
                <!-- Main navigation - Full width style -->
                <div class="wide-nav lt-bg-dark lt-mgr-y-30">
                    <?php lt_get_main_menu('2'); ?>
                </div>
            </div>
        </header>
    </div>
</div>



