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
fLabels['e'] = 'equals';
fLabels['n'] = 'not equal to';
fLabels['s'] = 'starts with';
fLabels['c'] = 'contains';
fLabels['k'] = 'does not contain';
fLabels['l'] = 'less than';
fLabels['g'] = 'greater than';
fLabels['m'] = 'less or equal';
fLabels['h'] = 'greater or equal';
var noneLabel;

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
    if( actionName == "newReportFolder" )
    {
        goToURL( "?module=Reports&action=NewReportFolder&return_module=Reports&return_action=index" );
        return;
    }    
    goToURL( "/crm/ScheduleReport.do?step=showAllSchedules" );
} 
function showRelatedModules(currmodule) 
        {
            for (i=0;i<getObj("primarymodule").length;i++) 
            {
                var moduleopt=getObj("primarymodule")[i].value
                if (currmodule==i) 
                    getObj(moduleopt+"relatedmodule").style.display="block"
                else
                    getObj(moduleopt+"relatedmodule").style.display="none"
            }
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
		alert("Missing required fields:" + errorMessage);
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

            for (i=0;i<availListObj.length;i++) 
            {
                if (availListObj.options[i].selected==true) 
                {
                    for (j=0;j<selectedColumnsObj.length;j++) 
                    {
                        if (selectedColumnsObj.options[j].value==availListObj.options[i].value) 
                        {
                            var rowFound=true
                            var existingObj=selectedColumnsObj.options[j]
                            break
                        }
                    }

                    if (rowFound!=true) 
                    {
                        var newColObj=document.createElement("OPTION")
                        newColObj.value=availListObj.options[i].value
                        if (browser_ie) newColObj.innerText=availListObj.options[i].innerText
                        else if (browser_nn4 || browser_nn6) newColObj.text=availListObj.options[i].text
                        selectedColumnsObj.appendChild(newColObj)
                        availListObj.options[i].selected=false
                        newColObj.selected=true
                        rowFound=false
                    } 
                    else 
                    {
                        existingObj.selected=true
                    }
                }
            }
        }

        function delColumn() 
        {
            for (i=0;i<=selectedColumnsObj.options.length;i++) 
            {
                if (selectedColumnsObj.options.selectedIndex>=0)
                selectedColumnsObj.remove(selectedColumnsObj.options.selectedIndex)
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
            if (currpos>0) 
            {
                var prevpos=selectedColumnsObj.options.selectedIndex-1
		
                if (browser_ie) 
                {
                    temp=selectedColumnsObj.options[prevpos].innerText
                    selectedColumnsObj.options[prevpos].innerText=selectedColumnsObj.options[currpos].innerText
                    selectedColumnsObj.options[currpos].innerText=temp     
                } 
                else if (browser_nn4 || browser_nn6) 
                {
                    temp=selectedColumnsObj.options[prevpos].text
                    selectedColumnsObj.options[prevpos].text=selectedColumnsObj.options[currpos].text
                    selectedColumnsObj.options[currpos].text=temp
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
            if (currpos<selectedColumnsObj.options.length-1)	
            {
                var nextpos=selectedColumnsObj.options.selectedIndex+1

                if (browser_ie) 
                {	
                    temp=selectedColumnsObj.options[nextpos].innerText
                    selectedColumnsObj.options[nextpos].innerText=selectedColumnsObj.options[currpos].innerText
                    selectedColumnsObj.options[currpos].innerText=temp
                }
                else if (browser_nn4 || browser_nn6) 
                {
                    temp=selectedColumnsObj.options[nextpos].text
                    selectedColumnsObj.options[nextpos].text=selectedColumnsObj.options[currpos].text
                    selectedColumnsObj.options[currpos].text=temp
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
        
        function selectAction( url )
        {
            formSelectColumnString();
            document.NewReport.action = url;
            document.NewReport.submit();
        }

function getOpenerObj(n) {

   return getObj(n,opener.document)

}
function saveReport( dlgType )
    {
       if( !emptyCheck( "reportName", "Report Name" ) )
       return false;    
                
        var repNameObj = getOpenerObj( "reportName" );
        var repDescObj = getOpenerObj( "reportDesc" );
        var folderObj = getOpenerObj( "folder" );
        var actionObj = getOpenerObj( "actionItem" );
        var formObj = getOpenerObj( "NewReport" );
        if( dlgType == "save" )
        {
           formObj = getOpenerObj( "NewReport" );
            if( getOpenerObj( 'reportId' ) != null )
            {
                formObj.removeChild( getOpenerObj( 'reportId' ) );
            }
        }
        else
        {
//            formObj = getOpenerObj( "SaveAsForm" );
            actionObj.value = "saveAs";
        }
        
        repNameObj.value = document.NewReport.reportName.value;
        repDescObj.value = document.NewReport.reportDesc.value;
        folderObj.value = document.NewReport.folder.value;        
        formObj.submit();
        
        window.self.close();
        return false;
    }   
		
