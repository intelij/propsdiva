<?php
/*
Plugin Name: Vicomi Feelbacks
Plugin URI: http://vicomi.com/
Description: Feelbacks is a new voting engagement widget that allows users to express their feelings about your content
Author: Vicomi <support@vicomi.com>
Version: 1.37
Author URI: http://vicomi.com/
*/

require_once(dirname(__FILE__) . '/lib/vc-api.php');
define('VICOMI_FEELBACKS_V', '1.37');

// set unique id
if(!get_option('vicomi_feelbacks_uuid')) {
    update_option('vicomi_feelbacks_uuid', vcfGetGUID());
}

function vicomi_feelbacks_plugin_basename($file) {
    $file = dirname($file);

    // From WP2.5 wp-includes/plugin.php:plugin_basename()
    $file = str_replace('\\','/',$file); // sanitize for Win32 installs
    $file = preg_replace('|/+|','/', $file); // remove any duplicate slash
    $file = preg_replace('|^.*/' . PLUGINDIR . '/|','',$file); // get relative path from plugins dir

    if ( strstr($file, '/') === false ) {
        return $file;
    }

    $pieces = explode('/', $file);
    return !empty($pieces[count($pieces)-1]) ? $pieces[count($pieces)-1] : $pieces[count($pieces)-2];
}

if ( !defined('WP_CONTENT_URL') ) {
    define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
}
if ( !defined('PLUGINDIR') ) {
    define('PLUGINDIR', 'wp-content/plugins'); // back compat.
}

define('VICOMI_FEELBACKS_PLUGIN_URL', WP_CONTENT_URL . '/plugins/' . vicomi_feelbacks_plugin_basename(__FILE__));

// api ref
$vicomi_feelbacks_api = new VicomiAPI();

function vicomi_feelbacks_is_installed() {
    return get_option('vicomi_feelbacks_api_key');
}

/**************************************************
* register plugin state events
**************************************************/
function vicomi_feelbacks_activate() {
    if (!get_option('vicomi_feelbacks_api_key')) {
        $vicomi_feelbacks_api = new VicomiAPI();

        $site_name = get_option('blogname');

        if (!$site_name || $site_name == '') {
            $site_name = 'wordpress site';
        } else {
            $site_name = get_option('blogname');
        }

        $access_token = $vicomi_feelbacks_api->register_source
                            ($site_name, get_option('home')); 
         if(!$access_token) {
            deactivate_plugins(basename(__FILE__)); // Deactivate ourself
            wp_die("Activation failed please try again later");
         } else {                   
            update_option('vicomi_feelbacks_api_key',$access_token);
            update_option('vicomi_feelbacks_replace', 'all');
            update_option('vicomi_feelbacks_active','1');
        }
    }

    $vicomi_feelbacks_api = new VicomiAPI();
    $vicomi_feelbacks_api->plugin_activate
    (get_option('vicomi_feelbacks_api_key'), 'feelbacks', get_option('vicomi_feelbacks_uuid'));
}

function vicomi_feelbacks_deactivate() {
    $vicomi_feelbacks_api = new VicomiAPI();
    $vicomi_feelbacks_api->plugin_deactivate(get_option('vicomi_feelbacks_api_key'), 'feelbacks', get_option('vicomi_feelbacks_uuid'));
}

function vicomi_feelbacks_uninstall() {
    $vicomi_feelbacks_api = new VicomiAPI();
    $vicomi_feelbacks_api->plugin_uninstall(get_option('vicomi_feelbacks_api_key'), 'feelbacks', get_option('vicomi_feelbacks_uuid'));
}

register_activation_hook( __FILE__, 'vicomi_feelbacks_activate' );
register_deactivation_hook( __FILE__, 'vicomi_feelbacks_deactivate' );
register_uninstall_hook( __FILE__, 'vicomi_feelbacks_uninstall' );

function vicomi_feelbacks_can_replace() {
    global $id, $post;

    if (get_option('vicomi_feelbacks_active') === '0'){ return false; }

    $replace = get_option('vicomi_feelbacks_replace');

    if ( is_feed() )                       { return false; }
    if ( 'draft' == $post->post_status )   { return false; }
    if ( !get_option('vicomi_feelbacks_api_key') ) { return false; }
    else if ( 'all' == $replace )          { return true; }
}

/**************************************************
* add vicomi to settings menu
**************************************************/
function add_feelbacks_settings_menu(){
     add_options_page('Vicomi Feelbacks', 'Vicomi', 'manage_options', 'vicomi-feelbacks', 'vicomi_feelbacks_manage');
}

function vicomi_feelbacks_manage() {
    include_once(dirname(__FILE__) . '/manager.php');
}

add_action('admin_menu', 'add_feelbacks_settings_menu');

/**************************************************
* add action links to plgins page
**************************************************/
function vicomi_feelbacks_plugin_action_links($links, $file) {
    $plugin_file = basename(__FILE__);
    if (basename($file) == $plugin_file) {
        if (!vicomi_feelbacks_is_installed()) {
            $settings_link = '<a href="options-general.php?page=vicomi-feelbacks">Configure</a>';
        } else {
            $settings_link = '<a href="options-general.php?page=vicomi-feelbacks#adv">Settings</a>';    
        }
        array_unshift($links, $settings_link);
    }
    return $links;
}
add_filter('plugin_action_links', 'vicomi_feelbacks_plugin_action_links', 10, 2);

/**************************************************
* add feelbacks container and script to page
**************************************************/

function vicomi_feelbacks_template($content) {

    if ( !vicomi_feelbacks_is_installed() || !vicomi_feelbacks_can_replace() || !is_singular() ) {
        return $content;
    }


    $plugin_content = '<div id="vc-feelback-main" data-access-token="' . get_option('vicomi_feelbacks_api_key') . '"></div>' .
        // '<script type="text/javascript" src="http://127.0.0.1:9000/common/scripts/vicomi.js"></script>';
        '<script type="text/javascript" src="http://assets-prod.vicomi.com/vicomi.js"></script>';

    return $content . $plugin_content;
}

add_action('the_content', 'vicomi_feelbacks_template');


/**
 * JSON ENCODE for PHP < 5.2.0
 * Checks if json_encode is not available and defines json_encode
 * to use php_json_encode in its stead
 * Works on iteratable objects as well - stdClass is iteratable, so all WP objects are gonna be iteratable
 */
if(!function_exists('cf_json_encode')) {
    function cf_json_encode($data) {
// json_encode is sending an application/x-javascript header on Joyent servers
// for some unknown reason.
//         if(function_exists('json_encode')) { return json_encode($data); }
//         else { return cfjson_encode($data); }
        return cfjson_encode($data);
    }

    function cfjson_encode_string($str) {
        if(is_bool($str)) {
            return $str ? 'true' : 'false';
        }

        return str_replace(
            array(
                '"'
                , '/'
                , "\n"
                , "\r"
            )
            , array(
                '\"'
                , '\/'
                , '\n'
                , '\r'
            )
            , $str
        );
    }

    function cfjson_encode($arr) {
        $json_str = '';
        if (is_array($arr)) {
            $pure_array = true;
            $array_length = count($arr);
            for ( $i = 0; $i < $array_length ; $i++) {
                if (!isset($arr[$i])) {
                    $pure_array = false;
                    break;
                }
            }
            if ($pure_array) {
                $json_str = '[';
                $temp = array();
                for ($i=0; $i < $array_length; $i++) {
                    $temp[] = sprintf("%s", cfjson_encode($arr[$i]));
                }
                $json_str .= implode(',', $temp);
                $json_str .="]";
            }
            else {
                $json_str = '{';
                $temp = array();
                foreach ($arr as $key => $value) {
                    $temp[] = sprintf("\"%s\":%s", $key, cfjson_encode($value));
                }
                $json_str .= implode(',', $temp);
                $json_str .= '}';
            }
        }
        else if (is_object($arr)) {
            $json_str = '{';
            $temp = array();
            foreach ($arr as $k => $v) {
                $temp[] = '"'.$k.'":'.cfjson_encode($v);
            }
            $json_str .= implode(',', $temp);
            $json_str .= '}';
        }
        else if (is_string($arr)) {
            $json_str = '"'. cfjson_encode_string($arr) . '"';
        }
        else if (is_numeric($arr)) {
            $json_str = $arr;
        }
        else if (is_bool($arr)) {
            $json_str = $arr ? 'true' : 'false';
        }
        else {
            $json_str = '"'. cfjson_encode_string($arr) . '"';
        }
        return $json_str;
    }
}

// generate unique id
function vcfGetGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}

?>