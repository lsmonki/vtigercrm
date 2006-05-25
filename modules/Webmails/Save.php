<?php

require_once('modules/Emails/Email.php');
require_once('modules/Webmails/Webmail.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('include/utils/UserInfoUtil.php');
require_once('include/utils/CommonUtils.php');
global $current_user;


$local_log =& LoggerManager::getLogger('index');
$focus = new Email();

$to_address = explode(";",$_REQUEST['to_list']);
$cc_address = explode(";",$_REQUEST['cc_list']);
$bcc_address = explode(";",$_REQUEST['bcc_list']);

$date = $_REQUEST["date_start"];
$subject = $_REQUEST['subject'];

$start_message=$_REQUEST["start_message"];

$mailInfo = getMailServerInfo($current_user);
if($adb->num_rows($mailInfo) < 1) {
	echo "<center><font color='red'><h3>Please configure your mail settings</h3></font></center>";
	exit();
}

$temprow = $adb->fetch_array($mailInfo);
$login_username= $temprow["mail_username"];
$secretkey=$temprow["mail_password"];
$imapServerAddress=$temprow["mail_servername"];
$box_refresh=$temprow["box_refresh"];
$mails_per_page=$temprow["mails_per_page"];
$mail_protocol=$temprow["mail_protocol"];
$ssltype=$temprow["ssltype"];
$sslmeth=$temprow["sslmeth"];
$account_name=$temprow["account_name"];

if($_REQUEST["mailbox"] && $_REQUEST["mailbox"] != "") {$mailbox=$_REQUEST["mailbox"];} else {$mailbox="INBOX";}

global $mbox;
if($ssltype == "") {$ssltype = "notls";}
if($sslmeth == "") {$sslmeth = "novalidate-cert";}
$mbox = @imap_open("{".$imapServerAddress."/".$mail_protocol."/".$ssltype."/".$sslmeth."}".$mailbox, $login_username, $secretkey);

if(!$mbox)
        $mbox = @imap_open("{".$imapServerAddress."/".$mail_protocol."/}".$mailbox, $login_username, $secretkey) or die("Connection to server failed ".imap_last_error());



$email = new Webmail($mbox, $_REQUEST["mailid"]);

if(isset($_REQUEST["email_body"]))
	$msgData = $_REQUEST["email_body"];
else {
	$email->loadMail();
	$msgData = $email->body;
	$subject = $email->subject;
	$imported=true;
}

$focus->column_fields['subject']=$subject;
$focus->column_fields["activitytype"]="Emails";

$ddate = date("Y-m-d");
$dtime = date("h:m");
$focus->column_fields["assigned_user_id"]=$current_user->id;
$focus->column_fields["date_start"]=$ddate;
$focus->column_fields["time_start"]=$dtime;

//$tmpBody = preg_replace(array('/<br(.*?)>/i',"/&gt;/i","/&lt;/i","/&nbsp;/i","/&amp/i","/&copy;/i","/<style(.*?)>(.*?)<\/style>/i","/\{(.*?)\}/i","/BODY/i"),array("\r",">","<"," ","&","(c)","","",""),$msgData);
//$focus->column_fields["description"]=strip_tags($tmpBody);
$focus->column_fields["description"]=$msgData;


//to save the email details in emaildetails tables
$fieldid = $adb->query_result($adb->query('select fieldid from field where tablename="contactdetails" and fieldname="email" and columnname="email"'),0,'fieldid');
if($email->relationship != 0)
{
	$focus->column_fields['parent_id']=$email->relationship["id"].'@'.$fieldid.'|';

	if($email->relationship["type"] == "Contacts")
		add_attachment_to_contact($email->relationship["id"],$email);
}else
{
	//if relationship is not available create a contact and relate the email to the contact
	require_once('modules/Contacts/Contact.php');
	$contact_focus = new Contact();	
	$contact_focus->column_fields['lastname'] =$email->fromname; 
	$contact_focus->column_fields['email'] = $email->from;
	$contact_focus->save("Contacts");
	$focus->column_fields['parent_id']=$contact_focus->id.'@'.$fieldid.'|';

	add_attachment_to_contact($contact_focus->id,$email);
}

function add_attachment_to_contact($cid,$email) {
	// add attachments to contact
	global $adb,$current_user;
	
	$attachments=$email->downloadAttachments();
	$upload_filepath = decideFilePath();
	for($i=0,$num_files=count($attachments);$i<$num_files;$i++) {
		$current_id = $adb->getUniqueID("crmentity");
		$date_var = date('YmdHis');

		$filename = ereg_replace("[ ()-]+", "_",$attachments[$i]["filename"]);
        	$filetype= substr($filename,strstr($filename,"."),strlen($filename));
		$filesize = $attachments[$i]["filesize"];

                $query = "insert into crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime) values('";
                $query .= $current_id."','".$current_user->id."','".$current_user->id."','Contacts Attachment','Uploaded from webmail during qualification','".$date_var."')";
                $result = $adb->query($query);

                $sql = "insert into attachments values(";
                $sql .= $current_id.",'".$filename."','Uploaded ".$filename." from webmail','".$filetype."','".$upload_filepath."')";
		echo $query;
                $result = $adb->query($sql);

                $sql1 = "insert into seattachmentsrel values('";
                $sql1 .= $cid."','".$current_id."')";
                $result = $adb->query($sql1);

		$fp = fopen($upload_filepath.'/'.$filename, "w") or die("Can't open file");
		fputs($fp, base64_decode($attachments[$i]["filedata"]));
		fclose($fp);
	}
}

$_REQUEST['parent_id'] = $focus->column_fields['parent_id'];
$focus->save("Emails");

//saving in emaildetails table
$id_lists = $focus->column_fields['parent_id'].'@'.$fieldid;
$all_to_ids = $email->from;
$query = 'insert into emaildetails values ('.$focus->id.',"","'.$all_to_ids.'","","","","'.$id_lists.'","WEBMAIL")';
$adb->query($query);

$return_id = $_REQUEST["mailid"];
$return_module='Webmails';
$return_action='ListView';


if(isset($_REQUEST["send_mail"]) && $_REQUEST["send_mail"] == "true") {
	require_once("sendmail.php");
	global $adb;
	$sql = "select email1, first_name,last_name from users where id='".$current_user->id."'";
	$res = $adb->query($sql);
	$emailaddr = $adb->query_result($res,0,'email1');
	$who = $adb->query_result($res,0,'first_name')." ".$adb->query_result($res,0,'last_name');
	sendmail($to_address,$cc_address,$bcc_address,$emailaddr,$who,$subject,$msgData);
	header("Location: index.php?action=$return_action&module=$return_module");
} else {
	if($_POST["ajax"] != "true")
		header("Location: index.php?action=$return_action&module=$return_module&record=$return_id"); 
}

return;
?>
