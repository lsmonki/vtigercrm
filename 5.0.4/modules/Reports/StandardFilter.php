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

if(isset($_REQUEST["record"]) == false)
{
        $oReport = new Reports();
        $primarymodule = $_REQUEST["primarymodule"];
        $secondarymodule = $_REQUEST["secondarymodule"];

	$BLOCK1 = getPrimaryStdFilterHTML($primarymodule);
	$BLOCK1 .= getSecondaryStdFilterHTML($secondarymodule);

		$report_std_filter->assign("BLOCK1_STD",$BLOCK1);
        $BLOCKJS = $oReport->getCriteriaJS();
		$report_std_filter->assign("BLOCKJS_STD",$BLOCKJS);
        $BLOCKCRITERIA = $oReport->getSelectedStdFilterCriteria();
		$report_std_filter->assign("BLOCKCRITERIA_STD",$BLOCKCRITERIA);

}elseif(isset($_REQUEST["record"]) == true)
{
        $reportid = $_REQUEST["record"];
        $oReport = new Reports($reportid);
        $oReport->getSelectedStandardCriteria($reportid);
	
		$BLOCK1 = getPrimaryStdFilterHTML($oReport->primodule,$oReport->stdselectedcolumn);
        $BLOCK1 .= getSecondaryStdFilterHTML($oReport->secmodule,$oReport->stdselectedcolumn);
		$report_std_filter->assign("BLOCK1_STD",$BLOCK1);

        $BLOCKJS = $oReport->getCriteriaJS();
		$report_std_filter->assign("BLOCKJS_STD",$BLOCKJS);

        $BLOCKCRITERIA = $oReport->getSelectedStdFilterCriteria($oReport->stdselectedfilter);
		$report_std_filter->assign("BLOCKCRITERIA_STD",$BLOCKCRITERIA);

	if(isset($oReport->startdate) && isset($oReport->enddate))
	{
		$report_std_filter->assign("STARTDATE_STD",getDisplayDate($oReport->startdate));
                $report_std_filter->assign("ENDDATE_STD",getDisplayDate($oReport->enddate));
	}else{
		$report_std_filter->assign("STARTDATE_STD",$oReport->startdate);
		$report_std_filter->assign("ENDDATE_STD",$oReport->enddate);
	}	
}


	/** Function to get the HTML strings for the primarymodule standard filters
	 * @ param $module : Type String
	 * @ param $selected : Type String(optional)
	 *  This Returns a HTML combo srings
	 */
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

	/** Function to get the HTML strings for the secondary  standard filters
	 * @ param $module : Type String
	 * @ param $selected : Type String(optional)
	 *  This Returns a HTML combo srings for the secondary modules
	 */
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
?>

