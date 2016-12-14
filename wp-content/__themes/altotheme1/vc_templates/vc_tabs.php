<?php
global $lee_tab_item;
$lee_tab_item = array();
$output = $title = $interval = $el_class = $align_tab = '';
extract(shortcode_atts(array(
    'title' => '',
    'interval' => 0,
    'el_class' => '',
    'align_tab' => ''
), $atts));
wpb_js_remove_wpautop($content);
$el_class = $this->getExtraClass($el_class);
$element = 'tabs-top';
if ('vc_tour' == $this->shortcode) $element = 'tabs-left';
if($align_tab == '') $align_tab = 'text-left';
?>

<div class="lt-tabs-content shortcode_tabgroup <?php echo esc_attr($element) . esc_attr($el_class);?>">
    <ul class="lt-tabs <?php echo esc_attr($align_tab);?>">
        <?php foreach($lee_tab_item as $key=>$tab){ ?>
            <li<?php echo ($key==0)?' class="lt-tab active first"':' class="lt-tab"'; ?>>
                <a href="javascript:void(0);" data-id="#lt-panel<?php echo esc_attr($tab['tab-id']); ?>"><h5><?php echo esc_attr($tab['title']); ?></h5></a>
                <span class="bery-hr small"></span>
            </li>
            <li class="separator">|</li>
        <?php } ?>
    </ul>

    <div class="lt-panels">
        <?php foreach($lee_tab_item as $key=>$tab){ ?>
            <div<?php echo ($key==0)?' style="display: block" class="first lt-panel active"':' style="display: none" class="lt-panel"';?> id="lt-panel<?php echo esc_attr($tab['tab-id']); ?>">
                <?php echo $tab['content']; ?>
            </div>
        <?php } ?>
    </div>
</div>