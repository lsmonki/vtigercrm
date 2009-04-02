<?php
/*********************************************************************************
 ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
  * ("License"); You may not use this file except in compliance with the License
  * The Initial Developer of the Original Code is FOSS Labs.
  * Portions created by FOSS Labs are Copyright (C) FOSS Labs.
  * Portions created by vtiger are Copyright (C) vtiger.
  * All Rights Reserved.
  *
  ********************************************************************************/

require_once('modules/Emails/Emails.php');
require_once('modules/Webmails/Webmails.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('include/utils/UserInfoUtil.php');
require_once('include/utils/CommonUtils.php');
require_once('modules/Webmails/MailParse.php');
require_once('modules/Webmails/MailBox.php');
global $current_user;

$local_log =& LoggerManager::getLogger('index');
$focus = new Emails();

$to_address = explode(";",$_REQUEST['to_list']);
$cc_address = explode(";",$_REQUEST['cc_list']);
$bcc_address = explode(";",$_REQUEST['bcc_list']);

$start_message=$_REQUEST["start_message"];
if($_REQUEST["mailbox"] && $_REQUEST["mailbox"] != "") {$mailbox=$_REQUEST["mailbox"];} else {$mailbox="INBOX";}

$MailBox = new MailBox($mailbox);
$mail = $MailBox->mbox;
$email = new Webmails($MailBox->mbox, $_REQUEST["mailid"]);
$subject = imap_utf8($email->subject);
$date = $email->date;
$array_tab = Array();
$email->loadMail($array_tab);
$msgData = str_replace('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">',"",$email->body);
$content['attachtab'] = $email->attachtab;
while ($tmp = array_pop($content['attachtab'])){
	if ((!eregi('ATTACHMENT', $tmp['disposition'])) && $conf->display_text_attach && (eregi('text/plain', $tmp['mime'])))
		$msgData .= '<hr />'.view_part_detail($mail, $mailid, $tmp['number'], $tmp['transfer'], $tmp['charset'], $charset);
}
$focus->column_fields['subject']=$subject;
$focus->column_fields["activitytype"]="Emails";

$ddate = date("Y-m-d");
$dtime = date("h:m");
$focus->column_fields["assigned_user_id"]=$current_user->id;
$focus->column_fields["date_start"]=$ddate;
$focus->column_fields["time_start"]=$dtime;

//$tmpBody = preg_replace(array('/<br(.*?)>/i',"/&gt;/i","/&lt;/i","/&nbsp;/i","/&amp/i","/&copy;/i","/<style(.*?)>(.*?)<\/style>/i","/\{(.*?)\}/i","/BODY/i"),array("\r",">","<"," ","&","(c)","","",""),$msgData);
//$focus->column_fields["description"]=strip_tags($tmpBody);
$focus->column_fields["description"]=$msgData;


//to save the email details in vtiger_emaildetails vtiger_tables
$fieldid = $adb->query_result($adb->pquery('select fieldid from vtiger_field where tablename="vtiger_contactdetails" and fieldname="email" and columnname="email" and vtiger_field.presence in (0,2)', array()),0,'fieldid');

if(count($email->relationship) != 0) {
	$focus->column_fields['parent_id']=$email->relationship["id"].'@'.$fieldid.'|';

	if($email->relationship["type"] == "Contacts")
		add_attachment_to_contact($email->relationship["id"],$email);
}else {
	//if relationship is not available create a contact and relate the email to the contact
	require_once('modules/Contacts/Contacts.php');
	$contact_focus = new Contacts();	
	$contact_focus->column_fields['lastname'] =$email->fromname; 
	$contact_focus->column_fields['email'] = $email->from;
	$contact_focus->column_fields["assigned_user_id"]=$current_user->id;
	$contact_focus->save("Contacts");
	$focus->column_fields['parent_id']=$contact_focus->id.'@'.$fieldid.'|';

	add_attachment_to_contact($contact_focus->id,$email);
}

function add_attachment_to_contact($cid,$email) {
	// add vtiger_attachments to contact
	global $adb,$current_user;
	for($j=0;$j<2;$j++) {
	    if($j==0)
	    	$attachments=$email->downloadAttachments();
	    else
	    	$attachments=$email->downloadInlineAttachments();

	    $upload_filepath = decideFilePath();
	    for($i=0,$num_files=count($attachments);$i<$num_files;$i++)
	    {
		$current_id = $adb->getUniqueID("vtiger_crmentity");
		$date_var = $adb->formatDate(date('Y-m-d H:i:s'), true);	

		$filename = ereg_replace("[ ()-]+", "_",$attachments[$i]["filename"]);
        	$filetype= substr($filename,strstr($filename,"."),strlen($filename));
		$filesize = $attachments[$i]["filesize"];

                $query = "insert into vtiger_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values(?,?,?,?,?,?,?)";
                $qparams = array($current_id, $current_user->id, $current_user->id, 'Contacts Attachment', 'Uploaded from webmail during qualification', $date_var, $date_var);
                $result = $adb->pquery($query, $qparams);

                $sql = "insert into vtiger_attachments values(?,?,?,?,?)";
                $params = array($current_id, $filename, 'Uploaded '.$filename.' from webmail', $filetype, $upload_filepath);
                $result = $adb->pquery($sql, $params);

                $sql1 = "insert into vtiger_seattachmentsrel values(?,?)";
                $params1 = array($cid, $current_id);
                $result = $adb->pquery($sql1, $params1);

		//we have to add attachmentsid_ as prefix for the filename
		$move_filename = $upload_filepath.'/'.$current_id.'_'.$filename;

		$fp = fopen($move_filename, "w") or die("Can't open file");
		fputs($fp, base64_decode($attachments[$i]["filedata"]));
		fclose($fp);
	    }
	}
}
function view_part_detail($mail,$mailid,$part_no, &$transfer, &$msg_charset, &$charset)
{
        $text = imap_fetchbody($mail,$mailid,$part_no);
        if ($transfer == 'BASE64')
                $str = nl2br(imap_base64($text));
        elseif($transfer == 'QUOTED-PRINTABLE')
                $str = nl2br(quoted_printable_decode($text));
        else
                $str = nl2br($text);
        return ($str);
}

$_REQUEST['parent_id'] = $focus->column_fields['parent_id'];
$focus->save("Emails");

//saving in vtiger_emaildetails vtiger_table
$id_lists = $focus->column_fields['parent_id'].'@'.$fieldid;
$all_to_ids = $email->from;
//added to save < as $lt; and > as &gt; in the database so as to retrive the emailID
$all_to_ids = str_replace('<','&lt;',$all_to_ids);
$all_to_ids = str_replace('>','&gt;',$all_to_ids);
$query = 'insert into vtiger_emaildetails values (?,?,?,?,?,?,?,?)';
$adb->pquery($query, array($focus->id, "", $all_to_ids, "", "", "", $id_lists,"WEBMAIL"));
$query = 'insert into vtiger_seactivityrel values (?,?)';
$adb->pquery($query, array($contact_focus->id, $focus->id));
$return_id = $_REQUEST["mailid"];
$return_module='Webmails';
$return_action='ListView';


if($_POST["ajax"] != "true")
	header("Location: index.php?action=$return_action&module=$return_module&record=$return_id"); 

return;
?>
