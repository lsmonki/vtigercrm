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
require_once('modules/Contacts/Contact.php');
require_once('modules/Potentials/Opportunity.php');
require_once('modules/Notes/Note.php');
require_once('modules/Emails/Email.php');
require_once('modules/Accounts/Account.php');
require_once('include/ComboUtil.php');

// Get _dom arrays from Database
$comboFieldNames = Array('leadsource'=>'lead_source_dom'
                      ,'opportunity_type'=>'opportunity_type_dom'
                      ,'sales_stage'=>'sales_stage_dom');
$comboFieldArray = getComboArray($comboFieldNames);

// Account is used to store account information.
class ImportOpportunity extends Potential {
	 var $db;

	// This is the list of fields that are required.
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
						//"add_lead_source",
						//"add_opportunity_type",
				        	//"add_date_closed"
				        	//"add_sales_stage"
				       );

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


	//exactly the same function from ImportAccount.php
	// lets put this in one place.. 

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
                $focus = new Account();

		$query = '';

		// if user is defining the account id to be associated with this contact..
		$acc_name = trim(addslashes($acc_name));
		$query = "select crmentity.deleted, account.* from account,crmentity WHERE accountname='{$acc_name}' and crmentity.crmid =account.accountid";

                $this->log->info($query);

                $result = $adb->query($query)	or die("Error selecting sugarbean: ".mysql_error());

                $row = $this->db->fetchByAssoc($result, -1, false);

		$adb->println("fetched account");
		$adb->println($row);

		// we found a row with that id
                if (isset($row['accountid']) && $row['accountid'] != -1)
                {
                        // if it exists but was deleted, just remove it entirely
                        if ( isset($row['deleted']) && $row['deleted'] == 1)
                        {
				$adb->println("row exists - deleting");
                                $query2 = "delete from crmentity WHERE crmid='". $row['accountid']."'";

                                $this->log->info($query2);

                                $result2 = $adb->query($query2)	or die("Error deleting existing sugarbean: ".mysql_error());

                        }
			// else just use this id to link the contact to the account
                        else
                        {				
                                $focus->id = $row['accountid'];
				$adb->println("row exists - using same id=".$focus->id);
                        }
                }

		// if we didnt find the account, so create it
                if (! isset($focus->id) || $focus->id == '')
                {
			$adb->println("Createing new account");
                        $focus->column_fields['accountname'] = $acc_name;
                        $focus->column_fields['assigned_user_id'] = $current_user->id;
                        $focus->column_fields['modified_user_id'] = $current_user->id;

			$focus->save("Accounts");
			$acc_id = $focus->id;

			$adb->println("New Account created id=".$focus->id);

			// avoid duplicate mappings:
			if (! isset( $imported_ids[$acc_id]) )
			{
				$adb->println("inserting users last import for accounts");
				// save the new account as a users_last_import
                		$last_import = new UsersLastImport();
                		$last_import->assigned_user_id = $current_user->id;
                		$last_import->bean_type = "Accounts";
                		$last_import->bean_id = $focus->id;
                		$last_import->save();
				$imported_ids[$acc_id] = 1;
			}
                }

		$adb->println("prev contact accid=".$this->column_fields["account_id"]);
		// now just link the account
                $this->column_fields["account_id"] = $focus->id;
		$adb->println("curr contact accid=".$this->column_fields["account_id"]);

        }	


	function fix_website()
	{
		if ( isset($this->website) &&
			preg_match("/^http:\/\//",$this->website) )
		{
			$this->website = substr($this->website,7);
		}	
	}

	
	// This is the list of fields that are importable.
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

	function ImportOpportunity() {
		$this->log = LoggerManager::getLogger('import_opportunity');
		$this->db = new PearDatabase();

		$this->db->println("IMP ImportOpportunity");
		$colf = getColumnFields("Potentials");
		foreach($colf as $key=>$value)
			$this->importable_fields[$key]=1;
		
		
		$this->db->println($this->importable_fields);
	}

}



?>
