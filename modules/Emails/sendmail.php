<?php
////////////////////////////////////////////////////
// PHPMailer - PHP email class
//
// Class for sending email using either
// sendmail, PHP mail(), or SMTP.  Methods are
// based upon the standard AspEmail(tm) classes.
//
// Copyright (C) 2001 - 2003  Brent R. Matzelle
//
// License: LGPL, see LICENSE
////////////////////////////////////////////////////

/**
 * PHPMailer - PHP email transport class
 * @package PHPMailer
 * @author Brent R. Matzelle
 * @copyright 2001 - 2003 Brent R. Matzelle
 */

require_once('modules/Emails/Email.php');
require_once('include/logging.php');
require("class.phpmailer.php");
require_once('include/database/PearDatabase.php');

$local_log =& LoggerManager::getLogger('index');

echo get_module_title("Emails", $mod_strings['LBL_MODULE_NAME'], true); 

sendmail($_REQUEST['assigned_user_id'],$current_user->user_name,$_REQUEST['name'],$_REQUEST['description'],$mail_server,$mail_server_username,$mail_server_password);

function sendmail($to,$from,$subject,$contents,$mail_server,$mail_server_username,$mail_server_password)
{
global $adb,$root_directory;

	$sql = $_REQUEST['query'];
	$result= $adb->query($sql);
	
	$noofrows = $adb->num_rows($result);

	$dbQuery = 'select attachments.*, activity.subject, emails.description  from emails inner join crmentity on crmentity.crmid = emails.emailid inner join activity on activity.activityid = crmentity.crmid left join seattachmentsrel on seattachmentsrel.crmid = emails.emailid left join attachments on seattachmentsrel.attachmentsid = attachments.attachmentsid where crmentity.crmid = '.$_REQUEST['return_id'];

        $result1 = $adb->query($dbQuery) or die("Couldn't get file list");
	$temparray = $adb->fetch_array($result1);

	$notequery = 'select  attachments.*, notes.notesid, notes.filename,notes.notecontent  from notes inner join senotesrel on senotesrel.notesid= notes.notesid inner join crmentity on crmentity.crmid= senotesrel.crmid left join seattachmentsrel  on seattachmentsrel.crmid =notes.notesid left join attachments on seattachmentsrel.attachmentsid = attachments.attachmentsid where crmentity.crmid='.$_REQUEST['return_id'];
	$result2 = $adb->query($notequery) or die("Couldn't get file list");

	        $mail = new PHPMailer();
	
                $mail->Subject =$adb->query_result($result1,0,"subject");
                $mail->Body    =$adb->query_result($result1,0,"description");
		$initialfrom = $from;
		$mail->IsSMTP();

if($mail_server=='')
{
        $mailserverresult=$adb->query("select * from systems");
        $mail_server=$adb->query_result($mailserverresult,0,'mail_server');
	$_REQUEST['mail_server']=$mail_server;
}
		$mail->Host = $mail_server;
		$mail->SMTPAuth = true;
		$mail->Username = "";
		$mail->Password = "";
		$mail->From = $adb->query_result($adb->query("select * from users where user_name='".$from."'"),0,"email1");
		$mail->FromName = $initialfrom;
//		$mail->AddAddress($to);
		$mail->AddReplyTo($from);
		$mail->WordWrap = 50;

//store this to the hard disk and give that url

for($i=0;$i< $adb->num_rows($result1);$i++)
{
	$fileContent = $adb->query_result($result1,$i,"attachmentcontents");
	$filename=$adb->query_result($result1,$i,"name");
	$filesize=$adb->query_result($result1,$i,"attachmentsize");

	if(!@$handle = fopen($root_directory."/test/upload/".$filename,"wb")){}

	//chmod("/home/rajeshkannan/test/".$fileContent,0755);
	if(!@fwrite($handle,base64_decode($fileContent),$filesize)){}
	if(!@fclose($handle)){}

	//select 
	$mail->AddAttachment($root_directory."/test/upload/".$filename);//temparray['filename']) // add attachments

//	$mail->IsHTML(true);
//	$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
}

for($i=0;$i< $adb->num_rows($result2);$i++)
{
        $fileContent = $adb->query_result($result2,$i,"attachmentcontents");
        $filename=$adb->query_result($result2,$i,"name");
        $filesize=$adb->query_result($result2,$i,"attachmentsize");

        if(!@$handle = fopen($root_directory."/test/upload/".$filename,"wb")){}

        //chmod("/home/rajeshkannan/test/".$fileContent,0755);
        if(!@fwrite($handle,base64_decode($fileContent),$filesize)){}
        if(!@fclose($handle)){}

        //select
        $mail->AddAttachment($root_directory."/test/upload/".$filename);//temparray['filename']) // add attachments

//        $mail->IsHTML(true);
//        $mail->AltBody = "This is the body in plain text for non-HTML mail clients";
}
$mail->IsHTML(true);
$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
echo '<table>';
for($i=0;$i< $noofrows;$i++)
{
	$mail->ClearAddresses();
	$to=$adb->query_result($result,$i,"email");
	$mail->AddAddress($to);
	$j=$i+1;

	if($mail->Send())
	{
	        if($i==0)
			echo '<tr><b><h3>Mail has been sent to the following User(s) and Contact(s) : </h3></b></tr>';
                        echo '<center><tr align="left"><b><h3>'.$j.' . '.$to.'</h3></b></tr></center>';
	}
	else
	{
		if($mail->ErrorInfo=='Language string failed to load: connect_host')
		{
			echo "<br><b><h3> Please Check the Mail Server Name...</b></h3>";
		}
		elseif($mail->ErrorInfo=='Language string failed to load: recipients_failed')
		{
			if($to=='')
		                echo '<center><tr align="left"><font color="red"><b><h3>'.$j.' . Mail Id is incorrect. Please Check this Mail Id... </h3></b></font></tr></center>';
		}
	}
}
if($i==0) echo '<br><td align="left"><font color="red"><b><center><h3>Please Add any User(s) or Contact(s)...</h3></b></font>';
if($i>1)echo "<br><br><B><center><h3> Mail(s) sent successfully! </h3></B>";
echo '</table>';
}
?>
