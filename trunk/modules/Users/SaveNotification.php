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
global $adb;

if(isset($_REQUEST['record']) && $_REQUEST['record']!='')
{
	$query="UPDATE vtiger_notificationscheduler set notificationsubject='".$_REQUEST['notifysubject']."', notificationbody='".$_REQUEST['notifybody']."', active =".$_REQUEST['active']." where schedulednotificationid=".$_REQUEST['record'];
	$adb->query($query);	
}
$loc = "Location: index.php?action=UsersAjax&file=listnotificationschedulers&module=Users&directmode=ajax";
header($loc);
?>
