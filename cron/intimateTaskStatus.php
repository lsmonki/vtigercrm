<?
require('send_mail.php');
require_once('../config.php');

$dbhost = $dbconfig['db_host_name'];
$dbuser =$dbconfig['db_user_name']; 
$dbpass = $dbconfig['db_password'];
$db = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
if (!$db) {
  die('Could not connect: ' . mysql_error());
}
else
{
  echo 'Successfully connected to database '.$dbconfig['db_name'];
}
$selecteddb = $dbconfig['db_name'];
mysql_select_db($selecteddb,$db);

$emailresult = mysql_query("SELECT email1 from users",$db);
$emailid = mysql_fetch_row($emailresult);
$emailaddress = $emailid[0];
$mailserveresult = mysql_query("SELECT server,server_username,server_password FROM systems",$db);
$mailrow = mysql_fetch_row($mailserveresult);
$mailserver = $mailrow[0];

$mailuname = $mailrow[1];
$mailpwd = $mailrow[1];

//query the notificationscheduler table and get data for those notifications which are active


$sql = "select active from notificationscheduler where schedulednotificationid=1";
$result = mysql_query($sql);

$activevalue = mysql_fetch_row($result);
if($activevalue[0] == 1)
{
//Delayed Tasks Notification

//get all those activities where the status is not completed even after the passing of 24 hours
$today = date("Ymd"); 
$result = mysql_query("select (activity.date_start +1) from activity where activity.status !='Completed' and ".$today." > (activity.date_start+1)",$db);
while ($myrow = mysql_fetch_row($result))
{
  $status=$myrow[0];
  if($status != 'Completed')
  {
	 sendmail($emailaddress,$emailaddress,"test mail","Not completed task",$mailserver,$mailuname,$mailpwd,"");	
  }
}
}

//Big Deal Alert
$sql = "select active from notificationscheduler where schedulednotificationid=2";
$result = mysql_query($sql);

$activevalue = mysql_fetch_row($result);
if($activevalue[0] == 1)
{
$result = mysql_query("SELECT sales_stage,amount FROM potential",$db);
while ($myrow = mysql_fetch_row($result))
{
  $amount=$myrow[1];
  $stage = $myrow[0];
  if($stage == 'Closed Won' &&  $amount > 10000)
  {
    
    sendmail($emailaddress,$emailaddress,"Big Deal Closed Successfully!","Time to Party! Big Deal Closed!!!!",$mailserver,$mailuname,$mailpwd,"");	
  }
}

}

//Pending tickets
$sql = "select active from notificationscheduler where schedulednotificationid=3";
$result = mysql_query($sql);

$activevalue = mysql_fetch_row($result);
if($activevalue[0] == 1)
{
$result = mysql_query("SELECT status,ticketid FROM troubletickets",$db);
while ($myrow = mysql_fetch_row($result))
{
  $status=$myrow[0];
  $ticketid = $myrow[1];
  if($status != 'Completed')
  {

    echo 'mail sent';
    sendmail($emailaddress,$emailaddress,"test mail","Ticket number ".$ticketid ." yet to be closed",$mailserver,$mailuname,$mailpwd,"");	
  }
}


}

//Too many tickets related to a particular account/company causing concern
$sql = "select active from notificationscheduler where schedulednotificationid=4";
$result = mysql_query($sql);

$activevalue = mysql_fetch_row($result);
if($activevalue[0] == 1)
{

$result = mysql_query("SELECT status,troubletickets.ticketid FROM troubletickets where status!='Completed'",$db);
while ($myrow = mysql_fetch_row($result))
{
  $status=$myrow[0];
  $ticketid = $myrow[1];
  echo 'mail sent';
  sendmail($emailaddress,$emailaddress,"Too many pending tickets","Too many pending tickets ".$ticketid ." too many pending tickets",$mailserver,$mailuname,$mailpwd,"");	
}

}

//Support Starting
$sql = "select active from notificationscheduler where schedulednotificationid=5";
$result = mysql_query($sql);

$activevalue = mysql_fetch_row($result);
if($activevalue[0] == 1)
{
$result = mysql_query("SELECT start_date FROM products",$db);
while ($myrow = mysql_fetch_row($result))
{
  $status=$myrow[0];
  sendmail($emailaddress,$emailaddress,"Support starting","Support Starting ".$ticketid ."Congratulations! Your support starts from today",$mailserver,$mailuname,$mailpwd,"");	
}

}

//Support ending
$sql = "select active from notificationscheduler where schedulednotificationid=6";
$result = mysql_query($sql);

$activevalue = mysql_fetch_row($result);
if($activevalue[0] == 1)
{

$result = mysql_query("SELECT expiry_date from products",$db);
while ($myrow = mysql_fetch_row($result))
{
  $status=$myrow[0];
  sendmail($emailaddress,$emailaddress,"Support Ending","Support Ending ".$ticketid ." Renew support please",$mailserver,$mailuname,$mailpwd,"");	
}

}


?>
