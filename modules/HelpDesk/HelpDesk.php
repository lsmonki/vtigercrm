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
require_once('user_privileges/default_module_view.php');

class HelpDesk extends CRMEntity {
	var $log;
	var $db;
	var $table_name = "vtiger_troubletickets";
	var $tab_name = Array('vtiger_crmentity','vtiger_troubletickets','vtiger_ticketcf');
	var $tab_name_index = Array('vtiger_crmentity'=>'crmid','vtiger_troubletickets'=>'ticketid','vtiger_ticketcf'=>'ticketid','vtiger_ticketcomments'=>'ticketid');
	var $column_fields = Array();
	//Pavani: Assign value to entity_table
        var $entity_table = "vtiger_crmentity";

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
	var $search_fields = Array(
		'Ticket ID' => Array('vtiger_crmentity'=>'crmid'),
		'Title' => Array('vtiger_troubletickets'=>'title')
		);
	var $search_fields_name = Array(
		'Ticket ID' => '',
		'Title'=>'ticket_title',
		);
	
	//By Pavani...Specify Required fields
        var $required_fields =  array('ticket_title'=>1);
        //Added these variables which are used as default order by and sortorder in ListView
        var $default_order_by = 'title';
        var $default_sort_order = 'DESC';

	var $groupTable = Array('vtiger_ticketgrouprelation','ticketid');

	/**	Constructor which will set the column_fields in this object
	 */
	function HelpDesk() 
	{
		$this->log =LoggerManager::getLogger('helpdesk');
		$this->log->debug("Entering HelpDesk() method ...");
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('HelpDesk');
		$this->log->debug("Exiting HelpDesk method ...");
	}


	function save_module($module)
	{
		//Inserting into Ticket Comment Table
		$this->insertIntoTicketCommentTable("vtiger_ticketcomments",'HelpDesk');

		//Inserting into vtiger_attachments
		$this->insertIntoAttachment($this->id,'HelpDesk');
				
	}	

	/** Function to insert values in vtiger_ticketcomments  for the specified tablename and  module
  	  * @param $table_name -- table name:: Type varchar
  	  * @param $module -- module:: Type varchar
 	 */	
	function insertIntoTicketCommentTable($table_name, $module)
	{
		global $log;
		$log->info("in insertIntoTicketCommentTable  ".$table_name."    module is  ".$module);
        	global $adb;
		global $current_user;

        	$current_time = $adb->formatDate(date('YmdHis'), true);
		if($this->column_fields['assigned_user_id'] != '')
			$ownertype = 'user';
		else
			$ownertype = 'customer';

		if($this->column_fields['comments'] != '')
			$comment = $this->column_fields['comments'];
		else
			$comment = $_REQUEST['comments'];
		
		if($comment != '')
		{
			$sql = "insert into vtiger_ticketcomments values(?,?,?,?,?,?)";	
	        	$params = array('', $this->id, from_html($comment), $current_user->id, $ownertype, $current_time);
			$adb->pquery($sql, $params);
		}
	}


	/**
	 *      This function is used to add the vtiger_attachments. This will call the function uploadAndSaveFile which will upload the attachment into the server and save that attachment information in the database.
	 *      @param int $id  - entity id to which the vtiger_files to be uploaded
	 *      @param string $module  - the current module name
	*/
	function insertIntoAttachment($id,$module)
	{
		global $log, $adb;
		$log->debug("Entering into insertIntoAttachment($id,$module) method.");
		
		$file_saved = false;

		foreach($_FILES as $fileindex => $files)
		{
			if($files['name'] != '' && $files['size'] > 0)
			{
				$files['original_name'] = $_REQUEST[$fileindex.'_hidden'];
				$file_saved = $this->uploadAndSaveFile($id,$module,$files);
			}
		}

		$log->debug("Exiting from insertIntoAttachment($id,$module) method.");
	}
	
	
	/**	Function used to get the sort order for HelpDesk listview
	 *	@return string	$sorder	- first check the $_REQUEST['sorder'] if request value is empty then check in the $_SESSION['HELPDESK_SORT_ORDER'] if this session value is empty then default sort order will be returned. 
	 */
	function getSortOrder()
	{
		global $log;
                $log->debug("Entering getSortOrder() method ...");	
		if(isset($_REQUEST['sorder'])) 
			$sorder = $_REQUEST['sorder'];
		else
			$sorder = (($_SESSION['HELPDESK_SORT_ORDER'] != '')?($_SESSION['HELPDESK_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder() method ...");
		return $sorder;
	}

	/**	Function used to get the order by value for HelpDesk listview
	 *	@return string	$order_by  - first check the $_REQUEST['order_by'] if request value is empty then check in the $_SESSION['HELPDESK_ORDER_BY'] if this session value is empty then default order by will be returned. 
	 */
	function getOrderBy()
	{
		global $log;
                $log->debug("Entering getOrderBy() method ...");
		if (isset($_REQUEST['order_by'])) 
			$order_by = $_REQUEST['order_by'];
		else
			$order_by = (($_SESSION['HELPDESK_ORDER_BY'] != '')?($_SESSION['HELPDESK_ORDER_BY']):($this->default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}	

	/**     Function to form the query to get the list of activities
         *      @param  int $id - ticket id
	 *	@return array - return an array which will be returned from the function GetRelatedList
        **/
	function get_activities($id)
	{
		global $log, $singlepane_view;
		$log->debug("Entering get_activities(".$id.") method ...");
		global $mod_strings;
		global $app_strings;
		require_once('modules/Calendar/Activity.php');
		$focus = new Activity();

		$button = '';

		if($singlepane_view == 'true')
			$returnset = '&return_module=HelpDesk&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=HelpDesk&return_action=CallRelatedList&return_id='.$id;

		$query = "SELECT case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name,vtiger_activity.*, vtiger_cntactivityrel.*, vtiger_contactdetails.lastname, vtiger_contactdetails.firstname, vtiger_crmentity.crmid, vtiger_recurringevents.recurringtype, vtiger_crmentity.smownerid, vtiger_crmentity.modifiedtime from vtiger_activity inner join vtiger_seactivityrel on vtiger_seactivityrel.activityid=vtiger_activity.activityid inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_activity.activityid left join vtiger_cntactivityrel on vtiger_cntactivityrel.activityid = vtiger_activity.activityid left join vtiger_contactdetails on vtiger_contactdetails.contactid = vtiger_cntactivityrel.contactid left outer join vtiger_recurringevents on vtiger_recurringevents.activityid=vtiger_activity.activityid left join vtiger_users on vtiger_users.id=vtiger_crmentity.smownerid left join vtiger_activitygrouprelation on vtiger_activitygrouprelation.activityid=vtiger_crmentity.crmid left join vtiger_groups on vtiger_groups.groupname=vtiger_activitygrouprelation.groupname where vtiger_seactivityrel.crmid=".$id." and vtiger_crmentity.deleted=0 and (activitytype='Task' or activitytype='Call' or activitytype='Meeting') AND ( vtiger_activity.status is NULL OR vtiger_activity.status != 'Completed' ) and ( vtiger_activity.eventstatus is NULL OR vtiger_activity.eventstatus != 'Held') ";
		$log->debug("Exiting get_activities method ...");
		return GetRelatedList('HelpDesk','Calendar',$focus,$query,$button,$returnset);
	}

	/**     Function to get the Ticket History information as in array format
	 *	@param int $ticketid - ticket id
	 *	@return array - return an array with title and the ticket history informations in the following format
							array(	
								header=>array('0'=>'title'),
								entries=>array('0'=>'info1','1'=>'info2',etc.,)
							     )
	 */
	function get_ticket_history($ticketid)
	{
		global $log, $adb;
		$log->debug("Entering into get_ticket_history($ticketid) method ...");

		$query="select title,update_log from vtiger_troubletickets where ticketid=?";
		$result=$adb->pquery($query, array($ticketid));
		$update_log = $adb->query_result($result,0,"update_log");

		$splitval = split('--//--',trim($update_log,'--//--'));

		$header[] = $adb->query_result($result,0,"title");

		$return_value = Array('header'=>$header,'entries'=>$splitval);

		$log->debug("Exiting from get_ticket_history($ticketid) method ...");

		return $return_value;
	}

	/**	Function to form the query to get the list of attachments and notes
	 *	@param  int $id - ticket id
         *      @return array - return an array which will be returned from the function getAttachmentsAndNotes
	**/
	function get_attachments($id)
	{
		global $log;
		$log->debug("Entering get_attachments(".$id.") method ...");
		$query = "select vtiger_notes.title,'Notes      '  ActivityType, vtiger_notes.filename,
		vtiger_attachments.type  FileType,crm2.modifiedtime lastmodified,
		vtiger_seattachmentsrel.attachmentsid attachmentsid, vtiger_notes.notesid crmid,
		vtiger_notes.notecontent description, vtiger_users.user_name
		from vtiger_notes
			inner join vtiger_senotesrel on vtiger_senotesrel.notesid= vtiger_notes.notesid
			inner join vtiger_crmentity on vtiger_crmentity.crmid= vtiger_senotesrel.crmid
			inner join vtiger_crmentity crm2 on crm2.crmid=vtiger_notes.notesid and crm2.deleted=0
			left join vtiger_seattachmentsrel  on vtiger_seattachmentsrel.crmid =vtiger_notes.notesid
			left join vtiger_attachments on vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid
			inner join vtiger_users on crm2.smcreatorid= vtiger_users.id
		where vtiger_crmentity.crmid=".$id;

		$query .= ' union all ';

		$query .= "select vtiger_attachments.subject AS title ,'Attachments'  ActivityType,
		vtiger_attachments.name filename, vtiger_attachments.type FileType,crm2.modifiedtime lastmodified,
		vtiger_attachments.attachmentsid attachmentsid, vtiger_seattachmentsrel.attachmentsid crmid,
		vtiger_attachments.description, vtiger_users.user_name
		from vtiger_attachments
			inner join vtiger_seattachmentsrel on vtiger_seattachmentsrel.attachmentsid= vtiger_attachments.attachmentsid
			inner join vtiger_crmentity on vtiger_crmentity.crmid= vtiger_seattachmentsrel.crmid
			inner join vtiger_crmentity crm2 on crm2.crmid=vtiger_attachments.attachmentsid
			left join vtiger_users on crm2.smcreatorid= vtiger_users.id
		where vtiger_crmentity.crmid=".$id;	
		$log->debug("Exiting get_attachments method ...");
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
		global $log;
		$log->debug("Entering get_ticket_comments_list(".$ticketid.") method ...");
		 $sql = "select * from vtiger_ticketcomments where ticketid=? order by createdtime DESC";
		 $result = $this->db->pquery($sql, array($ticketid));
		 $noofrows = $this->db->num_rows($result);
		 for($i=0;$i<$noofrows;$i++)
		 {
			 $ownerid = $this->db->query_result($result,$i,"ownerid");
			 $ownertype = $this->db->query_result($result,$i,"ownertype");
			 if($ownertype == 'user')
				 $name = getUserName($ownerid);
			 elseif($ownertype == 'customer')
			 {
				 $sql1 = 'select * from vtiger_portalinfo where id=?';
				 $name = $this->db->query_result($this->db->pquery($sql1, array($ownerid)),0,'user_name');
			 }

			 $output[$i]['comments'] = nl2br($this->db->query_result($result,$i,"comments"));
			 $output[$i]['owner'] = $name;
			 $output[$i]['createdtime'] = $this->db->query_result($result,$i,"createdtime");
		 }
		$log->debug("Exiting get_ticket_comments_list method ...");
		 return $output;
	 }
		
	/**	Function to form the query which will give the list of tickets based on customername and id ie., contactname and contactid
	 *	@param  string $user_name - name of the customer ie., contact name
	 *	@param  int    $id	 - contact id 
	 * 	@return array  - return an array which will be returned from the function process_list_query
	**/
	function get_user_tickets_list($user_name,$id,$where='',$match='')
	{
		global $log;
		$log->debug("Entering get_user_tickets_list(".$user_name.",".$id.",".$where.",".$match.") method ...");

		$this->db->println("where ==> ".$where);

		$query = "select vtiger_crmentity.crmid, vtiger_troubletickets.*, vtiger_crmentity.description, vtiger_crmentity.smownerid, vtiger_crmentity.createdtime, vtiger_crmentity.modifiedtime, vtiger_contactdetails.firstname, vtiger_contactdetails.lastname, vtiger_products.productid, vtiger_products.productname, vtiger_ticketcf.* from vtiger_troubletickets inner join vtiger_ticketcf on vtiger_ticketcf.ticketid = vtiger_troubletickets.ticketid inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_troubletickets.ticketid left join vtiger_contactdetails on vtiger_troubletickets.parent_id=vtiger_contactdetails.contactid left join vtiger_products on vtiger_products.productid = vtiger_troubletickets.product_id left join vtiger_users on vtiger_crmentity.smownerid=vtiger_users.id  where vtiger_crmentity.deleted=0 and vtiger_contactdetails.email='".$user_name."' and vtiger_troubletickets.parent_id = '".$id."'";

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
				if($val[0] == 'vtiger_troubletickets.title')
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

		$query .= " order by vtiger_crmentity.crmid desc";
		$log->debug("Exiting get_user_tickets_list method ...");
		return $this->process_list_query($query);
	}

	/**	Function to process the list query and return the result with number of rows
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
		global $log;
		$log->debug("Entering process_list_query(".$query.") method ...");
	  
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

		$log->debug("Exiting process_list_query method ...");
	        return $response;
	}

	/**	Function to get the HelpDesk field labels in caps letters without space
	 *	@return array $mergeflds - array(	key => val	)    where   key=0,1,2..n & val = ASSIGNEDTO,RELATEDTO, .,etc
	**/
	function getColumnNames_Hd()
	{
		global $log,$current_user;
		$log->debug("Entering getColumnNames_Hd() method ...");
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
		{
			$sql1 = "select fieldlabel from vtiger_field where tabid=13 and block <> 30 and vtiger_field.uitype <> 61";
			$params1 = array();
		}else
		{
			$profileList = getCurrentUserProfileList();
			$sql1 = "select vtiger_field.fieldid,fieldlabel from vtiger_field inner join vtiger_profile2field on vtiger_profile2field.fieldid=vtiger_field.fieldid inner join vtiger_def_org_field on vtiger_def_org_field.fieldid=vtiger_field.fieldid where vtiger_field.tabid=13 and vtiger_field.block <> 30 and vtiger_field.uitype <> 61 and vtiger_field.displaytype in (1,2,3,4) and vtiger_profile2field.visible=0 and vtiger_def_org_field.visible=0";
			$params1 = array();
			if (count($profileList) > 0) {
				$sql1 .= " and vtiger_profile2field.profileid in (". generateQuestionMarks($profileList) .")  group by fieldid";
				array_push($params1, $profileList);
			}
		}
		$result = $this->db->pquery($sql1, $params1);
		$numRows = $this->db->num_rows($result);
		for($i=0; $i < $numRows;$i++)
		{
			$custom_fields[$i] = $this->db->query_result($result,$i,"fieldlabel");
			$custom_fields[$i] = ereg_replace(" ","",$custom_fields[$i]);
			$custom_fields[$i] = strtoupper($custom_fields[$i]);
		}
		$mergeflds = $custom_fields;
		$log->debug("Exiting getColumnNames_Hd method ...");
		return $mergeflds;
	}

	/**     Function to get the list of comments for the given ticket id
	 *      @param  int  $ticketid - Ticket id
	 *      @return list $list - return the list of comments and comment informations as a html output where as these comments and comments informations will be formed in div tag.
	**/
	function getCommentInformation($ticketid)
	{
		global $log;
		$log->debug("Entering getCommentInformation(".$ticketid.") method ...");
		global $adb;
		global $mod_strings, $default_charset;
		$sql = "select * from vtiger_ticketcomments where ticketid=?";
		$result = $adb->pquery($sql, array($ticketid));
		$noofrows = $adb->num_rows($result);

		//In ajax save we should not add this div
		if($_REQUEST['action'] != 'HelpDeskAjax')
		{
			$list .= '<div id="comments_div" style="overflow: auto;height:200px;width:100%;">';
			$enddiv = '</div>';
		}
		for($i=0;$i<$noofrows;$i++)
		{
			if($adb->query_result($result,$i,'comments') != '')
			{
				//this div is to display the comment
				$comment = $adb->query_result($result,$i,'comments');
				// Asha: Fix for ticket #4478 . Need to escape html tags during ajax save.
				if($_REQUEST['action'] == 'HelpDeskAjax') {
					$comment = htmlentities($comment, ENT_QUOTES, $default_charset);
				}
				$list .= '<div valign="top" style="width:99%;padding-top:10px;" class="dataField">';
				$list .= make_clickable(nl2br($comment));

				$list .= '</div>';

				//this div is to display the author and time
				$list .= '<div valign="top" style="width:99%;border-bottom:1px dotted #CCCCCC;padding-bottom:5px;" class="dataLabel"><font color=darkred>';
				$list .= $mod_strings['LBL_AUTHOR'].' : ';

				if($adb->query_result($result,$i,'ownertype') == 'user')
					$list .= getUserName($adb->query_result($result,$i,'ownerid'));
				else
					$list .= $this->getCustomerName($ticketid);

				$list .= ' on '.$adb->query_result($result,$i,'createdtime').' &nbsp;';

				$list .= '</font></div>';
			}
		}
		
		$list .= $enddiv;

		$log->debug("Exiting getCommentInformation method ...");
		return $list;
	}

	/**     Function to get the Customer Name who has made comment to the ticket from the customer portal
	 *      @param  int    $id   - Ticket id
	 *      @return string $customername - The contact name
	**/
	function getCustomerName($id)
	{
		global $log;
		$log->debug("Entering getCustomerName(".$id.") method ...");
        	global $adb;
	        $sql = "select * from vtiger_portalinfo inner join vtiger_troubletickets on vtiger_troubletickets.parent_id = vtiger_portalinfo.id where vtiger_troubletickets.ticketid=?";
        	$result = $adb->pquery($sql, array($id));
	        $customername = $adb->query_result($result,0,'user_name');
		$log->debug("Exiting getCustomerName method ...");
        	return $customername;
	}
	//Pavani: Function to create, export query for helpdesk module
        /** Function to export the ticket records in CSV Format
        * @param reference variable - where condition is passed when the query is executed
        * Returns Export Tickets Query.
        */
        function create_export_query($where)
        {
                global $log;
                global $current_user;
                $log->debug("Entering create_export_query(".$where.") method ...");

                include("include/utils/ExportUtils.php");

                //To get the Permitted fields query and the permitted fields list
                $sql = getPermittedFieldsQuery("HelpDesk", "detail_view");
                $fields_list = getFieldsListFromQuery($sql);

                $query = "SELECT $fields_list,vtiger_ticketgrouprelation.groupname as 'Assigned To Group',case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name
                       FROM ".$this->entity_table. "
				INNER JOIN vtiger_troubletickets 
					ON vtiger_troubletickets.ticketid =vtiger_crmentity.crmid 
				LEFT JOIN vtiger_crmentity vtiger_crmentityRelatedTo 
					ON vtiger_crmentityRelatedTo.crmid = vtiger_troubletickets.parent_id
				LEFT JOIN vtiger_account 
					ON vtiger_account.accountid = vtiger_troubletickets.parent_id 
				LEFT JOIN vtiger_contactdetails 
					ON vtiger_contactdetails.contactid = vtiger_troubletickets.parent_id
				LEFT JOIN vtiger_ticketcomments 
					ON vtiger_ticketcomments.ticketid = vtiger_troubletickets.ticketid 
				LEFT JOIN vtiger_ticketcf 
					ON vtiger_ticketcf.ticketid=vtiger_troubletickets.ticketid 
				LEFT JOIN vtiger_ticketgrouprelation 
					ON vtiger_ticketgrouprelation.ticketid=vtiger_ticketcf.ticketid
				LEFT JOIN vtiger_groups 
					ON vtiger_groups.groupname = vtiger_ticketgrouprelation.groupname 
				LEFT JOIN vtiger_users 
					ON vtiger_users.id=vtiger_crmentity.smownerid and vtiger_users.status='Active' 
				LEFT JOIN vtiger_seattachmentsrel 
					ON vtiger_seattachmentsrel.crmid =vtiger_troubletickets.ticketid
				LEFT JOIN vtiger_attachments 
					ON vtiger_attachments.attachmentsid=vtiger_seattachmentsrel.attachmentsid 
				LEFT JOIN vtiger_products 
					ON vtiger_products.productid=vtiger_troubletickets.product_id";

			$where_auto="   vtiger_crmentity.deleted = 0 ";
				
				if($where != "")
					$query .= "  WHERE ($where) AND ".$where_auto;
				else
					$query .= "  WHERE ".$where_auto;
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
                require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
                //we should add security check when the user has Private Access
                if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[13] == 3)
                {
                        //Added security check to get the permitted records only
                        $query = $query." ".getListViewSecurityParameter("HelpDesk");
                }


                $log->debug("Exiting create_export_query method ...");
                return $query;
        }

	
	/**	Function used to get the Activity History
	 *	@param	int	$id - ticket id to which we want to display the activity history
	 *	@return  array	- return an array which will be returned from the function getHistory
	 */
	function get_history($id)
	{
		global $log;
		$log->debug("Entering get_history(".$id.") method ...");
		$query = "SELECT vtiger_activity.activityid, vtiger_activity.subject, vtiger_activity.status, vtiger_activity.eventstatus, vtiger_activity.date_start, vtiger_activity.due_date,vtiger_activity.time_start,vtiger_activity.time_end,vtiger_activity.activitytype, vtiger_troubletickets.ticketid, vtiger_troubletickets.title, vtiger_crmentity.modifiedtime,vtiger_crmentity.createdtime, vtiger_crmentity.description,
case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name
				from vtiger_activity
				inner join vtiger_seactivityrel on vtiger_seactivityrel.activityid= vtiger_activity.activityid
				inner join vtiger_troubletickets on vtiger_troubletickets.ticketid = vtiger_seactivityrel.crmid
				inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_activity.activityid
				left join vtiger_activitygrouprelation on vtiger_activitygrouprelation.activityid=vtiger_activity.activityid
                                left join vtiger_groups on vtiger_groups.groupname=vtiger_activitygrouprelation.groupname
				left join vtiger_users on vtiger_users.id=vtiger_crmentity.smownerid
				where (vtiger_activity.activitytype = 'Meeting' or vtiger_activity.activitytype='Call' or vtiger_activity.activitytype='Task')
				and (vtiger_activity.status = 'Completed' or vtiger_activity.status = 'Deferred' or (vtiger_activity.eventstatus = 'Held' and vtiger_activity.eventstatus != ''))
				and vtiger_seactivityrel.crmid=".$id."
                                and vtiger_crmentity.deleted = 0";
		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php
		$log->debug("Entering get_history method ...");
		return getHistory('HelpDesk',$query,$id);
	}

	/** Function to get the update ticket history for the specified ticketid
	  * @param $id -- $ticketid:: Type Integer 
	 */	
	function constructUpdateLog($focus, $mode, $assigned_group_name, $assigntype)
	{
		global $adb;
		global $current_user;

		if($mode != 'edit')//this will be updated when we create new ticket
		{
			$updatelog = "Ticket created. Assigned to ";

			if($assigned_group_name != '' && $assigntype == 'T')
			{
				$updatelog .= " group ".$assigned_group_name;
			}
			elseif($focus->column_fields['assigned_user_id'] != '')
			{
				$updatelog .= " user ".getUserName($focus->column_fields['assigned_user_id']);
			}
			else
			{
				$updatelog .= " user ".getUserName($current_user->id);
			}

			$fldvalue = date("l dS F Y h:i:s A").' by '.$current_user->user_name;
			$updatelog .= " -- ".$fldvalue."--//--";
		}
		else
		{
			$ticketid = $focus->id;

			//First retrieve the existing information
			$tktresult = $adb->pquery("select * from vtiger_troubletickets where ticketid=?", array($ticketid));
			$crmresult = $adb->pquery("select * from vtiger_crmentity where crmid=?", array($ticketid));

			$updatelog = decode_html($adb->query_result($tktresult,0,"update_log"));

			$old_user_id = $adb->query_result($crmresult,0,"smownerid");
			$old_status = $adb->query_result($tktresult,0,"status");
			$old_priority = $adb->query_result($tktresult,0,"priority");
			$old_severity = $adb->query_result($tktresult,0,"severity");
			$old_category = $adb->query_result($tktresult,0,"category");

			//Assigned to change log
			if($assigned_group_name != '' && $assigntype == 'T')
			{
				$group_info = getGroupName($ticketid,'HelpDesk');
				$group_name = $group_info[0];
				if($group_name != $assigned_group_name)
					$updatelog .= ' Transferred to group '.$assigned_group_name.'\.';
			}
			elseif($focus->column_fields['assigned_user_id'] != $old_user_id)
			{
				$user_name = getUserName($focus->column_fields['assigned_user_id']);
				$updatelog .= ' Transferred to user '.decode_html($user_name).'\.'; // Need to decode UTF characters which are migrated from versions < 5.0.4.
			}
			//Status change log
			if($old_status != $focus->column_fields['ticketstatus'] && $focus->column_fields['ticketstatus'] != '')
			{
				$updatelog .= ' Status Changed to '.$focus->column_fields['ticketstatus'].'\.';
			}
			//Priority change log
			if($old_priority != $focus->column_fields['ticketpriorities'] && $focus->column_fields['ticketpriorities'] != '')
			{
				$updatelog .= ' Priority Changed to '.$focus->column_fields['ticketpriorities'].'\.';
			}
			//Severity change log
			if($old_severity != $focus->column_fields['ticketseverities'] && $focus->column_fields['ticketseverities'] != '')
			{
				$updatelog .= ' Severity Changed to '.$focus->column_fields['ticketseverities'].'\.';
			}
			//Category change log
			if($old_category != $focus->column_fields['ticketcategories'] && $focus->column_fields['ticketcategories'] != '')
			{
				$updatelog .= ' Category Changed to '.$focus->column_fields['ticketcategories'].'\.';
			}

			$updatelog .= ' -- '.date("l dS F Y h:i:s A").' by '.$current_user->user_name.'--//--';
		}
		return $updatelog;
	}



}
?>
