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
 * $Header:  vtiger_crm/sugarcrm/modules/Contacts/Contact.php,v 1.13 2004/12/28 09:22:47 jack Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('include/utils.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Cases/Case.php');
require_once('modules/Tasks/Task.php');
require_once('modules/Notes/Note.php');
require_once('modules/Meetings/Meeting.php');
require_once('modules/Calls/Call.php');
require_once('modules/Emails/Email.php');

// Contact is used to store customer information.
class Contact extends SugarBean {
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
	var $salutation;	
	var $first_name;
	var $last_name;
	var $title;
	var $department;
	var $birthdate;
	var $reports_to_id;
	var $do_not_call;
	var $phone_home;
	var $phone_mobile;
	var $phone_work;
	var $phone_other;
	var $phone_fax;
	var $email1;
	var $email2;
	var $yahoo_id;
	var $assistant;
	var $assistant_phone;
	var $email_opt_out;
	var $primary_address_street;
	var $primary_address_city;
	var $primary_address_state;
	var $primary_address_postalcode;
	var $primary_address_country;
	var $alt_address_street;
	var $alt_address_city;
	var $alt_address_state;
	var $alt_address_postalcode;
	var $alt_address_country;

	// These are for related fields
	var $account_name;
	var $account_id;
	var $reports_to_name;
	var $opportunity_role;
	var $opportunity_rel_id;
	var $opportunity_id;
	var $case_role;
	var $case_rel_id;
	var $case_id;
	var $task_id;
	var $note_id;
	var $meeting_id;
	var $call_id;
	var $email_id;
	var $assigned_user_name;
		
	var $table_name = "contacts";
	var $rel_account_table = "accounts_contacts";
	//This is needed for upgrade.  This table definition moved to Opportunity module.
	var $rel_opportunity_table = "opportunities_contacts";

	var $object_name = "Contact";
	
	var $new_schema = true;

	var $column_fields = Array("id"
		,"date_entered"
		,"date_modified"
		,"modified_user_id"
		,"assigned_user_id"
		,"salutation"
		,"first_name"
		,"last_name"
		,"lead_source"
		,"title"
		,"department"
		,"birthdate"
		,"reports_to_id"
		,"do_not_call"
		,"phone_home"
		,"phone_mobile"
		,"phone_work"
		,"phone_other"
		,"phone_fax"
		,"email1"
		,"email2"
		,"yahoo_id"
		,"assistant"
		,"assistant_phone"
		,"email_opt_out"
		,"primary_address_street"
		,"primary_address_city"
		,"primary_address_state"
		,"primary_address_postalcode"
		,"primary_address_country"
		,"alt_address_street"
		,"alt_address_city"
		,"alt_address_state"
		,"alt_address_postalcode"
		,"alt_address_country"
		,"description"
		);

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'account_name', 'account_id', 'opportunity_id', 'case_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id');		
	
	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'first_name', 'last_name','salutation', 'account_name', 'account_id', 'title', 'yahoo_id', 'email1','primary_address_city','phone_mobile','reports_to_id','primary_address_street', 'phone_work','primary_address_state','primary_address_postalcode','primary_address_country','alt_address_city','alt_address_street','alt_address_state','alt_address_postalcode','alt_address_country','assigned_user_name', 'assigned_user_id', "case_role", 'case_rel_id', 'opportunity_role', 'opportunity_rel_id');	
	// This is the list of fields that are required
	var $required_fields =  array("last_name"=>1);

	function Contact() {
		$this->log = LoggerManager::getLogger('contact');
		$this->db = new PearDatabase();
	}

	function create_tables () {
		$query = 'CREATE TABLE '.$this->table_name.' ( ';
		$query .='id char(36) NOT NULL';
		$query .=', deleted bool NOT NULL default 0';
		$query .=', date_entered datetime NOT NULL';
		$query .=', date_modified datetime NOT NULL';
		$query .=', modified_user_id char(36) NOT NULL';
		$query .=', assigned_user_id char(36)';
		$query .=', salutation char(5)';
		$query .=', first_name char(25)';
		$query .=', last_name char(25)';
		$query .=', lead_source char(100)';
		$query .=', title char(25)';
		$query .=', department char(100)';
		$query .=', reports_to_id char(36)';
		$query .=', birthdate date';
		$query .=', do_not_call char(3) default 0';
		$query .=', phone_home char(25)';
		$query .=', phone_mobile char(25)';
		$query .=', phone_work char(25)';
		$query .=', phone_other char(25)';
		$query .=', phone_fax char(25)';
		$query .=', email1 char(100)';
		$query .=', email2 char(100)';
		$query .=', yahoo_id char(75)';
		$query .=', assistant char(75)';
		$query .=', assistant_phone char(25)';
		$query .=', email_opt_out char(3) default 0';
		$query .=', primary_address_street char(150)';
		$query .=', primary_address_city char(100)';
		$query .=', primary_address_state char(100)';
		$query .=', primary_address_postalcode char(20)';
		$query .=', primary_address_country char(100)';
		$query .=', alt_address_street char(150)';
		$query .=', alt_address_city char(100)';
		$query .=', alt_address_state char(100)';
		$query .=', alt_address_postalcode char(20)';
		$query .=', alt_address_country char(100)';
		$query .=', description text';
		$query .=', PRIMARY KEY ( ID ) )';
		
		
		
		$this->db->query($query,true,"Error creating table: ");

		//TODO Clint 4/27 - add exception handling logic here if the table can't be created.
		
		$query = "CREATE TABLE $this->rel_account_table (";
		$query .='id char(36) NOT NULL';
		$query .=', contact_id char(36)';
		$query .=', account_id char(36)';
		$query .=', deleted bool NOT NULL default 0';
		$query .=', PRIMARY KEY ( ID ) )';
	
		
		$this->db->query($query,true,"Error creating account/contact relationship table: ");

		
		// Create the indexes
		$this->create_index("create index idx_cont_last_first on contacts (last_name, first_name, deleted)");
		$this->create_index("create index idx_acc_cont_acc on accounts_contacts (account_id)");
		$this->create_index("create index idx_acc_cont_cont on accounts_contacts (contact_id)");
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
	
	function delete($id)
        {
          mysql_query('update contacts set deleted=1 where id = \'' .$id . '\'');
        }
    
    function getCount($user_name) 
    {
        $query = "select count(*) from contacts inner join users on users.id=contacts.assigned_user_id where user_name='" .$user_name ."' and contacts.deleted=0";

//        echo "\n Query is " .$query ."\n";
        $result = $this->db->query($query,true,"Error retrieving contacts count");
        $rows_found =  $this->db->getRowCount($result);
        $row = $this->db->fetchByAssoc($result, 0);

  //      echo "ROW COUNT is " .$row["count(*)"];
    //    echo "\nROWs FOUND is " .$rows_found;

    
    
            return $row["count(*)"];
    }       

    function get_contacts($user_name,$from_index,$offset)
    {   
         $query = "select contacts.* from contacts inner join users on users.id=contacts.assigned_user_id where user_name='" .$user_name ."' and contacts.deleted=0 limit " .$from_index ."," .$offset;
    // $query = "select * from contacts limit " .$from_index ."," .$offset;
//    echo $query;
    return $this->process_list_query1($query);
    
    }


    function process_list_query1($query)
    {
        $result =& $this->db->query($query,true,"Error retrieving $this->object_name list: ");
        $list = Array();
        $rows_found =  $this->db->getRowCount($result);
        if($rows_found != 0)
        {
               for($index = 0 , $row = $this->db->fetchByAssoc($result, $index); $row && $index <$rows_found;$index++, $row = $this->db->fetchByAssoc($result, $index))
            
            {
                foreach($this->list_fields as $field)
                {
                    if (isset($row[$field])) {
                        $this->$field = $row[$field];
                        //$this->log->debug("$this->object_name({$row['id']}): ".$field." = ".$this->$field);
                    }   
                    else     
                    {   
                            $this->$field = "";
                    }   
                }   
    
                $this->fill_in_additional_list_fields();
    
                    $list[] = $this;
                }
        }   

        $response = Array();
        $response['list'] = $list;
        $response['row_count'] = $rows_found;
        $response['next_offset'] = $next_offset;
        $response['previous_offset'] = $previous_offset;



        return $response;
    }

	function get_summary_text()
	{
		return "$this->first_name $this->last_name";
	}
	
	/** Returns a list of the associated contacts who are direct reports
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_direct_reports()
	{
		// First, get the list of IDs.
		$query = "SELECT c1.id from contacts as c1, contacts as c2 where c2.id=c1.reports_to_id AND c2.id='$this->id' AND c1.deleted=0 order by c1.last_name";
		
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
		$query = "SELECT opportunity_id as id from opportunities_contacts where contact_id='$this->id' AND deleted=0";
		
		return $this->build_related_list($query, new Opportunity());
	}
	
		/** Returns a list of the associated opportunities
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_accounts()
	{
		// First, get the list of IDs.
		$query = "SELECT account_id as id from accounts_contacts where contact_id='$this->id' AND deleted=0";
		
		return $this->build_related_list($query, new Account());
	}
	
	/** Returns a list of the associated cases
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_cases()
	{
		// First, get the list of IDs.
		$query = "SELECT case_id as id from contacts_cases where contact_id='$this->id' AND deleted=0";
		
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
		$query = "SELECT id from tasks where contact_id='$this->id' AND deleted=0";
		
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
		$query = "SELECT id from notes where contact_id='$this->id' AND deleted=0";
		
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
		$query = "SELECT meeting_id as id from meetings_contacts where contact_id='$this->id' AND deleted=0";
		
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
		$query = "SELECT call_id as id from calls_contacts where contact_id='$this->id' AND deleted=0";
		
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
		$query = "SELECT email_id as id from emails_contacts where contact_id='$this->id' AND deleted=0";
		
		return $this->build_related_list($query, new Email());
	}

	function create_list_query(&$order_by, &$where)
	{
		// Determine if the account name is present in the where clause.
		$account_required = ereg("accounts\.name", $where);
		
		if($account_required)
		{
			$query = "SELECT * FROM accounts, accounts_contacts a_c, contacts ";
			//$query = "SELECT contacts.id, contacts.assigned_user_id, contacts.yahoo_id, contacts.first_name, contacts.last_name, contacts.phone_work, contacts.title, contacts.email1 FROM contacts, accounts_contacts a_c, accounts ";
			$where_auto = "a_c.contact_id = contacts.id AND a_c.account_id = accounts.id AND a_c.deleted=0 AND accounts.deleted=0 AND contacts.deleted=0";
		}
		else 
		{
			$query = "SELECT * FROM contacts ";
			//$query = "SELECT id, yahoo_id, contacts.assigned_user_id, first_name, last_name, phone_work, title, email1 FROM contacts ";
			$where_auto = "deleted=0";
		}
		
		if($where != "")
			$query .= "where ($where) AND ".$where_auto;
		else 
			$query .= "where ".$where_auto;		

		if(!empty($order_by))
			$query .= " ORDER BY $order_by";

		return $query;
	}


//method added to construct the query to fetch the custom fields 
	function constructCustomQueryAddendum()
	{
		$result = mysql_query("SHOW COLUMNS FROM contactcf");
		$i=0;
		while ($myrow = mysql_fetch_row($result))
		{
		        $columnName[$i] = $myrow[0];
		        $i++;
		}

		$sql1 = "select column_name,fieldlabel from customfields where column_name in (";
		$colName = 0;
		$addTag;
		while($colName < count($columnName))
		{
		        if ($columnName[$colName] == "contactid")
        		{
        		}
        		else
        		{
		                if($colName == 1)
                		{

		                        $addTag .= "'" .$columnName[$colName] ."'";
                		}
		                else
                		{
		                        $addTag .= ",'" .$columnName[$colName] ."'";
                		}
        		}
		        $colName++;
		}
		$sql2 = $sql1.$addTag .")";
		$result_sql2 = mysql_query($sql2);
		$resultCount = mysql_num_rows($result_sql2);
		$rs=mysql_fetch_array($result_sql2);
		 $j=0;
		while($j<mysql_num_rows($result_sql2))
  		{
			    for($i=0;$i<=$resultCount;$i++)
    				{
				      $copy[$j][$i]=$rs[$i];
    				}
			    $rs=mysql_fetch_array($result_sql2);
			    $j++;
		}
		$sql3 = "select ";
		$k=0;
		$l=0;
		while($k< $resultCount)
		{	

		        if($k == 0)
        		{
		        $sql3.= "contactcf.".$copy[$k][$l]." ".$copy[$k][$l+1];
        		}
		        else
        		{
		        $sql3.= ",contactcf.".$copy[$k][$l]." ".$copy[$k][$l+1];
        		}
		        $k++;
		}
	return $sql3;

	}

//check if the custom table exists or not in the first place
function checkIfCustomTableExists()
{
 $result = mysql_query("SHOW tables like 'contactcf'");
 $testrow = mysql_num_fields($result);
	if(count($testrow) > 1)
	{
		$exists=true;
	}
	else
	{
		$exists=false;
	}
return $exists;
}

        function create_export_query(&$order_by, &$where)
        {
		if($this->checkIfCustomTableExists())
		{
	
                         $query =  $this->constructCustomQueryAddendum() .",
                                contacts.*,
                                accounts.name as account_name,
                                users.user_name as assigned_user_name
                                FROM contacts
                                LEFT JOIN users
                                ON contacts.assigned_user_id=users.id
                                LEFT JOIN accounts_contacts
                                ON contacts.id=accounts_contacts.contact_id
                                LEFT JOIN accounts
                                ON accounts_contacts.account_id=accounts.id left join contactcf on contactcf.contactid=contacts.id ";
		}
		else
		{
			 $query = "SELECT
                                contacts.*,
                                accounts.name as account_name,
                                users.user_name as assigned_user_name
                                FROM contacts
                                LEFT JOIN users
                                ON contacts.assigned_user_id=users.id
                                LEFT JOIN accounts_contacts
                                ON contacts.id=accounts_contacts.contact_id
                                LEFT JOIN accounts
                                ON accounts_contacts.account_id=accounts.id ";
		}

                        $where_auto = " (accounts_contacts.deleted=0 or accounts_contacts.deleted is null) 
                        AND users.status='ACTIVE' AND (accounts.deleted=0 or accounts.deleted is null) AND contacts.deleted=0 ";

                if($where != "")
                        $query .= "where ($where) AND ".$where_auto;
                else
                        $query .= "where ".$where_auto;

                if(!empty($order_by))
                        $query .= " ORDER BY $order_by";
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
		$this->db->query($query,true,"Error clearing account to contact relationship: ");
	}
    
	function set_account_contact_relationship($contact_id, $account_id)
	{
		$query = "insert into accounts_contacts set id='".create_guid()."', contact_id='$contact_id', account_id='$account_id'";
		$this->db->query($query,true,"Error setting account to contact relationship: "."<BR>$query");
	}

	function set_opportunity_contact_relationship($contact_id, $opportunity_id)
	{
		global $app_list_strings;
		$default = $app_list_strings['opportunity_relationship_type_default_key'];
		$query = "insert into opportunities_contacts set id='".create_guid()."', opportunity_id='$opportunity_id', contact_id='$contact_id', contact_role='$default'";
		$this->db->query($query,true,"Error setting account to contact relationship: "."<BR>$query");
	}

	function clear_opportunity_contact_relationship($contact_id)
	{
		$query = "UPDATE opportunities_contacts set deleted=1 where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing opportunity to contact relationship: ");
	}
    
	function set_case_contact_relationship($contact_id, $case_id)
	{
		global $app_list_strings;
		$default = $app_list_strings['case_relationship_type_default_key'];
		$query = "insert into contacts_cases set id='".create_guid()."', case_id='$case_id', contact_id='$contact_id', contact_role='$default'";
		$this->db->query($query,true,"Error setting account to contact relationship: "."<BR>$query");
	}

	function clear_case_contact_relationship($contact_id)
	{
		$query = "UPDATE contacts_cases set deleted=1 where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing case to contact relationship: ");
	}
    
	function set_task_contact_relationship($contact_id, $task_id)
	{
		$query = "UPDATE tasks set contact_id='$contact_id' where id='$task_id'";
		$this->db->query($query,true,"Error setting contact to task relationship: ");
	}
	
	function clear_task_contact_relationship($contact_id)
	{
		$query = "UPDATE tasks set deleted=1 where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing task to contact relationship: ");
	}

	function set_note_contact_relationship($contact_id, $note_id)
	{
		$query = "UPDATE notes set contact_id='$contact_id' where id='$note_id'";
		$this->db->query($query,true,"Error setting contact to note relationship: ");
	}
	
	function clear_note_contact_relationship($contact_id)
	{
		$query = "UPDATE notes set deleted=1 where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing note to contact relationship: ");
	}

	function set_meeting_contact_relationship($contact_id, $meeting_id)
	{
		$query = "insert into meetings_contacts set id='".create_guid()."', meeting_id='$meeting_id', contact_id='$contact_id'";
		$this->db->query($query,true,"Error setting meeting to contact relationship: "."<BR>$query");
	}

	function clear_meeting_contact_relationship($contact_id)
	{
		$query = "UPDATE meetings_contacts set deleted=1 where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing meeting to contact relationship: ");
	}

	function set_call_contact_relationship($contact_id, $call_id)
	{
		$query = "insert into calls_contacts set id='".create_guid()."', call_id='$call_id', contact_id='$contact_id'";
		$this->db->query($query,true,"Error setting meeting to contact relationship: "."<BR>$query");
	}

	function clear_call_contact_relationship($contact_id)
	{
		$query = "UPDATE calls_contacts set deleted=1 where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing call to contact relationship: ");
	}

	function set_email_contact_relationship($contact_id, $email_id)
	{
		$query = "insert into emails_contacts set id='".create_guid()."', email_id='$email_id', contact_id='$contact_id'";
		$this->db->query($query,true,"Error setting email to contact relationship: "."<BR>$query");
	}

	function clear_email_contact_relationship($contact_id)
	{
		$query = "UPDATE emails_contacts set deleted=1 where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing email to contact relationship: ");
	}

	function clear_contact_all_direct_report_relationship($contact_id)
	{
		$query = "UPDATE contacts set reports_to_id='' where reports_to_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing contact to direct report relationship: ");
	}

	function clear_contact_direct_report_relationship($contact_id)
	{
		$query = "UPDATE contacts set reports_to_id='' where id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing contact to direct report relationship: ");
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
		
	function fill_in_additional_list_fields()
	{
		$this->fill_in_additional_detail_fields();	
	}
	
	function fill_in_additional_detail_fields()
	{
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);
		
		$query = "SELECT acc.id, acc.name from accounts as acc, accounts_contacts as a_c where acc.id = a_c.account_id and a_c.contact_id = '$this->id' and a_c.deleted=0";
		$result = $this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);
		
		if($row != null)
		{
			$this->account_name = $row['name'];
			$this->account_id = $row['id'];
		}
		else 
		{
			$this->account_name = '';
			$this->account_id = '';
		}		
		$query = "SELECT c1.first_name, c1.last_name from contacts as c1, contacts as c2 where c1.id = c2.reports_to_id and c2.id = '$this->id' and c1.deleted=0";
		$result = $this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);
		
		if($row != null)
		{
			$this->reports_to_name = $row['first_name'].' '.$row['last_name'];
		}
		else 
		{
			$this->reports_to_name = '';
		}		
	}
	function get_list_view_data(){
		$temp_array = $this->get_list_view_array();
    	$temp_array["ENCODED_NAME"]=htmlspecialchars($this->first_name.' '.$this->last_name, ENT_QUOTES);
    	return $temp_array;
		
	}
	function list_view_parse_additional_sections(&$list_form, $section){
		
		if($list_form->exists($section.".row.yahoo_id") && isset($this->yahoo_id) && $this->yahoo_id != '')
			$list_form->parse($section.".row.yahoo_id");
		elseif ($list_form->exists($section.".row.no_yahoo_id"))
				$list_form->parse($section.".row.no_yahoo_id");
		return $list_form;
		
		
	}
	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string) {
	$where_clauses = Array();
	$the_query_string = addslashes($the_query_string);
	array_push($where_clauses, "last_name like '$the_query_string%'");
	array_push($where_clauses, "first_name like '$the_query_string%'");
	array_push($where_clauses, "assistant like '$the_query_string%'");
	array_push($where_clauses, "email1 like '$the_query_string%'");
	array_push($where_clauses, "email2 like '$the_query_string%'");
	array_push($where_clauses, "yahoo_id like '$the_query_string%'");
	if (is_numeric($the_query_string)) {
		array_push($where_clauses, "phone_home like '%$the_query_string%'");
		array_push($where_clauses, "phone_mobile like '%$the_query_string%'");
		array_push($where_clauses, "phone_work like '%$the_query_string%'");
		array_push($where_clauses, "phone_other like '%$the_query_string%'");
		array_push($where_clauses, "phone_fax like '%$the_query_string%'");
		array_push($where_clauses, "assistant_phone like '%$the_query_string%'");
	}
	
	$the_where = "";
	foreach($where_clauses as $clause)
	{
		if($the_where != "") $the_where .= " or ";
		$the_where .= $clause;
	}

	
	return $the_where;
}



 function getColumnNames()
 {
 $result = $this->db->query("SHOW COLUMNS FROM contacts");
 $i=0;
 while ($myrow = mysql_fetch_row($result))
 {
         $copy[$i]=$myrow;
         $i++;
 }
 return $copy;
}

}



?>
