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

require_once('include/utils/utils.php');
require_once('Smarty_setup.php');
global $app_strings;
global $list_max_entries_per_page;

$log = LoggerManager::getLogger('user_list');

global $mod_strings;
global $currentModule, $current_user;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
global $current_language;
$mod_strings = return_module_language($current_language,'Users');
$category = getParentTab();
$focus = new Users();
$smarty = new vtigerCRM_Smarty;
$no_of_users=UserCount();
//Retreiving the start value from request
if(isset($_REQUEST['start']) && $_REQUEST['start'] != '')
{
	        $start = $_REQUEST['start'];
}
elseif($_SESSION['user_pagestart'] != '')
{
	        $start = $_SESSION['user_pagestart'];
}
else
	$start=1;

$list_query = getListQuery("Users"); 

$_SESSION['user_pagestart'] = $start;
if($_REQUEST['sorder'] !='')
	$sorder = $_REQUEST['sorder'];
elseif($_SESSION['user_sorder'] != '')
	$sorder = $_SESSION['user_sorder'];
else
	$sorder = 'ASC';
$_SESSION['user_sorder'] = $sorder;
if($_REQUEST['order_by'] != '')
	$order_by = $_REQUEST['order_by'];
elseif($_SESSION['user_orderby'] != '')
	$order_by = $_SESSION['user_orderby'];
else
	$order_by = 'last_name';
$_SESSION['user_orderby'] = $orderby;
$list_query .= ' ORDER BY '.$order_by.' '.$sorder;
$list_result = $adb->query($list_query);
//Retreive the Navigation array
$navigation_array = getNavigationValues($start, $adb->num_rows($list_result), $no_of_users['user']);

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Administration","index",'');
$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("CMOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("CURRENT_USERID", $current_user->id);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("CATEGORY",$category);
$smarty->assign("LIST_HEADER",getListViewHeader($focus,"Users",$url_string,$sorder,$order_by,"",""));
$smarty->assign("LIST_ENTRIES",getListViewEntries($focus,"Users",$list_result,$navigation_array,"","","EditView","Delete",""));
$smarty->assign("USER_COUNT",$no_of_users);
$smarty->assign("RECORD_COUNTS", $record_string);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("USER_IMAGES",getUserImageNames());
if($_REQUEST['ajax'] !='')
	$smarty->display("UserListViewContents.tpl");
else
	$smarty->display("UserListView.tpl");

?>
