jQuery(document).ready(function($){
    "use strict";
    
    if($('.lt-breadcrumb-flag input[type="checkbox"]').is(':checked')){
	$('.lt-breadcrumb-type').show();
	if($('.lt-breadcrumb-type').find('select').val() == '1'){
	    $('.lt-breadcrumb-bg').show();
	    $('.lt-breadcrumb-bg-color').show();
	    $('.lt-breadcrumb-height').show();
	    $('.lt-breadcrumb-color').show();
	}
    }
    
    $('body').on('change', '.lt-breadcrumb-flag input[type="checkbox"]', function(){
	if($(this).is(':checked')){
	    $('.lt-breadcrumb-type').fadeIn(200);
	    if($('.lt-breadcrumb-type').find('select').val() == '1'){
		$('.lt-breadcrumb-bg').fadeIn(200);
		$('.lt-breadcrumb-bg-color').fadeIn(200);
		$('.lt-breadcrumb-height').fadeIn(200);
		$('.lt-breadcrumb-color').fadeIn(200);
	    }
	}else{
	    $('.lt-breadcrumb-type').fadeOut(200);
	    $('.lt-breadcrumb-bg').fadeOut(200);
	    $('.lt-breadcrumb-bg-color').fadeOut(200);
	    $('.lt-breadcrumb-height').fadeOut(200);
	    $('.lt-breadcrumb-color').fadeOut(200);
	}
    });
    
    $('body').on('change', '.lt-breadcrumb-type select', function(){
	if($(this).val() == '1'){
	    $('.lt-breadcrumb-bg').fadeIn(200);
	    $('.lt-breadcrumb-bg-color').fadeIn(200);
	    $('.lt-breadcrumb-height').fadeIn(200);
	    $('.lt-breadcrumb-color').fadeIn(200);
	}else{
	    $('.lt-breadcrumb-bg').fadeOut(200);
	    $('.lt-breadcrumb-bg-color').fadeOut(200);
	    $('.lt-breadcrumb-height').fadeOut(200);
	    $('.lt-breadcrumb-color').fadeOut(200);
	}
    });
    
    $('.lt-breadcrumb-color, .lt-breadcrumb-bg-color').find('input').wpColorPicker();
    /* =============== End document ready !!! ================== */
});
