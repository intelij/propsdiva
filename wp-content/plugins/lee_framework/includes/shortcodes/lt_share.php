<?php
function lt_sc_share($atts, $content = null) {
    extract(shortcode_atts(array(
        'title'  => '',
        'size' => '',
        'tooltip' => 'top',
        'style' => '',
    ), $atts));
    global $post, $lt_opt;
    
    if (isset($post->ID )) {
        $permalink = get_permalink($post->ID );
        $featured_image =  wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
        $featured_image_2 = $featured_image['0'];
        $post_title = rawurlencode(get_the_title($post->ID));
    }
    if($title) $title = '<span>'.$title.'</span>';

    ob_start();
    ?>
    <ul class="social-icons share-row <?php echo esc_attr($size); ?> <?php echo esc_attr($style); ?>">
        <?php echo esc_attr($title); ?>
        <?php if($lt_opt['social_icons']['facebook']) { ?>
            <li>
                <a href="http://www.facebook.com/sharer.php?u=<?php echo esc_url($permalink); ?>" target="_blank" class="icon tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Share on Facebook','lee_framework'); ?>"><span class="icon-facebook"></span>
                </a>
            </li>
        <?php } ?>
        <?php if($lt_opt['social_icons']['twitter']) { ?>
            <li>
                <a href="https://twitter.com/share?url=<?php echo esc_url($permalink); ?>" target="_blank" class="icon tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Share on Twitter','lee_framework'); ?>"><span class="icon-twitter"></span>
                </a>
            </li>
        <?php } ?>
        <?php if($lt_opt['social_icons']['email']) { ?>
            <li>
                <a href="mailto:enteryour@addresshere.com?subject=<?php echo esc_attr($post_title); ?>&amp;body=Check%20this%20out:%20<?php echo esc_url($permalink); ?>" class="icon tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Email to a Friend','lee_framework'); ?>"><span class="icon-envelop"></span>
                </a>
            </li>
        <?php } ?>
        <?php if($lt_opt['social_icons']['pinterest']) { ?>
            <li>
                <a href="//pinterest.com/pin/create/button/?url=<?php echo esc_url($permalink); ?>&amp;media=<?php echo esc_attr($featured_image_2); ?>&amp;description=<?php echo esc_attr($post_title); ?>" target="_blank" class="icon tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Pin on Pinterest','lee_framework'); ?>"><span class="icon-pinterest"></span>
                </a>
            </li>
        <?php } ?>
        <?php if($lt_opt['social_icons']['googleplus']) { ?>
            <li>
                <a href="//plus.google.com/share?url=<?php echo esc_url($permalink); ?>" target="_blank" class="icon tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Share on Google+','lee_framework'); ?>"><span class="icon-google-plus"></span>
                </a>
            </li>
        <?php } ?>
    </ul>
    
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
} 
add_shortcode('share', 'lt_sc_share');


function lt_sc_follow($atts, $content = null) {
    $sliderrandomid = rand();
    extract(shortcode_atts(array(
        'size' => 'normal',
        'tooltip' => 'top',
        'style' => '',
        'title' => '',
        'twitter' => '',
        'facebook' => '',
        'pinterest' => '',
        'email' => '',
        'googleplus' => '',
        'instagram' => '',
        'rss' => '',
        'linkedin' => '',
        'youtube' => '',
        'flickr' => '',
    ), $atts));
    ob_start();
    ?>
    <div class="social-icons <?php echo esc_attr($size);?> <?php echo esc_attr($style); ?>">
        <?php if($title){?>
            <span><?php echo esc_attr($title); ?></span>
        <?php }?>

        <?php if($facebook){?>
            <a href="<?php echo esc_url($facebook); ?>" target="_blank"  class="icon icon_facebook tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Follow us on Facebook','lee_framework') ?>"><span class="icon-facebook"></span></a>
        <?php }?>
        <?php if($twitter){?>
            <a href="<?php echo esc_url($twitter); ?>" target="_blank" class="icon icon_twitter tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Follow us on Twitter','lee_framework') ?>"><span class="icon-twitter"></span></a>
        <?php }?>
        <?php if($email){?>
            <a href="mailto:<?php echo esc_url($email); ?>" target="_blank" class="icon icon_email tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Send us an email','lee_framework') ?>"><span class="icon-envelop"></span></a>
        <?php }?>
        <?php if($pinterest){?>
            <a href="<?php echo esc_url($pinterest); ?>" target="_blank" class="icon icon_pintrest tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Follow us on Pinterest','lee_framework') ?>"><span class="icon-pinterest"></span></a>
        <?php }?>
        <?php if($googleplus){?>
            <a href="<?php echo esc_url($googleplus); ?>" target="_blank" class="icon icon_googleplus tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Follow us on Google+','lee_framework')?>"><span class="icon-google-plus"></span></a>
        <?php }?>
        <?php if($instagram){?>
            <a href="<?php echo esc_url($instagram); ?>" target="_blank" class="icon icon_instagram tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Follow us on Instagram','lee_framework')?>"><span class="icon-instagram"></span></a>
        <?php }?>
        <?php if($rss){?>
            <a href="<?php echo esc_url($rss); ?>" target="_blank" class="icon icon_rss tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Subscribe to RSS','lee_framework') ?>"><span class="icon-feed"></span></a>
        <?php }?>
        <?php if($linkedin){?>
            <a href="<?php echo esc_url($linkedin); ?>" target="_blank" class="icon icon_linkedin tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('LinkedIn','lee_framework') ?>"><span class="icon-linkedin"></span></a>
        <?php }?>
        <?php if($youtube){?>
            <a href="<?php echo esc_url($youtube); ?>" target="_blank" class="icon icon_youtube tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('YouTube','lee_framework') ?>"><span class="icon-youtube"></span></a>
        <?php }?>
        <?php if($flickr){?>
            <a href="<?php echo esc_url($flickr); ?>" target="_blank" class="icon icon_flickr tip-<?php echo esc_attr($tooltip); ?>" data-tip="<?php _e('Flickr','lee_framework') ?>"><span class="icon-flickr"></span></a>
        <?php }?>
    </div>

    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode("follow", "lt_sc_follow");