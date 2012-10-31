<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Project_DetailView_Model extends Vtiger_DetailView_Model {

	public function getDetailViewRelatedLinks() {
		$relatedLinks = parent::getDetailViewRelatedLinks();
		if(!is_array($relatedLinks)){
			$relatedLinks = array();
		}


		// TODO : Check if related list can be get by from the table
		$relatedLinkList = array(

				array(
					'linktype' => 'DETAILVIEWRELATED',
					'linklabel' => vtranslate('LBL_CHARTS', 'Projects'),
					//TODO : Assign the url which gives the chart
					'linkurl' => '',
					'linkicon' => ''
				),

				array(
					'linktype' => 'DETAILVIEWRELATED',
					'linklabel' => vtranslate('LBL_EVENTS', 'Calendar').' & '.vtranslate('LBL_TODOS', 'Calendar'),
					//TODO : Assign the url which gives the chart
					'linkurl' => '',
					'linkicon' => ''
				)
		);

		foreach($relatedLinkList as $relatedLinkEntry) {
			$relatedLinks[] = $relatedLinkEntry;
		}

		return $relatedLinks;
	}

}