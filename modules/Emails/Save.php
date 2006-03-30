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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Emails/Save.php,v 1.27 2005/04/29 08:54:38 rank Exp $
 * Description:  Saves an Account record and then redirects the browser to the 
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

//Added on 09-11-2005 to avoid loading the webmail files in Email process
if($_REQUEST['smodule'] != '')
{
	define('SM_PATH','modules/squirrelmail-1.4.4/');
	/* SquirrelMail required files. */
	require_once(SM_PATH . 'functions/strings.php');
	require_once(SM_PATH . 'functions/imap_general.php');
	require_once(SM_PATH . 'functions/imap_messages.php');
	require_once(SM_PATH . 'functions/i18n.php');
	require_once(SM_PATH . 'functions/mime.php');
	require_once(SM_PATH .'include/load_prefs.php');
	//require_once(SM_PATH . 'class/mime/Message.class.php');
	require_once(SM_PATH . 'class/mime.class.php');
	sqgetGlobalVar('key',       $key,           SQ_COOKIE);
	sqgetGlobalVar('username',  $username,      SQ_SESSION);
	sqgetGlobalVar('onetimepad',$onetimepad,    SQ_SESSION);
	$mailbox = 'INBOX';
}

require_once('modules/Emails/Email.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');

$local_log =& LoggerManager::getLogger('index');

$focus = new Email();

setObjectValuesFromRequest(&$focus);

//Check if the file is exist or not.
if($_FILES["filename"]["size"] == 0 && $_FILES["filename"]["name"] != '')
{
        $file_upload_error = true;
        $_FILES = '';
}

if((isset($_REQUEST['deletebox']) && $_REQUEST['deletebox'] != null) && $_REQUEST['addbox'] == null)
{
	imap_delete($mbox,$_REQUEST['deletebox']);
	imap_expunge($mbox);
	header("Location: index.php?module=Emails&action=index");
	exit();
}
if(isset($_REQUEST['fromemail']) && $_REQUEST['fromemail'] != null)
{
	//get the list of data from the comma separated array
	$emailids = explode(",",$_REQUEST['fromemail']);
	$subjects = explode(",",$_REQUEST['subject']);
	$ids = explode(",",$_REQUEST['idlist']);
	$total = count($ids);
	for($z=0;$z<$total;$z++)
	{
		$msgData='';
		global $current_user;
		require_once('include/utils/UserInfoUtil.php');
		$mailInfo = getMailServerInfo($current_user);
		$temprow = $adb->fetch_array($mailInfo);

		$secretkey=$temprow["mail_password"];
		$imapServerAddress=$temprow["mail_servername"];
		$imapPort="143";

		$key = OneTimePadEncrypt($secretkey, $onetimepad);
		$imapConnection = sqimap_login($username, $key, $imapServerAddress, $imapPort, 0);
		$mbx_response=sqimap_mailbox_select($imapConnection, $mailbox);

		$message = sqimap_get_message($imapConnection, $ids[$z], $mailbox);
		$header = $message->rfc822_header;
		$ent_ar = $message->findDisplayEntity(array(), array('text/plain'));
		$cnt = count($ent_ar);
		global $color;
		for ($u = 0; $u < $cnt; $u++)
		{
			$messagebody .= formatBody($imapConnection, $message, $color, $wrap_at, $ent_ar[$u], $ids[$z], $mailbox);
			$msgData = $messagebody;
		}

			$ctctExists = checkIfContactExists($emailids[$z]);
			if($ctctExists > 0)
			{
				$focus->column_fields['parent_id']=$ctctExists;
			}
			global $current_user;

			$focus->column_fields['subject']=$subjects[$z];
			$focus->column_fields["activitytype"]="Emails";
			//this line has been added to get the related list data in the contact description
			$focus->column_fields["assigned_user_id"]=$current_user->id;
			$focus->column_fields["date_start"]=$_REQUEST['adddate'];
			$focus->column_fields["time_start"]=$_REQUEST['adddate'];

			$focus->column_fields["description"]=$msgData;
			$focus->save("Emails");
			$return_id = $focus->id;
			$return_module='Emails';	
			$return_action='DetailView';	
	}
	header("Location: index.php?action=$return_action&module=$return_module&parent_id=$parent_id&record=$return_id&filename=$filename");
	return;
}

/**	Function to check whether the contact is exist of not
 *	input  : contact id
 *	return : contact id if contact exist, else -1 will be return
 */
function checkIfContactExists($mailid)
{
	global $adb;
	$sql = "select contactid from contactdetails inner join crmentity on crmentity.crmid=contactdetails.contactid where crmentity.deleted=0 and email= ".PearDatabase::quote($mailid);
	$result = $adb->query($sql);
	$numRows = $adb->num_rows($result);
	if($numRows > 0)
	{
		return $adb->query_result($result,0,"contactid");
	}
	else
	{
		return -1;
	}
}

$focus->filename = $_REQUEST['file_name'];
$focus->parent_id = $_REQUEST['parent_id'];
$focus->parent_type = $_REQUEST['parent_type'];
$focus->column_fields["activitytype"]="Emails";
$focus->save("Emails");

$return_id = $focus->id;
$email_id = $return_id;

$focus->retrieve_entity_info($return_id,"Emails");

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

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Emails";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];
if(isset($_REQUEST['filename']) && $_REQUEST['filename'] != "") $filename = $_REQUEST['filename'];

$local_log->debug("Saved record with id of ".$return_id);

if($file_upload_error)
{
        $return_module = 'Emails';
        $return_action = 'EditView';
        $return_id = $email_id.'&upload_error=true&return_module='.$_REQUEST['return_module'].'&return_action='.$_REQUEST['return_action'].'&return_id='.$_REQUEST['return_id'];
	header("Location: index.php?action=$return_action&module=$return_module&parent_id=$parent_id&record=$return_id&filename=$filename");
}
elseif( isset($_REQUEST['send_mail']) && $_REQUEST['send_mail'])
{
	include("modules/Emails/mailsend.php");
}
elseif(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] == 'mailbox')
{
	header("Location: index.php?module=$return_module&action=index");
}
else
{
	//code added for returning back to the current view after edit from list view
	if($_REQUEST['return_viewname'] == '') $return_viewname='0';
	if($_REQUEST['return_viewname'] != '')$return_viewname=$_REQUEST['return_viewname'];
	header("Location: index.php?action=$return_action&module=$return_module&parent_id=$parent_id&record=$return_id&filename=$filename&viewname=$return_viewname");
}
?>
