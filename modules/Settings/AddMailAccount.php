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
require_once('modules/Settings/Forms.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;

echo '<br>';
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].' : '.$mod_strings['LBL_ADD_MAIL_ACCOUNT'], true);
echo '<br><br>';

global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/Settings/AddMailAccount.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("POP_SELECT", "CHECKED");

if(isset($_REQUEST['record']) && $_REQUEST['record']!='')
{
	$sql = "select * from mail_accounts where account_id=".$_REQUEST['record'];
	$result = $adb->query($sql);
	$rowcount = $adb->num_rows($result);
	if ($rowcount!=0)
	{
		while($temprow = $adb->fetchByAssoc($result))
		{
			$xtpl->assign("DISPLAYNAME", $temprow['display_name']);
			$xtpl->assign("EMAIL", $temprow['mail_id']);
			$xtpl->assign("ACCOUNTNAME", $temprow['account_name']);
			$xtpl->assign($temprow['mail_protocol'],$temprow['mail_protocol']);
			$xtpl->assign("SERVERUSERNAME", $temprow['mail_username']);
			$xtpl->assign("SERVERPASSWORD", $temprow['mail_password']);
			$xtpl->assign("SERVERNAME", $temprow['mail_servername']);
			$xtpl->assign("RECORD_ID", $temprow['account_id']);
			$xtpl->assign("EDIT", "TRUE");
		}
	}
}	

/*$sql="select * from systems where server_type = 'email'";
$result = $adb->query($sql);
$mail_server = $adb->query_result($result,0,'server');
$mail_server_username = $adb->query_result($result,0,'server_username');
$mail_server_password = $adb->query_result($result,0,'server_password');
*/

$xtpl->assign("RETURN_MODULE","Settings");
$xtpl->assign("RETURN_ACTION","index");
$xtpl->assign("JAVASCRIPT", get_validate_record_js());
/*if (isset($mail_server))
	$xtpl->assign("MAILSERVER",$mail_server);
if (isset($mail_server_username))
	$xtpl->assign("USERNAME",$mail_server_username);
if (isset($mail_server_password))
	$xtpl->assign("PASSWORD",$mail_server_password);
*/
$xtpl->parse("main");
$xtpl->out("main");

?>
