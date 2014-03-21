/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
Vtiger_Popup_Js("Vtiger_EmailsRelatedModule_Popup_Js",{},{
	
	getListViewEntries: function(e){
		var thisInstance = this;
                var selectFields=JSON.parse(jQuery('input[name="selectFields"]').val());  
		var row  = jQuery(e.currentTarget);
		var id = row.data('id');
		var recordName = row.data('name');
		var emailFields = JSON.parse(jQuery(row).attr('data-info')); 
		var emailValue = '';
		jQuery.each(selectFields,function(i,element){ 
                    emailValue = emailFields[selectFields[element]]; 
                    if(typeof(emailFields[selectFields[element]]) == "undefined"){  
                        var error = recordName+" "+app.vtranslate("JS_DO_NOT_HAVE_AN_EMAIL_ID");
                        alert(error);  
                        return;  
                    } 
                });
		var response ={};
		response[id] = {'name' : recordName,'email' : emailValue} ;
		thisInstance.done(response, thisInstance.getEventName());
		e.preventDefault();
	},
	
	registerEvents: function(){
		this._super();
	}
})