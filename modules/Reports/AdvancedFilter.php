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

$log = LoggerManager::getLogger('adv_report');

global $currentModule;
global $image_path;
global $theme;

global $adv_filter_options;

$adv_report=new XTemplate ('modules/Reports/AdvancedFilter.html');
$adv_report->assign("MOD", $mod_strings);
$adv_report->assign("APP", $app_strings);

if(isset($_REQUEST["record"]))
{
$reportid = $_REQUEST["record"];
$oReport = new Reports();
$oReport->getAdvancedFilterList($reportid);

$BLOCK1 = getPrimaryColumns_AdvFilterHTML($primarymodule,$oReport->advft_column[0]);
$BLOCK1 .= getSecondaryColumns_AdvFilterHTML($secondarymodule,$oReport->advft_column[0]);
$adv_report->assign("BLOCK1", $BLOCK1);

$BLOCK2 = getPrimaryColumns_AdvFilterHTML($primarymodule,$oReport->advft_column[1]);
$BLOCK2 .= getSecondaryColumns_AdvFilterHTML($secondarymodule,$oReport->advft_column[1]);
$adv_report->assign("BLOCK2", $BLOCK2);

$BLOCK3 = getPrimaryColumns_AdvFilterHTML($primarymodule,$oReport->advft_column[2]);
$BLOCK3 .= getSecondaryColumns_AdvFilterHTML($secondarymodule,$oReport->advft_column[2]);
$adv_report->assign("BLOCK3", $BLOCK3);

$BLOCK4 = getPrimaryColumns_AdvFilterHTML($primarymodule,$oReport->advft_column[3]);
$BLOCK4 .= getSecondaryColumns_AdvFilterHTML($secondarymodule,$oReport->advft_column[3]);
$adv_report->assign("BLOCK4", $BLOCK4);

$BLOCK5 = getPrimaryColumns_AdvFilterHTML($primarymodule,$oReport->advft_column[4]);
$BLOCK5 .= getSecondaryColumns_AdvFilterHTML($secondarymodule,$oReport->advft_column[4]);
$adv_report->assign("BLOCK5", $BLOCK5);

$FILTEROPTION1 = getAdvCriteriaHTML($oReport->advft_option[0]);
$adv_report->assign("FOPTION1",$FILTEROPTION1);

$FILTEROPTION2 = getAdvCriteriaHTML($oReport->advft_option[1]);
$adv_report->assign("FOPTION2",$FILTEROPTION2);

$FILTEROPTION3 = getAdvCriteriaHTML($oReport->advft_option[2]);
$adv_report->assign("FOPTION3",$FILTEROPTION3);

$FILTEROPTION4 = getAdvCriteriaHTML($oReport->advft_option[3]);
$adv_report->assign("FOPTION4",$FILTEROPTION4);

$FILTEROPTION5 = getAdvCriteriaHTML($oReport->advft_option[4]);
$adv_report->assign("FOPTION5",$FILTEROPTION5);

$adv_report->assign("VALUE1",$oReport->advft_value[0]);
$adv_report->assign("VALUE2",$oReport->advft_value[1]);
$adv_report->assign("VALUE3",$oReport->advft_value[2]);
$adv_report->assign("VALUE4",$oReport->advft_value[3]);
$adv_report->assign("VALUE5",$oReport->advft_value[4]);

}else
{
$primarymodule = $_REQUEST["primarymodule"];
$secondarymodule = $_REQUEST["secondarymodule"];
$BLOCK1 = getPrimaryColumns_AdvFilterHTML($primarymodule);
$BLOCK1 .= getSecondaryColumns_AdvFilterHTML($secondarymodule);
$adv_report->assign("BLOCK1", $BLOCK1);
$adv_report->assign("BLOCK2", $BLOCK1);
$adv_report->assign("BLOCK3", $BLOCK1);
$adv_report->assign("BLOCK4", $BLOCK1);
$adv_report->assign("BLOCK5", $BLOCK1);

//$FILTEROPTION = getAdvCriteriaHTML();
//$adv_report->assign("FOPTION1",$FILTEROPTION);
//$adv_report->assign("FOPTION2",$FILTEROPTION);
//$adv_report->assign("FOPTION3",$FILTEROPTION);
//$adv_report->assign("FOPTION4",$FILTEROPTION);
//$adv_report->assign("FOPTION5",$FILTEROPTION);

}

function getPrimaryColumns_AdvFilterHTML($module,$selected="")
{
        global $ogReport;
	global $app_list_strings;
        global $current_language;

	$mod_strings = return_module_language($current_language,$module);

        foreach($ogReport->module_list[$module] as $key=>$value)
        {
            $shtml .= "<optgroup label=\"".$app_list_strings['moduleList'][$module]." ".$key."\" class=\"select\" style=\"border:none\">";
	    if(isset($ogReport->pri_module_columnslist[$module][$key]))
	    {
		foreach($ogReport->pri_module_columnslist[$module][$key] as $field=>$fieldlabel)
		{
			if(isset($mod_strings[$fieldlabel]))
			{
				if($selected == $field)
				{
					$shtml .= "<option selected value=\"".$field."\">".$mod_strings[$fieldlabel]."</option>";
				}else
				{
					$shtml .= "<option value=\"".$field."\">".$mod_strings[$fieldlabel]."</option>";
				}
			}else
			{
				if($selected == $field)
				{
					$shtml .= "<option selected value=\"".$field."\">".$fieldlabel."</option>";
				}else
				{
					$shtml .= "<option value=\"".$field."\">".$fieldlabel."</option>";
				}
			}
		}
           }
        }
        return $shtml;
}


function getSecondaryColumns_AdvFilterHTML($module,$selected="")
{
        global $ogReport;
	global $app_list_strings;
        global $current_language;

        if($module != "")
        {
        $secmodule = explode(":",$module);
        for($i=0;$i < count($secmodule) ;$i++)
        {
                $mod_strings = return_module_language($current_language,$secmodule[$i]);
		foreach($ogReport->module_list[$secmodule[$i]] as $key=>$value)
                {
                        $shtml .= "<optgroup label=\"".$app_list_strings['moduleList'][$secmodule[$i]]." ".$key."\" class=\"select\" style=\"border:none\">";
			if(isset($ogReport->sec_module_columnslist[$secmodule[$i]][$key]))
			{
				foreach($ogReport->sec_module_columnslist[$secmodule[$i]][$key] as $field=>$fieldlabel)
				{
					if(isset($mod_strings[$fieldlable]))
					{
						if($selected == $field)
						{
							$shtml .= "<option selected value=\"".$field."\">".$mod_strings[$fieldlabel]."</option>";
						}else
						{
							$shtml .= "<option value=\"".$field."\">".$mod_strings[$fieldlabel]."</option>";
						}
					}else
					{
						if($selected == $field)
						{
							$shtml .= "<option selected value=\"".$field."\">".$fieldlabel."</option>";
						}else
						{
							$shtml .= "<option value=\"".$field."\">".$fieldlabel."</option>";
						}
					}
				}
			}
                }
        }
        }
        return $shtml;
}

function getAdvCriteriaHTML($selected="")
{
	 global $adv_filter_options;
		
	 foreach($adv_filter_options as $key=>$value)
	 {
		if($selected == $key)
		{
			$shtml .= "<option selected value=\"".$key."\">".$value."</option>";
		}else
		{
			$shtml .= "<option value=\"".$key."\">".$value."</option>";
		}
	 }
	
         return $shtml;
}

$adv_report->parse("main");
$adv_report->out("main");
?>

