/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_Edit_Js("Users_Edit_Js",{},{

	//Hold the conditions for a hour format
	hourFormatConditionMapping : false,
	
	
	registerWidthChangeEvent : function() {
		var thisInstance = this;
		var widthType = app.cacheGet('widthType', 'wideWidthType');
		jQuery('#currentWidthType').html(jQuery('li[data-class="'+widthType+'"]').html());
		jQuery('#widthType').on('click', 'li', function(e){
			var value = jQuery(e.currentTarget).data('class');
			app.cacheSet('widthType', value);
			jQuery('#currentWidthType').html(jQuery(e.currentTarget).html());
			window.location.reload();
		});
	},
	
	registerHourFormatChangeEvent : function() {
		
	},
	
	changeStartHourValuesEvent : function(form){
		var thisInstance = this;
		form.on('change','select[name="hour_format"]',function(e){
			var hourFormatVal = jQuery(e.currentTarget).val();
			var startHourElement = jQuery('select[name="start_hour"]',form);
			var conditionSelected = startHourElement.val();
			var list = thisInstance.hourFormatConditionMapping['hour_format'][hourFormatVal]['start_hour'];
			var options = '';
			for(var key in list) {
				//IE Browser consider the prototype properties also, it should consider has own properties only.
				if(list.hasOwnProperty(key)) {
					var conditionValue = list[key];
					options += '<option value="'+key+'"';
					if(key == conditionSelected){
						options += ' selected="selected" ';
					}
					options += '>'+conditionValue+'</option>';
				}
			}
			startHourElement.html(options).trigger("liszt:updated");
		});
		
		
	},
	
	triggerHourFormatChangeEvent : function(form) {
		this.hourFormatConditionMapping = jQuery('input[name="timeFormatOptions"]',form).data('value');
		this.changeStartHourValuesEvent(form);
		jQuery('select[name="hour_format"]',form).trigger('change');
	},

	registerEvents : function() {
        this._super();
		var form = this.getForm();
		this.registerWidthChangeEvent();
		this.triggerHourFormatChangeEvent(form);
	}
});
