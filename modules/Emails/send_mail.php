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
//require("Save.php");

//echo 'action : '.$_REQUEST['return_action'].'<br><br>User : '.$_REQUEST['assigned_user_id'].'<br> user : '.$current_user->user_name.'<br> name : '.$_REQUEST['name'].'<br>description : '.$_REQUEST['description'].'<br> server : '.$mail_server;

send_mail('users',$_REQUEST['assigned_user_id'],$current_user->user_name,$_REQUEST['name'],$_REQUEST['description'],$mail_server,$mail_server_username,$mail_server_password);

function send_mail($srcmodule,$to,$from,$subject,$contents,$mail_server,$mail_server_username,$mail_server_password)
//function send_mail($to,$from,$subject,$contents,$mail_server,$mail_server_username,$mail_server_password)
{
	$sql="select email1 from ". $srcmodule ." where id='" .$to ."'" ;
        $result = mysql_query($sql);

	$mail = new PHPMailer();
	$to = mysql_result($result,0,"email1");
	$mail->Subject = $subject;
	$mail->Body    = $contents;//"This is the HTML message body <b>in bold!</b>";

	$initialfrom = $from;

	$sql="select email1 from users where user_name='" .$from ."'" ;
        $result = mysql_query($sql);
        $from = mysql_result($result,0,"email1");

	$mail->IsSMTP();                                      // set mailer to use SMTP
	//$mail->Host = "smtp1.example.com;smtp2.example.com";  // specify main and backup server
	$mail->Host = $mail_server;  // specify main and backup server
	$mail->SMTPAuth = true;     // turn on SMTP authentication
	$mail->Username = $mail_server_username ;//$smtp_username;  // SMTP username
	$mail->Password = $mail_server_password ;//$smtp_password; // SMTP password
	$mail->From = $from;
	$mail->FromName = $initialfrom;
	$mail->AddAddress($to);                  // name is optional
	$mail->AddReplyTo($from);
	$mail->WordWrap = 50;                                 // set word wrap to 50 characters

	$dbQuery = 'SELECT emails.id, emails.name, emails.assigned_user_id, emails.parent_type, emails.parent_id, emails.date_start, emails.time_start , email_attachments.filename , email_attachments.parent_id,data,filesize FROM emails left join  email_attachments on emails.id=email_attachments.parent_id ';

        $result1 = mysql_query($dbQuery) or die("Couldn't get file list");
	$temparray = mysql_fetch_array($result1);
	//store this to the hard disk and give that url
	if(mysql_num_rows($result1) != 0)
	{
		$fileContent = $temparray['data'];
		$filename=$temparray['filename'];
		$filesize=$temparray['filesize'];

		//echo '<br> file name and size is ..'.$filename .'...'.$filesize; 
		$handle = fopen($_SERVER['DOCUMENT_ROOT']."/test/upload/".$temparray['filename'],"wb") ;
		//chmod("/home/rajeshkannan/test/".$fileContent,0755);
		fwrite($handle,base64_decode($fileContent),$filesize);
		fclose($handle);
	}

	$mail->AddAttachment($_SERVER['DOCUMENT_ROOT']."/test/upload/".$temparray['filename']); // add attachments

	//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
	//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
	$mail->IsHTML(true);                                  // set email format to HTML
	
	$mail->AltBody = "This is the body in plain text for non-HTML mail clients";


	if(!$mail->Send())
	{
	   echo "Message could not be sent. <p>";
	   echo "Mailer Error: " . $mail->ErrorInfo;
	   exit;
	}

require_once("Save.php");
header("Location: index.php?action=ListView&module=Emails&parent_id=$parent_id&record=$return_id&filename=$filename");

}
?>
