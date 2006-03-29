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


//file modified by richie

require("modules/Emails/class.phpmailer.php");
require("include/database/PearDatabase.php");
require_once('include/logging.php');
require("config.php");

// Get the list of activity for which reminder needs to be sent

global $adb;
global $log;
$log =& LoggerManager::getLogger('SendReminder');
$log->debug(" invoked SendReminder ");

//modified query for recurring events -Jag
 	$query="select crmentity.crmid,seactivityrel.crmid as setype,activity.*,activity_reminder.reminder_time,activity_reminder.reminder_sent,activity_reminder.recurringid,recurringevents.recurringdate from activity inner join crmentity on crmentity.crmid=activity.activityid inner join activity_reminder on activity.activityid=activity_reminder.activity_id left outer join recurringevents on activity.activityid=recurringevents.activityid left outer join seactivityrel on seactivityrel.activityid = activity.activityid where DATE_FORMAT(activity.date_start,'%Y-%m-%d, %H:%i:%s') >= '".date('Y-m-d')."' and crmentity.crmid != 0 and activity.eventstatus = 'Planned' and activity_reminder.reminder_sent = 0 group by activity.activityid,recurringevents.recurringid";

$result = $adb->query($query);

if($adb->num_rows($result) >= 1)
{
	while($result_set = $adb->fetch_array($result))
	{
		$date_start = $result_set['date_start'];
		$time_start = $result_set['time_start'];
		$reminder_time = $result_set['reminder_time'];
	        $curr_time = strtotime(date("Y-m-d H:i"))/60;
		$activity_id = $result_set['activityid'];
		$activitymode = ($result_set['activitytype'] == "Task")?"Task":"Events";
		$parent_type = $result_set['setype']; 
		$activity_sub = $result_set['subject'];
		$to_addr='';
			
		if($parent_type!='')
		$parent_content = getParentInfo($parent_type)."\n";
		else
		$parent_content = "";
		//code included for recurring events by jaguar starts	
		$recur_id = $result_set['recurringid'];
		$current_date=date('Y-m-d');
		if($recur_id == 0)
		{
			$date_start = $result_set['date_start'];
		}
		else
		{
			$date_start = $result_set['recurringdate'];
		}
		//code included for recurring events by jaguar ends	

	        $activity_time = strtotime(date("$date_start $time_start"))/60;

		if (($activity_time - $curr_time) > 0 && ($activity_time - $curr_time) == $reminder_time)
		{
			$log->debug(" InSide  REMINDER");
			$query_user="SELECT users.email1,salesmanactivityrel.smid FROM salesmanactivityrel inner join users on users.id=salesmanactivityrel.smid where salesmanactivityrel.activityid =".$activity_id." and users.deleted=0"; 
			$user_result = $adb->query($query_user);		
			if($adb->num_rows($user_result)>=1)
			{
				while($user_result_row = $adb->fetch_array($user_result))
				{
					if($user_result_row['email1']!='' || $user_result_row['email1'] !=NULL)
					{
						$to_addr[] = $user_result_row['email1'];
					}
				}
			}
		
				// Set the preferred email id
			$from ="reminders@localserver.com";
			
			// Retriving the Subject and message from reminder table		
			$sql = "select active,notificationsubject,notificationbody from notificationscheduler where schedulednotificationid=7";
			$result_main = $adb->query($sql);

			$subject = "[Reminder:".$result_set['activitytype']." @ ".$result_set['date_start']." ".$result_set['time_start']."] ".$adb->query_result($result_main,0,'notificationsubject');

			//Set the mail body/contents here
			$contents = nl2br($adb->query_result($result_main,0,'notificationbody')) ."\n\n Subject : ".$activity_sub."\n ". $parent_content ." Date & Time : ".$date_start." ".$time_start."\n\n Kindly visit the link for more details on the activity <a href='".$site_URL."/index.php?action=DetailView&module=Activities&record=".$activity_id."&activity_mode=".$activitymode."'>Click here</a>";

			if(count($to_addr) >=1)
			{
				send_mail($to_addr,$from,$subject,$contents,$mail_server,$mail_server_username,$mail_server_password);
				$upd_query = "UPDATE activity_reminder SET reminder_sent=1 where activity_id=".$activity_id;

				if($recur_id!=0)
				{
					$upd_query.=" and recurringid =".$recur_id;
				}

				$adb->query($upd_query);

				
				
			}
		}
	}
}

/**
 This function is used to assign parameters to the mail object and send it.
 It takes the following as parameters.
	$to as string - to address
	$from as string - from address
	$subject as string - subject if the mail
	$contents as text - content of the mail
	$mail_server as string - sendmail server name 
	$mail_server_username as string - sendmail server username 
	$mail_server_password as string - sendmail server password

*/
function send_mail($to,$from,$subject,$contents,$mail_server,$mail_server_username,$mail_server_password)
{
	global $adb;
	 global $log;
        $log->info("This is send_mail function in SendReminder.php(vtiger home).");
	global $root_directory;

	$mail = new PHPMailer();


	$mail->Subject = $subject;
	$mail->Body    = nl2br($contents);//"This is the HTML message body <b>in bold!</b>";


	$mail->IsSMTP();                                      // set mailer to use SMTP

	if($mail_server=='')
	{
		$mailserverresult=$adb->query("select * from systems where server_type='email'");
		$mail_server = $adb->query_result($mailserverresult,0,'server');
		$mail_server_username = $adb->query_result($mailserverresult,0,'server_username');
		$mail_server_password = $adb->query_result($mailserverresult,0,'server_password');
		$smtp_auth = $adb->query_result($mailserverresult,0,'smtp_auth');

		$_REQUEST['server']=$mail_server;
		$log->info("Mail Server Details => '".$mail_server."','".$mail_server_username."','".$mail_server_password."'");

	}	

	$mail->Host = $mail_server;			// specify main and backup server
	$mail->SMTPAuth = $smtp_auth;			// turn on SMTP authentication
	$mail->Username = $mail_server_username ;	// SMTP username
	$mail->Password = $mail_server_password ;	// SMTP password
	$mail->From = $from;
	$mail->FromName = $initialfrom;
	$log->info("Mail sending process : From Name & email id => '".$initialfrom."','".$from."'");
	foreach($to as $pos=>$addr)
	{
		$mail->AddAddress($addr);                  // name is optional
		$log->info("Mail sending process : To Email id = '".$addr."' (set in the mail object)");

	}
	$mail->WordWrap = 50;                                 // set word wrap to 50 characters

	$mail->IsHTML(true);                                  // set email format to HTML
	
	$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

	$flag = MailSend($mail);
	$log->info("After executing the mail->Send() function.");
}

/**
 This function is used to ensure mail has been sent sucessfully with out error.
 It takes the mail object as the input and returns true if sucess else an error messaget. 
*/
function MailSend($mail)
{
	global $log;
        if(!$mail->Send())
        {
		$log->info("Error in Mail Sending : Error log = '".$mail->ErrorInfo."'");
           $msg = $mail->ErrorInfo;
        }
	else
       	{	
		$log->info("Mail has been sent from the vtigerCRM system : Status : '".$mail->ErrorInfo."'");
		return true;
	}		
}

/**
 This function is used to get the Parent mail id
 It takes the input returnmodule as string and parentid as integer, returns the parent mailid as string. 
*/
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
			$parentid = $_REQUEST['contact_id'];
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

/**
 This function is used to get the Parent type and its Name
 It takes the input integer - crmid and returns the parent type and its name as string. 
*/
function getParentInfo($value)
{
	global $adb;
 	$parent_module = getSalesEntityType($value);
	if($parent_module == "Leads")
	{
		$sql = "select * from leaddetails where leadid=".$value;
		$result = $adb->query($sql);
		$first_name = $adb->query_result($result,0,"firstname");
		$last_name = $adb->query_result($result,0,"lastname");

		$parent_name = $last_name.' '.$first_name;
	}
	elseif($parent_module == "Accounts")
	{
		$sql = "select * from  account where accountid=".$value;
		$result = $adb->query($sql);
		$account_name = $adb->query_result($result,0,"accountname");

		$parent_name =$account_name;
	}
	elseif($parent_module == "Potentials")
	{
		$sql = "select * from  potential where potentialid=".$value;
		$result = $adb->query($sql);
		$potentialname = $adb->query_result($result,0,"potentialname");

		$parent_name =$potentialname;
	}
	  return $parent_module ." : ".$parent_name;
}
?>
