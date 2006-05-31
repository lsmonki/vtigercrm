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

if($_REQUEST["mailbox"] && $_REQUEST["mailbox"] != "") {$mailbox=$_REQUEST["mailbox"];} else {$mailbox="INBOX";}
if($_REQUEST["start"] && $_REQUEST["start"] != "") {$start=$_REQUEST["start"];} else {$start="1";}

global $current_user;
require_once('Smarty_setup.php');
require_once("data/Tracker.php");
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/utils/utils.php');
require_once('include/utils/UserInfoUtil.php');
require_once("modules/Webmails/MailParse.php");

$mods = parsePHPModules();
$mailInfo = getMailServerInfo($current_user);

if($adb->num_rows($mailInfo) < 1 || !isset($mods["imap"]) || $mods["imap"] == "") {
	echo "<center><font color='red'><h3>Please configure your mail settings</h3></font></center>";
	exit();
}

$temprow = $adb->fetch_array($mailInfo);
$imapServerAddress=$temprow["mail_servername"];
$box_refresh=$temprow["box_refresh"];
$mails_per_page=$temprow["mails_per_page"];
$mail_protocol=$temprow["mail_protocol"];
$account_name=$temprow["account_name"];
$display_name=$temprow["display_name"];
$show_hidden=$_REQUEST["show_hidden"];

$degraded_service='false';
if($mail_protocol == "imap" || $mail_protocol == "pop3")
	$degraded_service='true';

?>
<script language="JavaScript" type="text/javascript" src="include/scriptaculous/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="include/scriptaculous/scriptaculous.js?load=effects,builder"></script>

<script type="text/javascript">
<?php if($degraded_service == 'true') { echo 'var degraded_service="true";';}else{echo 'var degraded_service="false";';};?>
var mailbox = "<?php echo $mailbox;?>";
var box_refresh=<?php echo $box_refresh;?>;
var webmail = new Array();
var timer;
var command;
var id;


addOnloadEvent(function() {
		window.setTimeout("periodic_event()",box_refresh);
	}
);
</script>
<script language="JavaScript" type="text/javascript" src="modules/Webmails/webmails.js"></script>
<?

global $mbox,$displayed_msgs;
$mbox = getImapMbox($mailbox,$temprow,"true");

if($_POST["command"] == "move_msg" && $_POST["ajax"] == "true") {
	imap_mail_move($mbox,$_REQUEST["mailid"],$_REQUEST["mvbox"]);
	imap_close($mbox);
	echo "SUCCESS";
	flush();
	exit();
}

function SureRemoveDir($dir) {
   if(!$dh = @opendir($dir)) return;
   while (($obj = readdir($dh))) {
     if($obj=='.' || $obj=='..') continue;
     if (!@unlink($dir.'/'.$obj)) {
         SureRemoveDir($dir.'/'.$obj);
     } else {
         $file_deleted++;
     }
   }
   if (@rmdir($dir)) $dir_deleted++;
}

$save_path='/usr/local/share/vtiger/modules/Webmails/tmp';
$user_dir=$save_path."/".$_SESSION["authenticated_user_id"];

$elist = fullMailList($mbox);

$numEmails = $elist["count"];
$headers = $elist["headers"];

//show all emails if user didn't specify amount per page
if($mails_per_page < 1)
	$mails_per_page=$numEmails;

if($start == 1 || $start == "") {
	$start_message=$numEmails;
} else {
	$start_message=($numEmails-($start*$mails_per_page));
}
$c=$numEmails;

if(!isset($_REQUEST["search"])) {
	$numPages = round($numEmails/$mails_per_page);
	if($numPages > 1) {
		$navigationOutput = "<a href='index.php?module=Webmails&action=index&start=1&mailbox=".$mailbox."'><img src='modules/Webmails/images/start.gif' border='0'></a>&nbsp;&nbsp;";
		$navigationOutput .= "<a href='index.php?module=Webmails&action=index&start=".($start-1)."&mailbox=".$mailbox."'><img src='modules/Webmails/images/previous.gif' border='0'></a> &nbsp;";
		$navigationOutput .= "<a href='index.php?module=Webmails&action=index&start=".($start+1)."&mailbox=".$mailbox."'><img src='modules/Webmails/images/next.gif' border='0'></a>&nbsp;&nbsp;";
		$navigationOutput .= "<a href='index.php?module=Webmails&action=index&start=".$numPages."&mailbox=".$mailbox."'><img src='modules/Webmails/images/end.gif' border='0'></a>";
	}
}

$overview=$elist["overview"];
?>
<!-- MAIN MSG LIST TABLE -->
<script type="text/javascript">
// Here we are creating a multi-dimension array to store mail info
// these are mainly used in the preview window and could be ajaxified/
// during the preview window load instead.
var msgCount = "<?php echo $numEmails;?>";
<?
$mails = array();
if (is_array($overview)) {
   foreach ($overview as $val) {
	$mails[$val->msgno] = $val;
	?>
	webmail[<?php echo $val->msgno;?>] = new Array();
	webmail[<?php echo $val->msgno;?>]["from"]="<?php echo addslashes($val->from);?>";
	webmail[<?php echo $val->msgno;?>]["to"]="<?php echo addslashes($val->to);?>";
	webmail[<?php echo $val->msgno;?>]["subject"]="<?php echo addslashes($val->subject);?>";
	webmail[<?php echo $val->msgno;?>]["date"]="<?php echo addslashes($val->date);?>";
	<?
   }
}
echo "</script>";

$listview_header = array("<th>Info</th>","<th>Subject</th>","<th>Date</th>","<th>From</th>","<th>Del</th>");
$listview_entries = array();


// draw a row for the listview entry
function show_msg($mails,$start_message) {
 	global $mbox,$displayed_msgs,$show_hidden,$new_msgs;

  	$num = $mails[$start_message]->msgno;
  	// TODO: scan the current db tables to find a
  	// matching email address that will make a good
  	// candidate for record_id
  	// this module will also need to be able to associate to any entity type
  	$record_id='';

	if($mails[$start_message]->subject=="")
		$mails[$start_message]->subject="(No Subject)";

  	// Let's pre-build our URL parameters since it's too much of a pain not to
  	$detailParams = 'record='.$num.'&mailbox='.$mailbox.'&mailid='.$num.'&parenttab=My Home Page';

	$displayed_msgs++;
	if ($mails[$start_message]->deleted && !$show_hidden) {
		$flags = "<tr id='row_".$num."' class='deletedRow' style='display:none'><td width='2px'><input type='checkbox' name='checkbox_".$num."' class='msg_check'></td><td colspan='1'>";
	$displayed_msgs--;
	} elseif ($mails[$start_message]->deleted && $show_hidden)
		$flags = "<tr id='row_".$num."' class='deletedRow'><td width='2px'><input type='checkbox' name='checkbox_".$num."' class='msg_check'></td><td colspan='1'>";
  	elseif (!$mails[$start_message]->seen || $mails[$start_message]->recent) {
		$flags = "<tr class='unread_email' id='row_".$num."'><td width='2px'><input type='checkbox' name='checkbox_".$num."' class='msg_check'></td><td colspan='1'>";
		$new_msgs++;
	} else 
		$flags = "<tr id='row_".$num."'><td width='2px'><input type='checkbox' name='checkbox_".$num."' class='msg_check'></td><td colspan='1'>";

  	// Attachment Icons
	if(getAttachments($num,$mbox))
		$flags.='<a href="javascript:;" onclick="displayAttachments('.$num.');"><img src="modules/Webmails/images/stock_attach.png" border="0" width="14px" height="14"></a>&nbsp;';
  	else
		$flags.='<img src="modules/Webmails/images/blank.png" border="0" width="14px" height="14" alt="">&nbsp;';

  	// read/unread/forwarded/replied
  	if(!$mails[$start_message]->seen || $mails[$start_message]->recent)
	{
  		$flags.='<span id="unread_img_'.$num.'"><a href="javascript:;" onclick="OpenCompose(\''.$num.'\',\'reply\');"><img src="modules/Webmails/images/stock_mail-unread.png" border="0" width="10" height="14"></a></span>&nbsp;';
	}
  	elseif ($mails[$start_message]->in_reply_to || $mails[$start_message]->references || preg_match("/^re:/i",$mails[$start_message]->subject))
		$flags.='<a href="javascript:;" onclick="OpenCompose(\''.$num.'\',\'reply\');"><img src="modules/Webmails/images/stock_mail-replied.png" border="0" width="10" height="12"></a>&nbsp;';
  	elseif (preg_match("/^fw:/i",$mails[$start_message]->subject))
		$flags.='<a href="javascript:;" onclick="OpenCompose(\''.$num.'\',\'reply\');"><img src="modules/Webmails/images/stock_mail-forward.png" border="0" width="10" height="13"></a>&nbsp;';
  	else
  		$flags.='<a href="javascript:;" onclick="OpenCompose(\''.$num.'\',\'reply\');"><img src="modules/Webmails/images/stock_mail-read.png" border="0" width="10" height="11"></a>&nbsp;';

  	// Set IMAP flag
  	if($mails[$start_message]->flagged)
		$flags.='<span id="clear_td_'.$num.'"><a href="javascript:runEmailCommand(\'clear_flag\','.$num.');"><img src="modules/Webmails/images/stock_mail-priority-high.png" border="0" width="11" height="11" id="clear_flag_img_'.$num.'"></a></span>';
  	else 
		$flags.='<span id="set_td_'.$num.'"><a href="javascript:void(0);" onclick="runEmailCommand(\'set_flag\','.$num.');"><img src="modules/Webmails/images/plus.gif" border="0" width="11" height="11" id="set_flag_img_'.$num.'"></a></span>';

  	
  	$tmp=imap_mime_header_decode($mails[$start_message]->from);
  	$from = $tmp[0]->text;
  	$listview_entries[$num] = array();

	$listview_entries[$num][] = $flags."</td>";

  	if ($mails[$start_message]->deleted) {
        	$listview_entries[$num][] = '<td width="20%" nowrap align="left" id="deleted_subject_'.$num.'"><s><a href="javascript:;" onclick="load_webmail(\''.$num.'\');">'.substr($mails[$start_message]->subject,0,50).'</a></s></td>';
        	$listview_entries[$num][] = '<td width="10%" nowrap align="left" nowrap id="deleted_date_'.$num.'"><s>'.substr($mails[$start_message]->date,0,30).'</s></td>';
        	$listview_entries[$num][] = '<td width="10%" nowrap align="left" id="deleted_from_'.$num.'"><s>'.substr($from,0,20).'</s></td>';
  	} elseif(!$mails[$start_message]->seen || $mails[$start_message]->recent) {
        	$listview_entries[$num][] = '<td width="20%" nowrap align="left" ><a href="javascript:;" onclick="load_webmail(\''.$num.'\');" id="ndeleted_subject_'.$num.'">'.substr($mails[$start_message]->subject,0,50).'</a></td>';
        	$listview_entries[$num][] = '<td width="10%" nowrap align="left" nowrap id="ndeleted_date_'.$num.'" >'.substr($mails[$start_message]->date,0,30).' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>';
        	$listview_entries[$num][] = '<td  width="10%" nowrap align="left" id="ndeleted_from_'.$num.'">'.substr($from,0,20).'</td>';
  	} else {
        	$listview_entries[$num][] = '<td width="20%" nowrap align="left" ><a href="javascript:;" onclick="load_webmail(\''.$num.'\');" id="ndeleted_subject_'.$num.'">'.substr($mails[$start_message]->subject,0,50).'</a></td>';
        	$listview_entries[$num][] = '<td width="10%" npwrap align="left" nowrap id="ndeleted_date_'.$num.'">'.substr($mails[$start_message]->date,0,30).'</td>';
        	$listview_entries[$num][] = '<td width="10%" nowrap align="left" id="ndeleted_from_'.$num.'">'.substr($from,0,20).'</td>';
  	}

	if($mails[$start_message]->deleted)
  		$listview_entries[$num][] = '<td nowrap align="center" id="deleted_td_'.$num.'"><span id="del_link_'.$num.'"><a href="javascript:void(0);" onclick="runEmailCommand(\'undelete_msg\','.$num.');"><img src="modules/Webmails/images/gnome-fs-trash-full.png" border="0" width="14" height="14" alt="del"></a></span></td></tr>';
	else
  		$listview_entries[$num][] = '<td nowrap align="center" id="ndeleted_td_'.$num.'"><span id="del_link_'.$num.'"><a href="javascript:void(0);" onclick="runEmailCommand(\'delete_msg\','.$num.');"><img src="modules/Webmails/images/gnome-fs-trash-empty.png" border="0" width="14" height="14" alt="del"></a></span></td></tr>';

	return $listview_entries[$num];
}

$displayed_msgs=0;
$new_msgs=0;
if($numEmails <= 0)
	$listview_entries[0][] = '<td colspan="6" width="100%" align="center"><b>No Emails In This Folder</b></td>';
else {

if(isset($_REQUEST["search"])) {
	$searchstring = $_REQUEST["search_type"].' "'.$_REQUEST["search_input"].'"';
	//echo $searchstring."<br>";
	$searchlist = imap_search($mbox,$searchstring);
	if($searchlist === false)
  		echo "The search failed";

	$num_searches = count($searchlist);

	//print_r($searchlist);
	$c=$numEmails;
}

// MAIN LOOP
// Main loop to create listview entries
$i=1;
while ($i<$c) {
	if(is_array($searchlist)) {
		for($l=0;$l<$num_searches;$l++) {
			if($mails[$start_message]->msgno == $searchlist[$l])
				$listview_entries[] = show_msg($mails,$start_message);
		}
	} else {
		$listview_entries[] = show_msg($mails,$start_message);
		if($displayed_msgs == $mails_per_page) {break;}
	}
  	$i++;
  	$start_message--;
}
}

// Build folder list and move_to dropdown box
$list = imap_getmailboxes($mbox, "{".$imapServerAddress."}", "*");
sort($list);
$i=0;
if (is_array($list)) {
      	$boxes = '<select name="mailbox" id="mailbox_select">';
        foreach ($list as $key => $val) {
		$tmpval = preg_replace(array("/\{.*?\}/i"),array(""),$val->name);
		if(preg_match("/trash/i",$tmpval))
			$img = "webmail_trash.gif";
		elseif(preg_match("/sent/i",$tmpval))
			$img = "webmail_uparrow.gif";
		else
			$img = "webmail_downarrow.gif";

		$i++;

		if ($_REQUEST["mailbox"] == $tmpval) {
                        $boxes .= '<option value="'.$tmpval.'" SELECTED>'.$tmpval;
			$_SESSION["mailboxes"][$tmpval] = $new_msgs;

			$folders .= '<li><img src="'.$image_path.'/'.$img.'" align="absmiddle" />&nbsp;&nbsp;<a href="javascript:changeMbox(\''.$tmpval.'\');" class="webMnu" onmouseover="show_remfolder(\''.$tmpval.'\');" onmouseout="show_remfolder(\''.$tmpval.'\');">'.$tmpval.'</a>&nbsp;&nbsp;<span id="'.$tmpval.'_count" style="font-weight:bold">(<span id="'.$tmpval.'_unread">'.$new_msgs.'</span> of <span id="'.$tmpval.'_read">'.$numEmails.'</span>)</span>&nbsp;&nbsp;<span id="remove_'.$tmpval.'" style="position:relative;display:none">Remove</span></li>';
		} else {
			$box = imap_status($mbox, "{".$imapServerAddress."}".$tmpval, SA_ALL);
			$_SESSION["mailboxes"][$tmpval] = $box->unseen;

                      	$boxes .= '<option value="'.$tmpval.'">'.$tmpval;
			$folders .= '<li><img src="'.$image_path.'/'.$img.'" align="absmiddle" />&nbsp;&nbsp;<a href="javascript:changeMbox(\''.$tmpval.'\');" class="webMnu">'.$tmpval.'</a>&nbsp;<span id="'.$tmpval.'_count" style="font-weight:bold">(<span id="'.$tmpval.'_unread">'.$box->unseen.'</span> of <span id="'.$tmpval.'_read">'.$box->messages.'</span>)</span></li>';
		}
 	}
        $boxes .= '</select>';
}

imap_close($mbox);
global $current_user;

$smarty = new vtigerCRM_Smarty;
$smarty->assign("USERID", $current_user->id);
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("LISTENTITY", $listview_entries);
$smarty->assign("LISTHEADER", $listview_header);
$smarty->assign("MODULE","Webmails");
$smarty->assign("SINGLE_MOD",'Webmails');
$smarty->assign("BUTTONS",$other_text);
$smarty->assign("CATEGORY","My Home Page");
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("FOLDER_SELECT", $boxes);
$smarty->assign("NUM_EMAILS", $numEmails);
$smarty->assign("MAILBOX", $mailbox);
$smarty->assign("ACCOUNT", $display_name);
$smarty->assign("BOXLIST",$folders);
$smarty->assign("DEGRADED_SERVICE",$degraded_service);
$smarty->display("Webmails.tpl");
?>
