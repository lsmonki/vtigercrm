/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/


function massDelete()
{
	x = document.massdelete.selected_id.length;
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
		show("status");
		var ajaxObj = new Ajax(ajaxSaveResponse);
		var urlstring ="module=Users&action=massdelete&return_module=Faq&idlist="+idstring;
	    ajaxObj.process("index.php?",urlstring);
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

