<?
require_once('include/database/PearDatabase.php');
global $adb;

if($_REQUEST['module']=='Users')
	$sql = "insert into salesmanactivityrel values (". $_REQUEST["entityid"] .",".$_REQUEST["parid"] .")";
else
	$sql = "insert into seactivityrel values (". $_REQUEST["entityid"] .",".$_REQUEST["parid"] .")";
$adb->query($sql);
 header("Location: index.php?action=DetailView&module=Emails&record=".$_REQUEST["parid"]);






?>
