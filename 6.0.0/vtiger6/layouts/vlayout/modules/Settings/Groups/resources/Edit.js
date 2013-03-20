/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

jQuery.Class('Settings_Groups_Edit_Js', {}, {
	memberSelectElement : false,
	chosenMemberSelectElement : false,
	memberSelectId : '#memberList',

	init : function () {
		this.setMemberSelectElement(this.memberSelectId);
	},

	/**
	 * Function to get the member select element
	 * @return : jQuery object
	 */
	getMemberSelectElement : function () {
		if(this.memberSelectElement == false) {
			this.setMemberSelectElement(this.memberSelectId);
		}
		return this.memberSelectElement;
	},

	/**
	 * Function to set raw select element
	 * @params : element <object> - element which need to be set as select element
	 * @retusn : current instance
	 */
	setMemberSelectElement : function(element) {
		if(element instanceof jQuery) {
			this.memberSelectElement = element
		}
		this.memberSelectElement = jQuery(element);
		return this;
	},

	/**
	 * Function to get the chosen library modified select element
	 * @return : jquery object
	 */
	getChosenMemberSelect : function() {
		if(this.chosenMemberSelectElement == false) {
			this.setChosenMemberSelect(this.memberSelectId+"_chzn");
		}
		return this.chosenMemberSelectElement;
	},

	/**
	 * Function to set the chosen select element
	 * @params : element<object> - element which need to be set as chosen element
	 * @return : current instance
	 */
	setChosenMemberSelect : function (element) {
		if(element instanceof jQuery) {
			this.chosenMemberSelectElement = element
		}
		this.chosenMemberSelectElement = jQuery(element);
		return this;
	},

	/**
	 * Function which will give color for the option once it is selected in the chosen element
	 * @params : selectedChoiceElement - the element that will be created by chosen (with class search-choice) once you select
	 * @return : current instance
	 */
	setColorForSelectedOption : function (selectedChoiceElement){
		var id = selectedChoiceElement.attr('id');
		var chosenContainer = this.getChosenMemberSelect();
		var idFormat = chosenContainer.attr('id')+'_c_';
		var idSplit = id.split(idFormat);
		var optionIndex = idSplit[1];
		
		var rawSelectElement = this.getMemberSelectElement();
		var rawOption = jQuery(rawSelectElement.find('optgroup,option').get(optionIndex));
		var memberType = rawOption.data('memberType');
		
		selectedChoiceElement.attr('data-member-type', memberType);
		return this;
	},

	/**
	 * Function which will handle the registrations for the elements 
	 */
	registerEvents : function() {
		var thisInstance = this;
		
		var selectMemberElement = this.getMemberSelectElement();
		app.changeSelectElementView(selectMemberElement);
		var chosenElement = app.getChosenElementFromSelect(selectMemberElement);
		this.setChosenMemberSelect(chosenElement);
		
		//mouse up instead of click since library is using it
		chosenElement.on('mouseup', 'ul.chzn-results li.group-option', function(e){
			var optionChoice = jQuery(e.currentTarget);
			var optionChoiceId = optionChoice.attr('id');
			var chosenSelectElement = optionChoice.closest('.chzn-container');
			var chosenContainerId = chosenSelectElement.attr('id');
			var optionIdFormat = chosenContainerId+"_o_";
			var idSplit = optionChoiceId.split(optionIdFormat);
			var optionIndex = idSplit[1];

			var selectedChoiceElementId  = chosenContainerId + "_c_" + optionIndex;
			var selectedChoiceElement = jQuery('#'+ selectedChoiceElementId);

			thisInstance.setColorForSelectedOption(selectedChoiceElement);

		});

		jQuery('ul.chzn-choices li.search-choice',chosenElement).each(function(index,domElement){
			var choiceElement = jQuery(domElement);
			thisInstance.setColorForSelectedOption(choiceElement);
		});
	}
});

jQuery(document).ready(function(){
	var instance = new Settings_Groups_Edit_Js();
	instance.registerEvents();
})

