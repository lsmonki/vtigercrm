<?php
require_once("include/database/PearDatabase.php");

$mail_server=$_REQUEST['mail_server'];
$mail_server_username=$_REQUEST['mail_server_username'];
$mail_server_password=$_REQUEST['mail_server_password'];

$sql="select * from systems";
$id=$adb->query_result($adb->query($sql),0,"id");

if($id=='')
{
	$id = $adb->getUniqueID("systems");
	$sql="insert into systems values(" .$id .",'".$_REQUEST['mail_server']."','".$_REQUEST['mail_server_username']."','".$_REQUEST['mail_server_password']."')";
}
else
	$sql="update systems set mail_server = '".$_REQUEST['mail_server']."', mail_server_username = '".$_REQUEST['mail_server_username']."', mail_server_password = '".$_REQUEST['mail_server_password']."'";

$adb->query($sql);

header("Location: index.php?module=Settings&action=index");
?>
