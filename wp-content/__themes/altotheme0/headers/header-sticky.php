<?php $cartType = !in_array($hstructure, array('2', '4', '7')) ? 'full' : 'simple';?>
<div class="fixed-header-area hide-for-small hide-for-medium">
    <div class="fixed-header">
        <div class="row">
            <div class="large-12 columns header-container"> 
                <!-- Logo -->
                <div class="logo-wrapper">
                    <?php lt_logo(); ?>
                </div>
                <!-- Main navigation - Full width style -->
                <div class="wide-nav">
                    <?php lt_get_main_menu('2'); ?>
                </div>
                <div class="header-utilities">
                    <?php lt_mini_cart($cartType); ?>
                </div>
            </div>
        </div>
    </div>
</div>