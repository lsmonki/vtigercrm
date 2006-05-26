<?php
function fullMailList($mbox) {
	$mailHeaders = @imap_headers($mbox);
	$numEmails = sizeof($mailHeaders);
	$mailOverviews = @imap_fetch_overview($mbox, "1:$numEmails", 0);
	$out = array("headers"=>$mailHeaders,"overview"=>$mailOverviews,"count"=>$numEmails);
	return $out;
}
function getInlineAttachments($mailid,$mbox) {
       $struct = imap_fetchstructure($mbox, $mailid);
       $parts = $struct->parts;

        $done="false";
        $i = 0;
        if (!$parts)
		return;
	else {
       	$stack = array(); /* Stack while parsing message */
       	$content = "";    /* Content of message */
       	$attachment = array(); /* Attachments */
        $endwhile = false;
        while (!$endwhile) {
           if (!$parts[$i]) {
             if (count($stack) > 0) {
               $parts = $stack[count($stack)-1]["p"];
               $i    = $stack[count($stack)-1]["i"] + 1;
               array_pop($stack);
             } else {
               $endwhile = true;
             }
        }
           if (!$endwhile) {
             /* Create message part first (example '1.2.3') */
             $partstring = "";
             foreach ($stack as $s) {
               $partstring .= ($s["i"]+1) . ".";
             }
             $partstring .= ($i+1);
       		if (strtoupper($parts[$i]->disposition) == "INLINE" && strtoupper($parts[$i]->subtype) != "PLAIN")
               		$attachment[] = array("filename" => $parts[$i]->parameters[0]->value, "filedata" => imap_fetchbody($mbox, $mailid, $partstring),"ID"=> $parts[$i]->parts[0]);
           }
            if ($parts[$i]->parts) {
              $stack[] = array("p" => $parts, "i" => $i);
              $parts = $parts[$i]->parts;
              $i = 0;
            } else {
              $i++;
            }
          } 
       	} 
	return $attachment;
}

function getAttachmentDetails($mailid,$mbox) {
       $struct = imap_fetchstructure($mbox, $mailid);
       $parts = $struct->parts;

        $done="false";
        $i = 0;
        if (!$parts)
		return;
	else {
      
       	$stack = array(); /* Stack while parsing message */
       	$content = "";    /* Content of message */
       	$attachment = array(); /* Attachments */

        $endwhile = false;

        while (!$endwhile) {
           if (!$parts[$i]) {
             if (count($stack) > 0) {
               $parts = $stack[count($stack)-1]["p"];
               $i    = $stack[count($stack)-1]["i"] + 1;
               array_pop($stack);
             } else {
               $endwhile = true;
             }
        }
        
           if (!$endwhile) {
             /* Create message part first (example '1.2.3') */
             $partstring = "";
             foreach ($stack as $s) {
               $partstring .= ($s["i"]+1) . ".";
             }
             $partstring .= ($i+1);
       		if (strtoupper($parts[$i]->disposition) == "ATTACHMENT")
               		$attachment[] = array("filename" => $parts[$i]->parameters[0]->value,"filesize"=>$parts[$i]->bytes);
           }

           if ($parts[$i]->parts) {
             $stack[] = array("p" => $parts, "i" => $i);
             $parts = $parts[$i]->parts;
             $i = 0;
           } else {
             $i++;
           }
         } /* while */
       } /* complicated message */
	return $attachment;
}

function getAttachments($mailid,$mbox) {
       $struct = imap_fetchstructure($mbox, $mailid);
       $parts = $struct->parts;

        $done="false";
        $i = 0;
        if (!$parts)
		return;
	else {
      
       	$stack = array(); /* Stack while parsing message */
       	$content = "";    /* Content of message */
       	$attachment = array(); /* Attachments */

        $endwhile = false;

        while (!$endwhile) {
           if (!$parts[$i]) {
             if (count($stack) > 0) {
               $parts = $stack[count($stack)-1]["p"];
               $i    = $stack[count($stack)-1]["i"] + 1;
               array_pop($stack);
             } else {
               $endwhile = true;
             }
        }
        
           if (!$endwhile) {
             /* Create message part first (example '1.2.3') */
             $partstring = "";
             foreach ($stack as $s) {
               $partstring .= ($s["i"]+1) . ".";
             }
             $partstring .= ($i+1);
       		if (strtoupper($parts[$i]->disposition) == "ATTACHMENT")
               		$attachment[] = array("filename" => $parts[$i]->parameters[0]->value,"filesize"=>$parts[$i]->bytes, "filedata" => imap_fetchbody($mbox, $mailid, $partstring));
           }

           if ($parts[$i]->parts) {
             $stack[] = array("p" => $parts, "i" => $i);
             $parts = $parts[$i]->parts;
             $i = 0;
           } else {
             $i++;
           }
         } /* while */
       } /* complicated message */
	return $attachment;
}
function getBody($mailid, $mbox) {
       $struct = imap_fetchstructure($mbox, $mailid);
       $parts = $struct->parts;

        $done="false";
        $i = 0;
        if (!$parts) { /* Simple message, only 1 piece */
         $attachment = array(); /* No attachments */
	 $bod=imap_body($mbox, $mailid);
	 if(preg_match("/\<br\>/",$bod))
         	$content = $bod;
	 else
         	$content = nl2br($bod);
	return $content;
	} else { /* Complicated message, multiple parts */
      
       	$stack = array(); /* Stack while parsing message */
       	$content = "";    /* Content of message */
       	$attachment = array(); /* Attachments */

        $endwhile = false;

        while (!$endwhile) {
           if (!$parts[$i]) {
             if (count($stack) > 0) {
               $parts = $stack[count($stack)-1]["p"];
               $i    = $stack[count($stack)-1]["i"] + 1;
               array_pop($stack);
             } else {
               $endwhile = true;
             }
        }
        
	$search = array("/=20=/","/=20/","/=\r\n/","/=3D/","@&(<a|<A);@i","/=0A/i","/=A0/i");
	$replace = array("","","","=","<a target='_blank' ","");
           if (!$endwhile) {
             /* Create message part first (example '1.2.3') */
             $partstring = "";
             foreach ($stack as $s) {
               $partstring .= ($s["i"]+1) . ".";
             }
             $partstring .= ($i+1);
          
	     $done='';
	     if (strtoupper($parts[$i]->subtype) == "HTML") {
               		$content = preg_replace($search,$replace,imap_fetchbody($mbox, $mailid, $partstring));
			return $content;
             } elseif (strtoupper($parts[$i]->subtype) == "TEXT" && $done != "html") {
         		$content = nl2br(imap_fetchbody($mbox, $mailid, $partstring));
		$done="text";
             } elseif (strtoupper($parts[$i]->subtype) == "PLAIN" && $done != "html") {
         		$content = nl2br(imap_fetchbody($mbox, $mailid, $partstring));
		$done="plain";
	     }
           }

           if ($parts[$i]->parts) {
             $stack[] = array("p" => $parts, "i" => $i);
             $parts = $parts[$i]->parts;
             $i = 0;
           } else {
             $i++;
           }
         } /* while */
       } /* complicated message */
	return $content;
}
?>
