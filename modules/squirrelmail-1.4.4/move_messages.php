<?php

/**
 * move_messages.php
 *
 * Copyright (c) 1999-2005 The SquirrelMail Project Team
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Enables message moving between folders on the IMAP server.
 *
 * $Id$
 */

/* Path for SquirrelMail required files. */
//define('SM_PATH','../');
define('SM_PATH','modules/squirrelmail-1.4.4/');
/* SquirrelMail required files. */
require_once(SM_PATH . 'include/validate.php');
require_once(SM_PATH . 'functions/global.php');
require_once(SM_PATH . 'functions/display_messages.php');
require_once(SM_PATH . 'functions/imap.php');
require_once(SM_PATH . 'functions/html.php');

global $compose_new_win;

if ( !sqgetGlobalVar('composesession', $composesession, SQ_SESSION) ) {
  $composesession = 0;
}

function attachSelectedMessages($msg, $imapConnection) {
    global $username, $attachment_dir, $startMessage,
           $data_dir, $composesession, $uid_support,
       $msgs, $thread_sort_messages, $allow_server_sort, $show_num,
       $compose_messages;

    if (!isset($compose_messages)) {
        $compose_messages = array();
            sqsession_register($compose_messages,'compose_messages');
    }

    if (!$composesession) {
        $composesession = 1;
            sqsession_register($composesession,'composesession');
    } else {
        $composesession++;
        sqsession_register($composesession,'composesession');
    }

    $hashed_attachment_dir = getHashedDir($username, $attachment_dir, $composesession);

    if ($thread_sort_messages || $allow_server_sort) {
       $start_index=0;
    } else {
       $start_index = ($startMessage-1) * $show_num;
    }

    $i = 0;
    $j = 0;
    $hashed_attachment_dir = getHashedDir($username, $attachment_dir);

    $composeMessage = new Message();
    $rfc822_header = new Rfc822Header();
    $composeMessage->rfc822_header = $rfc822_header;
    $composeMessage->reply_rfc822_header = '';

    while ($j < count($msg)) {
        if (isset($msg[$i])) {
            $id = $msg[$i];
            $body_a = sqimap_run_command($imapConnection, "FETCH $id RFC822",true, $response, $readmessage, $uid_support);
            if ($response == 'OK') {

                // fetch the subject for the message with $id from msgs.
                // is there a more efficient way to do this?
                foreach($msgs as $k => $vals) {
                    if($vals['ID'] == $id) {
                        $subject = $msgs[$k]['SUBJECT'];
                        break;
                    }
                }

                array_shift($body_a);
                array_pop($body_a);
                $body = implode('', $body_a);
                $body .= "\r\n";

                $localfilename = GenerateRandomString(32, 'FILE', 7);
                $full_localfilename = "$hashed_attachment_dir/$localfilename";

                $fp = fopen( $full_localfilename, 'wb');
                fwrite ($fp, $body);
                fclose($fp);
                $composeMessage->initAttachment('message/rfc822',$subject.'.msg',
                     $full_localfilename);
            }
            $j++;
        }
        $i++;
    }
    $compose_messages[$composesession] = $composeMessage;
    sqsession_register($compose_messages,'compose_messages');
    session_write_close();
    return $composesession;
}



/* get globals */
sqgetGlobalVar('key',       $key,           SQ_COOKIE);
sqgetGlobalVar('username',  $username,      SQ_SESSION);
sqgetGlobalVar('onetimepad',$onetimepad,    SQ_SESSION);
sqgetGlobalVar('delimiter', $delimiter,     SQ_SESSION);
sqgetGlobalVar('base_uri',  $base_uri,      SQ_SESSION);

sqgetGlobalVar('mailbox', $mailbox);
sqgetGlobalVar('startMessage', $startMessage);
sqgetGlobalVar('msg', $msg);

sqgetGlobalVar('msgs',              $msgs,              SQ_SESSION);
sqgetGlobalVar('composesession',    $composesession,    SQ_SESSION);
sqgetGlobalVar('lastTargetMailbox', $lastTargetMailbox, SQ_SESSION);

sqgetGlobalVar('moveButton',      $moveButton,      SQ_POST);
sqgetGlobalVar('expungeButton',   $expungeButton,   SQ_POST);
sqgetGlobalVar('targetMailbox',   $targetMailbox,   SQ_POST);
sqgetGlobalVar('expungeButton',   $expungeButton,   SQ_POST);
sqgetGlobalVar('undeleteButton',  $undeleteButton,  SQ_POST);
sqgetGlobalVar('markRead',        $markRead,        SQ_POST);
sqgetGlobalVar('markUnread',      $markUnread,      SQ_POST);
sqgetGlobalVar('attache',         $attache,         SQ_POST);
sqgetGlobalVar('addToVtigerCRMButton',         $addToVtigerCRMButton,         SQ_POST);
sqgetGlobalVar('location',        $location,        SQ_POST);

/* end of get globals */

global $current_user;
require_once('modules/Users/UserInfoUtil.php');
$mailInfo = getMailServerInfo($current_user);
$temprow = $adb->fetch_array($mailInfo);

$secretkey=$temprow["mail_password"];
$imapServerAddress=$temprow["mail_servername"];
$imapPort="143";


$key = OneTimePadEncrypt($secretkey, $onetimepad);
$imapConnection = sqimap_login($username, $key, $imapServerAddress, $imapPort, 0);
$mbx_response=sqimap_mailbox_select($imapConnection, $mailbox);

$location = set_url_var($location,'composenew',0,false);
$location = set_url_var($location,'composesession',0,false);
$location = set_url_var($location,'session',0,false);

/* remember changes to mailbox setting */
if (!isset($lastTargetMailbox)) 
{
    $lastTargetMailbox = 'INBOX';
}

if ($targetMailbox != $lastTargetMailbox)
{
    $lastTargetMailbox = $targetMailbox;
    sqsession_register($lastTargetMailbox, 'lastTargetMailbox');
}
$exception = false;

do_hook('move_before_move');


/*
    Move msg list sorting up here, as it is used several times,
    makes it more efficient to do it in one place for the code
*/
$id = array();
if (isset($msg) && is_array($msg))
{
  foreach( $msg as $key=>$uid )
  {
    // using foreach removes the risk of infinite loops that was there //
    $id[] = $uid;
  }
}

// expunge-on-demand if user isn't using move_to_trash or auto_expunge
if(isset($expungeButton))
{
  $cnt = sqimap_mailbox_expunge($imapConnection, $mailbox, true);
    if (($startMessage+$cnt-1) >= $mbx_response['EXISTS']) {
        if ($startMessage > $show_num) {
            $location = set_url_var($location,'startMessage',$startMessage-$show_num,false);
        } else {
            $location = set_url_var($location,'startMessage',1,false);
        }
    }
}
elseif(isset($undeleteButton))
{
  // undelete messages if user isn't using move_to_trash or auto_expunge
    // Removes \Deleted flag from selected messages
    if (count($id)) {
        sqimap_toggle_flag($imapConnection, $id, '\\Deleted',false,true);
    } else {
        $exception = true;
    }
}
elseif (!isset($moveButton) )
{
	//id is an array, so taking each id and storing it in a comma separated string
	if (count($id)) 
	{
		$cnt = count($id);
		if(isset($addToVtigerCRMButton))
		{
                  $msgsubject =  array();
                  $msgfromemail = array();
                  $mbodies = array();
                  sqgetGlobalVar('mailbox',       $mailbox);
                 
                  for($k=0;$k< count($id);$k++)
                  {

                    $message = sqimap_get_message($imapConnection, $id[$k], $mailbox);
                    $header = $message->rfc822_header;
                    
                    //from read_body.php
                    
                    $ent_ar = $message->findDisplayEntity(array(), array('text/plain'));
                    $cnt = count($ent_ar);
                    for ($u = 0; $u < $cnt; $u++)
                    {
                      $messagebody .= formatBody($imapConnection, $message, $color, $wrap_at, $ent_ar[$u], $id[$k], $mailbox);
                      $msgData = $messagebody;
                      /*
                      if ($i != $cnt-1)
                      {
                        $messagebody .= '<hr noshade size=1>';
                      }
                      */
                    }
                    $explodedmessage= explode("\n",$messagebody);
                    $implodedmessage = implode(":",$explodedmessage);
                    $from = $message->rfc822_header->getAddr_s('from');
                    
                    //$date = getLongDateString($message->rfc822_header->date);
                    //Richie : Changed the format to suit the Email part
                    $date = getVTigerLongDateString($message->rfc822_header->date);
                    /* 
				$subject = trim($rfc822_header->subject);
				$cc = $message->rfc822_header->getAddr_s('cc');
				$to = $message->rfc822_header->getAddr_s('to');
                                
                                echo 'date is ' .$date;
				echo 'cc is '.$cc;
				echo 'to is '.$to;
				echo 'subject is ' .$header->subject;
				print_r($header);
				exit;
                    */
                    $fromemail = $header->from[0]->mailbox .'@'.$header->from[0]->host;
                    $subject  = $header->subject ;
                    $msgsubject[$k]=$subject;
                    $msgfromemail[$k]=$fromemail;
                    $mbodies[$k] = $implodedmessage;
                  }
                  $tempidlist = implode(",", $id); 
                  $fromemail = implode(",",$msgfromemail);
                  $subject = implode(",",$msgsubject);
                  $detail = implode(",",$mbodies);
                  
                  header("Location: index.php?module=Emails&action=Save&fromemail=".$fromemail."&subject=".$subject."&idlist=".$tempidlist."&adddate=".$date."&detail=".$detail);      
                  return;
                }
    
                    if (!isset($attache))
                    {
                      if (isset($markRead))
                      {
                        sqimap_toggle_flag($imapConnection, $id, '\\Seen',true,true);
                      }
                      else if (isset($markUnread))
                      {
                        sqimap_toggle_flag($imapConnection, $id, '\\Seen',false,true);
                      }
                      else
                      {
                        sqimap_msgs_list_delete($imapConnection, $mailbox, $id);
                
                        if ($auto_expunge)
                        {
                          $cnt = sqimap_mailbox_expunge($imapConnection, $mailbox, true);
                        }
                        if (($startMessage+$cnt-1) >= $mbx_response['EXISTS'])
                        {
                          if ($startMessage > $show_num)
                          {
                            $location = set_url_var($location,'startMessage',$startMessage-$show_num, false);
                          }
                          else
                          {
                            $location = set_url_var($location,'startMessage',1, false);
                          }
                        }
                      }
                    }
                    else
                    {
                      $composesession = attachSelectedMessages($id, $imapConnection);
                      $location = set_url_var($location, 'session', $composesession, false);
                      if ($compose_new_win)
                      {
                        $location = set_url_var($location, 'composenew', 1, false);
                      }
                      else
                      {
                        $location = str_replace('index.php/module=squirrelmail-1.4.4&action=search','index.php/module=squirrelmail-1.4.4&action=compose',$location);
                        $location = str_replace('index.php/module=squirrelmail-1.4.4&action=right_main','index.php/module=squirrelmail-1.4.4&action=compose',$location);
                      }
                    }
                  }
                  else
                  {
                    $exception = true;
                  }
                }
else
{    // Move messages
  if (count($id))
    {
        sqimap_msgs_list_copy($imapConnection,$id,$targetMailbox);
          if ($auto_expunge)
	  {
            $cnt = sqimap_mailbox_expunge($imapConnection, $mailbox, true);
          }
	  else
	  {
            $cnt = 0;
          }

        if (($startMessage+$cnt-1) >= $mbx_response['EXISTS'])
       	{
            if ($startMessage > $show_num)
	    {
                $location = set_url_var($location,'startMessage',$startMessage-$show_num, false);
            }
	    else
	    {
                $location = set_url_var($location,'startMessage',1, false);
            }
        }
	
    }
    else
    {
      $exception = true;
    }
}





// Log out this session
sqimap_logout($imapConnection);
if ($exception)
{
 header("Location: index.php?module=squirrelmail-1.4.4&action=right_main");
// displayPageHeader($color, $mailbox);
  //  error_message(_("No messages were selected."), $mailbox, $sort, $startMessage, $color);
}
else
{
    header("Location: $location");
    exit;
}
?>
</BODY></HTML>
