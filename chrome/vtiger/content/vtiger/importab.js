//$Id:
/* ********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
******************************************************************************* */
var gvtABName;
var gvtABURL = "";
var gctcount = "1";
var gvtusername;
var gvtpassword;
var gvturl;
var gvtContactCount = 0 ;
const cvtPABDirectory  = 2;
var gcount =0;


function trim(stext) 
{
	return stext.replace(/^\s+/,'').replace(/\s+$/,'');
}

function load_ab()
{
	//to get the URI of vtigercrm Addressbook
	var count = opener.dirTree.view.rowCount;
   var vtigerABURL = "";
   var nsIndex = 0;
	var ncount=0;
   for (var i = 0 ; i <= count-1; i++)
	{
		var res = opener.dirTree.builderView.getResourceAtIndex(i);
		var Aburl = res.Value;
		var RDF = Components.classes["@mozilla.org/rdf/rdf-service;1"].getService(Components.interfaces.nsIRDFService);

		// get the datasource for the addressdirectory
		var addressbookDS = RDF.GetDataSource("rdf:addressdirectory");
		
		// moz-abdirectory:// is the RDF root to get all types of addressbooks.
		var parentDir = RDF.GetResource("moz-abdirectory://").QueryInterface(Components.interfaces.nsIAbDirectory);
		
		// the RDF resource URI for LDAPDirectory will be like: "moz-abmdbdirectory://abook-3.mab"
		var ABdirectory = RDF.GetResource(Aburl).QueryInterface(Components.interfaces.nsIAbDirectory);
		
		// Copy existing dir type category id and mod time so they won't get reset.
		var ABProperties = ABdirectory.directoryProperties;
		
		var menupopelmnt = document.getElementById("impopup");	
		
		if(ABProperties.description!="")
		{
			var typechild = document.createElement('menuitem');
			typechild.setAttribute('label',ABProperties.description);
			typechild.setAttribute('value',Aburl);
			menupopelmnt.appendChild(typechild);
			if(Aburl == opener.gselecteddir)
			{
				nsIndex = ncount;
			}
			ncount = ncount + 1;
		}
	}
	document.getElementById('impablist').selectedIndex = nsIndex;
	document.getElementById('impablist').focus();
}

function get_vtigerABURL()
{
	//to get the URI of vtigercrm Addressbook

	var count = opener.dirTree.view.rowCount;
   var vtigerABURL = "";

   for (var i = 0 ; i <= count-1; i++)
	{
		var res = opener.dirTree.builderView.getResourceAtIndex(i);
		var Aburl = res.Value;
		var RDF = Components.classes["@mozilla.org/rdf/rdf-service;1"].getService(Components.interfaces.nsIRDFService);

		// get the datasource for the addressdirectory
		var addressbookDS = RDF.GetDataSource("rdf:addressdirectory");
		
		// moz-abdirectory:// is the RDF root to get all types of addressbooks.
		var parentDir = RDF.GetResource("moz-abdirectory://").QueryInterface(Components.interfaces.nsIAbDirectory);
		
		// the RDF resource URI for LDAPDirectory will be like: "moz-abmdbdirectory://abook-3.mab"
		var ABdirectory = RDF.GetResource(Aburl).QueryInterface(Components.interfaces.nsIAbDirectory);
		
		// Copy existing dir type category id and mod time so they won't get reset.
		var ABProperties = ABdirectory.directoryProperties;
		
		//get the addressbook name and compare with vtigerCRMAB
		if(ABProperties.description == gvtABName)
		{
			vtigerABURL = Aburl;
			break;
		}else
		{
			vtigerABURL = "";
		}
		
	}
	return vtigerABURL;
}

function create_vtigerABURL()
{
	//this method is the intial point to create import contacts from vtigerCRM
	if(trim(document.getElementById("impablist").value) != "")
	{
		gvtABName = document.getElementById("impablist").value;
		gvtABURL = get_vtigerABURL();

		if(gvtABURL == "")
		{
			//vtigerCRMAB not created then create AB for vtigerCRM
			CreateNewAddressBook(gvtABName);
			gvtABURL = get_vtigerABURL();
		
		}

		var oPrefObj = Components.classes["@mozilla.org/preferences-service;1"].getService(Components.interfaces.nsIPrefService).getBranch("vtiger.");
	   
		if(oPrefObj.prefHasUserValue("Settings.Conf.vtigerUName"))
		{
			gvtusername = oPrefObj.getCharPref("Settings.Conf.vtigerUName");
			gvtpassword = oPrefObj.getCharPref("Settings.Conf.vtigerPword");
			gvturl = oPrefObj.getCharPref("Settings.Conf.vtigerURL");
		}else
		{
			alert_message("Configure login details to access the vtiger CRM");
			return;
		}
		var disbleImpButton = document.getElementById("impbutton");
		disbleImpButton.setAttribute("disabled","true");
		create_cardinvtigerAB();
		disbleImpButton.setAttribute("disabled","false");
		window.close();
	}else
	{
		alert_message("Specify Address Book name to create");
	}
}

function CreateNewAddressBook(aName)
{
	//used to create new address book in Thunderbird
	var addressbookVt = Components.classes["@mozilla.org/addressbook;1"].createInstance(Components.interfaces.nsIAddressBook);
	var vtproperties = Components.classes["@mozilla.org/addressbook/properties;1"].createInstance(Components.interfaces.nsIAbDirectoryProperties);
	vtproperties.description = aName;
	vtproperties.dirType = cvtPABDirectory;
	addressbookVt.newAddressBook(vtproperties);
}

function get_vtigercontcounts()
{
	
	//used to get the contacts count from vtigerCRM server, using soap method.
	var headers = new Array();
	var params = new Array(new SOAPParameter(gvtusername,"user_name"),new SOAPParameter(gvtpassword,"password"));
	var call = new SOAPCall();
	const objects = "uri:get_contacts_countRequest";
	call.transportURI =  gvturl + "/contactserialize.php";
	call.actionURI = objects + "/" + "get_contacts_count";
	call.encode(0,"get_contacts_count",objects,headers.length,headers,params.length,params);
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
				   gvtContactCount = oResponse.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue;
			}else
			{
				alert_message("contacts not available in vtiger CRM");
			}
		}catch(errorObject)
		{
			alert_message("Error while parsing response from the vtiger CRM server");
		}	
	}
}

function create_cardinvtigerAB()
{
	
	//used to get the contacts from vtigerCRM server, using soap method.
	var directory = GetDirectoryFromURI(gvtABURL);

	var headers = new Array();
	var params = new Array(new SOAPParameter(gvtusername,"username"));
	var call = new SOAPCall();
	const objects = "uri:GetContactsRequest";
	call.transportURI =  gvturl + "/vtigerservice.php?service=thunderbird";
	call.actionURI = objects + "/" + "GetContacts";
	call.encode(0,"GetContacts",objects,headers.length,headers,params.length,params);
	try
	{
		var oResponse = call.invoke();
   }catch(errorObject)
	{
		alert_message("Cannot connect to the vtiger CRM server");
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
					//the obj for the card to be created
					var ObjAbcard= Components.classes["@mozilla.org/addressbook/cardproperty;1"].createInstance(Components.interfaces.nsIAbCard);
					
					for(var j=0;j<itemsLength;j++)
					{
						//alert(itemsNode.item(j).childNodes.item(0).nodeValue);
						if(itemsNode.item(j).childNodes.item(0))
						{
              switch(j)
							{
								//modified for 4.2 Release
								case 1:
										ObjAbcard.firstName = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;
								case 2:
										ObjAbcard.lastName = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;
								case 3:
										ObjAbcard.primaryEmail = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;
								case 19:
										ObjAbcard.workCity = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;
								case 4:
										ObjAbcard.company = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;
								case 7:
										ObjAbcard.jobTitle = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;
								case 14:
										ObjAbcard.cellularNumber = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;
								case 18:
										ObjAbcard.workAddress = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;										
								case 20:
										ObjAbcard.workState = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;
								case 21:
										ObjAbcard.workZipCode = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;
								case 22:
										ObjAbcard.workCountry = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;
								case 24:
										ObjAbcard.homeCity = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;
								case 23:
										ObjAbcard.homeAddress = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;										
								case 25:
										ObjAbcard.homeState = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;
								case 26:
										ObjAbcard.homeZipCode = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;
								case 27:
										ObjAbcard.homeCountry = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;
								//added for 4.2 Release
								case 10:
										ObjAbcard.workPhone = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;
								case 11:
										ObjAbcard.homePhone = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;
								case 13:
										ObjAbcard.faxNumber = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;
								case 8:
										ObjAbcard.department = itemsNode.item(j).childNodes.item(0).nodeValue;
										break;

							}
						}
					}
					ObjAbcard.displayName = ObjAbcard.firstName + " " + ObjAbcard.lastName;
					//create card in ABook
					var addedCard = directory.addCard(ObjAbcard);	
				}
				alert_message("Successfully imported contacts from vtiger CRM");
			}else
			{
				alert_message("Cannot import contacts from vtiger CRM");
			}
		}catch(errorObject)
		{
			alert_message("Error while parsing response from the vtiger CRM server");
		}	
	}
}

function alert_message(message)
{
	var promptSvc = Components.classes["@mozilla.org/embedcomp/prompt-service;1"].getService(Components.interfaces.nsIPromptService);
   promptSvc.alert(window, "vtiger CRM", message);
}
