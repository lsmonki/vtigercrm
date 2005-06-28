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

global $adb;

$reportid = $_REQUEST["record"];
if($reportid != "")
{
	$idelreportsql = "delete from selectquery where queryid=".$reportid;
	$idelreportsqlresult = $adb->query($idelreportsql);

	$ireportsql = "delete from report where reportid=".$reportid;
        $ireportsqlresult = $adb->query($ireportsql);
}
header("Location: index.php?action=index&module=Reports");
?>
