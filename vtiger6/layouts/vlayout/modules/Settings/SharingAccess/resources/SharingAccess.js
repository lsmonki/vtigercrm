/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
jQuery.Class('Settings_Sharing_Access_Js', {}, {

	contentTable : false,
	contentsContainer : false,
	
	init : function() {
		this.setContentTable('.sharingAccessDetails').setContentContainer('#sharingAccessContainer');

	},

	setContentTable : function(element) {
		if(element instanceof jQuery){
			this.contentTable = element;
			return this;
		}
		this.contentTable = jQuery(element);
		return this;
	},

	setContentContainer : function(element) {
		if(element instanceof jQuery){
			this.contentsContainer = element;
			return this;
		}
		this.contentsContainer = jQuery(element);
		return this;
	},

	getContentTable : function() {
		return this.contentTable;
	},

	getContentContainer : function() {
		return this.contentsContainer;
	},

	getCustomRuleContainerClassName : function(parentModuleName) {
		return parentModuleName+'CustomRuleList';
	},

	showCustomRulesNextToElement : function(parentElement, rulesListElement) {
		var moduleName = parentElement.data('moduleName')
		var trElementForRuleList = jQuery('<tr class="'+this.getCustomRuleContainerClassName(moduleName)+'"><td class="row-fluid" colspan="6"></td></tr>');
		jQuery('td',trElementForRuleList).append(rulesListElement);
		parentElement.after(trElementForRuleList).addClass('collapseRow');
	},

	getCustomRules : function(forModule) {
		var aDeferred = jQuery.Deferred();
		var params = {}
		params['for_module'] = forModule;
		params['module'] = app.getModuleName();
		params['parent'] = app.getParentModuleName();
		params['view'] = 'IndexAjax';
		params['mode'] = 'showRules';
		AppConnector.request(params).then(
			function(data) {
				aDeferred.resolve(data);
			},
			function(error) {
				//TODO : Handle error
				aDeferred.reject(error);
			}
		);
		return aDeferred.promise();
	},

	save : function(data) {
		var aDeferred = jQuery.Deferred();

		var contentContainer = this.getContentContainer();
		contentContainer.progressIndicator();
		if(typeof data == 'undefined') {
			data = {};
		}

		AppConnector.request(data).then(
			function(data){
				contentContainer.progressIndicator({'mode' : 'hide'});
				aDeferred.resolve(data);
			},
			function(error, errorThrown){
				contentContainer.progressIndicator({'mode' : 'hide'});
				aDeferred.reject(error);
			}
		)

		return aDeferred.promise();
	},

	saveCustomRule : function(form) {
		var data = form.serializeFormData();

		if(typeof data == 'undefined' ) {
			data = {};
		}

		data.module = app.getModuleName();
		data.parent = app.getParentModuleName();
		data.action = 'IndexAjax';
		data.mode = 'saveRule';

		AppConnector.request(data).then(
			function(data) {

			},
			function(error) {
				//TODO : Handle error
			}
		);
	},

	editCustomRule : function(url) {
		var thisInstance = this;
		app.showModalWindow(null, url, function(modalContainer){
			jQuery('#editCustomRule').on('submit', function(e) {
				//To stop the submit of form
				e.preventDefault();
				var formElement = jQuery(e.currentTarget);
				thisInstance.saveCustomRule(formElement);
			})
		});
	},

	registerEvents : function() {
		var thisInstance = this;
		var contentTable = this.getContentTable();
		var contentContainer = this.getContentContainer();

		contentTable.on('click', 'td.triggerCustomSharingAccess', function(e){
			var element = jQuery(e.currentTarget);
			var trElement = element.closest('tr');
			var moduleName = trElement.data('moduleName');
			var customRuleListContainer = jQuery('.'+thisInstance.getCustomRuleContainerClassName(moduleName),contentTable);

			if(customRuleListContainer.length > 0) {
				if(app.isHidden(customRuleListContainer)) {
					customRuleListContainer.show();
					trElement.addClass('collapseRow');
					element.find('a').removeClass('icon-chevron-down').addClass('icon-chevron-up');
				}else{
					customRuleListContainer.hide();
					element.find('a').removeClass('icon-chevron-up').addClass('icon-chevron-down');
					trElement.removeClass('collapseRow');
				}
				return;
			}
			thisInstance.getCustomRules(moduleName).then(
					function(data){
						thisInstance.showCustomRulesNextToElement(trElement, data);
						element.find('a').removeClass('icon-chevron-down').addClass('icon-chevron-up');
					},
					function(error){
						//TODO: Handle Error
					}
			);
		});

		contentTable.on('click', 'button.addCustomRule' , function(e) {
			var button = jQuery(e.currentTarget);
			thisInstance.editCustomRule(button.data('url'));
		})

		contentTable.on('click', 'span.edit', function(e){
			var editElement = jQuery(e.currentTarget);
			var editUrl = editElement.data('url');
			thisInstance.editCustomRule(editUrl);
		});

		contentContainer.on('submit', '#EditSharingAccess', function(e){
			e.preventDefault();
			var form = jQuery(e.currentTarget);
			var data = form.serializeFormData();
			thisInstance.save(data);
		});
	}
});


jQuery(document).ready(function(){
	var settingSharingAcessInstance = new Settings_Sharing_Access_Js();
	settingSharingAcessInstance.registerEvents();
})
