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

//include("Serializer.php");
require_once("config.php");
require_once('modules/Contacts/Contact.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
//require_once('SOAP/lib/nusoap.php');
require_once('include/nusoap/nusoap.php');
// create object
//$serializer = new XML_Serializer();
$NAMESPACE = 'http://www.vtigercrm.com/vtigercrm';
$server = new soap_server;

$server->configureWSDL('vtigersoap');


$server->wsdl->addComplexType(
    'task_detail',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'start_date' => array('name'=>'start_date','type'=>'xsd:datetime'),
        'date_modified' => array('name'=>'date_modified','type'=>'xsd:datetime'),
        'name' => array('name'=>'name','type'=>'xsd:string'),
        'status' => array('name'=>'status','type'=>'xsd:string'),
        'date_due' => array('name'=>'date_due','type'=>'xsd:string'),
        'time_due' => array('name'=>'time_due','type'=>'xsd:datetime'),
        'priority' => array('name'=>'priority','type'=>'xsd:string'),
        'description' => array('name'=>'description','type'=>'xsd:string'),
	    'contact_name' => array('name'=>'contact_name','type'=>'xsd:string'),
        'id' => array('name'=>'id','type'=>'xsd:string'),

    )
);
    
$server->wsdl->addComplexType(
    'task_detail_array',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:task_detail[]')
    ),
    'tns:task_detail'
);

//calendar
$server->wsdl->addComplexType(
    'calendar_detail',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'start_date' => array('name'=>'start_date','type'=>'xsd:string'),
        'date_modified' => array('name'=>'date_modified','type'=>'xsd:string'),
        'name' => array('name'=>'name','type'=>'xsd:string'),
        'location' => array('name'=>'location','type'=>'xsd:string'),
        'date_due' => array('name'=>'date_due','type'=>'xsd:string'),
        'time_due' => array('name'=>'time_due','type'=>'xsd:string'),
        //'priority' => array('name'=>'priority','type'=>'xsd:string'),
        'description' => array('name'=>'description','type'=>'xsd:string'),
		'contact_name' => array('name'=>'contact_name','type'=>'xsd:string'),
        'id' => array('name'=>'id','type'=>'xsd:string'),

    )
);
    
$server->wsdl->addComplexType(
    'calendar_detail_array',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:calendar_detail[]')
    ),
    'tns:calendar_detail'
);
//calendar

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
        'primary_address_city' => array('name'=>'primary_address_city','type'=>'xsd:string'),
        'account_name' => array('name'=>'account_name','type'=>'xsd:string'),
				'account_id' => array('name'=>'account_id','type'=>'xsd:string'),
        'id' => array('name'=>'id','type'=>'xsd:string'),
        'salutation' => array('name'=>'salutation','type'=>'xsd:string'),
        'title'=> array('name'=>'title','type'=>'xsd:string'),
        'phone_mobile'=> array('name'=>'phone_mobile','type'=>'xsd:string'),
        'reports_to'=> array('name'=>'reports_to','type'=>'xsd:string'),
        'primary_address_city'=> array('name'=>'primary_address_city','type'=>'xsd:string'),
        'primary_address_street'=> array('name'=>'primary_address_street','type'=>'xsd:string'),
        'primary_address_state'=> array('name'=>'primary_address_state','type'=>'xsd:string'),
        'primary_address_postalcode'=> array('name'=>'primary_address_postalcode','type'=>'xsd:string'),
        'primary_address_country'=> array('name'=>'primary_address_country','type'=>'xsd:string'),
        'alt_address_city'=> array('name'=>'alt_address_city','type'=>'xsd:string'),
        'alt_address_street'=> array('name'=>'alt_address_street','type'=>'xsd:string'),
        'alt_address_state'=> array('name'=>'alt_address_state','type'=>'xsd:string'),
        'alt_address_postalcode'=> array('name'=>'alt_address_postalcode','type'=>'xsd:string'),
        'alt_address_country'=> array('name'=>'alt_address_country','type'=>'xsd:string'),

        'office_phone'=> array('name'=>'office_phone','type'=>'xsd:string'),
        'home_phone'=> array('name'=>'home_phone','type'=>'xsd:string'),
        'other_phone'=> array('name'=>'other_phone','type'=>'xsd:string'),
        'fax'=> array('name'=>'fax','type'=>'xsd:string'),
        'department'=> array('name'=>'fax','type'=>'xsd:string'),
        'birthdate'=> array('name'=>'birthdate','type'=>'xsd:string'),
        'assistant_name'=> array('name'=>'assistant_name','type'=>'xsd:string'),
        'assistant_phone'=> array('name'=>'assistant_phone','type'=>'xsd:string')

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

  


$server->wsdl->addComplexType(
    'contact_column_detail',
    'complexType',
    'array',
    '',
    array(
        'email_address' => array('name'=>'email_address','type'=>'xsd:string'),
        'first_name' => array('name'=>'first_name','type'=>'xsd:string'),
        'last_name' => array('name'=>'last_name','type'=>'xsd:string'),
        'primary_address_city' => array('name'=>'primary_address_city','type'=>'xsd:string'),
        'account_name' => array('name'=>'account_name','type'=>'xsd:string'),
        'id' => array('name'=>'id','type'=>'xsd:string'),
        'salutation' => array('name'=>'salutation','type'=>'xsd:string'),
        'title'=> array('name'=>'title','type'=>'xsd:string'),
        'phone_mobile'=> array('name'=>'phone_mobile','type'=>'xsd:string'),
        'reports_to'=> array('name'=>'reports_to','type'=>'xsd:string'),
        'primary_address_city'=> array('name'=>'primary_address_city','type'=>'xsd:string'),
        'primary_address_street'=> array('name'=>'primary_address_street','type'=>'xsd:string'),
        'primary_address_state'=> array('name'=>'primary_address_state','type'=>'xsd:string'),
        'primary_address_postalcode'=> array('name'=>'primary_address_postalcode','type'=>'xsd:string'),
        'primary_address_country'=> array('name'=>'primary_address_country','type'=>'xsd:string'),
        'alt_address_city'=> array('name'=>'alt_address_city','type'=>'xsd:string'),
        'alt_address_street'=> array('name'=>'alt_address_street','type'=>'xsd:string'),
        'alt_address_state'=> array('name'=>'alt_address_state','type'=>'xsd:string'),
        'alt_address_postalcode'=> array('name'=>'alt_address_postalcode','type'=>'xsd:string'),
        'alt_address_country'=> array('name'=>'alt_address_country','type'=>'xsd:string'),
    )
);


/*$server->wsdl->addComplexType(
    'contact_column_array',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:contact_column_detail[]')
    ),
    'tns:contact_column_detail'
);

 $server->wsdl->addComplexType(
    'account_column_array',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:account_column_detail[]')
    ),
    'tns:account_column_detail'
);

$server->wsdl->addComplexType(
    'lead_column_array',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:lead_column_detail[]')
    ),
    'tns:lead_column_detail'
);*/
 



$server->wsdl->addComplexType(
    'account_column_detail',
    'complexType',
    'array',
    '',
    array(
        'accountid' => array('name'=>'accountid','type'=>'xsd:string'),
        'accountname' => array('name'=>'accountname','type'=>'xsd:string'),
        'parentid' => array('name'=>'parentid','type'=>'xsd:string'),
        'account_type' => array('name'=>'account_type','type'=>'xsd:string'),
        'industry' => array('name'=>'industry','type'=>'xsd:string'), 
        'annualrevenue' => array('name'=>'annualrevenue','type'=>'xsd:string'),
        'rating'=> array('name'=>'rating','type'=>'xsd:string'), 
        'ownership' => array('name'=>'ownership','type'=>'xsd:string'),
        'siccode' => array('name'=>'siccode','type'=>'xsd:string'),
        'tickersymbol' => array('name'=>'tickersymbol','type'=>'xsd:string'),
        'phone' => array('name'=>'phone','type'=>'xsd:string'),
        'otherphone' => array('name'=>'otherphone','type'=>'xsd:string'),
        'email1' => array('name'=>'email1','type'=>'xsd:string'),
        'email2' => array('name'=>'email2','type'=>'xsd:string'),
        'website' => array('name'=>'website','type'=>'xsd:string'),
        'fax' => array('name'=>'fax','type'=>'xsd:string'),
        //'employees' => array('name'=>'employees','type'=>'xsd:string'),
			)
);

$server->wsdl->addComplexType(
    'lead_column_detail',
    'complexType',
    'array',
    '',
    array(
        'id' => array('name'=>'id','type'=>'xsd:string'), 
        'date_entered' => array('name'=>'date_entered','type'=>'xsd:string'),
        'date_modified' => array('name'=>'date_modified','type'=>'xsd:string'),
        'modified_user_id' => array('name'=>'modified_user_id','type'=>'xsd:string'),
        'assigned_user_id' => array('name'=>'assigned_user_id','type'=>'xsd:string'),
        'salutation' => array('name'=>'salutation','type'=>'xsd:string'),
        'first_name' => array('name'=>'first_name','type'=>'xsd:string'),
        'last_name' => array('name'=>'last_name','type'=>'xsd:string'),
        'company' => array('name'=>'company','type'=>'xsd:string'),
        'designation' => array('name'=>'designation','type'=>'xsd:string'),
        'lead_source' => array('name'=>'lead_source','type'=>'xsd:string'),
        'industry' => array('name'=>'industry','type'=>'xsd:string'),
        'annual_revenue' => array('name'=>'annual_revenue','type'=>'xsd:string'),
        'license_key' => array('name'=>'license_key','type'=>'xsd:string'),
        'phone' => array('name'=>'phone','type'=>'xsd:string'),
        'mobile' => array('name'=>'mobile','type'=>'xsd:string'),
        'fax' => array('name'=>'fax','type'=>'xsd:string'),
        'email' => array('name'=>'email','type'=>'xsd:string'),
        'yahoo_id' => array('name'=>'yahoo_id','type'=>'xsd:string'),
        'website' => array('name'=>'website','type'=>'xsd:string'),
        'lead_status' => array('name'=>'lead_status','type'=>'xsd:string'),
        'rating' => array('name'=>'rating','type'=>'xsd:string'),
        'employees' => array('name'=>'employees','type'=>'xsd:string'),
        'address_street' => array('name'=>'address_street','type'=>'xsd:string'),
        'address_city' => array('name'=>'address_city','type'=>'xsd:string'),
        'address_state' => array('name'=>'address_state','type'=>'xsd:string'),
        'address_postalcode' => array('name'=>'address_postalcode','type'=>'xsd:string'),
        'address_country' => array('name'=>'address_country','type'=>'xsd:string'),
        'description' => array('name'=>'description','type'=>'xsd:string'),
        'deleted' => array('name'=>'deleted','type'=>'xsd:string'),
        'converted' => array('name'=>'converted','type'=>'xsd:string'),
    )
);

//end code for mail merge



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
	'create_contact',
    array('user_name'=>'xsd:string', 'first_name'=>'xsd:string', 'last_name'=>'xsd:string', 'email_address'=>'xsd:string','account_name'=>'xsd:string', 'salutation'=>'xsd:string', 'title'=>'xsd:string', 'phone_mobile'=>'xsd:string' , 'reports_to'=>'xsd:string', 'primary_address_street'=>'xsd:string', 'primary_address_city'=>'xsd:string', 'primary_address_state'=>'xsd:string' , 'primary_address_postalcode'=>'xsd:string', 'primary_address_country'=>'xsd:string', 'alt_address_city'=>'xsd:string', 'alt_address_street'=>'xsd:string','alt_address_state'=>'xsd:string', 'alt_address_postalcode'=>'xsd:string', 'alt_address_country'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
    'get_version',
    array('user_name'=>'xsd:string','password'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);


$server->register(
    'contact_by_email',
    array('user_name'=>'xsd:string','email_address'=>'xsd:string'),
    array('return'=>'tns:contact_detail_array'),
    $NAMESPACE);
/*    
$server->register(
    'contact_by_email',
    array('email_address'=>'xsd:string'),
    array('return'=>'tns:contact_detail_array'),
    $NAMESPACE);
    */

$server->register(
    'contact_by_search',
    array('name'=>'xsd:string'),
    array('return'=>'tns:contact_detail_array'),
    $NAMESPACE);

$server->register(
	'update_contact',
        array('user_name'=>'xsd:string', 'id'=>'xsd:string','first_name'=>'xsd:string', 'last_name'=>'xsd:string', 'email_address'=>'xsd:string' , 'account_name'=>'xsd:string', 'salutation'=>'xsd:string', 'title'=>'xsd:string', 'phone_mobile'=>'xsd:string' , 'reports_to'=>'xsd:string', 'primary_address_street'=>'xsd:string', 'primary_address_city'=>'xsd:string', 'primary_address_state'=>'xsd:string' , 'primary_address_postalcode'=>'xsd:string', 'primary_address_country'=>'xsd:string', 'alt_address_city'=>'xsd:string', 'alt_address_street'=>'xsd:string','alt_address_city'=>'xsd:string', 'alt_address_state'=>'xsd:string', 'alt_address_postalcode'=>'xsd:string', 'alt_address_country'=>'xsd:string','office_phone'=>'xsd:string','home_phone'=>'xsd:string','other_phone'=>'xsd:string','fax'=>'xsd:string','department'=>'xsd:string','birthdate'=>'xsd:datetime','assistant_name'=>'xsd:string','assistant_phone'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);
	    
$server->register(
	'delete_contact',
    array('user_name'=>'xsd:string', 'id'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

/*
$server->register(
	'sync_contact',
    array('user_name'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);
*/

$server->register(
	'create_task',
        array('user_name'=>'xsd:string', 'start_date'=>'xsd:datetime', 'date_modified'=>'xsd:datetime','name'=>'xsd:string','status'=>'xsd:string','priority'=>'xsd:string','description'=>'xsd:string','date_due'=>'xsd:string','contact_name'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

//security
$server->register(
	'authorize_module',
        array('user_name'=>'xsd:string','module_name'=>'xsd:string', 'action'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);
//security    

$server->register(
	'update_task',
        array('user_name'=>'xsd:string', 'id'=>'xsd:string', 'start_date'=>'xsd:datetime','name'=>'xsd:string','status'=>'xsd:string','priority'=>'xsd:string','description'=>'xsd:string','date_due'=>'xsd:date','contact_name'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

        //'date_due_flag'=>'xsd:string', 'date_due'=>'xsd:string', 'time_due'=>'xsd:datetime','parent_type'=>xsd:string,'parent_id'=>'xsd:string', 'contact_id'=>'xsd:string', 'priority'=>'xsd:string', 'description'=>'xsd:string','deleted'=>xsd:string),

$server->register(
    'retrieve_task',
    array('name'=>'xsd:string'),
    array('return'=>'tns:task_detail_array'),
    $NAMESPACE);

$server->register(
	'delete_task',
    array('user_name'=>'xsd:string', 'id'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);


/*
$server->register(
	'sync_task',
    array('user_name'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);
*/

$server->register(
	'track_email',
    array('user_name'=>'xsd:string', 'contact_ids'=>'xsd:string', 'date_sent'=>'xsd:date', 'email_subject'=>'xsd:string', 'email_body'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

 $server->register(
	'upload_emailattachment',
    array('email_id'=>'xsd:string', 'filename'=>'xsd:string','binFile'=>'xsd:string','fileSize'=>'xsd:long'),
   array('return'=>'xsd:string'),
    $NAMESPACE);

    $server->register(
   'create_contacts',
    array('user_name'=>'xsd:string','contacts'=>'tns:contact_detail_array'),
    array('return'=>'tns:contact_detail_array'),
    $NAMESPACE);

  $server->register(
   'create_tasks',
    array('user_name'=>'xsd:string','tasks'=>'tns:task_detail_array'),
    array('return'=>'tns:task_detail_array'),
    $NAMESPACE);


$server->register(
    'contact_by_range',
    array('user_name'=>'xsd:string','from_index'=>'xsd:int','offset'=>'xsd:int'),
    array('return'=>'tns:contact_detail_array'),
    $NAMESPACE);

$server->register(
    'get_contacts_count',
    array('user_name'=>'xsd:string','password'=>'xsd:string'),
    array('return'=>'xsd:int'),
    $NAMESPACE);

$server->register(
    'task_by_range',
    array('user_name'=>'xsd:string','from_index'=>'xsd:int','offset'=>'xsd:int'),
    array('return'=>'tns:task_detail_array'),
    $NAMESPACE);

 $server->register(
    'get_tasks_count',
    array('user_name'=>'xsd:string','password'=>'xsd:string'),
    array('return'=>'xsd:int'),
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
	 
//calendar
$server->register(
    'get_calendar_count',
    array('user_name'=>'xsd:string','password'=>'xsd:string'),
    array('return'=>'xsd:int'),
    $NAMESPACE);
    
$server->register(
    'calendar_by_range',
    array('user_name'=>'xsd:string','from_index'=>'xsd:int','offset'=>'xsd:int'),
    array('return'=>'tns:calendar_detail_array'),
    $NAMESPACE);

$server->register(
   'create_calendars',
    array('user_name'=>'xsd:string','tasks'=>'tns:calendar_detail_array'),
    array('return'=>'tns:calendar_detail_array'),
    $NAMESPACE);

$server->register(
	'create_calendar',
    array('user_name'=>'xsd:string', 'start_date'=>'xsd:string','name'=>'xsd:string','description'=>'xsd:string','date_due'=>'xsd:string','contact_name'=>'xsd:string','location'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
	'update_calendar',
    array('user_name'=>'xsd:string', 'id'=>'xsd:string', 'start_date'=>'xsd:string','name'=>'xsd:string','description'=>'xsd:string','date_due'=>'xsd:string','contact_name'=>'xsd:string','location'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
    'retrieve_calendar',
    array('name'=>'xsd:string'),
    array('return'=>'tns:calendar_detail_array'),
    $NAMESPACE);

$server->register(
	'delete_calendar',
    array('user_name'=>'xsd:string', 'id'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);
        
//calendar   

function get_contacts_columns($user_name, $password)
{
    require_once('modules/Contacts/Contact.php');
    $contact = new Contact();
    return $contact->getColumnNames();
}

function authorize_module($user_name,$module_name,$action)
{
	require_once('modules/Users/UserInfoUtil.php');
	if($module_name == "Tasks")
	{
		$module_name = "Activities";
	}
	$user_id = getUserId_Ol($user_name);
	if($user_id != 0)
	{
		$auth_val = isAllowed_Outlook($module_name,$action,$user_id,"");
	}else
	{
	    $auth_val = "no";
	}
	return $auth_val;
}
/*require_once('modules/Accounts/Account.php');
$account = new Account();
foreach($account->getColumnNames_Acnt() as $flddetails)
{
	echo $flddetails;
}
*/
    
function get_accounts_columns($user_name, $password)
{
    require_once('modules/Accounts/Account.php');
    $account = new Account();
    return $account->getColumnNames_Acnt();
}


function get_leads_columns($user_name, $password)
{
    require_once('modules/Leads/Lead.php');
    $lead = new Lead();
    return $lead->getColumnNames_Lead();
}
//end code for mail merge

function get_contacts_count($user_name, $password)
{
    global $current_user;
	require_once('modules/Users/User.php');
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);
   
    require_once('modules/Contacts/Contact.php');
    $contact = new Contact();
   
    return $contact->getCount($user_name);
}

function create_contacts($user_name,$output_list)
{
	$counter=0;
	foreach($output_list as $contact)
	{
   
        if($contact[birthdate]=="4501-01-01")
        {
	        $contact[birthdate] = "";
        }
		$id = create_contact1($user_name, $contact[first_name], $contact[last_name], $contact[email_address ],$contact[account_name ], $contact[salutation ], $contact[title], $contact[phone_mobile], $contact[reports_to],$contact[primary_address_street],$contact[primary_address_city],$contact[primary_address_state],$contact[primary_address_postalcode],$contact[primary_address_country],$contact[alt_address_city],$contact[alt_address_street],$contact[alt_address_state],$contact[alt_address_postalcode],$contact[alt_address_country],$contact[office_phone],$contact[home_phone],$contact[other_phone],$contact[fax],$contact[department],$contact[birthdate],$contact[assistant_name],$contact[assistant_phone]);
      
	  $output_list[$counter] ['id']=$id;
	   $counter++;
	}
	return array_reverse($output_list);
}

function create_tasks($user_name,$output_list)
{
	$counter=0;
	foreach($output_list as $task)
	{
  
		if($task[date_due] == "4501-01-01")
		{
			$task[date_due] = "";
		}
		$id= create_task($user_name, $task[start_date], $task[date_modified],$task[name],$task[status],$task[priority],$task[description],$task[date_due],$task[contact_name]);
   
      
	  $output_list[$counter] ['id']=$id;
	   $counter++;
	}
	return array_reverse($output_list);
}


function get_version($user_name, $password)
{
	return "4.0";
}

function contact_by_email($user_name,$email_address)
{
        $seed_contact = new Contact();
        $output_list = Array();
   
         {  
            $response = $seed_contact->get_contacts1($user_name,$email_address);
            $contactList = $response['list'];

    
       // create a return array of names and email addresses.
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
            "alt_address_country"=>$contact[alt_address_country],
 );
    }
}

           


    //to remove an erroneous compiler warning
    $seed_contact = $seed_contact;


    return $output_list;
}

function contact_by_range($user_name,$from_index,$offset)
{
    
	   $seed_contact = new Contact();
        $output_list = Array();
   
         {  
            $response = $seed_contact->get_contacts($user_name,$from_index,$offset);
            $contactList = $response['list'];

    
       // create a return array of names and email addresses.
    foreach($contactList as $contact)
    {
        $account_name=$contact[account_name];
        $birthdate = $contact[birthdate];
        if($account_name=="Vtiger_Crm")
        {
            $account_name="";
        }
        if($birthdate == "")
        {
	        $birthdate = "4501-01-01";
        }
  
        $output_list[] = Array("first_name"    => $contact[first_name],
            "last_name" => $contact[last_name],
            "primary_address_city" => $contact[primary_address_city],
            "account_name" => $account_name,
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
            "alt_address_country"=>$contact[alt_address_country],
    "office_phone"=>$contact[office_phone],
    "home_phone"=>$contact[home_phone],
    "other_phone"=>$contact[other_phone],
    "fax"=>$contact[fax],
    "department"=>$contact[department],
    "birthdate"=>$birthdate,
    "assistant_name"=>$contact[assistant_name],
    "assistant_phone"=>$contact[assistant_phone],

 );
    }
}

           


    //to remove an erroneous compiler warning
    $seed_contact = $seed_contact;

    return $output_list;
}

function get_tasks_count($user_name, $password)
{   
    
    global $current_user;
	require_once('modules/Users/User.php');
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);
   
    require_once('modules/Activities/Activity.php');
    $task = new Activity();

   
    return $task->getCount($user_name);

}

function task_by_range($user_name,$from_index,$offset)
{
	require_once('modules/Activities/Activity.php');
        $seed_task = new Activity();
        $output_list = Array();
   
         {  
            $response = $seed_task->get_tasks($user_name,$from_index,$offset);
            $taskList = $response['list'];
            foreach($taskList as $temptask)
           {
		 if($temptask[date_due]=="")
		 {
			 $temptask[date_due]="4501-01-01";
		 }
		 if($temptask[time_due]=="")
		 {
			 $temptask[time_due]=NULL;
		 }



        		$output_list[] = Array(
                 "name"	=> $temptask[name],
		    	"date_modified" => $temptask[date_modified],
			    "start_date" => $temptask[start_date],
                "id" => $temptask[id],
    			
    			"status" => $temptask[status],
		        "date_due" => $temptask[date_due],	
	    		"description" => $temptask[description],		
                "contact_name" => $temptask[contact_name],		
		    	"priority" => $temptask[priority]);
                 
                 }
        }   


    //to remove an erroneous compiler warning
    $seed_task = $seed_task;
   
    return $output_list;
}



function create_session($user_name, $password)
{
	return "TempSessionID";	
}

function end_session($user_name)
{
	return "Success";	
}
 
function add_contacts_matching_email_address(&$output_list, $email_address, &$seed_contact)	
{
  //global $log;
	$safe_email_address = addslashes($email_address);
	
	$where = "email1 like '$safe_email_address' OR email2 like '$safe_email_address'";
	$response = $seed_contact->get_list("first_name,last_name,primary_address_city", $where, 0);
	$contactList = $response['list'];
	
	//$log->fatal("Retrieved the list");
	
	// create a return array of names and email addresses.
	foreach($contactList as $contact)
	{
		//$log->fatal("Adding another contact to the list: $contact-first_name");
		$output_list[] = Array("first_name"	=> $contact->first_name,
			"last_name" => $contact->last_name,
                        "primary_address_city" => $contact->primary_address_city,
                        "account_name" => $contact->account_name,
			"id" => $contact->id,
                        "email_address" => $contact->email1,
                       "salutation"=>$contact->salutation,
                       "title"=>$contact->title,
                       "phone_mobile"=>$contact->phone_mobile,
                      "reports_to_id"=>$contact->reports_to_id,
                      "primary_address_street"=>$contact->primary_address_street,
                     "primary_address_city"=>$contact->primary_address_city,
                     "primary_address_state"=>$contact->primary_address_state ,
                     "primary_address_postalcode"=>$contact->primary_address_postalcode,
                     "primary_address_country"=>$contact->primary_address_country,
                     "alt_address_city"=>$contact->alt_address_city,
                    "alt_address_street"=>$contact->alt_address_street,
                    "alt_address_city"=>$contact->alt_address_city,
                   "alt_address_state"=>$contact->alt_address_state,
                   "alt_address_postalcode"=>$contact->alt_address_postalcode,
                   "alt_address_country"=>$contact->alt_address_country,
);
        }
          }






function delete_contact($user_name,$id)
{
        global $current_user;
	require_once('modules/Users/User.php');
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);

        require_once('modules/Contacts/Contact.php');
        $contact = new Contact();
        $contact->id = $id;
        //$contact->delete($contact->id);
	$contact->mark_deleted($contact->id);
//	$contact->delete();
        return "Suceeded in deleting contact";
}

/*
function sync_contact($user_name)
{
  return "synchronized contact successfully";
}
*/

/*
function contact_by_email($email_address) 
{ 
  //global $log;
  //$this->log->debug("Contact by email called with: $email_address" .$email_address);
	
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
	
	//$log->debug("Contact by email returning");
	return $output_list;
}  
*/
function contact_by_search($name) 
{ 
//	global $log;
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
		//$log->fatal("Adding another contact to the list");
		$output_list[] = Array("first_name"	=> $contact->first_name,
			"last_name" => $contact->last_name,
			"account_name" => $contact->account_name,
			"id" => $contact->id,
			"email_address" => $contact->email1);
	}
	
	return $output_list;
}  

function upload_emailattachment($email_id, $filename,$binFile,$filesize)
{
	global $adb;
  $filetype= $_FILES['binFile']['type'];
  $filedata = "./cache/mails/".$email_id.$filename;

      $user_id=1;

  $account  = new Account();
  
  $account->insertIntoAttachment1($email_id,"Emails",$filedata,$filename,$filesize,$filetype,$user_id);

    unlink($filedata);

  return "Suceeded in upload_attachment";

    
}
function track_email($user_name, $contact_ids, $date_sent, $email_subject, $email_body)
{

	//global $log;
	
	//todo make the activity body not be html encoded
	//$log->fatal("In track email: username: $user_name contacts: $contact_ids date_sent: $date_sent"); // activity: $email_body");
	
	
	// translate date sent from VB format 7/22/2004 9:36:31 AM
	// to yyyy-mm-dd 9:36:31 AM
	$date_sent = ereg_replace("([0-9]*)/([0-9]*)/([0-9]*)( .*$)", "\\3-\\1-\\2\\4", $date_sent);
	
	
	
	global $current_user;
	require_once('modules/Users/User.php');
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);
	
	require_once('modules/Emails/Email.php');
	
	$email = new Email();

	$email_body = str_replace("'", "''", $email_body);
	$email_subject = str_replace("'", "''", $email_subject);

	$email->column_fields[name]=$email_subject;
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
	
	//return "Suceeded";
	return $email->id;
}

/*
function sync_task($user_name)
{
  return "synchronized task successfully";
}

*/

/*

    array('user_name'=>'xsd:string', 'first_name'=>'xsd:string', 'last_name'=>'xsd:string', 'email_address'=>'xsd:string','account_name'=>'xsd:string', 'salutation'=>'xsd:string', 'title'=>'xsd:string', 'phone_mobile'=>'xsd:string' , 'reports_to_id'=>'xsd:string', 'primary_address_street'=>'xsd:string', 'primary_address_city'=>'xsd:string', 'primary_address_state'=>'xsd:string' , 'primary_address_postalcode'=>'xsd:string', 'primary_address_country'=>'xsd:string', 'alt_address_city'=>'xsd:string', 'alt_address_street'=>'xsd:string','alt_address_city'=>'xsd:string', 'alt_address_state'=>'xsd:string', 'alt_address_postalcode'=>'xsd:string', 'alt_address_country'=>'xsd:string'),


*/

function create_contact($user_name, $first_name, $last_name, $email_address ,$account_name , $salutation , $title, $phone_mobile, $reports_to,$primary_address_street,$primary_address_city,$primary_address_state,$primary_address_postalcode,$primary_address_country,$alt_address_city,$alt_address_street,$alt_address_state,$alt_address_postalcode,$alt_address_country)
{
    if($birthdate == "4501-01-01")
    {
	    $birthdate = "";
    }
	return create_contact1($user_name, $first_name, $last_name, $email_address ,$account_name , $salutation , $title, $phone_mobile, $reports_to,$primary_address_street,$primary_address_city,$primary_address_state,$primary_address_postalcode,$primary_address_country,$alt_address_city,$alt_address_street,$alt_address_state,$alt_address_postalcode,$alt_address_country,"","","","","","","","");
}


function create_contact1($user_name, $first_name, $last_name, $email_address ,$account_name , $salutation , $title, $phone_mobile, $reports_to,$primary_address_street,$primary_address_city,$primary_address_state,$primary_address_postalcode,$primary_address_country,$alt_address_city,$alt_address_street,$alt_address_state,$alt_address_postalcode,$alt_address_country,$office_phone,$home_phone,$other_phone,$fax,$department,$birthdate,$assistant_name,$assistant_phone)
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
	$contact = new Contact();

	$contact->column_fields[firstname]=$first_name;
	$contact->column_fields[lastname]=$last_name;
		
	if($account_name=='')
	{
		$account_name="Vtiger_Crm";
	}
	else if($account_name==null)
	{
		$account_name="Vtiger_Crm";
	}
	
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
    $contact->column_fields[birthday]= $birthdate;
    $contact->column_fields[assistant]= $assistant_name;
    $contact->column_fields[assistantphone]= $assistant_phone;

	//$contact->saveentity("Contacts");
	$contact->save("Contacts");

	return $contact->id;
}

function retrievereportsto($reports_to,$user_id,$account_id)
{
  if($reports_to=="")
    {
        return null;
    }
     if($reports_to==null)
     {
         return null;
     }


$first_name;
$last_name;
$tok = strtok($reports_to," \n\t");
if($tok) {
    $first_name=$tok;
    $tok = strtok(" \n\t");
}
if($tok) {
    $last_name=$tok;
    $tok = strtok(" \n\t");
}

  if($first_name=="") 
    {
        return null;
    }
    if($last_name=="") 
    {
        return null;    
    }



// to do handle smartly handle the manager name
     $query = "select contactdetails.contactid as contactid from contactdetails inner join crmentity on crmentity.crmid=contactdetails.contactid where crmentity.deleted=0 and contactdetails.firstname like '".$first_name ."' and contactdetails.lastname like '" .$last_name ."'";



    	require_once('modules/Contacts/Contact.php');
	    $contact = new Contact();


    $db = new PearDatabase();
    $result= $db->query($query) or  die ("Not able to execute retyrievereports query");



    $rows_count =  $db->getRowCount($result);
    if($rows_count==0)
    {
    	$contact->column_fields[firstname] = $first_name;
        $contact->column_fields[lastname] = $last_name;
    	$contact->column_fields[assigned_user_id]=$user_id;
        $contact->column_fields[account_id]=$account_id;
    	//$contact->saveentity("Contacts");
    	$contact->save("Contacts");
        //mysql_close();
    	return $contact->id;
    }
    else if ($rows_count==1)
    {
        $row = $db->fetchByAssoc($result, 0);
        //mysql_close();
        return $row["contactid"];	    
    }
    else
    {
        $row = $db->fetchByAssoc($result, 0);
        //mysql_close();
        return $row["contactid"];	    
    }

}

function retrieve_account_id($account_name,$user_id)
{
  if($account_name=="")
    {
        return null;
    }

    $query = "select account.accountname accountname,account.accountid accountid from account inner join crmentity on crmentity.crmid=account.accountid where crmentity.deleted=0 and account.accountname='" .$account_name."'";


	$db = new PearDatabase();
    $result=  $db->query($query) or die ("Not able to execute insert");
    
    $rows_count =  $db->getRowCount($result);
    if($rows_count==0)
    {
    	require_once('modules/Accounts/Account.php');
	    $account = new Account();
    	$account->column_fields[accountname] = $account_name;
    	$account->column_fields[assigned_user_id]=$user_id;
    	//$account->saveentity("Accounts");
    	$account->save("Accounts");
        //mysql_close();
    	return $account->id;
    }
    else if ($rows_count==1)
    {
        $row = $db->fetchByAssoc($result, 0);
        //mysql_close();
        return $row["accountid"];	    
    }
    else
    {
        $row = $db->fetchByAssoc($result, 0);
        //mysql_close();
        return $row["accountid"];	    
    }
    
}


function create_task($user_name, $start_date, $date_modified,$name,$status,$priority,$description,$date_due,$contact_name)
{
	//global $log;
	
	//todo make the activity body not be html encoded
	//$log->fatal("In Create contact: username: $user_name first/last/email ($first_name, $last_name, $email_address)");
	
	global $current_user;
	require_once('modules/Users/User.php');
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);
	
	require_once('modules/Activities/Activity.php');
	$task = new Activity();
	
	//$task->date_entered = $date_entered;
	//$task->date_modified = $date_modified;
	//$task[assigned_user_id] = $assigned_user_id;
    $task->column_fields[subject] = $name;
	$task->column_fields[taskstatus]=$status;
    $task->column_fields[date_start]=$start_date;
	$task->column_fields[taskpriority]=$priority;
	$task->column_fields[description]=$description;
   	$task->column_fields[activitytype]="Task";
    // NOT EXIST IN DATA MODEL
    $task->column_fields[due_date]=$date_due;
   	$task->column_fields[contact_id]= retrievereportsto($contact_name,$user_id,null); 
	$task->column_fields[assigned_user_id]=$user_id;
    //$task->saveentity("Activities");
    $task->save("Activities");


	return $task->id;
}


function update_contact($user_name,$id, $first_name, $last_name, $email_address ,$account_name , $salutation , $title, $phone_mobile, $reports_to,$primary_address_street,$primary_address_city,$primary_address_state,$primary_address_postalcode,$primary_address_country,$alt_address_city,$alt_address_street,$alt_address_state,$alt_address_postalcode,$alt_address_country,$office_phone,$home_phone,$other_phone,$fax,$department,$birthdate,$assistant_name,$assistant_phone)
{
	global $current_user;
	require_once('modules/Users/User.php');
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);
	
	require_once('modules/Contacts/Contact.php');
	$contact = new Contact();
	/*
        $contact->first_name = $first_name;
	$contact->last_name = $last_name;
	$contact->email1 = $email_address;
        //$contact->account_name = $account_name;
	$contact->account_id=retrieve_account_id($account_name,$user_id);
        $contact->salutation = $salutation;
        $contact->title = $title;
        $contact->phone_mobile = $phone_mobile;
        $contact->reports_to_id = retrievereportsto($reports_to,$user_id,$contact->account_id);  
	$contact->primary_address_city = $primary_address_city;
        $contact->primary_address_postalcode = $primary_address_postalcode;
        $contact->primary_address_state = $primary_address_state;
        $contact->primary_address_street = $primary_address_street;
   $contact->primary_address_country = $primary_address_country; 
        $contact->alt_address_country = $alt_address_country;
        $contact->alt_address_postalcode = $alt_address_postalcode;
        $contact->alt_address_state = $alt_address_state;
        $contact->alt_address_street = $alt_address_street;

  $contact->alt_address_city = $alt_address_city;

	$contact->id=$id;

	

	$contact->save();
	*/
	
	$contact->column_fields[firstname]=$first_name;
	$contact->column_fields['lastname']=$last_name;
	
	if($account_name=='')
	{
		$account_name="Vtiger_Crm";
	}
	else if($account_name==null)
	{
		$account_name="Vtiger_Crm";
	}
	
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
	$contact->id=$id;

    $contact->column_fields[phone]= $office_phone;
    $contact->column_fields[homephone]= $home_phone;
    $contact->column_fields[otherphone]= $other_phone;
    $contact->column_fields[fax]= $fax;
    $contact->column_fields[department]=$department;
    if($birthdate == "4501-01-01")
    {
	    $birthdate = "";
    }
    $contact->column_fields[birthday]= $birthdate;
    $contact->column_fields[assistant]= $assistant_name;
    $contact->column_fields[assistantphone]= $assistant_phone;

	$contact->mode="edit";
	//$contact->saveentity("Contacts");
	$contact->save("Contacts");
	
	return "Suceeded";
}


function update_task($user_name, $id,$start_date, $name, $status,$priority,$description,$date_due,$contact_name)
{
	global $current_user;
	require_once('modules/Users/User.php');
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);
	
    require_once('modules/Activities/Activity.php');
	$task = new Activity();

	if($date_due == "4501-01-01")
	{
	    $date_due = "";
	}
		
    $task->column_fields[subject] = $name;
	$task->column_fields[taskstatus]=$status;
	$task->column_fields[taskpriority]=$priority;
	$task->column_fields[description]=$description;
    $task->column_fields[activitytype]="Task";
    $task->column_fields[due_date]=$date_due;
    $task->column_fields[date_start]=$start_date;
    $task->column_fields[contact_id]= retrievereportsto($contact_name,$user_id,null); 
	$task->column_fields[assigned_user_id]=$user_id;

    
	$task->id = $id;
    $task->mode="edit";
    //$task->saveentity("Activities");
    $task->save("Activities");
    return "Suceeded in updating task";
}




function delete_task($user_name,$id)
{
        global $current_user;
	require_once('modules/Users/User.php');
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);

     require_once('modules/Activities/Activity.php');
        $task = new Activity();
        $task->id = $id;
        $task->mark_deleted($id);
        return "Suceeded in deleting task";
}




function retrieve_task($name) 
{ 
//	global $log;
	$task = new Task();
	$where = "name like '$name%'";
	$response = $task->get_list("name", $where, 0);
	$taskList = $response['list'];
	$output_list = Array();
	
	foreach($taskList as $temptask)
	{
		$output_list[] = Array("name"	=> $temptask->name,
			"date_modified" => $temptask->date_modified,
			"start_date" => $temptask->start_date,
			"id" => $temptask->id,

			"status" => $temptask->status,
		        "date_due" => $temptask->date_due,	
			"description" => $temptask->description,		
			"priority" => $temptask->priority);
	}
	
	return $output_list;
}  

//calendar
function get_calendar_count($user_name, $password)
{   
    global $current_user;
	require_once('modules/Users/User.php');
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);
   
    require_once('modules/Activities/Activity.php');
    $calObj = new Activity();

    return $calObj->getCount_Meeting($user_name);
}

function calendar_by_range($user_name,$from_index,$offset)
{
	require_once('modules/Activities/Activity.php');
	$seed_task = new Activity();
	$output_list = Array(); 
 
	$response = $seed_task->get_calendars($user_name,$from_index,$offset);
	$taskList = $response['list'];
	foreach($taskList as $temptask)
	{
		$starthour = explode(":",$temptask[time_start]);
		
		if($temptask[date_due]=="0000-00-00")
		{
			$temptask[date_due] = $temptask[start_date];
		}
		
		if($temptask[duehours] == "0")
		{
			$temptask[duehours] = $starthour[0];
			if(strlen($temptask[duehours]) == 1)
			{
				$temptask[duehours] = "0".$temptask[duehours];
			}
		}else if($temptask[duehours] == "00")
		{
		   $temptask[duehours] = $starthour[0];
		   if(strlen($temptask[duehours]) == 1)
		   {
				$temptask[duehours] = "0".$temptask[duehours];
		   }
		}else
		{
			$temptask[duehours] = intval($starthour[0]) + intval($temptask[duehours]);
			if(intval($temptask[duehours]) == 24)
			{
				$temptask[duehours] = "00";
			}
		    if(strlen($temptask[duehours]) == 1)
		    {
				$temptask[duehours] = "0".$temptask[duehours];
		    }
		}
		
		$startdate = $temptask[start_date]." ".$temptask[time_start];
	    $duedate = $temptask[date_due]." ".$temptask[duehours].":".$temptask[dueminutes];
		$output_list[] = Array(
		"name"	=> $temptask[name],
		"date_modified" => $temptask[date_modified],
		"start_date" => $startdate,
		"id" => $temptask[id],	
		"date_due" => $duedate,	
		"description" => $temptask[description],		
		"contact_name" => $temptask[contact_name],
		"location" => $temptask[location],);		
	}   
	//to remove an erroneous compiler warning
	$seed_task = $seed_task;
	return $output_list;
}

function create_calendars($user_name,$output_list)
{
	$counter=0;
	foreach($output_list as $task)
	{
       $id= create_calendar($user_name, $task[start_date], $task[date_modified],$task[name],$task[description],$task[date_due],$task[contact_name],$task[location]);
      
	   $output_list[$counter] ['id']=$id;
	   $counter++;
	}
	return array_reverse($output_list);
}

function create_calendar($user_name, $start_date, $date_modified,$name,$description,$date_due,$contact_name,$location)
{
	
	global $current_user;
	require_once('modules/Users/User.php');
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);
	
	require_once('modules/Activities/Activity.php');
	$task = new Activity();
	
	$task->column_fields[subject] = $name;
	
	//<<<<<<<<<<<<<<<<<Date Time>>>>>>>>>>>>>>>
	$startdate = explode(" ",$start_date);
    $task->column_fields[date_start]=$startdate[0];
    $task->column_fields[time_start]=$startdate[1];
    
    $starthourmin = explode(":",$startdate[1]);
   	$task->column_fields[activitytype]="Meeting";

    $duedate = explode(" ",$date_due);
    $task->column_fields[due_date]=$duedate[0];
    
    $duetime = explode(":",$duedate[1]);

    if(intval($starthourmin[0]) < 23)
	{
	  $due_hour = intval($duetime[0]) - intval($starthourmin[0]);
	}else
	{
	  if($duetime[0] == "00")
	  {
	     $due_hour = 24 - intval($starthourmin[0]);
  	  }else
  	  {
	  	 $due_hour = intval($duetime[0]) - intval($starthourmin[0]);
  	  }
	}
    $task->column_fields[duration_hours] = $due_hour;
    
    $task->column_fields[duration_minutes] =$duetime[1];
    //<<<<<<<<<<<<<<<<<Date Time>>>>>>>>>>>>>>>
    
    $task->column_fields[description] = $description;
    $task->column_fields[location] = $location;
        // NOT EXIST IN DATA MODEL    
   	$task->column_fields[contact_id]= retrievereportsto($contact_name,$user_id,null); 
	$task->column_fields[assigned_user_id]=$user_id;
    $task->saveentity("Activities");
	return $task->id;
}

function update_calendar($user_name, $id,$start_date,$name,$description,$date_due,$contact_name,$location)
{
	global $current_user;
	require_once('modules/Users/User.php');
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);
	
    require_once('modules/Activities/Activity.php');
	$task = new Activity();

    $task->column_fields[subject] = $name;
	$task->column_fields[activitytype]="Meeting";
    //$task->column_fields[date_due]=$date_due;
    //$task->column_fields[date_start]=$start_date;
    
    //<<<<<<<<<<<<<<<<<Date Time>>>>>>>>>>>>>>>
	$startdate = explode(" ",$start_date);
    $task->column_fields[date_start]=$startdate[0];
    $task->column_fields[time_start]=$startdate[1];
    
    $starthourmin = explode(":",$startdate[1]);
   	$task->column_fields[activitytype]="Meeting";

    $duedate = explode(" ",$date_due);
    $task->column_fields[due_date]=$duedate[0];
    
    $duetime = explode(":",$duedate[1]);

    if(intval($starthourmin[0]) < 23)
	{
	  $due_hour = intval($duetime[0]) - intval($starthourmin[0]);
	}else
	{
	  if($duetime[0] == "00")
	  {
	     $due_hour = 24 - intval($starthourmin[0]);
  	  }else
  	  {
	  	 $due_hour = intval($duetime[0]) - intval($starthourmin[0]);
  	  }
	}
    $task->column_fields[duration_hours] = $due_hour;
    
    $task->column_fields[duration_minutes] =$duetime[1];
    //<<<<<<<<<<<<<<<<<Date Time>>>>>>>>>>>>>>>
    
    $task->column_fields[description] = $description;
    $task->column_fields[location] = $location;
    
    $task->column_fields[contact_id]= retrievereportsto($contact_name,$user_id,null); 
	$task->column_fields[assigned_user_id]=$user_id;

    
	$task->id = $id;
    $task->mode="edit";
    $task->saveentity("Activities");
    return "Suceeded in updating Calendar";
}

function delete_calendar($user_name,$id)
{
        global $current_user;
	require_once('modules/Users/User.php');
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);

        require_once('modules/Activities/Activity.php');
        $task = new Activity();
        //$task->id = $id;
        $task->mark_deleted($id);
        return "Suceeded in deleting Calendar";
}

function retrieve_calendar($name) 
{ 
	$task = new Task();
	$where = "name like '$name%'";
	$response = $task->get_list("name", $where, 0);
	$taskList = $response['list'];
	$output_list = Array();
	
	foreach($taskList as $temptask)
	{
		$output_list[] = Array("name"	=> $temptask->name,
			"date_modified" => $temptask->date_modified,
			"start_date" => $temptask->start_date,
			"id" => $temptask->id,
			"status" => $temptask->status,
	        "date_due" => $temptask->date_due,	
			"description" => $temptask->description,		
			"priority" => $temptask->priority);
	}
	
	return $output_list;
}
//calendar

//$log->fatal("In soap.php");

/* Begin the HTTP listener service and exit. */ 
$server->service($HTTP_RAW_POST_DATA); 

exit(); 


?>
