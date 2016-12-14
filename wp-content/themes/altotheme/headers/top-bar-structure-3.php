<?php do_action( 'before' ); ?>
<?php if(!isset($lt_opt['topbar_show']) || $lt_opt['topbar_show']){ ?>
    <div id="top-bar" class="top-bar-type-3">
        <div class="row">
            <div class="large-12 columns">
                <div class="left-text left">
                    <div class="inner-block"><?php if(isset($lt_opt['topbar_left'])) {echo do_shortcode($lt_opt['topbar_left']);} ?></div>
                </div>
                <div class="right-text right">
                    <?php lt_header_account(); ?>
                </div>
            </div>
        </div>
    </div>
<?php }?>