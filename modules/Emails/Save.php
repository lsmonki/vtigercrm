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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Emails/Save.php,v 1.25.2.1 2005/04/11 13:38:40 rank Exp $
 * Description:  Saves an Account record and then redirects the browser to the 
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Emails/Email.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
//require_once('send_mail.php');
$local_log =& LoggerManager::getLogger('index');

$focus = new Email();
if(isset($_REQUEST['record']))
{
        $focus->id = $_REQUEST['record'];
}
if(isset($_REQUEST['mode']))
{
        $focus->mode = $_REQUEST['mode'];
}

//Added for retrieve the old existing attachments when duplicated without new attachment
if($_FILES['filename']['name'] == '' && $_REQUEST['mode'] != 'edit')
{
	$sql = "select attachments.attachmentsid from attachments inner join seattachmentsrel on seattachmentsrel.attachmentsid=attachments.attachmentsid where seattachmentsrel.crmid= ".$_REQUEST['old_id'];

	$result = $adb->query($sql);
	if($adb->num_rows($result) != 0)
		$attachmentid = $adb->query_result($result,0,'attachmentsid');
	if($attachmentid != '')
	{
		$attachquery = "select * from attachments where attachmentsid = ".$attachmentid;
		$result = $adb->query($attachquery);
		$filename = $adb->query_result($result,0,'name');
		$filetype = $adb->query_result($result,0,'type');
		$filesize = $adb->query_result($result,0,'attachmentsize');
		$data = $adb->query_result($result,0,'attachmentcontents');
//		$_FILES["filename"]["tmp_name"] = basename($filename);
		$_FILES['filename']['name'] = $filename;
		$_FILES['filename']['type'] = $filetype;
		$_FILES['filename']['size'] = $filesize;
//		if(!@move_uploaded_file($_FILES["filename"]["tmp_name"],$uploaddir.$_FILES["filename"]["name"])){}
	}
}


//$focus->retrieve($_REQUEST['record']);
foreach($focus->column_fields as $fieldname => $val)
{
	if(isset($_REQUEST[$fieldname]))
	{
		$value = $_REQUEST[$fieldname];
		//$focus->$field = $value;
                //$local_log->debug("saving note: $field is $value");
		$focus->column_fields[$fieldname] = $value;
	}
}
/*
foreach($focus->additional_column_fields as $field)
{
	if(isset($_REQUEST[$field]))
	{
		$value = $_REQUEST[$field];
		$focus->$field = $value;
		
	}
}
if (!isset($_REQUEST['date_due_flag'])) $focus->date_due_flag = 'off';
*/
$focus->filename = $_REQUEST['file_name'];
$focus->parent_id = $_REQUEST['parent_id'];
$focus->parent_type = $_REQUEST['parent_type'];
$focus->column_fields["activitytype"]="Emails";
//echo 'file name : '.$_REQUEST['file_name'].'..'.$focus->filename;
//$focus->saveentity("Emails");
$focus->save("Emails");
$return_id = $focus->id;

//Added for update the existing attachments when duplicated without new attachment
if($attachmentid != '')
{
	$sql = "select attachmentsid from seattachmentsrel where crmid=".$focus->id;
	$attachmentid = $adb->query_result($adb->query($sql),0,'attachmentsid');
	$result = $adb->updateBlob('attachments','attachmentcontents',"attachmentsid='".$attachmentid."' and name='".$filename."'",$data);
}


$focus->retrieve_entity_info($return_id,"Emails");
//print_r($focus->column_fields);

//this is to receive the data from the Select Users button
//$_REQUEST['assigned_user_id']=$_REQUEST['user_id'];

//this will be the case if the Select Contact button is chosen
//if($_REQUEST['assigned_user_id'] == null)
//{
//   $_REQUEST['assigned_user_id']=$_REQUEST['entity_id'];
//}

//this is to receive the data from the Select Users button
if($_REQUEST['source_module'] == null)
{
	$module = 'users';
}
//this will be the case if the Select Contact button is chosen
else
{
	$module = $_REQUEST['source_module'];
}

$_REQUEST['filename']=$focus->column_fields['filename'];

//subject, contents
$_REQUEST['name'] = $focus->column_fields['name'];
$_REQUEST['description'] = $focus->column_fields['description'];


if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Emails";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];
if(isset($_REQUEST['filename']) && $_REQUEST['filename'] != "") $filename = $_REQUEST['filename'];

$local_log->debug("Saved record with id of ".$return_id);

$uploaddir = $root_directory ."/test/upload/" ;// set this to wherever

$binFile = $_FILES['filename']['name'];
$filename = basename($binFile);
$filetype= $_FILES['filename']['type'];
$filesize = $_FILES['filename']['attachmentsize'];

//echo 'In Save.php ==> file name,type,size : =>'.$filename.' .. '.$filetype.' .. '.$filesize;


if(move_uploaded_file($_FILES["filename"]["tmp_name"],$uploaddir.$_FILES["filename"]["name"])) 
{
  $binFile = $_FILES['filename']['name'];
  $filename = basename($binFile);
  $filetype= $_FILES['filename']['type'];
    $filesize = $_FILES['filename']['attachmentsize'];
    if($filesize != 0)	
    {
    $data = base64_encode(fread(fopen($uploaddir.$binFile, "r"), $filesize));
    $date_entered = date('YmdHis');
    //Retreiving the return module and setting the parent type
    $ret_module = $_REQUEST['return_module'];
    $parent_type;		
    if($_REQUEST['return_module'] == 'Leads')
    {
	    $parent_type = 'Lead';
    }
    elseif($_REQUEST['return_module'] == 'Accounts')
    {
	    $parent_type = 'Account';
    }
    elseif($_REQUEST['return_module'] == 'Contacts')
    {
	    $parent_type = 'Contact';
    }
    elseif($_REQUEST['return_module'] == 'Opportunities')
    {
	    $parent_type = 'Opportunity';
    }
    elseif($_REQUEST['return_module'] == 'Cases')
    {
	    $parent_type = 'Case';
    }
    elseif($_REQUEST['return_module'] == 'Emails')
    {
	    $parent_type = 'Emails';
    }		

    $parent_id = $_REQUEST['parent_id'];	 			

    $adb->println("attachment");

//     mysql_close();
     deleteFile($uploaddir,$filename);
//     header("Location: index.php?action=DetailView&module=$ret_module&record=$parent_id&filename=$filename");	
     }
    else
      {
    include('themes/'.$theme.'/header.php');
   $errormessage = "<font color='red'><B>Error Message<ul>
			<li><font color='red'>Invalid file OR</font>
			<li><font color='red'>File has no data</font>
			</ul></B></font> <br>" ;
   echo $errormessage;
       deleteFile($uploaddir,$filename);
   //include "upload.php";
     }			
   
} 
else 
{
  $errorCode =  $_FILES['uploadfile']['error'];
//  echo "Problems in file upload. Please try again! <br>" .$errorCode;
}

function deleteFile($dir,$filename)
{
   unlink($dir.$filename);	
}
$_REQUEST['return_id']=$return_id;
//echo 'return..'.$return_module.'/'.$return_action.'<br>parent id='.$parent_id.'<br>return id = '.$return_id.'/'.$filename;
if( isset($_REQUEST['send_mail']) && $_REQUEST['send_mail'])
	include("modules/Emails/send_mail.php");
else
	header("Location: index.php?action=$return_action&module=$return_module&parent_id=$parent_id&record=$return_id&filename=$filename");
?>
