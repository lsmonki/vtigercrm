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
require_once('modules/Campaigns/Campaign.php');
require_once('modules/Potentials/Opportunity.php');
require_once('modules/Activities/Activity.php');
require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');
require_once('include/language/en_us.lang.php');
require_once('include/ComboStrings.php');
require_once('include/ComboUtil.php');
require_once('modules/Products/Product.php');
require_once('modules/PriceBooks/PriceBook.php');
require_once('modules/Vendors/Vendor.php');
require_once('modules/Faq/Faq.php');
require_once('modules/HelpDesk/HelpDesk.php');
require_once('modules/Notes/Note.php');
require_once('modules/Quotes/Quote.php');
require_once('modules/SalesOrder/SalesOrder.php');
require_once('modules/PurchaseOrder/PurchaseOrder.php');
require_once('modules/Invoice/Invoice.php');
require_once('modules/Emails/Email.php');

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
global $campaign_name_array,$campaign_type_array,$campaign_status_array;
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
	$date .= "2006";
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
$contact_ids = Array();
$product_ids = Array();
$pricebook_ids = Array();
$quote_ids = Array();
$salesorder_ids = Array();
$purchaseorder_ids = Array();
$invoice_ids = Array();
$email_ids = Array();

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

	$company_count=0;
for($i=0; $i<10; $i++)
{
	$lead = new Lead();
	$lead->column_fields["firstname"] = ucfirst(strtolower($first_name_array[$i]));
	$lead->column_fields["lastname"] = ucfirst(strtolower($last_name_array[$i]));

	if($i<5)
       	{
        	$lead->column_fields["company"] = ucfirst(strtolower($company_name_array[$i]));
       	}
       	else
       	{
               	$lead->column_fields["company"] = ucfirst(strtolower($company_name_array[$company_count]));
               	$company_count++;
       	}

	$lead->column_fields["assigned_user_id"] = $assigned_user_id;
	
	$lead->column_fields["email"] = strtolower($lead->column_fields["firstname"])."_".strtolower($lead->column_fields["lastname"])."@company.com";
	
	$website = str_replace($whitespace, "", strtolower(ucfirst(strtolower($company_name_array[$i]))));
        $lead->column_fields["website"] = "www.".$website.".com";
	
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
	$vendor->column_fields["phone"] = create_phone_number();
	$vendor->column_fields["email"] = strtolower($vendor->column_fields["vendorname"])."@company.com";
	$website = str_replace($whitespace, "", strtolower(ucfirst(strtolower($company_name_array[$i]))));
        $vendor->column_fields["website"] = "www.".$website.".com";

	$vendor->column_fields["assigned_user_id"] = $assigned_user_id;
	

	
	// Fill in a bogus address
	$vendor->column_fields["treet"] = $street_address_array[rand(0,$street_address_count-1)]; 
	$key = array_rand($city_array);
	$vendor->column_fields["city"] = $city_array[$key];
	$vendor->column_fields["state"] = "CA";
	$vendor->column_fields["postalcode"] = '99999';
	$vendor->column_fields["country"] = 'USA';	

	$vendor->save("Vendors");
	$vendor_ids[] = $vendor->id;


}


//Populating Product Data

$product_name_array= array( "Vtiger Single User Pack", "Vtiger 5 Users Pack", "Vtiger 10 Users Pack",
        "Vtiger 25 Users Pack", "Vtiger 50 Users Pack", "Double Panel See-thru Clipboard",
        "abcd1234", "Cd-R CD Recordable", "Sharp - Plain Paper Fax" , "Brother Ink Jet Cartridge"); 
$product_code_array= array("001","002","003","023","005","sg-106","1324356","sg-108","sg-119","sg-125");
$subscription_rate=array("149","699","1299","2999","4995");
//added by jeri to populate product images
$product_image_array = array("product1.jpeg###","product2.jpeg###product3.jpeg###","product4.jpeg###product5.jpeg###product6
.jpeg###","product7.jpeg###product8.jpeg###product9.jpeg###product10.jpeg###");
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
		$product_image_name = '';
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
		$product_image_name = $product_image_array[$i];
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
	$product->column_fields["imagename"] =  $product_image_name;

	$product->save("Products");
	$product_ids[] = $product ->id;
}


//Populating HelpDesk- FAQ Data

	$status_array=array ("Draft","Reviewed","Published","Draft","Reviewed","Draft","Reviewed","Draft","Reviewed","Draft","Reviewed","Draft");
	$question_array=array (
	"How to migrate data from previous versions to the latest version?",
	"Error message: The file is damaged and could not be repaired.",
	"A program is trying to access e-mail addresses you have stored in Outlook. Do you want to allow this? If this is unexpected, it may be a virus and you should choose No when trying to add Email to vitger CRM ",
	"When trying to merge a template with a contact, First I was asked allow installation of ActiveX control. I accepted. After it appears a message that it will not be installed because it can't verify the publisher. Do you have a workarround for this issue ?",
	" Error message - please close all instances of word before using the vtiger word plugin. Do I need to close all Word and Outlook instances first before I can reopen Word and sign in?",
	"How to migrate data from previous versions to the latest version?",
	"A program is trying to access e-mail addresses you have stored in Outlook. Do you want to allow this? If this is unexpected, it may be a virus and you should choose No when trying to add Email to vitger CRM ",
	" Error message - please close all instances of word before using the vtiger word plugin. Do I need to close all Word and Outlook instances first before I can reopen Word and sign in?",
	"Error message: The file is damaged and could not be repaired.",
	"When trying to merge a template with a contact, First I was asked allow installation of ActiveX control. I accepted. After it appears a message that it will not be installed because it can't verify the publisher. Do you have a workarround for this issue ?",
	" Error message - please close all instances of word before using the vtiger word plugin. Do I need to close all Word and Outlook instances first before I can reopen Word and sign in?",
	"How to migrate data from previous versions to the latest version?",
	
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

$num_array=array(0,1,2,3,4,6,7,8,9,10,11,12);
for($i=0;$i<12;$i++)
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

//Populate Quote Data

$sub_array = array ("Prod_Quote", "Cont_Quote", "SO_Quote", "PO_Quote", "Vendor_Quote");
$stage_array = array ("Created", "Reviewed", "Delivered", "Accepted" , "Rejected");
$total_array = array ("2085.014", "7985.257", "5748.981", "1245.478", "410.530");
$carrier_array = array ("FedEx", "UPS", "USPS", "DHL", "BlueDart");
$invmgr_array = array ("admin", "user");

for($i=0;$i<5;$i++)
{
	$quote = new Quote();
	
	$quote->column_fields["assigned_user_id"] = $assigned_user_id;
	$account_key = array_rand($account_ids);
	$quote->column_fields["account_id"] = $account_ids[$account_key];
	$op_key = array_rand($opportunity_ids);
	$quote->column_fields["potential_id"] = $opportunity_ids[$op_key];
	$contact_key = array_rand($contact_ids);
        $quote->column_fields["contact_id"] = $contact_ids[$contact_key];
	$rand = array_rand($num_array);
	$quote->column_fields["subject"] = $sub_array[$i];
	$quote->column_fields["quotestage"] = $stage_array[$i];	
	$quote->column_fields["hdnGrandTotal"] = $total_array[$i];
	$quote->column_fields["carrier"] = $carrier_array[$i];
	$quote->column_fields["inventorymanager"] = $invmgr_array[$i];

	$quote->save("Quotes");

	$quote_ids[] = $quote->id;
}

//Populate SalesOrder Data

$subj_array = array ("SO_vtiger", "SO_zoho", "SO_vtiger5usrp", "SO_vt100usrpk", "SO_vendtl");
$status_array = array ("Created",  "Delivered", "Approved" , "Cancelled");
$sototal_array = array ("2085.014", "7985.257", "5748.981", "1245.478", "410.530");
$carrier_array = array ("FedEx", "UPS", "USPS", "DHL", "BlueDart");

for($i=0;$i<5;$i++)
{
	$so = new SalesOrder();
	
	$so->column_fields["assigned_user_id"] = $assigned_user_id;
	$account_key = array_rand($account_ids);
	$so->column_fields["account_id"] = $account_ids[$account_key];
	$quote_key = array_rand($quote_ids);
	$so->column_fields["quote_id"] = $quote_ids[$quote_key];
	$contact_key = array_rand($contact_ids);
        $so->column_fields["contact_id"] = $contact_ids[$contact_key];
	$rand = array_rand($num_array);
	$so->column_fields["subject"] = $subj_array[$i];
	$so->column_fields["sostatus"] = $status_array[$i];	
	$so->column_fields["hdnGrandTotal"] = $sototal_array[$i];
	$so->column_fields["carrier"] = $carrier_array[$i];

	$so->save("SalesOrder");

	$salesorder_ids[] = $so->id;
}


//Populate PurchaseOrder Data

$psubj_array = array ("PO_vtiger", "PO_zoho", "PO_vtiger5usrp", "PO_vt100usrpk", "PO_vendtl");
$pstatus_array = array ("Created",  "Delivered", "Approved" , "Cancelled", "Recieved Shipment");
$pototal_array = array ("2085.014", "7985.257", "5748.981", "1245.478", "410.530");
$carrier_array = array ("FedEx", "UPS", "USPS", "DHL", "BlueDart");
$trkno_array = array ("po1425", "po2587", "po7974", "po7979", "po6411"); 

for($i=0;$i<5;$i++)
{
	$po = new Order();
	
	$po->column_fields["assigned_user_id"] = $assigned_user_id;
	$vendor_key = array_rand($vendor_ids);
	$po->column_fields["vendor_id"] = $vendor_ids[$vendor_key];
	$contact_key = array_rand($contact_ids);
        $po->column_fields["contact_id"] = $contact_ids[$contact_key];
	$rand = array_rand($num_array);
	$po->column_fields["subject"] = $psubj_array[$i];
	$po->column_fields["postatus"] = $pstatus_array[$i];	
	$po->column_fields["hdnGrandTotal"] = $pototal_array[$i];
	$po->column_fields["carrier"] = $carrier_array[$i];
	$po->column_fields["tracking_no"] = $trkno_array[$i];
	
	$po->save("PurchaseOrder");

	$purchaseorder_ids[] = $po->id;
}

//Populate Invoice Data

$isubj_array = array ("vtiger_invoice201", "zoho_inv7841", "vtiger5usrp_invoice71134", "vt100usrpk_inv113", "vendtl_inv214");
$istatus_array = array ("Created",  "Sent", "Approved" , "Credit Invoice", "Paid");
$itotal_array = array ("2085.014", "7985.257", "5748.981", "1245.478", "410.530");

for($i=0;$i<5;$i++)
{
	$invoice = new Invoice();
	
	$invoice->column_fields["assigned_user_id"] = $assigned_user_id;
	$account_key = array_rand($account_ids);
	$invoice->column_fields["accountid"] = $account_ids[$account_key];
	$so_key = array_rand($salesorder_ids);
	$invoice->column_fields["salesorder_id"] = $salesorder_ids[$so_key];
	$contact_key = array_rand($contact_ids);
        $invoice->column_fields["contactid"] = $contact_ids[$contact_key];
	$rand = array_rand($num_array);
	$invoice->column_fields["subject"] = $isubj_array[$i];
	$invoice->column_fields["invoicestatus"] = $istatus_array[$i];	
	$invoice->column_fields["hdnGrandTotal"] = $itotal_array[$i];
	
	$invoice->save("Invoice");

	$invoice_ids[] = $invoice->id;
}

//Populate RSS Data




//Populate Email Data

$esubj_array =  array ("Vtiger Releases 5.0 Alpha4", "Try Zoho Writer", "Hi There!!!", "Welcome to Open Source", "SOS Vtiger");
$startdate_array =  array ("2006-1-2","2003-3-4","2003-4-5","2001-2-1","2005-8-8");
$filename_array = array ("vtiger5alpha.tar.gz", "zohowriter.zip", "hi.doc", "welcome.pps", "sos.doc");

for($i=0;$i<5;$i++)
{
	$email = new Email();

	$email->column_fields["assigned_user_id"] = $assigned_user_id;
	
	$rand = array_rand($num_array);
	$email->column_fields["subject"] = $esubj_array[$i];
	$email->column_fields["filename"] = $filename_array[$i];	
	$email->column_fields["date_start"] = $startdate_array[$i];
	$email->column_fields["semodule"] = 'Tasks';
	$email->column_fields["activitytype"] = 'Emails';
	
	$email->save("Emails");

	$email_ids[] = $email->id;
	
}


//Populate PriceBook data

$PB_array = array ("Cd-R PB", "Vtiger PB", "Gator PB", "Kyple PB", "Pastor PB", "Zoho PB", "PB_100", "Per_PB", "CST_PB", "GATE_PB", "Chevron_PB", "Pizza_PB");
$Active_array = array ("0", "1", "1", "0", "1","0", "1", "1", "0", "1","0","1");

//$num_array = array(0,1,2,3,4);
for($i=0;$i<12;$i++)
{
	$pricebook = new PriceBook();

	$rand = array_rand($num_array);
	$pricebook->column_fields["bookname"]   = $PB_array[$i];
	$pricebook->column_fields["active"]     = $Active_array[$i];

	$pricebook->save("PriceBooks");
	$pricebook_ids[] = $pricebook ->id;
}

//Populate Notes Data

$notes_array = array ("Cont_Notes", "Prod_Notes", "PB_Notes", "Vendor_Notes", "Invoice_Notes", "Task_Notes", "Event_Notes", "Email_Notes", "Rss_Notes", "Qt_Notes", "Notes_Customer", "Notes_PO");
$att_array = array ("cont.doc", "prod.txt", "pb.doc", "vendor.xls", "invoice.xls", "task.doc", "ev.xls", "em.txt", "rss.txt", "qt.txt", "cust.doc", "PO.doc");

for($i=0;$i<12;$i++)
{
	$notes = new Note();

	$rand = array_rand($num_array);
	$contact_key = array_rand($contact_ids);
        $notes->column_fields["contact_id"] 	= 	$contact_ids[$contact_key];
	$notes->column_fields["title"]		=	$notes_array[$i];
	$notes->column_fields["filename"]       =	$att_array[$i];

	$notes->save("Notes");
	$notes_ids[] = $notes ->id;
	
}



// Populate Ticket data


//$severity_array=array("Minor","Major","Critical","");
$status_array=array("Open","In Progress","Wait For Response","Open","Closed");
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
	//$rand_key = array_rand($s);$contact_key = array_rand($contact_ids);
        $notes->column_fields["contact_id"] 	= 	$contact_ids[$contact_key];
	$helpdesk->column_fields["ticket_title"]	= $ticket_title_array[$i];
	
        $helpdesk->column_fields["assigned_user_id"] = $contact_key = array_rand($contact_ids);
        $notes->column_fields["contact_id"] 	= 	$contact_ids[$contact_key];$assigned_user_id;

	
	$helpdesk->save("HelpDesk");
	$helpdesk_ids[] = $helpdesk->id;
}

// Populate Activities Data
$task_array=array("Tele Conference","Call user - John","Send Fax to Mary Smith");
$event_array=array("","","Call Smith","Team Meeting","Call Richie","Meeting with Don");
$task_status_array=array("Not Started","In Progress","Completed");
$task_priority_array=array("High","Medium","Low");
$visibility=array("","","Private","Public","Private","Public");

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
		$event->column_fields["visibility"] = $visibility[$i];	
				
	}
	else
	{
		$event->column_fields["subject"]	= $event_array[$i];	
		$event->column_fields["visibility"] = $visibility[$i];
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
	elseif($i>1) 
	{
		$event->column_fields["activitytype"]	= "Call";	
	}
	$event->column_fields["assigned_user_id"] = $assigned_user_id;
	$event->save("Activities");
        $event_ids[] = $event->id;

}


$adb->query("update crmentity set crmentity.smcreatorid=".$assigned_user_id);

$expected_revenue = Array("$250,000","$750,000","$500,000");
$budget_cost = Array("$25,000","$50,000","$90,000");
$actual_cost = Array("$23,500","$45,000","$80,000");
$num_sent = Array("2000","2500","3000");
$clo_date = Array('2003-1-2','2004-2-3','2005-4-12');



$expected_response = Array(null,null,null);
for($i=0;$i<count($campaign_name_array);$i++)
{
	$campaign = new Campaign();
	$campaign_name = $campaign_name_array[$i];
	$campaign->column_fields["campaignname"] = $campaign_name;
	$campaign->column_fields["campaigntype"] = $campaign_type_array[$i];
	$campaign->column_fields["campaignstatus"] = $campaign_status_array[$i];
	$campaign->column_fields["numsent"] = $num_sent[$i];
	$campaign->column_fields["expectedrevenue"] = $expected_revenue[$i];
	$campaign->column_fields["budgetcost"] = $budget_cost[$i];
	$campaign->column_fields["actualcost"] = $actual_cost[$i];
	$campaign->column_fields["closingdate"] = $clo_date[$i];
	$campaign->column_fields["expectedresponse"] = $expected_response[$i];
	$campaign->column_fields["assigned_user_id"] = $assigned_user_id;
	$campaign->save("Campaigns");
}

?>
