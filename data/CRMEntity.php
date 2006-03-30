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

class CRMEntity extends SugarBean
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

  function saveentity($module,$migration='')
  {
	global $current_user, $adb;//$adb added by raju for mass mailing
	$insertion_mode = $this->mode;

	$this->db->println("TRANS saveentity starts");
	$this->db->startTransaction();
	
	// Code included by Jaguar - starts    
	if(isset($_REQUEST['recurringtype']) && $_REQUEST['recurringtype']!='')
		$recur_type = trim($_REQUEST['recurringtype']);
	else
    		$recur_type='';	
	// Code included by Jaguar - Ends

	foreach($this->tab_name as $table_name)
	{
		if($table_name == "crmentity")
		{
			$this->insertIntoCrmEntity($module,$migration);
		}
		elseif($table_name == "salesmanactivityrel")
		{
			$this->insertIntoSmActivityRel($module);
		}
		//added by raju
		elseif($table_name=="seactivityrel" )
		{
			if($module=="Emails" && $_REQUEST['smodule']!='webmails')
			//modified by Richie as raju's implementation broke the feature for addition of webmail to crmentity.need to be more careful in future while integrating code
			//if($_REQUEST['smodule']!='webmails' && $_REQUEST['smodule'] != '')
			{
				if($_REQUEST['currentid']!='')
				{
					$actid=$_REQUEST['currentid'];
				}
				else 
				{
					$actid=$_REQUEST['record'];
				}
				$parentid=$_REQUEST['parent_id'];

				if($_REQUEST['module'] != 'Emails')
				{
					$mysql='insert into seactivityrel values('.$parentid.','.$actid.')';
					$adb->query($mysql);
				}
				else
				{	  
					$myids=explode("|",$parentid);  //2@71|
					for ($i=0;$i<(count($myids)-1);$i++)
					{
						$realid=explode("@",$myids[$i]);
						$mycrmid=$realid[0];

						$mysql='insert into seactivityrel values('.$mycrmid.','.$actid.')';
						$adb->query($mysql);
					}
				}
			}
			else
			{
				if(isset($this->column_fields['parent_id']) && $this->column_fields['parent_id'] != '')
				{
					$this->insertIntoEntityTable($table_name, $module);
				}
				elseif($this->column_fields['parent_id']=='' && $insertion_mode=="edit")
				{
					$this->deleteRelation($table_name);
				}
			}			
		}
		elseif($table_name == "seticketsrel" || $table_name ==  "seproductsrel" || $table_name ==  "senotesrel")
		{
			if(isset($this->column_fields['parent_id']) && $this->column_fields['parent_id'] != '')//raju - mass mailing ends
			{
				$this->insertIntoEntityTable($table_name, $module);
			}
			elseif($this->column_fields['parent_id']=='' && $insertion_mode=="edit")
			{
				$this->deleteRelation($table_name);
			}
		}
		elseif($table_name ==  "cntactivityrel")
		{
			if(isset($this->column_fields['contact_id']) && $this->column_fields['contact_id'] != '')
			{
				$this->insertIntoEntityTable($table_name, $module);
			}
			elseif($this->column_fields['contact_id'] =='' && $insertion_mode=="edit")
			{
				$this->deleteRelation($table_name);
			}

		}
		elseif($table_name ==  "ticketcomments")
		{
                	$this->insertIntoTicketCommentTable($table_name, $module);
		}
		elseif($table_name ==  "faqcomments")
		{
                	$this->insertIntoFAQCommentTable($table_name, $module);
		}
		elseif($table_name == "activity_reminder")
		{
			if($recur_type == "--None--")
			{
				$this->insertIntoReminderTable($table_name,$module,"");
			}
		}
		elseif($table_name == "recurringevents") // Code included by Jaguar -  starts
		{
			$recur_type = trim($_REQUEST['recurringtype']);
			if($recur_type != "--None--"  && $recur_type != '')
		      	{		   
	      			$this->insertIntoRecurringTable($table_name,$module);
			}		
		}// Code included by Jaguar - Ends
		else
		{
			$this->insertIntoEntityTable($table_name, $module);			
		}
	}


	if($module == 'Emails' || $module == 'Notes' || $module == 'HelpDesk')
	{
		if(isset($_FILES['filename']['name']) && $_FILES['filename']['name']!='')
		{
			$this->insertIntoAttachment($this->id,$module);
		}
	}

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
		
		$current_id = $adb->getUniqueID("crmentity");

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

		$sql1 = "insert into crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values(".$current_id.",".$current_user->id.",".$ownerid.",'".$module." Attachment','"."',".$adb->formatString("crmentity","createdtime",$date_var).",".$adb->formatString("crmentity","modifiedtime",$date_var).")";
		$adb->query($sql1);

		$sql2="insert into attachments(attachmentsid, name, description, type) values(".$current_id.",'".$filename."','"."','".$filetype."')";
		$result=$adb->query($sql2);

		//TODO -- instead of put contents in db now we should store the file in harddisk

		$sql3='insert into seattachmentsrel values('.$id.','.$current_id.')';
		$adb->query($sql3);
	}



	function insertIntoAttachment($id,$module)
	{
		$date_var = date('YmdHis');
		global $current_user;
		global $adb;
		global $root_directory;
		global $upload_badext;

		$ownerid = $this->column_fields['assigned_user_id'];

		if(!isset($ownerid) || $ownerid=='')
			$ownerid = $current_user->id;

		$uploaddir = $root_directory ."/test/upload/";
		// Arbitrary File Upload Vulnerability fix - Philip
		$binFile = $_FILES['filename']['name'];
		$ext_pos = strrpos($binFile, ".");

		$ext = substr($binFile, $ext_pos + 1);

		if (in_array($ext, $upload_badext))
		{
			$binFile .= ".txt";
		}
		// Vulnerability fix ends

		$filename = basename($binFile);
		$filetype= $_FILES['filename']['type'];
		$filesize = $_FILES['filename']['size'];

		if($binFile != '')
		{
			if(move_uploaded_file($_FILES["filename"]["tmp_name"],$uploaddir.$binFile))
			{
				if($filesize != 0)
				{
					$data = base64_encode(fread(fopen($uploaddir.$binFile, "r"), $filesize));
				}
			}
			$current_id = $adb->getUniqueID("crmentity");

			//This is only to update the attached filename in the notes table for the Notes module
			if($module=='Notes')
			{
				$sql="update notes set filename='".$filename."' where notesid = ".$id;
				$adb->query($sql);
			}

			$sql1 = "insert into crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values(".$current_id.",".$current_user->id.",".$ownerid.",'".$module." Attachment','".$this->column_fields['description']."',".$adb->formatString("crmentity","createdtime",$date_var).",".$adb->formatString("crmentity","modifiedtime",$date_var).")";
			$adb->query($sql1);

			$sql2="insert into attachments(attachmentsid, name, description, type) values(".$current_id.",'".$filename."','".$this->column_fields[$descname]."','".$filetype."')";
			$result=$adb->query($sql2);

			//TODO -- instead of put contents in db now we should store the file in harddisk

			if($_REQUEST['mode'] == 'edit')
			{
				if($id != '' && $_REQUEST['fileid'] != '')
				{
					$delquery = 'delete from seattachmentsrel where crmid = '.$id.' and attachmentsid = '.$_REQUEST['fileid'];
					$adb->query($delquery);
				}
			}
			if($module == 'Notes')
			{
				$query = "delete from seattachmentsrel where crmid = ".$id;
				$adb->query($query);
			}
			$sql3='insert into seattachmentsrel values('.$id.','.$current_id.')';
			$adb->query($sql3);
		}
	}



  function insertIntoCrmEntity($module,$migration='')
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
                
	//This check is done for products.
	if($module == 'Products' || $module == 'Notes' || $module =='Faq' || $module == 'Vendors' || $module == 'PriceBooks')
	{
		$log->info("module is =".$module);
		$ownerid = $current_user->id;
	}
	if($module == 'Events')
	{
		$module = 'Activities';
	}
	if($this->mode == 'edit')
	{
		$description_val = from_html($adb->formatString("crmentity","description",$this->column_fields['description']),($insertion_mode == 'edit')?true:false);
		$sql = "update crmentity set smownerid=".$ownerid.",modifiedby=".$current_user->id.",description=".$description_val.", modifiedtime=".$adb->formatString("crmentity","modifiedtime",$date_var)." where crmid=".$this->id;

		$adb->query($sql);
		$sql1 ="delete from ownernotify where crmid=".$this->id;
		$adb->query($sql1);
		if($ownerid != $current_user->id)
		{
			$sql1 = "insert into ownernotify values(".$this->id.",".$ownerid.",'')";
			$adb->query($sql1);
		}
	}
	else
	{
		//if this is the create mode and the group allocation is chosen, then do the following
		$current_id = $adb->getUniqueID("crmentity");
		$_REQUEST['currentid']=$current_id;

		if($migration != '')
		{
			$sql = "select * from Migrator where oldid='".$this->id ."'";

			$result = $adb->query($sql);
			$id = $adb->query_result($result,0,"newid");
			//get the corresponding newid for these assigned_user_id and modified_user_id
			$modifierid = $adb->query_result($result,0,"assigned_user_id");
			$id = $adb->query_result($result,0,"newid");

			$sql_modifierid = "select * from Migrator where oldid='".$modifierid ."'";
			$result_modifierid = $adb->query($sql_modifierid);
			$modifierid = $adb->query_result($result_modifierid,0,"newid");

			$creatorid =$adb->query_result($result,0,"modified_user_id");

			$sql_creatorid = "select * from Migrator where oldid='".$creatorid ."'";
			$result_creatorid = $adb->query($sql_creatorid);
			$creatorid = $adb->query_result($result_creatorid,0,"newid");

			$createdtime = $adb->query_result($result,0,"createdtime");
			$modifiedtime = $adb->query_result($result,0,"modifiedtime");
			$deleted = $adb->query_result($result,0,"deleted");
			$module = $adb->query_result($result,0,"module");
			$description_val = from_html($adb->formatString("crmentity","description",$this->column_fields['description']),($insertion_mode == 'edit')?true:false);
			//get the values from this and set to the query below and then relax!
			$sql = "insert into crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime,deleted) values('".$id."','".$creatorid."','".$modifierid."','".$module."',".$description_val.",'".$createdtime."','".$modifiedtime ."',".$deleted.")";
			$adb->query($sql);
			$this->id = $id;
		}
		else
		{
			$description_val = from_html($adb->formatString("crmentity","description",$this->column_fields['description']),($insertion_mode == 'edit')?true:false);
			$sql = "insert into crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values('".$current_id."','".$current_user->id."','".$ownerid."','".$module."',".$description_val.",'".$date_var."','".$date_var."')";
			$adb->query($sql);
			$this->id = $current_id;
		}
	}

	//$sql = "insert into crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values(".$current_id.",".$current_user->id.",".$ownerid.",'".$module."','".$this->column_fields['description']."',".$adb->formatString("crmentity","createdtime",$date_var).",".$adb->formatString("crmentity","modifiedtime",$date_var).")";
	//$adb->query($sql);
	//echo $sql;
	//$this->id = $current_id;
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
	$sql_qry = "insert into salesmanactivityrel (smid,activityid) values(".$this->column_fields['assigned_user_id'].",".$this->id.")";
    $adb->query($sql_qry);

  }
  //code added by richie starts
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
    $old_severity = $adb->query_result($tktresult,0,"severity");
    $old_category = $adb->query_result($tktresult,0,"category");
    if($_REQUEST['old_smownerid'] != $old_user_id && $old_user_id != 0)
    {
      $user_name = getUserName($this->column_fields['assigned_user_id']);
      $updatelog .= ' Transferred to '.$user_name.'\.';
    }
    elseif($old_user_id == 0)
    {
	$group_info = getGroupName($ticketid,'HelpDesk');	
	$group_name = $group_info[0];	
	if($group_name != $_REQUEST['assigned_group_name'])
		$updatelog .= ' Transferred to group '.$_REQUEST['assigned_group_name'].'\.';
    }
    if($old_status != $this->column_fields['ticketstatus'])
    {
      $updatelog .= ' Status Changed to '.$this->column_fields['ticketstatus'].'\.';
    }
    if($old_priority != $this->column_fields['ticketpriorities'])
    {
      $updatelog .= ' Priority Changed to '.$this->column_fields['ticketpriorities'].'\.';
    }
    if($old_severity != $this->column_fields['ticketseverities'])
    {
      $updatelog .= ' Severity Changed to '.$this->column_fields['ticketseverities'].'\.';
    }
    if($old_category != $this->column_fields['ticketcategories'])
    {
      $updatelog .= ' Category Changed to '.$this->column_fields['ticketcategories'].'\.';
    }
    if($_REQUEST['old_smownerid'] != $old_user_id || $old_status != $this->column_fields['ticketstatus'] || $old_priority != $this->column_fields['ticketpriorities'] || $old_severity != $this->column_fields['ticketseverities'] || $old_category != $this->column_fields['ticketcategories'] || $old_userid == 0)
    {
      $updatelog .= ' -- '.date("l dS F Y h:i:s A").' by '.$current_user->user_name.'--//--';
    }
    else
    {
        $update_log .= '--//--';
    }

    return $updatelog;
  }
  //code added by richie ends
  function insertIntoEntityTable($table_name, $module)
  {
	  global $log;	
	   $log->info("function insertIntoCrmEntity ".$module.' table name ' .$table_name);
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
				  if($this->column_fields[$fieldname] == 'on' || $this->column_fields[$fieldname] == 1)
				  {
					  $fldvalue = 1;
				  }
				  else
				  {
					  $fldvalue = 0;
				  }

			  }
			  elseif($uitype == 5 || $uitype == 6 || $uitype ==23)
			  {
				  if($_REQUEST['action'] == 'Import')
				  {
					  $fldvalue = $this->column_fields[$fieldname];
				  }
				  else
				  {
					  $fldvalue = getDBInsertDateValue($this->column_fields[$fieldname]);
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
		  if($fldvalue=='') $fldvalue ="NULL";
		  if($insertion_mode == 'edit')
		  {
			  //code by richie starts
			  if(($table_name == "troubletickets") && ($columname == "update_log"))
			  {
				  $fldvalue = $this->constructUpdateLog($this->id);
				  $fldvalue = from_html($adb->formatString($table_name,$columname,$fldvalue),($insertion_mode == 'edit')?true:false);
			  }
			  //code by richie ends

			  if($table_name == 'notes' && $columname == 'filename' && $_FILES['filename']['name'] == '')
			  {
				  $fldvalue = $this->getOldFileName($this->id);
			  }
			  if($table_name == 'products' && $columname == 'imagename')
			  {
			/*	  //Product Image Handling done
				  if($_FILES['imagename']['name'] != '')
				  {

					  $prd_img_arr = upload_product_image_file("edit",$this->id);
					  //print_r($prd_img_arr);
					  if($prd_img_arr["status"] == "yes")
					  {
						  $fldvalue ="'".$prd_img_arr["file_name"]."'";
					  }
					  else
					  {
						  $fldvalue ="'".getProductImageName($this->id)."'";
					  }	 


				  }
				  else
				  {
					  $fldvalue ="'".getProductImageName($this->id)."'";
				  }
		      */		  

			  }
			  if($table_name != 'ticketcomments')
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
			  //code by richie starts
			  if(($table_name == "troubletickets") && ($columname == "update_log"))
			  {
				  global $current_user;
				  $fldvalue = date("l dS F Y h:i:s A").' by '.$current_user->user_name;
				  if($_REQUEST['assigned_group_name'] != '' && $_REQUEST['assigntype'] == 'T')
                                  {
                                        $group_name = $_REQUEST['assigned_group_name'];
                                  }
				  elseif($this->column_fields['assigned_user_id'] != '')
				  {
					  $tkt_ownerid = $this->column_fields['assigned_user_id'];
				  }
				  else
				  {
					  $tkt_ownerid = $current_user->id;
				  }
				  if($group_name != '')
					  $tkt_ownername = $group_name;
				  else
					  $tkt_ownername = getUserName($tkt_ownerid);	
				  $fldvalue = " Ticket created. Assigned to ".$tkt_ownername." -- ".$fldvalue."--//--";
				  $fldvalue = from_html($adb->formatString($table_name,$columname,$fldvalue),($insertion_mode == 'edit')?true:false);
				  //echo ' updatevalue is ............. ' .$fldvalue;
			  }
			  elseif($table_name == 'products' && $columname == 'imagename')
			  {
				  //Product Image Handling done
			/*	  if($_FILES['imagename']['name'] != '')
				  {

					  $prd_img_arr = upload_product_image_file("create",$this->id);
					  //print_r($prd_img_arr);
					  if($prd_img_arr["status"] == "yes")
					  {
						  $fldvalue ="'".$prd_img_arr["file_name"]."'";
					  }
					  else
					  {
						  $fldvalue ="''";
					  }	 


				  }
				  else
				  {
					  $fldvalue ="''";
				  }
			*/	  

			  }
			  //code by richie ends
			  $column .= ", ".$columname;
			  $value .= ", ".$fldvalue."";
		  }

	  }





	  if($insertion_mode == 'edit')
	  {
		  if($_REQUEST['module'] == 'Potentials')
		  {
			  $dbquery = 'select sales_stage from potential where potentialid = '.$this->id;
			  $sales_stage = $adb->query_result($adb->query($dbquery),0,'sales_stage');
			  if($sales_stage != $_REQUEST['sales_stage'])
			  {
				  $date_var = date('YmdHis');
				  //$sql = "insert into potstagehistory values('',".$this->id.",".$_REQUEST['amount'].",'".$_REQUEST['sales_stage']."',".$_REQUEST['probability'].",".$_REQUEST['expectedrevenue'].",".$adb->formatString("potstagehistory","closedate",$_REQUEST['closingdate']).",".$adb->formatString("potstagehistory","lastmodified",$date_var).")";
				  //Changed to insert the close date based on user date format - after 4.2 patch2
				  $closingdate = getDBInsertDateValue($_REQUEST['closingdate']);
				  $sql = "insert into potstagehistory values('',".$this->id.",'".$_REQUEST['amount']."','".$sales_stage."','".$_REQUEST['probability']."',0,".$adb->formatString("potstagehistory","closedate",$closingdate).",".$adb->formatString("potstagehistory","lastmodified",$date_var).")";
				  $adb->query($sql);
			  }
		  }
		
		  //Check done by Don. If update is empty the the query fails
		  if(trim($update) != '')
        	  {
		  	$sql1 = "update ".$table_name." set ".$update." where ".$this->tab_name_index[$table_name]."=".$this->id;

		  	$adb->query($sql1); 
		  }

		  if($_REQUEST['assigntype'] == 'T')
		  {
			  $groupname = $_REQUEST['assigned_group_name'];
			  //echo 'about to update lead group relation';
			  if($module == 'Leads' && $table_name == 'leaddetails')
			  {
				  updateLeadGroupRelation($this->id,$groupname);
			  }
                          elseif($module == 'Accounts' && $table_name == 'account')
			  {
				  updateAccountGroupRelation($this->id,$groupname);
			  }
			  elseif($module == 'Contacts' && $table_name == 'contactdetails')
			  {
				  updateContactGroupRelation($this->id,$groupname);
			  }
			  elseif($module == 'Potentials' && $table_name == 'potential')
			  {
				  updatePotentialGroupRelation($this->id,$groupname);
			  }
			  elseif($module == 'Quotes' && $table_name == 'quotes')
			  {
				  updateQuoteGroupRelation($this->id,$groupname);
			  }
			  elseif($module == 'SalesOrder' && $table_name == 'salesorder')
			  {
				  updateSoGroupRelation($this->id,$groupname);
			  }
			  elseif($module == 'Invoice' && $table_name == 'invoice')
			  {
				  updateInvoiceGroupRelation($this->id,$groupname);
			  }
			  elseif($module == 'PurchaseOrder' && $table_name == 'purchaseorder')
			  {
				  updatePoGroupRelation($this->id,$groupname);
			  }
			  elseif($module == 'HelpDesk' && $table_name == 'troubletickets')
			  {
				  updateTicketGroupRelation($this->id,$groupname);
			  }
			  elseif($module =='Activities' || $module =='Events' || $module == 'Emails')
			  {
				 if($table_name == 'activity')
				 {
				   updateActivityGroupRelation($this->id,$groupname);
				 }
			  }
			   	

		  }
		  else
		  {
			  //echo 'about to update lead group relation again!';
			  if($module == 'Leads' && $table_name == 'leaddetails')
			  {
				  updateLeadGroupRelation($this->id,'');
			  }
			  elseif($module == 'Accounts' && $table_name == 'account')
			  {
				  updateAccountGroupRelation($this->id,'');
			  }
			  elseif($module == 'Contacts' && $table_name == 'contactdetails')
			  {
				  updateContactGroupRelation($this->id,'');
			  }
			  elseif($module == 'Potentials' && $table_name == 'potential')
			  {
				  updatePotentialGroupRelation($this->id,'');
			  }
			  elseif($module == 'Quotes' && $table_name == 'quotes')
			  {
				  updateQuoteGroupRelation($this->id,'');
			  }
			  elseif($module == 'SalesOrder' && $table_name == 'salesorder')
			  {
				  updateSoGroupRelation($this->id,'');
			  }
			  elseif($module == 'Invoice' && $table_name == 'invoice')
			  {
				  updateInvoiceGroupRelation($this->id,'');
			  }
			  elseif($module == 'PurchaseOrder' && $table_name == 'purchaseorder')
			  {
				  updatePoGroupRelation($this->id,'');
			  }
			  elseif($module == 'HelpDesk' && $table_name == 'troubletickets')
			  {
				  updateTicketGroupRelation($this->id,'');
			  }
			  elseif($module =='Activities' || $module =='Events' || $module == 'Emails')
			  {
				  if($table_name == 'activity')
                                  {
			             updateActivityGroupRelation($this->id,'');
				  }
			  }
			  	

		  }

	  }
	  else
	  {	
		  $sql1 = "insert into ".$table_name." (".$column.") values(".$value.")";
		  $adb->query($sql1); 
		  $groupname = $_REQUEST['assigned_group_name'];
		  if($_REQUEST['assigntype'] == 'T' && $table_name == 'leaddetails')
		  {
			  insert2LeadGroupRelation($this->id,$groupname);
		  }
		  elseif($_REQUEST['assigntype'] == 'T' && $table_name == 'account')
		  {
			  insert2AccountGroupRelation($this->id,$groupname);
		  }
		  elseif($_REQUEST['assigntype'] == 'T' && $table_name == 'contactdetails')
		  {
			  insert2ContactGroupRelation($this->id,$groupname);
		  }
		  elseif($_REQUEST['assigntype'] == 'T' && $table_name == 'potential')
		  {
			  insert2PotentialGroupRelation($this->id,$groupname);
		  }
		  elseif($_REQUEST['assigntype'] == 'T' && $table_name == 'quotes')
		  {
			  insert2QuoteGroupRelation($this->id,$groupname);
		  }
		  elseif($_REQUEST['assigntype'] == 'T' && $table_name == 'salesorder')
		  {
			  insert2SoGroupRelation($this->id,$groupname);
		  }
		  elseif($_REQUEST['assigntype'] == 'T' && $table_name == 'invoice')
		  {
			  insert2InvoiceGroupRelation($this->id,$groupname);
		  }
		  elseif($_REQUEST['assigntype'] == 'T' && $table_name == 'purchaseorder')
		  {
			  insert2PoGroupRelation($this->id,$groupname);
		  }
		  elseif($_REQUEST['assigntype'] == 'T' && $table_name == 'activity') 
		  {
			  insert2ActivityGroupRelation($this->id,$groupname);
		  }
		  elseif($_REQUEST['assigntype'] == 'T' && $table_name == 'troubletickets') 
		  {
			  insert2TicketGroupRelation($this->id,$groupname);
		  }

	  }

  }
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
function getOldFileName($notesid)
{
	   global $log;
$log->info("in getOldFileName  ".$notesid);
	global $adb;
	$query1 = "select * from seattachmentsrel where crmid=".$notesid;
	$result = $adb->query($query1);
	$noofrows = $adb->num_rows($result);
	if($noofrows != 0)
		$attachmentid = $adb->query_result($result,0,'attachmentsid');
	if($attachmentid != '')
	{
		$query2 = "select * from attachments where attachmentsid=".$attachmentid;
		$filename = $adb->query_result($adb->query($query2),0,'name');
	}
	return "'".$filename."'";
}
function insertIntoTicketCommentTable($table_name, $module)
{
	global $log;
	$log->info("in insertIntoTicketCommentTable  ".$table_name."    module is  ".$module);
        global $adb;
	global $current_user;

        $current_time = date('Y-m-d H:i:s');
	if($_REQUEST['assigned_user_id'] != '')
		$ownertype = 'user';
	else
		$ownertype = 'customer';

	if($_REQUEST['comments'] != '')
	{
		$comment = addslashes($_REQUEST['comments']);
		$sql = "insert into ticketcomments values('',".$this->id.",'".$comment."','".$current_user->id."','".$ownertype."','".$current_time."')";
	        $adb->query($sql);
	}
}
function insertIntoFAQCommentTable($table_name, $module)
{
	 global $log;
	$log->info("in insertIntoFAQCommentTable  ".$table_name."    module is  ".$module);
        global $adb;

        $current_time = date('Y-m-d H:i:s');

	if($_REQUEST['comments'] != '')
	{
		$comment = addslashes($_REQUEST['comments']);
		$sql = "insert into faqcomments values('',".$this->id.",'".$comment."','".$current_time."')";
		$adb->query($sql);
	}
}
function insertIntoReminderTable($table_name,$module,$recurid)
{
	 global $log;
$log->info("in insertIntoReminderTable  ".$table_name."    module is  ".$module);
	if($_REQUEST['set_reminder'] == 'Yes')
	{
$log->debug("set reminder is set");
		$rem_days = $_REQUEST['remdays'];
$log->debug("rem_days is ".$rem_days);
		$rem_hrs = $_REQUEST['remhrs'];
$log->debug("rem_hrs is ".$rem_hrs);
		$rem_min = $_REQUEST['remmin'];
$log->debug("rem_minutes is ".$rem_min);
		$reminder_time = $rem_days * 24 * 60 + $rem_hrs * 60 + $rem_min;
$log->debug("reminder_time is ".$reminder_time);
		if ($recurid == "")
		{
			if($_REQUEST['mode'] == 'edit')
			{
				$this->activity_reminder($this->id,$reminder_time,0,$recurid,'edit');
			}
			else
			{
				$this->activity_reminder($this->id,$reminder_time,0,$recurid,'');
			}
		}
		else
		{
			$this->activity_reminder($this->id,$reminder_time,0,$recurid,'');
		}
	}
	elseif($_REQUEST['set_reminder'] == 'No')
	{
		$this->activity_reminder($this->id,'0',0,$recurid,'delete');
	}
}

// Code included by Jaguar - starts 
function insertIntoRecurringTable($table_name,$module)
{
	global $log;
$log->info("in insertIntoRecurringTable  ".$table_name."    module is  ".$module);
        global $adb;
        $st_date = getDBInsertDateValue($_REQUEST["date_start"]);
$log->debug("st_date ".$st_date);
        $end_date = getDBInsertDateValue($_REQUEST["due_date"]);
$log->debug("end_date is set ".$end_date);
        $st=explode("-",$st_date);
$log->debug("exploding string is ".$st);
        $end=explode("-",$end_date);
$log->debug("exploding string again is ".$end);
        $type = trim($_REQUEST['recurringtype']);
$log->debug("type is ".$type);
        $flag="true";

	if($_REQUEST['mode'] == 'edit')
	{
		$activity_id=$this->id;

		$sql='select min(recurringdate) min_date,max(recurringdate) max_date,recurringtype from recurringevents where activityid='. $activity_id.' group by activityid';
		
		$result = $adb->query($sql);
		$noofrows = $adb->num_rows($result);
		for($i=0; $i<$noofrows; $i++)
		{
			$recur_type_b4_edit = $adb->query_result($result,$i,"recurringtype");
			$date_start_b4edit = $adb->query_result($result,$i,"min_date");
			$end_date_b4edit = $adb->query_result($result,$i,"max_date");
		}
		if(($st_date == $date_start_b4edit) && ($end_date==$end_date_b4edit) && ($type == $recur_type_b4_edit))
		{
			if($_REQUEST['set_reminder'] == 'Yes')
			{
				$sql = 'delete from activity_reminder where activity_id='.$activity_id;
				$adb->query($sql);
				$sql = 'delete  from recurringevents where activityid='.$activity_id;
				$adb->query($sql);
				$flag="true";
			}
			elseif($_REQUEST['set_reminder'] == 'No')
			{
				$sql = 'delete  from activity_reminder where activity_id='.$activity_id;
				$adb->query($sql);
				$flag="false";
			}
			else
				$flag="false";
		}
		else
		{
			$sql = 'delete from activity_reminder where activity_id='.$activity_id;
			$adb->query($sql);
			$sql = 'delete  from recurringevents where activityid='.$activity_id;
			$adb->query($sql);
		}
	}
	if($flag=="true")
	{
		$date_val=$st_date;
		$date_array[]=$st_date;
		if($type !=  "--None--")
		{
			while($date_val <= $end_date)
			{
				if($type == 'Daily')
				{
					$date_val = date("Y-m-d",mktime(0,0,0,date("$st[1]"),(date("$st[2]")+(1)),date("$st[0]")));
				}
				elseif($type == 'Weekly')
				{
					$date_val = date("Y-m-d",mktime(0,0,0,date("$st[1]"),(date("$st[2]")+(7)),date("$st[0]")));
				}
				elseif($type == 'Monthly' )
				{
					$date_val = date("Y-m-d",mktime(0,0,0,(date("$st[1]")+1),date("$st[2]"),date("$st[0]")));
				}
				elseif($type == 'Yearly')
				{
					$date_val = date("Y-m-d",mktime(0,0,0,date("$st[1]"),date("$st[2]"),(date("$st[0]")+1)));
				}
				$date_array[]=$date_val;
				$st=explode("-",$date_val);
			}
			for($k=0; $k< count($date_array); $k++)
			{
				$tdate=$date_array[$k];
				if($tdate <= $end_date)
				{
					$max_recurid_qry = 'select max(recurringid) recurid  from recurringevents;';
					$result = $adb->query($max_recurid_qry);
					$noofrows = $adb->num_rows($result);
					for($i=0; $i<$noofrows; $i++)
					{
						$recur_id = $adb->query_result($result,$i,"recurid");
					}
					$current_id =$recur_id+1;
					$recurring_insert = 'insert into recurringevents values ("'.$current_id.'","'.$this->id.'","'.$tdate.'","'.$type.'")';
					$adb->query($recurring_insert);
					if($_REQUEST['set_reminder'] == 'Yes')
					{
						$this->insertIntoReminderTable("activity_reminder",$module,$current_id,'');
					}
				}
			}
		}
	}
}

// Code included by Jaguar - Ends 

	
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

  function save($module_name) 
  {
	  global $log;
        $log->debug("module name is ".$module_name);
    //GS Save entity being called with the modulename as parameter
      $this->saveentity($module_name,$migration);
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
		$query = "UPDATE crmentity set deleted=1 where crmid='$id'";
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
         * Function to check if the custom field table exists
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
	 * function to construct the query to fetch the custom fields
	 * return the query to fetch the custom fields
         */
        function constructCustomQueryAddendum($tablename,$module)
        {
                global $adb;
		$tabid=getTabid($module);		
                $sql1 = "select columnname,fieldlabel from field where generatedtype=2 and tabid=".$tabid;
                $result = $adb->query($sql1);
                $numRows = $adb->num_rows($result);
                $sql3 = "select ";
                for($i=0; $i < $numRows;$i++)
                {
                        $columnName = $adb->query_result($result,$i,"columnname");
                        $fieldlable = $adb->query_result($result,$i,"fieldlabel");
                        //construct query as below
                        if($i == 0)
                        {
                                $sql3 .= $tablename.".".$columnName. " '" .$fieldlable."'";
                        }
                        else
                        {
                                $sql3 .= ", ".$tablename.".".$columnName. " '" .$fieldlable."'";
                        }

                }
                if($numRows>0)
                {
                        $sql3=$sql3.',';
                }
                return $sql3;

        }	

}
?>
