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
 * Description:  TODO: To be written.
 ********************************************************************************/
include_once('config.php');
require_once('include/logging.php');
/*
require_once('database/DatabaseConnection.php');
require_once('data/SugarBean.php');
require_once('include/utils.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Cases/Case.php');
require_once('modules/Tasks/Task.php');
require_once('modules/Notes/Note.php');
require_once('modules/Meetings/Meeting.php');
require_once('modules/Calls/Call.php');
require_once('modules/Emails/Email.php');
require_once('modules/Accounts/Account.php');
*/
require_once('modules/Contacts/Contact.php');
require_once('modules/Import/UsersLastImport.php');

global $app_list_strings;

// Contact is used to store customer information.
class ImportContact extends Contact {
	// these are fields that may be set on import
	// but are to be processed and incorporated
	// into fields of the parent class
	var $db;
	var $full_name;
	var $primary_address_street_2;
	var $primary_address_street_3;
	var $alt_address_street_2;
	var $alt_address_street_3;

       // This is the list of the functions to run when importing
        var $special_functions =  array(
		"get_names_from_full_name"
		,"add_create_account"
		//,"add_salutation"
		//,"add_lead_source"
		,"add_birthdate"
		,"add_do_not_call"
		,"add_email_opt_out"
		,"add_primary_address_streets"
		,"add_alt_address_streets"
		);

	function add_salutation()
	{
		if ( isset($this->salutation) &&
			! isset( $app_list_strings['salutation_dom'][ $this->salutation ]) )
		{
			$this->salutation = '';
		}
	}
	
	function add_lead_source()
	{
		if ( isset($this->lead_source) &&
			! isset( $app_list_strings['lead_source_dom'][ $this->lead_source ]) )
		{
			$this->lead_source = '';
		}

	}


	function add_birthdate()
	{
		if ( isset($this->birthdate))
		{
			if (! preg_match('/^\d{4}-\d{1,2}-\d{1,2}$/',$this->birthdate))
			{
				$this->birthdate = '';
			}
		}

	}

	function add_do_not_call()
	{
		if ( isset($this->do_not_call) && $this->do_not_call != 'on')
		{
			$this->do_not_call = '';
		}

	}

	function add_email_opt_out()
	{
		if ( isset($this->email_opt_out) && $this->email_opt_out != 'on')
		{
			$this->email_opt_out = '';
		}
	}

	function add_primary_address_streets()
	{
		if ( isset($this->primary_address_street_2))
		{
			$this->primary_address_street .= " ". $this->primary_address_street_2;
		}

		if ( isset($this->primary_address_street_3))
		{
			$this->primary_address_street .= " ". $this->primary_address_street_3;
		}
	}

	function add_alt_address_streets()
	{
		if ( isset($this->alt_address_street_2))
		{
			$this->alt_address_street .= " ". $this->alt_address_street_2;
		}

		if ( isset($this->alt_address_street_3))
		{
			$this->alt_address_street .= " ". $this->alt_address_street_3;
		}

	}

        function get_names_from_full_name()
        {
		if ( ! isset($this->full_name))
		{
			return;
		}
                $arr = array();

                $name_arr = preg_split('/\s+/',$this->full_name);

                if ( count($name_arr) == 1)
                {
                        $this->last_name = $this->full_name;
                }

                $this->first_name = array_shift($name_arr);

                $this->last_name = join(' ',$name_arr);

        }

        function add_create_account()
        {
		// global is defined in UsersLastImport.php
		global $imported_ids;
                global $current_user;

		if ( (! isset($this->account_name) || $this->account_name == '') &&
			(! isset($this->account_id) || $this->account_id == '') )
		{
			return; 
		}

                $arr = array();

		// check if it already exists
                $focus = new Account();

		$query = '';

		// if user is defining the account id to be associated with this contact..
		if ( isset($this->account_id) && $this->account_id != '')
		{
                	$query = "select * from {$focus->table_name} WHERE id='{$this->account_id}'";
		}	
		// else user is defining the account name to be associated with this contact..
		else 
		{
                	$query = "select * from {$focus->table_name} WHERE name='{$this->account_name}'";
		}

                $this->log->info($query);

                $result = mysql_query($query)
                       or die("Error selecting sugarbean: ".mysql_error());

                $row = $this->db->fetchByAssoc($result, -1, false);

		// we found a row with that id
                if (isset($row['id']) && $row['id'] != -1)
                {
                        // if it exists but was deleted, just remove it entirely
                        if ( isset($row['deleted']) && $row['deleted'] == 1)
                        {
                                $query2 = "delete from {$focus->table_name} WHERE id='". $row['id']."'";

                                $this->log->info($query2);

                                $result2 = mysql_query($query2)
                                        or die("Error deleting existing sugarbean: ".mysql_error());

                        }
			// else just use this id to link the contact to the account
                        else
                        {
                                $focus->id = $row['id'];
                        }
                }

		// if we didnt find the account, so create it
                if (! isset($focus->id) || $focus->id == '')
                {
                        $focus->name = $this->account_name;
                        $focus->assigned_user_id = $current_user->id;
                        $focus->modified_user_id = $current_user->id;

			if ( isset($this->account_id)  &&
                                $this->account_id != '')
                        {
				$focus->new_with_id = true;
                                $focus->id = $this->account_id;
                        }

                        $focus->save();
			// avoid duplicate mappings:
			if (! isset( $imported_ids[$this->account_id]) )
			{
				// save the new account as a users_last_import
                		$last_import = new UsersLastImport();
                		$last_import->assigned_user_id = $current_user->id;
                		$last_import->bean_type = "Accounts";
                		$last_import->bean_id = $focus->id;
                		$last_import->save();
				$imported_ids[$this->account_id] = 1;
			}
                }

		// now just link the account
                $this->account_id = $focus->id;

        }

	// This is the list of fields that can be imported
	// some of these don't map directly to columns in the db
	var $importable_fields =  array(
		"id"=>1,
		"first_name"=>1,
		"last_name"=>1,
                "salutation"=>1,
                "lead_source"=>1,
                "birthdate"=>1,
                "do_not_call"=>1,
                "email_opt_out"=>1,
		"primary_address_street_2"=>1,
		"primary_address_street_3"=>1,
		"alt_address_street_2"=>1,
		"alt_address_street_3"=>1,
                "full_name"=>1,
                "account_name"=>1,
                "account_id"=>1,
		"title"=>1,
		"department"=>1,
		"birthdate"=>1,
		"do_not_call"=>1,
		"phone_home"=>1,
		"phone_mobile"=>1,
		"phone_work"=>1,
		"phone_other"=>1,
		"phone_fax"=>1,
		"email1"=>1,
		"email2"=>1,
		"yahoo_id"=>1,
		"assistant"=>1,
		"assistant_phone"=>1,
		"primary_address_street"=>1,
		"primary_address_city"=>1,
		"primary_address_state"=>1,
		"primary_address_postalcode"=>1,
		"primary_address_country"=>1,
		"alt_address_street"=>1,
		"alt_address_city"=>1,
		"alt_address_state"=>1,
		"alt_address_postalcode"=>1,
		"alt_address_country"=>1,
		"description"=>1,
		);
		
	function ImportContact() {
		$this->log = LoggerManager::getLogger('import_contact');
		$this->db = new PearDatabase();
	}

}



?>
