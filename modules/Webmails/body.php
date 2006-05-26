<?php
/*********************************************************************************
 ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
  * ("License"); You may not use this file except in compliance with the License
  * The Initial Developer of the Original Code is FOSS Labs.
  * Portions created by FOSS Labs are Copyright (C) FOSS Labs.
  * Portions created by vtiger are Copyright (C) vtiger.
  * All Rights Reserved.
  *
  ********************************************************************************/

global $current_user;
require_once('include/utils/utils.php');
require_once('include/utils/UserInfoUtil.php');
require_once('modules/Webmails/Webmail.php');
require_once('modules/Webmails/MailParse.php');

if(!isset($_SESSION["authenticated_user_id"]) || $_SESSION["authenticated_user_id"] != $current_user->id) {echo "ajax failed";flush();exit();}

$mailInfo = getMailServerInfo($current_user);
$temprow = $adb->fetch_array($mailInfo);
$imapServerAddress=$temprow["mail_servername"];
$box_refresh=$temprow["box_refresh"];
$mails_per_page=$temprow["mails_per_page"];

$mailid=$_REQUEST["mailid"];
if(isset($_REQUEST["mailbox"]) && $_REQUEST["mailbox"] != "") {$mailbox=$_REQUEST["mailbox"];} else {$mailbox="INBOX";}

global $mbox;
$mbox = getImapMbox($mailbox,$temprow);

$email = new Webmail($mbox,$mailid);
$email->loadMail();

if(isset($_POST["command"])) {
	$command = $_POST["command"];
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
	echo $email->body;
} 
imap_close($mbox);

?>
