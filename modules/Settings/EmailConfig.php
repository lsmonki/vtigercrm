<?php

require_once('XTemplate/xtpl.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;

echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].' : '.$mod_strings['LBL_EMAIL_CONFIG'], true);
echo '<br><br>';

global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/Settings/EmailConfig.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$sql="select * from systems";
$result = $adb->query($sql);
$mail_server = $adb->query_result($result,0,'mail_server');
$mail_server_username = $adb->query_result($result,0,'mail_server_username');
$mail_server_password = $adb->query_result($result,0,'mail_server_password');

$xtpl->assign("RETURN_MODULE","Settings");
$xtpl->assign("RETURN_ACTION","index");

if (isset($mail_server))
	$xtpl->assign("MAILSERVER",$mail_server);
if (isset($mail_server_username))
	$xtpl->assign("USERNAME",$mail_server_username);
if (isset($mail_server_password))
	$xtpl->assign("PASSWORD",$mail_server_password);

$xtpl->parse("main");
$xtpl->out("main");

?>
