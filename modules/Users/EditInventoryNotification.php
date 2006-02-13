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

require_once('XTemplate/xtpl.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;

echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['INVENTORYNOTIFICATION'], true);
echo '<br><br>';

global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

if(isset($_REQUEST['record']) && $_REQUEST['record']!='') 
{
    $id = $_REQUEST['record'];
    $mode = 'edit'; 	
	$xtpl=new XTemplate ('modules/Users/EditInventoryNotification.html');
	$xtpl->assign("MOD", $mod_strings);
	$xtpl->assign("APP", $app_strings);

	$sql="select * from inventorynotification where notificationid = ".$id;
	$result = $adb->query($sql);
	if($adb->num_rows($result) ==1);
	{
		$label = $mod_strings[$adb->query_result($result,0,'notificationname')];
		$notification_subject = $adb->query_result($result,0,'notificationsubject');
		$notification_body = $adb->query_result($result,0,'notificationbody');

		$xtpl->assign("RETURN_MODULE","Users");
		$xtpl->assign("RETURN_ACTION","listinventorynotifications");
		$xtpl->assign("RECORD_ID",$id);

		if (isset($label))
			$xtpl->assign("LABEL",$label);
		if (isset($notification_subject))
			$xtpl->assign("SUBNOTIFY",$notification_subject);
		if (isset($notification_body))
			$xtpl->assign("BODYNOTIFY",$notification_body);
	}
	$xtpl->parse("main");
	$xtpl->out("main");
}
else
{
	header("Location:index.php?module=Users&action=listnotificationschedulers");
}
?>
