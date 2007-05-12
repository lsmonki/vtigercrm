/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/
var typeofdata = new Array();
typeofdata['V'] = ['e','n','s','c','k'];
typeofdata['N'] = ['e','n','l','g','m','h'];
typeofdata['T'] = ['e','n','l','g','m','h'];
typeofdata['I'] = ['e','n','l','g','m','h'];
typeofdata['C'] = ['e','n'];
typeofdata['D'] = ['e','n','l','g','m','h'];

var fLabels = new Array();
fLabels['e'] = alert_arr.EQUALS;
fLabels['n'] = alert_arr.NOT_EQUALS_TO;
fLabels['s'] = alert_arr.STARTS_WITH;
fLabels['c'] = alert_arr.CONTAINS;
fLabels['k'] = alert_arr.DOES_NOT_CONTAINS;
fLabels['l'] = alert_arr.LESS_THAN;
fLabels['g'] = alert_arr.GREATER_THAN;
fLabels['m'] = alert_arr.LESS_OR_EQUALS;
fLabels['h'] = alert_arr.GREATER_OR_EQUALS;
var noneLabel;
var gcurrepfolderid=0;
function trimfValues(value)
{
    var string_array;
    string_array = value.split(":");
    return string_array[4];
}

function updatefOptions(sel, opSelName) {
    var selObj = document.getElementById(opSelName);
    var fieldtype = null ;

    var currOption = selObj.options[selObj.selectedIndex];
    var currField = sel.options[sel.selectedIndex];

    if(currField.value != null && currField.value.length != 0)
    {
	fieldtype = trimfValues(currField.value);
	ops = typeofdata[fieldtype];
	var off = 0;
	if(ops != null)
	{

		var nMaxVal = selObj.length;
		for(nLoop = 0; nLoop < nMaxVal; nLoop++)
		{
			selObj.remove(0);
		}
		selObj.options[0] = new Option ('None', '');
		if (currField.value == '') {
			selObj.options[0].selected = true;
		}
		off = 1;
		for (var i = 0; i < ops.length; i++)
		{
			var label = fLabels[ops[i]];
			if (label == null) continue;
			var option = new Option (fLabels[ops[i]], ops[i]);
			selObj.options[i + off] = option;
			if (currOption != null && currOption.value == option.value)
			{
				option.selected = true;
			}
		}
	}
    }else
    {
	var nMaxVal = selObj.length;
	for(nLoop = 0; nLoop < nMaxVal; nLoop++)
	{
		selObj.remove(0);
	}
	selObj.options[0] = new Option ('None', '');
	if (currField.value == '') {
		selObj.options[0].selected = true;
	}
    }

}

// Setting cookies
function set_cookie ( name, value, exp_y, exp_m, exp_d, path, domain, secure )
{
  var cookie_string = name + "=" + escape ( value );

  if ( exp_y )
  {
    var expires = new Date ( exp_y, exp_m, exp_d );
    cookie_string += "; expires=" + expires.toGMTString();
  }

  if ( path )
        cookie_string += "; path=" + escape ( path );

  if ( domain )
        cookie_string += "; domain=" + escape ( domain );
  
  if ( secure )
        cookie_string += "; secure";
  
  document.cookie = cookie_string;
}

// Retrieving cookies
function get_cookie ( cookie_name )
{
  var results = document.cookie.match ( cookie_name + '=(.*?)(;|$)' );

  if ( results )
    return ( unescape ( results[1] ) );
  else
    return null;
}


// Delete cookies 
function delete_cookie ( cookie_name )
{
  var cookie_date = new Date ( );  // current date & time
  cookie_date.setTime ( cookie_date.getTime() - 1 );
  document.cookie = cookie_name += "=; expires=" + cookie_date.toGMTString();
}
function goToURL( url )
{
    document.location.href = url;
}
    
function invokeAction( actionName )
{
    if( actionName == "newReport" )
    {
        goToURL( "?module=Reports&action=NewReport0&return_module=Reports&return_action=index" );
        return;
    }    
    goToURL( "/crm/ScheduleReport.do?step=showAllSchedules" );
} 
function verify_data(form) {
	var isError = false;
	var errorMessage = "";
	if (trim(form.folderName.value) == "") {
		isError = true;
		errorMessage += "\nFolder Name";
	}
	// Here we decide whether to submit the form.
	if (isError == true) {
		alert(alert_arr.MISSING_FIELDS + errorMessage);
		return false;
	}
	return true;
}

function setObjects() 
{
	availListObj=getObj("availList")
	selectedColumnsObj=getObj("selectedColumns")

	moveupLinkObj=getObj("moveup_link")
	moveupDisabledObj=getObj("moveup_disabled")
	movedownLinkObj=getObj("movedown_link")
	movedownDisabledObj=getObj("movedown_disabled")
}

function addColumn() 
{
	for (i=0;i<selectedColumnsObj.length;i++) 
	{
		selectedColumnsObj.options[i].selected=false
	}
	addColumnStep1();
}

function addColumnStep1()
{
	//the below line is added for report not woking properly in browser IE7 --bharath
	document.getElementById("selectedColumns").style.width="164px";
	if (availListObj.options.selectedIndex > -1)
	{
		for (i=0;i<availListObj.length;i++) 
		{
			if (availListObj.options[i].selected==true) 
			{
				var rowFound=false;
				for (j=0;j<selectedColumnsObj.length;j++) 
				{
					if (selectedColumnsObj.options[j].value==availListObj.options[i].value) 
					{
						var rowFound=true;
						var existingObj=selectedColumnsObj.options[j];
						break;
					}
				}

				if (rowFound!=true) 
				{
					var newColObj=document.createElement("OPTION")
					newColObj.value=availListObj.options[i].value
					if (browser_ie) newColObj.innerText=availListObj.options[i].innerText
					else if (browser_nn4 || browser_nn6) newColObj.text=availListObj.options[i].text
					selectedColumnsObj.appendChild(newColObj)
					newColObj.selected=true
				} 
				else 
				{
					existingObj.selected=true
				}
				availListObj.options[i].selected=false
				addColumnStep1();
			}
		}
	}
}
//this function is done for checking,whether the user has access to edit the field :Bharath
function selectedColumnClick(oSel)
{
	if (oSel.selectedIndex == -1 || oSel.options[oSel.selectedIndex].disabled == true)
	{
		alert(alert_arr.NOT_ALLOWED_TO_EDIT);
		oSel.options[oSel.selectedIndex].selected = false;	
	}
}
function delColumn() 
{
	if (selectedColumnsObj.options.selectedIndex > -1)
	{
		for (i=0;i < selectedColumnsObj.options.length;i++) 
		{
			if(selectedColumnsObj.options[i].selected == true)
			{
				selectedColumnsObj.remove(i);
				delColumn();
			}
		}
	}
}

function formSelectColumnString()
{
	var selectedColStr = "";
	for (i=0;i<selectedColumnsObj.options.length;i++) 
	{
		selectedColStr += selectedColumnsObj.options[i].value + ";";
	}
	document.NewReport.selectedColumnsString.value = selectedColStr;
}

function moveUp() 
{
	var currpos=selectedColumnsObj.options.selectedIndex
	var tempdisabled= false;
	for (i=0;i<selectedColumnsObj.length;i++) 
	{
		if(i != currpos)
			selectedColumnsObj.options[i].selected=false
	}
	if (currpos>0) 
	{
		var prevpos=selectedColumnsObj.options.selectedIndex-1

		if (browser_ie) 
		{
			temp=selectedColumnsObj.options[prevpos].innerText
			tempdisabled = selectedColumnsObj.options[prevpos].disabled;
			selectedColumnsObj.options[prevpos].innerText=selectedColumnsObj.options[currpos].innerText
			selectedColumnsObj.options[prevpos].disabled = false;
			selectedColumnsObj.options[currpos].innerText=temp
			selectedColumnsObj.options[currpos].disabled = tempdisabled;     
		} 
		else if (browser_nn4 || browser_nn6) 
		{
			temp=selectedColumnsObj.options[prevpos].text
			tempdisabled = selectedColumnsObj.options[prevpos].disabled;
			selectedColumnsObj.options[prevpos].text=selectedColumnsObj.options[currpos].text
			selectedColumnsObj.options[prevpos].disabled = false;
			selectedColumnsObj.options[currpos].text=temp
			selectedColumnsObj.options[currpos].disabled = tempdisabled;
		}
		temp=selectedColumnsObj.options[prevpos].value
		selectedColumnsObj.options[prevpos].value=selectedColumnsObj.options[currpos].value
		selectedColumnsObj.options[currpos].value=temp
		selectedColumnsObj.options[prevpos].selected=true
		selectedColumnsObj.options[currpos].selected=false
		}
		
}

function moveDown() 
{
	var currpos=selectedColumnsObj.options.selectedIndex
	var tempdisabled= false;
	for (i=0;i<selectedColumnsObj.length;i++) 
	{
		if(i != currpos)
			selectedColumnsObj.options[i].selected=false
	}
	if (currpos<selectedColumnsObj.options.length-1)	
	{
		var nextpos=selectedColumnsObj.options.selectedIndex+1

		if (browser_ie) 
		{	
			temp=selectedColumnsObj.options[nextpos].innerText
			tempdisabled = selectedColumnsObj.options[nextpos].disabled;
			selectedColumnsObj.options[nextpos].innerText=selectedColumnsObj.options[currpos].innerText
			selectedColumnsObj.options[nextpos].disabled = false;
			selectedColumnsObj.options[nextpos];

			selectedColumnsObj.options[currpos].innerText=temp
			selectedColumnsObj.options[currpos].disabled = tempdisabled;
		}
		else if (browser_nn4 || browser_nn6) 
		{
			temp=selectedColumnsObj.options[nextpos].text
			tempdisabled = selectedColumnsObj.options[nextpos].disabled;
			selectedColumnsObj.options[nextpos].text=selectedColumnsObj.options[currpos].text
			selectedColumnsObj.options[nextpos].disabled = false;
			selectedColumnsObj.options[nextpos];
			selectedColumnsObj.options[currpos].text=temp
			selectedColumnsObj.options[currpos].disabled = tempdisabled;
		}
		temp=selectedColumnsObj.options[nextpos].value
		selectedColumnsObj.options[nextpos].value=selectedColumnsObj.options[currpos].value
		selectedColumnsObj.options[currpos].value=temp

		selectedColumnsObj.options[nextpos].selected=true
		selectedColumnsObj.options[currpos].selected=false
	}
}

function disableMove() 
{
	var cnt=0
		for (i=0;i<selectedColumnsObj.options.length;i++) 
		{
			if (selectedColumnsObj.options[i].selected==true)
				cnt++
		}

	if (cnt>1) 
	{
		moveupLinkObj.style.display=movedownLinkObj.style.display="none"
			moveupDisabledObj.style.display=movedownDisabledObj.style.display="block"
	}
	else 
	{
		moveupLinkObj.style.display=movedownLinkObj.style.display="block"
			moveupDisabledObj.style.display=movedownDisabledObj.style.display="none"
	}
}        


function hideTabs()
{
	var objreportType = getObj('reportType');
	if(objreportType[0].checked == true)
	{
		divarray = new Array('step1','step2','step4','step5');
	}
	else
	{
		divarray = new Array('step1','step2','step3','step4','step5');
	}
}
        
function showSaveDialog()
{    
	url = "index.php?module=Reports&action=SaveReport";
	window.open(url,"Save_Report","width=550,height=350,top=20,left=20;toolbar=no,status=no,menubar=no,directories=no,resizable=yes,scrollbar=no")
}
    
function saveAndRunReport()
{
	if(selectedColumnsObj.options.length == 0)
	{
		alert(alert_arr.COLUMNS_CANNOT_BE_EMPTY);
		return false;
	}

	formSelectColumnString();
	document.NewReport.submit();
}       
        
function changeSteps1() 
{
	if(getObj('step5').style.display != 'none')
	{
		var date1=getObj("startdate")
		var date2=getObj("enddate")

		//# validation added for date field validation in final step of report creation
		if ((date1.value != '') || (date2.value != ''))
		{

		if(!dateValidate("startdate","Start Date","D"))
        	        return false
	
		if(!dateValidate("enddate","End Date","D"))
        	        return false
	
		if(! compareDates(date1.value,'Start Date',date2.value,'End Date','LE'))
			return false;
		}	
		saveAndRunReport();

	}else
	{
		for(i = 0; i < divarray.length ;i++)
		{
			if(getObj(divarray[i]).style.display != 'none')
			{
				if(i == 1 && selectedColumnsObj.options.length == 0)
				{
					alert(alert_arr.COLUMNS_CANNOT_BE_EMPTY);
					return false;
				}	
				if(divarray[i] == 'step4')
				{
					document.getElementById("next").value = finish_text;	
				}
				hide(divarray[i]);
				show(divarray[i+1]);
				tableid = divarray[i]+'label';
				newtableid = divarray[i+1]+'label';
				getObj(tableid).className = 'settingsTabList'; 
				getObj(newtableid).className = 'settingsTabSelected';
				document.getElementById('back_rep').disabled = false;
				break;
			}

		}
	}
}
function changeStepsback1() 
{
	if(getObj('step1').style.display != 'none')
	{
		document.NewReport.action.value='ReportsAjax';
		document.NewReport.file.value='NewReport0';
		document.NewReport.submit();
	}else
	{
		for(i = 0; i < divarray.length ;i++)
		{
			if(getObj(divarray[i]).style.display != 'none')
			{
				if(divarray[i] == 'step2' && !backwalk_flag)
				{
					document.getElementById('back_rep').disabled = true;
				}
				document.getElementById("next").value = next_text+'>';	
				hide(divarray[i]);
				show(divarray[i-1]);
				tableid = divarray[i]+'label';
				newtableid = divarray[i-1]+'label';
				getObj(tableid).className = 'settingsTabList'; 
				getObj(newtableid).className = 'settingsTabSelected';
				break;
			}

		}
	}
}
function changeSteps()
{
	if(getObj('step1').style.display != 'none')
	{
		if (trim(document.NewRep.reportname.value) == "")
		{
			alert(alert_arr.MISSING_REPORT_NAME);
			return false;
		}else
		{
			new Ajax.Request(
                        'index.php',
                        {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: 'action=ReportsAjax&mode=ajax&file=CheckReport&module=Reports&check=reportCheck&reportName='+document.NewRep.reportname.value,
                                onComplete: function(response) {
					if(response.responseText!=0)
					{
						alert(alert_arr.REPORT_NAME_EXISTS);
						return false;
					}
					else
					{		
						hide('step1');
			                        show('step2');
			                        document.getElementById('back_rep').disabled = false;
                        			getObj('step1label').className = 'settingsTabList';
			                        getObj('step2label').className = 'settingsTabSelected';
					}

                                }
                        }
        	        );
	
		}

	}
	else
	{
		document.NewRep.submit();
	}
}
function changeStepsback()
{
	hide('step2');
	show('step1');
	document.getElementById('back_rep').disabled = true;
	getObj('step1label').className = 'settingsTabSelected'; 
	getObj('step2label').className = 'settingsTabList';
}
function editReport(id)
{
	var arg = 'index.php?module=Reports&action=ReportsAjax&file=NewReport1&record='+id;
	fnPopupWin(arg);
}
function CreateReport(module)
{
	var arg ='index.php?module=Reports&action=ReportsAjax&file=NewReport0&folder='+gcurrepfolderid+'&reportmodule='+module;
	fnPopupWin(arg);
}
function fnPopupWin(winName){
	window.open(winName, "ReportWindow","width=740px,height=625px,scrollbars=yes");
}
