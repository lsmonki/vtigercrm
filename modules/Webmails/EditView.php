<?php
require_once('Smarty_setup.php');
require_once('data/Tracker.php');
require_once('modules/Emails/Email.php');
require_once('modules/Emails/Forms.php');
require_once('include/utils/utils.php');
require_once('include/FormValidationUtil.php');
require_once("include/fckeditor/fckeditor.php");

global $log;
global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;

$submenu = array('LBL_EMAILS_TITLE'=>'index.php?module=Webmails&action=index','LBL_WEBMAILS_TITLE'=>'index.php?module=Webmailsaction=index');

$sec_arr = array('index.php?module=Emails&action=index'=>'Emails ','index.php?module=Webmails&action=index'=>'Webmails'); 
echo '<br>';

$focus = new Email();
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


global $theme;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$disp_view = getView($focus->mode);
$smarty->assign("MODULE","Webmails");
$smarty->assign("SINGLE_MOD","Webmails");

$tmp_theme = $theme;
$msgData='';
global $mbox;

//WEBMAIL FUNCTIONS
define('SM_PATH','modules/Webmails/');
require_once(SM_PATH . 'Webmail.php');
require_once('include/utils/UserInfoUtil.php');

$mailInfo = getMailServerInfo($current_user);
$temprow = $adb->fetch_array($mailInfo);
$login_username= $temprow["mail_username"];
$secretkey=$temprow["mail_password"];
$imapServerAddress=$temprow["mail_servername"];
$start_message=$_REQUEST["start_message"];
$box_refresh=$temprow["box_refresh"];
$mails_per_page=$temprow["mails_per_page"];
$mail_protocol=$temprow["mail_protocol"];
$ssltype=$temprow["ssltype"];
$sslmeth=$temprow["sslmeth"];
if($_REQUEST["mailbox"] && $_REQUEST["mailbox"] != "") {$mailbox=$_REQUEST["mailbox"];} else {$mailbox="INBOX";}

$mbox = @imap_open("\{$imapServerAddress/$mail_protocol/$ssltype/$sslmeth}$mailbox", $login_username, $secretkey) or die("Connection to server failed");
		
$webmail = new Webmail($mbox,$_REQUEST["mailid"]);
$webmail->loadMail();
$focus->column_fields['description'] = strip_tags($webmail->body);
$focus->column_fields['subject'] = $webmail->subject;
for($i=0;$i<count($webmail->reply_to);$i++) {
	$reply_to .= $webmail->reply_to[$i].";";
}
for($i=0;$i<count($webmail->to);$i++) {
	$to .= $webmail->to[$i].";";
}
for($i=0;$i<count($webmail->cc_list);$i++) {
	$cc .= $webmail->cc_list[$i].";";
}
for($i=0;$i<count($webmail->bcc_list);$i++) {
	$bcc .= $webmail->bcc_list[$i].";";
}

$body = "<br /><br /><br /><br /><strong>In reply to the message sent by ";
$body .= $webmail->fromname." &lt; ".$webmail->from." &gt; on ".$webmail->date.": </strong><br />";
$body .= "<blockquote type='cite'>".$webmail->body."</blockquote>";

$theme = $tmp_theme;

//get Email Information
$ddate = date("Y-m-d");
$dtime = date("h:m");
if($_REQUEST["reply"] == "all")
	$tmp = $reply_to."".$to;
else
	$tmp = $reply_to;


$block["Email Information"][0][] = array(array(6),array("Date &amp; Time Sent:"),array("date_start"),array(array($ddate=>$dtime),array("%Y-%m-%d"=>"yyyy-mm-dd 24:00")));
$block["Email Information"][0][] = array(array(53),array("Assigned To:"),array("assigned_user_id"),array(array(1=>array($current_user->user_name=>"selected"))));

$block["Email Information"][1][] = array(array(21),array("To:"),array("to_list"),array($tmp));
$block["Email Information"][1][] = array(array(21),array("CC:"),array("cc_list"),array($cc));

$block["Email Information"][3][] = array(array(1),array("Subject:"),array("subject"),array($webmail->subject));
$block["Email Information"][3][] = array(array(1),array("BCC:"),array("bcc_list"),array($bcc));
$block["Email Information"][4][] = array(array(19),array("Body :"),array("email_body"),array($body));

//$block["Email Information"][2][0] = array(array(357),array(array("Disabled"=>"selected")),array("parent_id"),array($tmp),array());
//echo '<pre>';print_r($block);echo '</pre><br><br>';
$smarty->assign("BLOCKS",$block);

//$smarty->assign("BLOCKS",getBlocks("Emails",$disp_view,$mode,$focus->column_fields));
//echo '<pre>';print_r(getBlocks("Emails",$disp_view,$mode,$focus->column_fields));echo '</pre>';
$smarty->assign("OP_MODE",$disp_view);

$disp_view = getView($focus->mode);

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

if(isset($_REQUEST['return_module'])) $smarty->assign("RETURN_MODULE", $_REQUEST['return_module']);
else $smarty->assign("RETURN_MODULE",'Emails');
if(isset($_REQUEST['return_action'])) $smarty->assign("RETURN_ACTION", $_REQUEST['return_action']);
else $smarty->assign("RETURN_ACTION",'index');
if(isset($_REQUEST['return_id'])) $smarty->assign("RETURN_ID", $_REQUEST['return_id']);
if (isset($_REQUEST['return_viewname'])) $smarty->assign("RETURN_VIEWNAME", $_REQUEST['return_viewname']);


$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$smarty->assign("ID", $focus->id);
//$smarty->assign("ENTITY_ID", $_REQUEST["record"]);
$smarty->assign("ENTITY_TYPE",$_REQUEST["email_directing_module"]);

// FCKeditor


$tabid = getTabid("Webmails");
$category = getParentTab();
$smarty->assign("CATEGORY",$category);
$smarty->display("salesEditView.tpl");
?>
