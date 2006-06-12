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

require_once('Smarty_setup.php');
require_once('modules/Settings/Forms.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;
global $current_user;

global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH", $image_path);

if(isset($_REQUEST['record']) && $_REQUEST['record']!='')
{
	$sql = "select * from vtiger_mail_accounts where user_id=".$_REQUEST['record'];
	$result = $adb->query($sql);
	$rowcount = $adb->num_rows($result);
	
	if ($rowcount!=0)
	{
		while($temprow = $adb->fetchByAssoc($result))
		{
			$smarty->assign("DISPLAYNAME", $temprow['display_name']);
			$smarty->assign("ID", $temprow['user_id']);
			$smarty->assign("EMAIL", $temprow['mail_id']);
			$smarty->assign("ACCOUNTNAME", $temprow['account_name']);
			$smarty->assign($temprow['mail_protocol'],$temprow['mail_protocol']);
			$smarty->assign("SERVERUSERNAME", $temprow['mail_username']);
			$smarty->assign("SERVERPASSWORD", $temprow['mail_password']);
			$smarty->assign("SERVERNAME", $temprow['mail_servername']);
			$smarty->assign("RECORD_ID", $temprow['account_id']);
			$smarty->assign("BOX_REFRESH", $temprow['box_refresh']);
			$smarty->assign("MAILS_PER_PAGE", $temprow['mails_per_page']);
			$smarty->assign("EDIT", "TRUE");

			if(strtolower($temprow['mail_protocol']) == "imap")
				$smarty->assign("IMAP", "CHECKED");
			if(strtolower($temprow['mail_protocol']) == "imap2")
				$smarty->assign("IMAP2", "CHECKED");
			if(strtolower($temprow['mail_protocol']) == "imap4")
				$smarty->assign("IMAP4", "CHECKED");
			if(strtolower($temprow['mail_protocol']) == "imap4rev1")
				$smarty->assign("IMAP4R1", "CHECKED");
			if(strtolower($temprow['mail_protocol']) == "pop3")
				$smarty->assign("POP3", "CHECKED");

			if(strtolower($temprow['ssltype']) == "notls")
				$smarty->assign("NOTLS", "CHECKED");
			if(strtolower($temprow['ssltype']) == "tls")
				$smarty->assign("TLS", "CHECKED");

			if(strtolower($temprow['sslmeth']) == "validate-cert")
				$smarty->assign("VALIDATECERT", "CHECKED");
			if(strtolower($temprow['sslmeth']) == "novalidate-cert")
				$smarty->assign("NOVALIDATECERT", "CHECKED");

			if($temprow['int_mailer'] == "1")
				$smarty->assign("INT_MAILER_USE", "CHECKED");
			else
				$smarty->assign("INT_MAILER_NOUSE", "CHECKED");

		}
	}
}	

$smarty->assign("RETURN_MODULE","Settings");
$smarty->assign("RETURN_ACTION","index");
$smarty->assign("JAVASCRIPT", get_validate_record_js());
$smarty->assign("USERID", $current_user->id);

$smarty->display('AddMailAccount.tpl');

?>
