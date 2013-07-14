/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_Popup_Js("Inventory_Popup_Js",{},{
	
	subProductsClickEvent : function() {
		var popupPageContentsContainer = this.getPopupPageContainer();
		var thisInstance = this;
		popupPageContentsContainer.on('click','.subproducts',function(e){
			var rowElement = jQuery(e.currentTarget).closest('tr');
			e.stopPropagation();
			var params = {};
			params.view = 'SubProductsPopup';
			params.module = app.getModuleName();
			params.multi_select = true;
			params.subProductsPopup = true;
			params.productid = rowElement.data('id');
			AppConnector.request(params).then(function(data) {
				jQuery('#popupContentsDiv').html(data);
			});
		});
	},
	
	registerEvents: function(){
		this._super();
		this.subProductsClickEvent();
	}
});

