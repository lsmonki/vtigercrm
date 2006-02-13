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

$fileid = $_REQUEST['fileid'];

//$dbQuery = "SELECT * from seattachmentsrel where crmid = '" .$fileid ."'";
//$attachmentsid = $adb->query_result($adb->query($dbQuery),0,'attachmentsid');
$attachmentsid = $fileid;

$returnmodule=$_REQUEST['return_module'];

if($_REQUEST['activity_type']=='Attachments')
	$attachmentsid=$fileid;

$dbQuery = "SELECT * FROM attachments ";
$dbQuery .= "WHERE attachmentsid = " .$attachmentsid ;

$result = $adb->query($dbQuery) or die("Couldn't get file list");
if($adb->num_rows($result) == 1)
{
$fileType = @$adb->query_result($result, 0, "type");
$name = @$adb->query_result($result, 0, "name");
//echo 'filetype is ' .$fileType;
$fileContent = @$adb->query_result($result, 0, "attachmentcontents");
$size = @$adb->query_result($result, 0, "attachmentsize");
header("Content-type: $fileType");
//header("Content-length: $size");
header("Cache-Control: private");
header("Content-Disposition: attachment; filename=$name");
header("Content-Description: PHP Generated Data");
echo base64_decode($fileContent);
}
else
{
echo "Record doesn't exist.";
}
?>

