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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Emails/EditView.php,v 1.25 2005/04/18 10:37:49 samk Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once('data/Tracker.php');
require_once('modules/Emails/Emails.php');
require_once('include/utils/utils.php');
require_once('include/utils/UserInfoUtil.php');
require_once('include/FormValidationUtil.php');

global $log;
global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;
global $currentModule;

$focus = new Emails();
$smarty = new vtigerCRM_Smarty();

if($_REQUEST['upload_error'] == true)
{
        echo '<br><b><font color="red"> The selected file has no data or a invalid file.</font></b><br>';
}

//Email Error handling
if($_REQUEST['mail_error'] != '') 
{
	require_once("modules/Emails/mail.php");
	echo parseEmailErrorString($_REQUEST['mail_error']);
}
//added to select the module in combobox of compose-popup
if(isset($_REQUEST['par_module']) && $_REQUEST['par_module']!=''){
	$smarty->assign('select_module',$_REQUEST['par_module']);
}
elseif(isset($_REQUEST['pmodule']) && $_REQUEST['pmodule']!='') {
	$smarty->assign('select_module',$_REQUEST['pmodule']);	
}

if(isset($_REQUEST['record']) && $_REQUEST['record'] !='') 
{
	$focus->id = $_REQUEST['record'];
	$focus->mode = 'edit';
	$focus->retrieve_entity_info($_REQUEST['record'],"Emails");
	if(isset($_REQUEST['forward']) && $_REQUEST['forward'] != '')
	{
		$focus->mode = '';
	}
	else
	{
		$query = 'select idlists,from_email,to_email,cc_email,bcc_email from vtiger_emaildetails where emailid ='.$focus->id;
		$result = $adb->query($query);
		$smarty->assign('FROM_MAIL',$adb->query_result($result,0,'from_email'));	
		$to_email = ereg_replace('###',',',$adb->query_result($result,0,'to_email'));
		$smarty->assign('TO_MAIL',$to_email);	
		$smarty->assign('CC_MAIL',ereg_replace('###',',',$adb->query_result($result,0,'cc_email')));	
		$smarty->assign('BCC_MAIL',ereg_replace('###',',',$adb->query_result($result,0,'bcc_email')));	
		$smarty->assign('IDLISTS',ereg_replace('###',',',$adb->query_result($result,0,'idlists')));	
	}
    $log->info("Entity info successfully retrieved for EditView.");
	$focus->name=$focus->column_fields['name'];		
}
elseif(isset($_REQUEST['sendmail']) && $_REQUEST['sendmail'] !='')
{
	$mailids = get_to_emailids($_REQUEST['pmodule']);
	$smarty->assign('TO_MAIL',$mailids['mailds']);
	$smarty->assign('IDLISTS',$mailids['idlists']);	
	$focus->mode = '';
}

// INTERNAL MAILER
if($_REQUEST["internal_mailer"] == "true") {
	$smarty->assign('INT_MAILER',"true");
	$rec_type = $_REQUEST["type"];
	$rec_id = $_REQUEST["rec_id"];
	
	//added for getting list-ids to compose email popup from list view(Accounts,Contacts,Leads)
	if(isset($_REQUEST['field_id']) && strlen($_REQUEST['field_id']) != 0) {
	     if($_REQUEST['par_module'] == "Users")
		$id_list = $_REQUEST['rec_id'].'@'.'-1|';
	     else
                $id_list = $_REQUEST['rec_id'].'@'.$_REQUEST['field_id'].'|';
             $smarty->assign("IDLISTS", $id_list);
        }
	if($rec_type == "record_id") {
		$rs = $adb->query("select setype from vtiger_crmentity where crmid='".$rec_id."'");
		$type = $adb->query_result($rs,0,'setype');
		//check added for email link in user detail view
		if($_REQUEST['par_module'] == "Users")
			$q = "select email1,email2 from vtiger_users where id='".$rec_id."'";	
		elseif($type == "Leads") 
			$q = "select email as email1 from vtiger_leaddetails where leadid='".$rec_id."'";
		elseif ($type == "Contacts")
			$q = "select email as email1 from vtiger_contactdetails where contactid='".$rec_id."'";
		elseif ($type == "Accounts")
			$q = "select email1,email2 from vtiger_account where accountid='".$rec_id."'";
		elseif ($type == "Vendors")
			$q = "select email as email1 from vtiger_vendor where vendorid='".$rec_id."'";
		$email1 = $adb->query_result($adb->query($q),0,"email1");
	} elseif ($rec_type == "email_addy") {
		$email1 = $_REQUEST["email_addy"];
	}

	$smarty->assign('TO_MAIL',$email1);
	//$smarty->assign('BCC_MAIL',$current_user->email1);
}

//handled for replying emails
if($_REQUEST['reply'] == "true")
{
		$fromadd = $_REQUEST['record'];	
		$query = "select from_email,idlists,cc_email,bcc_email from vtiger_emaildetails where emailid =$fromadd";
		$result = $adb->query($query);
		$from_mail = $adb->query_result($result,0,'from_email');	
		$smarty->assign('TO_MAIL',$from_mail.';');
		$smarty->assign('CC_MAIL',ereg_replace('###',',',$adb->query_result($result,0,'cc_email')));	
		$smarty->assign('BCC_MAIL',ereg_replace('###',',',$adb->query_result($result,0,'bcc_email')));	
		$smarty->assign('IDLISTS',ereg_replace('###',',',$adb->query_result($result,0,'idlists')));	
}


//Added to set the cc when click reply all
if(isset($_REQUEST['msg_cc']) && $_REQUEST['msg_cc'] != '')
{
        $smarty->assign("MAIL_MSG_CC", $_REQUEST['msg_cc']);
}

// Webmails
if(isset($_REQUEST["mailid"]) && $_REQUEST["mailid"] != "") {
	$mailid = $_REQUEST["mailid"];
	$mailbox = $_REQUEST["mailbox"];
	require_once('include/utils/UserInfoUtil.php');
	require_once("modules/Webmails/Webmails.php");
	require_once("modules/Webmails/MailParse.php");
	require_once('modules/Webmails/MailBox.php');

	$mailInfo = getMailServerInfo($current_user);
	$temprow = $adb->fetch_array($mailInfo);

	$MailBox = new MailBox($mailbox);
	$mbox = $MailBox->mbox;

	$webmail = new Webmails($mbox,$mailid);
	$array_tab = Array();
	$webmail->loadMail($array_tab);
	  $hdr = @imap_headerinfo($mbox, $mailid);
	$smarty->assign('WEBMAIL',"true");
	$temp_id = $MailBox->boxinfo['mail_id'];
	$smarty->assign('from_add',$temp_id);
	if($_REQUEST["reply"] == "all") {
		$smarty->assign('TO_MAIL',$webmail->from);	
		//added to remove the emailid of webmail client from cc list....to fix the issue #3818
                $cc_address = '';
                $cc_array = explode(',',$webmail->to[0].','.$hdr->ccaddress);
                for($i=0;$i<count($cc_array);$i++) {
                        if(trim($cc_array[$i]) != trim($temp_id)) {
                                $cc_address .= $cc_array[$i];
                                $cc_address = ($i != (count($cc_array)-1))?($cc_address.','):$cc_address;
                        }
                }
		$smarty->assign('CC_MAIL',str_replace(" ","",$cc_address));
		// fix #3818 ends
		/*if(is_array($webmail->cc_list))
		{
			$smarty->assign('CC_MAIL',implode(",",$webmail->cc_list).",".implode(",",$webmail->to));
		}
		else
		{
			//Commenting this to fix #3231
		//	$smarty->assign('CC_MAIL',implode(",",$webmail->to));
		}*/
		if(preg_match("/RE:/i", $webmail->subject))
			$smarty->assign('SUBJECT',$webmail->subject);
		else
			$smarty->assign('SUBJECT',"RE: ".$webmail->subject);

	} elseif($_REQUEST["reply"] == "single"){
		$smarty->assign('TO_MAIL',$webmail->reply_to[0]);	
		//$smarty->assign('BCC_MAIL',$webmail->to[0]);
		if(preg_match("/RE:/i", $webmail->subject))
			$smarty->assign('SUBJECT',$webmail->subject);
		else
			$smarty->assign('SUBJECT',"RE: ".$webmail->subject);

	} elseif($_REQUEST["forward"] == "true" ) {
		//$smarty->assign('TO_MAIL',$webmail->reply_to[0]);	
		//$smarty->assign('BCC_MAIL',$webmail->to[0]);
		if(preg_match("/FW:/i", $webmail->subject))
			$smarty->assign('SUBJECT',$webmail->subject);
		else
			$smarty->assign('SUBJECT',"FW: ".$webmail->subject);
	} 
	$smarty->assign('DESCRIPTION',$webmail->replyBody());
	$focus->mode='';
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$disp_view = getView($focus->mode);
$details = getBlocks($currentModule,$disp_view,$mode,$focus->column_fields);
//changed this below line to view description in all language - bharath
$smarty->assign("BLOCKS",$details[$mod_strings['LBL_EMAIL_INFORMATION']]); 
$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD",$app_strings['Email']);

//needed when creating a new email with default values passed in
if (isset($_REQUEST['contact_name']) && is_null($focus->contact_name)) 
{
	$focus->contact_name = $_REQUEST['contact_name'];
}
if (isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) 
{
	$focus->contact_id = $_REQUEST['contact_id'];
}
if (isset($_REQUEST['parent_name']) && is_null($focus->parent_name)) 
{
	$focus->parent_name = $_REQUEST['parent_name'];
}
if (isset($_REQUEST['parent_id']) && is_null($focus->parent_id)) 
{
	$focus->parent_id = $_REQUEST['parent_id'];
}
if (isset($_REQUEST['parent_type'])) 
{
	$focus->parent_type = $_REQUEST['parent_type'];
}
if (isset($_REQUEST['filename']) && $_REQUEST['isDuplicate'] != 'true') 
{
        $focus->filename = $_REQUEST['filename'];
}
elseif (is_null($focus->parent_type)) 
{
	$focus->parent_type = $app_list_strings['record_type_default_key'];
}

$log->info("Email detail view");

$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
else $smarty->assign("NAME", "");


if($focus->mode == 'edit')
{
	$smarty->assign("UPDATEINFO",updateInfo($focus->id));
        $smarty->assign("MODE", $focus->mode);
}

// Unimplemented until jscalendar language vtiger_files are fixed

$smarty->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
$smarty->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));

if(isset($_REQUEST['return_module'])) $smarty->assign("RETURN_MODULE", $_REQUEST['return_module']);
else $smarty->assign("RETURN_MODULE",'Emails');
if(isset($_REQUEST['return_action'])) $smarty->assign("RETURN_ACTION", $_REQUEST['return_action']);
else $smarty->assign("RETURN_ACTION",'index');
if(isset($_REQUEST['return_id'])) $smarty->assign("RETURN_ID", $_REQUEST['return_id']);
if (isset($_REQUEST['return_viewname'])) $smarty->assign("RETURN_VIEWNAME", $_REQUEST['return_viewname']);


$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("ID", $focus->id);
$smarty->assign("ENTITY_ID", $_REQUEST["record"]);
$smarty->assign("ENTITY_TYPE",$_REQUEST["email_directing_module"]);
$smarty->assign("OLD_ID", $old_id );
//Display the FCKEditor or not? -- configure $FCKEDITOR_DISPLAY in config.php 
$smarty->assign("FCKEDITOR_DISPLAY",$FCKEDITOR_DISPLAY);

if(empty($focus->filename))
{
        $smarty->assign("FILENAME_TEXT", "");
        $smarty->assign("FILENAME", "");
}
else
{
        $smarty->assign("FILENAME_TEXT", "(".$focus->filename.")");
        $smarty->assign("FILENAME", $focus->filename);
}
if($ret_error == 1) {
	require_once('modules/Webmails/MailBox.php');
	$smarty->assign("RET_ERROR",$ret_error);
	if($ret_parentid != '')
		$smarty->assign("IDLISTS",$ret_parentid);
	if($ret_toadd != '')
                $smarty->assign("TO_MAIL",$ret_toadd);
	$ret_toadd = '';
	if($ret_subject != '')
		$smarty->assign("SUBJECT",$ret_subject);
	if($ret_ccaddress != '')
        	$smarty->assign("CC_MAIL",$ret_ccaddress);
	if($ret_bccaddress != '')
        	$smarty->assign("BCC_MAIL",$ret_bccaddress);
	if($ret_description != '')
        	$smarty->assign("DESCRIPTION", $ret_description);
	$temp_obj = new MailBox($mailbox);
	$temp_id = $temp_obj->boxinfo['mail_id'];
	if($temp_id != '')
		$smarty->assign('from_add',$temp_id);
}
$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);

$smarty->display("ComposeEmail.tpl");
?>

