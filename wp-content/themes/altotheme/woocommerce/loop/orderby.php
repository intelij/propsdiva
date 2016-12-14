<?php
/**
 * Show options for ordering
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $wp_query;

if ( 1 == $wp_query->found_posts || ! woocommerce_products_will_display() )
	return;
?>
<form class="woocommerce-ordering custom" method="get">
	<div class="select-wrapper"><select name="orderby" class="orderby">
		<?php
			$catalog_orderby = apply_filters('woocommerce_catalog_orderby', array(
				'menu_order' => esc_html__('Default Sorting', 'altotheme'),
				'popularity' => esc_html__('Popularity', 'altotheme'),
				'rating'     => esc_html__('Average rating', 'altotheme'),
				'date'       => esc_html__('Newness', 'altotheme'),
				'price'      => esc_html__('Price: low to high', 'altotheme'),
				'price-desc' => esc_html__('Price: high to low', 'altotheme')
			));

			if ( get_option( 'woocommerce_enable_review_rating' ) == 'no' ){
				unset( $catalog_orderby['rating'] );
            }
			foreach ( $catalog_orderby as $id => $name ){
				echo '<option value="' . esc_attr( $id ) . '" ' . selected( $orderby, $id, false ) . '>' . esc_attr( $name ) . '</option>';
            }
		?>
	</select></div>
	<?php
    if(!isset($_GET['action']) || $_GET['action'] != 'lt_products_page'){
		// Keep query string vars intact
		foreach ( $_GET as $key => $val ) {
			if ( 'orderby' == $key )
				continue;
			
			if (is_array($val)) {
				foreach($val as $innerVal) {
					echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $innerVal ) . '" />';
				}
			
			} else {
				echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
			}
		}
    }?>
</form>
