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

function send_mail($srcmodule,$to,$from,$subject,$contents,$mail_server,$mail_server_username,$mail_server_password)
//function send_mail($to,$from,$subject,$contents,$mail_server,$mail_server_username,$mail_server_password)
{
	$sql="select email1 from ". $srcmodule ." where id='" .$to ."'" ;
        $result = mysql_query($sql);
	$to = mysql_result($result,0,"email1");
	$initialfrom = $from;
	$sql="select email1 from users where user_name='" .$from ."'" ;
        $result = mysql_query($sql);
        $from = mysql_result($result,0,"email1");
$mail = new PHPMailer();

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
//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
$mail->IsHTML(true);                                  // set email format to HTML

$mail->Subject = $subject;
$mail->Body    = $contents;//"This is the HTML message body <b>in bold!</b>";
$mail->AltBody = "This is the body in plain text for non-HTML mail clients";


if(!$mail->Send())
{
   echo "Message could not be sent. <p>";
   echo "Mailer Error: " . $mail->ErrorInfo;
   exit;
}

}
?>
