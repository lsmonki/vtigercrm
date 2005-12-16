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
require_once('include/utils/utils.php');

global $log;
$db = new PearDatabase();
	$log->debug("the foldername is ".$folderName);
$folderName = $_REQUEST["foldername"];
$templateName = $_REQUEST["templatename"];
	  $log->debug("the templatename is ".$templateName);
$templateid = $_REQUEST["templateid"];
	  $log->debug("the templateid is ".$templateid);
$description = $_REQUEST["description"];
	  $log->debug("the description is ".$description);
$subject = $_REQUEST["subject"];
	  $log->debug("the subject is ".$subject);  
$body = $_REQUEST["body"];
	  $log->debug("the body is ".$body);  
if ($body !='')
{
	$body = to_html($body);
	  $log->info("the body value is set ");  
}
if(isset($templateid) && $templateid !='')
{
	$log->info("the templateid is set");  
	$sql = "update emailtemplates set foldername = '".$folderName."', templatename ='".$templateName."', subject ='".$subject."', description ='".$description."', body ='".$body."' where templateid =".$templateid;
	$adb->query($sql);
 
	$log->info("about to invoke the detailviewemailtemplate file");  
	header("Location:index.php?module=Users&action=detailviewemailtemplate&templateid=".$templateid);
}
else
{
	$sql = "insert into emailtemplates values ('". $folderName. "','".$templateName."','".$subject."','".$description."','".$body."',0,".$db->getUniqueID('emailtemplates').")";
	$adb->query($sql);

	 $log->info("added to the db the emailtemplate");
	header("Location:index.php?module=Users&action=listemailtemplates");
}
?>
