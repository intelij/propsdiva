<div class="wrap" id="of_container">

	<div id="of-popup-save" class="of-save-popup">
		<div class="of-save-save">Options Updated</div>
	</div>
	
	<div id="of-popup-reset" class="of-save-popup">
		<div class="of-save-reset">Options Reset</div>
	</div>
	
	<div id="of-popup-fail" class="of-save-popup">
		<div class="of-save-fail">Error!</div>
	</div>
	
	<span style="display: none;" id="hooks"><?php echo json_encode(of_get_header_classes_array()); ?></span>
	<input type="hidden" id="reset" value="<?php if(isset($_REQUEST['reset'])) echo $_REQUEST['reset']; ?>" />
	<input type="hidden" id="security" name="security" value="<?php echo wp_create_nonce('of_ajax_nonce'); ?>" />

	<form id="of_form" method="post" action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ) ?>" enctype="multipart/form-data" >
		<h2 style="display: none;"><?php esc_html_e('Theme Options', 'altotheme'); ?></h2>
        
        <div class="running-import">
            <div class="bg-success"></div>
            <span class="result-import"><span class="success-import">0</span>%</span>
        </div>
        
		<div class="updated error importer-notice importer-notice-1" style="display: none;"><p><strong><?php echo sprintf(esc_html__('Seems like an error has occured. Please double check the imported data. If incorrect, please use %s and try again', 'altotheme'), '<a href="'.admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=wordpress-reset&amp;TB_iframe=true&amp;width=830&amp;height=472' ).'" class="thickbox" title="'.esc_html__('Reset WordPress plugin', 'altotheme').'">'.esc_html__('Reset WordPress plugin', 'altotheme').'</a>'); ?> </strong></p></div>

		<div class="updated importer-notice importer-notice-2" style="display: none;"><p><strong><?php echo sprintf(esc_html__('Demo data successfully imported. Click Save All Changes. Go to Tools -> Regen. Thumbnails -> Click Regenerate All Thumbnails', 'altotheme')); ?></strong></p></div>

		<div class="updated error importer-notice importer-notice-3" style="display: none;"><p><strong><?php esc_html_e('Sorry but your import failed. Most likely, it cannot work with your webhost. You will have to ask your webhost to increase your PHP max_execution_time (or any other webserver timeout to at least 300 secs) and memory_limit (to at least 196M) temporarily.', 'altotheme'); ?></strong></p></div>
		
        
        
		<div id="header">
		
			<div class="logo">
				<h2><?php echo THEMENAME; ?></h2>
				<!-- <span><?php echo ('v'. THEMEVERSION); ?></span> -->
				<span style="margin-left: 30px;"><a href="http://www.leetheme.com/support/alto-documentation/" target="_blank"><?php esc_html_e('Online Documentation', 'altotheme'); ?></a></span>
			</div>
			<?php // Lee Edit ?>
			<div class="docs">
				
			</div>
			<?php // End Lee Edit ?>
			<div id="js-warning">Warning- This options panel will not work properly without javascript!</div>
			<div class="icon-option"></div>
			<div class="clear"></div>
		
    	</div>

		<div id="info_bar">
		
			<a>
				<div id="expand_options" class="expand">Expand</div>
			</a>
						
			<img style="display:none" src="<?php echo ADMIN_DIR; ?>assets/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />

			<button id="of_save" type="button" class="button-primary">
				<?php esc_html_e('Save All Changes', 'altotheme');?>
			</button>
			
		</div><!--.info_bar--> 	
		
		<div id="main">
		
			<div id="of-nav">
				<ul>
				  <?php echo $options_machine->Menu ?>
				</ul>
			</div>

			<div id="content">
		  		<?php echo $options_machine->Inputs /* Settings */ ?>
		  	</div>
		  	
			<div class="clear"></div>
			
		</div>
		
		<div class="save_bar"> 
		
			<img style="display:none" src="<?php echo ADMIN_DIR; ?>assets/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
			<button id ="of_save" type="button" class="button-primary"><?php esc_html_e('Save All Changes', 'altotheme');?></button>			
			<button id ="of_reset" type="button" class="button submit-button reset-button" ><?php esc_html_e('Options Reset', 'altotheme');?></button>
			<img style="display:none" src="<?php echo ADMIN_DIR; ?>assets/images/loading-bottom.gif" class="ajax-reset-loading-img ajax-loading-img-bottom" alt="Working..." />
			
		</div><!--.save_bar--> 
 
	</form>



</div><!--wrap-->