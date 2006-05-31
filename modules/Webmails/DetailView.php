<?php
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
require_once('include/utils/UserInfoUtil.php');
require_once("modules/Webmails/Webmail.php");
require_once("modules/Webmails/MailParse.php");

global $app_strings;
global $mod_strings;

if(isset($_REQUEST["mailbox"]) && $_REQUEST["mailbox"] != "") { $mailbox=$_REQUEST["mailbox"];} else { $mailbox = "INBOX";}
if(isset($_REQUEST["mailid"]) && $_REQUEST["mailid"] != "") { $mailid=$_REQUEST["mailid"];} else { echo "ERROR";flush();exit();}

$mailInfo = getMailServerInfo($current_user);
$temprow = $adb->fetch_array($mailInfo);

global $mbox;
$mbox = getImapMbox($mailbox,$temprow);

$webmail = new Webmail($mbox,$mailid);
$webmail->loadMail();


echo '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="previewWindow"><tr>';

echo '<td>';

echo '<table border="0" width="100%" cellpadding="0" cellspacing="0">';
echo '<tr><td width="10%">From:</td><td>'.$webmail->fromname.'</td></tr>';
echo '<tr><td width="10%">To:</td><td>'.implode(" ",$webmail->to).'</td></tr>';
echo '<tr><td width="10%">Subject:</td><td>'.$webmail->subject.'</td></tr>';
echo '<tr><td width="10%">Date:</td><td>'.$webmail->date.'</td></tr>';
echo '</table>';

echo '</td></tr>';

echo '<tr><td align="center"><iframe src="index.php?module=Webmails&action=body&mailid='.$mailid.'&mailbox='.$mailbox.'" width="100%" height="450" frameborder="0" style="border:1px solid gray">No Iframes supported</iframe></td></tr>';

echo "<tr><td><p style='font-weight:bold'>Email Attachments:</p></td></tr>";
foreach($webmail->attachments as $key=>$value) {
	echo '<tr><td>'.($key+1).') <a href="index.php?module=Webmails&action=dlAttachments&num='.$key.'&mailid='.$mailid.'" target="_blank">'.$value["filename"]."</a></td></tr>";
}
echo '</table>';
?>
