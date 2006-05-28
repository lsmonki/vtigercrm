<?php
require_once('include/utils/UserInfoUtil.php');
require_once("modules/Webmails/Webmail.php");
require_once("modules/Webmails/MailParse.php");

if(isset($_REQUEST["mailbox"]) && $_REQUEST["mailbox"] != "") { $mailbox=$_REQUEST["mailbox"];} else { $mailbox = "INBOX";}
if(isset($_REQUEST["mailid"]) && $_REQUEST["mailid"] != "") { $mailid=$_REQUEST["mailid"];} else { echo "ERROR";flush();exit();}

$mailInfo = getMailServerInfo($current_user);
$temprow = $adb->fetch_array($mailInfo);

global $mbox;
$mbox = getImapMbox($mailbox,$temprow);

$webmail = new Webmail($mbox,$mailid);
$webmail->loadMail();


echo '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>';

echo '<td style="background-color:#CCCC99">';

echo '<table border="1" width="100%" cellpadding="0" cellspacing="0">';
echo '<tr><td width="10%">From:</td><td>'.$webmail->fromname.'</td></tr>';
echo '<tr><td width="10%">To:</td><td>'.implode(" ",$webmail->to).'</td></tr>';
echo '</table>';

echo '</td></tr>';

echo '<tr><td align="center"><iframe src="index.php?module=Webmails&action=body&mailid='.$mailid.'&mailbox='.$mailbox.'" width="100%" height="450" frameborder="0">No Iframes supported</iframe></td></tr>';

echo "<tr><td><p style='font-weight:bold'>Email Attachments:</p></td></tr>";
foreach($webmail->attachments as $key=>$value) {
	echo '<tr><td>'.($key+1).') <a href="">'.$value["filename"]."</a></td></tr>";
}
echo '</table>';
?>
