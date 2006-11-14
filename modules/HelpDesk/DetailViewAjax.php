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

require_once('include/logging.php');
require_once('modules/HelpDesk/HelpDesk.php');
require_once('include/database/PearDatabase.php');
global $adb;

$local_log =& LoggerManager::getLogger('HelpDeskAjax');

$ajaxaction = $_REQUEST["ajxaction"];
if($ajaxaction == "DETAILVIEW")
{
	$crmid = $_REQUEST["recordid"];
	$tablename = $_REQUEST["tableName"];
	$fieldname = $_REQUEST["fldName"];
	$fieldvalue = utf8RawUrlDecode($_REQUEST["fieldValue"]); 

	if($crmid != "")
	{
		$modObj = new HelpDesk();
		$modObj->retrieve_entity_info($crmid,"HelpDesk");
		
		//Added to avoid the comment save, when we edit other fields through ajax edit
		if($fieldname != 'comments')
			$modObj->column_fields['comments'] = '';

		$modObj->column_fields[$fieldname] = $fieldvalue;
		$modObj->id = $crmid;
		$modObj->mode = "edit";

		//Added to construct the update log for Ticket history
		$assigned_group_name = $_REQUEST['assigned_group_name'];
		$assigntype = $_REQUEST['assigntype'];

		$fldvalue = $modObj->constructUpdateLog(&$modObj, $modObj->mode, $assigned_group_name, $assigntype);
		$fldvalue = from_html($adb->formatString('vtiger_troubletickets','update_log',$fldvalue),($modObj->mode == 'edit')?true:false);
		
		$modObj->save("HelpDesk");
		
		//update the log information for ticket history
		$adb->query("update vtiger_troubletickets set update_log=$fldvalue where ticketid=".$modObj->id);
		
		if($modObj->id != "")
		{
			echo ":#:SUCCESS";
		}else
		{
			echo ":#:FAILURE";
		}   
	}else
	{
		echo ":#:FAILURE";
	}
}
?>
