<?php
function lt_sc_search() {
    return get_product_search_form();
}
add_shortcode("search", "lt_sc_search");

