<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$es_af_es_plugin_active = true;
if ( !is_plugin_active( 'email-subscribers/email-subscribers.php' ) ) 
{
	echo "<div class='error fade'><p><strong>";
	_x('Please note, Email Subscribers Group Selector plugin works only if you have activated Email Subscribers plugin first.', 'es-af-add' ,ES_AF_TDOMAIN);
	echo "</strong></p></div>";
	$es_af_es_plugin_active = false;
}

$es_af_errors = array();
$es_af_success = '';
$es_af_error_found = FALSE;

// Preset the form fields
$form = array(
	'es_af_id' => '',
	'es_af_title' => '',
	'es_af_desc' => '',
	'es_af_name' => '',
	'es_af_name_mand' => '',
	'es_af_email' => '',
	'es_af_email_mand' => '',
	'es_af_group' => '',
	'es_af_group_mand' => '',
	'es_af_group_list' => ''
);

// Form submitted, check the data
if (isset($_POST['es_af_form_submit']) && $_POST['es_af_form_submit'] == 'yes')
{
	//	Just security thing that wordpress offers us
	check_admin_referer('es_af_form_add');
	
	$form['es_af_title'] = isset($_POST['es_af_title']) ? $_POST['es_af_title'] : '';
	if ($form['es_af_title'] == '')
	{
		$es_af_errors[] = __('Enter title for your form.', ES_AF_TDOMAIN);
		$es_af_error_found = TRUE;
	}
	
	$form['es_af_desc'] = isset($_POST['es_af_desc']) ? $_POST['es_af_desc'] : '';
	$form['es_af_name'] = isset($_POST['es_af_name']) ? $_POST['es_af_name'] : '';
	$form['es_af_name_mand'] = isset($_POST['es_af_name_mand']) ? $_POST['es_af_name_mand'] : '';
	$form['es_af_email'] = isset($_POST['es_af_email']) ? $_POST['es_af_email'] : '';
	$form['es_af_email_mand'] = isset($_POST['es_af_email_mand']) ? $_POST['es_af_email_mand'] : '';
	$form['es_af_group'] = isset($_POST['es_af_group']) ? $_POST['es_af_group'] : '';
	$form['es_af_group_mand'] = isset($_POST['es_af_group_mand']) ? $_POST['es_af_group_mand'] : '';
	$form['es_af_group_list'] = isset($_POST['es_af_group_list']) ? $_POST['es_af_group_list'] : '';

	//	No errors found, we can add this Group to the table
	if ($es_af_error_found == FALSE)
	{
		$action = es_af_query::es_af_act($form, "ins");
		if($action == "sus")
		{
			$es_af_success = __('New details was successfully added.', ES_AF_TDOMAIN);
		}
		elseif($action == "err")
		{
			$es_af_success = __('Oops unexpected error occurred.', ES_AF_TDOMAIN);
			$es_af_error_found = TRUE;
		}

		// Reset the form fields
		$form = array(
			'es_af_id' => '',
			'es_af_title' => '',
			'es_af_desc' => '',
			'es_af_name' => '',
			'es_af_name_mand' => '',
			'es_af_email' => '',
			'es_af_email_mand' => '',
			'es_af_group' => '',
			'es_af_group_mand' => '',
			'es_af_group_list' => ''
		);
	}
}

if ($es_af_error_found == TRUE && isset($es_af_errors[0]) == TRUE)
{
	?>
	<div class="error fade">
		<p><strong><?php echo $es_af_errors[0]; ?></strong></p>
	</div>
	<?php
}
if ($es_af_error_found == FALSE && strlen($es_af_success) > 0)
{
	?>
	<div class="updated fade">
		<p><strong><?php echo $es_af_success; ?> <a href="<?php echo ES_AF_ADMINURL; ?>"><?php _e('Click here', ES_AF_TDOMAIN); ?></a> 
		<?php _e('to view the details', ES_AF_TDOMAIN); ?></strong></p>
	</div>
	<?php
}
?>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php _e(ES_AF_PLUGIN_DISPLAY, ES_AF_TDOMAIN); ?></h2>
	<form name="es_af_form" method="post" action="#" onsubmit="return es_af_submit()"  >
      <h3><?php _e('Add Form', ES_AF_TDOMAIN); ?></h3>
      
		<label for="tag-a"><?php _e('Title', ES_AF_TDOMAIN); ?></label>
		<input name="es_af_title" type="text" id="es_af_title" value="" size="30" maxlength="100" />
		<p><?php _e('Enter title for your form.', ES_AF_TDOMAIN); ?></p>
		
		<label for="tag-a"><?php _e('Short description', ES_AF_TDOMAIN); ?></label>
		<input name="es_af_desc" type="text" id="es_af_desc" value="" size="50" maxlength="255" />
		<p><?php _e('Enter short description about your subscription form. ', ES_AF_TDOMAIN); ?></p>			
		
		<label for="tag-a"><?php _e('NAME box', ES_AF_TDOMAIN); ?></label>
		<select name="es_af_name" id="es_af_email">
			<option value='YES'>YES</option>
			<option value='NO'>NO</option>
		</select>
		<p><?php _e('Do you want to display NAME box in the subscription form?', ES_AF_TDOMAIN); ?></p>
		
		<label for="tag-a"><?php _e('Mandatory option', ES_AF_TDOMAIN); ?></label>
		<select name="es_af_name_mand" id="es_af_name_mand">
			<option value='YES'>YES</option>
			<option value='NO'>NO</option>
		</select>
		<p><?php _e('Do you want to set mandatory option for NAME box?', ES_AF_TDOMAIN); ?></p>
		
		
		<label for="tag-a"><?php _e('EMAIL box', ES_AF_TDOMAIN); ?></label>
		<select name="es_af_email" id="es_af_email">
			<option value='YES'>YES</option>
		</select>
		<p><?php _e('Do you want to display EMAIL box in the subscription form?', ES_AF_TDOMAIN); ?></p>
		
		<label for="tag-a"><?php _e('Mandatory option', ES_AF_TDOMAIN); ?></label>
		<select name="es_af_email_mand" id="es_af_email_mand">
			<option value='YES'>YES</option>
		</select>
		<p><?php _e('Do you want to set mandatory option for EMAIL box?', ES_AF_TDOMAIN); ?></p>
		
		
		<label for="tag-a"><?php _e('GROUP box', ES_AF_TDOMAIN); ?></label>
		<select name="es_af_group" id="es_af_group">
			<option value='YES'>YES</option>
			<option value='NO'>NO</option>
		</select>
		<p><?php _e('Do you want to display GROUP box in the subscription form?', ES_AF_TDOMAIN); ?></p>
		
		<label for="tag-a"><?php _e('Mandatory option', ES_AF_TDOMAIN); ?></label>
		<select name="es_af_group_mand" id="es_af_group_mand">
			<option value='YES'>YES</option>
			<option value='NO'>NO</option>
		</select>
		<p><?php _e('Do you want to set mandatory option for GROUP box?', ES_AF_TDOMAIN); ?></p>
		
		<label for="tag-a"><?php _e('Enter GROUP name to display (coma separated value)', ES_AF_TDOMAIN); ?></label>
		<input name="es_af_group_list" type="text" id="es_af_group_list" value="Public" size="30" maxlength="225" />
		<?php
		$existing_groups = "";
		if($es_af_es_plugin_active)
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
						$existing_groups = $existing_groups . ", ";
					}
					$existing_groups = $existing_groups . $group["es_email_group"];
					$i = $i +1;
				}
			}
		}
		?>
		<p><?php _e('Existing Groups : ', ES_AF_TDOMAIN); ?><?php echo $existing_groups; ?></p>
		
		
      <input type="hidden" name="es_af_form_submit" id="es_af_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button" value="<?php _e('Submit', ES_AF_TDOMAIN); ?>" type="submit" />
        <input name="publish" lang="publish" class="button" onclick="es_af_redirect()" value="<?php _e('Cancel', ES_AF_TDOMAIN); ?>" type="button" />
        <input name="Help" lang="publish" class="button" onclick="es_af_help()" value="<?php _e('Help', ES_AF_TDOMAIN); ?>" type="button" />
      </p>
	  <?php wp_nonce_field('es_af_form_add'); ?>
    </form>
</div>
<p class="description"><?php echo ES_AF_OFFICIAL; ?></p>
</div>