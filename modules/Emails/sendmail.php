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

require_once('modules/Emails/Emails.php');
require_once('include/logging.php');
require("modules/Emails/class.phpmailer.php");
require_once('include/database/PearDatabase.php');

$local_log =& LoggerManager::getLogger('index');

echo get_module_title("Emails", $mod_strings['LBL_MODULE_NAME'], true); 

sendmail($_REQUEST['assigned_user_id'],$current_user->user_name,$_REQUEST['name'],$_REQUEST['description'],$mail_server,$mail_server_username,$mail_server_password);

function sendmail($to,$from,$subject,$contents,$mail_server,$mail_server_username,$mail_server_password)
{
	global $adb,$root_directory,$mod_strings, $log;

	$sql = $_REQUEST['query'];
	$result= $adb->query($sql);
	
	$noofrows = $adb->num_rows($result);

	$dbQuery = 'select vtiger_attachments.*, vtiger_activity.subject, vtiger_crmentity.description  from vtiger_activity inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_activity.activityid left join vtiger_seattachmentsrel on vtiger_seattachmentsrel.crmid = vtiger_activity.activityid left join vtiger_attachments on vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid where vtiger_crmentity.crmid = '.$adb->quote($_REQUEST['return_id']);

        $result1 = $adb->query($dbQuery) or die("Couldn't get file list");
	$temparray = $adb->fetch_array($result1);

	$notequery = 'select  vtiger_attachments.*, vtiger_notes.notesid, vtiger_notes.filename,vtiger_notes.notecontent  from vtiger_notes inner join vtiger_senotesrel on vtiger_senotesrel.notesid= vtiger_notes.notesid inner join vtiger_crmentity on vtiger_crmentity.crmid= vtiger_senotesrel.crmid left join vtiger_seattachmentsrel  on vtiger_seattachmentsrel.crmid =vtiger_notes.notesid left join vtiger_attachments on vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid where vtiger_crmentity.crmid='.$adb->quote($_REQUEST['return_id']);
	$result2 = $adb->query($notequery) or die("Couldn't get file list");

	$mail = new PHPMailer();
	
        $mail->Subject =$adb->query_result($result1,0,"subject");

	$DESCRIPTION = $adb->query_result($result1,0,"description");
	$DESCRIPTION .= '<br><br>\n';
	$DESCRIPTION .= nl2br($adb->query_result($adb->query("select * from vtiger_users where user_name=".$adb->quote($from)),0,"signature"));

	$mail->IsHTML(true);
        $mail->Body    = nl2br($DESCRIPTION);
	$mail->AltBody = strip_tags(preg_replace(array("/<p>/i","/<br>/i","/<br \/>/i"),array("\n","\n","\n"),$DESCRIPTION));
	$initialfrom = $from;
	$mail->IsSMTP();

	if($mail_server=='')
	{
	        $mailserverresult = $adb->query("select * from vtiger_systems where server_type = 'email'");
        	$mail_server = $adb->query_result($mailserverresult,0,'server');
		$mail_server_username = $adb->query_result($mailserverresult,0,'server_username');
                $mail_server_password = $adb->query_result($mailserverresult,0,'server_password');
                $smtp_auth = $adb->query_result($mailserverresult,0,'smtp_auth');
		$_REQUEST['server']=$mail_server;

		$log->info("Mail Server Details => '".$mail_server."','".$mail_server_username."','".$mail_server_password."'");
	}
	$mail->Host = $mail_server;
	$mail->SMTPAuth = $smtp_auth;
	$mail->Username = $mail_server_username;
	$mail->Password = $mail_server_password;
	//$mail->From = $adb->query_result($adb->query("select * from vtiger_users where user_name='".$from."'"),0,"email1");

	$mail->From = $adb->query_result($adb->query("select * from vtiger_users where user_name=".$adb->quote($from)),0,"email1");
	$mail->FromName = $initialfrom;
	$mail->AddReplyTo($from);
	$mail->WordWrap = 50;
	$log->info("From name & id are set in mail object => '".$mail->FromName."<".$mail->From.">' ");

	//store this to the hard disk and give that url
	for($i=0;$i< $adb->num_rows($result1);$i++)
	{
		$fileContent = $adb->query_result($result1,$i,"attachmentcontents");
		$filename=$adb->query_result($result1,$i,"name");
		$filesize=$adb->query_result($result1,$i,"attachmentsize");

		if(!@$handle = fopen($root_directory."/test/upload/".$filename,"wb")){}

		if(!@fwrite($handle,base64_decode($fileContent),$filesize)){}
		if(!@fclose($handle)){}

		$mail->AddAttachment($root_directory."/test/upload/".$filename);//temparray['filename']) // add vtiger_attachments
		$log->info("File '".$filename."' is attached with the mail.");
	}

	for($i=0;$i< $adb->num_rows($result2);$i++)
	{
		$fileContent = $adb->query_result($result2,$i,"attachmentcontents");
		$filename=$adb->query_result($result2,$i,"name");
		$filesize=$adb->query_result($result2,$i,"attachmentsize");

		if(!@$handle = fopen($root_directory."/test/upload/".$filename,"wb")){}

		if(!@fwrite($handle,base64_decode($fileContent),$filesize)){}
		if(!@fclose($handle)){}

		$mail->AddAttachment($root_directory."/test/upload/".$filename);//temparray['filename']) // add vtiger_attachments
		$log->info("File '".$filename."' is attached with the mail.");
	}

	echo '<table>';

	$count = 1;
	for($i=0;$i< $noofrows;$i++)
	{
		$mail->ClearAddresses();
		if($_REQUEST['mailto'] == 'users')
		{
			$to=$adb->query_result($result,$i,"email1");
			if($to == '')
				$to=$adb->query_result($result,$i,"email2");
			if($to == '')
				$to=$adb->query_result($result,$i,"yahoo_id");
		}
		else
			$to=$adb->query_result($result,$i,"email");

		$mail->AddAddress($to);
		$log->info("To email address is added in the mail object => '".$to."'");
		$emailoptout = $adb->query_result($result,$i,"emailoptout");
		if($emailoptout == 1 && $to != '')
		{
			$mail->ClearAddresses();
			$mail->AddAddress("");
			$emailoptout_error = true;
			$log->info("Email opt out (contact) value is set. So To email id address cleared(empty).");
		}

		$j=$i+1;


		if($mail->Send())
		{
			$flag = true;
			if($flag && $count == 1 && $noofrows >= 1)
			{
				$count = 0;
				if($_REQUEST['mailto'] == 'users')
					echo '<tr><b><h3>'.$mod_strings['MESSAGE_MAIL_HAS_SENT_TO_USERS'].' </h3></b></tr>';
				else
					echo '<tr><b><h3>'.$mod_strings['MESSAGE_MAIL_HAS_SENT_TO_CONTACTS'].' </h3></b></tr>';
			}
			echo '<center><tr align="left"><b><h3>'.$j.' . '.$to.'</h3></b></tr></center>';
			$log->info("Mail has been sent from vtiger system. Status => '".$mail->ErrorInfo."'");
		}
		else
		{
			$log->info("Error block : Mail sending process failed. Status => '".$mail->ErrorInfo."'");
			$message = substr($mail->ErrorInfo,0,49);
			$flag = false;
			if($message=='Language string failed to load: connect_host')
			{
				if($i == 0)
					echo "<br><b><h3>".$mod_strings['MESSAGE_CHECK_MAIL_SERVER_NAME']."</b></h3>";
			}
			elseif( $count == 1 && $noofrows >= 1)
			{
				$count = 0;
				if($_REQUEST['mailto'] == 'users')
					echo '<tr><b><h3>'.$mod_strings['MESSAGE_MAIL_HAS_SENT_TO_USERS'].' </h3></b></tr>';
				else
					echo '<tr><b><h3>'.$mod_strings['MESSAGE_MAIL_HAS_SENT_TO_CONTACTS'].' </h3></b></tr>';
			}
			if($message=='Language string failed to load: recipients_failed')
			{
				if($emailoptout_error == true)
				{
					$ERROR_MESSAGE = $mod_strings['MESSAGE_CONTACT_NOT_WANT_MAIL'];
					$flag = true;
				}
				else
					$ERROR_MESSAGE = $mod_strings['MESSAGE_MAIL_ID_IS_INCORRECT'];

				echo '<center><tr align="left"><font color="purple"<b><h3>'.$j.' . '.$ERROR_MESSAGE.' </h3></font></b></tr></center>';
			}
		}
	}

	if($i==0)
		echo '<br><td align="left"><font color="red"><b><center><h3>'.$mod_strings['MESSAGE_ADD_USER_OR_CONTACT'].'</h3></b></font>';
	if($i>1 && $flag)
		echo "<br><br><B><center><h3>".$mod_strings['MESSAGE_MAIL_SENT_SUCCESSFULLY']." </h3></B>";
	echo '</table>';

}

?>
