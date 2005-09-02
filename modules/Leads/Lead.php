<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of txhe License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('data/CRMEntity.php');
require_once('modules/Activities/Activity.php');
require_once('modules/Notes/Note.php');
require_once('modules/Emails/Email.php');
require_once('include/ComboUtil.php');
require_once('include/utils.php');

class Lead extends CRMEntity {
	var $log;
	var $db;

	// Stored fields
  var $leadid;
        var $email;
	var $firstname;
        var $salutation;
	var $lastname;
	var $company;
	var $annualrevenue;
  
	var $industry;
  var $campaign;
         var $rating;
         var $status;
         var $leadsource;
         var $designation;
         var $licencekey;
  var $region;
  var $space;
  var $comments;
  var $priority;
  var $partnercontact;
  var $maildate;
  var $nextstepdate;
  var $fundingsituation;
  var $deleted;
  
  	var $description;
        // These are for related fields
  var $city;
  var $code;
  var $state;
  var $country;
  var $phone;
  var $mobile;
  var $fax;
  var $lane;
  var $leadaddresstype;
  var $currency;
  var $website;
  var $callornot;
  var $readornot;
  var $empct;
  
  var $accountid;
	var $contactid;
  	var $potentialid;
  	
	var $module_id = "leadid";
	//var $tab_name = Array('crmentity','account','accountbillads','accountshipads');

	var $tab_name = Array('crmentity','leaddetails','leadsubdetails','leadaddress','leadscf');
        var $tab_name_index = Array('crmentity'=>'crmid','leaddetails'=>'leadid','leadsubdetails'=>'leadsubscriptionid','leadaddress'=>'leadaddressid','leadscf'=>'leadid');

	
	var $entity_table = "crmentity";
	var $table_name = "leaddetails";

	var $object_name = "Lead";

	var $new_schema = true;

	//construct this from database;	
	var $column_fields = Array();

        var $sortby_fields = Array('lastname','email','phone');		  
       
	var $combofieldNames = Array('leadsource'=>'leadsource_dom'
                      ,'salutation'=>'salutation_dom'
                      ,'status'=>'leadstatus_dom'
                      ,'industry'=>'industry_dom'
                      ,'rating'=>'rating_dom'
                      ,'licencekey'=>'licensekey_dom');
	
	// This is used to retrieve related fields from form posts.
	
	var $additional_column_fields = Array('smcreatorid', 'smownerid', 'contactid','potentialid' ,'crmid');
	
	// This is the list of fields that are in the lists.
        var $list_fields = Array(
                                'Name'=>Array('leaddetails'=>'lastname'),
                                'Company'=>Array('leaddetails'=>'company'),
                                'Phone'=>Array('leadaddress'=>'phone'),
                                'Website'=>Array('leadsubdetails'=>'website'),
                                'Email'=>Array('leaddetails'=>'email'),
                                'Assigned To'=>Array('crmentity'=>'smownerid')
                                );
        var $list_fields_name = Array(
                                        'Name'=>'lastname',
                                        'Company'=>'company',
                                        'Phone'=>'phone',
                                        'Website'=>'website',
                                        'Email'=>'email',
                                        'Assigned To'=>'assigned_user_id'
                                     );
        var $list_link_field= 'lastname';

	var $record_id;
	var $list_mode;
        var $popup_type;

	var $search_fields = Array(
                                'Name'=>Array('leaddetails'=>'lastname'),
                                'Company'=>Array('leaddetails'=>'company')
                                );
        var $search_fields_name = Array(
                                        'Name'=>'lastname',
                                        'Company'=>'company'
                                        );

	var $required_fields =  array("lastname"=>1, 'company'=>1);

	function Lead() {
		$this->log = LoggerManager::getLogger('lead');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Leads');
	}

	function create_tables () {
          
	}

	function drop_tables () {
        }

	function get_summary_text()
	{
		return "$this->firstname $this->lastname";
	}
	
//method added to construct the query to fetch the custom fields 
	function constructCustomQueryAddendum()
	{
        global $adb;
        	//get all the custom fields created 
		$sql1 = "select columnname,fieldlabel from field where generatedtype=2 and tabid=7";
        	$result = $adb->query($sql1);
		$numRows = $adb->num_rows($result);
	//select accountscf.columnname fieldlabel,accountscf.columnname fieldlabel	
		$sql3 = "select ";
		for($i=0; $i < $numRows;$i++)
		{
			$columnName = $adb->query_result($result,$i,"columnname");
			$fieldlable = $adb->query_result($result,$i,"fieldlabel");
			//construct query as below
		       if($i == 0)
		      	{
				$sql3 .= "leadscf.".$columnName. " '" .$fieldlable."'";
			}
			else
			{	
				$sql3 .= ", leadscf.".$columnName. " '" .$fieldlable."'";
			}
        
	         }
	return $sql3;

	}

//check if the custom table exists or not in the first place
function checkIfCustomTableExists()
{
  //$result = mysql_query("select * from accountcf");
  //$testrow = mysql_num_fields($result);
 $result = $this->db->query("select * from leadscf");
 $testrow = $this->db->num_fields($result);
	if($testrow > 1)
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
          
  $query = $this->constructCustomQueryAddendum() . ", 
			leaddetails.*, ".$this->entity_table.".*, leadsubdetails.*,leadaddress.city city, leadaddress.state state,leadaddress.code code,leadaddress.country country, leadaddress.phone phone, users.user_name, users.status user_status
                        FROM ".$this->entity_table."
                        INNER JOIN leaddetails
                        ON crmentity.crmid=leaddetails.leadid
                        LEFT JOIN leadaddress 
                        ON leaddetails.leadid=leadaddress.leadaddressid
                        LEFT JOIN leadsubdetails
                        ON leaddetails.leadid=leadsubdetails.leadsubscriptionid
                        LEFT JOIN leadscf 
                        ON leadscf.leadid=leaddetails.leadid
                        LEFT JOIN users
                        ON crmentity.smownerid = users.id ";

		}
		else
		{
                  $query = "SELECT 
			leaddetails.*, ".$this->entity_table.".*, leadsubdetails.*,leadaddress.*,users.user_name, users.status user_status FROM ".$this->entity_table."
                        INNER JOIN leaddetails
                        ON crmentity.crmid=leaddetails.leadid
                        LEFT JOIN leadsubdetails
                        ON leaddetails.leadid = leadsubdetails.leadsubscriptionid
                        LEFT JOIN leadaddress
                        ON leaddetails.leadid=leadaddress.leadaddressid
			LEFT JOIN users
                        ON crmentity.smownerid = users.id ";
		}

                        $where_auto = " users.status='Active'
                        AND crmentity.deleted=0 ";

                if($where != "")
                        $query .= "where ($where) AND ".$where_auto;
                else
                        $query .= "where ".$where_auto;

                if(!empty($order_by))
                        $query .= " ORDER BY $order_by";

                return $query;
        }


	
	/** Returns a list of the associated tasks
	*/
	function get_activities($id)
	{
          // First, get the list of IDs.
	    $query = "SELECT contactdetails.lastname, contactdetails.firstname, contactdetails.contactid, activity.*,seactivityrel.*,crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime, users.user_name,recurringevents.recurringtype from activity inner join seactivityrel on seactivityrel.activityid=activity.activityid inner join crmentity on crmentity.crmid=activity.activityid left join cntactivityrel on cntactivityrel.activityid= activity.activityid left join contactdetails on contactdetails.contactid = cntactivityrel.contactid left join users on users.id=crmentity.smownerid left outer join recurringevents on recurringevents.activityid=activity.activityid where seactivityrel.crmid=".$id." and (activitytype='Task' or activitytype='Call' or activitytype='Meeting') AND ( activity.status is NULL || activity.status != 'Completed' ) and (  activity.eventstatus is NULL ||  activity.eventstatus != 'Held')";
          //include('modules/Leads/RenderRelatedListUI.php');
          renderRelatedTasks($query,$id);
        }

	/** Returns a list of the associated notes
         */
	function get_notes($id)
	{
		// First, get the list of IDs.
//          $query = "SELECT notes.title,notes.filename,notes.notecontent from notes inner join senotesrel on senotesrel.notesid=notes.notesid inner join crmentity on crmentity.crmid=senotesrel.notesid and crmentity.deleted=0 where senotesrel.crmid=".$id."";
//          renderRelatedNotes($query,$id);
        }

	/** Returns a list of the associated meetings
	*/
	function get_meetings($id)
	{
		// First, get the list of IDs.
          $query ="select name,meetings.location,meetings.status,meetings.description from meetings inner join events on meetings.meetingid=events.eventid inner join activity on activity.activityid=events.eventid inner join seactivityrel on seactivityrel.activityid=meetings.meetingid where seactivityrel.crmid=".$id;
          renderRelatedMeetings($query,$id);
        }

	/** Returns a list of the associated calls
	*/
  function get_calls($id)
  {
    // First, get the list of IDs.
    $query="select name,calls.description,calls.status,calls.date_start from calls inner join events on calls.callid=events.eventid inner join activity on activity.activityid=events.eventid inner join seactivityrel on seactivityrel.activityid=calls.callid where seactivityrel.crmid=".$id."";
    renderRelatedCalls($query,$id);
        }

	/** Returns a list of the associated emails
	*/
	function get_emails($id)
	{
		// First, get the list of IDs.
	// First, get the list of IDs.
	    $query ="select activity.activityid, activity.subject, emails.emailid, emails.filename,semodule,activitytype,date_start,activity.status,priority,crmentity.crmid,crmentity.smownerid,crmentity.modifiedtime, users.user_name  from emails inner join activity on activity.activityid=emails.emailid inner join seactivityrel on seactivityrel.activityid=emails.emailid inner join crmentity on crmentity.crmid=emails.emailid inner join users on  users.id=crmentity.smownerid where seactivityrel.crmid=".$id;
            renderRelatedEmails($query,$id);
        }
	function get_history($id)
	{
		$query = "SELECT activity.activityid, activity.subject, activity.status, activity.eventstatus, activity.activitytype, activity.description, contactdetails.contactid, contactdetails.firstname, contactdetails.lastname, crmentity.modifiedtime from activity inner join seactivityrel on seactivityrel.activityid=activity.activityid inner join crmentity on crmentity.crmid=activity.activityid left join cntactivityrel on cntactivityrel.activityid= activity.activityid left join contactdetails on contactdetails.contactid= cntactivityrel.contactid where (activity.activitytype = 'Meeting' or activity.activitytype='Call' or activity.activitytype='Task') and (activity.status='Completed' or activity.eventstatus='Held') and seactivityrel.crmid=".$id;
		renderRelatedHistory($query,$id);
	}

  function get_attachments($id)
  {
		$query = "select notes.title,'Notes      ' ActivityType, notes.filename, attachments.type  FileType,crm2.modifiedtime lastmodified, seattachmentsrel.attachmentsid attachmentsid, notes.notesid crmid from notes inner join senotesrel on senotesrel.notesid= notes.notesid inner join crmentity on crmentity.crmid= senotesrel.crmid inner join crmentity crm2 on crm2.crmid=notes.notesid and crm2.deleted=0 left join seattachmentsrel  on seattachmentsrel.crmid =notes.notesid left join attachments on seattachmentsrel.attachmentsid = attachments.attachmentsid where crmentity.crmid=".$id;
                $query .= ' union all ';
                $query .= "select attachments.description title ,'Attachments' ActivityType, attachments.name filename, attachments.type FileType,crm2.modifiedtime lastmodified, attachments.attachmentsid attachmentsid, seattachmentsrel.attachmentsid crmid from attachments inner join seattachmentsrel on seattachmentsrel.attachmentsid= attachments.attachmentsid inner join crmentity on crmentity.crmid= seattachmentsrel.crmid inner join crmentity crm2 on crm2.crmid=attachments.attachmentsid where crmentity.crmid=".$id;

    renderRelatedAttachments($query,$id);
  }
  function get_tickets($id)
  {
	//	$query = 'select users.user_name, users.id, leaddetails.leadid,leaddetails.lastname, products.productid, products.productname, seticketsrel.*, troubletickets.ticketid, troubletickets.title, troubletickets.status, crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime from leaddetails inner join seproductsrel on seproductsrel.crmid = leaddetails.leadid inner join products on seproductsrel.productid = products.productid inner join users on users.id=crmentity.smownerid inner join seticketsrel on seticketsrel.crmid = leaddetails.leadid left join troubletickets on troubletickets.ticketid = seticketsrel.ticketid inner join crmentity on crmentity.crmid = leaddetails.leadid where leaddetails.leadid='.$id;
		$query = 'select users.user_name, users.id, seticketsrel.*, troubletickets.title, troubletickets.status, crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime  from troubletickets inner join seticketsrel on seticketsrel.ticketid = troubletickets.ticketid inner join crmentity on crmentity.crmid = seticketsrel.crmid inner join users on users.id=crmentity.smownerid where seticketsrel.crmid = '.$id; 
          renderRelatedTickets($query);
  }
	function get_products($id)
	{
		$query = 'select products.productid, products.productname, products.productcode, products.commissionrate, products.qty_per_unit, products.unit_price, crmentity.crmid, crmentity.smownerid from products inner join seproductsrel on products.productid = seproductsrel.productid inner join crmentity on crmentity.crmid = products.productid inner join leaddetails on leaddetails.leadid = seproductsrel.crmid  where leaddetails.leadid = '.$id.' and crmentity.deleted = 0';
	      	renderRelatedProducts($query,$id);
        }



  function save_relationship_changes($is_update)
  {
    if($this->task_id != "")
    	{
    		$this->set_lead_task_relationship($this->id, $this->task_id);
    	}
    	if($this->note_id != "")
    	{
    		$this->set_lead_note_relationship($this->id, $this->note_id);
    	}
    	if($this->meeting_id != "")
    	{
    		$this->set_lead_meeting_relationship($this->id, $this->meeting_id);
    	}
    	if($this->call_id != "")
    	{
    		$this->set_lead_call_relationship($this->id, $this->call_id);
    	}
    	if($this->email_id != "")
    	{
    		$this->set_lead_email_relationship($this->id, $this->email_id);
    	}
    }

  
	function set_lead_task_relationship($lead_id, $task_id)
	{
		$query = "UPDATE tasks set parent_id='$lead_id', parent_type='Lead' where id='$task_id'";
		$this->db->query($query) or die("Error setting lead to task relationship: ".mysql_error());
	}

	function clear_lead_task_relationship($lead_id)
	{
		$query = "update tasks set parent_id='', parent_type='' where parent_id='$lead_id' and deleted=0";
		$this->db->query($query) or die("Error clearing lead to task relationship: ".mysql_error());
	}

	function set_lead_note_relationship($lead_id, $note_id)
	{
		$query = "UPDATE notes set parent_id='$lead_id', parent_type='Lead' where id='$note_id'";
		$this->db->query($query) or die("Error setting lead to note relationship: ".mysql_error());
	}

	function clear_lead_note_relationship($lead_id)
	{
		$query = "update notes set parent_id='', parent_type='' where parent_id='$lead_id' and deleted=0";
		$this->db->query($query) or die("Error clearing lead to note relationship: ".mysql_error());
	}

	function set_lead_meeting_relationship($lead_id, $meeting_id)
	{
		$query = "UPDATE meetings set parent_id='$lead_id', parent_type='Lead' where id='$meeting_id'";
		$this->db->query($query) or die("Error setting lead to meeting relationship: ".mysql_error());
	}

	function clear_lead_meeting_relationship($lead_id)
	{
		$query = "update meetings set parent_id='', parent_type='' where parent_id='$lead_id' and deleted=0";
		$this->db->query($query) or die("Error clearing lead to meeting relationship: ".mysql_error());
	}

	function set_lead_call_relationship($lead_id, $call_id)
	{
		$query = "UPDATE calls set parent_id='$lead_id', parent_type='Lead' where id='$call_id'";
		$this->db->query($query) or die("Error setting lead to call relationship: ".mysql_error());
	}

	function clear_lead_call_relationship($lead_id)
	{
		$query = "update calls set parent_id='', parent_type='' where parent_id='$lead_id' and deleted=0";
		$this->db->query($query) or die("Error clearing lead to call relationship: ".mysql_error());
	}

	function set_lead_email_relationship($lead_id, $email_id)
	{
		$query = "UPDATE emails set parent_id='$lead_id', parent_type='Lead' where id='$email_id'";
		$this->db->query($query) or die("Error setting lead to email relationship: ".mysql_error());
	}

	function clear_lead_email_relationship($lead_id)
	{
		$query = "update emails set parent_id='', parent_type='' where parent_id='$lead_id' and deleted=0";
		$this->db->query($query) or die("Error clearing lead to email relationship: ".mysql_error());
	}

  function mark_relationships_deleted($id)
	{
          
		$this->clear_lead_task_relationship($id);
		$this->clear_lead_note_relationship($id);
		$this->clear_lead_meeting_relationship($id);
		$this->clear_lead_call_relationship($id);
		$this->clear_lead_email_relationship($id);
	}

	// This method is used to provide backward compatibility with old data that was prefixed with http://
	// We now automatically prefix http://
	function remove_redundant_http()
	{
		if(eregi("http://", $this->website))
		{
			$this->website = substr($this->website, 7);
		}
	}

	function fill_in_additional_list_fields()
	{
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);

		$this->remove_redundant_http();
	}

	function list_view_parse_additional_sections(&$list_form, $section){
		
		if($list_form->exists($section.".row.yahoo_id") && isset($this->yahoo_id) && $this->yahoo_id != '')
			$list_form->parse($section.".row.yahoo_id");
		elseif ($list_form->exists($section.".row.no_yahoo_id"))
				$list_form->parse($section.".row.no_yahoo_id");
		return $list_form;
		
		
	}
	function get_lead_field_options($list_option)
	{
		$comboFieldArray = getComboArray($this->combofieldNames);
		return $comboFieldArray[$list_option];
	}
	function get_list_view_data(){
                $temp_array = $this->get_list_view_array();
        $temp_array["ENCODED_NAME"]=htmlspecialchars($this->firstname.' '.$this->lastname, ENT_QUOTES);
        return $temp_array;

        }

function getColumnNames_Lead()
 {
 	$table1flds = $this->db->getColumnNames("leaddetails");
	$table2flds = $this->db->getColumnNames("leadsubdetails");
	$table3flds = $this->db->getColumnNames("leadaddress");
	$sql1 = "select fieldlabel from field where generatedtype=2 and tabid=7";
	$result = $this->db->query($sql1);
	$numRows = $this->db->num_rows($result);
	for($i=0; $i < $numRows;$i++)
	{
   	$custom_fields[$i] = $this->db->query_result($result,$i,"fieldlabel");
	}
	$mergeflds = array_merge($table1flds,$table2flds,$table3flds,$custom_fields);
	return $mergeflds;
 }
}

?>
