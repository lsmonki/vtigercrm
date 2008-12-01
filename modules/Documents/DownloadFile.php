<?php
/********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
* 
 ********************************************************************************/

require_once('config.php');
require_once('include/database/PearDatabase.php');

global $adb;
global $fileId;
global $current_user;


$fileid = $_REQUEST['fileid'];
$folderid = $_REQUEST['folderid'];

$returnmodule='Documents';

$dbQuery = "SELECT * FROM vtiger_notes WHERE notesid = ? and folderid= ?";
$result = $adb->pquery($dbQuery,array($fileid,$folderid)) or die("Couldn't get file list");
if($adb->num_rows($result) == 1)
{
	$fileType = @$adb->query_result($result, 0, "filetype");
	$name = @$adb->query_result($result, 0, "filename");
	$name = html_entity_decode($name, ENT_QUOTES, $default_charset);
	$filepath = @$adb->query_result($result, 0, "filepath");

	$saved_filename = $fileid."_".$folderid."_".$name;
	if(!file($filepath.$saved_filename))$saved_filename = $fileid."_".$name;
	$filesize = filesize($filepath.$saved_filename);
	if(!fopen($filepath.$saved_filename, "r"))
	{
		echo 'unable to open file';
		$log->debug('Unable to open file');
	}
	else
	{
		$fileContent = fread(fopen($filepath.$saved_filename, "r"), $filesize);
	}
	if($fileContent != '')
	{
		$log->debug('About to update download count');
		$sql = "select filedownloadcount from vtiger_notes where notesid= ?";
		$download_count = $adb->query_result($adb->pquery($sql,array($fileid)),0,'filedownloadcount') + 1;
		$sql="update vtiger_notes set filedownloadcount= ? where notesid= ?";
		$res=$adb->pquery($sql,array($download_count,$fileid));	
		
		/*$query="select max(downloadid) from vtiger_dldhistory";
		$downloadid=$adb->query_result($adb->pquery($query,array()),0,'max(downloadid)')+1;
		$usip=$_SERVER['REMOTE_ADDR'];
		$date1=date('Y-m-d H:i:s');
		$sqldldhis="insert into vtiger_dldhistory (downloadid,dldfileid,userid,ipaddress,dateTime) values(?,?,?,?,?)";
		$res=$adb->pquery($sqldldhis,array($downloadid,$fileid,$current_user->id,$usip,$date1));*/
	}

	header("Content-type: $fileType");
	header("Content-length: $filesize");
	header("Cache-Control: private");
	header("Content-Disposition: attachment; filename=$name");
	header("Content-Description: PHP Generated Data");
	echo $fileContent;
}
else
{
	echo "Record doesn't exist.";
}
?>
