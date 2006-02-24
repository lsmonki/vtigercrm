/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/

function set_focus()
{
}
function cancelForm(frm)
{
	window.history.back();
}
	
function trim(s) 
{                                                                                                                     
	while (s.substring(0,1) == " ") 
	{
		s = s.substring(1, s.length);
	}
	return s;
} 

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
		alert("Missing required fields: " + errorMessage);
		return false;
	}
	return true;
}

function showDefaultCustomView(selectView)
{
	viewName = selectView.options[selectView.options.selectedIndex].value;
	document.massdelete.viewname.value=viewName;
	document.massdelete.action="index.php?module=Vendors&action=index&viewname="+viewName;
	document.massdelete.submit();
}
function massDelete()
{

	x = document.massdelete.selected_id.length;
	var viewid = document.massdelete.viewname.value;
	idstring = "";

	if ( x == undefined)
	{
	
		if (document.massdelete.selected_id.checked)
		{
			document.massdelete.idlist.value=document.massdelete.selected_id.value;
		}
		else 
		{
			alert("Please select atleast one entity");
			return false;
		}
	}
	else
	{
		xx = 0;
		for(i = 0; i < x ; i++)
		{
			if(document.massdelete.selected_id[i].checked)
			{
				idstring = document.massdelete.selected_id[i].value +";"+idstring
			xx++	
			}
		}
		if (xx != 0)
		{
			document.massdelete.idlist.value=idstring;
		}
		else
		{
			alert("Please select atleast one entity");
			return false;
		}
	}
	if(confirm("Are you sure you want to delete the selected "+xx+" records ?"))
    {
	document.massdelete.action="index.php?module=Users&action=massdelete&return_module=Vendors&return_action=index&viewname="+viewid;
	}
	else
	{
		return false;
	}

}

function clear_form(form) 
{
	for (j = 0; j < form.elements.length; j++) 
	{
		if (form.elements[j].type == 'text' || form.elements[j].type == 'select-one') 
		{
			form.elements[j].value = '';
		}
	}
}

function set_return_specific(vendor_id, vendor_name) 
{
        //getOpenerObj used for DetailView 
        var fldName = getOpenerObj("vendor_name");
        var fldId = getOpenerObj("vendor_id");
        fldName.value = vendor_name;
        fldId.value = vendor_id;
	//window.opener.document.EditView.vendor_name.value = vendor_name;
        //window.opener.document.EditView.vendor_id.value = vendor_id;
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

