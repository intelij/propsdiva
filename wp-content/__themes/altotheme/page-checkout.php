<?php
/*
Template name: Page Checkout
*/
get_header();
//lt_get_breadcrumb(); 
?>

<div class="container-wrap page-checkout">
    <div class="order-steps">
        <div class="row">
            <div class="large-12 columns">
              <?php  if(function_exists('is_wc_endpoint_url')){ ?>
                            <?php if (!is_wc_endpoint_url('order-received')){ ?>
                                <div class="checkout-breadcrumb">
                                    <div class="title-cart">
                                        <h1>01</h1>
                                        <a href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>">
                                            <h4><?php esc_html_e('Shopping Cart', 'altotheme'); ?></h4>
                                            <p><?php esc_html_e('Manage your items list.','altotheme'); ?></p>
                                        </a>
                                        <span class="icon-angle-right"></span>
                                    </div>
                                    
                                    <div class="title-checkout">
                                        <h1>02</h1>
                                        <a href="<?php echo esc_url( $woocommerce->cart->get_checkout_url()); ?>">
                                            <h4><?php esc_html_e('Checkout details', 'altotheme'); ?></h4>
                                            <p><?php esc_html_e('Checkout your items list','altotheme'); ?></p>
                                        </a>
                                        <span class="icon-angle-right"></span>
                                    </div>
                                    <div class="title-thankyou">
                                        <h1>03</h1>
                                        <h4><?php esc_html_e('Order Complete', 'altotheme'); ?></h4>
                                        <p><?php esc_html_e('Review and submit your order', 'altotheme'); ?></p>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } else { ?> 
                            <div class="checkout-breadcrumb">
                                <div class="title-cart">
                                    <span>1</span>
                                    <p><?php esc_html_e('Shopping Cart', 'altotheme'); ?></p>
                                </div>
                                <div class="title-checkout">
                                    <span>2</span>
                                    <p><?php esc_html_e('Checkout details', 'altotheme'); ?></p>
                                </div>
                                <div class="title-thankyou">
                                    <span>3</span>
                                    <p><?php esc_html_e('Order Complete', 'altotheme'); ?></p>
                                </div>
                            </div>
                        <?php } ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div id="content" class="large-12 columns" role="main">
            <?php while ( have_posts() ) : the_post(); ?>

                <?php the_content(); ?>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>