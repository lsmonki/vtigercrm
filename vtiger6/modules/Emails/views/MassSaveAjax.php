<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Emails_MassSaveAjax_View extends Vtiger_Footer_View {
	function __construct() {
		parent::__construct();
		$this->exposeMethod('massSave');
	}

	public function checkPermission(Vtiger_Request $request) {
		$moduleName = $request->getModule();

		if (!Users_Privileges_Model::isPermitted($moduleName, 'Save')) {
			throw new AppException(vtranslate($moduleName).' '.vtranslate('LBL_NOT_ACCESSIBLE'));
		}
	}

	public function process(Vtiger_Request $request) {
		$mode = $request->getMode();
		if(!empty($mode)) {
			echo $this->invokeExposedMethod($mode, $request);
			return;
		}
	}

	/**
	 * Function Sends/Saves mass emails
	 * @param <Vtiger_Request> $request
	 */
	public function massSave(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$recordIds = $this->getRecordsListFromRequest($request);
		$documentIds = $request->get('documentids');

		// This is either SENT or SAVED
		$flag = $request->get('flag');

		$result = Vtiger_Util_Helper::transformUploadedFiles($_FILES, true);
		$_FILES = $result['file'];

		// This will be used for sending mails to each individual
		$toMailInfo = $request->get('toemailinfo');

		$to = $request->get('to');
		if(is_array($to)) {
			$to = implode(',',$to);
		}

		$recordModel = Vtiger_Record_Model::getCleanInstance($moduleName);
		$recordModel->set('mode', '');
		$recordModel->set('description', $request->get('description'));
		$recordModel->set('subject', $request->get('subject'));
		$recordModel->set('saved_toid', $to);
		$recordModel->set('ccmail', $request->get('cc'));
		$recordModel->set('bccmail', $request->get('bcc'));
		$recordModel->set('assigned_user_id', $currentUserModel->getId());
		$recordModel->set('email_flag', $flag);
		$recordModel->set('documentids', $documentIds);

		$recordModel->set('toemailinfo', $toMailInfo);
		foreach($recordIds as $recordId) {
			$parentIds .= $recordId.'@1|';
		}
		$recordModel->set('parent_id', $parentIds);

		//save_module still depends on the $_REQUEST, need to clean it up
		$_REQUEST['parent_id'] = $parentIds;

		$success = false;
		$viewer = $this->getViewer($request);
		if ($recordModel->checkUploadSize($documentIds)) {
			$recordModel->save();
			$success = true;
			if($flag == 'SENT') {
				$status = $recordModel->send();
				if ($status) {
					// This is needed to set vtiger_email_track table as it is used in email reporting
					$recordModel->setAccessCountValue();
				} else {
					$success = false;
					$message = $status;
				}
			}

		} else {
			$message = vtranslate('LBL_MAX_UPLOAD_SIZE', $moduleName).' '.vtranslate('LBL_EXCEEDED', $moduleName);
		}
		$viewer->assign('SUCCESS', $success);
		$viewer->assign('MESSAGE', $message);
		$viewer->view('SendEmailResult.tpl', $moduleName);
	}

	/**
	 * Function returns the record Ids selected in the current filter
	 * @param Vtiger_Request $request
	 * @return integer
	 */
	public function getRecordsListFromRequest(Vtiger_Request $request) {
		$cvId = $request->get('viewname');
		$selectedIds = $request->get('selected_ids');
		$excludedIds = $request->get('excluded_ids');

		if(!empty($selectedIds) && $selectedIds != 'all') {
			if(!empty($selectedIds) && count($selectedIds) > 0) {
				return $selectedIds;
			}
		}

		$customViewModel = CustomView_Record_Model::getInstanceById($cvId);
		if($customViewModel) {
			return $customViewModel->getRecordIds($excludedIds);
		}
	}
}
