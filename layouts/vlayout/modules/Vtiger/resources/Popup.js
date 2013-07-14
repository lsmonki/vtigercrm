/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
jQuery.Class("Vtiger_Popup_Js",{

    getInstance: function(){
	    var module = app.getModuleName();
		var className = jQuery('#popUpClassName').val();
		if(typeof className != 'undefined'){
			var moduleClassName = className;
		}else{
			var moduleClassName = module+"_Popup_Js";
		}
		var fallbackClassName = Vtiger_Popup_Js;
	    if(typeof window[moduleClassName] != 'undefined'){
			var instance = new window[moduleClassName]();
		}else{
			var instance = new fallbackClassName();
		}
	    return instance;
	}

},{

	//holds the event name that child window need to trigger
	eventName : '',
	popupPageContentsContainer : false,
	sourceModule : false,
	sourceRecord : false,
	sourceField : false,
	multiSelect : false,
	relatedParentModule : false,
	relatedParentRecord : false,

	/**
	 * Function to get source module
	 */
	getSourceModule : function(){
		if(this.sourceModule == false){
			this.sourceModule = jQuery('#parentModule').val();
		}
		return this.sourceModule;
	},

	/**
	 * Function to get source record
	 */
	getSourceRecord : function(){
		if(this.sourceRecord == false){
			this.sourceRecord = jQuery('#sourceRecord').val();
		}
		return this.sourceRecord;
	},

	/**
	 * Function to get source field
	 */
	getSourceField : function(){
		if(this.sourceField == false){
			this.sourceField = jQuery('#sourceField').val();
		}
		return this.sourceField;
	},

	/**
	 * Function to get related parent module
	 */
	getRelatedParentModule : function(){
		if(this.relatedParentModule == false){
			this.relatedParentModule = jQuery('#relatedParentModule').val();
		}
		return this.relatedParentModule;
	},
	/**
	 * Function to get related parent id
	 */
	getRelatedParentRecord : function(){
		if(this.relatedParentRecord == false){
			this.relatedParentRecord = jQuery('#relatedParentId').val();
		}
		return this.relatedParentRecord;
	},

	/**
	 * Function to get Search key
	 */
	getSearchKey : function(){
		return jQuery('#searchableColumnsList').val();
	},

	/**
	 * Function to get Search value
	 */
	getSearchValue : function(){
		return jQuery('#searchvalue').val();
	},

	/**
	 * Function to get Order by
	 */
	getOrderBy : function(){
		return jQuery('#orderBy').val();
	},

	/**
	 * Function to get Sort Order
	 */
	getSortOrder : function(){
			return jQuery("#sortOrder").val();
	},

	/**
	 * Function to get Page Number
	 */
	getPageNumber : function(){
		return jQuery('#pageNumber').val();
	},

	getPopupPageContainer : function(){
		if(this.popupPageContentsContainer == false) {
			this.popupPageContentsContainer = jQuery('#popupPageContainer');
		}
		return this.popupPageContentsContainer;

	},

	show : function(urlOrParams, cb, windowName, eventName, onLoadCb){
		if(typeof urlOrParams == 'undefined'){
			urlOrParams = {};
		}
		if (typeof urlOrParams == 'object' && (typeof urlOrParams['view'] == "undefined")) {
			urlOrParams['view'] = 'Popup';
		}

		// Target eventName to be trigger post data selection.
		if(typeof eventName == 'undefined') {
			eventName = 'postSelection'+ Math.floor(Math.random() * 10000);
		}
		if(typeof windowName == 'undefined' ){
			windowName = 'test';
		}
		if (typeof urlOrParams == 'object') {
			urlOrParams['triggerEventName'] = eventName;
		} else {
			urlOrParams += '&triggerEventName=' + eventName;
		}

		var urlString = (typeof urlOrParams == 'string')? urlOrParams : jQuery.param(urlOrParams);
		var url = 'index.php?'+urlString;
		var popupWinRef =  window.open(url, windowName ,'width=800,height=650,resizable=0,scrollbars=1');
		if (typeof this.destroy == 'function') {
			// To remove form elements that have created earlier
			this.destroy();
		}
		jQuery.initWindowMsg();

		if(typeof cb != 'undefined') {
			this.retrieveSelectedRecords(cb, eventName);
		}

		 if(typeof onLoadCb == 'function') {
			jQuery.windowMsg('Vtiger.OnPopupWindowLoad.Event', function(data){
				onLoadCb(data);
            })
        }

		// http://stackoverflow.com/questions/13953321/how-can-i-call-a-window-child-function-in-javascript
		// This could be useful for the caller to invoke child window methods post load.
		return popupWinRef;
	},

	retrieveSelectedRecords : function(cb, eventName) {
		if(typeof eventName == 'undefined') {
			eventName = 'postSelection';
		}

		jQuery.windowMsg(eventName, function(data) {
			cb(data);
		});
	},

	/**
	 * Function which removes the elements that are added by the plugin
	 *
	 */
	destroy : function(){
		jQuery('form[name="windowComm"]').remove();
	},

	done : function(result, eventToTrigger, window) {

		if(typeof eventToTrigger == 'undefined' || eventToTrigger.length <=0 ) {
			eventToTrigger = 'postSelection'
		}

		if(typeof window == 'undefined'){
			window = self;
		}
		window.close();

		jQuery.triggerParentEvent(eventToTrigger, JSON.stringify(result));

	},

	getView : function(){
	    var view = jQuery('#view').val();
	    if(view == '') {
		    view = 'PopupAjax';
	    } else {
		    view = view+'Ajax';
	    }
	    return view;
	},

	setEventName : function(eventName) {
		this.eventName = eventName;
	},

	getEventName : function() {
		return this.eventName;
	},

	isMultiSelectMode : function() {
		if(this.multiSelect == false){
			this.multiSelect = jQuery('#multi_select');
		}
		var value = this.multiSelect.val();
		if(value) {
			return value;
		}
		return false;
	},

	getListViewEntries: function(e){
		var thisInstance = this;
		var row  = jQuery(e.currentTarget);
		var dataUrl = row.data('url');
		if(typeof dataUrl != 'undefined'){
			dataUrl = dataUrl+'&currency_id='+jQuery('#currencyId').val();
		    AppConnector.request(dataUrl).then(
			function(data){
				for(var id in data){
				    if(typeof data[id] == "object"){
					var recordData = data[id];
				    }
				}
				thisInstance.done(recordData, thisInstance.getEventName());
				e.preventDefault();
			},
			function(error,err){

			}
		    );
		} else {
		    var id = row.data('id');
		    var recordName = row.data('name');
			var recordInfo = row.data('info');
		    var response ={};
		    response[id] = {'name' : recordName,'info' : recordInfo} ;
			thisInstance.done(response, thisInstance.getEventName());
		    e.preventDefault();
		}

	},

	registerSelectButton : function(){
		var popupPageContentsContainer = this.getPopupPageContainer();
		var thisInstance = this;
		popupPageContentsContainer.on('click','button.select', function(e){
			var tableEntriesElement = popupPageContentsContainer.find('table');
			var selectedRecordDetails = {};
			var recordIds = new Array();
			var dataUrl;
			jQuery('input.entryCheckBox', tableEntriesElement).each(function(index, checkBoxElement){
				var checkBoxJqueryObject = jQuery(checkBoxElement)
				if(! checkBoxJqueryObject.is(":checked")){
					return true;
				}
				var row = checkBoxJqueryObject.closest('tr');
				var id = row.data('id');
				recordIds.push(id);
				var name = row.data('name');
				dataUrl = row.data('url');
				selectedRecordDetails[id] = {'name' : name};
			});
			var jsonRecorIds = JSON.stringify(recordIds);
			if(Object.keys(selectedRecordDetails).length <= 0) {
				alert(app.vtranslate('JS_PLEASE_SELECT_ONE_RECORD'));
			}else{
				if(typeof dataUrl != 'undefined'){
				    dataUrl = dataUrl+'&idlist='+jsonRecorIds+'&currency_id='+jQuery('#currencyId').val();
				    AppConnector.request(dataUrl).then(
					function(data){
						for(var id in data){
						    if(typeof data[id] == "object"){
							var recordData = data[id];
						    }
						}
						var recordDataLength = Object.keys(recordData).length;
						if(recordDataLength == 1){
							recordData = recordData[0];
						}
						thisInstance.done(recordData, thisInstance.getEventName());
						e.preventDefault();
					},
					function(error,err){

					}
				);
				}else{
				    thisInstance.done(selectedRecordDetails, thisInstance.getEventName());
				}
			}
		});
	},

	selectAllHandler : function(e){
		var currentElement = jQuery(e.currentTarget);
		var isMainCheckBoxChecked = currentElement.is(':checked');
		var tableElement = currentElement.closest('table');
		if(isMainCheckBoxChecked) {
			jQuery('input.entryCheckBox', tableElement).attr('checked','checked').closest('tr').addClass('highlightBackgroundColor');
		}else {
			jQuery('input.entryCheckBox', tableElement).removeAttr('checked').closest('tr').removeClass('highlightBackgroundColor');
		}
	},

	registerEventForSelectAllInCurrentPage : function(){
		var thisInstance = this;
		var popupPageContentsContainer = this.getPopupPageContainer();
		popupPageContentsContainer.on('change','input.selectAllInCurrentPage',function(e){
			thisInstance.selectAllHandler(e);
		});
	},

	checkBoxChangeHandler : function(e){
		var elem = jQuery(e.currentTarget);
		var parentElem = elem.closest('tr');
		if(elem.is(':checked')){
			parentElem.addClass('highlightBackgroundColor');

		}else{
			parentElem.removeClass('highlightBackgroundColor');
		}
	},

	/**
	 * Function to register event for entry checkbox change
	 */
	registerEventForCheckboxChange : function(){
		var thisInstance = this;
		var popupPageContentsContainer = this.getPopupPageContainer();
		popupPageContentsContainer.on('click','input.entryCheckBox',function(e){
			e.stopPropagation();
			thisInstance.checkBoxChangeHandler(e);
		});
	},
	/**
	 * Function to get complete params
	 */
	getCompleteParams : function(){
		var params = {};
		params['view'] = this.getView();
		params['src_module'] = this.getSourceModule();
		params['src_record'] = this.getSourceRecord();
		params['src_field'] = this.getSourceField();
		params['search_key'] =  this.getSearchKey();
		params['search_value'] =  this.getSearchValue();
		params['orderby'] =  this.getOrderBy();
		params['sortorder'] =  this.getSortOrder();
		params['page'] = this.getPageNumber();
		params['related_parent_module'] = this.getRelatedParentModule();
		params['related_parent_id'] = this.getRelatedParentRecord();

		if(this.isMultiSelectMode()) {
			params['multi_select'] = true;
		}
		return params;
	},

	/**
	 * Function to get Page Records
	 */
	getPageRecords : function(params){
		var aDeferred = jQuery.Deferred();
		var progressIndicatorElement = jQuery.progressIndicator({
			'position' : 'html',
			'blockInfo' : {
				'enabled' : true
			}
		});
		Vtiger_BaseList_Js.getPageRecords(params).then(
				function(data){
					jQuery('#popupContents').html(data);
					progressIndicatorElement.progressIndicator({
						'mode' : 'hide'
					})
					aDeferred.resolve(data);
				},

				function(textStatus, errorThrown){
					aDeferred.reject(textStatus, errorThrown);
				}
			);
		return aDeferred.promise();
	},
	/**
	 * Function to handle search event
	 */

	searchHandler : function(){
		var completeParams = this.getCompleteParams();
		completeParams['page'] = 1;
		return this.getPageRecords(completeParams);
	},

	/**
	 * Function to register event for Search
	 */
	registerEventForSearch : function(){
		var thisInstance = this;
		var popupPageContentsContainer = this.getPopupPageContainer();
		jQuery('#popupSearchButton').on('click',function(e){
			thisInstance.searchHandler();
		});
	},

	/**
	 * Function to handle Sort
	 */
	sortHandler : function(headerElement){
		var fieldName = headerElement.data('columnname');
		var sortOrderVal = headerElement.data('nextsortorderval');
		var sortingParams = {
			"orderby" : fieldName,
			"sortorder" : sortOrderVal
		}
		var completeParams = this.getCompleteParams();
		jQuery.extend(completeParams,sortingParams);
		return this.getPageRecords(completeParams);
	},

	/**
	 * Function to register Event for Sorting
	 */
	registerEventForSort : function(){
		var thisInstance = this;
		var popupPageContentsContainer = this.getPopupPageContainer();
		popupPageContentsContainer.on('click','.listViewHeaderValues',function(e){
			var element = jQuery(e.currentTarget);
			thisInstance.sortHandler(element);
		});
	},

	/**
	 * Function to handle Sort
	 */
	sortHandler : function(headerElement){
		var fieldName = headerElement.data('columnname');
		var sortOrderVal = headerElement.data('nextsortorderval');
		var sortingParams = {
			"orderby" : fieldName,
			"sortorder" : sortOrderVal
		}
		var completeParams = this.getCompleteParams();
		jQuery.extend(completeParams,sortingParams);
		return this.getPageRecords(completeParams);
	},

	/**
	 * Function to register Event for Sorting
	 */
	registerEventForSort : function(){
		var thisInstance = this;
		var popupPageContentsContainer = this.getPopupPageContainer();
		popupPageContentsContainer.on('click','.listViewHeaderValues',function(e){
			var element = jQuery(e.currentTarget);
			thisInstance.sortHandler(element);
		});
	},

	/**
	 * Function to handle next page navigation
	 */

	nextPageHandler : function(){
		var aDeferred = jQuery.Deferred();
		var pageLimit = jQuery('#pageLimit').val();
		var noOfEntries = jQuery('#noOfEntries').val();
		if(noOfEntries == pageLimit){
			var pageNumber = jQuery('#pageNumber').val();
			var nextPageNumber = parseInt(pageNumber) + 1;
			var pagingParams = {
					"page": nextPageNumber
				}
			var completeParams = this.getCompleteParams();
			jQuery.extend(completeParams,pagingParams);
			this.getPageRecords(completeParams).then(
				function(data){
					jQuery('#pageNumber').val(nextPageNumber);
					aDeferred.resolve(data);
				},

				function(textStatus, errorThrown){
					aDeferred.reject(textStatus, errorThrown);
				}
			);
		}
		return aDeferred.promise();
	},

	/**
	 * Function to handle Previous page navigation
	 */
	previousPageHandler : function(){
		var aDeferred = jQuery.Deferred();
		var pageNumber = jQuery('#pageNumber').val();
		var previousPageNumber = parseInt(pageNumber) - 1;
		if(pageNumber > 1){
			var pagingParams = {
				"page": previousPageNumber
			}
			var completeParams = this.getCompleteParams();
			jQuery.extend(completeParams,pagingParams);
			this.getPageRecords(completeParams).then(
				function(data){
					jQuery('#pageNumber').val(previousPageNumber);
					aDeferred.resolve(data);
				},

				function(textStatus, errorThrown){
					aDeferred.reject(textStatus, errorThrown);
				}
			);
		}
		return aDeferred.promise();
	},

	/**
	 * Function to register event for Paging
	 */
	registerEventForPagination : function(){
		var thisInstance = this;
		var popupPageContentsContainer = this.getPopupPageContainer();
		popupPageContentsContainer.on('click','#listViewNextPageButton',function(){
			thisInstance.nextPageHandler();
		});
		popupPageContentsContainer.on('click','#listViewPreviousPageButton',function(){
			thisInstance.previousPageHandler();
		});
	},

	registerEventForListViewEntries : function(){
		var thisInstance = this;
		var popupPageContentsContainer = this.getPopupPageContainer();
		popupPageContentsContainer.on('click','.listViewEntries',function(e){
		    thisInstance.getListViewEntries(e);
		});
	},

	triggerDisplayTypeEvent : function() {
		var widthType = app.cacheGet('widthType', 'narrowWidthType');
		if(widthType) {
			var elements = jQuery('.listViewEntriesTable').find('td,th');
			elements.addClass(widthType);
		}
	},

	registerEvents: function(){
		var thisInstance = this;
		var pageNumber = jQuery('#pageNumber').val();
		if(pageNumber == 1){
			jQuery('#listViewPreviousPageButton').attr("disabled", "disabled");
		}
		var popupPageContentsContainer = this.getPopupPageContainer();
		this.registerEventForSelectAllInCurrentPage();
		this.registerSelectButton();
		this.registerEventForCheckboxChange();
		this.registerEventForSearch();
		this.registerEventForSort();
		this.registerEventForPagination();
		this.registerEventForListViewEntries();
		this.triggerDisplayTypeEvent();
	}
});
jQuery(document).ready(function() {
	var popupInstance = Vtiger_Popup_Js.getInstance();
	var triggerEventName = jQuery('.triggerEventName').val();
	var documentHeight = (jQuery(document).height())+'px';
	jQuery('#popupPageContainer').css('height',documentHeight);
	popupInstance.setEventName(triggerEventName);
	popupInstance.registerEvents();
});