<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the 
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header:  vtiger_crm/sugarcrm/modules/Tasks/Save.php,v 1.5 2005/01/16 12:04:38 jack Exp $
 * Description:  Saves an Account record and then redirects the browser to the 
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Tasks/Task.php');
require_once('include/logging.php');
require("modules/Emails/class.phpmailer.php");
require_once("config.php");

$local_log =& LoggerManager::getLogger('index');

$focus = new Task();

$focus->retrieve($_REQUEST['record']);

foreach($focus->column_fields as $field)
{
	if(isset($_REQUEST[$field]))
	{
		$value = $_REQUEST[$field];
		$focus->$field = $value;
		
		$local_log->debug("saving task: $field is $value");
	}
}

foreach($focus->additional_column_fields as $field)
{
	if(isset($_REQUEST[$field]))
	{
		$value = $_REQUEST[$field];
		$focus->$field = $value;
		
	}
}

if (!isset($_REQUEST['date_due_flag'])) $focus->date_due_flag = 'off';

$focus->save();
$return_id = $focus->id;

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Tasks";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

$local_log->debug("Saved record with id of ".$return_id);

$fromquery="select * from users where user_name='".$current_user->user_name."'";
$rs=mysql_query($fromquery);
$from=mysql_result($rs,0,"email1");
$userfrom=mysql_result($rs,0,"user_name");

//echo $return_id.'..'.$_REQUEST['return_id'].'-----'.$focus->id;
//$sql="select accounts.email1,tasks.parent_id,parent_type from tasks left join accounts on accounts.id=tasks.parent_id where tasks.id='".$focus->id."' and parent_type='Accounts'";

$sql="select email1,id,user_name from users where id='".$_REQUEST['assigned_user_id']."'";
$result=mysql_query($sql);
$mailid=mysql_result($result,0,"email1");

$query="select * from tasks where id='".$return_id."'";
$result1=mysql_query($query);
$subject=mysql_result($result1,0,"name");
$contents=mysql_result($result1,0,"description");


	$mail = new PHPMailer();
	$mail->Subject = $subject;
	$mail->Body    = $contents;//"This is the HTML message body <b>in bold!</b>";
	$initialfrom = $userfrom;//$from;
        $mail->IsSMTP();                                      // set mailer to use SMTP

	$to=$mailid;

        $mail->Host = $mail_server;  // specify main and backup server
        $mail->SMTPAuth = true;     // turn on SMTP authentication
        $mail->Username = $mail_server_username ;//$smtp_username;  // SMTP username
        $mail->Password = $mail_server_password ;//$smtp_password; // SMTP password
        $mail->From = $from;
        $mail->FromName = $initialfrom;
        $mail->AddAddress($to);                  // name is optional
        $mail->AddReplyTo($from);
        $mail->WordWrap = 50;                                 // set word wrap to 50 characters



       $mail->IsHTML(true);                                  // set email format to HTML

        $mail->AltBody = "This is the body in plain text for non-HTML mail clients";


        if(!$mail->Send())
        {
           echo "Message could not be sent. <p>";
           echo "Mailer Error: " . $mail->ErrorInfo;
        }


header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
?>
