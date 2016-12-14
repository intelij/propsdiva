<?php
/**
 * Plugin Name: LEE Framework
 * Plugin URI: http://leetheme.com
 * Description: Framework
 * Version: 1.1
 * Author: Derry Vu
 * Author URI: derryvu@gmail.com
 * License: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: leeframework
 * Domain Path: /languages
 */
//echo 'lee_framework.php';

define('LEE_FRAMEWORK_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
define('LEE_FRAMEWORK_PLUGIN_URL',  plugin_dir_url( __FILE__ ));

define('LEE_VISUAL_COMPOSER_ACTIVED', in_array('js_composer/js_composer.php', apply_filters('active_plugins', get_option('active_plugins'))));

// Back-end
if(is_admin()){
    foreach (glob(LEE_FRAMEWORK_PLUGIN_PATH . '/admin/*.php') as $file) {
        include_once $file;
    }
}

// Includes
foreach (glob(LEE_FRAMEWORK_PLUGIN_PATH . '/includes/*.php') as $file) {
    include_once $file;
}

// Include post-type
foreach (glob(LEE_FRAMEWORK_PLUGIN_PATH . '/post-type/*.php') as $file) {
    include_once $file;
}

add_action('plugins_loaded', 'lee_framework_load_textdomain');
function lee_framework_load_textdomain() {
    load_plugin_textdomain( 'lee_framework', false, LEE_FRAMEWORK_PLUGIN_PATH . '/languages/' );
}


include_once 'plugin-updates/plugin-update-checker.php';

/* Plugin update */
class lee_update_plugin{
	public static $_instance;

	private $check_url, $theme_option;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct(){
		
		$this->check_url 	= 'http://leetheme.com/server_update/alto/info.json';

		
		$this->update_plugin();

	}

	private function update_plugin(){
		$updatechecker = PucFactory::buildUpdateChecker(
			$this->check_url,
			__FILE__
		);
		$updatechecker->addQueryArgFilter( array( $this, 'get_secretkey' ) );
	}

	public function get_secretkey($query){
		$query['secret'] = 'foo';
		return $query;
	}

}

new lee_update_plugin();