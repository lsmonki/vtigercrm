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


//file modified by shankar

require("class.phpmailer.php");
//require('include/database/PearDatabase.php');


if($_REQUEST['return_module'] != 'Activities')
send_mail('users',$_REQUEST['assigned_user_id'],$current_user->user_name,$_REQUEST['name'],$_REQUEST['description'],$mail_server,$mail_server_username,$mail_server_password,$filename);

function send_mail($srcmodule,$to,$from,$subject,$contents,$mail_server,$mail_server_username,$mail_server_password,$filename)
{
	global $vtlog;
	global $adb;
	global $root_directory;

	$uploaddir = $root_directory ."/test/upload/" ;// set this to wherever


	$sql="select email1 from ". $srcmodule ." where id='" .$to ."'" ;
	$vtlog->logthis("Email for assigned_user_id is selected.",'debug');
        $result = $adb->query($sql);

	$mail = new PHPMailer();

	if(!@$to = $adb->query_result($result,0,"email1"))
	{
	//	header("Location: index.php?action=ListView&module=".$_REQUEST['return_module']."&parent_id=$parent_id&record=$return_id");
	}

	if(!@$sign = $adb->query_result($adb->query("select * from users where user_name='".$from."'"),0,"signature")){}
        $contents .= '<br><br><font color=darkgrey>'.$sign.'</font>';

	$mail->Subject = $subject;
	$mail->Body    = nl2br($contents);//"This is the HTML message body <b>in bold!</b>";

	$initialfrom = $from;

	$sql="select email1 from users where user_name='" .$from ."'" ;

        $result = $adb->query($sql);
        $from = $adb->query_result($result,0,"email1");
	$vtlog->logthis("From Email is selected.",'debug');

	$mail->IsSMTP();                                      // set mailer to use SMTP
	//$mail->Host = "smtp1.example.com;smtp2.example.com";  // specify main and backup server

	if($mail_server=='')
	{
		$mailserverresult=$adb->query("select * from systems where server_type='email'");
		$mail_server=$adb->query_result($mailserverresult,0,'server');
		$_REQUEST['server']=$mail_server;
	}	

	$mail->Host = $mail_server;  // specify main and backup server
	$mail->SMTPAuth = true;     // turn on SMTP authentication
	$mail->Username = $mail_server_username ;//$smtp_username;  // SMTP username
	$mail->Password = $mail_server_password ;//$smtp_password; // SMTP password
	if($_REQUEST['return_module'] == 'HelpDesk')
	{
		$mail->From = 'support@vtiger.com';
		$mail->FromName = 'Vtiger Support';
	}
	else
	{
		$mail->From = $from;
		$mail->FromName = $initialfrom;
	}

	$mail->AddAddress($to);                  // name is optional
        if($_REQUEST['ccmail'] != '')
        {
		$ccmail = explode(",",$_REQUEST['ccmail']);
		for($i=0;$i<count($ccmail);$i++)
		{
	                $mail->AddCC($ccmail[$i]);
		}
        }

	$mail->AddReplyTo($from);
	$mail->WordWrap = 50;                                 // set word wrap to 50 characters

//	if($_REQUEST['return_module'] == 'Emails')
		$dbQuery = 'SELECT emails.*, attachments.*, seattachmentsrel.crmid from emails left join seattachmentsrel on seattachmentsrel.crmid=emails.emailid left join attachments on seattachmentsrel.attachmentsid=attachments.attachmentsid where emails.emailid = '.$_REQUEST['return_id'].' order by attachmentsid DESC';

        if(!@$result1 = $adb->query($dbQuery)){}// or die("Couldn't get file list");
if($result1 != '')
{
	$temparray = $adb->fetch_array($result1);
	//store this to the hard disk and give that url
	if($adb->num_rows($result1) != 0)
	{
		$fileContent = $temparray['attachmentcontents'];
		$filename=$temparray['name'];
		$filesize=$temparray['attachmentsize'];

		if(!@$handle = fopen($root_directory."/test/upload/".$filename,"wb")){}//temparray['filename'],"wb")
		//chmod("/home/rajeshkannan/test/".$fileContent,0755);
		if(!@fwrite($handle,base64_decode($fileContent),$filesize)){}
		if(!@fclose($handle)){}
	}

	$mail->AddAttachment($root_directory."/test/upload/".$filename);//temparray['filename']) //add attachments
	$vtlog->logthis("Attachment Files are Attached with the mail.",'debug');
}
	//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
	//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
	$mail->IsHTML(true);                                  // set email format to HTML
	
	$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

        if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != '')
                $returnmodule = $_REQUEST['return_module'];
        if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != '')
                $returnaction = $_REQUEST['return_action'];
        if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != '')
                $return_id = $_REQUEST['return_id'];

	$error_info = MailSend($mail);

	if($_REQUEST['return_module'] == 'Leads' || $_REQUEST['return_module'] == 'Contacts' || $_REQUEST['return_module'] == 'HelpDesk')
	{
		$mail->ClearAddresses();	
		$mailto = getParentMailId($_REQUEST['return_module'],$_REQUEST['parent_id']);
		if($mailto != '')
		{
			$mail->AddAddress($mailto);
			$vtlog->logthis("Parent(comes from Lead/Contact) Mail id is selected and added in to address.",'debug');
			$error_info = MailSend($mail);
		}
		$returnaction = 'DetailView';
		$return_id = $_REQUEST['record'];
	}

	if($_REQUEST['return_module'] == 'Emails')
	{
		if($_REQUEST['parent_id']!= '')
		{
			$mail->ClearAddresses();
	                $mailto = getParentMailId($_REQUEST['parent_type'],$_REQUEST['parent_id']);
			$vtlog->logthis("Parent(Lead/Contact) Mail id is selected and added in to address.",'debug');
	                if($mailto != '')
			{
        	                $mail->AddAddress($mailto);
				$error_info = MailSend($mail);
			}
		}
		$returnaction = 'index';
	}
	if($error_info != 1)
	{
		if($_REQUEST['return_module'] == 'Activities')
		{
			$_SESSION['mail_send_error'] = $error_info;
		}
		if($_REQUEST['return_module'] == 'Emails' || $_REQUEST['return_module'] == '')
		{
			$returnmodule = 'Emails';
			$returnaction = 'EditView';
		}
	}
	if($return_id == '' && $_REQUEST['return_id'] != '')
		$return_id = $_REQUEST['return_id'];

   	   header("Location: index.php?action=$returnaction&module=$returnmodule&parent_id=$parent_id&record=$return_id&filename=$filename&message=$error_info");

}

function MailSend($mail)
{
        if(!$mail->Send())
        {
		$msg =$mail->ErrorInfo;
		return $msg;
        }
	else 
		return true;
}

function getParentMailId($returnmodule,$parentid)
{
	global $adb;
        if($returnmodule == 'Leads')
        {
                $tablename = 'leaddetails';
                $idname = 'leadid';
        }
        if($returnmodule == 'Contacts' || $returnmodule == 'HelpDesk')
        {
		if($returnmodule == 'HelpDesk')
			$parentid = $_REQUEST['parent_id'];
                $tablename = 'contactdetails';
                $idname = 'contactid';
        }
	if($parentid != '')
	{
	        $query = 'select * from '.$tablename.' where '.$idname.' = '.$parentid;
	        $mailid = $adb->query_result($adb->query($query),0,'email');
	}
        if($mailid == '' && $returnmodule =='Contacts')
        {
                $mailid = $adb->query_result($adb->query($query),0,'otheremail');
                if($mailid == '')
                        $mailid = $adb->query_result($adb->query($query),0,'yahooid');
        }
	return $mailid;
}

?>
