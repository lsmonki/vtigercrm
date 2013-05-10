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
					var imageEle = widgetContainer.parent().find('.imageElement');
					var imagePath = imageEle.data('rightimage');
					imageEle.attr('src',imagePath);
					var key = widgetContainer.attr('id');
					app.cacheSet(key, 0);
			}
		});
	},

	loadWidgets : function(widgetContainer) {
		var message = jQuery('.loadingWidgetMsg').html();

		if(widgetContainer.html() != '') {
			var imageEle = widgetContainer.parent().find('.imageElement');
			var imagePath = imageEle.data('downimage');
			imageEle.attr('src',imagePath);
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
				var imageEle = widgetContainer.parent().find('.imageElement');
				var imagePath = imageEle.data('downimage');
				imageEle.attr('src',imagePath);
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
					var imageEle = widgetContainer.parent().find('.imageElement');
					var imagePath = imageEle.data('rightimage');
					imageEle.attr('src',imagePath);
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

	/**
	 * Function registers event for Calendar Reminder popups
	 */
	registerActivityReminder : function() {
		var activityReminder = jQuery('#activityReminder').val();
		activityReminder = activityReminder * 1000;
		if(activityReminder != '') {
			var currentTime = new Date().getTime()/1000;
			var nextActivityReminderCheck = app.cacheGet('nextActivityReminderCheckTime', 0);
			if((currentTime + activityReminder) > nextActivityReminderCheck) {
				Vtiger_Index_Js.requestReminder();
				setTimeout('Vtiger_Index_Js.requestReminder()', activityReminder);
				app.cacheSet('nextActivityReminderCheckTime', currentTime + parseInt(activityReminder));
			}
		}
	},

	/**
	 * Function request for reminder popups
	 */
	requestReminder : function() {
		var url = 'index.php?module=Calendar&action=ActivityReminder&mode=getReminders';
		AppConnector.request(url).then(function(data){
			if(data.success && data.result) {
				for(i=0; i< data.result.length; i++) {
					var record  = data.result[i];
					Vtiger_Index_Js.showReminderPopup(record);
				}
			}
		});
	},

	/**
	 * Function display the Reminder popup
	 */
	showReminderPopup : function(record) {
		var params = {
			title: '&nbsp;&nbsp;<span style="position: relative; top: 8px;">'+record.activitytype+' - '+
					'<a target="_blank" href="index.php?module=Calendar&view=Detail&record='+record.id+'">'+record.subject+'</a></span>',
			text: '<div class="row-fluid" style="color:black">\n\
							<span class="span12">'+app.vtranslate('JS_START_DATE_TIME')+' : '+record.date_start+'</span>\n\
							<span class="span8">'+app.vtranslate('JS_END_DATE_TIME')+' : '+record.due_date+'</span>'+
							'<span class="span3 right"><h4><a id="reminder_'+record.id+'" class="pull-right" href=#>'
								+app.vtranslate('JS_POSTPONE')+'</a></h4></span></div>',
			width: '30%',
			min_height: '75px',
			addclass:'vtReminder',
			icon: 'vtReminder-icon',
			hide:false,
			closer:true,
			type:'info',
			after_open:function(p) {
				jQuery(p).data('info', record);
			}
		};
		var notify = Vtiger_Helper_Js.showPnotify(params);

		jQuery('#reminder_'+record.id).bind('click', function() {
			notify.remove();
			var url = 'index.php?module=Calendar&action=ActivityReminder&mode=postpone&record='+record.id;
			AppConnector.request(url);
		});
	},

	registerEvents : function(){
		Vtiger_Index_Js.registerWidgetsEvents();
		Vtiger_Index_Js.loadWidgetsOnLoad();
		Vtiger_Index_Js.registerActivityReminder();
	}
}


//On Page Load
jQuery(document).ready(function() {
	Vtiger_Index_Js.registerEvents();
});