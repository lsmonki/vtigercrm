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
require_once('modules/Webmails/MailParse.php');

global $adb,$mbox,$current_user;
$mailInfo = getMailServerInfo($current_user);
if($adb->num_rows($mailInfo) < 1) {
        echo "<center><font color='red'><h3>Please configure your mail settings</h3></font></center>";
        exit();
}

$temprow = $adb->fetch_array($mailInfo);

$imapServerAddress=$temprow["mail_servername"];

$mailbox = $_REQUEST["mailbox"];

if($_REQUEST["command"] == "check_mbox") {
	$mbox = getImapMbox($mailbox,$temprow);

	$search = imap_search($mbox, "NEW");
	if($search === false) {echo "failed";flush();exit();}

	$data = imap_fetch_overview($mbox,implode(',',$search));
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
	imap_close($mbox);
}
if($_REQUEST["command"] == "check_mbox_all") {
	$boxes = array();
	$i=0;
        foreach ($_SESSION["mailboxes"] as $key => $val) {
		$mailbox=$key;
		$mbox = getImapMbox($mailbox,$temprow,"true");

		$box = imap_status($mbox, "{".$imapServerAddress."}".$mailbox, SA_ALL);

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
		imap_close($mbox);
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
?>
