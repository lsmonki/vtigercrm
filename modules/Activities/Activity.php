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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Activities/Activity.php,v 1.26 2005/03/26 10:42:13 rank Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('data/CRMEntity.php');

// Task is used to store customer information.
class Activity extends CRMEntity {
	var $log;
	var $db;

	// Stored fields
  	var $eventid;
	var $description;
	var $firstname;
	var $lastname;
	var $setype;
	var $status;
	var $date_start;
	var $time_start;
	var $priority;
   	var $sendnotification;
	var $duration_hours;
	var $duration_minutes;

	var $table_name = "activity";

	var $object_name = "activity";	
	
	var $reminder_table = "activity_reminder";
	
	var $tab_name = Array('crmentity','activity','seactivityrel','cntactivityrel','salesmanactivityrel','activity_reminder','recurringevents');

	var $tab_name_index = Array('crmentity'=>'crmid','activity'=>'activityid','seactivityrel'=>'activityid','cntactivityrel'=>'activityid','salesmanactivityrel'=>'activityid','activity_reminder'=>'activity_id','recurringevents'=>'activityid');

	var $column_fields = Array();
	var $sortby_fields = Array('subject','due_date','date_start');	//Sorting is added for due date and start date	

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'contactname', 'contact_phone', 'contact_email', 'parent_name');

	// This is the list of fields that are in the lists.
	var $list_fields = Array(
       'Close'=>Array('activity'=>'status'),
       'Type'=>Array('activity'=>'activitytype'),
       'Subject'=>Array('activity'=>'subject'),
       'Contact Name'=>Array('contactdetails'=>'lastname'),
       'Related to'=>Array('seactivityrel'=>'activityid'),
       'Start Date'=>Array('activity'=>'date_start'),
       'End Date'=>Array('activity'=>'due_date'),
       'Recurring Type'=>Array('recurringevents'=>'recurringtype'),
       'Assigned To'=>Array('crmentity','smownerid')
       );

       var $range_fields = Array(
	'name',
	'date_modified',
	'start_date',
	'id',
	'status',
	'date_due',
	'time_start',
	'description',
	'contact_name',
	'priority',
	'duehours',
	'dueminutes',
	'location'
	);
       

       var $list_fields_name = Array(
       'Close'=>'status',
       'Type'=>'activitytype',
       'Subject'=>'subject',
       'Contact Name'=>'lastname',
       'Related to'=>'activityid',
       'Start Date'=>'date_start',
       'End Date'=>'due_date',
	'Recurring Type'=>'recurringtype',	
       'Assigned To'=>'assigned_user_id');

       var $list_link_field= 'subject';
	

	function Activity() {
		$this->log = LoggerManager::getLogger('Activities');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Activities');
	}

	var $new_schema = true;

	function create_tables () {
		global $app_strings;
	}

	function drop_tables () {
	}


//Function Call for Related List -- Start
        function get_contacts($id)
        {
                //$query="select contactdetails.firstname,contactdetails.lastname,contactdetails.phone,contactdetails.email  from contactdetails inner join seactivityrel on seactivityrel.crmid=contactdetails.contactid and seactivityrel.activityid=".$id."";
		$query = 'select contactdetails.accountid, contactdetails.contactid, contactdetails.firstname,contactdetails.lastname, contactdetails.department, contactdetails.title, contactdetails.email, contactdetails.phone, crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime from contactdetails inner join seactivityrel on seactivityrel.crmid=contactdetails.contactid inner join crmentity on crmentity.crmid = contactdetails.contactid where seactivityrel.activityid='.$id.' and crmentity.deleted=0';
                renderRelatedContacts($query,$id);
        }

        function get_users($id)
        {
               //$query = 'SELECT users.id, users.first_name,users.last_name, users.user_name, users.email1, users.email2, users.yahoo_id,  users.phone_home, users.phone_work, users.phone_mobile, users.phone_other, users.phone_fax from users inner join salesmanactivityrel on salesmanactivityrel.smid=users.id and salesmanactivityrel.activityid='.$id;
		$query = 'SELECT users.id, users.first_name,users.last_name, users.user_name, users.email1, users.email2, users.yahoo_id, users.phone_home, users.phone_work, users.phone_mobile, users.phone_other, users.phone_fax,activity.date_start,activity.due_date,activity.time_start,activity.duration_hours,activity.duration_minutes from users inner join salesmanactivityrel on salesmanactivityrel.smid=users.id  inner join activity on activity.activityid=salesmanactivityrel.activityid where activity.activityid='.$id;

                renderRelatedUsers($query,$id);
        }

	function get_products($id)
	{
		$query = 'select activity.activityid, products.productid, products.productname, products.productcode, products.commissionrate, products.qty_per_unit, products.unit_price, crmentity.crmid, crmentity.smownerid from activity inner join seactivityrel on activity.activityid = seactivityrel.activityid inner join products on seactivityrel.crmid = products.productid inner join crmentity on crmentity.crmid = products.productid where activity.activityid = '.$id.' and crmentity.deleted = 0';
		renderRelatedProducts($query,$id);
	}
//Function Call for Related List -- End


	function get_summary_text()
	{
		return "$this->name";
	}


  function get_full_list($criteria)
  {
    $query = "select crmentity.crmid,crmentity.smownerid,crmentity.setype, activity.*, contactdetails.lastname, contactdetails.firstname, contactdetails.contactid from activity inner join crmentity on crmentity.crmid=activity.activityid left join cntactivityrel on cntactivityrel.activityid= activity.activityid left join contactdetails on contactdetails.contactid= cntactivityrel.contactid left join seactivityrel on seactivityrel.activityid = activity.activityid WHERE crmentity.deleted=0 ".$criteria;
    $result =& $this->db->query($query);
	echo $query;
    $this->log->debug("process_full_list_query: result is ".$result);
        
    if($this->db->getRowCount($result) > 0){
		
      // We have some data.
      while ($row = $this->db->fetchByAssoc($result)) {
        foreach($this->list_fields_name as $field)
        {
          if (isset($row[$field])) {
            $this->$field = $row[$field];
          }
          else {
            $this->$field = '';   
          }
        }
        $list[] = $this;
      }
    }
    if (isset($list)) return $list;
    else return null;
  }
 /* 
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
*/
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
		  
//calendarsync
    function getCount_Meeting($user_name) 
	{
      $query = "select count(*) from activity inner join crmentity on crmentity.crmid=activity.activityid inner join salesmanactivityrel on salesmanactivityrel.activityid=activity.activityid inner join users on users.id=salesmanactivityrel.smid where user_name='" .$user_name ."' and crmentity.deleted=0 and activity.activitytype='Meeting'";

      $result = $this->db->query($query,true,"Error retrieving contacts count");
      $rows_found =  $this->db->getRowCount($result);
      $row = $this->db->fetchByAssoc($result, 0);

      return $row["count(*)"];
    }
   
    function get_calendars($user_name,$from_index,$offset)
    {   
		$query = "select activity.location as location,activity.duration_hours as duehours, activity.duration_minutes as dueminutes,activity.time_start as time_start, activity.subject as name,crmentity.modifiedtime as date_modified, activity.date_start start_date,activity.activityid as id,activity.status as status,activity.description as description, activity.priority as priority, activity.due_date as date_due ,contactdetails.firstname cfn, contactdetails.lastname cln from activity inner join salesmanactivityrel on salesmanactivityrel.activityid=activity.activityid inner join users on users.id=salesmanactivityrel.smid left join cntactivityrel on cntactivityrel.activityid=activity.activityid left join contactdetails on contactdetails.contactid=cntactivityrel.contactid inner join crmentity on crmentity.crmid=activity.activityid where user_name='" .$user_name ."' and crmentity.deleted=0 and activity.activitytype='Meeting' limit " .$from_index ."," .$offset;
	    return $this->process_list_query1($query);   
    }       
//calendarsync

    function getCount($user_name) 
    {
        $query = "select count(*) from activity inner join crmentity on crmentity.crmid=activity.activityid inner join salesmanactivityrel on salesmanactivityrel.activityid=activity.activityid inner join users on users.id=salesmanactivityrel.smid where user_name='" .$user_name ."' and crmentity.deleted=0 and activity.activitytype='Task'";

        $result = $this->db->query($query,true,"Error retrieving contacts count");
        $rows_found =  $this->db->getRowCount($result);
        $row = $this->db->fetchByAssoc($result, 0);

    
        return $row["count(*)"];
    }       

    function get_tasks($user_name,$from_index,$offset)
    {   
//         $query = "select tasks.*, contacts.first_name cfn, contacts.last_name cln from tasks inner join users on users.id=tasks.assigned_user_id left join contacts on contacts.id=tasks.contact_id  where user_name='" .$user_name ."' and tasks.deleted=0 limit " .$from_index ."," .$offset;

	 $query = "select activity.subject as name,crmentity.modifiedtime as date_modified, activity.date_start start_date,activity.activityid as id,activity.status as status,activity.description as description, activity.priority as priority, activity.due_date as date_due ,contactdetails.firstname cfn, contactdetails.lastname cln from activity inner join salesmanactivityrel on salesmanactivityrel.activityid=activity.activityid inner join users on users.id=salesmanactivityrel.smid left join cntactivityrel on cntactivityrel.activityid=activity.activityid left join contactdetails on contactdetails.contactid=cntactivityrel.contactid inner join crmentity on crmentity.crmid=activity.activityid where user_name='" .$user_name ."' and crmentity.deleted=0 and activity.activitytype='Task' limit " .$from_index ."," .$offset;

    return $this->process_list_query1($query);
    
    }
	

    function process_list_query1($query)
    {
        $result =& $this->db->query($query,true,"Error retrieving $this->object_name list: ");
        $list = Array();
        $rows_found =  $this->db->getRowCount($result);
        if($rows_found != 0)
        {
            $task = Array();
              for($index = 0 , $row = $this->db->fetchByAssoc($result, $index); $row && $index <$rows_found;$index++, $row = $this->db->fetchByAssoc($result, $index))
            
             {
                foreach($this->range_fields as $columnName)
                {
                    if (isset($row[$columnName])) {
			    
                        $task[$columnName] = $row[$columnName];
                    }   
                    else     
                    {   
                            $task[$columnName] = "";
                    }   
	            }	
    
                $task[contact_name] = return_name($row, 'cfn', 'cln');    

                    $list[] = $task;
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


		if ($this->duedate	< $toDAy) {
			$task_fields['DATE_DUE'] = "<font class='overdueTask'>".$task_fields['DATE_DUE']."</font>";
		}
		return $task_fields;
	}
	
	function activity_reminder($activity_id,$reminder_time,$reminder_sent=0,$recurid,$remindermode='')
	{
		//Check for activityid already present in the reminder_table
		$query_exist = "SELECT activity_id FROM ".$this->reminder_table." WHERE activity_id = ".$activity_id;
		$result_exist = $this->db->query($query_exist);

		if($remindermode == 'edit')
		{
			if($this->db->num_rows($result_exist) == 1)
			{
				$query = "UPDATE ".$this->reminder_table." SET";
				$query .=" reminder_sent = ".$reminder_sent.",";
				$query .=" reminder_time = ".$reminder_time." WHERE activity_id =".$activity_id; 
			}
			else
			{
				$query = "INSERT INTO ".$this->reminder_table." VALUES (".$activity_id.",".$reminder_time.",0,'".$recurid."')";
			}
		}
		elseif(($remindermode == 'delete') && ($this->db->num_rows($result_exist) == 1))
		{
			$query = "DELETE FROM ".$this->reminder_table." WHERE activity_id = ".$activity_id;
		}
		else
		{
			$query = "INSERT INTO ".$this->reminder_table." VALUES (".$activity_id.",".$reminder_time.",0,'".$recurid."')";
		}
      		$this->db->query($query,true,"Error in processing table $this->reminder_table");
	}
	


}
?>
