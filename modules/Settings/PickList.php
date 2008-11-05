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
require_once 'include/utils/CommonUtils.php';
global $mod_strings;
global $app_strings;
global $app_list_strings;
global $current_language, $currentModule;

if(isset($_REQUEST['fld_module']) && $_REQUEST['fld_module'] != '')
{
	$fld_module = $_REQUEST['fld_module'];
	$roleid = $_REQUEST['roleid'];
}
else
{
	$fld_module = 'Potentials';
	$roleid='H2';
}

if(isset($_REQUEST['uitype']) && $_REQUEST['uitype'] != '')
	$uitype = $_REQUEST['uitype'];

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MODULE_LISTS",getPickListModules());
$smarty->assign("ROLE_LISTS",getrole2picklist());

$picklists_entries = getUserFldArray($fld_module,$roleid);

$available_module_picklist = get_available_module_picklist($picklists_entries);
$smarty->assign("ALL_LISTS",$available_module_picklist);


if((sizeof($picklists_entries) %3) != 0)
	$value = (sizeof($picklists_entries) + 3 - (sizeof($picklists_entries))%3); 
else
	$value = sizeof($picklists_entries);

if($fld_module == 'Events')

	$temp_module_strings = return_module_language($current_language, 'Calendar');
else
	$temp_module_strings = return_module_language($current_language, $fld_module);

$smarty->assign("TEMP_MOD", $temp_module_strings);
$picklist_fields = array_chunk(array_pad($picklists_entries,$value,''),3);
$smarty->assign("MODULE",$fld_module);
$smarty->assign("PICKLIST_VALUES",$picklist_fields);
$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP", $app_strings);
$smarty->assign("CMOD", $mod_strings);
$smarty->assign("UITYPE", $uitype);
$smarty->assign("SEL_ROLEID",$roleid);
$smarty->assign("SEL_MODULE",$fld_module);

if($_REQUEST['directmode'] != 'ajax')
	$smarty->display("Settings/PickList.tpl");
else
	$smarty->display("Settings/PickListContents.tpl");
	
	/** Function to get picklist fields for the given module 
	 *  @ param $fld_module
	 *  It gets the picklist details array for the given module in the given format
	 *  			$fieldlist = Array(Array('fieldlabel'=>$fieldlabel,'generatedtype'=>$generatedtype,'columnname'=>$columnname,'fieldname'=>$fieldname,'value'=>picklistvalues))	
	 */

function getUserFldArray($fld_module,$roleid)
{
	global $adb, $log;
	$user_fld = Array();
	$tabid = getTabid($fld_module);
	
	$query="select vtiger_field.fieldlabel,vtiger_field.columnname,vtiger_field.fieldname, vtiger_field.uitype" .
			" FROM vtiger_field inner join vtiger_picklist on vtiger_field.fieldname = vtiger_picklist.name" .
			" where (displaytype in(1,5) and vtiger_field.tabid=? and vtiger_field.uitype in ('15','16','111','55','33') " .
			" or (vtiger_field.tabid=? and fieldname='salutationtype' and fieldname !='vendortype')) " .
			" and vtiger_picklist.picklistid in (select picklistid from vtiger_role2picklist where roleid = ?)" .
			" ORDER BY vtiger_picklist.picklistid ASC";
	$params = array($tabid,$tabid,$roleid);

	$result = $adb->pquery($query, $params);
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
				if($adb->query_result($result,$i,"fieldname") != 'recurringtype' && $adb->query_result($result,$i,"fieldname") != 'visibility')	
				{	
					$user_fld['fieldlabel'] = $adb->query_result($result,$i,"fieldlabel");	
					$user_fld['generatedtype'] = $adb->query_result($result,$i,"generatedtype");	
					$user_fld['columnname'] = $adb->query_result($result,$i,"columnname");	
					$user_fld['fieldname'] = $adb->query_result($result,$i,"fieldname");	
					$user_fld['uitype'] = $adb->query_result($result,$i,"uitype");	
					$user_fld['value'] = getPickListValues($user_fld['fieldname'],$roleid); 
					$fieldlist[] = $user_fld;
				}
			}
			else
			{
					$user_fld['fieldlabel'] = $adb->query_result($result,$i,"fieldlabel");	
					$user_fld['generatedtype'] = $adb->query_result($result,$i,"generatedtype");	
					$user_fld['columnname'] = $adb->query_result($result,$i,"columnname");	
					$user_fld['fieldname'] = $adb->query_result($result,$i,"fieldname");	
					$user_fld['uitype'] = $adb->query_result($result,$i,"uitype");	
					$user_fld['value'] = getPickListValues($user_fld['fieldname'],$roleid); 
					$fieldlist[] = $user_fld;
			}
    	}
    }
    return $fieldlist;
}
	/** Function to get modules which has picklist values  
	 *  It gets the picklist modules and return in an array in the following format 
	 *  			$modules = Array($tabid=>$tablabel,$tabid1=>$tablabel1,$tabid2=>$tablabel2,-------------,$tabidn=>$tablabeln)	
	 */
function getPickListModules()
{
	global $adb;
	// vtlib customization: Ignore disabled modules.
	//$query = 'select distinct vtiger_field.fieldname,vtiger_field.tabid,tablabel,uitype from vtiger_field inner join vtiger_tab on vtiger_tab.tabid=vtiger_field.tabid where uitype IN (15,16, 111,33) and vtiger_field.tabid != 29 order by vtiger_field.tabid ASC';
	$query = 'select distinct vtiger_field.fieldname,vtiger_field.tabid,tablabel,uitype from vtiger_field inner join vtiger_tab on vtiger_tab.tabid=vtiger_field.tabid where uitype IN (15,16, 111,33) and vtiger_field.tabid != 29 and vtiger_tab.presence != 1 order by vtiger_field.tabid ASC';
	// END
	$result = $adb->pquery($query, array());
	while($row = $adb->fetch_array($result))
	{
		$modules[$row['tabid']] = $row['tablabel']; 
	}
	return $modules;
}
function getrole2picklist()
{
	global $adb;
	$query = "select rolename,roleid from vtiger_role where roleid not in('H1') order by roleid";
	$result = $adb->pquery($query, array());
	while($row = $adb->fetch_array($result))
	{
		$role[$row['roleid']] = $row['rolename'];
	}
	return $role;

}
function get_available_module_picklist($picklist_details)
{
	$avail_pick_values = $picklist_details;
	foreach($avail_pick_values as $key => $val)
	{
		$module_pick[$avail_pick_values[$key]['fieldname']] = getTranslatedString($avail_pick_values[$key]['fieldlabel']);
	}
	return $module_pick;	
}
?>
