<?
require('cron/send_mail.php');
require_once('config.php');
require_once('include/database/PearDatabase.php');

$emailresult = $adb->query("SELECT email1 from users");
$emailid = $adb->fetch_array($emailresult);
$emailaddress = $emailid['email1'];
$mailserveresult = $adb->query("SELECT server,server_username,server_password FROM systems where server_type='email'");
$mailrow = $adb->fetch_array($mailserveresult);
$mailserver = $mailrow['server'];

$mailuname = $mailrow['server_username'];
$mailpwd = $mailrow['server_password'];

//query the notificationscheduler table and get data for those notifications which are active

$sql = "select active,notificationsubject,notificationbody from notificationscheduler where schedulednotificationid=1";
$result_main = $adb->query($sql);
$activevalue = $adb->fetch_array($result_main);
if($activevalue['active'] == 1)
{
//Delayed Tasks Notification

//get all those activities where the status is not completed even after the passing of 24 hours
$today = date("Ymd"); 
$result = $adb->query("select (activity.date_start +1) from activity where activity.status !='Completed' and ".$today." > (activity.date_start+1)");
while ($myrow = $adb->fetch_array($result))
{
  $status=$myrow[0];
  if($status != 'Completed')
  {
	 $subject = $adb->query_result($result_main,0,'notificationsubject');
	 $content = nl2br($adb->query_result($result_main,0,'notificationbody'));
	 sendmail($emailaddress,$emailaddress,$subject,$content,$mailserver,$mailuname,$mailpwd,"");	
  }
}
}

//Big Deal Alert
$sql = "select active,notificationsubject,notificationbody from notificationscheduler where schedulednotificationid=2";
$result_main = $adb->query($sql);

$activevalue = $adb->fetch_array($result_main);
if($activevalue['active'] == 1)
{
$result = $adb->query("SELECT sales_stage,amount FROM potential");
while ($myrow = $adb->fetch_array($result))
{
  $amount=$myrow['amount'];
  $stage = $myrow['sales_stage'];
  if($stage == 'Closed Won' &&  $amount > 10000)
  {
	 $subject = $adb->query_result($result_main,0,'notificationsubject');
	 $content = nl2br($adb->query_result($result_main,0,'notificationbody'));
    	 sendmail($emailaddress,$emailaddress,$subject,$content,$mailserver,$mailuname,$mailpwd,"");	
  }
}

}

//Pending tickets
$sql = "select active,notificationsubject,notificationbody from notificationscheduler where schedulednotificationid=3";
$result_main = $adb->query($sql);

$activevalue = $adb->fetch_array($result_main);
if($activevalue['active'] == 1)
{
$result = $adb->query("SELECT status,ticketid FROM troubletickets");

while ($myrow = $adb->fetch_array($result))
{
  $status=$myrow['status'];
  $ticketid = $myrow['ticketid'];
  if($status != 'Completed')
  {

	 $subject = $adb->query_result($result_main,0,'notificationsubject');
	 $content = nl2br($adb->query_result($result_main,0,'notificationbody'));
    	 sendmail($emailaddress,$emailaddress,$subject,$content.".\n\n Ticket number ".$ticketid ." yet to be closed",$mailserver,$mailuname,$mailpwd,"");	
  }
}


}

//Too many tickets related to a particular account/company causing concern
$sql = "select active,notificationsubject,notificationbody from notificationscheduler where schedulednotificationid=4";
$result_main = $adb->query($sql);

$activevalue = $adb->fetch_array($result_main);
if($activevalue['Active'] == 1)
{

$result = $adb->query("SELECT status,troubletickets.ticketid as ticketid FROM troubletickets where status!='Completed'");

while ($myrow = $adb->fetch_array($result))
{
  $status=$myrow['status'];
  $ticketid = $myrow['ticketid'];
  	
  $subject = $adb->query_result($result_main,0,'notificationsubject');
  $content = nl2br($adb->query_result($result_main,0,'notificationbody'));
  sendmail($emailaddress,$emailaddress,$subject,$content.".\n\n Too many pending tickets ".$ticketid ." too many pending tickets",$mailserver,$mailuname,$mailpwd,"");	
}

}

//Support Starting
$sql = "select active,notificationsubject,notificationbody from notificationscheduler where schedulednotificationid=5";
$result_main = $adb->query($sql);

$activevalue = $adb->fetch_array($result_main);
if($activevalue['Active'] == 1)
{
$result = $adb->query("SELECT start_date FROM products");
while ($myrow = $adb->fetch_array($result))
{
  $start_date=$myrow['start_date'];
  $subject = $adb->query_result($result_main,0,'notificationsubject');
  $content = nl2br($adb->query_result($result_main,0,'notificationbody'));
  sendmail($emailaddress,$emailaddress,$subject,$content.".\n\n Support starting","Support Starting ".$start_date."Congratulations! Your support starts from today",$mailserver,$mailuname,$mailpwd,"");	
}

}

//Support ending
$sql = "select active,notificationsubject,notificationbody from notificationscheduler where schedulednotificationid=6";
$result_main = $adb->query($sql);

$activevalue = $adb->fetch_array($result_main);
if($activevalue['active'] == 1)
{

$result = $adb->query("SELECT expiry_date from products");
while ($myrow = $adb->fetch_array($result))
{
  $expiry_date=$myrow['expiry_date'];
  $subject = $adb->query_result($result_main,0,'notificationsubject');
  $content = nl2br($adb->query_result($result_main,0,'notificationbody'));
  sendmail($emailaddress,$emailaddress,$subject,$content.".\n\n Support Ending ".$expiry_date." Renew support please",$mailserver,$mailuname,$mailpwd,"");	
}

}


?>
