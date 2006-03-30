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


    function getCount($user_name) 
    {
        $query = "select count(*) from tasks inner join users on users.id=tasks.assigned_user_id where user_name='" .$user_name ."' and tasks.deleted=0";

//       echo "\n Query is " .$query ."\n";
        $result = $this->db->query($query,true,"Error retrieving contacts count");
        $rows_found =  $this->db->getRowCount($result);
        $row = $this->db->fetchByAssoc($result, 0);

    
        return $row["count(*)"];
    }       

	
}
?>
