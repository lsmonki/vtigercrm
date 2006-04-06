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
require_once('include/database/PearDatabase.php');
require_once('database/DatabaseConnection.php');
require_once('themes/'.$theme.'/layout_utils.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;

if(isset($_REQUEST['fld_module']) && $_REQUEST['fld_module'] != '')
	$fld_module = $_REQUEST['fld_module'];
else	
	$fld_module = 'Potentials';

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MODULE_LISTS",getPickListModules());

$picklists_entries = getUserFldArray($fld_module);
if((sizeof($picklists_entries) %3) != 0)
	$value = (sizeof($picklists_entries) + 3 - (sizeof($picklists_entries))%3); 
else
	$value = sizeof($picklists_entries);

$picklist_fields = array_chunk(array_pad($picklists_entries,$value,''),3);
$smarty->assign("MODULE",$fld_module);
$smarty->assign("PICKLIST_VALUES",$picklist_fields);
$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP", $app_strings);
$smarty->assign("CMOD", $mod_strings);

if($_REQUEST['directmode'] != 'ajax')
	$smarty->display("Settings/PickList.tpl");
else
	$smarty->display("Settings/PickListContents.tpl");
	
function getUserFldArray($fld_module)
{
	global $adb;
	$user_fld = Array();
	$tabid = getTabid($fldmodule);
	$query = "select fieldlabel,generatedtype,columnname,fieldname from field where displaytype = 1 and (tabid = ".getTabid($fld_module)." && uitype IN (15,16)) || (tabid = ".getTabid($fld_module)." && fieldname='salutationtype')";
	$result = $adb->query($query);
	$noofrows = $adb->num_rows($result);
    if($noofrows > 0)
    {
		$fieldlist = Array();
    	for($i=0; $i<$noofrows; $i++)
    	{
			$user_fld = Array();
			$fld_name = $adb->query_result($result,$i,"fieldname");
			if($fld_module == 'Events')	
			{
				if($adb->query_result($result,$i,"fieldname") != 'recurringtype' && $adb->query_result($result,$i,"fieldname") != 'activitytype' && $adb->query_result($result,$i,"fieldname") != 'taskpriority' && $adb->query_result($result,$i,"fieldname") != 'visibility')	
				{	
					$user_fld['fieldlabel'] = $adb->query_result($result,$i,"fieldlabel");	
					$user_fld['generatedtype'] = $adb->query_result($result,$i,"generatedtype");	
					$user_fld['columnname'] = $adb->query_result($result,$i,"columnname");	
					$user_fld['fieldname'] = $adb->query_result($result,$i,"fieldname");	
					$user_fld['value'] = getPickListValues($user_fld['fieldname']); 
					$fieldlist[] = $user_fld;
				}
			}
			elseif($fld_module == 'Faq' )
			{
				
				if($adb->query_result($result,$i,"fieldname") != 'faqstatus')
				{
					$user_fld['fieldlabel'] = $adb->query_result($result,$i,"fieldlabel");	
					$user_fld['generatedtype'] = $adb->query_result($result,$i,"generatedtype");	
					$user_fld['columnname'] = $adb->query_result($result,$i,"columnname");	
					$user_fld['fieldname'] = $adb->query_result($result,$i,"fieldname");	
					$user_fld['value'] = getPickListValues($user_fld['fieldname']); 
					$fieldlist[] = $user_fld;
				}
			}
			else
			{
				if($fld_name != 'invoicestatus' && $fld_name != 'quotestage' && $fld_name != 'postatus' && $fld_name != 'sostatus')
				{
					$user_fld['fieldlabel'] = $adb->query_result($result,$i,"fieldlabel");	
					$user_fld['generatedtype'] = $adb->query_result($result,$i,"generatedtype");	
					$user_fld['columnname'] = $adb->query_result($result,$i,"columnname");	
					$user_fld['fieldname'] = $adb->query_result($result,$i,"fieldname");	
					$user_fld['value'] = getPickListValues($user_fld['fieldname']); 
					$fieldlist[] = $user_fld;
				}
			}
    	}
    }
    return $fieldlist;
}

function getPickListValues($tablename)
{
	global $adb;	
	$query = "select * from ".$tablename." order by sortorderid";
	$result = $adb->query($query);
	$fldVal = Array();
	while($row = $adb->fetch_array($result))
	{
		$fldVal []= $row[$tablename];
	}
	return $fldVal;
}
function getPickListModules()
{
	global $adb;
	$query = 'select distinct field.fieldname,field.tabid,tablabel from field inner join tab on tab.tabid=field.tabid where uitype IN (15,16)';
	$result = $adb->query($query);
	while($row = $adb->fetch_array($result))
	{
		if($row['fieldname'] != 'invoicestatus')	
			$modules[$row['tabid']] = $row['tablabel']; 
	}
	return $modules;
}
?>
