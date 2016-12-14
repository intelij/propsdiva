<?php
/* SERVICE BOX */
add_shortcode("service_box", "lt_sc_service_box");
function lt_sc_service_box($atts, $content = null){
    extract(Shortcode_atts(array(
        'service_icon' => '',
        'service_title' => '',
        'service_desc' => '',
        'service_hover' => '',
        'el_class' => ''
    ), $atts));
    ob_start();
    ?>
    <div class="service-block <?php echo esc_attr($el_class); ?>">
        <div class="box">
            <div class="service-icon <?php echo esc_attr($service_hover); ?> <?php echo esc_attr($service_icon)?>"></div>
            <div class="service-text">
                <div class="service-title"><h5><?php echo esc_attr($service_title); ?></h5></div>
                <div class="service-desc"><?php echo $service_desc; ?></div>
            </div>
        </div>
    </div>

    <?php 
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

add_shortcode('client', 'lt_sc_client');
function lt_sc_client($params = array(), $content = null) {
    extract(shortcode_atts(array(
        "img_src" => '',
        "name" => '',
        "company" => '',
        "text_color" => '',
        "content_say" => 'Some promo text'
    ), $params));
    $content = preg_replace('#<br\s*/?>#', "", $content);
    $src = ''; $image = '';
    if($img_src != '') {
        $image = wp_get_attachment_image_src($img_src,'full');
        $src = $image[0];
    }
    $client='
        <div class="client large-12 columns">
            <div class="client-inner" style="'.$text_color.'">
                <img class="wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1s" src="'.esc_url($src).'" alt="" />
                <div class="client-info wow fadeInUp" data-wow-delay="800ms" data-wow-duration="1s">
                    <div class="client-content" style="color: '.$text_color.'">'.$content_say.'</div>
                    <h4 class="client-name primary-color">'.esc_attr($name).'</h4>
                    <span class="client-pos" style="color: '.$text_color.'">'.esc_attr($company).'</span>
                </div>
            </div>
        </div>
    ';

    return $client;
}

/* CONTACT US ELEMENT */
add_shortcode("contact_us", "lt_sc_contact_us");
function lt_sc_contact_us($atts, $content = null){
    extract(Shortcode_atts(array(
        'contact_logo' => '',
        'contact_address' => '',
        'contact_phone' => '',
        'service_desc' => '',
        'contact_email' => '',
        'el_class' => ''
    ), $atts));
    ob_start();
    ?>
    <ul class="contact-information <?php echo esc_attr($el_class); ?>">
        <?php if (isset($contact_logo) && $contact_logo != null) { ?>
            <li class="contact-logo">
                <img src="<?php echo esc_attr($contact_logo); ?>" alt="Logo" />
            </li>
        <?php } ?>
        <?php if (isset($contact_address) && $contact_address != null) {?>
            <li class="media">
                <div class="contact-icon"><i class="pe-7s-home"></i></div>
                <div class="contact-text"><span><?php echo esc_attr($contact_address); ?></span></div>
            </li>
        <?php } ?>
        
        <?php if (isset($contact_phone) && $contact_phone != null) {?>
        <li class="media">
            <div class="contact-icon"><i class="pe-7s-phone"></i></div>
            <div class="contact-text"><span><?php echo esc_attr($contact_phone); ?></span></div>
        </li>
        <?php } ?>

        <?php if (isset($contact_email) && $contact_email != null) {?>
        <li class="media">
            <div class="contact-icon"><i class="pe-7s-mail"></i></div>
            <div class="contact-text"><span><?php echo esc_attr($contact_email); ?></span></div>
        </li>
        <?php } ?>
    </ul>

    <?php 
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

/* INSTAGRAM */
add_shortcode('lee_instagram', 'lt_instagram');
function lt_instagram($atts, $content = null){
    extract(shortcode_atts(array(
        'photos'    => '10',
        'username'  => 'bleutheme',
        'text'      => '',
        'el_class' => ''
    ), $atts));

    if (class_exists('null_instagram_widget')){
        ob_start();
        the_widget(
            'null_instagram_widget',
            array('username' => $username, 'target' => '_blank', 'number' => $photos),
            array('before_widget' => '<div class="lt-instagram '.$el_class.'"><div class="username-text text-center hide-for-small"><i class="fa fa-instagram"></i><span>'.$username.'</span> '.__('on Instagram','lee_framework').' </div>', 'after_widget' => '</div>')
        );

        $l = ob_get_contents();
        ob_end_clean();
        return $l;
    } else {
        _e('Please active Instagram Widget plugin to use this featured', 'lee_framework');
    }
}

