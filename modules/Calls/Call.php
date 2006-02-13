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
 * $Header:  vtiger_crm/sugarcrm/modules/Calls/Call.php,v 1.6 2004/12/16 10:28:54 jack Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Users/User.php');

// Call is used to store customer information.
class Call extends SugarBean {
	var $log;
	var $db;

	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $assigned_user_id;
	var $description;
	var $name;
	var $status;
	var $date_start;
	var $time_start;
	var $duration_hours;
	var $duration_minutes;
	var $parent_type;
	var $parent_id;
	var $contact_id;
	var $user_id;
	
	var $parent_name;
	var $contact_name;
	var $contact_phone;
	var $contact_email;
	var $account_id;
	var $opportunity_id;
	var $case_id;
	var $assigned_user_name;
	var $required_fields =  array("name"=>1, "date_start"=>2, "time_start"=>3,);
	
	var $default_call_name_values = array('Assemble catalogs', 'Make travel arrangements', 'Send a letter', 'Send contract', 'Send fax', 'Send a follow-up letter', 'Send literature', 'Send proposal', 'Send quote');
	var $minutes_values = array('00'=>'00', '15'=>'15', '30'=>'30', '45'=>'45');

	var $table_name = "calls";
	var $rel_users_table = "calls_users";
	var $rel_contacts_table = "calls_contacts";

	var $object_name = "Call";

	var $column_fields = Array("id"
		, "date_entered"
		, "date_modified"
		, "assigned_user_id"
		, "description"
		, "status"
		, "name"
		, "date_start"
		, "time_start"
		, "duration_hours"
		, "duration_minutes"
		, "parent_type"
		, "parent_id"
		);

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'contact_id', 'user_id', 'contact_name');		

	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'duration_hours', 'status', 'name', 'parent_type', 'parent_name', 'parent_id', 'date_start', 'time_start', 'assigned_user_name', 'assigned_user_id', 'contact_name', 'contact_id');
		
	function Call() {
		$this->log = LoggerManager::getLogger('call');
		$this->db = new PearDatabase();
	}

	var $new_schema = true;

	function create_tables () {
		$query = 'CREATE TABLE '.$this->table_name.' ( ';
		$query .='id char(36) NOT NULL';
		$query .=', date_entered datetime NOT NULL';
		$query .=', date_modified datetime NOT NULL';
		$query .=', assigned_user_id char(36)';
		$query .=', name char(50)';
		$query .=', duration_hours char(2)';
		$query .=', duration_minutes char(2)';
		$query .=', date_start date'; 
		$query .=', time_start time'; 
		$query .=', parent_type char(25)';  
		$query .=', status char(25)';  
		$query .=', parent_id char(36)';
		$query .=', description TEXT';
		$query .=', deleted bool NOT NULL default 0';
		$query .=', PRIMARY KEY ( ID ) )';

		
		
		$this->db->query($query,true,"Error creating table: ");

		//TODO Clint 4/27 - add exception handling logic here if the table can't be created.

		$query = "CREATE TABLE $this->rel_users_table (";
		$query .='id char(36) NOT NULL';
		$query .=', call_id char(36)';
		$query .=', user_id char(36)';
		$query .=', deleted bool NOT NULL default 0';
		$query .=', PRIMARY KEY ( ID ) )';
	
		
		$this->db->query($query,true,"Error creating call/user relationship table: ");
		
		$query = "CREATE TABLE $this->rel_contacts_table (";
		$query .='id char(36) NOT NULL';
		$query .=', call_id char(36)';
		$query .=', contact_id char(36)';
		$query .=', deleted bool NOT NULL default 0';
		$query .=', PRIMARY KEY ( ID ) )';
	
		
		$this->db->query($query,true,"Error creating call/contact relationship table: ");

		// Create the indexes
		$this->create_index("create index idx_call_name on calls (name)");
		$this->create_index("create index idx_usr_call_call on $this->rel_users_table (call_id)");
		$this->create_index("create index idx_usr_call_usr on $this->rel_users_table (user_id)");
		$this->create_index("create index idx_con_call_call on $this->rel_contacts_table (call_id)");
		$this->create_index("create index idx_con_call_con on $this->rel_contacts_table (contact_id)");
	}

	function drop_tables () {
		$query = 'DROP TABLE IF EXISTS '.$this->table_name;

		
			
		$this->db->query($query);

		$query = 'DROP TABLE IF EXISTS '.$this->rel_users_table;

		
			
		$this->db->query($query);

		$query = 'DROP TABLE IF EXISTS '.$this->rel_contacts_table;

		
			
		$this->db->query($query);

		//TODO Clint 4/27 - add exception handling logic here if the table can't be dropped.

	}
	
	/** Returns a list of the associated contacts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_contacts()
	{
		// First, get the list of IDs.
		$query = "SELECT contact_id as id from calls_contacts where call_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Contact());
	}
	
	/** Returns a list of the associated users
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_users()
	{
		// First, get the list of IDs.
		$query = "SELECT user_id as id from calls_users where call_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new User());
	}
	
	function save_relationship_changes($is_update)
    {
		if($this->account_id != "")
    	{
    		$this->set_calls_account_relationship($this->id, $this->account_id);    	
    	}
		if($this->opportunity_id != "")
    	{
    		$this->set_calls_opportunity_relationship($this->id, $this->opportunity_id);    	
    	}
		if($this->case_id != "")
    	{
    		$this->set_calls_case_relationship($this->id, $this->case_id);    	
    	}
		if($this->contact_id != "")
    	{
			$this->mark_call_contact_relationship_deleted($this->contact_id, $this->id);
    		$this->set_calls_contact_invitee_relationship($this->id, $this->contact_id);    	
    	}
		if($this->user_id != "")
    	{
			$this->mark_call_user_relationship_deleted($this->user_id, $this->id);
    		$this->set_calls_user_invitee_relationship($this->id, $this->user_id);    	
    	}
    }
	
	function set_calls_account_relationship($call_id, $account_id)
	{
		//changed the _id to id. Fixes the issue in business card creation
		$query = "update $this->table_name set parent_id='$account_id', parent_type='Accounts' where id='$call_id'";
		$this->db->query($query,true,"Error setting account to call relationship: "."<BR>$query");
	}

	function set_calls_opportunity_relationship($call_id, $opportunity_id)
	{
		$query = "update $this->table_name set parent_id='$opportunity_id', parent_type='Opportunities' where _id='$call_id'";
		$this->db->query($query,true,"Error setting opportunity to call relationship: "."<BR>$query");
	}

	function set_calls_case_relationship($call_id, $case_id)
	{
		$query = "update $this->table_name set parent_id='$case_id', parent_type='Cases' where _id='$call_id'";
		$this->db->query($query,true,"Error setting case to call relationship: "."<BR>$query");
	}

	function set_calls_contact_invitee_relationship($call_id, $contact_id)
	{
		$query = "insert into $this->rel_contacts_table set id='".create_guid()."', contact_id='$contact_id', call_id='$call_id'";
		$this->db->query($query,true,"Error setting call to contact relationship: "."<BR>$query");
	}
	
	function set_calls_user_invitee_relationship($call_id, $user_id)
	{
		$query = "insert into $this->rel_users_table set id='".create_guid()."', user_id='$user_id', call_id='$call_id'";
		$this->db->query($query,true,"Error setting call to user relationship: "."<BR>$query");
	}

	function get_summary_text()
	{
		return "$this->name";
	}

	function create_list_query(&$order_by, &$where)
	{
		$contact_required = ereg("contacts", $where);

		if($contact_required)
		{
			$query = "SELECT calls.id, calls.name, calls.assigned_user_id, calls.status, calls.parent_type, calls.parent_id, calls.date_start, calls.time_start, contacts.first_name, contacts.last_name FROM contacts, calls, calls_contacts ";
			$where_auto = "calls_contacts.contact_id = contacts.id AND calls_contacts.call_id = calls.id AND calls.deleted=0 AND contacts.deleted=0";
		}
		else 
		{
			$query = 'SELECT id, name, assigned_user_id, status, parent_type, parent_id, date_start, time_start FROM calls ';
			$where_auto = "deleted=0";
		}
		
		if($where != "")
			$query .= "where $where AND ".$where_auto;
		else 
			$query .= "where ".$where_auto;		

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else 
			$query .= " ORDER BY calls.name";			

		return $query;
	}

	function create_export_query(&$order_by, &$where)
        {
                $contact_required = ereg("contacts", $where);

                if($contact_required)
                {
                        $query = "SELECT calls.*, contacts.first_name, contacts.last_name FROM contacts, calls, calls_contacts ";
                        $where_auto = "calls_contacts.contact_id = contacts.id AND calls_contacts.call_id = calls.id AND calls.deleted=0 AND contacts.deleted=0";
                }
                else
                {
                        $query = 'SELECT * FROM calls ';
                        $where_auto = "deleted=0";
                }

                if($where != "")
                        $query .= "where $where AND ".$where_auto;
                else
                        $query .= "where ".$where_auto;

                if($order_by != "")
                        $query .= " ORDER BY $order_by";
                else
                        $query .= " ORDER BY calls.name";

                return $query;
        }



	function fill_in_additional_list_fields()
	{
		$this->fill_in_additional_detail_fields();	
	}
	
	function fill_in_additional_detail_fields()
	{
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);

		$query  = "SELECT c.first_name, c.last_name, c.phone_work, c.email1, c.id FROM contacts as c, calls_contacts as c_c ";
		$query .= "WHERE c_c.contact_id=c.id AND c_c.call_id='$this->id' AND c_c.deleted=0 AND c.deleted=0";
		$result = $this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = Array();
		$row = $this->db->fetchByAssoc($result);
		
		$this->log->info("additional call fields $query");
		
		if($row != null)
		{
			$this->contact_name = return_name($row, 'first_name', 'last_name');				
			$this->contact_phone = $row['phone_work'];
			$this->contact_id = $row['id'];
			$this->contact_email = $row['email1'];
			$this->log->debug("Call($this->id): contact_name = $this->contact_name");
			$this->log->debug("Call($this->id): contact_phone = $this->contact_phone");
			$this->log->debug("Call($this->id): contact_id = $this->contact_id");
			$this->log->debug("Call($this->id): contact_email1 = $this->contact_email");
		}
		else {
			$this->contact_name = '';
			$this->contact_phone = '';
			$this->contact_id = '';
			$this->contact_email = '';
			$this->log->debug("Call($this->id): contact_name = $this->contact_name");
			$this->log->debug("Call($this->id): contact_phone = $this->contact_phone");
			$this->log->debug("Call($this->id): contact_id = $this->contact_id");
			$this->log->debug("Call($this->id): contact_email1 = $this->contact_email");
		}
		if ($this->parent_type == "Opportunities") {
			require_once("modules/Opportunities/Opportunity.php");
			$parent = new Opportunity();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";
			$result = $this->db->query($query,true," Error filling in additional detail fields: ");
	
			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);
			
			if($row != null)
			{
				$this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Cases") {
			require_once("modules/Cases/Case.php");
			$parent = new aCase();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";
			$result = $this->db->query($query,true," Error filling in additional detail fields: ");
	
			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);
			
			if($row != null)
			{
				$this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Accounts") {
			require_once("modules/Accounts/Account.php");
			$parent = new Account();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";
			$result = $this->db->query($query,true," Error filling in additional detail fields: ");
	
			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);
			
			if($row != null)
			{
				$this->parent_name = stripslashes($row['name']);
			}
		}
		else {
			$this->parent_name = '';
		}			
	}
	
	function mark_relationships_deleted($id)
	{
		$query = "UPDATE $this->rel_users_table set deleted=1 where call_id='$id'";
		$this->db->query($query,true,"Error marking record deleted: ");

		$query = "UPDATE $this->rel_contacts_table set deleted=1 where call_id='$id'";
		$this->db->query($query,true,"Error marking record deleted: ");
	}
	
	function mark_call_contact_relationship_deleted($contact_id, $call_id)
	{
		$query = "UPDATE $this->rel_contacts_table set deleted=1 where contact_id='$contact_id' and call_id='$call_id' and deleted=0";
		$this->db->query($query,true,"Error clearing call to contact relationship: ");
	}

	function mark_call_user_relationship_deleted($user_id, $call_id)
	{
		$query = "UPDATE $this->rel_users_table set deleted=1 where user_id='$user_id' and call_id='$call_id' and deleted=0";
		$this->db->query($query,true,"Error clearing call to user relationship: ");
	}
	function get_list_view_data(){
		$call_fields = $this->get_list_view_array();
		global $app_list_strings, $focus, $action, $currentModule;
		if (isset($this->parent_type) && $this->parent_type != null)
		{ 
			$call_fields['PARENT_MODULE'] = $this->parent_type;
		}
		if ($this->status == "Planned") {
			$call_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=$focus->id&action=Save&module=Calls&record=$this->id&status=Held'>X</a>";
		}	
		return $call_fields;
	}
}
?>
