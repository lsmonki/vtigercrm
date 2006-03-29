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
	var $column_fields = Array(
					"id"
					,"assigned_user_id"
					,"bean_type"
					,"bean_id"
					,"deleted"
				  );

	var $new_schema = true;

	var $additional_column_fields = Array();

	var $list_fields = Array();
	var $list_fields_name = Array();
	var $list_link_field;
		
	function UsersLastImport() {
		$this->log = LoggerManager::getLogger('UsersLastImport');
		$this->db = new PearDatabase();
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

		$this->db->println("create list bean_type = ".$this->bean_type." where = ".$where);

		if ($this->bean_type == 'Contacts')
		{
				$query = "SELECT distinct crmid,
				account.accountname as account_name,
				contactdetails.contactid,
				contactdetails.accountid,				
				contactdetails.yahooid,
				contactdetails.firstname,
				contactdetails.lastname,
				contactdetails.phone,
				contactdetails.title,
				contactdetails.email,
				users.id as assigned_user_id,
				smownerid,
                                users.user_name as assigned_user_name
				FROM contactdetails
				left join users_last_import on users_last_import.bean_id=contactdetails.contactid
				LEFT JOIN users ON contactdetails.contactid=users.id 
				LEFT JOIN account  ON account.accountid=contactdetails.accountid 
				inner join crmentity on crmentity.crmid=contactdetails.contactid  
				WHERE users_last_import.assigned_user_id= '{$current_user->id}'  
				AND users_last_import.bean_type='Contacts' 
				AND users_last_import.deleted=0  AND crmentity.deleted=0";
			
		} 
		else if ($this->bean_type == 'Accounts')
		{
				$query = "SELECT distinct account.*, accountbillads.city,
                                users.user_name assigned_user_name,
				crmid, smownerid 
				FROM account
				inner join crmentity on crmentity.crmid=account.accountid
				inner join accountbillads on crmentity.crmid=accountbillads.accountaddressid
				left join users_last_import on users_last_import.bean_id=crmentity.crmid
			       	left join users ON crmentity.smownerid=users.id
				WHERE 
				users_last_import.assigned_user_id=
					'{$current_user->id}'
				AND users_last_import.bean_type='Accounts'
				AND users_last_import.deleted=0
				AND crmentity.deleted=0
				AND users.status='Active'";
		} 
		else if ($this->bean_type == 'Potentials')
		{
		
			$query = "SELECT distinct
                                account.accountid account_id,
                                account.accountname account_name,
                                users.user_name assigned_user_name,
				crmentity.crmid, smownerid,
				potential.*
                               FROM potential 
			       inner join account on account.accountid=potential.accountid 
			       inner join  crmentity on crmentity.crmid=potential.potentialid 
			       left join users ON crmentity.smownerid=users.id 
			       left join users_last_import on users_last_import.assigned_user_id=users.id 
			       where users_last_import.assigned_user_id='{$current_user->id}'
				AND users_last_import.bean_type='Potentials'
				AND users_last_import.bean_id=crmentity.crmid
				AND users_last_import.deleted=0
				AND crmentity.deleted=0 
				AND users.status='Active'";

		}
		else if($this->bean_type == 'Leads')
		{
			$query = "SELECT distinct leaddetails.*, crmentity.crmid, leadaddress.phone,leadsubdetails.website,
                                users.user_name assigned_user_name,
				smownerid 
				FROM leaddetails 
				inner join crmentity on crmentity.crmid=leaddetails.leadid 
				inner join leadaddress on crmentity.crmid=leadaddress.leadaddressid 
				inner join leadsubdetails on crmentity.crmid=leadsubdetails.leadsubscriptionid 
				left join users_last_import on users_last_import.bean_id=crmentity.crmid			       	
				left join users ON crmentity.smownerid=users.id
				WHERE 
				users_last_import.assigned_user_id=
					'{$current_user->id}'
				AND users_last_import.bean_type='Leads'
				AND users_last_import.deleted=0
				AND crmentity.deleted=0
				AND users.status='Active'";
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
		$count += $this->undo_leads($user_id);
		$count += $this->undo_products($user_id);

		return $count;
	}

	function undo_contacts($user_id)
	{
		$count = 0;
		$query1 = "select bean_id from users_last_import where assigned_user_id='$user_id' AND bean_type='Contacts' AND deleted=0";

		$this->log->info($query1); 

		$result1 = $this->db->query($query1) or die("Error getting last import for undo: ".mysql_error()); 

		while ( $row1 = $this->db->fetchByAssoc($result1))
		{
			$query2 = "update crmentity set deleted=1 where crmid='{$row1['bean_id']}'";

			$this->log->info($query2); 

			$result2 = $this->db->query($query2) or die("Error undoing last import: ".mysql_error()); 

			$count++;
			
		}
		return $count;
	}

	function undo_leads($user_id)
	{
		$count = 0;
		$query1 = "select bean_id from users_last_import where assigned_user_id='$user_id' AND bean_type='Leads' AND deleted=0";

		$this->log->info($query1); 

		$result1 = $this->db->query($query1) or die("Error getting last import for undo: ".mysql_error()); 

		while ( $row1 = $this->db->fetchByAssoc($result1))
		{
			$query2 = "update crmentity set deleted=1 where crmid='{$row1['bean_id']}'";

			$this->log->info($query2); 

			$result2 = $this->db->query($query2) or die("Error undoing last import: ".mysql_error()); 

			$count++;
			
		}
		return $count;
	}

	function undo_accounts($user_id)
	{
		// this should just be a loop foreach module type
		$count = 0;
		$query1 = "select bean_id from users_last_import where assigned_user_id='$user_id' AND bean_type='Accounts' AND deleted=0";

		$this->log->info($query1); 

		$result1 = $this->db->query($query1) or die("Error getting last import for undo: ".mysql_error()); 

		while ( $row1 = $this->db->fetchByAssoc($result1))
		{
			$query2 = "update crmentity set deleted=1 where crmid='{$row1['bean_id']}'";

			$this->log->info($query2); 

			$result2 = $this->db->query($query2) or die("Error undoing last import: ".mysql_error()); 

			$count++;

		}
		return $count;
	}

	function undo_opportunities($user_id)
	{
		// this should just be a loop foreach module type
		$count = 0;
		$query1 = "select bean_id from users_last_import where assigned_user_id='$user_id' AND bean_type='Potentials' AND deleted=0";

		$this->log->info($query1); 

		$result1 = $this->db->query($query1) or die("Error getting last import for undo: ".mysql_error()); 

		while ( $row1 = $this->db->fetchByAssoc($result1))
		{
			$query2 = "update crmentity set deleted=1 where crmid='{$row1['bean_id']}'";

			$this->log->info($query2); 

			$result2 = $this->db->query($query2) or die("Error undoing last import: ".mysql_error()); 

			$count++;

		}
		return $count;
	}

	function undo_products($user_id)
	{
		$count = 0;
		$query1 = "select bean_id from users_last_import where assigned_user_id='$user_id' AND bean_type='Products' AND deleted=0";

		$this->log->info($query1); 

		$result1 = $this->db->query($query1) or die("Error getting last import for undo: ".mysql_error()); 

		while ( $row1 = $this->db->fetchByAssoc($result1))
		{
			$query2 = "update crmentity set deleted=1 where crmid='{$row1['bean_id']}'";

			$this->log->info($query2); 

			$result2 = $this->db->query($query2) or die("Error undoing last import: ".mysql_error()); 

			$count++;
		}
		return $count;
	}

}


?>
