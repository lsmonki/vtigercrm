<?php
/**
 * Created on 10-Oct-08
 * this file saves the notebook contents to database
 */
echo SaveNotebookContents();

function SaveNotebookContents(){
	global $adb,$current_user;
	
	$contents = $_REQUEST['contents'];
	
	$sql = "update vtiger_notebook_contents set contents=? where userid=?";
	$adb->pquery($sql, array($contents, $current_user->id));
	return true;
}
?>
