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

$NAMESPACE = 'http://www.vtiger.com/vtigercrm/';
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
    array('user_name'=>'xsd:string','contacts'=>'tns:contact_detail_array'),
    array('return'=>'tns:contact_detail_array'),
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










function create_site_from_webform($username,$name,$url)
{
	global $log;
	global $adb;
	global $current_user;
	require_once("modules/Users/User.php");
	$seed_user=new User();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');
	require_once("modules/Portal/Portal.php");
	$adb->println("name url  >>>>>>>>>>".$name .' >>>>>>>>>>> ' .$url);
	if(isPermitted("Portals","EditView") == "yes")
	{
		$result = SavePortal($name,$url);

		$adb->println("Create New Portal from Web Form - Ends");

		if($result != '')
		return 'Thank you for your interest. Information has been successfully added as Portal';
		else
		return "Portal creation failed. Try again";
	}
	else
	{
		return $accessDenied;
	}
}


function create_rss_from_webform($username,$url)
{

	global $log;
	global $adb;
	global $current_user;
	require_once("modules/Users/User.php");
	$seed_user=new User();
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
				return "RSS creation failed. Try again";
			}
			else
			{
					return 'Thank you for your interest. Information has been successfully added as RSS.';
			}

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
	require_once("modules/Users/User.php");
	$seed_user=new User();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');
	$adb->println("Create New Note from Web Form - Starts");
	require_once("modules/Notes/Note.php");

	$focus = new Note();
	if(isPermitted("Notes","EditView") == "yes")
	{
		$focus->column_fields['title'] = $subject;
		$focus->column_fields['notecontent'] = $desc;

		$focus->save("Notes");

		$focus->retrieve_entity_info($focus->id,"Notes");

		$adb->println("Create New Note from Web Form - Ends");

		if($focus->id != '')
		return 'Thank you for your interest. Information has been successfully added as Note.';
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
	require_once("modules/Users/User.php");
	$seed_user=new User();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');
	$adb->println("Create New Product from Web Form - Starts");
	require_once("modules/Products/Product.php");
	if(isPermitted("Products","EditView") == "yes")
	{
		$focus = new Product();
		$focus->column_fields['productname'] = $productname;
		$focus->column_fields['productcode'] = $code;
		$focus->column_fields['website'] = $website;

		$adb->println("Values are  --------------->".$productname .'       '.$code .'           '.$website);
		$focus->save("Products");

		$focus->retrieve_entity_info($focus->id,"Products");

		$adb->println("Create New Product from Web Form - Ends");

		if($focus->id != '')
		return 'Thank you for your interest. Information has been successfully added as Product.';
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
	require_once("modules/Users/User.php");
	$seed_user=new User();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');
	$adb->println("Create New Vendor from Web Form - Starts");
	require_once("modules/Vendors/Vendor.php");
	if(isPermitted("Vendors","EditView" ) == "yes")
	{
		$focus = new Vendor();
		$focus->column_fields['vendorname'] = $vendorname;
		$focus->column_fields['email'] = $email;
		$focus->column_fields['phone'] = $phone;
		$focus->column_fields['website'] = $website;

		$focus->save("Vendors");

		$focus->retrieve_entity_info($focus->id,"Vendors");

		$adb->println("Create New Vendor from Web Form - Ends");

		if($focus->id != '')
		return 'Thank you for your interest. Information has been successfully added as Vendor.';
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
	require_once("modules/Users/User.php");
	$seed_user=new User();
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

		return $ticket->id;
	}
	else
	{
		return $accessDenied;
	}


}

function create_account($username,$accountname,$email,$phone,$primary_address_street,$primary_address_city,$primary_address_state,$primary_address_postalcode,$primary_address_country)
{
	global $current_user;
	require_once("modules/Users/User.php");
	$seed_user=new User();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve($user_id);
	require_once("modules/Accounts/Account.php");
	if(isPermitted("Accounts","EditView") == "yes")
	{
		$account=new Account();
		$account->column_fields['accountname']=$accountname;
		$account->column_fields['email1']=$email;
		$account->column_fields['phone']=$phone;
		$account->column_fields['bill_street']=$primary_address_street;
		$account->column_fields['bill_city']=$primary_address_city;
		$account->column_fields['bill_state']=$primary_address_state;
		$account->column_fields['bill_code']=$primary_address_postalcode;
		$account->column_fields['bill_country']=$primary_address_country;
		$account->column_fields['assigned_user_id']=$user_id;
		$account->save('Accounts');
		return $account->id;
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



function create_lead_from_webform($username,$lastname,$email,$phone,$company,$country,$description)
{

	global $log;
	global $adb;
	global $current_user;
	require_once("modules/Users/User.php");
	$seed_user=new User();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');
	$adb->println("Create New Lead from Web Form - Starts");
	require_once("modules/Leads/Lead.php");

	$focus = new Lead();
	if(isPermitted("Leads","EditView") == "yes")
	{
		$focus->column_fields['lastname'] = $lastname;
		$focus->column_fields['email'] = $email;
		$focus->column_fields['phone'] = $phone;
		$focus->column_fields['company'] = $company;
		$focus->column_fields['country'] = $country;
		$focus->column_fields['description'] = $description;
		$focus->save("Leads");
		$focus->retrieve_entity_info($focus->id,"Leads");
		$adb->println("Create New Lead from Web Form - Ends");
		if($focus->id != '')
		return 'Thank you for your interest. Information has been successfully added as Lead.';
		else
		return "Lead creation failed. Try again";
     }
	else
	{
		return $accessDenied;
	}


}

function create_contacts($user_name,$output_list)
{
	$counter=0;
	foreach($output_list as $contact)
	{

		if($contact[birthdate]=="4501-01-01")
		{
			$contact[birthdate] = "0000-00-00";
		}
			$id = create_contact1($user_name, $contact[first_name], $contact[last_name], $contact[email_address ],$contact[account_name ], $contact[salutation ], $contact[title], $contact[phone_mobile], $contact[reports_to],$contact[primary_address_street],$contact[primary_address_city],$contact[primary_address_state],$contact[primary_address_postalcode],$contact[primary_address_country],$contact[alt_address_city],$contact[alt_address_street],$contact[alt_address_state],$contact[alt_address_postalcode],$contact[alt_address_country],$contact[office_phone],$contact[home_phone],$contact[other_phone],$contact[fax],$contact[department],$contact[birthdate],$contact[assistant_name],$contact[assistant_phone]);

			$output_list[$counter] ['id']=$id;
			$counter++;
	}
	return array_reverse($output_list);
}

function create_contact1($user_name, $first_name, $last_name, $email_address ,$account_name , $salutation , $title, $phone_mobile, $reports_to,$primary_address_street,$primary_address_city,$primary_address_state,$primary_address_postalcode,$primary_address_country,$alt_address_city,$alt_address_street,$alt_address_state,$alt_address_postalcode,$alt_address_country,$office_phone,$home_phone,$other_phone,$fax,$department,$birthdate,$assistant_name,$assistant_phone,$description='')
{
	global $adb;
	global $current_user;
	require_once('modules/Users/User.php');
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);
	$adb->println("OUTLOOK: The user id is ".$current_user->id);
	
	require_once('modules/Contacts/Contact.php');
     if(isPermitted("Contacts","EditView") == "yes")
     {

	     $contact = new Contact();

	     $contact->column_fields[firstname]=$first_name;
	     $contact->column_fields[lastname]=$last_name;

	     $contact->column_fields[account_id]=retrieve_account_id($account_name,$user_id);// NULL value is not supported NEED TO FIX

	     $contact->column_fields[salutation]=$salutation;
	     // EMAIL IS NOT ADDED
	     $contact->column_fields[title]=$title;
	     $contact->column_fields[email]=$email_address;


	     $contact->column_fields[mobile]=$phone_mobile;
	     $contact->column_fields[reports_to_id] =retrievereportsto($reports_to,$user_id,$account_id);// NOT FIXED IN SAVEENTITY.PHP
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

	     //$contact->saveentity("Contacts");
	     $contact->save("Contacts");

	     return $contact->id;
     }
	else
	{
		return $accessDenied;
	}


}



$server->service($HTTP_RAW_POST_DATA); 
exit(); 
?>
