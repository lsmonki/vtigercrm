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
 * Contributor(s): ______________________________________..
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Notes/Save.php,v 1.7 2005/04/18 10:37:49 samk Exp $
 * Description:  Saves an Account record and then redirects the browser to the
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Notes/Note.php');
require_once('include/logging.php');
require_once('include/upload_file.php');

$local_log =& LoggerManager::getLogger('index');

$focus = new Note();
if(isset($_REQUEST['record']))
{
        $focus->id = $_REQUEST['record'];
}
if(isset($_REQUEST['mode']))
{
        $focus->mode = $_REQUEST['mode'];
}


//Check if the file is exist or not.
if($_FILES["filename"]["size"] == 0 && $_FILES["filename"]["name"] != '')
{
	$file_upload_error = true;
	$_FILES = '';
}


//Added for retrieve the old existing attachments when duplicated without new attachment
if($_FILES['filename']['name'] == '' && $_REQUEST['mode'] != 'edit' && $_REQUEST['old_id'] != '')
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
*/
if (!isset($_REQUEST['date_due_flag'])) $focus->date_due_flag = 'off';

//Commented for avoid to save the old attachments using the include/upload_file.php methods
/*
$upload_file = new UploadFile('uploadfile');

$do_final_move = 0;

if ($upload_file->confirm_upload()) 
{

        if (!empty($focus->id) && !empty($_REQUEST['old_filename']) )
	{
                $upload_file->unlink_file($focus->id,$_REQUEST['old_filename']);
        }

        $focus->filename = $upload_file->get_stored_file_name();

	$do_final_move = 1;
}
else
{
        $focus->filename = $_REQUEST['old_filename'];
}
*/

//$focus->saveentity("Notes");
$focus->save("Notes");

//Added for update the existing attachments when duplicated without new attachment
if($attachmentid != '')
{
	$sql = "select attachmentsid from seattachmentsrel where crmid=".$focus->id;
	$attachmentid = $adb->query_result($adb->query($sql),0,'attachmentsid');
	$result = $adb->updateBlob('attachments','attachmentcontents',"attachmentsid='".$attachmentid."' and name='".$filename."'",$data);
}

//Commented for avoid to save the old attachments using the include/upload_file.php methods
/*
if ($do_final_move)
{
	$upload_file->final_move($focus->id);
}
else if ( ! empty($_REQUEST['old_id']))
{
	$upload_file->duplicate_file($_REQUEST['old_id'], $focus->id, $focus->filename);
}
*/
$return_id = $focus->id;
$note_id = $return_id;

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Notes";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

if($_REQUEST['mode'] != 'edit' && (($_REQUEST['return_module']=='Emails') ||($_REQUEST['return_module']=='HelpDesk') ))
{
	if($_REQUEST['email_id'] != '')
		$crmid = $_REQUEST['email_id'];
	if($_REQUEST['ticket_id'] != '')
		$crmid = $_REQUEST['ticket_id'];
	if($crmid != $_REQUEST['parent_id'])
	{
		$sql = "insert into senotesrel (notesid, crmid) values('".$focus->id."','".$crmid."')";
		$adb->query($sql);
	}
}

$local_log->debug("Saved record with id of ".$return_id);

//Redirect to EditView if the given file is not valid.

if($file_upload_error)
{
	$return_module = 'Notes';
	$return_action = 'EditView';
	$return_id = $note_id.'&upload_error=true&return_module='.$_REQUEST['return_module'].'&return_action='.$_REQUEST['return_action'].'&return_id='.$_REQUEST['return_id'];
}


header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
?>
