<?php
defined( 'ABSPATH' ) or die( 'You cannot access this script directly' );

// Don't resize images
function lt_filter_image_sizes( $sizes ) {
	return array();
}
// Hook importer into admin init
//add_action( 'wp_ajax_bery_import_demo_data', 'bery_importer' );
function bery_importer() {
	global $wpdb;
	if ( current_user_can( 'manage_options' ) ) {
		if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true); // we are loading importers

		if ( ! class_exists( 'WP_Importer' ) ) { // if main importer class doesn't exist

			$wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			include $wp_importer;
		}
		
		if ( ! class_exists('WP_Import') ) { // if WP importer doesn't exist
			$wp_import = LEE_FRAMEWORK_PLUGIN_PATH . '/admin/importer/wordpress-importer.php';
			require_once $wp_import;
		}
		
		if ( class_exists( 'WP_Importer' ) && class_exists( 'WP_Import' ) ) { // check for main import class and wp import class
			$theme_xml = LEE_FRAMEWORK_PLUGIN_PATH . '/admin/importer/data_import/alto.xml';
			$widgets_file = LEE_FRAMEWORK_PLUGIN_URL . '/admin/importer/data_import/widget_data.json';

			/* Import Woocommerce if WooCommerce Exists */
			if( class_exists('Woocommerce')) {
				$importer = new WP_Import();
				$importer->fetch_attachments = true;
				ob_start();
				$importer->import($theme_xml);
				ob_end_clean();

				/* Set imported menus to registered theme locations */
				$locations = get_theme_mod( 'nav_menu_locations' ); // registered menu locations in theme
				$menus = wp_get_nav_menus(); // registered menus

				if($menus) {
					foreach($menus as $menu) {
						if( $menu->name == 'Main Menu' ) {
							$locations['primary'] = $menu->term_id;
						} else if( $menu->name == 'Footer Menu' ) {
							$locations['footer_menu'] = $menu->term_id;
						} else if( $menu->name == 'My Account' ) {
							$locations['my_account'] = $menu->term_id;
						}
					} 
				}

				set_theme_mod( 'nav_menu_locations', $locations ); // set menus to locations


				// Set pages
				$woopages = array(
					'woocommerce_shop_page_id' => 'Shop',
					'woocommerce_cart_page_id' => 'Shopping cart',
					'woocommerce_checkout_page_id' => 'Checkout',
					'woocommerce_pay_page_id' => 'Checkout &#8594; Pay',
					'woocommerce_thanks_page_id' => 'Order Received',
					'woocommerce_myaccount_page_id' => 'My Account',
					'woocommerce_edit_address_page_id' => 'Edit My Address',
					'woocommerce_view_order_page_id' => 'View Order',
					'woocommerce_change_password_page_id' => 'Change Password',
					'woocommerce_logout_page_id' => 'Logout',
					'woocommerce_lost_password_page_id' => 'Lost Password'
				);
				foreach($woopages as $woo_page_name => $woo_page_title) {
					$woopage = get_page_by_title( $woo_page_title );
					if(isset( $woopage ) && $woopage->ID) {
						update_option($woo_page_name, $woopage->ID); // Front Page
					}
				}

                // Woo Image sizes
				$catalog = array(
					'width' 	=> '450',	// px
					'height'	=> '580',	// px
					'crop'		=> 1 		// true
				);
			 
				$single = array(
					'width' 	=> '575',	// px
					'height'	=> '675',	// px
					'crop'		=> 1 		// true
				);
			 
				$thumbnail = array(
					'width' 	=> '130',	// px
					'height'	=> '145',	// px
					'crop'		=> 1 		// false
				);

				
				update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
				update_option( 'shop_single_image_size', $single ); 		// Single product image
				update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs

				// Wordpress Media Setting
				update_option('thumbnail_size_w', 150);
				update_option('thumbnail_size_h', 150);
				update_option('medium_size_w', 280);
				update_option('medium_size_h', 280);
				update_option('large_size_w', 880);
				update_option('large_size_h', 350);
	 
				// We no longer need to install pages
				delete_option( '_wc_needs_pages' );
				delete_transient( '_wc_activation_redirect' );

				// Flush rules after install
				flush_rewrite_rules();
				
			}

			/* Update hompage reading */
			$home_id = get_page_by_title('Home');
			$blog_id = get_page_by_title('Blog');;
		    update_option( 'show_on_front', 'page' );
		    update_option( 'page_on_front', $home_id->ID );
		    update_option( 'page_for_posts', $blog_id->ID );

			
			/* Add data to widgets */
			$widgets_json = $widgets_file; // widgets data file
			$widgets_json = wp_remote_get( $widgets_json );
			$widget_data = $widgets_json['body'];
			$import_widgets = bery_import_widget_data( $widget_data );


			/* Import Ninja form */
			define('LEE_NINJA_FORM_ACTIVED', in_array('ninja-forms/ninja-forms.php', apply_filters('active_plugins', get_option('active_plugins'))));
			if (LEE_NINJA_FORM_ACTIVED){
				$ninja_file_nlt = file_get_contents(LEE_FRAMEWORK_PLUGIN_PATH . '/admin/importer/data_import/Newslettersignup');
				$ninja_file_contact = file_get_contents(LEE_FRAMEWORK_PLUGIN_PATH . '/admin/importer/data_import/Contactus');
				$ninja_file_promo = file_get_contents(LEE_FRAMEWORK_PLUGIN_PATH . '/admin/importer/data_import/NewsletterPromoPopup');

				ninja_forms_import_form( $ninja_file_nlt );
				ninja_forms_import_form( $ninja_file_contact );
				ninja_forms_import_form( $ninja_file_promo );
			}


			echo 'imported';

			exit;
		}
	}
}

add_action( 'wp_ajax_lt_import_contents', 'lt_import_contents' );
function lt_import_contents(){
	set_time_limit(0);
	$partial = $_POST['file'];
	$res = array();
	if ( current_user_can( 'manage_options' ) ) {
		if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true); // we are loading importers

		if ( ! class_exists( 'WP_Importer' ) ) { // if main importer class doesn't exist

			$wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			include $wp_importer;
		}
		
		if ( ! class_exists('WP_Import') ) { // if WP importer doesn't exist
			$wp_import = LEE_FRAMEWORK_PLUGIN_PATH . '/admin/importer/wordpress-importer.php';
			require_once $wp_import;
		}
		
		if ( class_exists( 'WP_Importer' ) && class_exists( 'WP_Import' ) ) {

			/* Import Woocommerce if WooCommerce Exists */
			if( class_exists('Woocommerce')) {
				$theme_xml = LEE_FRAMEWORK_PLUGIN_PATH . '/admin/importer/data_import/' . $partial . '.xml';
				if(is_file($theme_xml)){
					$importer = new WP_Import();
					
					$importer->fetch_attachments = true;
					ob_start();
					$importer->import($theme_xml);
					
					$res['mess'] = ob_get_clean();
					$res['end'] = 1;
				}else{
					$res['mess'] = 'file: ' . LEE_FRAMEWORK_PLUGIN_PATH . '/admin/importer/data_import/' . $partial . '.xml is not exists';
					$res['end'] = 1;
				}
				
				die(json_encode($res));
			}
		}
	}
	
	$res['mess'] = '';
	$res['end'] = 0;
	
	die(json_encode($res));
}

add_action( 'wp_ajax_lt_import_end_importer', 'lt_import_end_importer' );
function lt_import_end_importer() {
	global $wpdb;
	if ( current_user_can( 'manage_options' ) ) {
		if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true); // we are loading importers

		if ( ! class_exists( 'WP_Importer' ) ) { // if main importer class doesn't exist

			$wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			include $wp_importer;
		}
		
		if ( ! class_exists('WP_Import') ) { // if WP importer doesn't exist
			$wp_import = LEE_FRAMEWORK_PLUGIN_PATH . '/admin/importer/wordpress-importer.php';
			require_once $wp_import;
		}
		
		if ( class_exists( 'WP_Importer' ) && class_exists( 'WP_Import' ) ) { // check for main import class and wp import class
			
			/* Import Woocommerce if WooCommerce Exists */
			if( class_exists('Woocommerce')) {

				/* Set imported menus to registered theme locations */
				$locations = get_theme_mod( 'nav_menu_locations' ); // registered menu locations in theme
				$menus = wp_get_nav_menus(); // registered menus

				if($menus) {
					foreach($menus as $menu) {
						if( $menu->name == 'Main Menu' ) {
							$locations['primary'] = $menu->term_id;
						} else if( $menu->name == 'Footer Menu' ) {
							$locations['footer_menu'] = $menu->term_id;
						} else if( $menu->name == 'My Account' ) {
							$locations['my_account'] = $menu->term_id;
						}
					} 
				}

				set_theme_mod( 'nav_menu_locations', $locations ); // set menus to locations


				// Set pages
				$woopages = array(
					'woocommerce_shop_page_id' => 'Shop',
					'woocommerce_cart_page_id' => 'Shopping cart',
					'woocommerce_checkout_page_id' => 'Checkout',
					'woocommerce_pay_page_id' => 'Checkout &#8594; Pay',
					'woocommerce_thanks_page_id' => 'Order Received',
					'woocommerce_myaccount_page_id' => 'My Account',
					'woocommerce_edit_address_page_id' => 'Edit My Address',
					'woocommerce_view_order_page_id' => 'View Order',
					'woocommerce_change_password_page_id' => 'Change Password',
					'woocommerce_logout_page_id' => 'Logout',
					'woocommerce_lost_password_page_id' => 'Lost Password'
				);
				foreach($woopages as $woo_page_name => $woo_page_title) {
					$woopage = get_page_by_title( $woo_page_title );
					if(isset( $woopage ) && $woopage->ID) {
						update_option($woo_page_name, $woopage->ID); // Front Page
					}
				}

                // Woo Image sizes
				$catalog = array(
					'width' 	=> '450',	// px
					'height'	=> '580',	// px
					'crop'		=> 1 		// true
				);
			 
				$single = array(
					'width' 	=> '575',	// px
					'height'	=> '675',	// px
					'crop'		=> 1 		// true
				);
			 
				$thumbnail = array(
					'width' 	=> '130',	// px
					'height'	=> '145',	// px
					'crop'		=> 1 		// false
				);

				
				update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
				update_option( 'shop_single_image_size', $single ); 		// Single product image
				update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs

				// Wordpress Media Setting
				update_option('thumbnail_size_w', 150);
				update_option('thumbnail_size_h', 150);
				update_option('medium_size_w', 280);
				update_option('medium_size_h', 280);
				update_option('large_size_w', 880);
				update_option('large_size_h', 350);

	 
				// We no longer need to install pages
				delete_option( '_wc_needs_pages' );
				delete_transient( '_wc_activation_redirect' );

				// Flush rules after install
				flush_rewrite_rules();
				
			}

			/* Update hompage reading */
			$home_id = get_page_by_title('Home');
			$blog_id = get_page_by_title('Blog');;
		    update_option( 'show_on_front', 'page' );
		    update_option( 'page_on_front', $home_id->ID );
		    update_option( 'page_for_posts', $blog_id->ID );

			/* Add data to widgets */
			$widgets_file = LEE_FRAMEWORK_PLUGIN_URL . '/admin/importer/data_import/widget_data.json';
			$widgets_json = wp_remote_get( $widgets_file );
			$widget_data = $widgets_json['body'];
			$import_widgets = bery_import_widget_data( $widget_data );

			/* Import Ninja form */
			define('LEE_NINJA_FORM_ACTIVED', in_array('ninja-forms/ninja-forms.php', apply_filters('active_plugins', get_option('active_plugins'))));
			if (LEE_NINJA_FORM_ACTIVED){
				$ninja_file_nlt = file_get_contents(LEE_FRAMEWORK_PLUGIN_PATH . '/admin/importer/data_import/Newslettersignup');
				$ninja_file_contact = file_get_contents(LEE_FRAMEWORK_PLUGIN_PATH . '/admin/importer/data_import/Contactus');
				$ninja_file_promo = file_get_contents(LEE_FRAMEWORK_PLUGIN_PATH . '/admin/importer/data_import/NewsletterPromoPopup');

				ninja_forms_import_form( $ninja_file_nlt );
				ninja_forms_import_form( $ninja_file_contact );
				ninja_forms_import_form( $ninja_file_promo );
			}

			echo 'imported';

			exit;
		}
	}
}


// Parsing Widgets Function
// Thanks to http://wordpress.org/plugins/widget-settings-importexport/
function bery_import_widget_data( $widget_data ) {
	$json_data = $widget_data;
	$json_data = json_decode( $json_data, true );

	$sidebar_data = $json_data[0];
	$widget_data = $json_data[1];

	foreach ( $widget_data as $widget_data_title => $widget_data_value ) {
		$widgets[ $widget_data_title ] = '';
		foreach( $widget_data_value as $widget_data_key => $widget_data_array ) {
			if( is_int( $widget_data_key ) ) {
				$widgets[$widget_data_title][$widget_data_key] = 'on';
			}
		}
	}
	unset($widgets[""]);

	foreach ( $sidebar_data as $title => $sidebar ) {
		$count = count( $sidebar );
		for ( $i = 0; $i < $count; $i++ ) {
			$widget = array( );
			$widget['type'] = trim( substr( $sidebar[$i], 0, strrpos( $sidebar[$i], '-' ) ) );
			$widget['type-index'] = trim( substr( $sidebar[$i], strrpos( $sidebar[$i], '-' ) + 1 ) );
			if ( !isset( $widgets[$widget['type']][$widget['type-index']] ) ) {
				unset( $sidebar_data[$title][$i] );
			}
		}
		$sidebar_data[$title] = array_values( $sidebar_data[$title] );
	}

	foreach ( $widgets as $widget_title => $widget_value ) {
		foreach ( $widget_value as $widget_key => $widget_value ) {
			$widgets[$widget_title][$widget_key] = $widget_data[$widget_title][$widget_key];
		}
	}

	$sidebar_data = array( array_filter( $sidebar_data ), $widgets );

	bery_parse_import_data( $sidebar_data );
}

function bery_parse_import_data( $import_array ) {
	global $wp_registered_sidebars;
	$sidebars_data = $import_array[0];
	$widget_data = $import_array[1];
	$current_sidebars = get_option( 'sidebars_widgets' );
	$new_widgets = array( );

	foreach ( $sidebars_data as $import_sidebar => $import_widgets ) :

		foreach ( $import_widgets as $import_widget ) :
			//if the sidebar exists
			if ( isset( $wp_registered_sidebars[$import_sidebar] ) ) :
				$title = trim( substr( $import_widget, 0, strrpos( $import_widget, '-' ) ) );
				$index = trim( substr( $import_widget, strrpos( $import_widget, '-' ) + 1 ) );
				$current_widget_data = get_option( 'widget_' . $title );
				$new_widget_name = bery_get_new_widget_name( $title, $index );
				$new_index = trim( substr( $new_widget_name, strrpos( $new_widget_name, '-' ) + 1 ) );

				if ( !empty( $new_widgets[ $title ] ) && is_array( $new_widgets[$title] ) ) {
					while ( array_key_exists( $new_index, $new_widgets[$title] ) ) {
						$new_index++;
					}
				}
				$current_sidebars[$import_sidebar][] = $title . '-' . $new_index;
				if ( array_key_exists( $title, $new_widgets ) ) {
					$new_widgets[$title][$new_index] = $widget_data[$title][$index];
					$multiwidget = $new_widgets[$title]['_multiwidget'];
					unset( $new_widgets[$title]['_multiwidget'] );
					$new_widgets[$title]['_multiwidget'] = $multiwidget;
				} else {
					$current_widget_data[$new_index] = $widget_data[$title][$index];
					$current_multiwidget = isset($current_widget_data['_multiwidget']) ? $current_widget_data['_multiwidget'] : false;
					$new_multiwidget = isset($widget_data[$title]['_multiwidget']) ? $widget_data[$title]['_multiwidget'] : false;
					$multiwidget = ($current_multiwidget != $new_multiwidget) ? $current_multiwidget : 1;
					unset( $current_widget_data['_multiwidget'] );
					$current_widget_data['_multiwidget'] = $multiwidget;
					$new_widgets[$title] = $current_widget_data;
				}

			endif;
		endforeach;
	endforeach;

	if ( isset( $new_widgets ) && isset( $current_sidebars ) ) {
		update_option( 'sidebars_widgets', $current_sidebars );

		foreach ( $new_widgets as $title => $content )
			update_option( 'widget_' . $title, $content );

		return true;
	}

	return false;
}

function bery_get_new_widget_name( $widget_name, $widget_index ) {
	$current_sidebars = get_option( 'sidebars_widgets' );
	$all_widget_array = array( );
	foreach ( $current_sidebars as $sidebar => $widgets ) {
		if ( !empty( $widgets ) && is_array( $widgets ) && $sidebar != 'wp_inactive_widgets' ) {
			foreach ( $widgets as $widget ) {
				$all_widget_array[] = $widget;
			}
		}
	}
	while ( in_array( $widget_name . '-' . $widget_index, $all_widget_array ) ) {
		$widget_index++;
	}
	$new_widget_name = $widget_name . '-' . $widget_index;
	return $new_widget_name;
}


// Rename sidebar
function lt_name_to_class($name){
	$class = str_replace(array(' ',',','.','"',"'",'/',"\\",'+','=',')','(','*','&','^','%','$','#','@','!','~','`','<','>','?','[',']','{','}','|',':',),'',$name);
	return $class;
}

function lt_get_import_files( $directory, $filetype ) {
	$phpversion = phpversion();
	$files = array();

	// Check if the php version allows for recursive iterators
	if ( version_compare( $phpversion, '5.2.11', '>' ) ) {
		if ( $filetype != '*' )  {
			$filetype = '/^.*\.' . $filetype . '$/';
		} else {
			$filetype = '/.+\.[^.]+$/';
		}
		$directory_iterator = new RecursiveDirectoryIterator( $directory );
		$recusive_iterator = new RecursiveIteratorIterator( $directory_iterator );
		$regex_iterator = new RegexIterator( $recusive_iterator, $filetype );

		foreach( $regex_iterator as $file ) {
			$files[] = $file->getPathname();
		}
	// Fallback to glob() for older php versions
	} else {
		if ( $filetype != '*' )  {
			$filetype = '*.' . $filetype;
		}

		foreach( glob( $directory . $filetype ) as $filename ) {
			$filename = basename( $filename );
			$files[] = $directory . $filename;
		}
	}

	return $files;
}
