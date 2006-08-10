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

// figure out which page we are on and what mailbox we want to view
if($_REQUEST["mailbox"] && $_REQUEST["mailbox"] != "") {$mailbox=$_REQUEST["mailbox"];} else {$mailbox="INBOX";}
if($_REQUEST["start"] && $_REQUEST["start"] != "") {$start=$_REQUEST["start"];} else {$start="1";}
$show_hidden=$_REQUEST["show_hidden"];

global $current_user;
require_once('Smarty_setup.php');
require_once("data/Tracker.php");
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/utils/utils.php');
require_once('include/utils/UserInfoUtil.php');
require_once("modules/Webmails/MailBox.php");
require_once("modules/Webmails/Webmail.php");
require_once("modules/Webmails/MailParse.php");

$MailBox = new MailBox($mailbox);

// Check for a valid mailbox and also make sure the needed php_imap module is installed
$mods = parsePHPModules();

if(!$MailBox->mbox || !isset($mods["imap"]) || $mods["imap"] == "") {
	echo "<center><font color='red'><h3>Please configure your mail settings</h3></font></center>";
	exit();
}

// Set the system into degraded service mode where needed
$degraded_service='false';
if($MailBox->mail_protocol == "imap" || $MailBox->mail_protocol == "pop3")
	$degraded_service='true';


if($_POST["command"] == "check_mbox_all") {
	exit();
        $boxes = array();
        $i=0;
        foreach ($_SESSION["mailboxes"] as $key => $val) {
                $MailBox = new MailBox($key);
                $box = imap_status($MailBox->mbox, "{".$MailBox->imapServerAddress."}".$key, SA_ALL);

                $boxes[$i]["name"] = $key;
                if($val == $box->unseen)
                        $boxes[$i]["newmsgs"] = 0;
                elseif($val < $box->unseen) {
                        $boxes[$i]["newmsgs"] = ($box->unseen-$val);
                        $_SESSION["mailboxes"][$key] = $box->unseen;
                } else {
                        $boxes[$i]["newmsgs"] = 0;
                        $_SESSION["mailboxes"][$key] = $box->unseen;
                }
                $i++;
                imap_close($MailBox->mbox);
        }

        $ret = '';
        if(count($boxes) > 0) {
                $ret = '{"msgs":[';
                for($i=0,$num=count($boxes);$i<$num;$i++) {
                        $ret .= '{"msg":';
                        $ret .= '{';
                        $ret .= '"box":"'.$boxes[$i]["name"].'",';
                        $ret .= '"newmsgs":"'.$boxes[$i]["newmsgs"].'"}';

                        if(($i+1) == $num)
                                $ret .= '}';
                        else
                                $ret .= '},';
                }
                $ret .= ']}';
        }
        echo $ret;
        flush();
        exit();
}

if($_POST["command"] == "check_mbox") {
        $adb->println("Inside check_mbox AJAX command");

        $search = imap_search($MailBox->mbox, "ALL NEW");
        if($search === false) {echo "failed";flush();exit();}

        $data = imap_fetch_overview($MailBox->mbox,implode(',',$search));
        $num=sizeof($data);

        $ret = '';
        if($num > 0) {
                $ret = '{"mails":[';
                for($i=0;$i<$num;$i++) {
                        $ret .= '{"mail":';
                        $ret .= '{';
                        $ret .= '"mailid":"'.$data[$i]->msgno.'",';
                        $ret .= '"subject":"'.substr($data[$i]->subject,0,40).'",';
                        $ret .= '"date":"'.substr($data[$i]->date,0,30).'",';
                        $ret .= '"from":"'.substr($data[$i]->from,0,20).'",';
                        $ret .= '"to":"'.$data[$i]->to.'",';
                        $email = new Webmail($MailBox->mbox,$data[$i]->msgno);
                        if($email->has_attachments)
                                $ret .= '"attachments":"1"}';
                        else
                                $ret .= '"attachments":"0"}';
                        if(($i+1) == $num)
                                $ret .= '}';
                        else
                                $ret .= '},';
                }
                $ret .= ']}';
        }

        echo $ret;
        flush();
        imap_close($MailBox->mbox);
	exit();
}

?>
<script language="JavaScript" type="text/javascript" src="include/scriptaculous/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="include/scriptaculous/scriptaculous.js?load=effects,builder"></script>

<script type="text/javascript">
// Pass our PHP variables to js.
<?php if($degraded_service == 'true') { echo 'var degraded_service="true";';}else{echo 'var degraded_service="false";';};?>
var mailbox = "<?php echo $MailBox->mailbox;?>";
var box_refresh=<?php echo $MailBox->box_refresh;?>;
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

global $displayed_msgs;
// AJAX commands (should be moved)
if($_POST["command"] == "move_msg" && $_POST["ajax"] == "true") {
	imap_mail_move($MailBox->mbox,$_REQUEST["mailid"],$_REQUEST["mvbox"]);
	imap_close($MailBox->mbox);
	echo "SUCCESS";
	flush();
	exit();
}

// Function to remove directories used for tmp attachment storage
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

// Get the list of mails for this mailbox
$elist = $MailBox->mailList;
$numEmails = $elist["count"];
$headers = $elist["headers"];

if($start == 1 || $start == "") {
	$start_message=$numEmails;
} else {
	$start_message=($numEmails-($start*$mails_per_page));
}
$c=$numEmails;

if(!isset($_REQUEST["search"])) {
	$numPages = round($numEmails/$MailBox->mails_per_page);
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

$displayed_msgs=0;
$new_msgs=0;
if(($numEmails-1) <= 0)
	$listview_entries[0][] = '<td colspan="6" width="100%" align="center"><b>No Emails In This Folder</b></td>';
else {

if(isset($_REQUEST["search"])) {
	$searchstring = $_REQUEST["search_type"].' "'.$_REQUEST["search_input"].'"';
	//echo $searchstring."<br>";
	$searchlist = imap_search($MailBox->mbox,$searchstring);
	if($searchlist === false)
  		echo "The search failed";

	$num_searches = count($searchlist);

	//print_r($searchlist);
	$c=$numEmails;
}

flush();

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
		if($displayed_msgs == $MailBox->mails_per_page) {break;}
	}
  	$i++;
  	$start_message--;
}
}

// Build folder list and move_to dropdown box
$list = imap_getmailboxes($MailBox->mbox, "{".$MailBox->imapServerAddress."}", "*");
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

			if($numEmails==0) {$num=$numEmails;} else {$num=($numEmails-1);}
			$folders .= '<li><img src="'.$image_path.'/'.$img.'" align="absmiddle" />&nbsp;&nbsp;<a href="javascript:changeMbox(\''.$tmpval.'\');" class="webMnu" onmouseover="show_remfolder(\''.$tmpval.'\');" onmouseout="show_remfolder(\''.$tmpval.'\');">'.$tmpval.'</a>&nbsp;&nbsp;<span id="'.$tmpval.'_count" style="font-weight:bold">(<span id="'.$tmpval.'_unread">'.$new_msgs.'</span> of <span id="'.$tmpval.'_read">'.$num.'</span>)</span>&nbsp;&nbsp;<span id="remove_'.$tmpval.'" style="position:relative;display:none">Remove</span></li>';
		} else {
			$box = imap_status($MailBox->mbox, "{".$MailBox->imapServerAddress."}".$tmpval, SA_ALL);
			$_SESSION["mailboxes"][$tmpval] = $box->unseen;

			if($box->messages==0) {$num=$box->messages;} else {$num=($box->messages-1);}
                      	$boxes .= '<option value="'.$tmpval.'">'.$tmpval;
			$folders .= '<li><img src="'.$image_path.'/'.$img.'" align="absmiddle" />&nbsp;&nbsp;<a href="javascript:changeMbox(\''.$tmpval.'\');" class="webMnu">'.$tmpval.'</a>&nbsp;<span id="'.$tmpval.'_count" style="font-weight:bold">(<span id="'.$tmpval.'_unread">'.$box->unseen.'</span> of <span id="'.$tmpval.'_read">'.$num.'</span>)</span></li>';
		}
 	}
        $boxes .= '</select>';
}

imap_close($MailBox->mbox);

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
$smarty->assign("MAILBOX", $MailBox->mailbox);
$smarty->assign("ACCOUNT", $MailBox->display_name);
$smarty->assign("BOXLIST",$folders);
$smarty->assign("DEGRADED_SERVICE",$degraded_service);
$smarty->display("Webmails.tpl");
?>
