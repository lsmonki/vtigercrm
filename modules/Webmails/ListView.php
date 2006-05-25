<script language="JavaScript" type="text/javascript" src="include/js/prototype_fade.js"></script>
<?php
if($_REQUEST["mailbox"] && $_REQUEST["mailbox"] != "") {$mailbox=$_REQUEST["mailbox"];} else {$mailbox="INBOX";}
if($_REQUEST["start"] && $_REQUEST["start"] != "") {$start=$_REQUEST["start"];} else {$start="1";}
?>
<script type="text/javascript">
function load_webmail(mid) {
	var node = $("row_"+mid);
	var newhtml = remove(remove(node.innerHTML,'<b>'),'</b>');
	node.innerHTML = newhtml;
	try {
		var node = $("unread_img_"+mid).innerHTML = '<a href="javascript:;" onclick="OpenCompose(\''+mid+'\',\'reply\');"><img src="modules/Webmails/images/stock_mail-read.png" border="0" width="10" height="11"></a>';
	}catch(e){}
	$("from_addy").innerHTML = "&nbsp;"+webmail[mid]["from"];
	$("to_addy").innerHTML = "&nbsp;"+webmail[mid]["to"];
	$("webmail_subject").innerHTML = "&nbsp;"+webmail[mid]["subject"];
	$("webmail_date").innerHTML = "&nbsp;"+webmail[mid]["date"];
	$("body_area").innerHTML = '<iframe src="index.php?module=Webmails&action=body&mailid='+mid+'&mailbox=<?php echo $mailbox;?>" width="100%" height="210" frameborder="0">You must enabled iframes</iframe>';
	tmp = document.getElementsByClassName("previewWindow");
	for(var i=0;i<tmp.length;i++) {
		if(tmp[i].style.visibility === "hidden") {
			tmp[i].style.visibility="visible";
		}
	}
	$("delete_button").innerHTML = '<input type="button" name="Button" value=" Delete "  class="classWebBtn" onclick="runEmailCommand(\'delete_msg\','+mid+');"/>';
	$("reply_button_all").innerHTML = '<input type="button" name="reply" value=" Reply to All " class="classWebBtn" onclick="window.location = \'index.php?module=Webmails&action=EditView&mailid='+mid+'&reply=all&return_action=index&return_module=Webmails\';" />';
	$("reply_button").innerHTML = '<input type="button" name="reply" value=" Reply to Sender " class="classWebBtn" onclick="window.location = \'index.php?module=Webmails&action=EditView&mailid='+mid+'&reply=single&return_action=index&return_module=Webmails\';" />';
	$("qualify_button").innerHTML = '<input type="button" name="Qualify2" value=" Qualify " onclick="showRelationships('+mid+');" class="classWebBtn" />';
	$("download_attach_button").innerHTML = '<input type="button" name="download" value=" Download Attachments " class="classWebBtn" onclick="displayAttachments('+mid+');" />';

}
function displayAttachments(mid) {
	var url = "index.php?module=Webmails&action=dlAttachments&mailid="+mid;
	window.open(url,"Download Attachments",'menubar=no,toolbar=no,location=no,status=no,resizable=no,width=450,height=450');
}
function showRelationships(mid) {
	// just add to vtiger for now
	add_to_vtiger(mid);
}
function add_to_vtiger(mid) {
	$("status").style.display="block";
        new Ajax.Request(
                'index.php',
                {queue: {position:'front', scope: 'command', limit:1},
                        method: 'post',
                        postBody: 'module=Webmails&action=Save&mailid='+mid+'&ajax=true',
                        onComplete: function(t) {
				$("status").style.display="none";
			}
		}
	);
}
function select_all() {
	var els = document.getElementsByClassName("msg_check");
	for(var i=0;i<els.length;i++) {
		if(els[i].checked)
			els[i].checked = false;
		else
			els[i].checked = true;
	}
}
function check_for_new_mail(mbox) {
	$("status").style.display="block";
        new Ajax.Request(
                'index.php',
                {queue: {position:'front', scope: 'command', limit:1},
                        method: 'post',
                        postBody: 'module=Webmails&action=ListView&mailbox='+mbox+'&command=check_mbox&ajax=true',
                        onComplete: function(t) {
				if(!t.responseText.match(/NORELOAD/))
					window.location=window.location;

				$("status").style.display="none";
				timer = window.setTimeout("check_for_new_mail()",box_refresh);
			}
		}
	);
}
function show_hidden() {
	var els = document.getElementsByClassName("deletedRow");
	for(var i=0;i<els.length;i++) {
		if(els[i].style.display == "none")
			new Effect.Appear(els[i],{queue:{position:'end',scope:'effect',limit:'1'}});
		else
			new Effect.Fade(els[i],{queue:{position:'end',scope:'effect',limit:'1'}});
	}
}
function move_messages() {
        var els = document.getElementsByTagName("INPUT");
	var cnt = (els.length-1);
        for(var i=cnt;i>0;i--) {
                if(els[i].type === "checkbox" && els[i].name.indexOf("_")) {
                        if(els[i].checked) {
                                var nid = els[i].name.substr((els[i].name.indexOf("_")+1),els[i].name.length);
				var mvmbox = $("mailbox_select").value;
        			new Ajax.Request(
                			'index.php',
                			{queue: {position:'end', scope: 'command', limit:1},
                        			method: 'post',
                        			postBody: 'module=Webmails&action=ListView&mailbox=INBOX&command=move_msg&ajax=true&mailid='+nid+'&mvbox='+mvmbox,
                        			onComplete: function(t) {
							//alert(t.responseText);
						}
					}
				);
                        }
                }
        }
	runEmailCommand('expunge','');
}
</script>
<?php
global $current_user;
require_once('Smarty_setup.php');
require_once("data/Tracker.php");
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/utils/utils.php');
require_once('include/utils/UserInfoUtil.php');
require_once("modules/Webmails/MailParse.php");
require_once('modules/CustomView/CustomView.php');

$mailInfo = getMailServerInfo($current_user);
if($adb->num_rows($mailInfo) < 1) {
	echo "<center><font color='red'><h3>Please configure your mail settings</h3></font></center>";
	exit();
}

$temprow = $adb->fetch_array($mailInfo);
$login_username= $temprow["mail_username"];
$secretkey=$temprow["mail_password"];
$imapServerAddress=$temprow["mail_servername"];
$box_refresh=$temprow["box_refresh"];
$mails_per_page=$temprow["mails_per_page"];
$mail_protocol=$temprow["mail_protocol"];
$ssltype=$temprow["ssltype"];
$sslmeth=$temprow["sslmeth"];
$account_name=$temprow["account_name"];
$show_hidden=$_REQUEST["show_hidden"];
?>

<script language="Javascript" type="text/javascript" src="modules/Webmails/js/ajax_connection.js"></script>
<script language="Javascript" type="text/javascript" src="modules/Webmails/js/script.js"></script>
<script language="JavaScript" type="text/javascript" src="general.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/prototype.js"></script>

<script type="text/Javascript">
var box_refresh=<?php echo $box_refresh;?>;
var timer = addOnloadEvent(function() {
				window.setTimeout("check_for_new_mail()",box_refresh);
			}
		);

var command;
var id;
function runEmailCommand(com,id) {
	$("status").style.display="block";
	command=com;
	id=id;
	new Ajax.Request(
                'index.php',
                {queue: {position:'front', scope: 'command', limit:1},
                        method: 'post',
                        postBody: 'module=Webmails&action=body&command='+command+'&mailid='+id+'&mailbox=<?php echo $_REQUEST["mailbox"];?>',
                        onComplete: function(t) {
				resp = t.responseText;
				if(resp.match(/ajax failed/)) {return;}
				switch(command) {
				    case 'expunge':
					// NOTE: we either have to reload the page or count up from the messages that
					// are deleted and moved or we introduce a bug from invalid mail ids
					window.location = window.location;
				    break;
				    case 'delete_msg':
					var row = $("row_"+id);
					row.className = "deletedRow";
					try {
						$("ndeleted_subject_"+id).innerHTML = "<s>"+$("ndeleted_subject_"+id).innerHTML+"</s>";
						$("ndeleted_date_"+id).innerHTML = "<s>"+$("ndeleted_date_"+id).innerHTML+"</s>";
						$("ndeleted_from_"+id).innerHTML = "<s>"+$("ndeleted_from_"+id).innerHTML+"</s>";
					}catch(e){
						$("deleted_subject_"+id).innerHTML = "<s>"+$("deleted_subject_"+id).innerHTML+"</s>";
						$("deleted_date_"+id).innerHTML = "<s>"+$("deleted_date_"+id).innerHTML+"</s>";
						$("deleted_from_"+id).innerHTML = "<s>"+$("deleted_from_"+id).innerHTML+"</s>";
					}

					$("del_link_"+id).innerHTML = '<a href="javascript:void(0);" onclick="runEmailCommand(\'undelete_msg\','+id+');"><img src="modules/Webmails/images/gnome-fs-trash-full.png" border="0" width="14" height="14" alt="del"></a>';
					new Effect.Fade(row,{queue:{position:'end',scope:'effect',limit:'1'}});
					tmp = document.getElementsByClassName("previewWindow");
					for(var i=0;i<tmp.length;i++) {
						if(tmp[i].style.visibility === "visible") {
							tmp[i].style.visibility="hidden";
						}
					}
				    break;
				    case 'undelete_msg':
					var node = $("row_"+id);
					node.className='';
					node.style.display = '';
					var newhtml = remove(remove(node.innerHTML,'<s>'),'</s>');
					node.innerHTML=newhtml;
					$("del_link_"+id).innerHTML = '<a href="javascript:void(0);" onclick="runEmailCommand(\'delete_msg\','+id+');"><img src="modules/Webmails/images/gnome-fs-trash-empty.png" border="0" width="14" height="14" alt="del"></a>';
				    break;
				    case 'clear_flag':
					var nm = "clear_td_"+id;
                			var el = $(nm);
                			var tmp = el.innerHTML;
                			el.innerHTML ='<a href="javascript:void(0);" onclick="runEmailCommand(\'set_flag\','+id+');"><img src="modules/Webmails/images/plus.gif" border="0" width="11" height="11" id="set_flag_img_'+id+'"></a>';
                			el.id = "set_td_"+id;
				    break;
				    case 'set_flag':
					var nm = "set_td_"+id;
                			var el = $(nm);
                			var tmp = el.innerHTML;
                			el.innerHTML ='<a href="javascript:void(0);" onclick="runEmailCommand(\'clear_flag\','+id+');"><img src="modules/Webmails/images/stock_mail-priority-high.png" border="0" width="11" height="11" id="clear_flag_img'+id+'"></a>';
                			el.id = "clear_td_"+id;
				    break;

				}
				$("status").style.display="none";
                        }
                }
        );
}
function remove(s, t) {
  /*
  **  Remove all occurrences of a token in a string
  **    s  string to be processed
  **    t  token to be removed
  **  returns new string
  */
  i = s.indexOf(t);
  r = "";
  if (i == -1) return s;
  r += s.substring(0,i) + remove(s.substring(i + t.length), t);
  return r;
}
function changeMbox(box) {
	location.href = "index.php?module=Webmails&action=index&parenttab=My%20Home%20Page&mailbox="+box+"&start=<?php echo $start;?>";
}
</script>
<?

$viewname="20";

// CUSTOM VIEW
//<<<<cutomview>>>>>>>
global $currentModule;
$oCustomView = new CustomView("Webmails");
$viewid = $oCustomView->getViewId($currentModule);
$customviewcombo_html = $oCustomView->getCustomViewCombo($viewid);
$viewnamedesc = $oCustomView->getCustomViewByCvid($viewid);
//<<<<<customview>>>>>


global $mbox;
if($ssltype == "") {$ssltype = "notls";}
if($sslmeth == "") {$sslmeth = "novalidate-cert";}
$mbox = @imap_open("{".$imapServerAddress."/".$mail_protocol."/".$ssltype."/".$sslmeth."}".$mailbox, $login_username, $secretkey);

if(!$mbox)
	$mbox = @imap_open("{".$imapServerAddress."/".$mail_protocol."/}".$mailbox, $login_username, $secretkey) or die("Connection to server failed ".imap_last_error());
	

if($_POST["command"] == "check_mbox" && $_POST["ajax"] == "true") {
	$check = imap_mailboxmsginfo($mbox);
	if($check->Unread > 0)
		return "RELOAD";
	else
		return "NORELOAD";

	imap_close($mbox);
	flush();
	exit();
}

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

$numPages = round($numEmails/$mails_per_page);
if($numPages > 1) {
	$navigationOutput = "<a href='index.php?module=Webmails&action=index&start=1&mailbox=".$mailbox."'>&lt;&lt;</a>&nbsp;&nbsp;";
	$navigationOutput .= "<a href='index.php?module=Webmails&action=index&start=".($start-1)."&mailbox=".$mailbox."'>&lt;</a> -- ";
	$navigationOutput .= "<a href='index.php?module=Webmails&action=index&start=".($start+1)."&mailbox=".$mailbox."'>&gt;</a>&nbsp;&nbsp;";
	$navigationOutput .= "<a href='index.php?module=Webmails&action=index&start=".$numPages."&mailbox=".$mailbox."'>&gt;&gt;</a>";
}

$overview=$elist["overview"];
?>

<!-- MAIN MSG LIST TABLE -->
<script type="text/javascript">
var webmail = new Array();
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

$listview_header = array("Info","Subject","Date","From","Del");
$listview_entries = array();

if($numEmails <= 0)
	$listview_entries[0][] = '<td colspan="6" width="100%" align="center"><b>No Emails In This Folder</b></td>';
else {
$displayed_msgs=0;
$i=1;
    // Main loop to create listview entries
    while ($i<$c) {
	if($displayed_msgs==$mails_per_page) {break;}

  	$num = $mails[$start_message]->msgno;
  	// TODO: scan the current db tables to find a
  	// matching email address that will make a good
  	// candidate for record_id
  	// this module will also need to be able to associate to any entity type
  	$record_id='';

	if($mails[$start_message]->subject=="")
		$mails[$start_message]->subject="(No Subject)";

  	// Let's pre-build our URL parameters since it's too much of a pain not to
  	$detailParams = 'record='.$record_id.'&mailbox='.$mailbox.'&mailid='.$num.'&parenttab=My Home Page';
 	$defaultParams = 'parenttab=My Home Page&mailbox='.$mailbox.'&start='.$start.'&viewname='.$viewname;

	$displayed_msgs++;
	if ($mails[$start_message]->deleted && !$show_hidden) {
		$flags = "<tr id='row_".$mails[$start_message]->msgno."' class='deletedRow' style='display:none'><td colspan='1'><input type='checkbox' name='checkbox_".$mails[$start_message]->msgno."' class='msg_check'></td><td colspan='1'>";
	$displayed_msgs--;
	} elseif ($mails[$start_message]->deleted && $show_hidden)
		$flags = "<tr id='row_".$mails[$start_message]->msgno."' class='deletedRow'><td colspan='1'><input type='checkbox' name='checkbox_".$mails[$start_message]->msgno."' class='msg_check'></td><td colspan='1'>";
	else 
		$flags = "<tr id='row_".$mails[$start_message]->msgno."'><td colspan='1'><input type='checkbox' name='checkbox_".$mails[$start_message]->msgno."' class='msg_check'></td><td colspan='1'>";

  	// Attachment Icons
  	if(getAttachmentDetails($start_message,$mbox))
		$flags.='<a href="javascript:;" onclick="displayAttachments('.$num.');"><img src="modules/Webmails/images/stock_attach.png" border="0" width="14px" height="14"></a>&nbsp;';
  	else
		$flags.='<img src="modules/Webmails/images/blank.png" border="0" width="14px" height="14" alt="">&nbsp;';

  	// read/unread/forwarded/replied
  	if(!$mails[$start_message]->seen || $mails[$start_message]->recent)
	{
  		$flags.='<span id="unread_img_'.$num.'"><a href="sssindex.php?module=Webmails&action=DetailView&'.$detailParams.'"><img src="modules/Webmails/images/stock_mail-unread.png" border="0" width="10" height="14"></a></span>&nbsp;';
	}
  	elseif ($mails[$start_message]->in_reply_to || $mails[$start_message]->references || preg_match("/^re:/i",$mails[$start_message]->subject))
		$flags.='<a href="javascript:;" onclick="OpenCompose(\''.$mails[$start_message]->msgno.'\',\'reply\');"><img src="modules/Webmails/images/stock_mail-replied.png" border="0" width="10" height="12"></a>&nbsp;';
  	elseif (preg_match("/^fw:/i",$mails[$start_message]->subject))
		$flags.='<a href="javascript:;" onclick="OpenCompose(\''.$mails[$start_message]->msgno.'\',\'reply\');"><img src="modules/Webmails/images/stock_mail-forward.png" border="0" width="10" height="13"></a>&nbsp;';
  	else
  		$flags.='<a href="javascript:;" onclick="OpenCompose(\''.$mails[$start_message]->msgno.'\',\'reply\');"><img src="modules/Webmails/images/stock_mail-read.png" border="0" width="10" height="11"></a>&nbsp;';

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
        	$listview_entries[$num][] = '<td colspan="1" align="left" id="deleted_subject_'.$num.'"><s><a href="javascript:;" onclick="load_webmail(\''.$num.'\');">'.substr($mails[$start_message]->subject,0,50).'</a></s></td>';
        	$listview_entries[$num][] = '<td colspan="1" align="left" nowrap id="deleted_date_'.$num.'"><s>'.$mails[$start_message]->date.'</s></td>';
        	$listview_entries[$num][] = '<td colspan="1" align="left" id="deleted_from_'.$num.'"><s>'.substr($from,0,30).'</s></td>';
  	} elseif(!$mails[$start_message]->seen || $mails[$start_message]->recent) {
        	$listview_entries[$num][] = '<td colspan="1" align="left" ><b><a href="javascript:;" onclick="load_webmail(\''.$num.'\');" id="ndeleted_subject_'.$num.'">'.substr($mails[$start_message]->subject,0,50).'</a></b></td>';
        	$listview_entries[$num][] = '<td colspan="1" align="left" nowrap id="ndeleted_date_'.$num.'"><b>'.$mails[$start_message]->date.'</b> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>';
        	$listview_entries[$num][] = '<td  colspan="1" align="left" id="ndeleted_from_'.$num.'"><b>'.substr($from,0,30).'</b></td>';
  	} else {
        	$listview_entries[$num][] = '<td colspan="1" align="left" ><a href="javascript:;" onclick="load_webmail(\''.$num.'\');" id="ndeleted_subject_'.$num.'">'.substr($mails[$start_message]->subject,0,50).'</a></td>';
        	$listview_entries[$num][] = '<td colspan="1" align="left" nowrap id="ndeleted_date_'.$num.'">'.$mails[$start_message]->date.'</td>';
        	$listview_entries[$num][] = '<td colspan="1" align="left" id="ndeleted_from_'.$num.'">'.substr($from,0,30).'</td>';
  	}

	if($mails[$start_message]->deleted)
  		$listview_entries[$num][] = '<td colspan="1" nowrap align="center" id="deleted_td_'.$num.'"><span id="del_link_'.$num.'"><a href="javascript:void(0);" onclick="runEmailCommand(\'undelete_msg\','.$num.');"><img src="modules/Webmails/images/gnome-fs-trash-full.png" border="0" width="14" height="14" alt="del"></a></span></td>';
	else
  		$listview_entries[$num][] = '<td nowrap colspan="1" align="center" id="ndeleted_td_'.$num.'"><span id="del_link_'.$num.'"><a href="javascript:void(0);" onclick="runEmailCommand(\'delete_msg\','.$num.');"><img src="modules/Webmails/images/gnome-fs-trash-empty.png" border="0" width="14" height="14" alt="del"></a></span></td>';



  	$i++;
  	$start_message--;
    }
}

$list = imap_getmailboxes($mbox, "{".$imapServerAddress."}", "*");
sort($list);
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

		if ($mailbox == $tmpval) {
                        $boxes .= '<option value="'.$tmpval.'" SELECTED>'.$tmpval;
			$box = imap_mailboxmsginfo($mbox);
			$folders .= '<li><img src="'.$image_path.'/'.$img.'" align="absmiddle" />&nbsp;&nbsp;<a href="javascript:changeMbox(\''.$tmpval.'\');" class="webMnu">'.$tmpval.'</a>&nbsp;&nbsp;<b>('.$box->Unread.' of '.$box->Nmsgs.')</b></li>';
		} else {
			if($ssltype == "") {$ssltype = "notls";}
			if($sslmeth == "") {$sslmeth = "novalidate-cert";}
			$tmbox = @imap_open("{".$imapServerAddress."/".$mail_protocol."/".$ssltype."/".$sslmeth."}".$tmpval, $login_username, $secretkey) or die("Connection to server failed ".imap_last_error());
			if(!$tmbox)
				$tmbox = @imap_open("{".$imapServerAddress."/".$mail_protocol."/}".$mailbox, $login_username, $secretkey) or die("Connection to server failed ".imap_last_error());
			$box = imap_mailboxmsginfo($tmbox);
                      	$boxes .= '<option value="'.$tmpval.'">'.$tmpval;
			$folders .= '<li><img src="'.$image_path.'/'.$img.'" align="absmiddle" />&nbsp;&nbsp;<a href="javascript:changeMbox(\''.$tmpval.'\');" class="webMnu">'.$tmpval.'</a>&nbsp;<b>('.$box->Unread.' of '.$box->Nmsgs.')</b></li>';
			imap_close($tmbox);
		}
 	}
        $boxes .= '</select>';
}

imap_close($mbox);
//print_r($listview_entries);
global $current_user;

$smarty = new vtigerCRM_Smarty;

$smarty->assign("USERID", $current_user->id);
$smarty->assign("CUSTOMVIEW_OPTION",$customviewcombo_html);
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("LISTENTITY", $listview_entries);
$smarty->assign("LISTHEADER", $listview_header);
$smarty->assign("MODULE","Webmails");
$smarty->assign("SINGLE_MOD",'Webmails');
$smarty->assign("BUTTONS",$other_text);
$smarty->assign("CATEGORY","My  Home Page");
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("FOLDER_SELECT", $boxes);
$smarty->assign("NUM_EMAILS", $numEmails);
$smarty->assign("MAILBOX", $mailbox);
$smarty->assign("ACCOUNT", $account_name);
$smarty->assign("BOXLIST",$folders);
$smarty->display("Webmails.tpl");
//$smarty->display("ListView.tpl");

?>
