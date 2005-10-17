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
$firstloop=true;
$assigned=$_REQUEST['assigned_user_id'];
$contents=$_REQUEST['description'];
$subject=$_REQUEST['name'];
$ccmail=$_REQUEST['ccmail'];
$from=$current_user->user_name;
$sql="select email1 from users where id='" .$assigned ."'" ;
$vtlog->logthis("Query to select the Emailid of assigned_to user : ",'debug');
$result = $adb->query($sql);
if(!@$assigned = $adb->query_result($result,0,"email1"))
{
	$vtlog->logthis("Could not get the email id of assigned_to user (to email address).",'debug');
}
if(!@$sign = $adb->query_result($adb->query("select * from users where user_name='".$from."'"),0,"signature")){}
$contents .= '<br><br><font color=darkgrey>'.nl2br($sign).'</font>';
$vtlog->logthis("Current logged in users signature is added with body of the email => ".$sign,'info');

$sql="select email1 from users where user_name='" .$from ."'" ;
$result = $adb->query($sql);
$from = $adb->query_result($result,0,"email1");
$vtlog->logthis("From Email id is selected => ".$from,'debug');
$mailserverresult=$adb->query("select * from systems where server_type='email'");
$mail_server=$adb->query_result($mailserverresult,0,'server');
$mail_server_username=$adb->query_result($mailserverresult,0,'server_username');
$mail_server_password=$adb->query_result($mailserverresult,0,'server_password');
$_REQUEST['server']=$mail_server;
$vtlog->logthis("Mail Server is selected => '".$mail_server."'",'info');
$vtlog->logthis("Mail Server UserName is selected => '".$mail_server_username."'",'info');
$vtlog->logthis("Mail Server Password is selected => '".$mail_server_password."'",'info');
if($_REQUEST['return_id'] != '')
{
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

			if(!@$handle = fopen($root_directory."/test/upload/".$filename,"wb")){}
			//chmod("/home/rajeshkannan/test/".$fileContent,0755);
			if(!@fwrite($handle,base64_decode($fileContent),$filesize)){}
			if(!@fclose($handle)){}
		}
		$attachpath=$root_directory."/test/upload/".$filename;
	}
}
send_mail($assigned,$from,$subject,$contents,$mail_server,$mail_server_username,$mail_server_password,$attachpath,$ccmail);
$ccmail='';

$parentid= $_REQUEST['parent_id'];
$myids=explode("|",$parentid);
//echo count($myids).'count';
for ($i=0;$i<(count($myids)-1);$i++)
{
	$realid=explode("@",$myids[$i]);
	$nemail=count($realid);
	$mycrmid=$realid[0];
	$pmodule=getSalesEntityType($mycrmid);
	for ($j=1;$j<$nemail;$j++){
		$temp=$realid[$j];
		$myquery='Select columnname from field where fieldid='.$temp;
		$fresult=$adb->query($myquery);			
		if ($pmodule=='Contacts'){
			require_once('modules/Contacts/Contact.php');
			$myfocus = new Contact();
			$myfocus->retrieve_entity_info($mycrmid,"Contacts");
		}
		elseif ($pmodule=='Accounts'){
			require_once('modules/Accounts/Account.php');
			$myfocus = new Account();
			$myfocus->retrieve_entity_info($mycrmid,"Accounts");
		} 
		elseif ($pmodule=='Leads'){
			require_once('modules/Leads/Lead.php');
			$myfocus = new Lead();
			$myfocus->retrieve_entity_info($mycrmid,"Leads");
		}
		$fldname=$adb->query_result($fresult,0,"columnname");
		$emailadd=br2nl($myfocus->column_fields[$fldname]);
		send_mail($emailadd,$from,$subject,$contents,$mail_server,$mail_server_username,$mail_server_password,$attachpath,$ccmail);

	}	
}

function send_mail($mailto,$from,$subject,$contents,$mail_server,$mail_server_username,$mail_server_password,$attachpath,$ccmail)
{
	global $vtlog;
	global $adb;
	global $root_directory;
	global $HELPDESK_SUPPORT_EMAIL_ID, $HELPDESK_SUPPORT_NAME;
	global $firstloop;
	$uploaddir = $root_directory ."/test/upload/" ;// set this to wherever
	$mail = new PHPMailer();
	$mail->Subject = $subject;
	$mail->Body    = nl2br($contents);//"This is the HTML message body <b>in bold!</b>";
	$initialfrom = $from;
	$mail->IsSMTP();                                      // set mailer to use SMTP
	//$mail->Host = "smtp1.example.com;smtp2.example.com";  // specify main and backup server
	$mail->Host = $mail_server;  // specify main and backup server
	$mail->SMTPAuth = true;     // turn on SMTP authentication
	$mail->Username = $mail_server_username ;//$smtp_username;  // SMTP username
	$mail->Password = $mail_server_password ;//$smtp_password; // SMTP password
	$mail->From = $from;
	$mail->FromName = $initialfrom;
	$vtlog->logthis("Mail sending process : From Email id = '".$from."' (set in the mail object)",'info');
	$vtlog->logthis("Mail sending process : From Name  =  '".$initialfrom."' (set in the mail object)",'info');
	if($ccmail != '')
	{
		$ccmail = explode(";",$ccmail);
		for($i=0;$i<count($ccmail);$i++)
		{
			$mail->AddCC($ccmail[$i]);
			$vtlog->logthis("CC mail id is added => '".$ccmail[$i]."'",'info');
		}
	 }
	$mail->AddReplyTo($from);					
	$mail->WordWrap = 50;                                 // set word wrap to 50 characters
	//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
	//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
	$mail->AddAttachment($attachpath);//temparray['filename']) 
	$vtlog->logthis("File '".$filename."' is attached with the mail.",'debug');

	$mail->IsHTML(true);                                  // set email format to HTML
	$mail->AltBody = "This is an html email so please use an html client email program to see the content of this email";
	if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != '')
			$returnmodule = $_REQUEST['return_module'];
	if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != '')
			$returnaction = $_REQUEST['return_action'];
	if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != '')
			$return_id = $_REQUEST['return_id'];
 
	//added by raju to send the email to the assigned to user only once
	$mail->ClearAddresses();
	$vtlog->logthis("Parent(Lead/Contact) Mail id is selected and added in to address.",'debug');
	$mail->AddAddress($mailto);
	$error_info = MailSend($mail);
	$vtlog->logthis("After MailSend function. Return value = '".$error_info."'",'info');
	$returnaction = 'index';
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
	$vtlog->logthis("This page is redirected to : '".$returnmodule."/".$returnaction."' & return id :".$return_id,'info');
    header("Location: index.php?action=$returnaction&module=$returnmodule&parent_id=$parent_id&record=$return_id&filename=$filename&message=$error_info");
}

function MailSend($mail)
{
	global $vtlog;
	$vtlog->logthis("Inside of Send Mail function.",'info');
    if(!$mail->Send())
    {
		$vtlog->logthis("Error in Mail Sending : Error log = '".$mail->ErrorInfo."'",'debug');
		$msg =$mail->ErrorInfo;
		return $msg;
    }
	else 
	{
		$vtlog->logthis("Mail has been sent from the vtigerCRM system : Status : '".$mail->ErrorInfo."'",'info');
		return true;
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

?>
