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

global $vtlog;
$db = new PearDatabase();
$folderName = $_REQUEST["foldername"];
	  $vtlog->logthis("the foldername is ".$folderName,'debug');  
$templateName = $_REQUEST["templatename"];
	  $vtlog->logthis("the templatename is ".$templateName,'debug');  
$templateid = $_REQUEST["templateid"];
	  $vtlog->logthis("the templateid is ".$templateid,'debug');  
$description = $_REQUEST["description"];
	  $vtlog->logthis("the description is ".$description,'debug');  
$subject = $_REQUEST["subject"];
	  $vtlog->logthis("the subject is ".$subject,'debug');  
$body = $_REQUEST["body"];
	  $vtlog->logthis("the body is ".$body,'debug');  
if ($body !='')
{
	$body = to_html($body);
	  $vtlog->logthis("the body value is set ",'info');  
}
if(isset($templateid) && $templateid !='')
{
	$vtlog->logthis("the templateid is set",'info');  
	$sql = "update emailtemplates set foldername = '".$folderName."', templatename ='".$templateName."', subject ='".$subject."', description ='".$description."', body ='".$body."' where templateid =".$templateid;
	$adb->query($sql);
 
	$vtlog->logthis("about to invoke the detailviewemailtemplate file",'info');  
	header("Location:index.php?module=Users&action=detailviewemailtemplate&templateid=".$templateid);
}
else
{
	$sql = "insert into emailtemplates values ('". $folderName. "','".$templateName."','".$subject."','".$description."','".$body."',0,".$db->getUniqueID('emailtemplates').")";
	$adb->query($sql);

	$vtlog->logthis("added to the db the emailtemplate",'info');  
	header("Location:index.php?module=Users&action=listemailtemplates");
}
?>
