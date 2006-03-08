<?php

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once ($theme_path."layout_utils.php");
require_once('include/utils/utils.php');

global $current_user;
require_once('include/utils/UserInfoUtil.php');
$mailInfo = getMailServerInfo($current_user);
$temprow = $adb->fetch_array($mailInfo);

$login_username= $temprow["mail_username"];
$secretkey=$temprow["mail_password"];
$imapServerAddress=$temprow["mail_servername"];

global $mbox;
$mbox = @imap_open("\{$imapServerAddress/imap}INBOX", $login_username, $secretkey) or die("Connection to server failed");

$server = "mail.fosslabs.com";
$user = "mmbrich";
$pass = "mk2305";
$conn = @imap_open("\{$server/imap}INBOX", $user, $pass) or die("Connection to server failed");

$headers = @imap_headers($conn) or die("Couldn't get emails");
$numEmails = sizeof($headers); 

if($_REQUEST["start_msg"]) {$start_msg = $_REQUEST["start_msg"];}
?>

<table border="0" width="100%" cellpadding="0" cellspacing="0" align="center"><tr>
  <td>
<input type="hidden" name="msg" value="">
<input type="hidden" name="mailbox" value="INBOX">
<input type="hidden" name="startMessage" value="1">
<!-- MSG PAGE HEADER -->
<table border="0" width="90%" cellpadding="5"  cellspacing="0" class="formOuterBorder" style="background-color: #F5F5F5;" align="center">
	<tr>
	<td><FONT COLOR="#ABABAB">Previous</FONT>&nbsp;|&nbsp;<A HREF="index.php?module=squirrelmail-1.4.4&action=right_main&use_mailbox_cache=1&amp;startMessage=16&amp;mailbox=INBOX" >Next</A>&nbsp;|&nbsp;1&nbsp;

&nbsp; | &nbsp; <a href="index.php?module=squirrelmail-1.4.4&action=options">Options</a></td>
	<td align="right">Viewing Messages: <b>1</b> to <b>15</b> (1123 Total)</td>
	</tr>
	</table>

	<br>
	<table border="0" width="90%" cellpadding="1"  cellspacing="0" align="center">
	<tr>
	<td width="20%" class="formHeader">WebMails List</td>

<!-- VTIGER AND FETCH BUTTONS -->
	<td><input type="button" class="button" name="fetchmail" value="Fetch My Mails" onclick=document.location.href="index.php?module=Webmails&action=index";></input></td>
	<td width="50%"><input type="SUBMIT" name="addToVtigerCRMButton" value="Add to vtiger CRM" class="button"></input></td>

<!-- RIGHT BUTTONS -->
        <td><input type="SUBMIT" name="markRead" value="Read" class="button"></input> </td>
	<td align="right"><input type="SUBMIT" name="markUnread" value="Unread" class="button"></input> </td>
	<td align="right"><input type="SUBMIT" name="delete" value="Delete" class="button"></input> </td>
	</tr>
	</table>
</td></tr>
</table>

<!-- MAIN MSG LIST TABLE -->
<table width="90%" cellpadding="1" cellspacing="0" align="center" border="0" class=""><tr><td>
<table width="100%" cellpadding="1" cellspacing="0" align="center" border="0"><tr><td><tr align="center" class="formSecHeader">
<td align="left"><input type="checkbox" name="checkall"></td>
<td align="left" width="50%">
Subject <a href="index.php?module=squirrelmail-1.4.4&action=right_main&newsort=4&amp;startMessage=1&amp;mailbox=INBOX"><IMG SRC="modules/squirrelmail-1.4.4/images/sort_none.png" BORDER=0 WIDTH=12 HEIGHT=10 ALT="sort"></a></td>
<td align="left" width="20%" nowrap>
Date <a href="index.php?module=squirrelmail-1.4.4&action=right_main&newsort=1&amp;startMessage=1&amp;mailbox=INBOX"><IMG SRC="modules/squirrelmail-1.4.4/images/up_pointer.png" BORDER=0 WIDTH=12 HEIGHT=10 ALT="sort"></a></td>
<td align="left" width="25%">
From <a href="index.php?module=squirrelmail-1.4.4&action=right_main&newsort=2&amp;startMessage=1&amp;mailbox=INBOX"><IMG SRC="modules/squirrelmail-1.4.4/images/sort_none.png" BORDER=0 WIDTH=12 HEIGHT=10 ALT="sort"></a></td>
</tr>

<?php
$bodies = array();
for($i=$numEmails;$i>$numEmails-20;$i--)
{
$mailHeader = @imap_headerinfo($conn, $i);
$from = $mailHeader->fromaddress;
$subject = strip_tags($mailHeader->subject);
$date = $mailHeader->date;
if($subject == "")
	$subject="(No Subject)";

echo "<tr><td><input type='checkbox' name='".$i."' ></td>";
echo '<td align="left" width="50%"><a href="index.php?module=Webmails&action=ReadMail&mailid='.$i.'">'.$subject.'</a></td>';
echo '<td align="left" width="20%" nowrap>'.$date.'</td>';
echo '<td align="left" width="25%">'.$from.'</td></tr>';
}
imap_close($conn);
?>


</table>
</td></tr></table>



<!--script>
function toggleTab(id) {
   for (i=1;i<=3;i++) {
      if (i==id) {
         getObj("tab"+i).className="tabOn"
         getObj("tabcontent"+i).style.display="block"
	 set_cookie("prod_tab"+i,"block");
      } else {
         getObj("tab"+i).className="tabOff"
         getObj("tabcontent"+i).style.display="none"
	 set_cookie("prod_tab"+i,"none");
      }
   }
}

for(i=1;i<=3;i++)
{
	if(get_cookie("prod_tab"+i)!='' && get_cookie("prod_tab"+i)!=null)
	{
		if (get_cookie("prod_tab"+i) == 'block')
		{
			getObj("tab"+i).className="tabOn"
         		getObj("tabcontent"+i).style.display="block"
		}
		else
		{
			getObj("tab"+i).className="tabOff"
 	        	getObj("tabcontent"+i).style.display="none"
		}
	}
}
</script-->
