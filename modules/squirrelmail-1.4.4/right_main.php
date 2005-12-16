<?php

/**
 * right_main.php
 *
 * Copyright (c) 1999-2005 The SquirrelMail Project Team
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * This is where the mailboxes are listed. This controls most of what
 * goes on in SquirrelMail.
 *
 * @version $Id$
 * @package squirrelmail
 */

/**
 * Path for SquirrelMail required files.
 * @ignore
 */

echo get_module_title("Emails", $mod_strings['LBL_MODULE_TITLE'], true);
$submenu = array('LBL_EMAILS_TITLE'=>'index.php?module=Emails&action=index','LBL_WEBMAILS_TITLE'=>'index.php?module=squirrelmail-1.4.4&action=redirect');
$sec_arr = array('index.php?module=Emails&action=ListView.php'=>'Emails','index.php?module=squirrelmail-1.4.4&action=redirect'=>'Emails');
echo '<br>';
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
   <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
     <td class="tabStart">&nbsp;&nbsp;</td>
<?php

	if(isset($_REQUEST['smodule']) && $_REQUEST['smodule'] != '')
	{
		$classname = "tabOn";
	}
	else
	{
		$classname = "tabOff";
	}
	$listView = "ListView.php";
	foreach($submenu as $label=>$filename)
	{
		$cur_mod = $sec_arr[$filename];
		$cur_tabid = getTabid($cur_mod);

		if($tab_per_Data[$cur_tabid] == 0)
		{

			list($lbl,$sname,$title)=split("_",$label);
			if(stristr($label,"EMAILS"))
			{

				echo '<td class="tabOff" nowrap><a href="index.php?module=Emails&action=index&smodule='.$sname.'" class="tabLink">'.$mod_strings[$label].'</a></td>';

				$listView = $filename;
				$classname = "tabOff";
			}
			elseif(stristr($label,$_REQUEST['smodule']))
			{
				echo '<td class="tabOn" nowrap><a href="index.php?module=squirrelmail-1.4.4&action=redirect&smodule='.$_REQUEST['smodule'].'" class="tabLink">'.$mod_strings[$label].'</a></td>';
				$listView = $filename;
				$classname = "tabOff";
			}
			else
			{
				echo '<td class="'.$classname.'" nowrap><a href="index.php?module=squirrelmail-1.4.4&action=redirect&smodule='.$sname.'" class="tabLink">'.$mod_strings[$label].'</a></td>';
			}
			$classname = "tabOff";
		}

	}
?>
     <td width="100%" class="tabEnd">&nbsp;</td>
   </tr>
 </table></td>
 </tr>
 </table>
 <br>
<?
define('SM_PATH','modules/squirrelmail-1.4.4/');
//define('SM_PATH','../');
/* SquirrelMail required files. */
require_once(SM_PATH . 'include/validate.php');
require_once(SM_PATH . 'functions/global.php');
require_once(SM_PATH . 'functions/imap.php');
require_once(SM_PATH . 'functions/date.php');
require_once(SM_PATH . 'functions/mime.php');
require_once(SM_PATH . 'functions/mailbox_display.php');
require_once(SM_PATH . 'functions/display_messages.php');
require_once(SM_PATH . 'functions/html.php');
require_once(SM_PATH . 'functions/plugin.php');
/***********************************************************
 * incoming variables from URL:                            *
 *   $sort             Direction to sort by date           *
 *                        values:  0  -  descending order  *
 *                        values:  1  -  ascending order   *
 *   $startMessage     Message to start at                 *
 *    $mailbox          Full Mailbox name                  *
 *                                                         *
 * incoming from cookie:                                   *
 *    $key              pass                               *
 * incoming from session:                                  *
 *    $username         duh                                *
 *                                                         *
 ***********************************************************/

/* lets get the global vars we may need */
sqgetGlobalVar('username',  $username,      SQ_SESSION);
sqgetGlobalVar('onetimepad',$onetimepad,    SQ_SESSION);
sqgetGlobalVar('delimiter', $delimiter,     SQ_SESSION);
sqgetGlobalVar('base_uri',  $base_uri,      SQ_SESSION);
sqgetGlobalVar('mailbox',   $mailbox);
sqgetGlobalVar('lastTargetMailbox', $lastTargetMailbox, SQ_SESSION);
sqgetGlobalVar('numMessages'      , $numMessages,       SQ_SESSION);
sqgetGlobalVar('session',           $session,           SQ_GET);
sqgetGlobalVar('note',              $note,              SQ_GET);
sqgetGlobalVar('use_mailbox_cache', $use_mailbox_cache, SQ_GET);

if ( sqgetGlobalVar('startMessage', $temp) ) {
    $startMessage = (int) $temp;
}
if ( sqgetGlobalVar('PG_SHOWNUM', $temp) ) {
  $PG_SHOWNUM = (int) $temp;
}
if ( sqgetGlobalVar('PG_SHOWALL', $temp, SQ_GET) ) {
  $PG_SHOWALL = (int) $temp;
}
if ( sqgetGlobalVar('newsort', $temp, SQ_GET) ) {
  $newsort = (int) $temp;
}
if ( sqgetGlobalVar('checkall', $temp, SQ_GET) ) {
  $checkall = (int) $temp;
}
if ( sqgetGlobalVar('set_thread', $temp, SQ_GET) ) {
  $set_thread = (int) $temp;
}
if ( !sqgetGlobalVar('composenew', $composenew, SQ_GET) ) {
    $composenew = false;
}
global $current_user;
require_once('include/utils/UserInfoUtil.php');
$mailInfo = getMailServerInfo($current_user);
$temprow = $adb->fetch_array($mailInfo);
$secretkey=$temprow["mail_password"];
$imapServerAddress=$temprow["mail_servername"];

/* end of get globals */
//$secretkey="p1";
    $key = OneTimePadEncrypt($secretkey, $onetimepad);
/* Open a connection on the imap port (143) */
$imapConnection = sqimap_login($username, $key, $imapServerAddress, $imapPort, 0);

if (isset($PG_SHOWALL)) {
    if ($PG_SHOWALL) {
       $PG_SHOWNUM=999999;
       $show_num=$PG_SHOWNUM;
       sqsession_register($PG_SHOWNUM, 'PG_SHOWNUM');
    }
    else {
       sqsession_unregister('PG_SHOWNUM');
       unset($PG_SHOWNUM);
    }
}
else if( isset( $PG_SHOWNUM ) ) {
    $show_num = $PG_SHOWNUM;
}

if (!isset($show_num) || empty($show_num) || ($show_num == 0)) {
    setPref($data_dir, $username, 'show_num' , 15);
    $show_num = 15;
}

if (isset($newsort) && $newsort != $sort) {
    setPref($data_dir, $username, 'sort', $newsort);
}



/* If the page has been loaded without a specific mailbox, */
/* send them to the inbox                                  */
if (!isset($mailbox)) {
    $mailbox = 'INBOX';
    $startMessage = 1;
}


if (!isset($startMessage) || ($startMessage == '')) {
    $startMessage = 1;
}

/* compensate for the UW vulnerability. */
if ($imap_server_type == 'uw' && (strstr($mailbox, '../') || substr($mailbox, 0, 1) == '/')) {
   $mailbox = 'INBOX';
}


//$smoduleurl = "&smodule=".$_REQUEST['smodule'];
//echo '<input type="button" class="button" name="fetchmail" value="Fetch My Mails" onclick=document.location.href="index.php?module=squirrelmail-1.4.4&action=redirect'.$smoduleurl.'";></input>';
//echo '<hr>';

//Richie//Richie//Richie//Richie//Richie//Richie//Richie//Richie
//echo 'in right_main';

/* decide if we are thread sorting or not */
if ($allow_thread_sort == TRUE) {
    if (isset($set_thread)) {
        if ($set_thread == 1) {
            setPref($data_dir, $username, "thread_$mailbox", 1);
            $thread_sort_messages = '1';
        }
        elseif ($set_thread == 2)  {
            setPref($data_dir, $username, "thread_$mailbox", 0);
            $thread_sort_messages = '0';
        }
    }
    else {
        $thread_sort_messages = getPref($data_dir, $username, "thread_$mailbox");
    }
}
else {
    $thread_sort_messages = 0;
}

//do_hook ('generic_header');

sqimap_mailbox_select($imapConnection, $mailbox);
if ($composenew) {
    //$comp_uri = SM_PATH . 'compose.php?mailbox='. urlencode($mailbox).
    $comp_uri = 'index.php?module=squirrelmail-1.4.4&action=compose&mailbox='. urlencode($mailbox).
        "&session=$session";
    displayPageHeader($color, $mailbox, "comp_in_new('$comp_uri');", false);
} else {
    displayPageHeader($color, $mailbox);
}

//do_hook('right_main_after_header');
if (isset($note)) {
    echo html_tag( 'div', '<b>' . $note .'</b>', 'center' ) . "<br />\n";
}
if ( sqgetGlobalVar('just_logged_in', $just_logged_in, SQ_SESSION) ) {
    if ($just_logged_in == true) {
        $just_logged_in = false;
        sqsession_register($just_logged_in, 'just_logged_in');

        if (strlen(trim($motd)) > 0) {
            echo html_tag( 'table',
                        html_tag( 'tr',
                            html_tag( 'td',
                                html_tag( 'table',
                                    html_tag( 'tr',
                                        html_tag( 'td', $motd, 'center' )
                                    ) ,
                                '', $color[4], 'width="100%" cellpadding="5" cellspacing="1" border="0"' )
                             )
                        ) ,
                    'center', $color[9], 'width="70%" cellpadding="0" cellspacing="3" border="0"' );
        }
    }
}

if (isset($newsort)) {
    $sort = $newsort;
    sqsession_register($sort, 'sort');
}

/*********************************************************************
 * Check to see if we can use cache or not. Currently the only time  *
 * when you will not use it is when a link on the left hand frame is *
 * used. Also check to make sure we actually have the array in the   *
 * registered session data.  :)                                      *
 *********************************************************************/
if (! isset($use_mailbox_cache)) {
    $use_mailbox_cache = 0;
}


if ($use_mailbox_cache && sqsession_is_registered('msgs')) {
    showMessagesForMailbox($imapConnection, $mailbox, $numMessages, $startMessage, $sort, $color, $show_num, $use_mailbox_cache);
} else {
    if (sqsession_is_registered('msgs')) {
        unset($msgs);
    }

    if (sqsession_is_registered('msort')) {
        unset($msort);
    }

    if (sqsession_is_registered('numMessages')) {
        unset($numMessages);
    }

    $numMessages = sqimap_get_num_messages ($imapConnection, $mailbox);
    showMessagesForMailbox($imapConnection, $mailbox, $numMessages,
                           $startMessage, $sort, $color, $show_num,
                           $use_mailbox_cache);

    if (sqsession_is_registered('msgs') && isset($msgs)) {
        sqsession_register($msgs, 'msgs');
    }

    if (sqsession_is_registered('msort') && isset($msort)) {
        sqsession_register($msort, 'msort');
    }

    sqsession_register($numMessages, 'numMessages');
}
//do_hook('right_main_bottom');
sqimap_logout ($imapConnection);

echo '</body></html>';

?>
