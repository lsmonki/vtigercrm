/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_Edit_Js("Calendar_Edit_Js",{

},{
	registerReminderFieldCheckBox : function() {
		this.getForm().find('input[name="set_reminder"]').on('change', function(e) {
			var element = $(e.currentTarget);
			var closestDiv = element.closest('div').next();
			if(element.is(':checked')) {
				closestDiv.show();
			} else {
				closestDiv.hide();
			}
		})
	},

	registerEvents : function(){
		var statusToProceed = this.proceedRegisterEvents();
		if(!statusToProceed){
			return;
		}

	 	this.registerReminderFieldCheckBox();
		this._super();
	}
});