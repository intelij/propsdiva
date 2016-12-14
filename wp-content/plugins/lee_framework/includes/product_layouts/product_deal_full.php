<?php
$style = '';
$style .= is_numeric($position_top) ? 'top: ' . $position_top . '%;' : 'top: ' . $position_top . ';';
if($position_left){
    $style .= is_numeric($position_left) ? ' left: ' . $position_left . '%;' : ' left: ' . $position_left . ';';
}elseif($position_right){
    $style .= is_numeric($position_right) ? ' right: ' . $position_right . '%;' : ' right: ' . $position_right . ';';
}
$style = ' style="' . esc_attr($style) . '"';
?>
<div class="lt-sc-pdeal lt-sc-pdeal-full" data-id="<?php echo $_id;?>">
    <div class="lt-sc-p-info"<?php echo ($banner_src) ? ' style="' . esc_attr('background: url(\''.$banner_src.'\') center center no-repeat; ') : ''; echo 'height: '.$banner_height.';"';?>>
        <div class="lt-sc-block-content <?php echo ($text_align == 'right') ? 'right' : 'left';?>"<?php echo $style;?>>
            <div class="lt-sc-info">
                <h3>
                    <a href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title); ?>">
                        <?php echo $title; ?>
                    </a>
                </h3>
                <h4><?php echo '<span class="lt-text">'.esc_html__('From ', 'bleutheme').'</span>'.$product->get_categories();?></h4>
                <div class="lt-sc-p-price"><?php echo $product->get_price_html(); ?></div>
            </div>
            <?php if($time_sale):?>
                <div class="lt-sc-pdeal-countdown">
                    <span class="countdown" data-fomart="dhms" data-countdown="<?php echo esc_attr(date('M j Y H:i:s O',$time_sale)); ?>"></span>
                </div>
            <?php endif?>
        </div>
    </div>
</div>