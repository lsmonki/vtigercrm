<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Vtiger_ExportData_Action extends Vtiger_Mass_Action {

	function checkPermission(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);

		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPriviligesModel->hasModuleActionPermission($moduleModel->getId(), 'Export')) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

	/**
	 * Function is called by the controller
	 * @param Vtiger_Request $request
	 */
	function process(Vtiger_Request $request) {
		$this->ExportData($request);
	}

	private $moduleInstance;
	private $focus;

	/**
	 * Function exports the data based on the mode
	 * @param Vtiger_Request $request
	 */
	function ExportData(Vtiger_Request $request) {
		$db = PearDatabase::getInstance();
		$moduleName = $request->get('source_module');

		$this->moduleInstance = Vtiger_Module_Model::getInstance($moduleName);
		$this->moduleFieldInstances = $this->moduleInstance->getFields();

		$query = $this->getExportQuery($request);
		$result = $db->pquery($query, array());

		$headers = array();
		//Query generator set this when generating the query
		if(!empty($this->accessibleFields)) {
			$accessiblePresenceValue = array(0,2);
			foreach($this->accessibleFields as $fieldName) {
				$fieldModel = $this->moduleFieldInstances[$fieldName];
				// Check added as querygenerator is not checking this for admin users
				$presence = $fieldModel->get('presence');
				if(in_array($presence, $accessiblePresenceValue)) {
					$headers[] = $fieldModel->get('label');
				}
			}
		} else {
			foreach($this->moduleFieldInstances as $field) $headers[] = $field->get('label');
		}
		$translatedHeaders = array();
		foreach($headers as $header) $translatedHeaders[] = vtranslate($header, $moduleName);

		$this->focus = CRMEntity::getInstance($moduleName);

		$entries = array();
		for($j=0; $j<$db->num_rows($result); $j++) {
			$entries[] = $this->sanitizeValues($db->fetchByAssoc($result, $j));
		}

		$this->output($request, $translatedHeaders, $entries);
	}

	/**
	 * Function that generates Export Query based on the mode
	 * @param Vtiger_Request $request
	 * @return <String> export query
	 */
	function getExportQuery(Vtiger_Request $request) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$mode = $request->getMode();
		$cvId = $request->get('viewname');
		$moduleName = $request->get('source_module');

		$queryGenerator = new QueryGenerator($moduleName, $currentUser);
		$queryGenerator->initForCustomViewById($cvId);
		$fieldInstances = $this->moduleFieldInstances;

		foreach($fieldInstances as $field) $fields[] = $field->getName();
		$queryGenerator->setFields($fields);
		$query = $queryGenerator->getQuery();
		$this->accessibleFields = $queryGenerator->getFields();

		switch($mode) {
			case 'ExportAllData' :	return $query;
									break;

			case 'ExportCurrentPage' :	$pagingModel = new Vtiger_Paging_Model();
										$limit = $pagingModel->getPageLimit();

										$currentPage = $request->get('page');
										if(empty($currentPage)) $currentPage = 1;

										$currentPageStart = ($currentPage - 1) * $limit;
										if ($currentPageStart < 0) $currentPageStart = 0;
										$query .= ' LIMIT '.$currentPageStart.','.$limit;

										return $query;
										break;

			case 'ExportSelectedRecords' :	$idList = $this->getRecordsListFromRequest($request);
											$baseTable = $this->moduleInstance->get('basetable');
											$baseTableColumnId = $this->moduleInstance->get('basetableid');
											if(!empty($idList)) {
												if(!empty($baseTable) && !empty($baseTableColumnId)) {
													$idList = implode(',' , $idList);
													$query .= ' AND '.$baseTable.'.'.$baseTableColumnId.' IN ('.$idList.')';
												}
											} else {
												$query .= ' AND '.$baseTable.'.'.$baseTableColumnId.' NOT IN ('.implode(',',$request->get('excluded_ids')).')';
											}
											return $query;
											break;


			default :	return $query;
						break;
		}
	}

	/**
	 * Function returns the export type - This can be extended to support different file exports
	 * @param Vtiger_Request $request
	 * @return <String>
	 */
	function getExportContentType(Vtiger_Request $request) {
		$type = $request->get('export_type');
		if(empty($type)) {
			return 'text/csv';
		}
	}

	/**
	 * Function that create the exported file
	 * @param Vtiger_Request $request
	 * @param <Array> $headers - output file header
	 * @param <Array> $entries - outfput file data
	 */
	function output($request, $headers, $entries) {
		$moduleName = $request->get('source_module');
		$fileName = vtranslate($moduleName, $moduleName);
		$exportType = $this->getExportContentType($request);

		header("Content-Disposition:attachment;filename=$fileName.csv");
		header("Content-Type:$exportType;charset=UTF-8");
		header("Expires: Mon, 31 Dec 2000 00:00:00 GMT" );
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
		header("Cache-Control: post-check=0, pre-check=0", false );

		$header = implode("\", \"", $headers);
		$header = "\"" .$header;
		$header .= "\"\r\n";
		echo $header;

		foreach($entries as $row) {
			$line = implode("\",\"",$row);
			$line = "\"" .$line;
			$line .= "\"\r\n";
			echo $line;
		}
	}

	private $picklistValues;
	private $fieldArray;

	/**
	 * this function takes in an array of values for an user and sanitizes it for export
	 * @param array $arr - the array of values
	 */
	function sanitizeValues($arr){
		$db = PearDatabase::getInstance();
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$roleid = $currentUser->get('roleid');
		if(empty ($this->fieldArray)){
			$this->fieldArray = $this->moduleFieldInstances;
			foreach($this->fieldArray as $fieldName => $fieldObj){
				$columnName = $fieldObj->get('column');
				$this->fieldArray[$columnName] = $fieldObj;
			}
		}
		$moduleName = $this->moduleInstance->getName();
		foreach($arr as $fieldName=>&$value){
			if(isset($this->fieldArray[$fieldName])){
				$fieldInfo = $this->fieldArray[$fieldName];
			}else {
				unset($arr[$fieldName]);
				continue;
			}
			$value = decode_html($value);
			$uitype = $fieldInfo->get('uitype');
			$fieldname = $fieldInfo->get('name');
			$type = $fieldInfo->getFieldDataType();

			if($uitype == 15 || $uitype == 16 || $uitype == 33){
				if(empty($this->picklistValues[$fieldname])){
					$this->picklistValues[$fieldname] = getAssignedPicklistValues($fieldname, $roleid, $db);
				}
				if(in_array($value,$this->picklistValues[$fieldname])){
					$value = trim($value);
				} else {
					$value = "";
				}
			} elseif($uitype == 52 || $type == 'owner') {
				$value = getOwnerName($value);
			}elseif($type == 'reference'){
				$value = trim($value);
				if(!empty($value)) {
					$parent_module = getSalesEntityType($value);
					$displayValueArray = getEntityName($parent_module, $value);
					if(!empty($displayValueArray)){
						foreach($displayValueArray as $k=>$v){
							$displayValue = $v;
						}
					}
					if(!empty($parent_module) && !empty($displayValue)){
						$value = $parent_module."::::".$displayValue;
					}else{
						$value = "";
					}
				} else {
					$value = '';
				}
			} elseif($uitype == 71) {
                $value = $fieldInfo->getDisplayValue($value);
            } elseif($uitype == 72) {
                $value = CurrencyField::convertToUserFormat($value, null, true, true);
			} elseif($uitype == 7 && $fieldInfo->get('typeofdata') == 'N~O' || $uitype == 9){
				$value = decimalFormat($value);
			}
			if($moduleName == 'Documents' && $fieldname == 'description'){
				$value = strip_tags($value);
				$value = str_replace('&nbsp;','',$value);
				array_push($new_arr,$value);
			}
		}
		return $arr;
	}
}