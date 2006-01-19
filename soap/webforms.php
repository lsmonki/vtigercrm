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
require_once('modules/HelpDesk/HelpDesk.php');

$log = &LoggerManager::getLogger('webforms');

//$serializer = new XML_Serializer();
$NAMESPACE = 'http://www.vtigercrm.com/vtigercrm';
$server = new soap_server;

$server->configureWSDL('vtigersoap');


$server->register(
	'create_lead_from_webform',
	array(
		'lastname'=>'xsd:string',
		'email'=>'xsd:string', 
		'phone'=>'xsd:string', 
		'company'=>'xsd:string', 
		'country'=>'xsd:string', 
		'description'=>'xsd:string',
		'assigned_user_id'=>'xsd:string'
	     ),
	array('return'=>'xsd:string'),
	$NAMESPACE);

$server->register(
	'create_contact_from_webform',
	array(
		'first_name'=>'xsd:string',
		'last_name'=>'xsd:string',
		'email_address'=>'xsd:string',
		'home_phone'=>'xsd:string',
		'department'=>'xsd:string',
		'description'=>'xsd:string',
		'assigned_user_id'=>'xsd:string'
	     ),
	array('return'=>'xsd:string'),
	$NAMESPACE);


function create_lead_from_webform($lastname, $email, $phone, $company, $country, $description, $assigned_user_id)
{
	global $adb;
	$adb->println("Create New Lead from Web Form - Starts");

	if($assigned_user_id == '')
	{
		//if the user id is empty then assign it to the admin user
		$assigned_user_id = $adb->query_result($adb->query("select id from users where user_name='admin'"),0,'id');
	}

	require_once("modules/Leads/Lead.php");
	$focus = new Lead();
	$focus->column_fields['lastname'] = $lastname;
	$focus->column_fields['email'] = $email;
	$focus->column_fields['phone'] = $phone;
	$focus->column_fields['company'] = $company;
	$focus->column_fields['country'] = $country;
	$focus->column_fields['description'] = $description;
	$focus->column_fields['assigned_user_id'] = $assigned_user_id;

	$focus->save("Leads");
	//$focus->retrieve_entity_info($focus->id,"Leads");

	$adb->println("Create New Lead from Web Form - Ends");

	if($focus->id != '')
		$msg = 'Thank you for your interest. Information has been successfully added as Lead in vtigerCRM.';
	else
		$msg = "Lead creation failed. Please try again";

	return $msg;
}

function create_contact_from_webform($first_name, $last_name, $email_address, $home_phone, $department,$description, $assigned_user_id)
{
	global $adb;

	$adb->println("Create New Contact from Web Form - Starts");
	if($assigned_user_id == '')
	{
		//if the user id is empty then assign it to the admin user
		$assigned_user_id = $adb->query_result($adb->query("select id from users where user_name='admin'"),0,'id');
	}

	require_once('modules/Contacts/Contact.php');
	$focus = new Contact();

	$focus->column_fields['firstname'] = $first_name;
	$focus->column_fields['lastname'] = $last_name;
	$focus->column_fields['email'] = $email_address;
	$focus->column_fields['homephone'] = $home_phone;
	$focus->column_fields['department'] = $department;
	$focus->column_fields['description'] = $description;
	$focus->column_fields['assigned_user_id'] = $assigned_user_id;

	$focus->save("Contacts");
	//$focus->retrieve_entity_info($focus->id,"Contacts");

	$adb->println("Create New Contact from Web Form - Ends");

	if($focus->id != '')
		$msg = 'Thank you for your interest. Information has been successfully added as Contact in vtigerCRM.';
	else
		$msg = "Contact creation failed. Please try again";

	return $msg;
}




//$log->fatal("In soap.php");

/* Begin the HTTP listener service and exit. */ 
$server->service($HTTP_RAW_POST_DATA); 

exit(); 



?>
