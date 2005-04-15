<?php
include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('data/CRMEntity.php');
require_once('include/utils.php');


class HelpDesk extends CRMEntity {
	var $log;
	var $db;


	// Stored fields
	var $id;
	var $mode;

	var $tab_name = Array('crmentity','troubletickets','seticketsrel','ticketcf');
	var $tab_name_index = Array('crmentity'=>'crmid','troubletickets'=>'ticketid','seticketsrel'=>'ticketid','ticketcf'=>'ticketid');
	var $column_fields = Array();

	var $sortby_fields = Array('title','status','priority','crmid');

	var $list_fields = Array(
	'Ticket ID'=>Array('crmentity'=>'crmid'),
	'Subject'=>Array('troubletickets'=>'title'),	  			
	'Status'=>Array('troubletickets'=>'status'),
	'Priority'=>Array('troubletickets'=>'priority'),
	'Assigned To'=>Array('crmentity','smownerid')
	);

	var $list_fields_name = Array(
	'Ticket ID'=>'',
	'Subject'=>'ticket_title',	  			
	'Status'=>'ticketstatus',
	'Priority'=>'ticketpriorities',
	'Assigned To'=>'assigned_user_id');

	var $list_link_field= 'ticket_title';
			
	

	function HelpDesk() {
		$this->log =LoggerManager::getLogger('helpdesk');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('HelpDesk');
	}

	function get_summary_text()
        {
                return $this->name;
        }

	function get_opportunities($id)
	{
		//include('modules/HelpDesk/RenderRelatedListUI.php');
		//$query ='select * from troubletickets inner join crmentity on crmentity.crmid = troubletickets.ticketid where crmentity.crmid = '.$id;
		$query = 'select potential.*, seticketsrel.* from troubletickets inner join crmentity on crmentity.crmid = troubletickets.ticketid inner join seticketsrel on seticketsrel.ticketid = troubletickets.ticketid inner join potential on potential.potentialid = seticketsrel.crmid where crmentity.crmid = '.$id;
		renderRelatedPotentials($query,$id);
	}
	function get_activities($id)
	{
		$query = "SELECT activity.*,seactivityrel.*,crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime, users.user_name from activity inner join seactivityrel on seactivityrel.activityid=activity.activityid inner join crmentity on crmentity.crmid=activity.activityid left join users on users.id=crmentity.smownerid where seactivityrel.crmid=".$id." and (activitytype='Task' or activitytype='Call' or activitytype='Meeting')";
		renderRelaredActivities($query,$id);
	}
	function get_attachments($id)
	{
		//Done for Merge -- Don
		$query = "select notes.title,'Notes      '  ActivityType, notes.filename, attachments.type  FileType,crm2.modifiedtime  lastmodified, seattachmentsrel.attachmentsid attachmentsid, notes.notesid crmid from notes inner join senotesrel on senotesrel.notesid= notes.notesid inner join crmentity on crmentity.crmid= senotesrel.crmid inner join crmentity crm2 on crm2.crmid=notes.notesid left join seattachmentsrel  on seattachmentsrel.crmid =notes.notesid left join attachments on seattachmentsrel.attachmentsid = attachments.attachmentsid where crmentity.crmid=".$id;
                $query .= ' union all ';
                $query .= "select '          '  title ,'Attachments'  ActivityType, attachments.name  filename, attachments.type  FileType,crm2.modifiedtime  lastmodified, attachments.attachmentsid attachmentsid, seattachmentsrel.attachmentsid crmid from attachments inner join seattachmentsrel on seattachmentsrel.attachmentsid= attachments.attachmentsid inner join crmentity on crmentity.crmid= seattachmentsrel.crmid inner join crmentity crm2 on crm2.crmid=attachments.attachmentsid where crmentity.crmid=".$id;	
		renderRelatedAttachments($query,$id);	
	}



}
?>
