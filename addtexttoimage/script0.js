
	jQuery(document).ready(function($){
       
        
        //hide first
        //jQuery(".product-addon").hide();//everything
        jQuery('.product-addon-personalisation').hide();
        jQuery('.product-addon-customisation').hide();
        jQuery(".product-addon-personalised-text").hide();
		jQuery(".product-addon-font").hide();
        jQuery(".product-addon-custom-design-description").hide();
        jQuery(".product-addon-material").hide();
		
		jQuery(".product-addon-bundle-deal-singapore-only").hide();
	    jQuery(".product-addon-bundle-deal-singapore-and-international").hide(); 
		jQuery(".product-addon-backdrop-size").hide();
		
		//var imgsrcset = jQuery('a.woocommerce-main-image.product-image > img.wp-post-image').attr('src');
        	
		//dynamic fields for props	
		jQuery('#pa_personalised-props').on('click change', function() {			
            var selectedValue = (jQuery("#pa_personalised-props option:selected").val());
            	
			if(selectedValue == 'download-a3' || selectedValue == 'download-a4' || selectedValue == 'download-a5' || selectedValue == ''){		
				jQuery('.product-addon-personalisation').hide();
				jQuery('.product-addon-customisation').hide();
				jQuery(".product-addon-personalised-text").hide();
				jQuery(".product-addon-font").hide();
                jQuery(".product-addon-custom-design-description").hide();
                jQuery(".product-addon-material").hide();
			}else if(selectedValue == 'customise'){
                jQuery('.product-addon-personalisation').hide();
                jQuery(".product-addon-personalised-text").hide();
		        jQuery(".product-addon-font").hide();
				jQuery('.product-addon-customisation').show();
                
            }else{				
				jQuery('.product-addon-personalisation').show();
				jQuery('.product-addon-customisation').hide();
				jQuery(".product-addon-custom-design-description").hide();
				jQuery(".product-addon-material").hide();
			}
            //reset values
			jQuery('input.addon-checkbox:checkbox').attr('checked', false);
			jQuery("input.addon-custom:text").val("");
            jQuery(".product-addon-font").val();
            jQuery(".product-addon-custom-design-description").val();
            jQuery(".product-addon-material .addon-select").val($(".product-addon-material .addon-select option:first").val());

  		})
        
        //dynamic fields for customise
        jQuery('#pa_personalised-backdrop').on('click change', function() {			
			
            var selectedValue = (jQuery("#pa_personalised-backdrop option:selected").val());
			console.log(selectedValue);
            	
			if( selectedValue == 'download-ready-made' || selectedValue == ''){		
				jQuery('.product-addon-personalisation').hide();				
				jQuery(".product-addon-personalised-text").hide();
				jQuery(".product-addon-font").hide();
				jQuery('.product-addon-customisation').hide();
                jQuery(".product-addon-custom-design-description").hide();
				jQuery(".product-addon-backdrop-size").hide();
                jQuery(".product-addon-bundle-deal-singapore-only").hide();
				jQuery(".product-addon-bundle-deal-singapore-and-international").hide();
			}else if(selectedValue == 'customise'){
                jQuery('.product-addon-personalisation').hide();
                jQuery(".product-addon-personalised-text").hide();
		        jQuery(".product-addon-font").hide();
				jQuery('.product-addon-customisation').show();
                
            }else{				
				jQuery('.product-addon-personalisation').show();
				jQuery('.product-addon-customisation').hide();
				jQuery(".product-addon-custom-design-description").hide();	
				jQuery(".product-addon-bundle-deal-singapore-only").hide();
				jQuery(".product-addon-bundle-deal-singapore-and-international").hide();
				jQuery(".product-addon-backdrop-size").hide();
			}
            //reset values
			jQuery('input.addon-checkbox:checkbox').attr('checked', false);
			jQuery("input.addon-custom:text").val("");
            jQuery(".product-addon-font").val();
            jQuery(".product-addon-custom-design-description").val();
            jQuery(".product-addon-backdrop-size .addon-radio").prop('checked', false);
			jQuery(".product-addon-bundle-deal-singapore-only .addon-radio").prop('checked', false);
			jQuery(".product-addon-bundle-deal-singapore-and-international .addon-radio").prop('checked', false);

  		})
		

		//personalise checkbox
		jQuery('.product-addon-personalisation input.addon-checkbox:checkbox').on('click', function() {	
            
			if(jQuery(".product-addon-personalisation input.addon-checkbox:checkbox").is(':checked')){                
				jQuery(".product-addon-personalised-text").show();
				jQuery(".product-addon-font").show();
    				
			} else {                
   				jQuery(".product-addon-personalised-text").hide();
				jQuery(".product-addon-font").hide();
			}
		})
        
        
        //customise checkbox
		jQuery('.product-addon-customisation input.addon-checkbox:checkbox').on('click', function() {	
            
			if(jQuery(".product-addon-customisation input.addon-checkbox:checkbox").is(':checked')){               
				jQuery(".product-addon-custom-design-description").show(); 
                jQuery(".product-addon-material").show();
				
				jQuery(".product-addon-bundle-deal-singapore-only").show();
				jQuery(".product-addon-bundle-deal-singapore-and-international").show();
				jQuery(".product-addon-backdrop-size").show();
			} else {                
   				jQuery(".product-addon-custom-design-description").hide();		
                jQuery(".product-addon-material").hide();				
				jQuery(".product-addon-bundle-deal-singapore-only").hide();
				jQuery(".product-addon-bundle-deal-singapore-and-international").hide();
				jQuery(".product-addon-backdrop-size").hide();
			}
		})
		
		jQuery(".product-addon-bundle-deal-singapore-and-international .addon-radio").on('click', function(){			
			jQuery(".product-addon-bundle-deal-singapore-only .addon-radio").prop('checked', false);			
		});
		
		jQuery(".product-addon-bundle-deal-singapore-only .addon-radio").on('click', function(){			
			jQuery(".product-addon-bundle-deal-singapore-and-international .addon-radio").prop('checked', false);			
		});
		
		jQuery(".product-addon-add-on-bundle-deal-singapore-and-international .addon-radio").on('click', function(){			
			jQuery(".product-addon-add-on-bundle-deal-singapore-only .addon-radio").prop('checked', false);			
		});
		
		jQuery(".product-addon-add-on-bundle-deal-singapore-only .addon-radio").on('click', function(){			
			jQuery(".product-addon-add-on-bundle-deal-singapore-and-international .addon-radio").prop('checked', false);			
		});

		/*
		
		var title = jQuery('h1.entry-title').text();
		var product_id = jQuery('form.variations_form.cart').attr('data-product_id');

		
		console.log(product_id);		
		title = title.trim();
		imgsrcset = imgsrcset.trim()

		jQuery("input.addon-custom:text").keyup(function(event){
			var message = jQuery("input.addon-custom:text").val();
			message = message.trim();

			var param = "pid="+product_id+"&msg="+message+"&"+"imgurl="+imgsrcset;
			param = encodeURI(param);
			
			jQuery('a.product-image > img.wp-post-image').attr('srcset','http://propsdiva.com/addtexttoimage/index.php?'+param);
			jQuery('a.product-image').attr('href','http://propsdiva.com/addtexttoimage/index.php?'+param);
               		
            	});
				
		*/
		
	});	
