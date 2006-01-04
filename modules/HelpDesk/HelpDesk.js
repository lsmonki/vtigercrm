/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/

function verify_data(form) {
	if(! form.createpotential.checked == true)
	{
        	if (form.potential_name.value == "")
		{
                	alert("Opportunity Name field cannot be empty");
			return false;	
		}
		if (form.closedate.value == "")
		{
                	alert("Close Date field cannot be empty");
			return false;	
		}
		return dateValidate('closedate','Potential Close Date','GECD');
			
		
        }
        return true;
}

function togglePotFields(form)
{
	if (form.createpotential.checked == true)
	{
		form.potential_name.disabled = true;
		form.closedate.disabled = true;
		
	}
	else
	{
		form.potential_name.disabled = false;
		form.closedate.disabled = false;
	}	

}

function toggleAssignType(currType)
{
        if (currType=="U")
        {
                getObj("assign_user").style.display="block"
                getObj("assign_team").style.display="none"
        }
        else
        {
                getObj("assign_user").style.display="none"
                getObj("assign_team").style.display="block"
        }
}



function showDefaultCustomView(selectView)
{
	viewName = selectView.options[selectView.options.selectedIndex].value;
	document.massdelete.viewname.value=viewName;
	document.massdelete.action="index.php?module=HelpDesk&action=index&return_module=HelpDesk&return_action=ListView&viewname="+viewName;
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
	document.massdelete.action="index.php?module=Users&action=massdelete&return_module=HelpDesk&return_action=index&viewname="+viewid;
	}
	else
	{
		return false;
	}

}

//merge list of tickets with templates
function massMerge()
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
	if(getObj('selectall').checked == true)
	{
			getObj('idlist').value = getObj('allids').value;
	}
	document.massdelete.action="index.php?module=HelpDesk&action=Merge&return_module=HelpDesk&return_action=index&viewname="+viewid;
}
//end of mass merge

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

