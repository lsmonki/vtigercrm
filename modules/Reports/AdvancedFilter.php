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
require_once('include/Zend/Json.php');
if(isset($_REQUEST["record"]))
{
	$reportid = $_REQUEST["record"];
	$oReport = new Reports($reportid);
	$oReport->getAdvancedFilterList($reportid);
	
	$BLOCK1 = getPrimaryColumns_AdvFilterHTML($oReport->primodule,$oReport->advft_column[0]);
	$BLOCK1 .= getSecondaryColumns_AdvFilterHTML($oReport->secmodule,$oReport->advft_column[0]);
	$report_std_filter->assign("BLOCK1", $BLOCK1);
	$REL_FIELDS1 = getRelatedColumns($oReport->advft_column[0]);
	$report_std_filter->assign("REL_FIELDS1",$REL_FIELDS1);
	
	$BLOCK2 = getPrimaryColumns_AdvFilterHTML($oReport->primodule,$oReport->advft_column[1]);
	$BLOCK2 .= getSecondaryColumns_AdvFilterHTML($oReport->secmodule,$oReport->advft_column[1]);
	$report_std_filter->assign("BLOCK2", $BLOCK2);
	$REL_FIELDS2 = getRelatedColumns($oReport->advft_column[1]);
	$report_std_filter->assign("REL_FIELDS2",$REL_FIELDS2);
	
	$BLOCK3 = getPrimaryColumns_AdvFilterHTML($oReport->primodule,$oReport->advft_column[2]);
	$BLOCK3 .= getSecondaryColumns_AdvFilterHTML($oReport->secmodule,$oReport->advft_column[2]);
	$report_std_filter->assign("BLOCK3", $BLOCK3);
	$REL_FIELDS3 = getRelatedColumns($oReport->advft_column[2]);
	$report_std_filter->assign("REL_FIELDS3",$REL_FIELDS3);
	
	$BLOCK4 = getPrimaryColumns_AdvFilterHTML($oReport->primodule,$oReport->advft_column[3]);
	$BLOCK4 .= getSecondaryColumns_AdvFilterHTML($oReport->secmodule,$oReport->advft_column[3]);
	$report_std_filter->assign("BLOCK4", $BLOCK4);
	$REL_FIELDS4 = getRelatedColumns($oReport->advft_column[3]);
	$report_std_filter->assign("REL_FIELDS4",$REL_FIELDS4);
	
	$BLOCK5 = getPrimaryColumns_AdvFilterHTML($oReport->primodule,$oReport->advft_column[4]);
	$BLOCK5 .= getSecondaryColumns_AdvFilterHTML($oReport->secmodule,$oReport->advft_column[4]);
	$report_std_filter->assign("BLOCK5", $BLOCK5);
	$REL_FIELDS5 = getRelatedColumns($oReport->advft_column[4]);
	$report_std_filter->assign("REL_FIELDS5",$REL_FIELDS5);
	
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

	$rel_fields = getRelatedFieldColumns();
	$report_std_filter->assign("REL_FIELDS",Zend_Json::encode($rel_fields));
	$visiblecriteria=getVisibleCriteria($recordid);
	$report_std_filter->assign("VISIBLECRITERIA", $visiblecriteria);
	$member = getShareInfo($recordid);
	$report_std_filter->assign("MEMBER", $member);
} else
{
	$primarymodule = $_REQUEST["primarymodule"];
	$BLOCK1 = getPrimaryColumns_AdvFilterHTML($primarymodule);
	$ogReport =  new Reports();
	foreach($ogReport->related_modules[$primarymodule] as $key=>$value){
		//$BLOCK1 .= getSecondaryColumnsHTML($_REQUEST["secondarymodule_".$value]);
	$BLOCK1 .= getSecondaryColumns_AdvFilterHTML($_REQUEST["secondarymodule_".$value]);
	}
	$rel_fields = getRelatedFieldColumns();
	
	$report_std_filter->assign("BLOCK1", $BLOCK1);
	$report_std_filter->assign("BLOCK2", $BLOCK1);
	$report_std_filter->assign("BLOCK3", $BLOCK1);
	$report_std_filter->assign("BLOCK4", $BLOCK1);
	$report_std_filter->assign("BLOCK5", $BLOCK1);
	
	$report_std_filter->assign("REL_FIELDS",Zend_Json::encode($rel_fields));

	$visiblecriteria=getVisibleCriteria();
	$report_std_filter->assign("VISIBLECRITERIA", $visiblecriteria);
}

/** Function to get primary columns for an advanced filter
 *  This function accepts The module as an argument
 *  This generate columns of the primary modules for the advanced filter 
 *  It returns a HTML string of combo values 
 */

function getPrimaryColumns_AdvFilterHTML($module,$selected="")
{
        global $ogReport, $app_list_strings, $current_language;
	$mod_strings = return_module_language($current_language,$module);
        foreach($ogReport->module_list[$module] as $key=>$value)
        {
	    	if(isset($ogReport->pri_module_columnslist[$module][$key]))
	    	{
			$shtml .= "<optgroup label=\"".$app_list_strings['moduleList'][$module]." ".getTranslatedString($key)."\" class=\"select\" style=\"border:none\">";
				foreach($ogReport->pri_module_columnslist[$module][$key] as $field=>$fieldlabel)
				{
					if(isset($mod_strings[$fieldlabel]))
					{
						//fix for ticket 5191
						$selected = decode_html($selected);
						$field = decode_html($field);
						//fix ends
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
			if(isset($ogReport->sec_module_columnslist[$secmodule[$i]][$key]))
			{
                		$shtml .= "<optgroup label=\"".$app_list_strings['moduleList'][$secmodule[$i]]." ".getTranslatedString($key)."\" class=\"select\" style=\"border:none\">";
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

function getRelatedColumns($selected=""){
	global $ogReport;
	$rel_fields = $ogReport->adv_rel_fields;
	if($selected!='All'){
		$selected = split(":",$selected);
	}
	$related_fields = array();
	foreach($rel_fields as $i=>$index){
		$shtml='';
		foreach($index as $key=>$value){
			$fieldarray = split("::",$value);
			$shtml .= "<option value=\"".$fieldarray[0]."\">".$fieldarray[1]."</option>";
		}
		$related_fields[$i] = $shtml;
	}
	if(!empty($selected) && $selected[4]!='')
		return $related_fields[$selected[4]];
	else if($selected=='All'){
		return $related_fields;
	}
	else
		return ;	
}

function getRelatedFieldColumns($selected=""){
	global $ogReport;
	$rel_fields = $ogReport->adv_rel_fields;
	return $rel_fields;
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

/** Function to get visible criteria for a report
 *  This function accepts The reportid as an argument
 *  It returns a array of selected option of sharing along with other options
 */
function getVisibleCriteria($recordid='')
{
	global $mod_strings; 
	global $app_strings;
	global $adb,$current_user;
	//print_r("i am here");die;
	$filter = array();
	
	if($recordid!='')
	{
		$result = $adb->pquery("select sharingtype from vtiger_report where reportid=?",array($recordid));
		$selcriteria=$adb->query_result($result,0,"sharingtype");
	}
	
	$filter_result = $adb->query("select * from vtiger_reportfilters");
	$numrows = $adb->num_rows($filter_result);
	for($j=0;$j<$numrows;$j++)
	{
		$filter_id = $adb->query_result($filter_result,$j,"filterid");
		$filtername = $adb->query_result($filter_result,$j,"name");
		$name=str_replace(' ','_',$filtername);
		if($filtername == 'Private')
		{
			$FilterKey='Private';
			$FilterValue=getTranslatedString('PRIVATE_FILTER');
		}elseif($filtername=='Shared')
		{
			$FilterKey='Shared';
			$FilterValue=getTranslatedString('SHARE_FILTER');
		}else{
			$FilterKey='Public';
			$FilterValue=getTranslatedString('PUBLIC_FILTER');
		}
		if($FilterKey == $selcriteria)
		{
			$shtml['value'] = $FilterKey;
			$shtml['text'] = $FilterValue;
			$shtml['selected'] = "selected";
		}else
		{
			$shtml['value'] = $FilterKey;
			$shtml['text'] = $FilterValue;
			$shtml['selected'] = "";
		}
		$filter[] = $shtml;
	}		
	return $filter;
}

function getShareInfo($recordid=''){
	global $adb;
	$member_query = $adb->pquery("SELECT vtiger_reportsharing.setype,vtiger_users.id,vtiger_users.user_name FROM vtiger_reportsharing INNER JOIN vtiger_users on vtiger_users.id = vtiger_reportsharing.shareid WHERE vtiger_reportsharing.setype='users' AND vtiger_reportsharing.reportid = ?",array($recordid));
	$noofrows = $adb->num_rows($member_query);
	if($noofrows > 0){
		for($i=0;$i<$noofrows;$i++){
			$userid = $adb->query_result($member_query,$i,'id');
			$username = $adb->query_result($member_query,$i,'user_name');
			$setype = $adb->query_result($member_query,$i,'setype');
			$member_data[] = Array('id'=>$setype."::".$userid,'name'=>$setype."::".$username);
		}
	}
	
	$member_query = $adb->pquery("SELECT vtiger_reportsharing.setype,vtiger_groups.groupid,vtiger_groups.groupname FROM vtiger_reportsharing INNER JOIN vtiger_groups on vtiger_groups.groupid = vtiger_reportsharing.shareid WHERE vtiger_reportsharing.setype='groups' AND vtiger_reportsharing.reportid = ?",array($recordid));
	$noofrows = $adb->num_rows($member_query);
	if($noofrows > 0){
		for($i=0;$i<$noofrows;$i++){
			$grpid = $adb->query_result($member_query,$i,'groupid');
			$grpname = $adb->query_result($member_query,$i,'groupname');
			$setype = $adb->query_result($member_query,$i,'setype');
			$member_data[] = Array('id'=>$setype."::".$grpid,'name'=>$setype."::".$grpname);
		}
	}
	return $member_data;
}

?>

