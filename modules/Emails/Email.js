/*********************************************************************************

** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

var gFolderid = 1;
var gselectedrowid = 0;
function massDelete()
{
		var delete_selected_row = false;
        x = document.massdelete.selected_id.length;
        idstring = "";
        if ( x == undefined)
        {

                if (document.massdelete.selected_id.checked)
                {
					if(document.massdelete.selected_id.value == gselectedrowid)
					{
						gselectedrowid = 0;
						delete_selected_row = true;						
					}
                        idstring = document.massdelete.selected_id.value;
						xx = 1;
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
							if(document.massdelete.selected_id[i].value == gselectedrowid)
							{
								gselectedrowid = 0;
								delete_selected_row = true;						
							}
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
			getObj('search_text').value = '';
			show("status");
			if(!delete_selected_row)
				var ajaxObj = new VtigerAjax(ajaxSaveResponse);
			else	
				var ajaxObj = new VtigerAjax(ajaxDelResponse);
			var urlstring ="module=Users&action=massdelete&folderid="+gFolderid+"&return_module=Emails&idlist="+idstring;
		    ajaxObj.process("index.php?",urlstring);
		}
		else
		{
			return false;
		}
}
function DeleteEmail(id)
{
	if(confirm("Are you sure you want to delete ?"))
	{	
		getObj('search_text').value = '';
		gselectedrowid = 0;
		show("status");
		var ajaxObj = new VtigerAjax(ajaxDelResponse);
		var urlstring ="module=Users&action=massdelete&return_module=Emails&folderid="+gFolderid+"&idlist="+id;
	   	ajaxObj.process("index.php?",urlstring);
	}
	else
	{
		return false;
	}
}
function Searchfn()
{
	gselectedrowid = 0;
	var osearch_field = document.getElementById('search_field');
	var search_field = osearch_field.options[osearch_field.options.selectedIndex].value;
	var search_text = document.getElementById('search_text').value;
	var ajaxObj = new VtigerAjax(ajaxDelResponse);
	var urlstring ="module=Emails&action=EmailsAjax&ajax=true&file=ListView&folderid="+gFolderid+"&search=true&search_field="+search_field+"&search_text="+search_text;
    ajaxObj.process("index.php?",urlstring);
}

