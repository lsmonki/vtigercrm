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

echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].' : '.$mod_strings['LBL_BACKUP_SERVER_CONFIG'], true);
echo '<br><br>';

global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/Settings/BackupServerConfig.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$sql="select * from systems where server_type = 'backup'";
$result = $adb->query($sql);
$server = $adb->query_result($result,0,'server');
$server_username = $adb->query_result($result,0,'server_username');
$server_password = $adb->query_result($result,0,'server_password');

$xtpl->assign("RETURN_MODULE","Settings");
$xtpl->assign("RETURN_ACTION","index");

if (isset($server))
	$xtpl->assign("FTPSERVER",$server);
if (isset($server_username))
	$xtpl->assign("FTPUSER",$server_username);
if (isset($server_password))
	$xtpl->assign("FTPPASSWORD",$server_password);

$xtpl->parse("main");
$xtpl->out("main");

?>
