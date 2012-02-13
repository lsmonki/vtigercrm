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
require_once('include/CustomFieldUtil.php');
require_once 'include/Webservices/DescribeObject.php';
global $mod_strings,$app_strings;

$smarty=new vtigerCRM_Smarty;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$smarty->assign("IMAGE_PATH", $image_path);

/**
 * Function to get leads mapping custom fields
 * return array   $leadcf - mapping custom fields
 */

function customFieldMappings()
{
	global $adb;
	$tabid = getTabid('Leads');
	$convert_sql="SELECT vtiger_convertleadmapping.*,uitype,fieldlabel,typeofdata FROM vtiger_convertleadmapping LEFT JOIN vtiger_field
				ON vtiger_field.fieldid = vtiger_convertleadmapping.leadfid
				WHERE tabid=?
				AND vtiger_field.presence IN (0,2)
				AND vtiger_field.fieldname NOT IN('assigned_user_id','company','createdtime','modifiedtime','lead_no','modifiedby','campaignrelstatus')
				ORDER BY vtiger_field.fieldlabel";
	$convert_result = $adb->pquery($convert_sql, array($tabid));

	$no_rows = $adb->num_rows($convert_result);
	for($j=0; $j<$no_rows; $j++)
	{
		$lead_field_id=$adb->query_result($convert_result,$j,"fieldlabel");
		if(!empty($lead_field_id)){
			
			$cfmid = $adb->query_result($convert_result,$j,"cfmid");
			$accountid=$adb->query_result($convert_result,$j,"accountfid");
			$contactid=$adb->query_result($convert_result,$j,"contactfid");
			$potentialid=$adb->query_result($convert_result,$j,"potentialfid");
			$lead_field['sno'] = $j+1;
			$lead_field['cfmid']=$cfmid;
			$lead_field['cfmname']=$cfmid.'_cfmid';
			$lead_field['leadid'] = getTranslatedString($adb->query_result($convert_result,$j,"fieldlabel"),'Leads');
			$lead_field['typeofdata']=$adb->query_result($convert_result,$j,"typeofdata");
			$lead_field['fieldtype'] = getCustomFieldTypeName($adb->query_result($convert_result,$j,"uitype"));
			$lead_field['account'] = getModuleValues($cfmid,$accountid,'Accounts');
			$lead_field['contact'] = getModuleValues($cfmid,$contactid,'Contacts');
			$lead_field['potential'] = getModuleValues($cfmid,$potentialid,'Potentials');
			$leadcf[]= $lead_field;
		}
	}
	return $leadcf;
}

/*
 * function to get all the field in given module, with the corresponding field mapping
 */
function getModuleValues($cfmid,$moduleid,$module){
	global $adb;
	$potentialcf=Array();
	$sql="select fieldid,fieldlabel,uitype,typeofdata from vtiger_field,vtiger_tab where vtiger_field.tabid=vtiger_tab.tabid and generatedtype in (1,2) and vtiger_tab.name=? and vtiger_field.presence in (0,2)";
	$result = $adb->pquery($sql, array($module));
	$noofrows = $adb->num_rows($result);
	for($i=0; $i<$noofrows; $i++)
	{
		$module_field['fieldid']=$adb->query_result($result,$i,"fieldid");
		$module_field['fieldlabel']=getTranslatedString($adb->query_result($result,$i,"fieldlabel"),$module);
		$module_field['typeofdata']=$adb->query_result($result,$i,"typeofdata");
		$module_field['fieldtype']=getCustomFieldTypeName($adb->query_result($result,$i,"uitype"));

		if($module_field['fieldid']==$moduleid)
			 $module_field['selected']="selected";
		else
             $module_field['selected'] = "";
		$module_cfelement[]=$module_field;
	}
	$modulecf[$cfmid.'_'.$module]=$module_cfelement;
        return $modulecf;
}

$module = 'Leads';
$smarty->assign("MOD",$mod_strings);
$smarty->assign("APP",$app_strings);
$smarty->assign("THEME", $theme);
$smarty->assign("CUSTOMFIELDMAPPING",customFieldMappings());
$smarty->assign("MODULE",$module);
$smarty->display("CustomFieldMapping.tpl");

?>
