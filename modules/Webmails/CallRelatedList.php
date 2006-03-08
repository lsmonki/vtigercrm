<?php
global $current_user;
require_once('Smarty_setup.php');
require_once('modules/Leads/Lead.php');
require_once('include/utils/utils.php');
require_once('include/utils/UserInfoUtil.php');
require_once('data/Tracker.php');
require_once('include/upload_file.php');
require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');
require_once('modules/Webmails/Webmail.php');

global $log;
global $app_strings;
global $mod_strings;

if($_REQUEST["record"]) {$mailid=$_REQUEST["record"];} else {$mailid=$_REQUEST["mailid"];}

$mailInfo = getMailServerInfo($current_user);
$temprow = $adb->fetch_array($mailInfo);

$login_username= $temprow["mail_username"];
$secretkey=$temprow["mail_password"];
$imapServerAddress=$temprow["mail_servername"];
$start_message=$_REQUEST["start_message"];
$box_refresh=$temprow["box_refresh"];
$mails_per_page=$temprow["mails_per_page"];
$mail_protocol=$temprow["mail_protocol"];
$ssltype=$temprow["ssltype"];
$sslmeth=$temprow["sslmeth"];

if($_REQUEST["mailbox"] && $_REQUEST["mailbox"] != "") {$mailbox=$_REQUEST["mailbox"];} else {$mailbox="INBOX";}
global $mbox;
$mbox = @imap_open("\{$imapServerAddress/$mail_protocol/$ssltype/$sslmeth}$mailbox", $login_username, $secretkey) or die("Connection to server failed");

$email = new Webmail($mbox, $mailid);
$from = $email->from;
$subject=$email->subject;
$date=$email->date;
$to=$email->to;
$cc_list=$email->cc_list;
$reply_to=$email->replyTo;


$block["Leads"]= "";
global $adb;
if($email->relationship != 0 && $email->relationship["type"] == "Leads") {
	$q = "SELECT leaddetails.firstname, leaddetails.lastname, leaddetails.email, leaddetails.company, crmentity.smownerid from leaddetails left join crmentity on crmentity.crmid=leaddetails.leadid WHERE leaddetails.leadid='".$email->relationship["id"]."'";
	$rs = $adb->query($q);
	$block["Leads"]["header"]= array("0"=>"First Name","1"=>"Last Name","2"=>"Company Name","3"=>"Email Address","4"=>"Assigned To");
	$block["Leads"]["entries"]= array("0"=>array($adb->query_result($rs,0,'firstname'),"1"=>$adb->query_result($rs,0,'lastname'),2=>$adb->query_result($rs,0,'company'),3=>$adb->query_result($rs,0,'email'),4=>$adb->query_result($rs,0,'smownerid')));
}
$block["Contacts"]= "";
if($email->relationship != 0 && $email->relationship["type"] == "Contacts") {
	$q = "SELECT contactdetails.firstname, contactdetails.lastname, contactdetails.email, contactdetails.title, crmentity.smownerid from contactdetails left join crmentity on crmentity.crmid=contactdetails.contactid WHERE contactdetails.contactid='".$email->relationship["id"]."'";
	$rs = $adb->query($q);
	$block["Contacts"]["header"]= array("0"=>"First Name","1"=>"Last Name","2"=>"Title","3"=>"Email Address","4"=>"Assigned To");
	$block["Contacts"]["entries"]= array("0"=>array($adb->query_result($rs,0,'firstname'),"1"=>$adb->query_result($rs,0,'lastname'),2=>$adb->query_result($rs,0,'title'),3=>$adb->query_result($rs,0,'email'),4=>$adb->query_result($rs,0,'smownerid')));
}
$block["Accounts"]= "";
if($email->relationship != 0 && $email->relationship["type"] == "Accounts") {
	$q = "SELECT acccount.accountname, account.email1, account.website, account.industry, crmentity.smownerid from account left join crmentity on crmentity.crmid=account.accountid WHERE account.accountid='".$email->relationship["id"]."'";
	$rs = $adb->query($q);
	$block["Accounts"]["header"]= array("0"=>"Account Name","1"=>"Email","2"=>"Web Site","3"=>"Industry","4"=>"Assigned To");
	$block["Accounts"]["entries"]= array("0"=>array($adb->query_result($rs,0,'accountname'),"1"=>$adb->query_result($rs,0,'email'),2=>$adb->query_result($rs,0,'website'),3=>$adb->query_result($rs,0,'industry'),4=>$adb->query_result($rs,0,'smownerid')));
}

$smarty = new vtigerCRM_Smarty;
$smarty->assign("CATEGORY","My Home Page");
$smarty->assign("id",$_REQUEST["record"]);
$smarty->assign("NAME","From: ".$from);
$smarty->assign("RELATEDLISTS", $block);
$smarty->assign("SINGLE_MOD","Webmails");
$smarty->assign("REDIR_MOD","Webmails");
$smarty->assign("MODULE", "Webmails");
$smarty->assign("ID",$_REQUEST["record"] );
$smarty->display("RelatedLists.tpl");
?>
