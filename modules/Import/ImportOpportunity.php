<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header$
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('modules/Contacts/Contacts.php');
require_once('modules/Potentials/Potentials.php');
require_once('modules/Documents/Documents.php');
require_once('modules/Emails/Emails.php');
require_once('modules/Accounts/Accounts.php');
require_once('include/ComboUtil.php');

// Get _dom arrays from Database
$comboFieldNames = Array('leadsource'=>'lead_source_dom'
                      ,'opportunity_type'=>'opportunity_type_dom'
                      ,'sales_stage'=>'sales_stage_dom');
$comboFieldArray = getComboArray($comboFieldNames);

// Account is used to store vtiger_account information.
class ImportOpportunity extends Potentials {
	 var $db;

	// This is the list of the functions to run when importing
	var $special_functions =  array(
						"add_related_to",
						"map_campaign_source",
						"modseq_number",
				       );

	/**
	 * this function is used to create the related to field for the potential
	 */
	function add_related_to(){
		global $adb, $imported_ids, $current_user;
		
		$related_to = $this->column_fields['related_to'];

		if(empty($related_to)){
			return;
		}
		
		//check if the field has module information; if not exit
		if(!strpos($related_to, "::::")){
			$module = getFirstModule('Potentials', 'related_to');
			echo "er";
		}else{
			//check the module of the field
			$arr = array();
			$arr = explode("::::", $related_to);
			$module = $arr[0];
			$value = $arr[1];
			if(empty($module)){
				$module = getFirstModule();
			}
		}
				
		require_once "modules/$module/$module.php";
		$focus = new $module();

		$query = '';
		if($module == 'Accounts'){
			$query = "select vtiger_crmentity.deleted, vtiger_account.* 
						from vtiger_account, vtiger_crmentity 
						WHERE accountname=? and vtiger_crmentity.crmid=vtiger_account.accountid and vtiger_crmentity.deleted=0";
		}elseif($module == 'Contacts'){
			$query = "select vtiger_crmentity.deleted, vtiger_contactdetails.* 
						from vtiger_contactdetails inner join vtiger_crmentity 
						on vtiger_crmentity.crmid=vtiger_contactdetails.contactid  
						WHERE concat(lastname, ' ', firstname)=? and vtiger_crmentity.deleted=0";
		}
		$result = $adb->pquery($query, array($value));
		if($adb->num_rows($result)>0){
			//record found
			$focus->id = $adb->query_result($result, 0, $focus->table_index);
		}else{
			//record not found; create it
			if($module == 'Accounts'){
		        $focus->column_fields['accountname'] = $value;
		        $focus->column_fields['assigned_user_id'] = $current_user->id;
		        $focus->column_fields['modified_user_id'] = $current_user->id;
			}else if($module == 'Contacts'){
		        $focus->column_fields['lastname'] = $value;
		        $focus->column_fields['assigned_user_id'] = $current_user->id;
		        $focus->column_fields['modified_user_id'] = $current_user->id;
			}
			$focus->save($module);
		}
		// avoid duplicate mappings:
		if (!isset($imported_ids[$focus->id])){
    		$last_import = new UsersLastImport();
    		$last_import->assigned_user_id = $current_user->id;
    		$last_import->bean_type = $module;
    		$last_import->bean_id = $focus->id;
    		$last_import->save();
			$imported_ids[$focus->id] = 1;
		}
		$this->column_fields["related_to"] = $focus->id;
    }	

	/**     function used to map with existing Campaign Source if the potential is map with an campaign during import
         */
	function map_campaign_source(){
		global $adb;

		$campaign_name = $this->column_fields['campaignid'];
		$adb->println("Entering map_campaign_source campaignid=".$campaign_name);

		if ((! isset($campaign_name) || $campaign_name == '') ){
			$adb->println("Exit map_campaign_source. Campaign Name not set for this entity.");
			return; 
		}

		$campaign_name = trim($campaign_name);

		//Query to get the available campaign which is not deleted
		$query = "select campaignid from vtiger_campaign inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_campaign.campaignid WHERE vtiger_campaign.campaignname=? and vtiger_crmentity.deleted=0";

		$campaignid = $adb->query_result($adb->pquery($query, array($campaign_name)),0,'campaignid');

		if($campaignid == '' || !isset($campaignid)){
			$campaignid = 0;
		}

		$this->column_fields['campaignid'] = $campaignid;
		$adb->println("Exit map_campaign_source. Fetched Campaign for '".$campaign_name."' and the campaignid = $campaignid");
    }

	var $importable_fields = Array();

	/** Constructor which will set the importable_fields as $this->importable_fields[$key]=1 in this object where key is the fieldname in the field table
	 */
	function ImportOpportunity() {
		parent::Potentials();
		$this->log = LoggerManager::getLogger('import_opportunity');
		$this->db = new PearDatabase();

		$this->db->println("IMP ImportOpportunity");
		$this->initImportableFields("Potentials");		
		$this->db->println($this->importable_fields);
	}

	// Module Sequence Numbering	
	function modseq_number() {
		$this->column_fields['potential_no'] = '';
	}
	// END
}
?>