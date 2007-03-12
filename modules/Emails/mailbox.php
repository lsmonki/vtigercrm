<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

//get the mail server info
global $current_user;
require_once('include/utils/UserInfoUtil.php');
$mailInfo = getMailServerInfo($current_user);
   $temprow = $adb->fetch_array($mailInfo);
	if($temprow["mail_protocol"] == 'POP')
	{
         $ServerName = "{".$temprow["mail_servername"]."/pop3:110/notls}INBOX"; 
        }
	else
	{
   $ServerName = "{".$temprow["mail_servername"]."/imap:143/notls}INBOX";
   // For a IMAP connection    (PORT 143)
  	}
   $UserName = $temprow["mail_username"];
   $PassWord = $temprow["mail_password"];
   
   $mbox = imap_open($ServerName, $UserName,$PassWord) or die("Could not open Mailbox - try again later!");
   
   if ($hdr = imap_check($mbox)) {
	   echo "Total Messages  :-> " . $hdr->Nmsgs ."\n\n<br><br>";
   	$msgCount = $hdr->Nmsgs;
   } else {
   	echo "failed";
   }
$MN=$msgCount;
$overview=imap_fetch_overview($mbox,"1:$MN",0);
$mime_type = get_mime_type($overview);
//$size=sizeof($overview);
$size = imap_num_msg($mbox);

global $app_strings,$current_user;

if(isset($_REQUEST['view']) && $_REQUEST['view']!='')
{
  $msgid = $_REQUEST['view'];
  $header = @imap_headerinfo($mbox, $msgid, 80, 80);
  $fromaddress[$msgid] = $header->from[0]->host;
  $domain = $fromaddress[$msgid];
  $fromname[$msgid] = $header->from[0]->mailbox;
  $sendername=$fromname[$msgid];
  $from[$msgid]= $fromname[$msgid]."@".$fromaddress[$msgid];
  $totalfromaddress = $sendername ."@".$domain;
	  $val=$overview[$msgid-1];
	  $msg=$val->msgno;
	  $from=$val->from;
	  $date=$val->date;
	  $subj=$val->subject;
	  //transformHTML($subj);
	  $seen=$val->seen;
   
	  $from = ereg_replace("\"","",$from);
   
	  echo '<script language="JavaScript">
   	   var b;
	   browser = navigator.appName;
	   if (browser == "Microsoft Internet Explorer") {
	   	b = "ie";
	   } else {
	   	b = "other";
	   }
	      
	   function handleFile(nr) {
	   	if (b != "ie") {
 	      		alert('.$mod_strings['FEATURE_AVAILABLE_INFO'].');
	   	} else {
	   		check = confirm('.$mod_strings['DOWNLOAD_CONFIRAMATION'].');
			if (check) {
				setTimeout("this.location.reload()",8000);
				location.href="index.php?action=gotodownload&module=Emails&download=1&file="+ nr +"&msgno='.$msg.'";					     } else {
				location.reload();
			}
		}
	 }
	</script>';		   

	  // MAKE DANISH DATE DISPLAY
	  list($dayName,$day,$month,$year,$time) = split(" ",$date); 
	  $time = substr($time,0,5);
	  $date = $day ." ". $month ." ". $year . " ". $time;
   
	  if ($bgColor == "#F0F0F0") {
	    $bgColor = "#FFFFFF";
	  } else {
	    $bgColor = "#F0F0F0";
  	  }
   
	  if (strlen($subj) > 60) {
	    $subj = substr($subj,0,59) ."...";
	    get_part();
  	  }
	
	echo get_module_title("Emails",'Emails', true);
	echo "<br>";
	echo "<form action='' method=post>";
	echo '<table border="0" cellpadding="0" cellspacing="1" width="80%" class="formOuterBorder">';
	echo '<tr><td class="formSecHeader" colspan=2>Email Information</td></tr>';
	echo "<tr><td class='datalabel' width='15%'>From:</td><td>" . $from ."</td></tr>";
	echo "<tr><td class='datalabel'>Date:</td><td>" .$date ."</td></tr>";
	echo "<tr><td class='datalabel'>Subject:</td><td>" .$subj."</td></tr>";
	echo "<tr><td class='datalabel' valign=top>Mail body:</td><td>";
	$content = get_part($mbox,$msg,$mime_type);
	echo nl2br($content);
	echo "</td></tr></table>";
	//get the attachment
	$struct = imap_fetchstructure($mbox,$msg);
	$contentParts = count($struct->parts);
	      if ($contentParts >= 2) {
	      	   for ($i=2;$i<=$contentParts;$i++) {
		      	$att[$i-2] = imap_bodystruct($mbox,$msg,$i);
	   	   }
		   for ($k=0;$k<sizeof($att);$k++) {
			if ($att[$k]->parameters[0]->value == "us-ascii" || $att[$k]->parameters[0]->value    == "US-ASCII") {
				if ($att[$k]->parameters[1]->value != "") {
					$selectBoxDisplay[$k] = $att[$k]->parameters[1]->value;
				}
   		        } 
			elseif ($att[$k]->parameters[0]->value != $app_strings['LBL_CHARSET'] &&  $att[$k]->parameters[0]->value != $app_strings['LBL_CHARSET']) {
				$selectBoxDisplay[$k] = $att[$k]->parameters[0]->value;
			}
		    }
		}
		
		if (sizeof($selectBoxDisplay) > 0) {
		       	echo "<br><select name=\"attachments\" size=\"3\" class=\"tblContent\"    onChange=\"handleFile(this.value)\" style=\"width:170;\">";
		   	for ($j=0;$j<sizeof($selectBoxDisplay);$j++) {
		 		echo "\n<option value=\"$j\">". $selectBoxDisplay[$j]    ."</option>";
		   	}
		   	echo "</select>";
		}	

        echo '<form name=f1 action=index.php method=post>
	        <input type="hidden" name="module" value="Emails">
        <input type="hidden" name="action">
        <input type="hidden" name="return_module" value="Emails">
        <input type="hidden" name="return_id" value="{RETURN_ID}">
        <input type="hidden" name="return_action" value="mailbox">
        <input type="hidden" name="from" value="'.$from .'">;
          <input type="hidden" name="sname" value="'.$ServerName .'">
          <input type="hidden" name="uname" value="'.$UserName .'">
         <input type="hidden" name="passwd" value="'.$PassWord .'">';
        
        
	
        $date_fmt = Array();
	list($mday,$mmon,$myear,$mtime)=split(" ",$date);
	$maildate = strtotime($mday." ".$mmon." ".$myear);
	list($date_fmt[0],$date_fmt[1],$date_fmt[2]) = split("-",$current_user->date_format);
	for($i=0;$i<=2;$i++)
	{
		if(stristr($date_fmt[$i],"d"))
		$date_fmt[$i] = "d";
		elseif(stristr($date_fmt[$i],"m"))
		$date_fmt[$i] = "m";
		elseif(stristr($date_fmt[$i],"y"))
		$date_fmt[$i] = "Y";
	}
	#echo date("$date_fmt[0]-$date_fmt[1]-$date_fmt[2]",$maildate);
	echo '<input type=hidden name="date_start" value="'.date("$date_fmt[0]-$date_fmt[1]-$date_fmt[2]",$maildate).'">
	<input type=hidden name="time_start" value="'.$mtime.'">
	<input type=hidden name="assigned_user_id" value="'.$current_user->id.'">
	<input type=hidden name="description" value="'.$content.'">
	<input type=hidden name="subject" value="'.$subj.'">
        <input type=hidden name="fromemail" value="'.$totalfromaddress.'">
        <br><input type=checkbox name=addbox value=Add>  Add to vtiger CRM  <br>
        <input type=checkbox name=deletebox value='.$msg.'> Delete from Mail Server <br>';
    echo '<br><table width=80%><tr><td align=center><input title="'.$app_strings[LBL_ADD_VTIGER_BUTTON_TITLE].'" accessKey="'.$app_strings['LBL_ADD_VTIGER_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'Save\';" type="submit" name="button" value="'.$app_strings['LBL_SAVE_LABEL'].'">&nbsp;<input class=button type=button value=Cancel onclick="window.history.back()" ></td></tr></table>
	</form>';

}
else
{
	echo get_module_title("Emails",'Emails', true); 
	echo "<br>";
	echo "<form action='index.php>module=Emails&action=Save' method=post>";
	echo '<table border="0" cellpadding="0" cellspacing="0" width="80%"><tr><td>';
	echo get_form_header("Received Emails", "<table width=\"100%\" border=\"0\" cellpadding=\"1\" cellspacing=\"0\"><tr></tr></table>", false );
	echo "</td></tr></table>";
	echo '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="80%">';
	echo '<tr class="ModuleListTitle" height=20>';
	echo '<td width="10" class="moduleListTitle" style="padding:0px 3px 0px 3px;"></td>';
	echo '<td width="15" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><input type="checkbox" name="selectall" onClick=toggleSelect(this.checked,"selected_id")></td>';
	echo '<td width="" class="moduleListTitle" style="padding:0px 3px 0px 3px;">Sender</td>';
	echo '<td width="" class="moduleListTitle" style="padding:0px 3px 0px 3px;">Subject</td>';
	echo '<td width="" class="moduleListTitle" style="padding:0px 3px 0px 3px;">Date</td>';
	echo '</tr>';
        
	for($i=$size-1;$i>=0;$i--)
	{
          $j = $i+1;
          $header = @imap_headerinfo($mbox, $j, 80, 80);
          
          $fromaddress[$j] = $header->from[0]->host;
          $domain = $fromaddress[$j];
          $fromname[$j] = $header->from[0]->mailbox;
          $sendername=$fromname[$j];
          $sender[$j]= $fromname[$j]."@".$fromaddress[$j];
          $totalfromaddress = $sendername ."@".$domain;
          
	  $val=$overview[$i];
	  $msg=$val->msgno;
          $from=$val->from;
         
	  $date=$val->date;
	  $subj=$val->subject;
	  //transformHTML($subj);
	  $seen=$val->seen;
          //imap_delete($mbox, 1);
          //imap_expunge($mbox);

	  $from = ereg_replace("\"","",$from);
   
	  // MAKE DANISH DATE DISPLAY
	  list($dayName,$day,$month,$year,$time) = split(" ",$date); 
	  $time = substr($time,0,5);
	  $date = $day ." ". $month ." ". $year . " ". $time;
   
	  if ($bgColor == "#F0F0F0") {
	    $bgColor = "#FFFFFF";
	    $rowClass = "oddListRow";
	  } else {
	    $bgColor = "#F0F0F0";
	    $rowClass = "evenListrow";
  	  }
   
	  if (strlen($subj) > 60) {
	    $subj = substr($subj,0,59) ."...";
	    get_part();
  	  }
          
	  echo "<tr class=\"$rowClass\"><td height=\"21\" style=\"padding:0px 3px 0px 3px;\">$msg</td><td style=\"padding:0px 3px 0px 3px;\"><input type=checkbox NAME=\"selected_id\" onClick=toggleSelectAll(this.name,\"selectall\")>$sendername</td><td style=\"padding:0px 3px 0px 3px;\">$totalfromaddress </td><td style=\"padding:0px 3px 0px 3px;\"><a href=\"index.php?action=mailbox&module=Emails&view=".$j."\">$subj</a></td>
   		 <td style=\"padding:0px 3px 0px 3px;\">$date</td></tr>\n ";
	}

	echo "</table>";
	echo "</form>";
}
imap_close($mbox);





function get_mime_type(&$structure) 
{
	global $log;
	$log->debug("Entering get_mime_type(".$structure.") method ...");
  $primary_mime_type = array("TEXT", "MULTIPART","MESSAGE", "APPLICATION", "AUDIO","IMAGE", "VIDEO", "OTHER");
  if($structure->subtype) {
	$log->debug("Exiting get_mime_type method ...");
    return $primary_mime_type[(int) $structure->type] . '/' .$structure->subtype;
  }
	$log->debug("Exiting get_mime_type method ...");
  return "TEXT/PLAIN";
}


function get_part($stream, $msg_number, $mime_type, $structure = false,$part_number    = false) 
{
	 global $log;
        $log->debug("Entering get_part(".$stream.", ".$msg_number.", ".$mime_type.", ".$structure.",".$part_number.") method ...");
 
    if(!$structure) {
    $structure = imap_fetchstructure($stream, $msg_number);
  }
  if($structure) {
    if($mime_type == get_mime_type($structure)) {
      if(!$part_number) {
        $part_number = "1";
      }
      $text = imap_fetchbody($stream, $msg_number, $part_number);
      if($structure->encoding == 3) {
	$log->debug("Exiting get_part method ...");
        return imap_base64($text);
      } else if($structure->encoding == 4) {
	$log->debug("Exiting get_part method ...");
        return imap_qprint($text);
      } else {
	$log->debug("Exiting get_part method ...");
        return $text;
      }
    }
   
    if($structure->type == 1) /* multipart */ {
      while(list($index, $sub_structure) = each($structure->parts)) {
        if($part_number) {
          //$prefix = $part_number . '.';
          $prefix = $part_number ;
        }
        $data = get_part($stream, $msg_number, $mime_type, $sub_structure,$prefix .    ($index + 1));
        if($data) {
		$log->debug("Exiting get_part method ...");
          return $data;
        }
      } // END OF WHILE
    } // END OF MULTIPART
  } // END OF STRUTURE
  $log->debug("Exiting get_part method ...");
  return false;
} // END OF FUNCTION


/*
 // GET TEXT BODY
   $dataTxt = get_part($mbox, $msgno, "TEXT/PLAIN");
   
   // GET HTML BODY
   $dataHtml = get_part($mbox, $msgno, "TEXT/HTML");
   
   if ($dataHtml != "") {
	   $msgBody = $dataHtml;
   	$mailformat = "html";
   } else {
   	$msgBody = ereg_replace("\n","<br>",$dataTxt);
   	$mailformat = "text";
   }
	// To out put the message body to the user simply print $msgBody like this.
   
   if ($mailformat == "text") {
   	echo "<html><head><title>Messagebody</title></head><body    bgcolor=\"white\">$msgBody</body></html>";
   } else {
   	echo $msgBody; // It contains all HTML HEADER tags so we don't have to make them.
   }

*/



 function transformHTML($str) {
  global $log;
        $log->debug("Entering transformHTML(".$str.") method ...");
   if ((strpos($str,"<HTML") < 0) || (strpos($str,"<html")    < 0)) {
  		$makeHeader = "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$app_strings['LBL_CHARSET']."\"></head>\n";
   	if ((strpos($str,"<BODY") < 0) || (strpos($str,"<body")    < 0)) {
   		$makeBody = "\n<body>\n";
   		$str = $makeHeader . $makeBody . $str ."\n</body></html>";
   	} else {
   		$str = $makeHeader . $str ."\n</html>";
   	}
   } else {
   	$str = "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$app_strings['LBL_CHARSET']."\">\n". $str;
   }
        $log->debug("Exiting transformHTML method ...");
   	return $str;
 }
   
 if ($dataHtml != "") {
	$msgBody = transformHTML($dataHtml);
 } else {
   $msgBody = ereg_replace("\n","<br>",$dataTxt);
   $msgBody = preg_replace("/([^\w\/])(www\.[a-z0-9\-]+\.[a-z0-9\-]+)/i","$1http://$2",    $msgBody);
   $msgBody = preg_replace("/([\w]+:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/i","<A    TARGET=\"_blank\" HREF=\"$1\">$1</A>", $msgBody);
   $msgBody = preg_replace("/([\w-?&;#~=\.\/]+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?))/i","<A    HREF=\"mailto:$1\">$1</A>",$msgBody);
 }


?>
