/* ********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
******************************************************************************* */

var gselecteddir = "";
var gvtusername;
var gvtpassword;
var gvturl;

function get_selected_export()
{
  //to get selected address book directory to import and export to that address book
	gselecteddir = GetSelectedDirectory();
	window.openDialog('chrome://vtiger/content/exportab.xul','','chrome,resizable=no,titlebar,modal,centerscreen');
}	

function get_selected_import()
{
  //to get selected address book directory to import and export to that address book
	gselecteddir = GetSelectedDirectory();
	window.openDialog('chrome://vtiger/content/importab.xul','','chrome,resizable=no,titlebar,modal,centerscreen');
}	

function checkContactView_perm()
{
  //function to import
  if(check_login())
  {  
    var p = new Array();
  	p[0] = new SOAPParameter(gvtusername,"user_name");
    var headers = new Array();
  	var call = new SOAPCall();
  	const objects = "uri:CheckContactViewPermRequest";
  	call.transportURI =  gvturl + "/vtigerservice.php?service=thunderbird";
  	call.actionURI = objects + "/" + "CheckContactViewPerm";
  	call.encode(0,"CheckContactViewPerm",objects,headers.length,headers,p.length,p);
  
  	try
  	{
  		var oResponse = call.invoke();
  	}catch(errorObject)
  	{
  		gErrMsg = "Cannot connect to the vtiger CRM server";
  	}
    if(oResponse.fault){
  			gErrMsg = "Error while receiving response from the vtiger CRM server";
  	}else
  	{
        try
  			{
  			   if(oResponse.body.childNodes.item(0).childNodes.item(0).hasChildNodes())
  				 {
  					   if(oResponse.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue != 'allowed')
  					   {
  					     alert_message("The user doesn't have permission to access contacts");
  					     return false;
  					   }else
  					   {
  					     get_selected_import();
               }
           }else
           {
              alert_message("Error while checking permission for the User");
           }
           
        }catch(errorObject)
  			{
  				gErrMsg = "Error while parsing response from the vtiger CRM server";
  			}
  	}
  	
  	//Display Error Message if set
  	if(gErrMsg != '')
  	   alert_message(gErrMsg);
  }	   
}

function check_login()
{
  var oPrefObj = Components.classes["@mozilla.org/preferences-service;1"].getService(Components.interfaces.nsIPrefService).getBranch("vtiger.");
  if(oPrefObj.prefHasUserValue("Settings.Conf.vtigerUName"))
	{
		gvtusername = oPrefObj.getCharPref("Settings.Conf.vtigerUName");
		gvtpassword = oPrefObj.getCharPref("Settings.Conf.vtigerPword");
		gvturl = oPrefObj.getCharPref("Settings.Conf.vtigerURL");
	}else
	{
		alert_message("Configure login details to access the vtiger CRM");
		return false;
	}
  var p = new Array();
	p[0] = new SOAPParameter(gvtusername,"user_name");
	p[1] = new SOAPParameter(gvtpassword,"password");
  var headers = new Array();
	var call = new SOAPCall();
	const objects = "uri:create_sessionRequest";
	call.transportURI =  gvturl + "/vtigerservice.php?service=thunderbird";
	call.actionURI = objects + "/" + "create_session";
	call.encode(0,"create_session",objects,headers.length,headers,p.length,p);

	try
	{
		var oResponse = call.invoke();
	}catch(errorObject)
	{
		gErrMsg = "Cannot connect to the vtiger CRM server";
	}
  if(oResponse.fault){
			gErrMsg = "Error while receiving response from the vtiger CRM server";
	}else
	{
      try
			{
			   if(oResponse.body.childNodes.item(0).childNodes.item(0).hasChildNodes())
				 {
				     var returnvalue = oResponse.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue;
					   if(returnvalue != 'success')
					   {
					     alert_message("Unable to Login to vtigerCRM with the UserName and Password Configured");
					     return false;
					   }else
					   {
					     return true;
             }
         }else
         {
            alert_message("Error while Login to vtigerCRM");
         }
         
      }catch(errorObject)
			{
				gErrMsg = "Error while parsing response from the vtiger CRM server";
			}
	}
	
	//Display Error Message if set
	if(gErrMsg != '')
	   alert_message(gErrMsg);
	   
}

function trim(s)
{
	while (s.substring(0,1) == " " || s.substring(0,1) == "\n")
	{
		s = s.substring(1, s.length);
	}
	while (s.substring(s.length-1, s.length) == " " || s.substring(s.length-1,s.length) == "\n") {
		s = s.substring(0,s.length-1);
	}
	return s;
}

function checkContact_perm()
{
  if(check_login())
  {  
    var p = new Array();
  	p[0] = new SOAPParameter(gvtusername,"user_name");
    var headers = new Array();
  	var call = new SOAPCall();
  	const objects = "uri:CheckContactPermRequest";
  	call.transportURI =  gvturl + "/vtigerservice.php?service=thunderbird";
  	call.actionURI = objects + "/" + "CheckContactPerm";
  	call.encode(0,"CheckContactPerm",objects,headers.length,headers,p.length,p);
  
  	try
  	{
  		var oResponse = call.invoke();
  	}catch(errorObject)
  	{
  		gErrMsg = "Cannot connect to the vtiger CRM server";
  	}
    if(oResponse.fault){
  			gErrMsg = "Error while receiving response from the vtiger CRM server";
  	}else
  	{
        try
  			{
  			   if(oResponse.body.childNodes.item(0).childNodes.item(0).hasChildNodes())
  				 {
  					   if(oResponse.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue != 'allowed')
  					   {
  					     alert_message("The user doesn't have permission to create contacts");
  					     return false;
  					   }else
  					   {
  					     get_selected_export();
               }
           }else
           {
              alert_message("Error while checking permission for the User");
           }
           
        }catch(errorObject)
  			{
  				gErrMsg = "Error while parsing response from the vtiger CRM server";
  			}
  	}
  	
  	//Display Error Message if set
  	if(gErrMsg != '')
  	   alert_message(gErrMsg);
  }	   
}

function alert_message(message)
{
	var promptSvc = Components.classes["@mozilla.org/embedcomp/prompt-service;1"].getService(Components.interfaces.nsIPromptService);
   promptSvc.alert(window, "vtiger CRM", message);
}
