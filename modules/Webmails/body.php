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

global $current_user;
require_once('include/utils/utils.php');
require_once('include/utils/UserInfoUtil.php');
require_once('modules/Webmails/Webmails.php');
require_once('modules/Webmails/MailBox.php');
global $mod_strings;

if(!isset($_SESSION["authenticated_user_id"]) || $_SESSION["authenticated_user_id"] != $current_user->id) {echo "ajax failed";flush();exit();}
$mailid=$_REQUEST["mailid"];
if(isset($_REQUEST["mailbox"]) && $_REQUEST["mailbox"] != "")
{
	$mailbox=$_REQUEST["mailbox"];
}
else
{
	$mailbox="INBOX";
}
$MailBox = new MailBox($mailbox);
$mail = $MailBox->mbox;
$email = new Webmails($MailBox->mbox,$mailid);
$status=imap_setflag_full($MailBox->mbox,$mailid,"\\Seen");
?>
<script type="text/javascript">
function show_inline(num) {
	var el = document.getElementById("block_"+num);
	if(el.style.display == 'block')
		el.style.display='none';
	else
		el.style.display='block';
}
</script>
<?php
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
$attach_tab=array();
$email->loadMail($attach_tab);
$content['body'] = '<span id="webmail_body">'.$email->body.'</span>';
$content['attachtab'] = $email->attachtab;
//Need to put this along with the subject block
echo $email->att;
echo $content['body'];

//test added by Richie
if (!isset($_REQUEST['display_images']) || $_REQUEST['display_images'] != 1)
{
	$content['body'] = eregi_replace('src="[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]"', 'src="none"', $content['body']);
	$content['body'] = eregi_replace('src=[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]', 'src="none"', $content['body']);
}

//Display embedded HTML images
$tmp_attach_tab=$content['attachtab'];
$i = 0;
$conf->display_img_attach = true;
$conf->display_text_attach = true;

while ($tmp = array_pop($tmp_attach_tab)) 
{
	if ($conf->display_img_attach && (eregi('image', $tmp['mime']) && ($tmp['number'] != '')))
	{
		$exploded = explode('/', $tmp['mime']);
		$img_type = array_pop($exploded);
		if (eregi('JPEG', $img_type) || eregi('JPG', $img_type) || eregi('GIF', $img_type) || eregi ('PNG', $img_type))
		{
			$new_img_src = 'src="get_img.php?mail=' . $mailid.'&num=' . $tmp['number'] . '&mime=' . $img_type . '&transfer=' . $tmp['transfer'] . '"';
			$img_id = str_replace('<', '', $tmp['id']);
			$img_id = str_replace('>', '', $img_id);
			$content['body'] = str_replace('src="cid:'.$img_id.'"', $new_img_src, $content['body']);
			$content['body'] = str_replace('src=cid:'.$img_id, $new_img_src, $content['body']);
		}
		}
}
while ($tmp = array_pop($content['attachtab']))
{
	if ((!eregi('ATTACHMENT', $tmp['disposition'])) && $conf->display_text_attach && (eregi('text/plain', $tmp['mime'])))
		echo '<hr />'.view_part_detail($mail, $mailid, $tmp['number'], $tmp['transfer'], $tmp['charset'], $charset);
	if ($conf->display_img_attach && (eregi('image', $tmp['mime']) && ($tmp['number'] != '')))
	{
		$exploded = explode('/', $tmp['mime']);
		$img_type = array_pop($exploded);
		if (eregi('JPEG', $img_type) || eregi('JPG', $img_type) || eregi('GIF', $img_type) || eregi ('PNG', $img_type))
                        {
			echo '<hr />';
			echo '<center>';
			echo $mod_strings['LBL_LOADING_IMAGE'];
			echo '..........<br>';
			echo '<img src="index.php?module=Webmails&action=get_img&mail=' . $mailid.'&num=' . $tmp['number'] . '&mime=' . $img_type . '&transfer=' . $tmp['transfer'] . '" />';
			echo '</center>';
	}                
}                    
}


//test ended by Richie

imap_close($MailBox->mbox);


?>
<script>parent.document.getElementById('webmail_attachment').innerHTML=document.getElementById('webmail_cont').innerHTML</script>
