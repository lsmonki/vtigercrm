<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Documents_ListView_Model extends Vtiger_ListView_Model {

	/**
	 * Function to get the list of listview links for the module
	 * @param <Array> $linkParams
	 * @return <Array> - Associate array of Link Type to List of Vtiger_Link_Model instances
	 */
	public function getListViewLinks($linkParams) {
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$moduleModel = $this->getModule();

		$linkTypes = array('LISTVIEWBASIC', 'LISTVIEW', 'LISTVIEWSETTING');
		$links = Vtiger_Link_Model::getAllByType($moduleModel->getId(), $linkTypes, $linkParams);

		$createPermission = Users_Privileges_Model::isPermitted($moduleModel->getName(), 'EditView');
		if($createPermission) {
			$basicLinks = array(
					array(
							'linktype' => 'LISTVIEWBASIC',
							'linklabel' => 'LBL_ADD_RECORD',
							'linkurl' => $moduleModel->getCreateRecordUrl(),
							'linkicon' => ''
					),
					array(
							'linktype' => 'LISTVIEWBASIC',
							'linklabel' => 'LBL_ADD_FOLDER',
							'linkurl' => 'javascript:Documents_List_Js.triggerAddFolder("'.$moduleModel->getAddFolderUrl().'")',
							'linkicon' => ''
					)
			);
			foreach($basicLinks as $basicLink) {
				$links['LISTVIEWBASIC'][] = Vtiger_Link_Model::getInstanceFromValues($basicLink);
			}
		}

		$exportPermission = Users_Privileges_Model::isPermitted($moduleModel->getName(), 'Export');
		if($exportPermission) {
			$advancedLink = array(
					'linktype' => 'LISTVIEW',
					'linklabel' => 'LBL_EXPORT',
					'linkurl' => 'javascript:Vtiger_List_Js.triggerExportAction("'.$moduleModel->getExportUrl().'")',
					'linkicon' => ''
			);
			$links['LISTVIEW'][] = Vtiger_Link_Model::getInstanceFromValues($advancedLink);
		}

		if($currentUserModel->isAdminUser()) {
			$settingsLinks = array(
					array(
							'linktype' => 'LISTVIEWSETTING',
							'linklabel' => 'LBL_EDIT_FIELDS',
							'linkurl' => $moduleModel->getSettingsUrl('LayoutEditor'),
							'linkicon' => ''
					),
					array(
							'linktype' => 'LISTVIEWSETTING',
							'linklabel' => 'LBL_EDIT_PICKLIST_VALUES',
							'linkurl' => $moduleModel->getSettingsUrl('PicklistEditor'),
							'linkicon' => ''
					)
			);
			foreach($settingsLinks as $settingsLink) {
				$links['LISTVIEWSETTING'][] = Vtiger_Link_Model::getInstanceFromValues($settingsLink);
			}
		}
		return $links;
	}

	/**
	 * Function to get the list of Mass actions for the module
	 * @param <Array> $linkParams
	 * @return <Array> - Associative array of Link type to List of  Vtiger_Link_Model instances for Mass Actions
	 */
	public function getListViewMassActions($linkParams) {
		$currentUserModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$moduleModel = $this->getModule();

		$linkTypes = array('LISTVIEWMASSACTION');
		$links = Vtiger_Link_Model::getAllByType($moduleModel->getId(), $linkTypes, $linkParams);

		if($currentUserModel->hasModuleActionPermission($moduleModel->getId(), 'Delete')) {
			$massActionLinks = array(
					array(
							'linktype' => 'LISTVIEWMASSACTION',
							'linklabel' => 'LBL_DELETE',
							'linkurl' => 'javascript:Vtiger_List_Js.massDeleteRecords("index.php?module='.$moduleModel->getName().'&action=MassDelete");',
							'linkicon' => ''
					),
					array(
							'linktype' => 'LISTVIEWMASSACTION',
							'linklabel' => 'LBL_MOVE',
							'linkurl' => 'javascript:Documents_List_Js.massMove("index.php?module='.$moduleModel->getName().'&view=MoveDocuments");',
							'linkicon' => ''
					)
			);
			foreach($massActionLinks as $massActionLink) {
				$links['LISTVIEWMASSACTION'][] = Vtiger_Link_Model::getInstanceFromValues($massActionLink);
			}
		}

		return $links;
	}

}
