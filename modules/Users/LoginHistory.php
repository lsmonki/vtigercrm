<?php

/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
* 
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('database/DatabaseConnection.php');
require_once('data/SugarBean.php');
require_once('include/utils.php');

// Contact is used to store customer information.
class LoginHistory extends SugarBean {
	var $log;

	// Stored fields
	var $login_id;
	var $user_name;
	var $user_ip;
	var $login_time;
	var $logout_time;
	var $status;

	var $table_name = "loginhistory";

	var $object_name = "LoginHistory";
	
	var $new_schema = true;

	var $column_fields = Array("id"
		,"login_id"
		,"user_name"
		,"user_ip"
		,"login_time"
		,"logout_time"
		,"status"
		);

	function LoginHistory() {
		$this->log = LoggerManager::getLogger('loginhistory');
	}

	function create_tables () {
		$query = 'CREATE TABLE '.$this->table_name.' ( ';
		$query .='login_id int(11) NOT NULL default 0 auto_increment';
		$query .=', user_name varchar(25) NOT NULL';
		$query .=', user_ip varchar(25) NOT NULL';
		$query .=', login_time datetime NOT NULL default 0';
		$query .=', logout_time datetime NOT NULL default 0';
		$query .=', status enum(\'Signedin\',\'Signedoff\') default \'Signedin\'';
		$query .=', PRIMARY KEY ( login_id ) )';
		
		$this->log->info($query);
		
		mysql_query($query) or die("Error creating table: ".mysql_error());

		//TODO Clint 4/27 - add exception handling logic here if the table can't be created.
		
	}

	function drop_tables () {
		$query = 'DROP TABLE IF EXISTS '.$this->table_name;

		$this->log->info($query);
			
		mysql_query($query);

		//TODO Clint 4/27 - add exception handling logic here if the table can't be dropped.

	}
	
	function get_summary_text()
	{
		return "$this->first_name $this->last_name";
	}
	
	/** Records the Login info */
	function user_login(&$usname,&$usip,&$intime)
	{
		$query = "Insert into loginhistory values ('','$usname','$usip','$intime','','Signedin')";
		$result = mysql_query($query)
                        or die("MySQL error: ".mysql_error());
		
		return $result;
	}
	
	function user_logout(&$usname,&$usip,&$outtime)
	{
		// First, get the list of IDs.
		$query = "Update loginhistory set logout_time='$outtime', status='Signedoff' where user_name='$usname' and user_ip='$usip'";
		$result = mysql_query($query)
                        or die("MySQL error: ".mysql_error());
	}

	/** Returns a list of the associated tasks
	*/
	function get_tasks()
	{
		// First, get the list of IDs.
		$query = "SELECT id from tasks where contact_id='$this->id' AND deleted=0";
		
		return $this->build_related_list($query, new Task());
	}

	/** Returns a list of the associated notes
	*/
	function get_notes()
	{
		// First, get the list of IDs.
		$query = "SELECT id from notes where contact_id='$this->id' AND deleted=0";
		
		return $this->build_related_list($query, new Note());
	}

	/** Returns a list of the associated meetings
	*/
	function get_meetings()
	{
		// First, get the list of IDs.
		$query = "SELECT meeting_id as id from meetings_contacts where contact_id='$this->id' AND deleted=0";
		
		return $this->build_related_list($query, new Meeting());
	}

	/** Returns a list of the associated calls
	*/
	function get_calls()
	{
		// First, get the list of IDs.
		$query = "SELECT call_id as id from calls_contacts where contact_id='$this->id' AND deleted=0";
		
		return $this->build_related_list($query, new Call());
	}

	/** Returns a list of the associated emails
	*/
	function get_emails()
	{
		// First, get the list of IDs.
		$query = "SELECT email_id as id from emails_contacts where contact_id='$this->id' AND deleted=0";
		
		return $this->build_related_list($query, new Email());
	}

	function create_list_query(&$order_by, &$where)
	{
		// Determine if the account name is present in the where clause.
		$account_required = ereg("accounts\.name", $where);
		
		if($account_required)
		{
			$query = "SELECT contacts.id, contacts.assigned_user_id, contacts.yahoo_id, contacts.first_name, contacts.last_name, contacts.phone_work, contacts.title, contacts.email1 FROM contacts, accounts_contacts a_c, accounts ";
			$where_auto = "a_c.contact_id = contacts.id AND a_c.account_id = accounts.id AND a_c.deleted=0 AND accounts.deleted=0 AND contacts.deleted=0";
		}
		else 
		{
			$query = "SELECT id, yahoo_id, contacts.assigned_user_id, first_name, last_name, phone_work, title, email1 FROM contacts ";
			$where_auto = "deleted=0";
		}
		
		if($where != "")
			$query .= "where ($where) AND ".$where_auto;
		else 
			$query .= "where ".$where_auto;		

		$query .= " ORDER BY last_name, first_name";

		return $query;
	}

	function save_relationship_changes($is_update)
    {
    	$this->clear_account_contact_relationship($this->id);
    	
    	if($this->account_id != "")
    	{
    		$this->set_account_contact_relationship($this->id, $this->account_id);    	
    	}
    	if($this->opportunity_id != "")
    	{
    		$this->set_opportunity_contact_relationship($this->id, $this->opportunity_id);    	
    	}
    	if($this->case_id != "")
    	{
    		$this->set_case_contact_relationship($this->id, $this->case_id);    	
    	}
    	if($this->task_id != "")
    	{
    		$this->set_task_contact_relationship($this->id, $this->task_id);    	
    	}
    	if($this->note_id != "")
    	{
    		$this->set_note_contact_relationship($this->id, $this->note_id);    	
    	}
    	if($this->meeting_id != "")
    	{
    		$this->set_meeting_contact_relationship($this->id, $this->meeting_id);    	
    	}
    	if($this->call_id != "")
    	{
    		$this->set_call_contact_relationship($this->id, $this->call_id);    	
    	}
    	if($this->email_id != "")
    	{
    		$this->set_email_contact_relationship($this->id, $this->email_id);    	
    	}
    }

	function clear_account_contact_relationship($contact_id)
	{
		$query = "UPDATE accounts_contacts set deleted=1 where contact_id='$contact_id' and deleted=0";
		mysql_query($query) or die("Error clearing account to contact relationship: ".mysql_error());
	}
    
	function set_account_contact_relationship($contact_id, $account_id)
	{
		$query = "insert into accounts_contacts set id='".create_guid()."', contact_id='$contact_id', account_id='$account_id'";
		mysql_query($query) or die("Error setting account to contact relationship: ".mysql_error()."<BR>$query");
	}

	function set_opportunity_contact_relationship($contact_id, $opportunity_id)
	{
		global $app_list_strings;
		$default = $app_list_strings['opportunity_relationship_type_default_key'];
		$query = "insert into opportunities_contacts set id='".create_guid()."', opportunity_id='$opportunity_id', contact_id='$contact_id', contact_role='$default'";
		mysql_query($query) or die("Error setting account to contact relationship: ".mysql_error()."<BR>$query");
	}

	function clear_opportunity_contact_relationship($contact_id)
	{
		$query = "UPDATE opportunities_contacts set deleted=1 where contact_id='$contact_id' and deleted=0";
		mysql_query($query) or die("Error clearing opportunity to contact relationship: ".mysql_error());
	}
    
	function set_case_contact_relationship($contact_id, $case_id)
	{
		global $app_list_strings;
		$default = $app_list_strings['case_relationship_type_default_key'];
		$query = "insert into contacts_cases set id='".create_guid()."', case_id='$case_id', contact_id='$contact_id', contact_role='$default'";
		mysql_query($query) or die("Error setting account to contact relationship: ".mysql_error()."<BR>$query");
	}

	function clear_case_contact_relationship($contact_id)
	{
		$query = "UPDATE contacts_cases set deleted=1 where contact_id='$contact_id' and deleted=0";
		mysql_query($query) or die("Error clearing case to contact relationship: ".mysql_error());
	}
    
	function set_task_contact_relationship($contact_id, $task_id)
	{
		$query = "UPDATE tasks set contact_id='$contact_id' where id='$task_id'";
		mysql_query($query) or die("Error setting contact to task relationship: ".mysql_error());
	}
	
	function clear_task_contact_relationship($contact_id)
	{
		$query = "UPDATE tasks set deleted=1 where contact_id='$contact_id' and deleted=0";
		mysql_query($query) or die("Error clearing task to contact relationship: ".mysql_error());
	}

	function set_note_contact_relationship($contact_id, $note_id)
	{
		$query = "UPDATE notes set contact_id='$contact_id' where id='$note_id'";
		mysql_query($query) or die("Error setting contact to note relationship: ".mysql_error());
	}
	
	function clear_note_contact_relationship($contact_id)
	{
		$query = "UPDATE notes set deleted=1 where contact_id='$contact_id' and deleted=0";
		mysql_query($query) or die("Error clearing note to contact relationship: ".mysql_error());
	}

	function set_meeting_contact_relationship($contact_id, $meeting_id)
	{
		$query = "insert into meetings_contacts set id='".create_guid()."', meeting_id='$meeting_id', contact_id='$contact_id'";
		mysql_query($query) or die("Error setting meeting to contact relationship: ".mysql_error()."<BR>$query");
	}

	function clear_meeting_contact_relationship($contact_id)
	{
		$query = "UPDATE meetings_contacts set deleted=1 where contact_id='$contact_id' and deleted=0";
		mysql_query($query) or die("Error clearing meeting to contact relationship: ".mysql_error());
	}

	function set_call_contact_relationship($contact_id, $call_id)
	{
		$query = "insert into calls_contacts set id='".create_guid()."', call_id='$call_id', contact_id='$contact_id'";
		mysql_query($query) or die("Error setting meeting to contact relationship: ".mysql_error()."<BR>$query");
	}

	function clear_call_contact_relationship($contact_id)
	{
		$query = "UPDATE calls_contacts set deleted=1 where contact_id='$contact_id' and deleted=0";
		mysql_query($query) or die("Error clearing call to contact relationship: ".mysql_error());
	}

	function set_email_contact_relationship($contact_id, $email_id)
	{
		$query = "insert into emails_contacts set id='".create_guid()."', email_id='$email_id', contact_id='$contact_id'";
		mysql_query($query) or die("Error setting email to contact relationship: ".mysql_error()."<BR>$query");
	}

	function clear_email_contact_relationship($contact_id)
	{
		$query = "UPDATE emails_contacts set deleted=1 where contact_id='$contact_id' and deleted=0";
		mysql_query($query) or die("Error clearing email to contact relationship: ".mysql_error());
	}

	function clear_contact_all_direct_report_relationship($contact_id)
	{
		$query = "UPDATE contacts set reports_to_id='' where reports_to_id='$contact_id' and deleted=0";
		mysql_query($query) or die("Error clearing contact to direct report relationship: ".mysql_error());
	}

	function clear_contact_direct_report_relationship($contact_id)
	{
		$query = "UPDATE contacts set reports_to_id='' where id='$contact_id' and deleted=0";
		mysql_query($query) or die("Error clearing contact to direct report relationship: ".mysql_error());
	}

	function mark_relationships_deleted($id)
	{
		$this->clear_contact_all_direct_report_relationship($id);
		$this->clear_account_contact_relationship($id);
		$this->clear_opportunity_contact_relationship($id);
		$this->clear_case_contact_relationship($id);
		$this->clear_task_contact_relationship($id);
		$this->clear_note_contact_relationship($id);
		$this->clear_call_contact_relationship($id);
		$this->clear_meeting_contact_relationship($id);
		$this->clear_email_contact_relationship($id);
	}
		
	
	function list_view_pare_additional_sections(&$list_form){
		if(isset($this->yahoo_id) && $this->yahoo_id != '')
			$list_form->parse("main.row.yahoo_id");
		else
			$list_form->parse("main.row.no_yahoo_id");
		return $list_form;
		
	}

}



?>
