<?php
include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('include/utils.php');


class HelpDesk extends SugarBean {
	var $log;
	var $db;


	// Stored fields
	var $id;
	var $mode;

	var $tab_name = Array('crmentity','troubletickets','seticketsrel','ticketcf');
	var $tab_name_index = Array('crmentity'=>'crmid','troubletickets'=>'ticketid','seticketsrel'=>'ticketid','ticketcf'=>'ticketid');
	var $column_fields = Array();

	var $sortby_fields = Array('title','status','priority');

	var $list_fields = Array(
	'Ticket ID'=>Array('crmentity'=>'crmid'),
	'Subject'=>Array('troubletickets'=>'title'),	  			
	'Status'=>Array('troubletickets'=>'status'),
	'Priority'=>Array('troubletickets'=>'priority'),
	'Assigned To'=>Array('crmentity','smownerid')
	);

	var $list_fields_name = Array(
	'Ticket ID'=>'',
	'Subject'=>'title',	  			
	'Status'=>'troubleticketstatus',
	'Priority'=>'troubleticketpriorities',
	'Assigned To'=>'assigned_user_id');

	var $list_link_field= 'title';
			
	

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
		$query ='select * from troubletickets inner join crmentity on crmentity.crmid = troubletickets.ticketid where crmentity.crmid = '.$id;
		renderRelatedPotentials($query,$id);
	}
	function get_attachments($id)
	{
		$query = 'select notes.title,"Notes      " as ActivityType, notes.filename, attachments.type as "FileType",crm2.modifiedtime as "lastmodified", notes.notesid as noteattachmentid from notes inner join senotesrel on senotesrel.notesid= notes.notesid inner join crmentity on crmentity.crmid= senotesrel.crmid inner join crmentity crm2 on crm2.crmid=notes.notesid left join seattachmentsrel  on seattachmentsrel.crmid =notes.notesid left join attachments on seattachmentsrel.attachmentsid = attachments.attachmentsid where crmentity.crmid='.$id;
                $query .= ' union all ';
                $query .= 'select "          " as Title ,"Attachments" as ActivityType, attachments.name as "filename", attachments.type as "FileType",crm2.modifiedtime as "lastmodified", attachments.attachmentsid as noteattachmentid from attachments inner join seattachmentsrel on seattachmentsrel.attachmentsid= attachments.attachmentsid inner join crmentity on crmentity.crmid= seattachmentsrel.crmid inner join crmentity crm2 on crm2.crmid=attachments.attachmentsid where crmentity.crmid='.$id;
		renderRelatedAttachments($query);	
	}



}
?>
