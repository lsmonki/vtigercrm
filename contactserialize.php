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
require_once('modules/Tasks/Task.php');
require_once('modules/Contacts/Contact.php');
require_once('include/logging.php');
require_once('database/DatabaseConnection.php');
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
        'date_entered' => array('name'=>'date_entered','type'=>'xsd:datetime'),
        'date_modified' => array('name'=>'date_modified','type'=>'xsd:datetime'),
        'name' => array('name'=>'name','type'=>'xsd:string'),
        'status' => array('name'=>'status','type'=>'xsd:string'),
        'date_due' => array('name'=>'date_due','type'=>'xsd:date'),
        'time_due' => array('name'=>'time_due','type'=>'xsd:datetime'),
        'priority' => array('name'=>'priority','type'=>'xsd:string'),
        'description' => array('name'=>'description','type'=>'xsd:string'),
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


$server->wsdl->addComplexType(
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
);
 



$server->wsdl->addComplexType(
    'account_column_detail',
    'complexType',
    'array',
    '',
    array(
        'id' => array('name'=>'id','type'=>'xsd:string'),
        'date_entered' => array('name'=>'date_entered','type'=>'xsd:string'),
        'date_modified' => array('name'=>'date_modified','type'=>'xsd:string'),
        'modified_user_id' => array('name'=>'modified_user_id','type'=>'xsd:string'),
        'assigned_user_id' => array('name'=>'assigned_user_id','type'=>'xsd:string'), 
        'name' => array('name'=>'name','type'=>'xsd:string'),
        'parent_id'=> array('name'=>'parent_id','type'=>'xsd:string'), 
        'account_type' => array('name'=>'account_type','type'=>'xsd:string'),
        'industry' => array('name'=>'industry','type'=>'xsd:string'),
        'annual_revenue' => array('name'=>'annual_revenue','type'=>'xsd:string'),
        'phone_fax' => array('name'=>'phone_fax','type'=>'xsd:string'),
        'billing_address_street' => array('name'=>'billing_address_street','type'=>'xsd:string'),
        'billing_address_city' => array('name'=>'billing_address_city','type'=>'xsd:string'),
        'billing_address_state' => array('name'=>'billing_address_state','type'=>'xsd:string'),
        'billing_address_postalcode' => array('name'=>'billing_address_postalcode','type'=>'xsd:string'),
        'billing_address_country' => array('name'=>'billing_address_country','type'=>'xsd:string'),
        'description' => array('name'=>'description','type'=>'xsd:string'),
        'rating' => array('name'=>'rating','type'=>'xsd:string'),
        'phone_office' => array('name'=>'phone_office','type'=>'xsd:string'),
        'phone_alternate' => array('name'=>'phone_alternate','type'=>'xsd:string'),
        'email1' => array('name'=>'email1','type'=>'xsd:string'),
        'email2' => array('name'=>'email2','type'=>'xsd:string'),
        'website' => array('name'=>'website','type'=>'xsd:string'),
        'ownership' => array('name'=>'ownership','type'=>'xsd:string'),
        'employees' => array('name'=>'employees','type'=>'xsd:string'),
        'sic_code' => array('name'=>'sic_code','type'=>'xsd:string'),
        'ticker_symbol' => array('name'=>'ticker_symbol','type'=>'xsd:string'),
        'shipping_address_street' => array('name'=>'shipping_address_street','type'=>'xsd:string'),
        'shipping_address_city' => array('name'=>'shipping_address_city','type'=>'xsd:string'),
        'shipping_address_state' => array('name'=>'shipping_address_state','type'=>'xsd:string'),
        'shipping_address_postalcode' => array('name'=>'shipping_address_postalcode','type'=>'xsd:string'),
        'shipping_address_country' => array('name'=>'shipping_address_country','type'=>'xsd:string'),
        'deleted' => array('name'=>'deleted','type'=>'xsd:string'),
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
	'update_contact',
        array('user_name'=>'xsd:string', 'id'=>'xsd:string','first_name'=>'xsd:string', 'last_name'=>'xsd:string', 'email_address'=>'xsd:string' , 'account_name'=>'xsd:string', 'salutation'=>'xsd:string', 'title'=>'xsd:string', 'phone_mobile'=>'xsd:string' , 'reports_to'=>'xsd:string', 'primary_address_street'=>'xsd:string', 'primary_address_city'=>'xsd:string', 'primary_address_state'=>'xsd:string' , 'primary_address_postalcode'=>'xsd:string', 'primary_address_country'=>'xsd:string', 'alt_address_city'=>'xsd:string', 'alt_address_street'=>'xsd:string','alt_address_city'=>'xsd:string', 'alt_address_state'=>'xsd:string', 'alt_address_postalcode'=>'xsd:string', 'alt_address_country'=>'xsd:string'),
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
        array('user_name'=>'xsd:string', 'date_entered'=>'xsd:datetime', 'date_modified'=>'xsd:datetime','name'=>'xsd:string','status'=>'xsd:string','priority'=>'xsd:string','description'=>'xsd:string','date_due'=>'xsd:date','contact_name'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
	'update_task',
        array('user_name'=>'xsd:string', 'id'=>'xsd:string', 'date_modified'=>'xsd:datetime','name'=>'xsd:string','status'=>'xsd:string','priority'=>'xsd:string','description'=>'xsd:string','date_due'=>'xsd:date','contact_name'=>'xsd:string'),
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
    array('return'=>'tns:contact_column_array'),
    $NAMESPACE);

$server->register(
    'get_accounts_columns',
    array('user_name'=>'xsd:string','password'=>'xsd:string'),
    array('return'=>'tns:account_column_array'),
    $NAMESPACE);

$server->register(
    'get_leads_columns',
    array('user_name'=>'xsd:string','password'=>'xsd:string'),
    array('return'=>'tns:lead_column_array'),
    $NAMESPACE);

function get_contacts_columns($user_name, $password)
{
    require_once('modules/Contacts/Contact.php');
    $contact = new Contact();
    return $contact->getColumnNames();
}

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

    require_once('modules/Users/User.php');
    $seed_user = new User();
    $user_id = $seed_user->retrieve_user_id($user_name);
    $current_user = $seed_user;
   
    require_once('modules/Contacts/Contact.php');
    $contact = new Contact();
   
    return $contact->getCount($user_name);
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
   
        $output_list[] = Array("first_name"    => $contact->first_name,
            "last_name" => $contact->last_name,
            "primary_address_city" => $contact->primary_address_city,
            "account_name" => $contact->account_name,
	    "account_id"=> $contact->account_id,
            "id" => $contact->id,
            "email_address" => $contact->email1,
            "salutation"=>$contact->salutation,
            "title"=>$contact->title,
            "phone_mobile"=>$contact->phone_mobile,
            "reports_to"=>$contact->reports_to_name,
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



           


    //to remove an erroneous compiler warning
    $seed_contact = $seed_contact;

    return $output_list;
}

function get_tasks_count($user_name, $password)
{   
    
    require_once('modules/Users/User.php');
    $seed_user = new User();
    $user_id = $seed_user->retrieve_user_id($user_name);
    $current_user = $seed_user;
   
    require_once('modules/Tasks/Task.php');
    $task = new Task();
   
    return $task->getCount($user_name);

}

function task_by_range($user_name,$from_index,$offset)
{

        $seed_task = new Task();
        $output_list = Array();
   
         {  
            $response = $seed_task->get_tasks($user_name,$from_index,$offset);
            $taskList = $response['list'];
            foreach($taskList as $temptask)
           {

        		$output_list[] = Array(
                 "name"	=> $temptask->name,
		    	"date_modified" => $temptask->date_modified,
			    "date_entered" => $temptask->date_entered,
                "id" => $temptask->id,
    			
    			"status" => $temptask->status,
		        "date_due" => $temptask->date_due,	
	    		"description" => $temptask->description,		
                "contact_name" => $temptask->contact_name,		
		    	"priority" => $temptask->priority);
                 
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
	
        //echo "contact list is  --------  > " .$contactList;
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
        require_once('modules/Users/User.php');
        $seed_user = new User();
        $user_id = $seed_user->retrieve_user_id($user_name);
        $current_user = $seed_user;

        require_once('modules/Contacts/Contact.php');
        $contact = new Contact();
        $contact->id = $id;
        $contact->delete($contact->id);
//	$contact->delete();
        return "Suceeded in deleting contact";
}

/*
function sync_contact($user_name)
{
  return "synchronized contact successfully";
}
*/


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
		//echo '------------------------ >>>>>>>>>>>>>>>>>>   ' .$$output_list;
	return $output_list;
}  

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
  $filetype= $_FILES['binFile']['type'];
  $filedata = "./cache/mails/".$email_id.$filename;

    
    if($filesize != 0)	
    {
    $data = base64_encode(fread(fopen($filedata, "r"), $filesize)); 
   // $fileid = create_guid();
    $date_entered = date('YmdHis');
    //Retreiving the return module and setting the parent type
    $parent_type = 'Emails';
    $parent_id = $email_id;	 			

    $sql = "INSERT INTO email_attachments ";
    $sql .= "(date_entered,parent_type,parent_id,data, filename, filesize, filetype) ";
    $sql .= "VALUES ('$date_entered','$parent_type','$email_id','$data',";
    $sql .= "'$filename', '$filesize', '$filetype')";

    echo 'sql ois ' .$sql; 
/*    
    $sql = "INSERT INTO email_attachments ";
    $sql .= "(id,date_entered,parent_type,parent_id,data, filename, filesize, filetype) ";
    $sql .= "VALUES ('$fileid','$date_entered','$parent_type','$parent_id','$data',";
    $sql .= "'$filename', '$filesize', '$filetype')";
 echo 'sql ois ' .$sql; 
*/
    
    mysql_query($sql) or die ("Not able to execute insert");
    mysql_close();
    unlink($filedata);

  return "Suceeded in upload_attachment";
    }
}
function track_email($user_name, $contact_ids, $date_sent, $email_subject, $email_body)
{
	//global $log;
	
	//todo make the activity body not be html encoded
	//$log->fatal("In track email: username: $user_name contacts: $contact_ids date_sent: $date_sent"); // activity: $email_body");
	
	
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
	$email->date_start = $date_sent;
	
	// Save one copy of the email message
	$email->save();
	
	// for each contact, add a link between the contact and the email message
	$contact_id_list = explode(";", $contact_ids);

	foreach( $contact_id_list as $contact_id)
	{
		$email->set_emails_contact_invitee_relationship($email->id, $contact_id);
	}
	
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
	
	require_once('modules/Users/User.php');
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	
	require_once('modules/Contacts/Contact.php');
	$contact = new Contact();
	$contact->first_name = $first_name;
	$contact->last_name = $last_name;
	$contact->email1 = $email_address;
        $contact->account_name = $account_name;
        $contact->salutation = $salutation;
        $contact->title = $title;
        $contact->phone_mobile = $phone_mobile;
       // $contact->reports_to_id = $reports_to_id; 
	
	$contact->primary_address_country = $primary_address_country;
	$contact->primary_address_city = $primary_address_city;
        $contact->primary_address_postalcode = $primary_address_postalcode;
        $contact->primary_address_state = $primary_address_state;
        $contact->primary_address_street = $primary_address_street;
        $contact->alt_address_country = $alt_address_country;
        $contact->alt_address_postalcode = $alt_address_postalcode;
        $contact->alt_address_state = $alt_address_state;
        $contact->alt_address_street = $alt_address_street;
	$contact->alt_address_city = $alt_address_city; 
	$contact->assigned_user_id=$user_id;
	
	$account_id = retrieve_account_id($account_name,$user_id);
 	$contact->account_id=$account_id;
        $contact->reports_to_id = retrievereportsto($reports_to,$user_id,$account_id); 
	$contact->save();
	
	return $contact->id;
}

function retrievereportsto($reports_to,$user_id,$account_id)
{
  if($reports_to=="")
    {
     //  echo "\nretruninhg null\n";
        return null;
    }
     if($reports_to==null)
     {
      //  echo "\nretruninhg null\n";
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
    $query = "select contacts.id contactid from contacts where contacts.deleted=0 and contacts.first_name like '".$first_name ."' and contacts.last_name like '" .$last_name ."'";


  // echo "<br> Query : $query <br>";

    	require_once('modules/Contacts/Contact.php');
	    $contact = new Contact();


    $db = new PearDatabase();
    $result= $db->query($query) or  die ("Not able to execute retyrievereports query");



    $rows_count =  $db->getRowCount($result);
    // echo "\nCount ".$rows_count ."\n";
    if($rows_count==0)
    {
    	$contact->first_name = $first_name;
        $contact->last_name = $last_name;
    	$contact->assigned_user_id=$user_id;
        $contact->account_id=$account_id;
    	$contact->save();
        mysql_close();
    	return $contact->id;
    }
    else if ($rows_count==1)
    {
        $row = $db->fetchByAssoc($result, 0);
        mysql_close();
        return $row["contactid"];	    
    }
    else
    {
        $row = $db->fetchByAssoc($result, 0);
        mysql_close();
        return $row["contactid"];	    
    }

}

function retrieve_account_id($account_name,$user_id)
{
  if($account_name=="")
    {
        return null;
    }

    $query = "select accounts.name accountname,accounts.id accountid from accounts where accounts.deleted=0 and accounts.name='" .$account_name."'";

//  echo "\n".$query;

    $result=  mysql_query($query) or die ("Not able to execute insert");
    $db = new PearDatabase();
    $rows_count =  $db->getRowCount($result);
  //  echo "\nCount ".$rows_count ."\n";
    if($rows_count==0)
    {
    	require_once('modules/Accounts/Account.php');
	    $account = new Account();
    	$account->name = $account_name;
    	$account->assigned_user_id=$user_id;
    	$account->save();
        mysql_close();
    	return $account->id;
    }
    else if ($rows_count==1)
    {
        $row = $db->fetchByAssoc($result, 0);
        mysql_close();
        return $row["accountid"];	    
    }
    else
    {
        $row = $db->fetchByAssoc($result, 0);
        mysql_close();
        return $row["accountid"];	    
    }
    
}


function create_task($user_name, $date_entered, $date_modified,$name,$status,$priority,$description,$date_due,$contact_name)
{
	//global $log;
	
	//todo make the activity body not be html encoded
	//$log->fatal("In Create contact: username: $user_name first/last/email ($first_name, $last_name, $email_address)");
	
	require_once('modules/Users/User.php');
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	
	require_once('modules/Contacts/Contact.php');
	$task = new Task();
	//$task->date_entered = $date_entered;
	//$task->date_modified = $date_modified;
	$task->assigned_user_id = $assigned_user_id;
        $task->name = $name;
	$task->status=$status;
	$task->priority=$priority;
	$task->description=$description;
	$task->date_due=$date_due;

    	$task->contact_id= retrievereportsto($contact_name,$user_id,null); 
	$task->assigned_user_id=$user_id;
	
        $task->save();
	
	return $task->id;
}


function update_contact($user_name,$id, $first_name, $last_name, $email_address ,$account_name , $salutation , $title, $phone_mobile, $reports_to,$primary_address_street,$primary_address_city,$primary_address_state,$primary_address_postalcode,$primary_address_country,$alt_address_city,$alt_address_street,$alt_address_state,$alt_address_postalcode,$alt_address_country)
{
	require_once('modules/Users/User.php');
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	
	require_once('modules/Contacts/Contact.php');
	$contact = new Contact();
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
	
	return "Suceeded";
}


function update_task($user_name, $id,$date_modified, $name, $status,$priority,$description,$date_due,$contact_name)
{
	require_once('modules/Users/User.php');
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($user_name);
	$current_user = $seed_user;
	
	require_once('modules/Tasks/Task.php');
	$task = new Task();
	$task->user_name = $user_name;
	$task->id = $id;
//	$task->date_modified = $date_modified;
	$task->name = $name;
        $task->status = $status;
	$task->priority = $priority;
	$task->description = $description;
	$task->date_due = $date_due;
	$task->contact_id= retrievereportsto($contact_name,$user_id,null); 
        $task->save();
        return "Suceeded in updating task";
}



function delete_task($user_name,$id)
{
        require_once('modules/Users/User.php');
        $seed_user = new User();
        $user_id = $seed_user->retrieve_user_id($user_name);
        $current_user = $seed_user;

        require_once('modules/Tasks/Task.php');
        $task = new Task();
        $task->id = $id;
        $task->delete($task->id);
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
			"date_entered" => $temptask->date_entered,
			"id" => $temptask->id,

			"status" => $temptask->status,
		        "date_due" => $temptask->date_due,	
			"description" => $temptask->description,		
			"priority" => $temptask->priority);
	}
	
	return $output_list;
}  



//$log->fatal("In soap.php");

/* Begin the HTTP listener service and exit. */ 
$server->service($HTTP_RAW_POST_DATA); 

exit(); 


?>
