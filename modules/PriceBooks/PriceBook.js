/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/

prod_array = new Array();
function addtopricebook()
{
	x = document.addToPB.selected_id.length;
	prod_array = new Array(x);
	idstring = "";

	if ( x == undefined)
	{
		if (document.addToPB.selected_id.checked)
		{
			yy = document.addToPB.selected_id.value+"_listprice";
			document.addToPB.idlist.value=document.addToPB.selected_id.value;
		
			var elem = document.addToPB.elements;
			var ele_len =elem.length;
			var i=0,j=0;
	
			for(i=0; i<ele_len; i++)
			{	
				if(elem[i].name == yy)
				{
					if (elem[i].value.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0) 
					{
						alert("List Price cannot be empty");
			               		return false;	
					}
					else if(isNaN(elem[i].value))
					{
						alert("Invalid List Price");
						return false;	
					}
	
				}
				
			}		
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
			if(document.addToPB.selected_id[i].checked)
			{
				idstring = document.addToPB.selected_id[i].value +";"+idstring;
				prod_array[xx] = document.addToPB.selected_id[i].value;

				xx++;	
			}
		}
		if (xx != 0)
		{
			document.addToPB.idlist.value=idstring;
			var elem = document.addToPB.elements;
			var ele_len =elem.length;
			var i=0,j=0;
			for(i=0; i<ele_len; i++)
			{	
				for(j=0; j < xx; j++)
				{		
					var xy= prod_array[j]+"_listprice";
					if(elem[i].name == xy)
					{
						if (elem[i].value.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0) 
						{
		
							alert("List Price cannot be empty");
			                		return false;	
						}
						else if(isNaN(elem[i].value))
						{
							alert("Invalid List Price");
			                		return false;	
							
						}
					}	
				}
							
			}
		}
		else
		{
			alert("Please select atleast one entity");
			return false;
		}
	}
document.addToPB.action="index.php?module=Products&action=addPbProductRelToDB&return_module=Products&return_action=AddProductsToPriceBook"
}

function showDefaultCustomView(selectView)
{
viewName = selectView.options[selectView.options.selectedIndex].value;
document.massdelete.viewname.value=viewName;
document.massdelete.action="index.php?module=PriceBooks&action=index&return_module=PriceBooks&return_action=index&viewname="+viewName;
document.massdelete.submit();
}

function updateListPrice(unitprice,fieldname)
{
	var elem=document.addToPB.elements;
	var i;	
	for(i=0; i<elem.length; i++)
	{
		if(elem[i].name== fieldname)
		{
			elem[i].value=unitprice;	
		}
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
document.massdelete.action="index.php?module=Users&action=massdelete&return_module=PriceBooks&return_action=index"
	}
	else
	{
		return false;
	}
}


function set_return_specific(vendor_id, vendor_name) 
{
        window.opener.document.EditView.vendor_name.value = vendor_name;
        window.opener.document.EditView.vendor_id.value = vendor_id;
}
function set_return_inventory_pb(listprice, fldname) 
{
        window.opener.document.EditView.elements[fldname].value = listprice;
	window.opener.document.EditView.elements[fldname].focus();
}


