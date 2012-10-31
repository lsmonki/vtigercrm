<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Calendar_QuickCreateAjax_View extends Vtiger_QuickCreateAjax_View {

	public function  process(Vtiger_Request $request) {
		$moduleName = $request->getModule();

		$moduleList = array('Calendar','Events');

		$quickCreateContents = array();
		foreach($moduleList as $module){
			$info = array();
			$moduleModel = Vtiger_Module_Model::getInstance($module);
			$recordStructureInstance = Vtiger_RecordStructure_Model::getInstanceForModule($moduleModel, Vtiger_RecordStructure_Model::RECORD_STRUCTURE_MODE_QUICKCREATE);

			$info['recordStructureModel'] = $recordStructureInstance;
			$info['recordStructure'] = $recordStructureInstance->getStructure();
			$info['moduleModel'] = $moduleModel;
			$quickCreateContents[$module] = $info;
		}

		$viewer = $this->getViewer($request);
		$viewer->assign('CURRENTDATE', date('Y-n-j'));
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('QUICK_CREATE_CONTENTS', $quickCreateContents);
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());

		$viewer->view('QuickCreate.tpl', $moduleName);
	}
}
