<div class="setting-switcher no-bullet inline-block">
    <div class="setting-dropdown">
        <a class="icon pe7-icon pe-7s-config" href="javascript:void(0);" onclick="return false;"></a>
        <div class="nav-dropdown">
            <div class="language-switcher">
                <div class="label-title"><?php esc_html_e('Language', 'altotheme'); ?></div>
                <?php do_action('lt_language_switcher'); ?>
            </div>
            <div class="account-link">
                <div class="label-title"></div>
                <ul class="no-bullet">
                    <?php
                    if (is_user_logged_in()):
                        echo '<li class="menu-item"><a href="'.esc_url(home_url('/')).'my-account/" title="'.esc_html__( 'My Account', 'altotheme' ).'"><span class="pe-7s-user"></span>'.esc_html__('My Account','altotheme').'</a></li>';
                        echo '<li class="menu-item"><a class="nav-top-link" href="'.wp_logout_url().'" title="'.esc_html__( 'Log Out', 'altotheme' ).'"><span class="pe-7s-unlock"></span>'.esc_html__('Log Out','altotheme').'</a></li>';
                    elseif (!is_user_logged_in()):
                        echo '<li class="menu-item color"><a href="'.get_permalink( get_option('woocommerce_myaccount_page_id') ).'" title=""><span class="pe-7s-lock"></span>'.esc_html__('Sign in / Register','altotheme').'</a></li>';
                    endif;
                    ?>
                    <li><?php echo lt_tini_wishlist(); ?></li>
                    <li><a href="<?php echo ($woocommerce) ? esc_url( $woocommerce->cart->get_cart_url() ) : '#'; ?>" title="<?php esc_html_e( 'My Cart', 'altotheme' ); ?>"><span class="pe-7s-shopbag"></span><?php esc_html_e('My Cart','altotheme'); ?></a></li>
                    <li><a href="<?php echo ($woocommerce) ? esc_url( $woocommerce->cart->get_checkout_url() ) : '#';?> " title="<?php esc_html_e( 'Checkout', 'altotheme' ); ?>"><span class="pe-7s-check"></span><?php esc_html_e('Checkout','altotheme'); ?></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>