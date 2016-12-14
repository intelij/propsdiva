<?php
/**
 * The template for displaying search forms in leetheme
 *
 * @package leetheme
 */

$search_param = array(
    'name'  => 'post_type',
    'value' => 'product'
);

$_id = rand();
?>

<div class="search-wrapper lt-ajaxsearchform-container <?php echo $_id;?>_container">
    <form method="get" class="lt-ajaxsearchform" action="<?php echo esc_url(home_url('/')) ?>">
        <div class="search-control-group control-group">
            <label class="sr-only screen-reader-text">
                <?php esc_html_e('Search here', 'altotheme'); ?>
            </label>
            <input id="lt-input-<?php echo esc_attr($_id);?>" type="text" class="search-field search-input live-search-input" value="<?php echo get_search_query();?>" name="s" placeholder="<?php esc_html_e('Search here', 'altotheme'); ?>" />
            <input type="submit" name="page" value="<?php esc_html_e('search', 'altotheme'); ?>" style="display: none" />
            <input type="hidden" class="search-param" name="<?php echo $search_param['name'];?>" value="<?php echo $search_param['value'];?>" />
        </div>
    </form>
</div>