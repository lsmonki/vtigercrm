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

	registerEvents : function(){
		Vtiger_Index_Js.registerWidgetsEvents();
		Vtiger_Index_Js.loadWidgetsOnLoad();
	}
}


//On Page Load
jQuery(document).ready(function() {
	Vtiger_Index_Js.registerEvents();
});