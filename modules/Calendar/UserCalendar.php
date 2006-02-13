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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Calendar/UserCalendar.php,v 1.7 2005/05/03 13:18:42 saraj Exp $
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('data/CRMEntity.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Accounts/Account.php');
//require_once('modules/Tasks/Task.php');

// Account is used to store account information.
class UserCalendar extends CRMEntity {
	var $log;
	var $db;


	// Stored fields
	var $a_start;
	var $a_end;
	var $t_ignore;
	var $descr;
	var $outside;
	var $subject;
	var $date_entered;
	var $modified_user_id;
	var $contact_id;
	var $creator;
	var $id;

	// These are for related fields
	var $account_name;
	var $contact_name;
	var $contact_id;
	var $task_id;
	var $assigned_user_name;
	
	var $table_name = "calendar";

	var $object_name = "Calendar";

	var $new_schema = true;

	var $column_fields = Array(
		"a_start",
		"a_end",
		"t_ignore",
		"descr",
		"outside",
		"subject",
		"date_entered",
		"contact_id",
		"creator",
		"id");

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'opportunity_id', 'case_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id', 'parent_name', 'member_id' );

	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'descr', 'subject', 'user_name');

	// This is the list of fields that are required.
	var $required_fields =  array("name"=>1);

	function UserCalendar() {
		$this->log =LoggerManager::getLogger('calendar');
		$this->db = new PearDatabase();
	}

	function create_tables () {
		$query = 'CREATE TABLE '.$this->table_name.' ( ';
		$query .='id char(36) NOT NULL';
		$query .=', a_start datetime NOT NULL';
		$query .=', a_end datetime NOT NULL';
		$query .=', t_ignore int(11)';
		$query .=', descr varchar(100)';
		$query .=', outside int(11)';
		$query .=', subject varchar(50)';
		$query .=', contact_id char(36)';
		$query .=', creator char(36)';
		$query .=', date_entered datetime NOT NULL';
		$query .=', PRIMARY KEY ( id ) )';

		

		$this->db->query($query);
	//TODO Clint 4/27 - add exception handling logic here if the table can't be created.

	}

	function drop_tables () {
		$query = 'DROP TABLE IF EXISTS '.$this->table_name;

		

		$this->db->query($query);

	//TODO Clint 4/27 - add exception handling logic here if the table can't be dropped.

	}

	function appointment_delete($id)
        {
                $query = "DELETE FROM $this->table_name where id='$id'";
                $this->db->query($query, true,"Error marking record deleted: ");
	}

	function get_summary_text()
	{
		return $this->name;
	}

	/** Returns a list of the associated accounts who are member orgs
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_member_accounts()
	{
		// First, get the list of IDs.
		$query = "SELECT a1.id from accounts as a1, accounts as a2 where a2.id=a1.parent_id AND a2.id='$this->id' AND a1.deleted=0";

		return $this->build_related_list($query, new Account());
	}

	/** Returns a list of the associated contacts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_contacts()
	{
		// First, get the list of IDs.
		$query = "SELECT contact_id as id from accounts_contacts where account_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Contact());
	}

	/** Returns a list of the associated opportunities
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_opportunities()
	{
		// First, get the list of IDs.
		$query = "SELECT opportunity_id as id from accounts_opportunities where account_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Opportunity());
	}

	/** Returns a list of the associated cases
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_cases()
	{
		// First, get the list of IDs.
		$query = "SELECT id from cases where account_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new aCase());
	}

	/** Returns a list of the associated tasks
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_tasks()
	{
		// First, get the list of IDs.
		$query = "SELECT id from tasks where parent_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Task());
	}

	/** Returns a list of the associated notes
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_notes()
	{
		// First, get the list of IDs.
		$query = "SELECT id from notes where parent_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new note());
	}

	/** Returns a list of the associated meetings
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_meetings()
	{
		// First, get the list of IDs.
		$query = "SELECT id from meetings where parent_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Meeting());
	}

	/** Returns a list of the associated calls
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_calls()
	{
		// First, get the list of IDs.
		$query = "SELECT id from calls where parent_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Call());
	}

	/** Returns a list of the associated emails
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_emails()
	{
		// First, get the list of IDs.
		$query = "SELECT id from emails where parent_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Email());
	}

}

?>
