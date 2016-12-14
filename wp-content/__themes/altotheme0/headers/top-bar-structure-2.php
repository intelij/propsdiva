<?php do_action( 'before' ); ?>
<?php if(!isset($lt_opt['topbar_show']) || $lt_opt['topbar_show']){ ?>
    <div id="top-bar" class="top-bar-type-2">
        <div class="row">
            <div class="large-12 columns">
                <div class="left-text left">
                    <div class="inline-block"><?php if (shortcode_exists('share')) : echo do_shortcode('[share tooltip="bottom"]'); endif; ?></div>
                </div>
                <div class="right-text right">
                    <?php lt_header_account(); ?>
                </div>
            </div>
        </div>
    </div>
<?php }?>