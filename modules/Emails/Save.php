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
 //check for mail server configuration thro ajax
if(isset($_REQUEST['server_check']) && $_REQUEST['server_check'] == 'true')
{
	$sql="select * from vtiger_systems where server_type = 'email'";
	$records=$adb->num_rows($adb->query($sql),0,"id");
	if($records != '')
		echo 'SUCESS';
	else
		echo 'FAILURE';	
	die;	
}

//Added on 09-11-2005 to avoid loading the webmail vtiger_files in Email process
if($_REQUEST['smodule'] != '')
{
	define('SM_PATH','modules/squirrelmail-1.4.4/');
	/* SquirrelMail required vtiger_files. */
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

require_once('modules/Emails/Emails.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');

$local_log =& LoggerManager::getLogger('index');

$focus = new Emails();

global $current_user,$mod_strings,$app_strings;
setObjectValuesFromRequest($focus);
//Check if the file is exist or not.
//$file_name = '';
$file_name = $_FILES['filename']['name'];//preg_replace('/\s+/', '_', $_FILES['filename']['name']);
$errorCode =  $_FILES['filename']['error'];
$errormessage = "";
if($file_name != '' && $_FILES['filename']['size'] == 0){
	if($errorCode == 4 || $errorCode == 0)
	{
		 if($_FILES['filename']['size'] == 0)
			 $errormessage = "<B><font color='red'>".$mod_strings['LBL_PLEASE_ATTACH']."</font></B> <br>";
	}
	else if($errorCode == 2)
	{
		  $errormessage = "<B><font color='red'>".$mod_strings['LBL_EXCEED_MAX'].$upload_maxsize.$mod_strings['LBL_BYTES']." </font></B> <br>";
	}
	else if($errorCode == 6)
	{
	     $errormessage = "<B>".$mod_strings['LBL_KINDLY_UPLOAD']."</B> <br>" ;
	}
	else if($errorCode == 3 )
	{
	     if($_FILES['filename']['size'] == 0)
		     $errormessage = "<b><font color='red'>".$mod_strings['LBL_PLEASE_ATTACH']."</font></b><br>";
	}
	else{}
	if($errormessage != ""){
		$ret_error = 1;
		$ret_parentid = $_REQUEST['parent_id'];
		$ret_toadd = $_REQUEST['parent_name'];
		$ret_subject = $_REQUEST['subject'];
		$ret_ccaddress = $_REQUEST['ccmail'];
		$ret_bccaddress = $_REQUEST['bccmail'];
		$ret_description = $_REQUEST['description'];
		echo $errormessage;
        	include("EditView.php");	
		exit();
	}
}
if(isset($_REQUEST['send_mail']) && $_REQUEST['send_mail']) {
	require_once("modules/Emails/mail.php");
	if($_REQUEST['parent_id'] == '')
		$user_mail_status = send_mail('Emails',$current_user->column_fields['email1'],$_REQUEST['from_add'],'',$_REQUEST['subject'],$_REQUEST['description'],'','','all',$focus->id);
	else
		$user_mail_status = send_mail('Emails',$current_user->column_fields['email1'],$current_user->user_name,'',$_REQUEST['subject'],$_REQUEST['description'],$_REQUEST['ccmail'],$_REQUEST['bccmail'],'all',$focus->id);
		
//if block added to fix the issue #3759
	if($user_mail_status != 1){
        	$error_msg = "<font color=red><strong>".$mod_strings['LBL_CHECK_USER_MAILID']."</strong></font>";
	        $ret_error = 1;
		$ret_parentid = $_REQUEST['parent_id'];
	        $ret_toadd = $_REQUEST['parent_name'];
        	$ret_subject = $_REQUEST['subject'];
	        $ret_ccaddress = $_REQUEST['ccmail'];
        	$ret_bccaddress = $_REQUEST['bccmail'];
	        $ret_description = $_REQUEST['description'];
        	echo $error_msg;
	        include("EditView.php");
        	exit();
	}

}
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
/*if(isset($_REQUEST['fromemail']) && $_REQUEST['fromemail'] != null)
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
}*/

/**	Function to check whether the contact is exist of not
 *	input  : contact id
 *	return : contact id if contact exist, else -1 will be return
 */
function checkIfContactExists($mailid)
{
	global $log;
	$log->debug("Entering checkIfContactExists(".$mailid.") method ...");
	global $adb;
	$sql = "select contactid from vtiger_contactdetails inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_contactdetails.contactid where vtiger_crmentity.deleted=0 and email= ".$adb->quote($mailid);
	$result = $adb->query($sql);
	$numRows = $adb->num_rows($result);
	if($numRows > 0)
	{
		$log->debug("Exiting checkIfContactExists method ...");
		return $adb->query_result($result,0,"contactid");
	}
	else
	{
		$log->debug("Exiting checkIfContactExists method ...");
		return -1;
	}
}
//assign the focus values
$focus->filename = $_REQUEST['file_name'];
$focus->parent_id = $_REQUEST['parent_id'];
$focus->parent_type = $_REQUEST['parent_type'];
$focus->column_fields["assigned_user_id"]=$current_user->id;
$focus->column_fields["activitytype"]="Emails";
$focus->column_fields["date_start"]= date(getNewDisplayDate());//This will be converted to db date format in save
$focus->save("Emails");

//saving the email details in vtiger_emaildetails vtiger_table
$qry = 'select email1 from vtiger_users where id = '.$current_user->id;
$res = $adb->query($qry);
$user_email = $adb->query_result($res,0,"email1");
$return_id = $focus->id;
$email_id = $return_id;
$query = 'select emailid from vtiger_emaildetails where emailid ='.$email_id;
$result = $adb->query($query);
if(isset($_REQUEST["hidden_toid"]) && $_REQUEST["hidden_toid"]!='')
	$all_to_ids = ereg_replace(",","###",$_REQUEST["hidden_toid"]);
if(isset($_REQUEST["saved_toid"]) && $_REQUEST["saved_toid"]!='')
	$all_to_ids .= ereg_replace(",","###",$_REQUEST["saved_toid"]);


//added to save < as $lt; and > as &gt; in the database so as to retrive the emailID
$all_to_ids = str_replace('<','&lt;',$all_to_ids);
$all_to_ids = str_replace('>','&gt;',$all_to_ids);
	
$all_cc_ids = ereg_replace(",","###",$_REQUEST["ccmail"]);
$all_bcc_ids = ereg_replace(",","###",$_REQUEST["bccmail"]);
$userid = $current_user->id;
if($adb->num_rows($result) > 0)
{
	$query = 'update vtiger_emaildetails set to_email="'.$all_to_ids.'",cc_email="'.$all_cc_ids.'",bcc_email="'.$all_bcc_ids.'",idlists="'.$_REQUEST["parent_id"].'",email_flag="SAVED" where emailid = '.$email_id;
}else
{
	$query = 'insert into vtiger_emaildetails values ('.$email_id.',"'.$user_email.'","'.$all_to_ids.'","'.$all_cc_ids.'","'.$all_bcc_ids.'","","'.$_REQUEST["parent_id"].'","SAVED")';
}
$adb->query($query);


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

if(isset($_REQUEST['send_mail']) && $_REQUEST['send_mail'] && $_REQUEST['parent_id'] == ''){
	if($_REQUEST["parent_name"] != '' && isset($_REQUEST["parent_name"])) {
		include("modules/Emails/webmailsend.php");
	}

} elseif( isset($_REQUEST['send_mail']) && $_REQUEST['send_mail'])
	include("modules/Emails/mailsend.php");



if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] == 'mailbox')
	header("Location: index.php?module=$return_module&action=index");
else {
	if($_REQUEST['return_viewname'] == '') $return_viewname='0';
	if($_REQUEST['return_viewname'] != '')$return_viewname=$_REQUEST['return_viewname'];
	$inputs="<script>window.opener.location.href=window.opener.location.href;window.self.close();</script>";
	echo $inputs;
}
?>
