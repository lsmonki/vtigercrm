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


require("class.phpmailer.php");

//Add these lines in wherever we want to use this function
//include("modules/Emails/mail.php");
//send_mail('Emails',$to_email,$current_user->user_name,'',$subject,$body,$ccmail,$bccmail);

/* Function used to send email 
   $module 		-- current module 
   $to_email 		-- to email address 
   $from_name		-- currently loggedin user name
   $from_email		-- currently loggedin users's email id. please give as '' if you are not in HelpDesk module
   $subject		-- subject of the email you want to send
   $contents		-- body of the email you want to send
   $cc			-- add email ids with comma seperated. - optional 
   $bcc			-- add email ids with comma seperated. - optional.
*/
function send_mail($module,$to_email,$from_name,$from_email,$subject,$contents,$cc='',$bcc='')
{

	global $adb, $vtlog;
	global $root_directory;
	global $HELPDESK_SUPPORT_EMAIL_ID, $HELPDESK_SUPPORT_NAME;

	$uploaddir = $root_directory ."/test/upload/";

	$adb->println("To id => '".$to_email."'\nSubject ==>'".$subject."'\nContents ==> '".$contents."'");

	//Get the email id of assigned_to user -- pass the value and name, name must be "user_name" or "id"(field names of users table)
	//$to_email = getUserEmailId('id',$assigned_user_id);

	//if module is HelpDesk then from_email will come based on support email id 
	if($from_email == '')//$module != 'HelpDesk')
		$from_email = getUserEmailId('user_name',$from_name);

	//Add the signature with the contents of the email
	$contents = addSignature($contents,$from_name);

	//Create new PHPMailer object and set all the values in that object
	$mail = new PHPMailer();

	setMailerProperties(&$mail,$subject,$contents,$from_email,$from_name,$to_email);
	setCCAddress(&$mail,'cc',$cc);
	setCCAddress(&$mail,'bcc',$bcc);

	$mail_status = MailSend(&$mail);

	//This is to get the correct mail error
	if($mail_status != 1)
	{
		$mail_error = getMailError(&$mail,$mail_status,$mailto);
	}
	else
	{
		$mail_error = $mail_status;
	}

return $mail_error;

	/*//This functionality is changed to multi parent by Raju
	//TODO -- get the Parent mail id and add it with the mail object
	if($_REQUEST['module'] != 'Emails')
	{
	        $parent_mail = getParentMailId($_REQUEST['return_module'],$_REQUEST['parent_id']);
		if($parent_mail != '')
		{
			$mail->ClearAddresses();
			$mail->AddAddress($mailto);
			$mail_status = MailSend(&$mail);
			echo 'Parent Mail sending status => '.$mail_status;
		}
	}
	*/

//	header("Location: index.php?action=$returnaction&module=$returnmodule&parent_id=$parent_id&record=$return_id&filename=$filename&message=$error_info");

}

function getUserEmailId($name,$val)
{
	global $adb;
	$adb->println("Inside the function getUserEmailId. --- ".$name." = '".$val."'");
	if($val != '')
	{
		$sql = "select email1, email2, yahoo_id from users where ".$name." = '".$val."'";
		$res = $adb->query($sql);
		$email = $adb->query_result($res,0,'email1');
		if($email == '')
		{
			$email = $adb->query_result($res,0,'email2');
			if($email == '')
			{
				$email = $adb->query_result($res,0,'yahoo_id');
			}
		}
		$adb->println("Email id is selected  => '".$email."'");
		return $email;
	}
	else
	{
		$adb->println("User id is empty. so return value is ''");
		return '';
	}
}
function addSignature($contents, $fromname)
{
	global $adb;
	$adb->println("Inside the function addSignature");

	$sign = $adb->query_result($adb->query("select signature from users where user_name='".$fromname."'"),0,"signature");
	if($sign != '')
	{
		$contents .= '<br><br><font color=darkgrey>'.$sign.'</font>';
		$adb->println("Signature is added with the body => '.".$sign."'");
	}
	else
	{
		$adb->println("Signature is empty for the user => '".$fromname."'");
	}
	return $contents;
}

function setMailerProperties($mail,$subject,$contents,$from_email,$from_name,$to_email)
{
	global $adb;
	$adb->println("Inside the function setMailerProperties");

	$mail->Subject = $subject;
	$mail->Body = nl2br($contents);

	$mail->IsSMTP();		//set mailer to use SMTP
	//$mail->Host = "smtp1.example.com;smtp2.example.com";  // specify main and backup server

	setMailServerProperties(&$mail);	
	$mail->SMTPAuth = true;     // turn on SMTP authentication

	//TODO -- handle the from name and email for HelpDesk
	$mail->From = $from_email;
	$mail->FromName = $from_name;

	$mail->AddAddress($to_email);

	$mail->AddReplyTo($from_email);
	$mail->WordWrap = 50;

	//TODO -- handling the attachments here
	addAttachments(&$mail,$_REQUEST['record'],$_REQUEST['filename']);

	$mail->IsHTML(true);		// set email format to HTML

	$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

        //echo '<pre>';print_r($_REQUEST);echo '</pre>';
	return;
}
function setMailServerProperties($mail)
{
	global $adb;
	$adb->println("Inside the function setMailServerProperties");

	$res = $adb->query("select * from systems where server_type='email'");
	$server=$adb->query_result($res,0,'server');
        $username=$adb->query_result($res,0,'server_username');
        $password=$adb->query_result($res,0,'server_password');

	$adb->println("Mail server name,username & password => '".$server."','".$username."','".$password."'");

	$mail->Host = $server;		// specify main and backup server
	$mail->Username = $username ;	// SMTP username
        $mail->Password = $password ;	// SMTP password

	return;
}
function addAttachments($mail,$record,$filename)
{
	global $adb, $root_directory;
	$adb->println("Inside the function addAttachments");

	//TODO -- if the file is unlinked and available in database then we will open and write the file and then attach
	if($filename != '')
	{
		$mail->AddAttachment($root_directory."test/upload/".$filename);//temparray['filename']) 
	}
}
function setCCAddress($mail,$cc_mod,$cc_val)
{
	global $adb;
	$adb->println("Inside the functin setCCAddress");

	if($cc_mod == 'cc')
		$method = 'AddCC';
	if($cc_mod == 'bcc')
		$method = 'AddBCC';
	if($cc_val != '')
	{
		$ccmail = explode(",",$cc_val);
		for($i=0;$i<count($ccmail);$i++)
		{
			if($ccmail[$i] != '')
				$mail->$method($ccmail[$i]);
		}
	}
}
function MailSend($mail)
{
	global $vtlog;
	$vtlog->logthis("Inside of Send Mail function.",'info');
        if(!$mail->Send())
        {
		$vtlog->logthis("Error in Mail Sending : Error log = '".$mail->ErrorInfo."'",'debug');
		//$info = explode(":",$mail->ErrorInfo);
		//$msg = trim($info[1]);
		return $mail->ErrorInfo;//$msg;
        }
	else 
	{
		$vtlog->logthis("Mail has been sent from the vtigerCRM system : Status : '".$mail->ErrorInfo."'",'info');
		return 1;
	}
}

function getParentMailId($returnmodule,$parentid)
{
	global $adb;
	global $vtlog;
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
		$vtlog->logthis("Return Module in send_mail page : ".$returnmodule,'info');
		$vtlog->logthis("Email id of parent (Lead/Contact) is selected => ".$mailid,'info');
	}
        if($mailid == '' && $returnmodule =='Contacts')
        {
                $mailid = $adb->query_result($adb->query($query),0,'otheremail');
                if($mailid == '')
                        $mailid = $adb->query_result($adb->query($query),0,'yahooid');
        }
	return $mailid;
}

//Added function to parse the mail error and return the correct error
function getMailError($mail,$mail_status,$to)
{
	//Error types in class.phpmailer.php
	/*
	provide_address, mailer_not_supported, execute, instantiate, from_failed, recipients_failed, data_not_accepted, authenticate, 
	connect_host, file_access, file_open, encoding
	*/

	global $adb;
	$adb->println("Inside the function getMailError");

	$error_info = explode(":",$mail_status);
	$msg = trim($error_info[1]);
	$adb->println("Error message ==> ".$msg);

	if($msg == 'connect_host')
	{
		$error_msg =  $msg;
	}
	elseif(strstr($msg,'from_failed'))
	{
		$error_msg = $msg;//."&&&".$mail->from;
	}
	elseif(strstr($msg,'recipients_failed'))
	{
		$error_msg = $msg;//."&&&".$to;
	}

	$adb->println("return error => ".$error_msg);
	return $error_msg;
}

//Function to get the mail status string(string of all mail status) and return the error status only as a encoded string
function getMailErrorString($mail_status_str)
{
	global $adb;
	$adb->println("Inside getMailErrorString function.\nMail status string ==> ".$mail_status_str);

	$mail_status_str = trim($mail_status_str,"&&&");
	$mail_status_array = explode("&&&",$mail_status_str);
	$adb->println("All Mail status ==>\n".$mail_status_str."\n");
	//$adb->println($mail_status_array);
	foreach($mail_status_array as $key => $val)
	{
		$list = explode("=",$val);
		$adb->println("Mail id & status ==> ".$list[0]." = ".$list[1]);
		if($list[1] == 0)
		{
			$mail_error_str .= $list[0]."=".$list[1]."&&&";
		}
	}
	$adb->println("Mail error string => '".$mail_error_str."'");
	if($mail_error_str != '')
	{
		$mail_error_str = 'mail_error='.base64_encode($mail_error_str);
	}
	return $mail_error_str;
}

//Function to parse the error string and return the display message
function parseEmailErrorString($mail_error_str)
{
	global $adb, $mod_strings;
	$adb->println("Inside the parseEmailErrorString function.\n encoded mail error string ==> ".$mail_error_str);

	$mail_error = base64_decode($mail_error_str);
	$adb->println("Original error string => ".$mail_error);
	$mail_status = explode("&&&",trim($mail_error,"&&&"));
	foreach($mail_status as $key => $val)
	{
		$status_str = explode("=",$val);
		$adb->println('Mail id => "'.$status_str[0].'".........status => "'.$status_str[1].'"');
		if($status_str[1] != 1 && $status_str[1] != '')
		{
			$adb->println("Error in mail sending");
			if($status_str[1] == 'connect_host')
			{
				$adb->println("if part - Mail sever is not configured");
				$errorstr .= '<br><b><font color=red>Please Check the Mail Server Name...</font></b>';
				break;
			}
			elseif($status_str[1] == '0')
			{
				$adb->println("first elseif part - status will be 0 which is the case of assigned to users's email is empty.");
				$errorstr .= '<br><b><font color=red> Mail could not be sent to the assigned to user. Please check the assigned to user email id...</font></b>';
			}
			elseif(strstr($status_str[1],'from_failed'))
			{
				$adb->println("second elseif part - from email id is failed.");
				$from = explode('from_failed',$status_str[1]);
				$errorstr .= "<br><b><font color=red>Please check the from email id '".$from[1]."'</font></b>";
			}
			else
			{
				$adb->println("else part - mail send process failed due to the following reason.");
				$errorstr .= "<br><b><font color=red> Mail could not be sent to this email id '".$status_str[0]."'. Please check this mail id...</font></b>";	
			}
		}
	}
	$adb->println("Return Error string => ".$errorstr);
	return $errorstr;
}
?>
