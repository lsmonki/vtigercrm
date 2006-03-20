function showDefaultCustomView(selectView)
{
		show("status");
		var ajaxObj = new Ajax(ajaxSaveResponse);
		var viewName = selectView.options[selectView.options.selectedIndex].value;
		var urlstring ="module=Campaigns&action=CampaignsAjax&file=ListView&ajax=true&viewname="+viewName;
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
			var urlstring ="module=Users&action=massdelete&return_module=Campaigns&viewname="+viewid+"&idlist="+idstring;
		    ajaxObj.process("index.php?",urlstring);
        }
        else
        {
           return false;
        }
}

