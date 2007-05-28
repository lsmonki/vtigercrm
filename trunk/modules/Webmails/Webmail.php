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


include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('data/CRMEntity.php');

class Webmail extends CRMEntity {
        var $log;
        var $db;

	var $headers;
  	var $mailid;
        var $to = array();
        var $to_name = array();
        var $from;
        var $fromname;
        var $fromaddr;
	var $reply_to = array();
	var $reply_to_name = array();
	var $cc_list = array();
	var $cc_list_name = array();
	var $subject;
	var $date;
	var $body_type;
	var $body;
	var $attachments = array();
	var $inline = array();
	var $mbox;
	var $email;
	var $relationship = array();
	var $has_attachments = false;


 	function Webmail($mbox,$mailid) {

		$this->db = new PearDatabase();
		$this->db->println("Entering Webmail($mbox,$mailid)");
		$this->log = &LoggerManager::getLogger('WEBMAILS');
		$this->mbox=$mbox;
		$this->mailid=$mailid;

		$this->headers = $this->load_headers();

		$this->to = $this->headers["theader"]["to"];
		$this->to_name = $this->headers["theader"]["to_name"];
		$this->db->println("Webmail TO: $this->to");

		$this->from = $this->headers["theader"]["from"];
		$this->fromname = $this->headers["theader"]["from_name"];
		$this->fromaddr = $this->headers["theader"]["fromaddr"];

		$this->reply_to = $this->headers["theader"]["reply_to"];
		$this->reply_to_name = $this->headers["theader"]["reply_to_name"];

		$this->cc_list = $this->headers["cc_list"];
		$this->cc_list_name = $this->headers["cc_list_name"];

		$this->subject = $this->headers["theader"]["subject"];
		$this->date = $this->headers["theader"]["date"];

		$this->has_attachments = $this->get_attachments();
		$this->db->println("Exiting Webmail($mbox,$mailid)");
        }

	function delete() {
		imap_delete($this->mbox, $this->mailid);
	}

	function loadMail() {
		$this->email = $this->load_mail();
		$this->inline = $this->email["inline"];
		$this->attachments = $this->email["attachments"];
		$this->body = $this->email["content"]["body"];
		$this->relationship = $this->find_relationships();
	}

	function replyBody() {
		$tmp = "<br><br><p style='font-weight:bold'>In reply to the message sent by ".$this->reply_name." on ".$this->date."</p>";
		$tmp .= "<blockquote style='border-left:1px solid blue;padding-left:5px'>".$this->body."</blockquote>";
		return $tmp;
	}

	function unDeleteMsg() {
		imap_undelete($this->mbox, $this->mailid);
	}

	function setFlag() {
		$status=imap_setflag_full($this->mbox,$this->mailid,"\\Flagged");
	}

	function delFlag() {
		$status=imap_clearflag_full($this->mbox,$this->mailid,"\\Flagged");
	}

	function getBodyType() {
		return $this->body_type;
	}

	function downloadInlineAttachments() {
		return $this->dl_inline();
	}

	function downloadAttachments() {
		return $this->dl_attachments($this->mailid,$this->mbox);
	}

    function load_headers() {
	// get the header info
	$mailHeader=Array();
	$theader = @imap_headerinfo($this->mbox, $this->mailid);
	$tmp = imap_mime_header_decode($theader->fromaddress);

	for($p=0;$p<count($theader->to);$p++) {
		$mailHeader['to'][] = $theader->to[$p]->mailbox.'@'.$theader->to[$p]->host;
		$mailHeader['to_name'][] = $theader->to[$p]->personal;
	}
	$mailHeader['from'] = $theader->from[0]->mailbox.'@'.$theader->from[0]->host;	
	$mailHeader['from_name'] = $theader->from[0]->personal;
	$mailHeader['fromaddr'] = $theader->fromaddress;

	$mailHeader['subject'] = strip_tags($theader->subject);
	$mailHeader['date'] = $theader->date;

	for($p=0;$p<count($theader->reply_to);$p++) {
		$mailHeader['reply_to'][] = $theader->reply_to[$p]->mailbox.'@'.$theader->reply_to[$p]->host;
		$mailHeader['reply_to_name'][] = $theader->reply_to[$p]->personal;
	}
	for($p=0;$p<count($theader->cc);$p++) {
		$mailHeader['cc_list'][] = $theader->cc[$p]->mailbox.'@'.$theader->cc[$p]->host;
		$mailHeader['cc_list_name'][] = $theader->cc[$p]->personal;
	}
    	return $ret = Array("theader"=>$mailHeader);
    }

    private function get_attachments() {
       $struct = imap_fetchstructure($this->mbox, $this->mailid);
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

    private function find_relationships() {
	// leads search
	$sql = "SELECT * from vtiger_leaddetails left join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_leaddetails.leadid where vtiger_leaddetails.email = '".trim($this->from)."' AND vtiger_crmentity.deleted='0'";
	$res = $this->db->query($sql,true,"Error: "."<BR>$query");
	$numRows = $this->db->num_rows($res);
	if($numRows > 0)
		return array('type'=>"Leads",'id'=>$this->db->query_result($res,0,"leadid"),'name'=>$this->db->query_result($res,0,"firstname")." ".$this->db->query_result($res,0,"lastname"));

	// contacts search
	$sql = "SELECT * from vtiger_contactdetails left join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_contactdetails.contactid where vtiger_contactdetails.email = '".trim($this->from)."'  AND vtiger_crmentity.deleted='0'";
	$res = $this->db->query($sql,true,"Error: "."<BR>$query");
	$numRows = $this->db->num_rows($res);
	if($numRows > 0)
		return array('type'=>"Contacts",'id'=>$this->db->query_result($res,0,"contactid"),'name'=>$this->db->query_result($res,0,"firstname")." ".$this->db->query_result($res,0,"lastname"));

	// vtiger_accounts search
	$sql = "SELECT * from vtiger_account left join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_account.accountid where vtiger_account.email1 = '".trim($this->from)."' OR vtiger_account.email1='".trim($this->from)."'  AND vtiger_crmentity.deleted='0'";
	$res = $this->db->query($sql,true,"Error: "."<BR>$query");
	$numRows = $this->db->num_rows($res);
	if($numRows > 0)
		return array('type'=>"Accounts",'id'=>$this->db->query_result($res,0,"accountid"),'name'=>$this->db->query_result($res,0,"accountname"));

	return 0;
    }

    private function dl_inline() {
        $struct = imap_fetchstructure($this->mbox, $this->mailid);
        $parts = $struct->parts;

        $i = 0;
        if (!$parts) 
		return;
        else {

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

             if (strtoupper($parts[$i]->disposition) == "INLINE")
                        $inline[] = array("filename" => $parts[$i]->dparameters[0]->value,"filedata"=>imap_fetchbody($this->mbox, $this->mailid, $partstring),"subtype"=>$parts[$i]->subtype,"filesize"=>$parts[$i]->bytes);
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
	return $inline;
    }

    private function dl_attachments() {
        $struct = imap_fetchstructure($this->mbox, $this->mailid);
        $parts = $struct->parts;

        $content = array();
        $i = 0;
        if (!$parts)
		return;
        else {

        $stack = array();
        $attachment = array();

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

             if (strtoupper($parts[$i]->disposition) == "ATTACHMENT")
                        $attachment[] = array("filename" => $parts[$i]->dparameters[0]->value,"filedata"=>imap_fetchbody($this->mbox, $this->mailid, $partstring),"subtype"=>$parts[$i]->subtype,"filesize"=>$parts[$i]->bytes);
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
    private function load_mail() {
	// parse the message
	$struct = imap_fetchstructure($this->mbox, $this->mailid);
       	$parts = $struct->parts;

        $content = array();
        $i = 0;
        if (!$parts) { /* Simple message, only 1 piece */
         $attachment = array(); /* No vtiger_attachments */
         $bod=imap_body($this->mbox, $this->mailid);
         if(preg_match("/\<br\>/",$bod))
                	$content['body'] = $bod;
         else 
                	$content['body'] = nl2br($bod);
        } else {

        $stack = array(); 
        $attachment = array();

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

             $partstring = "";
             foreach ($stack as $s) {
               $partstring .= ($s["i"]+1) . ".";
             }
             $partstring .= ($i+1);

             $type='';
	     if (strtoupper($parts[$i]->disposition) == "INLINE" && strtoupper($parts[$i]->subtype) != "PLAIN") {
                        $inline[] = array("filename" => $parts[$i]->dparameters[0]->value,"subtype"=>$parts[$i]->subtype,"filesize"=>$parts[$i]->bytes);
	     } elseif (strtoupper($parts[$i]->disposition) == "ATTACHMENT") {
                        $attachment[] = array("filename" => $parts[$i]->dparameters[0]->value,"subtype"=>$parts[$i]->subtype,"filesize"=>$parts[$i]->bytes);

             } elseif (strtoupper($parts[$i]->subtype) == "HTML") {
                        $content['body'] = preg_replace($search,$replace,imap_fetchbody($this->mbox, $this->mailid, $partstring));
			$stat="done";
             } elseif (strtoupper($parts[$i]->subtype) == "TEXT" && !$stat == "done") {
                        $content['body'] = nl2br(imap_fetchbody($this->mbox, $this->mailid, $partstring));
			$stat="done";
             } elseif (strtoupper($parts[$i]->subtype) == "PLAIN" && !$stat == "done") {
                        $content['body'] = nl2br(imap_fetchbody($this->mbox, $this->mailid, $partstring));
			$stat="done";
             } elseif (!$stat == "done") {
                        $content['body'] = nl2br(imap_fetchbody($this->mbox, $this->mailid, $partstring));
             }
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
    	if($struct->encoding==3)
		$content['body'] = base64_decode($content['body']);
    	if($struct->encoding==4)
		$content['body'] = quoted_printable_decode($content['body']);

    	$ret = Array("content" => $content,"attachments"=>$attachment,"inline"=>$inline);
    	return $ret;
    }
}
?>
