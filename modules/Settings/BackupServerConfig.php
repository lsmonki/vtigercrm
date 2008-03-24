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

require_once('user_privileges/enable_backup.php');
require_once('Smarty_setup.php');

global $mod_strings;
global $app_strings, $enable_backup;
global $app_list_strings;
global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

if(isset($_REQUEST['opmode']) && $_REQUEST['opmode'] != '')
{
	$sql_del = "delete from vtiger_systems where server_type=?";
	$adb->pquery($sql_del, array('backup'));
}

$smarty = new vtigerCRM_Smarty;
if($_REQUEST['error'] != '')
{
		$smarty->assign("ERROR_MSG",'<b><font color="red">'.$_REQUEST["error"].'</font></b>');
}
$sql="select * from vtiger_systems where server_type = ?";
$result = $adb->pquery($sql, array('backup'));
$server = $adb->query_result($result,0,'server');
$server_username = $adb->query_result($result,0,'server_username');
$server_password = $adb->query_result($result,0,'server_password');

if(isset($_REQUEST['bkp_server_mode']) && $_REQUEST['bkp_server_mode'] != '')
	$smarty->assign("BKP_SERVER_MODE",$_REQUEST['bkp_server_mode']);
else
	$smarty->assign("BKP_SERVER_MODE",'view');
if(isset($_REQUEST['server']))
	$smarty->assign("FTPSERVER",$_REQUEST['server']);
else if (isset($server))
	$smarty->assign("FTPSERVER",$server);
if (isset($_REQUEST['server_user']))
	$smarty->assign("FTPUSER",$_REQUEST['server_user']);
else if (isset($server_username))
	$smarty->assign("FTPUSER",$server_username);
if (isset($_REQUEST['password']))
	$smarty->assign("FTPPASSWORD",$_REQUEST['password']);
else if (isset($server_password))
	$smarty->assign("FTPPASSWORD",$server_password);


$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP", $app_strings);
$smarty->assign("CMOD", $mod_strings);

if($enable_backup == 'true')	
	$backup_status = 'enabled';
else
	$backup_status = 'disabled';

$smarty->assign("BACKUP_STATUS", $backup_status);

if($_REQUEST['ajax'] == 'true')
	$smarty->display("Settings/BackupServerContents.tpl");
else
	$smarty->display("Settings/BackupServer.tpl");
?>
