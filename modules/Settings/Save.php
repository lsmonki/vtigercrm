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

$server=$_REQUEST['server'];
$server_username=$_REQUEST['server_username'];
$server_password=$_REQUEST['server_password'];
$server_type = $_REQUEST['server_type'];

if($_REQUEST['smtp_auth'] == 'on' || $_REQUEST['smtp_auth'] == 1)
	$smtp_auth = 'true';
else
	$smtp_auth = 'false';

$sql="select * from systems where server_type = '".$server_type."'";
$id=$adb->query_result($adb->query($sql),0,"id");


if($id=='')
{
	$id = $adb->getUniqueID("systems");
	$sql="insert into systems values(" .$id .",'".$server."','".$server_username."','".$server_password."','".$server_type."','".$smtp_auth."')";
}
else
	$sql="update systems set server = '".$server."', server_username = '".$server_username."', server_password = '".$server_password."', smtp_auth='".$smtp_auth."', server_type = '".$server_type."' where id = ".$id;

$adb->query($sql);
if($server_type == 'backup')
{
	$conn_id = @ftp_connect($server);
	$action = 'BackupServerConfig&bkp_server_mode=edit';
	if(!$conn_id)
	{
		$error_str = 'error=Unable connect to "'.$server.'"';
	}else
	{
		if(!@ftp_login($conn_id, $server_username, $server_password))
		{
			$error_str = 'error=Couldn\'t connect to "'.$server.'" as user "'.$server_username.'"';
		}
		else
		{
			$action = 'BackupServerConfig';
		}
		ftp_close($conn_id);
	}
}
//Added code to send a test mail to the currently logged in user
if($server_type != 'backup')
{
	require_once("modules/Emails/mail.php");
	global $current_user;

	$to_email = getUserEmailId('id',$current_user->id);
	$from_email = $to_email;
	$subject = 'Test mail about the mail server configuration.';
	$description = 'Dear '.$current_user->user_name.', <br><br> This is the test mail which is sent during the outgoing mail server configuration process. <br><br>Thanks <br>'.$current_user->user_name;
	if($to_email != '')
	{
		$mail_status = send_mail('Users',$to_email,$current_user->user_name,$from_email,$subject,$description);
		$mail_status_str = $to_email."=".$mail_status."&&&";
	}
	else
	{
		$mail_status_str = "'".$to_email."'=0&&&";
	}
	$error_str = getMailErrorString($mail_status_str);
	$action = 'EmailConfig';
	if($mail_status != 1)
		$action = 'EmailConfig&emailconfig_mode=edit';
}
header("Location: index.php?module=Settings&action=$action&$error_str");
?>
