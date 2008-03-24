/*********************************************************************************

** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
function change(obj,divid)
{
	var select_options  =  document.getElementById('allselectedboxes').value;
	//Added to remove the semi colen ';' at the end of the string.done to avoid error.
        var x = select_options.split(";");
        var count=x.length;
        var viewid =getviewId();
        idstring = "";

        if (count > 1)
        {
                idstring=select_options;
                document.getElementById('idlist').value=idstring;
        }
        else
        {
                alert(alert_arr.SELECT);
                return false;
        }
        fnvshobj(obj,divid);
}
function getviewId()
{
	if(typeof(document.getElementById("viewname")) != 'undefined')
	{
		var oViewname = document.getElementById("viewname");
		var viewid = oViewname.options[oViewname.selectedIndex].value;
	}
	else
	{
		var viewid ='';		
	}
	return viewid;	
}
var gstart='';
function massDelete(module)
{
        var select_options  =  document.getElementById('allselectedboxes').value;
        var x = select_options.split(";");
	var searchurl= document.getElementById('search_url').value;
        var count=x.length
        var viewid =getviewId();
        var idstring = "";

        if (count > 1)
        {
            //document.getElementById('idlist').value=idstring;
            document.getElementById('idlist').value=select_options;
            idstring = select_options;
        }
        else
        {
            alert(alert_arr.SELECT);
            return false;
        }
        //we have to decrese the count value by 1 because when we split with semicolon we will get one extra count
        count = count - 1;

        var alert_str = alert_arr.DELETE + count +alert_arr.RECORDS;

        if(module=="Accounts")
                alert_str = alert_arr.DELETE_ACCOUNT +count+alert_arr.RECORDS;
        else if(module=="Vendors")
                alert_str = alert_arr.DELETE_VENDOR+count+alert_arr.RECORDS;

	if(confirm(alert_str))
	{
		$("status").style.display="inline";
		new Ajax.Request(
          	      'index.php',
		      	{queue: {position: 'end', scope: 'command'},
	                        method: 'post',
               		        postBody:"module=Users&action=massdelete&return_module="+module+"&"+gstart+"&viewname="+viewid+"&idlist="+idstring+searchurl,
	                        onComplete: function(response) {
                	                $("status").style.display="none";
               	        	        result = response.responseText.split('&#&#&#');
                       	        	$("ListViewContents").innerHTML= result[2];
	                       	        if(result[1] != '')
                                       		alert(result[1]);
						$('basicsearchcolumns').innerHTML = '';
	                        }
       			 }
		);
	}
	else
	{
		return false;
	}
}

function showDefaultCustomView(selectView,module,parenttab)
{
	$("status").style.display="inline";
	var viewName = selectView.options[selectView.options.selectedIndex].value;
	new Ajax.Request(
               	'index.php',
                {queue: {position: 'end', scope: 'command'},
                       	method: 'post',
                        postBody:"module="+module+"&action="+module+"Ajax&file=ListView&ajax=true&start=1&viewname="+viewName+"&parenttab="+parenttab,
                        onComplete: function(response) {
                        $("status").style.display="none";
                        result = response.responseText.split('&#&#&#');
                        $("ListViewContents").innerHTML= result[2];
                        if(result[1] != '')
                               	alert(result[1]);
			$('basicsearchcolumns_real').innerHTML = $('basicsearchcolumns').innerHTML
			$('basicsearchcolumns').innerHTML = '';
			document.basicSearch.search_text.value = '';
                        }
                }
	);
}


function getListViewEntries_js(module,url)
{
        var select_options  =  document.getElementsByName('selected_id');
        var x = select_options.length;
        var viewid =getviewId();
        idstring = "";

        xx = 0;
        for(i = 0; i < x ; i++)
        {
                if(select_options[i].checked)
                {
                        idstring = select_options[i].value +";"+idstring
                        xx++
                }
        }
        var all_selected=document.getElementById('allselectedboxes').value;

        $("status").style.display="inline";
        if($('search_url').value!='')
                urlstring = $('search_url').value;
        else
                urlstring = '';

	gstart = url;
        new Ajax.Request(
        	'index.php',
                {queue: {position: 'end', scope: 'command'},
                	method: 'post',
                        postBody:"module="+module+"&action="+module+"Ajax&file=ListView&ajax=true&allselobjs="+all_selected+"&selobjs="+idstring+"&"+url+urlstring,
			onComplete: function(response) {
                        	$("status").style.display="none";
                                result = response.responseText.split('&#&#&#');
                                $("ListViewContents").innerHTML= result[2];
				update_selected_checkbox();
                                if(result[1] != '')
                                        alert(result[1]);
				$('basicsearchcolumns').innerHTML = '';
                  	}
                }
        );
}
//for multiselect check box in list view:

function check_object(sel_id)
{
        var select_global=new Array();
        var selected=trim(document.getElementById("allselectedboxes").value);
        select_global=selected.split(";");
        var box_value=sel_id.checked;
        var id= sel_id.value;
        var duplicate=select_global.indexOf(id);
        var size=select_global.length-1;
        var result="";
        //alert("size: "+size);
        //alert("Box_value: "+box_value);
        //alert("Duplicate: "+duplicate);
        if(box_value == true)
        {
                if(duplicate == "-1")
                {
                        select_global[size]=id;
                }

                size=select_global.length-1;
                var i=0;
                for(i=0;i<=size;i++)
                {
                        if(trim(select_global[i])!='')
                                result=select_global[i]+";"+result;
                }
                default_togglestate();
        }
        else
        {
                if(duplicate != "-1")
                        select_global.splice(duplicate,1)

                size=select_global.length-1;
                var i=0;
                for(i=size;i>=0;i--)
                {
                        if(trim(select_global[i])!='')
                                result=select_global[i]+";"+result;
                }
          //      getObj("selectall").checked=false
                default_togglestate();
        }

        document.getElementById("allselectedboxes").value=result;
        //alert("Result: "+result);
}

function update_selected_checkbox()
{
        var all=document.getElementById('current_page_boxes').value;
        var tocheck=document.getElementById('allselectedboxes').value;
        var allsplit=new Array();
        allsplit=all.split(";");

        var selsplit=new Array();
        selsplit=tocheck.split(";");

        var n=selsplit.length;
        for(var i=0;i<n;i++)
        {
                if(allsplit.indexOf(selsplit[i]) != "-1")
                        document.getElementById(selsplit[i]).checked='true';
        }

}
