<?php
$hstructure = '1';
if(isset($lt_opt['header-type'])){
    $hstructure = $lt_opt['header-type'];
}
if (isset($post->ID)){
    $custom_header = get_post_meta($wp_query->get_queried_object_id(), '_lee_custom_header', true);
    if (!empty($custom_header)){
        $hstructure = lt_get_header_structure($custom_header);
    }
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" >
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

    <?php if (function_exists('wp_site_icon')){ ?>
        <link rel="shortcut icon" href="<?php if ($lt_opt['site_favicon']) {echo esc_attr($lt_opt['site_favicon']);} else {echo get_template_directory_uri().'/favicon.png';} ?>" />
    <?php } ?>

    <!-- Demo Purpose Only. Should be removed in production -->
    <?php
        if (isset($lt_opt['demo_show']) && $lt_opt['demo_show']) {
            wp_enqueue_style('lt-demo-style', get_template_directory_uri() . '/css/demo/config.css', array(), null, 'all');
        }
    ?>
    <!-- Demo Purpose Only. Should be removed in production : END -->

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <div id="lt-before-load"><div class="lt-relative lt-center"><div class="please-wait type2"></div></div></div>
<?php /*for($i=0;$i<=10000;$i++){
   echo '<input type="hidden" name="i'.$i.'" />'; 
}*/?>
<!-- For demo -->
<?php
if (isset($lt_opt['demo_show']) && $lt_opt['demo_show']) {
    get_template_part('css/demo/config');
}
?>
<!-- End For demo -->

<div id="wrapper" class="fixNav-enabled<?php echo ($hstructure == 4) ? ' wrapper-type-4' : '';?>">
<?php lt_promotion_recent_post();?>
<?php
    if ($lt_opt['fixed_nav']):
        include get_template_directory() . '/headers/header-sticky.php';
    endif;
?>

<?php
    include get_template_directory() . '/headers/header-structure-' . $hstructure . '.php';
?>

<div id="main-content" class="site-main light<?php echo ($hstructure == 4) ? ' content-type-4' : '';?>">
<?php if(function_exists('wc_print_notices')) {
    wc_print_notices();
}?>