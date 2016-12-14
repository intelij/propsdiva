<?php
class es_af_registerhook
{
	public static function es_af_activation()
	{
		global $wpdb;
		$es_af_pluginversion = "";
		$es_af_tableexists = "YES";
		$es_af_pluginversion = get_option("es_af_pluginversion");

		$es_af_dbtable = $wpdb->get_var("show tables like '". $wpdb->prefix . ES_AF_TABLE . "'");
		
		if($es_af_dbtable <> "")
		{
			if( strtoupper($es_af_dbtable) != strtoupper($wpdb->prefix . ES_AF_TABLE) )
			{
				$es_af_tableexists = "NO";
			}
		}
		else
		{
			$es_af_tableexists = "NO";
		}

		if(($es_af_tableexists == "NO") || ($es_af_pluginversion != ES_AF_DBVERSION)) 
		{
			$sSql = "CREATE TABLE ". $wpdb->prefix . ES_AF_TABLE . " (
				 es_af_id mediumint(9) NOT NULL AUTO_INCREMENT,
				 es_af_title VARCHAR(1024) DEFAULT 'Advanced Form 1' NOT NULL,
				 es_af_desc VARCHAR(1024) DEFAULT 'Welcome to Email Subscribers Advanced Form' NOT NULL,
				 es_af_name VARCHAR(20) DEFAULT 'YES' NOT NULL,
				 es_af_name_mand VARCHAR(20) DEFAULT 'YES' NOT NULL,	 
				 es_af_email VARCHAR(20) DEFAULT 'YES' NOT NULL,
				 es_af_email_mand VARCHAR(20) DEFAULT 'YES' NOT NULL,
				 es_af_group VARCHAR(20) DEFAULT 'YES' NOT NULL,
				 es_af_group_mand VARCHAR(20) DEFAULT 'YES' NOT NULL,
				 es_af_group_list VARCHAR(1024) DEFAULT 'Public' NOT NULL,
				 es_af_plugin VARCHAR(20) DEFAULT 'es-af' NOT NULL,
				 UNIQUE KEY es_af_id (es_af_id)
			  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sSql );
			
			if($es_af_pluginversion == "") {
				add_option('es_af_pluginversion', "1.0");
			} else {
				update_option( "es_af_pluginversion", ES_AF_DBVERSION );
			}
			
			if($es_af_tableexists == "NO")
			{
				$welcome_text = "Advanced Form 1";		
				$rows_affected = $wpdb->insert( $wpdb->prefix . ES_AF_TABLE , array( 'es_af_title' => $welcome_text) );
			}
		}
	}
	
	public static function es_af_deactivation() {
		// No action required
	}
	
	public static function es_af_adminmenu() {
		if (is_admin() && is_plugin_active('email-subscribers/email-subscribers.php') )	{
			add_submenu_page('email-subscribers', __( 'Advanced Form', ES_AF_TDOMAIN ), 
				__( 'Advanced Form', ES_AF_TDOMAIN ), 'manage_options', 'es-af-advancedform', array( 'es_af_intermediate', 'es_af_advancedform' ));
		} else {
			?><div class="notice notice-error is-dismissible">
	        <p><?php _e( '<strong>Email Subscribers</strong> plugin should be installed & activated before activating <strong>Email Subscribers Advanced Form</strong>', ES_AF_TDOMAIN ); ?></p>
	    	</div>
	    	<?php
		}	
	}

	public static function es_af_load_scripts() {
		if( !empty( $_GET['page'] ) ) {
			if( $_GET['page'] == 'es-af-advancedform' ) {

				wp_register_script( 'esaf-settings', ES_AF_URL . 'page/setting.js' );
				wp_enqueue_script( 'esaf-settings', ES_AF_URL . 'page/setting.js' );
				$esaf_select_params = array(
					'esaf_form_title'				=> _x( 'Enter title for your form.', 'settings-enhanced-select', ES_AF_TDOMAIN ),
					'esaf_settings_delete_record'	=> _x( 'Do you want to delete this record?', 'settings-enhanced-select', ES_AF_TDOMAIN )
				);
				wp_localize_script( 'esaf-settings', 'esaf_settings_notices', $esaf_select_params );
			}
		}
	}

	public static function es_af_widget_loading() {
		register_widget( 'es_af_widget_register' );
	}
}

class es_af_form_submuit
{
	public static function es_af_preparation($es_af_name = "", $es_af_email = "", $es_af_group = array())
	{
		$sts = "";
		$data = es_cls_settings::es_setting_select(1);
		$form = array(
			'es_email_name' => '',
			'es_email_status' => '',
			'es_email_group' => '',
			'es_email_mail' => ''
		);
		$email_saved_single_opt_in = 0;
		$email_saved_double_opt_in = 0;
		$email_already_exists = 0;
		
		$es_af_group_count = count($es_af_group);
		if($es_af_group_count > 0)
		{
			for($i=0; $i<$es_af_group_count; $i++)
			{
				$form['es_email_name'] = $es_af_name;
				$form['es_email_mail'] = $es_af_email;
				$form['es_email_group'] = $es_af_group[$i];					
				
				if( $data['es_c_optinoption'] == "Double Opt In" )
				{
					$form['es_email_status'] = "Unconfirmed";
				}
				else
				{
					$form['es_email_status'] = "Single Opt In";
				}
			
				$action = es_cls_dbquery::es_view_subscriber_widget($form);
				
				if($action == "sus")
				{
					$subscribers = array();
					$subscribers = es_cls_dbquery::es_view_subscriber_one($form['es_email_mail']);
					if( $data['es_c_optinoption'] == "Double Opt In" )
					{
						if($email_saved_double_opt_in == 0)
						{
							es_cls_sendmail::es_sendmail("optin", $template = 0, $subscribers, "optin", 0);
						}
						$email_saved_double_opt_in = $email_saved_double_opt_in + 1;
					}
					else
					{
						if( $data['es_c_usermailoption'] == "YES" )
						{
							if($email_saved_single_opt_in == 0)
							{
								es_cls_sendmail::es_sendmail("welcome", $template = 0, $subscribers, "welcome", 0);
							}
						}
						$email_saved_single_opt_in = $email_saved_single_opt_in + 1;
					}
				}
				elseif($action == "ext")
				{
					$email_already_exists = $email_already_exists + 1;
				}
			}
		}
		
		if($email_saved_double_opt_in > 0)
		{
			$sts = "double_opt_in_saved";
		}
		elseif($email_saved_single_opt_in > 0)
		{
			$sts = "single_opt_in_saved";
		}
		elseif($email_already_exists > 0)
		{
			$sts = "emails_already_exists";
		}
		else
		{
			$sts = "no_email_saved";
		}
		return $sts;
	}
	
	public static function es_af_formdisplay($form_setting = array())
	{
		$es_af = "";
		$es_af_alt_nm = '';
		$es_af_alt_em = '';
		$es_af_alt_gp = '';
		$es_af_alt_success = '';
		$es_af_alt_techerror = '';
		$es_af_error = false;
		
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( !is_plugin_active( 'email-subscribers/email-subscribers.php' ) ) {
			$es_af = _e('This is a add-on plugin for Email Subscribers plugin. Please note that this plugin works only if you have activated Email Subscribers plugin.', ES_AF_TDOMAIN);
			return $es_af;
		}
		
		if(count($form_setting) == 0) {
			return $es_af;
		} else {
			$es_af_title 		= $form_setting['es_af_title'];
			$es_af_desc			= $form_setting['es_af_desc'];
			$es_af_name			= $form_setting['es_af_name'];
			$es_af_name_mand	= $form_setting['es_af_name_mand'];
			$es_af_email		= $form_setting['es_af_email'];
			$es_af_email_mand	= $form_setting['es_af_email_mand'];
			$es_af_group		= $form_setting['es_af_group'];
			$es_af_group_mand	= $form_setting['es_af_group_mand'];
			$es_af_group_list	= $form_setting['es_af_group_list'];
		}

		if ( isset( $_POST['es_af_btn'] ) ) {
			
			check_admin_referer('es_af_form_subscribers');
			
			if($es_af_name == "YES") {
				//$es_af_txt_nm = $_POST['es_af_txt_nm'];
				$es_af_txt_nm = isset($_POST['es_af_txt_nm']) ? $_POST['es_af_txt_nm'] : '';
			}
			
			if($es_af_email == "YES") {
				//$es_af_txt_em = $_POST['es_af_txt_em'];
				$es_af_txt_em = isset($_POST['es_af_txt_em']) ? $_POST['es_af_txt_em'] : '';
			}
			
			if($es_af_group == "YES") {
				if($es_af_group_mand == "YES") {
					//$es_af_chk = $_POST['es_af_chk'];
					$es_af_chk = isset($_POST['es_af_chk']) ? $_POST['es_af_chk'] : '';
				} else {
					$es_af_chk = array();
					$es_af_chk[0] = ES_AF_DEFAULT_GROUP;
				}
			} else {
				$es_af_chk = array();
				$es_af_chk[0] = ES_AF_DEFAULT_GROUP;
			}

			if($es_af_name == "YES" && $es_af_name_mand == "YES" && $es_af_txt_nm == "") {
				$es_af_alt_nm = '<span class="es_af_validation">'.ES_AF_MSG_01.'</span>';
				$es_af_error = true;
			}
			
			if($es_af_email == "YES" && $es_af_email_mand == "YES" && $es_af_txt_em == "") {
				$es_af_alt_em = '<span class="es_af_validation">'.ES_AF_MSG_01.'</span>';
				$es_af_error = true;
			}

			if (!filter_var($es_af_txt_em, FILTER_VALIDATE_EMAIL) && $es_af_txt_em <> "") {
				$es_af_alt_em = '<span class="es_af_validation">'.ES_AF_MSG_02.'</span>';
				$es_af_error = true;
			}
			
			if($es_af_group_mand == "YES" && empty($es_af_chk)) {
				$es_af_alt_gp = '<span class="es_af_validation">'.ES_AF_MSG_03.'</span>';
				$es_af_error = true;
			}
			
			if(!$es_af_error)
			{
				$homeurl = home_url();
				$samedomain = strpos($_SERVER['HTTP_REFERER'], $homeurl);
				if (($samedomain !== false) && $samedomain < 5) 
				{					
					$sts = es_af_form_submuit::es_af_preparation($es_af_txt_nm, $es_af_txt_em, $es_af_chk);
					if($sts == "double_opt_in_saved")
					{
						$es_af_alt_success = '<span class="es_af_sent_successfully">'.ES_AF_MSG_04.'</span>';
					}
					elseif($sts == "single_opt_in_saved")
					{
						$es_af_alt_success = '<span class="es_af_sent_successfully">'.ES_AF_MSG_05.'</span>';
					}
					elseif($sts == "emails_already_exists")
					{
						$es_af_alt_success = '<span class="es_af_tech_error">'.ES_AF_MSG_06.'</span>';
					}
					elseif($sts == "no_email_saved")
					{
						$es_af_alt_success = '<span class="es_af_tech_error">'.ES_AF_MSG_07.'</span>';
					}
					else
					{
						$es_af_alt_success = '<span class="es_af_tech_error">'.ES_AF_MSG_08.'</span>';
					}
				}
				else
				{
					$es_af_alt_success = '<span class="es_af_tech_error">'.ES_AF_MSG_09.'</span>';
				}
			}
		}
		
		$es_af = $es_af . '<form method="post" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '">';
		
		if($es_af_desc	<> "")
		{
			$es_af = $es_af . '<p>';
				$es_af = $es_af . '<span class="es_af_short_desc">';
					$es_af = $es_af . $es_af_desc;
				$es_af = $es_af . '</span>';
			$es_af = $es_af . '</p>';
		
		}
		
		if($es_af_name == "YES")
		{
			$es_af = $es_af . '<p>';
				$es_af = $es_af . __('Name', ES_AF_TDOMAIN);
				if($es_af_name_mand == "YES")
				{
					$es_af = $es_af . ' *';
				}
				$es_af = $es_af . '<br>';
				$es_af = $es_af . '<span class="es_af_css_txt">';
					$es_af = $es_af . '<input class="es_af_tb_css" name="es_af_txt_nm" id="es_af_txt_nm" value="" maxlength="225" type="text">';
				$es_af = $es_af . '</span>';
				$es_af = $es_af . $es_af_alt_nm;
			$es_af = $es_af . '</p>';
		}
		
		if($es_af_email == "YES")
		{
			$es_af = $es_af . '<p>';
				$es_af = $es_af . __('<span class="esField">Email', ES_AF_TDOMAIN);
				if($es_af_email_mand == "YES")
				{
					$es_af = $es_af . ' *';
				}
				$es_af = $es_af . '</span><br>';
				$es_af = $es_af . '<span class="es_af_css_txt">';
					$es_af = $es_af . '<input class="es_af_tb_css" name="es_af_txt_em" id="es_af_txt_em" value="" maxlength="225" type="text">';
				$es_af = $es_af . '</span>';
				$es_af = $es_af . $es_af_alt_em;
			$es_af = $es_af . '</p>';
		}
		
		if($es_af_group == "YES")
		{
			$es_af = $es_af . '<p>';
				$es_af = $es_af . __('Interested groups', ES_AF_TDOMAIN);
				if($es_af_group_mand == "YES")
				{
					$es_af = $es_af . ' *';
				}
				$es_af = $es_af . '<br>';
				if($es_af_group_list <> "")
				{
					$groups = explode(',', $es_af_group_list);
					foreach ($groups as $group)
					{
						$es_af = $es_af . '<input type="checkbox" value="'.$group.'" name="es_af_chk[]"> <span class="">'.$group.'</span> <br>';
					}
				}
				else
				{
					$es_af = $es_af . '<input type="checkbox" value="'.ES_AF_DEFAULT_GROUP.'" name="es_af_chk[]"> <span class="">'.ES_AF_DEFAULT_GROUP.'</span> <br>';
				}
				$es_af = $es_af . $es_af_alt_gp;
			$es_af = $es_af . '</p>';
		}
		
		$es_af = $es_af . '<p>';
			$es_af = $es_af . '<input class="es_af_bt_css" name="es_af_btn" id="es_af_btn" value="'.__('Subscribe', ES_AF_TDOMAIN).'" type="submit">';
		$es_af = $es_af . '</p>';
		
		if($es_af_error)
		{
			$es_af = $es_af . '<span class="es_af_validation_full">'.ES_AF_MSG_10.'</span>';
		}
		else
		{
			$es_af = $es_af . $es_af_alt_success;
		}
		
		$es_af = $es_af . wp_nonce_field('es_af_form_subscribers');
		
		$es_af = $es_af . '</form>';
		
		return $es_af;
	}
}

function es_af_shortcode( $atts ) {
	if ( ! is_array( $atts ) ) {
		return '';
	}
	
	//[email-subscribers-advanced-form id="1"]
	$id = isset($atts['id']) ? $atts['id'] : '0';
	if(!is_numeric($id)) { return "Error in your short code."; }

	$data = array();
	$data = es_af_query::es_af_select($id);
	if(count($data) == 0) {
		$error_notice = _e( 'Error in your shortcode. Record does not exists for this ID.', ES_AF_TDOMAIN );
		return $error_notice;
	}
	
	$arr = array();
	$arr["es_af_title"] 		= $data[0]['es_af_title'];
	$arr["es_af_desc"] 			= $data[0]['es_af_desc'];
	$arr["es_af_name"] 			= $data[0]['es_af_name'];
	$arr["es_af_name_mand"] 	= $data[0]['es_af_name_mand'];
	$arr["es_af_email"] 		= $data[0]['es_af_email'];
	$arr["es_af_email_mand"] 	= $data[0]['es_af_email_mand'];
	$arr["es_af_group"] 		= $data[0]['es_af_group'];
	$arr["es_af_group_mand"] 	= $data[0]['es_af_group_mand'];
	$arr["es_af_group_list"] 	= $data[0]['es_af_group_list'];
	return es_af_form_submuit::es_af_formdisplay($arr);
}

function  es_af_subbox( $id = "1" ) {
	$arr = array();
	$arr["id"] 	= $id;
	echo es_af_shortcode($arr);
}

class es_af_widget_register extends WP_Widget 
{
	function __construct() {
		$widget_ops = array('classname' => 'widget_text elp-widget', 'description' => __(ES_AF_PLUGIN_DISPLAY, ES_AF_TDOMAIN), ES_AF_PLUGIN_NAME);
		parent::__construct(ES_AF_PLUGIN_NAME, __(ES_AF_PLUGIN_DISPLAY, ES_AF_TDOMAIN), $widget_ops);
	}
	
	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
		
		$es_af_title 		= apply_filters( 'widget_title', empty( $instance['es_af_title'] ) ? '' : $instance['es_af_title'], $instance, $this->id_base );
		$es_af_desc			= $instance['es_af_desc'];
		$es_af_name			= $instance['es_af_name'];
		$es_af_name_mand	= $instance['es_af_name_mand'];
		$es_af_email		= $instance['es_af_email'];
		$es_af_email_mand	= $instance['es_af_email_mand'];
		$es_af_group		= $instance['es_af_group'];
		$es_af_group_mand	= $instance['es_af_group_mand'];
		$es_af_group_list	= $instance['es_af_group_list'];

		echo $args['before_widget'];
		if ( ! empty( $es_af_title ) )
		{
			echo $args['before_title'] . $es_af_title . $args['after_title'];
		}
		
		$form_setting = array(
			'es_af_title' 		=> $es_af_title,
			'es_af_desc' 		=> $es_af_desc,
			'es_af_name' 		=> $es_af_name,
			'es_af_name_mand' 	=> $es_af_name_mand,
			'es_af_email' 		=> $es_af_email,
			'es_af_email_mand' 	=> $es_af_email_mand,
			'es_af_group' 		=> $es_af_group,
			'es_af_group_mand' 	=> $es_af_group_mand,
			'es_af_group_list' 	=> $es_af_group_list		
		);
		
		$es_af = es_af_form_submuit::es_af_formdisplay($form_setting);
		echo $es_af;
		
		echo $args['after_widget'];
	}
	
	function update( $new_instance, $old_instance ) 
	{
		$instance 						= $old_instance;
		$instance['es_af_title'] 		= ( ! empty( $new_instance['es_af_title'] ) ) ? strip_tags( $new_instance['es_af_title'] ) : '';
		$instance['es_af_desc'] 		= ( ! empty( $new_instance['es_af_desc'] ) ) ? strip_tags( $new_instance['es_af_desc'] ) : '';
		$instance['es_af_name'] 		= ( ! empty( $new_instance['es_af_name'] ) ) ? strip_tags( $new_instance['es_af_name'] ) : '';
		$instance['es_af_name_mand'] 	= ( ! empty( $new_instance['es_af_name_mand'] ) ) ? strip_tags( $new_instance['es_af_name_mand'] ) : '';
		$instance['es_af_email'] 		= ( ! empty( $new_instance['es_af_email'] ) ) ? strip_tags( $new_instance['es_af_email'] ) : '';
		$instance['es_af_email_mand'] 	= ( ! empty( $new_instance['es_af_email_mand'] ) ) ? strip_tags( $new_instance['es_af_email_mand'] ) : '';
		$instance['es_af_group'] 		= ( ! empty( $new_instance['es_af_group'] ) ) ? strip_tags( $new_instance['es_af_group'] ) : '';
		$instance['es_af_group_mand'] 	= ( ! empty( $new_instance['es_af_group_mand'] ) ) ? strip_tags( $new_instance['es_af_group_mand'] ) : '';
		$instance['es_af_group_list'] 	= ( ! empty( $new_instance['es_af_group_list'] ) ) ? strip_tags( $new_instance['es_af_group_list'] ) : '';
		return $instance;
	}
	
	function form( $instance ) 
	{
		$defaults = array(
			'es_af_title' 		=> '',
            'es_af_desc' 		=> '',
            'es_af_name' 		=> '',
			'es_af_name_mand' 	=> '',
			'es_af_email' 		=> '',
			'es_af_email_mand' 	=> '',
			'es_af_group' 		=> '',
			'es_af_group_mand' 	=> '',
			'es_af_group_list' 	=> ''
        );
		
		$instance 			= wp_parse_args( (array) $instance, $defaults);
		$es_af_title 		= $instance['es_af_title'];
        $es_af_desc 		= $instance['es_af_desc'];
        $es_af_name 		= $instance['es_af_name'];
		$es_af_name_mand 	= $instance['es_af_name_mand'];
		$es_af_email 		= $instance['es_af_email'];
		$es_af_email_mand 	= $instance['es_af_email_mand'];
		$es_af_group 		= $instance['es_af_group'];
		$es_af_group_mand 	= $instance['es_af_group_mand'];
		$es_af_group_list 	= $instance['es_af_group_list'];
		?>
		<p>
			<label for="<?php echo $this->get_field_id('es_af_title'); ?>"><?php _e('Widget title', ES_AF_TDOMAIN); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('es_af_title'); ?>" name="<?php echo $this->get_field_name('es_af_title'); ?>" type="text" value="<?php echo $es_af_title; ?>" />
        </p>
		<p>
			<label for="<?php echo $this->get_field_id('es_af_desc'); ?>"><?php _e('Short description', ES_AF_TDOMAIN); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('es_af_desc'); ?>" name="<?php echo $this->get_field_name('es_af_desc'); ?>" type="text" value="<?php echo $es_af_desc; ?>" />
			<?php _e('Short description about your subscription form.', ES_AF_TDOMAIN); ?>
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('es_af_name'); ?>"><?php _e('Display NAME box', ES_AF_TDOMAIN); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('es_af_name'); ?>" name="<?php echo $this->get_field_name('es_af_name'); ?>">
				<option value="YES" <?php $this->es_af_selected($es_af_name == 'YES'); ?>>YES</option>
				<option value="NO" <?php $this->es_af_selected($es_af_name == 'NO'); ?>>NO</option>
			</select>
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('es_af_name_mand'); ?>"><?php _e('Do you want to set NAME box is mandatory box?', ES_AF_TDOMAIN); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('es_af_name_mand'); ?>" name="<?php echo $this->get_field_name('es_af_name_mand'); ?>">
				<option value="YES" <?php $this->es_af_selected($es_af_name_mand == 'YES'); ?>>YES</option>
				<option value="NO" <?php $this->es_af_selected($es_af_name_mand == 'NO'); ?>>NO</option>
			</select>
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('es_af_email'); ?>"><?php _e('Display EMAIL box', ES_AF_TDOMAIN); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('es_af_email'); ?>" name="<?php echo $this->get_field_name('es_af_email'); ?>">
				<option value="YES" <?php $this->es_af_selected($es_af_email == 'YES'); ?>>YES</option>
			</select>
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('es_af_email_mand'); ?>"><?php _e('Do you want to set EMAIL box is mandatory box?', ES_AF_TDOMAIN); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('es_af_email_mand'); ?>" name="<?php echo $this->get_field_name('es_af_email_mand'); ?>">
				<option value="YES" <?php $this->es_af_selected($es_af_email_mand == 'YES'); ?>>YES</option>
			</select>
        </p>
		<p>
			<label for="<?php echo $this->get_field_id('es_af_group'); ?>"><?php _e('Display GROUPS options', ES_AF_TDOMAIN); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('es_af_group'); ?>" name="<?php echo $this->get_field_name('es_af_group'); ?>">
				<option value="YES" <?php $this->es_af_selected($es_af_group == 'YES'); ?>>YES</option>
				<option value="NO" <?php $this->es_af_selected($es_af_group == 'NO'); ?>>NO</option>
			</select>
        </p>
		
		<p>
			<label for="<?php echo $this->get_field_id('es_af_group_mand'); ?>"><?php _e('Do you want to set mandatory option for GROUP box?', ES_AF_TDOMAIN); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('es_af_group_mand'); ?>" name="<?php echo $this->get_field_name('es_af_group_mand'); ?>">
				<option value="YES" <?php $this->es_af_selected($es_af_group_mand == 'YES'); ?>>YES</option>
				<option value="NO" <?php $this->es_af_selected($es_af_group_mand == 'NO'); ?>>NO</option>
			</select>
        </p>
		
		<p>
			<label for="<?php echo $this->get_field_id('es_af_group_list'); ?>"><?php _e('Enter GROUP name to display (coma separated value)', ES_AF_TDOMAIN); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('es_af_group_list'); ?>" name="<?php echo $this->get_field_name('es_af_group_list'); ?>" type="text" value="<?php echo $es_af_group_list; ?>" />
			<?php
			if ( is_plugin_active( 'email-subscribers/email-subscribers.php' ) ) 
			{
				$groups = array();
				$groups = es_cls_dbquery::es_view_subscriber_group();
				if(count($groups) > 0)
				{
					$i = 1;
					foreach ($groups as $group)
					{
						if($i <> 1)
						{
							echo ", ";
						}
						else
						{
							echo "<br>Existing Groups : ";
						}
						echo $group["es_email_group"];
						$i = $i +1;
					}
				}
			}
			?>
        </p>
		<?php
	}
	
	function es_af_selected($var) {
		if ($var==1 || $var==true) {
			echo 'selected="selected"';
		}
	}

}

class es_af_intermediate
{
	public static function es_af_advancedform()
	{
		$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
		switch($current_page) {
			case 'add':
				require_once(ES_AF_DIR.'page'.DS.'es-af-add.php');
				break;
			case 'edit':
				require_once(ES_AF_DIR.'page'.DS.'es-af-edit.php');
				break;
			default:
				require_once(ES_AF_DIR.'page'.DS.'es-af-show.php');
				break;
		}
	}
}

?>