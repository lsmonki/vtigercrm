function showDefaultCustomView(selectView)
{
viewName = selectView.options[selectView.options.selectedIndex].value;
document.massdelete.viewname.value=viewName;
document.massdelete.action="index.php?module=Campaigns&action=index&return_module=Campaignsts&return_action=index&viewname="+viewName;
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
     	   document.massdelete.action="index.php?module=Users&action=massdelete&return_module=Campaigns&return_action=ListView&viewname="+viewid;
        }
        else
        {
           return false;
        }
}

