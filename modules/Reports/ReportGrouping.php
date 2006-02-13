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
$report_group=new XTemplate('modules/Reports/ReportGrouping.html');
$report_group->assign("MOD", $mod_strings);
$report_group->assign("APP", $app_strings);
$report_group->assign("IMAGE_PATH",$image_path);

if(isset($_REQUEST["record"]))
{

$reportid = $_REQUEST["record"];
$oReport = new Reports($reportid);
$list_array = $oReport->getSelctedSortingColumns($reportid);

$BLOCK1 = getPrimaryColumns_GroupingHTML($primarymodule,$list_array[0]);
$BLOCK1 .= getSecondaryColumns_GroupingHTML($secondarymodule,$list_array[0]);
$report_group->assign("BLOCK1",$BLOCK1);

$BLOCK2 = getPrimaryColumns_GroupingHTML($primarymodule,$list_array[1]);
$BLOCK2 .= getSecondaryColumns_GroupingHTML($secondarymodule,$list_array[1]);
$report_group->assign("BLOCK2",$BLOCK2);

$BLOCK3 = getPrimaryColumns_GroupingHTML($primarymodule,$list_array[2]);
$BLOCK3 .= getSecondaryColumns_GroupingHTML($secondarymodule,$list_array[2]);
$report_group->assign("BLOCK3",$BLOCK3);

$sortorder = $oReport->ascdescorder;

}else
{
$primarymodule = $_REQUEST["primarymodule"];
$secondarymodule = $_REQUEST["secondarymodule"];
$BLOCK1 = getPrimaryColumns_GroupingHTML($primarymodule);
$BLOCK1 .= getSecondaryColumns_GroupingHTML($secondarymodule);
$report_group->assign("BLOCK1",$BLOCK1);
$report_group->assign("BLOCK2",$BLOCK1);
$report_group->assign("BLOCK3",$BLOCK1);
}



function getPrimaryColumns_GroupingHTML($module,$selected="")
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

function getSecondaryColumns_GroupingHTML($module,$selected="")
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
        }
        }
        return $shtml;
}

if($sortorder[0] != "Descending")
{
$shtml = "<option selected value='Ascending'>Ascending</option>
	 <option value='Descending'>Descending</option>";
}else
{
$shtml = "<option value='Ascending'>Ascending</option>
	  <option selected value='Descending'>Descending</option>";
}
$report_group->assign("ASCDESC1",$shtml);

if($sortorder[1] != "Descending")
{
$shtml = "<option selected value='Ascending'>Ascending</option>
          <option value='Descending'>Descending</option>";
}else
{
$shtml = "<option value='Ascending'>Ascending</option>
          <option selected value='Descending'>Descending</option>";
}
$report_group->assign("ASCDESC2",$shtml);

if($sortorder[2] != "Descending")
{
$shtml = "<option selected value='Ascending'>Ascending</option>
	  <option value='Descending'>Descending</option>";
}else
{
$shtml =  "<option value='Ascending'>Ascending</option>
	   <option selected value='Descending'>Descending</option>";
}
$report_group->assign("ASCDESC3",$shtml);

$report_group->parse("main");
$report_group->out("main");
?>
