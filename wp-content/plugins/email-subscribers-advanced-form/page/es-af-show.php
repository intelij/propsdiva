<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
if ( !is_plugin_active( 'email-subscribers/email-subscribers.php' ) ) 
{
	echo "<div class='error fade'><p><strong>";
	_x('Please note, Email Subscribers Group Selector plugin works only if you have activated Email Subscribers plugin first.', 'es-af-show' ,ES_AF_TDOMAIN);
	echo "</strong></p></div>";
}

// Form submitted, check the data
if (isset($_POST['frm_es_af_display']) && $_POST['frm_es_af_display'] == 'yes')
{
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	if(!is_numeric($did)) { die('<p>Are you sure you want to do this?</p>'); }
	
	$es_af_success = '';
	$es_af_success_msg = FALSE;
	
	// First check if ID exist with requested ID
	$result = es_af_query::es_af_count($did);
	
	if ($result != '1')
	{
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist.', ES_AF_TDOMAIN); ?></strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('es_af_form_show');
			
			//	Delete selected record from the table
			es_af_query::es_af_delete($did);
			
			//	Set success message
			$es_af_success_msg = TRUE;
			$es_af_success = __('Selected record was successfully deleted.', ES_AF_TDOMAIN);
		}
	}
	
	if ($es_af_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $es_af_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php _e(ES_AF_PLUGIN_DISPLAY, ES_AF_TDOMAIN); ?></h2>
    <h3><?php _e('Form Details', ES_AF_TDOMAIN); ?></h3>
	<div class="tool-box">
	<?php
		$myData = array();
		$myData = es_af_query::es_af_select(0);
		?>
		<form name="frm_es_af_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
		  	<th class="check-column" scope="col" style="padding: 8px 2px;"><input type="checkbox" name="es_af_checkall" id="es_af_checkall" /></th>
            <th scope="col"><?php _e('Title', ES_AF_TDOMAIN); ?></th>
			<th scope="col"><?php _e('Short Code', ES_AF_TDOMAIN); ?></th>
			<th scope="col"><?php _e('ID', ES_AF_TDOMAIN); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
		  	<th class="check-column" scope="col" style="padding: 8px 2px;"><input type="checkbox" name="es_af_checkall" id="es_af_checkall" /></th>
            <th scope="col"><?php _e('Title', ES_AF_TDOMAIN); ?></th>
			<th scope="col"><?php _e('Short Code', ES_AF_TDOMAIN); ?></th>
			<th scope="col"><?php _e('ID', ES_AF_TDOMAIN); ?></th>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			if(count($myData) > 0 )
			{
				foreach ($myData as $data)
				{
					?>
					<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
						<td align="left"><input name="chk_delete[]" id="chk_delete[]" type="checkbox" value="<?php echo $data['es_af_title'] ?>" /></td>
						<td><?php echo stripslashes($data['es_af_title']); ?>
						<div class="row-actions">
						<span class="edit">
						<a title="Edit" href="<?php echo ES_AF_ADMINURL; ?>&ac=edit&amp;did=<?php echo $data['es_af_id']; ?>"><?php _e('Edit', ES_AF_TDOMAIN); ?></a> | </span>
						<span class="trash">
						<a onClick="javascript:es_af_delete('<?php echo $data['es_af_id']; ?>')" href="javascript:void(0);"><?php _e('Delete', ES_AF_TDOMAIN); ?></a>
						</span> 
						</div>
						</td>
						<td>[email-subscribers-advanced-form id="<?php echo $data['es_af_id']; ?>"]</td>
						<td><?php echo $data['es_af_id']; ?></td>
					</tr>
					<?php 
					$i = $i+1; 
				} 	
			}
			else
			{
				?><tr><td colspan="3" align="center"><?php _e('No records available.', ES_AF_TDOMAIN); ?></td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('es_af_form_show'); ?>
		<input type="hidden" name="frm_es_af_display" value="yes"/>
      </form>	
	  <div class="tablenav">
		  <h2>
			  <a class="button add-new-h2" href="<?php echo ES_AF_ADMINURL; ?>&amp;ac=add"><?php _e('Add New', ES_AF_TDOMAIN); ?></a>
			  <a class="button add-new-h2" target="_blank" href="<?php echo ES_AF_FAV; ?>"><?php _e('Help', ES_AF_TDOMAIN); ?></a>
		  </h2>
	  </div>
	  <div style="height:5px"></div>
	<p class="description"><?php echo ES_AF_OFFICIAL; ?></p>
	</div>
</div>