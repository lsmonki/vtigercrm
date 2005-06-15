/* ********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
******************************************************************************* */

//to store the details to access vtigercrm.
var oObj = Components.classes["@mozilla.org/preferences-service;1"].getService(Components.interfaces.nsIPrefService).getBranch("vtiger.");

function trim(stext) 
{
	return stext.replace(/^\s+/,'').replace(/\s+$/,'');
}
				
function save_vtigerconfig()
{
	//save the vtigercrm configuration details in the preferences datastore
	var svtusername = trim(document.getElementById("txtusername").value);
	var svtpassword = trim(document.getElementById("txtpassword").value);
	var svturl = trim(document.getElementById("txturl").value);
	var ncount =0;
	var salertmsg = "";
	
	if(svtusername=="")
	{
		ncount = ncount + 1;
		salertmsg = ncount + ". vtiger Username\n";
	}
	if(svtpassword=="")
	{
		ncount = ncount + 1;
		salertmsg = salertmsg + ncount + ". vtiger Password\n";
	}
	if(svturl=="")
	{
		ncount = ncount + 1;
		salertmsg = salertmsg + ncount + ". vtiger URL\n";
	}
	
	if(salertmsg !="")
	{
		alert_message("The following fields are mandatory\n"+salertmsg);
		return;
	}else
	{
		//save all the vtigerconfigurations
		oObj.setCharPref("Settings.Conf.vtigerUName",svtusername);
		oObj.setCharPref("Settings.Conf.vtigerPword",svtpassword);
		oObj.setCharPref("Settings.Conf.vtigerURL",svturl);
	}
	window.close();
}

function get_vtigerconfig()
{
	//get all the vtigerconfigurations
	if(oObj.prefHasUserValue("Settings.Conf.vtigerUName"))
	{
		document.getElementById("txtusername").value = oObj.getCharPref("Settings.Conf.vtigerUName");
		document.getElementById("txtpassword").value = oObj.getCharPref("Settings.Conf.vtigerPword");
		document.getElementById("txturl").value = oObj.getCharPref("Settings.Conf.vtigerURL");
	}
}

function alert_message(message)
{
	var promptSvc = Components.classes["@mozilla.org/embedcomp/prompt-service;1"].getService(Components.interfaces.nsIPromptService);
   promptSvc.alert(window,"vtiger CRM",message);
}