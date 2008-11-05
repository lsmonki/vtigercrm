/*********************************************************************************

** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

// MassEdit Feature
function mass_edit(obj,divid,module) {
	var select_options = document.getElementById('allselectedboxes').value;
	var x = select_options.split(';');
	var count = x.length;
	
	if(count > 1) {
		idstring=select_options;
		mass_edit_formload(idstring,module);
	} else {
		alert(alert_arr.SELECT);
		return false;
	}
	fnvshobj(obj, divid);
}
function mass_edit_formload(idstring,module) {
	$("status").style.display="inline";
	new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
	    	method: 'post',
			postBody:"module="+encodeURIComponent(module)+"&action="+encodeURIComponent(module+'Ajax')+"&file=MassEdit&mode=ajax&idstring="+idstring,
				onComplete: function(response) {
                	$("status").style.display="none";
               	    var result = response.responseText;
                    $("massedit_form_div").innerHTML= result;
					$("massedit_form")["massedit_recordids"].value = idstring;
					$("massedit_form")["massedit_module"].value = module;
				}
		}
	);
}
function mass_edit_fieldchange(selectBox) {
	var oldSelectedIndex = selectBox.oldSelectedIndex;
	var selectedIndex = selectBox.selectedIndex;

	if($('massedit_field'+oldSelectedIndex)) $('massedit_field'+oldSelectedIndex).style.display='none';
	if($('massedit_field'+selectedIndex)) $('massedit_field'+selectedIndex).style.display='block';

	selectBox.oldSelectedIndex = selectedIndex;
}

function mass_edit_save(){
	var masseditform = $("massedit_form");
	var module = masseditform["massedit_module"].value;
	var viewid = document.getElementById("viewname").options[document.getElementById("viewname").options.selectedIndex].value; 
	var searchurl = document.getElementById("search_url").value; 

	var urlstring = 
		"module="+encodeURIComponent(module)+"&action="+encodeURIComponent(module+'Ajax')+
		"&return_module="+encodeURIComponent(module)+"&return_action=ListView"+
		"&mode=ajax&file=MassEditSave&viewname=" + viewid ;//+"&"+ searchurl;

	fninvsh("massedit");

	new Ajax.Request(
		"index.php", 
		{queue:{position:"end", scope:"command"}, 
			method:"post", 
			postBody:urlstring, 
			onComplete:function (response) {
				$("status").style.display = "none";
				var result = response.responseText.split("&#&#&#");
				$("ListViewContents").innerHTML = result[2];
				if (result[1] != "") {
					alert(result[1]);
				}
				$("basicsearchcolumns").innerHTML = "";
			}
		}
	); 
	
}
function ajax_mass_edit() {
	alert();
	$("status").style.display = "inline";

	var masseditform = $("massedit_form");
	var module = masseditform["massedit_module"].value;

	var viewid = document.getElementById("viewname").options[document.getElementById("viewname").options.selectedIndex].value; 
	var idstring = masseditform["massedit_recordids"].value; 
	var searchurl = document.getElementById("search_url").value; 
	var tplstart = "&"; 
	if (gstart != "") { tplstart = tplstart + gstart; }

	var masseditfield = masseditform['massedit_field'].value;
	var masseditvalue = masseditform['massedit_value_'+masseditfield].value;

	var urlstring = 
		"module="+encodeURIComponent(module)+"&action="+encodeURIComponent(module+'Ajax')+
		"&return_module="+encodeURIComponent(module)+
		"&mode=ajax&file=MassEditSave&viewname=" + viewid + 
		"&massedit_field=" + encodeURIComponent(masseditfield) +
		"&massedit_value=" + encodeURIComponent(masseditvalue) +
	   	"&idlist=" + idstring + searchurl;

	fninvsh("massedit");

	new Ajax.Request(
		"index.php", 
		{queue:{position:"end", scope:"command"}, 
			method:"post", 
			postBody:urlstring, 
			onComplete:function (response) {
				$("status").style.display = "none";
				var result = response.responseText.split("&#&#&#");
				$("ListViewContents").innerHTML = result[2];
				if (result[1] != "") {
					alert(result[1]);
				}
				$("basicsearchcolumns").innerHTML = "";
			}
		}
	); 
}
	
// END

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
	var viewName = encodeURIComponent(selectView.options[selectView.options.selectedIndex].value);
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

//Function to Set the status as Approve/Deny for Public access by Admin
function ChangeCustomViewStatus(viewid,now_status,changed_status,module,label)
{
	$('status').style.display = 'block';
	new Ajax.Request(
       		'index.php',
               	{queue: {position: 'end', scope: 'command'},
               		method: 'post',
                    postBody:'module=CustomView&action=CustomViewAjax&file=ChangeStatus&dmodule='+module+'&record='+viewid+'&status='+changed_status,
					onComplete: function(response) 
					{
			        	var responseVal=response.responseText;
						if(responseVal.indexOf(':#:FAILURE') > -1) {
							alert('Failed');
						} else if(responseVal.indexOf(':#:SUCCESS') > -1) {
							var values = responseVal.split(':#:');
							var module_name = values[2];
							var customview_ele = $('viewname');
							showDefaultCustomView(customview_ele, module_name);
						} else {
							$('ListViewContents').innerHTML = responseVal;
						}
						$('status').style.display = 'none';
					} 
				}
	);
}
