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

echo get_module_title($mod_strings['LBL_MODULE_NAME'],$mod_strings['LBL_CREATE_REPORT'], true);
echo "\n<BR>\n";

function getPrimaryModuleList()
{
	global $adb;
	global $app_list_strings;
	global $report_modules;	

	foreach($app_list_strings['moduleList'] as $key=>$value)
	{
		
		for($i=0;$i<count($report_modules);$i++)
		{
			if($key == $report_modules[$i])
			{
				$shtml .= "<option value=\"$key\">$value</option>";
			}
		}
	}
	
	return $shtml;
}
function getRelatedModuleList()
{
	global $app_list_strings;
	global $related_modules;

	foreach($related_modules as $key_module=>$rel_modules)
	{
		$shtml .= "<select id='".$key_module."relatedmodule' name='".$key_module."relatedmodule[]' class='select' style='width:150;'>";
		$shtml .= "<option value=''>--None--</option>";
		$optionhtml = "";
		foreach($rel_modules as $rep_key=>$rep_value)
		{
			if($rep_value != '')
			{
			$optionhtml .= "<option value='".$rep_value."'>".$app_list_strings['moduleList'][$rep_value]."</option>";			
			}
		}
		$shtml .= $optionhtml."</select>";
	}
	
	return $shtml;
}

$primary_module_html = getPrimaryModuleList();
$related_module_html = getRelatedModuleList();
$list_report_form=new XTemplate ('modules/Reports/NewReport0.html');
$list_report_form->assign("MOD", $mod_strings);
$list_report_form->assign("APP", $app_strings);
$list_report_form->assign("PRIMARYMODULE",$primary_module_html);
$list_report_form->assign("RELATEDMODULES",$related_module_html);
$list_report_form->assign("IMAGE_PATH", $image_path);
$list_report_form->parse("main");
$list_report_form->out("main");
?>
