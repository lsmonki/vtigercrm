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
require_once('include/database/PearDatabase.php');
require_once('include/logging.php');
require_once('include/utils/utils.php');
require_once('include/utils/UserInfoUtil.php');
require_once('modules/Webmails/MailBox.php');
require_once('modules/Webmails/Webmail.php');

global $adb,$current_user;

if($_POST['config_chk'] == 'true')
{
	$MailBox = new MailBox();
	if($MailBox->enabled == 'false') {
		echo 'FAILED';
		exit();
	} else {
		echo 'SUCESS';
		exit();
	}
	exit();
}
$mailid = $_REQUEST["mailid"];
if(isset($_REQUEST["mailbox"]) && $_REQUEST["mailbox"] != "") {$mailbox=$_REQUEST["mailbox"];} else {$mailbox="INBOX";}

$adb->println("Inside WebmailsAjax.php");

if(isset($_POST["file"]) && $_POST["ajax"] == "true") {
	require_once("modules/".$_REQUEST["module"]."/".$_POST["file"].".php");
}

if(isset($_REQUEST["command"]) && $_REQUEST["command"] != "") {
    $command = $_REQUEST["command"];
    if($command == "expunge") {
    	$MailBox = new MailBox($mailbox);
    	imap_expunge($MailBox->mbox);
	imap_close($MailBox->mbox);
	flush();
	exit();
    }
    if($command == "delete_msg") {
	$adb->println("DELETE SINGLE WEBMAIL MESSAGE $mailid");
    	$MailBox = new MailBox($mailbox);
	$email = new Webmail($MailBox->mbox,$mailid);
       	$email->delete();
	imap_close($MailBox->mbox);
	echo $mailid;
	flush();
	exit();
    }
    if($command == "delete_multi_msg") {
    	$MailBox = new MailBox($mailbox);
	$tlist = explode(":",$mailid);
	foreach($tlist as $id) {
		$adb->println("DELETE MULTI MESSAGE $id");
		$email = new Webmail($MailBox->mbox,$id);
       	 	$email->delete();
	}
	imap_close($MailBox->mbox);
	echo $mailid;
	flush();
	exit();
    } 
    if($command == "undelete_msg") {
    	$MailBox = new MailBox($mailbox);
	$email = new Webmail($MailBox->mbox,$mailid);
        $email->unDeleteMsg();
	imap_close($MailBox->mbox);
	echo $mailid;
	flush();
	exit();
    }
    if($command == "set_flag") {
    	$MailBox = new MailBox($mailbox);
	$email = new Webmail($MailBox->mbox,$mailid);
        $email->setFlag();
	imap_close($MailBox->mbox);
	flush();
	exit();
    }
    if($command == "clear_flag") {
    	$MailBox = new MailBox($mailbox);
	$email = new Webmail($MailBox->mbox,$mailid);
        $email->delFlag();
	imap_close($MailBox->mbox);
	flush();
	exit();
    }

imap_close($MailBox->mbox);
flush();
exit();
}
?>
