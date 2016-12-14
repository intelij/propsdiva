<div class="cart_sidebar">
    <a href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" class="cart-link">
        <span class="shopping-cart icon flaticon-cart"></span>
        <span class="products-number primary-color"><strong><?php echo $woocommerce->cart->cart_contents_count;?></strong></span>
    </a>
    <div class="nav-dropdown hide-for-small"></div>
</div>