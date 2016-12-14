<?php
/**
 * The template for displaying the footer.
 *
 * @package leetheme
 */
?>
    </div>
    <!-- MAIN FOOTER -->
    <footer id="lt-footer" class="footer-wrapper">
        <?php do_action('lt_footer_layout_style'); ?>
    </footer>
    <!-- END MAIN FOOTER -->
</div>

<a href="javascript:void(0);" id="top-link" class="wow bounceIn"><span class="icon-angle-up"></span></a>
<div class="scroll-to-bullets"></div>
<?php wp_footer(); ?>

<div class="static-position">
    <div class="black-window hidden-tag"></div>
    <div class="white-window hidden-tag"></div>
    <div class="warpper-mobile-search hidden-tag"><!-- for mobile -->
        <?php get_search_form();?>
    </div>
    <div id="heading-menu-mobile" class="hidden-tag"><i class="fa fa-bars"></i><?php esc_html_e('Navigation','altotheme');?></div>
    <div id="mobile-account" class="hidden-tag"><?php include get_template_directory() . '/includes/mobile-account.php';?></div>
    <div id="cart-sidebar" class="hidden-tag">
        <div class="cart-close">
            <h3 class="lt-tit-mycart"><?php echo esc_html__('MY CART', 'altotheme');?></h3>
            <a href="javascript:void(0);" title="<?php esc_html_e('Close', 'altotheme');?>"><?php esc_html_e('Close','altotheme');?></a>
            <hr />
        </div>
        <?php lt_mini_cart_sidebar(); ?>
    </div>
</div>
<!--<script src='http://www.propsdiva.com/addtexttoimage/script.js'></script>-->

</body>
</html>