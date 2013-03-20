<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Contacts_Popup_View extends Vtiger_Popup_View {
	
	/*
	 * Function to initialize the required data in smarty to display the List View Contents
	 */
	public function initializeListViewContents(Vtiger_Request $request, Vtiger_Viewer $viewer) {
		$moduleName = $this->getModule($request);
		$sourceModule = $request->get('src_module');
		$sourceField = $request->get('src_field');
		
		if($sourceModule == 'Calendar' && $sourceField == 'contact_id'){
            $relatedParentModule = $request->get('related_parent_module');
			$relatedParentId = $request->get('related_parent_id');

            if(empty($relatedParentModule)) {
                return parent::initializeListViewContents($request, $viewer);
            }

			$cvId = $request->get('cvid');
			$pageNumber = $request->get('page');
			$orderBy = $request->get('orderby');
			$sortOrder = $request->get('sortorder');
			$sourceRecord = $request->get('src_record');
			$searchKey = $request->get('search_key');
			$searchValue = $request->get('search_value');
			$currencyId = $request->get('currency_id');
			

			$requestedPage = $pageNumber;
			if(empty ($requestedPage)) {
				$requestedPage = 1;
			}

			$pagingModel = new Vtiger_Paging_Model();
			$pagingModel->set('page',$requestedPage);

			$parentRecordModel = Vtiger_Record_Model::getInstanceById($relatedParentId, $relatedParentModule);
			$relationListView = Vtiger_RelationListView_Model::getInstance($parentRecordModel, $moduleName, $label);

			if($sortOrder == "ASC") {
				$nextSortOrder = "DESC";
				$sortImage = "icon-chevron-down";
			} else {
				$nextSortOrder = "ASC";
				$sortImage = "icon-chevron-up";
			}
			if(!empty($orderBy)) {
				$relationListView->set('orderby', $orderBy);
				$relationListView->set('sortorder',$sortOrder);
			}
			$models = $relationListView->getEntries($pagingModel);
			$noOfEntries = count($models);

			//To handle special operation when selecting record from Popup
			$getUrl = $request->get('get_url');

			//Check whether the request is in multi select mode
			$multiSelectMode = $request->get('multi_select');
			if(empty($multiSelectMode)) {
				$multiSelectMode = false;
			}

			if(empty($cvId)) {
				$cvId = '0';
			}
			if(empty ($pageNumber)){
				$pageNumber = '1';
			}

			$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
			$recordStructureInstance = Vtiger_RecordStructure_Model::getInstanceForModule($moduleModel);

			$viewer->assign('MODULE', $moduleName);

			$viewer->assign('SOURCE_MODULE', $relatedParentModule);
			$viewer->assign('SOURCE_FIELD', $sourceField);
			$viewer->assign('SOURCE_RECORD', $relatedParentId);

			$viewer->assign('SEARCH_KEY', $searchKey);
			$viewer->assign('SEARCH_VALUE', $searchValue);

			$viewer->assign('ORDER_BY',$orderBy);
			$viewer->assign('SORT_ORDER',$sortOrder);
			$viewer->assign('NEXT_SORT_ORDER',$nextSortOrder);
			$viewer->assign('SORT_IMAGE',$sortImage);
			$viewer->assign('GETURL', $getUrl);
			$viewer->assign('CURRENCY_ID', $currencyId);

			$viewer->assign('RECORD_STRUCTURE_MODEL', $recordStructureInstance);
			$viewer->assign('RECORD_STRUCTURE', $recordStructureInstance->getStructure());

			$viewer->assign('PAGING_MODEL', $pagingModel);
			$viewer->assign('PAGE_NUMBER',$pageNumber);

			$viewer->assign('LISTVIEW_ENTIRES_COUNT',$noOfEntries);
			$viewer->assign('LISTVIEW_HEADERS', $relationListView->getHeaders());
			$viewer->assign('LISTVIEW_ENTRIES', $models);

			$viewer->assign('MULTI_SELECT', $multiSelectMode);
		} else {
			return parent::initializeListViewContents($request, $viewer);
		}
		
		
	}

}