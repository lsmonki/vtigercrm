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
require_once("include/database/PearDatabase.php");

global $current_user;
$displayname=$_REQUEST['displayname'];
$userid = $current_user->id;
$email=$_REQUEST['email'];
$account_name=$_REQUEST['account_name'];
$mailprotocol=$_REQUEST['mailprotocol'];
$server_username = $_REQUEST['server_username'];
$server_password = $_REQUEST['server_password'];
$mail_servername = $_REQUEST['mail_servername'];
$box_refresh = $_REQUEST['box_refresh'];
$mails_per_page = $_REQUEST['mails_per_page'];
$ssltype = $_REQUEST["ssltype"];
$sslmeth = $_REQUEST["sslmeth"];

if(isset($_REQUEST['record']) && $_REQUEST['record']!='')
{
	$id=$_REQUEST['record'];
}
#$sql="select * from vtiger_systems where server_type = '".$server_type."'";
#$id=$adb->query_result($adb->query($sql),0,"id");

if(isset($_REQUEST['edit']) && $_REQUEST['edit'] && $_REQUEST['record']!='')
{
	$sql="update vtiger_mail_accounts set display_name = '".$displayname."', mail_id = '".$email."', account_name = '".$account_name."', mail_protocol = '".$mailprotocol."', mail_username = '".$server_username."', mail_password='".$server_password."', mail_servername='".$mail_servername."',  box_refresh='".$box_refresh."',  mails_per_page='".$mails_per_page."', ssltype='".$ssltype."' , sslmeth='".$sslmeth."', int_mailer='".$_REQUEST["int_mailer"]."' where user_id = '".$id."'";
}
else
{
	$account_id = $adb->getUniqueID("vtiger_mail_accounts");
	$sql="insert into vtiger_mail_accounts(account_id, user_id, display_name, mail_id, account_name, mail_protocol, mail_username, mail_password, mail_servername, box_refresh, mails_per_page, ssltype, sslmeth, int_mailer, status, set_default) values(" .$account_id .",'".$current_user->id."','".$displayname."','".$email."','".$account_name."','".$mailprotocol."','".$server_username."','".$server_password."','".$mail_servername."','".$box_refresh."','".$mails_per_page."', '".$ssltype."', '".$sslmeth."', '".$_REQUEST["int_mailer"]."','1','0')";
}

$adb->query($sql);

header("Location:index.php?module=Webmails&action=index&mailbox=INBOX&parenttab=My Home Page");
?>
