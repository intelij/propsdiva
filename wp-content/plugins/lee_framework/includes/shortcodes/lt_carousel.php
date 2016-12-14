<?php
function lt_sc_carousel($atts, $content=null){
    extract(shortcode_atts( array(
        'title' => '',
        'align' => '',
        'column_number' => '1',
        'column_number_tablet' => '2',
        'column_number_small' => '1',
        'navigation' => 'true',
        'nav_type' => '',
        'bullets' => 'true',
        //'slidespeed' => '300',
        'paginationspeed' => '800',
        'autoplay' => 'false',
        //'stoponhover' => 'true',
        //'bullets_type' => '',
    ), $atts));
    
    if ($align == 'center'){
        $align = 'text-center';
    }
    
    $sliderid = rand();
    ob_start();
    
    if($title):?>
        <div class="large-12 columns">
            <div class="title-inner <?php echo esc_attr($align); ?>"> 
                <h3 class="section-title <?php echo esc_attr($align); ?>"><span><?php echo esc_attr($title); ?></span></h3>
                <div class="bery-hr medium"></div>
            </div>
        </div>
    <?php endif; ?>
    <div class="lt-sc-carousel-warper">
        <div class="lt-sc-carousel item-slider<?php echo esc_attr($sliderid); ?>-<?php echo esc_attr($column_number); ?> owl-carousel <?php echo esc_attr($nav_type); ?>" data-contruct="<?php echo esc_attr($sliderid); ?>-<?php echo esc_attr($column_number); ?>">
            <?php echo lt_fixShortcode($content); ?>
        </div>
    </div>
    <script type="text/javascript">
        (function($){
            var owl = $('.item-slider<?php echo esc_attr($sliderid); ?>-<?php echo esc_attr($column_number); ?>');
            var height = ($(owl).find('.banner').length) ? $(owl).find('.banner').height() : 0;
            if(height){
                var loading = '<div class="lt-carousel-loadding" style="height: '+height+'px"><div class="please-wait type2"></div></div>';
                $(owl).parent().append(loading);
            }
            
            $(window).load(function(){
                owl.owlCarousel({
                    loop: true,
                    nav: <?php echo esc_attr($navigation); ?>,
                    dots: <?php echo esc_attr($bullets); ?>,
                    autoplay: <?php echo esc_attr($autoplay); ?>,
                    autoplaySpeed: <?php echo esc_attr($paginationspeed); ?>, // Speed when auto play
                    autoplayTimeout: 5000, //Delay for next slide
                    autoplayHoverPause : true,
                    navText: ["",""],
                    navSpeed: <?php echo esc_attr($paginationspeed); ?>, //Speed when click to navigation arrow
                    dotsSpeed: <?php echo esc_attr($paginationspeed); ?>,
                    responsiveClass:true,
                    callbacks: true,
                    responsive:{
                        0:{
                            items: <?php echo esc_attr($column_number_small); ?>,
                            nav:false
                        },
                        600:{
                            items: <?php echo esc_attr($column_number_tablet); ?>,
                            nav:false
                        },
                        1000:{
                            items: <?php echo esc_attr($column_number); ?>
                        }
                    }
                });
                
                owl.find('.owl-item').each(function(){
                    var _this = $(this);
                    if($(_this).find('.banner .banner-inner').length){
                        var _banner = $(_this).find('.banner .banner-inner');
                        $(_banner).removeAttr('class').removeAttr('style').css({'opacity': 0}).addClass('banner-inner');
                        if($(_this).hasClass('active')){
                            var animation = $(_banner).attr('data-animation');
                            setTimeout(function(){
                                $(_banner).show();
                                $(_banner).addClass('animated').addClass(animation).css({'opacity': 1});
                            }, 200);
                        }
                    }
                });
                
                
                owl.on('translated.owl.carousel', function(items) {
                    var warp = items.target;
                    if($(warp).find('.owl-item').length){
                        $(warp).find('.owl-item').each(function(){
                            var _this = $(this);
                            if($(_this).find('.banner .banner-inner').length){
                                var _banner = $(_this).find('.banner .banner-inner');
                                var animation = $(_banner).attr('data-animation');
                                $(_banner).removeClass('animated').removeClass(animation).removeAttr('style').css({'opacity': 0});
                                if($(_this).hasClass('active')){
                                    setTimeout(function(){
                                        $(_banner).show();
                                        $(_banner).addClass('animated').addClass(animation).css({'opacity': 1});
                                    }, 200);
                                }
                            }
                        });
                    }
                });
                
                $(owl).parent().find('.lt-carousel-loadding').remove();
            });
        })(jQuery);
    </script>
<?php
    $content = ob_get_contents();
    ob_end_clean();
    
    return $content;
}

add_shortcode("bery_slider","lt_sc_carousel");