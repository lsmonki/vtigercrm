/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
Vtiger_AdvanceFilter_Js('Workflows_AdvanceFilter_Js',{},{
	
	//Hols field type for which there is validations always needed
	allConditionValidationNeededFieldList : ['double', 'integer'],
	
	// comparators which do not have any field Specific UI.
	comparatorsWithNoValueBoxMap : ['has changed','is empty','is not empty'],
	
	getFieldSpecificType : function(fieldSelected) {
		var fieldInfo = fieldSelected.data('fieldinfo');
		var type = fieldInfo.type;
		return type;
	},
	
	getModuleName : function() {
		return app.getModuleName();
	},
	
	
	/**
	 * Function to add new condition row
	 * @params : condtionGroupElement - group where condtion need to be added
	 * @return : current instance
	 */
	addNewCondition : function(conditionGroupElement){
		var basicElement = jQuery('.basic',conditionGroupElement);
		var newRowElement = basicElement.find('.conditionRow').clone(true,true);
		jQuery('select',newRowElement).addClass('chzn-select');
		var conditionList = jQuery('.conditionList', conditionGroupElement);	
		conditionList.append(newRowElement);
		
		//change in to chosen elements
		app.changeSelectElementView(newRowElement);
		newRowElement.find('[name="columnname"]').find('optgroup:first option:first').attr('selected','selected').trigger('liszt:updated').trigger('change');
		return this;
	},
	
	/**
	 * Function to retrieve the values of the filter
	 * @return : object
	 */
	getValues : function() {
		var thisInstance = this;
		var filterContainer = this.getFilterContainer();

		var fieldList = new Array('columnname', 'comparator', 'value', 'valuetype', 'column_condition');

		var values = {};
		var columnIndex = 0;
		var conditionGroups = jQuery('.conditionGroup', filterContainer);
		conditionGroups.each(function(index,domElement){
			var groupElement = jQuery(domElement);
			
			var conditions = jQuery('.conditionList .conditionRow',groupElement);
            if(conditions.length <=0) {
                return true;
            }
            values[index+1] = {};
			values[index+1]['columns'] = {};
			conditions.each(function(i, conditionDomElement){
				var rowElement = jQuery(conditionDomElement);
				var fieldSelectElement = jQuery('[name="columnname"]', rowElement);
				var valueSelectElement = jQuery('[data-value="value"]',rowElement);
				//To not send empty fields to server
				if(thisInstance.isEmptyFieldSelected(fieldSelectElement)) {
					return true;
				}
				var fieldDataInfo = fieldSelectElement.find('option:selected').data('fieldinfo');
				var fieldType = fieldDataInfo.type;
				var rowValues = {};
				if(fieldType == 'owner'){
					for(var key in fieldList) {
						var field = fieldList[key];
						if(field == 'value' && valueSelectElement.is('select')){
							rowValues[field] = valueSelectElement.find('option:selected').text();
						} else {
							rowValues[field] = jQuery('[name="'+field+'"]', rowElement).val();
						}
					}
				} else if (fieldType == 'picklist' || fieldType == 'multipicklist') {
					for(var key in fieldList) {
						var field = fieldList[key];
						if(field == 'value' && valueSelectElement.is('input')) {
							var commaSeperatedValues = valueSelectElement.val();
							var pickListValues = valueSelectElement.data('picklistvalues');
							var valuesArr = commaSeperatedValues.split(',');
							var newvaluesArr = [];
							for(i=0;i<valuesArr.length;i++){
								if(typeof pickListValues[valuesArr[i]] != 'undefined'){
									newvaluesArr.push(pickListValues[valuesArr[i]]);
								} else {
									newvaluesArr.push(valuesArr[i]);
								}
							}
							var reconstructedCommaSeperatedValues = newvaluesArr.join(',');
							rowValues[field] = reconstructedCommaSeperatedValues;
						} else if(field == 'value' && valueSelectElement.is('select') && fieldType == 'picklist'){
							rowValues[field] = valueSelectElement.val();
						} else if(field == 'value' && valueSelectElement.is('select') && fieldType == 'multipicklist'){
							var value = valueSelectElement.val();
							if(value == null){
								rowValues[field] = value;
							} else {
								rowValues[field] = value.join(',');
							}
						} else {
							rowValues[field] = jQuery('[name="'+field+'"]', rowElement).val();
						}
					}

				} else {
					for(var key in fieldList) {
						var field = fieldList[key];
						if(field == 'value'){
							rowValues[field] = valueSelectElement.val();
						}  else {
							rowValues[field] = jQuery('[name="'+field+'"]', rowElement).val();
						}
					}
				}
				
				if(jQuery('[name="valuetype"]', rowElement).val() == 'false' || (jQuery('[name="valuetype"]', rowElement).length == 0)) {
					rowValues['valuetype'] = 'rawtext';
				}
				
				if(index == '0') {
					rowValues['groupid'] = '0';
				} else {
					rowValues['groupid'] = '1';
				}

				if(rowElement.is(":last-child")) {
					rowValues['column_condition'] = '';
				}
				values[index+1]['columns'][columnIndex] = rowValues;
				columnIndex++;
			});
			if(groupElement.find('div.groupCondition').length > 0) {
				values[index+1]['condition'] = conditionGroups.find('div.groupCondition [name="condition"]').val();
			}
		});
		return values;

	},
	
	/**
	 * Functiont to get the field specific ui for the selected field
	 * @prarms : fieldSelectElement - select element which will represents field list
	 * @return : jquery object which represents the ui for the field
	 */
	getFieldSpecificUi : function(fieldSelectElement) {
		var fieldSelected = fieldSelectElement.find('option:selected');
		var fieldInfo = fieldSelected.data('fieldinfo');
		if(jQuery.inArray(fieldInfo.comparatorElementVal,this.comparatorsWithNoValueBoxMap) != -1){
			return jQuery('');
		} else {
			return this._super(fieldSelectElement);
		}
	}
});

Vtiger_Field_Js('Workflows_Field_Js',{},{

	getUiTypeSpecificHtml : function() {
		var uiTypeModel = this.getUiTypeModel();
		return uiTypeModel.getUi();
	},
	
	getModuleName : function() {
		var currentModule = app.getModuleName();
		return currentModule;
	},
	
	/**
	 * Funtion to get the ui for the field  - generally this will be extend by the child classes to
	 * give ui type specific ui
	 * return <String or Jquery> it can return either plain html or jquery object
	 */
	getUi : function() {
		var html = '<input type="text" class="getPopupUi" name="'+ this.getName() +'"  /><input type="hidden" name="valuetype" value="'+this.get('workflow_valuetype')+'" />';
		html = jQuery(html);
		html.filter('.getPopupUi').val(app.htmlDecode(this.getValue()));
		return this.addValidationToElement(html);
	}
});

Vtiger_Date_Field_Js('Workflows_Date_Field_Js',{},{

	/**
	 * Function to get the user date format
	 */
	getDateFormat : function(){
		return this.get('date-format');
	},

	/**
	 * Function to get the ui
	 * @return - input text field
	 */
	getUi : function() {
		var html = '<input type="text" class="getPopupUi date" name="'+ this.getName() +'"  data-date-format="'+ this.getDateFormat() +'"  value="'+  this.getValue() + '" /><input type="hidden" name="valuetype" value="'+this.get('workflow_valuetype')+'" />'
		var element = jQuery(html);
		return this.addValidationToElement(element);
	}
});

Vtiger_Date_Field_Js('Workflows_Datetime_Field_Js',{},{
	/**
	 * Function to get the user date format
	 */
	getDateFormat : function(){
		return this.get('date-format');
	},

	/**
	 * Function to get the ui
	 * @return - input text field
	 */
	getUi : function() {
		var html = '<input type="text" class="getPopupUi date" name="'+ this.getName() +'"  data-date-format="'+ this.getDateFormat() +'"  value="'+  this.getValue() + '" /><input type="hidden" name="valuetype" value="'+this.get('workflow_valuetype')+'" />'
		var element = jQuery(html);
		return this.addValidationToElement(element);
	}
});

Vtiger_Currency_Field_Js('Workflows_Currency_Field_Js',{},{

	/**
	 * get the currency symbol configured for the user
	 */
	getCurrencySymbol : function() {
		return this.get('currency_symbol');
	},

	getUi : function() {
		var html = '<span class="input-prepend row-fluid">'+
									'<span class="add-on">'+ this.getCurrencySymbol()+'</span>'+
									'<input type="text" class="span9 getPopupUi marginLeftZero" name="'+ this.getName() +'" value="'+  this.getValue() + '"  />'+
					'</span><input type="hidden" name="valuetype" value="'+this.get('workflow_valuetype')+'" />';
		return html;
	},

	/**
	 * Function to add the validation for the element
	 */
	addValidationToElement : function(element) {
		return element.find('[name="'+ this.getName() +'"]').data('fieldinfo',JSON.stringify(this.getData()));
	}
});
