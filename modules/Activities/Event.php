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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Activities/Event.php,v 1.2 2005/03/02 13:56:52 jack Exp $
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
class Event extends SugarBean {
	var $log;
	var $db;

	// Stored fields
  	var $eventid;
	var $description;
	var $status;
	var $date_start;
	var $time_start;
	var $priority;
   var $sendnotification;
  var $duration_hours;
  var $duration_minutes;

	var $table_name = "events";

	var $object_name = "Event";	
	
	var $tab_name = Array('crmentity','activity','events','seactivityrel','cntactivityrel');

	var $tab_name_index = Array('crmentity'=>'crmid','events'=>'eventid','activity'=>'activityid','seactivityrel'=>'activityid','cntactivityrel'=>'activityid');

	var $column_fields = Array();

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'contactname', 'contact_phone', 'contact_email', 'parent_name');

	// This is the list of fields that are in the lists.
	var $list_fields = Array(
       'Close'=>Array('event'=>'status'),
       'Subject'=>Array('activity'=>'subject'),
       'Contact Name'=>Array('contactdetails'=>'lastname'),
       'Related to'=>Array('seactivityrel'=>'activityid'),
       'Start Date'=>Array('event'=>'date_start'),
       'Assigned To'=>Array('crmentity','smownerid')
       );

       var $list_fields_name = Array(
       'Close'=>'status',
       'Subject'=>'subject',
       'Contact Name'=>'lastname',
       'Related to'=>'activityid',
       'Start Date'=>'date_start',
       'Assigned To'=>'assigned_user_id');

       var $list_link_field= 'subject';
	

	function Task() {
		$this->log = LoggerManager::getLogger('events');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Events');
	}

	var $new_schema = true;

	function create_tables () {
		global $app_strings;
	}

	function drop_tables () {
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
			$query = "SELECT task.taskid, tasks.assigned_user_id, task.status, task.name, task.parent_type, tasks.parent_id, tasks.contact_id, tasks.datedue, contactdetails.firstname, contactdetails.lastname ,task.priority,task.description FROM contactdetails, task ";
			$where_auto = "task.contact_id = contactdetails.contactid AND task.deleted=0 AND contact.deleted=0";
		}
		else
		{
			$query = 'SELECT taskid, smcreatorid, task.status, duedate ,priority FROM task inner join crmentity on crmentity.crmid=task.taskid ';
			$where_auto = " AND deleted=0";
		}

		if($where != "")
			$query .= "where $where ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
                  //$query .= " ORDER BY name";
		return $query;

	}

        function create_export_query(&$order_by, &$where)
        {
                $contact_required = ereg("contacts", $where);

                if($contact_required)
                {
                      $query = "SELECT task.*, contactdetailss.firstname, contactdetails.lastname FROM task inner join seactivityrel on seactivityrel.activityid=task.taskid inner join crmentity on crmentity.crmid=task.taskid and crmentity.deleted=0";
                }
                else
                {
                      $query = 'SELECT * FROM task inner join seactivityrel on seactivityrel.activityid=task.taskid inner join crmentity on crmentity.crmid=task.taskid and crmentity.deleted=0';
                }
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
			$query = "SELECT firstname, lastname, phone, email from $contact->table_name where contactid = '$this->contact_id'";

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
		if ($this->parent_type == "Potentials") {
			require_once("modules/Potentials/Opportunity.php");
			$parent = new Potential();
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
		
          $this->db->query("update tasks set deleted=1 where id = '" . $id . "'");
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
			$task_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=" . ((is_object($focus)) ? $focus->id : "") . "&action=Save&module=Activities&record=$this->id&status=Completed'>X</a>";
		}


		if ($this->duedate	< $toDAy) {
			$task_fields['DATE_DUE'] = "<font class='overdueTask'>".$task_fields['DATE_DUE']."</font>";
		}
		return $task_fields;
	}

}
?>
