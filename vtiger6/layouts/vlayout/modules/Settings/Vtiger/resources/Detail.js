/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

jQuery.Class("Settings_Vtiger_Detail_Js",{

	detailInstance : false,
	
	/**
	 * Function to get detail view instance
	 */
	
	getInstance: function(){
        if( Settings_Vtiger_Detail_Js.detailInstance == false ){
            var module = app.getModuleName();
			var parentModule = app.getParentModuleName();
            var moduleClassName = parentModule+"_"+module+"_Detail_Js";
			var fallbackClassName = parentModule+"_Vtiger_Detail_Js";
            if(typeof window[moduleClassName] != 'undefined'){
                var instance = new window[moduleClassName]();
            }else{
                var instance = new fallbackClassName();
            }
            Settings_Vtiger_Detail_Js.detailInstance = instance;
        }
        return Settings_Vtiger_Detail_Js.detailInstance;
	}
},{
	detailViewForm : false,
	
	/**
	 * Function which will give the detail view form
	 * @return : jQuery element
	 */
	getForm : function() {
		if(this.detailViewForm == false) {
			this.detailViewForm = jQuery('#detailView');
		}
		return this.detailViewForm;
	},
	
	/**
	 * Function to register form for validation
	 */
	registerFormForValidation : function(){
		var detailViewForm = this.getForm();
		detailViewForm.validationEngine(app.validationEngineOptions);
	},
	
	/**
	 * Function which will handle the registrations for the elements 
	 */
	registerEvents : function() {
		this.registerFormForValidation();
	}
})

//On Page Load
jQuery(document).ready(function() {
	var detailInstance = Settings_Vtiger_Detail_Js.getInstance();
	detailInstance.registerEvents();
});
