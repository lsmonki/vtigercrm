
/*********************************************************************************

** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

function set_values(form) {

	if (form.duedate_flag.checked) {

		form.duedate_flag.value='on';

		form.duedate.value="";

		form.duetime.value="";

		form.duedate.readOnly=true;

		form.duetime.readOnly=true;

		document.images.jscal_trigger.width = 0;

		document.images.jscal_trigger.height = 0;

	}

	else {

		form.duedate_flag.value='off';

		form.duedate.readOnly=false;

		form.duetime.readOnly=false;

		if (form.duetime.readonly) alert ("it's readonly");

		document.images.jscal_trigger.width = 16;

		document.images.jscal_trigger.height = 16;

	}

}
function toggleTime()
{
	if(getObj("notime").checked)
	{
		getObj("notime").value = 'on';
		getObj("duration_hours").disabled = true;
		getObj("duration_minutes").disabled = true;
	}
	else
	{
		getObj("notime").value = 'off';
        getObj("duration_minutes").disabled = false;
		getObj("duration_hours").disabled = false;
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

		show("status");
		var ajaxObj = new Ajax(ajaxSaveResponse);
		var viewName = selectView.options[selectView.options.selectedIndex].value;
		var urlstring ="module=Activities&action=ActivitiesAjax&file=ListView&ajax=true&viewname="+viewName;
	    ajaxObj.process("index.php?",urlstring);
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
                        //alert(document.massdelete.idlist.value);
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
                        //alert(document.massdelete.idlist.value);
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
			var urlstring ="module=Users&action=massdelete&return_module=Activities&viewname="+viewid+"&idlist="+idstring;
	    	ajaxObj.process("index.php?",urlstring);
		}
		else
		{
			return false;
		}
}

function showActivityView(selectactivity_view)
{
	//script to reload the page with the view type when the combo values are changed
	View_name = selectactivity_view.options[selectactivity_view.options.selectedIndex].value;
	document.frmOpenLstView.action = "index.php?module=Home&action=index&activity_view="+View_name;
	document.frmOpenLstView.submit();
}	


