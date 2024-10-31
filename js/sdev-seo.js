jQuery(document).ready(function() {

	jQuery('#wpse-sdevseo-metabox > h2').addClass('dashicons-before dashicons-admin-site-alt');
	
});

jQuery(document).on('click touch', 'label.label-on-top', function(e) {
	
	e.preventDefault();
	
	var parent = jQuery(this).parents('.sdevseo-input-container');
	
	if( jQuery(parent).find('input').length > 0) {
		
		jQuery(parent).find('input').focus();
		
	} else if( jQuery(parent).find('textarea').length > 0) {
	
		jQuery(parent).find('textarea').focus();
	
	}
	
	jQuery(this).hide();
	
	return false;
	
});

jQuery(document).on('focus', '.sdevseo-input, .sdevseo-textarea', function() {
	
	var parent = jQuery(this).parents('.sdevseo-input-container');
	var label = jQuery(parent).find('label');
	jQuery(label).hide();
	
});

jQuery(document).on('focusout', '.sdevseo-input, .sdevseo-textarea', function() {
	
	var parent = jQuery(this).parents('.sdevseo-input-container');
	var label = jQuery(parent).find('label');
	
	if( jQuery(this).val().length < 1) {
		
		jQuery(label).show();
		
	} else if( jQuery(this).val().length < 1) {
	
		jQuery(label).show();
	
	}
	
});

jQuery(document).on('keyup', '.sdevseo-input', function() {
	
	var el = jQuery(this);
	var parent = jQuery(this).parents('.sdevseo-input-container');
	var countEl = jQuery(parent).find('#charnum');
	var len = jQuery(this).val().length;
	
	jQuery(countEl).text('Characters: ' + len);
});

jQuery(document).on('keyup', '.sdevseo-textarea', function() {
	
	var el = jQuery(this);
	var parent = jQuery(this).parents('.sdevseo-input-container');
	var countEl = jQuery(parent).find('#charnum');
	var len = jQuery(this).val().length;
	
	if(len > 155) {
		len = 155;
		
		var shortVal = jQuery(this).val().slice(0, 155);
		jQuery(this).val(shortVal);
		
	}
	
	jQuery(countEl).text('Characters: ' + len + ' / 155');
	
});