<?php
/**
 * Single Product tabs / and sections
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );
global $lt_opt;

if (!empty( $tabs ) )  : ?>

	<div class="lt-tabs-content woocommerce-tabs">
		<ul class="lt-tabs">
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<li class="<?php echo $key ?>_tab lt-tab<?php echo ($key=='description')? ' active':'';?> first">
					<a href="javascript:void(0);" data-id="#lt-tab-<?php echo $key ?>">
						<h5><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ?></h5>
						<span class="bery-hr medium"></span>
					</a>
				</li>
				<li class="separator">|</li>
			<?php endforeach; ?>

			<?php
			if($lt_opt['tab_title']){
				?> 
				<li class="additional-tab lt-tab">
					<a href="javascript:void(0);" data-id="#lt-tab-additional">
						<h5><?php echo esc_attr($lt_opt['tab_title'])?></h5>
						<span class="bery-hr medium"></span>
					</a>
				</li>
				<li class="separator">|</li>
			<?php } ?>
		</ul>
		<div class="lt-panels">
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<div class="lt-panel entry-content<?php echo ($key=='description')? ' active':'';?>" id="lt-tab-<?php echo $key ?>">
					<?php call_user_func( $tab['callback'], $key, $tab ) ?>
				</div>
			<?php endforeach; ?>

			<?php 
				if($lt_opt['tab_title']){ ?>
				<div class="lt-panel entry-content" id="lt-tab-additional">
					<?php echo do_shortcode($lt_opt['tab_content']);?>
				</div>	
			<?php } ?>
		</div>
	</div>

<?php endif;?>