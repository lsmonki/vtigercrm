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

require_once('include/utils/utils.php');
require_once('Smarty_setup.php');
global $app_strings;
global $list_max_entries_per_page;

$log = LoggerManager::getLogger('user_list');

global $mod_strings;
global $currentModule;
global $theme;
global $current_language;
$mod_strings = return_module_language($current_language,'Users');
$category = getParentTab();
$focus = new User();
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

$_SESSION['user_pagestart'] = $start;
if($_REQUEST['sorder'] !='')
	$sortorder = $_REQUEST['sorder'];
else
	$sortorder = $_SESSION['user_sorder'];
$_SESSION['user_sorder'] = $sortorder;
if($_REQUEST['order_by'] != '')
	$sortby = $_REQUEST['order_by'];
else
	$sortby = $_SESSION['user_orderby'];
$_SESSION['user_orderby'] = $sortby;

//Retreive the Navigation array
$navigation_array = getNavigationValues($start, $no_of_users['user'], '10');
$start_rec = $navigation_array['start'];
$end_rec = $navigation_array['end_val'];
$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$no_of_users['user'];
$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Administration","index",'');
$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("CMOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("CATEGORY",$category);
$smarty->assign("LIST_HEADER",$focus->getUserListViewHeader());
$smarty->assign("LIST_ENTRIES",$focus->getUserListViewEntries($navigation_array,$sortorder,$sortby));
$smarty->assign("USER_COUNT",$no_of_users);
$smarty->assign("RECORD_COUNTS", $record_string);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("USER_IMAGES",getUserImageNames());
if($_REQUEST['ajax'] !='')
	$smarty->display("UserListViewContents.tpl");
else
	$smarty->display("UserListView.tpl");

?>
