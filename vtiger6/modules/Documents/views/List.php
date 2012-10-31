<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Documents_List_View extends Vtiger_List_View {
	function __construct() {
		parent::__construct();
	}
	
	function preProcess (Vtiger_Request $request) {
		$viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();

		$documentModuleModel = Vtiger_Module_Model::getInstance($moduleName);
		$defaultCustomFilter = $documentModuleModel->getDefaultCustomFilter();
		$folderList = Documents_Module_Model::getAllFolders();

		$viewer->assign('DEFAULT_CUSTOM_FILTER_ID', $defaultCustomFilter);
		$viewer->assign('FOLDERS', $folderList);

		parent::preProcess($request);
	}
}