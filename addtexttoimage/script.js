jQuery(document).ready(function($){
       
        
        //hide first
        jQuery('#tm-extra-product-options').hide();				
		
		//var imgsrcset = jQuery('a.woocommerce-main-image.product-image > img.wp-post-image').attr('src');
        	
		//dynamic fields for props	
		jQuery('#pa_personalised-props').on('click change', function() {			
            var selectedValue = (jQuery("#pa_personalised-props option:selected").val());
            	
			if(selectedValue == 'download-a3' || selectedValue == 'download-a4' || selectedValue == 'download-a5' || selectedValue == ''){		
				jQuery('#tm-extra-product-options').hide();				  
            }else{				
				jQuery('#tm-extra-product-options').show();
			}
            //reset values
			//jQuery('input.addon-checkbox:checkbox').attr('checked', false);
			

  		})
        
       
		
		
	});	
