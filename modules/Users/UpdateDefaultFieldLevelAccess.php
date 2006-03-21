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

$field_module = Array('Leads','Accounts','Contacts','Potentials','HelpDesk','Products','Notes','Emails','Activities','Events','Vendors','PriceBooks','Quotes','PurchaseOrder','SalesOrder','Invoice');
foreach($field_module as $fld_module)
{
	$fieldListResult = getDefOrgFieldList($fld_module);
	$noofrows = $adb->num_rows($fieldListResult);
	$tab_id = getTabid($fld_module);
	for($i=0; $i<$noofrows; $i++)
	{
		$fieldid =  $adb->query_result($fieldListResult,$i,"fieldid");
		$visible = $_REQUEST[$fieldid];
		if($visible == 'on')
		{
			$visible_value = 0;
		}
		else
		{
			$visible_value = 1;
		}
		//Updating the Mandatory fields
		$uitype = $adb->query_result($fieldListResult,$i,"uitype");
		if($uitype == 2 || $uitype == 51 || $uitype == 6 || $uitype == 22 || $uitype == 73 || $uitype == 24 || $uitype == 81 || $uitype == 50 || $uitype == 23 || $uitype == 16)
		{
			$visible_value = 0; 
		}		

		//Updating the database
		$update_query = "update def_org_field set visible=".$visible_value." where fieldid='".$fieldid."' and tabid=".$tab_id;
		$adb->query($update_query);

	}
}
$loc = "Location: index.php?action=DefaultFieldPermissions&module=Users&fld_module=".$fld_module;
header($loc);

?>
