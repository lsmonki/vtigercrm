<?php

global $adb;

if($_REQUEST['idstring'] != '')
	$idlist = $_REQUEST['idstring'];
elseif($_REQUEST['idlist'] != '')
	$idlist = $_REQUEST['idlist'];

$selected_array = explode(";",$idlist);
foreach($selected_array as $account_id)
{
	if($account_id != '')
	{
		$query = "update mail_accounts set status=0 where account_id=".$account_id;
		$adb->query($query);
	}
}

header("Location:index.php?module=Settings&action=ListMailAccount");

?>
