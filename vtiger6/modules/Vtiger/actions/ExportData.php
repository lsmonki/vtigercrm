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

		$query = $this->getExportQuery($request);
		$result = $db->pquery($query, array());

		$headerList = $db->getFieldsArray($result);
		$headers = array_diff($headerList,array("user_name"));
		$translatedHeaders = array();
		for($i=0; $i<count($headers); $i++) {
			$translatedHeaders[$i] = vtranslate($headers[$i], $moduleName);
		}
		$this->moduleInstance = Vtiger_Module_Model::getInstance($moduleName);
		$this->focus = CRMEntity::getInstance($moduleName);
		
		// TODO: sanitize the data before sending it
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
		$mode = $request->getMode();
		$cvId = $request->get('viewname');
		$moduleName = $request->get('source_module');

		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		$query = $moduleModel->getExportQuery('');

		$customViewModel = CustomView_Record_Model::getInstanceById($cvId);
		$stdFilterSql = $customViewModel->getCVStdFilterSQL();
		$advFilterSql = $customViewModel->getCVAdvFilterSQL();
		if(isset($stdFilterSql) && $stdFilterSql != ''){
			$query .= ' AND '.$stdFilterSql;
		}
		if(isset($advFilterSql) && $advFilterSql != '') {
			$query .= ' AND '.$advFilterSql;
		}

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
											$baseTable = $moduleModel->get('basetable');
											$baseTableColumnId = $moduleModel->get('basetableid');
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
			$this->fieldArray = $this->moduleInstance->getFieldsByLabel();
			foreach($this->fieldArray as $fieldLabel => $fieldObj){
					$this->fieldArray[strtolower($fieldLabel)] = $fieldObj;
			}
		}
		$type= $this->moduleInstance->getName();
		
		foreach($arr as $fieldLabel=>&$value){
			if(isset($this->fieldArray[$fieldLabel])){
				$fieldInfo = $this->fieldArray[$fieldLabel];
			}else {
				unset($arr[$fieldLabel]);
			}
			$value = decode_html($value);
			$uitype = $fieldInfo->get('uitype');
			$fieldname = $fieldInfo->get('name');
			if($uitype == 15 || $uitype == 16 || $uitype == 33){
				
				//picklists
				if(empty($this->picklistValues[$fieldname])){
					$this->picklistValues[$fieldname] = getAssignedPicklistValues($fieldname, $roleid, $db);
				}
				if(in_array($value,$this->picklistValues[$fieldname])){
					$value = trim($value);
				} else {
					$value = "";
				}
			}elseif($uitype == 10){
				//have to handle uitype 10
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
			if($type == 'Documents' && $fieldLabel == 'description'){
				$value = strip_tags($value);
				$value = str_replace('&nbsp;','',$value);
				array_push($new_arr,$value);
			}
			
		}
		return $arr;
	}
}