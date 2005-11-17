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

$sql="select * from systems where server_type = '".$server_type."'";
$id=$adb->query_result($adb->query($sql),0,"id");


if($id=='')
{
	$id = $adb->getUniqueID("systems");
	$sql="insert into systems values(" .$id .",'".$server."','".$server_username."','".$server_password."','".$server_type."')";
}
else
	$sql="update systems set server = '".$server."', server_username = '".$server_username."', server_password = '".$server_password."', server_type = '".$server_type."' where id = ".$id;

$adb->query($sql);

$action = 'index';
//Added code to send a test mail to the currently logged in user
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
$mail_error_str = getMailErrorString($mail_status_str);
if($mail_status != 1)
	$action = 'EmailConfig';

header("Location: index.php?module=Settings&action=$action&$mail_error_str");
?>
