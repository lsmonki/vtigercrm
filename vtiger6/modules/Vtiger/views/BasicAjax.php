<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Vtiger_BasicAjax_View extends Vtiger_Basic_View {

	function __construct() {
		parent::__construct();
		$this->exposeMethod('showAdvancedSearch');
		$this->exposeMethod('showSearchResults');
	}

	function checkPermission() { }

	function preProcess(Vtiger_Request $request) {
		return true;
	}

	function postProcess(Vtiger_Request $request) {
		return true;
	}

	function process(Vtiger_Request $request) {
		$mode = $request->get('mode');
		if(!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
		}
		return;
	}

	/**
	 * Function to display the UI for advance search on any of the module
	 * @param Vtiger_Request $request
	 */
	function showAdvancedSearch(Vtiger_Request $request) {
		//Modules for which search is excluded
		$excludedModuleForSearch = array('Vtiger');

		$viewer = $this->getViewer($request);
		$moduleName = $request->get('source_module');

		//See if it is an excluded module, If so search in home module
		if(in_array($moduleName, $excludedModuleForSearch)) {
			$moduleName = 'Home';
		}
		$module = $request->getModule();

		$customViewModel = new CustomView_Record_Model();
        $customViewModel->setModule($moduleName);
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		$recordStructureInstance = Vtiger_RecordStructure_Model::getInstanceForModule($moduleModel);

		$viewer->assign('SEARCHABLE_MODULES', Vtiger_Module_Model::getSearchableModules());
		$viewer->assign('CUSTOMVIEW_MODEL', $customViewModel);
		$viewer->assign('ADVANCED_FILTER_OPTIONS', Vtiger_Field_Model::getAdvancedFilterOptions());
		$viewer->assign('ADVANCED_FILTER_OPTIONS_BY_TYPE', Vtiger_Field_Model::getAdvancedFilterOpsByFieldType());
		$viewer->assign('RECORD_STRUCTURE', $recordStructureInstance->getStructure());
		$viewer->assign('SOURCE_MODULE',$moduleName);
		$viewer->assign('MODULE', $module);

		echo $viewer->view('AdvanceSearch.tpl',$moduleName, true);
	}

	/**
	 * Function to display the Search Results
	 * @param Vtiger_Request $request
	 */
	function showSearchResults(Vtiger_Request $request) {
		$db = PearDatabase::getInstance();

		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$advFilterList = $request->get('advfilterlist');

		//used to show the save modify filter option
		$isAdvanceSearch = false;
		$matchingRecords = array();
		if(is_array($advFilterList) && count($advFilterList) > 0) {
			$isAdvanceSearch = true;
			$user = Users_Record_Model::getCurrentUserModel();
			$queryGenerator = new QueryGenerator($moduleName, $user);
			$queryGenerator->setFields(array('id'));

			foreach ($advFilterList as $groupindex=>$groupcolumns) {
				$filtercolumns = $groupcolumns['columns'];
				if(count($filtercolumns) > 0) {
					$queryGenerator->startGroup('');
					foreach ($filtercolumns as $index=>$filter) {
						$name = explode(':',$filter['columnname']);
						if(empty($name[2]) && $name[1] == 'crmid' && $name[0] == 'vtiger_crmentity') {
							$name = $queryGenerator->getSQLColumn('id');
						} else {
							$name = $name[2];
						}
						$queryGenerator->addCondition($name, $filter['value'], $filter['comparator']);
						$columncondition = $filter['column_condition'];
						if(!empty($columncondition)) {
							$queryGenerator->addConditionGlue($columncondition);
						}
					}
					$queryGenerator->endGroup();
					$groupConditionGlue = $groupcolumns['condition'];
					if(!empty($groupConditionGlue))
						$queryGenerator->addConditionGlue($groupConditionGlue);
				}
			}
			$query = $queryGenerator->getQuery();
			$result = $db->pquery($query, array());
			$rows = $db->num_rows($result);

			for($i=0; $i<$rows; ++$i) {
				$row = $db->query_result_rowdata($result, $i);
				$recordInstance = Vtiger_Record_Model::getInstanceById($row[0]);
				$moduleName = $recordInstance->getModuleName();
				$matchingRecords[$moduleName][$row[0]] = $recordInstance;
			}
		} else {
			$searchKey = $request->get('value');
			$viewer->assign('SEARCH_KEY', $searchKey);
			$matchingRecords =  Vtiger_Record_Model::getSearchResult($searchKey);
		}

		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('MATCHING_RECORDS', $matchingRecords);
		$viewer->assign('IS_ADVANCE_SEARCH', $isAdvanceSearch);

		echo $viewer->view('UnifiedSearchResults.tpl', '', true);
	}
}