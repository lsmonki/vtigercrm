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
require_once('themes/'.$theme.'/layout_utils.php');
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
$theme_path="themes/".$theme."/";
$report_column=new vtigerCRM_Smarty;
$report_column->assign("MOD", $mod_strings);
$report_column->assign("APP", $app_strings);
$report_column->assign("IMAGE_PATH",$image_path);
$report_column->assign("THEME_PATH",$theme_path);
if(isset($_REQUEST["record"]))
{
	$recordid = $_REQUEST["record"];
	$oReport = new Reports($recordid);
	$BLOCK1 = getPrimaryColumnsHTML($oReport->primodule);
	$BLOCK1 .= getSecondaryColumnsHTML($oReport->secmodule);
	$BLOCK2 = $oReport->getSelectedColumnsList($recordid);
	$report_column->assign("BLOCK1",$BLOCK1);
	$report_column->assign("BLOCK2",$BLOCK2);
}else
{
	$primarymodule = $_REQUEST["primarymodule"];
	$secondarymodule = $_REQUEST["secondarymodule"];
	$BLOCK1 = getPrimaryColumnsHTML($primarymodule);
	$BLOCK1 .= getSecondaryColumnsHTML($secondarymodule);
	$report_column->assign("BLOCK1",$BLOCK1);

}

/** Function to formulate the vtiger_fields for the primary modules 
 *  This function accepts the module name 
 *  as arguments and generates the vtiger_fields for the primary module as
 *  a HTML Combo values
 */

function getPrimaryColumnsHTML($module)
{
	global $ogReport;
	global $app_list_strings;
	global $app_strings;
	global $current_language;

	$mod_strings = return_module_language($current_language,$module);
	foreach($ogReport->module_list[$module] as $key=>$value)
	{
		if(isset($ogReport->pri_module_columnslist[$module][$key]))
		{
			
			$shtml .= "<optgroup label=\"".$app_list_strings['moduleList'][$module]." ".$app_strings[$key]."\" class=\"select\" style=\"border:none\">";
			foreach($ogReport->pri_module_columnslist[$module][$key] as $field=>$fieldlabel)
			{
				if(isset($mod_strings[$fieldlabel]))
				{
					$shtml .= "<option value=\"".$field."\">".$mod_strings[$fieldlabel]."</option>";
				}else
				{
					$shtml .= "<option value=\"".$field."\">".$fieldlabel."</option>";
				}
			}
		}
	}
	return $shtml;
}

/** Function to formulate the vtiger_fields for the secondary modules
 *  This function accepts the module name
 *  as arguments and generates the vtiger_fields for the secondary module as
 *  a HTML Combo values
 */


function getSecondaryColumnsHTML($module)
{
	global $ogReport;
	global $app_list_strings,$app_strings;
	global $current_language;

	if($module != "")
	{
		$secmodule = explode(":",$module);
		for($i=0;$i < count($secmodule) ;$i++)
		{
			$mod_strings = return_module_language($current_language,$secmodule[$i]);
			foreach($ogReport->module_list[$secmodule[$i]] as $key=>$value)
			{
				if(isset($ogReport->sec_module_columnslist[$secmodule[$i]][$key]))
				{
					$shtml .= "<optgroup label=\"".$app_list_strings['moduleList'][$secmodule[$i]]." ".$app_strings[$key]."\" class=\"select\" style=\"border:none\">";
					foreach($ogReport->sec_module_columnslist[$secmodule[$i]][$key] as $field=>$fieldlabel)
					{
						if(isset($mod_strings[$fieldlabel]))
						{
							$shtml .= "<option value=\"".$field."\">".$mod_strings[$fieldlabel]."</option>";
						}else
						{
							$shtml .= "<option value=\"".$field."\">".$fieldlabel."</option>";
						}
					}
				}
			}
		}
	}
	return $shtml;
}

$report_column->display("ReportColumns.tpl");
?>

