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
require_once("data/Tracker.php");
require_once('include/logging.php');
require_once('include/utils/utils.php');
require_once('modules/Reports/Reports.php');

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
global $focus_list;


$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$list_report_form = new vtigerCRM_Smarty;
$list_report_form->assign("MOD", $mod_strings);
$list_report_form->assign("APP", $app_strings);
$repObj = new Reports ();

if($_REQUEST['reportmodule'] != '')
{
	$list_report_form->assign("RELATEDMODULES",getReportRelatedModules($_REQUEST['reportmodule'],$repObj));
	$list_report_form->assign("REP_MODULE",$_REQUEST['reportmodule']);
}
if($_REQUEST['reportName'] !='')
{
	$list_report_form->assign("RELATEDMODULES",getReportRelatedModules($_REQUEST['primarymodule'],$repObj));
	$list_report_form->assign("REPORTNAME",$_REQUEST['reportName']);
	$list_report_form->assign("REPORTDESC",$_REQUEST['reportDesc']);
	$list_report_form->assign("REP_MODULE",$_REQUEST['primarymodule']);
	$list_report_form->assign("SEC_MODULE",$_REQUEST['secondarymodule']);
	$list_report_form->assign("BACK_WALK",'true');
		
}
$list_report_form->assign("FOLDERID",$_REQUEST['folder']);
$list_report_form->assign("REP_FOLDERS",$repObj->sgetRptFldr());
$list_report_form->assign("IMAGE_PATH", $image_path);
$list_report_form->assign("THEME_PATH", $theme_path);
$list_report_form->assign("ERROR_MSG", $mod_strings['LBL_NO_PERMISSION']);
$list_report_form->display("ReportsStep0.tpl");
?>
