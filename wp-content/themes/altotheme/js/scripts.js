var $j = jQuery.noConflict();

$j(document).ready(function($) {
	$('img, a.prettyphoto').on('dragstart', function(event) { 
		event.preventDefault(); 
	})

	$("body").on("contextmenu", "img, a.prettyphoto", function(e) {
		return false;
	})

	$('body').delegate('#pa_personalised-props', 'change', function(){

		var val = $('#pa_personalised-props option:selected').val();

		if (val === 'download-ready-made-designs-a3') {
			$('#tm-extra-product-options-fields').hide();
		} else {
			$('#tm-extra-product-options-fields').show();
		}

		if ( val === 'personalise-download-prop' ) {
			$('#tm-extra-product-options-fields li#tm-epo-field-0 > div:last-child').hide();
		} else {
			$('#tm-extra-product-options-fields li#tm-epo-field-0 > div:last-child').show();
		}
		
		$('.variations_form')[0].reset();

		$('#pa_personalised-props option').each(function() {
			if ($(this).attr('value') === val) {
				$(this).attr('selected', true);
			}
		})
	})

	$('body').delegate('#pa_personalised-cake-toppers', 'change', function(){
		var val = $('#pa_personalised-cake-toppers option:selected').val();

		if (val !== '') {
			$('#tm-extra-product-options').show();
		} else {
			$('#tm-extra-product-options').hide();
		}

		if (val === 'download-ready-made-cake-topper') {
			$('#tm-extra-product-options-fields').hide();
		} else {
			$('#tm-extra-product-options-fields').show();
		}

		if ( val === 'personalise-download-cake-topper' ) {
			$('#tm-extra-product-options-fields li#tm-epo-field-0 > div:last-child').hide();
		} else {
			$('#tm-extra-product-options-fields li#tm-epo-field-0 > div:last-child').show();
		}
		
		$('.variations_form')[0].reset();

		$('#pa_personalised-cake-toppers option').each(function() {
			if ($(this).attr('value') === val) {
				$(this).attr('selected', true);
			}
		})
	})


})