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

require_once('include/logging.php');
require("class.phpmailer.php");
require_once('include/database/PearDatabase.php');

$local_log =& LoggerManager::getLogger('index');

function sendmail($to_list,$cc_list,$bcc_list,$from,$fromname,$subject,$body)
{
	global $adb,$root_directory,$mod_strings, $log;

	$mail = new PHPMailer();
	
	//$sub = $adb->query_result($result1,0,"subject");
	if(preg_match("/RE:/i",$subject))
		$mail->Subject = $subject;
	else
		$mail->Subject = "Re: ".$subject;


	//$DESCRIPTION .= '<font color=darkgrey>'.nl2br($adb->query_result($adb->query("select * from users where user_name=".PearDatabase::quote($from).),0,"signature")).'</font>';

        $mail->Body = $body;
	$mail->IsSMTP();

	$mailserverresult=$adb->query("select * from systems where server_type = 'email'");
        $mail_server=$adb->query_result($mailserverresult,0,'server');
	$mail_server_username=$adb->query_result($mailserverresult,0,'server_username');
        $mail_server_password=$adb->query_result($mailserverresult,0,'server_password');

	$mail->Host = $mail_server;
	$mail->SMTPAuth = true;
	$mail->Username = $mail_server_username;
	$mail->Password = $mail_server_password;

	$mail->From = $from;
	$mail->FromName = $fromname;
	$mail->AddReplyTo($from);
	$mail->WordWrap = 50;

	$log->info("From name & id are set in mail object => '".$mail->FromName."<".$mail->From.">' ");

	//$mail->AddAttachment($root_directory."/test/upload/".$filename);//temparray['filename']) // add attachments

	$mail->IsHTML(true);
	$tmpBody = preg_replace(array('/<br(.*?)>/i',"/&gt;/i","/&lt;/i","/&nbsp;/i","/&amp/i","/&copy;/i","/<style(.*?)>(.*?)<\/style>/i","/\{(.*?)\}/i","/BODY/i"),array("\r",">","<"," ","&","(c)","","",""),$body);
	$mail->AltBody = strip_tags($tmpBody);

	//header("Location: index.php?action=index&module=Webmails");
	echo '<table>';
	for($i=0;$i<count($to_list);$i++) {
		if($to_list[$i] != ""){
			$mail->AddAddress($to_list[$i]);
			echo '<tr><td><font color="red">Added TO: '.$to_list[$i].'</font></td></tr>';
		}
	}
	for($i=0;$i<count($cc_list);$i++) {
		if($cc_list[$i] != ""){
			$mail->AddCC($cc_list[$i]);
			echo '<tr><td><font color="red">Added CC: '.$cc_list[$i].'</font></td></tr>';
		}
	}
	for($i=0;$i<count($bcc_list);$i++) {
		if($bcc_list[$i] != ""){
			$mail->AddBCC($bcc_list[$i]);
			echo '<tr><td><font color="red">Added BCC: '.$bcc_list[$i].'</font></td></tr>';
		}
	}
	$mail->AddBCC = $from;
	if(!$mail->Send())
		echo $mail->ErrorInfo;

	echo '</table>';
	echo "<br><a href='index.php?module=Webmails&action=index'>Back to webmails</a>";
}
?>
