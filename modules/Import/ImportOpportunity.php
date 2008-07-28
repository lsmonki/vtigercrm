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

	// This is the list of vtiger_fields that are required.
	/*
	var $required_fields =  array(
					"potentialname"=>1,
					"account_id"=>1,
					"closingdate"=>1,
					"sales_stage"=>1,
					"amount"=>1
				     );
	*/

	// This is the list of the functions to run when importing
	var $special_functions =  array(
						"add_create_account",
						"map_campaign_source",
						//"add_lead_source",
						//"add_opportunity_type",
				        	//"add_date_closed"
				        	//"add_sales_stage"
				       );
	/*
        function add_lead_source()
        {
                if ( isset($this->lead_source) &&
                        ! isset( $comboFieldArray['lead_source_dom'][ $this->lead_source ]) )
                {
                        $this->lead_source = '';
                }

        }

        function add_sales_stage()
        {
                if ( isset($this->sales_stage) &&
                        ! isset( $comboFieldArray['sales_stage_dom'][ $this->sales_stage ]) )
                {
                        $this->sales_stage = 'Prospecting';
                }


	}

        function add_opportunity_type()
        {
                if ( isset($this->opportunity_type) &&
                        ! isset( $comboFieldArray['opportunity_type_dom'][ $this->opportunity_type ]) )
                {
                        $this->opportunity_type = '';
                }

        }

        function add_date_closed()
        {
                if ( isset($this->date_closed))
                {
                        if ( preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/',$this->date_closed,$match))
                        {
                                $this->date_closed = $match[3]."-".$match[1]."-".$match[2];
                        }

                        if (! preg_match('/^\d{4}-\d{1,2}-\d{1,2}$/',$this->date_closed))
                        {
                                $this->date_closed = '';
                        }
                }

        }
	*/

	//exactly the same function from ImportAccount.php
	// lets put this in one place.. 

	/**     function used to create or map with existing account if the potential is map with an account during import
         */
	function add_create_account()
        {
		global $adb;
		// global is defined in UsersLastImport.php
		global $imported_ids;
                global $current_user;

		$acc_name = $this->column_fields['account_id'];
		$adb->println("oppor add_create acc=".$acc_name);

		if ((! isset($acc_name) || $acc_name == '') )
		{
			return; 
		}

                $arr = array();

		// check if it already exists
                $focus = new Accounts();

		$query = '';

		// if user is defining the vtiger_account id to be associated with this contact..
		$acc_name = trim($acc_name);

		//Modified the query to get the available account only ie., which is not deleted
		$query = "select vtiger_crmentity.deleted, vtiger_account.* from vtiger_account, vtiger_crmentity WHERE accountname=? and vtiger_crmentity.crmid =vtiger_account.accountid and vtiger_crmentity.deleted=0";
		$this->log->info($query);
		$result = $adb->pquery($query, array($acc_name));

         $row = $this->db->fetchByAssoc($result, -1, false);

		$adb->println("fetched account");
		$adb->println($row);

		// we found a row with that id
                if (isset($row['accountid']) && $row['accountid'] != -1)
                {
			$focus->id = $row['accountid'];
			$adb->println("Account row exists - using same id=".$focus->id);
                }

		// if we didnt find the vtiger_account, so create it
                if (! isset($focus->id) || $focus->id == '')
                {
			$adb->println("Createing new vtiger_account");
                        $focus->column_fields['accountname'] = $acc_name;
                        $focus->column_fields['assigned_user_id'] = $current_user->id;
                        $focus->column_fields['modified_user_id'] = $current_user->id;

			$focus->save("Accounts");
			$acc_id = $focus->id;

			$adb->println("New Account created id=".$focus->id);

			// avoid duplicate mappings:
			if (! isset( $imported_ids[$acc_id]) )
			{
				$adb->println("inserting vtiger_users last import for vtiger_accounts");
				// save the new vtiger_account as a vtiger_users_last_import
                		$last_import = new UsersLastImport();
                		$last_import->assigned_user_id = $current_user->id;
                		$last_import->bean_type = "Accounts";
                		$last_import->bean_id = $focus->id;
                		$last_import->save();
				$imported_ids[$acc_id] = 1;
			}
                }

		$adb->println("prev contact accid=".$this->column_fields["account_id"]);
		// now just link the vtiger_account
                $this->column_fields["account_id"] = $focus->id;
		$adb->println("curr contact accid=".$this->column_fields["account_id"]);

        }	

	/**     function used to map with existing Campaign Source if the potential is map with an campaign during import
         */
	function map_campaign_source()
	{
		global $adb;

		$campaign_name = $this->column_fields['campaignid'];
		$adb->println("Entering map_campaign_source campaignid=".$campaign_name);

		if ((! isset($campaign_name) || $campaign_name == '') )
		{
			$adb->println("Exit map_campaign_source. Campaign Name not set for this entity.");
			return; 
		}

		$campaign_name = trim($campaign_name);

		//Query to get the available campaign which is not deleted
		$query = "select campaignid from vtiger_campaign inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_campaign.campaignid WHERE vtiger_campaign.campaignname=? and vtiger_crmentity.deleted=0";

		$campaignid = $adb->query_result($adb->pquery($query, array($campaign_name)),0,'campaignid');

		if($campaignid == '' || !isset($campaignid))
			$campaignid = 0;

		$this->column_fields['campaignid'] = $campaignid;

		$adb->println("Exit map_campaign_source. Fetched Campaign for '".$campaign_name."' and the campaignid = $campaignid");
        }


	/*
	function fix_website()
	{
		if ( isset($this->website) &&
			preg_match("/^http:\/\//",$this->website) )
		{
			$this->website = substr($this->website,7);
		}	
	}
	*/
	
	// This is the list of vtiger_fields that are importable.
	// some if these do not map directly to database columns
	/*var $importable_fields = Array(
		"id"=>1
                , "name"=>1
                , "account_id"=>1
                , "account_name"=>1
                , "opportunity_type"=>1
                , "lead_source"=>1
                , "amount"=>1
                , "date_entered"=>1
                , "date_closed"=>1
                , "next_step"=>1
                , "sales_stage"=>1
                , "probability"=>1
                , "description"=>1
		);*/

	var $importable_fields = Array();

	/** Constructor which will set the importable_fields as $this->importable_fields[$key]=1 in this object where key is the fieldname in the field table
	 */
	function ImportOpportunity() {
		$this->log = LoggerManager::getLogger('import_opportunity');
		$this->db = new PearDatabase();

		$this->db->println("IMP ImportOpportunity");
		$this->initImportableFields("Potentials");		
		
		$this->db->println($this->importable_fields);
	}

}



?>
