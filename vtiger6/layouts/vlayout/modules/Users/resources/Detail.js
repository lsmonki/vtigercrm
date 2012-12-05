/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_Detail_Js("Users_Detail_Js",{},{
	
	usersEditInstance : false,
	
	updateStartHourElement : function(form) {
		this.usersEditInstance.triggerHourFormatChangeEvent(form);
		this.updateStartHourElementValue();
	},
	hourFormatUpdateEvent  : function() {
		var thisInstance = this;
		this.getForm().on(this.fieldUpdatedEvent, '[name="hour_format"]', function(e, params){
			thisInstance.updateStartHourElementValue();
		});
	},
	
	updateStartHourElementValue : function() {
		var form = this.getForm();
		var startHourSelectElement = jQuery('select[name="start_hour"]',form);
		var selectedElementValue = startHourSelectElement.find('option:selected').text();
		startHourSelectElement.closest('td').find('span.value').text(selectedElementValue);
	},
	
	startHourUpdateEvent : function(form) {
		var thisInstance = this;
		form.on(this.fieldUpdatedEvent, '[name="start_hour"]', function(e, params){
			thisInstance.updateStartHourElement(form);
		});
	},
	
	registerEvents : function() {
        this._super();
		var form = this.getForm();
		this.usersEditInstance = Vtiger_Edit_Js.getInstance();
		this.updateStartHourElement(form);
		this.hourFormatUpdateEvent();
		this.startHourUpdateEvent(form);
	}
	
});