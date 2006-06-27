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

$log = &LoggerManager::getLogger('wordplugin');

$NAMESPACE = 'http://www.vtiger.com/vtigercrm/';
$server = new soap_server;
$accessDenied = "You are not permitted to perform this action";
$server->configureWSDL('vtigersoap');

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
    'get_contacts_columns',
    array('user_name'=>'xsd:string','password'=>'xsd:string'),
    array('return'=>'tns:contact_column_detail'),
    $NAMESPACE);

$server->register(
    'get_accounts_columns',
    array('user_name'=>'xsd:string','password'=>'xsd:string'),
    array('return'=>'tns:account_column_detail'),
    $NAMESPACE);

$server->register(
    'get_leads_columns',
    array('user_name'=>'xsd:string','password'=>'xsd:string'),
    array('return'=>'tns:lead_column_detail'),
    $NAMESPACE);

$server->register(
    'get_user_columns',
    array('user_name'=>'xsd:string','password'=>'xsd:string'),
    array('return'=>'tns:user_column_detail'),
    $NAMESPACE);



$server->register(
    'get_tickets_columns',
    array('user_name'=>'xsd:string','password'=>'xsd:string'),
    array('return'=>'tns:tickets_list_array'),
    $NAMESPACE);


	        
function get_tickets_columns($user_name, $password)
{
    require_once('modules/HelpDesk/HelpDesk.php');
    if(isPermitted("HelpDesk","index") == "yes")
    { 
    $helpdesk = new HelpDesk();
    return $helpdesk->getColumnNames_Hd();
    }
    else
    return null;
}

function get_contacts_columns($user_name, $password)
{
    require_once('modules/Contacts/Contact.php');
    if(isPermitted("Contacts","index") == "yes")
    {
	    $contact = new Contact();
	    return $contact->getColumnNames();	   
    }
 else
    return null;

}


function get_accounts_columns($user_name, $password)
{
    require_once('modules/Accounts/Account.php');
    if(isPermitted("Accounts","index") == "yes")
    {
	    $account = new Account();
	    return $account->getColumnNames_Acnt();
    }
 else
    return null;

}


function get_leads_columns($user_name, $password)
{
    require_once('modules/Leads/Lead.php');
 if(isPermitted("Leads","index") == "yes")
     {
    $lead = new Lead();
    return $lead->getColumnNames_Lead();
    }
 else
    return null;

}

function get_user_columns($user_name, $password)
{
    require_once('modules/Users/User.php');
     if(isPermitted("Users","index") == "yes")
         {
		 
    $user = new User();
    return $user->getColumnNames_User();
    }
 else
    return null;

}




function create_session($user_name, $password)
{
	return "TempSessionID";	
}

function end_session($user_name)
{
	return "Success";	
}
 



$server->service($HTTP_RAW_POST_DATA); 
exit(); 
?>
