<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/

if (substr(phpversion(), 0, 1) == "5") {
	ini_set("zend.ze1_compatibility_mode", "1");
}

require_once('include/logging.php');
require_once("config.php");
require_once('include/nusoap/nusoap.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Cases/Case.php');

global $HTTP_RAW_POST_DATA;

$log =& LoggerManager::getLogger('soap_contacts');

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
        'name1' => array('name'=>'name1','type'=>'xsd:string'),
        'name2' => array('name'=>'name2','type'=>'xsd:string'),
        'association' => array('name'=>'association','type'=>'xsd:string'),
        'id' => array('name'=>'id','type'=>'xsd:string'),
        'msi_id' => array('name'=>'id','type'=>'xsd:string'),
        'type' => array('name'=>'type','type'=>'xsd:string'),
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
    array('user_name'=>'xsd:string','password'=>'xsd:string', 'email_address'=>'xsd:string'),
    array('return'=>'tns:contact_detail_array'),
    $NAMESPACE);


$server->register(
    'search',
    array('user_name'=>'xsd:string','password'=>'xsd:string', 'name'=>'xsd:string'),
    array('return'=>'tns:contact_detail_array'),
    $NAMESPACE);

$server->register(
	'track_email',
    array('user_name'=>'xsd:string','password'=>'xsd:string','parent_id'=>'xsd:string', 'contact_ids'=>'xsd:string', 'date_sent'=>'xsd:date', 'email_subject'=>'xsd:string', 'email_body'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
	'create_contact',
    array('user_name'=>'xsd:string','password'=>'xsd:string', 'first_name'=>'xsd:string', 'last_name'=>'xsd:string', 'email_address'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
	'create_account',
    array('user_name'=>'xsd:string','password'=>'xsd:string', 'name'=>'xsd:string', 'phone'=>'xsd:string', 'website'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
	'create_opportunity',
    array('user_name'=>'xsd:string','password'=>'xsd:string', 'name'=>'xsd:string', 'amount'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);


$server->register(
	'create_case',
    array('user_name'=>'xsd:string','password'=>'xsd:string', 'name'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);


function create_session($user_name, $password)
{
	if(validate_user($user_name, $password))
	{
		return "Success";
	}

	return "Failed";
}

function end_session($user_name)
{
	return "Success";
}

function validate_user($user_name, $password){
	global $server, $log;
	$user = new User();
	$user->user_name = $user_name;

	if($user->authenticate_user($password)){
		return true;
	}else{
		$log->fatal("SECURITY: failed attempted login for $user_name using SOAP api");
		$server->setError("Invalid username and/or password");
		return false;
	}

}
function add_contacts_matching_email_address(&$output_list, $email_address, &$seed_contact, &$msi_id)
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
//		$log->fatal("Adding another contact to the list: $contact->first_name ($msi_id)");
		$output_list[] = Array("name1"	=> $contact->first_name,
			"name2" => $contact->last_name,
			"association" => $contact->account_name,
			"type" => 'Contact',
			"id" => $contact->id,
			"msi_id" => $msi_id,
			"email_address" => $contact->email1);

		$accounts = $contact->get_accounts();
		foreach($accounts as $account)
		{
			$output_list[] = get_account_array($account, $msi_id);
		}

		$opps = $contact->get_opportunities();
		foreach($opps as $opp)
		{
			$output_list[] = get_opportunity_array($opp, $msi_id);
		}

		$cases = $contact->get_cases();
		foreach($cases as $case)
		{
			$output_list[] = get_case_array($case, $msi_id);
		}

		$msi_id = $msi_id + 1;
	}
}




function contact_by_email($user_name, $password, $email_address)
{
	if(!validate_user($user_name, $password)){
		return array();
	}
	global $log;
	//$log->fatal("Contact by email called with: $email_address");

	$seed_contact = new Contact();
	$output_list = Array();
	$treeResults =Array();
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

	// Track the msi_id
	$msi_id = 1;

	foreach( $email_address_list as $single_address)
	{
		//$log->fatal("************".$single_address);
		add_contacts_matching_email_address($output_list, $single_address, $seed_contact, $msi_id);
	}

	//to remove an erroneous compiler warning
	$seed_contact = $seed_contact;

	//$log->fatal("Contact by email returning");
	return $output_list;
}

function get_contact_array($contact, $msi_id = '0'){
	 return Array("name1"	=> $contact->first_name,
			"name2" => $contact->last_name,
			"association" => $contact->account_name,
			"type" => 'Contact',
			"id" => $contact->id,
			"msi_id" => $msi_id,
			"email_address" => $contact->email1);

}
function contact_by_search($name, $where = '', $msi_id = '0')
{
	//global $log;
	$seed_contact = new Contact();
	if($where == ''){
		$where = $seed_contact->build_generic_where_clause($name);
	}
	$response = $seed_contact->get_list("first_name, last_name", $where, 0);
	$contactList = $response['list'];
	//$row_count = $response['row_count'];

	$output_list = Array();

	//$log->fatal("Retrieved the list");

	// create a return array of names and email addresses.
	foreach($contactList as $contact)
	{
		//$log->fatal("Adding another contact to the list");
		$output_list[] = get_contact_array($contact, $msi_id);
	}
	return $output_list;
}

function get_account_array($account, $msi_id){
	return Array("name1"	=> '',
			"name2" => $account->name,
			"association" => $account->billing_address_city,
			"type" => 'Account',
			"id" => $account->id,
			"msi_id" => $msi_id,
			"email_address" => $account->email1);
}

function account_by_search($name, $where = '', $msi_id = '0')
{
	//global $log;
	$seed_account = new Account();
	if($where == ''){
		$where = $seed_account->build_generic_where_clause($name);
	}
	$response = $seed_account->get_list("name", $where, 0);
	$accountList = $response['list'];
	//$row_count = $response['row_count'];

	$output_list = Array();

	//$log->fatal("Retrieved the list");

	// create a return array of names and email addresses.
	foreach($accountList as $account)
	{
		//$log->fatal("Adding another account to the list");
		$output_list[] = get_account_array($account, $msi_id);
	}
	return $output_list;
}

function get_opportunity_array($value, $msi_id = '0'){
		return  Array("name1"	=> '',
			"name2" => $value->name,
			"association" => $value->account_name,
			"type" => 'Opportunity',
			"id" => $value->id,
			"msi_id" => $msi_id,
			"email_address" => '');

}

function opportunity_by_search($name, $where = '', $msi_id = '0')
{
	//global $log;
	$seed = new Opportunity();
	if($where == ''){
		$where = $seed->build_generic_where_clause($name);
	}
	$response = $seed->get_list("name", $where, 0);
	$list = $response['list'];
	//$row_count = $response['row_count'];

	$output_list = Array();

	//$log->fatal("Retrieved the list");

	// create a return array of names and email addresses.
	foreach($list as $value)
	{
		//$log->fatal("Adding another account to the list");
		$output_list[] = get_opportunity_array($value, $msi_id);
	}
	return $output_list;
}

function get_case_array($value, $msi_id){
	return Array("name1"	=> '',
			"name2" => $value->name,
			"association" => $value->account_name,
			"type" => 'Case',
			"id" => $value->id,
			"msi_id" => $msi_id,
			"email_address" => '');

}
function case_by_search($name, $where = '', $msi_id='0')
{
	//global $log;
	$seed = new aCase();
	if($where == ''){
		$where = $seed->build_generic_where_clause($name);
	}
	$response = $seed->get_list("name", $where, 0);
	$list = $response['list'];
	//$row_count = $response['row_count'];

	$output_list = Array();

	//$log->fatal("Retrieved the list");

	// create a return array of names and email addresses.
	foreach($list as $value)
	{
		//$log->fatal("Adding another account to the list");
		$output_list[] = get_case_array($value, $msi_id);
		}
	return $output_list;
}

function track_email($user_name, $password,$parent_id, $contact_ids, $date_sent, $email_subject, $email_body)
{
	if(!validate_user($user_name, $password)){
		return "Invalid username and/or password";
	}
	global $log;

	//todo make the activity body not be html encoded
	$log->info("In track email: username: $user_name contacts: $contact_ids date_sent: $date_sent"); // activity: $email_body");


	// translate date sent from VB format 7/22/2004 9:36:31 AM
	// to yyyy-mm-dd 9:36:31 AM
	$date_sent = ereg_replace("([0-9]*)/([0-9]*)/([0-9]*)( .*$)", "\\3-\\1-\\2\\4", $date_sent);



	require_once('modules/Users/User.php');
	$seed_user = new User();

	//$log->fatal("about to retrieve user id for $user_name");
	$user_id = $seed_user->retrieve_user_id($user_name);
	//$log->fatal("done retrieving user id for $user_id");
	$seed_user->retrieve($user_id);
	$current_user = $seed_user;
	require_once('modules/Emails/Email.php');

	$email = new Email();

	$email->description = $email_body;
	$email->name = $email_subject;
	$email->user_id = $user_id;
	$email->assigned_user_id = $user_id;
	$email->assigned_user_name = $user_name;
	$email->date_start = $date_sent;

	// Save one copy of the email message

	$parent_id_list = explode(";", $parent_id);
	$parent_id = explode(':', $parent_id_list[0]);

	// Having a parent object is optional.  If it is set, then associate it.
	if(isset($parent_id[0]) && isset($parent_id[1]))
	{
		$email->parent_type = $parent_id[0];
		$email->parent_id = $parent_id[1];
	}

	$email->save();
	// for each contact, add a link between the contact and the email message
	$id_list = explode(";", $contact_ids);

	foreach( $id_list as $id)
	{
		if(!empty($id))
		$email->set_emails_contact_invitee_relationship($email->id, $id);
	}

	return "Succeeded";
}

function create_contact($user_name,$password, $first_name, $last_name, $email_address)
{
	if(!validate_user($user_name, $password)){
		return 0;
	}
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
	$contact->assigned_user_id = $user_id;
	$contact->assigned_user_name = $user_name;
	return $contact->save();
}

function create_account($user_name,$password, $name, $phone, $website)
{
	if(!validate_user($user_name, $password)){
		return 0;
	}
	global $log;

	//todo make the activity body not be html encoded
//	$log->fatal("In Create contact: username: $user_name first/last/email ($first_name, $last_name, $email_address)");

	require_once('modules/Users/User.php');
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	$account = new Account();
	$account->name = $name;
	$account->phone_office = $phone;
	$account->website = $website;
	$account->assigned_user_id = $user_id;
	$account->assigned_user_name = $user_name;
	return $accountid = $account->save();



}
function create_case($user_name,$password, $name)
{
	if(!validate_user($user_name, $password)){
		return 0;
	}
	global $log;

	//todo make the activity body not be html encoded
//	$log->fatal("In Create contact: username: $user_name first/last/email ($first_name, $last_name, $email_address)");

	require_once('modules/Users/User.php');
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	$case = new aCase();
	$case->assigned_user_id = $user_id;
	$case->assigned_user_name = $seed_user->user_name;
	$case->name = $name;
	$case->assigned_user_id = $user_id;
	$case->assigned_user_name = $user_name;
	return $case->save();
}

function create_opportunity($user_name,$password, $name, $amount)
{
	if(!validate_user($user_name, $password)){
		return 0;
	}
	global $log;

	//todo make the activity body not be html encoded
//	$log->fatal("In Create contact: username: $user_name first/last/email ($first_name, $last_name, $email_address)");

	require_once('modules/Users/User.php');
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	$opp = new Opportunity();
	$opp->name = $name;
	$opp->amount = $amount;
	$opp->assigned_user_id = $user_id;
	$opp->assigned_user_name = $user_name;
	return $opp->save();
}

function search($user_name, $password,$name){
	if(!validate_user($user_name, $password)){
		return array();
	}
	$list = contact_by_search($name);
	$list = array_merge($list, account_by_search($name));
	$list = array_merge($list, case_by_search($name));
	$list = array_merge($list, opportunity_by_search($name));
	return $list;
}




//echo "<b>".create_contact('admin', 'sugar', 'sweet', 'sugar@sweet.com')."</b>";
//echo "<BR>";
//print_r(search('sugar@sweet.com'));
//echo "<BR>";
//echo "<b>".create_account('admin', 'test_account', '111-111-1111', 'www.sugarcrm.com'). "</B>";
//echo "<BR>";
//print_r(search('test_account'));
//echo "<BR>";
//echo "<b>".create_case('admin', 'test_case'). "</B>";
//echo "<BR>";
//print_r(search('test_case'));
//echo "<BR>";
//echo "<b>".create_opportunity('admin', 'test_opportunity', '10000'). "</B>";
//echo "<BR>";
//print_r(search('test_opportunity'));
//echo "<BR>";
//$log->fatal("In soap.php");

/* Begin the HTTP listener service and exit. */
$server->service($HTTP_RAW_POST_DATA);

exit();



?>
