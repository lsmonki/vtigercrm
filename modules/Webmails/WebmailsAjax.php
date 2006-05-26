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

session_start();
if(!isset($_SESSION["authenticated_user_id"]) || $_SESSION["authenticated_user_id"] == "") {exit();}

ini_set("include_path","../../");
require_once('config.php');
require_once('include/database/PearDatabase.php');
require_once('include/logging.php');
require_once('include/utils/utils.php');
require_once('include/utils/UserInfoUtil.php');
require_once('modules/Webmails/MailParse.php');

global $adb,$mbox,$current_user;

$sql = "select * from mail_accounts where status=1 and user_id='".$_SESSION["authenticated_user_id"]."'";
$mailInfo = $adb->query($sql);

if($adb->num_rows($mailInfo) < 1) {
        echo "<center><font color='red'><h3>Please configure your mail settings</h3></font></center>";
        exit();
}

$temprow = $adb->fetch_array($mailInfo);
$imapServerAddress=$temprow["mail_servername"];
$box_refresh=$temprow["box_refresh"];
$mails_per_page=$temprow["mails_per_page"];
$account_name=$temprow["account_name"];
$show_hidden=$_REQUEST["show_hidden"];

$mbox = getImapMbox($mailbox,$temprow);

$search = imap_search($mbox, "NEW ALL");
if($search === false) {echo "";flush();exit();}

$data = imap_fetch_overview($mbox,implode(',',$search));
$num=sizeof($data);

$ret = '';
if($num > 0) {
	$ret = '{"mails":[';
	for($i=0;$i<$num;$i++) {
		$part = imap_fetchstructure($mbox,$data[$i]->msgno);
		$ret .= '{"mail":';
		$ret .= '{';
		$ret .= '"mailid":"'.$data[$i]->msgno.'",';
		$ret .= '"subject":"'.substr($data[$i]->subject,0,50).'",';
		$ret .= '"date":"'.$data[$i]->date.'",';
		$ret .= '"from":"'.$data[$i]->from.'",';
		if(sizeof($part->parts) >0)
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
?>
