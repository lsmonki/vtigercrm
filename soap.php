<?php

require_once('include/logging.php');
require_once("config.php");
require_once('include/nusoap/nusoap.php');  
require_once('modules/Contacts/Contact.php');

global $HTTP_RAW_POST_DATA;

$log =& LoggerManager::getLogger('soap_contacts');


	// translate date sent from VB format 7/22/2004 9:36:31 AM
	// to yyyy-mm-dd 9:36:31 AM

//$date_vb = "7/22/2004 9:36:31 AM";
	
//$date_result = ereg_replace("([0-9]*)/([0-9]*)/([0-9]*)( .*$)", "\\3-\\1-\\2\\4", $date_vb);
//$date_result = ereg_replace("([0-9]*)-([0-9]*)-([0-9]*)(.*$)", "${2}-${0}-${1}${3}", $date_vb);
//exit();

//track_email("admin", "c72541e8-5d8d-718f-34eb-40fa3af93257", "7/20/2004 3:55:14 PM", "Test message subject", "This is a test message body");


// Temp should be in config.php
$NAMESPACE = 'http://www.sugarcrm.com/sugarcrm';
$server = new soap_server; 
$server->configureWSDL('sugarsoap');//, $NAMESPACE);

$server->wsdl->addComplexType(
    'contact_detail',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'email_address' => array('name'=>'email_address','type'=>'xsd:string'),
        'first_name' => array('name'=>'first_name','type'=>'xsd:string'),
        'last_name' => array('name'=>'last_name','type'=>'xsd:string'),
        'account_name' => array('name'=>'account_name','type'=>'xsd:string'),
        'id' => array('name'=>'id','type'=>'xsd:string'),
    )
);
    
$server->wsdl->addComplexType(
    'contact_detail_array',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:contact_detail[]')
    ),
    'tns:contact_detail'
);



$server->register(
    'create_session',
    array('user_name'=>'xsd:string','password'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
    'end_session',
    array('user_name'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
    'contact_by_email',
    array('email_address'=>'xsd:string'),
    array('return'=>'tns:contact_detail_array'),
    $NAMESPACE);

$server->register(
    'contact_by_search',
    array('name'=>'xsd:string'),
    array('return'=>'tns:contact_detail_array'),
    $NAMESPACE);

$server->register(
	'track_email',
    array('user_name'=>'xsd:string', 'contact_ids'=>'xsd:string', 'date_sent'=>'xsd:date', 'email_subject'=>'xsd:string', 'email_body'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
	'create_contact',
    array('user_name'=>'xsd:string', 'first_name'=>'xsd:string', 'last_name'=>'xsd:string', 'email_address'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);
	    

function create_session($user_name, $password)
{
	return "TempSessionID";	
}

function end_session($user_name)
{
	return "Seccess";	
}

function add_contacts_matching_email_address(&$output_list, $email_address, &$seed_contact)	
{
	global $log;
	$safe_email_address = addslashes($email_address);
	
	$where = "email1 like '$safe_email_address' OR email2 like '$safe_email_address'";
	$response = $seed_contact->get_list("first_name, last_name", $where, 0);
	$contactList = $response['list'];
	
//	$log->fatal("Retrieved the list");
	
	// create a return array of names and email addresses.
	foreach($contactList as $contact)
	{
//		$log->fatal("Adding another contact to the list: $contact-first_name");
		$output_list[] = Array("first_name"	=> $contact->first_name,
			"last_name" => $contact->last_name,
			"account_name" => $contact->account_name,
			"id" => $contact->id,
			"email_address" => $contact->email1);
	}
}




function contact_by_email($email_address) 
{ 
	global $log;
	//$log->fatal("Contact by email called with: $email_address");
	
	$seed_contact = new Contact();
	$output_list = Array();

	$email_address_list = explode("; ", $email_address);

	// remove duplicate email addresses
	$non_duplicate_email_address_list = Array();
	foreach( $email_address_list as $single_address)
	{
		// Check to see if the current address is a match of an existing address
		$found_match = false;
		foreach( $non_duplicate_email_address_list as $non_dupe_single)
		{
			if(strtolower($single_address) == $non_dupe_single)
			{
				$found_match = true;
				break;
			}
		}	
		
		if($found_match == false)
		{
			$non_duplicate_email_address_list[] = strtolower($single_address);
		}	
	}
	
	// now copy over the non-duplicated list as the original list.
	$email_address_list = &$non_duplicate_email_address_list;
	
	foreach( $email_address_list as $single_address)
	{
		add_contacts_matching_email_address($output_list, $single_address, $seed_contact);	
	}

	//to remove an erroneous compiler warning
	$seed_contact = $seed_contact;
	
	//$log->fatal("Contact by email returning");
	return $output_list;
}  

function contact_by_search($name) 
{ 
	global $log;
	$seed_contact = new Contact();
	$where = "first_name like '$name%' OR last_name like '$name%' OR email1 like '$name%' OR email2 like '$name%'";
	$response = $seed_contact->get_list("first_name, last_name", $where, 0);
	$contactList = $response['list'];
	//$row_count = $response['row_count'];
	
	$output_list = Array();
	
	//$log->fatal("Retrieved the list");
	
	// create a return array of names and email addresses.
	foreach($contactList as $contact)
	{
//		$log->fatal("Adding another contact to the list");
		$output_list[] = Array("first_name"	=> $contact->first_name,
			"last_name" => $contact->last_name,
			"account_name" => $contact->account_name,
			"id" => $contact->id,
			"email_address" => $contact->email1);
	}
	
	return $output_list;
}  

function track_email($user_name, $contact_ids, $date_sent, $email_subject, $email_body)
{
	global $log;
	
	//todo make the activity body not be html encoded
//	$log->fatal("In track email: username: $user_name contacts: $contact_ids date_sent: $date_sent"); // activity: $email_body");
	
	
	// translate date sent from VB format 7/22/2004 9:36:31 AM
	// to yyyy-mm-dd 9:36:31 AM
	$date_sent = ereg_replace("([0-9]*)/([0-9]*)/([0-9]*)( .*$)", "\\3-\\1-\\2\\4", $date_sent);
	
	
	
	require_once('modules/Users/User.php');
	$seed_user = new User();
	
//	$log->fatal("about to retrieve user id for $user_name");
	$user_id = $seed_user->retrieve_user_id($user_name);
//	$log->fatal("done retrieving user id for $user_id");
	$seed_user->retrieve($user_id);
	$current_user = $seed_user;
	
	require_once('modules/Emails/Email.php');
	
	$email = new Email();
	
	$email->description = $email_body;
	$email->name = $email_subject;
	$email->user_id = $user_id;
	$email->date_start = $date_sent;
	
	// Save one copy of the email message
	$email->save();
	
	// for each contact, add a link between the contact and the email message
	$contact_id_list = explode(";", $contact_ids);

	foreach( $contact_id_list as $contact_id)
	{
		$email->set_emails_contact_invitee_relationship($email->id, $contact_id);
	}
	
	return "Suceeded";
}

function create_contact($user_name, $first_name, $last_name, $email_address)
{
	global $log;
	
	//todo make the activity body not be html encoded
//	$log->fatal("In Create contact: username: $user_name first/last/email ($first_name, $last_name, $email_address)");
	
	require_once('modules/Users/User.php');
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	
	require_once('modules/Contacts/Contact.php');
	$contact = new Contact();
	$contact->first_name = $first_name;
	$contact->last_name = $last_name;
	$contact->email1 = $email_address;
	
	$contact->save();
	
	return "Suceeded";
}

//$log->fatal("In soap.php");

/* Begin the HTTP listener service and exit. */ 
$server->service($HTTP_RAW_POST_DATA); 

exit(); 



?>