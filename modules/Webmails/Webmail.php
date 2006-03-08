<?php
include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('data/CRMEntity.php');

class Webmail extends CRMEntity {
        var $log;
        var $db;

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


 	function Webmail($mbox,$mailid) {
		$this->db = new PearDatabase();
		$this->mbox=$mbox;
		$this->mailid=$mailid;

		$headers = load_headers($this->mailid,$this->mbox);
		$this->to = $headers["header"]["to"];
		$this->to_name = $headers["header"]["to_name"];

		$this->from = $headers["header"]["from"];
		$this->fromname = $headers["header"]["from_name"];
		$this->fromaddr = $headers["header"]["fromaddr"];

		$this->reply_to = $headers["header"]["reply_to"];
		$this->reply_to_name = $headers["header"]["reply_to_name"];

		$this->cc_list = $headers["header"]["cc_list"];
		$this->cc_list_name = $headers["header"]["cc_list_name"];

		$this->subject = $headers["header"]["subject"];
		$this->date = $headers["header"]["date"];

		$this->relationship = find_relationships($this->db,ltrim(rtrim($this->from)));
        }

	function delete() {
		imap_delete($this->mbox, $this->mailid);
	}

	function loadMail() {
		$this->email = load_mail($this->mailid,$this->mbox);
		$this->inline = $this->email["inline"];
		$this->attachments = $this->email["attachments"];
		$this->body = $this->email["content"]["body"];
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
		return dl_inline($this->mailid,$this->mbox);
	}

	function downloadAttachments() {
		return dl_attachments($this->mailid,$this->mbox);
	}
}
function load_headers($mailid,$mbox) {
	// get the header info
	$mailHeader=Array();
	$header = @imap_headerinfo($mbox, $mailid);
	$tmp = imap_mime_header_decode($header->fromaddress);

	for($p=0;$p<count($header->to);$p++) {
		$mailHeader['to'][] = $header->to[$p]->mailbox.'@'.$header->to[$p]->host;
		$mailHeader['to_name'][] = $header->to[$p]->personal;
	}
	$mailHeader['from'] = $header->from[0]->mailbox.'@'.$header->from[0]->host;	
	$mailHeader['from_name'] = $header->from[0]->personal;
	$mailHeader['fromaddr'] = $header->fromaddress;

	$mailHeader['subject'] = strip_tags($header->subject);
	$mailHeader['date'] = $header->date;

	for($p=0;$p<count($header->reply_to);$p++) {
		$mailHeader['reply_to'][] = $header->reply_to[$p]->mailbox.'@'.$header->reply_to[$p]->host;
		$mailHeader['reply_to_name'][] = $header->reply_to[$p]->personal;
	}
	for($p=0;$p<count($header->cc);$p++) {
		$mailHeader['cc_list'][] = $header->cc[$p]->mailbox.'@'.$header->cc[$p]->host;
		$mailHeader['cc_list_name'][] = $header->cc[$p]->personal;
	}
    	return $ret = Array("header"=>$mailHeader);
}
function find_relationships($db,$from) {

	// leads search
	$sql = "SELECT * from leaddetails left join crmentity on crmentity.crmid=leaddetails.leadid where leaddetails.email = '".$from."' AND crmentity.deleted='0' AND crmentity.presence='0'";
	$res = $db->query($sql,true,"Error: "."<BR>$query");
	$numRows = $db->num_rows($res);
	if($numRows > 0)
		return array('type'=>"Leads",'id'=>$db->query_result($res,0,"leadid"),'name'=>$db->query_result($res,0,"firstname")." ".$db->query_result($res,0,"lastname"));

	// contacts search
	$sql = "SELECT * from contactdetails left join crmentity on crmentity.crmid=contactdetails.contactid where contactdetails.email = '".$from."'  AND crmentity.deleted='0'";
	$res = $db->query($sql,true,"Error: "."<BR>$query");
	$numRows = $db->num_rows($res);
	if($numRows > 0)
		return array('type'=>"Contacts",'id'=>$db->query_result($res,0,"contactid"),'name'=>$db->query_result($res,0,"firstname")." ".$db->query_result($res,0,"lastname"));

	// accounts search
	$sql = "SELECT * from account left join crmentity on crmentity.crmid=account.accountid where account.email1 = '".$from."' OR account.email1='".$from."'  AND crmentity.deleted='0' AND crmentity.presence='0'";
	$res = $db->query($sql,true,"Error: "."<BR>$query");
	$numRows = $db->num_rows($res);
	if($numRows > 0)
		return array('type'=>"Accounts",'id'=>$db->query_result($res,0,"accountid"),'name'=>$db->query_result($res,0,"accountname"));
/*
	// user search
	$sql = "SELECT * from users where users.email1 = '".$from."' OR users.email2='".$from."' AND deleted='0'";
	$res = $db->query($sql,true,"Error: "."<BR>$query");
	$numRows = $db->num_rows($res);
	if($numRows > 0)
		return array('type'=>"User",'id'=>$db->query_result($res,0,"userid"));

	// vendor search
	$sql = "SELECT * from vendor left join crmentity on crmentity.crmid=vendor.vendorid where vendor.email = '".$from."'";
	$res = $db->query($sql,true,"Error: "."<BR>$query");
	$numRows = $db->num_rows($res);
	if($numRows > 0)
		return array('type'=>"Vendor",'id'=>$db->query_result($res,0,"vendorid"));
*/
	return 0;
}

function dl_inline($mailid,$mbox) {
        $struct = imap_fetchstructure($mbox, $mailid);
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
                        $inline[] = array("filename" => $parts[$i]->parameters[0]->value,"filedata"=>imap_fetchbody($mbox, $mailid, $partstring));
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
function dl_attachments($mailid,$mbox) {
        $struct = imap_fetchstructure($mbox, $mailid);
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
                        $attachment[] = array("filename" => $parts[$i]->parameters[0]->value,"filedata"=>imap_fetchbody($mbox, $mailid, $partstring));
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
function load_mail($mailid,$mbox) {

	// parse the message
	$struct = imap_fetchstructure($mbox, $mailid);
       	$parts = $struct->parts;

        $content = array();
        $i = 0;
        if (!$parts) { /* Simple message, only 1 piece */
         $attachment = array(); /* No attachments */
         $bod=imap_body($mbox, $mailid);
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
                        $inline[] = array("filename" => $parts[$i]->parameters[0]->value,"filesize"=>$parts[$i]->bytes);
	     } elseif (strtoupper($parts[$i]->disposition) == "ATTACHMENT") {
                        $attachment[] = array("filename" => $parts[$i]->parameters[0]->value,"filesize"=>$parts[$i]->bytes);

             } elseif (strtoupper($parts[$i]->subtype) == "HTML") {
                        $content['body'] = preg_replace($search,$replace,imap_fetchbody($mbox, $mailid, $partstring));
			$stat="done";
             } elseif (strtoupper($parts[$i]->subtype) == "TEXT" && !$stat == "done") {
                        $content['body'] = nl2br(imap_fetchbody($mbox, $mailid, $partstring));
			$stat="done";
             } elseif (strtoupper($parts[$i]->subtype) == "PLAIN" && !$stat == "done") {
                        $content['body'] = nl2br(imap_fetchbody($mbox, $mailid, $partstring));
			$stat="done";
             } elseif (!$stat == "done") {
                        $content['body'] = nl2br(imap_fetchbody($mbox, $mailid, $partstring));
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
?>
