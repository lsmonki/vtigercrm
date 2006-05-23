<?php
include('config.php');
require_once('include/utils/UserInfoUtil.php');
require_once('include/utils/utils.php');
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

if($ssltype == "") {$ssltype = "notls";}
if($sslmeth == "") {$sslmeth = "novalidate-cert";}
$mbox = @imap_open("{".$imapServerAddress."/".$mail_protocol."/".$ssltype."/".$sslmeth."}".$mailbox, $login_username, $secretkey) or die("Connection to server failed ".imap_last_error());


$mailid=$_REQUEST["mailid"];
$num=$_REQUEST["num"];

$email = new Webmail($mbox,$mailid);
$attachments=$email->downloadAttachments();

if($num == "" || !isset($num) && count($attachments) >0 ) {
	echo "<table width='100%' cellspacing='1' cellpadding='0' border='0'><tr><td align='center'>There are ".count($attachments)." attachment(s) to choose from:</td></tr>";
	for($i=0;$i<count($attachments);$i++) {
		echo "<tr><td align='center'>".count($attachments).") &nbsp; <a href='index.php?module=Webmails&action=dlAttachments&mailid=".$mailid."&num=".$i."'>".$attachments[$i]["filename"]."</td></tr>";
	}
	echo "</table><br><br>";

} elseif (count(attachments) == 0) {
	echo "<center><strong>No attachments for this email</strong></center><br><br>";
} else {

global $root_directory;
$save_path=$root_directory.'/modules/Webmails/tmp';
$user_dir=$save_path."/".$_SESSION["authenticated_user_id"];
if(!is_dir($user_dir))
	mkdir($user_dir);

$fp = fopen($user_dir.'/'.$attachments[$num]["filename"], "w") or die("Can't open file");
fputs($fp, base64_decode($attachments[$num]["filedata"]));
fclose($fp);
imap_close($mbox);

$filename = 'modules/Webmails/tmp/'.$_SESSION['authenticated_user_id'].'/'.$attachments[$num]['filename'];
?>
<center><h2>File Download</h2></center>
<META HTTP-EQUIV="Refresh"
CONTENT="0; URL=<?php echo $filename;?>"
]"
<?
}
?>
