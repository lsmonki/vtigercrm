<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

require_once("config.php");
require_once('include/logging.php');
require_once('include/nusoap/nusoap.php');
require_once('include/database/PearDatabase.php');

$log = &LoggerManager::getLogger('firefoxlog');

$NAMESPACE = 'http://www.vtiger.com/products/crm';
$server = new soap_server;
$accessDenied = "You are not authorized for performing this action";
$server->configureWSDL('vtigersoap');

$server->register(
    'get_version',
      array('user_name'=>'xsd:string','password'=>'xsd:string'),
    array('return'=>'xsd:string'),
       $NAMESPACE);

$server->register(
	'create_lead_from_webform',
	array('username'=>'xsd:string', 
		'lastname'=>'xsd:string',
		'firstname'=>'xsd:string',
		'email'=>'xsd:string', 
		'phone'=>'xsd:string', 
		'company'=>'xsd:string', 
		'country'=>'xsd:string', 
		'description'=>'xsd:string'),
	array('return'=>'xsd:string'),
	$NAMESPACE);




$server->register(
	'create_site_from_webform',
	array('username'=>'xsd:string', 
		'portalname'=>'xsd:string',
		'portalurl'=>'xsd:string'), 
	array('return'=>'xsd:string'),
	$NAMESPACE);



$server->register(
	'create_rss_from_webform',
	array('username'=>'xsd:string', 
		'rssurl'=>'xsd:string'),
	array('return'=>'xsd:string'),
	$NAMESPACE);




	
$server->register(
   'create_contacts',
    array('user_name'=>'xsd:string','firstname'=>'xsd:string','lastname'=>'xsd:string','phone'=>'xsd:string','mobile'=>'xsd:string','email'=>'xsd:string','street'=>'xsd:string','city'=>'xsd:string','state'=>'xsd:string','country'=>'xsd:string','zipcode'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);



$server->register(
	'create_account',
    array('username'=>'xsd:string', 'accountname'=>'xsd:string', 'email'=>'xsd:string', 'phone'=>'xsd:string','$primary_address_street'=>'xsd:string','$primary_address_city'=>'xsd:string','$primary_address_state'=>'xsd:string','$primary_address_postalcode'=>'xsd:string','$primary_address_country'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

    
    $server->register(
	'create_ticket_from_toolbar',
	array('username'=>'xsd:string', 'title'=>'xsd:string','description'=>'xsd:string','priority'=>'xsd:string','severity'=>'xsd:string','category'=>'xsd:string','user_name'=>'xsd:string','parent_id'=>'xsd:string','product_id'=>'xsd:string'),
	array('return'=>'xsd:string'),
	$NAMESPACE);
 

$server->register(
	'create_vendor_from_webform',
	array('username'=>'xsd:string', 'vendorname'=>'xsd:string',
		'email'=>'xsd:string', 
		'phone'=>'xsd:string', 
		'website'=>'xsd:string'), 
	array('return'=>'xsd:string'),
	$NAMESPACE);


$server->register(
	'create_product_from_webform',
	array('username'=>'xsd:string', 'productname'=>'xsd:string',
		'productcode'=>'xsd:string', 
		'website'=>'xsd:string'), 
	array('return'=>'xsd:string'),
	$NAMESPACE);


$server->register(
	'create_note_from_webform',
	array('username'=>'xsd:string', 'title'=>'xsd:string',
		'notecontent'=>'xsd:string'), 
	array('return'=>'xsd:string'),
	$NAMESPACE);

$server->register(
    'LogintoVtigerCRM',
    array('user_name'=>'xsd:string','password'=>'xsd:string','version'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);
    
$server->register(
    'CheckLeadPermission',
    array('username'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
    'CheckContactPermission',
    array('username'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);
    
$server->register(
    'CheckAccountPermission',
    array('username'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
    'CheckTicketPermission',
    array('username'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
    'CheckVendorPermission',
    array('username'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
    'CheckProductPermission',
    array('username'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE); 

$server->register(
    'CheckNotePermission',
    array('username'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
    'CheckSitePermission',
    array('username'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
    'CheckRssPermission',
    array('username'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);


function CheckLeadPermission($username)
{
	global $current_user;
	require_once("modules/Users/Users.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');

	if(isPermitted("Leads","EditView") == "yes")
	{
		return "allowed";
	}else
	{
		return "denied";
	}
}

function CheckContactPermission($username)
{
	global $current_user;
	require_once("modules/Users/Users.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');

	if(isPermitted("Contacts","EditView") == "yes")
	{
		return "allowed";
	}else
	{
		return "denied";
	}
}

function CheckAccountPermission($username)
{
	global $current_user;
	require_once("modules/Users/Users.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');

	if(isPermitted("Accounts","EditView") == "yes")
	{
		return "allowed";
	}else
	{
		return "denied";
	}
}

function CheckTicketPermission($username)
{
	global $current_user;
	require_once("modules/Users/Users.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');

	if(isPermitted("HelpDesk","EditView") == "yes")
	{
		return "allowed";
	}else
	{
		return "denied";
	}
}

function CheckVendorPermission($username)
{
	global $current_user;
	require_once("modules/Users/Users.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');

	if(isPermitted("Vendors","EditView") == "yes")
	{
		return "allowed";
	}else
	{
		return "denied";
	}
}

function CheckProductPermission($username)
{
	global $current_user;
	require_once("modules/Users/Users.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');

	if(isPermitted("Products","EditView") == "yes")
	{
		return "allowed";
	}else
	{
		return "denied";
	}
}

function CheckNotePermission($username)
{
	global $current_user;
	require_once("modules/Users/Users.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');

	if(isPermitted("Notes","EditView") == "yes")
	{
		return "allowed";
	}else
	{
		return "denied";
	}
}

function CheckSitePermission($username)
{
	global $current_user;
	require_once("modules/Users/Users.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');

	if(isPermitted("Portal","EditView") == "yes")
	{
		return "allowed";
	}else
	{
		return "denied";
	}
}

function CheckRssPermission($username)
{
	global $current_user;
	require_once("modules/Users/Users.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');

	if(isPermitted("Rss","EditView") == "yes")
	{
		return "allowed";
	}else
	{
		return "denied";
	}
}

    
function create_site_from_webform($username,$portalname,$portalurl)
{
	global $log;
	global $adb;
	global $current_user;
	require_once("modules/Users/Users.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');
	require_once("modules/Portal/Portal.php");
	if(isPermitted("Portals","EditView") == "yes")
	{
		$result = SavePortal($portalname,$portalurl);

		$adb->println("Create New Portal from Web Form - Ends");

		if($result != '')
		  return 'URL added successfully';
		else
		  return "Portal creation failed. Try again";
	}
	else
	{
		return $accessDenied;
	}
}
function LogintoVtigerCRM($user_name,$password,$version)
{
	global $log;
	require_once('modules/Users/Users.php');
	include('vtigerversion.php');
	if($version != $vtiger_current_version)
	{
		return "VERSION";
	}
	$return_access = "FALSES";
	
	$objuser = new Users();
	
	if($password != "")
	{
		$objuser->column_fields['user_name'] = $user_name;
		$objuser->load_user($password);
		if($objuser->is_authenticated())
		{
			$return_access = "TRUES";
		}else
		{
			$return_access = "FALSES";
		}
	}else
	{
			//$server->setError("Invalid username and/or password");
			$return_access = "FALSES";
	}
	$objuser = $objuser;
	return $return_access;
}

function create_rss_from_webform($username,$url)
{

	global $log;
	global $adb;
	global $current_user;
	require_once("modules/Users/Users.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');
	require_once("modules/Rss/Rss.php");

	$oRss = new vtigerRSS();
	if(isPermitted("RSS","EditView") == "yes")
	{
		if($oRss->setRSSUrl($url))
		{
			if($oRss->saveRSSUrl($url) == false)
			{
				return "RSS feed addition failed. Try again";
			}
			else
			{
					return 'RSS feed added successfully.';
			}

	  }else
	  {
	     return "Not a valid RSS Feed or your Proxy Settings is not correct. Try again";
    }
	}
	else
	{
		return $accessDenied;
	}

}


function create_note_from_webform($username,$subject,$desc)
{
	global $log;
	global $adb;
	global $current_user;
	require_once("modules/Users/Users.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');
	$adb->println("Create New Note from Web Form - Starts");
	require_once("modules/Notes/Notes.php");

	$focus = new Notes();
	if(isPermitted("Notes","EditView") == "yes")
	{
		$focus->column_fields['notes_title'] = $subject;
		$focus->column_fields['notecontent'] = $desc;

		$focus->save("Notes");

		$focus->retrieve_entity_info($focus->id,"Notes");

		$adb->println("Create New Note from Web Form - Ends");

		if($focus->id != '')
		return 'Note added successfully.';
		else
		return "Note creation failed. Try again";
	}
	else
	{
		return $accessDenied;
	}

}

function create_product_from_webform($username,$productname,$code,$website)
{
	global $log;
	global $adb;
	global $current_user;
	require_once("modules/Users/Users.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');
	$adb->println("Create New Product from Web Form - Starts");
	
  require_once("modules/Products/Products.php");
	if(isPermitted("Products","EditView") == "yes")
	{
		$focus = new Products();
		$focus->column_fields['productname'] = $productname;
		$focus->column_fields['productcode'] = $code;
		$focus->column_fields['website'] = $website;
		$focus->save("Products");
		$adb->println("Create New Product from Web Form - Ends");

		if($focus->id != '')
		  return 'Product added successfully.';
		else
		  return "Product creation failed. Try again";
	}
	else
	{
		return $accessDenied;
	}

	
}

function create_vendor_from_webform($username,$vendorname,$email,$phone,$website)
{
	global $log;
	global $adb;
	global $current_user;
	require_once("modules/Users/Users.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');
	$adb->println("Create New Vendor from Web Form - Starts");
	require_once("modules/Vendors/Vendors.php");
	if(isPermitted("Vendors","EditView" ) == "yes")
	{
		$focus = new Vendors();
		$focus->column_fields['vendorname'] = $vendorname;
		$focus->column_fields['email'] = $email;
		$focus->column_fields['phone'] = $phone;
		$focus->column_fields['website'] = $website;

		$focus->save("Vendors");

		$focus->retrieve_entity_info($focus->id,"Vendors");

		$adb->println("Create New Vendor from Web Form - Ends");

		if($focus->id != '')
		return 'Vendor added successfully';
		else
		return "Vendor creation failed. Try again";
  }		
  else
	{
		return $accessDenied;
	}

	
}

function create_ticket_from_toolbar($username,$title,$description,$priority,$severity,$category,$user_name,$parent_id,$product_id)
{
	global $log;
	global $adb;
	global $current_user;
	require_once("modules/Users/Users.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');

	if(isPermitted("HelpDesk","EditView") == "yes")
	{

		$seed_ticket = new HelpDesk();
		$output_list = Array();

		require_once('modules/HelpDesk/HelpDesk.php');
		$ticket = new HelpDesk();

		$ticket->column_fields[ticket_title] = $title;
		$ticket->column_fields[description]=$description;
		$ticket->column_fields[ticketpriorities]=$priority;
		$ticket->column_fields[ticketseverities]=$severity;
		$ticket->column_fields[ticketcategories]=$category;
		$ticket->column_fields[ticketstatus]='Open';

		$ticket->column_fields[parent_id]=$parent_id;
		$ticket->column_fields[product_id]=$product_id;
		$ticket->column_fields[assigned_user_id]=$user_id;
		//$ticket->saveentity("HelpDesk");
		$ticket->save("HelpDesk");

		if($ticket->id != '')
      return "Ticket created successfully";
    else
      return "Error while creating Ticket.Try again";  
	}
	else
	{
		return $accessDenied;
	}


}

function create_account($username,$accountname,$email,$phone,$primary_address_street,$primary_address_city,$primary_address_state,$primary_address_postalcode,$primary_address_country)
{
	global $current_user,$log,$adb;
	$log->DEBUG("Entering with data ".$username.$accountname.$email.$phone."<br>".$primary_address_street.$primary_address_city.$primary_address_state.$primary_address_postalcode.$primary_address_country);
	require_once("modules/Users/Users.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id,'Users');
	require_once("modules/Accounts/Accounts.php");
	if(isPermitted("Accounts","EditView") == "yes")
	{
		$query = "SELECT accountname FROM vtiger_account,vtiger_crmentity WHERE accountname ='".$accountname."' and vtiger_account.accountid = vtiger_crmentity.crmid and vtiger_crmentity.deleted != 1";
		$result = $adb->query($query);
	        if($adb->num_rows($result) > 0)
		{
			return "Accounts";
			die;
		}
		$account=new Accounts();
		$account->column_fields['accountname']=$accountname;
		$account->column_fields['email1']=$email;
		$account->column_fields['phone']=$phone;
		$account->column_fields['bill_street']=$primary_address_street;
		$account->column_fields['bill_city']=$primary_address_city;
		$account->column_fields['bill_state']=$primary_address_state;
		$account->column_fields['bill_code']=$primary_address_postalcode;
		$account->column_fields['bill_country']=$primary_address_country;
		$account->column_fields['ship_street']=$primary_address_street;
		$account->column_fields['ship_city']=$primary_address_city;
		$account->column_fields['ship_state']=$primary_address_state;
		$account->column_fields['ship_code']=$primary_address_postalcode;
		$account->column_fields['ship_country']=$primary_address_country;
		$account->column_fields['assigned_user_id']=$user_id;
		$account->save('Accounts');
		if($account->id != '')
      return "Success";
    else
      return "Error while adding Account.Try again";  
	}
	else
	{
		return $accessDenied;
	}

}



function get_version($user_name, $password)
{
    return "5.0.0";
}



function create_lead_from_webform($username,$lastname,$email,$phone,$company,$country,$description,$firstname)
{

	global $log;
	global $adb;
	global $current_user;
	require_once("modules/Users/Users.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');
	$adb->println("Create New Lead from Web Form - Starts");
	require_once("modules/Leads/Leads.php");

	$focus = new Leads();
	if(isPermitted("Leads","EditView") == "yes")
	{
		$focus->column_fields['lastname'] = $lastname;
		$focus->column_fields['firstname'] = $firstname;
		$focus->column_fields['email'] = $email;
		$focus->column_fields['phone'] = $phone;
		$focus->column_fields['company'] = $company;
		$focus->column_fields['country'] = $country;
		$focus->column_fields['description'] = $description;
		$focus->column_fields['assigned_user_id'] = $user_id;
		$focus->save("Leads");
		$adb->println("Create New Lead from Web Form - Ends");
		if($focus->id != '')
		  return "Thank you for your interest. Information has been successfully added as Lead.";
		else
		  return "Lead creation failed. Try again";
  }
	else
	{
		return $accessDenied;
	}


}

function create_contacts($user_name,$firstname,$lastname,$phone,$mobile,$email,$street,$city,$state,$country,$zipcode)
{
	global $log;
	$log->DEBUG("Entering into create_contacts");
	$log->DEBUG($firstname."Firstisname");
	$birthdate = "";
	
	return create_contact1($user_name, $firstname, $lastname, $email,"", "","", $mobile, "",$street,$city,$state,$zipcode,$country,$city,$street,$state,$zipcode,$country,$phone,"","","","",$birthdate,"","");
	
}

function create_contact1($user_name, $first_name, $last_name, $email_address ,$account_name , $salutation , $title, $phone_mobile, $reports_to,$primary_address_street,$primary_address_city,$primary_address_state,$primary_address_postalcode,$primary_address_country,$alt_address_city,$alt_address_street,$alt_address_state,$alt_address_postalcode,$alt_address_country,$office_phone,$home_phone,$other_phone,$fax,$department,$birthdate,$assistant_name,$assistant_phone,$description='')
{
	global $adb,$log;
	global $current_user;
	require_once('modules/Users/Users.php');
	$seed_user = new Users();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	$current_user->retrieve_entity_info($user_id,'Users');

	require_once('modules/Contacts/Contacts.php');
	$log->DEBUG($first_name."First & Name");
  if(isPermitted("Contacts","EditView") == "yes")
  {
   $contact = new Contacts();
   $contact->column_fields[firstname]= $first_name;
   $contact->column_fields[lastname]= $last_name;
   //$contact->column_fields[account_id]=retrieve_account_id($account_name,$user_id);// NULL value is not supported NEED TO FIX
   $contact->column_fields[salutation]=$salutation;
   // EMAIL IS NOT ADDED
   $contact->column_fields[title]=$title;
   $contact->column_fields[email]=$email_address;
   $contact->column_fields[mobile]=$phone_mobile;
   //$contact->column_fields[reports_to_id] =retrievereportsto($reports_to,$user_id,$account_id);// NOT FIXED IN SAVEENTITY.PHP
   $contact->column_fields[mailingstreet]=$primary_address_street;
   $contact->column_fields[mailingcity]=$primary_address_city;
   $contact->column_fields[mailingcountry]=$primary_address_country;
   $contact->column_fields[mailingstate]=$primary_address_state;
   $contact->column_fields[mailingzip]=$primary_address_postalcode;
   $contact->column_fields[otherstreet]=$alt_address_street;
   $contact->column_fields[othercity]=$alt_address_city;
   $contact->column_fields[othercountry]=$alt_address_country;
   $contact->column_fields[otherstate]=$alt_address_state;
   $contact->column_fields[otherzip]=$alt_address_postalcode;
   $contact->column_fields[assigned_user_id]=$user_id;
   // new Fields
   $contact->column_fields[phone]= $office_phone;
   $contact->column_fields[homephone]= $home_phone;
   $contact->column_fields[otherphone]= $other_phone;
   $contact->column_fields[fax]= $fax;
   $contact->column_fields[department]=$department;
   $contact->column_fields[birthday]= getDisplayDate($birthdate);
   $contact->column_fields[assistant]= $assistant_name;
   $contact->column_fields[assistantphone]= $assistant_phone;
   $contact->column_fields[description]= $description;
   $contact->save("Contacts");
   if($contact->id != '')
      return 'Contact added successfully';
   else
      return "Contact creation failed. Try again";
  }
	else
	{
		return $accessDenied;
	}

}

$server->service($HTTP_RAW_POST_DATA); 
exit(); 
?>
