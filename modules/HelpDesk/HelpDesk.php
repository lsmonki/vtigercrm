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

	var $tab_name = Array('crmentity','troubletickets','seticketsrel','ticketcf','ticketcomments');
	var $tab_name_index = Array('crmentity'=>'crmid','troubletickets'=>'ticketid','seticketsrel'=>'ticketid','ticketcf'=>'ticketid','ticketcomments'=>'ticketid');
	var $column_fields = Array();

	var $sortby_fields = Array('title','status','priority','crmid','firstname');

	var $list_fields = Array(
	'Ticket ID'=>Array('crmentity'=>'crmid'),
	'Subject'=>Array('troubletickets'=>'title'),	  			
	'Related to'=>Array('troubletickets'=>'parent_id'),	  			
	'Status'=>Array('troubletickets'=>'status'),
	'Priority'=>Array('troubletickets'=>'priority'),
	'Assigned To'=>Array('crmentity','smownerid')
	);

	var $list_fields_name = Array(
	'Ticket ID'=>'',
	'Subject'=>'ticket_title',	  			
	'Related to'=>'parent_id',	  			
	'Status'=>'ticketstatus',
	'Priority'=>'ticketpriorities',
	'Assigned To'=>'assigned_user_id');

	var $list_link_field= 'ticket_title';
			
	var $range_fields = Array(
	        'ticketid',
		'title',
        	'firstname',
	        'lastname',
        	'parent_id',
        	'productid',
        	'productname',
        	'priority',
        	'severity',
	        'status',
        	'category',
		'description',
		'solution',
		'modifiedtime',
		'createdtime'
		);

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
		$query = "SELECT activity.*, crmentity.crmid, contactdetails.contactid, contactdetails.lastname, contactdetails.firstname, recurringevents.recurringtype, crmentity.smownerid, crmentity.modifiedtime, users.user_name from activity inner join seactivityrel on seactivityrel.activityid=activity.activityid inner join crmentity on crmentity.crmid=activity.activityid left outer join recurringevents on recurringevents.activityid=activity.activityid left join cntactivityrel on cntactivityrel.activityid= activity.activityid left join contactdetails on contactdetails.contactid= cntactivityrel.contactid left join users on users.id=crmentity.smownerid where seactivityrel.crmid=".$id." and (activitytype='Task' or activitytype='Call' or activitytype='Meeting')";
		renderRelaredActivities($query,$id);
	}
	function get_attachments($id)
	{
		//Done for Merge -- Don
		$query = "select notes.title,'Notes      '  ActivityType, notes.filename, attachments.type  FileType,crm2.modifiedtime  lastmodified, seattachmentsrel.attachmentsid attachmentsid, notes.notesid crmid from notes inner join senotesrel on senotesrel.notesid= notes.notesid inner join crmentity on crmentity.crmid= senotesrel.crmid inner join crmentity crm2 on crm2.crmid=notes.notesid and crm2.deleted=0 left join seattachmentsrel  on seattachmentsrel.crmid =notes.notesid left join attachments on seattachmentsrel.attachmentsid = attachments.attachmentsid where crmentity.crmid=".$id;
                $query .= ' union all ';
                $query .= "select attachments.description title ,'Attachments'  ActivityType, attachments.name  filename, attachments.type  FileType,crm2.modifiedtime  lastmodified, attachments.attachmentsid attachmentsid, seattachmentsrel.attachmentsid crmid from attachments inner join seattachmentsrel on seattachmentsrel.attachmentsid= attachments.attachmentsid inner join crmentity on crmentity.crmid= seattachmentsrel.crmid inner join crmentity crm2 on crm2.crmid=attachments.attachmentsid where crmentity.crmid=".$id;	
		renderRelatedAttachments($query,$id);	
	}

        function get_ticket_comments_list($ticketid)
        {
                $sql = "select * from ticketcomments where ticketid=".$ticketid;
                $result = $this->db->query($sql);
                $noofrows = $this->db->num_rows($result);
                for($i=0;$i<$noofrows;$i++)
                {
			$ownerid = $this->db->query_result($result,$i,"ownerid");
			$ownertype = $this->db->query_result($result,$i,"ownertype");
                        if($ownertype == 'user')
                                $name = getUserName($ownerid);
                        elseif($ownertype == 'customer')
                        {
                                $sql1 = 'select * from PortalInfo where id='.$ownerid;
                                $name = $this->db->query_result($this->db->query($sql1),0,'user_name');
                        }

                        $output['comments'][$i] = nl2br($this->db->query_result($result,$i,"comments"));
			$output['owner'][$i] = $name;
                        $output['createdtime'][$i] = $this->db->query_result($result,$i,"createdtime");
                }
                return $output;
        }	
	function get_user_tickets_list($user_name,$id)
	{
		$query = "select crmentity.crmid, troubletickets.*, crmentity.smownerid, crmentity.createdtime, crmentity.modifiedtime, contactdetails.firstname, contactdetails.lastname, products.productid, products.productname, ticketcf.* from troubletickets inner join ticketcf on ticketcf.ticketid = troubletickets.ticketid inner join crmentity on crmentity.crmid=troubletickets.ticketid left join contactdetails on troubletickets.parent_id=contactdetails.contactid left join products on products.productid = troubletickets.product_id left join users on crmentity.smownerid=users.id  where crmentity.deleted=0 and contactdetails.email='".$user_name."' and troubletickets.parent_id = '".$id."'";
		return $this->process_list_query($query);
	}

	function process_list_query($query)
	{
	  
   		$result =& $this->db->query($query,true,"Error retrieving $this->object_name list: ");
		$list = Array();
	        $rows_found =  $this->db->getRowCount($result);
        	if($rows_found != 0)
	        {
			$ticket = Array();
			for($index = 0 , $row = $this->db->fetchByAssoc($result, $index); $row && $index <$rows_found;$index++, $row = $this->db->fetchByAssoc($result, $index))
			{
		                foreach($this->range_fields as $columnName)
                		{
		                	if (isset($row[$columnName])) 
					{
			                	$ticket[$columnName] = $row[$columnName];
                    			}
		                       	else     
				        {   
		                        	$ticket[$columnName] = "";
			                }   
	     			}	
    		                $list[] = $ticket;
                	}
        	}   

		$response = Array();
	        $response['list'] = $list;
        	$response['row_count'] = $rows_found;
	        $response['next_offset'] = $next_offset;
        	$response['previous_offset'] = $previous_offset;

	        return $response;
	}

 function getColumnNames_Hd()
 {
       $table1flds = $this->db->getColumnNames("troubletickets");
       $sql1 = "select fieldlabel from field where generatedtype=2 and tabid=13";
                 $result = $this->db->query($sql1);
                 $numRows = $this->db->num_rows($result);
                 for($i=0; $i < $numRows;$i++)
                 {
                        $custom_fields[$i] = $this->db->query_result($result,$i,"fieldlabel");
                 }

                 $mergeflds = array_merge($table1flds,$custom_fields);
       return $mergeflds;
}

}
?>
