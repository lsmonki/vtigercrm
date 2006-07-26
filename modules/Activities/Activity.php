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
require_once('modules/Activities/RenderRelatedListUI.php');
require_once('data/SugarBean.php');
require_once('data/CRMEntity.php');

// Task is used to store customer information.
class Activity extends CRMEntity {
	var $log;
	var $db;

	var $table_name = "vtiger_activity";
	#var $object_name = "activity";	
		// Mike Crowe Mod --------------------------------------------------------Renamed to match vtiger_tab
	var $object_name = "Activities";
	// Mike Crowe Mod --------------------------------------------------------added for general search
    var $base_table_name = "vtiger_activity";
    var $cf_table_name = "";
	var $module_id = "activityid";
	
	var $reminder_table = "vtiger_activity_reminder";
	
	var $tab_name = Array('vtiger_crmentity','vtiger_activity','vtiger_seactivityrel','vtiger_cntactivityrel','vtiger_salesmanactivityrel','vtiger_activity_reminder','vtiger_recurringevents','vtiger_invitees');

	var $tab_name_index = Array('vtiger_crmentity'=>'crmid','vtiger_activity'=>'activityid','vtiger_seactivityrel'=>'activityid','vtiger_cntactivityrel'=>'activityid','vtiger_salesmanactivityrel'=>'activityid','vtiger_activity_reminder'=>'activity_id','vtiger_recurringevents'=>'activityid');

	var $column_fields = Array();
	var $sortby_fields = Array('subject','due_date','date_start','smownerid','activitytype');	//Sorting is added for due date and start date	

	// This is used to retrieve related vtiger_fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'contactname', 'contact_phone', 'contact_email', 'parent_name');

	// This is the list of vtiger_fields that are in the lists.
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
	
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'due_date';
	var $default_sort_order = 'ASC';

	function Activity() {
		$this->log = LoggerManager::getLogger('Activities');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Activities');
	}

	var $new_schema = true;

	
	// Mike Crowe Mod --------------------------------------------------------Default ordering for us
	/**
	 * Function to get sort order
	 * return string  $sorder    - sortorder string either 'ASC' or 'DESC'
	 */
	function getSortOrder()
	{	
		global $log;                                                                                                  $log->debug("Entering getSortOrder() method ...");
		if(isset($_REQUEST['sorder'])) 
			$sorder = $_REQUEST['sorder'];
		else
			$sorder = (($_SESSION['ACTIVITIES_SORT_ORDER'] != '')?($_SESSION['ACTIVITIES_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder method ...");
		return $sorder;
	}
	
	/**
	 * Function to get order by
	 * return string  $order_by    - fieldname(eg: 'subject')
	 */
	function getOrderBy()
	{
		global $log;
                 $log->debug("Entering getOrderBy() method ...");
		if (isset($_REQUEST['order_by'])) 
			$order_by = $_REQUEST['order_by'];
		else
			$order_by = (($_SESSION['ACTIVITIES_ORDER_BY'] != '')?($_SESSION['ACTIVITIES_ORDER_BY']):($this->default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}	
	// Mike Crowe Mod --------------------------------------------------------



//Function Call for Related List -- Start
	/**
	 * Function to get Activity related Contacts
	 * @param  integer   $id      - activityid
	 * returns related Contacts record in array format
	 */
        function get_contacts($id)
	{
			global $log;
                        $log->debug("Entering get_contacts(".$id.") method ...");
			global $app_strings;

			$focus = new Contact();

			$button = '';

			$returnset = '&return_module=Activities&return_action=CallRelatedList&activity_mode=Events&return_id='.$id;

			$query = 'select vtiger_users.user_name,vtiger_contactdetails.accountid,vtiger_contactdetails.contactid, vtiger_contactdetails.firstname,vtiger_contactdetails.lastname, vtiger_contactdetails.department, vtiger_contactdetails.title, vtiger_contactdetails.email, vtiger_contactdetails.phone, vtiger_crmentity.crmid, vtiger_crmentity.smownerid, vtiger_crmentity.modifiedtime from vtiger_contactdetails inner join vtiger_cntactivityrel on vtiger_cntactivityrel.contactid=vtiger_contactdetails.contactid inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_contactdetails.contactid left join vtiger_users on vtiger_users.id = vtiger_crmentity.smownerid where vtiger_cntactivityrel.activityid='.$id.' and vtiger_crmentity.deleted=0';
			$log->debug("Exiting get_contacts method ...");
			return GetRelatedList('Activities','Contacts',$focus,$query,$button,$returnset);
        }
	
	/**
	 * Function to get Activity related Users
	 * @param  integer   $id      - activityid
	 * returns related Users record in array format
	 */

        function get_users($id)
	{	
		global $log;
                $log->debug("Entering get_contacts(".$id.") method ...");
		global $app_strings;

		$focus = new User();

		$button = '';

		$returnset = '&return_module=Activities&return_action=CallRelatedList&return_id='.$id;

		$query = 'SELECT vtiger_users.id, vtiger_users.first_name,vtiger_users.last_name, vtiger_users.user_name, vtiger_users.email1, vtiger_users.email2, vtiger_users.yahoo_id, vtiger_users.phone_home, vtiger_users.phone_work, vtiger_users.phone_mobile, vtiger_users.phone_other, vtiger_users.phone_fax,vtiger_activity.date_start,vtiger_activity.due_date,vtiger_activity.time_start,vtiger_activity.duration_hours,vtiger_activity.duration_minutes from vtiger_users inner join vtiger_salesmanactivityrel on vtiger_salesmanactivityrel.smid=vtiger_users.id  inner join vtiger_activity on vtiger_activity.activityid=vtiger_salesmanactivityrel.activityid where vtiger_activity.activityid='.$id;
		$log->debug("Exiting get_users method ...");
		return GetRelatedList('Activities','Users',$focus,$query,$button,$returnset);


	}

	/**
         * Function to get activities for given criteria
	 * @param   string   $criteria     - query string
	 * returns  activity records in array format($list) or null value
         */	 
  	function get_full_list($criteria)
  	{
	 global $log;
         $log->debug("Entering get_full_list(".$criteria.") method ...");
    $query = "select vtiger_crmentity.crmid,vtiger_crmentity.smownerid,vtiger_crmentity.setype, vtiger_activity.*, vtiger_contactdetails.lastname, vtiger_contactdetails.firstname, vtiger_contactdetails.contactid from vtiger_activity inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_activity.activityid left join vtiger_cntactivityrel on vtiger_cntactivityrel.activityid= vtiger_activity.activityid left join vtiger_contactdetails on vtiger_contactdetails.contactid= vtiger_cntactivityrel.contactid left join vtiger_seactivityrel on vtiger_seactivityrel.activityid = vtiger_activity.activityid WHERE vtiger_crmentity.deleted=0 ".$criteria;
    $result =& $this->db->query($query);
	echo $query;
        
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
    if (isset($list))
    	{
		$log->debug("Exiting get_full_list method ...");
	    return $list;
	}
	else
	{
		$log->debug("Exiting get_full_list method ...");
	    return null;
	}

  }

	
//calendarsync
    /**
     * Function to get meeting count
     * @param  string   $user_name        - User Name
     * return  integer  $row["count(*)"]  - count
     */
    function getCount_Meeting($user_name) 
	{
		global $log;
	        $log->debug("Entering getCount_Meeting(".$user_name.") method ...");
      $query = "select count(*) from vtiger_activity inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_activity.activityid inner join vtiger_salesmanactivityrel on vtiger_salesmanactivityrel.activityid=vtiger_activity.activityid inner join vtiger_users on vtiger_users.id=vtiger_salesmanactivityrel.smid where user_name='" .$user_name ."' and vtiger_crmentity.deleted=0 and vtiger_activity.activitytype='Meeting'";

      $result = $this->db->query($query,true,"Error retrieving contacts count");
      $rows_found =  $this->db->getRowCount($result);
      $row = $this->db->fetchByAssoc($result, 0);
	$log->debug("Exiting getCount_Meeting method ...");
      return $row["count(*)"];
    }
   
    function get_calendars($user_name,$from_index,$offset)
    {   
	    global $log;
            $log->debug("Entering get_calendars(".$user_name.",".$from_index.",".$offset.") method ...");
		$query = "select vtiger_activity.location as location,vtiger_activity.duration_hours as duehours, vtiger_activity.duration_minutes as dueminutes,vtiger_activity.time_start as time_start, vtiger_activity.subject as name,vtiger_crmentity.modifiedtime as date_modified, vtiger_activity.date_start start_date,vtiger_activity.activityid as id,vtiger_activity.status as status, vtiger_crmentity.description as description, vtiger_activity.priority as vtiger_priority, vtiger_activity.due_date as date_due ,vtiger_contactdetails.firstname cfn, vtiger_contactdetails.lastname cln from vtiger_activity inner join vtiger_salesmanactivityrel on vtiger_salesmanactivityrel.activityid=vtiger_activity.activityid inner join vtiger_users on vtiger_users.id=vtiger_salesmanactivityrel.smid left join vtiger_cntactivityrel on vtiger_cntactivityrel.activityid=vtiger_activity.activityid left join vtiger_contactdetails on vtiger_contactdetails.contactid=vtiger_cntactivityrel.contactid inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_activity.activityid where user_name='" .$user_name ."' and vtiger_crmentity.deleted=0 and vtiger_activity.activitytype='Meeting' limit " .$from_index ."," .$offset;
	$log->debug("Exiting get_calendars method ...");
	    return $this->process_list_query1($query);   
    }       
//calendarsync
	/**
	 * Function to get task count
	 * @param  string   $user_name        - User Name
	 * return  integer  $row["count(*)"]  - count
	 */
    function getCount($user_name) 
    {
	    global $log;
            $log->debug("Entering getCount(".$user_name.") method ...");
        $query = "select count(*) from vtiger_activity inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_activity.activityid inner join vtiger_salesmanactivityrel on vtiger_salesmanactivityrel.activityid=vtiger_activity.activityid inner join vtiger_users on vtiger_users.id=vtiger_salesmanactivityrel.smid where user_name='" .$user_name ."' and vtiger_crmentity.deleted=0 and vtiger_activity.activitytype='Task'";

        $result = $this->db->query($query,true,"Error retrieving contacts count");
        $rows_found =  $this->db->getRowCount($result);
        $row = $this->db->fetchByAssoc($result, 0);

	$log->debug("Exiting getCount method ...");    
        return $row["count(*)"];
    }       

    /**
     * Function to get list of task for user with given limit
     * @param  string   $user_name        - User Name
     * @param  string   $from_index       - query string
     * @param  string   $offset           - query string 
     * returns tasks in array format
     */
    function get_tasks($user_name,$from_index,$offset)
    {   
	global $log;
        $log->debug("Entering get_tasks(".$user_name.",".$from_index.",".$offset.") method ...");
	 $query = "select vtiger_activity.subject as name,vtiger_crmentity.modifiedtime as date_modified, vtiger_activity.date_start start_date,vtiger_activity.activityid as id,vtiger_activity.status as status, vtiger_crmentity.description as description, vtiger_activity.priority as priority, vtiger_activity.due_date as date_due ,vtiger_contactdetails.firstname cfn, vtiger_contactdetails.lastname cln from vtiger_activity inner join vtiger_salesmanactivityrel on vtiger_salesmanactivityrel.activityid=vtiger_activity.activityid inner join vtiger_users on vtiger_users.id=vtiger_salesmanactivityrel.smid left join vtiger_cntactivityrel on vtiger_cntactivityrel.activityid=vtiger_activity.activityid left join vtiger_contactdetails on vtiger_contactdetails.contactid=vtiger_cntactivityrel.contactid inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_activity.activityid where user_name='" .$user_name ."' and vtiger_crmentity.deleted=0 and vtiger_activity.activitytype='Task' limit " .$from_index ."," .$offset;
	 $log->debug("Exiting get_tasks method ...");
    return $this->process_list_query1($query);
    
    }
	
    /**
     * Function to process the activity list query
     * @param  string   $query     - query string
     * return  array    $response  - activity lists
     */
    function process_list_query1($query)
    {
	    global $log;
            $log->debug("Entering process_list_query1(".$query.") method ...");
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


	$log->debug("Exiting process_list_query1 method ...");
        return $response;
    }

    	/**
	 * Function to get reminder for activity
	 * @param  integer   $activity_id     - activity id
	 * @param  string    $reminder_time   - reminder time
	 * @param  integer   $reminder_sent   - 0 or 1
	 * @param  integer   $recurid         - recuring eventid
	 * @param  string    $remindermode    - string like 'edit'	 
	 */	
	function activity_reminder($activity_id,$reminder_time,$reminder_sent=0,$recurid,$remindermode='')
	{
		global $log;
		$log->debug("Entering vtiger_activity_reminder(".$activity_id.",".$reminder_time.",".$reminder_sent.",".$recurid.",".$remindermode.") method ...");
		//Check for vtiger_activityid already present in the reminder_table
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
      		$this->db->query($query,true,"Error in processing vtiger_table $this->reminder_table");
		$log->debug("Exiting vtiger_activity_reminder method ...");
	}

//Used for vtigerCRM Outlook Add-In
/**
 * Function to get tasks to display in outlookplugin
 * @param   string    $username     -  User name
 * return   string    $query        -  sql query 
 */
function get_tasksforol($username)
{
	global $log;
        $log->debug("Entering get_tasksforol(".$username.") method ...");
	$query = "select vtiger_activity.subject,vtiger_activity.date_start startdate,
			 vtiger_activity.activityid as taskid,vtiger_activity.status,
			 vtiger_crmentity.description,vtiger_activity.priority as vtiger_priority,vtiger_activity.due_date as duedate,
			 vtiger_contactdetails.firstname, vtiger_contactdetails.lastname 
			 from vtiger_activity inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_activity.activityid 
			 inner join vtiger_users on vtiger_users.id = vtiger_crmentity.smownerid 
			 left join vtiger_cntactivityrel on vtiger_cntactivityrel.activityid=vtiger_activity.activityid 
			 left join vtiger_contactdetails on vtiger_contactdetails.contactid=vtiger_cntactivityrel.contactid 
			 where vtiger_users.user_name='".$username."' and vtiger_crmentity.deleted=0 and vtiger_activity.activitytype='Task'";
	$log->debug("Exiting get_tasksforol method ...");		 
	return $query;
}

/**
 * Function to get calendar query for outlookplugin
 * @param   string    $username     -  User name                                                                            * return   string    $query        -  sql query                                                                            */ 
function get_calendarsforol($user_name)
{
	global $log;
        $log->debug("Entering get_calendarsforol(".$user_name.") method ...");
	  $query = "select vtiger_activity.location, vtiger_activity.duration_hours as duehours, 
			vtiger_activity.duration_minutes as dueminutes,vtiger_activity.time_start as startime, 
			vtiger_activity.subject,vtiger_activity.date_start as startdate,vtiger_activity.activityid as clndrid,
			vtiger_crmentity.description,vtiger_activity.due_date as duedate ,
			vtiger_contactdetails.firstname, vtiger_contactdetails.lastname from vtiger_activity 
				inner join vtiger_salesmanactivityrel on vtiger_salesmanactivityrel.activityid=vtiger_activity.activityid 
				inner join vtiger_users on vtiger_users.id=vtiger_salesmanactivityrel.smid 
				left join vtiger_cntactivityrel on vtiger_cntactivityrel.activityid=vtiger_activity.activityid 
				left join vtiger_contactdetails on vtiger_contactdetails.contactid=vtiger_cntactivityrel.contactid 
				inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_activity.activityid 
				where vtiger_users.user_name='".$user_name."' and vtiger_crmentity.deleted=0 and vtiger_activity.activitytype='Meeting'";
	$log->debug("Exiting get_calendarsforol method ...");
	return $query;
}
//End

}
?>
