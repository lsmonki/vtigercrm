/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/



document.write("<script type='text/javascript' src='include/js/Mail.js'></"+"script>");
function set_return(user_id, user_name) {
		window.opener.document.EditView.reports_to_name.value = user_name;
		window.opener.document.EditView.reports_to_id.value = user_id;
}

//Function added for Mass select in Popup - Philip
function SelectAll()
{

        x = document.selectall.selected_id.length;
        var entity_id = window.opener.document.getElementById('parent_id').value
        var module = window.opener.document.getElementById('return_module').value
        document.selectall.action.value='updateRelations'
        idstring = "";

        if ( x == undefined)
        {

                if (document.selectall.selected_id.checked)
                {
                        document.selectall.idlist.value=document.selectall.selected_id.value;
                }
                else
                {
                        alert("Please select at least one entity");
                        return false;
                }
        }
        else
        {
                xx = 0;
                for(i = 0; i < x ; i++)
                {
                        if(document.selectall.selected_id[i].checked)
                        {
                                idstring = document.selectall.selected_id[i].value +";"+idstring
                        xx++
                        }
                }
                if (xx != 0)
                {
                        document.selectall.idlist.value=idstring;
                }
                else
                {
			alert("Please select at least one entity");
	                return false;
                }
        }
        if(confirm("Are you sure you want to add the selected "+xx+" records ?"))
        {
                opener.document.location.href="index.php?module="+module+"&parentid="+entity_id+"&action=updateRelations&destination_module=Users&idlist="+idstring;
                self.close();
        }
        else
        {
        	return false;
        }
}

