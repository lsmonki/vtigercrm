/* ********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
******************************************************************************* */
var gvtusername;
var gvtpassword;
var gvturl;

function vget_mailfrmaddrs()
{
	//intial method for adding mails to vtigercrm server
	var oPrefObj = Components.classes["@mozilla.org/preferences-service;1"].getService(Components.interfaces.nsIPrefService).getBranch("vtiger.");

	if(oPrefObj.prefHasUserValue("Settings.Conf.vtigerUName"))
	{
		gvtusername = oPrefObj.getCharPref("Settings.Conf.vtigerUName");
		gvtpassword = oPrefObj.getCharPref("Settings.Conf.vtigerPword");
		gvturl = oPrefObj.getCharPref("Settings.Conf.vtigerURL");
	}
	
	var fromaddress = opener.gauthor;
	
	if(fromaddress != "")
	{
	  if(fromaddress.indexOf("<") > -1)
	  {
			fromaddress = fromaddress.substring(fromaddress.indexOf("<")+1,fromaddress.indexOf(">"));
	  }
	}

	document.getElementById("txtemailid").value = fromaddress;
	document.getElementById("TextAreaValue").value = opener.gwinBody;
}

function launch_contact()
{
  //method to open vtigerCRM
  var messenger1 = Components.classes["@mozilla.org/messenger;1"].createInstance();         
  messenger1 = messenger1.QueryInterface(Components.interfaces.nsIMessenger);
  messenger1.launchExternalURL(gvturl + "/index.php?module=Contacts&action=DetailView&record="+cntid);
}

function load_vtigercrm(cntid)
{
  //method to open vtigerCRM
  var messenger = Components.classes["@mozilla.org/messenger;1"].createInstance();         
  messenger = messenger.QueryInterface(Components.interfaces.nsIMessenger);
  messenger.launchExternalURL(gvturl + "/index.php?module=Users&action=Authenticate&return_module=Users&return_action=Login&user_name=admin&user_password=admin");
  setTimeout("launch_contact()",5000);
}

function bget_CntBySearch()
{
	//method to search, any available contacts in vtigercrm server
	var frstname ="";
	var lstname = "";
	var cntid = "";
	var acntname = "";
	var cntemail = "";
   var rcount = 1;
	
	var contactlist = document.getElementById('lstcontactinfo');

	while(contactlist.getRowCount() >= 1)
	{
		contactlist.removeItemAt(2);
	}
	
	var headers = new Array();
	var params = new Array(new SOAPParameter(gvtusername,"user_name"),new SOAPParameter(document.getElementById('txtemailid').value,"email_address"));
	var call = new SOAPCall();
	const objects = "uri:contact_by_emailRequest";
	call.transportURI = gvturl + "/contactserialize.php";
	call.actionURI = objects + "/" + "contact_by_email";
	call.encode(0,"contact_by_email",objects,headers.length,headers,params.length,params);
	try
	{
		var oResponse = call.invoke();
   }catch(errorObject)
	{
		alert_message("Can not connect to the vtiger CRM server");
	}
	
	if(oResponse.fault){
		alert_message("Error while receiving response from the vtiger CRM server");
	}else
	{
		try
		{
			if(oResponse.body.childNodes.item(0).childNodes.item(0).hasChildNodes())
			{ 
				var itemLength = oResponse.body.childNodes.item(0).childNodes.item(0).childNodes.length;
				var itemNode = oResponse.body.childNodes.item(0).childNodes.item(0).childNodes;

				for(var i=0;i<itemLength;i++)
				{
					var itemsLength = itemNode.item(i).childNodes.length;
					var itemsNode = itemNode.item(i).childNodes;
					for(var j=0;j<itemsLength;j++)
					{
						if(itemsNode.item(j).childNodes.item(0))
						{
							switch(j)
							{
								case 1:
										frstname = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;
								case 2:
										lstname = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;
								case 0:
										cntemail = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;
								case 6:
										cntid = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;
								case 4:
										acntname = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;
							}
						}
					}
					//code to add the items to the listbox
						var listboxelmnt = document.getElementById("lstcontactinfo");
						
						var listitemdoc = document.createElement('listitem');
						listitemdoc.setAttribute("value",cntid);
						
						var typechild = document.createElement('listcell');
						typechild.setAttribute('label',"Contact");
						listitemdoc.appendChild(typechild);
						
						var fullnamechild = document.createElement('listcell');
						fullnamechild.setAttribute('label',frstname+ " " +lstname);
						listitemdoc.appendChild(fullnamechild);
						
						var accountchild = document.createElement('listcell');
						accountchild.setAttribute('label',acntname);
						listitemdoc.appendChild(accountchild);
						
						var emailchild = document.createElement('listcell');
						emailchild.setAttribute('label',cntemail);
						listitemdoc.appendChild(emailchild);
										
						listboxelmnt.appendChild(listitemdoc);
					//end code to add the items to the listbox
				}
			}else
			{
				alert_message("Contact is not available in the vtiger CRM");
			}
		}catch(errorObject)
		{
		alert_message("Error while parsing response from the vtiger CRM server");
		}	
	}
}


function vaddemailtovtigerCRM()
{

	//method to add message to vtigerCRM server
	if(document.getElementById('lstcontactinfo').selectedItem)
	{
		var contactid = document.getElementById('lstcontactinfo').selectedItem.value;
		var headers = new Array();
		var params = new Array(new SOAPParameter(gvtusername,"user_name"),new SOAPParameter(contactid,"contact_ids"),new SOAPParameter(opener.gdate,"date_sent"),new SOAPParameter(opener.gsubject,"email_subject"),new SOAPParameter(document.getElementById("TextAreaValue").value,"email_body"));
		var call = new SOAPCall();
		const objects = "uri:track_emailRequest";
		call.transportURI = gvturl + "/contactserialize.php";
		call.actionURI = objects + "/" + "track_email";
		call.encode(0,"track_email",objects,headers.length,headers,params.length,params);
		try
		{
			var oResp = call.invoke();
		}catch(errorObject)
		{
			alert_message("Can not connect to the vtiger CRM server");
		}
	   try
		{
			if(oResp.fault){
				alert_message("Error while receiving response from the vtiger CRM server");
			}
			else
			{
				if(oResp.body.childNodes.item(0).childNodes.item(0).hasChildNodes())
				{
					if(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue!="")
					{
						alert_message("Successfully added message to the vtiger CRM");
						window.close();
					}else
					{
						alert_message("Can not add message to the vtiger CRM");
					}
				}else
				{
					alert_message("Can not add message to the vtiger CRM");
				}
			}
			
		}catch(errorObject)
		{
			alert_message(" Error while parsing response from the vtiger CRM server");
		}
	}else
	{
		alert_message("Select contact to add message to the vtiger CRM"); 
	}
}

function alert_message(message)
{
	var promptSvc = Components.classes["@mozilla.org/embedcomp/prompt-service;1"].getService(Components.interfaces.nsIPromptService);
   promptSvc.alert(window, "vtiger CRM", message);
}
