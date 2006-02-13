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
require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/utils.php');
require_once('modules/Reports/Reports.php');
require_once('include/database/PearDatabase.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
$current_module_strings = return_module_language($current_language, 'Reports');

global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('report_list');

global $currentModule;

global $image_path;
global $theme;

echo get_module_title($mod_strings['LBL_MODULE_NAME'],$mod_strings['LBL_REP_FOLDER'], true);
echo "\n<BR>\n";

$new_report_form=new XTemplate ('modules/Reports/NewReportFolder.html');
$new_report_form->assign("MOD", $mod_strings);
$new_report_form->assign("APP", $app_strings);

if( $_REQUEST['record'] != "")
{
	$new_report_form->assign("MODE", "Edit");
	$new_report_form->assign("ID",$_REQUEST['record']);
	$sql = "select * from reportfolder where folderid=".$_REQUEST['record'];
	$result = $adb->query($sql);
	$reportfldrow = $adb->fetch_array($result);
	$new_report_form->assign("FOLDERNAME",stripslashes($reportfldrow["foldername"]));
	$new_report_form->assign("FOLDERDESC", stripslashes($reportfldrow["description"]));
}else
{
	$new_report_form->assign("MODE", "Save");
}

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "")  $new_report_form->assign("RETURN_MODULE", $_REQUEST['return_module']);
else $new_report_form->assign("RETURN_MODULE", "Reports");
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $new_report_form->assign("RETURN_ACTION", $_REQUEST['return_action']);
else $new_report_form->assign("RETURN_ACTION","index");

$new_report_form->parse("main");
$new_report_form->out("main");
?>
