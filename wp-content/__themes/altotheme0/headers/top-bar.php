<?php do_action( 'before' ); ?>
<?php if(!isset($lt_opt['topbar_show']) || $lt_opt['topbar_show']){ ?>
    <div id="top-bar" class="top-bar-type-1">
        <div class="row">
            <div class="large-12 columns">
                <div class="left-text left">
                    <?php lt_header_account(); ?>
                </div>
                <div class="right-text right">
                    <div class="inline-block"><?php lt_mini_cart('full'); ?></div>

                    <div class="inline-block"><?php if (shortcode_exists('share')) : echo do_shortcode('[share tooltip="none"]'); endif; ?></div>

                </div>
            </div>
        </div>
    </div>
<?php }?>