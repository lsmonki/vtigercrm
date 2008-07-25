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

require_once('include/database/PearDatabase.php');
require_once('include/utils/UserInfoUtil.php');
require_once('include/utils/utils.php');
global $adb;


$field_module=getFieldModuleAccessArray();
foreach($field_module as $fld_module=>$fld_name)
{
	$fieldListResult = getDefOrgFieldList($fld_module);
	$noofrows = $adb->num_rows($fieldListResult);
	$tab_id = getTabid($fld_module);
	for($i=0; $i<$noofrows; $i++)
	{
		$fieldid =  $adb->query_result($fieldListResult,$i,"fieldid");
		$displaytype = $adb->query_result($fieldListResult,$i,"displaytype");
		$visible = $_REQUEST[$fieldid];
		if($visible == 'on')
		{
			$visible_value = 0;
		}
		else
		{
			$visible_value = 1;
		}
		//Updating the Mandatory vtiger_fields
		$uitype = $adb->query_result($fieldListResult,$i,"uitype");
		$fieldname = $adb->query_result($fieldListResult,$i,"fieldname");
		$typeofdata = $adb->query_result($fieldListResult,$i,"typeofdata");
		$fieldtype = explode("~",$typeofdata);
		if($uitype == 2 || $uitype == 3 || $uitype == 6 || $uitype == 22 || $uitype == 73 || $uitype == 24 || $uitype == 81 || $uitype == 50 || $uitype == 23 || $uitype == 16 || $uitype == 53 || $uitype == 255 || $displaytype == 3 || $uitype == 20 || ($displaytype != 3 && $fieldname == "activitytype" && $uitype == 15)|| ($uitype == 111 && $fieldtype[1] == "M") || ($uitype == 117 && $fieldtype[1] == "M"))
		{
			$visible_value = 0; 
		}		

		//Updating the database
		$update_query = "update vtiger_def_org_field set visible=? where fieldid=? and tabid=?";
		$update_params = array($visible_value, $fieldid, $tab_id);
		$adb->pquery($update_query, $update_params);

	}
}
$loc = "Location: index.php?action=DefaultFieldPermissions&module=Settings&parenttab=Settings&fld_module=".$_REQUEST['fld_module'];
header($loc);

?>
