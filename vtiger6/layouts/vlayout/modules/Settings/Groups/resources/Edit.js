/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Settings_Vtiger_Edit_Js('Settings_Groups_Edit_Js', {}, {
	memberSelectElement : false,
	
	getMemberSelectElement : function () {
		if(this.memberSelectElement == false) {
			this.memberSelectElement = jQuery('#memberList');
		}
		return this.memberSelectElement;
	},
	/**
	 * Function to register event for select2 element
	 */
	registerEventForSelect2Element : function(){
		var editViewForm = this.getForm();
		var selectElement = this.getMemberSelectElement();
		var params = {};
		params.dropdownCss = {'z-index' : 0};
		params.formatSelection = function(object,container){
			var selectedId = object.id;
			var selectedOptionTag = editViewForm.find('option[value="'+selectedId+'"]');
			var selectedMemberType = selectedOptionTag.data('memberType');
			container.addClass(selectedMemberType);
			var element = '<div>'+selectedOptionTag.text()+'</div>';
			return element;
		}
		app.changeSelectElementView(selectElement, 'select2',params);
	},
	
	/**
	 * Function to register form for validation
	 */
	registerFormForValidation : function(){
		var editViewForm = this.getForm();
		editViewForm.validationEngine(app.getvalidationEngineOptions(true));
	},
	
	/**
	 * Function which will handle the registrations for the elements 
	 */
	registerEvents : function() {
		this._super();
		this.registerEventForSelect2Element();
	}
});