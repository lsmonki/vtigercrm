<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Reports_List_View extends Vtiger_Index_View {

	public function checkPermission(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Reports_Module_Model::getInstance($moduleName);

		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPriviligesModel->hasModulePermission($moduleModel->getId())) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}
	
	

	function preProcess(Vtiger_Request $request, $display=true) {
		parent::preProcess($request, false);

		$viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);

		$folders = $moduleModel->getFolders();
		$listViewModel = new Reports_ListView_Model();
		$listViewModel->set('module', $moduleModel);
		
		$folderId = $request->get('viewname');
		if(empty($folderId)){
			$folderId = $folders[0]->getId();
		}
		$listViewModel->set('folderid', $folderId);

		$linkModels = $listViewModel->getListViewLinks();
		
		$viewer->assign('LISTVIEW_LINKS', $linkModels);
		$viewer->assign('FOLDERS', $folders);
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('VIEWNAME',$folderId);

		if($display) {
			$this->preProcessDisplay($request);
		}
	}

	function preProcessTplName(Vtiger_Request $request) {
		return 'ListViewPreProcess.tpl';
	}
	function process(Vtiger_Request $request) {
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		$folders = $moduleModel->getFolders();
		$folderId = $request->get('viewname');
		if(empty($folderId)){
			$folderId = $folders[0]->getId();
		}
		$pageNumber = $request->get('page');
		$orderBy = $request->get('orderby');

		$sortOrder = $request->get('sortorder');
		if($sortOrder == "ASC"){
			$nextSortOrder = "DESC";
			$sortImage = "downArrowSmall.png";
		}else{
			$nextSortOrder = "ASC";
			$sortImage = "upArrowSmall.png";
		}
		
		$listViewModel = new Reports_ListView_Model();
		$listViewModel->set('module', $moduleModel);
		$listViewModel->set('folderid', $folderId);

		if(!empty($orderBy)) {
			$listViewModel->set('orderby', $orderBy);
			$listViewModel->set('sortorder', $sortOrder);
		}
		$listViewMassActionModels = $listViewModel->getListViewMassActions();
		if(empty ($pageNumber)){
			$pageNumber = '1';
		}
		$viewer->assign('MODULE', $moduleName);
		$pagingModel = new Vtiger_Paging_Model();
		$pagingModel->set('page', $pageNumber);
		$viewer->assign('PAGING_MODEL', $pagingModel);
	
		$viewer->assign('LISTVIEW_MASSACTIONS', $listViewMassActionModels);

		$listViewHeaders = $listViewModel->getListViewHeaders();
		$listViewEntries = $listViewModel->getListViewEntries($pagingModel);
		$noOfEntries = count($listViewEntries);
		
  		$viewer->assign('PAGE_NUMBER',$pageNumber);
		$viewer->assign('LISTVIEW_ENTIRES_COUNT',$noOfEntries);
		$viewer->assign('LISTVIEW_HEADERS', $listViewHeaders);
		$viewer->assign('LISTVIEW_ENTRIES', $listViewEntries);

		if (PerformancePrefs::getBoolean('LISTVIEW_COMPUTE_PAGE_COUNT', false)) {
			$viewer->assign('LISTVIEW_COUNT', $listViewModel->getListViewCount());
		}
		
		$viewer->view('ListViewContents.tpl', $moduleName);
	}

	function postProcess(Vtiger_Request $request) {
	}

	/**
	 * Function to get the list of Script models to be included
	 * @param Vtiger_Request $request
	 * @return <Array> - List of Vtiger_JsScript_Model instances
	 */
	function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			'modules.Vtiger.resources.List',
			"modules.$moduleName.resources.List",
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
}