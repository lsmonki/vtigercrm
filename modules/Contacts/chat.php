<?php
/*
    Copyright 2005 Rolando Gonzalez (rolosworld@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**** config  *****/

/**
 * MySQL server configuration
 */
//$db = array();
//$db['host'] = "localhost:3306";
//$db['user'] = "root";
//$db['pass'] = "";
//$db['database'] = "vtigercrm01_03";
require_once('include/database/PearDatabase.php');
/**
 * Constants for the chat
 */
$chat_conf = array();
$chat_conf['alive_time'] = "30"; // time vtiger_users should vtiger_report to be online, in seconds.
$chat_conf['msg_limit'] = "10"; // maximum msg's to send in one request.

/*************************************************************/
/*** YOU SHOULD NOT NEED TO EDIT ANYTHING ELSE BELOW THIS. ***/
/*************************************************************/

session_name("AjaxPopupChat");
//session_save_path("sessions");
session_start();


function mysqlQuery($query)
{
	global $adb;
  $result = $adb->query($query);
  return $result;
}

/**** handler *****/
/**
 * Chat object
 */
class Chat
{
  // stores the string to be returned
  var $json;
  
  function Chat()
  {
	  global $adb;
    $this->json = '';
    
    // las message id received by user
    if(!isset($_SESSION["mlid"]))
      {
	$res = $adb->query("SELECT max(id) AS id FROM vtiger_chat_msg");
	$line = $adb->fetch_array($res);
	if(intval($line['id']) == 0)
	  $_SESSION["mlid"] = 0;
	else
	  $_SESSION["mlid"] = intval($line['id']) - 1;
      }
    
    // when the las user list was sended.
    if(!isset($_SESSION["lul"]))
      {
	$_SESSION["lul"] = 0;
      }

    // check if user is active.
    if(!isset($_SESSION['chat_user']))
      {
	$this->setUserNick();
      }
    else
      {
	      $res = $adb->query("UPDATE vtiger_chat_users SET ping = ".$adb->database->sysTimeStamp." WHERE session = '".session_id()."'");
	if($adb->getAffectedRowCount($res) == 0)
	  {
	    $this->setUserNick();
	  }
      }
    
    switch($_POST['submode'])
      {
	// request all the json data at once.
      case 'get_all':
	global $chat_conf;
	$this->lastMsgId();
	
	$this->json = '[%s]';
	$this->getAllPVChat();
	$pvchat = $this->json;

	$this->json = '[%s]';
	$this->getPubChat();
	$pchat = $this->json;

	$this->json = '';
	if(time() - $_SESSION["lul"] > $chat_conf['alive_time'])
	  {
	    $_SESSION["lul"] = time();
	    $this->json = '[%s]';
	    $this->getUserList();
	  }
	$ulist = $this->json;
	
	$tmp = array();
	$this->json = '{%s}';
	if(strlen($ulist) > 0)
	  $tmp[] = '"ulist":'.$ulist;
	
	if(strlen($pvchat) > 0)
	  $tmp[] = '"pvchat":'.$pvchat;
	
	if(strlen($pchat) > 0)
	  $tmp[] = '"pchat":'.$pchat;
	
	$this->json = sprintf($this->json, implode(',',$tmp));
	break;

	// user is submiting a msg
      case 'submit':
	$this->submit($_POST['msg'],intval($_POST['to']));
	break;

	// user closed a private chat
      case 'pvclose':
	$this->pvClose(intval($_POST['to']));
	break;

      default:
	break;
      }
  }
  
  /**
   * returns the JSON created
   */
  function getAJAX()
  {
    return $this->json;
  }
  
  /**
   * Sets the user initial nickname.
   */
  function setUserNick()
  {
	global $current_user;	
	global $adb;
    $res = $adb->query("SELECT id FROM vtiger_chat_users WHERE session = '".session_id()."'");
    if($adb->num_rows($res) > 0)
      {
	$line = $adb->fetch_array($res);
	$_SESSION['chat_user'] = $line['id'];
	return;
      }
    
    $_SESSION['chat_user'] = $adb->getUniqueID('chat_users');
    
    $res = $adb->query("INSERT INTO vtiger_chat_users (id, nick, session, ping, ip)
   			 VALUES ('".$_SESSION['chat_user']."',
			 	'".$current_user->user_name."',
				 '".session_id()."',
				 ".$adb->database->sysTimeStamp.",
				 '".$_SERVER['REMOTE_ADDR']."')");
    //$res = $adb->query("select LAST_INSERT_ID()");
    //$line = $adb->fetch_array($res);
    //$_SESSION['chat_user'] = $line[0];
  }
  
  /**
   * generate the available vtiger_users list
   */
  function getUserList()
  {
	global $adb;
    global $chat_conf;
    $tmp = '';
    $delete_from = time() - $chat_conf['alive_time'];
    $res = $adb->query("DELETE FROM vtiger_chat_users
    			WHERE ping > ".$adb->formatDate($delete_from));
    $res = $adb->query("SELECT id, nick FROM vtiger_chat_users");
    if($adb->num_rows($res)==0)
      {
	$this->json = '';
	return;
      }

    while($line = $adb->fetch_array($res))
      {
	if($line['id'] != $_SESSION['chat_user'])
	  $tmp .= '{"uid":'.$line['id'].',"nick":"'.$line['nick'].'"},';
      }
    $tmp = trim($tmp,',');
    $this->json = sprintf($this->json,$tmp);
  }

  /**
   * Sets user last post received.
   */
  function lastMsgId()
  {
    if(isset($_POST['mlid']) && intval($_POST['mlid']) > $_SESSION["mlid"])
      $_SESSION["mlid"] = intval($_POST['mlid']);
  }

  /**
   * generates the private chat data
   */
  function getAllPVChat()
  {
	global $adb;
    global $chat_conf;
    $format = '{"mlid":%s,"chat":%s,"from":"%s","msg":"%s"},';
    $res = $adb->limitQuery("SELECT ms.id AS mid, ms.chat_from AS mfrom,
   				 ms.chat_to AS mto,pv.id AS id,
				 us.nick AS nfrom, ms.msg AS msg
			FROM vtiger_chat_users us
			INNER JOIN vtiger_chat_msg ms
				ON us.id = ms.chat_from
			INNER JOIN vtiger_chat_pvchat pv
				ON pv.msg = ms.id
			WHERE ms.id > '".($_SESSION['mlid'])."'
			AND (ms.chat_from = '".$_SESSION['chat_user']."'
				AND ms.chat_to > 0)
			OR (ms.chat_to = '".$_SESSION['chat_user']."'
				AND ms.chat_from>0)
			ORDER BY ms.born",
			0, $chat_conf['msg_limit']);
    if($adb->num_rows($res)==0)
      {
	$this->json = '';
	return;
      }

    $tmp = '';
    while($line = $adb->fetch_array($res))
      {
	if($line['mfrom'] == $_SESSION['chat_user'])
	  $cid = $line['mto'];
	else
	  $cid = $line['mfrom'];

	$tmp .= sprintf($format,$line['mid'],$cid,$line['nfrom'],addslashes($line['msg']));
      }
    $tmp = trim($tmp,',');
    $this->json = sprintf($this->json,$tmp);
  }


  /**
   * generates the public chat data
   * NOTE: this is alpha
   */
  function getPubChat()
  {
	global $adb;
    global $chat_conf;
    $format = '{"mlid":%s,"from":"%s","msg":"%s"},';
    $res = $adb->limitQuery("SELECT ms.id AS mid, ms.chat_from AS mfrom,
    				ms.chat_to AS mto, p.id AS id,
				us.nick AS nfrom, ms.msg AS msg
			FROM vtiger_chat_users us
			INNER JOIN vtiger_chat_msg ms
				ON us.id = ms.chat_from
			INNER JOIN vtiger_chat_pchat p
				ON p.msg = ms.id
			WHERE ms.id > '".($_SESSION['mlid'])."'
			AND ms.chat_to = 0
			ORDER BY ms.born",
			0, $chat_conf['msg_limit']);
    if($adb->num_rows($res)==0)
      {
	$this->json = '';
	return;
      }

    $tmp = '';
    while($line = $adb->fetch_array($res))
      {
	$tmp .= sprintf($format,$line['mid'],$line['nfrom'],addslashes($line['msg']));
      }
    $tmp = trim($tmp,',');
    $this->json = sprintf($this->json,$tmp);
  }

  /**
   * Check for special commands on message.
   */
  function msgParse($msg)
  {
	global $adb;
    if(strlen($msg) == 0) return '';
    $msg = stripslashes($msg);

    if($msg[0] == '\\')
      {
	$words = explode(" ",$msg);
	switch($words[0])
	  {
	  case '\nick':
	    if(isset($words[1]) && strlen($words[1]) > 3)
	      {
		$res = $adb->query("SELECT nick
				FROM vtiger_chat_users
				WHERE id= '".$_SESSION['chat_user']."'");
		$line = $adb->fetch_array($res);
		$res = $adb->query("UPDATE vtiger_chat_users
				SET nick = ".$adb->quote($words[1])."
				WHERE id = '".$_SESSION['chat_user']."'");
		$msg = '\sys <span class="sysb">'.$line['nick'].'</span> changed nick to <span class="sysb">'.$words[1].'</span>';
	      }
	    break;
	    
	  default:
	    $msg = '\sys Bad command: '.$words[0];
	    break;
	  }
      }
    return addslashes($msg);    
  }

  /**
   * process a submited msg
   */
  function submit($msg, $to=0)
  {
	global $adb;
    $msg = $this->msgParse($msg);
    if(strlen($msg) == 0) return;
    
    $id = $adb->getUniqueID('chat_msg');
    $res = $adb->query("INSERT INTO vtiger_chat_msg (id, chat_from, chat_to, born, msg)
    			VALUES (".$id.", '".$_SESSION['chat_user']."', '".$to."', ".$adb->database->sysTimeStamp.", '".$msg."')");
    
    $chat = "p";
    if($to != 0)
      $chat .= "v";
    
      $res = $adb->query("INSERT INTO chat_".$chat."chat (msg) VALUES (".$id.")");
  }

  /**
   * removes the private conversation msg's because someone closed it
   */
  function pvClose($to)
  {
	global $adb;
    $res = $adb->query("DELETE FROM vtiger_chat_msg
   			 WHERE (chat_from = '".$to."'
				 AND chat_to = '".$_SESSION['chat_user']."')
			 OR (chat_from = '".$_SESSION['chat_user']."'
				 AND chat_to = '".$to."')");
  }
}

/**** caller ****/
$chat = new Chat();
echo $chat->getAJAX();
?>
