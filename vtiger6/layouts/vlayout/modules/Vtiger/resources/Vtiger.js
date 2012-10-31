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
					Vtiger_Index_Js.loadWidgets(jQuery(e.currentTarget));
			},
				hidden: function(e) {
				var widgetContainer = jQuery(e.currentTarget);
				widgetContainer.parent().find('.icon-chevron-down').removeClass('icon-chevron-down alignBottom').addClass('icon-chevron-up alignBottom');
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

		widgetContainer.progressIndicator({ 'message' : message });
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
			}
		);
	},

	registerEvents : function(){
		Vtiger_Index_Js.registerWidgetsEvents();
	}
}


//On Page Load
jQuery(document).ready(function() {
	Vtiger_Index_Js.registerEvents();
});