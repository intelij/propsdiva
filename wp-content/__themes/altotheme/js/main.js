jQuery(window).trigger('resize').trigger('scroll');

// ---------------------------------------------- //
// Global Read-Only Variables (DO NOT CHANGE!)
// ---------------------------------------------- //
var doc = document.documentElement; doc.setAttribute('data-useragent', navigator.userAgent);

var fullwidth = 1200;
var iOS = check_iOS();
var _event = (iOS) ? 'click, mousemove':'click';
var globalTimeout = null;
var load_flag = false;
var page_load = 1;

/* =========== Document ready ==================== */
jQuery(document).ready(function($){
"use strict";
$(window).stellar();
$('body #lt-before-load').fadeOut(300);

var jPM = $.jPanelMenu({
    menu: '#site-navigation',
    trigger: '.mobile-menu a.mobile_toggle',
    animated: true
});

var jRes = jRespond([
    {
        label: 'small',
        enter: 0,
        exit: 768
    },{
        label: 'medium',
        enter: 768,
        exit: 980
    },{
        label: 'large',
        enter: 980,
        exit: 10000
    }
]);

jRes.addFunc({
    breakpoint: 'small',
    enter: function() {
        jPM.on();
        // Add class accordion
        $('#jPanelMenu-menu').attr('id', 'lt-menu-mobile'); // change jPanelMenu-menu => lt-menu_mobile
        $('#lt-menu-mobile').addClass('lt-menu-accordion');
        $('#lt-menu-mobile .nav-dropdown').attr('class', 'nav-dropdown-mobile');
        $('#lt-menu-mobile .nav-column-links').addClass('nav-dropdown-mobile');
        $('#lt-menu-mobile').find('hr.hr-lt-megamenu').remove();
        $('.lt-menu-accordion li').each(function(k, v){
            if($(this).hasClass('menu-item-has-children')){
                $(this).addClass('li_accordion');
                if($(this).hasClass('current-menu-ancestor') || $(this).hasClass('current-menu-parent')){
                    $(this).addClass('active');
                    $(this).prepend('<a href="javascript:void(0);" class="accordion"><span class="icon fa fa-minus-square-o"></span></a>');
                }else{
                    $(this).prepend('<a href="javascript:void(0);" class="accordion"><span class="icon fa fa-plus-square-o"></span></a>').find('>.nav-dropdown-mobile').hide();
                }
            }
        });
        var head_menu = $('#heading-menu-mobile').html();
        var mini_acc = $('#mobile-account').html();
        $('#lt-menu-mobile').prepend('<li class="menu-item root-item menu-item-heading">' + head_menu + '</li>');
        $('#lt-menu-mobile').append('<li class="menu-item root-item menu-item-account">' + mini_acc + '</li>');
        
        $('body').on('click', '.mobile-menu a.mobile_toggle', function(e){
            e.preventDefault();
            if(!$('#lt-menu-mobile').hasClass('menu-show')){
                $('#lt-menu-mobile').addClass('menu-show');
            }
        });
    },
    exit: function() {
        $('#lt-menu-mobile').attr('id', 'jPanelMenu-menu');
        $('#jPanelMenu-menu').removeClass('lt-menu-accordion');
        $('#jPanelMenu-menu .nav-dropdown-mobile').attr('class', 'nav-dropdown');
        $('#lt-menu-mobile .nav-column-links').removeClass('nav-dropdown-mobile');
        $('.accordion').remove();
        $('.lt-menu-accordion li').each(function(){
            if($(this).hasClass('menu-item-has-children')){
                $(this).removeClass('li_accordion');
            }
        });
        jPM.off();
    }
});

// Accordion menu
$('body').on('click', '.lt-menu-accordion .li_accordion > a.accordion', function(e) {
    e.preventDefault();
    var ths = $(this).parent();
    var cha = $(ths).parent();
    if(!$(ths).hasClass('active')) {
        var c = $(cha).children('li.active');
        $(c).removeClass('active').children('.nav-dropdown-mobile').css({height:'auto'}).slideUp(300);
        $(ths).children('.nav-dropdown-mobile').slideDown(300).parent().addClass('active');
        $(c).find('> a.accordion > span').removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
        $(this).find('span').removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
    } else {
        $(ths).find('>.nav-dropdown-mobile').slideUp(300).parent().removeClass('active');
        $(this).find('span').removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
    }
    return false;
});

if($('.lt-accordion .li_accordion > a.accordion').length > 0){
    $('body').on('click', '.lt-accordion .li_accordion > a.accordion', function() {
        var ths = $(this).parent();
        var cha = $(ths).parent();
        if(!$(ths).hasClass('active')) {
            $(cha).removeClass('current-cat-parent').removeClass('current-cat');
            var c = $(cha).children('li.active');
            $(c).removeClass('active').children('.children').slideUp(300);
            $(ths).addClass('active').children('.children').slideDown(300);
            $(c).find('>a.accordion>span').removeClass('pe-7s-less').addClass('pe-7s-plus');
            $(this).find('span').removeClass('pe-7s-plus').addClass('pe-7s-less');
        } else {
            $(ths).removeClass('active').children('.children').slideUp(300);
            $(this).find('span').removeClass('pe-7s-less').addClass('pe-7s-plus');
        }
        return false;
    });
}
/* GRID LIST SWITCH */
if($('.productGrid').length > 0){
    $('body').on('click', ".productGrid", function(){
        //var product_per_row = $('.category-page .products').attr('data-product-per-row');
        $(".productGrid").addClass("active");
        $(".productList").removeClass("active");
        $.cookie('gridcookie','grid', {path: '/'});
        if(wow_enable){
            $("ul.products").fadeOut(300,function(){
                $(this).addClass('grid').removeClass('list').fadeIn(300);
            });
        }else{
            $("ul.products").addClass('grid').removeClass('list');
        }
        return false;
    });
}
if($('.productList').length > 0){
    $('body').on('click', ".productList", function(){
        //var product_per_row = $(".category-page .products").attr('data-product-per-row');
        $(".productList").addClass("active");
        $(".productGrid").removeClass("active");
        $.cookie('gridcookie','list', {path: '/'});
        if(wow_enable){
            $("ul.products").fadeOut(300,function(){
                $(this).addClass('list').removeClass('grid').fadeIn(300);
            });
        }else{
            $("ul.products").addClass('list').removeClass('grid');
        }
        return false;
    });
}
if ($.cookie('gridcookie')){
    $("ul.products").addClass($.cookie('gridcookie'));
}

if ($.cookie('gridcookie') === 'grid') {
    $(".filter-tabs .productGrid").addClass('active');
    $(".filter-tabs .productList").removeClass('active');
}

if($.cookie('gridcookie') === 'list') {
    $(".filter-tabs .productList").addClass('active');
    $(".filter-tabs .productGrid").removeClass('active');
}

if($('.filter-tabs li').length > 0){
    $('body').on('click', ".filter-tabs li", function(event){
        event.preventDefault();
    });
}

$('body').on('click', '.product-summary .quick-view', function(){
    var product_img = $(this).parents('.product-img');
    $(product_img).css({opacity: 0.5});
    $(product_img).after('<div class="please-wait type2" style="top:40%"></div>');
});

$('body').on('click', '.product_list_widget .quick-view', function(){
    $(this).parents('.item-product-widget').find('.images').append('<div class="please-wait dark"><span></span><span></span><span></span></div>');
});

$('body').on('click', '.quick-view', function(e){
    var _this = $(this);
    var product_img = $(_this).parents('.product-img');
    var product_id = $(_this).attr('data-prod');
    var _head_type = $(_this).attr('data-head_type');
    var data = { action: 'jck_quickview', product: product_id, head_type: _head_type};
    $.post(ajaxurl, data, function(response) {
        $.magnificPopup.open({
            mainClass: 'my-mfp-zoom-in',
            items: {
                src: '<div class="product-lightbox">' + response + '</div>',
                type: 'inline'
            },
            callbacks: {
                afterClose: function() {
                    var buttons = $(_this).parents('.product-summary');
                    $(buttons).addClass('hidden-tag');
                    setTimeout(function(){
                        $(buttons).removeClass('hidden-tag');
                    }, 100);
                }
            }
        });

        $('.please-wait,.color-overlay').remove();
        $(product_img).removeAttr('style');

        setTimeout(function() {
            $('.main-image-slider').owlCarousel({
                items:1,
                loop:true,
                nav:true,
                autoplay:true,
                autoplaySpeed:500,
                dots:false,
                autoplayTimeout:3000,
                autoplayHoverPause:true,
                responsiveClass:true,
                navText:["",""],
                navSpeed:500
            });
            $('.product-lightbox form').wc_variation_form();
            $('.product-lightbox form select').change();
        }, 600);
    });
    e.preventDefault();
});

jRes.addFunc({
    breakpoint: ['large','medium'],
    enter: function() {
        $('.category-tree').hoverIntent(
            function(){
                $(this).find('.nav-dropdown').fadeIn(50);
                $(this).addClass('active');
            },
            function(){
                $(this).find('nav-dropdown').fadeOut(50);
                $(this).removeClass('active');
            }
        );

        $('body').on('click', '.category-tree .nav-dropdown ul > li', function(){
            var selected = $.trim($(this).text());
            var maxLengh = 8;
            $('.category-tree > .category-inner span').html(selected).text(function(i, text){
                if(text.length > maxLengh){
                    return text.substr(0, maxLengh) + '...';
                }
            });
        });
    },
    exit: function() {
        
    }
});

$('.setting-dropdown').hoverIntent(
    function(){
        $(this).addClass('active');
    },
    function(){
        $(this).removeClass('active');
    }
);

/* Product Gallery Popup */
if($('a.product-lightbox-btn').length > 0 || $('a.product-video-popup').length > 0){
    $('.main-images').magnificPopup({
        delegate: 'a',
        type: 'image',
        tLoading: '<div class="please-wait dark"><span></span><span></span><span></span></div>',
        removalDelay: 300,
        closeOnContentClick: true,
        gallery: {
            enabled: true,
            navigateByImgClick: false,
            preload: [0,1]
        },
        image: {
            verticalFit: false,
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
        },
        callbacks: {
            beforeOpen: function() {
                var productVideo = $('.product-video-popup').attr('href');

                if(productVideo){
                    // Add product video to gallery popup
                    this.st.mainClass = 'has-product-video';
                    var galeryPopup = $.magnificPopup.instance;
                    galeryPopup.items.push({
                        src: productVideo,
                        type: 'iframe'
                    });

                    galeryPopup.updateItemHTML();
                }
            },
            open: function() {
                
            }
        }
    });

    $('body').on('click', '.product-lightbox-btn', function(e){
        $('.product-images-slider').find('.owl-item.active a').click();
        e.preventDefault();
    });

    /* Product Video Popup */
    $('body').on('click', "a.product-video-popup", function(e){
        $('.product-images-slider').find('.first a').click();
        var galeryPopup = $.magnificPopup.instance;
        galeryPopup.prev();
        e.preventDefault();
    });
};

$("*[id^='attachment'] a, .entry-content a[href$='.jpg'], .entry-content a[href$='.jpeg']").magnificPopup({
    type: 'image',
    tLoading: '<div class="please-wait dark"><span></span><span></span><span></span></div>',
    closeOnContentClick: true,
    mainClass: 'my-mfp-zoom-in',
    image: {
        verticalFit: false
    }
});

$(".gallery a[href$='.jpg'],.gallery a[href$='.jpeg'],.featured-item a[href$='.jpeg'],.featured-item a[href$='.gif'],.featured-item a[href$='.jpg'], .page-featured-item .slider > a, .page-featured-item .page-inner a > img, .gallery a[href$='.png'], .gallery a[href$='.jpeg'], .gallery a[href$='.gif']").parent().magnificPopup({
    delegate: 'a',
    type: 'image',
    tLoading: '<div class="please-wait dark"><span></span><span></span><span></span></div>',
    mainClass: 'my-mfp-zoom-in',
    gallery: {
        enabled: true,
        navigateByImgClick: true,
        preload: [0,1]
    },
    image: {
        tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
    }
});

$('#main-content').waypoint(function() {
    $('#top-link').toggleClass('active');
},{offset:'-100%'});

// **********************************************************************// 
// ! Fixed header
// **********************************************************************// 
    
$(window).scroll(function(){
    if (!$('body').find('fixNav-enabled')) {return false; }
    var fixedHeader = $('.fixed-header-area');
    var scrollTop = $(this).scrollTop();
    var headerHeight = $('.header-wrapper').height() + 50;
    
    if(scrollTop > headerHeight){
        if(!fixedHeader.hasClass('fixed-already')) {
            fixedHeader.stop().addClass('fixed-already');
        }
    }else{
        if(fixedHeader.hasClass('fixed-already')) {
            fixedHeader.stop().removeClass('fixed-already');
        }
    }
});

// **********************************************************************// 
// ! Vertical header
// **********************************************************************//
if($('.header-fold-btn').length > 0){
    $('body').on('click', '.header-fold-btn', function(){
        var _header = $(this).parent().parent();
        $(_header).toggleClass('fold-active');
        return false;
        $(window).resize();
    });
}

// **********************************************************************// 
// ! Header slider overlap for Transparent
// **********************************************************************//
$(window).resize(function() {
    var headerWrapper = $('.header-wrapper');
    var bodyWidth = $('body').width();
    if(headerWrapper.hasClass('header-transparent')) {
        var headerHeight = headerWrapper.height();
        var lt_sc_carousel = $('.lt-sc-carousel-warper').first();
        if(bodyWidth < 768) {
            headerHeight = 0;
        }
        lt_sc_carousel.css({
            'marginTop' : - headerHeight
        });
    }
    
    // Fix Sidebar Mobile, Search Mobile display switch to desktop
    var cart = $('.black-window').hasClass('cart-window');
    if(bodyWidth > 768 && !cart) {
        if($('.col-sidebar').length > 0){
            $('.col-sidebar').removeAttr('style');
        }
        if($('.warpper-mobile-search').length > 0 && !$('.warpper-mobile-search').hasClass('show-in-desk')){
            $('.warpper-mobile-search').hide();
        }
        if($('.black-window').length > 0){
            $('.black-window').hide();
        }
    }
});

// top link
if($('#top-link').length > 0){
    $('body').on('click', '#top-link', function(e) {
        $.scrollTo(0,300);
        e.preventDefault();
    });
}

if($('.scroll-to').length > 0){
    $('.scroll-to').each(function(){
        var link = $(this).data('link');
        var end = $(this).offset().top;
        var title = $(this).data('title');

        if($(this).data('bullet','true')){
            $('.scroll-to-bullets').append('<a href="' + link + '"><strong>' + title + '</strong><span></span></a><br/>');
        }

        $('body').on('click', 'a[href="' + link + '"]', function(){
            $.scrollTo(end,500);
        });

        $(this).waypoint(function() {
            $('.scroll-to-bullets a').removeClass('active');
            $('.scroll-to-bullets').find('a[href="' + link + '"]').toggleClass('active');
        },{offset:'0'});
    });
}

/***** Progress Bar *****/
if ($('.progress-bar').length > 0) {
    $('.progress-bar').each(function(){
        var meter = $(this).find('.bar-meter');
        var number = $(this).find('.bar-number');
        var _per = $(meter).attr('data-meter');
        $(this).waypoint(function() {
            $(meter).css({'width': 0, 'max-width': _per + '%'});
            $(meter).delay(50).animate({width : _per + '%'}, 400);
            $(number).delay(400).show();
            setTimeout(function(){
                $(number).css('opacity',1);
            }, 400);
        },
        {
            offset : _per + '%',
            triggerOnce : true
        });
    });
}

// For demo
if($('.show-theme-options').length > 0){
    $('body').on('click', '.show-theme-options', function(){
        $(this).parent().toggleClass('open');
        $(window).resize();

        return false;
    });
}
if($('.wide-button').length > 0){
    $('body').on('click', '.wide-button', function(e){
        $('body').removeClass('boxed');
        $(this).addClass('active');
        $('.config-options').find('.ss-content .boxed-button').removeClass('active');
        $.cookie('layout', null, {path: '/'});
        $(window).resize();
        $('.ss-patterns-content').fadeOut(500);
        $('.lt-sc-carousel').resize();
    });
}
if($('.boxed-button').length > 0){
    var boxed = $.cookie('layout');
    if(boxed === 'boxed'){
        $('.ss-patterns-content').show();
    }
    
    $('body').on('click', '.boxed-button', function(){
        $('body').addClass('boxed');
        $(this).addClass('active');
        $('.config-options').find('.ss-content .wide-button').removeClass('active');
        $.cookie('layout' , 'boxed' , {path: '/'});
        $(window).resize();
        $('.ss-patterns-content').fadeIn(500);
        $('.lt-sc-carousel').resize();
    });
}
if (($.cookie('layout') != null) && ($.cookie('layout') === 'boxed')){
    $('body').addClass('boxed');
    $('.boxed-button').addClass('active');
    $('.wide-button').removeClass('active');
} 

if($('.ss-color').length > 0){
    $('body').on('click', '.ss-color', function(){
        var datastyle = $(this).attr('data-style');
        $('head').append('<link rel="stylesheet" href="'+datastyle+'" type="text/css" />');
        if (($.cookie('data-style') != null) && ($.cookie('data-style') != datastyle)){
            $.cookie('data-style', null, { path: '/' });
        }
        $.cookie('data-style',datastyle,{path: '/'});
    });
}

if ($.cookie('data-style') != null){
    $('head').append('<link rel="stylesheet" href="'+$.cookie('data-style')+'" type="text/css" />');
};

if($('.ss-image').length > 0){
    $('body').on('click', '.ss-image', function(){
        var pattern = $(this).attr('data-pattern');
        $('html').css({"background-image":"url('"+pattern+"')", "background-attachment":"fixed"});
        $('body').css("background-color", "transparent");
        if(($.cookie('data-bg') != null) && ($.cookie('data-bg') !== pattern)){
            $.cookie('data-bg', null, { path: '/' });
        }
        $.cookie('data-bg',pattern,{path: '/'});
    });
}

if ($.cookie('data-bg') != null){
    $('html').css({"background-image": "url('"+$.cookie('data-bg')+"')", "background-attachment": "fixed"});
    $('body').css("background-color", "transparent");
};
// End For demo


if($('.collapses .collapses-title a').length > 0){
    $('body').on('click', '.collapses .collapses-title a', function(e) {
        var g = $(this).parents('.collapses-group');
        var t = $(this).parents('.collapses');
        if(!$(t).hasClass('active')) {
            var c = $(g).find('.collapses.active');
            $(c).removeClass('active').find('.collapses-inner').slideUp(200);
            $(t).addClass('active').find('.collapses-inner').slideDown(200);
        } else {
            $(t).removeClass('active').find('.collapses-inner').slideUp(200);
        }
        return false;
    });
}

if($('.lt-accordions-content .lt-accordion-title a').length > 0){
    $('body').on(_event, '.lt-accordions-content .lt-accordion-title a', function() {
        var warp = $(this).parents('.lt-accordions-content');
        var _id = $(this).attr('data-id');
        if(!$(this).hasClass('active')){
            $(warp).find('.lt-accordion-title a').removeClass('active');
            $(warp).find('.lt-panel.active').removeClass('active').slideUp(200);
            $('#lt-secion-' + _id).addClass('active').slideDown(200);
            $(this).addClass('active');
        } else {
            $(this).removeClass('active');
            $('#lt-secion-' + _id).removeClass('active').slideUp(200);
        }
        return false;
    });
}

// Tabable
if($('.lt-tabs-content ul.lt-tabs li a').length > 0){
    $('body').on(_event, '.lt-tabs-content ul.lt-tabs li a', function(e){
        e.preventDefault();
        var _this = $(this);
        if(!$(_this).parent().hasClass('active')){
            var _root = $(_this).parents('.lt-tabs-content');
            var currentTab = $(_this).attr('data-id');
            var show = $(_this).parent().attr('data-show');
            $(_root).find('ul li').removeClass('active');
            $(_this).parent().addClass('active');
            $(_root).find('div.lt-panel').removeClass('active').hide();
            $(currentTab).addClass('active').show();
            var lt_slider = $(currentTab).find('.group-slider');
            
            if(wow_enable){
                if($(currentTab).find('.product-item').length > 0 || $(currentTab).find('.product_list_widget').length > 0){
                    $(currentTab).css({'opacity': '0.9'}).append('<div class="please-wait type2" style="top:40%"></div>');
                    $(_root).find('.wow').css({
                        'visibility': 'hidden',
                        'animation-name': 'none',
                        'opacity': '0'
                    });

                    if($(lt_slider).length < 1){
                        $(currentTab).find('.wow').removeClass('animated').css({'animation-name': 'fadeInUp'});
                        $(currentTab).find('.wow').each(function(){
                            var _wow = $(this);
                            var _delay = parseInt($(_wow).attr('data-wow-delay'));

                            setTimeout(function(){
                                $(_wow).css({'visibility': 'visible'});
                                $(_wow).animate({'opacity': 1}, _delay);
                                if($('.please-wait').length){
                                    $(currentTab).css({'opacity': 1})
                                    $('.please-wait').remove();
                                }
                            }, _delay);
                        });
                    }else{
                        //if(show === '0'){
                            $(currentTab).find('.owl-stage').css({'opacity': '0'});
                            setTimeout(function(){
                                $(currentTab).find('.owl-stage').css({'opacity': '1'});
                            }, 500);
                            //$(_this).parent().attr('data-show', '1');
                        //}

                        $(currentTab).find('.wow').each(function(){
                            var _wow = $(this);
                            $(_wow).css({
                                'animation-name': 'fadeInUp',
                                'visibility': 'visible',
                                'opacity': 0
                            });
                            var _delay = parseInt($(_wow).attr('data-wow-delay'));
                            _delay += (show === '0') ? 500 : 0;
                            setTimeout(function(){
                                $(_wow).animate({'opacity': 1}, _delay);
                                if($('.please-wait').length){
                                    $(currentTab).css({'opacity': 1});
                                    $('.please-wait').remove();
                                }
                            }, _delay);
                        });
                    }
                }
            }else{
                if($(lt_slider).length){
                    $(currentTab).css({'opacity': 0.9}).append('<div class="please-wait type2" style="top:40%"></div>');
                    $(currentTab).find('.owl-stage').css({'opacity': '0'});
                    setTimeout(function(){
                        $(currentTab).find('.owl-stage').css({'opacity': '1'});
                        if($('.please-wait').length){
                            $(currentTab).css({'opacity': 1});
                            $('.please-wait').remove();
                        }
                    }, 300);
                }
            }
        }
        
        return false;
    });
}

// Countdown
$.countdown.regionalOptions[''] = {
    labels: [lee_countdown_l10n.years, lee_countdown_l10n.months, lee_countdown_l10n.weeks, lee_countdown_l10n.days, lee_countdown_l10n.hours, lee_countdown_l10n.minutes, lee_countdown_l10n.seconds],
    labels1: [lee_countdown_l10n.year, lee_countdown_l10n.month, lee_countdown_l10n.week, lee_countdown_l10n.day, lee_countdown_l10n.hour, lee_countdown_l10n.minute, lee_countdown_l10n.second],
    compactLabels: ['y', 'm', 'w', 'd'],
    whichLabels: null,
    digits: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
    timeSeparator: ':', isRTL: true
}
$.countdown.setDefaults($.countdown.regionalOptions['']);
$('.countdown').each(function() {
    var count = $(this);
    var austDay = new Date(count.data('countdown'));
    $(count).countdown({
        until: austDay,
        format: 'dHMS'
    });
});

if(! /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
    $('.yith-wcwl-wishlistexistsbrowse.show').each(function(){
        var tip_message = $(this).find('a').text();
        $(this).find('a').attr('data-tip',tip_message).addClass('tip-top');
    });

    $('.yith-wcwl-add-button.show').each(function(){
        var tip_message = $(this).find('a.add_to_wishlist').text();
        $(this).find('a.add_to_wishlist').attr('data-tip',tip_message).addClass('tip-top');
    });

    $('.tip,.tip-bottom').tipr();
    $('#main-content .tip-top, .footer .tip-top, .absolute-footer .tip-top, .tip-top, .quick-view .tip-top').tipr({mode:"top"});
    $('#top-bar .tip-top, #header-outer-wrap .tip-top').tipr({mode:"bottom"});
}

if($('.bery_banner .center').length > 0){
    $('.bery_banner .center').vAlign();
    $(window).resize(function() {
        $('.bery_banner .center').vAlign();
    });
}

if($('.col_hover_focus').length > 0){
    $('body').on('hover', '.col_hover_focus', function(){
        $(this).parent().find('.columns > *').css('opacity','0.5');
    }, function() {
        $(this).parent().find('.columns > *').css('opacity','1');
    });
}

if($('.add-to-cart-grid.product_type_simple').length > 0){
    $('body').on('click', '.add-to-cart-grid.product_type_simple', function(){
        $('.mini-cart').addClass('active cart-active');
        $('.mini-cart').hover(function(){$('.cart-active').removeClass('cart-active');});
        setTimeout(function(){$('.cart-active').removeClass('active')}, 5000);
    });
}

$('.row ~ br').remove(); 
$('.columns ~ br').remove();
$('.columns ~ p').remove();
$('#megaMenu').wrap('<li/>');
$('select.ninja-forms-field,select.addon-select').wrap('<div class="custom select-wrapper"/>');
$(window).resize();

/* Carousel */
$('.lt-slider').each(function(){
    var cols = $(this).attr('data-columns'),
        cols_small = $(this).attr('data-columns-small'),
        cols_tablet = $(this).attr('data-columns-tablet'),

        autoplay_enable = ($(this).attr('data-autoplay') === 'true') ? true : false,
        loop_enable = ($(this).attr('data-loop') === 'true') ? true : false,
        nav_disable = ($(this).attr('data-disable-nav') === 'true') ? false : true,
        
        margin_px = parseInt($(this).attr('data-margin')),
        ap_speed = parseInt($(this).attr('data-speed')),
        ap_delay = parseInt($(this).attr('data-delay'));
        
    if(!ap_speed){
        ap_speed = 600;
    }
    
    if(!ap_delay){
        ap_delay = 3000;
    }
    
    var lt_slider_params = {
        //addClassActive: true,
        nav: nav_disable,
        autoplay: autoplay_enable,
        autoplaySpeed: ap_speed,
        loop: loop_enable,
        dots: false,
        autoplayTimeout: ap_delay,
        autoplayHoverPause:true,
        responsiveClass:true,
        navText: ["",""],
        navSpeed: 600,
        lazyLoad : true,
        responsive:{
            0:{
                items: cols_small,
                nav:false
            },
            600:{
                items:cols_tablet
            },
            1000:{
                items:cols
            }
        }
    };
    
    if (margin_px){
        lt_slider_params.margin = margin_px;
    }

    $(this).owlCarousel(lt_slider_params);
});

/* Resize carousel */
setInterval(function(){
    var owldata = $(".owl-carousel").data('owlCarousel');
    if (typeof owldata !== 'undefined' && owldata !== false){
        owldata.updateVars();
    }
},1500);

/* Limit product title charactor*/
$('.product-title a').each(function(){
    var selected = $.trim($(this).text());
    var maxLengh = 20;
    //console.log($(this).html());
    $(this).html(selected).text(function(i, text){
        if(text.length > maxLengh){
            return text.substr(0, maxLengh) + '...';
        }
    });
});

$('.main-images').owlCarousel({
    items: 1,
    nav: false,
    autoplaySpeed: 600,
    dots: false,
    autoHeight:true,
    autoplayTimeout:3000,
    autoplayHoverPause:true,
    responsiveClass:true,
    navText: ["",""],
    navSpeed: 600
});

$('.main-images').on('change.owl.carousel', function(e) {
    var currentItem = e.relatedTarget.relative(e.property.value),
        owlThumbs = $(".product-thumbnails .owl-item");
    $('.active-thumbnail').removeClass('active-thumbnail')
    $(".product-thumbnails").find('.owl-item').eq(currentItem).addClass('active-thumbnail');
    owlThumbs.trigger('to.owl.carousel', [currentItem, 300, true]);
}).data('owl.carousel');

$('body').on('click', '.main-images a', function(e){
    e.preventDefault();
});

$('.product-thumbnails .owl-item').owlCarousel();
$('.product-thumbnails').owlCarousel({
    items: 3,
    nav: false,
    autoplay: false,
    dots: false,
    autoHeight: true,
    autoplayTimeout: 3000,
    autoplayHoverPause: true,
    responsiveClass: true,
    navText: ["", ""],
    navSpeed: 600,
    responsive: {
        "0": {
            items: 2,
            nav: false
        },
        "600": {
            items: 3,
            nav: true
        },
        "1000": {
            items: 4
        }
    }
}).on('click', '.owl-item', function () {
    var currentItem = $(this).index()
    $('.main-images').trigger('to.owl.carousel', [currentItem, 300, true]);
});

$('body').on('click', '.product-thumbnails .owl-item a', function(e) {
    e.preventDefault();
});

/* Language switch */
if($('.language-filter select').length > 0){
    $('body').on('change', '.language-filter select', function(){
        window.location = $(this).val();
    });
}

/*********************************************************************
// ! Promo popup
/ *******************************************************************/
var et_popup_closed = $.cookie('leetheme_popup_closed');
$('.lt-popup').magnificPopup({
    items: {
        src: '#lt-popup',
        type: 'inline'
    },
    removalDelay: 300, //delay removal by X to allow out-animation
    fixedContentPos: false,
    callbacks: {
        beforeOpen: function() {
            this.st.mainClass = 'my-mfp-slide-bottom';
        },
        beforeClose: function() {
            var showagain = $('#showagain:checked').val();
            if(showagain === 'do-not-show')
                $.cookie('leetheme_popup_closed', 'do-not-show', {expires: 1, path: '/'});
        }
    }
    // (optionally) other options
});

if(et_popup_closed !== 'do-not-show' && $('.lt-popup').length > 0 && $('body').hasClass('open-popup')) {
    $('.lt-popup').magnificPopup('open');
}

if($('.product-interactions .btn-compare').length > 0){
    $('body').delegate().on('click', '.product-interactions .btn-compare', function(){
        var $button = $(this).parents('.product-interactions');
        $button.find('.compare-button .compare.button').trigger('click');
        return false;
    });
}
if($('.product-interactions .btn-wishlist').length > 0){
    $('body').delegate().on('click', '.product-interactions .btn-wishlist', function(){
        var $button = $(this).parents('.product-interactions');
        $button.find('.add_to_wishlist').trigger('click');
        return false;
    });
}

if($('.product_list_widget .btn-wishlist').length > 0){
    $('body').delegate().on('click', '.product_list_widget .btn-wishlist',function(){
        var $button = $(this).parents('.product_list_widget');
        $button.find('.add_to_wishlist').trigger('click');
        return false;
    });
}

/* PRODUCT ZOOM */
if(!/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
    $('.product-zoom .easyzoom').easyZoom({loadingNotice: ''});
}

/* AJAX PRODUCT */
if($('.load-more-btn').length > 0){
    $('body').on('click', '.load-more-btn', function(){
        if(load_flag){
            return;   
        }else{
            load_flag = true;
            var infinite_id = $(this).attr('data-infinite');
            var _type = $('.shortcode_'+infinite_id).attr('data-product-type');
            var _page = parseInt($('.shortcode_'+infinite_id).attr('data-next-page'));
            var _cat = parseInt($('.shortcode_'+infinite_id).attr('data-cat'));
            var _post_per_page = parseInt($('.shortcode_'+infinite_id).attr('data-post-per-page'));
            var _is_deals = $('.shortcode_'+infinite_id).attr('data-is-deals');
            var _max_pages = parseInt($('.shortcode_'+infinite_id).attr('data-max-pages'));
            $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {
                    action: 'moreProduct',
                    page: _page,
                    type: _type,
                    cat: _cat,
                    post_per_page: _post_per_page,
                    is_deals: _is_deals
                },
                beforeSend: function(){
                    $('.load-more-btn.'+infinite_id).before('<div id="ajax-loading" class="absolute"></div>');
                    $('.load-more-btn.'+infinite_id).css('opacity','0');
                },
                success: function(res){
                    $('.load-more-btn.'+infinite_id).css('opacity', '1');
                    $('.shortcode_'+infinite_id).append(res).fadeIn(1000);
                    $('.shortcode_'+infinite_id).attr('data-next-page', _page + 1);
                    if (_page == _max_pages){
                        $('.load-more-btn.'+infinite_id).addClass('end-product');
                        $('.load-more-btn.'+infinite_id).html('ALL PRODUCTS LOADED').removeClass('load-more-btn');
                    }
                    $('#ajax-loading').remove();
                    $('.tip, .tip-bottom').tipr();
                    $('.products-infinite .tip-top').tipr({mode:"top"});
                    load_flag = false;
                }
            });
            return false;
        }
    });
}

// Target quantity inputs on product pages
$('body').find('input.qty:not(.product-quantity input.qty)').each(function() {
    var min = parseFloat($(this).attr('min'));
    if (min && min > 0 && parseFloat($(this).val()) < min) {
        $(this).val(min);
    }
});

$('body').on('click', '.plus, .minus', function() {
    // Get values
    var $qty = $(this).closest('.quantity').find('.qty'),
        button_add = $(this).parent().parent().find('.single_add_to_cart_button'),
        currentVal = parseFloat($qty.val()),
        max = parseFloat($qty.attr('max')),
        min = parseFloat($qty.attr('min')),
        step = $qty.attr('step');
    // Format values
    if (!currentVal || currentVal === '' || currentVal === 'NaN') currentVal = 0;
    if (max === '' || max === 'NaN') max = '';
    if (min === '' || min === 'NaN') min = 0;
    if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN') step = 1;
    // Change the value
    if ($(this).is('.plus')) {
        if (max && (max == currentVal || currentVal > max)) {
            $qty.val(max);
            if(button_add.length > 0){
                button_add.attr('data-quantity', max);
            }
        } else {
            $qty.val(currentVal + parseFloat(step));
            if(button_add.length > 0){
                button_add.attr('data-quantity', currentVal + parseFloat(step));
            }
        }
    } else {
        if (min && (min == currentVal || currentVal < min)) {
            $qty.val(min);
            if(button_add.length > 0){
                button_add.attr('data-quantity', min);
            }
        } else if (currentVal > 0) {
            $qty.val(currentVal - parseFloat(step));
            if(button_add.length > 0){
                button_add.attr('data-quantity', currentVal - parseFloat(step));
            }
        }
    }
    // Trigger change event
    $qty.trigger('change');
});

// Ajax search
if(search_options.enable_live_search == '1') {
    var searchProducts = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('title'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: ajaxurl + '?action=live_search_products',
        remote: {
            url: ajaxurl + '?action=live_search_products&s=%QUERY',
            ajax: {
                data:{cat: $('.lt-cats-search').val()},
                beforeSend: function () {
                    if($('.live-search-input').parent().find('.loader-search').length == 0){
                        $('.live-search-input').parent().append('<div class="please-wait dark"><span></span><span></span><span></span></div>');
                    }
                },
                success: function () {
                    $('.please-wait').remove();
                },
                error: function () {
                    $('.please-wait').remove();
                }
            }
        }
    });

    searchProducts.initialize();

    $('.live-search-input').typeahead({
        minLength: 3,
        hint: true,
        highlight: false,
        backdrop: {
            "opacity": 0.8,
            "filter": "alpha(opacity=80)",
            "background-color": "#eaf3ff"
        },
        backdropOnFocus: true
    },
    {
        name: 'search',
        source: searchProducts.ttAdapter(),
        displayKey: 'title',
        templates: {
            empty : '<p class="empty-message" style="padding:0;margin:0;font-size:100%;">Sorry. No results match your search.</p>',
            suggestion: Handlebars.compile(search_options.live_search_template)
        }
    });
}

$('body').on('mouseover', '.search-dropdown', function () {
    $(this).addClass('active');
}).on('mouseout', '.search-dropdown', function () {
    $(this).removeClass('active');
});

/* Banner Lax */
if($('.banner.hover-lax .banner-image').length > 0){
    var windowWidth = $(window).width();
    $(window).resize(function() {
        windowWidth = $(window).width();
        if(windowWidth <= 768){
            $('.banner.hover-lax').css('background-position', 'center center');
        }
    });
    
    $('body').on('mousemove', '.banner.hover-lax', function(e){
        var lax_bg = $(this);
        var minWidth = $(lax_bg).attr('data-minwidth') ? $(lax_bg).attr('data-minwidth') : 768;
        
        if(windowWidth > minWidth){
            var amountMovedX = (e.pageX * -1 / 6);
            var amountMovedY = (e.pageY * -1 / 6);
            $(lax_bg).css('background-position', amountMovedX + 'px ' + amountMovedY + 'px');
        }else{
            $(lax_bg).css('background-position', 'center center');
        }
    });
}

// **********************************************************************// 
// ! Portfolio
// **********************************************************************//
if($('.portfolio-list').length > 0){
    var _columns = $('.portfolio-list').attr('data-columns');
    var portfolioGrid = $('.portfolio-list');
    
    $(portfolioGrid).isotope({
        itemSelector: '.portfolio-item',
        layoutMode: 'masonry',
        filter: '*'
    });

    $(portfolioGrid).parent().find('.portfolio-tabs li a').on('click', function(){
        var selector = $(this).attr('data-filter');
        $(portfolioGrid).parent().find('.portfolio-tabs li').removeClass('active');
        if(!$(this).parents('li').hasClass('active')) {
            $(this).parents('li').addClass('active');
        }
        $(portfolioGrid).isotope({filter: selector});
        return false;
    });
    
    var _cat_id = $('.loadmore-portfolio').attr('data-category');
    load_flag = true;
    loadMorePortfolio($, _cat_id, _columns, page_load, ajaxurl);
    
    // loadMore Portfolio
    $('body').on('click', '.loadmore-portfolio', function(){
        var button = $(this);
        if(load_flag){
            return;   
        }else{
            load_flag = true;
            var _cat_id = $(button).attr('data-category');
            page_load++;
            loadMorePortfolio($, _cat_id, _columns, page_load, ajaxurl);
            return false;
        }
    });
}

$('body').on('click', '.portfolio-image-view', function(e){
    var _src = $(this).attr('data-src');
    $.magnificPopup.open({
        closeOnContentClick: true,
        items: {
            src: '<div class="portfolio-lightbox"><img src="'+_src+'" /></div>',
            type: 'inline'
        }
    });
    $('.please-wait,.color-overlay').remove();
    e.preventDefault();
});

$('body').on('click', '.mobile-search', function(){
    $('.black-window').fadeIn(200);
    $('.warpper-mobile-search').show().animate({top: 0}, 500);
    $('.warpper-mobile-search').find('input[name="s"]').val('').focus();
});

$('body').on('click', '.desk-search', function(){
    var _search = $(this).parent().find('.lt-show-search-form'),
        _menu = $('#site-navigation');
    var _w = '500px';
    if($(_menu).length > 0 && $(_menu).width()){
        _w = $(_menu).width();
    }
    var _this = $(this);
    setTimeout(function(){
        $(_this).toggleClass('open');
    },300);
    $(_menu).animate({'opacity': 0}, 200);
    $(_search).show().animate({width: _w}, 300).removeClass('lt-over-hide').after('<div class="lt-tranparent" />');
    $(_search).find('input[name="s"]').css({'padding-left': '15px', 'padding-right': '15px'}).val('').focus();
});

$('body').on('click', '.lt-tranparent', function(){
    var _search = $(this).parent().find('.lt-show-search-form');
    var _this = $('.desk-search');
    setTimeout(function(){
        $(_this).toggleClass('open');
    },200);
    $(_search).find('input[name="s"]').css({padding: 0});
    $(_search).addClass('lt-over-hide').animate({width: 0}, 200).hide(200);
    var _menu = $('.header-container').find('.header-nav');
    if($(_menu).length > 0){
        $(_menu).animate({'opacity': 1}, 200);
    }
    $(this).remove();
});

$('body').on('click', '.toggle-sidebar', function(){
    $('.black-window').fadeIn(200);
    if($('.col-sidebar').hasClass('left')){
        $('.col-sidebar').show().animate({left: 0}, 500);
    }else{
        $('.col-sidebar').show().animate({right: 0}, 500);
    }
});

$('body').on('click', '.cart-link', function(){
    $('.black-window').fadeIn(200).addClass('cart-window');
    $('#cart-sidebar').show().animate({right: 0}, 500);
});

$('body').on('click', '.black-window, .white-window, .cart-close a', function(){
    if($('.black-window').hasClass('cart-window')){
        $('.black-window').removeClass('cart-window');
    }
    
    if($('.warpper-mobile-search').length > 0){
        $('.warpper-mobile-search').animate({top: '-100%'}, 200).hide(200);
        if($('.warpper-mobile-search').hasClass('show-in-desk')){
            setTimeout(function () {
                $('.warpper-mobile-search').removeClass('show-in-desk');
            }, 300);
        }
    }
    
    var bodyWidth = $('body').width();
    if($('.col-sidebar').length > 0 && bodyWidth <= 768){
        if($('.col-sidebar').hasClass('left')){
            $('.col-sidebar').animate({left: '-100%'}, 200).hide(200);
        }else{
            $('.col-sidebar').animate({right: '-100%'}, 200).hide(200);
        }
    }
    
    if($('#cart-sidebar').length > 0){
        $('#cart-sidebar').animate({right: '-100%'}, 200).hide(200);
    }
    
    $('.black-window, .white-window').fadeOut(200);
});

$(document).on('keyup', function(e){
    if (e.keyCode == 27){
        $('.lt-tranparent').click();
        $('.black-window, .white-window, .cart-close a').click();
        $.magnificPopup.close();
    }
});

$('body').on('click', '.add_to_cart_button', function() {
    $.magnificPopup.close();
    setTimeout(function () {
        $('.black-window').fadeIn(200).addClass('cart-window');
        $('#cart-sidebar').show().animate({right: 0}, 500);
    }, 200);
});

// Remove items in cart
$('body').on('click', '.remove.item-in-cart', function(){
    var _this = $(this);
    var _key = $(_this).attr('data-key');
    var _id = $(_this).attr('data-id');
    $('.remove.item-in-cart').removeClass('remove');
    if(_key && _id){
        $.ajax({
            url : ajaxurl,
            type: 'post',
            dataType: 'json',
            data: {
                action: 'lt_cart_remove_item',
                item_key: _key
            },
            beforeSend: function(){
                $(_this).parent().html('<div class="please-wait"><span></span><span></span><span></span></div>');
            },
            success: function(res){
                if(res.succes){
                    $('#item-'+_id).remove();
                    if(
                        $('.add_to_cart_button[data-product_id="'+_id+'"]').length > 0 &&
                        $('.add_to_cart_button[data-product_id="'+_id+'"]').hasClass('added')
                    ){
                        $('.add_to_cart_button[data-product_id="'+_id+'"]').removeClass('added');
                    }

                    if($('.cart_sidebar .mini-cart-item').length < 1){
                        var empty = $('.empty.hidden-tag').html();
                        $('.cart_sidebar').html(empty);
                        setTimeout(function () {
                            $('.black-window').removeClass('cart-window');
                            $('#cart-sidebar').animate({right: '-100%'}, 200).hide(200);
                            $('.black-window').fadeOut(200);
                        }, 200);
                    }
                    
                    $('.products-number .lt-sl').html(res.sl);
                    if($('.total-price').length > 0){
                        $('.total-price').html(res.pr);
                    }
                    $('#cart-sidebar .item-in-cart').addClass('remove');
                }
            },
            error: function(){
                $('#cart-sidebar .item-in-cart').addClass('remove');
            }
        });
    }
});

// Single add to cart
$('body').on('click', '.lt_add_to_cart', function(){
    var _this = $(this),
        _id = $(_this).attr('data-product_id');
    if(_id){
        var _form = $(_this).parents('#lt_form_add_product_'+_id),
            _type = $(_form).attr('data-type'),
            _quantity = $(_form).find('.quantity input[name="quantity"]').val(),
            _variation_id = ($(_form).find('input[name="variation_id"]').length > 0) ? $(_form).find('input[name="variation_id"]').val() : 0,
            _variation = {},
            _head_type = $(_this).attr('data-head_type');
        if(_variation_id && $(_form).find('.variations').length > 0){
            var v = $(_form).find('.variations');
            $(v).find('select').each(function(){
                _variation[$(this).attr('name')] = $(this).val();
            });
        }
        $.ajax({
            url: ajaxurl,
            type: 'post',
            dataType: 'json',
            data: {
                action: 'lt_single_add_to_cart',
                product_id: _id,
                quantity: _quantity,
                product_type: _type,
                variation_id: _variation_id,
                variation: _variation,
                head_type: _head_type
            },
            beforeSend: function(){
                $.magnificPopup.close();
                $('.black-window').fadeIn(200).addClass('cart-window');
                $('#cart-sidebar').show().animate({right: 0}, 500);
            },
            success: function(res){
                var fragments = res.fragments;
                if (fragments) {
                    $.each( fragments, function(key, value) {
                        $(key).addClass('updating');
                        $(key).replaceWith(value);
                    });
                }
            }
        });
    }
    return false;
});

$('body').on('click', '.product_type_variable', function(){
    var _parent = $(this).parents('.product-interactions');
    if($(_parent).length < 1){
        _parent = $(this).parents('.item-product-widget');
    }
    $(_parent).find('.quick-view').click();
    return false;
});
$('body').on('click', '.ajax_add_to_cart_variable', function(){
    $(this).parent().find('.quick-view').click();
    return false;
})

//Shortcode Product_deals
if($('.lt-sc-pdeal-block').length > 0){
    $('.lt-sc-pdeal-block').each(function(){
        var _id = $(this).attr('data-id');
        lt_corousel_deal(_id, $);
    });
}

// shortcode post to top
if($('.lt-post-slider').length > 0){
    var _items = parseInt($('.lt-post-slider').attr('data-show'));
    $('.lt-post-slider').owlCarousel({
        items: _items,
        loop: true,
        nav: false,
        autoplay: true,
        dots: false,
        autoHeight: true,
        //autoWidth: false,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
        responsiveClass: true,
        navText: ["", ""],
        navSpeed: 600,
        responsive:{
            "0": {
                items: 1,
                nav: false
            },
            "600": {
                items: 1,
                nav: false
            },
            "1000": {
                items: _items,
                nav: false
            }
        }
    });
};

if($('.lt-promotion-close').length > 0){
    var height = $('.lt-promotion-news').outerHeight();
    if($.cookie('promotion') !== 'hide'){
        $('.lt-position-relative').animate({'height': height+'px'}, 500);
        $('.lt-promotion-news').fadeIn(500);
    }
    
    $('body').on('click', '.lt-promotion-close', function(){
        $.cookie('promotion','hide', {path: '/'});
        $('.lt-promotion-show').show();
        $('.lt-position-relative').animate({'height': '0px'}, 500);
        $('.lt-promotion-news').fadeOut(500);
    });
    
    $('body').on('click', '.lt-promotion-show', function(){
        $.cookie('promotion','show', {path: '/'});
        $('.lt-promotion-show').hide();
        $('.lt-position-relative').animate({'height': height+'px'}, 500);
        $('.lt-promotion-news').fadeIn(500);
    });
    
};

/* ===================== Filter by sidebar =============================== */
var min_price = 0, max_price = 0, hasPrice = '0';
if($('.price_slider_wrapper').length){
    $('.price_slider_wrapper').find('input').attr('readonly', true);
    $('.price_slider_wrapper').find('button').remove();
    min_price = parseFloat($('.price_slider_wrapper').find('input[name="min_price"]').val()),
    max_price = parseFloat($('.price_slider_wrapper').find('input[name="max_price"]').val());
    hasPrice = ($('.lt_hasPrice').length) ? $('.lt_hasPrice').val() : '0';
    $('.price_slider_wrapper').append('<a href="javascript:void(0);" class="reset_price">Reset</a>');
}

// Tag clouds
if($('.lt-tag-cloud').length && $('.lt-has-filter-ajax').length){
    var _cat_act = parseInt($('.lt-has-filter-ajax').find('.current-cat a').attr('data-id'));
    var re = /(tag-link-position-\d+)/g;
    $('.lt-tag-cloud').find('a').each(function(){
        var _id = $(this).attr('class'); //tag-link-id
        var m = re.exec(_id);
        if(m !== null) {
            _id.replace(m[0], "");
            _id.replace(' ', "");
        }
        
        _id = parseInt(_id.replace("tag-link-", ""));
        if(_id){
            $(this).addClass('lt-filter-by-cat').attr('data-id', _id);
            if(_id === _cat_act){
                $(this).addClass('lt-active');
            }
        }
    });
}

// Filter by Category
$('body').on('click', '.lt-filter-by-cat', function(){
    if($('.lt-has-filter-ajax').length < 1) {
        return;
    }else{
        if(!$(this).hasClass('lt-disable') && !$(this).hasClass('lt-active')){
            $('li.cat-item').removeClass('current-cat');
            var _this = $(this),
                _catid = $(this).attr('data-id'),
                _order = $('select[name="orderby"]').val(),
                _url = $(this).attr('href'),
                _page = false;

            if(_catid){
                var _variations = [];
                $('.lt-filter-by-variations').each(function(){
                    if($(this).hasClass('lt-filter-var-chosen')){
                        $(this).parent().removeClass('chosen lt-chosen');
                        $(this).removeClass('lt-filter-var-chosen');
                    }
                });
                
                var min = null,
                    max = null;
                if(hasPrice === '1'){
                    var _obj = $(".price_slider").parents('form');
                    if($(_obj).length){
                        min = parseFloat($(_obj).find('input[name="min_price"]').val());
                        max = parseFloat($(_obj).find('input[name="max_price"]').val());

                        if(min < 0){
                            min = 0;
                        }
                        if(max < min){
                            max = min;
                        }
                    }
                }
                
                lt_Ajax_filter($, ajaxurl, _url, _page, _catid, _order, _variations, hasPrice, min, max,  0, '', _this);
            }
        }
        return false;
    }
});

if($('.woocommerce-ordering').length > 0 && $('.lt-has-filter-ajax').length > 0){
    var _parent = $('.woocommerce-ordering').parent(),
        _order = $('.woocommerce-ordering').html();
    $(_parent).html(_order);
}

// Filter by ORDER BY
$('body').on('change', 'select[name="orderby"]', function(){
    if($('.lt-has-filter-ajax').length < 0) {
        return;
    }else{
        var _this = $('.current-cat > .lt-filter-by-cat'),
            _order = $(this).val(),
            _page = false,
            _catid = null,
            _url = '';
            
        if($(_this).length){
            _catid = $(_this).attr('data-id');
            _url = $(_this).attr('href');
        }
        
        var _variations = lt_setVariations($, [], []);
        
        var min = null,
            max = null;
        if(hasPrice === '1'){
            var _obj = $(".price_slider").parents('form');
            if($(_obj).length){
                min = parseFloat( $(_obj).find('input[name="min_price"]').val() );
                max = parseFloat( $(_obj).find('input[name="max_price"]').val() );
                if(min < 0){
                    min = 0;
                }
                if(max < min){
                    max = min;
                }
            }
        }
        
        var _hasSearch = ($('input#lt_hasSearch').length > 0 && !_catid) ? 1 : 0;
        var _s = (_hasSearch === 1) ? $('input#lt_hasSearch').val() : '';
        
        lt_Ajax_filter($, ajaxurl, _url, _page, _catid, _order, _variations, hasPrice, min, max, _hasSearch, _s, _this);
        return false;
    }
});

// Filter by Paging
$('body').on('click', '.lt-pagination-ajax .page-numbers', function(){
    if($(this).hasClass('lt-current')){
        return;
    }else{
        var _this = $('.current-cat > .lt-filter-by-cat'),
            _order = $('select[name="orderby"]').val(),
            _page = $(this).attr('data-page'),
            _catid = null,
            _url = '';
        if(_page === '1'){
            _page = false;
        }
        if($(_this).length){
            _catid = $(_this).attr('data-id');
            _url = $(_this).attr('href');
        }
        
        var _variations = lt_setVariations($, [], []);
        
        var min = null,
            max = null;
        if(hasPrice === '1'){
            var _obj = $(".price_slider").parents('form');
            if($(_obj).length){
                min = parseFloat( $(_obj).find('input[name="min_price"]').val() );
                max = parseFloat( $(_obj).find('input[name="max_price"]').val() );
                if(min < 0){
                    min = 0;
                }
                if(max < min){
                    max = min;
                }
            }
        }
        
        var _hasSearch = ($('input#lt_hasSearch').length > 0  && !_catid) ? 1 : 0;
        var _s = (_hasSearch === 1) ? $('input#lt_hasSearch').val() : '';
        
        lt_Ajax_filter($, ajaxurl, _url, _page, _catid, _order, _variations, hasPrice, min, max, _hasSearch, _s, _this);
        return false;
    }
});

// Filter by variations
$('body').on('click', '.lt-filter-by-variations', function(){
    //return;
    if($('.lt-has-filter-ajax').length < 1){
        return;
    }else{
        var _this = $('.current-cat > .lt-filter-by-cat'),
            _order = $('select[name="orderby"]').val(),
            _page = false,
            _catid = null,
            _url = '';
            
        if($(_this).length){
            _catid = $(_this).attr('data-id');
            _url = $(_this).attr('href');
        }
        
        var _variations = [], 
            _keys = [],
            flag = false;
        if($(this).hasClass('lt-filter-var-chosen')){
            $(this).parent().removeClass('chosen lt-chosen');
            $(this).removeClass('lt-filter-var-chosen');
        }else{
            $(this).parent().addClass('chosen lt-chosen');
            $(this).addClass('lt-filter-var-chosen');
        }
        flag = true;
        
        if(flag){
            _variations = lt_setVariations($, _variations, _keys);
        }
        
        var min = null,
            max = null;
        if(hasPrice === '1'){
            var _obj = $(".price_slider").parents('form');
            if($(_obj).length){
                min = parseFloat( $(_obj).find('input[name="min_price"]').val() );
                max = parseFloat( $(_obj).find('input[name="max_price"]').val() );
                if(min < 0){
                    min = 0;
                }
                if(max < min){
                    max = min;
                }
            }
        }

        var _hasSearch = ($('input#lt_hasSearch').length > 0  && !_catid) ? 1 : 0;
        var _s = (_hasSearch === 1) ? $('input#lt_hasSearch').val() : '';
        
        lt_Ajax_filter($, ajaxurl, _url, _page, _catid, _order, _variations, hasPrice, min, max, _hasSearch, _s, _this);
        return false;
    }
});

// Filter by Price
$(".price_slider").on("slidestop", function(e){    
    var _obj = $(this).parents('form');
    if($('.lt-has-filter-ajax').length < 1){
        $(_obj).submit();
    }else{
        var min = parseFloat( $(_obj).find('input[name="min_price"]').val() ),
            max = parseFloat( $(_obj).find('input[name="max_price"]').val() );
        if(min < 0){
            min = 0;
        }
        if(max < min){
            max = min;
        }
        
        if(min != min_price || max != max_price){
            min_price = min;
            max_price = max;
            hasPrice = '1';
            if($('.lt_hasPrice').length){
                $('.lt_hasPrice').val('1');
            }
            
            // Call filter by price
            var _this = $('.current-cat > .lt-filter-by-cat'),
                _order = $('select[name="orderby"]').val(),
                _page = false,
                _catid = null,
                _url = '';

            if($(_this).length){
                _catid = $(_this).attr('data-id');
                _url = $(_this).attr('href');
            }
            
            var _variations = lt_setVariations($, [], []);
            
            var _hasSearch = ($('input#lt_hasSearch').length > 0 && !_catid) ? 1 : 0;
            var _s = (_hasSearch === 1) ? $('input#lt_hasSearch').val() : '';
            
            lt_Ajax_filter($, ajaxurl, _url, _page, _catid, _order, _variations, hasPrice, min, max, _hasSearch, _s, _this);
        }
        return false;
    }
});

$('body').on('click', '.reset_price', function(){
    var min = $('#min_price').attr('data-min');
    var max = $('#max_price').attr('data-max');
    $('.price_slider').slider('values', 0, min);
    $('.price_slider').slider('values', 1, max);
    $('#min_price').val(min);
    $('#max_price').val(max);
    
    var _obj = $(this).parents('form');
    if($('.lt-has-filter-ajax').length < 1){
        $(_obj).submit();
    }else{
        var min = 0,
            max = 0;

        hasPrice = '0';
        if($('.lt_hasPrice').length){
            $('.lt_hasPrice').val('0');
        }

        // Call filter by price
        var _this = $('.current-cat > .lt-filter-by-cat'),
            _order = $('select[name="orderby"]').val(),
            _page = false,
            _catid = null,
            _url = '';

        if($(_this).length){
            _catid = $(_this).attr('data-id');
            _url = $(_this).attr('href');
        }

        var _variations = lt_setVariations($, [], []);

        var _hasSearch = ($('input#lt_hasSearch').length > 0 && !_catid) ? 1 : 0;
        var _s = (_hasSearch === 1) ? $('input#lt_hasSearch').val() : '';

        lt_Ajax_filter($, ajaxurl, _url, _page, _catid, _order, _variations, hasPrice, min, max, _hasSearch, _s, _this);
    }
    
    return false;
});


/* =============== End document ready !!! ================== */
});

function check_iOS() {
    var iDevices = [
        'iPad Simulator',
        'iPhone Simulator',
        'iPod Simulator',
        'iPad',
        'iPhone',
        'iPod'
    ];
    while (iDevices.length) {
        if (navigator.platform === iDevices.pop()){ return true; }
    }
    return false;
}

function loadMorePortfolio(jq, cat_id, columns, paged, ajaxurl){
    jq.ajax({
        url : ajaxurl,
        type: 'post',
        dataType: 'json',
        data: {
            action: 'get_more_portfolio',
            page: paged,
            category: cat_id,
            col: columns
        },
        beforeSend: function(){
            jq('.loadmore-portfolio').before('<div id="ajax-loading"></div>');
            jq('.portfolio-list').css({'overflow': 'hidden'});
        },
        success: function(res){
            jq('#ajax-loading').remove();
            jq('.loadmore-portfolio').show();
            if(res.success){
                jq('.portfolio-list').isotope('insert', jq(res.result)).isotope({itemSelector:'.portfolio-item'});
                setTimeout(function () {
                    jq('.portfolio-list').isotope({itemSelector:'.portfolio-item'});
                }, 800);
                if(paged >= res.max){
                    jq('.loadmore-portfolio').addClass('end-product').html(res.alert).removeClass('loadmore-portfolio');
                }
            } else {
                jq('.loadmore-portfolio').addClass('end-product').html(res.alert).removeClass('loadmore-portfolio');
            }
            load_flag = false;
        }
    });
    
    return false;
};

function lt_corousel_deal(id, $){
    $('.main-images-'+id).owlCarousel({
        items: 1,
        nav: false,
        lazyLoad: true,
        autoplaySpeed: 600,
        dots: false,
        autoHeight: true,
        autoplay: true,
        loop: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        responsiveClass:true,
        navText: ["",""],
        navSpeed: 600
    });

    $('.main-images-'+id).on('change.owl.carousel', function(e) {
        var currentItem = e.relatedTarget.relative(e.property.value);
        var owlThumbs = $(".product-thumbnails-" + id + " .owl-item");
        $('.product-thumbnails-'+ id +' .active-thumbnail').removeClass('active-thumbnail')
        $(".product-thumbnails-" + id).find('.owl-item').eq(currentItem).addClass('active-thumbnail');
        owlThumbs.trigger('to.owl.carousel', [currentItem, 300, true]);
    }).data('owl.carousel');

    $('.product-thumbnails-'+id+' .owl-item').owlCarousel();
    $('.product-thumbnails-'+id).owlCarousel({
        items: 4,
        loop: false,
        nav: false,
        autoplay: false,
        dots: false,
        autoHeight: true,
        //autoWidth: false,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
        responsiveClass: true,
        navText: ["", ""],
        navSpeed: 600,
        responsive:{
            "0": {
                items: 3,
                nav: false
            },
            "600": {
                items: 4,
                nav: false
            },
            "1000": {
                items: 4,
                nav: false
            }
        }
    }).on('click', '.owl-item', function () {
        var currentItem = $(this).index()
        $('.main-images-'+id).trigger('to.owl.carousel', [currentItem, 300, true]);
    });

    $('body').on('click', '.product-thumbnails-'+id+' .owl-item a', function(e) {
        e.preventDefault();
    });
};

function lt_Ajax_filter($, ajaxurl, _url, _page, _catid, _order, _variations, _hasPrice, _min, _max, _hasSearch, _s, _this){
    $.ajax({
        url: ajaxurl,
        type: 'get',
        dataType: 'json',
        data: {
            action: 'lt_products_page',
            catId: _catid,
            orderby: _order,
            baseUrl: _url,
            variations: _variations,
            hasPrice: _hasPrice,
            min_price: _min,
            max_price: _max,
            hasSearch: _hasSearch,
            s: _s,
            paged: _page
        },
        beforeSend: function(){
            $('.lt-content-page-products').append(('<div class="opacity-3"><div class="please-wait type2"></div></div>'));
            $('.col-sidebar').append(('<div class="opacity-2"></div>'));
            
            $('.lt-filter-by-cat').addClass('lt-disable').removeClass('lt-active');
            
            if($(_this).parents('ul.children').length){
                $(_this).parents('ul.children').show();
            }
            
            $('.lt-tranparent').click();
            $('.black-window, .white-window, .cart-close a').click();
            $.magnificPopup.close();
        },
        success: function(res){
            $('.lt-filter-by-cat').removeClass('lt-disable');
            
            $('#lt-hidden-current-cat').attr('href', _url);
            $('#lt-hidden-current-cat').attr('data-id', _catid);
            
            if(_url === ''){
                if(_hasSearch === 0){
                    _url = res.shop_url;
                }else if(_hasSearch === 1){
                    _url = res.base_url;
                }
            }
            
            // Paging change (friendly Url)
            if(_page){
                var lenUrl = _url.length;
                _url += (_url.length && _url.substring(lenUrl - 1, lenUrl) !== '/') ? '/' : '';
                _url += 'page/' + _page + '/';
            }
            
            var _h = false;
            
            // Search change
            if(_hasSearch === 1){
                _url += _h ? '&' : '?';
                _url += 's=' + encodeURI(_s) + '&page=search&post_type=product';
                _h = true;
            }else{
                if($('.lt-results-blog-search').length > 0){
                    $('.lt-results-blog-search').remove();
                }
                if($('input[name="hasSearch"]').length > 0){
                    $('input[name="hasSearch"]').remove();
                }
            }
            
            // Variations change
            if(_variations.length > 0){
                var l = _variations.length;
                for(var i=0; i<l; i++){
                    var _qtype = (_variations[i].type === 'or') ? '&query_type_' + _variations[i].taxonomy + '=' + _variations[i].type : '';
                    _url += _h ? '&' : '?';
                    _url += 'filter_' + _variations[i].taxonomy + '=' + (_variations[i].slug).toString() + _qtype;
                    _h = true;
                }
            }
            
            // Price change
            if(_hasPrice === '1' && _min && _max){
                _url += _h ? '&' : '?';
                _url += 'min_price='+_min+'&max_price='+_max;
                _h = true;
            }
            
            // Order change
            if(_order && _order !== 'menu_order'){
                _url += _h ? '&' : '?';
                _url += 'orderby=' + _order;
                _h = true;
            }
            
            window.history.pushState(null, '', _url);
            
            $(_this).addClass('lt-active');
            $('.opacity-2').remove();
            $('.opacity-3').remove();
            
            //Refress List product
            if(wow_enable){
                $('.lt-content-page-products .products').hide().html(res.content).fadeIn(1000);
            }else{
                $('.lt-content-page-products .products').html(res.content);
            }
            
            $('.lt-content-page-products .products .tip-top').tipr({mode:"top"});
            
            //Refress Select order
            var select = res.select_order;
            if(select){
                select = $(select).html();
            }
            $('.lt-filter-order').html(select);
            
            //Refress Pagination
            $('.filters-container-down').html(res.pagination);
            
            //Refress Breadcrumb
            $('.lt-breadcrumb').replaceWith(res.breadcrumb);
            
            //Refress variations
            if(!$.isEmptyObject(res.results)){
                
                $.each(res.results, function(key, value){
                    var f = false;
                    if(!$.isEmptyObject(value)){
                        $.each(value, function(k, v){
                            if($('.' + k).length){
                                $('.' + k + ' span.count').html('(' + v + ')');
                                if(v === 0){
                                    if(!$('.' + k).hasClass('no-hidden')){
                                        $('.' + k).fadeOut(200);
                                    }
                                }else{
                                    f = true;
                                    $('.' + k).fadeIn(200);
                                }
                            }
                        });
                    }
                    
                    if(!$('.lt_div_attr_' + key).hasClass('no-hidden')){
                        if(!f){
                            $('.lt_div_attr_' + key).fadeOut(200);
                        }else{
                            $('.lt_div_attr_' + key).fadeIn(200);
                        }
                    }
                });
            }
            
            var _top = $('.filters-container').offset().top;
            $('html, body').animate({scrollTop: (_top - 95)}, 700);
        },
        error: function(){
            // $('.opacity').remove();
            $('.opacity-2').remove();
            $('.opacity-3').remove();
            $('.lt-filter-by-cat').removeClass('lt-disable');
        }
    });
}

function lt_setVariations($, variations, keys){
    $('.lt-filter-var-chosen').each(function(){
        var _attr = $(this).attr('data-attr'),
            _attrVal = $(this).attr('data-term_id'),
            _attrSlug = $(this).attr('data-term_slug'),
            _attrType = $(this).attr('data-type');
        var l = variations.length;
        if(keys.indexOf(_attr) === -1){
            variations.push({
                taxonomy: _attr,
                values: [_attrVal],
                slug: [_attrSlug],
                type: _attrType
            });
            keys.push(_attr);
        }else{
            for(var i=0; i<l; i++){
                if(variations[i].taxonomy.length && variations[i].taxonomy === _attr){
                    variations[i].values.push(_attrVal);
                    variations[i].slug.push(_attrSlug);
                    break;
                }
            }
        }
    });
    
    return variations;
}