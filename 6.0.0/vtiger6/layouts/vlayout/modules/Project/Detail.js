/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_Detail_Js("Project_Detail_Js",{},{
	
	/**
	 * Function to register event for create related record
	 * in summary view widgets
	 */
	registerSummaryViewContainerEvents : function(summaryViewContainer){
		var thisInstance = this;
		this._super(summaryViewContainer);
		this.registerEventForTicketsWidget();
		this.registerEventForTasksWidget();
		this.registerEventForAddingModuleRelatedRecordFromSummaryWidget();
		summaryViewContainer.on(this.widgetPostLoad,function(e, params){
			if(params.widgetName == "Documents"){
//				thisInstance.registerEventForDocumentWidget();
			}
		})
	},
	
	/**
	* Function to get records according to ticket status
	*/
	registerEventForTicketsWidget : function(){
		var thisInstance = this;
		jQuery('[name="ticketstatus"]').on('change',function(e){
			var statusCondition = {};
			var currentElement = jQuery(e.currentTarget);
			var summaryWidgetContainer = currentElement.closest('.summaryWidgetContainer');
			var widgetDataContainer = summaryWidgetContainer.find('.widget_contents');
			var referenceModuleName = widgetDataContainer.find('[name="relatedModule"]').val();
			var recordId = thisInstance.getRecordId();
			var module = app.getModuleName();
			var selectedStatus = currentElement.find('option:selected').text();
			statusCondition['vtiger_troubletickets.status'] = selectedStatus;
			var params = {};
			params['record'] = recordId;
			params['view'] = 'Detail';
			params['module'] = module;
			params['page'] = widgetDataContainer.find('[name="page"]').val();
			params['limit'] = widgetDataContainer.find('[name="pageLimit"]').val();
			params['relatedModule'] = referenceModuleName;
			params['mode'] = 'showRelatedRecords';
			params['whereCondition'] = statusCondition;
			AppConnector.request(params).then(
				function(data) {
					widgetDataContainer.html(data);
					thisInstance.registerEventForTicketsWidget();
				}
			);
	   })
	},
	
	/**
	* Function to get records according to task status
	*/
	registerEventForTasksWidget : function(){
		var thisInstance = this;
		jQuery('[name="projecttaskstatus"]').on('change',function(e){
			var statusCondition = {};
			var currentElement = jQuery(e.currentTarget);
			var summaryWidgetContainer = currentElement.closest('.summaryWidgetContainer');
			var widgetDataContainer = summaryWidgetContainer.find('.widget_contents');
			var referenceModuleName = widgetDataContainer.find('[name="relatedModule"]').val();
			var recordId = thisInstance.getRecordId();
			var module = app.getModuleName();
			var selectedStatus = currentElement.find('option:selected').text();
			statusCondition['vtiger_projecttask.projecttaskstatus'] = selectedStatus;
			var params = {};
			params['record'] = recordId;
			params['view'] = 'Detail';
			params['module'] = module;
			params['page'] = widgetDataContainer.find('[name="page"]').val();
			params['limit'] = widgetDataContainer.find('[name="pageLimit"]').val();
			params['relatedModule'] = referenceModuleName;
			params['mode'] = 'showRelatedRecords';
			params['whereCondition'] = statusCondition;
			AppConnector.request(params).then(
				function(data) {
					widgetDataContainer.html(data);
					thisInstance.registerEventForTicketsWidget();
				}
			);
	   })
	},
	
	/**
	 * Function to add module related record from summary widget
	 */
	registerEventForAddingModuleRelatedRecordFromSummaryWidget : function(){
		var thisInstance = this;
		jQuery('#createProjectMileStone,#createProjectTask').on('click',function(e){
			console.log("triggered");
			var currentElement = jQuery(e.currentTarget);
			var quickcreateUrl = currentElement.data('url');
			var parentId = thisInstance.getRecordId();
			var quickCreateParams = {};
			var relatedField = currentElement.data('parentRelatedField');
			var moduleName = currentElement.closest('.widget_header').find('[name="relatedModule"]').val();
			var relatedParams = {};
			relatedParams[relatedField] = parentId;
			console.log(quickcreateUrl);
			
			var postQuickCreateSave = function(data) {
				thisInstance.postSummaryWidgetAddRecord(data,currentElement);
			}
			
			if(typeof relatedField != "undefined"){
				quickCreateParams['data'] = relatedParams;
			}
			quickCreateParams['noCache'] = true;
			quickCreateParams['callbackFunction'] = postQuickCreateSave;
			var progress = jQuery.progressIndicator();
			var headerInstance = new Vtiger_Header_Js();
			headerInstance.getQuickCreateForm(quickcreateUrl, moduleName,quickCreateParams).then(function(data){
				headerInstance.handleQuickCreateData(data,quickCreateParams);
				progress.progressIndicator({'mode':'hide'});
			});
		})
	},
	
	/**
	 * Function to register event for documet widget
	 */
	registerEventForDocumentWidget : function(){
		jQuery('#documentRelatedRecord').on('hover',function(e){
			var currentElement = jQuery(e.currentTarget);
			var downloadableFile = currentElement.find('#DownloadableLink');
			if(downloadableFile.length > 0){
				if(downloadableFile.hasClass('hide')){
					downloadableFile.removeClass('hide');
				} else {
					downloadableFile.addClass('hide');
				}
			}
		})
	}
})