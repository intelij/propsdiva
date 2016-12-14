jQuery(document).ready(function($){
       
       
        //hide first
        jQuery('#tm-extra-product-options').hide();	
    
        var delivery = true;        
             
        	
		//for props	
		jQuery('#pa_personalised-props').on('change', function() {			
            var selectedValue = (jQuery("#pa_personalised-props option:selected").val());
            jQuery('button[type="submit"]').removeAttr('disabled');
            	
			if(selectedValue == 'download-a3' || selectedValue == 'download-a4' || selectedValue == 'download-a5' || selectedValue == ''){					
                jQuery('[id^=tmcp_select_]').prop('selectedIndex',0);
                jQuery('.tmcp-checkbox:checkbox').attr('checked', false);           
			    jQuery('.tmcp-field:text').val('');
                jQuery('.tmcp-textarea').val('');                                               
                jQuery('#tm-extra-product-options').hide();
               
            }else if(selectedValue == 'personalised-download-a3' || selectedValue == 'personalised-download-a4' || selectedValue == 'personalised-download-a5'){	                
				jQuery('#tm-extra-product-options').show(); 
                jQuery('[id^=tmcp_select_]').prop('selectedIndex',0);                 
                
            }else{
                jQuery('#tm-extra-product-options').show();
                jQuery('[id^=tmcp_select_]').show();
                if(!delivery){
                     jQuery('#tm-extra-product-options').hide();	
                    alert('Sorry. This option is temporarily unavaiable');
                    jQuery('button[type="submit"]').attr('disabled','disabled');
                }
            }        
        })
        
        //for cupcakes
        jQuery('#pa_personalised-cupcakes').on('change', function() {			
            var selectedValue = (jQuery("#pa_personalised-cupcakes option:selected").val());
            jQuery('button[type="submit"]').removeAttr('disabled');
            	
			if(selectedValue == 'download' || selectedValue == ''){					
                jQuery('[id^=tmcp_select_]').prop('selectedIndex',0);
                jQuery('.tmcp-checkbox:checkbox').attr('checked', false);           
			    jQuery('.tmcp-field:text').val('');
                jQuery('.tmcp-textarea').val('');                                               
                jQuery('#tm-extra-product-options').hide();
               
            }else if(selectedValue == 'personalised-download'){	                
				jQuery('#tm-extra-product-options').show(); 
                 jQuery('[id^=tmcp_select_]').prop('selectedIndex',0);                 
                
            }else{
                jQuery('#tm-extra-product-options').show();
                jQuery('[id^=tmcp_select_]').show();
                if(!delivery){
                    jQuery('#tm-extra-product-options').hide();	
                    alert('Sorry. This option is temporarily unavaiable');
                    jQuery('button[type="submit"]').attr('disabled','disabled');
                }
            }        
        })
        
        //for backdrops
        jQuery('#pa_personalised-backdrop').on('change', function() {			
            var selectedValue = (jQuery("#pa_personalised-backdrop option:selected").val());
            jQuery('button[type="submit"]').removeAttr('disabled');
            	
			if(selectedValue == 'download' || selectedValue == ''){					
                jQuery('[id^=tmcp_select_]').prop('selectedIndex',0);
                jQuery('.tmcp-checkbox:checkbox').attr('checked', false);           
			    jQuery('.tmcp-field:text').val('');
                jQuery('.tmcp-textarea').val('');                                               
                jQuery('#tm-extra-product-options').hide();
               
            }else if(selectedValue == 'personalised-download'){	                
				jQuery('#tm-extra-product-options').show(); 
                 jQuery('[id^=tmcp_select_]').prop('selectedIndex',0);                 
                
            }else{
                jQuery('#tm-extra-product-options').show();
                jQuery('[id^=tmcp_select_]').show();
                if(!delivery){
                    jQuery('#tm-extra-product-options').hide();	
                    alert('Sorry. This option is temporarily unavaiable');
                    jQuery('button[type="submit"]').attr('disabled','disabled');
                }
            }        
        })
        
        //for customise prop
        jQuery('#pa_customise-props').on('change', function() {			
            var selectedValue = (jQuery("#pa_customise-props option:selected").val());
            jQuery('button[type="submit"]').removeAttr('disabled');
            	
            if(selectedValue == 'download'){	                
				jQuery('#tm-extra-product-options').show(); 
                jQuery('[id^=tmcp_select_]').prop('selectedIndex',0);                 
                
            }else{
                jQuery('#tm-extra-product-options').show();
                jQuery('[id^=tmcp_select_]').show();
                if(!delivery){
                    jQuery('#tm-extra-product-options').hide();	
                    alert('Sorry. This option is temporarily unavaiable');
                    jQuery('button[type="submit"]').attr('disabled','disabled');
                }
            }        
        })
        
        
    
         //for customise backdrop
        jQuery('#pa_customise-backdrops').on('change', function() {			
            var selectedValue = (jQuery("#pa_customise-backdrops option:selected").val());
            jQuery('button[type="submit"]').removeAttr('disabled');
            	
            if(selectedValue == 'download'){	                
				jQuery('#tm-extra-product-options').show(); 
                jQuery('[id^=tmcp_select_]').prop('selectedIndex',0);                              
                
            }else{
                jQuery('#tm-extra-product-options').show();
                jQuery('[id^=tmcp_select_]').show();
                if(!delivery){
                    jQuery('#tm-extra-product-options').hide();	
                    alert('Sorry. This option is temporarily unavaiable');
                    jQuery('button[type="submit"]').attr('disabled','disabled');
                }
            }        
        })
        
        
        
        
        
       
		
		
	});	
