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
$report_std_filter=new XTemplate('modules/Reports/StandardFilter.html');
$report_std_filter->assign("MOD", $mod_strings);
$report_std_filter->assign("APP", $app_strings);
$report_std_filter->assign("IMAGE_PATH",$image_path);

if(isset($_REQUEST["record"]) == false)
{
        $oReport = new Reports();
        $primarymodule = $_REQUEST["primarymodule"];
        $secondarymodule = $_REQUEST["secondarymodule"];

	$BLOCK1 = getPrimaryStdFilterHTML($primarymodule);
	$BLOCK1 .= getSecondaryStdFilterHTML($secondarymodule);

	$report_std_filter->assign("BLOCK1",$BLOCK1);
        $BLOCKJS = $oReport->getCriteriaJS();
	$report_std_filter->assign("BLOCKJS",$BLOCKJS);
        $BLOCKCRITERIA = $oReport->getSelectedStdFilterCriteria();
	$report_std_filter->assign("BLOCKCRITERIA",$BLOCKCRITERIA);

}elseif(isset($_REQUEST["record"]) == true)
{
        $reportid = $_REQUEST["record"];
        $oReport = new Reports($reportid);
        $oReport->getSelectedStandardCriteria($reportid);
	
	$BLOCK1 = getPrimaryStdFilterHTML($primarymodule,$oReport->stdselectedcolumn);
        $BLOCK1 .= getSecondaryStdFilterHTML($secondarymodule,$oReport->stdselectedcolumn);
	$report_std_filter->assign("BLOCK1",$BLOCK1);

        $BLOCKJS = $oReport->getCriteriaJS();
	$report_std_filter->assign("BLOCKJS",$BLOCKJS);

        $BLOCKCRITERIA = $oReport->getSelectedStdFilterCriteria($oReport->stdselectedfilter);
	$report_std_filter->assign("BLOCKCRITERIA",$BLOCKCRITERIA);

        $startdate = $oReport->startdate;
	$report_std_filter->assign("STARTDATE",$startdate);	

        $enddate = $oReport->enddate;
	$report_std_filter->assign("ENDDATE",$enddate);
}


function getPrimaryStdFilterHTML($module,$selected="")
{
	global $app_list_strings;
	global $ogReport;
	global $current_language;

        $mod_strings = return_module_language($current_language,$module);

	$result = $ogReport->getStdCriteriaByModule($module);
	
	if(isset($result))
	{
		foreach($result as $key=>$value)
		{
			if(isset($mod_strings[$value]))
			{
				if($key == $selected)
				{
					$shtml .= "<option selected value=\"".$key."\">".$app_list_strings['moduleList'][$module]." - ".$mod_strings[$value]."</option>";
				}else
				{
					$shtml .= "<option value=\"".$key."\">".$app_list_strings['moduleList'][$module]." - ".$mod_strings[$value]."</option>";
				}
			}else
			{
				if($key == $selected)
				{
					$shtml .= "<option selected value=\"".$key."\">".$app_list_strings['moduleList'][$module]." - ".$value."</option>";
				}else
				{
					$shtml .= "<option value=\"".$key."\">".$app_list_strings['moduleList'][$module]." - ".$value."</option>";
				}
			}
		}
	}
	
	return $shtml;
}

function getSecondaryStdFilterHTML($module,$selected="")
{
	global $app_list_strings;
	global $ogReport;
	global $current_language;

	if($module != "")
        {
        	$secmodule = explode(":",$module);
        	for($i=0;$i < count($secmodule) ;$i++)
        	{
			$result = $ogReport->getStdCriteriaByModule($secmodule[$i]);
			$mod_strings = return_module_language($current_language,$secmodule[$i]);
        		if(isset($result))
        		{
                		foreach($result as $key=>$value)
                		{
                        		if(isset($mod_strings[$value]))
                                        {
						if($key == $selected)
						{
							$shtml .= "<option selected value=\"".$key."\">".$app_list_strings['moduleList'][$secmodule[$i]]." - ".$mod_strings[$value]."</option>";
						}else
						{
							$shtml .= "<option value=\"".$key."\">".$app_list_strings['moduleList'][$secmodule[$i]]." - ".$mod_strings[$value]."</option>";
						}
					}else
					{
						if($key == $selected)
						{
							$shtml .= "<option selected value=\"".$key."\">".$app_list_strings['moduleList'][$secmodule[$i]]." - ".$value."</option>";
						}else
						{
							$shtml .= "<option value=\"".$key."\">".$app_list_strings['moduleList'][$secmodule[$i]]." - ".$value."</option>";
						}
					}
                		}
        		}
		
		}
	}
	return $shtml;
}
$report_std_filter->parse("main");
$report_std_filter->out("main");
?>

