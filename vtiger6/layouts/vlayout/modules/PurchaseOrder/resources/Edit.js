/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Inventory_Edit_Js("PurchaseOrder_Edit_Js",{},{
	
	/**
	 * Function which will register event for Reference Fields Selection
	 */
	registerReferenceSelectionEvent : function() {
		this._super();
		var thisInstance = this;
		var formElement = thisInstance.getForm();
		
		jQuery('input[name="vendor_id"]', formElement).on(Vtiger_Edit_Js.referenceSelectionEvent, function(e, data){
			thisInstance.copyAddressDetails(data);
		});
	}
});


