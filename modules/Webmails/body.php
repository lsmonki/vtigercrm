<?php
global $current_user;
require_once('include/utils/utils.php');
require_once('include/utils/UserInfoUtil.php');
require_once('modules/Webmails/Webmail.php');

$mailInfo = getMailServerInfo($current_user);
$temprow = $adb->fetch_array($mailInfo);
$login_username= $temprow["mail_username"];
$secretkey=$temprow["mail_password"];
$imapServerAddress=$temprow["mail_servername"];
$box_refresh=$temprow["box_refresh"];
$mails_per_page=$temprow["mails_per_page"];
$mail_protocol=$temprow["mail_protocol"];
$ssltype=$temprow["ssltype"];
$sslmeth=$temprow["sslmeth"];

$mailid=$_REQUEST["mailid"];
if(isset($_REQUEST["mailbox"]) && $_REQUEST["mailbox"] != "") {$mailbox=$_REQUEST["mailbox"];} else {$mailbox="INBOX";}

global $mbox;
$mbox = @imap_open("\{$imapServerAddress/$mail_protocol/$ssltype/$sslmeth}$mailbox", $login_username, $secretkey) or die("Connection to server failed");

$email = new Webmail($mbox,$mailid);
$email->loadMail();

if(isset($_REQUEST["command"])) {
	$command = $_REQUEST["command"];
	if($command == "expunge")
		imap_expunge($mbox);
	if($command == "delete_msg")
		 $email->delete();
	if($command == "undelete_msg")
		 $email->unDeleteMsg();
	if($command == "set_flag")
		 $email->setFlag();
	if($command == "clear_flag")
		 $email->delFlag();
} else {
	echo ($email->body);
}
imap_close($mbox);
?>
