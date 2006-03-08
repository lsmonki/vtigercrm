<?php
/*
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program; if not, write to the Free Software
 *   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 *   This file was derived from the SugarCRM<->Asterisk integration done
 *   by the Asterisk@Home project.  Most of the script is re-wrote but the
 *   original author retains copyright over his/her material
 *
 *   (c) 2005 Matthew Brichacek <mmbrich@fosslabs.com>
 */


include_once ("include/phpagi/phpagi-asmanager.php");
include_once ("include/database/PearDatabase.php");

$AMP=0;
$context="local";
$db = new PearDatabase();
$PhoneNum = "$_REQUEST[number]";
$PhoneCall = ereg_replace("[ ()-]+", "", $PhoneNum);

session_start();
### Get extension from DB
$ID = $_SESSION["authenticated_user_id"];
$sql  = "SELECT extension FROM users WHERE ID = '$ID' ";
$result = $db->query($sql, true, "Error:");
$Extension = $db->query_result($result,0,"extension");

if(!$Extension)
{
?>
	<html><head><title>CallMe</title>
	</head><body><font color=red>ERROR: You need to add an extension to your CRM user account so we know where to send the call.</font></body></html>

<?php
  	exit;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title>CallMe</title>
	<meta http-equiv="Content-Type" content="text/html">

</head>
<body>
<center>
<img src="/images/animlogo.gif">
<?

if ($AMP) {
	$as = new AGI_AsteriskManager();
	// && CONNECTING
	$res = $as->connect();
	if (!$res){ echo 'Error connection to the Asterisk manager!'; exit();}
	$device=$as->database_get("AMPUSER",$Extension."/device");
	if (preg_match("/\&/i", $device) && !($_REQUEST[choosedevice])) {
		$devices=explode("&", $device);
		$count=count($devices);
		echo "<br><b>I show you as being associated to more than one device.  Please choose the device you would like to place this call from</b><br>";
  		echo "<form method=POST action=callme.php><select name=fromdevice>";
  		for($j=0;$j<$count;$j++) {
			echo "<option value=\"$devices[$j]\" /> $devices[$j]";
  		}
  		echo "</select>";
  		echo "<input type=hidden name=number value=$PhoneNum><br>";
  		echo "<input type=submit name=choosedevice value=\"Select\">";
  		echo "</form>";
  		exit;
	} else if($_REQUEST[choosedevice]) {
  		$from=$as->database_get("DEVICE",$_REQUEST[fromdevice]."/dial");
	} else {
  		$from=$as->database_get("DEVICE",$device."/dial");
	}
	// && DISCONNECTING	
	$as->disconnect();
} else {
		if (preg_match("/\//i", $Extension)) 
		{
                	$tmp = explode("/",$Extension);                 
			$from=$tmp[1];
        	} 
		else 
		{                
			$from=$Extension;
        	}	
      }
?>

<br><b>Please pick up your phone</b><br>
<? 
flush();
$channel = $from;
$exten = $PhoneCall;
$context = $context;
$priority = '1';
$timeout = '';
$callerid = '';
$variable = '';
$account = '';
$application = '';
$data = '';

$as = new AGI_AsteriskManager();
// && CONNECTING
$res = $as->connect();
if (!$res){ echo 'Error connection to the Asterisk manager!'; exit();}

echo "channel:".$channel;
echo "exten:".$exten;
echo "context:".$context;

	
$res = $as->Originate ($channel, $exten, $context, $priority, $timeout, $callerid, $variable, $account, $application, $data);
	
// && DISCONNECTING	
$as->disconnect();

$failed=$res['Message'];
$failmsg="/Failed/i";
if (! preg_match($failmsg, $failed)) { ?>
	<h1>Please be patient...</H1><hr>
	<b>while I connect you to <?=$PhoneNum;?></b><br>
	<a href="#" onclick="if(window.opener){window.close()}else{alert('This page is not being displayed as a popup.')}">Close Window</a>
	</center>
	</body>
	</html>
<? } else { ?>
	<br>
	<font color=red><b><?=$res['Message']?></b></font><br>
	<a href="#" onclick="if(window.opener){window.close()}else{alert('This page is not being displayed as a popup.')}">Close Window</a>
	</center>
	</body>
	</html>
<? } ?>
