<?php
startCall();

/**
 * this function starts the call, it writes the caller and called information to database where it is picked up from
 */
function startCall(){	
	global $current_user, $adb;
	require_once 'include/utils/utils.php';
	
	$id = $current_user->id;
	$number = $_REQUEST['number'];
	
	$result = $adb->query("select * from vtiger_asteriskextensions where userid=".$current_user->id);
	$extension = $adb->query_result($result, 0, "asterisk_extension");

	$adb->query("delete from vtiger_asteriskoutgoingcalls");
	$adb->query("insert into vtiger_asteriskoutgoingcalls values ('$id','$extension', '$number')");
	
	addToCallHistory($extension, $extension, $number, "outgoing", $adb);
}
?>
