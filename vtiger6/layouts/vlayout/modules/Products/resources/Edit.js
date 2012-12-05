/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_Edit_Js("Products_Edit_Js",{
	
},{
	baseCurrency : '',
	
	baseCurrencyName : '',
	//Container which stores the multi currency element
	multiCurrencyContainer : false,
	
	//Container which stores unit price
	unitPrice : false,
	
	/**
	 * Function to get unit price
	 */
	getUnitPrice : function(){
		if(this.unitPrice == false) {
			this.unitPrice = jQuery('input.unitPrice',this.getForm());
		}
		return this.unitPrice;
	},
	
	/**
	 * Function to get more currencies container
	 */
	getMoreCurrenciesContainer : function(){
		if(this.multiCurrencyContainer == false) {
			this.multiCurrencyContainer = jQuery('.multiCurrencyEditUI');
		}
		return this.multiCurrencyContainer;
	},
	
	/**
	 * Function which aligns data just below global search element
	 */
	alignBelowUnitPrice : function(dataToAlign) {
		var parentElem = jQuery('input[name="unit_price"]',this.getForm());
		dataToAlign.position({
			'of' : parentElem,
			'my': "left top",
			'at': "left bottom",
			'collision' : 'flip'
		});
		return this;
	},
	
	/**
	 * Function to get current Element
	 */
	getCurrentElem : function(e){
		return jQuery(e.currentTarget);
	},
	/**
	 *Function to register events for taxes
	 */
	registerEventForTaxes : function(){
		var thisInstance = this;
		var formElem = this.getForm();
		jQuery('.taxes').on('change',function(e){
			var elem = thisInstance.getCurrentElem(e);
			var taxBox  = elem.data('taxName');
			if(elem.is(':checked')) {
				jQuery('input[name='+taxBox+']',formElem).removeClass('hide').show();
			}else{
				jQuery('input[name='+taxBox+']',formElem).addClass('hide');
			}

		});
		return this;
	},
	
	/**
	 * Function to register event for enabling base currency on radio button clicked
	 */
	registerEventForEnableBaseCurrency : function(){
		var container = this.getMoreCurrenciesContainer();
		var thisInstance = this;
		jQuery('.baseCurrency',container).on('change',function(e){
			var elem = thisInstance.getCurrentElem(e);
			var parentElem = elem.closest('tr');
			if(elem.is(':checked')) {
				var convertedPrice = jQuery('.convertedPrice',parentElem).val();
				thisInstance.baseCurrencyName = parentElem.data('currencyId');
				thisInstance.baseCurrency = convertedPrice;
			}
		});
		return this;
	},
	
	/**
	 * Function to register event for action button like save and cancel
	 */
	registerEventForActionButtons : function(){
		var container = this.getMoreCurrenciesContainer();
		var form = this.getForm();
		var thisInstance = this;
		var failure;
		jQuery('#saveCurrencies').on('click',function(e){
			var elem = thisInstance.getCurrentElem(e);
			/** invalidFields contains information about invalidated fields **/
			var invalidFields = form.data('jqv').InvalidFields;
			jQuery.each(jQuery('.convertedPrice',container),function(key,val){
				if(jQuery.inArray(val,invalidFields) != -1){
					failure = 1;
					return false;
				}else{
					failure = 0;
				}
			});
			if(failure == 1){
				container.show();
			}else{
				jQuery.each(jQuery('.baseCurrency',container),function(key,val){
					if(jQuery(val).is(':checked')) {
						var parentElem = jQuery(val).closest('tr');
						var convertedPrice = jQuery('.convertedPrice',parentElem).val();
						thisInstance.baseCurrencyName = parentElem.data('currencyId');
						thisInstance.baseCurrency = convertedPrice;
					}
				})
				thisInstance.getUnitPrice().val(thisInstance.baseCurrency);
				jQuery('input[name="base_currency"]',form).val(thisInstance.baseCurrencyName);
				container.addClass('hide');
			}
		});
		jQuery('.cancelLink',container).on('click',function(e){
			container.addClass('hide');
		});
		jQuery('.close',container).on('click',function(e){
			container.addClass('hide');
		});
		return this;
	},
	
	/**
	 * Function to register event for reseting the currencies
	 */
	registerEventForResetCurrency : function(){
		var container = this.getMoreCurrenciesContainer();
		var thisInstance = this;
		jQuery('.currencyReset',container).on('click',function(e){
			var parentElem = thisInstance.getCurrentElem(e).closest('tr');
			var	unitPrice = thisInstance.getDataBaseFormatUnitPrice();
			var conversionRate = jQuery('.conversionRate',parentElem).val();
			var price = parseFloat(unitPrice) * parseFloat(conversionRate);
			jQuery('.convertedPrice',parentElem).val(price.toFixed(2));
		});
		return this;
	},
	
	/**
	 *  Function to return stripped unit price
	 */
		getDataBaseFormatUnitPrice : function(){
			var field = this.getUnitPrice();
			var unitPrice = field.val();
			if(unitPrice == ''){
				unitPrice = 0;
			}else{
				var fieldData = field.data();
				var strippedValue = unitPrice.replace(fieldData.groupSeperator, '');
				var strippedValue = strippedValue.replace(fieldData.decimalSeperator, '.');
				unitPrice = strippedValue;
			}
			return unitPrice;
		},
	/**
	 * Function to register event for enabling currency on checkbox checked
	 */
	
	registerEventForEnableCurrency : function(){
		var container = this.getMoreCurrenciesContainer();
		var thisInstance = this;
		jQuery('.enableCurrency',container).on('change',function(e){
			var elem = thisInstance.getCurrentElem(e);
			var parentRow = elem.closest('tr');
			
			if(elem.is(':checked')) {
				var conversionRate = jQuery('.conversionRate',parentRow).val();
				var unitPrice = thisInstance.getDataBaseFormatUnitPrice();
				var price = parseFloat(unitPrice)*parseFloat(conversionRate);
				jQuery('input',parentRow).attr('disabled', true).removeAttr('disabled');
				jQuery('input.convertedPrice',parentRow).val(price)
			}else{
				jQuery('input',parentRow).attr('disabled', true);
				jQuery('input.enableCurrency',parentRow).removeAttr('disabled');
			}
		})
		return this;
	},
	
	/*
	 * function to register events for more currencies link
	 */
	registerEventForMoreCurrencies : function(){
		var thisInstance = this;
		var form = this.getForm();
		jQuery('#moreCurrencies').on('click',function(e){
			var moduleName = app.getModuleName();
			var parentElem = thisInstance.getCurrentElem(e).closest('div');
			var moreCurrenciesContainer = jQuery('#moreCurrenciesContainer');
			var recordId = jQuery('input[name="record"]').val();
			var moreCurrenciesDiv = thisInstance.getMoreCurrenciesContainer();
			if(moreCurrenciesDiv.length == 0){
				var moreCurrenciesParams = {
					'module' : moduleName,
					'view' : "MoreCurrenciesList",
					'record' : recordId
				}
				var progressInstance = jQuery.progressIndicator();
				AppConnector.request(moreCurrenciesParams).then(
					function(data){
						progressInstance.hide();
						thisInstance.baseCurrency = thisInstance.getUnitPrice().val();
						moreCurrenciesContainer.html(data);
						form.validationEngine('detach');
						form.validationEngine('attach');
						thisInstance.registerSubmitEvent();
						var multiCurrencyEditUI = jQuery('.multiCurrencyEditUI',moreCurrenciesContainer);
						thisInstance.multiCurrencyContainer = multiCurrencyEditUI;
						thisInstance.registerEventForEnableCurrency().registerEventForEnableBaseCurrency()
									.registerEventForResetCurrency().registerEventForActionButtons()
									.alignBelowUnitPrice(multiCurrencyEditUI).triggerForBaseCurrencyCalc();
						
					},
					function(error,err){
						//TODO : handle the error caseEdit.js
					}
				);
			}else{
				moreCurrenciesDiv.removeClass('hide');
			}
		});
	},
	/**
	 * Function to calculate base currency price value if unit
	 * present on click of more currencies
	 */
	triggerForBaseCurrencyCalc : function(){
		var multiCurrencyEditUI = this.getMoreCurrenciesContainer();
		var baseCurrency = multiCurrencyEditUI.find('.baseCurrency');
		jQuery.each(baseCurrency,function(key,val){
			if(jQuery(val).is(':checked')){
				var baseCurrencyRow = jQuery(val).closest('tr');
				baseCurrencyRow.find('.currencyReset').trigger('click');
			}
		})
	},
	
	/**
	 * Function to register onchange event for unit price
	 */
	registerEventForUnitPrice : function(){
		var thisInstance = this;
		var unitPrice = this.getUnitPrice();
		unitPrice.on('change',function(){
			thisInstance.triggerForBaseCurrencyCalc();
		})
	},

	registerRecordPreSaveEvent : function(form) {
		var thisInstance = this;
		if(typeof form == 'undefined') {
			form = this.getForm();
		}

		form.on(Vtiger_Edit_Js.recordPreSave, function(e, data) {
			var unitPrice = form.find('[name="unit_price"]').val();
			var baseCurrencyName = form.find('[name="base_currency"]').val();
			form.find('[name="'+ baseCurrencyName +'"]').val(unitPrice);
			form.find('#requstedUnitPrice').attr('name',baseCurrencyName).val(unitPrice);
		})
	},
	
	registerEvents : function(){
		this._super();
		this.registerEventForMoreCurrencies();
		this.registerEventForTaxes();
		this.registerEventForUnitPrice();
		this.registerRecordPreSaveEvent();
	}
})