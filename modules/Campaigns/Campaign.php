<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of txhe License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('data/CRMEntity.php');
require_once('include/utils/utils.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Leads/Lead.php');

class Campaign extends CRMEntity {
	var $log;
	var $db;

	// Stored fields
	var $id;
	var $mode;

	var $tab_name = Array('crmentity','campaign');
	var $tab_name_index = Array('crmentity'=>'crmid','campaign'=>'campaignid');
	var $column_fields = Array();

	var $sortby_fields = Array('campaignname','smownerid','expectedcost');

	var $list_fields = Array(
	'Campaign ID'=>Array('crmentity'=>'crmid'),
	'Campaign Name'=>Array('campaign'=>'campaignname'),	  			
	'Expected Cost'=>Array('campaign'=>'expectedcost')
	);

	var $list_fields_name = Array(
	'Campaign ID'=>'',
	'Campaign Name'=>'campaignname',	  			
	'Expected Cost'=>'expectedcost');	  			

	var $list_link_field= 'campaignname';
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'DESC';

	function Campaign() 
	{
		$this->log =LoggerManager::getLogger('campaign');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Campaigns');
	}

	function get_contacts($id)
        {
                global $mod_strings;

                $focus = new Contact();
                $button = '';
                $returnset = '&return_module=Campaigns&return_action=DetailView&return_id='.$id;

                $query = 'SELECT contactdetails.*, crmentity.crmid, crmentity.smownerid from contactdetails inner join crmentity on crmentity.crmid = contactdetails.contactid  where crmentity.deleted=0 and contactdetails.campaignid = '.$id;

                return GetRelatedList('Campaigns','Contacts',$focus,$query,$button,$returnset);
        }
	function get_leads($id)
        {
                global $mod_strings;

                $focus = new Lead();

                $button = '';
                $returnset = '&return_module=Campaigns&return_action=DetailView&return_id='.$id;

                $query = 'SELECT leaddetails.*, crmentity.crmid, crmentity.smownerid from leaddetails inner join crmentity on crmentity.crmid = leaddetails.leadid  where crmentity.deleted=0 and leaddetails.campaignid = '.$id;

                return GetRelatedList('Campaigns','Leads',$focus,$query,$button,$returnset);
        }

}
?>
