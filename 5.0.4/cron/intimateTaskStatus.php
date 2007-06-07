<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
********************************************************************************/
ini_set("include_path", "../");

require('send_mail.php');
require_once('config.php');
require_once('include/utils/utils.php');
require_once('include/language/en_us.lang.php');
global $app_strings;
// Email Setup
$emailresult = $adb->query("SELECT email1 from vtiger_users");
$emailid = $adb->fetch_array($emailresult);
$emailaddress = $emailid[0];
$mailserveresult = $adb->query("SELECT server,server_username,server_password FROM vtiger_systems where server_type = 'email'");
$mailrow = $adb->fetch_array($mailserveresult);
$mailserver = $mailrow[0];
$mailuname = $mailrow[1];
$mailpwd = $mailrow[1];
// End Email Setup


//query the vtiger_notificationscheduler vtiger_table and get data for those notifications which are active
$sql = "select active from vtiger_notificationscheduler where schedulednotificationid=1";
$result = $adb->query($sql);

$activevalue = $adb->fetch_array($result);

if($activevalue[0] == 1)
{
//Delayed Tasks Notification

//get all those activities where the status is not completed even after the passing of 24 hours
$today = date("Ymd"); 
$result = $adb->query("select (vtiger_activity.date_start +1) from vtiger_activity where vtiger_activity.status <> 'Completed' and ".$today." > (vtiger_activity.date_start+1)",$db);

while ($myrow = $adb->fetch_array($result))
{
  $status=$myrow[0];
  if($status != 'Completed')
  {
	 sendmail($emailaddress,$emailaddress,$app_strings['Task_Not_completed'],$app_strings['Dear_Admin_tasks_not_been_completed'],$mailserver,$mailuname,$mailpwd,"");	
  }
}
}

//Big Deal Alert
$sql = "select active from vtiger_notificationscheduler where schedulednotificationid=2";
$result = $adb->query($sql);

$activevalue = $adb->fetch_array($result);
if($activevalue[0] == 1)
{
$result = $adb->query("SELECT sales_stage,amount FROM vtiger_potential",$db);
while ($myrow = $adb->fetch_array($result))
{
  $amount=$myrow[1];
  $stage = $myrow[0];
  if($stage == 'Closed Won' &&  $amount > 10000)
  {
    sendmail($emailaddress,$emailaddress,$app_strings['Big_Deal_Closed_Successfully'],$app_strings['Dear_Team_Time_to_Party'],$mailserver,$mailuname,$mailpwd,"");	
  }
}

}

//Pending tickets
$sql = "select active from vtiger_notificationscheduler where schedulednotificationid=3";
$result = $adb->query($sql);

$activevalue = $adb->fetch_array($result);
if($activevalue[0] == 1)
{
$result = $adb->query("SELECT vtiger_troubletickets.status,ticketid FROM vtiger_troubletickets INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid=vtiger_troubletickets.ticketid WHERE vtiger_crmentity.deleted='0' AND vtiger_troubletickets.status <> 'Completed' AND vtiger_troubletickets.status <> 'Closed' ",$db);

while ($myrow = $adb->fetch_array($result))
{
  $status=$myrow[0];
  $ticketid = $myrow[1];
  if($status != "Completed" || $status != "Closed")
  {
    sendmail($emailaddress,$emailaddress,$app_strings['Pending_Ticket_notification'],$app_strings['Kind_Attention'].$ticketid .$app_strings['Thank_You_HelpDesk'],$mailserver,$mailuname,$mailpwd,"");	
  }
}


}

//Too many tickets related to a particular vtiger_account/company causing concern
$sql = "select active from vtiger_notificationscheduler where schedulednotificationid=4";
$result = $adb->query($sql);

$activevalue = $adb->fetch_array($result);
if($activevalue[0] == 1)
{

$result = $adb->query("SELECT status,vtiger_troubletickets.ticketid FROM vtiger_troubletickets where status <> 'Completed' AND status <> 'Closed'",$db);
while ($myrow = $adb->fetch_array($result))
{
  $status=$myrow[0];
  $ticketid = $myrow[1];
  sendmail($emailaddress,$emailaddress,$app_strings['Too_many_pending_tickets'],$app_strings['Dear_Admin_too_ many_tickets_pending'],$mailserver,$mailuname,$mailpwd,"");	
}

}

//Support Starting
$sql = "select active from vtiger_notificationscheduler where schedulednotificationid=5";
$result = $adb->query($sql);

$activevalue = $adb->fetch_array($result);
if($activevalue[0] == 1)
{
$result = $adb->query("SELECT productname FROM vtiger_products where start_date like '".date('Y-m-d')."%'",$db);
while ($myrow = $adb->fetch_array($result))
{
  $productname=$myrow[0];
  sendmail($emailaddress,$emailaddress,$app_strings['Support_starting'],$app_strings['Hello_Support'].$productname ."\n ".$app_strings['Congratulations'],$mailserver,$mailuname,$mailpwd,"");	
}

}

//Support ending
$sql = "select active from vtiger_notificationscheduler where schedulednotificationid=6";
$result = $adb->query($sql);

$activevalue = $adb->fetch_array($result);
if($activevalue[0] == 1)
{
	$result = $adb->query("SELECT productname from vtiger_products where expiry_date like '".date('Y-m-d')."%'",$db);
	while ($myrow = $adb->fetch_array($result))
	{
		$productname=$myrow[0];
		sendmail($emailaddress,$emailaddress,$app_strings['Support_Ending_Subject'],$app_strings['Support_Ending_Content'].$productname.$app_strings['kindly_renew'],$mailserver,$mailuname,$mailpwd,"");	
	}
}

?>
