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
require_once('modules/Accounts/Accounts.php');
require_once('modules/Contacts/Contacts.php');
require_once('modules/Leads/Leads.php');
require_once('modules/Contacts/Contacts.php');
require_once('modules/Emails/Emails.php');
require_once('modules/Calendar/Activity.php');
require_once('modules/Notes/Notes.php');
require_once('modules/Potentials/Potentials.php');
require_once('modules/Users/Users.php');
require_once('modules/Products/Products.php');
//Pavani...adding HelpDesk and Vendors modules
require_once('modules/HelpDesk/HelpDesk.php');
require_once('modules/Vendors/Vendors.php');
require_once('include/utils/UserInfoUtil.php');
require_once('modules/CustomView/CustomView.php');

// Set the current language and the language strings, if not already set.
setCurrentLanguage();

global $allow_exports,$app_strings;

session_start();

$current_user = new Users();

if(isset($_SESSION['authenticated_user_id']))
{
        $result = $current_user->retrieve_entity_info($_SESSION['authenticated_user_id'],"Users");
        if($result == null)
        {
		session_destroy();
		header("Location: index.php?action=Login&module=Users");
        }

}

//Security Check
if(isPermitted($_REQUEST['module'],"Export") == "no")
{
	$allow_exports="none";
}

if ($allow_exports=='none' || ( $allow_exports=='admin' && ! is_admin($current_user) ) )
{

?>
	<script language=javascript>
		alert("<?php echo $app_strings['NOT_PERMITTED_TO_EXPORT']?>");
		window.location="index.php?module=<?php echo $_REQUEST['module'] ?>&action=index";
	</script>
<?php
}

/**Function convert line breaks to space in description during export 
 * Pram $str - text
 * retrun type string
*/
function br2nl_vt($str) 
{
	global $log;
	$log->debug("Entering br2nl_vt(".$str.") method ...");
	$str = preg_replace("/(\r\n)/", " ", $str);
	$log->debug("Exiting br2nl_vt method ...");
	return $str;
}

/**This function exports all the data for a given module
 * Param $type - module name
 * Return type text
*/
function export($type)
{
        global $log,$list_max_entries_per_page;
        $log->debug("Entering export(".$type.") method ...");
        global $adb;

        $focus = 0;
        $content = '';

        if ($type != "")
        {
                $focus = new $type;
        }
        $log = LoggerManager::getLogger('export_'.$type);
        $db = new PearDatabase();

	$oCustomView = new CustomView("$type");
	$viewid = $oCustomView->getViewId("$type");
	$sorder = $focus->getSortOrder();
	$order_by = $focus->getOrderBy();

        $search_type = $_REQUEST['search_type'];
        $export_data = $_REQUEST['export_data'];
	
	if(isset($_SESSION['export_where']) && $_SESSION['export_where']!='' && $search_type == 'includesearch')
                $where =$_SESSION['export_where'];

	$query = $focus->create_export_query($where);
	$stdfiltersql = $oCustomView->getCVStdFilterSQL($viewid);
	$advfiltersql = $oCustomView->getCVAdvFilterSQL($viewid);
	if(isset($stdfiltersql) && $stdfiltersql != '')
	{
		$query .= ' and '.$stdfiltersql;
	}
	if(isset($advfiltersql) && $advfiltersql != '')
	{
		$query .= ' and '.$advfiltersql;
	}
	$params = array();

	if(($search_type == 'withoutsearch' || $search_type == 'includesearch') && $export_data == 'selecteddata')
	{
		$idstring = explode(";", $_REQUEST['idstring']);
		if($type == 'Accounts' && count($idstring) > 0) {
			$query .= ' and vtiger_account.accountid in ('. generateQuestionMarks($idstring) .')';
			array_push($params, $idstring);
		} elseif($type == 'Contacts' && count($idstring) > 0) {
			$query .= ' and vtiger_contactdetails.contactid in ('. generateQuestionMarks($idstring) .')';
			array_push($params, $idstring);
		} elseif($type == 'Potentials' && count($idstring) > 0) {
			$query .= ' and vtiger_potential.potentialid in ('. generateQuestionMarks($idstring) .')';
			array_push($params, $idstring);
		} elseif($type == 'Leads' && count($idstring) > 0) {
			$query .= ' and vtiger_leaddetails.leadid in ('. generateQuestionMarks($idstring) .')';
			array_push($params, $idstring);
		} elseif($type == 'Products' && count($idstring) > 0) {
			$query .= ' and vtiger_products.productid in ('. generateQuestionMarks($idstring) .')';
			array_push($params, $idstring);
		} elseif($type == 'Notes' && count($idstring) > 0) {
			$query .= ' and vtiger_notes.notesid in ('. generateQuestionMarks($idstring) .')';
			array_push($params, $idstring);
		}
		//Pavani..adding HelpDesk and Vendors modules
		elseif($type == 'HelpDesk' && count($idstring) > 0) {
			$query .= ' and vtiger_troubletickets.ticketid in ('. generateQuestionMarks($idstring) .')';
			array_push($params, $idstring);
		} elseif($type == 'Vendors' && count($idstring) > 0) {
			$query .= ' and vtiger_vendor.vendorid in ('. generateQuestionMarks($idstring) .')';
			array_push($params, $idstring);
		}
	}
	
	if(isset($order_by) && $order_by != '')
	{
		if($order_by == 'smownerid')
		{
			$query .= ' ORDER BY user_name '.$sorder;
		}
		elseif($order_by == 'lastname' && $type == 'Notes')
		{
			$query .= ' ORDER BY vtiger_contactdetails.lastname  '. $sorder;
		}
		elseif($order_by == 'crmid' && $type == 'HelpDesk')
		{
			$query .= ' ORDER BY vtiger_troubletickets.ticketid  '. $sorder;
		}
		else
		{
			$tablename = getTableNameForField($type,$order_by);
			$tablename = (($tablename != '')?($tablename."."):'');
			if( $adb->dbType == "pgsql")
				$query .= ' GROUP BY '.$tablename.$order_by;
			$query .= ' ORDER BY '.$tablename.$order_by.' '.$sorder;
		}
	}
	
	if(isset($_SESSION['nav_start']) && $_SESSION['nav_start']!='' && $export_data == 'currentpage')
	{
		$start_rec = $_SESSION['nav_start'];
		$limit_start_rec = ($start_rec == 0) ? 0 : ($start_rec - 1);
		$query .= ' LIMIT '.$limit_start_rec.','.$list_max_entries_per_page;
	}

        $result = $adb->pquery($query, $params, true, "Error exporting $type: "."<BR>$query");
        $fields_array = $adb->getFieldsArray($result);
	$fields_array = array_diff($fields_array,array("user_name"));
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
			if($key != "user_name")
			{
				// No conversions are required here. We need to send the data to csv file as it comes from database.
				array_push($new_arr, preg_replace("/\"/","\"\"",$value));
			}	
		}
		$line = implode("\",\"",$new_arr);
		$line = "\"" .$line;
		$line .= "\"\r\n";
		$content .= $line;
	}
	$log->debug("Exiting export method ...");
	return $content;
	
}

$content = export($_REQUEST['module']);

header("Content-Disposition: attachment; filename={$_REQUEST['module']}.csv");
header("Content-Type: text/csv; charset=UTF-8");
header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
header( "Cache-Control: post-check=0, pre-check=0", false );
header("Content-Length: ".strlen($content));
print $content;

exit;
?>
