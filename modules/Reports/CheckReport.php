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

require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
$check=$_REQUEST['check'];


if($_REQUEST['check']== 'reportCheck')
{
	$reportName = $_REQUEST['reportName'];
	$sSQL="select * from vtiger_report where reportname='".$reportName."'";
	
	$sqlresult = $adb->query($sSQL);
	echo $adb->num_rows($sqlresult);

}
else if($_REQUEST['check']== 'folderCheck')
{
	$folderName = $_REQUEST['folderName'];
	$sSQL="select * from vtiger_reportfolder where foldername='".$folderName."'";
	
	$sqlresult = $adb->query($sSQL);
	echo $adb->num_rows($sqlresult);
}

?>




