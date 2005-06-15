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

function load_addmsgtovtiger()
{
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
}  

function alert_message(message)
{
	var promptSvc = Components.classes["@mozilla.org/embedcomp/prompt-service;1"].getService(Components.interfaces.nsIPromptService);
   promptSvc.alert(window, "vtiger CRM", message);
}
