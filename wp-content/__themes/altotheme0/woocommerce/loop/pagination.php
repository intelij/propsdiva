<?php
/**
 * Pagination - Show numbered pagination for catalog pages.
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $lt_opt, $wp_query;

if ( $wp_query->max_num_pages <= 1 )
    return;

if(!isset($paged) || !$paged){
    $paged = max( 1, get_query_var('paged') );
}
/* <!-- PAGINATION --> */
?>

<div class="large-12 columns">
<?php
if (isset($lt_opt['pagination_style'])) {
    if ($lt_opt['pagination_style'] == 'style-1') { ?>
        <div class="lt-pagination clearfix style-1">
            <div class="page-sumary">
                <ul>
                    <li><?php do_action( 'lt_shop_category_count', 'woocommerce_result_count', 20 ); ?></li>
                </ul>
            </div>
            <div class="page-number">
                <?php
                echo lt_get_pagination_ajax(
                    $wp_query->max_num_pages, // Total
                    $paged, // Current
                    'list', // Type display
                    // Prev text
                    '<span class="pe7-icon pe-7s-angle-left"></span>' . esc_html__('PREV', 'altotheme'),
                    // Next text
                    esc_html__('NEXT', 'altotheme') . '<span class="pe7-icon pe-7s-angle-right"></span>',
                    3, // end_size
                    3  // mid_size
                );
                /*echo paginate_links( apply_filters( 'woocommerce_pagination_args', array(
                    'base' 	=> str_replace( 999999999, '%#%', get_pagenum_link( 999999999 ) ),
                    'format' 	=> '',
                    'current' 	=> max( 1, get_query_var('paged') ),
                    'total' 	=> $wp_query->max_num_pages,
                    'prev_text' => '<span class="pe7-icon pe-7s-angle-left"></span>' . esc_html__('PRE', 'altotheme'),
                    'next_text' => esc_html__('NEXT', 'altotheme') . '<span class="pe7-icon pe-7s-angle-right"></span>',
                    'type'	=> 'list',
                    'end_size'	=> 3,
                    'mid_size'	=> 3,
                ) ) );*/
                ?>
            </div>
        </div>
    <?php } elseif ($lt_opt['pagination_style'] == 'style-2') {?>
        <div class="lt-pagination style-2">
            <div class="page-number">
                <?php
                echo lt_get_pagination_ajax(
                    $wp_query->max_num_pages,
                    $paged,
                    'list',
                    '<span class="fa fa-caret-left"></span>',
                    '<span class="fa fa-caret-right"></span>',
                    3,
                    3
                );
                /*echo paginate_links( apply_filters( 'woocommerce_pagination_args', array(
                    'base' 	=> str_replace( 999999999, '%#%', get_pagenum_link( 999999999 ) ),
                    'format' 	=> '',
                    'current' 	=> max( 1, get_query_var('paged') ),
                    'total' 	=> $wp_query->max_num_pages,
                    'prev_text' => '<span class="fa fa-caret-left"></span>',
                    'next_text' => '<span class="fa fa-caret-right"></span>',
                    'type'	=> 'list',
                    'end_size'	=> 3,
                    'mid_size'	=> 3,
                ) ) );*/
                ?>
            </div>
        </div>
    <?php }?>
<?php }?>
</div>
<?php /*!-- end PAGINATION -- */?>