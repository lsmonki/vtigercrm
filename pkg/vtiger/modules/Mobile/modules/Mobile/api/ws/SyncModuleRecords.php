<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
include_once dirname(__FILE__) . '/SaveRecord.php';

include_once 'include/Webservices/GetUpdates.php';

class Mobile_WS_SyncModuleRecords extends Mobile_WS_SaveRecord {
	
	function process(Mobile_API_Request $request) {
		$current_user = $this->getActiveUser();

		$module = $request->get('module');
		$lastSyncTime = $request->get('lastSyncTime');
		if (empty($lastSyncTime)) $lastSyncTime = date('U', mktime(0, 0, 0, 1, 1, 1970));
		
		$syncResult = vtws_sync($lastSyncTime, $module, $current_user);
		
		$lastModifiedTime=$syncResult['lastModifiedTime'];
		$deletedRecords = $syncResult['deleted'];
		$updatedRecords = array();
		
		if(!empty($syncResult['updated'])) {
		 	$describeInfo = vtws_describe($module, $current_user);
		 	$this->cacheDescribeInfo($describeInfo);

		 	foreach($syncResult['updated'] as $updatedRecord) {
		 		$this->resolveRecordValues($updatedRecord, $current_user);
		 		$transformedRecord = $this->transformRecordWithGrouping($updatedRecord, $module);
		 		// Update entity fieldnames
		 		$transformedRecord['labelFields'] = $this->cachedEntityFieldnames($module);
		 		
		 		$updatedRecords[] = $transformedRecord;
		 	}
		}
		
		$result = array('sync' => array());
		$result['sync']['updated'] = $updatedRecords;
		$result['sync']['deleted'] = $deletedRecords;
		$result['sync']['lastModifiedTime'] = $lastModifiedTime; 
		
		$response = new Mobile_API_Response();
		$response->setResult($result);
		return $response;
	}
}