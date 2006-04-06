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

/**Function convert line breaks to space in description during export 
 * Pram $str - text
 * retrun type string
*/
function br2nl_vt($str) 
{
	$str = preg_replace("/(\r\n)/", " ", $str);
	return $str;
}

/**This function exports all the data for a given module
 * Param $type - module name
 * Return type text
*/
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

	$order_by = "";

	$query = $focus->create_export_query($order_by,$where);


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
