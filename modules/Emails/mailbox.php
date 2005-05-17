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

   $ServerName = "{localhost/imap:143/notls}INBOX"; // For a IMAP connection    (PORT 143)
   
   $UserName = "user";
   $PassWord = "password";
   
   $mbox = imap_open($ServerName, $UserName,$PassWord) or die("Could not open Mailbox - try again later!");
   
   if ($hdr = imap_check($mbox)) {
	   echo "Num Messages " . $hdr->Nmsgs ."\n\n<br><br>";
   	$msgCount = $hdr->Nmsgs;
   } else {
   	echo "failed";
   }
$MN=$msgCount;
$overview=imap_fetch_overview($mbox,"1:$MN",0);
$mime_type = get_mime_type($overview);
$size=sizeof($overview);
global $app_strings,$current_user;
if(isset($_REQUEST['view']) && $_REQUEST['view']!='')
{
	

	  $msgid = $_REQUEST['view'];
	  $val=$overview[$msgid];
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
 	      		alert("This feature is currently only available for Microsoft Internet Explorer 5.5+ users\n\nWait for an update!");
	   	} else {
	   		check = confirm("Do you want to download the file ?");
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
	echo "<b>From<b>:" . $from ."<br>";
	echo "<b>Date<b>:" .$date ."<br>";
	echo "<b>Subject<b>:" .$subj."<br>";
	echo "<b>Mail body<b>:";
	$content = get_part($mbox,$msg,$mime_type);
	echo nl2br($content);
	
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
			elseif ($att[$k]->parameters[0]->value != "iso-8859-1" &&    $att[$k]->parameters[0]->value != "ISO-8859-1") {
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
        <input type="hidden" name="return_action" value="mailbox">';
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
';
   echo '<input title="'.$app_strings[LBL_ADD_VTIGER_BUTTON_TITLE].'" accessKey="'.$app_strings['LBL_ADD_VTIGER_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'Save\';" type="submit" name="button" value="'.$app_strings['LBL_ADD_VTIGER_BUTTON_LABEL'].'">
	</form>';

}
else
{
	echo "<table border=\"0\" cellspacing=\"0\" width=\"582\">";

	for($i=$size-1;$i>=0;$i--)
	{
	  $val=$overview[$i];
	  $msg=$val->msgno;
	  $from=$val->from;
	  $date=$val->date;
	  $subj=$val->subject;
	  //transformHTML($subj);
	  $seen=$val->seen;
   
	  $from = ereg_replace("\"","",$from);
   
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
   
	  echo "<tr bgcolor=\"$bgColor\"><td colspan=\"2\">$from</td><td colspan=\"2\"><a href=\"index.php?action=mailbox&module=Emails&view=".$i."\">$subj</a></td>
   		 <td class=\"tblContent\" colspan=\"2\">$date</td></tr>\n";
	}

	echo "</table>";
}
imap_close($mbox);





function get_mime_type(&$structure) 
{
  $primary_mime_type = array("TEXT", "MULTIPART","MESSAGE", "APPLICATION", "AUDIO","IMAGE", "VIDEO", "OTHER");
  if($structure->subtype) {
    return $primary_mime_type[(int) $structure->type] . '/' .$structure->subtype;
  }
  return "TEXT/PLAIN";
}


function get_part($stream, $msg_number, $mime_type, $structure = false,$part_number    = false) 
{
 
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
        return imap_base64($text);
      } else if($structure->encoding == 4) {
        return imap_qprint($text);
      } else {
        return $text;
      }
    }
   
    if($structure->type == 1) /* multipart */ {
      while(list($index, $sub_structure) = each($structure->parts)) {
        if($part_number) {
          $prefix = $part_number . '.';
        }
        $data = get_part($stream, $msg_number, $mime_type, $sub_structure,$prefix .    ($index + 1));
        if($data) {
          return $data;
        }
      } // END OF WHILE
    } // END OF MULTIPART
  } // END OF STRUTURE
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
   if ((strpos($str,"<HTML") < 0) || (strpos($str,"<html")    < 0)) {
  		$makeHeader = "<html><head><meta http-equiv=\"Content-Type\"    content=\"text/html; charset=iso-8859-1\"></head>\n";
   	if ((strpos($str,"<BODY") < 0) || (strpos($str,"<body")    < 0)) {
   		$makeBody = "\n<body>\n";
   		$str = $makeHeader . $makeBody . $str ."\n</body></html>";
   	} else {
   		$str = $makeHeader . $str ."\n</html>";
   	}
   } else {
   	$str = "<meta http-equiv=\"Content-Type\" content=\"text/html;    charset=iso-8859-1\">\n". $str;
   }
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
