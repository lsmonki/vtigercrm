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
require_once("data/Tracker.php");
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/utils/utils.php');
require_once('modules/Reports/Reports.php');
require_once('Smarty_setup.php');

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
global $ogReport;
// focus_list is the means of passing data to a ListView.
global $focus_list;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
$list_report_form = new vtigerCRM_Smarty;
$list_report_form->assign("MOD", $mod_strings);
$list_report_form->assign("APP", $app_strings);
if(isset($_REQUEST["record"]))
{
	$reportid = $_REQUEST["record"];
	$list_report_form->assign('REPORT_ID',$reportid);
	$oReport = new Reports($reportid);
	$primarymodule = $oReport->primodule;
	$secondarymodule = $oReport->secmodule;
	$reporttype = $oReport->reporttype;
	$reportname  = $oReport->reportname;
	$reportdescription  = $oReport->reportdescription;
	$folderid  = $oReport->folderid;	
	$ogReport = new Reports();
        $ogReport->getPriModuleColumnsList($oReport->primodule);
        $ogReport->getSecModuleColumnsList($oReport->secmodule);
}else
{
	$primarymodule = $_REQUEST["primarymodule"];
	$secondarymodule = $_REQUEST["secondarymodule"];
	$reportname = $_REQUEST["reportname"];
	$reportdescription = $_REQUEST["reportdes"];
	$folderid = $_REQUEST["reportfolder"];
	$ogReport = new Reports();
	$ogReport->getPriModuleColumnsList($primarymodule);
	$ogReport->getSecModuleColumnsList($secondarymodule);
	$list_report_form->assign('BACK_WALK','true');
}

$date_format='<script> var userDateFormat = \''.$current_user->date_format.'\' </script>';
$list_report_form->assign('DATE_FORMAT',$date_format);

$list_report_form->assign('PRI_MODULE',$primarymodule);
$list_report_form->assign('SEC_MODULE',$secondarymodule);
$list_report_form->assign('REPORT_NAME',$reportname);
$list_report_form->assign('REPORT_DESC',$reportdescription);
$list_report_form->assign('FOLDERID',$folderid);
$list_report_form->assign("IMAGE_PATH", $image_path);
$list_report_form->assign("THEME_PATH", $theme_path);
$list_report_form->display("ReportsStep1.tpl");
?>
