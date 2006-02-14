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


require("modules/Emails/class.phpmailer.php");

//Add these lines in wherever we want to use this function
//include("modules/Emails/mail.php");
//send_mail('Emails',$to_email,$from_name,$from_email,$subject,$body,$ccmail,$bccmail);

/**   Function used to send email 
  *   $module 		-- current module 
  *   $to_email 	-- to email address 
  *   $from_name	-- currently loggedin user name
  *   $from_email	-- currently loggedin users's email id. you can give as '' if you are not in HelpDesk module
  *   $subject		-- subject of the email you want to send
  *   $contents		-- body of the email you want to send
  *   $cc		-- add email ids with comma seperated. - optional 
  *   $bcc		-- add email ids with comma seperated. - optional.
  *   $attachment	-- whether we want to attach the currently selected file or all files.[values = current,all] - optional
  *   $emailid		-- id of the email object which will be used to get the attachments
  */
function send_mail($module,$to_email,$from_name,$from_email,$subject,$contents,$cc='',$bcc='',$attachment='',$emailid='')
{

	global $adb, $log;
	global $root_directory;
	global $HELPDESK_SUPPORT_EMAIL_ID, $HELPDESK_SUPPORT_NAME;

	$uploaddir = $root_directory ."/test/upload/";

	$adb->println("To id => '".$to_email."'\nSubject ==>'".$subject."'\nContents ==> '".$contents."'");

	//Get the email id of assigned_to user -- pass the value and name, name must be "user_name" or "id"(field names of users table)
	//$to_email = getUserEmailId('id',$assigned_user_id);

	//if module is HelpDesk then from_email will come based on support email id 
	if($from_email == '')//$module != 'HelpDesk')
		$from_email = getUserEmailId('user_name',$from_name);

	$contents = addSignature($contents,$from_name);

	$mail = new PHPMailer();

	setMailerProperties(&$mail,$subject,$contents,$from_email,$from_name,$to_email,$attachment,$emailid);
	setCCAddress(&$mail,'cc',$cc);
	setCCAddress(&$mail,'bcc',$bcc);

	$mail_status = MailSend(&$mail);

	if($mail_status != 1)
	{
		$mail_error = getMailError(&$mail,$mail_status,$mailto);
	}
	else
	{
		$mail_error = $mail_status;
	}

	return $mail_error;
}

/**	Function to get the user Email id based on column name and column value
  *	$name -- column name of the users table 
  *	$val  -- column value 
  */
function getUserEmailId($name,$val)
{
	global $adb;
	$adb->println("Inside the function getUserEmailId. --- ".$name." = '".$val."'");
	if($val != '')
	{
		//$sql = "select email1, email2, yahoo_id from users where ".$name." = '".$val."'";
		$sql = "select email1, email2, yahoo_id from users where ".$name." = ".PearDatabase::quote($val);
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

/**	Funtion to add the user's signature with the content passed
  *	$contents -- where we want to add the signature
  *	$fromname -- which user's signature will be added to the contents
  */
function addSignature($contents, $fromname)
{
	global $adb;
	$adb->println("Inside the function addSignature");

	$sign = $adb->query_result($adb->query("select signature from users where user_name=".PearDatabase::quote($fromname)),0,"signature");
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

/**	Function to set all the Mailer properties
  *	$mail -- reference of the mail object
  *	other parameters are same as passed in send_mail function
  */
function setMailerProperties($mail,$subject,$contents,$from_email,$from_name,$to_email,$attachment='',$emailid='')
{
	global $adb;
	$adb->println("Inside the function setMailerProperties");

	$mail->Subject = $subject;
	$mail->Body = nl2br($contents);

	$mail->IsSMTP();		//set mailer to use SMTP
	//$mail->Host = "smtp1.example.com;smtp2.example.com";  // specify main and backup server

	setMailServerProperties(&$mail);	

	//Handle the from name and email for HelpDesk
	$mail->From = $from_email;
	$mail->FromName = $from_name;

	if($to_email != '')
	{
		$mail->AddAddress($to_email);
	}

	$mail->AddReplyTo($from_email);
	$mail->WordWrap = 50;

	//If we want to add the currently selected file only then we will use the following function
	if($attachment == 'current')
	{
		addAttachment(&$mail,$_FILES['filename']['name'],$emailid);
	}

	//This will add all the files which are related to this record or email
	if($attachment == 'all')
	{
		addAllAttachments(&$mail,$emailid);
	}

	$mail->IsHTML(true);		// set email format to HTML

	$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

	return;
}

/**	Function to set the Mail Server Properties in the object passed
  *	$mail -- reference of the mailobject
  */
function setMailServerProperties($mail)
{
	global $adb;
	$adb->println("Inside the function setMailServerProperties");

	$res = $adb->query("select * from systems where server_type='email'");
	$server = $adb->query_result($res,0,'server');
        $username = $adb->query_result($res,0,'server_username');
        $password = $adb->query_result($res,0,'server_password');
	$smtp_auth = $adb->query_result($res,0,'smtp_auth');

	$adb->println("Mail server name,username & password => '".$server."','".$username."','".$password."'");

	$mail->SMTPAuth = $smtp_auth;	// turn on SMTP authentication
	$mail->Host = $server;		// specify main and backup server
	$mail->Username = $username ;	// SMTP username
        $mail->Password = $password ;	// SMTP password

	return;
}

/**	Function to add the file as attachment with the mail object
  *	$mail -- reference of the mail object
  *	$filename -- filename which is going to added with the mail
  *	$record -- id of the record - optional 
  */
function addAttachment($mail,$filename,$record)
{
	global $adb, $root_directory;
	$adb->println("Inside the function addAttachment");
	$adb->println("The file name is => '".$filename."'");

	//This is the file which has been selected in Email EditView
        if(is_file($filename) && $filename != '')
        {
                $mail->AddAttachment($root_directory."test/upload/".$filename);
        }
}

/**     Function to add all the files as attachment with the mail object
  *     $mail -- reference of the mail object
  *     $record -- email id ie., record id which is used to get the all attachments from database
  */
function addAllAttachments($mail,$record)
{
	global $adb, $root_directory;
        $adb->println("Inside the function addAllAttachments");

	//Retrieve the files from database where avoid the file which has been currently selected
	$sql = "select attachments.* from attachments inner join seattachmentsrel on attachments.attachmentsid = seattachmentsrel.attachmentsid inner join crmentity on crmentity.crmid = attachments.attachmentsid where crmentity.deleted=0 and seattachmentsrel.crmid=".$record;
	$res = $adb->query($sql);
	$count = $adb->num_rows($res);

	for($i=0;$i<$count;$i++)
	{
		$filename = $adb->query_result($res,$i,'name');
		$filewithpath = $root_directory."test/upload/".$filename;

		//if the file is exist in test/upload directory then we will add directly
		//else get the contents of the file and write it as a file and then attach (this will occur when we unlink the file)
		if(is_file($filewithpath))
		{
			$mail->AddAttachment($filewithpath);
		}
		elseif($filename != '')
		{
			$contents = $adb->query_result($res,$i,'attachmentcontents');
			$size = $adb->query_result($res,$i,'attachmentsize');

			@$handle = fopen($filewithpath,'wb');
			@fwrite($handle,base64_decode($contents),$size);
			@fclose($handle);

			$mail->AddAttachment($filewithpath);
		}
	}
}

/**	Function to set the CC or BCC addresses in the mail
  *	$mail -- reference of the mail object
  *	$cc_mod -- mode to set the address ie., cc or bcc
  *	$cc_val -- addresss with comma seperated to set as CC or BCC in the mail
  */
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

/**	Function to send the mail which will be called after set all the mal object values
  *	$mail -- reference of the mail object
  */
function MailSend($mail)
{
	global $log;
         $log->info("Inside of Send Mail function.");
	if(!$mail->Send())
        {
		$log->debug("Error in Mail Sending : Error log = '".$mail->ErrorInfo."'");
		return $mail->ErrorInfo;
        }
	else 
	{
		 $log->info("Mail has been sent from the vtigerCRM system : Status : '".$mail->ErrorInfo."'");
		return 1;
	}
}

/**	Function to get the Parent email id from HelpDesk to send the details about the ticket via email
  *	$returnmodule -- Parent module value. Contact or Account for send email about the ticket details
  *	$parentid -- id of the parent ie., contact or account
  */
function getParentMailId($parentmodule,$parentid)
{
	global $adb;
	$adb->println("Inside the function getParentMailId. \n parent module and id => ".$parentmodule."&".$parentid);

        if($parentmodule == 'Contacts')
        {
                $tablename = 'contactdetails';
                $idname = 'contactid';
		$first_email = 'email';
		$second_email = 'yahooid';
        }
        if($parentmodule == 'Accounts')
        {
                $tablename = 'account';
                $idname = 'accountid';
		$first_email = 'email1';
		$second_email = 'email2';
        }
	if($parentid != '')
	{
	        //$query = 'select * from '.$tablename.' where '.$idname.' = '.$parentid;
	        $query = 'select * from '.$tablename.' where '. $idname.' = '.PearDatabase::quote($parentid);
	        $mailid = $adb->query_result($adb->query($query),0,$first_email);
		$mailid2 = $adb->query_result($adb->query($query),0,$second_email);
	}
        if($mailid == '' && $mailid2 != '')
        	$mailid = $mailid2;

	return $mailid;
}

/**	Function to parse the mail error and return the correct error
  *	$mail -- reference of the mail object
  *	$mail_status -- status of the mail which is sent or not
  *	$to -- the email address to whom we sent the mail and failes
  */
function getMailError($mail,$mail_status,$to)
{
	//Error types in class.phpmailer.php
	/*
	provide_address, mailer_not_supported, execute, instantiate, file_access, file_open, encoding, data_not_accepted, authenticate, 
	connect_host, recipients_failed, from_failed
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
		$error_msg = $msg;
	}
	elseif(strstr($msg,'recipients_failed'))
	{
		$error_msg = $msg;
	}
	else
	{
		$adb->println("Mail error is not as connect_host or from_failed or recipients_failed");
		//$error_msg = $msg;
	}

	$adb->println("return error => ".$error_msg);
	return $error_msg;
}

/**	Function to get the mail status string(string of all mail status) and return the error status only as a encoded string
  *	$mail_status_str -- concatenated string with all the error messages with &&& seperation
  */
function getMailErrorString($mail_status_str)
{
	global $adb;
	$adb->println("Inside getMailErrorString function.\nMail status string ==> ".$mail_status_str);

	$mail_status_str = trim($mail_status_str,"&&&");
	$mail_status_array = explode("&&&",$mail_status_str);
	$adb->println("All Mail status ==>\n".$mail_status_str."\n");

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

/**	Function to parse the error string and return the display message
  *	$mail_error_str -- base64 encoded string which contains the mail sending errors as concatenated with &&&
  */
function parseEmailErrorString($mail_error_str)
{
	//TODO -- we can modify this function for better email error handling in future
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
				//Added to display the message about the CC && BCC mail sending status
				if($status_str[0] == 'cc_success')
				{
                                        $cc_msg = 'But the mail has been sent to CC & BCC addresses.';
					$errorstr .= '<br><b><font color=purple>'.$cc_msg.'</font></b>';
				}
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
