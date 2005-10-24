<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

global $adb;
$modules_list = explode('&&&',trim($_REQUEST['modules_list'],'&&&'));

//echo '<pre>';print_r($modules_list);echo '</pre>';

foreach($modules_list as $val)
{
	$req_name = 'user_'.strtolower($val);
	$userid = $_REQUEST[$req_name];

	$tabid = getTabid($val);

	//echo '<br>>>>>>>>>'.$val.' == '.$tabid.' == '.$userid;
	if($tabid != '' && $userid != '')
	{
		$sql = 'update moduleowners set user_id = '.$userid.' where tabid = '.$tabid;
		$adb->query($sql);
	}
}

$return_module = $_REQUEST['return_module'];
$return_action = $_REQUEST['return_action'];
header("Location: index.php?module=$return_module&action=$return_action");

?>
