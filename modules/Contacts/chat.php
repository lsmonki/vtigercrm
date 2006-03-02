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
$chat_conf['alive_time'] = "30"; // time users should report to be online, in seconds.
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
	$res = $adb->query("show table status like 'chat_msg'");
	$line = $adb->fetch_array($res);
	if(intval($line['Auto_increment']) == 0)
	  $_SESSION["mlid"] = 0;
	else
	  $_SESSION["mlid"] = intval($line['Auto_increment']) - 1;
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
	$res = $adb->query("update chat_users set ping=now() where session='".session_id()."'");
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
    $res = $adb->query("select id from chat_users where session='".session_id()."'");
    if($adb->num_rows($res) > 0)
      {
	$line = $adb->fetch_array($res);
	$_SESSION['chat_user'] = $line['id'];
	return;
      }
    
    $res = $adb->query("show table status like 'chat_users'");
    $line = $adb->fetch_array($res);
    if(intval($line['Auto_increment']) == 0)
      $line['Auto_increment'] = 1;
    
    $_SESSION['chat_user'] = $line['Auto_increment'];
    
    $res = $adb->query("insert into chat_users set nick='".$current_user->user_name."',session='".session_id()."',ping=now(),ip='".$_SERVER['REMOTE_ADDR']."'");
    //$res = $adb->query("select LAST_INSERT_ID()");
    //$line = $adb->fetch_array($res);
    //$_SESSION['chat_user'] = $line[0];
  }
  
  /**
   * generate the available users list
   */
  function getUserList()
  {
	global $adb;
    global $chat_conf;
    $tmp = '';
    $res = $adb->query("delete from chat_users where ((unix_timestamp(now())-unix_timestamp(ping))>'".$chat_conf['alive_time']."')");
    $res = $adb->query("select id,nick from chat_users");
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
    $res = $adb->query("select ms.id mid,ms.chat_from mfrom,ms.chat_to mto,pv.id id,us.nick `from`,ms.msg msg from chat_users us,chat_pvchat pv,chat_msg ms where pv.msg=ms.id and us.id=ms.chat_from and ms.id>'".($_SESSION['mlid'])."' and ((ms.chat_from='".$_SESSION['chat_user']."' and ms.chat_to>0) or (ms.chat_to='".$_SESSION['chat_user']."' and ms.chat_from>0)) order by ms.born limit 0,".$chat_conf['msg_limit']);
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

	$tmp .= sprintf($format,$line['mid'],$cid,$line['from'],addslashes($line['msg']));
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
    $res = $adb->query("select ms.id mid,ms.chat_from mfrom,ms.chat_to mto,p.id id,us.nick `from`,ms.msg msg from chat_users us,chat_pchat p,chat_msg ms where p.msg=ms.id and us.id=ms.chat_from and ms.id>'".($_SESSION['mlid'])."' and ms.chat_to=0 order by ms.born limit 0,".$chat_conf['msg_limit']);
    if($adb->num_rows($res)==0)
      {
	$this->json = '';
	return;
      }

    $tmp = '';
    while($line = $adb->fetch_array($res))
      {
	$tmp .= sprintf($format,$line['mid'],$line['from'],addslashes($line['msg']));
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
		$res = $adb->query("select nick from chat_users where id='".$_SESSION['chat_user']."'");
		$line = $adb->fetch_array($res);
		$res = $adb->query("update chat_users set nick='".addslashes($words[1])."' where id='".$_SESSION['chat_user']."'");
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
    
    $res = $adb->query("insert into chat_msg set `chat_from`='".$_SESSION['chat_user']."',`chat_to`='".$to."',born=now(),msg='".$msg."'");
    
    $chat = "p";
    if($to != 0)
      $chat .= "v";
    
    $res = $adb->query("insert into chat_".$chat."chat set msg=LAST_INSERT_ID()");
  }

  /**
   * removes the private conversation msg's because someone closed it
   */
  function pvClose($to)
  {
	global $adb;
    $res = $adb->query("delete from chat_msg where (`chat_from`='".$to."' and `chat_to`='".$_SESSION['chat_user']."') or (`chat_from`='".$_SESSION['chat_user']."' and `chat_to`='".$to."')");
  }
}

/**** caller ****/
$chat = new Chat();
echo $chat->getAJAX();
?>
