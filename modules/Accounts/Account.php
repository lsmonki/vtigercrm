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
 * $Header:  vtiger_crm/sugarcrm/modules/Accounts/Account.php,v 1.1 2004/08/17 15:02:56 gjayakrishnan Exp $
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('database/DatabaseConnection.php');
require_once('data/SugarBean.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Cases/Case.php');
require_once('modules/Calls/Call.php');
require_once('modules/Notes/Note.php');
require_once('modules/Emails/Email.php');

// Account is used to store account information.
class Account extends SugarBean {
	var $log;

	// Stored fields
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $assigned_user_id;
	var $annual_revenue;
	var $billing_address_street;
	var $billing_address_city;
	var $billing_address_state;
	var $billing_address_country;
	var $billing_address_postalcode;
	var $description;
	var $email1;
	var $email2;
	var $employees;
	var $id;
	var $industry;
	var $name;
	var $ownership;
	var $parent_id;
	var $phone_alternate;
	var $phone_fax;
	var $phone_office;
	var $rating;
	var $shipping_address_street;
	var $shipping_address_city;
	var $shipping_address_state;
	var $shipping_address_country;
	var $shipping_address_postalcode;
	var $sic_code;
	var $ticker_symbol;
	var $account_type;
	var $website;

	// These are for related fields
	var $opportunity_id;
	var $case_id;
	var $contact_id;
	var $task_id;
	var $note_id;
	var $meeting_id;
	var $call_id;
	var $email_id;
	var $member_id;
	var $parent_name;
	var $assigned_user_name;
	
	var $table_name = "accounts";

	var $object_name = "Account";

	var $new_schema = true;

	var $column_fields = Array(
		"annual_revenue",
		"billing_address_street",
		"billing_address_city",
		"billing_address_state",
		"billing_address_postalcode",
		"billing_address_country",
		"date_entered",
		"date_modified",
		"modified_user_id",
		"assigned_user_id",
		"description",
		"email1",
		"email2",
		"employees",
		"id",
		"industry",
		"name",
		"ownership",
		"parent_id",
		"phone_alternate",
		"phone_fax",
		"phone_office",
		"rating",
		"shipping_address_street",
		"shipping_address_city",
		"shipping_address_state",
		"shipping_address_postalcode",
		"shipping_address_country",
		"sic_code",
		"ticker_symbol",
		"account_type",
		"website");

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'opportunity_id', 'case_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id', 'parent_name', 'member_id');

	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'name', 'website', 'phone_office', 'billing_address_city', 'assigned_user_name', 'assigned_user_id');

	function Account() {
		$this->log = LoggerManager::getLogger('account');
	}

	function create_tables () {
		$query = 'CREATE TABLE '.$this->table_name.' ( ';
		$query .='id char(36) NOT NULL';
		$query .=', date_entered datetime NOT NULL';
		$query .=', date_modified datetime NOT NULL';
		$query .=', modified_user_id char(36) NOT NULL';
		$query .=', assigned_user_id char(36)';
		$query .=', name char(150)';
		$query .=', parent_id char(36)';
		$query .=', account_type char(25)';
		$query .=', industry char(25)';
		$query .=', annual_revenue char(25)';
		$query .=', phone_fax char(25)';
		$query .=', billing_address_street char(150)';
		$query .=', billing_address_city char(100)';
		$query .=', billing_address_state char(100)';
		$query .=', billing_address_postalcode char(20)';
		$query .=', billing_address_country char(100)';
		$query .=', description text';
		$query .=', rating char(25)';
		$query .=', phone_office char(25)';
		$query .=', phone_alternate char(25)';
		$query .=', email1 char(100)';
		$query .=', email2 char(100)';
		$query .=', website char(255)';
		$query .=', ownership char(100)';
		$query .=', employees char(10)';
		$query .=', sic_code char(10)';
		$query .=', ticker_symbol char(10)';
		$query .=', shipping_address_street char(150)';
		$query .=', shipping_address_city char(100)';
		$query .=', shipping_address_state char(100)';
		$query .=', shipping_address_postalcode char(20)';
		$query .=', shipping_address_country char(100)';
		$query .=', deleted bool NOT NULL default 0';
		$query .=', PRIMARY KEY ( id ) )';

		$this->log->info($query);

		mysql_query($query);
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
		return $this->name;
	}

	/** Returns a list of the associated accounts who are member orgs
	*/
	function get_member_accounts()
	{
		// First, get the list of IDs.
		$query = "SELECT a1.id from accounts as a1, accounts as a2 where a2.id=a1.parent_id AND a2.id='$this->id' AND a1.deleted=0";

		return $this->build_related_list($query, new Account());
	}

	/** Returns a list of the associated contacts
	*/
	function get_contacts()
	{
		// First, get the list of IDs.
		$query = "SELECT contact_id as id from accounts_contacts where account_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Contact());
	}

	/** Returns a list of the associated opportunities
	*/
	function get_opportunities()
	{
		// First, get the list of IDs.
		$query = "SELECT opportunity_id as id from accounts_opportunities where account_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Opportunity());
	}

	/** Returns a list of the associated cases
	*/
	function get_cases()
	{
		// First, get the list of IDs.
		$query = "SELECT id from cases where account_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new aCase());
	}

	/** Returns a list of the associated tasks
	*/
	function get_tasks()
	{
		// First, get the list of IDs.
		$query = "SELECT id from tasks where parent_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Task());
	}

	/** Returns a list of the associated notes
	*/
	function get_notes()
	{
		// First, get the list of IDs.
		$query = "SELECT id from notes where parent_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new note());
	}

	/** Returns a list of the associated meetings
	*/
	function get_meetings()
	{
		// First, get the list of IDs.
		$query = "SELECT id from meetings where parent_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Meeting());
	}

	/** Returns a list of the associated calls
	*/
	function get_calls()
	{
		// First, get the list of IDs.
		$query = "SELECT id from calls where parent_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Call());
	}

	/** Returns a list of the associated emails
	*/
	function get_emails()
	{
		// First, get the list of IDs.
		$query = "SELECT id from emails where parent_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Email());
	}

	function save_relationship_changes($is_update)
    {
    	if($this->member_id != "")
    	{
    		$this->set_account_member_account_relationship($this->id, $this->member_id);
    	}
    	if($this->opportunity_id != "")
    	{
    		$this->set_account_opportunity_relationship($this->id, $this->opportunity_id);
    	}
    	if($this->case_id != "")
    	{
    		$this->set_account_case_relationship($this->id, $this->case_id);
    	}
		if($this->contact_id != "")
    	{
    		$this->set_account_contact_relationship($this->id, $this->contact_id);
    	}
    	if($this->task_id != "")
    	{
    		$this->set_account_task_relationship($this->id, $this->task_id);
    	}
    	if($this->note_id != "")
    	{
    		$this->set_account_note_relationship($this->id, $this->note_id);
    	}
    	if($this->meeting_id != "")
    	{
    		$this->set_account_meeting_relationship($this->id, $this->meeting_id);
    	}
    	if($this->call_id != "")
    	{
    		$this->set_account_call_relationship($this->id, $this->call_id);
    	}
    	if($this->email_id != "")
    	{
    		$this->set_account_email_relationship($this->id, $this->email_id);
    	}
    }

	function set_account_opportunity_relationship($account_id, $opportunity_id)
	{
		$query = "insert into accounts_opportunities set id='".create_guid()."', opportunity_id='$opportunity_id', account_id='$account_id'";
		mysql_query($query) or die("Error setting account to opportunity relationship: ".mysql_error());
	}

	function clear_account_opportunity_relationship($account_id)
	{
		$query = "update accounts_opportunities set deleted=1 where account_id='$account_id' and deleted=0";
		mysql_query($query) or die("Error clearing account to opportunity relationship: ".mysql_error());
	}

	function set_account_case_relationship($account_id, $case_id)
	{
		$query = "update cases set account_id='$account_id' where id='$case_id'";
		mysql_query($query) or die("Error setting account to case relationship: ".mysql_error());
	}

	function clear_account_case_relationship($account_id)
	{
		$query = "update cases set deleted=1 where account_id='$account_id' and deleted=0";
		mysql_query($query) or die("Error clearing account to case relationship: ".mysql_error());
	}

	function set_account_contact_relationship($account_id, $contact_id)
	{
		$query = "insert into accounts_contacts set id='".create_guid()."', contact_id='$contact_id', account_id='$account_id'";
		mysql_query($query) or die("Error setting account to contact relationship: ".mysql_error()."<BR>$query");
	}

	function clear_account_contact_relationship($account_id)
	{
		$query = "UPDATE accounts_contacts set deleted=1 where account_id='$account_id' and deleted=0";
		mysql_query($query) or die("Error clearing account to contact relationship: ".mysql_error());
	}

	function set_account_task_relationship($account_id, $task_id)
	{
		$query = "UPDATE tasks set parent_id='$account_id', parent_type='Account' where id='$task_id'";
		mysql_query($query) or die("Error setting account to task relationship: ".mysql_error());
	}

	function clear_account_task_relationship($account_id)
	{
		$query = "update tasks set parent_id='', parent_type='' where parent_id='$account_id' and deleted=0";
		mysql_query($query) or die("Error clearing account to task relationship: ".mysql_error());
	}

	function set_account_note_relationship($account_id, $note_id)
	{
		$query = "UPDATE notes set parent_id='$account_id', parent_type='Account' where id='$note_id'";
		mysql_query($query) or die("Error setting account to note relationship: ".mysql_error());
	}

	function clear_account_note_relationship($account_id)
	{
		$query = "update notes set parent_id='', parent_type='' where parent_id='$account_id' and deleted=0";
		mysql_query($query) or die("Error clearing account to note relationship: ".mysql_error());
	}

	function set_account_meeting_relationship($account_id, $meeting_id)
	{
		$query = "UPDATE meetings set parent_id='$account_id', parent_type='Account' where id='$meeting_id'";
		mysql_query($query) or die("Error setting account to meeting relationship: ".mysql_error());
	}

	function clear_account_meeting_relationship($account_id)
	{
		$query = "update meetings set parent_id='', parent_type='' where parent_id='$account_id' and deleted=0";
		mysql_query($query) or die("Error clearing account to meeting relationship: ".mysql_error());
	}

	function set_account_call_relationship($account_id, $call_id)
	{
		$query = "UPDATE calls set parent_id='$account_id', parent_type='Account' where id='$call_id'";
		mysql_query($query) or die("Error setting account to call relationship: ".mysql_error());
	}

	function clear_account_call_relationship($account_id)
	{
		$query = "update calls set parent_id='', parent_type='' where parent_id='$account_id' and deleted=0";
		mysql_query($query) or die("Error clearing account to call relationship: ".mysql_error());
	}

	function set_account_email_relationship($account_id, $email_id)
	{
		$query = "UPDATE emails set parent_id='$account_id', parent_type='Account' where id='$email_id'";
		mysql_query($query) or die("Error setting account to email relationship: ".mysql_error());
	}

	function clear_account_email_relationship($account_id)
	{
		$query = "update emails set parent_id='', parent_type='' where parent_id='$account_id' and deleted=0";
		mysql_query($query) or die("Error clearing account to email relationship: ".mysql_error());
	}

	function set_account_member_account_relationship($account_id, $member_id)
	{
		$query = "update accounts set parent_id='$account_id' where id='$member_id' and deleted=0";
		mysql_query($query) or die("Error setting account to member account relationship: ".mysql_error());
	}

	function clear_account_account_relationship($account_id)
	{
		$query = "update accounts set parent_id='' where parent_id='$account_id' and deleted=0";
		mysql_query($query) or die("Error clearing account to account relationship: ".mysql_error());
	}

	function clear_account_member_account_relationship($account_id)
	{
		$query = "update accounts set parent_id='' where id='$account_id' and deleted=0";
		mysql_query($query) or die("Error clearing account to member account relationship: ".mysql_error());
	}

	function mark_relationships_deleted($id)
	{
		$this->clear_account_account_relationship($id);
		$this->clear_account_contact_relationship($id);
		$this->clear_account_opportunity_relationship($id);
		$this->clear_account_case_relationship($id);
		$this->clear_account_task_relationship($id);
		$this->clear_account_note_relationship($id);
		$this->clear_account_meeting_relationship($id);
		$this->clear_account_call_relationship($id);
		$this->clear_account_email_relationship($id);
	}

	// This method is used to provide backward compatibility with old data that was prefixed with http://
	// We now automatically prefix http://
	function remove_redundant_http()
	{
		if(eregi("http://", $this->website))
		{
			$this->website = substr($this->website, 7);
		}
	}

	function fill_in_additional_list_fields()
	{
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);

		$this->remove_redundant_http();
	}

	function fill_in_additional_detail_fields()
	{
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);

		$query = "SELECT a1.name from accounts as a1, accounts as a2 where a1.id = a2.parent_id and a2.id = '$this->id' and a1.deleted=0";
		$result = mysql_query($query) or die("Error filling in additional detail fields: ".mysql_error());

		// Get the id and the name.
		$row = mysql_fetch_assoc($result);

		if($row != null)
		{
			$this->parent_name = $row['name'];
		}
		else
		{
			$this->parent_name = '';
		}

		$this->remove_redundant_http();
	}

}



?>
