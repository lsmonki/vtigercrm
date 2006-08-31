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
	vtiger_pref.setCharPref("Settings.Conf.temp_variable","true");
	if(save_username=="" || save_password=="" || save_url=="")
	{
		
		window.alert("All fields are mandatory");
		vtiger_pref.setCharPref("Settings.Conf.temp_variable","false");
		return;
	}else
	{
			vtiger_pref.setCharPref("Settings.Conf.vtiger_username",save_username);
			vtiger_pref.setCharPref("Settings.Conf.vtiger_password",save_password);
			vtiger_pref.setCharPref("Settings.Conf.vtiger_url",save_url);
	}
	window.close();                                                    
}
function logout()
{
		document.getElementById("VTIGER-LEAD").disabled=true;
		document.getElementById("VTIGER-CONTACT").disabled=true;
		document.getElementById("VTIGER-ACCOUNT").disabled=true;
		document.getElementById("VTIGER-TICKET").disabled=true;
		document.getElementById("VTIGER-VENDOR").disabled=true;
		document.getElementById("VTIGER-PRODUCT").disabled=true;
		document.getElementById("VTIGER-NOTE").disabled=true;
		document.getElementById("VTIGER-RSS").disabled=true;
		document.getElementById("VTIGER-SITE").disabled=true;

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
	 const objects = "uri:LogintoVtigerCRMRequest";
	 call.transportURI = get_url + "/vtigerservice.php?service=firefox";
	 call.actionURI = objects + "/" + "LogintoVtigerCRM";
	 call.encode(0,"LogintoVtigerCRM",objects,headers.length,headers,params.length,params);
	 var oResp = call.invoke();
	}catch(errorObject)
	{
		window.alert("Can not connect to the vtiger CRM server");
	}
			
		 	try
			{
				if(oResp.fault){
					window.alert("Error while receiving response from the vtiger CRM server");
				}
				else
				{
					if(oResp.body.childNodes.item(0).childNodes.item(0).hasChildNodes())
					{
						if(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue == "TRUE")
						{
					    if(getPermission('Lead') == "allowed")
							   document.getElementById("VTIGER-LEAD").disabled=false;
							else
							   document.getElementById("VTIGER-LEAD").disabled=true;  
							if(getPermission('Contact') == "allowed")
							   document.getElementById("VTIGER-CONTACT").disabled=false;
							else
							   document.getElementById("VTIGER-CONTACT").disabled=true;
							if(getPermission('Account') == "allowed")
							   document.getElementById("VTIGER-ACCOUNT").disabled=false;
							else
							   document.getElementById("VTIGER-ACCOUNT").disabled=true;
							if(getPermission('Ticket') == "allowed")
							   document.getElementById("VTIGER-TICKET").disabled=false;
							else
							   document.getElementById("VTIGER-TICKET").disabled=true;
							if(getPermission('Vendor') == "allowed")
							   document.getElementById("VTIGER-VENDOR").disabled=false;
							else
							   document.getElementById("VTIGER-VENDOR").disabled=true;
							if(getPermission('Product') == "allowed")
							   document.getElementById("VTIGER-PRODUCT").disabled=false;
							else
                 document.getElementById("VTIGER-PRODUCT").disabled=true;
							if(getPermission('Note') == "allowed")
							   document.getElementById("VTIGER-NOTE").disabled=false;
							else
							   document.getElementById("VTIGER-NOTE").disabled=true;
							if(getPermission('Rss') == "allowed")
							   document.getElementById("VTIGER-RSS").disabled=false;
							else
							   document.getElementById("VTIGER-RSS").disabled=true;
							if(getPermission('Site') == "allowed")
							   document.getElementById("VTIGER-SITE").disabled=false;
							else
							   document.getElementById("VTIGER-SITE").disabled=true;
						   						
						}else
						{
							
							document.getElementById("VTIGER-LEAD").disabled=true;
							document.getElementById("VTIGER-CONTACT").disabled=true;
							document.getElementById("VTIGER-ACCOUNT").disabled=true;
							document.getElementById("VTIGER-TICKET").disabled=true;
							document.getElementById("VTIGER-VENDOR").disabled=true;
							document.getElementById("VTIGER-PRODUCT").disabled=true;
							document.getElementById("VTIGER-NOTE").disabled=true;
							document.getElementById("VTIGER-RSS").disabled=true;
							document.getElementById("VTIGER-SITE").disabled=true;
							window.alert("Invalid Username or Password");
						    	

						}
					}else
					{
						window.alert("Can not connect to vtiger CRM");
						document.getElementById("VTIGER-LEAD").disabled=true;
						document.getElementById("VTIGER-CONTACT").disabled=true;
						document.getElementById("VTIGER-ACCOUNT").disabled=true;
						document.getElementById("VTIGER-TICKET").disabled=true;
						document.getElementById("VTIGER-VENDOR").disabled=true;
						document.getElementById("VTIGER-PRODUCT").disabled=true;
						document.getElementById("VTIGER-NOTE").disabled=true;
						document.getElementById("VTIGER-RSS").disabled=true;
						document.getElementById("VTIGER-SITE").disabled=true;

					}
				}
			
			}catch(errorObject)
			{
			   window.alert("Can not connect to the vtiger CRM server");
				//alert(" Error while parsing response from the vtiger CRM server");
			}
	

}

function getPermission(module)
{
  try
	{
   var functionname = 'Check'+module+'Permission';
	 var headers = new Array();
	 var params = new Array(new SOAPParameter(get_username,"user_name"),new SOAPParameter(get_password,"password"));
	 var call = new SOAPCall();
	 const objects = "uri:"+functionname+"Request";
	 call.transportURI = get_url + "/vtigerservice.php?service=firefox";
	 call.actionURI = objects + "/" + functionname;
	 call.encode(0,functionname,objects,headers.length,headers,params.length,params);
	 
	 var oResp = call.invoke();
	}catch(errorObject)
	{
		window.alert("Can not connect to the vtiger CRM server");
	}
			
		 	try
			{
				if(oResp.fault){
					window.alert("Error while receiving response from the vtiger CRM server");
				}
				else
				{
					if(oResp.body.childNodes.item(0).childNodes.item(0).hasChildNodes())
					{
					   return oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue;
          }
        }
      }catch(errorObject)
      {
        window.alert("Can not connect to the vtiger CRM server");
      }
    return "false";
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
		window.alert("Configure login details to access the vtiger CRM");
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
	}else if(save_lead_name=="")
  {
    window.alert("Last Name is mandatory");return;
  }else if(save_lead_company=="")
  {
    window.alert("Company Name is mandatory");return;
  }
	else
	{
			
    if(save_lead_name!="" && save_lead_company!="")
		{
			//SOAP method to save information in vtigerCRM
			var description=document.getElementById("txtdescription").value;
			var headers = new Array();
			var params = new Array(new SOAPParameter(get_username,"username"),new SOAPParameter(save_lead_name,"lastname"),new SOAPParameter(save_lead_email,"email"),new SOAPParameter(save_lead_phone,"phone"),new SOAPParameter(save_lead_company,"company"),new SOAPParameter("","country"),new SOAPParameter(document.getElementById("txtdescription").value,"description"));
		  var call = new SOAPCall();
		  const objects = "uri:create_lead_from_webformRequest";
			call.transportURI = get_url + "/vtigerservice.php?service=firefox";
		  call.actionURI = objects + "/" + "create_lead_from_webform";
		  call.encode(0,"create_lead_from_webform",objects,headers.length,headers,params.length,params);
			try
			{
				var oResp = call.invoke();
			}catch(errorObject)
			{
					window.alert("Can not connect to the vtiger CRM server");
			}
			
		 	try
			{
				if(oResp.fault){
					window.alert("Error while receiving response from the vtiger CRM server");
				}
				else
				{
					if(oResp.body.childNodes.item(0).childNodes.item(0).hasChildNodes())
					{
						if(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue != "")
						{
							window.alert(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue);
						}else
						{
							window.alert("Can not add Lead to vtiger CRM");
						}
					}else
					{
						window.alert("Can not add Lead to vtiger CRM");
					}
				}
			
			}catch(errorObject)
			{
				window.alert(" Error while parsing response from the vtiger CRM server");
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
	 	    var headers = new Array();
		    var call = new SOAPCall();
		    var params = new Array(new SOAPParameter(get_username,"user_name"),new SOAPParameter(save_contact_lname,"lastname"),new SOAPParameter(save_contact_phone,"phone"),new SOAPParameter(save_contact_mobile,"mobile"),new SOAPParameter(save_contact_email,"email"),new SOAPParameter(save_contact_street,"street"),new SOAPParameter(save_contact_city,"city"),new SOAPParameter(save_contact_state,"state"),new SOAPParameter(save_contact_country,"country"),new SOAPParameter(save_contact_code,"zipcode"));
		    const objects = "uri:create_contactsRequest";
		    call.transportURI = get_url + "/vtigerservice.php?service=firefox";
		    call.actionURI = objects + "/" + "create_contacts";
		    call.encode(0,"create_contacts",objects,headers.length,headers,params.length,params);
				var oResp = call.invoke();
			}catch(errorObject)
			{
			       window.alert("Can not connect to the vtigerCRM server");
			}
			
		 	try
			{
				if(oResp.fault)
        {
					window.alert("Error while receiving response from the vtigerCRM server");
			  }
				else
				{
					if(oResp.body.childNodes.item(0).childNodes.item(0).hasChildNodes())
					{
						if(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue != "")
						{
							window.alert(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue);
						}else
						{
							window.alert("Can not add Contact to vtigerCRM");
						}
					}else
					{
						window.alert("Can not add Contact to vtigerCRM");
					}
				}
			
			}catch(errorObject)
			{
				window.alert(" Error while parsing response from the vtigerCRM server");
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
		const objects = "uri:create_accountRequest";
		call.transportURI = get_url + "/vtigerservice.php?service=firefox";
		call.actionURI = objects + "/" + "create_account";
		call.encode(0,"create_account",objects,headers.length,headers,params.length,params);
		try
		{
				var oResp = call.invoke();
		}catch(errorObject)
		{
			 window.alert("Can not connect to the vtiger CRM server");
		}
		
		try
		{
				if(oResp.fault)
        {
					window.alert("Error while receiving response from the vtiger CRM server");
				}
				else
				{
					if(oResp.body.childNodes.item(0).childNodes.item(0).hasChildNodes())
					{
						if(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue != "")
						{
							window.alert(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue);
						}else
						{
							window.alert("Can not add Account to vtiger CRM");
						}
					}else
					{
						window.alert("Can not add Account to vtiger CRM");
					}
				}
			
			}catch(errorObject)
			{
				window.alert(" Error while parsing response from the vtiger CRM server");
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
		var params = new Array(new SOAPParameter(get_username,"username"),new SOAPParameter(save_ticket_title,"title"),new SOAPParameter(document.getElementById("txtticket_description").value,"description"),new SOAPParameter(save_ticket_priority,"priority"),new SOAPParameter(save_ticket_severity,"severity"),new SOAPParameter(save_ticket_category,"category"),new SOAPParameter(get_username,"user_name"),new SOAPParameter("","parent_id"),new SOAPParameter("","product_id"));
		var call = new SOAPCall();
		const objects = "uri:create_ticket_from_toolbarRequest";
		call.transportURI = get_url + "/vtigerservice.php?service=firefox";
		call.actionURI = objects + "/" + "create_ticket_from_toolbar";
		call.encode(0,"create_ticket_from_toolbar",objects,headers.length,headers,params.length,params);
			try
			{
				var oResp = call.invoke();
			}catch(errorObject)
				{
					window.alert("Can not connect to the vtiger CRM server");
				}
			
		 	try
			{
				if(oResp.fault){
					window.alert("Error while receiving response from the vtiger CRM server");
				}
				else
				{
					if(oResp.body.childNodes.item(0).childNodes.item(0).hasChildNodes())
					{
						if(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue!="")
						{
							window.alert(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue);
						}else
						{
							window.alert("Can not add Ticket to vtiger CRM");
						}
					}else
					{
						window.alert("Can not add Ticket to vtiger CRM");
					}
				}
			
			}catch(errorObject)
			{
				window.alert(" Error while parsing response from the vtiger CRM server");
			}
		}

  
	}
	window.close();

}

//method to invoke enable_menubar().
function check_temp_variable()
{
	//if save button is pressed,call enable_menubar()
	if(vtiger_pref.getCharPref("Settings.Conf.temp_variable") == "true")
	{
		enable_menubar();
	}
	//if cancel button is pressed
	else
	{
		vtiger_pref.setCharPref("Settings.Conf.temp_variable","false");
	  //window.alert('unable to set the menu bar')
	}
}

//add vendor to vtigerCRM

function add_vendor_to_vtigercrm()
{
	//get Lead's information to save it in vtigerCRM
	var save_vendor_name = trim(document.getElementById("txtvendorname").value);
	var save_vendor_email = trim(document.getElementById("txtvendoremail").value);
	var save_vendor_phone = trim(document.getElementById("txtvendorphone").value);
	var save_vendor_website = trim(document.getElementById("txtvendorwebsite").value);
	//check for Vendor's Name 
	if(save_vendor_name=="")
	{
		window.alert("Vendor Name is mandatory");
		return;
	}
      if(save_vendor_name!="")
		{
			//SOAP method to save information in vtigerCRM
						var headers = new Array();
			var params = new Array(new SOAPParameter(get_username,"username"),new SOAPParameter(save_vendor_name,"vendorname"),new SOAPParameter(save_vendor_email,"email"),new SOAPParameter(save_vendor_phone,"phone"),new SOAPParameter(save_vendor_website,"company"));
		var call = new SOAPCall();
		const objects = "uri:create_vendor_from_webformRequest";
			call.transportURI = get_url + "/vtigerservice.php?service=firefox";
		call.actionURI = objects + "/" + "create_vendor_from_webform";
		call.encode(0,"create_vendor_from_webform",objects,headers.length,headers,params.length,params);
			try
			{
				var oResp = call.invoke();
			}catch(errorObject)
				{
					window.alert("Can not connect to the vtiger CRM server");
				}
			
		 	try
			{
				if(oResp.fault){
					window.alert("Error while receiving response from the vtiger CRM server");
				}
				else
				{
					if(oResp.body.childNodes.item(0).childNodes.item(0).hasChildNodes())
					{
						if(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue!="")
						{
							window.alert(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue);
						}else
						{
							window.alert("Can not add Vendor to vtiger CRM");
						}
					}else
					{
						window.alert("Can not add Vendor to vtiger CRM");
					}
				}
			
			}catch(errorObject)
			{
				window.alert(" Error while parsing response from the vtiger CRM server");
				window.alert(errorObject.description);
			}
		}

  
	window.close();
}
//add vendor to vtigerCRM

function add_product_to_vtigercrm()
{
	//get Product's information to save it in vtigerCRM
	var save_product_name = trim(document.getElementById("txtproductname").value);
	var save_product_code = trim(document.getElementById("txtproductcode").value);
	var save_product_website = trim(document.getElementById("txtproductwebsite").value);
	//check for Product's Name 
	if(save_product_name=="")
	{
		window.alert("Product Name is mandatory");
		return;
	}
      if(save_product_name!="")
		{
			//SOAP method to save information in vtigerCRM
			var headers = new Array();
			var params = new Array(new SOAPParameter(get_username,"username"),new SOAPParameter(save_product_name,"productname"),new SOAPParameter(save_product_code,"productcode"),new SOAPParameter(save_product_website,"website"));
		var call = new SOAPCall();
		const objects = "uri:create_product_from_webformRequest";
			call.transportURI = get_url + "/vtigerservice.php?service=firefox";
		call.actionURI = objects + "/" + "create_product_from_webform";
		call.encode(0,"create_product_from_webform",objects,headers.length,headers,params.length,params);
			try
			{
				var oResp = call.invoke();
			}catch(errorObject)
				{
					window.alert("Can not connect to the vtiger CRM server");
				}
			
		 	try
			{
				if(oResp.fault){
					window.alert("Error while receiving response from the vtiger CRM server");
				}
				else
				{
					if(oResp.body.childNodes.item(0).childNodes.item(0).hasChildNodes())
					{
						if(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue!="")
						{
							window.alert(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue);
						}else
						{
							window.alert("Can not add Product to vtiger CRM");
						}
					}else
					{
						window.alert("Can not add Product to vtiger CRM");
					}
				}
			
			}catch(errorObject)
			{
				window.alert(" Error while parsing response from the vtiger CRM server");
			}
		}

  
	window.close();
}



//add Note to vtigerCRM

function add_note_to_vtigercrm()
{
	//get Lead's information to save it in vtigerCRM
	var save_note_subject = trim(document.getElementById("txtnotesubject").value);
	var save_note_description = trim(document.getElementById("txtnotedescription").value);
	//check for Vendor's Name 
	if(save_note_subject=="")
	{
		window.alert("Note Subject is mandatory");
		return;
	}
      if(save_note_subject!="")
		{
			//SOAP method to save information in vtigerCRM
						var headers = new Array();
			var params = new Array(new SOAPParameter(get_username,"username"),new SOAPParameter(save_note_subject,"title"),new SOAPParameter(save_note_description,"notecontent"));
		var call = new SOAPCall();
		const objects = "uri:create_note_from_webformRequest";
		call.transportURI = get_url + "/vtigerservice.php?service=firefox";
		call.actionURI = objects + "/" + "create_note_from_webform";
		call.encode(0,"create_note_from_webform",objects,headers.length,headers,params.length,params);
			try
			{
				var oResp = call.invoke();
			}catch(errorObject)
				{
					window.alert("Can not connect to the vtiger CRM server");
				}
			
		 	try
			{
				if(oResp.fault){
					window.alert("Error while receiving response from the vtiger CRM server");
				}
				else
				{
					if(oResp.body.childNodes.item(0).childNodes.item(0).hasChildNodes())
					{
						if(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue!="")
						{
							window.alert(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue);
						}else
						{
							window.alert("Can not add Note to vtiger CRM");
						}
					}else
					{
						window.alert("Can not add Note to vtiger CRM");
					}
				}
			
			}catch(errorObject)
			{
				window.alert(" Error while parsing response from the vtiger CRM server");
			}
		}

  
	window.close();
}
//add Site to vtigerCRM

function add_site_to_vtigercrm()
{
	var save_portal_name = trim(document.getElementById("txtsitename").value);
	var save_portal_url = trim(document.getElementById("txtsiteurl").value);
	//check for Site's URL
	if(save_portal_url=="" && save_portal_name == '')
	{
		window.alert("Site name and Site Url are mandatory");
		return;
  }
	else if(save_portal_url =="")
	{
		window.alert("Site url is mandatory");
		return;
	}else if(save_portal_name == '')
	{
	  window.alert("Site Name is mandatory");
		return;
  }
  if(save_portal_url!="")
	{
		//SOAP method to save information in vtigerCRM
		var headers = new Array();
		var params = new Array(new SOAPParameter(get_username,"username"),new SOAPParameter(save_portal_name,"portalname"),new SOAPParameter(save_portal_url,"portalurl"));
		var call = new SOAPCall();
		const objects = "uri:create_site_from_webformRequest";
		call.transportURI = get_url + "/vtigerservice.php?service=firefox";
		call.actionURI = objects + "/" + "create_site_from_webform";
		call.encode(0,"create_site_from_webform",objects,headers.length,headers,params.length,params);
		try
		{
				var oResp = call.invoke();
		}catch(errorObject)
		{
					window.alert("Can not connect to the vtiger CRM server");
		}
			
	 	try
		{
			if(oResp.fault){
				window.alert("Error while receiving response from the vtiger CRM server");
			}
			else
			{
					if(oResp.body.childNodes.item(0).childNodes.item(0).hasChildNodes())
					{
						if(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue!="")
						{
							window.alert(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue);
						}else
						{
							window.alert("Can not add Site to vtiger CRM");
						}
					}else
					{
						window.alert("Can not add Site to vtiger CRM");
					}
			}
			
		}catch(errorObject)
		{
				window.alert(" Error while parsing response from the vtiger CRM server");
		}
	}
 
	window.close();
}

//add RSS to vtigerCRM

function add_rss_to_vtigercrm()
{
	var save_rss_url = trim(document.getElementById("txtrssurl").value);
	//check for RSS URL 
	if(save_rss_url=="")
	{
		window.alert("RSS URL is mandatory");
		return;
	}
  if(save_rss_url!="")
	{
			//SOAP method to save information in vtigerCRM
			var headers = new Array();
			var params = new Array(new SOAPParameter(get_username,"username"),new SOAPParameter(save_rss_url,"rssurl"));
		  var call = new SOAPCall();
		  const objects = "uri:track_emailRequest";
			call.transportURI = get_url + "/vtigerservice.php?service=firefox";
		  call.actionURI = objects + "/" + "create_rss_from_webform";
		  call.encode(0,"create_rss_from_webform",objects,headers.length,headers,params.length,params);
			try
			{
				var oResp = call.invoke();
			}catch(errorObject)
			{
					window.alert("Can not connect to the vtiger CRM server");
			}
			
		 	try
			{
				if(oResp.fault){
					window.alert("Error while receiving response from the vtiger CRM server");
				}
				else
				{
					if(oResp.body.childNodes.item(0).childNodes.item(0).hasChildNodes()){

						if(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue != "")
						{
							window.alert(oResp.body.childNodes.item(0).childNodes.item(0).childNodes.item(0).nodeValue);
						}else
						{
							window.alert("Can not add RSS to vtiger CRM");
						}
					}else
					{
						window.alert("Can not add RSS to vtiger CRM");
					}
				}
			
			}catch(errorObject)
			{
				window.alert(" Error while parsing response from the vtiger CRM server. Kindly check your proxy settings");
			}
		}

  
	window.close();
}
