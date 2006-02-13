<?
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
global $adb;

//if($_REQUEST['module']=='Users')
if(isset($_REQUEST['user_id']) && $_REQUEST['user_id'] != '')
{
	$record = $_REQUEST['record'];
	$sql = "insert into salesmanactivityrel values (". $_REQUEST["user_id"] .",".$_REQUEST["record"] .")";
}
else
{
	$record = $_REQUEST["parid"];
	$sql = "insert into seactivityrel values (". $_REQUEST["entityid"] .",".$_REQUEST["parid"] .")";
}

$adb->query($sql);
 header("Location: index.php?action=DetailView&module=Activities&activity_mode=Events&record=".$record);


?>
