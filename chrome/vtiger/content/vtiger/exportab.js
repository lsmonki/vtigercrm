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
var gvtABName = "";
var gvtABURL = "";
var gctcount = "1";
var gvtusername;
var gvtpassword;
var gvturl;
var gvtContactCount;
var gErrMsg="";

function load_expab()
{
	//to get the URI of vtigercrm Addressbook

	var count = opener.dirTree.view.rowCount;
   var vtigerABURL = "";
   var nsIndex = 0;
	var ncount = 0;
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
		
		var menupopelmnt = document.getElementById("expopup");	
		
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
	document.getElementById('expablist').selectedIndex = nsIndex;
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

function export_vtigerABURL()
{
	//intial method for exporting Ab cards to vtigerCRM Server
	if(document.getElementById("expablist").selectedItem.value != "")
	{
		gvtABURL = document.getElementById("expablist").selectedItem.value;
	}else
	{
		alert_message("Select Address Book to Export");
		return;
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
	
	var disbleExpButton = document.getElementById("expbutton");
	disbleExpButton.setAttribute("disabled","true");
		
	get_vtigercrmcards(gvtABURL);
	
	disbleExpButton.setAttribute("disabled","false");
	window.close();
}

function get_vtigercrmcards(expAburl)
{
		//method for exporting contacts to vtigerCRM server
		
		var RDF = Components.classes["@mozilla.org/rdf/rdf-service;1"].getService(Components.interfaces.nsIRDFService);

		// get the datasource for the addressdirectory
		var addressbookDS = RDF.GetDataSource("rdf:addressdirectory");
		
		// moz-abdirectory:// is the RDF root to get all types of addressbooks.
		var parentDir = RDF.GetResource("moz-abdirectory://").QueryInterface(Components.interfaces.nsIAbDirectory);
		
		// the RDF resource URI for LDAPDirectory will be like: "moz-abmdbdirectory://abook-3.mab"
		var ABdirectory = RDF.GetResource(expAburl).QueryInterface(Components.interfaces.nsIAbDirectory);
		
		var childE=ABdirectory.childCards;
      var count =0;
		
    try {
        childE.first();
        while(1) {
            var card=childE.currentItem().QueryInterface(Components.interfaces.nsIAbCard);
				if(card.primaryEmail != "")
				{
					var p = new Array();
					p[0] = new SOAPParameter(gvtusername,"user_name");
					p[1] = new SOAPParameter(card.firstName,"first_name"); // the search query
					if(card.lastName)
					{
						p[2] = new SOAPParameter(card.lastName,"last_name");
					}else if(card.displayName)
					{
						p[2] = new SOAPParameter(card.displayName,"last_name");
					}else if(card.nickName)
					{
						p[2] = new SOAPParameter(card.nickName,"last_name");
					}else if(card.primaryEmail)
					{
						var emailname = card.primaryEmail;
						if(emailname.indexOf("@") > -1)
						{
							emailname = emailname.substring(0,emailname.indexOf("@"));
						}
						p[2] = new SOAPParameter(emailname,"last_name");
					}
					
					p[3] = new SOAPParameter(card.primaryEmail,"email_address");
					p[4] = new SOAPParameter(card.company,"account_name");
					p[5] = new SOAPParameter("","salutation");
					p[6] = new SOAPParameter(card.jobTitle,"title");
					p[7] = new SOAPParameter(card.cellularNumber,"phone_mobile");
					p[8] = new SOAPParameter("","reports_to");
					p[9] = new SOAPParameter(card.workAddress+" "+card.workAddress2,"primary_address_street");
					p[10] = new SOAPParameter(card.workCity,"primary_address_city");
					p[11] = new SOAPParameter(card.workState,"primary_address_state");
					p[12] = new SOAPParameter(card.workZipCode,"primary_address_postalcode");
					p[13] = new SOAPParameter(card.workCountry,"primary_address_country");
					p[14] = new SOAPParameter(card.homeCity,"alt_address_city");
					p[15] = new SOAPParameter(card.homeAddress+" "+ card.homeAddress2,"alt_address_street");
					p[16] = new SOAPParameter(card.homeState,"alt_address_state");
					p[17] = new SOAPParameter(card.homeZipCode,"alt_address_postalcode");
					p[18] = new SOAPParameter(card.homeCountry,"alt_address_country");
					p[19] = new SOAPParameter(card.workPhone,"office_phone");
					p[20] = new SOAPParameter(card.homePhone,"home_phone");
					p[21] = new SOAPParameter(card.faxNumber,"fax");
					p[22] = new SOAPParameter(card.department,"department");
					
					var headers = new Array();
					var call = new SOAPCall();
					const objects = "uri:AddContactRequest";
					call.transportURI =  gvturl + "/vtigerservice.php?service=thunderbird";
					call.actionURI = objects + "/" + "AddContact";
					call.encode(0,"AddContact",objects,headers.length,headers,p.length,p);
					
					try
					{
						var oResponse = call.invoke();
					}catch(errorObject)
					{
						gErrMsg = "Cannot connect to the vtiger CRM server";
					}
					
					if(oResponse.fault){
					    //alert(oResponse.fault.faultString);
							gErrMsg = "Error while receiving response from the vtiger CRM server";
					}else
					{
						try
						{
							if(oResponse.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue == "")
							{
								gErrMsg = "Cannot export contacts to vtiger CRM";							
							}else
							{
								gErrMsg = "Successfully exported contacts to vtiger CRM";
							}
						}catch(errorObject)
						{
							gErrMsg = "Error while parsing response from the vtiger CRM server";
						}
					}
				}
            childE.next();
        }
    } catch(ex) {
			if(gErrMsg!="")
			{
				alert_message(gErrMsg);
			}
    }
}
function alert_message(message)
{
	var promptSvc = Components.classes["@mozilla.org/embedcomp/prompt-service;1"].getService(Components.interfaces.nsIPromptService);
   promptSvc.alert(window, "vtiger CRM", message);
}
