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

function fullMailList($mbox) {
	$mailHeaders = @imap_headers($mbox);
	$numEmails = sizeof($mailHeaders);
	$mailOverviews = @imap_fetch_overview($mbox, "1:$numEmails", 0);
	$out = array("headers"=>$mailHeaders,"overview"=>$mailOverviews,"count"=>$numEmails);
	return $out;
}
function isBase64($iVal){
	$_tmp=preg_replace("/[^A-Z0-9\+\/\=]/i","",$iVal);
	return (strlen($_tmp) % 4 == 0 ) ? "y" : "n";
}
function getImapMbox($mailbox,$temprow,$readonly='') {
	global $mbox; 
	$login_username= $temprow["mail_username"]; 
	$secretkey=$temprow["mail_password"]; 
	$imapServerAddress=$temprow["mail_servername"]; 
	$mail_protocol=$temprow["mail_protocol"]; 
	$ssltype=$temprow["ssltype"]; 
	$sslmeth=$temprow["sslmeth"]; 
	 	 
	 	 
	// first we will try a regular old IMAP connection: 
	if($ssltype == "") {$ssltype = "notls";} 
	if($sslmeth == "") {$sslmeth = "novalidate-cert";} 
	if($readonly == "true")
		$mbox = @imap_open("{".$imapServerAddress."/".$mail_protocol."/".$ssltype."/".$sslmeth."/readonly}".$mailbox, $login_username, $secretkey); 
	else
		$mbox = @imap_open("{".$imapServerAddress."/".$mail_protocol."/".$ssltype."/".$sslmeth."}".$mailbox, $login_username, $secretkey); 

	// next we'll try to make a port specific connection to see if that helps.
	// this may need to be updated to remove SSL/TLS since the c-client libs
	// are not linked correctly to SSL in most windows installs.
	if(!$mbox) {
	 	if($mail_protocol == 'pop3') {
			if($readonly == "true")
	 	        	$connectString = "{".$imapServerAddress."/".$mail_protocol.":110/".$ssltype."/readonly}".$mailbox;
			else
	 	        	$connectString = "{".$imapServerAddress."/".$mail_protocol.":110/".$ssltype."}".$mailbox;
	 	} else { 
			if($readonly == "true")
	 	        	$connectString = "{".$imapServerAddress.":143/".$mail_protocol."/".$ssltype."/readonly}".$mailbox; 
			else
	 	        	$connectString = "{".$imapServerAddress.":143/".$mail_protocol."/".$ssltype."}".$mailbox; 
	 	} 
	 	$mbox = imap_open($connectString, $login_username, $secretkey) or die("Connection to server failed ".imap_last_error()); 
	} 
	return $mbox; 
}
function getAttachments($mailid,$mbox) {
       $struct = imap_fetchstructure($mbox, $mailid);
       $parts = $struct->parts;

        $done="false";
        $i = 0;
        if (!$parts)
		return false; // simple message
	else  {
        $stack = array();
        $inline = array();

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

             $partstring = "";
             foreach ($stack as $s) {
               $partstring .= ($s["i"]+1) . ".";
             }
             $partstring .= ($i+1);

             if (strtoupper($parts[$i]->disposition) == "INLINE" || strtoupper($parts[$i]->disposition) == "ATTACHMENT")
			return true;
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
        return false;
}
?>
