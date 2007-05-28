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

$id=$_REQUEST['record'];

$sql = "delete from vtiger_seattachmentsrel where attachmentsid ='".$id."'";
$adb->query($sql);

$sql = "delete from vtiger_attachments where attachmentsid ='".$id."'";
$adb->query($sql);

header("Location:index.php?module=".$_REQUEST['return_module']."&action=".$_REQUEST['return_action']."&record=".$_REQUEST['return_id']);


?>
