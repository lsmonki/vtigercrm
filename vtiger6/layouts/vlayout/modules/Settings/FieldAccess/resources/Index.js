/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

jQuery.Class('Settings_FieldAccess_Index_Js',{},{

	container : false,
	contentsContainer : false,

	/**
	 * Construtor
	 */
	init : function() {
		this.setContainer(jQuery('#fieldAccessContainer'));
		var pageContainer = this.getContainer();
		this.setContentsContainer(jQuery('.contents',pageContainer));
	},

	/**
	 * Function used to set the container
	 * @params element - jquery element which will be set as container
	 * @returns current instance
	 */
	setContainer : function(element) {
		this.container = element;
		return this;
	},

	/**
	 * Function used to get the container
	 * #returns container
	 */
	getContainer : function() {
		return this.container;
	},

	/**
	 * Function used to set the contents container
	 * @params element - jquery element which will be set as contents container
	 * @return current instance
	 */
	setContentsContainer : function (element) {
		this.contentsContainer = element;
		return this;
	},

	/**
	 * Function used to get the contents cotnainer
	 * @return contents container
	 */
	getContentsContainer : function (){
		return this.contentsContainer;
	},

	/**
	 * Function to get the base url which can be used to load current selected module
	 * return url
	 */
	getBaseUrl : function() {
		return jQuery('#loadUrl').data('url');
	},

	/**
	 * Function to get the form contents
	 * return jquery form element
	 */
	getContentsForm : function () {
		return jQuery('form.fieldAccessContents', this.getContentsContainer());
	},

	/**
	 * Function to get module list select element
	 * @return jquery module list select element
	 */
	getModuleSelectElement : function() {
		return jQuery('#modulesList');
	},

	/**
	 * Function to load the contents with the specified url
	 * @params url - optional parameter if is not sent it will take the base url and load currently selected module
	 * @return deferred promise
	 */
	load : function(url) {
		var aDeferred = jQuery.Deferred();
		var contentsContainer = this.getContentsContainer();
		jQuery.progressIndicator({
			'blockInfo' : {
					'enabled' : true,
					'elementToBlock' : contentsContainer
				}
			});
		if(typeof url == 'undefined') {
			url = this.getBaseUrl();
		}
		AppConnector.requestPjax(url).then(
			function(data){
				jQuery.progressIndicator({'mode':'hide'});
				aDeferred.resolve(data);
			},
			function(error,errorStatus){
				jQuery.progressIndicator({'mode':'hide'});
				aDeferred.reject(error);
			}
		);
		return aDeferred.promise();
	},

	/*
	 * Function which shows edit view of the module
	 * @return current instance
	 */
	showEditView : function() {
		var contentsContainer = this.getContentsContainer();
		jQuery('.fieldContainer', contentsContainer).each(function(index,fieldContainer){
			jQuery('.edit',fieldContainer).show();
			jQuery('.detail',fieldContainer).hide();
		});
		jQuery('button.edit',contentsContainer).hide();
		jQuery('.formActions',contentsContainer).show();
		return this;
	},

	/**
	 * Function to save the module details
	 * @params url - url which need to hit to save
	 * @return deferred promise
	 */
	save : function (url) {
		var aDeferred = jQuery.Deferred();
		var form = this.getContentsForm();
		var data = form.serializeFormData();
		
		var params = {}
		params.url = url;
		params.data = data;

		jQuery.progressIndicator({
			'blockInfo' : {
					'enabled' : true,
					'elementToBlock' : this.getContentsContainer()
				}
			});
			
		AppConnector.request(params).then(
			function(data) {
				aDeferred.resolve();
				jQuery.progressIndicator({'mode':'hide'});
			},
			function(error) {
				aDeferred.reject();
				jQuery.progressIndicator({'mode':'hide'});
			}
		);
		return aDeferred.promise();
	},

	registerEvents : function() {
		var container = this.getContainer();
		var thisInstance = this;

		container.on('change','#modulesList', function(e){
			var element = jQuery(e.currentTarget);
			var selectedModuleName = element.val();
			var url = element.find('option:selected').data('url');
			var contentsContainer  = thisInstance.getContentsContainer();
			thisInstance.load(url).then(
				function(data){
					contentsContainer.html(data);
					app.changeSelectElementView(contentsContainer);
				},
				function(error){
					
				}
			);
		});

		container.on('click', 'button.edit', function(e){
			thisInstance.showEditView();
		})

		container.on('click', 'button.save',function(e){
			var element = jQuery(e.currentTarget);
			var url = element.data('url');
			thisInstance.save(url).then(
				function(data) {
					var selectElement = thisInstance.getModuleSelectElement();
					selectElement.trigger('change');
				},
				function(error) {
					
				}
			);
		})

		container.on('click','a.cancel',function(e){
			var selectElement = thisInstance.getModuleSelectElement();
			selectElement.trigger('change');
		})
	}
})

jQuery(document).ready(function(e){
	var instance = new Settings_FieldAccess_Index_Js();
	instance.registerEvents();
})
