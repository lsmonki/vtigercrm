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
 * $Header:  vtiger_crm/sugarcrm/modules/Tasks/Task.php,v 1.8 2005/01/10 08:18:55 jack Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');

// Task is used to store customer information.
class Task extends SugarBean {
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
	var $date_due_flag;
	var $date_due;
	var $time_due;
	var $priority;
	var $parent_type;
	var $parent_id;
	var $contact_id;

	var $parent_name;
	var $contact_name;
	var $contact_phone;
	var $contact_email;
	var $assigned_user_name;

	var $default_task_name_values = array('Assemble catalogs', 'Make travel arrangements', 'Send a letter', 'Send contract', 'Send fax', 'Send a follow-up letter', 'Send literature', 'Send proposal', 'Send quote');

	var $table_name = "tasks";

	var $object_name = "Task";

	var $column_fields = Array("id"
		, "date_entered"
		, "date_modified"
		, "assigned_user_id"
		, "description"
		, "name"
		, "status"
		, "date_due"
		, "time_due"
		, "priority"
		, "date_due_flag"
		, "parent_type"
		, "parent_id"
		, "contact_id"
		);

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'contact_name', 'contact_phone', 'contact_email', 'parent_name');

	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'status', 'name', 'parent_type', 'parent_name', 'parent_id', 'date_due', 'contact_id', 'contact_name', 'assigned_user_name', 'assigned_user_id','priority','description');

	function Task() {
		$this->log = LoggerManager::getLogger('task');
		$this->db = new PearDatabase();
	}

	var $new_schema = true;

	function create_tables () {
		global $app_strings;

		$query = 'CREATE TABLE '.$this->table_name.' ( ';
		$query .='id char(36) NOT NULL';
		$query .=', date_entered datetime NOT NULL';
		$query .=', date_modified datetime NOT NULL';
		$query .=', assigned_user_id char(36)';
		$query .=', name char(50)';
		$query .=', status char(25)';
		$query .=', date_due_flag char(5) default \'on\'';
		$query .=', date_due date';
		$query .=', time_due time';
		$query .=', parent_type char(25)';
		$query .=', parent_id char(36)';
		$query .=', contact_id char(36)';
		$query .=', priority char(25)';
		$query .=', description TEXT';
		$query .=', deleted bool NOT NULL default 0';
		$query .=', PRIMARY KEY ( ID ) )';



		$this->db->query($query,$app_strings['ERR_CREATING_TABLE']);

		// Create the indexes
		$this->create_index("create index idx_tsk_name on tasks (name)");
	}

	function drop_tables () {
		$query = 'DROP TABLE IF EXISTS '.$this->table_name;



		$this->db->query($query);

		//TODO Clint 4/27 - add exception handling logic here if the table can't be dropped.

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
			$query = "SELECT tasks.id, tasks.assigned_user_id, tasks.status, tasks.name, tasks.parent_type, tasks.parent_id, tasks.contact_id, tasks.date_due, contacts.first_name, contacts.last_name ,tasks.priority,tasks.description FROM contacts, tasks ";
			$where_auto = "tasks.contact_id = contacts.id AND tasks.deleted=0 AND contacts.deleted=0";
		}
		else
		{
			$query = 'SELECT id, assigned_user_id, status, name, parent_type, parent_id, contact_id, date_due ,priority,description FROM tasks ';
			$where_auto = "deleted=0";
		}

		if($where != "")
			$query .= "where $where AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY name";
		return $query;

	}

        function create_export_query(&$order_by, &$where)
        {
                $contact_required = ereg("contacts", $where);

                if($contact_required)
                {
                        $query = "SELECT tasks.*, contacts.first_name, contacts.last_name FROM contacts, tasks ";
                        $where_auto = "tasks.contact_id = contacts.id AND tasks.deleted=0 AND contacts.deleted=0";
                }
                else
                {
                        $query = 'SELECT * FROM tasks ';
                        $where_auto = "deleted=0";
                }

                if($where != "")
                        $query .= "where $where AND ".$where_auto;
                else
                        $query .= "where ".$where_auto;

                if($order_by != "")
                        $query .= " ORDER BY $order_by";
                else
                        $query .= " ORDER BY name";
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

		global $app_strings;

		if (isset($this->contact_id)) {
			require_once("modules/Contacts/Contact.php");
			$contact = new Contact();
			$query = "SELECT first_name, last_name, phone_work, email1 from $contact->table_name where id = '$this->contact_id'";

			$result =$this->db->query($query,true,$app_strings['ERR_CREATING_FIELDS']);


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);


			if($row != null)
			{
				$this->contact_name = return_name($row, 'first_name', 'last_name');
				if ($row['phone_work'] != '') $this->contact_phone = $row['phone_work'];
				if ($row['email1'] != '') $this->contact_email = $row['email1'];
			}
		}
		if ($this->parent_type == "Opportunities") {
			require_once("modules/Opportunities/Opportunity.php");
			$parent = new Opportunity();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true, $app_strings['ERR_CREATING_FIELDS']);

			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);


			if($row != null)
			{
				if ($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		}
		if ($this->parent_type == "Cases") {
			require_once("modules/Cases/Case.php");
			$parent = new aCase();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true,$app_strings['ERR_CREATING_FIELDS']);


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);


			if($row != null)
			{
				if ($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		}
		if ($this->parent_type == "Accounts") {
			require_once("modules/Accounts/Account.php");
			$parent = new Account();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true, $app_strings['ERR_CREATING_FIELDS']);


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);


			if($row != null)
			{
				if ($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		}
	}

   function delete($id)
        {
		
          mysql_query("update tasks set deleted=1 where id = '" . $id . "'");
        }

    function getCount($user_name) 
    {
        $query = "select count(*) from tasks inner join users on users.id=tasks.assigned_user_id where user_name='" .$user_name ."' and tasks.deleted=0";

//       echo "\n Query is " .$query ."\n";
        $result = $this->db->query($query,true,"Error retrieving contacts count");
        $rows_found =  $this->db->getRowCount($result);
        $row = $this->db->fetchByAssoc($result, 0);

    
        return $row["count(*)"];
    }       

    function get_tasks($user_name,$from_index,$offset)
    {   
         $query = "select tasks.*, contacts.first_name cfn, contacts.last_name cln from tasks inner join users on users.id=tasks.assigned_user_id left join contacts on contacts.id=tasks.contact_id  where user_name='" .$user_name ."' and tasks.deleted=0 limit " .$from_index ."," .$offset;

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
                    }   
                    else     
                    {   
                            $this->$field = "";
                    }   
                }   
    
    // TODO OPTIMIZE THE QUERY ACCOUNT NAME AND ID are set separetly for every contacts and hence 
    // account query goes for ecery single account row

       //         $this->fill_in_additional_list_fields();
		//$this->account_name = $row['accountname'];
		//$this->account_id = $row['accountid'];
        $this->contact_name = return_name($row, 'cfn', 'cln');
        
    
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
	

function save_relationship_changes($is_update)
    {

		$query = "UPDATE tasks  set contact_id=null where id='". $this->id ."' and deleted=0";
		$this->db->query($query,true,"Error clearing contact to task relationship: ");

     //  echo "\n Quwry is " .$query; 
      // echo "\ncontact_id is " .$this->contact_id; 

    	
    	if($this->contact_id != "")
    	{
          $query = "UPDATE tasks  set contact_id='" .$this->contact_id ."' where id='" .$this->id ."' and deleted=0";
          //echo $query;  
	      $this->db->query($query,true,"Error setting contact to task relationship: "."<BR>$query");
    	}

    }
    


	function get_list_view_data(){
		global $action, $currentModule, $focus, $app_list_strings;
		$today = date("Y-m-d", time());
		$task_fields =$this->get_list_view_array();
		if (isset($this->parent_type))
			$task_fields['PARENT_MODULE'] = $this->parent_type;
		if ($this->status != "Completed" && $this->status != "Deferred" ) {
			$task_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=" . ((is_object($focus)) ? $focus->id : "") . "&action=Save&module=Tasks&record=$this->id&status=Completed'>X</a>";
		}


		if ($this->date_due	< $today) {
			$task_fields['DATE_DUE'] = "<font class='overdueTask'>".$task_fields['DATE_DUE']."</font>";
		}
		return $task_fields;
	}

}
?>
