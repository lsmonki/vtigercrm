/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Settings_Vtiger_List_Js("Settings_Payments_List_Js",{
    
    triggerEdit:function(url) {
        AppConnector.request(url).then(function(data){
            app.showModalWindow(data,function(data){
				var params = app.getvalidationEngineOptions(true);
				params.onValidationComplete = function(form,valid){
					if(valid) {
						Settings_Payments_List_Js.registerSaveEvent(data);
					}
					return false;
				}
				jQuery('#paymentsSettingsForm').validationEngine(params);
                Settings_Payments_List_Js.getInstance().registerProviderChange();
            });
        })
    },
    
    triggerDelete : function(url) {
      var thisInstance = Settings_Payments_List_Js.getInstance();
		var message = app.vtranslate('LBL_DELETE_CONFIRMATION');
		Vtiger_Helper_Js.showConfirmationBox({'message' : message}).then(
			function(e) {
				AppConnector.request(url).then(
					function() {
						var params = {
							text: app.vtranslate('JS_DELETED_SUCCESSFULLY')
						};
						Settings_Vtiger_Index_Js.showMessage(params);
						thisInstance.getListViewRecords();
					},
					function(error,err){
					}
				);
			},
			function(error, err){
			}
		);
    },
    
    registerSaveEvent : function(data) {
        var thisInstance = Settings_Payments_List_Js.getInstance();
		var form = jQuery(data).find('form');
		var values = form.serializeFormData();
		values['module'] = app.getModuleName();
		values['parent'] = app.getParentModuleName();
		values['action'] = 'Save';
		var progressIndicatorElement = jQuery.progressIndicator({
			'position' : 'html',
			'blockInfo' : {
				'enabled' : true
			}
		});
		AppConnector.request(values).then(function(response){
			progressIndicatorElement.progressIndicator({
				'mode' : 'hide'
			})
			Settings_Vtiger_Index_Js.showMessage({text : app.vtranslate('JS_SAVED_SUCCESSFULLY')});
			thisInstance.getListViewRecords();
		})
    }
    
},{
    
    registerProviderChange : function () {
        jQuery('[name="providertype"]').on('change',function(e){
            var element = jQuery(e.currentTarget);
            var providerName = element.val();
            jQuery('.providers').hide('200');
            jQuery('[name="'+providerName+'"]').show('200');
        })
    }
    
});