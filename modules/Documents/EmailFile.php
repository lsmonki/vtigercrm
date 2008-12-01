<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/

require_once('include/database/PearDatabase.php');

global $adb;

$fileid = $_REQUEST['record'];

$dbQuery = "select filename,folderid,filepath from vtiger_notes where notesid= ?";
$result = $adb->pquery($dbQuery,array($fileid));

$folderid = $adb->query_result($result,0,'folderid');
$filepath = $adb->query_result($result,0,'filepath');
$filename = $adb->query_result($result,0,'filename');

$fileinattachments = $root_directory.$filepath.$fileid.'_'.$folderid.'_'.$filename;
if(!file($fileinattachments))$fileinattachments = $root_directory.$filepath.$fileid."_".$filename;

$newfileinstorage = $root_directory.'/storage/'.$filename;

copy($fileinattachments,$newfileinstorage);

echo "<script>window.history.back();</script>";
exit();
?>
