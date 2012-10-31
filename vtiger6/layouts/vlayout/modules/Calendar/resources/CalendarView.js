/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/


jQuery.Class("Calendar_CalendarView_Js",{

	currentInstance : false,
	
	initiateCalendarFeeds : function() {
		Calendar_CalendarView_Js.currentInstance.performCalendarFeedIntiate();
	}
},{

	calendarView : false,
	calendarCreateView : false,

	calendarfeedDS : {},

	getCalendarView : function() {
		if(this.calendarView == false) {
			this.calendarView = jQuery('#calendarview');
		}
		return this.calendarView;
	},

	getCalendarCreateView : function() {
		var thisInstance = this;
		var aDeferred = jQuery.Deferred();
		
		if(this.calendarCreateView !== false) {
			aDeferred.resolve(this.calendarCreateView.clone(true,true));
			return aDeferred.promise();
		}
		var progressInstance = jQuery.progressIndicator();
		this.loadCalendarCreateView().then(
			function(data){
				progressInstance.hide();
				thisInstance.calendarCreateView = data;
				aDeferred.resolve(data.clone(true,true));
			},
			function(){
				progressInstance.hide();
			}
		);
		return aDeferred.promise();
	},

	loadCalendarCreateView : function() {
		var aDeferred  = jQuery.Deferred();
		var quickCreateCalendarElement = jQuery('#quickCreateModules').find('[data-name="Calendar"]');
		var url = quickCreateCalendarElement.data('url');
		var name = quickCreateCalendarElement.data('name');

		var headerInstance = new Vtiger_Header_Js();
		headerInstance.getQuickCreateForm(url,name).then(
			function(data){
				aDeferred.resolve(jQuery(data));
			},
			function(){
				aDeferred.reject();
			}
		);
		return aDeferred.promise();
	},

	fetchCalendarFeed : function(feedcheckbox) {
		function toDateString(date) {
			var d = date.getDate();
			var m = date.getMonth() +1;
			var y = date.getFullYear();

			d = (d <= 9)? ("0"+d) : d;
			m = (m <= 9)? ("0"+m) : m;
			return y + "-" + m + "-" + d;
		}
		
		var type = feedcheckbox.data('calendar-feed');
		this.calendarfeedDS[type] = function(start, end, callback) {
			feedcheckbox.attr('disabled', true);
			var params = {
				module: 'Calendar',
				action: 'Feed',
				start: toDateString(start),
				end: toDateString(end),
				type: feedcheckbox.data('calendar-feed'),
				cssClass: feedcheckbox.data('calendar-feed-css')
			}
			var customData = feedcheckbox.data('customData');
			if( customData != undefined) {
				params = jQuery.extend(params, customData);
			}

			AppConnector.request(params).then(function(events){
				callback(events);
				feedcheckbox.attr('disabled', false).attr('checked', true);
			});
		}

		this.getCalendarView().fullCalendar('addEventSource', this.calendarfeedDS[type]);
	},

	fetchAllCalendarFeeds : function(calendarfeedidx) {
		var thisInstance = this;
		var calendarfeeds = jQuery('[data-calendar-feed]');

		//TODO : see if you get all the feeds in one request
		calendarfeeds.each(function(index,element){
			var feedcheckbox = jQuery(element);
			var	disabledOnes = app.cacheGet('calendar.feeds.disabled',[]);
			if (disabledOnes.indexOf(feedcheckbox.data('calendar-feed')) == -1) {
				thisInstance.fetchCalendarFeed(feedcheckbox);
			}
		});
	},

	performCalendarFeedIntiate : function() {
		this.registerCalendarFeedChange();
		this.fetchAllCalendarFeeds();
	},

	registerCalendarFeedChange : function() {
		var thisInstance = this;
		var calendarfeeds = jQuery('[data-calendar-feed]');

		calendarfeeds.bind('change', function(){
			var type = $(this).data('calendar-feed');
			if($(this).is(':checked')) {
				// NOTE: We are getting cache data fresh - as it shared between browser tabs
				var disabledOnes = app.cacheGet('calendar.feeds.disabled',[]);
				// http://stackoverflow.com/a/3596096
				disabledOnes = jQuery.grep(disabledOnes, function(value){ return value != type; });
				app.cacheSet('calendar.feeds.disabled', disabledOnes);
				
				thisInstance.getCalendarView().fullCalendar('addEventSource', thisInstance.calendarfeedDS[type]);
			} else {
				// NOTE: We are getting cache data fresh - as it shared between browser tabs
				var disabledOnes = app.cacheGet('calendar.feeds.disabled',[]);
				if (disabledOnes.indexOf(type) == -1) disabledOnes.push(type);
				app.cacheSet('calendar.feeds.disabled', disabledOnes);
				
				thisInstance.getCalendarView().fullCalendar('removeEventSource', thisInstance.calendarfeedDS[type]);
			}
		});
	},

	dayClick : function(date, allDay, jsEvent, view){
		var thisInstance = this;
		this.getCalendarCreateView().then(function(data){
			if(data.length <= 0) {
				return;
			}
			var dateFormat = data.find('[name="date_start"]').data('dateFormat');

			var startDateInstance = Date.parse(date);
			var startDateString = app.getDateInVtigerFormat(dateFormat,startDateInstance);
			var startTimeString = startDateInstance.toString('hh:mm tt');

			var endDateInstance = Date.parse(date)
			endDateInstance.addHours(1);
			var endDateString = app.getDateInVtigerFormat(dateFormat,endDateInstance);
			var endTimeString = endDateInstance.toString('hh:mm tt');

			data.find('[name="date_start"]').val(startDateString);
			data.find('[name="due_date"]').val(endDateString);

			data.find('[name="time_start"]').val(startTimeString);
			data.find('[name="time_end"]').val(endTimeString);

			var headerInstance = new Vtiger_Header_Js();
			headerInstance.handleQuickCreateData(data, {callbackFunction:function(data){
					thisInstance.addCalendarEvent(data.result);
			}});
		});
		
	},

	registerCalendar : function() {
		var thisInstance = this;
		var calendarview = this.getCalendarView();
		var userDefaultActivityView = jQuery('#activity_view').val();

		calendarview.fullCalendar({
			header: {
				left: 'month,agendaWeek,agendaDay',
				center: 'title today',
				right: 'prev,next'
			},
			height: 600,
			defaultView: userDefaultActivityView,
			dayClick : function(date, allDay, jsEvent, view){thisInstance.dayClick(date, allDay, jsEvent, view);}
		});

		//To create custom button to create event or task
		jQuery('<span class="pull-left"><button class="btn">'+ app.vtranslate('LBL_ADD_EVENT_TASK') +'</button></span>')
				.prependTo(calendarview.find('.fc-header .fc-header-right')).on('click', 'button', function(e){
					thisInstance.getCalendarCreateView().then(function(data){
						var headerInstance = new Vtiger_Header_Js();
						headerInstance.handleQuickCreateData(data,{callbackFunction:function(data){
								thisInstance.addCalendarEvent(data.result);
						}});
					});
					
				})

	},

	addCalendarEvent : function(calendarDetails) {
		//If type is not shown then dont render the created event
		if(jQuery('[data-calendar-feed="Tasks"]').length <= 0 ) return;

		var eventObject = {};
		eventObject.id = calendarDetails._recordId;
		eventObject.title = calendarDetails.subject.display_value;
		var startDate = Date.parse(calendarDetails.date_start.calendar_display_value);
		eventObject.start = startDate.toString();
		var endDate = Date.parse(calendarDetails.due_date.calendar_display_value);
		eventObject.end = endDate.toString();
		eventObject.url = 'index.php?module=Calendar&view=Detail&record='+calendarDetails._recordId;
		if(calendarDetails.activitytype.value == 'Task'){
			var cssClass = jQuery('[data-calendar-feed="Tasks"]').data('calendarFeedCss');
			eventObject.allDay = true;
		}else{
			var cssClass = jQuery('[data-calendar-feed="Events"]').data('calendarFeedCss');
			eventObject.allDay = false;
		}
		eventObject.className = cssClass;
		this.getCalendarView().fullCalendar('renderEvent',eventObject);
	},

    registerHandlerForCalendarQuickCreate : function() {
        var thisInstance = this;
        var headerInstance = Vtiger_Header_Js.getInstance();
        headerInstance.registerQuickCreateCallBack(function(params){
            if(params.name != 'Calendar') {
                return;
            }

            thisInstance.addCalendarEvent(params.data.result);
        })
    },

	registerEvents : function() {
		this.registerCalendar();
        this.registerHandlerForCalendarQuickCreate();
		jQuery('[data-widget-url="module=Calendar&view=ViewTypes&mode=getViewTypes"]').trigger('click');
		return this;
	}
});

jQuery(document).ready(function() {
	var instance = new Calendar_CalendarView_Js();
	instance.registerEvents()
	Calendar_CalendarView_Js.currentInstance = instance;
})