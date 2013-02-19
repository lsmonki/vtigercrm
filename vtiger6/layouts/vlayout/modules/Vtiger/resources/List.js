/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

jQuery.Class("Vtiger_List_Js",{

	listInstance : false,
	
	massEditPreSave : 'Vtiger.MassEdit.PreSave',

    getInstance: function(){
		if(Vtiger_List_Js.listInstance == false){
			var module = app.getModuleName();
			var moduleClassName = module+"_List_Js";
			var fallbackClassName = Vtiger_List_Js;
			if(typeof window[moduleClassName] != 'undefined'){
				var instance = new window[moduleClassName]();
			}else{
				var instance = new fallbackClassName();
			}
			Vtiger_List_Js.listInstance = instance;
			return instance;
		}
		return Vtiger_List_Js.listInstance;
	},
		/*
	 * function to trigger send Email
	 * @params: send email url , module name.
	 */
	triggerSendEmail : function(massActionUrl, module){
		var listInstance = Vtiger_List_Js.getInstance();
		var validationResult = listInstance.checkListRecordSelected();
		if(validationResult != true){
			var selectedIds = listInstance.readSelectedIds(true);
			var excludedIds = listInstance.readExcludedIds(true);
			var cvId = listInstance.getCurrentCvId();
			var postData = {
				"viewname" : cvId,
				"selected_ids":selectedIds,
				"excluded_ids" : excludedIds
			};

            var listViewInstance = Vtiger_List_Js.getInstance();
            var searchValue = listViewInstance.getAlphabetSearchValue();

			if((typeof searchValue != "undefined") && (searchValue.length > 0)) {
                postData['search_key'] = listViewInstance.getAlphabetSearchField();
                postData['search_value'] = searchValue;
                postData['operator'] = "s";
            }

			var actionParams = {
				"type":"POST",
				"url":massActionUrl,
				"dataType":"html",
				"data" : postData
			};

			Vtiger_Index_Js.showComposeEmailPopup(actionParams);
		} else {
			listInstance.noRecordSelectedAlert();
		}

	},
	/*
	 * function to trigger Send Sms
	 * @params: send email url , module name.
	 */
	triggerSendSms : function(massActionUrl, module){
		var listInstance = Vtiger_List_Js.getInstance();
		var validationResult = listInstance.checkListRecordSelected();
		if(validationResult != true){
		Vtiger_Helper_Js.checkServerConfig(module).then(function(data){
			if(data == true){
				Vtiger_List_Js.triggerMassAction(massActionUrl);
			} else {
				alert(app.vtranslate('JS_SMS_SERVER_CONFIGURATION'));
			}
		});
		} else {
			listInstance.noRecordSelectedAlert();
		}

	},

	massDeleteRecords : function(url) {
		var listInstance = Vtiger_List_Js.getInstance();
		var validationResult = listInstance.checkListRecordSelected();
		if(validationResult != true){
			// Compute selected ids, excluded ids values, along with cvid value and pass as url parameters
			var selectedIds = listInstance.readSelectedIds(true);
			var excludedIds = listInstance.readExcludedIds(true);
			var cvId = listInstance.getCurrentCvId();
			var message = app.vtranslate('LBL_MASS_DELETE_CONFIRMATION');
			Vtiger_Helper_Js.showConfirmationBox({'message' : message}).then(
				function(e) {

					var deleteURL = url+'&viewname='+cvId+'&selected_ids='+selectedIds+'&excluded_ids='+excludedIds;
                    var listViewInstance = Vtiger_List_Js.getInstance();
                    var searchValue = listViewInstance.getAlphabetSearchValue();

                   	if((typeof searchValue != "undefined") && (searchValue.length > 0)) {
                        deleteURL += '&search_key='+listViewInstance.getAlphabetSearchField();
                        deleteURL += '&search_value='+searchValue;
                        deleteURL += '&operator=s';
                    }
					AppConnector.request(deleteURL).then(
						function(data) {
							app.hideModalWindow();
							var module = app.getModuleName();
							var params = listInstance.getDefaultParams();
							AppConnector.request(params).then(
								function(data) {
									var listViewContainer = listInstance.getListViewContentContainer();
									listViewContainer.html(data);
									listInstance.triggerDisplayTypeEvent();
									jQuery('#deSelectAllMsg').trigger('click');
									listInstance.updatePagination();
							});
							jQuery('#recordsCount').val('');
						}
					);
				},
				function(error, err){
				})
		} else {
			listInstance.noRecordSelectedAlert();
		}

	},

	deleteRecord : function(recordId) {
		var listInstance = Vtiger_List_Js.getInstance();
		var message = app.vtranslate('LBL_DELETE_CONFIRMATION');
		Vtiger_Helper_Js.showConfirmationBox({'message' : message}).then(
			function(e) {
				var module = app.getModuleName();
				var postData = {
					"module": module,
					"action": "DeleteAjax",
					"record": recordId
				}

				AppConnector.request(postData).then(
					function(data){
						if(data.success) {
							var orderBy = jQuery('#orderBy').val();
							var sortOrder = jQuery("#sortOrder").val();
							var urlParams = {
								"viewname": data.result.viewname,
								"orderby": orderBy,
								"sortorder": sortOrder
							}
							listInstance.getListViewRecords(urlParams).then(function(){
								listInstance.updatePagination();
							});
							jQuery('#recordsCount').val('');
						} else {
							var  params = {
								text : app.vtranslate(data.error.message),
								title : app.vtranslate('JS_LBL_PERMISSION')
							}
							Vtiger_Helper_Js.showPnotify(params);
						}
					},
					function(error,err){

					}
				);
			},
			function(error, err){
			}
		);
	},


	triggerMassAction : function(massActionUrl,callBackFunction,beforeShowCb, css) {

		//TODO : Make the paramters as an object
		if(typeof beforeShowCb == 'undefined') {
			beforeShowCb = function(){return true;};
		}

		if(typeof beforeShowCb == 'object') {
			css = beforeShowCb;
			beforeShowCb = function(){return true;};
		}
		var listInstance = Vtiger_List_Js.getInstance();
		var validationResult = listInstance.checkListRecordSelected();
		if(validationResult != true){
		// Compute selected ids, excluded ids values, along with cvid value and pass as url parameters
		var selectedIds = listInstance.readSelectedIds(true);
		var excludedIds = listInstance.readExcludedIds(true);
		var cvId = listInstance.getCurrentCvId();
		var postData = {
			"viewname" : cvId,
			"selected_ids":selectedIds,
			"excluded_ids" : excludedIds
		};

        var listViewInstance = Vtiger_List_Js.getInstance();
        var searchValue = listViewInstance.getAlphabetSearchValue();

		if((typeof searchValue != "undefined") && (searchValue.length > 0)) {
            postData['search_key'] = listViewInstance.getAlphabetSearchField();
            postData['search_value'] = searchValue;
            postData['operator'] = "s";
        }

		var actionParams = {
			"type":"POST",
			"url":massActionUrl,
			"dataType":"html",
			"data" : postData
		};

		if(typeof css == 'undefined'){
			css = {};
		}
		var css = jQuery.extend({'text-align' : 'left'},css);

		AppConnector.request(actionParams).then(
			function(data) {
				if(data) {
					var result = beforeShowCb(data);
					if(!result) {
						return;
					}
					app.showModalWindow(data,function(data){
						if(typeof callBackFunction == 'function'){
							callBackFunction(data);
							listInstance.triggerDisplayTypeEvent();
						}
					},css)

				}
			},
			function(error,err){

			}
		);
		} else {
			listInstance.noRecordSelectedAlert();
		}

	},

	triggerMassEdit : function(massEditUrl) {
		Vtiger_List_Js.triggerMassAction(massEditUrl, function(container){
			var massEditForm = container.find('#massEdit');
			massEditForm.validationEngine(app.validationEngineOptions);
			var listInstance = Vtiger_List_Js.getInstance();
			listInstance.inactiveFieldValidation(massEditForm);
			listInstance.registerReferenceFieldsForValidation(massEditForm);
			listInstance.registerFieldsForValidation(massEditForm);
			listInstance.registerEventForTabClick(massEditForm);
			listInstance.registerRecordAccessCheckEvent(massEditForm);
			var editInstance = Vtiger_Edit_Js.getInstance();
			editInstance.registerBasicEvents(massEditForm);
			//To remove the change happended for select elements due to picklist dependency
			container.find('select').trigger('change',{'forceDeSelect':true});
			listInstance.postMassEdit(container);

			listInstance.registerSlimScrollMassEdit();
		},{'width':'65%'});
	},
	
	/*
	 * function to trigger export action
	 * returns UI
	 */
	triggerExportAction :function(exportActionUrl){
		var listInstance = Vtiger_List_Js.getInstance();
		// Compute selected ids, excluded ids values, along with cvid value and pass as url parameters
		var selectedIds = listInstance.readSelectedIds(true);
		var excludedIds = listInstance.readExcludedIds(true);
		var cvId = listInstance.getCurrentCvId();
		var pageNumber = jQuery('#pageNumber').val();

        exportActionUrl += '&selected_ids='+selectedIds+'&excluded_ids='+excludedIds+'&viewname='+cvId+'&page='+pageNumber;

        var listViewInstance = Vtiger_List_Js.getInstance();
        var searchValue = listViewInstance.getAlphabetSearchValue();

		if((typeof searchValue != "undefined") && (searchValue.length > 0)) {
            exportActionUrl += '&search_key='+listViewInstance.getAlphabetSearchField()+'&search_value='+searchValue+'&operator=s';
        }
        window.location.href = exportActionUrl;
	}

},{

	//contains the List View element.
	listViewContainer : false,

	//Contains list view top menu element
	listViewTopMenuContainer : false,

	//Contains list view content element
	listViewContentContainer : false,

	//Contains filter Block Element
	filterBlock : false,

	filterSelectElement : false,


	getListViewContainer : function() {
		if(this.listViewContainer == false){
			this.listViewContainer = jQuery('div.listViewPageDiv');
		}
		return this.listViewContainer;
	},

	getListViewTopMenuContainer : function(){
		if(this.listViewTopMenuContainer == false){
			this.listViewTopMenuContainer = jQuery('.listViewTopMenuDiv');
		}
		return this.listViewTopMenuContainer;
	},

	getListViewContentContainer : function(){
		if(this.listViewContentContainer == false){
			this.listViewContentContainer = jQuery('.listViewContentDiv');
		}
		return this.listViewContentContainer;
	},

	getFilterBlock : function(){
		if(this.filterBlock == false){
			var filterSelectElement = this.getFilterSelectElement();
			this.filterBlock = filterSelectElement.data('select2').dropdown;
		}
		return this.filterBlock;
	},

	getFilterSelectElement : function() {

		if(this.filterSelectElement == false) {
			this.filterSelectElement = jQuery('#customFilter');
		}
		return this.filterSelectElement;
	},


	getDefaultParams : function() {
		var pageNumber = jQuery('#pageNumber').val();
		var module = app.getModuleName();
		var parent = app.getParentModuleName();
		var cvId = this.getCurrentCvId();
		var orderBy = jQuery('#orderBy').val();
		var sortOrder = jQuery("#sortOrder").val();
		var params = {
			'module': module,
			'parent' : parent,
			'page' : pageNumber,
			'view' : "List",
			'mode' : "showListViewRecords",
			'viewname' : cvId,
			'orderby' : orderBy,
			'sortorder' : sortOrder
		}

        var searchValue = this.getAlphabetSearchValue();

        if((typeof searchValue != "undefined") && (searchValue.length > 0)) {
            params['search_key'] = this.getAlphabetSearchField();
            params['search_value'] = searchValue;
            params['operator'] = "s";
        }
		return params;
	},

	/*
	 * Function which will give you all the list view params
	 */
	getListViewRecords : function(urlParams) {
		var aDeferred = jQuery.Deferred();
		if(typeof urlParams == 'undefined') {
			urlParams = {};
		}

		var thisInstance = this;
		var loadingMessage = jQuery('.listViewLoadingMsg').text();
		var progressIndicatorElement = jQuery.progressIndicator({
			'message' : loadingMessage,
			'position' : 'html',
			'blockInfo' : {
				'enabled' : true
			}
		});

		var defaultParams = this.getDefaultParams();
		var urlParams = jQuery.extend(defaultParams, urlParams);
		AppConnector.requestPjax(urlParams).then(
			function(data){
				progressIndicatorElement.progressIndicator({
					'mode' : 'hide'
				})
                jQuery('#listViewContents').html(data);
				thisInstance.triggerDisplayTypeEvent();

				var selectedIds = thisInstance.readSelectedIds();
				if(selectedIds != ''){
					if(selectedIds == 'all'){
						jQuery('.listViewEntriesCheckBox').each( function(index,element) {
							jQuery(this).attr('checked', true).closest('tr').addClass('highlightBackgroundColor');
						});
						jQuery('#deSelectAllMsgDiv').show();
						var excludedIds = thisInstance.readExcludedIds();
						if(excludedIds != ''){
							jQuery('#listViewEntriesMainCheckBox').attr('checked',false);
							jQuery('.listViewEntriesCheckBox').each( function(index,element) {
								if(jQuery.inArray(jQuery(element).val(),excludedIds) != -1){
									jQuery(element).attr('checked', false).closest('tr').removeClass('highlightBackgroundColor');
								}
							});
						}
					} else {
						jQuery('.listViewEntriesCheckBox').each( function(index,element) {
							if(jQuery.inArray(jQuery(element).val(),selectedIds) != -1){
								jQuery(this).attr('checked', true).closest('tr').addClass('highlightBackgroundColor');
							}
						});
					}
					thisInstance.checkSelectAll();
				}
				aDeferred.resolve(data);
			},

			function(textStatus, errorThrown){
				aDeferred.reject(textStatus, errorThrown);
			}
		);
		return aDeferred.promise();
	},

	/*
	 * Function to return alerts if no records selected.
	 */
	noRecordSelectedAlert : function(){
		return alert(app.vtranslate('JS_PLEASE_SELECT_ONE_RECORD'));
	},

	massActionSave : function(form, isMassEdit){
		if(typeof isMassEdit == 'undefined') {
			isMassEdit = false;
		}
		var aDeferred = jQuery.Deferred();
		var massActionUrl = form.serializeFormData();
		if(isMassEdit) {
			var fieldsChanged = false;
            var massEditFieldList = jQuery('#massEditFieldsNameList').data('value');
			for(var fieldName in massEditFieldList){
                var fieldInfo = massEditFieldList[fieldName];

                var fieldElement = form.find('[name="'+fieldInfo.name+'"]');
                if(fieldInfo.type == "reference") {
                    //get the element which will be shown which has "_display" appended to actual field name
                    fieldElement = form.find('[name="'+fieldInfo.name+'_display"]');
                }else if(fieldInfo.type == "multipicklist") {
                    fieldElement = form.find('[name="'+fieldInfo.name+'[]"]');
                }
                
                //Not all fields will be enabled for mass edit 
                if(fieldElement.length == 0) {
                    continue;
                }
                
                var validationElement = fieldElement.filter('[data-validation-engine]');
                //check if you have element enabled has changed
                if(validationElement.length == 0){
                    if(fieldInfo.type == "multipicklist") {
                        fieldName = fieldName+"[]";
                    }
                    delete massActionUrl[fieldName];
                    if(fieldsChanged != true){
                        fieldsChanged = false;
                    }
                } else {
                    fieldsChanged = true;
                }
			}
			if(fieldsChanged == false){
				Vtiger_Helper_Js.showPnotify(app.vtranslate('NONE_OF_THE_FIELD_VALUES_ARE_CHANGED_IN_MASS_EDIT'));
				form.find('[name="saveButton"]').removeAttr('disabled');
				aDeferred.reject();
				return aDeferred.promise();
			}
			//on submit form trigger the massEditPreSave event
			var massEditPreSaveEvent = jQuery.Event(Vtiger_List_Js.massEditPreSave);
			form.trigger(massEditPreSaveEvent);
			if(massEditPreSaveEvent.isDefaultPrevented()) {
				form.find('[name="saveButton"]').removeAttr('disabled');
				aDeferred.reject();
				return aDeferred.promise();
			}
		}
		AppConnector.request(massActionUrl).then(
			function(data) {
				app.hideModalWindow();
				aDeferred.resolve(data);
			},
			function(error,err){
				app.hideModalWindow();
				aDeferred.reject(error,err);
			}
		);
		return aDeferred.promise();
	},
	
	/*
	 * Function to check the view permission of a record after save
	 */
	registerRecordAccessCheckEvent : function(form) {
		
		form.on(Vtiger_List_Js.massEditPreSave, function(e) {
			var assignedToSelectElement = form.find('[name="assigned_user_id"][data-validation-engine]');
			if(assignedToSelectElement.length > 0){
				if(assignedToSelectElement.data('recordaccessconfirmation') == true) {
					return;
				}else{
					if(assignedToSelectElement.data('recordaccessconfirmationprogress') != true) {
						var recordAccess = assignedToSelectElement.find('option:selected').data('recordaccess');
						if(recordAccess == false) {
							var message = app.vtranslate('JS_NO_VIEW_PERMISSION_AFTER_SAVE');
							Vtiger_Helper_Js.showConfirmationBox({
								'message' : message
							}).then(
								function(e) {
									assignedToSelectElement.data('recordaccessconfirmation',true);
									assignedToSelectElement.removeData('recordaccessconfirmationprogress');
									form.submit();
								},
								function(error, err){
									assignedToSelectElement.removeData('recordaccessconfirmationprogress');
									e.preventDefault();	
								});
							assignedToSelectElement.data('recordaccessconfirmationprogress',true);
						} else {
							return true;
						}
					} 
				}
			} else{
				return true;
			}
			e.preventDefault();
		});
	},

	checkSelectAll : function(){
		var state = true;
		jQuery('.listViewEntriesCheckBox').each(function(index,element){
			if(jQuery(element).is(':checked')){
				state = true;
			}else{
				state = false;
				return false;
			}
		});
		if(state == true){
			jQuery('#listViewEntriesMainCheckBox').attr('checked',true);
		} else {
			jQuery('#listViewEntriesMainCheckBox').attr('checked', false);
		}
	},

	getRecordsCount : function(){
		var aDeferred = jQuery.Deferred();
		var recordCountVal = jQuery("#recordsCount").val();
		if(recordCountVal != ''){
			aDeferred.resolve(recordCountVal);
		} else {
			var count = '';
			var cvId = this.getCurrentCvId();
			var module = app.getModuleName();
			var parent = app.getParentModuleName();
			var postData = {
				"module": module,
				"parent": parent,
				"view": "ListAjax",
				"viewname": cvId,
				"mode": "getRecordsCount"
			}

            var searchValue = this.getAlphabetSearchValue();
            if((typeof searchValue != "undefined") && (searchValue.length > 0)) {
                postData['search_key'] = this.getAlphabetSearchField();
                postData['search_value'] = this.getAlphabetSearchValue();
                postData['operator'] = "s";
            }

			AppConnector.request(postData).then(
				function(data) {
					var response = JSON.parse(data);
					jQuery("#recordsCount").val(response['result']['count']);
					count =  response['result']['count'];
					aDeferred.resolve(count);
				},
				function(error,err){

				}
			);
		}

		return aDeferred.promise();
	},

	getSelectOptionFromChosenOption : function(liElement){
		var classNames = liElement.attr("class");
		var classNamesArr = classNames.split(" ");
		var currentOptionId = '';
		jQuery.each(classNamesArr,function(index,element){
			if(element.match("^filterOptionId")){
				currentOptionId = element;
				return false;
			}
		});
		return jQuery('#'+currentOptionId);
	},

	readSelectedIds : function(decode){
		var cvId = this.getCurrentCvId();
		var selectedIdsElement = jQuery('#selectedIds');
		var selectedIdsDataAttr = cvId+'Selectedids';
		var selectedIdsElementDataAttributes = selectedIdsElement.data();
		if (!(selectedIdsDataAttr in selectedIdsElementDataAttributes) ) {
			var selectedIds = new Array();
			this.writeSelectedIds(selectedIds);
		} else {
			selectedIds = selectedIdsElementDataAttributes[selectedIdsDataAttr];
		}
		if(decode == true){
			if(typeof selectedIds == 'object'){
				return JSON.stringify(selectedIds);
			}
		}
		return selectedIds;
	},
	readExcludedIds : function(decode){
		var cvId = this.getCurrentCvId();
		var exlcudedIdsElement = jQuery('#excludedIds');
		var excludedIdsDataAttr = cvId+'Excludedids';
		var excludedIdsElementDataAttributes = exlcudedIdsElement.data();
		if(!(excludedIdsDataAttr in excludedIdsElementDataAttributes)){
			var excludedIds = new Array();
			this.writeExcludedIds(excludedIds);
		}else{
			excludedIds = excludedIdsElementDataAttributes[excludedIdsDataAttr];
		}
		if(decode == true){
			if(typeof excludedIds == 'object') {
				return JSON.stringify(excludedIds);
			}
		}
		return excludedIds;
	},

	writeSelectedIds : function(selectedIds){
		var cvId = this.getCurrentCvId();
		jQuery('#selectedIds').data(cvId+'Selectedids',selectedIds);
	},

	writeExcludedIds : function(excludedIds){
		var cvId = this.getCurrentCvId();
		jQuery('#excludedIds').data(cvId+'Excludedids',excludedIds);
	},

	getCurrentCvId : function(){
		return jQuery('#customFilter').find('option:selected').data('id');
	},

	getAlphabetSearchField : function() {
		return jQuery("#alphabetSearchKey").val();
	},

	getAlphabetSearchValue : function() {
		return jQuery("#alphabetValue").val();
	},


	/*
	 * Function to check whether atleast one record is checked
	 */
	checkListRecordSelected : function(){
		var selectedIds = this.readSelectedIds();
		if(typeof selectedIds == 'object' && selectedIds.length <= 0) {
			return true;
		}
		return false;
	},

	postMassEdit : function(massEditContainer) {
		var thisInstance = this;
		massEditContainer.find('form').on('submit', function(e){
			e.preventDefault();
			var form = jQuery(e.currentTarget);
			var invalidFields = form.data('jqv').InvalidFields;
			if(invalidFields.length == 0){
				form.find('[name="saveButton"]').attr('disabled',"disabled");
			}
			var invalidFields = form.data('jqv').InvalidFields;
			if(invalidFields.length > 0){
				return;
			}
			thisInstance.massActionSave(form, true).then(
				function(data) {
					thisInstance.getListViewRecords();
				},
				function(error,err){
				}
			)
		});
	},
	/*
	 * Function to register List view Page Navigation
	 */
	registerPageNavigationEvents : function(){
		var aDeferred = jQuery.Deferred();
		var thisInstance = this;
		jQuery('#listViewNextPageButton').on('click',function(){
			var pageLimit = jQuery('#pageLimit').val();
			var noOfEntries = jQuery('#noOfEntries').val();
			if(noOfEntries == pageLimit){
				var orderBy = jQuery('#orderBy').val();
				var sortOrder = jQuery("#sortOrder").val();
				var cvId = thisInstance.getCurrentCvId();
				var urlParams = {
					"orderby": orderBy,
					"sortorder": sortOrder,
					"viewname": cvId
				}
				var pageNumber = jQuery('#pageNumber').val();
				var nextPageNumber = parseInt(parseFloat(pageNumber)) + 1;
				jQuery('#pageNumber').val(nextPageNumber);
				jQuery('#pageToJump').val(nextPageNumber);
				thisInstance.getListViewRecords(urlParams).then(
					function(data){
						thisInstance.updatePagination();
						aDeferred.resolve();
					},

					function(textStatus, errorThrown){
						aDeferred.reject(textStatus, errorThrown);
					}
				);
			}
			return aDeferred.promise();
		});
		jQuery('#listViewPreviousPageButton').on('click',function(){
			var aDeferred = jQuery.Deferred();
			var pageNumber = jQuery('#pageNumber').val();
			if(pageNumber > 1){
				var orderBy = jQuery('#orderBy').val();
				var sortOrder = jQuery("#sortOrder").val();
				var cvId = thisInstance.getCurrentCvId();
				var urlParams = {
					"orderby": orderBy,
					"sortorder": sortOrder,
					"viewname" : cvId
				}
				var previousPageNumber = parseInt(parseFloat(pageNumber)) - 1;
				jQuery('#pageNumber').val(previousPageNumber);
				jQuery('#pageToJump').val(previousPageNumber);
				thisInstance.getListViewRecords(urlParams).then(
					function(data){
						thisInstance.updatePagination();
						aDeferred.resolve();
					},

					function(textStatus, errorThrown){
						aDeferred.reject(textStatus, errorThrown);
					}
				);
			}
		});

		jQuery('#listViewPageJump').on('click',function(e){
			var module = app.getModuleName();
			var cvId = thisInstance.getCurrentCvId();
			var pageCountParams = {
				'module' : module,
				'view' : "ListAjax",
				'mode' : "getPageCount",
				"viewname": cvId
			}
			var searchValue = thisInstance.getAlphabetSearchValue();

			if(searchValue.length > 0) {
				pageCountParams['search_key'] = thisInstance.getAlphabetSearchField();
				pageCountParams['search_value'] = searchValue;
				pageCountParams['operator'] = "s";
			}
			var element = jQuery('#totalPageCount');
			var totalPageNumber = element.text();
			if(totalPageNumber == ""){
				var totalRecordCount = jQuery('#totalCount').val();
				if(totalRecordCount != '') {
					var recordPerPage = jQuery('#numberOfEntries').val();
					pageCount = Math.round(totalRecordCount/recordPerPage);
					element.text(pageCount);
					return;
				}
				element.progressIndicator({});
				AppConnector.request(pageCountParams).then(
					function(data) {
						var response = JSON.parse(data);
						var pageCount = response['result']['page'];
						element.text(pageCount);
						element.progressIndicator({'mode': 'hide'});
					},
					function(error,err){

					}
				);
			}
		})

		jQuery('#listViewPageJumpDropDown').on('click','li',function(e){
			e.stopImmediatePropagation();
		}).on('keypress','#pageToJump',function(e){
			if(e.which == 13){
				e.stopImmediatePropagation();
				var element = jQuery(e.currentTarget);
				var response = Vtiger_WholeNumberGreaterThanZero_Validator_Js.invokeValidation(element);
				if(typeof response != "undefined"){
					element.validationEngine('showPrompt',response,'',"topLeft",true);
				} else {
					element.validationEngine('hideAll');
					var pageNumber = jQuery(e.currentTarget).val();
					jQuery('#pageNumber').val(pageNumber);
					thisInstance.getListViewRecords().then(
						function(data){
							thisInstance.updatePagination();
							element.closest('.btn-group ').removeClass('open');
						},
						function(textStatus, errorThrown){
						}
					);
				}
				return false;
		}
		});
	},

	/**
	 * Function to update Pagining status
	 */
	updatePagination : function(){
		var pageLimitValue = jQuery('#pageLimitValue').val();
		var numberOfEntries = jQuery('#numberOfEntries').val()
		var previousPageExist = jQuery('#previousPageExist').val();
		var nextPageExist = jQuery('#nextPageExist').val();
		var previousPageButton = jQuery('#listViewPreviousPageButton');
		var nextPageButton = jQuery('#listViewNextPageButton');
		var listViewEntriesCount = jQuery('#listViewEntriesCount').val();
		var pageStartRange = jQuery('#pageStartRange').val();
		var pageEndRange = jQuery('#pageEndRange').val();
		var totalCount = jQuery('#totalCount').val();

		jQuery('#pageLimit').val(pageLimitValue);
		jQuery('#noOfEntries').val(numberOfEntries);

		if(previousPageExist != ""){
			previousPageButton.removeAttr('disabled');
		} else if(previousPageExist == "") {
			previousPageButton.attr("disabled","disabled");
		}

		if(nextPageExist != ""){
			nextPageButton.removeAttr('disabled');
		} else if(nextPageExist == "") {
			nextPageButton.attr("disabled","disabled");
		}
		if(listViewEntriesCount != 0){
			var pageNumberText = pageStartRange+" "+app.vtranslate('to')+" "+pageEndRange;
			if(totalCount != '') {
				pageNumberText += " "+app.vtranslate('of')+" "+totalCount;
			}
			jQuery('.pageNumbers').html(pageNumberText);
		} else {
			jQuery('.pageNumbers').html("");
		}
	},
	/*
	 * Function to register the event for changing the custom Filter
	 */
	registerChangeCustomFilterEvent : function(){
		var thisInstance = this;
		var filterSelectElement = this.getFilterSelectElement();
		filterSelectElement.change(function(e){
			jQuery('#pageNumber').val("1");
			jQuery('#orderBy').val('');
			jQuery("#sortOrder").val('');
			var cvId = thisInstance.getCurrentCvId();
			selectedIds = new Array();
			excludedIds = new Array();

            var urlParams ={
                "viewname" : cvId,
                //to make alphabetic search empty
                "search_key" : thisInstance.getAlphabetSearchField(),
                "search_value" : ""
            }
			thisInstance.getListViewRecords(urlParams).then (function(){
                //Make the select all count as empty
                jQuery('#recordsCount').val('');
				thisInstance.updatePagination();
            });
		});
	},

	/*
	 * Function to register the click event for list view main check box.
	 */
	registerMainCheckBoxClickEvent : function(){
		var listViewPageDiv = this.getListViewContainer();
		var thisInstance = this;
		listViewPageDiv.on('click','#listViewEntriesMainCheckBox',function(){
			var selectedIds = thisInstance.readSelectedIds();
			var excludedIds = thisInstance.readExcludedIds();
			if(jQuery('#listViewEntriesMainCheckBox').is(":checked")){
				var recordCountObj = thisInstance.getRecordsCount();
				recordCountObj.then(function(data){
					jQuery('#totalRecordsCount').text(data);
					if(jQuery("#deSelectAllMsgDiv").css('display') == 'none'){
						jQuery("#selectAllMsgDiv").show();
					}
				});

				jQuery('.listViewEntriesCheckBox').each( function(index,element) {
					jQuery(this).attr('checked', true).closest('tr').addClass('highlightBackgroundColor');
					if(selectedIds == 'all'){
						if((jQuery.inArray(jQuery(element).val(), excludedIds))!= -1){
							excludedIds.splice(jQuery.inArray(jQuery(element).val(),excludedIds),1);
						}
					} else if((jQuery.inArray(jQuery(element).val(), selectedIds)) == -1){
						selectedIds.push(jQuery(element).val());
					}
				});
			}else{
				jQuery("#selectAllMsgDiv").hide();
				jQuery('.listViewEntriesCheckBox').each( function(index,element) {
					jQuery(this).attr('checked', false).closest('tr').removeClass('highlightBackgroundColor');
				if(selectedIds == 'all'){
					excludedIds.push(jQuery(element).val());
					selectedIds = 'all';
				} else {
					selectedIds.splice( jQuery.inArray(jQuery(element).val(), selectedIds), 1 );
				}
				});
			}
			thisInstance.writeSelectedIds(selectedIds);
			thisInstance.writeExcludedIds(excludedIds);

		});
	},

	/*
	 * Function  to register click event for list view check box.
	 */
	registerCheckBoxClickEvent : function(){
		var listViewPageDiv = this.getListViewContainer();
		var thisInstance = this;
		listViewPageDiv.delegate('.listViewEntriesCheckBox','click',function(e){
			var selectedIds = thisInstance.readSelectedIds();
			var excludedIds = thisInstance.readExcludedIds();
			var elem = jQuery(e.currentTarget);
			if(elem.is(':checked')){
				elem.closest('tr').addClass('highlightBackgroundColor');
				if(selectedIds== 'all'){
					excludedIds.splice( jQuery.inArray(elem.val(), excludedIds), 1 );
				} else if((jQuery.inArray(elem.val(), selectedIds)) == -1) {
					selectedIds.push(elem.val());
				}
			} else {
				elem.closest('tr').removeClass('highlightBackgroundColor');
				if(selectedIds == 'all') {
					excludedIds.push(elem.val());
					selectedIds = 'all';
				} else {
					selectedIds.splice( jQuery.inArray(elem.val(), selectedIds), 1 );
				}
			}
			thisInstance.checkSelectAll();
			thisInstance.writeSelectedIds(selectedIds);
			thisInstance.writeExcludedIds(excludedIds);
		});
	},

	/*
	 * Function to register the click event for select all.
	 */
	registerSelectAllClickEvent :  function(){
		var listViewPageDiv = this.getListViewContainer();
		var thisInstance = this;
		listViewPageDiv.delegate('#selectAllMsg','click',function(){
			jQuery('#selectAllMsgDiv').hide();
			jQuery("#deSelectAllMsgDiv").show();
			jQuery('#listViewEntriesMainCheckBox').attr('checked',true);
			jQuery('.listViewEntriesCheckBox').each( function(index,element) {
				jQuery(this).attr('checked', true).closest('tr').addClass('highlightBackgroundColor');
			});
			thisInstance.writeSelectedIds('all');
		});
	},

	/*
	* Function to register the click event for deselect All.
	*/
	registerDeselectAllClickEvent : function(){
		var listViewPageDiv = this.getListViewContainer();
		var thisInstance = this;
		listViewPageDiv.delegate('#deSelectAllMsg','click',function(){
			jQuery('#deSelectAllMsgDiv').hide();
			jQuery('#listViewEntriesMainCheckBox').attr('checked',false);
			jQuery('.listViewEntriesCheckBox').each( function(index,element) {
				jQuery(this).attr('checked', false).closest('tr').removeClass('highlightBackgroundColor');
			});
			var excludedIds = new Array();
			var selectedIds = new Array();
			thisInstance.writeSelectedIds(selectedIds);
			thisInstance.writeExcludedIds(excludedIds);
		});
	},

	/*
	 * Function to register the click event for listView headers
	 */
	registerHeadersClickEvent :  function(){
		var listViewPageDiv = this.getListViewContainer();
		var thisInstance = this;
		listViewPageDiv.on('click','.listViewHeaderValues',function(e){
			var fieldName = jQuery(e.currentTarget).data('columnname');
			var sortOrderVal = jQuery(e.currentTarget).data('nextsortorderval');
			var cvId = thisInstance.getCurrentCvId();
			var urlParams = {
				"orderby": fieldName,
				"sortorder": sortOrderVal,
				"viewname" : cvId
			}
			thisInstance.getListViewRecords(urlParams);
		});
	},

	/*
	 * function to register the click event event for create filter
	 */
	registerCreateFilterClickEvent : function(){
		var thisInstance = this;
		jQuery('#createFilter').on('click',function(event){
			//to close the dropdown
			thisInstance.getFilterSelectElement().data('select2').close();
			var currentElement = jQuery(event.currentTarget);
			var createUrl = currentElement.data('createurl');
			Vtiger_CustomView_Js.loadFilterView(createUrl);
		});
	},

	/*
	 * Function to register the click event for edit filter
	 */
	registerEditFilterClickEvent : function(){
		var thisInstance = this;
		var listViewFilterBlock = this.getFilterBlock();
		listViewFilterBlock.on('mouseup','li i.editFilter',function(event){
			//to close the dropdown
			thisInstance.getFilterSelectElement().data('select2').close();
			var liElement = jQuery(event.currentTarget).closest('.select2-result-selectable');
			var currentOptionElement = thisInstance.getSelectOptionFromChosenOption(liElement);
			var editUrl = currentOptionElement.data('editurl');
			Vtiger_CustomView_Js.loadFilterView(editUrl);
			event.stopPropagation();
		});
	},

	/*
	 * Function to register the click event for delete filter
	 */
	registerDeleteFilterClickEvent: function(){
		var thisInstance = this;
		var listViewFilterBlock = this.getFilterBlock();
		//used mouseup event to stop the propagation of customfilter select change event.
		listViewFilterBlock.on('mouseup','li i.deleteFilter',function(event){
			//to close the dropdown
			thisInstance.getFilterSelectElement().data('select2').close();
			var liElement = jQuery(event.currentTarget).closest('.select2-result-selectable');
			var message = app.vtranslate('JS_LBL_ARE_YOU_SURE_YOU_WANT_TO_DELETE');
			Vtiger_Helper_Js.showConfirmationBox({'message' : message}).then(
				function(e) {
					var currentOptionElement = thisInstance.getSelectOptionFromChosenOption(liElement);
					var deleteUrl = currentOptionElement.data('deleteurl');
					window.location.href = deleteUrl;
				},
				function(error, err){
				}
			);
			event.stopPropagation();
		});
	},

	/*
	 * Function to register the click event for approve filter
	 */
	registerApproveFilterClickEvent: function(){
		var thisInstance = this;
		var listViewFilterBlock = this.getFilterBlock();

		listViewFilterBlock.on('mouseup','li i.approveFilter',function(event){
			//to close the dropdown
			thisInstance.getFilterSelectElement().data('select2').close();
			var liElement = jQuery(event.currentTarget).closest('.select2-result-selectable');
			var currentOptionElement = thisInstance.getSelectOptionFromChosenOption(liElement);
			var approveUrl = currentOptionElement.data('approveurl');
			window.location.href = approveUrl;
			event.stopPropagation();
		});
	},

	/*
	 * Function to register the click event for deny filter
	 */
	registerDenyFilterClickEvent: function(){
		var thisInstance = this;
		var listViewFilterBlock = this.getFilterBlock();

		listViewFilterBlock.on('mouseup','li i.denyFilter',function(event){
			//to close the dropdown
			thisInstance.getFilterSelectElement().data('select2').close();
			var liElement = jQuery(event.currentTarget).closest('.select2-result-selectable');
			var currentOptionElement = thisInstance.getSelectOptionFromChosenOption(liElement);
			var denyUrl = currentOptionElement.data('denyurl');
			window.location.href = denyUrl;
			event.stopPropagation();
		});
	},

	/*
	 * Function to register the hover event for customview filter options
	 */
	registerCustomFilterOptionsHoverEvent : function(){
		var thisInstance = this;
		var listViewTopMenuDiv = this.getListViewTopMenuContainer();
		var filterBlock = this.getFilterBlock()
		filterBlock.on('hover','li.select2-result-selectable',function(event){
			var liElement = jQuery(event.currentTarget);
			var liFilterImages = liElement.find('.filterActionImgs');
			if (liElement.hasClass('group-result')){
				return;
			}

			if( event.type === 'mouseenter' ) {
				if(liFilterImages.length > 0){
					liFilterImages.show();
				}else{
					jQuery('.filterActionImages').clone(true,true).removeClass('filterActionImages').addClass('filterActionImgs').appendTo(liElement.find('.select2-result-label')).show();
					var currentOptionElement = thisInstance.getSelectOptionFromChosenOption(liElement);
					var deletable = currentOptionElement.data('deletable');
					if(deletable != '1'){
						liElement.find('.deleteFilter').remove();
					}
					var editable = currentOptionElement.data('editable');
					if(editable != '1'){
						liElement.find('.editFilter').remove();
					}
					var pending = currentOptionElement.data('pending');
					if(pending != '1'){
						liElement.find('.approveFilter').remove();
					}
					var approve = currentOptionElement.data('public');
					if(approve != '1'){
						liElement.find('.denyFilter').remove();
					}
				}

			} else {
				liFilterImages.hide();
			}
		});
	},

	/*
	 * Function to register the list view row click event
	 */
	registerRowClickEvent: function(){
		var thisInstance = this;
		var listViewContentDiv = this.getListViewContentContainer();
		listViewContentDiv.on('click','.listViewEntries',function(e){
			if(jQuery(e.target).is('input[type="checkbox"]')) return;
			var elem = jQuery(e.currentTarget);
			var recordUrl = elem.data('recordurl');
			window.location.href = recordUrl;
		});
	},

	/*
	 * Function to register the list view delete record click event
	 */
	registerDeleteRecordClickEvent: function(){
		var thisInstance = this;
		var listViewContentDiv = this.getListViewContentContainer();
		listViewContentDiv.on('click','.deleteRecordButton',function(e){
			var elem = jQuery(e.currentTarget);
			var recordId = elem.closest('tr').data('id');
			Vtiger_List_Js.deleteRecord(recordId);
			e.stopPropagation();
		});
	},
	/*
	 * Function to register the click event of email field
	 */
	registerEmailFieldClickEvent : function(){
		var listViewContentDiv = this.getListViewContentContainer();
		listViewContentDiv.on('click','.emailField',function(e){
			e.stopPropagation();
		})

	},

	/**
	 * Function to inactive field for validation in a form
	 * this will remove data-validation-engine attr of all the elements
	 * @param Accepts form as a parameter
	 */
	inactiveFieldValidation : function(form){
        var massEditFieldList = jQuery('#massEditFieldsNameList').data('value');
		for(var fieldName in massEditFieldList){
            var fieldInfo = massEditFieldList[fieldName];
            
            var fieldElement = form.find('[name="'+fieldInfo.name+'"]');
            if(fieldInfo.type == "reference") {
                //get the element which will be shown which has "_display" appended to actual field name
                fieldElement = form.find('[name="'+fieldInfo.name+'_display"]');
            }else if(fieldInfo.type == "multipicklist") {
                fieldElement = form.find('[name="'+fieldInfo.name+'[]"]');
            }
            
            //Not all the fields will be enabled for mass edit
            if(fieldElement.length == 0 ) {
                continue;
            }
            
			var elemData = fieldElement.data();
            
            //Blank validation by default
            var validationVal = "validate[]"
            if('validationEngine' in elemData) {
                validationVal =  elemData.validationEngine;
                delete elemData.validationEngine;
            }
            fieldElement.data('invalidValidationEngine',validationVal);
			fieldElement.removeAttr('data-validation-engine');
		}
	},

	/**
	 * function to register field for validation
	 * this will add the data-validation-engine attr of all the elements
	 * make the field available for validation
	 * @param Accepts form as a parameter
	 */
	registerFieldsForValidation : function(form){
		form.find('.fieldValue').on('change','input,select,textarea',function(e, params){
			if(typeof params == 'undefined'){
				params = {};
			}
			
			if(typeof params.forceDeSelect == 'undefined') {
				params.forceDeSelect = false;
			}
			var element = jQuery(e.currentTarget);
			var fieldValue = element.val();
			var parentTd = element.closest('td');
			if(((fieldValue == "" || fieldValue == null) && (typeof(element.attr('data-validation-engine')) != "undefined")) || params.forceDeSelect){
				if(parentTd.hasClass('massEditActiveField')){
					parentTd.removeClass('massEditActiveField');
				}
				element.removeAttr('data-validation-engine');
				element.validationEngine('hide');
				var invalidFields = form.data('jqv').InvalidFields;
				var response = jQuery.inArray(element.get(0),invalidFields);
				if(response != '-1'){
					invalidFields.splice(response,1);
				}
			} else if((fieldValue != "") && (typeof(element.attr('data-validation-engine')) == "undefined")){
				element.attr('data-validation-engine', element.data('invalidValidationEngine'));
				parentTd.addClass('massEditActiveField');
			}
		})
	},

	registerEventForTabClick : function(form){
		var ulContainer = form.find('.massEditTabs');
		ulContainer.on('click','a[data-toggle="tab"]',function(e){
			form.validationEngine('validate');
			var invalidFields = form.data('jqv').InvalidFields;
			if(invalidFields.length > 0){
				e.stopPropagation();
			}
		});
	},

	registerReferenceFieldsForValidation : function(form){
		var referenceField = form.find('.sourceField');
		form.find('.sourceField').on(Vtiger_Edit_Js.referenceSelectionEvent,function(e,params){
			var element = jQuery(e.currentTarget);
			var elementName = element.attr('name');
			var fieldDisplayName = elementName+"_display";
			var fieldDisplayElement = form.find('input[name="'+fieldDisplayName+'"]');
			if(params.selectedName == ""){
				return;
			}
			fieldDisplayElement.attr('data-validation-engine', fieldDisplayElement.data('invalidValidationEngine'));
            var parentTd = fieldDisplayElement.closest('td');
            if(!parentTd.hasClass('massEditActiveField')){
                parentTd.addClass('massEditActiveField');
            }
		})
		form.find('.clearReferenceSelection').on(Vtiger_Edit_Js.referenceDeSelectionEvent,function(e){
			var sourceField = form.find('.sourceField');
			var sourceFieldName = sourceField.attr('name');
			var fieldDisplayName = sourceFieldName+"_display";
			var fieldDisplayElement = form.find('input[name="'+fieldDisplayName+'"]').removeAttr('data-validation-engine');
            var parentTd = fieldDisplayElement.closest('td');
            if(parentTd.hasClass('massEditActiveField')){
                parentTd.removeClass('massEditActiveField');
            }
		})
	},

	registerSlimScrollMassEdit : function() {
		app.showScrollBar(jQuery('div[name="massEditContent"]'), {'height':'400px'});
	},

	/*
	 * Function to register the submit event for mass Actions save
	 */
	registerMassActionSubmitEvent : function(){
        var thisInstance = this;
		jQuery('body').on('submit','#massSave',function(e){
			var form = jQuery(e.currentTarget);
			var commentContent = form.find('#commentcontent')
			var commentContentValue = commentContent.val();
			if(commentContentValue == "") {
				var errorMsg = app.vtranslate('JS_LBL_COMMENT_VALUE_CANT_BE_EMPTY')
				commentContent.validationEngine('showPrompt', errorMsg , 'error','bottomLeft',true);
				e.preventDefault();
				return;
			}
			commentContent.validationEngine('hide');
			thisInstance.massActionSave(form);
			e.preventDefault();
		});
	},

	changeCustomFilterElementView : function() {
		var filterSelectElement = this.getFilterSelectElement();
		app.showSelect2ElementView(filterSelectElement,{
			formatSelection : function(data, contianer){
				var resultContainer = jQuery('<span></span>');
				resultContainer.append(jQuery(jQuery('.filterImage').clone().get(0)).show());
				resultContainer.append(data.text);
				return resultContainer;
			}
		});

		var select2Instance = filterSelectElement.data('select2');
		select2Instance.dropdown.append(jQuery('span.filterActionsDiv'));
	},

	triggerDisplayTypeEvent : function() {
		var widthType = app.cacheGet('widthType', 'narrowWidthType');
		if(widthType) {
			var elements = jQuery('.listViewEntriesTable').find('td,th');
			elements.attr('class', widthType);
		}
	},

	registerEventForAlphabetSearch : function() {
		var thisInstance = this;
		var listViewPageDiv = this.getListViewContainer();
		listViewPageDiv.on('click','.alphabetSearch',function(e) {
			var alphabet = jQuery(e.currentTarget).find('a').text();
			var cvId = thisInstance.getCurrentCvId();
			var AlphabetSearchKey = thisInstance.getAlphabetSearchField();
			var urlParams = {
				"viewname" : cvId,
				"search_key" : AlphabetSearchKey,
				"search_value" : alphabet,
				"operator" : 's',
				"page"	:	1
			}
			thisInstance.getListViewRecords(urlParams).then(
					function(data){
						thisInstance.updatePagination();
                        jQuery('#recordsCount').val('');
                        //To Set the page number as first page
                        jQuery('#pageNumber').val('1');
						jQuery('#pageToJump').val('1');
						jQuery('#totalPageCount').text("");
                        //To unmark the all the selected ids
                        jQuery('#deSelectAllMsg').trigger('click');
					},

					function(textStatus, errorThrown){
					}
			);
		});
	},

	registerEvents : function(){

		this.registerPageNavigationEvents();
		this.changeCustomFilterElementView();

		this.registerChangeCustomFilterEvent();
		this.registerMainCheckBoxClickEvent();
		this.registerCheckBoxClickEvent();
		this.registerSelectAllClickEvent();
		this.registerDeselectAllClickEvent();
		this.registerHeadersClickEvent();
		this.registerCreateFilterClickEvent();
		this.registerEditFilterClickEvent();
		this.registerDeleteFilterClickEvent();
		this.registerApproveFilterClickEvent();
		this.registerDenyFilterClickEvent();
		this.registerCustomFilterOptionsHoverEvent();
		this.registerRowClickEvent();
		this.registerDeleteRecordClickEvent();
		this.registerEmailFieldClickEvent();
		this.registerMassActionSubmitEvent();
		this.triggerDisplayTypeEvent();
		this.registerEventForAlphabetSearch();

		//Just reset all the checkboxes on page load: added for chrome issue.
		var listViewContainer = this.getListViewContentContainer();
		listViewContainer.find('#listViewEntriesMainCheckBox,.listViewEntriesCheckBox').prop('checked', false);
	}
});

//On Page Load
jQuery(document).ready(function() {
	var listInstance  =  Vtiger_List_Js.getInstance();
	listInstance.registerEvents();
});
