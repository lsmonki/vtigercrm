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

	// Stored vtiger_fields
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
		// Mike Crowe Mod --------------------------------------------------------Renamed to match vtiger_tab
	var $object_name = "Activities";
	// Mike Crowe Mod --------------------------------------------------------added for general search
    var $base_table_name = "activity";
    var $cf_table_name = "";
	var $module_id = "activityid";

	
	var $reminder_table = "activity_reminder";
	
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
        function get_contacts($id)
	{
			global $log;
                        $log->debug("Entering get_contacts(".$id.") method ...");
			global $app_strings;

			$focus = new Contact();

			$button = '';

			if(isPermitted("Contacts",3,"") == 'yes')
			{
				$button .= '<input title="Change" accessKey="" vtiger_tabindex="2" type="button" class="button" value="'.$app_strings['LBL_SELECT_CONTACT_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Contacts&return_module=Activities&action=Popup&popuptype=detailview&form=EditView&form_submit=false&recordid='.$_REQUEST["record"].'","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;';
			}
			$returnset = '&return_module=Activities&return_action=DetailView&activity_mode=Events&return_id='.$id;


			$query = 'select vtiger_contactdetails.accountid, vtiger_contactdetails.contactid, vtiger_contactdetails.firstname,vtiger_contactdetails.lastname, vtiger_contactdetails.department, vtiger_contactdetails.title, vtiger_contactdetails.email, vtiger_contactdetails.phone, vtiger_crmentity.crmid, vtiger_crmentity.smownerid, vtiger_crmentity.modifiedtime from vtiger_contactdetails inner join vtiger_seactivityrel on vtiger_seactivityrel.crmid=vtiger_contactdetails.contactid inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_contactdetails.contactid where vtiger_seactivityrel.activityid='.$id.' and vtiger_crmentity.deleted=0';
			$log->debug("Exiting get_contacts method ...");
			return GetRelatedList('Activities','Contacts',$focus,$query,$button,$returnset);
        }

        function get_users($id)
	{
			global $adb,$log;
			$log->debug("Entering get_users(".$id.") method ...");
			$query = 'SELECT vtiger_users.id, vtiger_users.first_name,vtiger_users.last_name, vtiger_users.user_name, vtiger_users.email1, vtiger_users.email2, vtiger_users.yahoo_id, vtiger_users.phone_home, vtiger_users.phone_work, vtiger_users.phone_mobile, vtiger_users.phone_other, vtiger_users.phone_fax,vtiger_activity.date_start,vtiger_activity.due_date,vtiger_activity.time_start,vtiger_activity.duration_hours,vtiger_activity.duration_minutes from vtiger_users inner join vtiger_salesmanactivityrel on vtiger_salesmanactivityrel.smid=vtiger_users.id  inner join vtiger_activity on vtiger_activity.activityid=vtiger_salesmanactivityrel.activityid where vtiger_activity.activityid='.$id;
			$activity_id=$id;

			global $mod_strings;
			global $app_strings;

			$result=$adb->query($query);   


			$noofrows = $adb->num_rows($result);

			$header[] = $app_strings['LBL_LIST_NAME'];
			$header[] = $app_strings['LBL_LIST_USER_NAME'];
			$header[] = $app_strings['LBL_EMAIL'];
			$header[] = $app_strings['LBL_PHONE']; 



			while($row = $adb->fetch_array($result))
			{

				global $current_user;

				$entries = Array();	

				if(is_admin($current_user))
				{
					$entries[] = '<a href="index.php?module=Users&action=DetailView&parenttab=Settings&return_module=Activities&return_action=DetailView&activity_mode=Events&record='.$row["id"].'&return_id='.$_REQUEST['record'].'">'.$row['last_name'].' '.$row['first_name'].'</a>';
					$entries[] = '<a href="index.php?module=Users&action=DetailView&parenttab=Settings&return_module=Activities&return_action=DetailView&activity_mode=Events&record='.$row["id"].'&return_id='.$_REQUEST['record'].'">'.$row['user_name'].'</a>';
				}
				else
				{
					$entries[] = $row['last_name'].' '.$row['first_name'];
					$entries[] = $row['user_name'];
				}	


				$entries[] = '<a href="mailto:'.$row["email1"].'"]">'.$row['email1'].'</a>';
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

			// To display the dates for the Group calendar starts -Jaguar
			$recur_dates_qry='select distinct(recurringdate) from vtiger_recurringevents where vtiger_activityid='.$activity_id;
			$recur_result=$adb->query($recur_dates_qry);
			$noofrows_recur = $adb->num_rows($recur_result);
			if($noofrows_recur==0)
			{
				$recur_dates_qry='select vtiger_activity.date_start,vtiger_recurringevents.* from vtiger_activity left outer join vtiger_recurringevents on vtiger_activity.activityid=vtiger_recurringevents.activityid where vtiger_recurringevents.activityid is NULL and vtiger_activity.activityid='.$activity_id;
				$recur_result=$adb->query($recur_dates_qry);
				$noofrows_recur = $adb->num_rows($recur_result);

			}
				//Added for Group Calendar -Jaguar


				$act_date_start= getDBInsertDateValue($row['date_start']); //getting the Date format - Jaguar
				$act_due_date= getDBInsertDateValue($row['due_date']);

				$act_time_start=$row['time_start'];
				$act_mins_dur=$row['duration_minutes'];

				$activity_start_time=time_to_number($act_time_start);	
				$activity_end_time=get_duration($act_time_start,$act_hour_dur,$act_mins_dur);	

				$activity_owner_qry='select vtiger_users.user_name,vtiger_users.id AS userid from vtiger_users,crmentity where vtiger_users.id=vtiger_crmentity.smownerid and vtiger_crmentity.crmid='.$id;
				$result_owner=$adb->query($activity_owner_qry);

				while($row_owner = $adb->fetch_array($result_owner))
				{
					$owner=$row_owner['userid'];
				}

				$recur_dates_qry='select recurringdate from vtiger_recurringevents where vtiger_activityid ='.$activity_id;
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
			$log->debug("Exiting get_users method ...");
			return $return_data;

		}

  	function get_full_list($criteria)
  	{
	 global $log;
         $log->debug("Entering get_full_list(".$criteria.") method ...");
    $query = "select vtiger_crmentity.crmid,vtiger_crmentity.smownerid,vtiger_crmentity.setype, vtiger_activity.*, vtiger_contactdetails.lastname, vtiger_contactdetails.firstname, vtiger_contactdetails.contactid from vtiger_activity inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_activity.activityid left join vtiger_cntactivityrel on vtiger_cntactivityrel.activityid= vtiger_activity.activityid left join vtiger_contactdetails on vtiger_contactdetails.contactid= vtiger_cntactivityrel.contactid left join vtiger_seactivityrel on vtiger_seactivityrel.activityid = vtiger_activity.activityid WHERE vtiger_crmentity.deleted=0 ".$criteria;
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

    function get_tasks($user_name,$from_index,$offset)
    {   
	global $log;
        $log->debug("Entering get_tasks(".$user_name.",".$from_index.",".$offset.") method ...");
	 $query = "select vtiger_activity.subject as name,vtiger_crmentity.modifiedtime as date_modified, vtiger_activity.date_start start_date,vtiger_activity.activityid as id,vtiger_activity.status as status, vtiger_crmentity.description as description, vtiger_activity.priority as vtiger_priority, vtiger_activity.due_date as date_due ,vtiger_contactdetails.firstname cfn, vtiger_contactdetails.lastname cln from vtiger_activity inner join vtiger_salesmanactivityrel on vtiger_salesmanactivityrel.activityid=vtiger_activity.activityid inner join vtiger_users on vtiger_users.id=vtiger_salesmanactivityrel.smid left join vtiger_cntactivityrel on vtiger_cntactivityrel.activityid=vtiger_activity.activityid left join vtiger_contactdetails on vtiger_contactdetails.contactid=vtiger_cntactivityrel.contactid inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_activity.activityid where user_name='" .$user_name ."' and vtiger_crmentity.deleted=0 and vtiger_activity.activitytype='Task' limit " .$from_index ."," .$offset;
	 $log->debug("Exiting get_tasks method ...");
    return $this->process_list_query1($query);
    
    }
	

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
		
	function vtiger_activity_reminder($activity_id,$reminder_time,$reminder_sent=0,$recurid,$remindermode='')
	{
		global $log;
		$log->debug("Entering vtiger_activity_reminder(".$activity_id.",".$reminder_time.",".$reminder_sent.",".$recurid.",".$remindermode.") method ...");
		//Check for vtiger_activityid already present in the reminder_table
		$query_exist = "SELECT vtiger_activity_id FROM ".$this->reminder_table." WHERE vtiger_activity_id = ".$activity_id;
		$result_exist = $this->db->query($query_exist);

		if($remindermode == 'edit')
		{
			if($this->db->num_rows($result_exist) == 1)
			{
				$query = "UPDATE ".$this->reminder_table." SET";
				$query .=" reminder_sent = ".$reminder_sent.",";
				$query .=" reminder_time = ".$reminder_time." WHERE vtiger_activity_id =".$activity_id; 
			}
			else
			{
				$query = "INSERT INTO ".$this->reminder_table." VALUES (".$activity_id.",".$reminder_time.",0,'".$recurid."')";
			}
		}
		elseif(($remindermode == 'delete') && ($this->db->num_rows($result_exist) == 1))
		{
			$query = "DELETE FROM ".$this->reminder_table." WHERE vtiger_activity_id = ".$activity_id;
		}
		else
		{
			$query = "INSERT INTO ".$this->reminder_table." VALUES (".$activity_id.",".$reminder_time.",0,'".$recurid."')";
		}
      		$this->db->query($query,true,"Error in processing vtiger_table $this->reminder_table");
		$log->debug("Exiting vtiger_activity_reminder method ...");
	}

//Used for vtigerCRM Outlook Add-In
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
