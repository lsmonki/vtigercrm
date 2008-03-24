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
 * $Header: /advent/projects/wesat/vtiger_crm/vtigercrm/data/CRMEntity.php,v 1.16 2005/04/29 04:21:31 mickie Exp $
 * Description:  Defines the base class for all data entities used throughout the 
 * application.  The base class including its methods and variables is designed to 
 * be overloaded with module-specific methods and variables particular to the 
 * module's base entity class. 
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('data/Tracker.php');
require_once('include/utils/utils.php');
require_once('include/utils/UserInfoUtil.php');

class CRMEntity
{
  /**
   * This method implements a generic insert and update logic for any SugarBean
   * This method only works for subclasses that implement the same variable names.
   * This method uses the presence of an id vtiger_field that is not null to signify and update.
   * The id vtiger_field should not be set otherwise.
   * todo - Add support for vtiger_field type validation and encoding of parameters.
   * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
   * All Rights Reserved.
   * Contributor(s): ______________________________________..
   */

  var $ownedby;
   
	
  function saveentity($module)
  {
	global $current_user, $adb;//$adb added by raju for mass mailing
	$insertion_mode = $this->mode;

	$this->db->println("TRANS saveentity starts $module");
	$this->db->startTransaction();
	

	foreach($this->tab_name as $table_name)
	{
			
		if($table_name == "vtiger_crmentity")
		{
			$this->insertIntoCrmEntity($module);
		}
		else
		{
			$this->insertIntoEntityTable($table_name, $module);			
		}
	}

	//Inserting into the group Table
	if($this->ownedby == 0)
	{
		$this->insertIntoGroupTable($module);
	}	
	
	//Calling the Module specific save code
	$this->save_module($module);

	$assigntype=$_REQUEST['assigntype'];
	if($module != "Calendar")
          $this->whomToSendMail($module,$this ->mode,$assigntype);
	
	$this->db->completeTransaction();
        $this->db->println("TRANS saveentity ends");
  }


	
	function insertIntoAttachment1($id,$module,$filedata,$filename,$filesize,$filetype,$user_id)
	{
		$date_var = date('YmdHis');
		global $current_user;
		global $adb;
		//global $root_directory;
		global $log;

		$ownerid = $user_id;
		
		if($filesize != 0)
		{
			$data = base64_encode(fread(fopen($filedata, "r"), $filesize));
		}
		
		$current_id = $adb->getUniqueID("vtiger_crmentity");

		if($module=='Emails') 
		{ 
			$log->info("module is ".$module);
			$idname='emailid';      $tablename='emails';    $descname='description';
		}
		else     
		{ 
			$idname='notesid';      $tablename='notes';     $descname='notecontent';
		}

		$sql="update ".$tablename." set filename='".$filename."' where ".$idname."=".$id;
		$adb->query($sql);

		$sql1 = "insert into vtiger_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values(".$current_id.",".$current_user->id.",".$ownerid.",'".$module." Attachment','"."',".$adb->formatString("vtiger_crmentity","createdtime",$date_var).",".$adb->formatString("vtiger_crmentity","modifiedtime",$date_var).")";
		$adb->query($sql1);

		$sql2="insert into vtiger_attachments(attachmentsid, name, description, type) values(".$current_id.",'".$filename."','"."','".$filetype."')";
		$result=$adb->query($sql2);

		//TODO -- instead of put contents in db now we should store the file in harddisk

		$sql3='insert into vtiger_seattachmentsrel values('.$id.','.$current_id.')';
		$adb->query($sql3);
	}
	


	/**
	 *      This function is used to upload the attachment in the server and save that attachment information in db.
	 *      @param int $id  - entity id to which the file to be uploaded
	 *      @param string $module  - the current module name
	 *      @param array $file_details  - array which contains the file information(name, type, size, tmp_name and error)
	 *      return void
	*/
	function uploadAndSaveFile($id,$module,$file_details)
	{
		global $log;
		$log->debug("Entering into uploadAndSaveFile($id,$module,$file_details) method.");

		global $adb, $current_user;
		global $upload_badext;

		$date_var = date('YmdHis');

		//to get the owner id
		$ownerid = $this->column_fields['assigned_user_id'];
		if(!isset($ownerid) || $ownerid=='')
			$ownerid = $current_user->id;

	
		// Arbitrary File Upload Vulnerability fix - Philip
		$binFile = preg_replace('/\s+/', '_', $file_details['name']);//replace space with _ in filename
		$ext_pos = strrpos($binFile, ".");

		$ext = substr($binFile, $ext_pos + 1);

		if (in_array($ext, $upload_badext))
		{
			$binFile .= ".txt";
		}
		// Vulnerability fix ends

		$current_id = $adb->getUniqueID("vtiger_crmentity");

		$filename = basename($binFile);
		$filetype= $file_details['type'];
		$filesize = $file_details['size'];
		$filetmp_name = $file_details['tmp_name'];

		//get the file path inwhich folder we want to upload the file
		$upload_file_path = decideFilePath();

		//upload the file in server
		$upload_status = move_uploaded_file($filetmp_name,$upload_file_path.$current_id."_".$binFile);

		$save_file = 'true';
		//only images are allowed for these modules
		if($module == 'Contacts' || $module == 'Products')
		{
			$save_file = validateImageFile($file_details);
		}

		if($save_file == 'true' && $upload_status == 'true')
		{
			//This is only to update the attached filename in the vtiger_notes vtiger_table for the Notes module
			if($module=='Notes')
			{
				$sql="update vtiger_notes set filename='".$filename."' where notesid = ".$id;
				$adb->query($sql);
			}

			$sql1 = "insert into vtiger_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values(".$current_id.",".$current_user->id.",".$ownerid.",'".$module." Attachment','".$this->column_fields['description']."',".$adb->formatString("vtiger_crmentity","createdtime",$date_var).",".$adb->formatString("vtiger_crmentity","modifiedtime",$date_var).")";
			$adb->query($sql1);

			$sql2="insert into vtiger_attachments(attachmentsid, name, description, type, path) values(".$current_id.",'".$filename."','".$this->column_fields['description']."','".$filetype."','".$upload_file_path."')";
			$result=$adb->query($sql2);

			if($_REQUEST['mode'] == 'edit')
			{
				if($id != '' && $_REQUEST['fileid'] != '')
				{
					$delquery = 'delete from vtiger_seattachmentsrel where crmid = '.$id.' and attachmentsid = '.$_REQUEST['fileid'];
					$adb->query($delquery);
				}
			}
			if($module == 'Notes')
			{
				$query = "delete from vtiger_seattachmentsrel where crmid = ".$id;
				$adb->query($query);
			}
			$sql3='insert into vtiger_seattachmentsrel values('.$id.','.$current_id.')';
			$adb->query($sql3);

			return true;
		}
		else
		{
			$log->debug("Skip the save attachment process.");
			return false;
		}
	}

	/** Function to insert values in the vtiger_crmentity for the specified module
  	  * @param $module -- module:: Type varchar
 	 */	

  function insertIntoCrmEntity($module)
  {
	global $adb;
	global $current_user;
	global $log;
                
	$date_var = date('YmdHis');
	if($_REQUEST['assigntype'] == 'T')
	{
		$ownerid= 0;
	}
	else
	{
		$ownerid = $this->column_fields['assigned_user_id'];
	}
        
	$sql="select ownedby from vtiger_tab where name='".$module."'";
	$res=$adb->query($sql);
	$this->ownedby = $adb->query_result($res,0,'ownedby');
	
	if($this->ownedby == 1)
	{
		$log->info("module is =".$module);
		$ownerid = $current_user->id;
	}	
	
	
	if($module == 'Events')
	{
		$module = 'Calendar';
	}
	if($this->mode == 'edit')
	{
		$description_val = from_html($adb->formatString("vtiger_crmentity","description",$this->column_fields['description']),($insertion_mode == 'edit')?true:false);

		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		$tabid = getTabid($module);
		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] ==0)
		{
			$sql = "update vtiger_crmentity set smownerid=".$ownerid.",modifiedby=".$current_user->id.",description=".$description_val.", modifiedtime=".$adb->formatString("vtiger_crmentity","modifiedtime",$date_var)." where crmid=".$this->id;
		}
		else
		{
			$profileList = getCurrentUserProfileList();
			$perm_qry = "SELECT columnname FROM vtiger_field INNER JOIN vtiger_profile2field ON vtiger_profile2field.fieldid = vtiger_field.fieldid INNER JOIN vtiger_def_org_field ON vtiger_def_org_field.fieldid = vtiger_field.fieldid WHERE vtiger_field.tabid = ".$tabid." AND vtiger_profile2field.visible = 0 AND vtiger_profile2field.profileid IN ".$profileList." AND vtiger_def_org_field.visible = 0 and vtiger_field.tablename='vtiger_crmentity' and vtiger_field.displaytype in (1,3);";
			$perm_result = $adb->query($perm_qry);
			$perm_rows = $adb->num_rows($perm_result);
			for($i=0; $i<$perm_rows; $i++)
			{
				$columname[]=$adb->query_result($perm_result,$i,"columnname");
			}
			if(is_array($columname) && in_array("description",$columname))
			{
				$sql = "update vtiger_crmentity set smownerid=".$ownerid.",modifiedby=".$current_user->id.",description=".$description_val.", modifiedtime=".$adb->formatString("vtiger_crmentity","modifiedtime",$date_var)." where crmid=".$this->id;
			}
			else
			{
				$sql = "update vtiger_crmentity set smownerid=".$ownerid.",modifiedby=".$current_user->id.", modifiedtime=".$adb->formatString("vtiger_crmentity","modifiedtime",$date_var)." where crmid=".$this->id;
			}
		}
		$adb->query($sql);
		$sql1 ="delete from vtiger_ownernotify where crmid=".$this->id;
		$adb->query($sql1);
		if($ownerid != $current_user->id)
		{
			$sql1 = "insert into vtiger_ownernotify values(".$this->id.",".$ownerid.",null)";
			$adb->query($sql1);
		}
		
		

		

		
	}
	else
	{
		//if this is the create mode and the group allocation is chosen, then do the following
		$current_id = $adb->getUniqueID("vtiger_crmentity");
		$_REQUEST['currentid']=$current_id;

		$description_val = from_html($adb->formatString("vtiger_crmentity","description",$this->column_fields['description']),($insertion_mode == 'edit')?true:false);
		$sql = "insert into vtiger_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values('".$current_id."','".$current_user->id."','".$ownerid."','".$module."',".$description_val.",".$adb->formatDate($date_var).",".$adb->formatDate($date_var).")";
		$adb->query($sql);
		$this->id = $current_id;
	}

	


    }


	/** Function to insert values in the specifed table for the specified module
  	  * @param $table_name -- table name:: Type varchar
  	  * @param $module -- module:: Type varchar
 	 */
  function insertIntoEntityTable($table_name, $module)
  {
	  global $log;
  	  global $current_user;	  
	   $log->info("function insertIntoEntityTable ".$module.' vtiger_table name ' .$table_name);
	  global $adb;
	  $insertion_mode = $this->mode;

	  //Checkin whether an entry is already is present in the vtiger_table to update
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
		  $tabid= getTabid($module);
	  	  if($tabid == 9)
	          	$tabid ="9,16";	  
		  require('user_privileges/user_privileges_'.$current_user->id.'.php');
		  if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] ==0)
		  {

			  $sql = "select * from vtiger_field where tabid in (".$tabid.") and tablename='".$table_name."' and displaytype in (1,3) group by columnname"; 
		  }
		  else
		  {
			  $profileList = getCurrentUserProfileList();
			  $sql = "SELECT *
			  FROM vtiger_field
			  INNER JOIN vtiger_profile2field
			  ON vtiger_profile2field.fieldid = vtiger_field.fieldid
			  INNER JOIN vtiger_def_org_field
			  ON vtiger_def_org_field.fieldid = vtiger_field.fieldid
			  WHERE vtiger_field.tabid in (".$tabid.")
			  AND vtiger_profile2field.visible = 0 
			  AND vtiger_profile2field.profileid IN ".$profileList."
			  AND vtiger_def_org_field.visible = 0 and vtiger_field.tablename='".$table_name."' and vtiger_field.displaytype in (1,3) group by columnname";
		  }	   

	  }
	  else
	  {
		  $column = $this->tab_name_index[$table_name];
		  if($column == 'id' && $table_name == 'vtiger_users')
		  {
		 	$currentuser_id = $adb->getUniqueID("vtiger_users");
			$this->id = $currentuser_id;
		  }
		  $value = $this->id;
	  	  $tabid= getTabid($module);	
		  $sql = "select * from vtiger_field where tabid=".$tabid." and tablename='".$table_name."' and displaytype in (1,3,4)"; 
	  }

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
				  if($this->column_fields[$fieldname] == 'on' || $this->column_fields[$fieldname] == 1)
				  {
					  $fldvalue = '1';
				  }
				  else
				  {
					  $fldvalue = '0';
				  }

			  }
			  elseif($uitype == 33)
			  {
  				if(is_array($this->column_fields[$fieldname]))
  				{
  				  $field_list = implode(' |##| ',$this->column_fields[$fieldname]);
  				}else
  				{
  				  $field_list = $this->column_fields[$fieldname];
          		}
  				$fldvalue = $field_list;
			  }
			  elseif($uitype == 5 || $uitype == 6 || $uitype ==23)
			  {
				  if($_REQUEST['action'] == 'Import')
				  {
					  $fldvalue = $this->column_fields[$fieldname];
				  }
				  else
				  {
					  //Added to avoid function call getDBInsertDateValue in ajax save
					  $fldvalue = (($_REQUEST['ajxaction'] == 'DETAILVIEW')?$this->column_fields[$fieldname]:getDBInsertDateValue($this->column_fields[$fieldname]));
				  }
			  }
			  elseif($uitype == 7)
			  {
				  //strip out the spaces and commas in numbers if given ie., in amounts there may be ,
				  $fldvalue = str_replace(",","",$this->column_fields[$fieldname]);//trim($this->column_fields[$fieldname],",");

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
		  if($fldvalue=='') $fldvalue ="NULL";
		  if($insertion_mode == 'edit')
		  {
			  if($table_name == 'vtiger_notes' && $columname == 'filename' && $_FILES['filename']['name'] == '')
			  {
				  $fldvalue = $this->getOldFileName($this->id);
			  }
			  if($table_name != 'vtiger_ticketcomments')
			  {
				  if($i == 0)
				  {
					  $update = $columname."=".$fldvalue."";
				  }
				  else
				  {
					  $update .= ', '.$columname."=".$fldvalue."";
				  }
			  }
		  }
		  else
		  {
			  $column .= ", ".$columname;
			  $value .= ", ".$fldvalue."";
		  }

	  }





	  if($insertion_mode == 'edit')
	  {
		  if($_REQUEST['module'] == 'Potentials')
		  {
			  $dbquery = 'select sales_stage from vtiger_potential where potentialid = '.$this->id;
			  $sales_stage = $adb->query_result($adb->query($dbquery),0,'sales_stage');
			  if($sales_stage != $_REQUEST['sales_stage'] && $_REQUEST['sales_stage'] != '')
			  {
				  $date_var = date('YmdHis');
				  $closingdate = getDBInsertDateValue($this->column_fields['closingdate']);
				  $sql = "insert into vtiger_potstagehistory values('',".$this->id.",'".$this->column_fields['amount']."','".$sales_stage."','".$this->column_fields['probability']."',0,".$adb->formatString("vtiger_potstagehistory","closedate",$closingdate).",".$adb->formatString("vtiger_potstagehistory","lastmodified",$date_var).")";
				  $adb->query($sql);
			  }
		  }
		  elseif($_REQUEST['module'] == 'PurchaseOrder' || $_REQUEST['module'] == 'SalesOrder' || $_REQUEST['module'] == 'Quotes' || $_REQUEST['module'] == 'Invoice')
		  {
			  //added to update the history for PO, SO, Quotes and Invoice
			  $history_field_array = Array(
				  			"PurchaseOrder"=>"postatus",
							"SalesOrder"=>"sostatus",
							"Quotes"=>"quotestage",
							"Invoice"=>"invoicestatus"
						      );

			  $inventory_module = $_REQUEST['module'];

			  if($_REQUEST['ajxaction'] == 'DETAILVIEW')//if we use ajax edit
			  {
				  if($inventory_module == "PurchaseOrder")
					  $relatedname = getVendorName($this->column_fields['vendor_id']);
				  else
				  	$relatedname = getAccountName($this->column_fields['account_id']);

				  $total = $this->column_fields['hdnGrandTotal'];
			  }
			  else//using edit button and save
			  {
			  	if($inventory_module == "PurchaseOrder")
			  		$relatedname = $_REQUEST["vendor_name"];
			  	else
			  		$relatedname = $_REQUEST["account_name"];

				$total = $_REQUEST['total'];
			  }

			  $oldvalue = getSingleFieldValue($this->table_name,$history_field_array[$inventory_module],$this->module_id,$this->id);
			  if($oldvalue != $this->column_fields["$history_field_array[$inventory_module]"])
			  {
				  addInventoryHistory($inventory_module, $this->id,$relatedname,$total,$this->column_fields["$history_field_array[$inventory_module]"]);
			  }
		  }

		  //Check done by Don. If update is empty the the query fails
		  if(trim($update) != '')
        	  {
		  	$sql1 = "update ".$table_name." set ".$update." where ".$this->tab_name_index[$table_name]."=".$this->id;

		  	$adb->query($sql1); 
		  }
		  

	  }
	  else
	  {
		  $sql1 = "insert into ".$table_name." (".$column.") values(".$value.")";
		  $adb->query($sql1); 
		  

	  }

  }

function whomToSendMail($module,$insertion_mode,$assigntype)
{
 global $adb;
       if($insertion_mode!="edit")
       {
               if($assigntype=='U')
               {
                       if($module == 'Events' || $module == 'Calendar')
                       {
                               $moduleObj=new Activity();
                       }else
                       {
                               $moduleObj=new $module();
                       }
                       sendNotificationToOwner($module,$moduleObj);
               }
               elseif($assigntype=='T')
               {
                       $groupname=$_REQUEST['assigned_group_name'];
                       $resultqry=$adb->query("select groupid from vtiger_groups where groupname='".$groupname."'");
                       $groupid=$adb->query_result($resultqry,0,"groupid");
                       sendNotificationToGroups($groupid,$this->id,$module);
               }
       }
}


	/** Function to delete a record in the specifed table 
  	  * @param $table_name -- table name:: Type varchar
	  * The function will delete a record .The id is obtained from the class variable $this->id and the columnname got from $this->tab_name_index[$table_name]
 	 */
function deleteRelation($table_name)
{
         global $adb;
         $check_query = "select * from ".$table_name." where ".$this->tab_name_index[$table_name]."=".$this->id;
         $check_result=$adb->query($check_query);
         $num_rows = $adb->num_rows($check_result);

         if($num_rows == 1)
         {
                $del_query = "DELETE from ".$table_name." where ".$this->tab_name_index[$table_name]."=".$this->id;
                $adb->query($del_query);
         }

}
	/** Function to attachment filename of the given entity 
  	  * @param $notesid -- crmid:: Type Integer
	  * The function will get the attachmentsid for the given entityid from vtiger_seattachmentsrel table and get the attachmentsname from vtiger_attachments table 
	  * returns the 'filename'
 	 */
function getOldFileName($notesid)
{
	   global $log;
$log->info("in getOldFileName  ".$notesid);
	global $adb;
	$query1 = "select * from vtiger_seattachmentsrel where crmid=".$notesid;
	$result = $adb->query($query1);
	$noofrows = $adb->num_rows($result);
	if($noofrows != 0)
		$attachmentid = $adb->query_result($result,0,'attachmentsid');
	if($attachmentid != '')
	{
		$query2 = "select * from vtiger_attachments where attachmentsid=".$attachmentid;
		$filename = $adb->query_result($adb->query($query2),0,'name');
	}
	return "'".$filename."'";
}
	
	
	





// Code included by Jaguar - Ends 

	/** Function to retrive the information of the given recordid ,module 
  	  * @param $record -- Id:: Type Integer
  	  * @param $module -- module:: Type varchar
	  * This function retrives the information from the database and sets the value in the class columnfields array
 	 */
  function retrieve_entity_info($record, $module)
  {
    global $adb,$log,$app_strings;
    $result = Array();
    foreach($this->tab_name_index as $table_name=>$index)
    {
	    $result[$table_name] = $adb->query("select * from ".$table_name." where ".$index."=".$record);
	    if($adb->query_result($result["vtiger_crmentity"],0,"deleted") == 1)
	    die("<br><br><center>".$app_strings['LBL_RECORD_DELETE']." <a href='javascript:window.history.back()'>".$app_strings['LBL_GO_BACK'].".</a></center>");
    }
    $tabid = getTabid($module);
    $sql1 =  "select * from vtiger_field where tabid=".$tabid;
    $result1 = $adb->query($sql1);
    $noofrows = $adb->num_rows($result1);
    for($i=0; $i<$noofrows; $i++)
    {
      $fieldcolname = $adb->query_result($result1,$i,"columnname");
      $tablename = $adb->query_result($result1,$i,"tablename");
      $fieldname = $adb->query_result($result1,$i,"fieldname");

      //when we don't have entry in the $tablename then we have to avoid retrieve, otherwise adodb error will occur(ex. when we don't have attachment for troubletickets, $result[vtiger_attachments] will not be set so here we should not retrieve)
      if(isset($result[$tablename]))
      {
	      $fld_value = $adb->query_result($result[$tablename],0,$fieldcolname);
      }
      else
      {
	      $adb->println("There is no entry for this entity $record ($module) in the table $tablename");
	      $fld_value = "";
      }

      $this->column_fields[$fieldname] = $fld_value;
      
				
    }
	if($module == 'Users')
	{
		for($i=0; $i<$noofrows; $i++)
		{
			$fieldcolname = $adb->query_result($result1,$i,"columnname");
			$tablename = $adb->query_result($result1,$i,"tablename");
			$fieldname = $adb->query_result($result1,$i,"fieldname");
			$fld_value = $adb->query_result($result[$tablename],0,$fieldcolname);
			$this->$fieldname = $fld_value;

		}
	}
		
    $this->column_fields["record_id"] = $record;
    $this->column_fields["record_module"] = $module;
  }

	/** Function to saves the values in all the tables mentioned in the class variable $tab_name for the specified module
  	  * @param $module -- module:: Type varchar
 	 */
	function save($module_name) 
	{
		global $log;
	        $log->debug("module name is ".$module_name);
		//GS Save entity being called with the modulename as parameter
		$this->saveentity($module_name);
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


				//$this->db->println("here is the bug");
				

				$list[] = clone($this);//added by Richie to support PHP5
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
		$this->log->debug("CRMEntity:process_full_list_query");
		$result =& $this->db->query($query, false);
		//$this->log->debug("CRMEntity:process_full_list_query: result is ".$result);


		if($this->db->getRowCount($result) > 0){
		
		//	$this->db->println("process_full mid=".$this->module_id." mname=".$this->module_name);
			// We have some data.
			while ($row = $this->db->fetchByAssoc($result)) {				
				$rowid=$row[$this->module_id];

				if(isset($rowid))
			       		$this->retrieve_entity_info($rowid,$this->module_name);
				else
					$this->db->println("rowid not set unable to retrieve");
				 
				 
				
		//clone function added to resolvoe PHP5 compatibility issue in Dashboards
		//If we do not use clone, while using PHP5, the memory address remains fixed but the
	//data gets overridden hence all the rows that come in bear the same value. This in turn
//provides a wrong display of the Dashboard graphs. The data is erroneously shown for a specific month alone
//Added by Richie
				$list[] = clone($this);//added by Richie to support PHP5
			}
		}

		if (isset($list)) return $list;
		else return null;
	}
	
	/** This function should be overridden in each module.  It marks an item as deleted.
	* If it is not overridden, then marking this type of item is not allowed
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function mark_deleted($id)
	{
		$query = "UPDATE vtiger_crmentity set deleted=1 where crmid='$id'";
		$this->db->query($query, true,"Error marking record deleted: ");


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
		return $this;
	}

	// this method is called during an import before inserting a bean
	// define an associative array called $special_fields
	// the keys are user defined, and don't directly map to the bean's vtiger_fields
	// the value is the method name within that bean that will do extra
	// processing for that vtiger_field. example: 'full_name'=>'get_names_from_full_name'

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
         * Function to check if the custom vtiger_field vtiger_table exists
         * return true or false
         */
        function checkIfCustomTableExists($tablename)
        {
                $query = "select * from ".$tablename;
                $result = $this->db->query($query);
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

	/**
	 * function to construct the query to fetch the custom vtiger_fields
	 * return the query to fetch the custom vtiger_fields
         */
        function constructCustomQueryAddendum($tablename,$module)
        {
                global $adb;
		$tabid=getTabid($module);		
                $sql1 = "select columnname,fieldlabel from vtiger_field where generatedtype=2 and tabid=".$tabid;
                $result = $adb->query($sql1);
                $numRows = $adb->num_rows($result);
                $sql3 = "select ";
                for($i=0; $i < $numRows;$i++)
                {
                        $columnName = $adb->query_result($result,$i,"columnname");
                        $fieldlabel = $adb->query_result($result,$i,"fieldlabel");
                        //construct query as below
                        if($i == 0)
                        {
                                $sql3 .= $tablename.".".$columnName. " '" .$fieldlabel."'";
                        }
                        else
                        {
                                $sql3 .= ", ".$tablename.".".$columnName. " '" .$fieldlabel."'";
                        }

                }
                if($numRows>0)
                {
                        $sql3=$sql3.',';
                }
                return $sql3;

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

	/**
	 * Track the viewing of a detail record.  This leverages get_summary_text() which is object specific
	 * params $user_id - The user that is viewing the record.
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function track_view($user_id, $current_module,$id='')
	{
		$this->log->debug("About to call vtiger_tracker (user_id, module_name, item_id)($user_id, $current_module, $this->id)");

		$tracker = new Tracker();
		$tracker->track_view($user_id, $current_module, $id, '');
	}

	function insertIntoGroupTable($module)
	{
		global $log;

		if($module == 'Events')
		{
			$module = 'Calendar';
		}
		if($this->mode=='edit')
		{
						
		  	//to disable the update of groupentity relation in ajax edit for the fields except assigned_user_id field
			if($_REQUEST['ajxaction'] != 'DETAILVIEW' || ($_REQUEST['ajxaction'] == 'DETAILVIEW' && $_REQUEST['fldName'] == 'assigned_user_id'))
		  	{	  
			  	if($_REQUEST['assigntype'] == 'T')
			  	{
					$groupname = $_REQUEST['assigned_group_name'];

					updateModuleGroupRelation($module,$this->id,$groupname);

			  	}
				else
				{
					updateModuleGroupRelation($module,$this->id,'');

				}

		  	}
      		}
		else
		{
			$groupname = $_REQUEST['assigned_group_name'];
		 	if($_REQUEST['assigntype'] == 'T')
		  	{
			  	insertIntoGroupRelation($module,$this->id,$groupname);
		  	}
		  
		}			

	}	

	

}
?>
