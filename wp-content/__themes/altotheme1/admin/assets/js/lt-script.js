jQuery(document).ready(function($){
    "use strict";
    loadListIcons($);
    
    $('body').on('click', '.lt-chosen-icon', function(){
        var _fill = $(this).attr('data-fill');
        if(_fill){
            if($('.lt-list-icons-select').length < 1){
                $.ajax({
                    url: ajaxurl,
                    type: 'get',
                    dataType: 'html',
                    data: {
                        action: 'lt_list_fonts_admin',
                        fill: _fill
                    },
                    success: function(res){
                        $('body').append(res);
                        $('body').append('<div class="lt-tranparent" />');
                        $('.lt-list-icons-select').animate({right: 0}, 300);
                    }
                });
            }else{
                $('body').append('<div class="lt-tranparent" />');
                $('.lt-list-icons-select').attr('data-fill', _fill);
                $('.lt-list-icons-select').animate({right: 0}, 300);
            }
        }
        
        return false;
    });
    
    $('body').on('click', '.lt-tranparent', function (){
        $('.lt-list-icons-select').animate({right: '-100%'}, 300);
        $(this).remove();
    });
    
    $('body').on('click', '.lt-fill-icon', function (){
        var _val = $(this).attr('data-val');
        var _fill = $(this).parent().attr('data-fill');
        $('#'+_fill).val(_val);
        if($('#ico-'+_fill).length){
            $('#ico-'+_fill).html('<i class="' + _val + '"></i><a href="javascript:void(0);" class="lt-remove-icon" data-id="' + _fill + '"><i class="fa fa-remove"></i></a>');
        }
        $('.lt-tranparent').click();
    });
    
    $('body').on('click', '.lt-remove-icon', function(){
        var _fill = $(this).attr('data-id');
        $('#'+_fill).val('');
        $('#ico-'+_fill).html('');
    });
    
    loadColorPicker($);
    $('.widget-control-save').ajaxComplete(function(){
        loadColorPicker($);
    });
    
    $('body').on('change', '.lt-select-attr', function(){
        var _warp = $(this).parents('.widget-content');
        if($(_warp).find('.lt-vari-type').val() === '1'){
            var taxonomy = $(this).val(),
                num = $(this).attr('data-num'),
                instance = $(_warp).find('.lt-widget-instance').attr('data-instance');
            loadColorDefault($, _warp, taxonomy, num, instance, false);
        }
        
        return true;
    });
    
    $('body').on('change', '.lt-vari-type', function(){
        var _warp = $(this).parents('.widget-content'),
            taxonomy = $(_warp).find('.lt-select-attr').val(),
            num = $(_warp).find('.lt-select-attr').attr('data-num'),
            instance = $(_warp).find('.lt-widget-instance').attr('data-instance');
        if ($(this).val() === '1') {  
            loadColorDefault($, _warp, taxonomy, num, instance, true);
        } else {
            unloadColor($, _warp);
        }
        
        return true;
    });
    
    // Option Breadcrumb
    if($('.lt-breadcrumb-flag-option input[type="checkbox"]').is(':checked')){
	$('.lt-breadcrumb-type-option').show();
	if($('.lt-breadcrumb-type-option').find('select').val() === 'Has background'){
	    $('.lt-breadcrumb-bg-option').show();
	    $('.lt-breadcrumb-color-option').show();
	    $('.lt-breadcrumb-hieght-option').show();
	    loadImgOpBreadcrumb($)
	}
    }
    
    $('body').on('change', '.lt-breadcrumb-flag-option input[type="checkbox"]', function(){
	if($(this).is(':checked')){
	    $('.lt-breadcrumb-type-option').fadeIn(200);
	    if($('.lt-breadcrumb-type-option').find('select').val() === 'Has background'){
		$('.lt-breadcrumb-bg-option').fadeIn(200);
		$('.lt-breadcrumb-color-option').fadeIn(200);
		$('.lt-breadcrumb-hieght-option').fadeIn(200);
		loadImgOpBreadcrumb($)
	    }
	}else{
	    $('.lt-breadcrumb-type-option').fadeOut(200);
	    $('.lt-breadcrumb-bg-option').fadeOut(200);
	    $('.lt-breadcrumb-color-option').fadeOut(200);
	    $('.lt-breadcrumb-hieght-option').fadeOut(200);
	}
    });
    
    $('body').on('change', '.lt-breadcrumb-type-option select', function(){
	if($(this).val() === 'Has background'){
	    $('.lt-breadcrumb-bg-option').fadeIn(200);
	    $('.lt-breadcrumb-color-option').fadeIn(200);
	    $('.lt-breadcrumb-hieght-option').fadeIn(200);
	    loadImgOpBreadcrumb($);
	}else{
	    $('.lt-breadcrumb-bg-option').fadeOut(200);
	    $('.lt-breadcrumb-color-option').fadeOut(200);
	    $('.lt-breadcrumb-hieght-option').fadeOut(200);
	}
    });
    
    if($('.type_promotion select').length){
        var val = $('.type_promotion select').val();
        if(val === 'My content custom'){
            $('.lt-custom_content').show();
        }else if(val === 'List posts'){
            $('.lt-list_post').show();
        }
        $('body').on('change', '.type_promotion select', function(){
            var val = $(this).val();
            if(val === 'My content custom'){
                $('.lt-custom_content').fadeIn(200);
                $('.lt-list_post').fadeOut(200);
            }else if(val === 'List posts'){
                $('.lt-custom_content').fadeOut(200);
                $('.lt-list_post').fadeIn(200);
            }
        });
    }

    /* =============== End document ready !!! ================== */
});

function loadImgOpBreadcrumb($){
    if($('.lt-breadcrumb-bg-option .screenshot').length && $('.lt-breadcrumb-bg-option #breadcrumb_bg_upload').val() !== ''){
	if($('.lt-breadcrumb-bg-option .screenshot').html() === ''){
	    $('.lt-breadcrumb-bg-option .screenshot').html('<img class="of-option-image" src="'+$('.lt-breadcrumb-bg-option #breadcrumb_bg_upload').val()+'" />');
	    $('.upload_button_div .remove-image').removeClass('hide').show();
	}
    }
}

function loadColorDefault($, _warp, _taxonomy, _num, _instance, _check){
    if(_check && $(_warp).find('.lt_p_color').length){
        var _this = $(_warp).find('.lt_p_color');
        $(_this).find('input').prop('disabled', false);
        $(_this).show();
    }else{
        _instance = _instance.toLocaleString();
        $.ajax({
	    url: ajaxurl,
	    type: 'post',
	    dataType: 'html',
	    data: {
		action: 'lt_list_colors_admin',
                taxonomy: _taxonomy,
		num: _num,
                instance: _instance
	    },
	    success: function(res){
                $(_warp).find('.lt_p_color').remove();
		$(_warp).append(res);
                loadColorPicker($);
	    }
	});
    }
}

function unloadColor($, _warp){
    var _this = $(_warp).find('.lt_p_color');
    $(_this).find('input').prop('disabled', true);
    $(_this).hide();
}

function loadColorPicker($){
    $('.lt-color-field').each(function(){
        if($(this).parents('.wp-picker-container').length < 1){
            $(this).wpColorPicker();
        }
    });
};

function loadListIcons($){
    if($('.lt-list-icons-select').length < 1){
	$.ajax({
	    url: ajaxurl,
	    type: 'get',
	    dataType: 'html',
	    data: {
		action: 'lt_list_fonts_admin',
		fill: ''
	    },
	    success: function(res){
		$('body').append(res);
	    }
	});
    }
};