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



function DelImage($id)
{
		
	global $adb;
	$query="delete from vtiger_attachments where attachmentsid=(select attachmentsid from vtiger_seattachmentsrel where crmid=".$id.")";
	$adb->query($query);

	$query="delete from vtiger_seattachmentsrel where crmid=".$id;
	$adb->query($query);
}

function DelAttachment($id)
{
	global $adb;
	$selresult = $adb->query("select name,path from vtiger_attachments where attachmentsid=$id");
	unlink($adb->query_result($selresult,0,'path').$id."_".$adb->query_result($selresult,0,'name'));
	$query="delete from vtiger_seattachmentsrel where attachmentsid=".$id;
	$adb->query($query);
	$query="delete from vtiger_attachments where attachmentsid=".$id.")";
	$adb->query($query);

}
$id = $_REQUEST["recordid"];
if(isset($_REQUEST["attachmodule"]) && $_REQUEST["attachmodule"]=='Emails')
{
	DelAttachment($id);
}
else
{
	DelImage($id);
}
echo 'SUCESS';
?>
