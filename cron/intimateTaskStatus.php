<?
ini_set("include_path", "../:.");

require('send_mail.php');
require_once('config.php');
require_once('include/utils/utils.php');

// Email Setup
$emailresult = $adb->query("SELECT email1 from vtiger_users");
$emailid = $adb->fetch_array($emailresult);
$emailaddress = $emailid[0];
$mailserveresult = $adb->query("SELECT server,server_username,server_password FROM vtiger_systems");
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

" > (vtiger_activity.date_start+1)";
while ($myrow = $adb->fetch_array($result))
{
  $status=$myrow[0];
  if($status != 'Completed')
  {
	 sendmail($emailaddress,$emailaddress,"Task Not completed","Dear Admin,<br><br> Please note that there are certain tasks in the system which have not been completed even after 24hours of their existence<br> Thank You<br>HelpDesk Team<br>",$mailserver,$mailuname,$mailpwd,"");	
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
    
    sendmail($emailaddress,$emailaddress,"Big Deal Closed Successfully!","Dear Team,<br>Congratulations!Time to Party! <br>We closed a deal worth more than 10000!!!!<br> Time to hit the dance floor!<br>",$mailserver,$mailuname,$mailpwd,"");	
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
    sendmail($emailaddress,$emailaddress,"Pending Ticket notification","Dear Admin,<br> This is to bring to your kind attention that ticket number ".$ticketid ." is yet to be closed<br> Thank You,<br> HelpDesk Team<br>",$mailserver,$mailuname,$mailpwd,"");	
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
  sendmail($emailaddress,$emailaddress,"Too many pending tickets","Dear Admin,<br> This is to bring to your notice that there are too many tickets pending. Kindly take the necessary action required for addressing the same<br><br> Thanks and Regards,<br> HelpDesk Team<br>",$mailserver,$mailuname,$mailpwd,"");	
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
  sendmail($emailaddress,$emailaddress,"Support starting","Hello! Support Starts for ".$productname ."\n Congratulations! Your support starts from today",$mailserver,$mailuname,$mailpwd,"");	
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
  sendmail($emailaddress,$emailaddress,"Support Ending","Dear Admin,<br> This is to inform you that the support for ".$productname ."\n ends shortly. Kindly renew your support please<br>Regards,<br>HelpDesk Team<br>",$mailserver,$mailuname,$mailpwd,"");	
}

}
?>
