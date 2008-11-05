<?php
global $currentModule;

require_once("modules/$currentModule/$currentModule.php");

$ajaxaction = $_REQUEST['ajxaction'];
if($ajaxaction == 'DETAILVIEW')
{
	$crmid = $_REQUEST['recordid'];
	$tablename = $_REQUEST['tableName'];
	$fieldname = $_REQUEST['fldName'];
	$fieldvalue = utf8RawUrlDecode($_REQUEST['fieldValue']); 
	if($crmid != '')
	{
		$modObj = new $currentModule();
		$modObj->retrieve_entity_info($crmid, $currentModule);
		$modObj->column_fields[$fieldname] = $fieldvalue;
		$modObj->id = $crmid;
		$modObj->mode = 'edit';
		$modObj->save($currentModule);
		if($modObj->id != '')
		{
			echo ':#:SUCCESS';
		}else
		{
			echo ':#:FAILURE';
		}   
	}else
	{
		echo ':#:FAILURE';
	}
}
?>
