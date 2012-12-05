/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

jQuery.Class("Vtiger_Detail_Js",{

	getInstance: function(){
	    var module = app.getModuleName();
	    var moduleClassName = module+"_Detail_Js";
		var fallbackClassName = Vtiger_Detail_Js;
	    if(typeof window[moduleClassName] != 'undefined'){
			var instance = new window[moduleClassName]();
		}else{
			var instance = new fallbackClassName();
		}
	    return instance;
	},



	/*
	 * function to trigger send Email
	 * @params: send email url , module name.
	 */
	triggerSendEmail : function(detailActionUrl, module){
        Vtiger_Helper_Js.checkServerConfig(module).then(function(data){
			if(data == true){
				var callBackFunction = function(data){
					var emailEditInstance = new Emails_MassEdit_Js();
					emailEditInstance.registerEmailFieldSelectionEvent();
				}
                Vtiger_Detail_Js.triggerDetailViewAction(detailActionUrl,callBackFunction);
			} else {
				alert(app.vtranslate('JS_EMAIL_SERVER_CONFIGURATION'));
			}
		});
	},

    /*
	 * function to trigger Detail view actions
	 * @params: Action url , callback function.
	 */
    triggerDetailViewAction : function(detailActionUrl, callBackFunction){
		var detailInstance = Vtiger_Detail_Js.getInstance();
        var selectedIds = new Array();
        selectedIds.push(detailInstance.getRecordId());
        var postData = {
           "selected_ids": JSON.stringify(selectedIds)
        };
        var actionParams = {
			"type":"POST",
			"url":detailActionUrl,
			"dataType":"html",
			"data" : postData
		};

        AppConnector.request(actionParams).then(
			function(data) {
				if(data) {
					app.showModalWindow(data,{'text-align' : 'left'});
					if(typeof callBackFunction == 'function'){
						callBackFunction(data);
					}
				}
			},
			function(error,err){

			}
		);
    },

    /*
	 * function to trigger send Sms
	 * @params: send sms url , module name.
	 */
    triggerSendSms : function(detailActionUrl, module) {
        Vtiger_Helper_Js.checkServerConfig(module).then(function(data){
			if(data == true){
                Vtiger_Detail_Js.triggerDetailViewAction(detailActionUrl);
			} else {
				alert(app.vtranslate('JS_SMS_SERVER_CONFIGURATION'));
			}
		});
    },

	/*
	 * function to trigger delete record action
	 * @params: delete record url.
	 */
    deleteRecord : function(deleteRecordActionUrl) {
		var message = app.vtranslate('LBL_DELETE_CONFIRMATION');
		Vtiger_Helper_Js.showConfirmationBox({'message' : message}).then(function(data) {
				AppConnector.request(deleteRecordActionUrl+'&ajaxDelete=true').then(
				function(data){
					if(data.success == true){
						window.location.href = data.result;
					}else{
						Vtiger_Helper_Js.showPnotify(data.error.message);
					}
				});
			},
			function(error, err){
			}
		);
	}

},{

	detailViewContentHolder : false,
	detailViewForm : false,
	detailViewSummaryTabLabel : 'LBL_RECORD_SUMMARY',
	detailViewRecentCommentsTabLabel : 'ModComments',
	detailViewRecentActivitiesTabLabel : 'Activities',
	detailViewRecentUpdatesTabLabel : 'LBL_UPDATES',

	fieldUpdatedEvent : 'Vtiger.Field.Updated',

	//Filels list on updation of which we need to upate the detailview header
	updatedFields : ['company','designation','title'],
	//Event that will triggered before saving the ajax edit of fields
	fieldPreSave : 'Vtiger.Field.PreSave',
	
	referenceFieldNames : {
		'Accounts' : 'parent_id',
		'Contacts' : 'contact_id',
		'Leads' : 'parent_id'
	},

	//constructor
	init : function() {

	},

	loadWidgets : function(){
		var thisInstance = this;
		var widgetList = jQuery('[class^="widgetContainer_"]');
		widgetList.each(function(index,widgetContainerELement){
			var widgetContainer = jQuery(widgetContainerELement);
			thisInstance.loadWidget(widgetContainer);
		});
	},
	
	loadWidget : function(widgetContainer) {
		var contentContainer = jQuery('.widget_contents',widgetContainer);
		var urlParams = widgetContainer.data('url');

		var params = {
			'type' : 'GET',
			'dataType': 'html',
			'data' : urlParams
		};
		contentContainer.progressIndicator({});
		AppConnector.request(params).then(
			function(data){
				contentContainer.progressIndicator({'mode': 'hide'});
				contentContainer.html(data);
			},
			function(){

			}
		);
	},
	
	/**
	 * Function to load only Comments Widget.
	 */
	//TODO improve this API.
	loadCommentsWidget : function() {

	},

	loadContents : function(url,data) {
		var thisInstance = this;
		var aDeferred = jQuery.Deferred();

		var detailContentsHolder = this.getContentHolder();
		var params = url;
		if(typeof data != 'undefined'){
			params = {};
			params.url = url;
			params.data = data;
		}
		AppConnector.requestPjax(params).then(
			function(reponseData){
				detailContentsHolder.html(reponseData);
				thisInstance.registerBlockStatusCheckOnLoad();
				//Make select box more usability
				app.changeSelectElementView(detailContentsHolder);
				//Attach date picker event to date fields
				app.registerEventForDatePickerFields(detailContentsHolder);
				thisInstance.getForm().validationEngine();
				aDeferred.resolve(reponseData);
			},
			function(){

			}
		);

		return aDeferred.promise();
	},

	getUpdatefFieldsArray : function(){
		return this.updatedFields;
	},

	postDetailViewLoadByMode : function(requestMode) {
		var detailViewTitleContainer = jQuery('.detailViewTitle');
		var modeChangingElement = jQuery('a.changeDetailViewMode',detailViewTitleContainer);
		var viewModeElement = jQuery('input[name="viewMode"]',detailViewTitleContainer);

		var prevModeName = viewModeElement.val();
		if(requestMode === prevModeName) {
			return;
		}
		var nextModeName = viewModeElement.data('nextviewname');
		var nextModeLabel = modeChangingElement.find('sub').html();
		var prevModeLabel = viewModeElement.data('currentviewlabel');

		modeChangingElement.find('sub').html(prevModeLabel);
		viewModeElement.data('nextviewname',prevModeName).data('currentviewlabel',nextModeLabel).val(nextModeName);
	},

	/**
	 * Function to return related tab.
	 * @return : jQuery Object.
	 */
	getTabByLabel : function(tabLabel) {
		var tabs = this.getTabs();
		var targetTab = false;
		tabs.each(function(index,element){
			var tab = jQuery(element);
			var labelKey = tab.data('labelKey');
			if(labelKey == tabLabel){
				targetTab = tab;
				return false;
			}
		});
		return targetTab;
	},

	selectModuleTab : function(){
		var relatedTabContainer = this.getTabContainer();
		var moduleTab = relatedTabContainer.find('li.module-tab');
		this.deSelectAllrelatedTabs();
		this.markTabAsSelected(moduleTab);
	},

	deSelectAllrelatedTabs : function() {
		var relatedTabContainer = this.getTabContainer();
		this.getTabs().removeClass('active');
	},

	markTabAsSelected : function(tabElement){
		tabElement.addClass('active');
	},

	getSelectedTab : function() {
		var tabContainer = this.getTabContainer();
		return tabContainer.find('li.active');
	},

	getTabContainer : function(){
		return jQuery('div.related');
	},

	getTabs : function() {
		return this.getTabContainer().find('li');
	},

	getContentHolder : function() {
		if(this.detailViewContentHolder == false) {
			this.detailViewContentHolder = jQuery('div.details div.contents');
		}
		return this.detailViewContentHolder;
	},

	/**
	 * Function which will give the detail view form
	 * @return : jQuery element
	 */
	getForm : function() {
		if(this.detailViewForm == false) {
			this.detailViewForm = jQuery('#detailView');
		}
		return this.detailViewForm;
	},

	getRecordId : function(){
		return jQuery('#recordId').val();
	},

	getRelatedModuleName : function() {
		return jQuery('.relatedModuleName',this.getContentHolder()).val();
	},


	saveFieldValues : function (fieldDetailList) {
		var aDeferred = jQuery.Deferred();

		var recordId = this.getRecordId();

		var data = {};
		if(typeof fieldDetailList != 'undefined'){
			data = fieldDetailList;
		}

		data['record'] = recordId;

		data['module'] = app.getModuleName();
		data['action'] = 'SaveAjax';

		AppConnector.request(data).then(
			function(reponseData){
				aDeferred.resolve(reponseData);
			}
		);

		return aDeferred.promise();
	},


	getRelatedListCurrentPageNum : function() {
		return jQuery('input[name="currentPageNum"]',this.getContentHolder()).val();
	},

	/**
	 * function to remove comment block if its exists.
	 */
	removeCommentBlockIfExists : function() {
		var detailContentsHolder = this.getContentHolder();
		var Commentswidget = jQuery('.commentsBody',detailContentsHolder);
		jQuery('.addCommentBlock',Commentswidget).remove();
	},

	/**
	 * function to get the Comment thread for the given parent.
	 * params: Url to get the Comment thread
	 */
	getCommentThread : function(url) {
		var aDeferred = jQuery.Deferred();
		AppConnector.request(url).then(
			function(data) {
				aDeferred.resolve(data);
			},
			function(error,err){

			}
		)
		return aDeferred.promise();
	},

	/**
	 * function to save comment
	 * return json response
	 */
	saveComment : function(e) {
		var thisInstance = this;
		var aDeferred = jQuery.Deferred();
		var currentTarget = jQuery(e.currentTarget);
		var closestCommentBlock = currentTarget.closest('.addCommentBlock');
		var commentContent = closestCommentBlock.find('.commentcontent');
		var commentContentValue = commentContent.val();
		if(commentContentValue == ""){
			var errorMsg = app.vtranslate('JS_LBL_COMMENT_VALUE_CANT_BE_EMPTY')
			commentContent.validationEngine('showPrompt', errorMsg , 'error','bottomLeft',true);
			aDeferred.reject();
			return aDeferred.promise();
		}

		var parentCommentId = closestCommentBlock.closest('.commentDetails').find('.commentInfoHeader').data('commentid');
		var postData = {
			"action" : 'SaveAjax',
			'commentcontent' : 	commentContentValue,
			'related_to': thisInstance.getRecordId(),
			'parent_comments' :  parentCommentId,
			'module' : 'ModComments'
		}
		AppConnector.request(postData).then(
			function(data){
				aDeferred.resolve(data);
			},
			function(textStatus, errorThrown){
				aDeferred.reject(textStatus, errorThrown);
			}
		);

		return aDeferred.promise();
	},

	/**
	 * function to return the UI of the comment.
	 * return html
	 */
	getCommentUI : function(commentId){
		var aDeferred = jQuery.Deferred();
		var postData = {
			'view' : 'DetailAjax',
			'module' : 'ModComments',
			'record' : commentId
		}
		AppConnector.request(postData).then(
			function(data){
				aDeferred.resolve(data);
			},
			function(error,err){

			}
		);
		return aDeferred.promise();
	},

	/**
	 * function to return cloned comment block
	 * return jQuery Obj.
	 */
	getCommentBlock : function(){
		var detailContentsHolder = this.getContentHolder();
		return jQuery('.basicAddCommentBlock',detailContentsHolder).clone(true,true).removeClass('basicAddCommentBlock hide').addClass('addCommentBlock');
	},

    /*
	 * Function to register the submit event for Send Sms
	 */
	registerSendSmsSubmitEvent : function(){
        var thisInstance = this;
		jQuery('body').on('submit','#massSave',function(e){
			var form = jQuery(e.currentTarget);
			thisInstance.SendSmsSave(form);
			e.preventDefault();
		});
	},

    /*
	 * Function to Save and sending the Sms and hide the modal window of send sms
	 */
    SendSmsSave : function(form){
		var SendSmsUrl = form.serializeFormData();
		AppConnector.request(SendSmsUrl).then(
			function(data) {
				app.hideModalWindow();
			},
			function(error,err){

			}
		);
	},

	/**
	 * Function which will register events to update the record name in the detail view when any of
	 * the name field is changed
	 */
	registerNameAjaxEditEvent : function() {
		var thisInstance = this;
		var detailContentsHolder = thisInstance.getContentHolder();
		detailContentsHolder.on(thisInstance.fieldUpdatedEvent, '.nameField', function(e, params){
			var form = thisInstance.getForm();
			var nameFields = form.data('nameFields');
			var recordLabel = '';
			for(var index in nameFields) {
				if(index != 0) {
					recordLabel += ' '
				}

				var nameFieldName = nameFields[index];
				recordLabel += form.find('[name="'+nameFieldName+'"]').val();
			}
			var recordLabelElement = detailContentsHolder.closest('.contentsDiv').find('.recordLabel');
			recordLabelElement.text(recordLabel);
		});
	},

	updateHeaderNameFields : function(){
		var thisInstance = this;
		var detailContentsHolder = thisInstance.getContentHolder();
		var form = thisInstance.getForm();
		var nameFields = form.data('nameFields');
		var recordLabel = '';
		for(var index in nameFields) {
			if(index != 0) {
				recordLabel += ' '
			}

			var nameFieldName = nameFields[index];
			recordLabel += form.find('[name="'+nameFieldName+'"]').val();
		}
		var recordLabelElement = detailContentsHolder.closest('.contentsDiv').find('.recordLabel');
		recordLabelElement.text(recordLabel);
	},

	registerAjaxEditEvent : function(){
		var thisInstance = this;
		var detailContentsHolder =  thisInstance.getContentHolder();
		detailContentsHolder.on(thisInstance.fieldUpdatedEvent,'input,select,textarea',function(e){
			thisInstance.updateHeaderValues(jQuery(e.currentTarget));
		});
	},

	updateHeaderValues : function(currentElement){
		var thisInstance = this;
		if( currentElement.hasClass('nameField')){
			thisInstance.updateHeaderNameFields();
			return true;
		}

		var name = currentElement.attr('name');
		var updatedFields = this.getUpdatefFieldsArray();
		var detailContentsHolder =  thisInstance.getContentHolder();
		if(jQuery.inArray(name,updatedFields) != '-1'){
			var recordLabel = currentElement.val();
			var recordLabelElement = detailContentsHolder.closest('.contentsDiv').find('.'+name+'_label');
			recordLabelElement.text(recordLabel);
		}
	},

	/*
	 * Function to register the click event of email field
	 */
	registerEmailFieldClickEvent : function(){
		var detailContentsHolder = this.getContentHolder();
		detailContentsHolder.on('click','.emailField',function(e){
			e.stopPropagation();
		})

	},


	/**
	 * Function to register event for related list row click
	 */
	registerRelatedRowClickEvent: function(){
		var detailContentsHolder = this.getContentHolder();
		detailContentsHolder.on('click','.listViewEntries',function(e){
			var elem = jQuery(e.currentTarget);
			var recordUrl = elem.data('recordurl');
			window.location.href = recordUrl;
		});

	},

	loadRelatedList : function(pageNumber){
		var relatedListInstance = new Vtiger_RelatedList_Js(this.getRecordId(), app.getModuleName(), this.getSelectedTab(), this.getRelatedModuleName());
		var params = {'page':pageNumber};
		relatedListInstance.loadRelatedList(params);
	},

	registerEventForRelatedListPagination : function(){
		var thisInstance = this;
		var detailContentsHolder = this.getContentHolder();
		detailContentsHolder.on('click','#listViewNextPageButton',function(e){
			var selectedTabElement = thisInstance.getSelectedTab();
			var relatedModuleName = thisInstance.getRelatedModuleName();
			var relatedController = new Vtiger_RelatedList_Js(thisInstance.getRecordId(), app.getModuleName(), selectedTabElement, relatedModuleName);
			relatedController.nextPageHandler();
		});
		detailContentsHolder.on('click','#listViewPreviousPageButton',function(){
			var selectedTabElement = thisInstance.getSelectedTab();
			var relatedModuleName = thisInstance.getRelatedModuleName();
			var relatedController = new Vtiger_RelatedList_Js(thisInstance.getRecordId(), app.getModuleName(), selectedTabElement, relatedModuleName);
			relatedController.previousPageHandler();
		});
	},

	/**
	 * Function to register Event for Sorting
	 */
	registerEventForRelatedListSort : function(){
		var thisInstance = this;
		var detailContentsHolder = this.getContentHolder();
		detailContentsHolder.on('click','.listViewHeaderValues',function(e){
			var element = jQuery(e.currentTarget);
			var selectedTabElement = thisInstance.getSelectedTab();
			var relatedModuleName = thisInstance.getRelatedModuleName();
			var relatedController = new Vtiger_RelatedList_Js(thisInstance.getRecordId(), app.getModuleName(), selectedTabElement, relatedModuleName);
			relatedController.sortHandler(element);
		});
	},

	registerBlockAnimationEvent : function(){
		var detailContentsHolder = this.getContentHolder();
		detailContentsHolder.on('click','.blockToggle',function(e){
			var currentTarget =  jQuery(e.currentTarget);
			var blockId = currentTarget.data('id');
			var closestBlock = currentTarget.closest('.detailview-table');
			var bodyContents = closestBlock.find('tbody');
			var data = currentTarget.data();
			var module = app.getModuleName();
			var hideHandler = function() {
				bodyContents.hide('slow');
				app.cacheSet(module+'.'+blockId, 0)
			}
			var showHandler = function() {
				bodyContents.show();
				app.cacheSet(module+'.'+blockId, 1)
			}
			var data = currentTarget.data();
			if(data.mode == 'show'){
				hideHandler();
				currentTarget.hide();
				closestBlock.find("[data-mode='hide']").show();
			}else{
				showHandler();
				currentTarget.hide();
				closestBlock.find("[data-mode='show']").show();
			}
		});

	},

	registerBlockStatusCheckOnLoad : function(){
		var blocks = this.getContentHolder().find('.detailview-table');
		var module = app.getModuleName();
		blocks.each(function(index,block){
			var currentBlock = jQuery(block);
			var headerAnimationElement = currentBlock.find('.blockToggle').not('.hide');
			var bodyContents = currentBlock.find('tbody')
			var blockId = headerAnimationElement.data('id');
			var cacheKey = module+'.'+blockId;
			var value = app.cacheGet(cacheKey, null);
			if(value != null){
				if(value == 1){
					headerAnimationElement.hide();
					currentBlock.find("[data-mode='show']").show();
					bodyContents.show();
				} else {
					headerAnimationElement.hide();
					currentBlock.find("[data-mode='hide']").show();
					bodyContents.hide();
				}
			}
		});
	},
	
	/**
	 * Function to register event for adding related record for module
	 */
	registerEventForAddingRelatedRecord : function(){
		var thisInstance = this;
		var detailContentsHolder = this.getContentHolder();
		detailContentsHolder.on('click','[name="addButton"]',function(e){
			var element = jQuery(e.currentTarget);
			var selectedTabElement = thisInstance.getSelectedTab();
			var relatedModuleName = thisInstance.getRelatedModuleName();
            var quickCreateNode = jQuery('#quickCreateModules').find('[data-name="'+ relatedModuleName +'"]');
            if(quickCreateNode.length <= 0) {
                window.location.href = element.data('url');
                return;
            }
            
			var relatedController = new Vtiger_RelatedList_Js(thisInstance.getRecordId(), app.getModuleName(), selectedTabElement, relatedModuleName);
			relatedController.addRelatedRecord(element);
		})
	},
	
	
	/**
	 * Function to handle the ajax edit for detailview and summary view fields
	 * which will expects the currentTdElement
	 */
	ajaxEditHandling : function(currentTdElement) {
			var thisInstance = this;
			var detailViewValue = jQuery('.value',currentTdElement);
			var editElement = jQuery('.edit',currentTdElement);
			var actionElement = jQuery('.summaryViewEdit', currentTdElement);

			if(editElement.length <= 0) {
				return;
			}

			if(editElement.is(':visible')){
				return;
			}

			detailViewValue.addClass('hide');
			editElement.removeClass('hide').show().children().filter('input[type!="hidden"]input[type!="image"],select').filter(':first').focus();

			var saveTriggred = false;
			var preventDefault = false;

			var saveHandler = function(e) {
				var element = jQuery(e.target);
				if((element.closest('td').is(currentTdElement))){
					return;
				}

				currentTdElement.removeAttr('tabindex');

				var fieldnameElement = jQuery('.fieldname', editElement);
				var previousValue = fieldnameElement.data('prevValue');
				var fieldName = fieldnameElement.val();
				var fieldElement = jQuery('[name="'+ fieldName +'"]', editElement);
				var formElement = thisInstance.getForm();
				var formData = formElement.serializeFormData();
				var ajaxEditNewValue = formData[fieldName];
				//value that need to send to the server
				var fieldValue = ajaxEditNewValue;
                var fieldInfo = Vtiger_Field_Js.getInstance(fieldElement.data('fieldinfo'));

                // Since checkbox will be sending only on and off and not 1 or 0 as currrent value
				if(fieldElement.is('input:checkbox')) {
					if(fieldElement.is(':checked')) {
						ajaxEditNewValue = '1';
					} else {
						ajaxEditNewValue = '0';
					}
					fieldElement = fieldElement.filter('[type="checkbox"]');
				}
				var errorExists = fieldElement.validationEngine('validate');
				//If validation fails
				if(errorExists) {
					return;
				}

				

				

                //Before saving ajax edit values we need to check if the value is changed then only we have to save
                if(previousValue == ajaxEditNewValue) {
                    editElement.addClass('hide');
                    detailViewValue.removeClass('hide');
					actionElement.show();
					jQuery(document).off('click', '*', saveHandler);
                } else {
					var preFieldSaveEvent = jQuery.Event(thisInstance.fieldPreSave);
					fieldElement.trigger(preFieldSaveEvent, {'fieldValue' : fieldValue,  'recordId' : thisInstance.getRecordId()});
					if(preFieldSaveEvent.isDefaultPrevented()) {
						//Stop the save
						saveTriggred = false;
						preventDefault = true;
						return
					}
					preventDefault = false;
					
					jQuery(document).off('click', '*', saveHandler);
					
					if(!saveTriggred && !preventDefault) {
						saveTriggred = true;
					}else{
						return;
					}

                    currentTdElement.progressIndicator();
                    editElement.addClass('hide');
                    var fieldNameValueMap = {};
                    if(fieldInfo.getType() == 'multipicklist') {
                        var multiPicklistFieldName = fieldName.split('[]');
                        fieldName = multiPicklistFieldName[0];
                    }
                    fieldNameValueMap['value'] = fieldValue;
					fieldNameValueMap['field'] = fieldName;
                    thisInstance.saveFieldValues(fieldNameValueMap).then(function(response) {
						var postSaveRecordDetails = response.result;
						currentTdElement.progressIndicator({'mode':'hide'});
                        detailViewValue.removeClass('hide');
						actionElement.show();
                        detailViewValue.html(postSaveRecordDetails[fieldName].display_value);
						fieldElement.trigger(thisInstance.fieldUpdatedEvent,{'old':previousValue,'new':fieldValue});
						fieldnameElement.data('prevValue', ajaxEditNewValue);
					},
                        function(error){
                            //TODO : Handle error
                            currentTdElement.progressIndicator({'mode':'hide'});
                        }
                    )
                }
			}

			jQuery(document).on('click','*', saveHandler);
	},
	

	triggerDisplayTypeEvent : function() {
		var widthType = app.cacheGet('widthType', 'wideWidthType');
		if(widthType) {
			var elements = jQuery('#detailView').find('td');
			elements.addClass(widthType);
		}
	},
	
	/**
	 * Function to register all the events related to summary view widgets
	 */
	registerSummaryViewContainerEvents : function(summaryViewContainer) {
		var thisInstance = this;

		/**
		 * Function to handle the ajax edit for summary view fields
		 */
		summaryViewContainer.on('click', '.summaryViewEdit', function(e){
			var currentTarget = jQuery(e.currentTarget);
			currentTarget.hide();
			var currentTdElement = currentTarget.closest('td.fieldValue');
			thisInstance.ajaxEditHandling(currentTdElement);
		});
		
		/**
		 * Function to handle actions after ajax save in summary view
		 */
		summaryViewContainer.on(thisInstance.fieldUpdatedEvent, '.recordDetails', function(e, params){
			var updatesWidget = summaryViewContainer.find("[data-name='LBL_UPDATES']");
			thisInstance.loadWidget(updatesWidget);
		});
		
		/*
		 * Register the event to edit the status for for related activities
		 */
		summaryViewContainer.on('click', '.editStatus', function(e){
			var currentTarget = jQuery(e.currentTarget);
			var currentDiv = currentTarget.closest('div.activityStatus');
			var editElement = currentDiv.find('.edit');
			var detailViewElement = currentDiv.find('.value');
			
			currentTarget.hide();
			detailViewElement.addClass('hide');
			editElement.removeClass('hide').show();
			
			var callbackFunction = function() {
				var fieldnameElement = jQuery('.fieldname', editElement);
				var fieldName = fieldnameElement.val();
				var fieldElement = jQuery('[name="'+ fieldName +'"]', editElement);
				var previousValue = fieldnameElement.data('prevValue');
				var ajaxEditNewValue = fieldElement.find('option:selected').text();
				
				if(previousValue == ajaxEditNewValue) {
                    editElement.addClass('hide');
                    detailViewElement.removeClass('hide');
					currentTarget.show();
                } else {
					var activityDiv = currentDiv.parent();
					var activityId = activityDiv.find('.activityId').val();
					var moduleName = activityDiv.find('.activityModule').val();
					var activityType = activityDiv.find('.activityType').val();
					
					currentDiv.progressIndicator();
                    editElement.addClass('hide');
					var params = {
						action : 'SaveAjax',
						record : activityId,
						field : fieldName,
						value : ajaxEditNewValue,
						module : moduleName,
						activitytype : activityType
					};
					
					AppConnector.request(params).then(
						function(data) {
							currentDiv.progressIndicator({'mode':'hide'});
							detailViewElement.removeClass('hide');
							currentTarget.show();
							detailViewElement.html(ajaxEditNewValue);
							fieldnameElement.data('prevValue', ajaxEditNewValue);
						}
					);
				}
			}
			
			//adding clickoutside event on the currentDiv - to save the ajax edit of status values
			Vtiger_Helper_Js.addClickOutSideEvent(currentDiv, callbackFunction);
		});
		
		/*
		 * Register click event for add button in Related Activities widget
		 */
		summaryViewContainer.on('click', '.createActivity', function(e){
			var referenceModuleName = "Calendar";
			var quickCreateNode = jQuery('#quickCreateModules').find('[data-name="'+ referenceModuleName +'"]');
			var recordId = thisInstance.getRecordId();
			var module = app.getModuleName();
			
			if(quickCreateNode.length <= 0) {
				Vtiger_Helper_Js.showPnotify(app.vtranslate('JS_NO_CREATE_OR_NOT_QUICK_CREATE_ENABLED'))
			}
			var fieldName = thisInstance.referenceFieldNames[module];
			
			var customParams = {};
			customParams[fieldName] = recordId;
			
			var callbackFunction = function() {
				var params = {};
				params['record'] = recordId;
				params['view'] = 'Detail';
				params['module'] = module;
				params['mode'] = 'getActivities';
				
				AppConnector.request(params).then(
					function(data) {
						var activitiesWidget = jQuery('#relatedActivities');
						activitiesWidget.html(data);
						app.changeSelectElementView(activitiesWidget);
					}
				);
			}
			
			var QuickCreateParams = {};
			QuickCreateParams['callbackFunction'] = callbackFunction;
			QuickCreateParams['data'] = customParams;
			QuickCreateParams['noCache'] = false;
			quickCreateNode.trigger('click', QuickCreateParams);
		});
	},
	
	registerEvents : function(){
		var thisInstance = this;
		thisInstance.triggerDisplayTypeEvent();
		thisInstance.registerSendSmsSubmitEvent();
		thisInstance.registerAjaxEditEvent();
		this.registerRelatedRowClickEvent();
		this.registerBlockAnimationEvent();
		this.registerBlockStatusCheckOnLoad();
		this.registerEmailFieldClickEvent();
		this.registerEventForRelatedListSort();
		this.registerEventForRelatedListPagination();
		this.registerEventForAddingRelatedRecord();

		var detailViewContainer = jQuery('div.detailViewContainer');
		if(detailViewContainer.length <= 0) {
			// Not detail view page
			return;
		}

		var detailContentsHolder = thisInstance.getContentHolder();
		var detailContainer = detailContentsHolder.closest('div.detailViewInfo');
		app.registerEventForDatePickerFields(detailContentsHolder);
		
		//register all the events for summary view container
		this.registerSummaryViewContainerEvents(detailContentsHolder);

		jQuery('.related', detailContainer).on('click', 'li', function(e, urlAttributes){
			var tabElement = jQuery(e.currentTarget);
			var element = jQuery('<div></div>');
			element.progressIndicator({
				'position':'html',
				'blockInfo' : {
					'enabled' : true,
					'elementToBlock' : detailContainer
				}
			});
			var url = tabElement.data('url');
			if(typeof urlAttributes != 'undefined'){
				var callBack = urlAttributes.callback;
				delete urlAttributes.callback;
			}
			thisInstance.loadContents(url,urlAttributes).then(
				function(data){
					thisInstance.deSelectAllrelatedTabs();
					thisInstance.markTabAsSelected(tabElement);
					element.progressIndicator({'mode': 'hide'});
					if(typeof callBack == 'function'){
						callBack(data);
					}
					//Summary tab is clicked
					if(tabElement.data('labelKey') == thisInstance.detailViewSummaryTabLabel) {
						thisInstance.loadWidgets();
					}

				},
				function (){
					//TODO : handle error
					element.progressIndicator({'mode': 'hide'});
				}
			);
		});


		detailContentsHolder.on('click', '#detailViewNextRecordButton', function(e){
			var selectedTabElement = thisInstance.getSelectedTab();
			var url = selectedTabElement.data('url');
			var currentPageNum = thisInstance.getRelatedListCurrentPageNum();
			var requestedPage = parseInt(currentPageNum)+1;
			var nextPageUrl = url+'&page='+requestedPage;
			thisInstance.loadContents(nextPageUrl);
		});

		detailContentsHolder.on('click', '#detailViewPreviousRecordButton', function(e){
			var selectedTabElement = thisInstance.getSelectedTab();
			var url = selectedTabElement.data('url');
			var currentPageNum = thisInstance.getRelatedListCurrentPageNum();
			var requestedPage = parseInt(currentPageNum)-1;
			var params = {};
			var nextPageUrl = url+'&page='+requestedPage;
			thisInstance.loadContents(nextPageUrl);
		});

		detailContentsHolder.on('click', 'button.selectRelation', function(e){
			var selectButton = jQuery(e.currentTarget);
			var selectedTabElement = thisInstance.getSelectedTab();
			var relatedModuleName = thisInstance.getRelatedModuleName();
			var relatedController = new Vtiger_RelatedList_Js(thisInstance.getRecordId(), app.getModuleName(), selectedTabElement, relatedModuleName);
			relatedController.showSelectRelationPopup();
		});

		detailContentsHolder.on('click', 'a.relationDelete', function(e){
			e.stopImmediatePropagation();
			var element = jQuery(e.currentTarget);
			var message = app.vtranslate('LBL_DELETE_CONFIRMATION');
			Vtiger_Helper_Js.showConfirmationBox({'message' : message}).then(
				function(e) {
					var row = element.closest('tr');
					var relatedRecordid = row.data('id');
					var selectedTabElement = thisInstance.getSelectedTab();
					var relatedModuleName = thisInstance.getRelatedModuleName();
					var relatedController = new Vtiger_RelatedList_Js(thisInstance.getRecordId(), app.getModuleName(), selectedTabElement, relatedModuleName);
					relatedController.deleteRelation([relatedRecordid]).then(function(response){
						relatedController.loadRelatedList();
					});
				},
				function(error, err){
				}
			);
		});

		jQuery('.changeDetailViewMode',detailViewContainer).click(function(e){
			var currentElement = jQuery(e.currentTarget);
			var detailViewTitleContainer = currentElement.closest('.detailViewTitle');
			var viewModeElement = jQuery('input[name="viewMode"]',detailViewTitleContainer)
			var nextModeName = viewModeElement.data('nextviewname');
			var url = '';
			if(nextModeName == 'full') {
				url = viewModeElement.data('fullUrl');
			}else{
				url = viewModeElement.data('summaryUrl');
			}

			var element = jQuery('<div></div>');
			element.progressIndicator({
				'position':'html',
				'blockInfo': {
					'enabled' : true,
					'elementToBlock' : detailContainer
				}
			});

			thisInstance.loadContents(url).then(
				function(){
					element.progressIndicator({'mode' : 'hide'});
					thisInstance.postDetailViewLoadByMode(nextModeName);
					thisInstance.deSelectAllrelatedTabs();
					var summaryTab = thisInstance.getTabByLabel(thisInstance.detailViewSummaryTabLabel);
					thisInstance.markTabAsSelected(summaryTab);
					thisInstance.loadWidgets();
				}
			);
		});

		detailContentsHolder.on('click','table.detailview-table td.fieldValue', function(e) {
			var currentTdElement = jQuery(e.currentTarget);
			thisInstance.ajaxEditHandling(currentTdElement);
		});


		detailContentsHolder.on('click', '.relatedPopup', function(e){
			var editViewObj = new Vtiger_Edit_Js();
			editViewObj.openPopUp(e);
			return false;
		});

		detailContentsHolder.on('click','.addCommentBtn', function(e){
			thisInstance.removeCommentBlockIfExists();
			var addCommentBlock = thisInstance.getCommentBlock();
			addCommentBlock.appendTo('.commentBlock');
			jQuery(e.currentTarget).attr('disabled',true);
		});

		detailContentsHolder.on('click','.closeCommentBlock', function(e){
			thisInstance.removeCommentBlockIfExists();
			jQuery('.addCommentBtn').attr('disabled',false);
		});

		detailContentsHolder.on('click','.replyComment', function(e){
			var detailContentsHolder = thisInstance.getContentHolder();
			thisInstance.removeCommentBlockIfExists();
			jQuery('.addCommentBtn').attr('disabled',false);
			var currentTarget = jQuery(e.currentTarget);
			var commentInfoBlock = currentTarget.closest('.singleComment');
			var addCommentBlock = thisInstance.getCommentBlock();
			addCommentBlock.appendTo(commentInfoBlock).show();
		});

		detailContentsHolder.on('click','.viewThread', function(e){
			var currentTarget = jQuery(e.currentTarget);
			var currentTargetParent = currentTarget.parent();
			var commentActionsBlock = currentTarget.closest('.commentActions');
			var currentCommentBlock = currentTarget.closest('.commentDetails');
			var ulElements = currentCommentBlock.find('ul');
			if(ulElements.length > 0){
				ulElements.show();
				commentActionsBlock.find('.hideThreadBlock').show();
				currentTargetParent.hide();
				return;
			}
			var commentId = currentTarget.closest('.commentDiv').find('.commentInfoHeader').data('commentid');
			var url= 'module='+app.getModuleName()+'&view=Detail&record='+thisInstance.getRecordId()+'&mode=showChildComments&commentid='+commentId;
			var dataObj = thisInstance.getCommentThread(url);
			dataObj.then(function(data){
				jQuery(data).appendTo(jQuery(e.currentTarget).closest('.commentDetails'));
				commentActionsBlock.find('.hideThreadBlock').show();
				currentTargetParent.hide();
			});
		});

		detailContentsHolder.on('click','.hideThread', function(e){
			var currentTarget = jQuery(e.currentTarget);
			var currentTargetParent = currentTarget.parent();
			var commentActionsBlock = currentTarget.closest('.commentActions');
			var currentCommentBlock = currentTarget.closest('.commentDetails');
			currentCommentBlock.find('ul').hide();
			currentTargetParent.hide();
			commentActionsBlock.find('.viewThreadBlock').show();
		});

		detailContentsHolder.on('click','.detailViewThread',function(e){
			var recentCommentsTab = thisInstance.getTabByLabel(thisInstance.detailViewRecentCommentsTabLabel);
			var commentId = jQuery(e.currentTarget).closest('.singleComment').find('.commentInfoHeader').data('commentid');
			var commentLoad = function(data){
				window.location.href = window.location.href+'#'+commentId;
			}
			recentCommentsTab.trigger('click',{'commentid':commentId,'callback':commentLoad});
		});

		detailContentsHolder.on('click','.detailViewSaveComment', function(e){
			var dataObj = thisInstance.saveComment(e);
			dataObj.then(function(data){
				var commentsContainer = detailContentsHolder.find("[data-name='ModComments']");
				thisInstance.loadWidget(commentsContainer);
			});
		});

		detailContentsHolder.on('keyup','.commentcontent', function(e){
			var commentContent = jQuery(e.currentTarget);
			var commentContentValue = commentContent.val();
			var parentElement = commentContent.closest('div.addCommentBlock');
			if(commentContentValue != ""){
				parentElement.find('.detailViewSaveComment').removeAttr('disabled');
			}else{
				parentElement.find('.detailViewSaveComment').attr('disabled',"disabled");
			}
		});

		detailContentsHolder.on('click','.saveComment', function(e){
			var dataObj = thisInstance.saveComment(e);
			dataObj.then(function(data){
				var commentId = data['result']['id'];
				var commentHtml = thisInstance.getCommentUI(commentId);
				commentHtml.then(function(data){
					var currentTarget = jQuery(e.currentTarget);
					var closestAddCommentBlock = currentTarget.closest('.addCommentBlock');
					closestAddCommentBlock.find('.commentcontent').val('')
					var commentBlock = closestAddCommentBlock.closest('.commentDetails');
					var detailContentsHolder = thisInstance.getContentHolder();
					var noCommentsMsgContainer = jQuery('.noCommentsMsgContainer',detailContentsHolder);
					noCommentsMsgContainer.remove();
					if(commentBlock.length > 0){
						jQuery('<ul class="liStyleNone"><li class="commentDetails">'+data+'</li></ul>').appendTo(commentBlock);
						closestAddCommentBlock.remove();
					} else {
						jQuery('<ul class="liStyleNone"><li class="commentDetails">'+data+'</li></ul>').prependTo(closestAddCommentBlock.closest('.commentContainer').find('.commentsList'));
					}
				});
			});
		});

		detailContentsHolder.on('click','.moreRecentComments', function(){
			var recentCommentsTab = thisInstance.getTabByLabel(thisInstance.detailViewRecentCommentsTabLabel);
			recentCommentsTab.trigger('click');
		});

		detailContentsHolder.on('click','.moreRecentUpdates', function(){
			var recentUpdatesTab = thisInstance.getTabByLabel(thisInstance.detailViewRecentUpdatesTabLabel);
			recentUpdatesTab.trigger('click');
		});
		
		detailContentsHolder.on('click','.moreRecentActivities', function(){
			var recentActivitiesTab = thisInstance.getTabByLabel(thisInstance.detailViewRecentActivitiesTabLabel);
			recentActivitiesTab.trigger('click');
		});
		
		thisInstance.getForm().validationEngine(app.validationEngineOptions);

		thisInstance.loadWidgets();
	}
});

//On Page Load
jQuery(document).ready(function() {
	var detailInstance = Vtiger_Detail_Js.getInstance();
	detailInstance.registerEvents();
});
