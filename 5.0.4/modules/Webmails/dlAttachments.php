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


include('config.php');
require_once('include/utils/UserInfoUtil.php');
require_once('include/utils/utils.php');
require_once('modules/Webmails/Webmails.php');
require_once('modules/Webmails/MailBox.php');

global $MailBox;
$MailBox = new MailBox($_REQUEST["mailbox"]);

$mailid=$_REQUEST["mailid"];
$num=$_REQUEST["num"];

$email = new Webmails($MailBox->mbox,$mailid);
$attach_tab = Array();
$email->loadMail($attach_tab);
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$email->charsets."\">\n";
echo '<script src="modules/Webmails/Webmails.js" type="text/javascript"></script>';
echo "<table class='small' width='100%' cellspacing='1' cellpadding='0' border='0' style='font-size:18px'>";
if(count($email->attname) <= 0)
	echo "<tr align='center'><td nowrap>No files to download</td></tr>";
else{
	for($i=0;$i<count($email->attname);$i++){
        	$attachment_links .= "&nbsp;&nbsp;&nbsp;&nbsp;".$email->anchor_arr[$i].$email->attname[$i]."</a></br>";
	}
	echo "<tr><td><table class='small' width='100%' cellspacing='1' cellpadding='0' border='0' style='font-size:13px'><tr><td width='90%'>There are ".count($email->attname)." attachment(s) to choose from:</td></tr>";
	echo "<tr><td width='100%'>".$attachment_links."</div></td></tr>";
	echo "</td></tr></table>";
}

echo "</table>";
//$attachments=$email->downloadAttachments();
//$inline=$email->downloadInlineAttachments();
/*
if($num == "" || !isset($num) && count($attachments) >0 )
{
	echo "<table class='small' width='100%' cellspacing='1' cellpadding='0' border='0'><tr><td width='10%'>&nbsp;</td><td width='90%'>There are ".count($attachments)." attachment(s) to choose from:</td></tr>";

	for($i=0;$i<count($attachments);$i++)
	{
		echo "<tr><td width='10%'>&nbsp;</td><td width='90%'>&nbsp;&nbsp;&nbsp;".($i+1).") &nbsp; <a href='index.php?module=Webmails&action=dlAttachments&mailid=".$mailid."&num=".$i."&mailbox=".$_REQUEST["mailbox"]."'>".$attachments[$i]["filename"]."</td></tr>";
	}

	echo "</table><br>";
	echo "<table class='small' width='100%' cellspacing='1' cellpadding='0' border='0'><tr><td width='10%'>&nbsp;</td><td width='90%'>There are ".count($inline)." <b>inline</b> attachment(s) to choose from:</td></tr>";

	for($i=0;$i<count($inline);$i++)
	{
		echo "<tr><td width='10%'>&nbsp;</td><td width='90%'>&nbsp;&nbsp;&nbsp;".($i+1).") &nbsp; <a href='index.php?module=Webmails&action=dlAttachments&mailid=".$mailid."&num=".$i."&inline=true&mailbox=".$_REQUEST["mailbox"]."'>".$inline[$i]["filename"]."</td></tr>";
	}

	echo "</table><br><br>";

}
elseif (count($attachments) == 0 && count($inline) == 0)
{
	echo "<center><strong>No vtiger_attachments for this email</strong></center><br><br>";
}
else
{

	global $root_directory;
	$save_path=$root_directory.'/modules/Webmails/tmp';
	if(!is_dir($save_path))
		mkdir($save_path);

	$user_dir=$save_path."/".$_SESSION["authenticated_user_id"];
	if(!is_dir($user_dir))
		mkdir($user_dir);

	if(isset($_REQUEST["inline"]) && $_REQUEST["inline"] == "true")
	{
		$fp = fopen($user_dir.'/'.$inline[$num]["filename"], "w") or die("Can't open file");
		fputs($fp, base64_decode($inline[$num]["filedata"]));
		$filename = 'modules/Webmails/tmp/'.$_SESSION['authenticated_user_id'].'/'.$inline[$num]['filename'];
	}
	else
	{
		$fp = fopen($user_dir.'/'.$attachments[$num]["filename"], "w") or die("Can't open file");
		fputs($fp, base64_decode($attachments[$num]["filedata"]));
		$filename = 'modules/Webmails/tmp/'.$_SESSION['authenticated_user_id'].'/'.$attachments[$num]['filename'];
	}
	fclose($fp);
	imap_close($MailBox->mbox);

	?>
	<center><h2>File Download</h2></center>
	<META HTTP-EQUIV="Refresh"
	CONTENT="0; URL=<?php echo $filename;?>"
	]"
	/*<?php
}*/


?>
