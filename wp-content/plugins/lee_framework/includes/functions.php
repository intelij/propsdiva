<?php

// **********************************************************************// 
// ! Fix shortcode content
// **********************************************************************//
if(!function_exists('lt_fixShortcode')){
    function lt_fixShortcode($content){
        $fix = array (
            '&nbsp;' => '',
            '<p>' => '',
            '</p>' => '',
            '<p></p>' => '',
        );
        $content = strtr($content, $fix);
        $content = wpautop( preg_replace( '/<\/?p\>/', "\n", $content ) . "\n" );

        return do_shortcode(shortcode_unautop($content));
    }
}

add_action('wp_ajax_get_shortcode', 'lt_get_shortcode');
add_action('wp_ajax_nopriv_get_shortcode', 'lt_get_shortcode');
function lt_get_shortcode() {
    $content = $_POST["content"];
    print do_shortcode($content);
    die();
}

/*==========================================================================
 WooCommerce - Function get Query
==========================================================================*/
function lt_woocommerce_query($type, $post_per_page=-1, $cat='', $paged=''){
    $args = lt_woocommerce_query_args($type, $post_per_page, $cat, $paged);
    return new WP_Query($args);
}

function lt_woocommerce_query_args($type, $post_per_page=-1, $cat='', $paged=''){
    global $woocommerce;
    if(!$woocommerce) return array();
    
    remove_filter( 'posts_clauses', array( $woocommerce->query, 'order_by_popularity_post_clauses' ) );
    remove_filter( 'posts_clauses', array( $woocommerce->query, 'order_by_rating_post_clauses' ) );
    if($paged == '') {
        $paged = ($paged = get_query_var('paged')) ? $paged : 1;
    }
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $post_per_page,
        'post_status' => 'publish',
        'paged' => $paged
    );
    switch ($type) {
        case 'best_selling':
            $args['meta_key'] = 'total_sales';
            $args['orderby'] = 'meta_value_num';
            $args['ignore_sticky_posts'] = 1;
            $args['meta_query'] = array();
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            break;
        case 'featured_product':
            $args['ignore_sticky_posts'] = 1;
            $args['meta_query'] = array();
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = array(
                'key' => '_featured',
                'value' => 'yes'
            );
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            break;
        case 'top_rate':
            add_filter('posts_clauses', array($woocommerce->query, 'order_by_rating_post_clauses'));
            $args['meta_query'] = array();
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            break;
        case 'recent_product':
            $args['meta_query'] = array();
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            break;
        case 'on_sale':
            $args['meta_query'] = array();
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            $args['post__in'] = wc_get_product_ids_on_sale();
            break;
        case 'recent_review':
            if($post_per_page == -1) $_limit = 4;
            else $_limit = $post_per_page;
            global $wpdb;
            $query = "SELECT c.comment_post_ID FROM {$wpdb->prefix}posts p, {$wpdb->prefix}comments c WHERE p.ID = c.comment_post_ID AND c.comment_approved > 0 AND p.post_type = %s AND p.post_status = %s AND p.comment_count > %s ORDER BY c.comment_date ASC LIMIT 0, ". $_limit;
            $results = $wpdb->get_results($wpdb->prepare($query, 'product', 'publish', '0', OBJECT));
            $_pids = array();
            foreach ($results as $re) {
                $_pids[] = $re->comment_post_ID;
            }

            $args['meta_query'] = array();
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            $args['post__in'] = $_pids;
            break;
        case 'deals':
            $args['meta_query'] = array();
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            $args['meta_query'][] = array(
                'key' => '_sale_price_dates_to',
                'value' => '0',
                'compare' => '>'
            );
            $args['post__in'] = wc_get_product_ids_on_sale();
            break;
    }

    if(is_numeric($cat) && $cat){
        $args['tax_query'] = array(
            array(
                'taxonomy'  => 'product_cat',
                'field'     => 'id', 
                'terms'     => array($cat)
            )
        );
    }
    
    // Find by slug
    elseif($cat != ''){
        $args['tax_query'] = array(
            array(
                'taxonomy'  => 'product_cat',
                'field'     => 'slug', 
                'terms'     => $cat
            )
        );
    }
    
    return $args;
}


// **********************************************************************// 
// ! Twitter API functions
// **********************************************************************// 
function lt_capture_tweets($consumer_key,$consumer_secret,$user_token,$user_secret,$user, $count) {
    $connection = new TwitterOAuth($consumer_key, $consumer_secret, $user_token, $user_secret);
    $content = $connection->get("statuses/user_timeline", array(
        'screen_name' => $user,
        'count' => $count
    ));
    
    return json_encode($content);
}

function lt_tweet_linkify($tweet) {
    $tweet = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $tweet);
    $tweet = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $tweet);
    $tweet = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $tweet);
    $tweet = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $tweet);
    return $tweet;
}

function lt_store_tweets($file, $tweets) {
    ob_start(); // turn on the output buffering 
    $fo = fopen($file, 'w'); // opens for writing only or will create if it's not there
    if (!$fo) return lt_print_tweet_error(error_get_last());
    $fr = fwrite($fo, $tweets); // writes to the file what was grabbed from the previous function
    if (!$fr) return lt_print_tweet_error(error_get_last());
    fclose($fo); // closes
    ob_end_flush(); // finishes and flushes the output buffer; 
}

function lt_pick_tweets($file) {
    ob_start(); // turn on the output buffering 
    $fo = fopen($file, 'r'); // opens for reading only 
    if (!$fo) return lt_print_tweet_error(error_get_last());
    $fr = fread($fo, filesize($file));
    if (!$fr) return lt_print_tweet_error(error_get_last());
    fclose($fo);
    ob_end_flush();
    return $fr;
}

function lt_print_tweet_error($errorArray) {
    return '<p class="eth-error">Error: ' . $errorArray['message'] . 'in ' . $errorArray['file'] . 'on line ' . $errorArray['line'] . '</p>';
}

function lt_twitter_cache_enabled(){
    return true;
}

function lt_print_tweets($consumer_key,$consumer_secret,$user_token,$user_secret,$user, $count, $cachetime=50) {
    if(lt_twitter_cache_enabled()){
        //setting the location to cache file
        $cachefile = get_template_directory() . '/includes/cache/twitterCache.json'; 
        
        // the file exitsts but is outdated, update the cache file
        if (file_exists($cachefile) && ( time() - $cachetime > filemtime($cachefile)) && filesize($cachefile) > 0) {
            //capturing fresh tweets
            $tweets = lt_capture_tweets($consumer_key,$consumer_secret,$user_token,$user_secret,$user, $count);
            $tweets_decoded = json_decode($tweets, true);
            //if get error while loading fresh tweets - load outdated file
            if(isset($tweets_decoded['error'])) {
                $tweets = lt_pick_tweets($cachefile);
            }
            //else store fresh tweets to cache
            else
                lt_store_tweets($cachefile, $tweets);
        }
        //file doesn't exist or is empty, create new cache file
        elseif (!file_exists($cachefile) || filesize($cachefile) == 0) {
            $tweets = lt_capture_tweets($consumer_key,$consumer_secret,$user_token,$user_secret,$user, $count);
            $tweets_decoded = json_decode($tweets, true);
            //if request fails, and there is no old cache file - print error
            if(isset($tweets_decoded['error']))
                return 'Error: ' . $tweets_decoded['error'];
            //make new cache file with request results
            else
                lt_store_tweets($cachefile, $tweets);            
        }
        //file exists and is fresh
        //load the cache file
        else { 
           $tweets = lt_pick_tweets($cachefile);
        }
    } else{
       $tweets = lt_capture_tweets($consumer_key,$consumer_secret,$user_token,$user_secret,$user, $count);
    }

    $tweets = json_decode($tweets, true);
    $html = '<ul class="twitter-list">';
    
    foreach ($tweets as $tweet) {
        $html .= '<li class="lastItem firstItem"><div class="media"><i class="pull-left fa fa-twitter"></i><div class="media-body">' . $tweet['text'] . '</div></div></li>';
    }
    $html .= '</ul>';
    $html = lt_tweet_linkify($html);
    return $html;
}

//convert dates to readable format  
if (!function_exists('lt_relative_time')) {
    function lt_relative_time($a) {
        //get current timestampt
        $b = strtotime('now'); 
        //get timestamp when tweet created
        $c = strtotime($a);
        //get difference
        $d = $b - $c;
        //calculate different time values
        $minute = 60;
        $hour = $minute * 60;
        $day = $hour * 24;
        $week = $day * 7;
            
        if(is_numeric($d) && $d > 0) {
            //if less then 3 seconds
            if($d < 3) return __('right now','lee_framework');
            //if less then minute
            if($d < $minute) return floor($d) . __(' seconds ago','lee_framework');
            //if less then 2 minutes
            if($d < $minute * 2) return __('about 1 minute ago','lee_framework');
            //if less then hour
            if($d < $hour) return floor($d / $minute) . __(' minutes ago','lee_framework');
            //if less then 2 hours
            if($d < $hour * 2) return __('about 1 hour ago','lee_framework');
            //if less then day
            if($d < $day) return floor($d / $hour) . __(' hours ago','lee_framework');
            //if more then day, but less then 2 days
            if($d > $day && $d < $day * 2) return __('yesterday','lee_framework');
            //if less then year
            if($d < $day * 365) return floor($d / $day) . __(' days ago','lee_framework');
            //else return more than a year
            return __('over a year ago','lee_framework');
        }
    }   
}