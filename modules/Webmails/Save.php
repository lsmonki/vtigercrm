<?php

require_once('modules/Emails/Email.php');
require_once('modules/Webmails/Webmail.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('include/utils/UserInfoUtil.php');
global $current_user;

$local_log =& LoggerManager::getLogger('index');
$focus = new Email();

$to_address = explode(";",$_REQUEST['to_list']);
$cc_address = explode(";",$_REQUEST['cc_list']);
$bcc_address = explode(";",$_REQUEST['bcc_list']);

$date = $_REQUEST["date_start"];
$subject = $_REQUEST['subject'];

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

$email = new Webmail($mbox, $_REQUEST["mailid"]);

if(isset($_REQUEST["email_body"]))
	$msgData = $_REQUEST["email_body"];
else {
	$email->loadMail();
	$msgData = $email->body;
	$subject = $email->subject;
	$imported=true;
}
if($email->relationship != 0)
{
	$focus->column_fields['parent_id']=$email->relationship["id"];
}

$focus->column_fields['subject']=$subject;
$focus->column_fields["activitytype"]="Emails";

$ddate = date("Y-m-d");
$dtime = date("h:m");
$focus->column_fields["assigned_user_id"]=$current_user->id;
$focus->column_fields["date_start"]=$ddate;
$focus->column_fields["time_start"]=$dtime;

$tmpBody = preg_replace(array('/<br(.*?)>/i',"/&gt;/i","/&lt;/i","/&nbsp;/i","/&amp/i","/&copy;/i","/<style(.*?)>(.*?)<\/style>/i","/\{(.*?)\}/i","/BODY/i"),array("\r",">","<"," ","&","(c)","","",""),$msgData);
$focus->column_fields["description"]=strip_tags($tmpBody);

$focus->save("Emails");
$return_id = $_REQUEST["mailid"];
$return_module='Webmails';
$return_action='ListView';


// check for relationships
if(!isset($_REQUEST["email_body"])) {
    $return_id = $focus->id;
    $return_module='Emails';
    $return_action='DetailView';
    $tables = array("account"=>array("email1","email2"),"contactdetails"=>array("email"),"leaddetails"=>array("email"));
    $ids = array("accountid","contactid","leadid");
    $i=0;
    foreach($tables as $key=>$value) {
	for($j=0;$j<count($tables[$key]);$j++) {
		$q = "SELECT ".$ids[$i]." AS id FROM ".$key." WHERE ".$tables[$key][$j]."='".$email->from."'";
		$rs = $adb->query($q);
		if($adb->num_rows($rs) > 0) {
			$entity = $adb->fetch_array($rs);
			$q = "INSERT INTO seactivityrel (crmid,activityid) VALUES ('".$entity["id"]."','".$focus->id."')";
			$rs = $adb->query($q);
		}
	}
	$i++;
    }
}

if(isset($_REQUEST["send_mail"]) && $_REQUEST["send_mail"] == "true") {
	require_once("sendmail.php");
	global $adb;
	$sql = "select email1, first_name,last_name from users where id='".$current_user->id."'";
	$res = $adb->query($sql);
	$emailaddr = $adb->query_result($res,0,'email1');
	$who = $adb->query_result($res,0,'first_name')." ".$adb->query_result($res,0,'last_name');
	sendmail($to_address,$cc_address,$bcc_address,$emailaddr,$who,$subject,$msgData);
	header("Location: index.php?action=$return_action&module=$return_module");
} else
	header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");

return;
?>
