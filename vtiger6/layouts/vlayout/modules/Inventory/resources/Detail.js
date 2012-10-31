/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

jQuery.Class("Inventory_Detail_Js",{
    
},{
    /**
    * Function which will regiter all events for this page 
    */
    registerEvents : function(){
        this.registerClickEvent();
    },
    
    /**
	 * Event handler which is invoked on click event happened on inventoryLineItemDetails
	 */
    registerClickEvent : function(){
        this.getDetails().on('click','.inventoryLineItemDetails',function(e){
            alert(jQuery(e.currentTarget).data("info"));
        });
    },
    
    /**
	 * This function will return the current page
	 */
    getDetails : function(){
        return jQuery('.details');
    }

});

//On Page Load
jQuery(document).ready(function(){
    var instance  = new Inventory_Detail_Js();
    instance.registerEvents();
});


