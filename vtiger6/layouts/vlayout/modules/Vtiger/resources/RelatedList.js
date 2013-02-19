/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

jQuery.Class("Vtiger_RelatedList_Js",{},{
	
	selectedRelatedTabElement : false,
	parentRecordId : false,
	parentModuleName : false,
	relatedModulename : false,
	relatedTabsContainer : false,
	detailViewContainer : false,
	relatedContentContainer : false,
	
	setSelectedTabElement : function(tabElement) {
		this.selectedRelatedTabElement = tabElement;
	},
	
	getSelectedTabElement : function(){
		return this.selectedRelatedTabElement;
	},
	
	getParentId : function(){
		return this.parentRecordId;
	},
	
	loadRelatedList : function(params){
		var aDeferred = jQuery.Deferred();
		var thisInstance = this;
		if(typeof this.relatedModulename== "undefined" || this.relatedModulename.length <= 0 ) {
			return;
		}
		var progressIndicatorElement = jQuery.progressIndicator({
			'position' : 'html',
			'blockInfo' : {
				'enabled' : true
			}
		});
		var completeParams = this.getCompleteParams();
		jQuery.extend(completeParams,params);
		AppConnector.request(completeParams).then(
			function(responseData){
				jQuery('.contents').html(responseData);
				progressIndicatorElement.progressIndicator({
					'mode' : 'hide'
				})
				aDeferred.resolve(responseData);
				thisInstance.relatedTabsContainer.find('li').removeClass('active');
				thisInstance.selectedRelatedTabElement.addClass('active');
				thisInstance.relatedContentContainer.html(responseData);
				aDeferred.resolve(responseData);
				jQuery('input[name="currentPageNum"]', thisInstance.relatedContentContainer).val(completeParams.page);
			},
			
			function(textStatus, errorThrown){
				aDeferred.reject(textStatus, errorThrown);
			}
		);
		return aDeferred.promise();
	},
	
	showSelectRelationPopup : function(){
		var thisInstance = this;
		var popupInstance = Vtiger_Popup_Js.getInstance();
		popupInstance.show(this.getPopupParams(), function(responseString){
				var responseData = JSON.parse(responseString);
				var relatedIdList = Object.keys(responseData);
				thisInstance.addRelations(relatedIdList).then(
					function(data){
						var relatedCurrentPage = thisInstance.getCurrentPageNum();
						var params = {'page':relatedCurrentPage};
						thisInstance.loadRelatedList(params);
					}
				);
			}
		);
	},

	addRelations : function(idList){
		var aDeferred = jQuery.Deferred();
		var sourceRecordId = this.parentRecordId;
		var sourceModuleName = this.parentModuleName;
		var relatedModuleName = this.relatedModulename;

		var params = {};
		params['mode'] = "addRelation";
		params['module'] = sourceModuleName;
		params['action'] = 'RelationAjax';
		
		params['related_module'] = relatedModuleName;
		params['src_record'] = sourceRecordId;
		params['related_record_list'] = JSON.stringify(idList);

		AppConnector.request(params).then(
			function(responseData){
				aDeferred.resolve(responseData);
			},

			function(textStatus, errorThrown){
				aDeferred.reject(textStatus, errorThrown);
			}
		);
		return aDeferred.promise();
	},

	getPopupParams : function(){
		var parameters = {};
		var parameters = {
			'module' : this.relatedModulename,
			'src_module' : this.parentModuleName,
			'src_record' : this.parentRecordId,
			'multi_select' : true
		}
		return parameters;
	},

	deleteRelation : function(relatedIdList) {
		var aDeferred = jQuery.Deferred();
		var params = {};
		params['mode'] = "deleteRelation";
		params['module'] = this.parentModuleName;
		params['action'] = 'RelationAjax';

		params['related_module'] = this.relatedModulename;
		params['src_record'] = this.parentRecordId;
		params['related_record_list'] = JSON.stringify(relatedIdList);

		AppConnector.request(params).then(
			function(responseData){
				aDeferred.resolve(responseData);
			},

			function(textStatus, errorThrown){
				aDeferred.reject(textStatus, errorThrown);
			}
		);
		return aDeferred.promise();
	},

	getCurrentPageNum : function() {
		return jQuery('input[name="currentPageNum"]',this.relatedContentContainer).val();
	},
	
	setCurrentPageNumber : function(pageNumber){
		jQuery('input[name="currentPageNum"]').val(pageNumber);
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
	
	getCompleteParams : function(){
		var params = {};
		params['view'] = "Detail";
		params['module'] = this.parentModuleName;
		params['record'] = this.getParentId(),
		params['relatedModule'] = this.relatedModulename,
		params['sortorder'] =  this.getSortOrder(),
		params['orderby'] =  this.getOrderBy(),
		params['page'] = this.getCurrentPageNum();
		params['mode'] = "showRelatedList"
		
		return params;
	},
	
	
	/**
	 * Function to handle Sort
	 */
	sortHandler : function(headerElement){
		var fieldName = headerElement.data('fieldname');
		var sortOrderVal = headerElement.data('nextsortorderval');
		var sortingParams = {
			"orderby" : fieldName,
			"sortorder" : sortOrderVal,
			"tab_label" : this.selectedRelatedTabElement.data('label-key')
		}
		return this.loadRelatedList(sortingParams);
	},
	
	/**
	 * Function to handle next page navigation
	 */
	nextPageHandler : function(){
		var thisInstance = this;
		var aDeferred = jQuery.Deferred();
		var pageLimit = jQuery('#pageLimit').val();
		var noOfEntries = jQuery('#noOfEntries').val();
		if(noOfEntries == pageLimit){
			var pageNumber = this.getCurrentPageNum();
			var nextPage = parseInt(pageNumber) + 1;
			var nextPageParams = {
				'page' : nextPage
			}
			this.loadRelatedList(nextPageParams).then(
				function(data){
					thisInstance.setCurrentPageNumber(nextPage);
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
	 * Function to handle next page navigation
	 */
	previousPageHandler : function(){
		var thisInstance = this;
		var aDeferred = jQuery.Deferred();
		var pageNumber = this.getCurrentPageNum();
		if(pageNumber > 1){
			var previousPage = parseInt(pageNumber) - 1;
			var previousPageParams = {
				'page' : previousPage
			}
			this.loadRelatedList(previousPageParams).then(
				function(data){
					thisInstance.setCurrentPageNumber(previousPage);
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
	 * Function to handle page jump in related list
	 */
	pageJumpHandler : function(e){
		var thisInstance = this;
		if(e.which == 13){
			var element = jQuery(e.currentTarget);
			var response = Vtiger_WholeNumberGreaterThanZero_Validator_Js.invokeValidation(element);
			if(typeof response != "undefined"){
				element.validationEngine('showPrompt',response,'',"topLeft",true);
				e.preventDefault();
			} else {
				element.validationEngine('hideAll');
				var jumpToPage = element.val();
				var jumptoPageParams = {
					'page' : jumpToPage
				}
				this.loadRelatedList(jumptoPageParams).then(
					function(data){
						thisInstance.setCurrentPageNumber(jumpToPage);
					},

					function(textStatus, errorThrown){
					}
				);
			}
		}
	},
	/**
	 * Function to add related record for the module
	 */
	addRelatedRecord : function(element){
		var thisInstance = this;
		var	referenceModuleName = this.relatedModulename;
		var parentId = this.getParentId();
		var parentModule = this.parentModuleName;
		var quickCreateParams = {};
		var relatedParams = {};
		var relatedField = element.data('name');
		var fullFormUrl = element.data('url');
		relatedParams[relatedField] = parentId;
		var preQuickCreateSave = function(data){
			data.find('#goToFullForm').data('editViewUrl',fullFormUrl);
			if(typeof relatedField != "undefined"){
				var field = data.find('[name="'+relatedField+'"]');
				//If their is no element with the relatedField name,we are adding hidden element with
				//name as relatedField name,for saving of record with relation to parent record
				if(field.length == 0){
					jQuery('<input type="hidden" name="'+relatedField+'" value="'+parentId+'" />').appendTo(data);
				}
			}

			jQuery('<input type="hidden" name="sourceModule" value="'+parentModule+'" />').appendTo(data);
			jQuery('<input type="hidden" name="sourceRecord" value="'+parentId+'" />').appendTo(data);
			jQuery('<input type="hidden" name="relationOperation" value="true" />').appendTo(data);

		}
		var postQuickCreateSave  = function(data) {
			thisInstance.loadRelatedList();
		}
		
		if(typeof relatedField != "undefined"){
			quickCreateParams['data'] = relatedParams;
		}
		quickCreateParams['callbackFunction'] = postQuickCreateSave;
		quickCreateParams['callbackPostShown'] = preQuickCreateSave;
		quickCreateParams['noCache'] = true;
		var quickCreateNode = jQuery('#quickCreateModules').find('[data-name="'+ referenceModuleName +'"]');
		if(quickCreateNode.length <= 0) {
			Vtiger_Helper_Js.showPnotify(app.vtranslate('JS_NO_CREATE_OR_NOT_QUICK_CREATE_ENABLED'))
		}
		quickCreateNode.trigger('click',quickCreateParams);
	},
	
	getRelatedPageCount : function(){
		var params = {};
		params['action'] = "RelationAjax";
		params['module'] = this.parentModuleName;
		params['record'] = this.getParentId(),
		params['relatedModule'] = this.relatedModulename,
		params['tab_label'] = this.selectedRelatedTabElement.data('label-key');
		params['mode'] = "getRelatedListPageCount"
		
		var element = jQuery('#totalPageCount');
		var totalPageNumber = element.text();
		if(totalPageNumber == ""){
			element.progressIndicator({});
			AppConnector.request(params).then(
				function(data) {
					var pageCount = data['result']['page'];
					element.text(pageCount);
					element.progressIndicator({'mode': 'hide'});
				},
				function(error,err){

				}
			);
		}
	},

	
	init : function(parentId, parentModule, selectedRelatedTabElement, relatedModuleName){
		this.selectedRelatedTabElement = selectedRelatedTabElement,
		this.parentRecordId = parentId;
		this.parentModuleName = parentModule;
		this.relatedModulename = relatedModuleName;
		this.relatedTabsContainer = selectedRelatedTabElement.closest('div.related');
		this.detailViewContainer = this.relatedTabsContainer.closest('div.detailViewContainer');
		this.relatedContentContainer = jQuery('div.contents',this.detailViewContainer);
	}
})