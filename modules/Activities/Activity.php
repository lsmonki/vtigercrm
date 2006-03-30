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
	#var $object_name = "activity";	
		// Mike Crowe Mod --------------------------------------------------------Renamed to match tab
	var $object_name = "Activities";
	// Mike Crowe Mod --------------------------------------------------------added for general search
    var $base_table_name = "activity";
    var $cf_table_name = "";
	var $module_id = "activityid";

	
	var $reminder_table = "activity_reminder";
	
	var $tab_name = Array('crmentity','activity','seactivityrel','cntactivityrel','salesmanactivityrel','activity_reminder','recurringevents');

	var $tab_name_index = Array('crmentity'=>'crmid','activity'=>'activityid','seactivityrel'=>'activityid','cntactivityrel'=>'activityid','salesmanactivityrel'=>'activityid','activity_reminder'=>'activity_id','recurringevents'=>'activityid');

	var $column_fields = Array();
	var $sortby_fields = Array('subject','due_date','date_start','smownerid');	//Sorting is added for due date and start date	

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
	function getSortOrder()
	{	
		if(isset($_REQUEST['sorder'])) 
			$sorder = $_REQUEST['sorder'];
		else
			$sorder = (($_SESSION['ACTIVITIES_SORT_ORDER'] != '')?($_SESSION['ACTIVITIES_SORT_ORDER']):($this->default_sort_order));

		return $sorder;
	}
	
	function getOrderBy()
	{
		if (isset($_REQUEST['order_by'])) 
			$order_by = $_REQUEST['order_by'];
		else
			$order_by = (($_SESSION['ACTIVITIES_ORDER_BY'] != '')?($_SESSION['ACTIVITIES_ORDER_BY']):($this->default_order_by));

		return $order_by;
	}	
	// Mike Crowe Mod --------------------------------------------------------



//Function Call for Related List -- Start
        function get_contacts($id)
	{
			global $app_strings;

			$focus = new Contact();

			$button = '';

			if(isPermitted("Contacts",3,"") == 'yes')
			{
				$button .= '<input title="Change" accessKey="" tabindex="2" type="button" class="button" value="'.$app_strings['LBL_SELECT_CONTACT_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Contacts&return_module=Activities&action=Popup&popuptype=detailview&form=EditView&form_submit=false&recordid='.$_REQUEST["record"].'","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;';
			}
			$returnset = '&return_module=Activities&return_action=DetailView&activity_mode=Events&return_id='.$id;


			$query = 'select contactdetails.accountid, contactdetails.contactid, contactdetails.firstname,contactdetails.lastname, contactdetails.department, contactdetails.title, contactdetails.email, contactdetails.phone, crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime from contactdetails inner join seactivityrel on seactivityrel.crmid=contactdetails.contactid inner join crmentity on crmentity.crmid = contactdetails.contactid where seactivityrel.activityid='.$id.' and crmentity.deleted=0';
			return GetRelatedList('Activities','Contacts',$focus,$query,$button,$returnset);
        }

        function get_users($id)
	{
			$query = 'SELECT users.id, users.first_name,users.last_name, users.user_name, users.email1, users.email2, users.yahoo_id, users.phone_home, users.phone_work, users.phone_mobile, users.phone_other, users.phone_fax,activity.date_start,activity.due_date,activity.time_start,activity.duration_hours,activity.duration_minutes from users inner join salesmanactivityrel on salesmanactivityrel.smid=users.id  inner join activity on activity.activityid=salesmanactivityrel.activityid where activity.activityid='.$id;
			$activity_id=$id;
			global $adb,$log;

			global $mod_strings;
			global $app_strings;

			$result=$adb->query($query);   


			$noofrows = $adb->num_rows($result);

			$header[] = $app_strings['LBL_LIST_NAME'];
			$header[] = $app_strings['LBL_LIST_USER_NAME'];
			$header[] = $app_strings['LBL_EMAIL'];
			$header[] = $app_strings['LBL_PHONE']; 


			// To display the dates for the Group calendar starts -Jaguar
			$recur_dates_qry='select distinct(recurringdate) from recurringevents where activityid='.$activity_id;
			$recur_result=$adb->query($recur_dates_qry);
			$noofrows_recur = $adb->num_rows($recur_result);
			if($noofrows_recur==0)
			{
				$recur_dates_qry='select activity.date_start,recurringevents.* from activity left outer join recurringevents on activity.activityid=recurringevents.activityid where recurringevents.activityid is NULL and activity.activityid='.$activity_id .' group by activity.activityid';
				$recur_result=$adb->query($recur_dates_qry);
				$noofrows_recur = $adb->num_rows($recur_result);

			}

			while($row = $adb->fetch_array($result))
			{

				global $current_user;

				$entries = Array();	

				if(is_admin($current_user))
				{
					$entries[] = $row['last_name'].' '.$row['first_name'];
				}
				else
				{
					$entries[] = $row['last_name'].' '.$row['first_name'];
				}	

				$entries[] = $row['user_name'];

				$entries[] = $row['email1'];
				if($email == '')	$email = $row['email2'];
				if($email == '')	$email = $row['yahoo_id'];
				$entries[] = $row['phone_home'];
				if($phone == '')	$phone = $row['phone_work'];
				if($phone == '')        $phone = $row['phone_other'];
				if($phone == '')	$phone = $row['phone_fax'];

				if(is_admin($current_user))
				{		
					$list .= '<a href="index.php?module=Users&action=EditView&return_module=Activities&return_action=DetailView&activity_mode=Events&record='.$row["id"].'&return_id='.$_REQUEST['record'].'">'.$app_strings['LNK_EDIT'].'</a>  | ';
				}


				//Added for Group Calendar -Jaguar


				$act_date_start= getDBInsertDateValue($row['date_start']); //getting the Date format - Jaguar
				$act_due_date= getDBInsertDateValue($row['due_date']);

				$act_time_start=$row['time_start'];
				$act_mins_dur=$row['duration_minutes'];

				$activity_start_time=time_to_number($act_time_start);	
				$activity_end_time=get_duration($act_time_start,$act_hour_dur,$act_mins_dur);	

				$activity_owner_qry='select users.user_name,users.id  userid from users,crmentity where users.id=crmentity.smownerid and crmentity.crmid='.$id;
				$result_owner=$adb->query($activity_owner_qry);

				while($row_owner = $adb->fetch_array($result_owner))
				{
					$owner=$row_owner['userid'];
				}

				$recur_dates_qry='select recurringdate from recurringevents where activityid ='.$activity_id;
				$recur_result=$adb->query($recur_dates_qry);
				$noofrows_recur = $adb->num_rows($recur_result);
				$userid=$row['id'];
				if($noofrows_recur !=0)
				{
					while($row_recur = $adb->fetch_array($recur_result))
					{
						$recur_dates=getDBInsertDateValue($row_recur['recurringdate']);
						$availability=status_availability($owner,$userid,$activity_id,$recur_dates,$activity_start_time,$activity_end_time);	
						$log->info("activity start time ".$activity_start_time."activity end time".$activity_end_time."Available date".$recur_dates);


					}
				}
				else
				{
					$recur_dates=$act_date_start;
					$availability=status_availability($owner,$userid,$activity_id,$recur_dates,$activity_start_time,$activity_end_time);	
					$log->info("activity start time ".$activity_start_time."activity end time".$activity_end_time."Available  date".$recur_dates);		
				}
				// Group Calendar coding	


				$entries_list[]=$entries;
			}


			if($entries_list != '')
				$return_data = array('header'=>$header, 'entries'=>$entries_list);
			return $return_data;

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
		$query = "select activity.location as location,activity.duration_hours as duehours, activity.duration_minutes as dueminutes,activity.time_start as time_start, activity.subject as name,crmentity.modifiedtime as date_modified, activity.date_start start_date,activity.activityid as id,activity.status as status, crmentity.description as description, activity.priority as priority, activity.due_date as date_due ,contactdetails.firstname cfn, contactdetails.lastname cln from activity inner join salesmanactivityrel on salesmanactivityrel.activityid=activity.activityid inner join users on users.id=salesmanactivityrel.smid left join cntactivityrel on cntactivityrel.activityid=activity.activityid left join contactdetails on contactdetails.contactid=cntactivityrel.contactid inner join crmentity on crmentity.crmid=activity.activityid where user_name='" .$user_name ."' and crmentity.deleted=0 and activity.activitytype='Meeting' limit " .$from_index ."," .$offset;
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

	 $query = "select activity.subject as name,crmentity.modifiedtime as date_modified, activity.date_start start_date,activity.activityid as id,activity.status as status, crmentity.description as description, activity.priority as priority, activity.due_date as date_due ,contactdetails.firstname cfn, contactdetails.lastname cln from activity inner join salesmanactivityrel on salesmanactivityrel.activityid=activity.activityid inner join users on users.id=salesmanactivityrel.smid left join cntactivityrel on cntactivityrel.activityid=activity.activityid left join contactdetails on contactdetails.contactid=cntactivityrel.contactid inner join crmentity on crmentity.crmid=activity.activityid where user_name='" .$user_name ."' and crmentity.deleted=0 and activity.activitytype='Task' limit " .$from_index ."," .$offset;

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

//Used for vtigerCRM Outlook Add-In
function get_tasksforol($username)
{
	$query = "select activity.subject,activity.date_start startdate,
			 activity.activityid as taskid,activity.status,
			 crmentity.description,activity.priority as priority,activity.due_date as duedate,
			 contactdetails.firstname, contactdetails.lastname 
			 from activity inner join crmentity on crmentity.crmid=activity.activityid 
			 inner join users on users.id = crmentity.smownerid 
			 left join cntactivityrel on cntactivityrel.activityid=activity.activityid 
			 left join contactdetails on contactdetails.contactid=cntactivityrel.contactid 
			 where users.user_name='".$username."' and crmentity.deleted=0 and activity.activitytype='Task'";
		 
	return $query;
}

function get_calendarsforol($user_name)
{
	  $query = "select activity.location, activity.duration_hours as duehours, 
				activity.duration_minutes as dueminutes,activity.time_start as startime, 
				activity.subject,activity.date_start as startdate,activity.activityid as clndrid,
				crmentity.description,activity.due_date as duedate ,
				contactdetails.firstname, contactdetails.lastname from activity 
				inner join salesmanactivityrel on salesmanactivityrel.activityid=activity.activityid 
				inner join users on users.id=salesmanactivityrel.smid 
				left join cntactivityrel on cntactivityrel.activityid=activity.activityid 
				left join contactdetails on contactdetails.contactid=cntactivityrel.contactid 
				inner join crmentity on crmentity.crmid=activity.activityid 
				where users.user_name='".$user_name."' and crmentity.deleted=0 and activity.activitytype='Meeting'";

	return $query;
}
//End

}
?>
