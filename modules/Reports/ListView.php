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
require_once('include/utils/utils.php');
require_once('modules/Reports/Reports.php');

global $log;
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

// focus_list is the means of passing data to a ListView.
global $focus_list;

$list_report_form=new XTemplate ('modules/Reports/ListView.html');
$list_report_form->assign("MOD", $mod_strings);
$list_report_form->assign("APP", $app_strings);

$list_report_form->assign("IMAGE_PATH", $image_path);

//report creation button
$newrpt_button = '<input type="button" class="button" name="newReport" value="'.$mod_strings[LBL_REP_BUTTON].'" onclick=invokeAction("newReport") >';
//report folder creation button
$newrpt_fldr_button = '<input type="button" class="button" name="newReportFolder" value="'.$mod_strings[LBL_REP_FOLDER_BUTTON].'" onclick=invokeAction("newReportFolder") >';

$list_report_form->assign("NEWRPT_BUTTON",$newrpt_button);
$list_report_form->assign("NEWRPT_FLDR_BUTTON",$newrpt_fldr_button);
$repObj = new Reports ();
$list_report_form->assign("REPT_FLDR_BLK",$repObj->sgetRptFldr());
$list_report_form->assign("JAVASCRIPT","<script language='Javascript'>".$repObj->sgetJsRptFldr()."</script>");
$list_report_form->parse("main");
$list_report_form->out("main");

?>
