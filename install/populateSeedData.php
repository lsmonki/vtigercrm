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
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/install/populateSeedData.php,v 1.15 2005/03/05 08:10:08 jack Exp $
 * Description:  Executes a step in the installation process.
 ********************************************************************************/

require_once('config.php');

require_once('modules/Leads/Lead.php');
require_once('modules/Contacts/contactSeedData.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Potentials/Opportunity.php');
require_once('modules/Activities/Activity.php');
require_once('include/database/PearDatabase.php');
require_once('include/utils.php');
require_once('include/language/en_us.lang.php');
require_once('include/ComboStrings.php');
require_once('include/ComboUtil.php');

global $first_name_array;
global $first_name_count;
global $last_name_array;
global $last_name_count;
global $company_name_array;
global $company_name_count;
global $street_address_array;
global $street_address_count;
global $city_array;
global $city_array_count;
 $db = new PearDatabase();

function add_digits($quantity, &$string, $min = 0, $max = 9)
{
	for($i=0; $i < $quantity; $i++)
	{
		$string .= rand($min,$max);
	}
}

function create_phone_number()
{
	$phone = "(";
$phone = $phone; // This line is useless, but gets around a code analyzer warning.  Bug submitted 4/28/04
	add_digits(3, $phone);
	$phone .= ") ";
	add_digits(3, $phone);
	$phone .= "-";
	add_digits(4, $phone);
	
	return $phone;
}

function create_date()
{
	$date = "";
	$date .= "2005";
	$date .= "/";
	$date .= rand(1,9);
	$date .= "/";
	$date .= rand(1,28);
	
	return $date;
}

//$adb->println("PSD dumping started");

$account_ids = Array();
$opportunity_ids = Array();

// Determine the assigned user for all demo data.  This is the default user if set, or admin
$assigned_user_name = "admin";
if(isset($default_user_name) && $default_user_name != '' && isset($create_default_user) && $create_default_user)
{
	$assigned_user_name = $default_user_name;
}

// Look up the user id for the assigned user
$seed_user = new User();

//$adb->println("PSD assignname=".$assigned_user_name);

$assigned_user_id = $seed_user->retrieve_user_id($assigned_user_name);

// Get _dom arrays
$comboFieldNames = Array('leadsource'=>'leadsource_dom'
		      ,'leadstatus'=>'lead_status_dom'
		      ,'industry'=>'industry_dom'
		      ,'rating'=>'rating_dom'
                      ,'opportunity_type'=>'opportunity_type_dom'
                      ,'sales_stage'=>'sales_stage_dom');
$comboFieldArray = getComboArray($comboFieldNames);

//$adb->println("PSD assignid=".$assigned_user_id);
$adb->println("company_name_array");
$adb->println($company_name_array);

for($i = 0; $i < $company_name_count; $i++)
{
	
	$account_name = $company_name_array[$i];

	// Create new accounts.
	$account = new Account();
	$account->column_fields["accountname"] = $account_name;
	$account->column_fields["phone"] = create_phone_number();
	$account->column_fields["assigned_user_id"] = $assigned_user_id;

	$whitespace = array(" ", ".", "&", "\/");
	$website = str_replace($whitespace, "", strtolower($account->column_fields["accountname"]));
	$account->column_fields["website"] = "www.".$website.".com";
	
	$account->column_fields["bill_street"] = $street_address_array[rand(0,$street_address_count-1)];
	$account->column_fields["bill_city"] = $city_array[rand(0,$city_array_count-1)];
	$account->column_fields["bill_state"] = "CA";
	$account->column_fields["bill_code"] = rand(10000, 99999);
	$account->column_fields["bill_country"] = 'USA';	

	$account->column_fields["ship_street"] = $account->column_fields["bill_street"];
	$account->column_fields["ship_city"] = $account->column_fields["bill_city"];
	$account->column_fields["ship_state"] = $account->column_fields["bill_state"];
	$account->column_fields["ship_code"] = $account->column_fields["bill_code"];
	$account->column_fields["ship_country"] = $account->column_fields["bill_country"];	

//      $key = array_rand($app_list_strings['industry_dom']);
//      $account->industry = $app_list_strings['industry_dom'][$key];
	$key = array_rand($comboFieldArray['industry_dom']);
	$account->column_fields["industry"] = $comboFieldArray['industry_dom'][$key];

	$account->column_fields["account_type"] = "Customer";

	$account->saveentity("Accounts");
	
	$account_ids[] = $account->id;

//	$adb->println("PSD Account [".$account->id."] - ".$account_name);
	
	//Create new opportunities
	$opp = new Potential();

	$opp->column_fields["assigned_user_id"] = $assigned_user_id;
	$opp->column_fields["potentialname"] = $account_name." - 1000 units";
	$opp->column_fields["closingdate"] = & create_date();

//      $key = array_rand($app_list_strings['lead_source_dom']);
//      $opp->lead_source = $app_list_strings['lead_source_dom'][$key];
	$key = array_rand($comboFieldArray['leadsource_dom']);
	$opp->column_fields["leadsource"] = $comboFieldArray['leadsource_dom'][$key];

//      $key = array_rand($app_list_strings['sales_stage_dom']);
//      $opp->sales_stage = $app_list_strings['sales_stage_dom'][$key];
	$key = array_rand($comboFieldArray['sales_stage_dom']);
	$opp->column_fields["sales_stage"] = $comboFieldArray['sales_stage_dom'][$key];
	
//      $key = array_rand($app_list_strings['opportunity_type_dom']);
//      $opp->opportunity_type = $app_list_strings['opportunity_type_dom'][$key];
	$key = array_rand($comboFieldArray['opportunity_type_dom']);
	$opp->column_fields["opportunity_type"] = $comboFieldArray['opportunity_type_dom'][$key];

	$amount = array("10000", "25000", "50000", "75000"); 
	$key = array_rand($amount);
	$opp->column_fields["amount"] = $amount[$key];
	$opp->column_fields["account_id"] = $account->id;

	$opp->saveentity("Potentials");
	
	$opportunity_ids[] = $opp->id;

//	$adb->println("PSD Potential [".$opp->id."] - account[".$account->id."]");
	
}


for($i=0; $i<10; $i++)
{
	$contact = new Contact();
	$contact->column_fields["firstname"] = ucfirst(strtolower($first_name_array[$i]));
	$contact->column_fields["lastname"] = ucfirst(strtolower($last_name_array[$i]));
	$contact->column_fields["assigned_user_id"] = $assigned_user_id;
	
	$contact->column_fields["email"] = strtolower($contact->column_fields["firstname"])."_".strtolower($contact->column_fields["lastname"])."@company.com";

	$contact->column_fields["phone"] = create_phone_number();
	$contact->column_fields["homephone"] = create_phone_number();
	$contact->column_fields["mobile"] = create_phone_number();
	
	// Fill in a bogus address
	$key = array_rand($street_address_array);
	$contact->column_fields["mailingstreet"] = $street_address_array[$key];
	$key = array_rand($city_array);
	$contact->column_fields["mailingcity"] = $city_array[$key];
	$contact->column_fields["mailingstate"] = "CA";
	$contact->column_fields["mailingzip"] = '99999';
	$contact->column_fields["mailingcountry"] = 'USA';	
	if ($contact->column_fields["mailingcity"] == "San Mateo") 
		$contact->column_fields["yahooid"] = "clint_oram";
	elseif ($contact->column_fields["mailingcity"] == "San Francisco") 
		$contact->column_fields["yahooid"] = "not_a_real_id";

//      $key = array_rand($app_list_strings['lead_source_dom']);
//      $contact->lead_source = $app_list_strings['lead_source_dom'][$key];
	$key = array_rand($comboFieldArray['leadsource_dom']);
	$contact->column_fields["leadsource"] = $comboFieldArray['leadsource_dom'][$key];

	$titles = array("President", 
					"VP Operations", 
					"VP Sales", 
					"Director Operations", 
					"Director Sales", 
					"Mgr Operations", 
					"IT Developer", 
					"");
	$key = array_rand($titles);
	$contact->column_fields["title"] = $titles[$key];
	
	$account_key = array_rand($account_ids);
	$contact->column_fields["account_id"] = $account_ids[$account_key];

	$contact->saveentity("Contacts");

	
	// This assumes that there will be one opportunity per company in the seed data.
	$opportunity_key = array_rand($opportunity_ids);
	//$query = "insert into opportunities_contacts set id='".create_guid()."', contact_id='$contact->id', contact_role='".$app_list_strings['opportunity_relationship_type_default_key']."', opportunity_id='".$opportunity_ids[$opportunity_key]."'";
	//$db->query($query, true, "unable to create seed links between opportunities and contacts");

	$query = "insert into contpotentialrel ( contactid, potentialid ) values (".$contact->id.",".$opportunity_ids[$opportunity_key].")";
	$db->query($query);

//	$adb->println("PSD Contact [".$contact->id."] - account[".$account_ids[$account_key]."] - potential[".$opportunity_ids[$opportunity_key]."]");

	//Create new tasks
	/*$task = new Task();

	$key = array_rand($task->default_task_name_values);
	$task->name = $task->default_task_name_values[$key];
	$task->date_due = & create_date();
	$task->time_due = date("H:i:s",time());
	$task->date_due_flag = 'off';
	$task->assigned_user_id = $assigned_user_id;
	
	$key = array_rand($app_list_strings['task_status_dom']);
	$task->status = $app_list_strings['task_status_dom'][$key];
	$task->contact_id = $contact->id;
	if ($contact->primary_address_city == "San Mateo") {
		$task->parent_id = $account_ids[$account_key];
		$task->parent_type = 'Accounts';
		$task->save();
	}*/

}

for($i=0; $i<10; $i++)
{
	$lead = new Lead();
	$lead->column_fields["firstname"] = ucfirst(strtolower($first_name_array[$i]));
	$lead->column_fields["lastname"] = ucfirst(strtolower($last_name_array[$i]));
	$lead->column_fields["company"] = ucfirst(strtolower($company_name_array[$i]));
	$lead->column_fields["assigned_user_id"] = $assigned_user_id;
	
	$lead->column_fields["email"] = strtolower($lead->column_fields["firstname"])."_".strtolower($lead->column_fields["lastname"])."@company.com";

	$lead->column_fields["phone"] = create_phone_number();
	$lead->column_fields["mobile"] = create_phone_number();
	
	// Fill in a bogus address
	$key = array_rand($street_address_array);
	//$lead->address_street = $street_address_array[$key];
	$key = array_rand($city_array);
	$lead->column_fields["city"] = $city_array[$key];
	$lead->column_fields["state"] = "CA";
	$lead->column_fields["code"] = '99999';
	$lead->column_fields["country"] = 'USA';	
	if ($lead->column_fields["city"] == "San Mateo") 
		$lead->column_fields["yahooid"] = "clint_oram";
	elseif ($lead->column_fields["city"] == "San Francisco") 
		$lead->column_fields["yahooid"] = "not_a_real_id";

//      $key = array_rand($app_list_strings['lead_source_dom']);
//      $lead->lead_source = $app_list_strings['lead_source_dom'][$key];
	$key = array_rand($comboFieldArray['leadsource_dom']);
	$lead->column_fields["leadsource"] = $comboFieldArray['leadsource_dom'][$key];

//      $key = array_rand($app_list_strings['lead_status_dom']);
//      $lead->lead_status = $app_list_strings['lead_status_dom'][$key];
	$key = array_rand($comboFieldArray['lead_status_dom']);
	$lead->column_fields["leadstatus"] = $comboFieldArray['lead_status_dom'][$key];

//      $key = array_rand($app_list_strings['rating_dom']);
//      $lead->rating = $app_list_strings['rating_dom'][$key];
	$key = array_rand($comboFieldArray['rating_dom']);
	$lead->column_fields["rating"] = $comboFieldArray['rating_dom'][$key];	

	$titles = array("President", 
					"VP Operations", 
					"VP Sales", 
					"Director Operations", 
					"Director Sales", 
					"Mgr Operations", 
					"IT Developer", 
					"");
	$key = array_rand($titles);
	$lead->column_fields["designation"] = $titles[$key];

	$lead->saveentity("Leads");

//	$adb->println("PSD Lead [".$lead->id."] - name=".$lead->column_fields["lastname"]);

}

// Temp fix since user is not logged in while populating data updating creatorid in crmentity - GS

$adb->query("update crmentity set crmentity.smcreatorid=".$assigned_user_id);





/*
for($i = 0; $i < $company_name_count; $i++)
{
	
	$account_name = $company_name_array[$i];

	// Create new accounts.
	$account = new Account();
	$account->name = $account_name;
	$account->phone_office = create_phone_number();
	$account->assigned_user_id = $assigned_user_id;

	$whitespace = array(" ", ".", "&", "\/");
	$website = str_replace($whitespace, "", strtolower($account->name));
	$account->website = "www.".$website.".com";
	
	$account->billing_address_street = $street_address_array[rand(0,$street_address_count-1)];
	$account->billing_address_city = $city_array[rand(0,$city_array_count-1)];
	$account->billing_address_state = "CA";
	$account->billing_address_postalcode = rand(10000, 99999);
	$account->billing_address_country = 'USA';	

	$account->shipping_address_street = $account->billing_address_street;
	$account->shipping_address_city = $account->billing_address_city;
	$account->shipping_address_state = $account->billing_address_state;
	$account->shipping_address_postalcode = $account->billing_address_postalcode;
	$account->shipping_address_country = $account->billing_address_country;	

//      $key = array_rand($app_list_strings['industry_dom']);
//      $account->industry = $app_list_strings['industry_dom'][$key];
	$key = array_rand($comboFieldArray['industry_dom']);
	$account->industry = $comboFieldArray['industry_dom'][$key];

	$account->account_type = "Customer";

	$account->save();
	
	$account_ids[] = $account->id;
	
	//Create new opportunities
	$opp = new Opportunity();

	$opp->assigned_user_id = $assigned_user_id;
	$opp->name = $account_name." - 1000 units";
	$opp->date_closed = & create_date();

//      $key = array_rand($app_list_strings['lead_source_dom']);
//      $opp->lead_source = $app_list_strings['lead_source_dom'][$key];
	$key = array_rand($comboFieldArray['leadsource_dom']);
	$opp->leadsource = $comboFieldArray['leadsource_dom'][$key];

//      $key = array_rand($app_list_strings['sales_stage_dom']);
//      $opp->sales_stage = $app_list_strings['sales_stage_dom'][$key];
	$key = array_rand($comboFieldArray['sales_stage_dom']);
	$opp->sales_stage = $comboFieldArray['sales_stage_dom'][$key];
	
//      $key = array_rand($app_list_strings['opportunity_type_dom']);
//      $opp->opportunity_type = $app_list_strings['opportunity_type_dom'][$key];
	$key = array_rand($comboFieldArray['opportunity_type_dom']);
	$opp->opportunity_type = $comboFieldArray['opportunity_type_dom'][$key];

	$amount = array("10000", "25000", "50000", "75000"); 
	$key = array_rand($amount);
	$opp->amount = $amount[$key];

	$opp->save();
	
	$opportunity_ids[] = $opp->id;
	// Create a linking table entry to assign an account to the opportunity.
	
	$query = "insert into accounts_opportunities set id='".create_guid()."', opportunity_id='$opp->id', account_id='$account->id'";
	global $db;
	$db->query($query);
}


for($i=0; $i<1000; $i++)
{
	$contact = new Contact();
	$contact->first_name = ucfirst(strtolower($first_name_array[$i]));
	$contact->last_name = ucfirst(strtolower($last_name_array[$i]));
	$contact->assigned_user_id = $assigned_user_id;
	
	$contact->email1 = strtolower($contact->first_name)."_".strtolower($contact->last_name)."@company.com";

	$contact->phone_work = create_phone_number();
	$contact->phone_home = create_phone_number();
	$contact->phone_mobile = create_phone_number();
	
	// Fill in a bogus address
	$key = array_rand($street_address_array);
	$contact->primary_address_street = $street_address_array[$key];
	$key = array_rand($city_array);
	$contact->primary_address_city = $city_array[$key];
	$contact->primary_address_state = "CA";
	$contact->primary_address_postalcode = '99999';
	$contact->primary_address_country = 'USA';	
	if ($contact->primary_address_city == "San Mateo") 
		$contact->yahoo_id = "clint_oram";
	elseif ($contact->primary_address_city == "San Francisco") 
		$contact->yahoo_id = "not_a_real_id";

//      $key = array_rand($app_list_strings['lead_source_dom']);
//      $contact->lead_source = $app_list_strings['lead_source_dom'][$key];
	$key = array_rand($comboFieldArray['leadsource_dom']);
	$contact->leadsource = $comboFieldArray['leadsource_dom'][$key];

	$titles = array("President", 
					"VP Operations", 
					"VP Sales", 
					"Director Operations", 
					"Director Sales", 
					"Mgr Operations", 
					"IT Developer", 
					"");
	$key = array_rand($titles);
	$contact->title = $titles[$key];

	$contact->save();

	// Create a linking table entry to assign an account to the contact.
	$account_key = array_rand($account_ids);
	$query = "insert into accounts_contacts set id='".create_guid()."', contact_id='$contact->id', account_id='".$account_ids[$account_key]."'";
	global $db;
	$db->query($query, true, " unable to create seed links between accounts and contacts:");
	
	// This assumes that there will be one opportunity per company in the seed data.
	$opportunity_key = array_rand($opportunity_ids);
	$query = "insert into opportunities_contacts set id='".create_guid()."', contact_id='$contact->id', contact_role='".$app_list_strings['opportunity_relationship_type_default_key']."', opportunity_id='".$opportunity_ids[$opportunity_key]."'";
	$db->query($query, true, "unable to create seed links between opportunities and contacts");

	//Create new tasks
	$task = new Task();

	$key = array_rand($task->default_task_name_values);
	$task->name = $task->default_task_name_values[$key];
	$task->date_due = & create_date();
	$task->time_due = date("H:i:s",time());
	$task->date_due_flag = 'off';
	$task->assigned_user_id = $assigned_user_id;
	
	$key = array_rand($app_list_strings['task_status_dom']);
	$task->status = $app_list_strings['task_status_dom'][$key];
	$task->contact_id = $contact->id;
	if ($contact->primary_address_city == "San Mateo") {
		$task->parent_id = $account_ids[$account_key];
		$task->parent_type = 'Accounts';
		$task->save();
	}

}

for($i=0; $i<100; $i++)
{
	$lead = new Lead();
	$lead->first_name = ucfirst(strtolower($first_name_array[$i]));
	$lead->last_name = ucfirst(strtolower($last_name_array[$i]));
	$lead->company = ucfirst(strtolower($company_name_array[$i]));
	$lead->assigned_user_id = $assigned_user_id;
	
	$lead->email = strtolower($lead->first_name)."_".strtolower($lead->last_name)."@company.com";

	$lead->phone = create_phone_number();
	$lead->mobile = create_phone_number();
	
	// Fill in a bogus address
	$key = array_rand($street_address_array);
	$lead->address_street = $street_address_array[$key];
	$key = array_rand($city_array);
	$lead->address_city = $city_array[$key];
	$lead->address_state = "CA";
	$lead->address_postalcode = '99999';
	$lead->address_country = 'USA';	
	if ($lead->address_city == "San Mateo") 
		$lead->yahoo_id = "clint_oram";
	elseif ($lead->address_city == "San Francisco") 
		$lead->yahoo_id = "not_a_real_id";

//      $key = array_rand($app_list_strings['lead_source_dom']);
//      $lead->lead_source = $app_list_strings['lead_source_dom'][$key];
	$key = array_rand($comboFieldArray['leadsource_dom']);
	$lead->leadsource = $comboFieldArray['leadsource_dom'][$key];

//      $key = array_rand($app_list_strings['lead_status_dom']);
//      $lead->lead_status = $app_list_strings['lead_status_dom'][$key];
	$key = array_rand($comboFieldArray['lead_status_dom']);
	$lead->lead_status = $comboFieldArray['lead_status_dom'][$key];

//      $key = array_rand($app_list_strings['rating_dom']);
//      $lead->rating = $app_list_strings['rating_dom'][$key];
	$key = array_rand($comboFieldArray['rating_dom']);
	$lead->rating = $comboFieldArray['rating_dom'][$key];	

	$titles = array("President", 
					"VP Operations", 
					"VP Sales", 
					"Director Operations", 
					"Director Sales", 
					"Mgr Operations", 
					"IT Developer", 
					"");
	$key = array_rand($titles);
	$lead->designation = $titles[$key];

	$lead->save();

}*/

//$adb->println("PSD - demo data over");


?>
