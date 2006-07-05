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

global $adb;
if(isset($_REQUEST['audit_trail']) && $_REQUEST['audit_trail'] != '')
{
	$qry ="select * from vtiger_systems where server_type = 'audit_trail'";
	$result = $adb->query($qry);
	$noofrows = $adb->num_rows($result);
	
	if ($noofrows == 0)	
	{
		$qry1 = "Insert into vtiger_systems values (".$adb->getUniqueID('vtiger_systems')." , '".$_REQUEST[audit_trail]."', '', '', '', 'audit_trail', '')";
		$qry1_result = $adb->query($qry1);
	}
	else
	{
		$qry2 = "Update vtiger_systems set server = '".$_REQUEST[audit_trail]."' where server_type = 'audit_trail'";
		$qry2_result = $adb->query($qry2);
	}	
 
}
 ?>
