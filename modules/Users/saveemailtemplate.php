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
require_once('include/utils.php');

$db = new PearDatabase();
$folderName = $_REQUEST["foldername"];
$templateName = $_REQUEST["templatename"];
$templateid = $_REQUEST["templateid"];
$description = $_REQUEST["description"];
$subject = $_REQUEST["subject"];
$body = $_REQUEST["body"];
if ($body !='')
$body = to_html($body);
if($templateid !='')
{
	$sql = "update emailtemplates set foldername = '".$folderName."', templatename ='".$templateName."', subject ='".$subject."', description ='".$description."', body ='".$body."' where templateid =".$templateid;
	$adb->query($sql);

	header("Location:index.php?module=Users&action=detailviewemailtemplate&templateid=".$templateid);
}
else
{
	$sql = "insert into emailtemplates values ('". $folderName. "','".$templateName."','".$subject."','".$description."','".$body."',0,".$db->getUniqueID('emailtemplates').")";
	$adb->query($sql);

	header("Location:index.php?module=Users&action=listemailtemplates");
}
?>
