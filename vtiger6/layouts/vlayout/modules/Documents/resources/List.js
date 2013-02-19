/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_List_Js("Documents_List_Js", {

	triggerAddFolder : function(url) {
		var params = url;
		AppConnector.request(params).then(
			function(data) {
				var callBackFunction = function(data){
					jQuery('#addDocumentsFolder').validationEngine({
						// to prevent the page reload after the validation has completed
						'onValidationComplete' : function(form,valid){
                             return valid;
						}
					});
					Vtiger_List_Js.getInstance().folderSubmit().then(function(data){
						app.hideModalWindow();
						if(data.success){
							var result = data.result;
							if(result.success){
								var info = result.info;
								Vtiger_List_Js.getInstance().updateCustomFilter(info);
								var  params = {
									title : app.vtranslate('JS_NEW_FOLDER'),
									text : result.message,
									delay: '2000',
									type: 'success'
								}
								Vtiger_Helper_Js.showPnotify(params);
							} else {
								var result = result.message;
								var folderNameElement = jQuery('#documentsFolderName');
								folderNameElement.validationEngine('showPrompt', result , 'error','topLeft',true);
							}
						} else {
							var  params = {
									title : app.vtranslate('JS_ERROR'),
									text : data.error.message,
									type: 'error'
								}
							Vtiger_Helper_Js.showPnotify(params);
						}
					});
				};
				app.showModalWindow(data,function(data){
					if(typeof callBackFunction == 'function'){
						callBackFunction(data);
					}
				});
			}
		)
	},

	massMove : function(url){
		var listInstance = Vtiger_List_Js.getInstance();
		var validationResult = listInstance.checkListRecordSelected();
		if(validationResult != true){
			var selectedIds = listInstance.readSelectedIds(true);
			var excludedIds = listInstance.readExcludedIds(true);
			var cvId = listInstance.getCurrentCvId();
			var postData = {
				"selected_ids":selectedIds,
				"excluded_ids" : excludedIds,
				"viewname" : cvId
			};
            
            var searchValue = listInstance.getAlphabetSearchValue();

            if(searchValue.length > 0) {
                postData['search_key'] = listInstance.getAlphabetSearchField();
                postData['search_value'] = searchValue;
                postData['operator'] = "s";
            }

			var params = {
				"url":url,
				"data" : postData
			};
			AppConnector.request(params).then(
				function(data) {
					var callBackFunction = function(data){

						listInstance.moveDocuments().then(function(data){
							if(data){
								var result = data.result;
								if(result.success){
									app.hideModalWindow();
									var  params = {
										title : app.vtranslate('JS_MOVE_DOCUMENTS'),
										text : result.message,
										delay: '2000',
										type: 'success'
									}
									Vtiger_Helper_Js.showPnotify(params);
									var urlParams = listInstance.getDefaultParams();
									listInstance.getListViewRecords(urlParams);
								} else {
									var  params = {
										title : app.vtranslate('JS_OPERATION_DENIED'),
										text : result.message,
										delay: '2000',
										type: 'error'
									}
									Vtiger_Helper_Js.showPnotify(params);
								}
							}
						});
					}
					app.showModalWindow(data,callBackFunction);
				}
			)
		} else{
			listInstance.noRecordSelectedAlert();
		}

	}

} ,{


	folderSubmit : function() {
		var aDeferred = jQuery.Deferred();
		jQuery('#addDocumentsFolder').on('submit',function(e){
			var validationResult = jQuery(e.currentTarget).validationEngine('validate');
			if(validationResult == true){
				var formData = jQuery(e.currentTarget).serializeFormData();
				AppConnector.request(formData).then(
					function(data){
						aDeferred.resolve(data);
					}
				);
			}
			e.preventDefault();
		});
		return aDeferred.promise();
	},

	updateCustomFilter : function (info){
		var folderId = info.folderId;
		var customFilter =  jQuery("#customFilter");
		var constructedOption = this.constructOptionElement(info);
		var optionId = 'filterOptionId_'+folderId;
		var optionElement = jQuery('#'+optionId);
		if(optionElement.length > 0){
			optionElement.replaceWith(constructedOption);
			customFilter.trigger("liszt:updated");
		} else {
			customFilter.find('#foldersBlock').append(constructedOption).trigger("liszt:updated");
		}
	},

	constructOptionElement : function(info){
		var cvId = this.getCurrentCvId();
		return '<option data-foldername="'+info.folderName+'" data-id="'+cvId+'" >'+info.folderName+'</option>';

	},

	moveDocuments : function(){
		var aDeferred = jQuery.Deferred();
		jQuery('#moveDocuments').on('submit',function(e){
			var formData = jQuery(e.currentTarget).serializeFormData();
			AppConnector.request(formData).then(
				function(data){
					aDeferred.resolve(data);
				}
			);
			e.preventDefault();
		});
		return aDeferred.promise();
	},

	getDefaultParams : function() {
		var search_value = jQuery('#customFilter').find('option:selected').data('foldername');
		var customParams = {
					'folder_id' : 'folderid',
					'folder_value' : search_value
					}
		var params = this._super();
		jQuery.extend(params,customParams);
		return params;
	}

});


