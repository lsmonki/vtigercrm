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
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');

$imported_ids = array();

// Contact is used to store customer information.
class UsersLastImport extends SugarBean 
{
	var $log;
	var $db;

	// Stored fields
	var $id;
	var $assigned_user_id;
	var $bean_type;
	var $bean_id;

	var $table_name = "users_last_import";
	var $object_name = "UsersLastImport";
	var $column_fields = Array("id"
		,"assigned_user_id"
		,"bean_type"
		,"bean_id"
		,"deleted"
		);

	var $new_schema = true;

	var $additional_column_fields = Array();
		
	function UsersLastImport() {
		$this->log = LoggerManager::getLogger('UsersLastImport');
		$this->db = new PearDatabase();
	}

	function fill_in_additional_detail_fields()
	{
		
	}

	function create_tables () 
	{ 
		$query = "CREATE TABLE $this->table_name ("; 
		$query .='id char(36) NOT NULL'; 
		$query .=', assigned_user_id char(36)'; 
		$query .=', bean_type char(36)'; 
		$query .=', bean_id char(36)'; 
                $query .=', deleted bool NOT NULL default 0';
		$query .=', PRIMARY KEY ( ID ) )'; 

		 

		$this->db->query($query,true,"Error creating import to sugarbean relationship table: "); 

		// Create the indexes 
		$this->create_index("create index idx_user_id on ".$this->table_name." (assigned_user_id)"); 
	}

        function drop_tables () 
	{ 
		$query = 'DROP TABLE IF EXISTS '.$this->table_name; 
		 
		$this->db->query($query); 
	}


	function mark_deleted_by_user_id($user_id)
        {

                $query = "UPDATE $this->table_name set deleted=1 where assigned_user_id='$user_id'";
                $this->db->query($query,true,"Error marking last imported accounts deleted: ");

        }


	function create_list_query(&$order_by, &$where)
	{
		global $current_user;
		$query = '';

		if ($this->bean_type == 'Contacts')
		{
			$query = "SELECT distinct
				accounts.name as account_name,
				accounts.id as account_id,
				contacts.id,
				contacts.assigned_user_id,
				contacts.yahoo_id,
				contacts.first_name,
				contacts.last_name,
				contacts.phone_work,
				contacts.title,
				contacts.email1,
                                users.user_name as assigned_user_name
				FROM contacts, users_last_import
                                LEFT JOIN users
                                ON contacts.assigned_user_id=users.id
				LEFT JOIN accounts_contacts
				ON contacts.id=accounts_contacts.contact_id
				LEFT JOIN accounts
				ON accounts_contacts.account_id=accounts.id
				WHERE 
				users_last_import.assigned_user_id=
					'{$current_user->id}'
				AND users_last_import.bean_type='Contacts'
				AND users_last_import.bean_id=contacts.id
				AND users_last_import.deleted=0
				AND contacts.deleted=0 
				AND users.status='ACTIVE'";

		} 
		else if ($this->bean_type == 'Accounts')
		{
			$query = "SELECT distinct accounts.*,
                                users.user_name as assigned_user_name
				FROM accounts, users_last_import
                                LEFT JOIN users
                                ON accounts.assigned_user_id=users.id
				WHERE 
				users_last_import.assigned_user_id=
					'{$current_user->id}'
				AND users_last_import.bean_type='Accounts'
				AND users_last_import.bean_id=accounts.id
				AND users_last_import.deleted=0
				AND accounts.deleted=0
				AND users.status='ACTIVE'";
		} 
		else if ($this->bean_type == 'Opportunities')
		{
		
			$query = "SELECT distinct
                                accounts.id as account_id,
                                accounts.name as account_name,
                                users.user_name as assigned_user_name,
                                opportunities.*
                                FROM opportunities, users_last_import
                                LEFT JOIN users
                                ON opportunities.assigned_user_id=users.id
                                LEFT JOIN accounts_opportunities
                                ON opportunities.id=accounts_opportunities.opportunity_id
                                LEFT JOIN accounts
                                ON accounts_opportunities.account_id=accounts.id 
                        	WHERE 
				users_last_import.assigned_user_id=
					'{$current_user->id}'
				AND users_last_import.bean_type='Opportunities'
				AND users_last_import.bean_id=opportunities.id
				AND users_last_import.deleted=0
				AND accounts_opportunities.deleted=0 
				AND accounts.deleted=0 
				AND opportunities.deleted=0 
				AND users.status='ACTIVE'";

	
		}

		if(! empty($order_by))
		{
			$query .= " ORDER BY $order_by";
		}

		return $query;

	}
	function list_view_parse_additional_sections(&$list_form)
	{
		if ($this->bean_type == "Contacts")
		{
                	if( isset($this->yahoo_id) && $this->yahoo_id != '')
			{
                        	$list_form->parse("main.row.yahoo_id");
			}
                	else
			{
                        	$list_form->parse("main.row.no_yahoo_id");
			}
		}
                return $list_form;

        }

	function undo($user_id)
	{
		$count = 0;

		$count += $this->undo_contacts($user_id);
		$count += $this->undo_accounts($user_id);
		$count += $this->undo_opportunities($user_id);

		return $count;
	}

	function undo_contacts($user_id)
	{
		$count = 0;
		$query1 = "select bean_id from users_last_import 
		where assigned_user_id='$user_id' 
		AND bean_type='Contacts' AND deleted=0";

		$this->log->info($query1); 

		$result1 = mysql_query($query1) 
			or die("Error getting last import for undo: ".mysql_error()); 

		while ( $row1 = mysql_fetch_assoc($result1))
		{
			$query2 = "update contacts set contacts.deleted=1 
					where contacts.id='{$row1['bean_id']}'";

			$this->log->info($query2); 

			$result2 = mysql_query($query2) 
				or die("Error undoing last import: ".mysql_error()); 

			$count = $this->db->getAffectedRowCount($result2);

			$query3 = "update accounts_contacts set accounts_contacts.deleted=1 
					where accounts_contacts.contact_id='{$row1['bean_id']}'
					AND accounts_contacts.deleted=0";

			$this->log->info($query3); 

			$result3 = mysql_query($query3) 
				or die("Error undoing last import: ".mysql_error()); 

			$query4 = "update opportunities_contacts set opportunities_contacts.deleted=1 
					where opportunities_contacts.contact_id='{$row1['bean_id']}'
					AND opportunities_contacts.deleted=0";

			$this->log->info($query4); 

			$result4 = mysql_query($query4) 
				or die("Error undoing last import: ".mysql_error()); 

		}
		return $count;
	}

	function undo_accounts($user_id)
	{
		// this should just be a loop foreach module type
		$count = 0;
		$query1 = "select bean_id from users_last_import 
		where assigned_user_id='$user_id' 
		AND bean_type='Accounts' AND deleted=0";

		$this->log->info($query1); 

		$result1 = mysql_query($query1) 
			or die("Error getting last import for undo: ".mysql_error()); 

		while ( $row1 = mysql_fetch_assoc($result1))
		{
			$query2 = "update accounts set accounts.deleted=1 
					where accounts.id='{$row1['bean_id']}'";

			$this->log->info($query2); 

			$result2 = mysql_query($query2) 
				or die("Error undoing last import: ".mysql_error()); 

			$count = $this->db->getAffectedRowCount($result2);

			$query3 = "update accounts_contacts set accounts_contacts.deleted=1 
					where accounts_contacts.account_id='{$row1['bean_id']}'
					AND accounts_contacts.deleted=0";

			$this->log->info($query3); 

			$result3 = mysql_query($query3) 
				or die("Error undoing last import: ".mysql_error()); 

			$query4 = "update accounts_opportunities set accounts_opportunities.deleted=1 
					where accounts_opportunities.account_id='{$row1['bean_id']}'
					AND accounts_opportunities.deleted=0";

			$this->log->info($query4); 

			$result4 = mysql_query($query4) 
				or die("Error undoing last import: ".mysql_error()); 

		}
		return $count;
	}

	function undo_opportunities($user_id)
	{
		// this should just be a loop foreach module type
		$count = 0;
		$query1 = "select bean_id from users_last_import 
		where assigned_user_id='$user_id' 
		AND bean_type='Opportunities' AND deleted=0";

		$this->log->info($query1); 

		$result1 = mysql_query($query1) 
			or die("Error getting last import for undo: ".mysql_error()); 

		while ( $row1 = mysql_fetch_assoc($result1))
		{
			$query2 = "update opportunities set opportunities.deleted=1 
					where opportunities.id='{$row1['bean_id']}'";

			$this->log->info($query2); 

			$result2 = mysql_query($query2) 
				or die("Error undoing last import: ".mysql_error()); 

			$count = $this->db->getAffectedRowCount($result2);

			$query3 = "update opportunities_contacts set opportunities_contacts.deleted=1 
					where opportunities_contacts.opportunity_id='{$row1['bean_id']}'
					AND opportunities_contacts.deleted=0";

			$this->log->info($query3); 

			$result3 = mysql_query($query3) 
				or die("Error undoing last import: ".mysql_error()); 


			$query4 = "update accounts_opportunities set accounts_opportunities.deleted=1 
					where accounts_opportunities.opportunity_id='{$row1['bean_id']}'
					AND accounts_opportunities.deleted=0";

			$this->log->info($query4); 

			$result4 = mysql_query($query4) 
				or die("Error undoing last import: ".mysql_error()); 

		}
		return $count;
	}

}


?>
