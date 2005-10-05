/* ********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
******************************************************************************* */

var vtiger_pref = Components.classes["@mozilla.org/preferences-service;1"].getService(Components.interfaces.nsIPrefService).getBranch("vtiger.");

var get_username;
var get_password;
var get_url;
var get_checkvalue = false;
vtiger_pref.setCharPref("Settings.Conf.temp_variable","false");

function VTIGER_LoadURL(URL)
{
    // Set the document's location to the incoming URL
    window._content.document.location = URL;

    // Make sure we get the focus
    window.content.focus();
}

//save cofiguration datas
function save_vtigeruserlogin()
{

	var save_username = trim(document.getElementById("txtusername").value);
	var save_password = trim(document.getElementById("txtpassword").value);
        var save_url=trim(document.getElementById("txturl").value);
	//get_checkvalue=document.getElementById("login_check").checked;
	vtiger_pref.setCharPref("Settings.Conf.temp_variable","true");
	if(save_username=="" || save_password=="" || save_url=="")
	{
		
		window.alert("All fields are mandatory");
		vtiger_pref.setCharPref("Settings.Conf.temp_variable","false");
		return;
	}else
	{
	
		//if(document.getElementById("login_check").checked == true)
		//{
			vtiger_pref.setCharPref("Settings.Conf.vtiger_username",save_username);
			vtiger_pref.setCharPref("Settings.Conf.vtiger_password",save_password);
			vtiger_pref.setCharPref("Settings.Conf.vtiger_url",save_url);
			//vtiger_pref.setCharPref("Settings.Conf.vtiger_checkvalue",get_checkvalue);	
		/*}
		else
		{
			vtiger_pref.setCharPref("Settings.Conf.vtiger_username",save_username);
			vtiger_pref.setCharPref("Settings.Conf.vtiger_password",save_password);
			vtiger_pref.setCharPref("Settings.Conf.vtiger_url",save_url);
			vtiger_pref.setCharPref("Settings.Conf.vtiger_checkvalue","false");
		}*/
	}
	window.close();                                                    
}

//enable toolbar button
function enable_menubar()
{
	
	if(vtiger_pref.prefHasUserValue("Settings.Conf.vtiger_username"))
	{
		get_username = vtiger_pref.getCharPref("Settings.Conf.vtiger_username");
		get_password = vtiger_pref.getCharPref("Settings.Conf.vtiger_password");
		get_url = vtiger_pref.getCharPref("Settings.Conf.vtiger_url");
	}

	try
	{
	//SOAP method to check login detail.
	var headers = new Array();
	var params = new Array(new SOAPParameter(get_username,"user_name"),new SOAPParameter(get_password,"password"));
		var call = new SOAPCall();
		const objects = "uri:track_emailRequest";
		call.transportURI = get_url + "/contactserialize.php";
		call.actionURI = objects + "/" + "get_version";
		call.encode(0,"get_version",objects,headers.length,headers,params.length,params);
		var oResp = call.invoke();
	}catch(errorObject)
	{
		alert("Can not connect to the vtiger CRM server");
	}
			
		 	try
			{
				if(oResp.fault){
					alert("Error while receiving response from the vtiger CRM server");
				}
				else
				{
					if(oResp.body.childNodes.item(0).childNodes.item(0).hasChildNodes())
					{
						if(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue!="")
						{
						    
						    
							document.getElementById("VTIGER-LEAD").disabled=false;
							document.getElementById("VTIGER-CONTACT").disabled=false;
							document.getElementById("VTIGER-ACCOUNT").disabled=false;
							document.getElementById("VTIGER-TICKET").disabled=false;
							
						   						
						}else
						{
							
							document.getElementById("VTIGER-LEAD").disabled=true;
							document.getElementById("VTIGER-CONTACT").disabled=true;
							document.getElementById("VTIGER-ACCOUNT").disabled=true;
							document.getElementById("VTIGER-TICKET").disabled=true;
							alert("Can not connect to vtiger CRM");
						    	

						}
					}else
					{
						alert("Can not connect to vtiger CRM");
						document.getElementById("VTIGER-LEAD").disabled=true;
						document.getElementById("VTIGER-CONTACT").disabled=true;
						document.getElementById("VTIGER-ACCOUNT").disabled=true;
						document.getElementById("VTIGER-TICKET").disabled=true;

					}
				}
			
			}catch(errorObject)
			{
				//alert(" Error while parsing response from the vtiger CRM server");
			}
	

}

//for saving configuration datas

function get_vtigeruserlogin()
{

	
	if(vtiger_pref.prefHasUserValue("Settings.Conf.vtiger_username"))
	{
		document.getElementById("txtusername").value = vtiger_pref.getCharPref("Settings.Conf.vtiger_username");
		document.getElementById("txtpassword").value = vtiger_pref.getCharPref("Settings.Conf.vtiger_password");
		document.getElementById("txturl").value = vtiger_pref.getCharPref("Settings.Conf.vtiger_url");
	}

}

//method to trim whitespace
function trim(stext) 
{
	return stext.replace(/^\s+/,'').replace(/\s+$/,'');
}


//method to get configuration datas for vtigerCRM
function get_vtigercrmconfig()
{
	
	if(vtiger_pref.prefHasUserValue("Settings.Conf.vtiger_username"))
	{
		get_username = vtiger_pref.getCharPref("Settings.Conf.vtiger_username");
		get_password = vtiger_pref.getCharPref("Settings.Conf.vtiger_password");
		get_url = vtiger_pref.getCharPref("Settings.Conf.vtiger_url");
	}
	else
	{
		alert("Configure login details to access the vtiger CRM");
		return;
		
	}
}



function VTIGER_InvokeTool(event, type, VTIGER_STMID, VTIGER_STID)
{
    
    //var win = window._content.document;
    var URL = "";
    var isEmpty = false;
    var searchTermsBox = document.getElementById(VTIGER_STID);

    // Get the value in the search terms box, trimming whitespace as necessary
    var searchTerms = trim(searchTermsBox.value);
   
    // If there are no search terms, than we set isEmpty to true
    // Otherwise, we convert the search terms to a safe URI version
    if(searchTerms.length == 0) { isEmpty = true; }
    else { searchTerms = VTIGER_ConvertTermsToURI(searchTerms); }
    switch(type)
    {
	case "VTIGER-SEARCH-ICON":
		if(isEmpty){URL="http://www.vtiger.com/discussions/search.php";}
		else{URL="http://search.vtiger.com/search.jsp?domain=all&hitsPerPage=10&query="+searchTerms+"&hitsPerPage=10&url_val=all&hitsPerSite=0";}
		break;	
    }	
    VTIGER_LoadURL(URL);
   
}


function VTIGER_ConvertTermsToURI(terms)
{
    // Split up the search terms based on the space character
    var termArray = new Array();
    termArray = terms.split(" ");
    var result = "";

    // Loop through the search terms, building up the result string
    for(var i=0; i<termArray.length; i++)
        {
            if(i > 0) { result += "+"; }

            // Call the built-in function encodeURIComponent() to clean up
            // this search term (making it safe for use in a URL)
            result += encodeURIComponent(termArray[i]);
        }


    return result;
}






//add lead to vtigerCRM

function add_lead_to_vtigercrm()
{
	//get Lead's information to save it in vtigerCRM
	var save_lead_name = trim(document.getElementById("txtleadname").value);
	var save_lead_email = trim(document.getElementById("txtleademail").value);
	var save_lead_phone = trim(document.getElementById("txtleadphone").value);
	var save_lead_company = trim(document.getElementById("txtleadcompany").value);
	//check for Lead's Name and Company feild is not empty
	if(save_lead_name=="" && save_lead_company=="")
	{
		window.alert("Last Name and Company are mandatory");
		return;
	}else if(save_lead_name==""){window.alert("Last Name is mandatory");return;}
	 else if(save_lead_company==""){window.alert("Company Name is mandatory");return;}
	else
	{
			
                if(save_lead_name!="" && save_lead_company!="")
		{
			//SOAP method to save information in vtigerCRM
			var description=document.getElementById("txtdescription").value;
			var headers = new Array();
			var params = new Array(new SOAPParameter(save_lead_name,"lastname"),new SOAPParameter(save_lead_email,"email"),new SOAPParameter(save_lead_phone,"phone"),new SOAPParameter(save_lead_company,"company"),new SOAPParameter("","country"),new SOAPParameter(document.getElementById("txtdescription").value,"description"));
		var call = new SOAPCall();
		const objects = "uri:track_emailRequest";
		call.transportURI = get_url + "/contactserialize.php";
		call.actionURI = objects + "/" + "create_lead_from_webform";
		call.encode(0,"create_lead_from_webform",objects,headers.length,headers,params.length,params);
			try
			{
				var oResp = call.invoke();
			}catch(errorObject)
				{
					alert("Can not connect to the vtiger CRM server");
				}
			
		 	try
			{
				if(oResp.fault){
					alert("Error while receiving response from the vtiger CRM server");
				}
				else
				{
					if(oResp.body.childNodes.item(0).childNodes.item(0).hasChildNodes())
					{
						if(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue!="")
						{
							alert("Lead added to vtiger CRM successfully");
						}else
						{
							alert("Can not add Lead to vtiger CRM");
						}
					}else
					{
						alert("Can not add Lead to vtiger CRM");
					}
				}
			
			}catch(errorObject)
			{
				alert(" Error while parsing response from the vtiger CRM server");
			}
		}

  
	}
	window.close();

}


//add contact to vtiger CRM

function add_contact_to_vtigercrm()
{

	
	//get the contact's information to save it to vtigerCRM
	var save_contact_lname = trim(document.getElementById("txtcontact_lname").value);
	var save_contact_phone = trim(document.getElementById("txtcontact_phone").value);
	var save_contact_mobile = trim(document.getElementById("txtcontact_mobile").value);
	var save_contact_email = trim(document.getElementById("txtcontact_email").value);
	var save_contact_street=trim(document.getElementById("txtcontact_street").value);
	var save_contact_city = trim(document.getElementById("txtcontact_city").value);
	var save_contact_state = trim(document.getElementById("txtcontact_state").value);
	var save_contact_country = trim(document.getElementById("txtcontact_country").value);
	var save_contact_code=trim(document.getElementById("txtcontact_code").value);
	 // check for Contact's Name is not empty      		
	if(save_contact_lname=="")
	{
		window.alert("Last Name is  mandatory");
		return;
	}
	else
	{
	
		
                if(save_contact_lname!="" && get_username!="")
		{
			//save information using SOAP method		
			try
			{
				var h=new Array();
				h[0] = new SOAPParameter(get_username,"user_name");
				h[1] = new SOAPParameter("","first_name");
				h[2] = new SOAPParameter(save_contact_lname,"last_name");
				h[3] = new SOAPParameter(save_contact_email,"email_address");
				h[4] = new SOAPParameter("","account_name");
				h[5] = new SOAPParameter("","salutation");
				h[6] = new SOAPParameter("","title");
				h[7] = new SOAPParameter(save_contact_mobile,"phone_mobile");
				h[8] = new SOAPParameter("","reports_to");
				h[9] = new SOAPParameter(save_contact_street,"primary_address_street");
				h[10] = new SOAPParameter(save_contact_city,"primary_address_city");
				h[11] = new SOAPParameter(save_contact_state,"primary_address_state");
				h[12] = new SOAPParameter(save_contact_code,"primary_address_postalcode");
				h[13] = new SOAPParameter(save_contact_country,"primary_address_country");
				h[14] = new SOAPParameter("","alt_address_city");
				h[15] = new SOAPParameter("","alt_address_street");
				h[16] = new SOAPParameter("","alt_address_state");
				h[17] = new SOAPParameter("","alt_address_postalcode");
				h[18] = new SOAPParameter("","alt_address_country");

				h[19] = new SOAPParameter(save_contact_phone,"office_phone");
				h[20] = new SOAPParameter("","home_phone");
				h[21] = new SOAPParameter("","fax");
				h[22] = new SOAPParameter("","department");
				h[23] = new SOAPParameter("","description")

		var headers = new Array();
		var call = new SOAPCall();
		const objects = "uri:track_emailRequest";
		call.transportURI = get_url + "/contactserialize.php";
		call.actionURI = objects + "/" + "create_contact";
		call.encode(0,"create_contact",objects,headers.length,headers,h.length,h);
			
				var oResp = call.invoke();
			}catch(errorObject)
			{
			       alert("Can not connect to the vtigerCRM server");
			}
			
		 	try
			{
				if(oResp.fault){
					alert("Error while receiving response from the vtigerCRM server");
				}
				else
				{
					if(oResp.body.childNodes.item(0).childNodes.item(0).hasChildNodes())
					{
						if(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue != "")
						{
							alert("Contact added to vtigerCRM successfully");
						}else
						{
							alert("Can not add Contact to vtigerCRM");
						}
					}else
					{
						alert("Can not add Contact to vtigerCRM");
					}
				}
			
			}catch(errorObject)
			{
				alert(" Error while parsing response from the vtigerCRM server");
			}
		}

  
	}
	window.close();

}


//add account to vtigerCRM
function add_account_to_vtigercrm()
{
	
	//get account's information to save it to vtigerCRM
	var save_account_name = trim(document.getElementById("txtaccount_name").value);
	var save_account_email = trim(document.getElementById("txtaccount_email").value);
	var save_account_phone = trim(document.getElementById("txtaccount_phone").value);
	var save_account_street=trim(document.getElementById("txtaccount_street").value);
	var save_account_city = trim(document.getElementById("txtaccount_city").value);
	var save_account_state = document.getElementById("txtaccount_state").value;
	var save_account_code=trim(document.getElementById("txtaccount_code").value);
	var save_account_country = trim(document.getElementById("txtaccount_country").value);
	


	//check for Account's Name is not Empty
	if(save_account_name=="")
	{
		window.alert("Account Name is  mandatory");
		return;
	}
	else
	{
	
		if(save_account_name!="")
		{
		//saving information using SOAP method	
               	var headers = new Array();
		var params = new Array(new SOAPParameter(get_username,"username"),new SOAPParameter(save_account_name,"accountname"),new SOAPParameter(save_account_email,"email"),new SOAPParameter(save_account_phone,"phone"),new SOAPParameter(save_account_street,"primary_address_street"),new SOAPParameter(save_account_city,"primary_address_city"),new SOAPParameter(save_account_state,"primary_address_state"),new SOAPParameter(save_account_code,"primary_address_postalcode"),new SOAPParameter(save_account_country,"primary_address_country"));
		var call = new SOAPCall();
		const objects = "uri:track_emailRequest";
		call.transportURI = get_url + "/contactserialize.php";
		call.actionURI = objects + "/" + "create_account";
		call.encode(0,"create_account",objects,headers.length,headers,params.length,params);
			try
			{
				var oResp = call.invoke();
			}catch(errorObject)
				{
					alert("Can not connect to the vtiger CRM server");
				}
			
		 	try
			{
				if(oResp.fault){
					alert("Error while receiving response from the vtiger CRM server");
				}
				else
				{
					if(oResp.body.childNodes.item(0).childNodes.item(0).hasChildNodes())
					{
						if(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue!="")
						{
							alert("Account added to vtiger CRM successfully");
						}else
						{
							alert("Can not add Account to vtiger CRM");
						}
					}else
					{
						alert("Can not add Account to vtiger CRM");
					}
				}
			
			}catch(errorObject)
			{
				alert(" Error while parsing response from the vtiger CRM server");
			}
		}

  
	}
	window.close();

}

//add ticket to vtigerCRM
function add_ticket_to_vtigercrm()
{
	//get ticket information to save it to vtigerCRM
	var save_ticket_title = trim(document.getElementById("txtticket_title").value);
	var save_ticket_priority = trim(document.getElementById("txtticket_priority").value);
	var save_ticket_severity = trim(document.getElementById("txtticket_severity").value);
	var save_ticket_category = trim(document.getElementById("txtticket_category").value);
	//check for Ticket title is not Empty
	if(save_ticket_title=="")
	{
		window.alert("Ticket Title is mandatory");
		return;
	}
	else
	{
		if(save_ticket_title!="")
		{
		//saving information using SOAP method	
                var headers = new Array();
		var params = new Array(new SOAPParameter(save_ticket_title,"title"),new SOAPParameter(document.getElementById("txtticket_description").value,"description"),new SOAPParameter(save_ticket_priority,"priority"),new SOAPParameter(save_ticket_severity,"severity"),new SOAPParameter(save_ticket_category,"category"),new SOAPParameter(get_username,"user_name"),new SOAPParameter("","parent_id"),new SOAPParameter("","product_id"));
		var call = new SOAPCall();
		const objects = "uri:track_emailRequest";
		call.transportURI = get_url + "/contactserialize.php";
		call.actionURI = objects + "/" + "create_ticket_from_toolbar";
		call.encode(0,"create_ticket_from_toolbar",objects,headers.length,headers,params.length,params);
			try
			{
				var oResp = call.invoke();
			}catch(errorObject)
				{
					alert("Can not connect to the vtiger CRM server");
				}
			
		 	try
			{
				if(oResp.fault){
					alert("Error while receiving response from the vtiger CRM server");
				}
				else
				{
					if(oResp.body.childNodes.item(0).childNodes.item(0).hasChildNodes())
					{
						if(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue!="")
						{
							alert("Ticket added to vtiger CRM successfully");
						}else
						{
							alert("Can not add Ticket to vtiger CRM");
						}
					}else
					{
						alert("Can not add Ticket to vtiger CRM");
					}
				}
			
			}catch(errorObject)
			{
				alert(" Error while parsing response from the vtiger CRM server");
			}
		}

  
	}
	window.close();

}

















//method to invoke enable_menubar().
function check_temp_variable()
{
	//if save button is pressed,call enable_menubar()
	if(vtiger_pref.getCharPref("Settings.Conf.temp_variable") == "true"){enable_menubar();}
	//if cancel button is pressed
	else{vtiger_pref.setCharPref("Settings.Conf.temp_variable","false");}
}




