/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

jQuery.Class("Vtiger_Helper_Js",{
    
	checkServerConfigResponseCache : '',
	/*
	 * Function to get the instance of Mass edit of Email 
	 */
	getEmailMassEditInstance : function(){
		
		var className = 'Emails_MassEdit_Js';
		var emailMassEditInstance = new window[className]();
		return emailMassEditInstance
	},
    /*
	 * function to check server Configuration
	 * returns boolean true or false
	 */
	
	checkServerConfig : function(module){
		var aDeferred = jQuery.Deferred();
		var actionParams = {
			"action": 'CheckServerInfo',
			'module' : module
		};
		AppConnector.request(actionParams).then(
			function(data) {
				var state = false;
				if(data.result){
					state = true;
				} else {
					state = false;
				}
				aDeferred.resolve(state);
			}
		);
		return aDeferred.promise();
	},
	/*
	 * Function to get Date Instance
	 * @params date---this is the field value
	 * @params dateFormat---user date format
	 * @return date object
	 */
	
	getDateInstance : function(dateTime,dateFormat){
		var dateTimeComponents = dateTime.split(" ");
		var dateComponent = dateTimeComponents[0];
		var timeComponent = dateTimeComponents[1];
		
		//Am/Pm component exits
		if(typeof dateTimeComponents[2] != 'undefined') {
			timeComponent += ' ' + dateTimeComponents[2];
		}
		
		var splittedDate = dateComponent.split("-");
		var splittedDateFormat = dateFormat.split("-");
		var year = splittedDate[splittedDateFormat.indexOf("yyyy")];
		var month = splittedDate[splittedDateFormat.indexOf("mm")];
		var date = splittedDate[splittedDateFormat.indexOf("dd")];
		if((year.length > 4) || (month.length > 2) || (date.length > 2)){
				var errorMsg = app.vtranslate("JS_INVALID_DATE");
				throw errorMsg;
		}
		//Before creating date object time is set to 00
		//because as while calculating date object it depends system timezone
		if(typeof timeComponent == "undefined"){
			timeComponent = '00:00:00';
		}
		var finalDate = month+" "+date+","+year+" "+timeComponent;
		
		return new Date(finalDate);
	},
	requestToShowComposeEmailForm : function(selectedId,fieldname){
		var selectedFields = new Array();
		selectedFields.push(fieldname);
		var selectedIds =  new Array();
		selectedIds.push(selectedId);
		var params = {
			'module' : 'Emails',
			'selectedFields' : selectedFields,
			'selected_ids' : selectedIds,
			'view' : 'ComposeEmail'
		}
		var emailsMassEditInstance = Vtiger_Helper_Js.getEmailMassEditInstance();
		emailsMassEditInstance.showComposeEmailForm(params);
	},
	
	/*
	 * Function to get the compose email popup
	 */
	getInternalMailer  : function(selectedId,fieldname){
		var module = 'Emails';
		var cacheResponse = Vtiger_Helper_Js.checkServerConfigResponseCache;
		var  checkServerConfigPostOperations = function (data) {
			if(data == true){
				Vtiger_Helper_Js.requestToShowComposeEmailForm(selectedId,fieldname);
			} else {
				alert(app.vtranslate('JS_EMAIL_SERVER_CONFIGURATION'));
			}
		}
		if(cacheResponse === ''){
			var checkServerConfig = Vtiger_Helper_Js.checkServerConfig(module); 
			checkServerConfig.then(function(data){
				Vtiger_Helper_Js.checkServerConfigResponseCache = data;
				checkServerConfigPostOperations(Vtiger_Helper_Js.checkServerConfigResponseCache);
			});
		} else {
			checkServerConfigPostOperations(Vtiger_Helper_Js.checkServerConfigResponseCache);
		}
	},
	
	/*
	 * Function to show the confirmation messagebox
	 */
	showMessageBox : function(data){
		var aDeferred = jQuery.Deferred();
		var html = '<div class="modelContainer messageBox">'+
						'<div class="conformationMsg"></div>'+
						'<div class="messageBoxActions btn-toolbar">'+
							'<button type="button" class="btn btn-small btn-success success"><strong>'+app.vtranslate('LBL_YES')+'</strong></button>&nbsp;'+
							'<button type="button" class="btn btn-small btn-danger failure"><strong>'+app.vtranslate('LBL_NO')+'</strong></button>'+
						'</div>'+
					'</div>';
		var messageContainer = jQuery(html);
		messageContainer.find('.conformationMsg').html(data['message']);
		
		var callBackFunction = function(){
			aDeferred.reject();
		}
		var params = {};
		params.data = messageContainer ;
		params.unblockcb = callBackFunction;
		params.css = {'text-align': 'center'};
		app.showModalWindow(params);
		
		jQuery('.success', messageContainer).on('click', function() {
			app.hideModalWindow();
			aDeferred.resolve();
		});
		jQuery('.failure', messageContainer).on('click', function() {
			app.hideModalWindow();
			aDeferred.reject();
		});
		return aDeferred.promise();
	},
	
	/*
	 * Function to check Duplication of Account Name
	 * returns boolean true or false
	 */
        
	checkDuplicateName : function(details) {
		var accountName = details.accountName;
		var recordId = details.recordId;
		var aDeferred = jQuery.Deferred();
		var moduleName = app.getModuleName();
		var params = {
		'module' : moduleName,
		'action' : "CheckDuplicate",
		'accountname' : accountName,
		'record' : recordId
		}
		AppConnector.request(params).then(
			function(data) {
				var response = data['result'];
				var result = response['success'];
				if(result == true) {
					aDeferred.reject(response);
				} else {
					aDeferred.resolve(response);
				}
			},
			function(error,err){
				aDeferred.reject();
			}
		);
		return aDeferred.promise();
	},
	
	/*
	 * Function to show pnotify message
	 */
	showPnotify : function(customParams) {
		
		var userParams = customParams;
		if(typeof customParams == 'string') {
			var userParams = {};
			userParams.text = customParams;
		}

		var params = {
			sticker: false,
			delay: '3000',
			type: 'error',
			pnotify_history: false
		}
		
		if(typeof userParams != 'undefined'){
			var params = jQuery.extend(params,userParams);
		}
		jQuery.pnotify(params);
	}

	
},{});