<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Campaigns_Detail_View extends Vtiger_Detail_View {

	/**
	 * Function returns related records
	 * @param Vtiger_Request $request
	 * @return <type>
	 */
	public function showRelatedList(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$relatedModuleName = $request->get('relatedModule');
		$parentId = $request->get('record');
		$label = $request->get('tab_label');

		$parentRecordModel = Vtiger_Record_Model::getInstanceById($parentId, $moduleName);
		$relationListView = Vtiger_RelationListView_Model::getInstance($parentRecordModel, $relatedModuleName, $label);
		$relationModel = $relationListView->getRelationModel();

		$viewer = $this->getViewer($request);
		if (array_key_exists($relatedModuleName, $relationModel->getEmailEnabledModulesInfoForDetailView())) {
			$viewer->assign('CUSTOM_VIEWS', CustomView_Record_Model::getAllByGroup($relatedModuleName));
			$viewer->assign('STATUS_VALUES', $relationModel->getCampaignRelationStatusValues());
			$viewer->assign('SELECTED_IDS', $request->get('selectedIds'));
			$viewer->assign('EXCLUDED_IDS', $request->get('excludedIds'));
		}
		return parent::showRelatedList($request);
	}

	/**
	 * Function to get the list of Script models to be included
	 * @param Vtiger_Request $request
	 * @return <Array> - List of Vtiger_JsScript_Model instances
	 */
	public function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
				'modules.Vtiger.resources.List',
				"modules.$moduleName.resources.List",
				'modules.CustomView.resources.CustomView',
				"modules.$moduleName.resources.CustomView",
				"modules.Emails.resources.MassEdit",
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
}