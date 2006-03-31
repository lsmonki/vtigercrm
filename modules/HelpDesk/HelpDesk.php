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
require_once('include/utils/utils.php');


class HelpDesk extends CRMEntity {
	var $log;
	var $db;


	// Stored fields
	var $id;
	var $mode;

	var $tab_name = Array('crmentity','troubletickets','seticketsrel','ticketcf','ticketcomments');
	var $tab_name_index = Array('crmentity'=>'crmid','troubletickets'=>'ticketid','seticketsrel'=>'ticketid','ticketcf'=>'ticketid','ticketcomments'=>'ticketid','attachments'=>'attachmentsid');
	var $column_fields = Array();

	var $sortby_fields = Array('title','status','priority','crmid','firstname','smownerid');

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
					'Assigned To'=>'assigned_user_id'
				     );

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

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'DESC';

	function HelpDesk() 
	{
		$this->log =LoggerManager::getLogger('helpdesk');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('HelpDesk');
	}

	/**     Function to form the query to get the list of activities
         *      @param  int $id - ticket id
         *      @return void
         *       but this function will call the function renderRelatedActivities with parameters query and ticket id
        **/
	function get_activities($id)
	{
		global $mod_strings;
		global $app_strings;
		require_once('modules/Activities/Activity.php');
		$focus = new Activity();

		$button = '';

		$returnset = '&return_module=HelpDesk&return_action=DetailView&return_id='.$id;

		$query = "SELECT activity.*, crmentity.crmid, contactdetails.contactid, contactdetails.lastname, contactdetails.firstname, recurringevents.recurringtype, crmentity.smownerid, crmentity.modifiedtime, users.user_name from activity inner join seactivityrel on seactivityrel.activityid=activity.activityid inner join crmentity on crmentity.crmid=activity.activityid left outer join recurringevents on recurringevents.activityid=activity.activityid left join cntactivityrel on cntactivityrel.activityid= activity.activityid left join contactdetails on contactdetails.contactid= cntactivityrel.contactid left join users on users.id=crmentity.smownerid left join activitygrouprelation on activitygrouprelation.activityid=crmentity.crmid left join groups on groups.groupname=activitygrouprelation.groupname where seactivityrel.crmid=".$id." and (activitytype='Task' or activitytype='Call' or activitytype='Meeting')";
		return GetRelatedList('HelpDesk','Activities',$focus,$query,$button,$returnset);
	}

	/**     Function to display the History of the Ticket which just includes a file which contains the TicketHistory informations
	 */
	function Get_Ticket_History()
	{
	        global $mod_strings;
	        echo '<br><br>';
	        echo get_form_header($mod_strings['LBL_TICKET_HISTORY'],"", false);
        	include("modules/HelpDesk/TicketHistory.php");
	}

	/**	Function to form the query to get the list of attachments and notes
	 *	@param  int $id - ticket id
	 *	@return void
	 *	 but this function will call the function renderRelatedAttachments with parameters query and ticket id
	**/
	function get_attachments($id)
	{
		$query = "select notes.title,'Notes      '  ActivityType, notes.filename,
			attachments.type  FileType,crm2.modifiedtime lastmodified,
			seattachmentsrel.attachmentsid attachmentsid, notes.notesid crmid,
			crm2.createdtime, notes.notecontent description, users.user_name
		from notes
			inner join senotesrel on senotesrel.notesid= notes.notesid
			inner join crmentity on crmentity.crmid= senotesrel.crmid
			inner join crmentity crm2 on crm2.crmid=notes.notesid and crm2.deleted=0
			left join seattachmentsrel  on seattachmentsrel.crmid =notes.notesid
			left join attachments on seattachmentsrel.attachmentsid = attachments.attachmentsid
			inner join users on crm2.smcreatorid= users.id
		where crmentity.crmid=".$id;

		$query .= ' union all ';

		$query .= "select attachments.description title ,'Attachments'  ActivityType,
			attachments.name filename, attachments.type FileType,crm2.modifiedtime lastmodified,
			attachments.attachmentsid attachmentsid, seattachmentsrel.attachmentsid crmid,
			crm2.createdtime, attachments.description, users.user_name
		from attachments
			inner join seattachmentsrel on seattachmentsrel.attachmentsid= attachments.attachmentsid
			inner join crmentity on crmentity.crmid= seattachmentsrel.crmid
			inner join crmentity crm2 on crm2.crmid=attachments.attachmentsid
			inner join users on crm2.smcreatorid= users.id
		where crmentity.crmid=".$id;	
		return getAttachmentsAndNotes('HelpDesk',$query,$id);
	}

	/**	Function to get the ticket comments as a array
	 *	@param  int   $ticketid - ticketid
	 *	@return array $output - array(	
						[$i][comments]    => comments
						[$i][owner]       => name of the user or customer who made the comment
						[$i][createdtime] => the comment created time
					     ) 
				where $i = 0,1,..n which are all made for the ticket
	**/
         function get_ticket_comments_list($ticketid)
	 {
		 $sql = "select * from ticketcomments where ticketid=".$ticketid." order by createdtime DESC";
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
				 $sql1 = 'select * from portalinfo where id='.$ownerid;
				 $name = $this->db->query_result($this->db->query($sql1),0,'user_name');
			 }

			 $output[$i]['comments'] = nl2br($this->db->query_result($result,$i,"comments"));
			 $output[$i]['owner'] = $name;
			 $output[$i]['createdtime'] = $this->db->query_result($result,$i,"createdtime");
		 }
		 return $output;
	 }
		
	/**	Function to form the query which will give the list of tickets based on customername and id ie., contactname and contactid
	 *	@param  string $user_name - name of the customer ie., contact name
	 *	@param  int    $id	 - contact id 
	 * 	@return array  which is return from the function process_list_query
	**/
	function get_user_tickets_list($user_name,$id,$where='',$match='')
	{

		$this->db->println("where ==> ".$where);

		$query = "select crmentity.crmid, troubletickets.*, crmentity.smownerid, crmentity.createdtime, crmentity.modifiedtime, contactdetails.firstname, contactdetails.lastname, products.productid, products.productname, ticketcf.* from troubletickets inner join ticketcf on ticketcf.ticketid = troubletickets.ticketid inner join crmentity on crmentity.crmid=troubletickets.ticketid left join contactdetails on troubletickets.parent_id=contactdetails.contactid left join products on products.productid = troubletickets.product_id left join users on crmentity.smownerid=users.id  where crmentity.deleted=0 and contactdetails.email='".$user_name."' and troubletickets.parent_id = '".$id."'";

		if(trim($where) != '')
		{
			if($match == 'all' || $match == '')
			{
				$join = " and ";
			}
			elseif($match == 'any')
			{
				$join = " or ";
			}
			$where = explode("&&&",$where);
			$count = count($where);
			$count --;
			$where_conditions = "";
			foreach($where as $key => $value)
			{
				$this->db->println('key : '.$key.'...........value : '.$value);
				$val = explode(" = ",$value);
				$this->db->println('val0 : '.$val[0].'...........val1 : '.$val[1]);
				if($val[0] == 'troubletickets.title')
				{
					$where_conditions .= $val[0]."  ".$val[1];
					if($count != $key) 	$where_conditions .= $join;
				}
				elseif($val[1] != '' && $val[1] != 'Any')
				{
					$where_conditions .= $val[0]." = ".$val[1];
					if($count != $key)	$where_conditions .= $join;
				}
			}
			if($where_conditions != '')
				$where_conditions = " and ( ".$where_conditions." ) ";

			$query .= $where_conditions;
			$this->db->println("where condition for customer portal tickets search : ".$where_conditions);
		}

		$query .= " order by crmentity.crmid desc";
		return $this->process_list_query($query);
	}

	/**	Function to process the query and return the result with number of rows
	 *	@param  string $query - query 
	 *	@return array  $response - array(	list           => array(   
											$i => array(key => val)   
									       ),
							row_count      => '',
							next_offset    => '',
							previous_offset	=>''		 
						)
		where $i=0,1,..n & key = ticketid, title, firstname, ..etc(range_fields) & val = value of the key from db retrieved row 
	**/
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

	/**	Function to get the HelpDesk field labels in caps letters without space
	 *	return array $mergeflds - array(	key => val	)    where   key=0,1,2..n & val = ASSIGNEDTO,RELATEDTO, .,etc
	**/
	function getColumnNames_Hd()
	{
		$sql1 = "select fieldlabel from field where tabid=13 and block <> 6 ";
		$result = $this->db->query($sql1);
		$numRows = $this->db->num_rows($result);
		for($i=0; $i < $numRows;$i++)
		{
			$custom_fields[$i] = $this->db->query_result($result,$i,"fieldlabel");
			$custom_fields[$i] = ereg_replace(" ","",$custom_fields[$i]);
			$custom_fields[$i] = strtoupper($custom_fields[$i]);
		}
		$mergeflds = $custom_fields;
		return $mergeflds;
	}

	/**     Function to get the list of comments for the given ticket id
	 *      @param  int  $ticketid - Ticket id
	 *      @return list $list - the list of comments which are formed as boxed info with div tags.
	**/
	function getCommentInformation($ticketid)
	{
		global $adb;
		global $mod_strings;
		$sql = "select * from ticketcomments where ticketid=".$ticketid;
		$result = $adb->query($sql);
		$noofrows = $adb->num_rows($result);

		if($noofrows == 0)
			return '';

		$list .= '<div style="overflow: scroll;height:200;width:100%;">';
		for($i=0;$i<$noofrows;$i++)
		{
			if($adb->query_result($result,$i,'comments') != '')
			{
				$list .= '<div valign="top" width="70%"class="dataField">';
				$list .= make_clickable(nl2br($adb->query_result($result,$i,'comments')));

				$list .= '</div><div valign="top" width="20%" class="dataLabel"><font color=darkred>';
				$list .= $mod_strings['LBL_AUTHOR'].' : ';
				if($adb->query_result($result,$i,'ownertype') == 'user')
					$list .= getUserName($adb->query_result($result,$i,'ownerid'));
				else
					$list .= $this->getCustomerName($ticketid);

				$list .= ' on '.$adb->query_result($result,$i,'createdtime').' &nbsp;';

				$list .= '</font></div>';
			}
		}
		$list .= '</div>';

		return $list;
	}

	/**     Function to get the Customer Name who has made comment to the ticket from the portal
	 *      @param  int    $id   - Ticket id
	 *      @return string $customername - The contact name
	**/
	function getCustomerName($id)
	{
        	global $adb;
	        $sql = "select * from portalinfo inner join troubletickets on troubletickets.parent_id = portalinfo.id where troubletickets.ticketid=".$id;
        	$result = $adb->query($sql);
	        $customername = $adb->query_result($result,0,'user_name');
        	return $customername;
	}


}
?>
