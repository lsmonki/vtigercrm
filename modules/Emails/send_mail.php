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


send_mail('users',$_REQUEST['assigned_user_id'],$current_user->user_name,$_REQUEST['name'],$_REQUEST['description'],$mail_server,$mail_server_username,$mail_server_password,$filename);

function send_mail($srcmodule,$to,$from,$subject,$contents,$mail_server,$mail_server_username,$mail_server_password,$filename)
{
	global $adb;
	global $root_directory;

	$uploaddir = $root_directory ."/test/upload/" ;// set this to wherever


	$sql="select email1 from ". $srcmodule ." where id='" .$to ."'" ;
        $result = $adb->query($sql);

	$mail = new PHPMailer();

	if(!@$to = $adb->query_result($result,0,"email1"))
	{
		header("Location: index.php?action=ListView&module=Emails&parent_id=$parent_id&record=$return_id");
	}

	$mail->Subject = $subject;
	$mail->Body    = $contents;//"This is the HTML message body <b>in bold!</b>";

	$initialfrom = $from;

	$sql="select email1 from users where user_name='" .$from ."'" ;

        $result = $adb->query($sql);
        $from = $adb->query_result($result,0,"email1");

	$mail->IsSMTP();                                      // set mailer to use SMTP
	//$mail->Host = "smtp1.example.com;smtp2.example.com";  // specify main and backup server
if($mail_server=='')
{
	$mailserverresult=$adb->query("select * from systems");
	$mail_server=$adb->query_result($mailserverresult,0,'mail_server');
	$_REQUEST['mail_server']=$mail_server;
}	
	$mail->Host = $mail_server;  // specify main and backup server
	$mail->SMTPAuth = true;     // turn on SMTP authentication
	$mail->Username = $mail_server_username ;//$smtp_username;  // SMTP username
	$mail->Password = $mail_server_password ;//$smtp_password; // SMTP password
	$mail->From = $from;
	$mail->FromName = $initialfrom;
	$mail->AddAddress($to);                  // name is optional
	$mail->AddReplyTo($from);
	$mail->WordWrap = 50;                                 // set word wrap to 50 characters

	$dbQuery = 'SELECT emails.*, attachments.*, seattachmentsrel.* from emails left join seattachmentsrel on seattachmentsrel.crmid=emails.emailid left join attachments on seattachmentsrel.attachmentsid=attachments.attachmentsid where emails.emailid = '.$_REQUEST['return_id'];

        $result1 = $adb->query($dbQuery) or die("Couldn't get file list");
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
	//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
	//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
	$mail->IsHTML(true);                                  // set email format to HTML
	
	$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

	if(!$mail->Send()) 
	{
	  // echo "Message could not be sent. <p>";
	  // echo "Mailer Error: " . $mail->ErrorInfo;
	   //exit;
	   $msg =$mail->ErrorInfo;
           header("Location: index.php?action=EditView&module=Emails&parent_id=$parent_id&record=".$_REQUEST['return_id']."&filename=$filename&message=$msg");
	}
	else
   	   header("Location: index.php?action=index&module=Emails&parent_id=$parent_id&record=$return_id&filename=$filename");

}
?>
