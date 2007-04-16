/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/

function check4null(form)
{
	var isError = false;
	var errorMessage = "";
	if (trim(form.productname.value) =='') 
	{
		isError = true;
		errorMessage += "\n Product Name";
		form.productname.focus();
	}
	if (isError == true) 
	{
		alert(alert_arr.MISSING_REQUIRED_FIELDS + errorMessage);
		return false;
	}
	return true;
}


function set_return_specific(vendor_id, vendor_name) 
{
        //getOpenerObj used for DetailView 
        var fldName = getOpenerObj("vendor_name");
        var fldId = getOpenerObj("vendor_id");
        fldName.value = vendor_name;
        fldId.value = vendor_id;
}

function set_return_address(vendor_id, vendor_name, street, city, state, code, country,pobox ) 
{
        window.opener.document.EditView.vendor_name.value = vendor_name;
        window.opener.document.EditView.vendor_id.value = vendor_id;
        window.opener.document.EditView.bill_street.value = street;
        window.opener.document.EditView.ship_street.value = street;
        window.opener.document.EditView.bill_city.value = city;
        window.opener.document.EditView.ship_city.value = city;
        window.opener.document.EditView.bill_state.value = state;
        window.opener.document.EditView.ship_state.value = state;
        window.opener.document.EditView.bill_code.value = code;
        window.opener.document.EditView.ship_code.value = code;
        window.opener.document.EditView.bill_country.value = country;
        window.opener.document.EditView.ship_country.value = country;
        window.opener.document.EditView.bill_pobox.value = pobox;
        window.opener.document.EditView.ship_pobox.value = pobox;
}

