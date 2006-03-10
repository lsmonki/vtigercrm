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
			$xtpl->assign("BOX_REFRESH", $temprow['box_refresh']);
			$xtpl->assign("MAILS_PER_PAGE", $temprow['mails_per_page']);
			$xtpl->assign("EDIT", "TRUE");

			if(strtolower($temprow['mail_protocol']) == "imap")
				$xtpl->assign("IMAP", "CHECKED");
			if(strtolower($temprow['mail_protocol']) == "imap2")
				$xtpl->assign("IMAP2", "CHECKED");
			if(strtolower($temprow['mail_protocol']) == "imap4")
				$xtpl->assign("IMAP4", "CHECKED");
			if(strtolower($temprow['mail_protocol']) == "imap4rev1")
				$xtpl->assign("IMAP4R1", "CHECKED");
			if(strtolower($temprow['mail_protocol']) == "pop3")
				$xtpl->assign("POP3", "CHECKED");

			if(strtolower($temprow['ssltype']) == "notls")
				$xtpl->assign("NOTLS", "CHECKED");
			if(strtolower($temprow['ssltype']) == "tls")
				$xtpl->assign("TLS", "CHECKED");

			if(strtolower($temprow['sslmeth']) == "validate-cert")
				$xtpl->assign("VALIDATECERT", "CHECKED");
			if(strtolower($temprow['sslmeth']) == "novalidate-cert")
				$xtpl->assign("NOVALIDATECERT", "CHECKED");

			if(strtolower($temprow['showbody']) == "yes")
				$xtpl->assign("SHOWBODY", "CHECKED");
			if(strtolower($temprow['showbody']) == "no")
				$xtpl->assign("NOSHOWBODY", "CHECKED");
		}
	}
}	

$xtpl->assign("RETURN_MODULE","Settings");
$xtpl->assign("RETURN_ACTION","index");
$xtpl->assign("JAVASCRIPT", get_validate_record_js());
$xtpl->parse("main");
$xtpl->out("main");
?>
