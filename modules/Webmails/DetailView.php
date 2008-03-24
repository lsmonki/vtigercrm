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
require_once("modules/Webmails/Webmails.php");
require_once("modules/Webmails/MailBox.php");

global $app_strings;
global $mod_strings;

if(isset($_REQUEST["mailbox"]) && $_REQUEST["mailbox"] != "") { $mailbox=$_REQUEST["mailbox"];} else { $mailbox = "INBOX";}
if(isset($_REQUEST["mailid"]) && $_REQUEST["mailid"] != "") { $mailid=$_REQUEST["mailid"];} else { echo "ERROR";flush();exit();}

global $MailBox;
$MailBox = new MailBox($mailbox);

$webmail = new Webmails($MailBox->mbox,$mailid);
$elist = $MailBox->mailList["overview"][($mailid-1)];

echo '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="previewWindow"><tr>';

echo '<td>';

/*echo '<table border="0" width="100%" cellpadding="0" cellspacing="0">';
echo '<tr><td width="10%">'.$mod_strings['LBL_FROM'].'</td><td>'.$elist->from.'</td></tr>';
echo '<tr><td width="10%">'.$mod_strings['LBL_TO'].'</td><td>'.$elist->to.'</td></tr>';

//Added to get the UTF-8 string - 30-11-06 - Mickie
$elist->subject = utf8_decode(imap_utf8($elist->subject));

echo '<tr><td width="10%">'.$mod_strings['LBL_SUBJECT'].'</td><td>'.$elist->subject.'</td></tr>';
echo '<tr><td width="10%">'.$mod_strings['LBL_DATE'].'</td><td>'.$elist->date.'</td></tr>';
echo '</table>';*/

echo '</td></tr>';
$array_tab = Array();
$webmail->loadMail($array_tab);

echo '<tr><td align="center"><iframe src="index.php?module=Webmails&action=body&fullview=true&mailid='.$mailid.'&mailbox='.$mailbox.'" width="100%" height="600" frameborder="0" style="border:1px solid gray">'.$mod_strings['LBL_NO_IFRAMES_SUPPORTED'].'</iframe></td></tr>';
/*if($webmail->has_attachments)
{
	//check for attachments
	echo "<tr><td><p style='font-weight:bold'>".$mod_strings['LBL_EMAIL_ATTACHMENTS']."</p></td></tr>";
	echo "<tr><td>".$webmail->att_links."</td></tr>";
}
/*foreach($webmail->attachments as $key=>$value) {
	echo '<tr><td>'.($key+1).') <a href="index.php?module=Webmails&action=dlAttachments&num='.$key.'&mailid='.$mailid.'&mailbox='.$mailbox.'" target="_blank">'.$value["filename"]."</a></td></tr>";
}*/
echo '</table>';
?>
