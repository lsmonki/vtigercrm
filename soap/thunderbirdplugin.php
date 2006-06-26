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

$log = &LoggerManager::getLogger('thunderbirdplugin');

$NAMESPACE = 'http://www.vtiger.com/vtigercrm/';
$server = new soap_server;

$server->configureWSDL('vtigersoap');

$server->register(
    'contact_by_email',
    array('user_name'=>'xsd:string','email_address'=>'xsd:string'),
    array('return'=>'tns:contact_detail_array'),
    $NAMESPACE);


$server->register(
	'track_email',
    array('user_name'=>'xsd:string', 'contact_ids'=>'xsd:string', 'date_sent'=>'xsd:date', 'email_subject'=>'xsd:string', 'email_body'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

    
    
    
    
    
function track_email($user_name, $contact_ids, $date_sent, $email_subject, $email_body)
{
	global $current_user;
	require_once('modules/Users/User.php');
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);
	
	$date_sent = getDisplayDate($date_sent);

	require_once('modules/Emails/Email.php');
	
	$email = new Email();

	$email_body = str_replace("'", "''", $email_body);
	$email_subject = str_replace("'", "''", $email_subject);
	
	//fixed subject issue 9/6/05
	$email->column_fields[activitytype]='Emails';
	$email->column_fields[subject]=$email_subject;
	$email->column_fields[assigned_user_id] = $user_id;
	$email->column_fields[date_start] = $date_sent;
	$email->column_fields[description]  = $email_body;

	
	// Save one copy of the email message
	//$email->saveentity("Emails");
	$email->save("Emails");


	
	// for each contact, add a link between the contact and the email message
	$contact_id_list = explode(";", $contact_ids);

	foreach( $contact_id_list as $contact_id)
	{
		$email->set_emails_contact_invitee_relationship($email->id, $contact_id);
		$email->set_emails_se_invitee_relationship($email->id,$contact_id);
	}
	$email->set_emails_user_invitee_relationship($email->id, $user_id);
	
	return $email->id;
}




function contact_by_email($user_name,$email_address)
{
	$seed_contact = new Contact();
	$output_list = Array();
	{  
		$response = $seed_contact->get_contacts1($user_name,$email_address);
		$contactList = $response['list'];


		foreach($contactList as $contact)
		{

			$output_list[] = Array("first_name"    => $contact[first_name],
			"last_name" => $contact[last_name],
			"primary_address_city" => $contact[primary_address_city],
			"account_name" => $contact[account_name],
			"account_id"=> $contact[account_id],
			"id" => $contact[id],
			"email_address" => $contact[email1],
			"salutation"=>$contact[salutation],
			"title"=>$contact[title],
			"phone_mobile"=>$contact[phone_mobile],
			"reports_to"=>$contact[reports_to_name],
			"primary_address_street"=>$contact[primary_address_street],
			"primary_address_city"=>$contact[primary_address_city],
			"primary_address_state"=>$contact[primary_address_state] ,
			"primary_address_postalcode"=>$contact[primary_address_postalcode],
			"primary_address_country"=>$contact[primary_address_country],
			"alt_address_city"=>$contact[alt_address_city],
			"alt_address_street"=>$contact[alt_address_street],
			"alt_address_city"=>$contact[alt_address_city],
			"alt_address_state"=>$contact[alt_address_state],
			"alt_address_postalcode"=>$contact[alt_address_postalcode],
			"alt_address_country"=>$contact[alt_address_country],);
		}
	}  

		//to remove an erroneous compiler warning
		$seed_contact = $seed_contact;
		return $output_list;
}


$server->service($HTTP_RAW_POST_DATA); 
exit(); 
?>
