/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_Edit_Js("Accounts_Edit_Js",{
   
},{
   
    //Stored history of account name and duplicate check result
	duplicateCheckCache : {},
	
	//This will store the editview form
	editViewForm : false,
   
		
	/**
	 * Function which will register basic events which will be used in quick create as well
	 *
	 */
	registerBasicEvents : function(container) {
		this._super(container);
		this.registerRecordPreSaveEvent(container);
			//container.trigger(Vtiger_Edit_Js.recordPreSave, {'value': 'edit'});
	},
        
	/**
	 * This function will return the current form
	 */
	getForm : function(){
		if(this.editViewForm == false) {
			this.editViewForm = jQuery('#EditView');
		}
		return this.editViewForm;
	},
        
	/**
	 * This function will return the account name
	 */
	getAccountName : function(container){
		return jQuery('input[name="accountname"]',container).val();
	},
        
	/**
	 * This function will return the current RecordId
	 */
	getRecordId : function(container){
		return jQuery('input[name="record"]',container).val();
	},
       
	/**
	 * This function will register before saving any record
	 */
	registerRecordPreSaveEvent : function(form) {
		var thisInstance = this;
		if(typeof form == 'undefined') {
			form = this.getForm();
		}
		
		form.on(Vtiger_Edit_Js.recordPreSave, function(e, data) {
			var accountName = thisInstance.getAccountName(form);
			var recordId = thisInstance.getRecordId(form);
			var params = {};
			if(!(accountName in thisInstance.duplicateCheckCache)) {
				Vtiger_Helper_Js.checkDuplicateName({
					'accountName' : accountName, 
					'recordId' : recordId
				}).then(
					function(data){
						thisInstance.duplicateCheckCache[accountName] = data['success'];
						form.submit();
					},
					function(data, err){
						thisInstance.duplicateCheckCache[accountName] = data['success'];
						thisInstance.duplicateCheckCache['message'] = data['message'];
						params = {
							title: app.vtranslate('JS_DUPLICATE_RECORD'),
							text: data['message']
						};
						Vtiger_Helper_Js.showPnotify(params);
					}
					);
			}
			else {
				if(thisInstance.duplicateCheckCache[accountName] == true){
					params = {
						title: app.vtranslate('JS_DUPLICATE_RECORD'),
						text: thisInstance.duplicateCheckCache['message']
					};
					Vtiger_Helper_Js.showPnotify(params);
				} else {
					return true;
				}
			}
			e.preventDefault();
		})
	}
    
});