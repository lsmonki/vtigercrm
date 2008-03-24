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
if(isset($_REQUEST["record"]))
{
$reportid = $_REQUEST["record"];
$oReport = new Reports($reportid);
$oReport->getAdvancedFilterList($reportid);

$BLOCK1 = getPrimaryColumns_AdvFilterHTML($oReport->primodule,$oReport->advft_column[0]);
$BLOCK1 .= getSecondaryColumns_AdvFilterHTML($oReport->secmodule,$oReport->advft_column[0]);
$report_std_filter->assign("BLOCK1", $BLOCK1);

$BLOCK2 = getPrimaryColumns_AdvFilterHTML($oReport->primodule,$oReport->advft_column[1]);
$BLOCK2 .= getSecondaryColumns_AdvFilterHTML($oReport->secmodule,$oReport->advft_column[1]);
$report_std_filter->assign("BLOCK2", $BLOCK2);

$BLOCK3 = getPrimaryColumns_AdvFilterHTML($oReport->primodule,$oReport->advft_column[2]);
$BLOCK3 .= getSecondaryColumns_AdvFilterHTML($oReport->secmodule,$oReport->advft_column[2]);
$report_std_filter->assign("BLOCK3", $BLOCK3);

$BLOCK4 = getPrimaryColumns_AdvFilterHTML($oReport->primodule,$oReport->advft_column[3]);
$BLOCK4 .= getSecondaryColumns_AdvFilterHTML($oReport->secmodule,$oReport->advft_column[3]);
$report_std_filter->assign("BLOCK4", $BLOCK4);

$BLOCK5 = getPrimaryColumns_AdvFilterHTML($oReport->primodule,$oReport->advft_column[4]);
$BLOCK5 .= getSecondaryColumns_AdvFilterHTML($oReport->secmodule,$oReport->advft_column[4]);
$report_std_filter->assign("BLOCK5", $BLOCK5);

$FILTEROPTION1 = getAdvCriteriaHTML($oReport->advft_option[0]);
$report_std_filter->assign("FOPTION1",$FILTEROPTION1);

$FILTEROPTION2 = getAdvCriteriaHTML($oReport->advft_option[1]);
$report_std_filter->assign("FOPTION2",$FILTEROPTION2);

$FILTEROPTION3 = getAdvCriteriaHTML($oReport->advft_option[2]);
$report_std_filter->assign("FOPTION3",$FILTEROPTION3);

$FILTEROPTION4 = getAdvCriteriaHTML($oReport->advft_option[3]);
$report_std_filter->assign("FOPTION4",$FILTEROPTION4);

$FILTEROPTION5 = getAdvCriteriaHTML($oReport->advft_option[4]);
$report_std_filter->assign("FOPTION5",$FILTEROPTION5);

$report_std_filter->assign("VALUE1",$oReport->advft_value[0]);
$report_std_filter->assign("VALUE2",$oReport->advft_value[1]);
$report_std_filter->assign("VALUE3",$oReport->advft_value[2]);
$report_std_filter->assign("VALUE4",$oReport->advft_value[3]);
$report_std_filter->assign("VALUE5",$oReport->advft_value[4]);

}else
{
$primarymodule = $_REQUEST["primarymodule"];
$secondarymodule = $_REQUEST["secondarymodule"];
$BLOCK1 = getPrimaryColumns_AdvFilterHTML($primarymodule);
$BLOCK1 .= getSecondaryColumns_AdvFilterHTML($secondarymodule);
$report_std_filter->assign("BLOCK1", $BLOCK1);
$report_std_filter->assign("BLOCK2", $BLOCK1);
$report_std_filter->assign("BLOCK3", $BLOCK1);
$report_std_filter->assign("BLOCK4", $BLOCK1);
$report_std_filter->assign("BLOCK5", $BLOCK1);

}

/** Function to get primary columns for an advanced filter
 *  This function accepts The module as an argument
 *  This generate columns of the primary modules for the advanced filter 
 *  It returns a HTML string of combo values 
 */

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



/** Function to get Secondary columns for an advanced filter
 *  This function accepts The module as an argument
 *  This generate columns of the secondary module for the advanced filter 
 *  It returns a HTML string of combo values
 */

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


/** Function to get the  advanced filter criteria for an option
 *  This function accepts The option in the advenced filter as an argument
 *  This generate filter criteria for the advanced filter 
 *  It returns a HTML string of combo values
 */


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

?>

