/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

jQuery.Class("Vtiger_DashBoard_Js", {
	gridster : false,

	//static property which will store the instance of dashboard
	currentInstance : false,

	getInstance: function(){
		var module = app.getModuleName();
		var moduleClassName = module+"_DashBoard_Js";
		var fallbackClassName = Vtiger_DashBoard_Js;
		if(typeof window[moduleClassName] != 'undefined'){
			var instance = new window[moduleClassName]();
		}else{
			var instance = new fallbackClassName();
		}
		return instance;
	},

		addWidget : function(element, url) {
			var element = jQuery(element);
			var linkId = element.data('linkid');
			var name = element.data('name');
			jQuery(element).parent().hide();
			var widgetContainer = jQuery('<li class="new dashboardWidget" id="'+ linkId +'" data-name="'+name+'" data-mode="open"></li>');
			widgetContainer.data('url', url);
			var width = element.data('width');
			var height = element.data('height');
			Vtiger_DashBoard_Js.gridster.add_widget(widgetContainer, width, height);
			Vtiger_DashBoard_Js.currentInstance.loadWidget(widgetContainer);
		},


	restrictContentDrag : function(container){
		container.on('mousedown.draggable', function(e){
			var element = jQuery(e.target);
			var isHeaderElement = element.closest('.dashboardWidgetHeader').length > 0 ? true : false;
			if(isHeaderElement){
				return;
			}
			//Stop the event propagation so that drag will not start for contents
			e.stopPropagation();
		})
	}

}, {

	container : false,

	instancesCache : {},

	getContainer : function() {
		if(this.container == false) {
			this.container = jQuery('.gridster ul');
		}
		return this.container;
	},

	getWidgetInstance : function(widgetContainer) {
		var id = widgetContainer.attr('id');
		if(!(id in this.instancesCache)) {
			var widgetName = widgetContainer.data('name');
			this.instancesCache[id] = Vtiger_Widget_Js.getInstance(widgetContainer, widgetName);
		}
		return this.instancesCache[id];
	},

	registerGridster : function() {
		Vtiger_DashBoard_Js.gridster = this.getContainer().gridster({
			widget_margins: [7, 7],
			widget_base_dimensions: [100, 300],
			min_cols: 6,
			min_rows: 20
		}).data('gridster');
	},

	loadWidgets : function() {
		var thisInstance = this;
		var widgetList = jQuery('.dashboardWidget');
		widgetList.each(function(index,widgetContainerELement){
			thisInstance.loadWidget(jQuery(widgetContainerELement));
		});

	},

	loadWidget : function(widgetContainer) {
		var thisInstance = this;
		var urlParams = widgetContainer.data('url');
		var mode = widgetContainer.data('mode');
		widgetContainer.progressIndicator();
		if(mode == 'open') {
			AppConnector.request(urlParams).then(
				function(data){
					widgetContainer.html(data);
					app.showScrollBar(widgetContainer.find('.dashboardWidgetContent'));
					var widgetInstance = thisInstance.getWidgetInstance(widgetContainer);
					widgetContainer.trigger(Vtiger_Widget_Js.widgetPostLoadEvent);
				},
				function(){
				}
				);
		} else {
	}
	},


	registerEvents : function() {
		this.registerGridster();
		this.loadWidgets();

		this.registerRefreshWidget();

		this.showWidgetIcons();
		this.hideWidgetIcons();
		this.removeWidget();
		this.registerFilterIntiater();

		this.gridsterStop();

	},

	gridsterStop : function() {
		// TODO: we need to allow the header of the widget to be draggable
		var gridster = Vtiger_DashBoard_Js.gridster;

	},

	registerRefreshWidget : function() {
		var thisInstance = this;
		this.getContainer().on('click', 'a[name="drefresh"]', function(e) {
			var element = $(e.currentTarget);
			var parent = element.closest('li');
			var widgetInstnace = thisInstance.getWidgetInstance(parent);
			widgetInstnace.refreshWidget();
			return;
		});
	},

	showWidgetIcons : function() {
		this.getContainer().on('mouseover', 'li', function(e) {
			var element = $(e.currentTarget);
			var widgetIcons = element.find('.widgeticons');
			widgetIcons.fadeIn('slow', function() {
				widgetIcons.css('visibility', 'visible');
			});
		});
	},

	hideWidgetIcons : function() {
		this.getContainer().on('mouseout', 'li', function(e) {
			var element = $(e.currentTarget);
			var widgetIcons = element.find('.widgeticons');
			widgetIcons.css('visibility', 'hidden');
		});
	},

	removeWidget : function() {
		this.getContainer().on('click', 'li a[name="dclose"]', function(e) {
			var element = $(e.currentTarget);
			var url = element.data('url');
			AppConnector.request(url).then(
				function(response) {
					if (response.success) {
						var parent = element.closest('.dashboardWidgetHeader').parent();
						parent.fadeOut('slow', function() {
							parent.remove();
						});
						
						var data = '<li><a onclick="Vtiger_DashBoard_Js.addWidget(this, \''+response.result.url+'\')" href="javascript:void(0);"';
						data += ' data-linkid='+response.result.linkid+' data-name='+response.result.name+'>'+response.result.title+'</a></li>';
						jQuery('.widgetsList').append(data);
					}
				}
				);
		});
	},

	registerFilterIntiater : function() {
		var container = this.getContainer();
		container.on('click', 'a[name="dfilter"]', function(e){
			var widgetContainer = jQuery(e.currentTarget).closest('.dashboardWidget');
			widgetContainer.find('.filterContainer').slideToggle(500);
		})
	}
});

jQuery(document).ready(function() {
	var dashboardInstance = Vtiger_DashBoard_Js.getInstance();
	Vtiger_DashBoard_Js.currentInstance = dashboardInstance;
	dashboardInstance.registerEvents();
});
