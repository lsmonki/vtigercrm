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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/install/populateSeedData.php,v 1.17 2005/03/25 20:13:52 simian Exp $
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
require_once('modules/Products/Product.php');
require_once('modules/Products/PriceBook.php');
require_once('modules/Products/Vendor.php');
require_once('modules/Faq/Faq.php');
require_once('modules/HelpDesk/HelpDesk.php');

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
$vendor_ids = Array();
$product_ids = Array();
$pricebook_ids = Array();

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

global $current_user;

$current_user = new User();
$result = $current_user->retrieve($assigned_user_id);


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

	//$account->saveentity("Accounts");
	$account->save("Accounts");
	
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

	//$opp->saveentity("Potentials");
	$opp->save("Potentials");
	
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

	//$contact->saveentity("Contacts");
	$contact->save("Contacts");
	$contact_ids[] = $contact->id;

	
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

	//$lead->saveentity("Leads");
	$lead->save("Leads");

//	$adb->println("PSD Lead [".$lead->id."] - name=".$lead->column_fields["lastname"]);

}

// Temp fix since user is not logged in while populating data updating creatorid in crmentity - GS


//Populating Vendor Data
for($i=0; $i<10; $i++)
{
	$vendor = new Vendor();
	$vendor->column_fields["vendorname"] = ucfirst(strtolower($first_name_array[$i]));
	$vendor->column_fields["company_name"] = ucfirst(strtolower($company_name_array[$i]));
	$vendor->column_fields["phone"] = create_phone_number();
	$vendor->column_fields["email"] = strtolower($vendor->column_fields["vendorname"])."@company.com";
	$website = str_replace($whitespace, "", strtolower($vendor->column_fields["company_name"]));
        $vendor->column_fields["website"] = "www.".$website.".com";

	$vendor->column_fields["assigned_user_id"] = $assigned_user_id;
	

	
	// Fill in a bogus address
	$vendor->column_fields["treet"] = $street_address_array[rand(0,$street_address_count-1)]; 
	$key = array_rand($city_array);
	$vendor->column_fields["city"] = $city_array[$key];
	$vendor->column_fields["state"] = "CA";
	$vendor->column_fields["postalcode"] = '99999';
	$vendor->column_fields["country"] = 'USA';	

	$vendor->save("Vendor");
	$vendor_ids[] = $vendor->id;


}


//Populating Product Data

$product_name_array= array( "Vtiger Single User Pack", "Vtiger 5 Users Pack", "Vtiger 10 Users Pack",
        "Vtiger 25 Users Pack", "Vtiger 50 Users Pack", "Double Panel See-thru Clipboard",
        "abcd1234", "Cd-R CD Recordable", "Sharp - Plain Paper Fax" , "Brother Ink Jet Cartridge"); 
$product_code_array= array("001","002","003","023","005","sg-106","1324356","sg-108","sg-119","sg-125");
$subscription_rate=array("149","699","1299","2999","4995");

for($i=0; $i<10; $i++)
{
        $product = new Product();
	if($i>4)
	{
		$parent_key = array_rand($opportunity_ids);
		$product->column_fields["parent_id"]=$opportunity_ids[$parent_key];

		$usageunit	=	"Each";
		$qty_per_unit	=	1;
		$qty_in_stock	=	rand(10000, 99999);
		$category 	= 	"Hardware";		
		$website 	=	"";
		$manufacturer	= 	"";
		$commission_rate=	rand(10,99);
		$unit_price	=	rand(100,999);
	}
	else
	{
		$account_key = array_rand($account_ids);
		$product->column_fields["parent_id"]=$account_ids[$account_key];

		$usageunit	=	"";
		$qty_per_unit	=	"";
		$qty_in_stock	=	"";
		$category 	= 	"Software";	
		$website 	=	"www.vtiger.com";
		$manufacturer	= 	"vtiger";
		$commission_rate=	0;
		$unit_price	=	$subscription_rate[$i];
	}

        $product->column_fields["productname"] 	= 	$product_name_array[$i];
        $product->column_fields["productcode"] 	= 	$product_code_array[$i];
        $product->column_fields["manufacturer"]	= 	$manufacturer;

	$product->column_fields["productcategory"] = 	$category;
        $product->column_fields["website"] 	=	$website;
        $product->column_fields["productsheet"] =	"";

	$vendor_key = array_rand($vendor_ids);
        $product->column_fields["vendor_id"] 	= 	$vendor_ids[$vendor_key];
	$contact_key = array_rand($contact_ids);
        $product->column_fields["contact_id"] 	= 	$contact_ids[$contact_key];

        $product->column_fields["start_date"] 	= 	& create_date();
        $product->column_fields["sales_start_date"] 	= & create_date();

        $product->column_fields["unit_price"] 	= 	$unit_price;
        $product->column_fields["commissionrate"] = 	$commission_rate;
        $product->column_fields["taxclass"] 	= 	'SalesTax';
        $product->column_fields["usageunit"]	= 	$usageunit;
     	$product->column_fields["qty_per_unit"] = 	$qty_per_unit;
        $product->column_fields["qtyinstock"] 	= 	$qty_in_stock;
      	//$product->column_fields["reorderlevel"] =	rand(10, 99);

	$product->save("Products");
	$product_ids[] = $product ->id;
}


//Populating HelpDesk- FAQ Data

	$status_array=array ("Draft","Reviewed","Published");
	$question_array=array (
	"How to migrate data from previous versions to the latest version?",
	"Error message: The file is damaged and could not be repaired.",
	"A program is trying to access e-mail addresses you have stored in Outlook. Do you want to allow this? If this is unexpected, it may be a virus and you should choose No when trying to add Email to vitger CRM ",
	"When trying to merge a template with a contact, First I was asked allow installation of ActiveX control. I accepted. After it appears a message that it will not be installed because it can't verify the publisher. Do you have a workarround for this issue ?",
	" Error message - please close all instances of word before using the vtiger word plugin. Do I need to close all Word and Outlook instances first before I can reopen Word and sign in?"
	
	);


	$answer_array=array (
	"Database migration scripts are available to migrate from the following versions:

	1.0 to 2.0

	2.0 to 2.1

	2.1 to 3.0

	3.0 to 3.2

	3.2 to 4.0

	4.0 to 4.0.1

	4.0.1 to 4.2",
	
	"The above error message is due to version incompatibility between FPDF and PHP5. Use PHP 4.3.X version","Published",
	"The above error message is displayed if you have installed the Microsoft(R) Outlook(R) E-mail Security Update. Please refer to the following URL for complete details:

http://support.microsoft.com/default.aspx?scid=kb%3BEN-US%3B263074

If you want to continue working with vtiger Outlook Plug-in, select the Allow access for check box and select the time from drop-down box.",
	" Since, vtigerCRM & all plugins are open source, it is not signed up with third party vendors and IE will ask to download even though the plugin are not signed.

This message if produced by Microsoft Windows XP. I English Windows XP with the SP2 and the last updates. I told IE to accept installation of the ActiveX, but after it, this message has appeared. Provably there is a place where to tall to WinXP to not validate if the code is signed... but I don\'t know where.

In IE from Tools->Internet Options->Security->Custom Level, there you can see various options for downloading plugins which are not signed and you can adjust according to your need, so relax your security settings for a while and give a try to vtiger Office Plugin.",
	"Before modifying any templates, please ensure that you don\'t have any documents open and only one instance of word is available in your memory."
	);

$num_array=array(0,1,2,3,4);
for($i=0;$i<5;$i++)
{

	$faq = new Faq();
	
	$rand=array_rand($num_array);
	$faq->column_fields["product_id"]	= $product_ids[$i];
	$faq->column_fields["faqcategories"]	= "General";
	$faq->column_fields["faqstatus"] 	= $status_array[$i];
	$faq->column_fields["question"]		= $question_array[$i];
	$faq->column_fields["faq_answer"]	= $answer_array[$i];

	$faq->save("Faq");
	$faq_ids[] = $faq ->id;
}

// Populate Ticket data


//$severity_array=array("Minor","Major","Critical","");
$status_array=array("Open","In Progress","Wait For Response","Open","closed");
$category_array=array("Big Problem ","Small Problem","Other Problem","Small Problem","Other Problem");
$ticket_title_array=array("Upload Attachment problem",
			"Individual Customization -Menu and RSS","Export Output query",
		"Import Error CSV Leads","How to automatically add a lead from a web form to VTiger");

for($i=0;$i<5;$i++)
{
	$helpdesk= new HelpDesk();
	
	$rand=array_rand($num_array);
	$contact_key = array_rand($contact_ids);
        $helpdesk->column_fields["parent_id"] 	= 	$contact_ids[$contact_key];

	$helpdesk->column_fields["ticketpriorities"]= "Normal";
	$helpdesk->column_fields["product_id"]	= 	$product_ids[$i];

	$helpdesk->column_fields["ticketseverities"]	= "Minor";
	$helpdesk->column_fields["ticketstatus"]	= $status_array[$i];
	$helpdesk->column_fields["ticketcategories"]	= $category_array[$i];
	//$rand_key = array_rand($s);
	$helpdesk->column_fields["ticket_title"]	= $ticket_title_array[$i];
	
	$helpdesk->save("HelpDesk");
	$helpdesk_ids[] = $helpdesk->id;
}

// Populate Activities Data
$task_array=array("Tele Conference","Call user - John","Send Fax to Mary Smith");
$event_array=array("","","Call Smith","Team Meeting","Call Richie","Meeting with Don");
$task_status_array=array("Not Started","In Progress","Completed");
$task_priority_array=array("High","Medium","Low");

for($i=0;$i<6;$i++)
{
	$event = new Activity();
	
	$rand_num=array_rand($num_array);

	$rand_date = & create_date();
	$en=explode("/",$rand_date);
	if($en[1]<10)
		$en[1]="0".$en[1];
	if($en[2]<10)
		$en[2]="0".$en[2];
	$recur_daily_date=date('Y-m-d',mktime(0,0,0,date($en[1]),date($en[2])+5,date($en[0])));
	$recur_week_date=date('Y-m-d',mktime(0,0,0,date($en[1]),date($en[2])+30,date($en[0])));


	$start_time_hr=rand(00,23);
	$start_time_min=rand(00,59);
	if($start_time_hr<10)
		$start_time_hr="0".$start_time_hr;
	if($start_time_min<10)
		$start_time_min="0".$start_time_min;

	$start_time=$start_time_hr.":".$start_time_min;
	if($i<2)
	{
		$event->column_fields["subject"]	= $task_array[$i];	
		if($i==1)
		{
			$account_key = array_rand($account_ids);
			$event->column_fields["parent_id"]	= $account_ids[$account_key];;	
		}
		$event->column_fields["taskstatus"]	= $task_status_array[$i];	
		$event->column_fields["taskpriority"]	= $task_priority_array[$i];	
		$event->column_fields["activitytype"]	= "Task";	
				
	}
	else
	{
		$event->column_fields["subject"]	= $event_array[$i];	
		$event->column_fields["duration_hours"]	= rand(0,3);	
		$event->column_fields["duration_minutes"]= rand(0,59);	
		$event->column_fields["eventstatus"]	= "Planned";	
	}
	$event->column_fields["date_start"]	= $rand_date;	
	$event->column_fields["time_start"]	= $start_time;	
	$event->column_fields["due_date"]	= $rand_date;	
	
	$contact_key = array_rand($contact_ids);
        $event->column_fields["contact_id"]	= 	$contact_ids[$contact_key];
	if($i==4)
	{
        	$event->column_fields["recurringtype"] 	= "Daily";
		$event->column_fields["activitytype"]	= "Meeting";	
		$event->column_fields["due_date"]	= $recur_daily_date;	
	}
	elseif($i==5)
	{	
        	$event->column_fields["recurringtype"] 	= "Weekly";
		$event->column_fields["activitytype"]	= "Meeting";	
		$event->column_fields["due_date"]	= $recur_week_date;	
	}
	else
	{
		$event->column_fields["activitytype"]	= "Call";	
	}

	$event->save("Activities");
        $event_ids[] = $event->id;

}


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
