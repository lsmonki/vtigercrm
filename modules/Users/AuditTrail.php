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

include_once('config.php');
require_once('include/logging.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/database/PearDatabase.php');

class AuditTrail{

	var $log;
	var $db;

	var $auditid;
	var $userid;
	var $module;
	var $action;
	var $recordid;
	var $actiondate;

	var $module_name = "Users";
	var $table_name = "vtiger_audit_trial";
	
	var $object_name = "AuditTrail";	
	
	var $new_schema = true;
	
	function AuditTrail() {
		$this->log = LoggerManager::getLogger('audit_trial');
		$this->db = new PearDatabase();
	}
	
	var $sortby_fields = Array('module', 'action', 'actiondate', 'recordid');	 

		// This is the list of vtiger_fields that are in the lists.
	var $list_fields = Array(
			'Module'=>Array('vtiger_audit_trial'=>'module'), 
			'Action'=>Array('vtiger_audit_trial'=>'action'), 
			'Record'=>Array('vtiger_audit_trial'=>'recordid'),
		        'Action Date'=>Array('vtiger_audit_trial'=>'actiondate'), 
		);	
	
	var $list_fields_name = Array(
			'Module'=>'module', 
			'Action'=>'action', 
			'Record'=>'recordid',
		        'Action Date'=>'actiondate',
		);	
		
	var $default_order_by = "actiondate";
	var $default_sort_order = 'DESC';

	function getAuditTrailHeader()
	{
		global $app_strings;
		
		$header_array = array($app_strings['LBL_MODULE'], $app_strings['LBL_ACTION'], $app_strings['LBL_RECORD_ID'], $app_strings['LBL_ACTION_DATE']);

		return $header_array;
		
	}

	function getAuditTrailEntries($userid, $navigation_array, $sorder='', $orderby='')
	{
		global $adb, $current_user;
		
		if($sorder != '' && $order_by != '')
			$list_query = "Select * from vtiger_audit_trial where userid =".$userid." order by ".$order_by." ".$sorder; 
		else
			$list_query = "Select * from vtiger_audit_trial where userid =".$userid." order by ".$this->default_order_by." ".$this->default_sort_order;
	
		$result = $adb->query($list_query);
		$entries_list = array();

		for($i = $navigation_array['start']; $i <= $navigation_array['end_val']; $i++)
		{
			$entries = array();
			$userid = $adb->query_result($result, $i-1, 'userid');
		
			$entries[] = $adb->query_result($result, $i-1, 'module');
			$entries[] = $adb->query_result($result, $i-1, 'action');
			$entries[] = $adb->query_result($result, $i-1, 'recordid');
			$entries[] = $adb->query_result($result, $i-1, 'actiondate');
			
			$entries_list[] = $entries;
		}
		return $entries_list;	
	}
	
}


?>
