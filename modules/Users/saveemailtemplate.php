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

$folderName = $_REQUEST["foldername"];
$templateName = $_REQUEST["templatename"];
$description = $_REQUEST["description"];
$subject = $_REQUEST["subject"];
$body = $_REQUEST["body"];

$sql = "insert into emailtemplates values ('". $folderName. "','".$templateName."','".$description."','".$subject."','".$body."',0)";
$adb->query($sql);

header("Location:index.php?module=Users&action=listemailtemplates");

?>
