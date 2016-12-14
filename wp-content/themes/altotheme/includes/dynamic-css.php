<?php
function lt_custom_css() {
	global $lt_opt;
?>

<style type="text/css">
	<?php  if (isset($lt_opt['type_texts'])) {?>
		.top-bar-nav a.nav-top-link,
		body,
		p,
		#top-bar,
		/*.cart-inner .nav-dropdown,*/
		.nav-dropdown {
		    font-family: <?php echo esc_attr($lt_opt['type_texts']) ?>, helvetica, arial, sans-serif!important;
		}
	<?php }?>

	<?php if (isset($lt_opt['type_nav'])) { ?>
		.cart-count,
		.mini-cart .nav-dropdown-inner,
		.price .amount,
		.megatop > a,
		.header-nav li.root-item > a,
		.lt-tabs .lt-tab a,
		#vertical-menu-wrapper li.root-item > a
		{font-family: <?php echo esc_attr($lt_opt['type_nav']) ?>,helvetica,arial,sans-serif!important;}
	<?php }?>

	<?php if (isset($lt_opt['type_headings'])) { ?>
		.product-title,
		h1,h2,h3,h4,h5,h6{font-family: <?php echo esc_attr($lt_opt['type_headings']) ?>,helvetica,arial,sans-serif!important;}
	<?php }?>

	<?php if (isset($lt_opt['type_alt'])) { ?>
		.alt-font{font-family: <?php echo esc_attr($lt_opt['type_alt']) ?>,Georgia,serif!important;}
	<?php }?>
	<?php if (isset($lt_opt['type_banner'])) { ?>
		.banner .banner-content .banner-inner h1,
		.banner .banner-content .banner-inner h2,
		.banner .banner-content .banner-inner h3,
		.banner .banner-content .banner-inner h4,
		.banner .banner-content .banner-inner h5,
		.banner .banner-content .banner-inner h6
		{
			font-family: <?php echo esc_attr($lt_opt['type_banner']); ?>, helvetica,arial,sans-serif!important;
			letter-spacing: 0px;
			font-weight: normal !important;
		}
	<?php } ?>

	<?php if($lt_opt['site_layout'] == 'boxed'){?> 
		body.boxed,
		body {
		    background-color: <?php echo esc_attr($lt_opt['site_bg_color']); ?>;
		    background-image: url("<?php echo esc_url($lt_opt['site_bg_image']); ?>");
		    background-attachment: fixed;
		}
	<?php }?>

	/* COLOR PRIMARY */
	<?php if(isset($lt_opt['color_primary'])){?>
		/* COLOR */
		a:hover,
		a:focus,
		.add-to-cart-grid .cart-icon strong,
		.tagcloud a,
		.navigation-paging a,
		.navigation-image a,
		#header-outer-wrap .mobile-menu a,
		.logo a,
		li.mini-cart .cart-icon strong,
		.widget_product_tag_cloud a,
		.widget_tag_cloud a,
		#header-outer-wrap .mobile-menu a.mobile-menu a,
		.checkout-group h3,
		.order-review h3,
		#yith-searchsubmit .icon-search,
		.mini-cart-item .cart_list_product_price,
		.remove:hover i,
		.price,
		.product-item .info .price,
		.widget_products .product_list_widget li .amount,
		.widget_top_rated_products .product_list_widget li .amount,
		.support-icon,
		.entry-meta a,
		#order_review_heading,
		.checkout-group h3,
		.shop_table.cart td.product-name a,
		a.shipping-calculator-button,
		.widget_layered_nav li a:hover,
		.widget_layered_nav_filters li a:hover,
		.product_list_widget .text-info span,
		.left-text i,
		.copyright-footer span,
		#menu-shop-by-category li.active.menu-parent-item .nav-top-link::after,
		.product_list_widget .product-title:hover,
		.item-product-widget .product-meta .product-title a:hover,
		.product_list_widget span.amount,
		.lt-sc-pdeal.lt-sc-pdeal-block .lt-sc-p-info .left .lt-sc-p-price ins span.amount,
		.lt-sc-pdeal.lt-sc-pdeal-full .lt-sc-block-content .lt-sc-info .lt-sc-p-price ins span.amount,
		.lt-sc-pdeal .countdown .countdown-row span.countdown-section span.countdown-amount,
		.bread.lt-breadcrumb-has-bg .row .breadcrumb-row a:hover,
		.bread.lt-breadcrumb-has-bg .columns .breadcrumb-row a:hover,
		.category-page .filter-tabs li.active i,
		.category-page .filter-tabs li:hover i,
		.group-blogs .blog_info .post-date span,
		.header-type-3 ul.header-nav li a.nav-top-link:hover,
		.widget_layered_nav li:hover a,
		.wpb_wrapper .contact-information li div i,
		.widget_layered_nav_filters li:hover a,
		.remove .icon-close:hover,
		.absolute-footer .left .copyright-footer span,
		.service-block .box .title .icon,
		.contact-information .contact-text strong,
		.nav-wrapper .header-nav li.root-item a:hover,
		.group-blogs .blog_info .read_more a:hover,
		.mini-cart .nav-dropdown .cart_list .mini-cart-item .mini-cart-info .cart_list_product_quantity,
		#top-bar .top-bar-nav li.color a,
		.absolute-footer li a:hover,
		.lt-recent-posts li .post-date,
		.lt-recent-posts .read-more a,
		.widget.woocommerce li a:hover,
		.filter-tabs li.active i, .filter-tabs li:hover i,
		.widget.woocommerce li.current-cat a,
		.team-member .member-details h3,
		.shop_table .remove-product .icon-close:hover,
		.lt-tabs-content .lt-tabs li.active h5,
		.lt-tabs-content .lt-tabs li:hover h5,
		.product-interactions .btn-link:hover,
		.service-block .box .service-icon,
		.absolute-footer ul.menu li a:hover,
		.vertical-menu h4.section-title::before,
		.lt-instagram .username-text span,
		.bread .breadcrumb-row h3 a:hover,
		.bread .breadcrumb-row h3 .current,
		.lt-pagination.style-1 .page-number li span.current,
		.lt-pagination.style-1 .page-number li a:hover,
		.tparrows:hover:before,
		#vertical-menu-wrapper li.root-item:hover > a,
		.widget.woocommerce li.cat-item a.lt-active,
		.widget.widget_categories li a.lt-active,
		.widget.widget_archive li a.lt-active,
		.lt-filter-by-cat.lt-active,
		.header-type-6 .lt-bg-dark.lt-menu-transparent .nav-wrapper .header-nav .root-item:hover > a,
		.html-text i {
		    color: <?php echo esc_attr($lt_opt['color_primary']) ?>;
		}
		ul.header-nav li.active a.nav-top-link,
		ul li .nav-dropdown > ul > li:hover > a,
		ul li .nav-dropdown > ul > li:hover > a::before,
		ul li .nav-dropdown > ul > li .nav-column-links > ul > li a:hover,
		ul li .nav-dropdown > ul > li .nav-column-links > ul > li:hover > a::before {
		    color: <?php echo esc_attr($lt_opt['color_primary']) ?>;
		}
		
		.blog_shortcode_item .blog_shortcode_text h3 a:hover,
		.main-navigation li.menu-item a:hover,
		.widget-area ul li a:hover,
		h1.entry-title a:hover,
		.comments-area a,
		.collapses.active .collapses-title a,
		.progress-bar .bar-meter .bar-number,
		.product-item .info .name a:hover,
		.wishlist_table td.product-name a:hover,
		.product_list_widget .text-info a:hover,
		.product-list .info .name:hover,
		.primary-color,
		ul.main-navigation li .nav-dropdown > ul > li .nav-column-links > ul > li a:hover {
		    color: <?php echo esc_attr($lt_opt['color_primary']) ?> !important;
		}
		
		/* BACKGROUND */
		.tabbed-content.pos_pills ul.tabs li.active a,
		li.featured-item.style_2:hover a,
		.bery_hotspot,
		.label-new.menu-item a:after,
		.text-box-primary,
		.navigation-paging a:hover,
		.navigation-image a:hover,
		.next-prev-nav .prod-dropdown > a:hover,
		.widget_product_tag_cloud a:hover,
		.widget_tag_cloud a:hover,
		.lt-tag-cloud a.lt-active,
		.custom-cart-count,
		a.button.trans-button:hover,
		.please-wait i,
		li.mini-cart .cart-icon .cart-count,
		.product-img .product-bg,
		#submit,
		button,
		#submit,
		button,
		input[type="submit"],
		.post-item:hover .post-date,
		.blog_shortcode_item:hover .post-date,
		.group-slider .sliderNav a:hover,
		.support-icon.square-round:hover,
		.entry-header .post-date-wrapper,
		.entry-header .post-date-wrapper:hover,
		.comment-inner .reply a:hover,
		.social-icons .icon.icon_email:hover,
		.widget_collapscat h3,
		ul.header-nav li a.nav-top-link::before,
		.sliderNav a span:hover,
		.shop-by-category h3.section-title,
		ul.header-nav li a.nav-top-link::before,
		.custom-footer-1 .bery-hr,
		.products.list .product-interactions .quick-view:hover,
		.products.list .product-interactions .yith-wcwl-add-button:hover,
		ul.header-nav li a.nav-top-link::before,
		.widget_collapscat h2,
		.shop-by-category h2.widgettitle,
		.rev_slider_wrapper .type-label-2,
		.bery-hr.primary-color,
		.products.list .product-interactions .yith-wcwl-add-button:hover,
		.pagination-centered .page-numbers a:hover, .pagination-centered .page-numbers span.current,
		.cart-wishlist .mini-cart .cart-link .cart-icon .products-number,
		.load-more::before,
		.item-product-widget .images .quick-view:hover,
		.owl-carousel .owl-nav div:hover,
		.tp-bullets .tp-bullet:hover,
		.tp-bullets .tp-bullet.selected,
		.products-arrow .next-prev-buttons .icon-next-prev:hover,
		.product-interactions .btn-link:hover
		{
		    background-color: <?php echo esc_attr($lt_opt['color_primary']) ?>
		}
		.button.trans-button.primary,
		button.primary-color,
		#lt-popup .ninja-forms-form-wrap .ninja-forms-form .ninja-forms-all-fields-wrap .field-wrap .button,
		.newsletter-button-wrap .newsletter-button
		{
		    background-color: <?php echo esc_attr($lt_opt['color_primary']) ?> !important
		}
	
		/* BORDER COLOR */
		.text-bordered-primary,
		.add-to-cart-grid .cart-icon-handle,
		.add-to-cart-grid.please-wait .cart-icon strong,
		.navigation-paging a,
		.navigation-image a,
		.post.sticky,
		.widget_product_tag_cloud a,
		.next-prev-nav .prod-dropdown > a:hover,
		.iosSlider .sliderNav a:hover span,
		.woocommerce-checkout form.login,
		li.mini-cart .cart-icon strong,
		li.mini-cart .cart-icon .cart-icon-handle,
		.post-date,
		.main-navigation .nav-dropdown ul,
		.remove:hover i,
		.support-icon.square-round:hover,
		.widget_price_filter .ui-slider .ui-slider-handle,
		h3.section-title span,
		.social-icons .icon.icon_email:hover,
		.button.trans-button.primary,
		.seam_icon .seam,
		.border_outner,	
		.pagination-centered .page-numbers a:hover, .pagination-centered .page-numbers span.current,
		.owl-carousel .owl-nav div:hover,
		.products.list .product-interactions .yith-wcwl-wishlistexistsbrowse a,
		.owl-carousel .owl-dots .owl-dot.active,
		.owl-carousel .owl-dots .owl-dot.active:hover,
		.products-arrow .next-prev-buttons .icon-next-prev:hover
		{
		    border-color: <?php echo esc_attr($lt_opt['color_primary']) ?>;
		}

		.widget_product_tag_cloud a:hover,
		.widget_tag_cloud a:hover,
		.promo .sliderNav span:hover,
		.remove .icon-close:hover {
		    border-color: <?php echo esc_attr($lt_opt['color_primary']) ?> !important;
		}

		.tabbed-content ul.tabs li a:hover:after,
		.tabbed-content ul.tabs li.active a:after {
		    border-top-color: <?php echo esc_attr($lt_opt['color_primary']) ?>;
		}

		.collapsing.categories.list li:hover,
		#menu-shop-by-category li.active {
		    border-left-color: <?php echo esc_attr($lt_opt['color_primary'])?> !important;
		}
	<?php }?>
	
	/* COLOR SECONDARY */
	<?php if(isset($lt_opt['color_secondary'])){?>
		a.secondary.trans-button,
		li.menu-sale a {
		    color: <?php echo esc_attr($lt_opt['color_secondary']) ?>!important
		}
		.label-sale.menu-item a:after,
		.mini-cart:hover .custom-cart-count,
		.button.secondary,
		.button.checkout,
		#submit.secondary,
		button.secondary,
		.button.secondary,
		input[type="submit"].secondary{
		    background-color: <?php echo esc_attr($lt_opt['color_secondary']) ?>
		}
		a.button.secondary,
		.button.secondary {
		    border-color: <?php echo esc_attr($lt_opt['color_secondary']) ?>;
		}
		a.secondary.trans-button:hover {
		    color: #FFF!important;
		    background-color: <?php echo esc_attr($lt_opt['color_secondary']) ?>!important
		}
	<?php }?>

	/* COLOR SUCCESS */
	<?php if(isset($lt_opt['color_success'])){?> 
		.woocommerce-message {
		    color: <?php echo esc_attr($lt_opt['color_success']) ?>!important
		}
		.woocommerce-message:before,
		.woocommerce-message:after {
		    color: #FFF!important;
		    background-color: <?php echo esc_attr($lt_opt['color_success']) ?>!important
		}
		.label-popular.menu-item a:after,
		.tooltip-new.menu-item > a:after {
		    background-color: <?php echo esc_attr($lt_opt['color_success']) ?>;
		    border-color: <?php echo esc_attr($lt_opt['color_success']) ?>;
		}
		.add-to-cart-grid.please-wait .cart-icon .cart-icon-handle,
		.woocommerce-message {
		    border-color: <?php echo esc_attr($lt_opt['color_success']) ?>
		}
		.tooltip-new.menu-item > a:before {
		    border-top-color: <?php echo esc_attr($lt_opt['color_success']) ?> !important;
		}
		.out-of-stock-label {
		    border-right-color: <?php echo esc_attr($lt_opt['color_success']); ?> !important;
		    border-top-color: <?php echo esc_attr($lt_opt['color_success']); ?> !important;
		}
		.yith-wcwl-wishlistexistsbrowse a,
		.yith-wcwl-wishlistaddedbrowse a {
		    background-color: <?php echo esc_attr($lt_opt['color_success']) ?>
		}
	<?php }?>

	/* COLOR BUTTON */
	<?php if(isset($lt_opt['color_button'])) {?> 
		form.cart .button,
		.cart-inner .button.secondary,
		.checkout-button,
		input#place_order,
		/*input.button,*/
		.btn-viewcart,
		/*.btn-checkout,*/
		input#submit,
		.add-to-cart-grid-style2,
		.add_to_cart,
		button,
		.button
		{
		    background-color: <?php echo esc_attr($lt_opt['color_button']) ?>!important;
		}
	<?php } else if(isset($lt_opt['color_primary'])){?>
		form.cart .button:hover,
		/*.button:hover,*/
		a.primary.trans-button:hover,
		.form-submit input:hover,
		#payment .place-order input:hover,
		input#submit:hover {
		    background-color: <?php echo esc_attr($lt_opt['color_primary']) ?>!important;
		};
	<?php } ?>

	/* COLOR BORDER BUTTON */
	<?php if(isset($lt_opt['color_border_button'])) {?>
		button,
		.button{
			border-color: <?php echo esc_attr($lt_opt['color_border_button']); ?> !important;
		}
	<?php } ?>


	/* COLOR HOVER */
	<?php if(isset($lt_opt['color_hover'])) {?>
		.product-lightbox-btn:hover
		{
			color: <?php echo esc_attr($lt_opt['color_hover']);?>;
		}
		input[type="submit"]:hover{
			border-color: <?php echo esc_attr($lt_opt['color_hover']);?>;
		}
		#submit:hover,
		.product-lightbox-btn:hover,
		button:hover,
		.button:hover,
		.products.list .product-item .inner-wrap .product-summary .product-interactions > div:hover,
		.product-interactions .btn-link:hover,
		.widget.woocommerce li.lt-li-filter-size.chosen a,
		.widget.woocommerce li.lt-li-filter-size.lt-chosen a,
		.widget.woocommerce li.lt-li-filter-size:hover a,
		.widget.widget_categories li.lt-li-filter-size.chosen a,
		.widget.widget_categories li.lt-li-filter-size.lt-chosen a,
		.widget.widget_categories li.lt-li-filter-size:hover a,
		.widget.widget_archive li.lt-li-filter-size.chosen a,
		.widget.widget_archive li.lt-li-filter-size.lt-chosen a,
		.widget.widget_archive li.lt-li-filter-size:hover a{
		    border-color: <?php echo esc_attr($lt_opt['color_hover']);?> !important;
		}
		form.cart .button:hover,
		.button:hover,
		a.primary.trans-button:hover,
		.form-submit input:hover,
		#payment .place-order input:hover,
		input#submit:hover,
		.add-to-cart-grid-style2:hover,
		.add-to-cart-grid-style2.added,
		.add-to-cart-grid-style2.loading,
		.product-list .product-img .quick-view.fa-search:hover,
		.yith-wcwl-add-to-wishlist a:hover,
		.footer-type-2 input.button,
		button:hover,
		.products.list .product-item .inner-wrap .product-summary .product-interactions > div:hover,
		.widget.woocommerce li.lt-li-filter-size.chosen,
		.widget.woocommerce li.lt-li-filter-size.lt-chosen,
		.widget.woocommerce li.lt-li-filter-size:hover,
		.widget.widget_categories li.lt-li-filter-size.chosen,
		.widget.widget_categories li.lt-li-filter-size.lt-chosen,
		.widget.widget_categories li.lt-li-filter-size:hover,
		.widget.widget_archive li.lt-li-filter-size.chosen,
		.widget.widget_archive li.lt-li-filter-size.lt-chosen,
		.widget.widget_archive li.lt-li-filter-size:hover
		{
		    background-color: <?php echo esc_attr($lt_opt['color_hover']) ?>!important;
		}
	<?php }?>

   	<?php if(is_admin_bar_showing()){ ?> 
    	.tipr_container_bottom{display: none;position: absolute;margin-top: 13px;z-index: 1000;}
        .tipr_container_top{display: none;position: absolute;margin-top:-70px;z-index: 1000;}
	<?php } ?>
	
    <?php if (!isset($lt_opt['disable_wow']) || !$lt_opt['disable_wow']) {?>
        .wow, .lt-panels .lt-panel .owl-item .wow{opacity: 0;}
        .owl-item .wow{opacity: 1}
    <?php }?>
</style>

<?php 
}
add_action( 'wp_head', 'lt_custom_css', 100 );