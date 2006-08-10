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

global $adb,$MailBox,$current_user;
if(!$MailBox->mbox)
	$MailBox = new MailBox();

if($_POST['config_chk'] == 'true')
{
	if($MailBox->enabled == 'false') {
		echo 'FAILED';
		exit();
	} else {
		echo 'SUCESS';
		exit();
	}
	exit();
}

$mailbox = $_REQUEST["mailbox"];
$mailid=$_REQUEST["mailid"];
if(isset($_REQUEST["mailbox"]) && $_REQUEST["mailbox"] != "") {$mailbox=$_REQUEST["mailbox"];} else {$mailbox="INBOX";}

global $MailBox;
if(isset($_REQUEST["command"])) {
    if(!$MailBox->mbox && $mailbox != "") {
        $MailBox->mailbox = $mailbox;
	$MailBox->getImapMbox(); 
	$MailBox->fullMailList();
    }
    $command = $_REQUEST["command"];
    if($command == "expunge") {
    	imap_expunge($MailBox->mbox);
	imap_close($MailBox->mbox);
	flush();
	exit();
    }
    if($command == "delete_msg") {
	$adb->println("DELETE SINGLE WEBMAIL MESSAGE $mailid");
	$email = new Webmail($MailBox->mbox,$mailid);
       	$email->delete();
	imap_close($MailBox->mbox);
	echo $mailid;
	flush();
	exit();
    }
    if($command == "delete_multi_msg") {
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
	$email = new Webmail($MailBox->mbox,$mailid);
        $email->unDeleteMsg();
	imap_close($MailBox->mbox);
	echo $mailid;
	flush();
	exit();
    }
    if($command == "set_flag") {
	$email = new Webmail($MailBox->mbox,$mailid);
        $email->setFlag();
	imap_close($MailBox->mbox);
	flush();
	exit();
    }
    if($command == "clear_flag") {
	$email = new Webmail($MailBox->mbox,$mailid);
        $email->delFlag();
	imap_close($MailBox->mbox);
	flush();
	exit();
    }

    if($_POST["command"] == "check_mbox") {
	$MailBox->mailbox=$mailbox;
	$MailBox->getImapMbox;

	$search = imap_search($MailBox->mbox, "NEW");
	if($search === false) {echo "failed";flush();exit();}

	$data = imap_fetch_overview($MailBox->mbox,implode(',',$search));
	$num=sizeof($data);

	$ret = '';
	if($num > 0) {
		$ret = '{"mails":[';
		for($i=0;$i<$num;$i++) {
			$ret .= '{"mail":';
			$ret .= '{';
			$ret .= '"mailid":"'.$data[$i]->msgno.'",';
			$ret .= '"subject":"'.substr($data[$i]->subject,0,40).'",';
			$ret .= '"date":"'.substr($data[$i]->date,0,30).'",';
			$ret .= '"from":"'.substr($data[$i]->from,0,20).'",';
			$ret .= '"to":"'.$data[$i]->to.'",';
			if(getAttachments($data[$i]->msgno,$mbox))
				$ret .= '"attachments":"1"}';
			else
				$ret .= '"attachments":"0"}';
			if(($i+1) == $num)
				$ret .= '}';
			else
				$ret .= '},';
		}
		$ret .= ']}';
	}

	echo $ret;
	flush();
	imap_close($MailBox->mbox);
    }

    if($_REQUEST["command"] == "check_mbox_all") {
	$boxes = array();
	$i=0;
        foreach ($_SESSION["mailboxes"] as $key => $val) {
		$mailbox=$key;
		$MailBox->mailbox=$mailbox;
		$MailBox->getImapMbox();

		$box = imap_status($MailBox->mbox, "{".$MailBox->imapServerAddress."}".$mailbox, SA_ALL);

		$boxes[$i]["name"] = $mailbox;
		if($val == $box->unseen)
			$boxes[$i]["newmsgs"] = 0;
		elseif($val < $box->unseen) {
			$boxes[$i]["newmsgs"] = ($box->unseen-$val);
			$_SESSION["mailboxes"][$mailbox] = $box->unseen;
		} else {
			$boxes[$i]["newmsgs"] = 0;
			$_SESSION["mailboxes"][$mailbox] = $box->unseen;
		}
		$i++;
		imap_close($MailBox->mbox);
	}

	$ret = '';
	if(count($boxes) > 0) {
		$ret = '{"msgs":[';
		for($i=0,$num=count($boxes);$i<$num;$i++) {
			$ret .= '{"msg":';
			$ret .= '{';
			$ret .= '"box":"'.$boxes[$i]["name"].'",';
			$ret .= '"newmsgs":"'.$boxes[$i]["newmsgs"].'"}';

			if(($i+1) == $num)
				$ret .= '}';
			else
				$ret .= '},';
		}
		$ret .= ']}';
	}

	echo $ret;
	flush();
	exit();
    }
}
?>
