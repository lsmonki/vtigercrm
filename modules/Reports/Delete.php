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
require_once('modules/Reports/Reports.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');

if(isset($_REQUEST['idlist']) && $_REQUEST['idlist']!= '')
{
	$id_array = Array();
	$id_array = explode(':',$_REQUEST['idlist']);
	for($i = 0;$i < count($id_array)-1;$i++)
	{
		DeleteReport($id_array[$i]);	
	}
	header("Location: index.php?action=ReportsAjax&file=ListView&mode=ajax&module=Reports");
}elseif(isset($_REQUEST['record']) && $_REQUEST['record']!= '')
{
	$id = $_REQUEST["record"];
	DeleteReport($id);	
	header("Location: index.php?action=ReportsAjax&file=ListView&mode=ajaxdelete&module=Reports");
}

/** To Delete a Report 
  * @param $reportid -- The report id
  * @returns nothing
  */
       
function DeleteReport($reportid)
{
	global $adb;
	$idelreportsql = "delete from vtiger_selectquery where queryid=?";
	$idelreportsqlresult = $adb->pquery($idelreportsql, array($reportid));

	$ireportsql = "delete from vtiger_report where reportid=?";
    $ireportsqlresult = $adb->pquery($ireportsql, array($reportid));
}
?>
