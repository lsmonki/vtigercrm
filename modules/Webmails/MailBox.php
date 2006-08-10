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
require_once('include/utils/utils.php');

class MailBox {

	var $mbox;
	var $db;
	var $boxinfo;
	var $readonly='false';
	var $enabled;

	var $login_username;
	var $secretkey;
	var $imapServerAddress;
	var $ssltype;
	var $sslmeth;
	var $box_refresh;
	var $mails_per_page;
	var $mail_protocol;
	var $account_name;
	var $display_name;
	var $mailbox;
	var $mailList;

	function MailBox($mailbox = '') {
		global $current_user;
		$this->db = new PearDatabase();
		$this->db->println("Entering MailBox($mailbox)");

		$this->mailbox = $mailbox;
		$tmp = getMailServerInfo($current_user);

		if($this->db->num_rows($tmp) < 1)
			$this->enabled = 'false';
		else
			$this->enabled = 'true';

		$this->boxinfo = $this->db->fetch_array($tmp);

		$this->login_username= $this->boxinfo["mail_username"]; 
		$this->secretkey=$this->boxinfo["mail_password"]; 
		$this->imapServerAddress=$this->boxinfo["mail_servername"]; 
		$this->mail_protocol=$this->boxinfo["mail_protocol"]; 
		$this->ssltype=$this->boxinfo["ssltype"]; 
		$this->sslmeth=$this->boxinfo["sslmeth"]; 
		$this->box_refresh=$this->boxinfo["box_refresh"];
		$this->mails_per_page=$this->boxinf["mails_per_page"];
		if($this->mails_per_page < 1)
        		$this->mails_per_page=20;

		$this->mail_protocol=$this->boxinfo["mail_protocol"];
		$this->account_name=$this->boxinfo["account_name"];
		$this->display_name=$this->boxinfo["display_name"];
		$this->imapServerAddress=$this->boxinfo["mail_servername"];

		$this->db->println("Setting Mailbox Name");
		if($this->mailbox != "") 
			$this->mailbox=$mailbox;

		$this->db->println("Opening Mailbox");
		if(!$this->mbox && $this->mailbox != "")
			$this->getImapMbox();

		$this->db->println("Loading mail list");
		if($this->mbox)
			$this->mailList = $this->fullMailList();

		$this->db->println("Exiting MailBox($mailbox)");
	}

	function fullMailList() {
		$mailHeaders = @imap_headers($this->mbox);
		$numEmails = sizeof($mailHeaders);
		$mailOverviews = @imap_fetch_overview($this->mbox, "1:$numEmails", 0);
		$out = array("headers"=>$mailHeaders,"overview"=>$mailOverviews,"count"=>$numEmails);
		return $out;
	}

	function isBase64($iVal){
		$_tmp=preg_replace("/[^A-Z0-9\+\/\=]/i","",$iVal);
		return (strlen($_tmp) % 4 == 0 ) ? "y" : "n";
	}

	function getImapMbox() {
		$this->db->println("Entering getImapMbox()");
		$mods = parsePHPModules();
		$this->db->println("Parsing PHP Modules");
	 	 
		// first we will try a regular old IMAP connection: 
		if($this->ssltype == "") {$this->ssltype = "notls";} 
		if($this->sslmeth == "") {$this->sslmeth = "novalidate-cert";} 

		$this->db->println("Building connection string");
		if($this->readonly == "true") {
	    		if($mods["imap"]["SSL Support"] == "enabled")
				$connectString = "{".$this->imapServerAddress."/".$this->mail_protocol."/".$this->ssltype."/".$this->sslmeth."/readonly}".$this->mailbox;
	    		else
				$connectString = "{".$this->imapServerAddress."/".$this->mail_protocol."/readonly}".$this->mailbox;
		} else {
	    		if($mods["imap"]["SSL Support"] == "enabled")
				$connectString = "{".$this->imapServerAddress."/".$this->mail_protocol."/".$this->ssltype."/".$this->sslmeth."}".$this->mailbox;
	    		else
				$connectString = "{".$this->imapServerAddress."/".$this->mail_protocol."}".$this->mailbox;
		}
		$this->db->println("Done Building Connection String.. Connecting to box");
		//$this->mbox = @imap_open($connectString, $this->login_username, $this->secretkey); 
		$this->mbox = @imap_open($connectString, $this->login_username, $this->secretkey,"OP_HALFOPEN"); 
		$this->db->println("Done connecting to box");

		// next we'll try to make a port specific connection to see if that helps.
		// this may need to be updated to remove SSL/TLS since the c-client libs
		// are not linked correctly to SSL in most windows installs.
		if(!$this->mbox) {
			$this->db->println("No regular Mailbox, building from port numbers");
	 		if($this->mail_protocol == 'pop3') {
				if($this->readonly == "true")
	 	        		$connectString = "{".$this->imapServerAddress."/".$this->mail_protocol.":110/readonly}".$this->mailbox;
				else
	 	        		$connectString = "{".$this->imapServerAddress."/".$this->mail_protocol.":110/}".$this->mailbox;
	 		} else { 
				if($this->readonly == "true") { 
	    		    	if($mods["imap"]["SSL Support"] == "enabled")
	 	        		$connectString = "{".$this->imapServerAddress.":143/".$this->mail_protocol."/".$this->ssltype."/".$this->sslmeth."/readonly}".$this->mailbox; 
			    	else
	 	        		$connectString = "{".$this->imapServerAddress.":143/".$this->mail_protocol."/}".$this->mailbox; 
				} else {
	    		    		if($mods["imap"]["SSL Support"] == "enabled")
	 	        			$connectString = "{".$this->imapServerAddress.":143/".$this->mail_protocol."/".$ssltype."/".$sslmeth."}".$mailbox;
			    		else
	 	        			$connectString = "{".$imapServerAddress.":143/".$mail_protocol."}".$mailbox;
				}
	 		} 
			$this->db->println("Opening MailBox");
	 		$this->mbox = imap_open($connectString, $login_username, $secretkey) or die("Connection to server failed ".imap_last_error()); 
		} 
	}
} // END CLASS


function parsePHPModules() {
 ob_start();
 phpinfo(INFO_MODULES);
 $s = ob_get_contents();
 ob_end_clean();

 $s = strip_tags($s,'<h2><th><td>');
 $s = preg_replace('/<th[^>]*>([^<]+)<\/th>/',"<info>\\1</info>",$s);
 $s = preg_replace('/<td[^>]*>([^<]+)<\/td>/',"<info>\\1</info>",$s);
 $vTmp = preg_split('/(<h2>[^<]+<\/h2>)/',$s,-1,PREG_SPLIT_DELIM_CAPTURE);
 $vModules = array();
 for ($i=1;$i<count($vTmp);$i++) {
  if (preg_match('/<h2>([^<]+)<\/h2>/',$vTmp[$i],$vMat)) {
   $vName = trim($vMat[1]);
   $vTmp2 = explode("\n",$vTmp[$i+1]);
   foreach ($vTmp2 AS $vOne) {
   $vPat = '<info>([^<]+)<\/info>';
   $vPat3 = "/$vPat\s*$vPat\s*$vPat/";
   $vPat2 = "/$vPat\s*$vPat/";
   if (preg_match($vPat3,$vOne,$vMat)) { // 3cols
     $vModules[$vName][trim($vMat[1])] = array(trim($vMat[2]),trim($vMat[3]));
   } elseif (preg_match($vPat2,$vOne,$vMat)) { // 2cols
     $vModules[$vName][trim($vMat[1])] = trim($vMat[2]);
   }
   }
  }
 }
 return $vModules;
}
?>
