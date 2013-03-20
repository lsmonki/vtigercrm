/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

var Vtiger_Index_Js = {

	registerWidgetsEvents : function() {
		var widgets = jQuery('div.widgetContainer');
		widgets.on({
				shown: function(e) {
					var widgetContainer = jQuery(e.currentTarget);
					Vtiger_Index_Js.loadWidgets(widgetContainer);
					var key = widgetContainer.attr('id');
					app.cacheSet(key, 1);
			},
				hidden: function(e) {
					var widgetContainer = jQuery(e.currentTarget);
					widgetContainer.parent().find('.icon-chevron-down').removeClass('icon-chevron-down alignBottom').addClass('icon-chevron-up alignBottom');
					var key = widgetContainer.attr('id');
					app.cacheSet(key, 0);
			}
		});
	},

	loadWidgets : function(widgetContainer) {
		var message = jQuery('.loadingWidgetMsg').html();

		if(widgetContainer.html() != '') {
			widgetContainer.parent().find('.icon-chevron-up').removeClass('icon-chevron-up alignBottom').addClass('icon-chevron-down alignBottom');
			widgetContainer.css('height', 'auto');
			return;
		}

		widgetContainer.progressIndicator({'message' : message});
		var url = widgetContainer.data('url');

		var listViewWidgetParams = {
			"type":"GET", "url":"index.php",
			"dataType":"html", "data":url
		}
		AppConnector.request(listViewWidgetParams).then(
			function(data){
				widgetContainer.progressIndicator({'mode':'hide'});
				widgetContainer.parent().find('.icon-chevron-up').removeClass('icon-chevron-up alignBottom').addClass('icon-chevron-down alignBottom');
				widgetContainer.css('height', 'auto');
				widgetContainer.html(data);
				var label = widgetContainer.closest('.quickWidget').find('.quickWidgetHeader').data('label');
				jQuery('.bodyContents').trigger('Vtiger.Widget.Load.'+label,jQuery(widgetContainer));
			}
		);
	},
	
	loadWidgetsOnLoad : function(){
		var widgets = jQuery('div.widgetContainer');
		widgets.each(function(index,element){
			var widgetContainer = jQuery(element);
			var key = widgetContainer.attr('id');
			var value = app.cacheGet(key);
			if(value != null){
				if(value == 1) {
					Vtiger_Index_Js.loadWidgets(widgetContainer);
					widgetContainer.addClass('in');
				} else {
					widgetContainer.parent().find('.icon-chevron-down').removeClass('icon-chevron-down alignBottom').addClass('icon-chevron-up alignBottom');
				}
			}
			
		});
		
	},
	
	/**
	 * Function to show compose email popup based on number of 
	 * email fields in given module,if email fields are more than
	 * one given option for user to select email for whom mail should 
	 * be sent,or else straight away open compose email popup
	 * @params : accepts params object
	 */
	
	showComposeEmailPopup : function(params){
		var currentModule = "Emails";
		Vtiger_Helper_Js.checkServerConfig(currentModule).then(function(data){
			if(data == true){
				var css = jQuery.extend({'text-align' : 'left'},css);
				AppConnector.request(params).then(
					function(data) {
						if(data) {
							data = jQuery(data);
							var form = data.find('#SendEmailFormStep1');
							var emailFields = form.find('.emailField');
							var length = emailFields.length;
							var emailEditInstance = new Emails_MassEdit_Js();
							if(length > 1) {
								app.showModalWindow(data,function(data){
									emailEditInstance.registerEmailFieldSelectionEvent();
								},css)
							} else {
								emailFields.attr('checked','checked');
								var params = form.serializeFormData();
								emailEditInstance.showComposeEmailForm(params);
							}
						}
					},
					function(error,err){

					}
				);
			} else {
				Vtiger_Helper_Js.showPnotify(app.vtranslate('JS_EMAIL_SERVER_CONFIGURATION'));
			}
		})
		
	},

	registerEvents : function(){
		Vtiger_Index_Js.registerWidgetsEvents();
		Vtiger_Index_Js.loadWidgetsOnLoad();
	}
}


//On Page Load
jQuery(document).ready(function() {
	Vtiger_Index_Js.registerEvents();
});