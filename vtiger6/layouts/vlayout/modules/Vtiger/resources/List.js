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
		Vtiger_Helper_Js.checkServerConfig(module).then(function(data){
			if(data == true){
				var callBackFunction = function(data){
					var emailEditInstance = new Emails_MassEdit_Js();
					emailEditInstance.registerEmailFieldSelectionEvent();
				}

				Vtiger_List_Js.triggerMassAction(massActionUrl,callBackFunction, function(data){
					data = jQuery(data);
					var form = data.find('#SendEmailFormStep1');
					var emailFields = form.find('.emailField');
					var length = emailFields.length;
					if(length > 1) {
						return true;
					}
					emailFields.attr('checked','checked');
					var form = data.find('form');
					var params = form.serializeFormData();
					var emailEditInstance = new Emails_MassEdit_Js();
					emailEditInstance.showComposeEmailForm(params);
					return false;
				},{'width':'30%'});
			} else {
				alert(app.vtranslate('JS_EMAIL_SERVER_CONFIGURATION'));
			}
		});
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
					AppConnector.request(deleteURL).then(
						function(data) {
							app.hideModalWindow();
							var module = app.getModuleName();
							AppConnector.request('index.php?module='+module+'&view=List&viewname='+cvId).then(
								function(data) {
									var listViewContainer = listInstance.getListViewContentContainer();
									listViewContainer.html(data);
									jQuery('#deSelectAllMsg').trigger('click');
							});
							jQuery('#recordsCount').val('');
						}
					);
				},
				function(error, err){
				}
			);
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
							listInstance.getListViewRecords(urlParams);
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
			var editInstance = Vtiger_Edit_Js.getInstance();
			editInstance.registerBasicEvents(jQuery(container));
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
		window.location.href = exportActionUrl+'&selected_ids='+selectedIds+'&excluded_ids='+excludedIds+'&viewname='+cvId+'&page='+pageNumber;
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
			var allowedKeys = new Array("module","action","excluded_ids","selected_ids","viewname");
			for(var key in massActionUrl){
				if(jQuery.inArray(key,allowedKeys) == '-1'){
					var validationElement = form.find('[name='+key+'][data-validation-engine]');
					if(validationElement.length == 0){
						delete massActionUrl[key];
						if(fieldsChanged != true){
							fieldsChanged = false;
						}
					} else {
						fieldsChanged = true;
					}
				}
			}
			if(fieldsChanged == false){
				Vtiger_Helper_Js.showPnotify(app.vtranslate('NONE_OF_THE_FIELD_VALUES_ARE_CHANGED_IN_MASS_EDIT'));
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
		return aDeferred.promise();
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

		if(listViewEntriesCount != ""){
			var pageNumberText = pageStartRange+" "+app.vtranslate('to')+" "+pageEndRange;
			jQuery('.pageNumbers').html(pageNumberText);
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
			var module = app.getModuleName();
			//TODO move it to Documents.js
			if(module == 'Documents'){
				var search_value = filterSelectElement.find('option:selected').data('foldername');
				var urlParams = {
					"viewname" : cvId,
					"search_key" : 'folderid',
					"search_value" : search_value
				}
			} else {
				var urlParams ={
					"viewname" : cvId
				}
			}
			thisInstance.getListViewRecords(urlParams);
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
		var validationFields = form.find('[data-validation-engine]');
		jQuery.each(validationFields,function(index,element){
			var elemData = jQuery(element).data();
			elemData.invalidValidationEngine = elemData.validationEngine;
			delete elemData.validationEngine;
			jQuery(element).removeAttr('data-validation-engine');
		})
	},

	/**
	 * function to register field for validation
	 * this will add the data-validation-engine attr of all the elements
	 * make the field available for validation
	 * @param Accepts form as a parameter
	 */
	registerFieldsForValidation : function(form){
		form.find('.fieldValue').on('change','input,select,textarea',function(e){
			var element = jQuery(e.currentTarget);
			var fieldValue = element.val();
			var parentTd = element.closest('td');
			if((fieldValue == "") && (typeof(element.attr('data-validation-engine')) != "undefined")){
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
			element.attr('data-validation-engine', element.data('invalidValidationEngine'));
			fieldDisplayElement.attr('data-validation-engine', element.data('invalidValidationEngine'));
		})
		form.find('.clearReferenceSelection').on(Vtiger_Edit_Js.referenceDeSelectionEvent,function(e){
			var sourceField = form.find('.sourceField');
			var sourceFieldName = sourceField.attr('name');
			var fieldDisplayName = sourceFieldName+"_display";
			sourceField.removeAttr('data-validation-engine');
			form.find('input[name="'+fieldDisplayName+'"]').removeAttr('data-validation-engine');
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
		var widthType = app.cacheGet('widthType', 'wideWidthType');
		if(widthType) {
			var elements = jQuery('.listViewEntriesTable').find('td,th');
			elements.attr('class', widthType);
		}
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
