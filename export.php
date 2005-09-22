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

require_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Leads/Lead.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Emails/Email.php');
require_once('modules/Activities/Activity.php');
require_once('modules/Notes/Note.php');
require_once('modules/Potentials/Opportunity.php');
require_once('modules/Users/User.php');
require_once('modules/Products/Product.php');

global $allow_exports;
session_start();

$current_user = new User();

if(isset($_SESSION['authenticated_user_id']))
{
        $result = $current_user->retrieve($_SESSION['authenticated_user_id']);
        if($result == null)
        {
                session_destroy();
            header("Location: index.php?action=Login&module=Users");
        }

}
if ($allow_exports=='none' || ( $allow_exports=='admin' && ! is_admin($current_user) ) )
{
die("you can't export!");
}



$contact_fields = array(
"id"=>"Contact ID"
,"lead_source"=>"Lead Source"
,"date_entered"=>"Date Entered"
,"date_modified"=>"Date Modified"
,"first_name"=>"First Name"
,"last_name"=>"Last Name"
,"salutation"=>"Salutation"
,"birthdate"=>"Lead Source"
,"do_not_call"=>"Do Not Call"
,"email_opt_out"=>"Email Opt Out"
,"title"=>"Title"
,"department"=>"Department"
,"birthdate"=>"Birthdate"
,"do_not_call"=>"Do Not Call"
,"phone_home"=>"Phone (Home)"
,"phone_mobile"=>"Phone (Mobile)"
,"phone_work"=>"Phone (Work)"
,"phone_other"=>"Phone (Other)"
,"phone_fax"=>"Fax"
,"email1"=>"Email"
,"email2"=>"Email (Other)"
,"yahoo_id"=>"Yahoo! ID"
,"assistant"=>"Assistant"
,"assistant_phone"=>"Assistant Phone"
,"primary_address_street"=>"Primary Address Street"
,"primary_address_city"=>"Primary Address City"
,"primary_address_state"=>"Primary Address State"
,"primary_address_postalcode"=>"Primary Address Postalcode"
,"primary_address_country"=>"Primary Address Country"
,"alt_address_street"=>"Other Address Street"
,"alt_address_city"=>"Other Address City"
,"alt_address_state"=>"Other Address State"
,"alt_address_postalcode"=>"Other Address Postalcode"
,"alt_address_country"=>"Other Address Country"
,"description"=>"Description"
);

/*$account_fields = array(
"id"=>"Account ID",
"name"=>"Account Name",
"website"=>"Website",
"industry"=>"Industry",
"account_type"=>"Type",
"ticker_symbol"=>"Ticker Symbol",
"employees"=>"Employees",
"ownership"=>"Ownership",
"phone_office"=>"Phone",
"phone_fax"=>"Fax",
"phone_alternate"=>"Other Phone",
"email1"=>"Email",
"email2"=>"Other Email",
"rating"=>"Rating",
"sic_code"=>"SIC Code",
"annual_revenue"=>"Annual Revenue",
"billing_address_street"=>"Billing Address Street",
"billing_address_city"=>"Billing Address City",
"billing_address_state"=>"Billing Address State",
"billing_address_postalcode"=>"Billing Address Postalcode",
"billing_address_country"=>"Billing Address Country",
"shipping_address_street"=>"Shipping Address Street",
"shipping_address_city"=>"Shipping Address City",
"shipping_address_state"=>"Shipping Address State",
"shipping_address_postalcode"=>"Shipping Address Postalcode",
"shipping_address_country"=>"Shipping Address Country",
"description"=>"Description"
);

*/

//Function added to convert line breaks to space in description during export 
function br2nl_vt($str) {
   $str = preg_replace("/(\r\n)/", " ", $str);
   return $str;
}

function export_all($type)
{
	$contact_fields = Array();
	$account_fields = Array();
	global $adb;
	$focus = 0;
	$content = '';

	if ($type == "Contacts")
	{
		$focus = new Contact;
			}
	else if ($type == "Accounts")
	{
		$focus = new Account;
		$exp_query="SELECT columnname, fieldlabel FROM field where tabid=6";
		$account_result=$adb->query($exp_query);
		if($adb->num_rows($account_result)!=0)
		{
			while($result = $adb->fetch_array($account_result))
			{
				$account_columnname = $result['columnname'];
				$account_fieldlabel = $result['fieldlabel'];
				$account_fields[$account_columnname] = $account_fieldlabel;
			}
		}
	}
	else if ($type == "Potentials")
	{
        	$focus = new Potential;
	}
	else if ($type == "Notes")
	{
		$focus = new Note;
	}
	else if ($type == "Leads")
	{
		$focus = new Lead;
	}

	else if ($type == "Emails")
	{
		$focus = new Email;
	}
	else if ($type == "Products")
        {
                $focus = new Product;
        }

	$log = LoggerManager::getLogger('export_'.$type);
	$db = new PearDatabase();

	if ( isset($_REQUEST['all']) )
	{
		$where = '';
	}
	else
	{
		$where = $_SESSION['export_where'];
	}

/*
	if ( isset( $_SESSION['order_by'] ))
	{
		$order_by = $_SESSION['order_by'];
	} 
	else
	{
		$order_by = "";
	}
	*/
	$order_by = "";

             $query = $focus->create_export_query($order_by,$where);

	//print $query;
//exit;

	$result = $adb->query($query,true,"Error exporting $type: "."<BR>$query");

	$fields_array = $adb->getFieldsArray($result);

	$header = implode("\",\"",array_values($fields_array));
	$header = "\"" .$header;
	$header .= "\"\r\n";
	$content .= $header;

	$column_list = implode(",",array_values($fields_array));

        while($val = $adb->fetchByAssoc($result, -1, false))
	{
		$new_arr = array();

		//foreach (array_values($val) as $value)
		foreach ($val as $key => $value)
		{
			if($key=="description")
			{
				$value=br2nl_vt($value);
			}
			array_push($new_arr, preg_replace("/\"/","\"\"",$value));
		}
		$line = implode("\",\"",$new_arr);
		$line = "\"" .$line;
		$line .= "\"\r\n";
		$content .= $line;
	}
	return $content;
	
}

$content = export_all($_REQUEST['module']);

header("Content-Disposition: inline; filename={$_REQUEST['module']}.csv");
header("Content-Type: text/csv; charset=UTF-8");
header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
header( "Cache-Control: post-check=0, pre-check=0", false );
header("Content-Length: ".strlen($content));
	print $content;
exit;
?>
        
