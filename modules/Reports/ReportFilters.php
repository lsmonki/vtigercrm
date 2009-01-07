<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 *****************************************************>***************************/
 
require_once('Smarty_setup.php');
require_once("data/Tracker.php");
require_once('include/logging.php');
require_once('include/utils/utils.php');
require_once('modules/Reports/Reports.php');
require_once('include/database/PearDatabase.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
$current_module_strings = return_module_language($current_language, 'Reports');
global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('report_type');
global $currentModule;
global $image_path;
global $theme;
global $current_user;

$report_std_filter = new vtigerCRM_Smarty; 
$report_std_filter->assign("MOD", $mod_strings);
$report_std_filter->assign("APP", $app_strings);
$report_std_filter->assign("IMAGE_PATH",$image_path);
$report_std_filter->assign("DATEFORMAT",$current_user->date_format);
$report_std_filter->assign("JS_DATEFORMAT",parse_calendardate($app_strings['NTC_DATE_FORMAT']));

$roleid = $current_user->column_fields['roleid'];
$user_array = getRoleAndSubordinateUsers($roleid);
$userIdStr = "";
$userNameStr = "";
$m=0;
foreach($user_array as $userid=>$username){
	
	if($userid!=$current_user->id){
		if($m!=0){
			$userIdStr .= ",";
			$userNameStr .= ",";
		}
		$userIdStr .="'".$userid."'"; 
		$userNameStr .="'".escape_single_quotes(decode_html($username))."'";
		$m++;
	}
}

require_once('include/utils/GetUserGroups.php');
$userGroups = new GetUserGroups();
$userGroups->getAllUserGroups($current_user->id);
$user_groups = $userGroups->user_groups;
$groupIdStr = "";
$groupNameStr = "";
$l=0;
foreach($user_groups as $i=>$grpid){
	$grp_details = getGroupDetails($grpid);
	if($l!=0){
		$groupIdStr .= ",";
		$groupNameStr .= ",";
	}
	$groupIdStr .= "'".$grp_details[0]."'";
	$groupNameStr .= "'".escape_single_quotes(decode_html($grp_details[1]))."'";
	$l++;
}

$report_std_filter->assign("GROUPNAMESTR", $groupNameStr);
$report_std_filter->assign("USERNAMESTR", $userNameStr);
$report_std_filter->assign("GROUPIDSTR", $groupIdStr);
$report_std_filter->assign("USERIDSTR", $userIdStr);


include("modules/Reports/StandardFilter.php");
include("modules/Reports/AdvancedFilter.php");

$report_std_filter->display('ReportFilters.tpl');
?>
