<div class="widget_shopping_cart_content cart_sidebar">
    <?php if (sizeof($woocommerce->cart->cart_contents)>0):?>
        <div class="cart_list">
            <?php foreach ($woocommerce->cart->cart_contents as $cart_item_key => $cart_item) :
                $_product = $cart_item['data'];
                if ($_product->exists() && $cart_item['quantity']>0) :
                    $price = (int) $cart_item['quantity'] * woocommerce_price($_product->get_price());
                    ?>  
                    <div class="row mini-cart-item collapse" id="item-<?php echo $_product->id;?>">
                        <div class="small-3 large-3 columns">
                            <?php  echo '<a class="cart_list_product_img" href="'.get_permalink($cart_item['product_id']).'">' . str_replace( array( 'http:', 'https:' ), '', $_product->get_image() ).'</a>'; ?>
                        </div>
                        <div class="small-7 large-7 columns">
                            <div class="mini-cart-info">
                                <?php
                                    $product_title = $_product->get_title();
                                    echo '<a class="cart_list_product_title" href="'.get_permalink($cart_item['product_id']).'">' . apply_filters('woocommerce_cart_widget_product_title', $product_title, $_product) . '</a>';
                                    echo '<div class="cart_list_product_quantity">'.$cart_item['quantity'].' x '.woocommerce_price($_product->get_price()).'</div>';
                                ?>
                            </div>
                        </div>
                        <div class="small-2 large-2 columns text-right">
                            <?php echo apply_filters(
                                'woocommerce_cart_item_remove_link',
                                sprintf(
                                    '<a href="javascript:void(0);" data-key="%s" data-id="%s" class="remove item-in-cart" title="%s"><i class="pe-7s-close-circle"></i></a>',
                                    $cart_item_key,
                                    $_product->id,
                                    esc_html__('Remove this item', 'altotheme')
                                ),
                                $cart_item_key
                            ); ?>
                        </div>
                    </div>
                <?php endif; ?>                                     
            <?php endforeach; ?>
        </div>
        <div class="minicart_total_checkout">
            <span><?php esc_html_e('Subtotal', 'altotheme'); ?></span>
            <span class="total-price right"><?php echo $woocommerce->cart->get_cart_total(); ?></span>
        </div>
        <div class="btn-mini-cart inline-lists text-center">
	    <div class="row collapse">
		<div class="small-6 large-6 columns">
		    <a href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" class="button btn-viewcart"><?php esc_html_e('View Cart', 'altotheme'); ?></a>
		</div>
		<?php if ( sizeof( $woocommerce->cart->cart_contents) > 0 ):?>
		    <div class="small-6 large-6 columns">
			<a href="<?php echo $woocommerce->cart->get_checkout_url() ?>" class="button btn-checkout" title="<?php esc_html_e( 'Checkout', 'altotheme' ) ?>"><?php esc_html_e( 'Checkout', 'altotheme'); ?></a>
		    </div>
		<?php endif; ?>
	    </div>
        </div>
    <?php                                        
        else: echo $empty;
        endif;
    ?>
</div>