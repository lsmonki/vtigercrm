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

$log = LoggerManager::getLogger('report_type');

global $currentModule;
global $image_path;
global $theme;
$report_type=new XTemplate('modules/Reports/ReportType.html');
$report_type->assign("MOD", $mod_strings);
$report_type->assign("APP", $app_strings);
$report_type->assign("IMAGE_PATH",$image_path);
if(isset($_REQUEST["record"]))
{
        $recordid = $_REQUEST["record"];
        $oReport = new Reports($recordid);
        $selectedreporttype = $oReport->reporttype;
}else
{
        $selectedreporttype = "tabular";
}
if($selectedreporttype == "tabular")
{
   $shtml = '<input checked type="radio" name="reportType" value="tabular" onclick="hideTabs( true )">';
}else
{
   $shtml = '<input type="radio" name="reportType" value="tabular" onclick="hideTabs( true )">';
}

$report_type->assign("REPORT_TAB_TYPE",$shtml);

if($selectedreporttype == "summary")
{
   $sumhtml = '<input type="radio" checked name="reportType" value="summary" onclick="hideTabs( false )">';
}else
{
   $sumhtml = '<input type="radio" name="reportType" value="summary" onclick="hideTabs( false )">';
}

$report_type->assign("REPORT_SUM_TYPE",$sumhtml);

$report_type->parse("main");

$report_type->out("main");
?>
