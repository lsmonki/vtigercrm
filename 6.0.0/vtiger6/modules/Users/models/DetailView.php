<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Users_DetailView_Model extends Vtiger_DetailView_Model {
    
    
    /**
	 * Function to get the detail view links (links and widgets)
	 * @param <array> $linkParams - parameters which will be used to calicaulate the params
	 * @return <array> - array of link models in the format as below
	 *                   array('linktype'=>list of link models);
	 */
	public function getDetailViewLinks($linkParams) {
        $currentUserModel = Users_Record_Model::getCurrentUserModel();
        $recordModel = $this->getRecord();
        
        if(($currentUserModel->isAdminUser() == true && $recordModel->getId() !=1 ) || $currentUserModel->get('id') == $recordModel->getId()){
            $recordModel = $this->getRecord();
            $detailViewLink = array(
                        'linktype' => 'DETAILVIEWBASIC',
                        'linklabel' => 'LBL_EDIT',
                        'linkurl' => $recordModel->getEditViewUrl(),
                        'linkicon' => ''
                );
            $linkModelList['DETAILVIEWBASIC'][] = Vtiger_Link_Model::getInstanceFromValues($detailViewLink);
            return $linkModelList;
        }
        
	}
}