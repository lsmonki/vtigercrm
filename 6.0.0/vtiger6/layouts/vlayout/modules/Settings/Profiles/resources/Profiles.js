/*+*******************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

var Settings_Profiles_Js = {
	
	initEditView: function() {

		function toggleEditViewTableRow(e) {
			var target = jQuery(e.currentTarget);
			var container = jQuery('[data-togglecontent="'+ target.data('togglehandler') + '"]');
			
			container.toggle();
			
			if (target.hasClass('icon-chevron-down')) {
				target.removeClass('icon-chevron-down').addClass('icon-chevron-up');
			} else {
				target.removeClass('icon-chevron-up').addClass('icon-chevron-down');
			}
		}
		
		function handleChangeOfPermissionRange(e, ui) {
			var target = jQuery(ui.handle);
			if (!target.hasClass('mini-slider-control')) {
				target = target.closest('.mini-slider-control');
			}
			var input  = jQuery('[data-range-input="'+target.data('range')+'"]');
			input.val(ui.value);
			target.attr('data-value', ui.value);
		}
		
		function handleModuleSelectionState(e) {
			var target = jQuery(e.currentTarget);
			var tabid  = target.data('value');
			
			var parent = target.closest('tr');
			if (target.attr('checked')) {
				jQuery('[data-action-state]', parent).attr('checked', 'checked').show();
				jQuery('[data-handlerfor]', parent).show();
			} else {
				jQuery('[data-action-state]', parent).hide();
				
				// Pull-up fields / tools details in disabled state.
				jQuery('[data-handlerfor]', parent).hide();
				jQuery('[data-togglecontent="'+tabid+'-fields"]').hide();
				jQuery('[data-togglecontent="'+tabid+'-tools"]').hide();
			}
		}
		
		function handleActionSelectionState(e) {
			var target = jQuery(e.currentTarget);
			var parent = target.closest('tr');
			var checked = target.attr('checked')? true : false;
			
			if (target.data('action-state') == 'EditView' || target.data('action-state') == 'Delete') {
				if (checked) jQuery('[data-action-state="DetailView"]', parent).attr('checked', 'checked');
			}
			if (target.data('action-state') == 'DetailView') {
				if (!checked) {
					jQuery('[data-action-state]', parent).removeAttr('checked');
					jQuery('[data-module-state]', parent).removeAttr('checked').trigger('change');
				}
			}
		}
		
		jQuery('[data-module-state]').change(handleModuleSelectionState);
		jQuery('[data-action-state]').change(handleActionSelectionState);
		
		jQuery('[data-togglehandler]').click(toggleEditViewTableRow);
		jQuery('[data-range]').each(function(index, item) {
			item = jQuery(item);
			var value = item.data('value');
			item.slider({
				min: 0,
				max: 2,
				value: value,
				disabled: item.data('locked'),
				slide: handleChangeOfPermissionRange
			});
		});		
	}
	
}