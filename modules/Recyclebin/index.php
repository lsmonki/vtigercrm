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
require_once('Smarty_setup.php');
require_once('include/database/PearDatabase.php');
require_once('database/DatabaseConnection.php');
require_once('include/utils/utils.php');
require_once('modules/CustomView/CustomView.php');
require_once('include/database/Postgres8.php');
require_once('include/DatabaseUtil.php');

global $mod_strings, $app_strings, $app_list_strings;
global $current_language, $currentModule, $theme;
global $adb, $log, $list_max_entries_per_page;
global $current_user;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

require("user_privileges/user_privileges_".$current_user->id.".php");

$smarty = new vtigerCRM_Smarty;

// Data from the below modules will not be allowed to restore
$skip_modules = array('Users', 'Events', 'Downloads', 'Dashboard', 'Home', 'Webmails', 'Recyclebin', 'Rss', 'Portal', 'Reports');
$skip_tab_ids = array();

for($i=0; $i<count($skip_modules); $i++) {
	$tab_id = getTabid($skip_modules[$i]);
	if ($tab_id != null && $tab_id != '') $skip_tab_ids[] = $tab_id;
}

$sql = "select distinct name, tabid from vtiger_tab where tabid not in (". generateQuestionMarks($skip_tab_ids) . ")";
$result =$adb->pquery($sql, array($skip_tab_ids));
$noofrows = $adb->num_rows($result);

$module_name =Array();
$module_data =Array();

if($noofrows > 0)
{
	for($x=0,$y=0; $x<$noofrows;$x++)
	{
		$tabid = $adb->query_result($result,$x,'tabid');
		if($is_admin || $profileGlobalPermission[2]==0 || $profileGlobalPermission[1]==0 || $profileTabsPermission[$tabid]==0) {
			$mod_name = $adb->query_result($result,$x,"name");
			$module_name[$y] = $mod_name;
			$y++;
		}
	}
}

if(isset($_REQUEST['selected_module']) && $_REQUEST['selected_module'] != '')
{
	$select_module = $_REQUEST['selected_module'];
	if (!in_array($select_module, $module_name)) {
		show_error_msg();
	}
}
else
{
	if (count($module_name) > 0) {
		$select_module = $module_name[0];
	} else {
		show_error_msg('no_permitted_modules');
	}
	
}

$url_string = '';
if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	list($where, $ustring) = split("#@@#",RBSearch($select_module));

	// we have a query
	$url_string .="&query=true".$ustring;
	$log->info("Here is the where clause for the list view: $where");
	$smarty->assign("SEARCH_URL",$url_string);
				
}

require_once("modules/$select_module/$select_module.php");
if($select_module == 'Calendar')
{
	$focus = new Activity();	
}else if ($select_module == 'Rss') 
{
	$focus = new vtigerRSS();
}else
{
	$focus = new $select_module();
}

if(count($module_name) > 0)
{

	$list_fields = getRbListViewDetails($select_module);
	$rb_listview_header = getRbListViewHeader($list_fields);
	
	$list_query = getListQuery($select_module, '');
	$list_query = preg_replace("/vtiger_crmentity.deleted\s*=\s*0/i", 'vtiger_crmentity.deleted = 1', $list_query);
	//Search criteria added to the list Query
	if(isset($where) && $where != '')
	{
		$list_query .= ' and '.$where;
	}
	$count_result = $adb->query( mkCountQuery($list_query));
	$noofrows = $adb->query_result($count_result,0,"count");

	$cur_mod_view = new CustomView($select_module);
	$viewid = $cur_mod_view->getViewId($select_module);
	$cur_mod_view = setListFieldsforCV($cur_mod_view,$list_fields);
	
	$listview_header_search=getSearchListHeaderValues($focus,$select_module,$url_string,$sorder,$order_by,"",$cur_mod_view);
	$smarty->assign("SEARCHLISTHEADER", $listview_header_search);

	if(isset($_REQUEST[start]) && $_REQUEST[start] != '')
		$start = $_REQUEST[start];
	else
		$start = 1;

	$navigation_array = getNavigationValues($start, $noofrows, $list_max_entries_per_page);

	// Setting the record count string
	//modified by rdhital
	$start_rec = $navigation_array['start'];
	$end_rec = $navigation_array['end_val']; 
	//By Raju Ends

	//limiting the query
	if ($start_rec ==0) 
		$limit_start_rec = 0;
	else
		$limit_start_rec = $start_rec -1;

	if( $adb->dbType == "pgsql")
     		$list_result = $adb->query($list_query. " OFFSET ".$limit_start_rec." LIMIT ".$list_max_entries_per_page);
 	else
     		$list_result = $adb->query($list_query. " LIMIT ".$limit_start_rec.",".$list_max_entries_per_page);

	$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

	$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Recyclebin","index","");

	$lvEntries = getRbListViewEntries($list_result,$list_fields,$select_module);
}

$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);

//to get the field name that mentions the module
$query = "select fieldname from vtiger_entityname where modulename =?";
$module_fielaname = $adb->query_result($adb->pquery($query, array($select_module)),0,'fieldname');

if(strpos($module_fielaname,','))
{
	$field_array = explode(',',$module_fielaname);
	$index_field = $field_array[0];
}
else
{
	$index_field = $module_fielaname;
}

$alphabetical = AlphabeticalSearch($currentModule,'index',$index_field,'true','basic',"","","","",$viewid);

if ($_REQUEST['parenttab'] == "Settings")
	$category = "Settings";
else $category = "Tools";

$check_button = Button_Check($_REQUEST['module']);
$smarty->assign("CHECK", $check_button);

$smarty->assign("ALPHABETICAL", $alphabetical);
$smarty->assign("NUMBER_MODULES",$noofrows);
$smarty->assign("MODULE_NAME",$module_name);
$smarty->assign("SELECTED_MODULE",$select_module);
$smarty->assign("MODULE_DATA",$rb_listview_header);
$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("MODULE","Recyclebin");
$smarty->assign("CATEGORY",$category);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP", $app_strings);
$smarty->assign("CMOD", $mod_strings);
$smarty->assign("lvEntries", $lvEntries);

$smarty->assign("IS_ADMIN", $is_admin);

if($_REQUEST['mode'] != 'ajax') {
	if($_REQUEST['parenttab'] == 'Settings')
		$smarty->display('Settings/Recyclebin.tpl');
	else 
		$smarty->display('Recyclebin/Recyclebin.tpl');	
} else
	$smarty->display('Recyclebin/RecyclebinContents.tpl');
	
function show_error_msg($error_type='permission_denied') {
	global $theme;
	if ($error_type == 'permission_denied') {		
		echo "<link rel='stylesheet' type='text/css' href='themes/$theme/style.css'>";	
		echo "<table border='0' cellpadding='5' cellspacing='0' width='100%' height='450px'><tr><td align='center'>";
		echo "<div style='border: 3px solid rgb(153, 153, 153); background-color: rgb(255, 255, 255); width: 55%; position: relative; z-index: 10000000;'>
	
			<table border='0' cellpadding='5' cellspacing='0' width='98%'>
			<tbody><tr>
			<td rowspan='2' width='11%'><img src='themes/images/denied.gif' ></td>
			<td style='border-bottom: 1px solid rgb(204, 204, 204);' nowrap='nowrap' width='70%'><span class='genHeaderSmall'>" 
				. getTranslatedString('LBL_PERMISSION') . "</span></td>
			</tr>
			<tr>
			<td class='small' align='right' nowrap='nowrap'>			   	
			<a href='javascript:window.history.back();'>" . getTranslatedString('LBL_GO_BACK') . "</a><br>
			</td>
			</tr>
			</tbody></table> 
			</div>";
		echo "</td></tr></table>";
		die();
	} else if ($error_type == 'no_permitted_modules') {		
		echo "<link rel='stylesheet' type='text/css' href='themes/$theme/style.css'>";	
		echo "<table border='0' cellpadding='5' cellspacing='0' width='100%' height='450px'><tr><td align='center'>";
		echo "<div style='border: 3px solid rgb(153, 153, 153); background-color: rgb(255, 255, 255); width: 55%; position: relative; z-index: 10000000;'>
	
			<table border='0' cellpadding='5' cellspacing='0' width='98%'>
			<tbody><tr>
			<td rowspan='2' width='11%'><img src='themes/$theme/images/empty.jpg' ></td>
			<td style='border-bottom: 1px solid rgb(204, 204, 204);' nowrap='nowrap' width='70%'><span class='genHeaderSmall'>" 
				. getTranslatedString('LBL_NO_PERMITTED_MODULES') . "</span></td>
			</tr>
			</tbody></table> 
			</div>";
		echo "</td></tr></table>";
		die();		
	}
}

?>