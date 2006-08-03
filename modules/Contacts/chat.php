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
require_once('config.inc.php');
$db = array();

$db['host'] = $dbconfig['db_server']."".$dbconfig['db_port'];
$db['user'] = $dbconfig['db_username'];
$db['pass'] = $dbconfig['db_password'];
$db['database'] = $dbconfig['db_name'];

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

$dbh = mysql_connect($db['host'], $db['user'],$db['pass']) or die ('I cannot connect to the database');

mysql_select_db($db['database']);

function mysqlQuery($query)
{
  $result = mysql_query($query);
  if(!$result)
    {
      die("DB Error.<br />\n".mysql_error()."<br />\n".$query);
    }
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
    $this->json = '';
    
    // las message id received by user
    if(!isset($_SESSION["mlid"]))
      {
	$res = mysqlQuery("show table status like 'vtiger_chat_msg'");
	$line = mysql_fetch_array($res,MYSQL_ASSOC);
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
	$res = mysqlQuery("update vtiger_chat_users set ping=now() where session='".session_id()."'");
	if(mysql_affected_rows() == 0)
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
    $res = mysqlQuery("select id from vtiger_chat_users where session='".session_id()."'");
    if(mysql_num_rows($res) > 0)
      {
	$line = mysql_fetch_array($res,MYSQL_ASSOC);
	$_SESSION['chat_user'] = $line['id'];
	return;
      }
    
    $res = mysqlQuery("show table status like 'vtiger_chat_users'");
    $line = mysql_fetch_array($res,MYSQL_ASSOC);
    if(intval($line['Auto_increment']) == 0)
      $line['Auto_increment'] = 1;
    
    $_SESSION['chat_user'] = $line['Auto_increment'];
    
    $res = mysqlQuery("insert into vtiger_chat_users set nick='".$current_user->user_name."',session='".session_id()."',ping=now(),ip='".$_SERVER['REMOTE_ADDR']."'");
    //$res = mysqlQuery("select LAST_INSERT_ID()");
    //$line = mysql_fetch_array($res,MYSQL_ASSOC);
    //$_SESSION['chat_user'] = $line[0];
  }
  
  /**
   * generate the available users list
   */
  function getUserList()
  {
    global $chat_conf;
    $tmp = '';
    $res = mysqlQuery("delete from vtiger_chat_users where ((unix_timestamp(now())-unix_timestamp(ping))>'".$chat_conf['alive_time']."')");
    $res = mysqlQuery("select id,nick from vtiger_chat_users");
    if(mysql_num_rows($res)==0)
      {
	$this->json = '';
	return;
      }

    while($line = mysql_fetch_array($res,MYSQL_ASSOC))
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
    global $chat_conf;
    $format = '{"mlid":%s,"chat":%s,"from":"%s","msg":"%s"},';
    $res = mysqlQuery("select ms.id mid,ms.chat_from mfrom,ms.chat_to mto,pv.id id,us.nick `chat_from`,ms.msg msg from vtiger_chat_users us,vtiger_chat_pvchat pv,vtiger_chat_msg ms where pv.msg=ms.id and us.id=ms.chat_from and ms.id>'".($_SESSION['mlid'])."' and ((ms.chat_from='".$_SESSION['chat_user']."' and ms.chat_to>0) or (ms.chat_to='".$_SESSION['chat_user']."' and ms.chat_from>0)) order by ms.born limit 0,".$chat_conf['msg_limit']);
    if(mysql_num_rows($res)==0)
      {
	$this->json = '';
	return;
      }

    $tmp = '';
    while($line = mysql_fetch_array($res,MYSQL_ASSOC))
      {
	if($line['mfrom'] == $_SESSION['chat_user'])
	  $cid = $line['mto'];
	else
	  $cid = $line['mfrom'];

	$tmp .= sprintf($format,$line['mid'],$cid,$line['chat_from'],addslashes($line['msg']));
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
    global $chat_conf;
    $format = '{"mlid":%s,"from":"%s","msg":"%s"},';
    $res = mysqlQuery("select ms.id mid,ms.chat_from mfrom,ms.chat_to mto,p.id id,us.nick `chat_from`,ms.msg msg from vtiger_chat_users us,vtiger_chat_pchat p,vtiger_chat_msg ms where p.msg=ms.id and us.id=ms.chat_from and ms.id>'".($_SESSION['mlid'])."' and ms.chat_to=0 order by ms.born limit 0,".$chat_conf['msg_limit']);
    if(mysql_num_rows($res)==0)
      {
	$this->json = '';
	return;
      }

    $tmp = '';
    while($line = mysql_fetch_array($res,MYSQL_ASSOC))
      {
	$tmp .= sprintf($format,$line['mid'],$line['chat_from'],addslashes($line['msg']));
      }
    $tmp = trim($tmp,',');
    $this->json = sprintf($this->json,$tmp);
  }

  /**
   * Check for special commands on message.
   */
  function msgParse($msg)
  {
    if(strlen($msg) == 0) return '';
    $msg = stripslashes($msg);

    if($msg[0] == '\\')
      {
	$today_date = getdate();
		  
	$words = explode(" ",$msg);
	switch($words[0])
	  {
	  case '\nick':
	    if(isset($words[1]) && strlen($words[1]) > 3)
	      {
		$res = mysqlQuery("select nick from vtiger_chat_users where id='".$_SESSION['chat_user']."'");
		$line = mysql_fetch_array($res,MYSQL_ASSOC);
		$res = mysqlQuery("update vtiger_chat_users set nick='".addslashes($words[1])."' where id='".$_SESSION['chat_user']."'");
		$msg = '\sys <span class="sysb">'.$line['nick'].'</span> changed nick to <span class="sysb">'.$words[1].'</span>';
	      }
	    break;
	    
	  case '\help':
		$msg = '\sys <br><span class="sysb">\\\\nick "nickname" </span> - change nick<br><span class="sysb">\\\\date </span> - date<br><span class="sysb">\\\\time </span> - time<br><span class="sysb">\\\\month </span> - month<br><span class="sysb">\\\\day </span> - weekday';
	   break;
	  case '\date':
       		$msg = '\sys Today is <span class="sysb">'.date('d-m-Y').'</span>';		  
	   break;	
	   case '\time':
       		$msg = '\sys The Current time is <span class="sysb">'.$today_date["hours"].':'.$today_date["minutes"].':'.$today_date["hours"].'</span>';		 break;	
	case '\month':
       		$msg = '\sys <span class="sysb">'.$today_date["month"].'</span>';		 
	break;
	case '\day':
       		$msg = '\sys <span class="sysb">'.$today_date["weekday"].'</span>';		 
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
    $msg = $this->msgParse($msg);
    if(strlen($msg) == 0) return;
    
    $res = mysqlQuery("insert into vtiger_chat_msg set `chat_from`='".$_SESSION['chat_user']."',`chat_to`='".$to."',born=now(),msg='".$msg."'");
    
    $chat = "p";
    if($to != 0)
      $chat .= "v";
    
    $res = mysqlQuery("insert into vtiger_chat_".$chat."chat set msg=LAST_INSERT_ID()");
  }

  /**
   * removes the private conversation msg's because someone closed it
   */
  function pvClose($to)
  {
    $res = mysqlQuery("delete from vtiger_chat_msg where (`chat_from`='".$to."' and `chat_to`='".$_SESSION['chat_user']."') or (`chat_from`='".$_SESSION['chat_user']."' and `chat_to`='".$to."')");
  }
}

/**** caller ****/
$chat = new Chat();
echo $chat->getAJAX();
?>
