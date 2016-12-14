<div class="heading-account">
    <i class="fa fa-user"></i>
    <?php echo esc_html__( 'Account', 'altotheme' );?>
    <hr />
</div>
<div class="content-account">
    <?php
    if (is_user_logged_in()){
        echo '<a href="'.esc_url(home_url('/')).'my-account/" title="' . esc_html__( 'My Account', 'altotheme' ) . '"><span class="pe-7s-user"></span> ' . esc_html__('My Account','altotheme') . '</a>';
        echo '<a class="nav-top-link" href="'.wp_logout_url().'" title="' . esc_html__( 'Log Out', 'altotheme' ) . '"><span class="pe-7s-unlock"></span> ' . esc_html__( 'Log Out', 'altotheme' ) . '</a>';
    }
    elseif (!is_user_logged_in()) {
        echo '<a class="center" href="'.get_permalink( get_option('woocommerce_myaccount_page_id') ).'" title=""><span class="pe-7s-lock"></span> ' . esc_html__('Sign in / Register','altotheme') . '</a>';
    }
    ?>
</div>