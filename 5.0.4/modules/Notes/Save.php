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

require_once('modules/Notes/Notes.php');
require_once('include/logging.php');
require_once('include/upload_file.php');

$local_log =& LoggerManager::getLogger('index');

$focus = new Notes();

setObjectValuesFromRequest($focus);

//Check if the file is exist or not.
if($_FILES["filename"]["size"] == 0 && $_FILES["filename"]["name"] != '')
{
	$file_upload_error = true;
	//$_FILES = '';
}

if (!isset($_REQUEST['date_due_flag'])) $focus->date_due_flag = 'off';

//Save the Note
$focus->save("Notes");

//Added to retrieve the existing attachment of the notes and save it for the new duplicated note
if($_FILES['filename']['name'] == '' && $_REQUEST['mode'] != 'edit' && $_REQUEST['old_id'] != '')
{
        $sql = "select vtiger_attachments.* from vtiger_attachments inner join vtiger_seattachmentsrel on vtiger_seattachmentsrel.attachmentsid=vtiger_attachments.attachmentsid where vtiger_seattachmentsrel.crmid= ".$_REQUEST['old_id'];
        $result = $adb->query($sql);
        if($adb->num_rows($result) != 0)
	{
                $attachmentid = $adb->query_result($result,0,'attachmentsid');
		$filename = $adb->query_result($result,0,'name');
		$filetype = $adb->query_result($result,0,'type');
		$filepath = $adb->query_result($result,0,'path');

		$new_attachmentid = $adb->getUniqueID("vtiger_crmentity");
		$date_var = $adb->formatDate(date('YmdHis'));	

		$upload_filepath = decideFilePath();

		//Read the old file contents and write it as a new file with new attachment id
		$handle = @fopen($upload_filepath.$new_attachmentid."_".$filename,'w');
		fputs($handle, file_get_contents($filepath.$attachmentid."_".$filename));
		fclose($handle);	

		$adb->query("update vtiger_notes set filename=\"$filename\" where notesid=$focus->id");	
		$adb->query("insert into vtiger_crmentity (crmid,setype,createdtime) values('".$new_attachmentid."','Notes Attachment',".$date_var.")");

		$adb->query("insert into vtiger_attachments values(".$new_attachmentid.",'".$filename."','','".$filetype."','".$upload_filepath."')");

		$adb->query("insert into vtiger_seattachmentsrel values('".$focus->id."','".$new_attachmentid."')");
	}
}


$return_id = $focus->id;
$note_id = $return_id;

if(isset($_REQUEST['parenttab']) && $_REQUEST['parenttab'] != "") $parenttab = $_REQUEST['parenttab'];
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Notes";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

// Notes added to Contacts should also update Accounts
// Added by DG 16 Nov 2005
if($_REQUEST['mode'] != 'edit' && ($_REQUEST['return_module']=='Contacts'))
{
	$crmid = $_REQUEST['return_id'];
	$noteid = $focus->id;
	$query = 'select accountid from vtiger_contactdetails where contactid='.$crmid;
	$result = $adb->query($query);
	if($adb->num_rows($result) != 0)
	{
		$associated_account = $adb->query_result($result,0,"accountid");
	}
	else
	{
		$associated_account = '';
	}
	if ($associated_account)
	{
		$sql1 = "insert into vtiger_senotesrel (notesid, crmid) values('";
		$sql1 .= $noteid."','".$associated_account."')";
		$result = $adb->query($sql1);
	}
}

if($_REQUEST['mode'] != 'edit' && (($_REQUEST['return_module']=='Emails') ||($_REQUEST['return_module']=='HelpDesk') ))
{
	if($_REQUEST['email_id'] != '')
		$crmid = $_REQUEST['email_id'];
	if($_REQUEST['ticket_id'] != '')
		$crmid = $_REQUEST['ticket_id'];
	if($crmid != $_REQUEST['parent_id'])
	{
		$sql = "insert into vtiger_senotesrel (notesid, crmid) values('".$focus->id."','".$crmid."')";
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

//code added for returning back to the current view after edit from list view
if($_REQUEST['return_viewname'] == '') $return_viewname='0';
if($_REQUEST['return_viewname'] != '')$return_viewname=$_REQUEST['return_viewname'];
header("Location: index.php?action=$return_action&module=$return_module&parenttab=$parenttab&record=$return_id&viewname=$return_viewname");
?>
