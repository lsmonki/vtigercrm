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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/data/SugarBean.php,v 1.64 2005/03/04 19:13:35 jack Exp $
 * Description:  Defines the base class for all data entities used throughout the 
 * application.  The base class including its methods and variables is designed to 
 * be overloaded with module-specific methods and variables particular to the 
 * module's base entity class. 
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('data/Tracker.php');
require_once('include/utils.php');
require_once('modules/Users/UserInfoUtil.php');

class SugarBean
{
    /**
     * This method implements a generic insert and update logic for any SugarBean
     * This method only works for subclasses that implement the same variable names.
     * This method uses the presence of an id field that is not null to signify and update.
     * The id field should not be set otherwise.
     * todo - Add support for field type validation and encoding of parameters.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
     */
	
	var $new_schema = false;
	var $new_with_id = false;

	function saveentity($module)
	{
		global $current_user;
		foreach($this->tab_name as $table_name)
		{
			if($table_name == "crmentity")
			{
				$this->insertIntoCrmEntity($module);
			}
			elseif($table_name == "salesmanactivityrel")
                        {
                                $this->insertIntoSmActivityRel($module);
                        }
			elseif($table_name == "seticketsrel" || $table_name == "seactivityrel" || $table_name ==  "seproductsrel" || $table_name ==  "senotesrel" || $table_name == "sefaqrel")
                        {
                                if(isset($this->column_fields['parent_id']) && $this->column_fields['parent_id'] != '')
                                {
                                        $this->insertIntoEntityTable($table_name, $module);
                                }
                        }
			elseif($table_name ==  "cntactivityrel")
                        {
                               if(isset($this->column_fields['contact_id']) && $this->column_fields['contact_id'] != '')
                                 {
                                         $this->insertIntoEntityTable($table_name, $module);
                                 }
                        }
			else
			{
                          $this->insertIntoEntityTable($table_name, $module);			
			}
		}
		if($module == 'Emails' || $module == 'Notes')
			if(isset($_FILES['filename']['name']) && $_FILES['filename']['name']!='')
	                        $this->insertIntoAttachment($this->id,$module);
	}


	 function insertIntoAttachment1($id,$module,$filedata,$filename,$filesize,$filetype,$user_id)
        {
		$date_var = date('YmdHis');
               // global $current_user;
                global $adb;
                //global $root_directory;

                $ownerid = $user_id;
		

		if($filesize != 0)
                    {
                          $data = base64_encode(fread(fopen($filedata, "r"), $filesize));
                    }
		
                $current_id = $adb->getUniqueID("crmentity");

                if($module=='Emails') 
		{ 
			 $idname='emailid';      $tablename='emails';    $descname='description';}
                else     
    		{ 
		   $idname='notesid';      $tablename='notes';     $descname='notecontent';}
	           $sql='update '.$tablename.' set filename="'.$filename.'" where '.$idname.'='.$id;
                $adb->query($sql);

                $sql1 = "insert into crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime) values('".$current_id."','".$current_user->id."','".$ownerid."','".$module." Attachment','"."','".$date_var."')";
                $adb->query($sql1);

                //$this->id = $current_id;

                $sql2="insert into attachments(attachmentsid, name, description, type, attachmentsize, attachmentcontents) values('".$current_id."','".$filename."','"."','".$filetype."','".$filesize."','".$adb->getEmptyBlob()."')";
                $result=$adb->query($sql2);

                if($result!=false)
                        $result = $adb->updateBlob('attachments','attachmentcontents',"attachmentsid='".$current_id."' and name='".$filename."'",$data);

                $sql3='insert into seattachmentsrel values('.$id.','.$current_id.')';
                $adb->query($sql3);
	}
        



        function insertIntoAttachment($id,$module)
        {
		$date_var = date('YmdHis');
                global $current_user;
                global $adb;
                global $root_directory;

                $ownerid = $this->column_fields['assigned_user_id'];

                $uploaddir = $root_directory ."/test/upload/" ;// set this to wherever
                $binFile = $_FILES['filename']['name'];
                $filename = basename($binFile);
                $filetype= $_FILES['filename']['type'];
                $filesize = $_FILES['filename']['size'];

		if($binFile != '')
		{
                if(move_uploaded_file($_FILES["filename"]["tmp_name"],$uploaddir.$_FILES["filename"]["name"]))
                {
//                      $binFile = $_FILES['filename']['name'];
//                      $filename = basename($binFile);
//                      $filetype= $_FILES['filename']['type'];
//                      $filesize = $_FILES['filename']['size'];
                        if($filesize != 0)
                        {
                                $data = base64_encode(fread(fopen($uploaddir.$binFile, "r"), $filesize));
                        }
                }
                $current_id = $adb->getUniqueID("crmentity");

                if($module=='Emails') { $idname='emailid';      $tablename='emails';    $descname='description';}
                else                  { $idname='notesid';      $tablename='notes';     $descname='notecontent';}

                $sql='update '.$tablename.' set filename="'.$filename.'" where '.$idname.'='.$id;
                $adb->query($sql);

                $sql1 = "insert into crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime) values('".$current_id."','".$current_user->id."','".$ownerid."','".$module." Attachment','".$this->column_fields['description']."','".$date_var."')";
                $adb->query($sql1);

                //$this->id = $current_id;

                $sql2="insert into attachments(attachmentsid, name, description, type, attachmentsize, attachmentcontents) values('".$current_id."','".$filename."','".$this->column_fields[$descname]."','".$filetype."','".$filesize."','".$adb->getEmptyBlob()."')";
                $result=$adb->query($sql2);

                if($result!=false)
                        $result = $adb->updateBlob('attachments','attachmentcontents',"attachmentsid='".$current_id."' and name='".$filename."'",$data);

                $sql3='insert into seattachmentsrel values('.$id.','.$current_id.')';
                $adb->query($sql3);
		}
        }

	function insertIntoCrmEntity($module)
	{
		global $adb;
		global $current_user;
                
		$date_var = date('YmdHis');
                if($_REQUEST['assigntype'] == 'T')
                {
                  $ownerid= 0;
                }
                else
                {
                  $ownerid = $this->column_fields['assigned_user_id'];
                }
                
                //This check is done for products.
		if($module == 'Products' || $module == 'Notes' || $module =='Faq')
                {
			$ownerid = $current_user->id;
		}
		if($module == 'Events')
		{
			$module = 'Activities';
		}		
		if($this->mode == 'edit')
		{
			$sql = "update crmentity set smownerid=".$ownerid.",modifiedby=".$current_user->id.",description='".$this->column_fields['description']."', modifiedtime='".$date_var."' where crmid=".$this->id;
			
			$adb->query($sql);
                }
		else
		{
                  //if this is the create mode and the group allocation is chosen, then do the following
                                 $current_id = $adb->getUniqueID("crmentity");
                                               
			
			$sql = "insert into crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime) values('".$current_id."','".$current_user->id."','".$ownerid."','".$module."','".$this->column_fields['description']."','".$date_var."')";
			$adb->query($sql);
			//echo $sql;
			$this->id = $current_id;
                       
                        

		}
		
	}

	function insertIntoSmActivityRel($module)
        {
                global $adb;
                global $current_user;
                if($this->mode == 'edit')
                {

                        $sql = "delete from salesmanactivityrel where activityid=".$this->id." and smid = ".$this->column_fields['assigned_user_id']."";
                        $adb->query($sql);

                }
                        $sql_qry = "insert into salesmanactivityrel (smid,activityid) values('".$this->column_fields['assigned_user_id']."','".$this->id."')";
                        $adb->query($sql_qry);

        }
 //code added by shankar starts
	function constructUpdateLog($id)
	{
		global $adb;
		global $current_user;
		$ticketid = $id;
		//Updating History
		$tktresult = $adb->query("select * from troubletickets where ticketid='".$ticketid."'");
		$crmresult = $adb->query("select * from crmentity where crmid='".$ticketid."'");
		$updatelog = $adb->query_result($tktresult,0,"update_log");
		$old_user_id = $adb->query_result($crmresult,0,"smownerid");
		$old_status = $adb->query_result($tktresult,0,"status");
		$old_priority = $adb->query_result($tktresult,0,"priority");
		if($old_user_id != $this->column_fields['assigned_user_id'] || $old_status != $this->column_fields['troubleticketstatus'] || $old_priority != $this->column_fields['troubleticketpriorities'])
		{
			$updatelog .= date("l dS F Y h:i:s A").' by '.$current_user->user_name.'--//--';
		}	
		if($old_user_id != $this->column_fields['assigned_user_id'])
		{
			$user_name = getUserName($this->column_fields['assigned_user_id']);
			$updatelog .= ' Transferred to '.$assigned_user_name.'\.';
		}
		if($old_status != $this->column_fields['troubleticketstatus'])
		{
			$updatelog .= ' Status Changed to '.$this->column_fields['troubleticketstatus'].'\.';
		}
		if($old_priority != $this->column_fields['troubleticketpriorities'])
		{
			$updatelog .= ' Priority Changed to '.$this->column_fields['troubleticketpriorities'].'\.';
		}

		if($old_user_id != $this->column_fields['assigned_user_id'] || $old_status != $this->column_fields['troubleticketstatus'] || $old_priority != $this->column_fields['troubleticketpriorities'])
		{
			$updatelog .= '--//--';
		}
		return $updatelog;
	}
  //code added by shankar ends
	function insertIntoEntityTable($table_name, $module)
	{
		
		global $adb;
		$insertion_mode = $this->mode;
		
		//Checkin whether an entry is already is present in the table to update
		if($insertion_mode == 'edit')
		{
			$check_query = "select * from ".$table_name." where ".$this->tab_name_index[$table_name]."=".$this->id;
			$check_result=$adb->query($check_query);

			$num_rows = $adb->num_rows($check_result);

			if($num_rows <= 0)
			{
				$insertion_mode = '';
			}	 
		}

		if($insertion_mode == 'edit')
		{
			$update = '';
		}
		else
		{
			$column = $this->tab_name_index[$table_name];
			$value = $this->id;
		}

		$tabid= getTabid($module);	
		$sql = "select * from field where tabid=".$tabid." and tablename='".$table_name."' and displaytype in (1,3)"; 
		$result = $adb->query($sql);
		$noofrows = $adb->num_rows($result);
		for($i=0; $i<$noofrows; $i++)
		{
			$fieldname=$adb->query_result($result,$i,"fieldname");
			$columname=$adb->query_result($result,$i,"columnname");
			$uitype=$adb->query_result($result,$i,"uitype");
			if(isset($this->column_fields[$fieldname]))
			{
				if($uitype == 56)
				{
					if($this->column_fields[$fieldname] == 'on')
					{
						$fldvalue = 1;
					}
					else
					{
						$fldvalue = 0;
					}

				}
				else
				{
					$fldvalue = $this->column_fields[$fieldname]; 
					$fldvalue = stripslashes($fldvalue);
				}
				$fldvalue = from_html($adb->formatString($table_name,$columname,$fldvalue),($insertion_mode == 'edit')?true:false);
				


			}
			else
			{
				$fldvalue = '';
			}
			if($fldvalue=='') $fldvalue ="''";
			if($insertion_mode == 'edit')
			{
				//code by shankar starts
				if(($table_name == "troubletickets") && ($columname == "update_log"))
				{
					$fldvalue = $this->constructUpdateLog($this->id);
					$fldvalue = from_html($adb->formatString($table_name,$columname,$fldvalue),($insertion_mode == 'edit')?true:false);
				}
				//code by shankar ends
				if($i == 0)
				{
					$update = $columname."=".$fldvalue."";
				}
				else
				{
					$update .= ', '.$columname."=".$fldvalue."";
				}

			}
			else
			{
				//code by shankar starts
				if(($table_name == "troubletickets") && ($columname == "update_log"))
				{
					global $current_user;
					$fldvalue = date("l dS F Y h:i:s A").' by '.$current_user->user_name;
					if($this->column_fields['assigned_user_id'] != '')
					{
						$tkt_ownerid = $this->column_fields['assigned_user_id'];
					}
					else
					{
						$tkt_ownerid = $current_user->id;
					}
					$tkt_ownername = getUserName($tkt_ownerid);	
					$fldvalue .= "--//--Ticket created. Assigned to ".$tkt_ownername."--//--";
					$fldvalue = from_html($adb->formatString($table_name,$columname,$fldvalue),($insertion_mode == 'edit')?true:false);
						//echo ' updatevalue is ............. ' .$fldvalue;
				}
				//code by shankar ends
				$column .= ", ".$columname;
				$value .= ", ".$fldvalue."";
			}

		}





		if($insertion_mode == 'edit')
		{

			$sql1 = "update ".$table_name." set ".$update." where ".$this->tab_name_index[$table_name]."=".$this->id;
                        
                        $adb->query($sql1); 
                        
                        if($_REQUEST['assigntype'] == 'T')
                        {
                          $groupname = $_REQUEST['assigned_group_name'];
                          //echo 'about to update lead group relation';
	if($module == 'Leads')
				{
	                          updateLeadGroupRelation($this->id,$groupname);
				}
				else
				{
				  
	                          updateActivityGroupRelation($this->id,$groupname);
				}
        
                        }
                        else
                        {
                          //echo 'about to update lead group relation again!';
	if($module == 'Leads')
				{
	                          updateLeadGroupRelation($this->id,'');
				}
				else
				{
	                          updateActivityGroupRelation($this->id,'');
				}

                        }

		}
		else
		{	
			$sql1 = "insert into ".$table_name." (".$column.") values(".$value.")";
                        $adb->query($sql1); 
                        if($_REQUEST['assigntype'] == 'T' && $table_name == 'leaddetails')
                        {
$groupname = $_REQUEST['assigned_group_name'];
				if($table_name == 'leaddetails')
				{
                          insert2LeadGroupRelation($this->id,$groupname);
				}
				elseif($table_name == 'activity') 
				{
                          insert2ActivityGroupRelation($this->id,$groupname);
				}
                         
                        }
		}

		/*		
				echo '<BR>';
				echo $sql1;
				echo '<BR>';
		 */





		
	}


	
	function retrieve_entity_info($record, $module)
	{
		global $adb;
		$result = Array();
		foreach($this->tab_name_index as $table_name=>$index)
		{
			$result[$table_name] = $adb->query("select * from ".$table_name." where ".$index."=".$record);
		}

		$tabid = getTabid($module);
		$sql1 =  "select * from field where tabid=".$tabid;
		$result1 = $adb->query($sql1);
		$noofrows = $adb->num_rows($result1);
		for($i=0; $i<$noofrows; $i++)
		{
			$fieldcolname = $adb->query_result($result1,$i,"columnname");
			$tablename = $adb->query_result($result1,$i,"tablename");
			$fieldname = $adb->query_result($result1,$i,"fieldname");

			$fld_value = $adb->query_result($result[$tablename],0,$fieldcolname);
			$this->column_fields[$fieldname] = $fld_value;
				
		}
		$this->column_fields["record_id"] = $record;
                $this->column_fields["record_module"] = $module;
		
	//	print_r($this->column_fields);
		
	}

	function save() 
	{
		global $current_user;
		$isUpdate = true;

		if(!isset($this->id) || $this->id == "")
		{
			$isUpdate = false;
		}

		if ( $this->new_with_id == true )
		{
			$isUpdate = false;
		}

		//$this->date_modified = $this->db->formatDate(date('YmdHis'));
		$this->date_modified = date('YmdHis');
		if (isset($current_user)) $this->modified_user_id = $current_user->id;
		
		if($isUpdate)
		{
    			$query = "Update ".$this->table_name." set ";
		}
		else
		{
    			//$this->date_entered = $this->db->formatDate(date('YmdHis'));
			$this->date_entered = date('YmdHis');

			if($this->new_schema && 
				$this->new_with_id == false)
			{
				//$this->id = create_guid();
			}

			$query = "INSERT into ".$this->table_name;
		}
		// todo - add date modified to the list.

		// write out the SQL statement.
		//$query .= $this->table_name." set ";

		$firstPass = 0;
		$insKeys = '(';
		$insValues = '(';
		$updKeyValues='';
		foreach($this->column_fields as $field)
		{
			// Do not write out the id field on the update statement.
			// We are not allowed to change ids.
			if($isUpdate && ('id' == $field))
				continue;

			// Only assign variables that have been set.
			if(isset($this->$field))
			{				
				// Try comparing this element with the head element.
				if(0 == $firstPass)
				{
					$firstPass = 1;
				}
				else
				{
					if($isUpdate)
					{
						$updKeyValues = $updKeyValues.", ";
					}
					else
					{
						$insKeys = $insKeys.", ";
						$insValues = $insValues.", ";
					}
				}
				/*else
					$query = $query.", ";
	
				$query = $query.$field."='".PearDatabase::quote(from_html($this->$field,$isUpdate))."'";
				*/
				if($isUpdate)
				{
					$updKeyValues = $updKeyValues.$field."=".$this->db->formatString($this->table_name,$field,from_html($this->$field,$isUpdate));
				}
				else
				{
					$insKeys = $insKeys.$field;
					$insValues = $insValues.$this->db->formatString($this->table_name,$field,from_html($this->$field,$isUpdate));
				}
			}
		}

		if($isUpdate)
		{
			$query = $query.$updKeyValues." WHERE ID = '$this->id'";
			$this->log->info("Update $this->object_name: ".$query);
		}
		else
		{
			$query = $query.$insKeys.") VALUES ".$insValues.")";
	        	$this->log->info("Insert: ".$query);
		}

		$this->db->query($query, true);

		// If this is not an update then store the id for later.
		if(!$isUpdate)
		{
			$this->db->println("Illegal Access - SugarBean");
			//this is mysql specific
	        	$this->id = $this->db->getOne("SELECT LAST_INSERT_ID()" );
		}
	        
		// let subclasses save related field changes
		$this->save_relationship_changes($isUpdate);
		return $this->id;
	}

    /** 
     * This function is a good location to save changes that have been made to a relationship.
     * This should be overriden in subclasses that have something to save.
     * param $is_update true if this save is an update.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
     */
    function save_relationship_changes($is_update)
    {
    	
    }
    
    /**
     * This function retrieves a record of the appropriate type from the DB.
     * It fills in all of the fields from the DB into the object it was called on.
     * param $id - If ID is specified, it overrides the current value of $this->id.  If not specified the current value of $this->id will be used.
     * returns this - The object that it was called apon or null if exactly 1 record was not found.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
     */
	 function retrieve($id = -1, $encodeThis=true) {
		if ($id == -1) {
			$id = $this->id;
		}

		$query = "SELECT * FROM $this->table_name WHERE $this->module_id = '$id'";
		$this->log->debug("Retrieve $this->object_name: ".$query);

		$result =& $this->db->requireSingleResult($query, true, "Retrieving record by id $this->table_name:$id found ");

		if(empty($result))
		{
			return null;
		}
				
		$row = $this->db->fetchByAssoc($result, -1, $encodeThis);

		foreach($this->column_fields as $field)
		{
			if(isset($row[$field]))
			{
				$this->$field = $row[$field];
			}
		}

		$this->fill_in_additional_detail_fields();

		return $this;
	}

	/**
	 * This function returns a paged list of the current object type.  It is intended to allow for
	 * hopping back and forth through pages of data.  It only retrieves what is on the current page.
	 * This method must be called on a new instance.  It trashes the values of all the fields in the current one.
	 */
	function get_lead_list($order_by = "", $where = "", $row_offset = 0) {
		$query = $this->create_lead_list_query($order_by, $where);
		return $this->process_list_query($query, $row_offset);
	}
		
	function get_list($order_by = "", $where = "", $row_offset = 0, $limit=-1, $max=-1) {
		$this->log->debug("get_list:  order_by = '$order_by' and where = '$where' and limit = '$limit'");
		
		$query = $this->create_list_query($order_by, $where);
		
		return $this->process_list_query($query, $row_offset, $limit, $max);
	}

	/**
	 * This function returns a full (ie non-paged) list of the current object type.  
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function get_full_list($order_by = "", $where = "") {
		$this->log->debug("get_full_list:  order_by = '$order_by' and where = '$where'");
		$query = $this->create_list_query($order_by, $where);
		return $this->process_full_list_query($query);
	}

	function create_list_query($order_by, $where)
	{
		$adr_table = "";
		$adr_where = "";
		
		$query = "SELECT * FROM users ";
		$query .= "where users.deleted=0";
		return $query;
	}
	
	function create_lead_list_query($order_by, $where)
	{
                $query = "select * from $this->table_name left join leadcf on leads.id=leadcf.leadid ";
		//$query = "SELECT * FROM $this->table_name ";

		if($where != "")
			$query .= "where ($where) AND deleted=0 AND converted=0";
		else
			$query .= "where deleted=0 AND converted=0";

		if($order_by != "")
			$query .= " ORDER BY $order_by";

		return $query;
	}


	function process_list_query($query, $row_offset, $limit= -1, $max_per_page = -1)
	{
		global $list_max_entries_per_page;
		$this->log->debug("process_list_query: ".$query);
		if(!empty($limit) && $limit != -1){
			$result =& $this->db->limitQuery($query, $row_offset + 0, $limit,true,"Error retrieving $this->object_name list: ");
		}else{
			$result =& $this->db->query($query,true,"Error retrieving $this->object_name list: ");
		}

		$list = Array();
		if($max_per_page == -1){
			$max_per_page 	= $list_max_entries_per_page;
		}
		$rows_found =  $this->db->getRowCount($result);

		$this->log->debug("Found $rows_found ".$this->object_name."s");
                
		$previous_offset = $row_offset - $max_per_page;
		$next_offset = $row_offset + $max_per_page;

		if($rows_found != 0)
		{

			// We have some data.

			for($index = $row_offset , $row = $this->db->fetchByAssoc($result, $index); $row && ($index < $row_offset + $max_per_page || $max_per_page == -99) ;$index++, $row = $this->db->fetchByAssoc($result, $index)){

				//$this->db->println("modulename=".$this->module_name);
				if($this->module_name=="Users")
				{
				foreach($this->list_fields as $field)
                                {
                                        if (isset($row[$field])) {
                                                $this->$field = $row[$field];


                                                $this->log->debug("$this->object_name({$row['id']}): ".$field." = ".$this->$field);
                                        }
                                        else
                                        {
                                                $this->$field = "";
                                        }
                                }
					$this->fill_in_additional_list_fields();
				}	
				else
				{
				
				foreach($this->list_fields as $entry)
				{

					foreach($entry as $key=>$field) // this will be cycled only once
					{						
						if (isset($row[$field])) {
							$this->column_fields[$this->list_fields_names[$key]] = $row[$field];
						
						
							$this->log->debug("$this->object_name({$row['id']}): ".$field." = ".$this->$field);
						}
						else 
						{
							$this->column_fields[$this->list_fields_names[$key]] = "";
						}
					}
				}
				}

				//$this->db->println("here is the bug");
			

				$list[] = $this;
			}
		}

		$response = Array();
		$response['list'] = $list;
		$response['row_count'] = $rows_found;
		$response['next_offset'] = $next_offset;
		$response['previous_offset'] = $previous_offset;

		return $response;
	}

	function process_full_list_query($query)
	{
		$this->log->debug("process_full_list_query: query is ".$query);
		$result =& $this->db->query($query, false);
		$this->log->debug("process_full_list_query: result is ".$result);


		if($this->db->getRowCount($result) > 0){
		
		//	$this->db->println("process_full mid=".$this->module_id." mname=".$this->module_name);
			// We have some data.
			while ($row = $this->db->fetchByAssoc($result)) {				
				$rowid=$row[$this->module_id];

				if(isset($rowid))
			       		$this->retrieve_entity_info($rowid,$this->module_name);
				else
					$this->db->println("rowid not set unable to retrieve");
				 
				 
				/*foreach($this->list_fields as $entry)
				{
					foreach($entry as $key=>$field) // this will be cycled only once
					{						
						if (isset($row[$field])) {
							$this->column_fields[$this->list_fields_names[$key]] = $row[$field];
						
							$this->log->debug("process_full_list: $this->object_name({$row['id']}): ".$field." = ".$this->$field);
						}
						else {
 	                	                                $this->column_fields[$this->list_fields_names[$key]] = '';   
						}
					}
				}

				$this->fill_in_additional_list_fields();*/

				$list[] = $this;
			}
		}

		if (isset($list)) return $list;
		else return null;
	}
	
	/**
	 * Track the viewing of a detail record.  This leverages get_summary_text() which is object specific
	 * params $user_id - The user that is viewing the record.
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function track_view($user_id, $current_module,$id)
	{
		$this->log->debug("About to call tracker (user_id, module_name, item_id)($user_id, $current_module, $this->id)");

		$tracker = new Tracker();
		$tracker->track_view($user_id, $current_module, $id, '');
	}

	/**
	 * return the summary text that should show up in the recent history list for this object.
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function get_summary_text()
	{
		return "Base Implementation.  Should be overridden.";
	}

	/**
	 * This is designed to be overridden and add specific fields to each record.  This allows the generic query to fill in
	 * the major fields, and then targetted queries to get related fields and add them to the record.  The contact's account for instance.
	 * This method is only used for populating extra fields in lists
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function fill_in_additional_list_fields()
	{
	}

	/**
	 * This is designed to be overridden and add specific fields to each record.  This allows the generic query to fill in
	 * the major fields, and then targetted queries to get related fields and add them to the record.  The contact's account for instance.
	 * This method is only used for populating extra fields in the detail form
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function fill_in_additional_detail_fields()
	{
	}

	/**
	 * This is a helper class that is used to quickly created indexes when createing tables
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function create_index($query)
	{
		$this->log->info($query);

		$result =& $this->db->query($query, true, "Error creating index:");
	}

	/** This function should be overridden in each module.  It marks an item as deleted.
	* If it is not overridden, then marking this type of item is not allowed
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function mark_deleted($id)
	{
		$query = "UPDATE crmentity set deleted=1 where crmid='$id'";
		$this->db->query($query, true,"Error marking record deleted: ");

		//$this->mark_relationships_deleted($id);

		// Take the item off of the recently viewed lists.
		//$tracker = new Tracker();
		//$tracker->delete_item_history($id);

	}

	/** This function deletes relationships to this object.  It should be overridden to handle the relationships of the specific object.
	* This function is called when the item itself is being deleted.  For instance, it is called on Contact when the contact is being deleted.
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function mark_relationships_deleted($id)
	{

	}

	/**
	 * This function is used to execute the query and create an array template objects from the resulting ids from the query.
	 * It is currently used for building sub-panel arrays.
	 * param $query - the query that should be executed to build the list
	 * param $template - The object that should be used to copy the records.
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function build_related_list($query, &$template)
	{

		$this->log->debug("Finding linked records $this->object_name: ".$query);

		$result =& $this->db->query($query, true);

		$list = Array();

		while($row = $this->db->fetchByAssoc($result))
		{
			$template->retrieve($row['id']);

			// this copies the object into the array
			$list[] = $template;
		}

		return $list;
	}

	/**
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function build_related_list2($query, &$template, &$field_list)
	{

		$this->log->debug("Finding linked values $this->object_name: ".$query);

		$result =& $this->db->query($query, true);

		$list = Array();

		while($row = $this->db->fetchByAssoc($result))
		{
			// Create a blank copy
			$copy = $template;
			
			foreach($field_list as $field)
			{
				// Copy the relevant fields
				$copy->$field = $row[$field];
				
			}	

			// this copies the object into the array
			$list[] = $copy;
		}

		return $list;
	}

	/* This is to allow subclasses to fill in row specific columns of a list view form */
	function list_view_parse_additional_sections(&$list_form)
	{
	}

	/* This function assigns all of the values into the template for the list view */
	function get_list_view_array(){
		$return_array = Array();
		
		foreach($this->list_fields as $field)
		{
			$return_array[strtoupper($field)] = $this->$field;
		}
		
		return $return_array;	
	}
	function get_list_view_data()
	{
		
		return $this->get_list_view_array();
	}

	function get_where(&$fields_array)
	{ 
		$where_clause = "WHERE "; 
		$first = 1; 
		foreach ($fields_array as $name=>$value) 
		{ 
			if ($first) 
			{ 
				$first = 0;
			} 
			else 
			{ 
				$where_clause .= " AND ";
			} 

			$where_clause .= "$name = ".PearDatabase::quote($value)."";
		} 

		$where_clause .= " AND deleted=0";
		return $where_clause;
	}


	function retrieve_by_string_fields($fields_array, $encode=true) 
	{ 
		$where_clause = $this->get_where($fields_array);
		
		$query = "SELECT * FROM $this->table_name $where_clause";
		$this->log->debug("Retrieve $this->object_name: ".$query);
		$result =& $this->db->requireSingleResult($query, true, "Retrieving record $where_clause:");
		if( empty($result)) 
		{ 
		 	return null; 
		} 

		 $row = $this->db->fetchByAssoc($result,-1, $encode);

		foreach($this->column_fields as $field) 
		{ 
			if(isset($row[$field])) 
			{ 
				$this->$field = $row[$field];
			}
		} 
		$this->fill_in_additional_detail_fields();
		return $this;
	}

	// this method is called during an import before inserting a bean
	// define an associative array called $special_fields
	// the keys are user defined, and don't directly map to the bean's fields
	// the value is the method name within that bean that will do extra
	// processing for that field. example: 'full_name'=>'get_names_from_full_name'

	function process_special_fields() 
	{ 
		foreach ($this->special_functions as $func_name) 
		{ 
			if ( method_exists($this,$func_name) ) 
			{ 
				$this->$func_name(); 
			} 
		} 
	}
	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause($value){
			$where_clause = "WHERE "; 
		$first = 1; 
		foreach ($fields_array as $name=>$value) 
		{ 
			if ($first) 
			{ 
				$first = 0;
			} 
			else 
			{ 
				$where_clause .= " or";
			} 

			$where_clause .= "$name = ".PearDatabase::quote($value)."";
		} 

		$where_clause .= " AND deleted=0";
		return $where_clause;
	}

/*	
	function get_msgboard_data($orderby = "" , $where = "" ,$row_offset = 0)
 	{
 	         $response = $this->get_messageboard_list($order_by, $where , $row_offset,$limit= -1,$max_per_page = -1);
 	         return $response;
 	}
 	
  function get_messageboard_list($orderby, $where, $row_offset,$limit= -1, $max_per_page = -1)
  {
    global $list_max_entries_per_page;

		if(isset($_REQUEST['query']))
			{
$sql='select distinct(t.topic_id), t.topic_title, c.cat_title, first.username as author, t.topic_replies,FROM_UNIXTIME(p.post_time) as post_time from phpbb_posts p, phpbb_topics t, phpbb_forums f, phpbb_categories c, phpbb_users first where t.topic_id = p.topic_id and p.post_id=t.topic_last_post_id and t.topic_poster=first.user_id and t.forum_id=f.forum_id and f.cat_id=c.cat_id and ' .$where;
	
//				$sql='select distinct(t.topic_title),c.cat_title,t.topic_poster, t.topic_replies,FROM_UNIXTIME(p.post_time) as post_time, t.topic_replies from phpbb_posts p, phpbb_topics t, phpbb_forums f, phpbb_categories c,phpbb_users u where t.forum_id=f.forum_id and f.cat_id=c.cat_id and ' .$where ;
			}
			else
			{
				$sql='select t.topic_id,p.post_id,t.topic_title,FROM_UNIXTIME(p.post_time) as post_time, f.forum_name , u.username , t.topic_replies from phpbb_posts p, phpbb_topics t, phpbb_forums f, phpbb_users u where p.topic_id=t.topic_id and t.forum_id=f.forum_id and u.user_id=t.topic_poster ORDER BY p.post_time ';
 	 		}
 	                 $result = mysql_query($sql);
 	                 $list = Array();
                        
                         if($max_per_page == -1)
                         {
                           $max_per_page 	= $list_max_entries_per_page;
                         }
	
 	                 $rows_found =  $this->db->getRowCount($result);
 	                 $previous_offset = $row_offset - $max_per_page;
 	                 $next_offset = $row_offset + $max_per_page;
 	                 if($rows_found != 0)
 	                 {
                           //$max_per_page=15;
 	                    for($index = $row_offset , $row = $this->db->fetchByAssoc($result, $index); $row && ($index < $row_offset + $max_per_page ||  $max_per_page == -99) ;$index++, $row = $this->db->fetchByAssoc($result, $index))
 	                         {
 	                                 foreach($this->list_fields as $field)
 	                                 {
 	                                         //print_r($this->list_fields);
 	                                         if (isset($row[$field]))
 	                                         {
 	                                                 $this->$field = $row[$field];
 	                                         }
 	                                         else
 	                                         {
 	                                                 $this->$field = "";
 	                                         }
 	                                 }
 	 
 	                    $list[] = $this;
 	                         }
 	                 }
 	 
 	           $response = Array();
 	                 $response['list'] = $list;
 	                 $response['row_count'] = $rows_found;
 	                 $response['next_offset'] = $next_offset;
 	                 $response['previous_offset'] = $previous_offset;
                         /*
 	                 foreach($this->list_fields as $field)
 	                                {
 	                                        if (isset($row[$field]))
 	                                         {
 	                                                $this->$field = $row[$field];
 	                                                $this->log->debug("process_full_list: $this->object_name({$row['id']}): ".$field." = ".$this->$field);
 	                                        }
 	                                }
 	                 return $response;
  }
	*/
}


?>
