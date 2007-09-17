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
require_once('include/utils/UserInfoUtil.php');

global $allow_exports,$app_strings;

session_start();

$current_user = new Users();

if(isset($_SESSION['authenticated_user_id']))
{
        $result = $current_user->retrieve_entity_info($_SESSION['authenticated_user_id'],"users");
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

        $tablename =$_SESSION['tablename'];
        $orderby = $_SESSION['order_by'];
        $sorder = $_SESSION['sorder'];
        $order_by = "";
        //$query = $focus->create_export_query($orderby,$where,$sorder,$tablename);
        $query = $focus->create_export_query($order_by,$where);


        $search_type = $_REQUEST['search_type'];
        $export_data = $_REQUEST['export_data'];
        $customview  = $_REQUEST['customview'];
        $module = $_REQUEST['module'];

        if($search_type == 'withoutsearch' && $export_data == 'all')
        {
                $query = $query;
        }
        elseif($search_type == 'withoutsearch' && $export_data == 'currentpage')
        {
                if($orderby != '')
                $query .=" ORDER BY  ".$tablename.$orderby."  ".$sorder.' LIMIT  '.($_SESSION['nav_start']-1).','.$_SESSION[
'nav_end'];
                else
                        $query .=' LIMIT  '.($_SESSION['nav_start']-1).','.$_SESSION['nav_end'];

	}
        elseif($search_type == 'withoutsearch' && $export_data == 'selecteddata')
        {
                $idstring = $_REQUEST['idstring'];
                if($module == 'Accounts' && $idstring != '')
                        $query .= '  and vtiger_account.accountid in ('.($idstring).')';
                elseif($module == 'Contacts' && $idstring != '')
                        $query .= ' and vtiger_contactdetails.contactid in ('.($idstring).')';
                elseif($module == 'Potentials' && $idstring != '')
                        $query .= ' and vtiger_potential.potentialid in ('.($idstring).')';
                elseif($module == 'Leads' && $idstring != '')
                        $query .= ' and vtiger_leaddetails.leadid in ('.($idstring).')';
                elseif($module == 'Products' && $idstring != '')
                        $query .= ' and vtiger_products.productid in ('.($idstring).')';

        }
        elseif($search_type == 'includesearch' && $export_data == 'all')
        {
                if($orderby != '' && $_SESSION['export_where'] != '')
                $query.=' and  '.$_SESSION['export_where']."  ORDER BY  ".$tablename.$orderby."  ".$sorder;
                elseif($orderby == '' && $_SESSION['export_where'] != '')
                $query.=' and  '.$_SESSION['export_where'];

        }
        elseif($search_type == 'includesearch' && $export_data == 'currentpage')
        {
                $nav_start_val = $_SESSION['nav_start'];
                $nav_end_val = $_SESSION['nav_end'];
                $query .= " ORDER BY  ".$tablename.$orderby."  ".$sorder. ' LIMIT '.$nav_start_val.','.$nav_end_val;
        }
        elseif($search_type == 'includesearch' && $export_data == 'selecteddata')
        {
                $module = $_REQUEST['module'];
                $idstring = $_REQUEST['idstring'];
                if($module == 'Accounts' && $idstring != '')
                    $query .= '  and vtiger_account.accountid in ('.($idstring).')';
                elseif($module == 'Contacts' && $idstring != '')
                        $query .= ' and vtiger_contactdetails.contactid in ('.($idstring).')';
                elseif($module == 'Potentials' && $idstring != '')
                        $query .= ' and vtiger_potential.potentialid in ('.($idstring).')';
                elseif($module == 'Leads' && $idstring != '')
                        $query .= ' and vtiger_leaddetails.leadid in ('.($idstring).')';
                elseif($module == 'Products' && $idstring != '')
                        $query .= ' and vtiger_products.productid in ('.($idstring).')';
        }

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
			if($key=="description" || $key=="note")
			{
				$value=br2nl_vt($value);
			}
			$value = preg_replace("/(<\/?)(\w+)([^>]*>)/i","",html_entity_decode($value, ENT_QUOTES, "UTF-8"));
			array_push($new_arr, preg_replace("/\"/","\"\"",$value));
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
