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
 * $Header: /advent/projects/wesat/vtiger_crm/vtigercrm/data/CRMEntity.php,v 1.16 2005/04/29 04:21:31 rajeshkannan Exp $
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
    global $current_user;
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
      elseif($table_name == "seticketsrel" || $table_name == "seactivityrel" || $table_name ==  "seproductsrel" || $table_name ==  "senotesrel" || $table_name == "sefaqrel")
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
      elseif($table_name ==  "ticketcomments" && $_REQUEST['comments'] != '')
      {
                $this->insertIntoTicketCommentTable($table_name, $module);
      }
      elseif($table_name ==  "faqcomments" && $_REQUEST['comments'] != '')
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
    if($module == 'Emails' || $module == 'Notes')
      if(isset($_FILES['filename']['name']) && $_FILES['filename']['name']!='')
        $this->insertIntoAttachment($this->id,$module);

	$this->db->completeTransaction();
        $this->db->println("TRANS saveentity ends");
  }


  function insertIntoAttachment1($id,$module,$filedata,$filename,$filesize,$filetype,$user_id)
  {
    $date_var = date('YmdHis');
    // global $current_user;
    global $adb;
    //global $root_directory;
	global $vtlog;

    $ownerid = $user_id;
		

    if($filesize != 0)
    {
      $data = base64_encode(fread(fopen($filedata, "r"), $filesize));
    }
		
    $current_id = $adb->getUniqueID("crmentity");

    if($module=='Emails') 
    { 
$vtlog->logthis("module is ".$module,'info');  
      $idname='emailid';      $tablename='emails';    $descname='description';}
    else     
    { 
      $idname='notesid';      $tablename='notes';     $descname='notecontent';}
	$sql="update ".$tablename." set filename='".$filename."' where ".$idname."=".$id;
    $adb->query($sql);

	$sql1 = "insert into crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values(".$current_id.",".$current_user->id.",".$ownerid.",'".$module." Attachment','"."',".$adb->formatString("crmentity","createdtime",$date_var).",".$adb->formatString("crmentity","modifiedtime",$date_var).")";
    $adb->query($sql1);

    //$this->id = $current_id;

	$sql2="insert into attachments(attachmentsid, name, description, type, attachmentsize, attachmentcontents) values(".$current_id.",'".$filename."','"."','".$filetype."','".$filesize."','".$adb->getEmptyBlob()."')";
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
    global $upload_badext;

    $ownerid = $this->column_fields['assigned_user_id'];
    $adb->println("insertattach ownerid=".$ownerid." mod=".$module);
    $adb->println($this->column_fields);	

	if(!isset($ownerid) || $ownerid=='')            $ownerid = $current_user->id;
    $uploaddir = $root_directory ."/test/upload/" ;// set this to wherever
    
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

	$sql="update ".$tablename." set filename='".$filename."' where ".$idname."=".$id;
      $adb->query($sql);

	$sql1 = "insert into crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values(".$current_id.",".$current_user->id.",".$ownerid.",'".$module." Attachment','".$this->column_fields['description']."',".$adb->formatString("crmentity","createdtime",$date_var).",".$adb->formatString("crmentity","modifiedtime",$date_var).")";
      $adb->query($sql1);

      //$this->id = $current_id;
	$sql2="insert into attachments(attachmentsid, name, description, type, attachmentsize, attachmentcontents) values(".$current_id.",'".$filename."','".$this->column_fields[$descname]."','".$filetype."','".$filesize."',".$adb->getEmptyBlob().")";

      $result=$adb->query($sql2);

      if($result!=false)
        $result = $adb->updateBlob('attachments','attachmentcontents',"attachmentsid='".$current_id."' and name='".$filename."'",$data);

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
    global $vtlog;	
                
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
$vtlog->logthis("module is =".$module,'info');  
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
    }
    else
    {
      //if this is the create mode and the group allocation is chosen, then do the following
      $current_id = $adb->getUniqueID("crmentity");
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
    $old_severity = $adb->query_result($tktresult,0,"severity");
    $old_category = $adb->query_result($tktresult,0,"category");
    if($_REQUEST['old_smownerid'] != $old_user_id || $old_status != $this->column_fields['ticketstatus'] || $old_priority != $this->column_fields['ticketpriorities'] || $old_severity != $this->column_fields['ticketseverities'] || $old_category != $this->column_fields['ticketcategories'] || $old_userid == 0)
    {
      $updatelog .= date("l dS F Y h:i:s A").' by '.$current_user->user_name.'--//--';
    }	
    if($_REQUEST['old_smownerid'] != $old_user_id && $old_user_id != 0)
    {
      $user_name = getUserName($this->column_fields['assigned_user_id']);
      $updatelog .= ' Transferred to '.$user_name.'\.';
    }
    elseif($old_user_id == 0)
    {
	$group_name = getGroupName($ticketid,'HelpDesk');
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
    if($old_user_id != $this->column_fields['assigned_user_id'] || $old_status != $this->column_fields['ticketstatus'] || $old_priority != $this->column_fields['ticketpriorities'])
    {
      $updatelog .= '--//--';
    }
    return $updatelog;
  }
  //code added by shankar ends
  function insertIntoEntityTable($table_name, $module)
  {
	  global $vtlog;	
	  $vtlog->logthis("function insertIntoCrmEntity ".$module.' table name ' .$table_name,'info');  
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
			  //code by shankar starts
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
				  $fldvalue .= "--//--Ticket created. Assigned to ".$tkt_ownername."--//--";
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
			  //code by shankar ends
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
				  $sql = "insert into potstagehistory values('',".$this->id.",'".$_REQUEST['amount']."','".$sales_stage."','".$_REQUEST['probability']."',0,".$adb->formatString("potstagehistory","closedate",$_REQUEST['closingdate']).",".$adb->formatString("potstagehistory","lastmodified",$date_var).")";
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
			  elseif($module == 'HelpDesk' && $table_name == 'troubletickets')
			  {
				  updateTicketGroupRelation($this->id,$groupname);
			  }
			  elseif($module =='Activities' || $module =='Events'  )
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
			  elseif($module == 'HelpDesk' && $table_name == 'troubletickets')
			  {
				  updateTicketGroupRelation($this->id,'');
			  }
			  elseif($module =='Activities' || $module =='Events')
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
			  if($table_name == 'leaddetails')
			  {
				  insert2LeadGroupRelation($this->id,$groupname);
			  }
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
	global $vtlog;
$vtlog->logthis("in getOldFileName  ".$notesid,'info');  
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
	global $vtlog;
$vtlog->logthis("in insertIntoTicketCommentTable  ".$table_name."    module is  ".$module,'info');  
        global $adb;
	global $current_user;

        $current_time = date('Y-m-d H:i:s');
	if($_REQUEST['assigned_user_id'] != '')
		$ownertype = 'user';
	else
		$ownertype = 'customer';

	$comment = addslashes($_REQUEST['comments']);
	$sql = "insert into ticketcomments values('',".$this->id.",'".$comment."','".$current_user->id."','".$ownertype."','".$current_time."')";
        $adb->query($sql);
}
function insertIntoFAQCommentTable($table_name, $module)
{
	global $vtlog;
$vtlog->logthis("in insertIntoFAQCommentTable  ".$table_name."    module is  ".$module,'info');  
        global $adb;

        $current_time = date('Y-m-d H:i:s');

	$comment = addslashes($_REQUEST['comments']);
	$sql = "insert into faqcomments values('',".$this->id.",'".$comment."','".$current_time."')";
	$adb->query($sql);
}
function insertIntoReminderTable($table_name,$module,$recurid)
{
	global $vtlog;
$vtlog->logthis("in insertIntoReminderTable  ".$table_name."    module is  ".$module,'info');  
	if($_REQUEST['set_reminder'] == 'Yes')
	{
$vtlog->logthis("set reminder is set",'debug');  
		$rem_days = $_REQUEST['remdays'];
$vtlog->logthis("rem_days is ".$rem_days,'debug');  
		$rem_hrs = $_REQUEST['remhrs'];
$vtlog->logthis("rem_hrs is ".$rem_hrs,'debug');  
		$rem_min = $_REQUEST['remmin'];
$vtlog->logthis("rem_minutes is ".$rem_min,'debug');  
		$reminder_time = $rem_days * 24 * 60 + $rem_hrs * 60 + $rem_min;
$vtlog->logthis("reminder_time is ".$reminder_time,'debug');  
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
	global $vtlog;
$vtlog->logthis("in insertIntoRecurringTable  ".$table_name."    module is  ".$module,'info');  
	global $adb;
	$st_date = getDBInsertDateValue($_REQUEST["date_start"]);	
$vtlog->logthis("st_date ".$st_date,'debug');  
	$end_date = getDBInsertDateValue($_REQUEST["due_date"]);
$vtlog->logthis("end_date is set ".$end_date,'debug');  
	$st=explode("-",$st_date);
$vtlog->logthis("exploding string is ".$st,'debug');  
	$end=explode("-",$end_date);
$vtlog->logthis("exploding string again is ".$end,'debug');  
	$type = trim($_REQUEST['recurringtype']);
$vtlog->logthis("type is ".$type,'debug');  
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
	global $vtlog;
	$vtlog->logthis("module name is ".$module_name,'debug');  
    //GS Save entity being called with the modulename as parameter
      $this->saveentity($module_name,$migration);
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
				 
				 
				

				$list[] = $this;
			}
		}

		if (isset($list)) return $list;
		else return null;
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
