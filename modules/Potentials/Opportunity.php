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
 * $Header:  vtiger_crm/sugarcrm/modules/Opportunities/Opportunity.php,v 1.3 2004/10/29 09:55:09 jack Exp $ 
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
require_once('modules/Tasks/Task.php');
require_once('modules/Notes/Note.php');
require_once('modules/Calls/Call.php');
require_once('modules/Emails/Email.php');
require_once('include/utils.php');

// Opportunity is used to store customer information.
class Opportunity extends SugarBean {
	var $log;
	var $db;
	// Stored fields
	var $id;
	var $lead_source;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $assigned_user_id;
	var $description;
	var $name;
	var $opportunity_type;
	var $amount;
	var $date_closed;
	var $next_step;
	var $sales_stage;
	var $probability;

	// These are related
	var $account_name;
	var $account_id;
	var $contact_id;
	var $task_id;
	var $note_id;
	var $meeting_id;
	var $call_id;
	var $email_id;
	var $assigned_user_name;

	var $table_name = "opportunities";
	var $rel_account_table = "accounts_opportunities";
	var $rel_opportunity_table = "opportunities_contacts";

	var $object_name = "Opportunity";

	var $column_fields = Array("id"
		, "name"
		, "opportunity_type"
		, "lead_source"
		, "amount"
		, "date_entered"
		, "date_modified"
		, "modified_user_id"
		, "assigned_user_id"
		, "date_closed"
		, "next_step"
		, "sales_stage"
		, "probability"
		, "description"
		);

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'account_name', 'account_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id');

	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'name', 'account_name', 'date_closed', 'amount', 'assigned_user_name', 'assigned_user_id');


	function Opportunity() {
		$this->log = LoggerManager::getLogger('opportunity');
		$this->db = new PearDatabase();
	}

	var $new_schema = true;

	function create_tables () {
		$query = 'CREATE TABLE '.$this->table_name.' ( ';
		$query .='id char(36) NOT NULL';
		$query .=', date_entered datetime NOT NULL';
		$query .=', date_modified datetime NOT NULL';
		$query .=', modified_user_id char(36) NOT NULL';
		$query .=', assigned_user_id char(36)';
		$query .=', deleted bool NOT NULL default 0';
		$query .=', name char(50)';
		$query .=', opportunity_type char(25)';
		$query .=', lead_source char(25)';
		$query .=', amount char(25)';
		$query .=', date_closed date';
		$query .=', next_step char(25)';
		$query .=', sales_stage char(25)';
		$query .=', probability char(3)';
		$query .=', description TEXT';
		$query .=', PRIMARY KEY ( ID ) )';

		$this->db->query($query, true, "Error creating table:" );



		//TODO Clint 4/27 - add exception handling logic here if the table can't be created.

		$query = "CREATE TABLE $this->rel_account_table (";
		$query .='id char(36) NOT NULL';
		$query .=', opportunity_id char(36)';
		$query .=', account_id char(36)';
		$query .=', deleted bool NOT NULL default 0';
		$query .=', PRIMARY KEY ( ID ) )';

		$this->db->query($query, true, "Error creating account/opportunity relationship table: " );


		$query = "CREATE TABLE $this->rel_opportunity_table (";
		$query .='id char(36) NOT NULL';
		$query .=', contact_id char(36)';
		$query .=', opportunity_id char(36)';
		$query .=', contact_role char(50)';
		$query .=', deleted bool NOT NULL default 0';
		$query .=', PRIMARY KEY ( ID ) )';
		$this->db->query($query, true,"Error creating opportunity/contact relationship table: ");

		// Create the indexes
		$this->create_index("create index idx_opp_name on opportunities (name)");
		$this->create_index("create index idx_acc_opp_acc on accounts_opportunities (account_id)");
		$this->create_index("create index idx_acc_opp_opp on accounts_opportunities (opportunity_id)");
		$this->create_index("create index idx_con_opp_con on opportunities_contacts (contact_id)");
		$this->create_index("create index idx_con_opp_opp on opportunities_contacts (opportunity_id)");
	}

	function drop_tables () {
		$query = 'DROP TABLE IF EXISTS '.$this->table_name;

		$this->db->query($query);

		$query = 'DROP TABLE IF EXISTS '.$this->rel_account_table;

		$this->db->query($query);

		$query = 'DROP TABLE IF EXISTS '.$this->rel_opportunity_table;

		$this->db->query($query);

		//TODO Clint 4/27 - add exception handling logic here if the table can't be dropped.

	}

	function get_summary_text()
	{
		return "$this->name";
	}

	function create_list_query($order_by, $where)
	{
		// Determine if the account name is present in the where clause.
		$account_required = ereg("accounts\.name", $where);

		if($account_required)
		{
			$query = "SELECT opportunities.id, opportunities.assigned_user_id, opportunities.name, opportunities.date_closed FROM opportunities, accounts_opportunities a_o, accounts ";
			$where_auto = "a_o.opportunity_id = opportunities.id AND a_o.account_id = accounts.id AND a_o.deleted=0 AND accounts.deleted=0 AND opportunities.deleted=0";
		}
		else
		{
			$query = 'SELECT id, name, assigned_user_id, date_closed FROM opportunities ';
			$where_auto = 'opportunities.deleted=0';
		}

		if($where != "")
			$query .= "where $where AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if($order_by != "")
			$query .= " ORDER BY opportunities.$order_by";
		else
			$query .= " ORDER BY opportunities.name";



		return $query;
	}


        function create_export_query($order_by, $where)
        {

                                $query = "SELECT
                                opportunities.*,
                                accounts.name as account_name,
                                users.user_name as assigned_user_name
                                FROM opportunities
                                LEFT JOIN users
                                ON opportunities.assigned_user_id=users.id
                                LEFT JOIN accounts_opportunities
                                ON opportunities.id=accounts_opportunities.opportunity_id
                                LEFT JOIN accounts
                                ON accounts_opportunities.account_id=accounts.id ";
                        $where_auto = "accounts_opportunities.deleted=0 AND accounts.deleted=0 AND opportunities.deleted=0 AND users.status='ACTIVE'";

                if($where != "")
                        $query .= "where $where AND ".$where_auto;
                else
                        $query .= "where ".$where_auto;

                if($order_by != "")
                        $query .= " ORDER BY $order_by";
                else
                        $query .= " ORDER BY opportunities.name";
                return $query;
        }



	function save_relationship_changes($is_update)
    {
    	$this->clear_opportunity_account_relationship($this->id);

		if($this->account_id != "")
    	{
    		$this->set_opportunity_account_relationship($this->id, $this->account_id);
    	}
    	if($this->contact_id != "")
    	{
    		$this->set_opportunity_contact_relationship($this->id, $this->contact_id);
    	}
    	if($this->task_id != "")
    	{
    		$this->set_opportunity_task_relationship($this->id, $this->task_id);
    	}
    	if($this->note_id != "")
    	{
    		$this->set_opportunity_note_relationship($this->id, $this->note_id);
    	}
    	if($this->meeting_id != "")
    	{
    		$this->set_opportunity_meeting_relationship($this->id, $this->meeting_id);
    	}
    	if($this->call_id != "")
    	{
    		$this->set_opportunity_call_relationship($this->id, $this->call_id);
    	}
    	if($this->email_id != "")
    	{
    		$this->set_opportunity_email_relationship($this->id, $this->email_id);
    	}
    }

	function set_opportunity_account_relationship($opportunity_id, $account_id)
	{
		$query = "insert into accounts_opportunities set id='".create_guid()."', opportunity_id='$opportunity_id', account_id='$account_id'";
		$this->db->query($query, true, "Error setting account to contact relationship: ");

	}

	function clear_opportunity_account_relationship($opportunity_id)
	{
		$query = "UPDATE accounts_opportunities set deleted=1 where opportunity_id='$opportunity_id' and deleted=0";
		$this->db->query($query, true, "Error clearing account to opportunity relationship: ");
	}

	function set_opportunity_contact_relationship($opportunity_id, $contact_id)
	{
		global $app_list_strings;
		$default = $app_list_strings['opportunity_relationship_type_default_key'];
		$query = "insert into opportunities_contacts set id='".create_guid()."', opportunity_id='$opportunity_id', contact_id='$contact_id', contact_role='$default'";
		$this->db->query($query, true, "Error setting opportunity to contact relationship: ");
	}

	function clear_opportunity_contact_relationship($opportunity_id)
	{
		$query = "UPDATE opportunities_contacts set deleted=1 where opportunity_id='$opportunity_id' and deleted=0";
		$this->db->query($query, true, "Error marking record deleted: ");

	}

	function set_opportunity_task_relationship($opportunity_id, $task_id)
	{
		$query = "UPDATE tasks set parent_id='$opportunity_id', parent_type='Opportunities' where id='$task_id'";
		$this->db->query($query, true, "Error setting opportunity to task relationship: ");

	}

	function clear_opportunity_task_relationship($opportunity_id)
	{
		$query = "UPDATE tasks set parent_id='', parent_type='' where parent_id='$opportunity_id'";
		$this->db->query($query, true, "Error clearing opportunity to task relationship: ");

	}

	function set_opportunity_note_relationship($opportunity_id, $note_id)
	{
		$query = "UPDATE notes set parent_id='$opportunity_id', parent_type='Opportunities' where id='$note_id'";
		$this->db->query($query, true, "Error setting opportunity to note relationship: ");
	}

	function clear_opportunity_note_relationship($opportunity_id)
	{
		$query = "UPDATE notes set parent_id='', parent_type='' where parent_id='$opportunity_id'";
		$this->db->query($query, true, "Error clearing opportunity to note relationship: ");
	}

	function set_opportunity_meeting_relationship($opportunity_id, $meeting_id)
	{
		$query = "UPDATE meetings set parent_id='$opportunity_id', parent_type='Opportunities' where id='$meeting_id'";
		$this->db->query($query, true,"Error setting opportunity to meeting relationship: ");
	}

	function clear_opportunity_meeting_relationship($opportunity_id)
	{
		$query = "UPDATE meetings set parent_id='', parent_type='' where parent_id='$opportunity_id'";
		$this->db->query($query, true,"Error clearing opportunity to meeting relationship: ");
	}

	function set_opportunity_call_relationship($opportunity_id, $call_id)
	{
		$query = "UPDATE calls set parent_id='$opportunity_id', parent_type='Opportunities' where id='$call_id'";
		$this->db->query($query, true,"Error setting opportunity to call relationship: ");
	}

	function clear_opportunity_call_relationship($opportunity_id)
	{
		$query = "UPDATE calls set parent_id='', parent_type='' where parent_id='$opportunity_id'";
		$this->db->query($query, true,"Error clearing opportunity to call relationship: ");
	}

	function set_opportunity_email_relationship($opportunity_id, $email_id)
	{
		$query = "UPDATE emails set parent_id='$opportunity_id', parent_type='Opportunities' where id='$email_id'";
		$this->db->query($query, true,"Error setting opportunity to email relationship: ");
	}

	function clear_opportunity_email_relationship($opportunity_id)
	{
		$query = "UPDATE emails set parent_id='', parent_type='' where parent_id='$opportunity_id'";
		$this->db->query($query, true,"Error clearing opportunity to email relationship: ");
	}

	function mark_relationships_deleted($id)
	{
		$this->clear_opportunity_contact_relationship($id);
		$this->clear_opportunity_account_relationship($id);
		$this->clear_opportunity_task_relationship($id);
		$this->clear_opportunity_note_relationship($id);
		$this->clear_opportunity_meeting_relationship($id);
		$this->clear_opportunity_call_relationship($id);
		$this->clear_opportunity_email_relationship($id);
	}

	function fill_in_additional_list_fields()
	{
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);

		$query = "SELECT amount, sales_stage, lead_source FROM opportunities where id = '$this->id' and deleted=0";
		$result =& $this->db->query($query, true,"Error filling in additional detail fields: ");

		// Get the id and the name.
		$row =  $this->db->fetchByAssoc($result);

		if($row != null)
		{
			$this->lead_source 	= stripslashes($row['lead_source']);
			$this->amount 		= stripslashes($row['amount']);
			$this->sales_stage 	= stripslashes($row['sales_stage']);
		}
		$this->fill_in_additional_detail_fields();
	}

	function fill_in_additional_detail_fields()
	{
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);

		$query = "SELECT acc.id, acc.name from accounts as acc, accounts_opportunities as a_o where acc.id = a_o.account_id and a_o.opportunity_id = '$this->id' and a_o.deleted=0 and acc.deleted=0";
		$result =& $this->db->query($query, true,"Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if($row != null)
		{
			$this->account_name = stripslashes($row['name']);
			$this->account_id 	= stripslashes($row['id']);
		}
		else
		{
			$this->account_name = '';
			$this->account_id = '';
		}

	}


	/** Returns a list of the associated contacts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_contacts()
	{
		// First, get the list of IDs.
		$query = "SELECT c.id, c.first_name, c.last_name, c.title, c.yahoo_id, c.email1, c.phone_work, o_c.contact_role as opportunity_role, o_c.id as opportunity_rel_id ".
				 "from opportunities_contacts o_c, contacts c ".
				 "where o_c.opportunity_id = '$this->id' and o_c.deleted=0 and c.id = o_c.contact_id AND c.deleted=0 order by c.last_name";

	    $temp = Array('id', 'first_name', 'last_name', 'title', 'yahoo_id', 'email1', 'phone_work', 'opportunity_role', 'opportunity_rel_id');
		return $this->build_related_list2($query, new Contact(), $temp);
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

		return $this->build_related_list($query, new Note());
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

	function get_list_view_data(){
		global $current_language;
		$app_strings = return_application_language($current_language);
		return  Array(
					'ID' => $this->id,
					'NAME' => (($this->name == "") ? "<em>blank</em>" : $this->name),
					'AMOUNT' => $app_strings['LBL_CURRENCY_SYMBOL'].$this->amount,
					'ACCOUNT_ID' => $this->account_id,
					'ACCOUNT_NAME' => $this->account_name,
					'DATE_CLOSED' => $this->date_closed,
					'ASSIGNED_USER_NAME' => $this->assigned_user_name,
					"ENCODED_NAME"=>htmlspecialchars($this->name, ENT_QUOTES)
				);
	}
	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string) {
	$where_clauses = Array();
	$the_query_string = addslashes($the_query_string);
	array_push($where_clauses, "name like '$the_query_string%'");

	$the_where = "";
	foreach($where_clauses as $clause)
	{
		if($the_where != "") $the_where .= " or ";
		$the_where .= $clause;
	}


	return $the_where;
}





}



?>
