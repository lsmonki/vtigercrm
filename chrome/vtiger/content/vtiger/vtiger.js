//#$Id:
/* ********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
******************************************************************************* */

var gwinBody;
var gsubject;
var gauthor;
var gdate;
var gvtusername;
var gvtpassword;
var gvturl;

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
					   if(oResponse.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue != 'success')
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
	return false;
	   
}

function check_load_addmsg()
{
  var messageURI = GetFirstSelectedMessage();
  if(messageURI == null)
  {
    alert_message("Please Select an email to Add to vtigerCRM");
    return false;
  }  
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
  					     load_addmsgtoVtiger();
  					     return true;
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

function load_addmsgtoVtiger()
{
    // check for contact view permission for the current user
    //to get message from the message browser window
   
    var win,loadedMessageURI,brwsr,retVal;
    var oDate;
    
    var oPrefObj = Components.classes["@mozilla.org/preferences-service;1"].getService(Components.interfaces.nsIPrefService).getBranch("vtiger.");
  
  	if(!oPrefObj.prefHasUserValue("Settings.Conf.vtigerUName"))
  	{
  		alert_message("Configure login details to access the vtiger CRM");
  		return;
  	}
    var windowManager = Components.classes["@mozilla.org/appshell/window-mediator;1"].getService(nsIWindowMediator);
    var messengerWindowList = windowManager.getEnumerator("mail:3pane");
    var messageWindowList = windowManager.getEnumerator("mail:messageWindow");
    //the messageURI which is selected by the user
    var messageURI = GetFirstSelectedMessage();
   
    if (messageURI!="")
    {
  	  while (true) {
  	
  		 if (messengerWindowList.hasMoreElements())
  			win = messengerWindowList.getNext();
  		 else if (messageWindowList.hasMoreElements())
  			win = messageWindowList.getNext();
  		 else break;
  	
  		 loadedMessageURI = win.GetLoadedMessage();
  		 if (loadedMessageURI != messageURI) continue;
  		 brwsr = win.getMessageBrowser();
  		 if (!brwsr) continue;
  		 //get the message content of the selected message
  		 gwinBody = brwsr.docShell.contentViewer.DOMDocument.body.textContent;
  	  }
  	  
  	  var srcMsgHdr = messenger.messageServiceFromURI(messageURI).messageURIToMsgHdr(messageURI);
  
  	  gsubject = srcMsgHdr.subject;
  	  gauthor = srcMsgHdr.author;
  	  //gdate = srcMsgHdr.date / (1000 * 1000);
  	  oDate = new Date();
  	  var y = oDate.getFullYear();
  	  var mo = oDate.getMonth() + 1;
       	  var d = oDate.getDate();
  	  gdate = y + "-" + mo + "-" + d ; 
  	  window.openDialog('chrome://vtiger/content/addtovtiger.xul','','chrome,resizable=no,titlebar,modal,centerscreen');
    }else
    {
  	alert_message("Pls, Select a Message and Add to vtiger CRM.");
    }
    return true;
}  

function alert_message(message)
{
	var promptSvc = Components.classes["@mozilla.org/embedcomp/prompt-service;1"].getService(Components.interfaces.nsIPromptService);
   promptSvc.alert(window, "vtiger CRM", message);
}
