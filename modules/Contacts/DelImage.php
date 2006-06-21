<?php

	function DelImage($id)
	{
		global $adb;
		$query="delete from vtiger_attachments where attachmentsid=(select attachmentsid from seattachmentsrel where crmid=".$id.")";
		$adb->query($query);

		$query="delete from vtiger_seattachmentsrel where crmid=".$id;
		$adb->query($query);
	}

$id = $_REQUEST["recordid"];

DelImage($id);
echo '';















?>
