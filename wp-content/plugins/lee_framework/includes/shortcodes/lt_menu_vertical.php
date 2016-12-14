<?php
function lt_sc_menu_vertical( $atts, $content = null ){
	extract( shortcode_atts( array(
		'title' => '',
		'menu' => ''
	), $atts ) );
	if($menu){
		ob_start();?>
        <div class="vertical-menu">
            <?php if($title){?>
                <div class="title-inner">
                    <h5 class="section-title">
                        <span><?php echo esc_attr($title);?></span>
                    </h5>
                </div>
            <?php }?>
            <div class="vertical-menu-container">
                <ul id="vertical-menu-wrapper">
                    <?php  
                        wp_nav_menu(array(
                            'menu' 			=> $menu,
                            'container'     => false,
                            'items_wrap'    => '%3$s',
                            'depth'         => 3,
                            'walker'        => new LT_NavDropdown()
                        ));
                    ?>
                </ul>
            </div>
        </div>
		<?php 
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
}
add_shortcode('lt_menu_vertical', 'lt_sc_menu_vertical');